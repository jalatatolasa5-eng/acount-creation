<?php
require 'inc.php';
require_login();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$a  = get_account($db, $id);
if (!$a) {
    http_response_code(404);
    page_top('Not found');
    echo '<p class="empty">Account not found. <a href="accounts.php">Back to accounts</a></p>';
    page_bottom();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $act = $_POST['action'] ?? '';
    $amt = is_numeric($_POST['amount'] ?? '') ? (float)$_POST['amount'] : 0.0;
    if      ($act === 'deposit'  && role() === 'officer')    $r = deposit($db, $id, $amt, me());
    elseif  ($act === 'withdraw' && role() === 'officer')    $r = withdraw($db, $id, $amt, me());
    elseif  ($act === 'passbook' && role() === 'officer')    $r = replace_passbook($db, $id, $_POST['kind'] ?? '', $_POST['method'] ?? '', me());
    elseif  ($act === 'close'    && role() === 'supervisor') $r = close_account($db, $id, $_POST['reason'] ?? '', me());
    else    $r = [false, 'That action is not available for your role or this account.'];
    flash($r[1], $r[0] ? 'ok' : 'err');
    redirect('account.php?id=' . $id);
}

$tx = $db->prepare("SELECT * FROM transactions WHERE account_id=? ORDER BY id");
$tx->execute([$id]);
$tx     = $tx->fetchAll(PDO::FETCH_ASSOC);
$active = $a['status'] === 'Active';

$status_cls = ['Active'=>'t-ok','Pending'=>'t-warn','Closed'=>'t-mute','Rejected'=>'t-bad'];
$cls = $status_cls[$a['status']] ?? 't-mute';

page_top('Account ' . ($a['account_no'] ?: $a['cif']));
?>
<!-- Page heading -->
<div class="page-heading">
  <div>
    <h1><?= e($a['first_name'] . ' ' . $a['last_name']) ?></h1>
    <p class="sub">
      <?= $a['account_no'] ? 'Account ' . e($a['account_no']) . ' &middot; ' : '' ?>
      <?= e($a['cif']) ?> &middot; <?= e($a['type']) ?>
    </p>
  </div>
  <span class="tag <?= $cls ?>" style="font-size:13px;padding:5px 14px;"><?= e($a['status']) ?></span>
</div>

<!-- Status banners -->
<?php if ($a['status'] === 'Pending'): ?>
  <div class="banner"><?= icon('info') ?> <div>This application is <strong>waiting for supervisor approval</strong>. No account number has been issued yet.</div></div>
<?php endif; ?>
<?php if ($a['status'] === 'Rejected'): ?>
  <div class="banner"><?= icon('warn') ?> <div><strong>Rejected:</strong> <?= e($a['reject_reason']) ?></div></div>
<?php endif; ?>
<?php if ($a['funding_status'] === 'Overdue'): ?>
  <div class="banner"><?= icon('warn') ?> <div><strong>Funding overdue.</strong> The balance was not ETB 100 or more by <?= e($a['funding_deadline']) ?>. Flagged for closure review.</div></div>
<?php elseif ($a['funding_status'] === 'Awaiting funding' && $active): ?>
  <div class="banner"><?= icon('warn') ?> <div>Zero-balance opening. Deposit enough to reach ETB 100 by <strong><?= e($a['funding_deadline']) ?></strong>.</div></div>
<?php endif; ?>

<!-- Account detail card -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('accounts') ?> Account Details</h2>
    <button class="btn alt sm noprint" onclick="window.print()"><?= icon('guide') ?> Print Statement</button>
  </div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 28px;">
    <dl class="info-grid">
      <dt>Account number</dt><dd><?= $a['account_no'] ? '<strong>' . e($a['account_no']) . '</strong>' : '<em>Not issued</em>' ?></dd>
      <dt>Status</dt><dd>
        <span class="tag <?= $cls ?>"><?= e($a['status']) ?></span>
        <?= $a['closed_at'] ? ' &mdash; ' . e($a['closed_at']) . ' (' . e($a['close_reason']) . ')' : '' ?>
      </dd>
      <dt>Account type</dt><dd><?= e($a['type']) ?></dd>
      <dt>Annual rate</dt><dd><?= e($a['rate']) ?>%</dd>
      <dt>Opened</dt><dd><?= e($a['opened_at'] ?: 'Not yet') ?></dd>
    </dl>
    <dl class="info-grid">
      <dt>Balance</dt><dd><strong style="font-size:18px;color:var(--brand-dk);">ETB <?= birr($a['balance']) ?></strong></dd>
      <dt>Customer ID</dt><dd><?= e($a['cif']) ?></dd>
      <dt>ID document</dt><dd><?= e($a['id_type']) ?> &mdash; <?= e($a['id_number']) ?></dd>
      <dt>Phone</dt><dd><?= e($a['phone']) ?></dd>
      <dt>Passbook</dt><dd>First passbook free<?= $a['passbook_replacements'] ? '; replaced ' . (int)$a['passbook_replacements'] . ' time(s)' : '' ?></dd>
    </dl>
  </div>
</div>

