<?php
require_once __DIR__ . '/../src/inc.php';
require_login();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$a  = get_account($db,$id);
if (!$a) {
    http_response_code(404); page_top('Not found');
    echo '<p class="empty">Account not found. <a href="/public/accounts.php">Back to accounts</a></p>';
    page_bottom(); exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    check_csrf();
    $act = $_POST['action']??'';
    $amt = is_numeric($_POST['amount']??'') ? (float)$_POST['amount'] : 0.0;
    if      ($act==='deposit'  && role()==='officer')    $r = deposit($db,$id,$amt,me());
    elseif  ($act==='withdraw' && role()==='officer')    $r = withdraw($db,$id,$amt,me());
    elseif  ($act==='passbook' && role()==='officer')    $r = replace_passbook($db,$id,$_POST['kind']??'',$_POST['method']??'',me());
    elseif  ($act==='close'    && role()==='supervisor') $r = close_account($db,$id,$_POST['reason']??'',me());
    else    $r = [false,'That action is not available for your role or this account.'];
    flash($r[1],$r[0]?'ok':'err');
    redirect('/public/account.php?id='.$id);
}

$tx     = $db->prepare("SELECT * FROM transactions WHERE account_id=? ORDER BY id");
$tx->execute([$id]);
$tx     = $tx->fetchAll();
$active = $a['status']==='Active';
$status_cls = ['Active'=>'t-ok','Pending'=>'t-brand','Closed'=>'t-mute','Rejected'=>'t-bad'];
$cls = $status_cls[$a['status']] ?? 't-mute';

page_top('Account '.($a['account_no']?:$a['cif']));
?>
<div class="page-heading">
  <div>
    <h1><?= e($a['first_name'].' '.$a['last_name']) ?></h1>
    <p class="sub"><?= $a['account_no']?'Account '.e($a['account_no']).' &middot; ':'' ?><?= e($a['cif']) ?> &middot; <?= e($a['type']) ?></p>
  </div>
  <div class="page-heading-actions">
    <span class="tag <?= $cls ?>" style="font-size:12px;padding:5px 12px;"><?= e($a['status']) ?></span>
    <button class="btn alt sm noprint" onclick="window.print()"><?= svg_icon('print') ?> Print</button>
  </div>
</div>

<?php if($a['status']==='Pending'): ?>
  <div class="banner info"><?= svg_icon('info') ?><div>Waiting for supervisor approval. No account number issued yet.</div></div>
<?php endif; ?>
<?php if($a['status']==='Rejected'): ?>
  <div class="banner bad"><?= svg_icon('warn') ?><div><strong>Rejected:</strong> <?= e($a['reject_reason']) ?></div></div>
<?php endif; ?>
<?php if($a['funding_status']==='Overdue'): ?>
  <div class="banner bad"><?= svg_icon('warn') ?><div><strong>Funding overdue.</strong> Balance was not ETB 100 by <?= e($a['funding_deadline']) ?>.</div></div>
<?php elseif($a['funding_status']==='Awaiting funding'&&$active): ?>
  <div class="banner"><?= svg_icon('warn') ?><div>Deposit enough to reach ETB 100 by <strong><?= e($a['funding_deadline']) ?></strong>.</div></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 280px;gap:16px;margin-bottom:16px;">
  <div class="card" style="margin-bottom:0;">
    <div class="card-header"><h2><?= svg_icon('accounts') ?> Account Details</h2></div>
    <dl class="info-grid">
      <dt>Account No.</dt><dd><?= $a['account_no']?'<strong style="font-family:monospace;">'.e($a['account_no']).'</strong>':'<em style="color:var(--muted);">Not issued</em>' ?></dd>
      <dt>Status</dt><dd><span class="tag <?= $cls ?>"><?= e($a['status']) ?></span><?= $a['closed_at']?' &mdash; '.e($a['closed_at']).' ('.e($a['close_reason']).')':'' ?></dd>
      <dt>Account Type</dt><dd><?= e($a['type']) ?></dd>
      <dt>Annual Rate</dt><dd><?= e($a['rate']) ?>%</dd>
      <dt>Opened</dt><dd><?= e($a['opened_at']?:'Not yet') ?></dd>
      <dt>Customer ID</dt><dd><?= e($a['cif']) ?></dd>
      <dt>ID Document</dt><dd><?= e($a['id_type']) ?> — <?= e($a['id_number']) ?></dd>
      <dt>Phone</dt><dd><?= e($a['phone']) ?></dd>
      <dt>Passbook</dt><dd>First free<?= $a['passbook_replacements']?'; replaced '.(int)$a['passbook_replacements'].'×':'' ?></dd>
    </dl>
  </div>
  <div style="display:flex;flex-direction:column;gap:12px;">
    <div class="card" style="margin-bottom:0;text-align:center;border-top:3px solid var(--brand);">
      <p style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Current Balance</p>
      <p style="font-size:28px;font-weight:800;color:var(--brand-dk);font-variant-numeric:tabular-nums;">ETB <?= birr($a['balance']) ?></p>
    </div>
  </div>