<!-- Teller actions (officer + active only) -->
<?php if ($active && role() === 'officer'): ?>
<div class="card noprint">
  <div class="card-header">
    <h2><?= icon('plus') ?> Teller Actions</h2>
  </div>
  <div class="teller-grid">

    <!-- Deposit -->
    <div class="teller-box">
      <h3>Deposit</h3>
      <form method="post" class="acts">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="deposit">
        <input id="dep_amt" type="number" name="amount" min="0.01" step="0.01"
               placeholder="Amount (ETB)" aria-label="Deposit amount" required style="flex:1;">
        <button class="btn ok" type="submit"><?= icon('plus') ?> Deposit</button>
      </form>
      <p class="teller-fee-note"><?= icon('info') ?> No fee on deposits.</p>
    </div>

    <!-- Withdrawal -->
    <div class="teller-box">
      <h3>Withdrawal</h3>
      <form method="post" class="acts">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="withdraw">
        <input id="wdr_amt" type="number" name="amount" min="0.01" step="0.01"
               placeholder="Amount (ETB)" aria-label="Withdrawal amount" required style="flex:1;">
        <button class="btn" type="submit"><?= icon('audit') ?> Withdraw</button>
      </form>
      <div class="money-summary" id="wdr_summary" style="display:none;">
        <dl>
          <dt>Amount requested</dt><dd id="ws_amt">—</dd>
          <dt>Service fee</dt><dd id="ws_fee">—</dd>
          <dt>Total deducted</dt><dd id="ws_total">—</dd>
          <dt>Balance after</dt><dd id="ws_after">—</dd>
        </dl>
      </div>
      <p class="teller-fee-note"><?= icon('info') ?> Free up to ETB 500 &middot; ETB 12 fee for ETB 501–75,000 &middot; Max ETB 75,000 &middot; Minimum balance ETB 100 must be kept.</p>
    </div>

    <!-- Passbook -->
    <div class="teller-box" style="grid-column:1/-1;">
      <h3>Passbook Replacement</h3>
      <form method="post" class="acts">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="passbook">
        <select name="kind" aria-label="Passbook reason" style="flex:1;min-width:160px;">
          <option value="Lost">Lost &mdash; ETB 115.00 incl. VAT</option>
          <option value="Damaged">Damaged &mdash; ETB 57.50 incl. VAT</option>
        </select>
        <select name="method" aria-label="Fee payment method" style="flex:1;min-width:140px;">
          <option value="Cash">Pay in cash</option>
          <option value="Account">Debit from account</option>
        </select>
        <button class="btn alt" type="submit"><?= icon('guide') ?> Replace Passbook</button>
      </form>
      <p class="teller-fee-note"><?= icon('info') ?> Debiting from account is refused if it would drop the balance below ETB 100.</p>
    </div>

  </div>
</div>
<?php endif; ?>

<!-- Close account (supervisor + active only) -->
<?php if ($active && role() === 'supervisor'): ?>
<div class="card noprint">
  <div class="card-header">
    <h2 style="color:var(--bad);"><?= icon('warn') ?> Close Account</h2>
  </div>
  <p style="margin-bottom:12px;font-size:13.5px;">
    <?= is_early_closure($a['opened_at'], today($db))
        ? 'Opened <strong>less than six months ago</strong> — early closure is free.'
        : 'Open for six months or more — no closing charge is published for this case.' ?>
  </p>
  <p class="note" style="margin-bottom:12px;">Closing pays out the full balance to the customer and permanently blocks further transactions.</p>
  <form method="post" class="acts" onsubmit="return confirm('Close this account and pay out the balance to the customer?')">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="action" value="close">
    <input name="reason" placeholder="Reason for closing" aria-label="Reason for closing" required style="flex:1;max-width:320px;">
    <button class="btn bad" type="submit"><?= icon('warn') ?> Close and Pay Out</button>
  </form>
</div>
<?php endif; ?>

<!-- Transaction statement -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('audit') ?> Account Statement</h2>
  </div>
  <?php if (!$tx): ?>
    <p class="empty">No transactions yet.</p>
  <?php else: ?>
    <div class="scroll">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Note</th>
            <th class="num">Amount (ETB)</th>
            <th class="num">Balance (ETB)</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($tx as $t):
          $is_cr = $t['amount'] >= 0;
        ?>
          <tr>
            <td><?= e($t['tx_date']) ?></td>
            <td><?= e($t['kind']) ?></td>
            <td><?= e($t['note']) ?></td>
            <td class="num <?= $is_cr ? 'credit' : 'debit' ?>">
              <?= $is_cr ? '+' : '' ?><?= birr($t['amount']) ?>
            </td>
            <td class="num"><?= birr($t['balance_after']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<script>
(function(){
  var wAmt   = document.getElementById('wdr_amt');
  var wBox   = document.getElementById('wdr_summary');
  var wAmt_  = document.getElementById('ws_amt');
  var wFee_  = document.getElementById('ws_fee');
  var wTot_  = document.getElementById('ws_total');
  var wAft_  = document.getElementById('ws_after');
  var bal    = <?= (float)$a['balance'] ?>;
  var MIN_BAL = 100;
  var FEE    = 12;
  var FREE_UP = 500;
  var MAX_WDR = 75000;

  function fmt(n){ return 'ETB ' + n.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }

  function update(){
    var v = parseFloat(wAmt.value);
    if (isNaN(v) || v <= 0){ wBox.style.display='none'; return; }
    var fee = (v > FREE_UP && v <= MAX_WDR) ? FEE : 0;
    var tot = v + fee;
    var after = bal - tot;
    wBox.style.display = 'block';
    wAmt_.textContent  = fmt(v);
    wFee_.textContent  = fee > 0 ? fmt(fee) : 'Free';
    wTot_.textContent  = fmt(tot);
    wAft_.textContent  = fmt(after);
    wAft_.style.color  = after < MIN_BAL ? 'var(--bad)' : 'var(--ok)';
  }

  if(wAmt) { wAmt.oninput = update; }
})();
</script>
<?php page_bottom(); ?>