</div>

<?php if($active && role()==='officer'): ?>
<div class="card noprint">
  <div class="card-header"><h2><?= svg_icon('plus') ?> Teller Actions</h2></div>
  <div class="teller-grid">
    <div class="teller-box">
      <h3>Deposit</h3>
      <form method="post" class="acts">
        <?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="deposit">
        <input type="number" name="amount" min="0.01" step="0.01" placeholder="Amount (ETB)" required style="flex:1;">
        <button class="btn ok"><?= svg_icon('plus') ?> Deposit</button>
      </form>
      <p class="note" style="margin-top:6px;">No fee on deposits.</p>
    </div>
    <div class="teller-box">
      <h3>Withdrawal</h3>
      <form method="post" class="acts">
        <?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="withdraw">
        <input id="wdr_amt" type="number" name="amount" min="0.01" step="0.01" placeholder="Amount (ETB)" required style="flex:1;">
        <button class="btn"><?= svg_icon('audit') ?> Withdraw</button>
      </form>
      <div class="money-summary" id="wdr_summary" style="display:none;">
        <dl>
          <dt>Amount</dt><dd id="ws_amt">—</dd>
          <dt>Service fee</dt><dd id="ws_fee">—</dd>
          <dt>Total deducted</dt><dd id="ws_total">—</dd>
          <dt>Balance after</dt><dd id="ws_after">—</dd>
        </dl>
      </div>
      <p class="note" style="margin-top:6px;">Free ≤ ETB 500 &middot; ETB 12 for ETB 501–75,000 &middot; Min balance ETB 100.</p>
    </div>
    <div class="teller-box" style="grid-column:1/-1;">
      <h3>Passbook Replacement</h3>
      <form method="post" class="acts">
        <?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="passbook">
        <select name="kind" style="flex:1;min-width:150px;">
          <option value="Lost">Lost — ETB 115.00 incl. VAT</option>
          <option value="Damaged">Damaged — ETB 57.50 incl. VAT</option>
        </select>
        <select name="method" style="flex:1;min-width:130px;">
          <option value="Cash">Pay in cash</option>
          <option value="Account">Debit from account</option>
        </select>
        <button class="btn alt">Replace</button>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if($active && role()==='supervisor'): ?>
<div class="card noprint" style="border-color:#fca5a5;">
  <div class="card-header" style="background:var(--bad-bg);border-radius:8px 8px 0 0;margin:-18px -20px 14px;padding:10px 20px;">
    <h2 style="color:var(--bad);"><?= svg_icon('warn') ?> Close Account</h2>
  </div>
  <p style="font-size:13px;color:var(--muted);margin-bottom:10px;">
    <?= is_early_closure($a['opened_at'],today($db))
        ? 'Opened less than six months ago — early closure is free.'
        : 'Open six months or more — no closing charge is published.' ?>
  </p>
  <form method="post" class="acts" onsubmit="return confirm('Close this account and pay out the balance?')">
    <?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="action" value="close">
    <input name="reason" placeholder="Reason for closing (required)" required style="flex:1;max-width:300px;">
    <button class="btn bad"><?= svg_icon('warn') ?> Close &amp; Pay Out</button>
  </form>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('audit') ?> Account Statement</h2>
    <span style="font-size:11px;color:var(--muted);"><?= count($tx) ?> transaction(s)</span>
  </div>
  <?php if(!$tx): ?><p class="empty">No transactions yet.</p>
  <?php else: ?>
  <div class="scroll">
    <table>
      <thead><tr><th>Date</th><th>Type</th><th>Note</th><th class="num">Amount (ETB)</th><th class="num">Balance (ETB)</th></tr></thead>
      <tbody>
      <?php foreach($tx as $t): $cr=$t['amount']>=0; ?>
        <tr>
          <td style="font-size:12px;color:var(--muted);white-space:nowrap;"><?= e($t['tx_date']) ?></td>
          <td style="font-size:12px;"><?= e($t['kind']) ?></td>
          <td style="font-size:12px;color:var(--muted);"><?= e($t['note']) ?></td>
          <td class="num <?= $cr?'credit':'debit' ?>"><?= $cr?'+':'' ?><?= birr($t['amount']) ?></td>
          <td class="num"><?= birr($t['balance_after']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<!-- Pass balance to app.js via data attribute -->
<div id="account-data" data-balance="<?= (float)$a['balance'] ?>" hidden></div>
<script>window.CBE_ACCOUNT_BALANCE = <?= (float)$a['balance'] ?>;</script>
<script src="/public/assets/js/app.js"></script>
<?php page_bottom(); ?>
