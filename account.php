<?php
require 'inc.php';
require_login();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$a  = get_account($db, $id);
if (!$a) {
    http_response_code(404); page_top('Not found');
    echo '<p class="empty">Account not found. <a href="accounts.php">Back to accounts</a></p>';
    page_bottom(); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $act = $_POST['action'] ?? '';
    $amt = is_numeric($_POST['amount'] ?? '') ? (float)$_POST['amount'] : 0.0;
    if      ($act==='deposit'  && role()==='officer')    $r = deposit($db,$id,$amt,me());
    elseif  ($act==='withdraw' && role()==='officer')    $r = withdraw($db,$id,$amt,me());
    elseif  ($act==='passbook' && role()==='officer')    $r = replace_passbook($db,$id,$_POST['kind']??'',$_POST['method']??'',me());
    elseif  ($act==='close'    && role()==='supervisor') $r = close_account($db,$id,$_POST['reason']??'',me());
    else    $r = [false,'That action is not available for your role or this account.'];
    flash($r[1],$r[0]?'ok':'err');
    redirect('account.php?id='.$id);
}

$tx = $db->prepare("SELECT * FROM transactions WHERE account_id=? ORDER BY id");
$tx->execute([$id]);
$tx     = $tx->fetchAll(PDO::FETCH_ASSOC);
$active = $a['status']==='Active';
$status_cls=['Active'=>'t-ok','Pending'=>'t-brand','Closed'=>'t-mute','Rejected'=>'t-bad'];
$cls = $status_cls[$a['status']] ?? 't-mute';

page_top('Account '.($a['account_no']?:$a['cif']));
?>

<div class="page-heading">
  <div>
    <h1><?= e($a['first_name'].' '.$a['last_name']) ?></h1>
    <p class="sub">
      <?= $a['account_no']?'Account '.e($a['account_no']).' &middot; ':'' ?>
      <?= e($a['cif']) ?> &middot; <?= e($a['type']) ?>
    </p>
  </div>
  <div class="page-heading-actions">
    <span class="tag <?= $cls ?>" style="font-size:12px;padding:5px 12px;"><?= e($a['status']) ?></span>
    <button class="btn alt sm noprint" onclick="window.print()"><?= svg_icon('print') ?> Print</button>
  </div>
</div>

<!-- Status banners -->
<?php if($a['status']==='Pending'): ?>
  <div class="banner info"><?= svg_icon('info') ?><div>This application is <strong>waiting for supervisor approval</strong>. No account number has been issued yet.</div></div>
<?php endif; ?>
<?php if($a['status']==='Rejected'): ?>
  <div class="banner bad"><?= svg_icon('warn') ?><div><strong>Rejected:</strong> <?= e($a['reject_reason']) ?></div></div>
<?php endif; ?>
<?php if($a['funding_status']==='Overdue'): ?>
  <div class="banner bad"><?= svg_icon('warn') ?><div><strong>Funding overdue.</strong> Balance was not ETB 100 or more by <?= e($a['funding_deadline']) ?>. Flagged for closure review.</div></div>
<?php elseif($a['funding_status']==='Awaiting funding'&&$active): ?>
  <div class="banner"><?= svg_icon('warn') ?><div>Zero-balance opening. Deposit enough to reach ETB 100 by <strong><?= e($a['funding_deadline']) ?></strong>.</div></div>
<?php endif; ?>

<!-- Two-column layout: details + balance -->
<div style="display:grid;grid-template-columns:1fr 280px;gap:16px;margin-bottom:16px;">

  <!-- Account details -->
  <div class="card" style="margin-bottom:0;">
    <div class="card-header"><h2><?= svg_icon('accounts') ?> Account Details</h2></div>
    <dl class="info-grid">
      <dt>Account No.</dt>
      <dd><?= $a['account_no']?'<strong style="font-family:monospace;">'.e($a['account_no']).'</strong>':'<em style="color:var(--muted);">Not issued</em>' ?></dd>
      <dt>Status</dt>
      <dd><span class="tag <?= $cls ?>"><?= e($a['status']) ?></span>
        <?= $a['closed_at']?' &mdash; '.e($a['closed_at']).' ('.e($a['close_reason']).')':'' ?></dd>
      <dt>Account Type</dt><dd><?= e($a['type']) ?></dd>
      <dt>Annual Rate</dt><dd><?= e($a['rate']) ?>%</dd>
      <dt>Opened</dt><dd><?= e($a['opened_at']?:'Not yet') ?></dd>
      <dt>Customer ID</dt><dd><?= e($a['cif']) ?></dd>
      <dt>ID Document</dt><dd><?= e($a['id_type']) ?> &mdash; <?= e($a['id_number']) ?></dd>
      <dt>Phone</dt><dd><?= e($a['phone']) ?></dd>
      <dt>Passbook</dt><dd>First free<?= $a['passbook_replacements']?'; replaced '.(int)$a['passbook_replacements'].'×':'' ?></dd>
    </dl>
  </div>

  <!-- Balance card -->
  <div style="display:flex;flex-direction:column;gap:12px;">
    <div class="card" style="margin-bottom:0;text-align:center;border-top:3px solid var(--brand);">
      <p style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Current Balance</p>
      <p style="font-size:28px;font-weight:800;color:var(--brand-dk);font-variant-numeric:tabular-nums;">ETB <?= birr($a['balance']) ?></p>
      <p style="font-size:11px;color:var(--muted);margin-top:4px;"><?= e($a['type']) ?> &middot; <?= e($a['rate']) ?>% p.a.</p>
    </div>
    <?php if($active): ?>
    <div class="card" style="margin-bottom:0;font-size:12px;">
      <p style="font-weight:700;margin-bottom:8px;">Fee Schedule</p>
      <dl style="display:grid;grid-template-columns:1fr max-content;gap:4px 8px;font-size:12px;">
        <dt style="color:var(--muted);">Withdrawal ≤ ETB 500</dt><dd style="text-align:right;"><span class="tag t-ok">Free</span></dd>
        <dt style="color:var(--muted);">Withdrawal ETB 501–75,000</dt><dd style="text-align:right;"><span class="tag t-warn">ETB 12</span></dd>
        <dt style="color:var(--muted);">Passbook (lost)</dt><dd style="text-align:right;">ETB 115</dd>
        <dt style="color:var(--muted);">Passbook (damaged)</dt><dd style="text-align:right;">ETB 57.50</dd>
      </dl>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Teller actions (officer) -->
<?php if($active && role()==='officer'): ?>
<div class="card noprint">
  <div class="card-header"><h2><?= svg_icon('plus') ?> Teller Actions</h2></div>
  <div class="teller-grid">

    <!-- Deposit -->
    <div class="teller-box">
      <h3>Deposit</h3>
      <form method="post" class="acts">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="deposit">
        <input type="number" name="amount" min="0.01" step="0.01"
               placeholder="Amount (ETB)" aria-label="Deposit amount" required style="flex:1;">
        <button class="btn ok"><?= svg_icon('plus') ?> Deposit</button>
      </form>
      <p class="note" style="margin-top:6px;">No fee on deposits.</p>
    </div>

    <!-- Withdraw -->
    <div class="teller-box">
      <h3>Withdrawal</h3>
      <form method="post" class="acts">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="withdraw">
        <input id="wdr_amt" type="number" name="amount" min="0.01" step="0.01"
               placeholder="Amount (ETB)" aria-label="Withdrawal amount" required style="flex:1;">
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
      <p class="note" style="margin-top:6px;">Free ≤ ETB 500 &middot; ETB 12 fee for ETB 501–75,000 &middot; Min balance ETB 100.</p>
    </div>

    <!-- Passbook -->
    <div class="teller-box" style="grid-column:1/-1;">
      <h3>Passbook Replacement</h3>
      <form method="post" class="acts">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="action" value="passbook">
        <select name="kind" aria-label="Passbook reason" style="flex:1;min-width:150px;">
          <option value="Lost">Lost — ETB 115.00 incl. VAT</option>
          <option value="Damaged">Damaged — ETB 57.50 incl. VAT</option>
        </select>
        <select name="method" aria-label="Fee payment" style="flex:1;min-width:130px;">
          <option value="Cash">Pay in cash</option>
          <option value="Account">Debit from account</option>
        </select>
        <button class="btn alt"><?= svg_icon('guide') ?> Replace</button>
      </form>
      <p class="note" style="margin-top:6px;">Debiting from account refused if balance would drop below ETB 100.</p>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Close account (supervisor) -->
<?php if($active && role()==='supervisor'): ?>
<div class="card noprint" style="border-color:#fca5a5;">
  <div class="card-header" style="background:var(--bad-bg);border-radius:8px 8px 0 0;margin:-18px -20px 14px;padding:12px 20px;">
    <h2 style="color:var(--bad);"><?= svg_icon('warn') ?> Close Account</h2>
  </div>
  <p style="font-size:13px;color:var(--muted);margin-bottom:10px;">
    <?= is_early_closure($a['opened_at'],today($db))
        ? '<strong>Opened less than six months ago</strong> — early closure is free.'
        : 'Open six months or more — no closing charge is published for this case.' ?>
    Closing pays out the full balance and permanently blocks further transactions.
  </p>
  <form method="post" class="acts" onsubmit="return confirm('Close this account and pay out the balance?')">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="action" value="close">
    <input name="reason" placeholder="Reason for closing (required)" required style="flex:1;max-width:300px;">
    <button class="btn bad"><?= svg_icon('warn') ?> Close &amp; Pay Out</button>
  </form>
</div>
<?php endif; ?>

<!-- Statement -->
<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('audit') ?> Account Statement</h2>
    <span style="font-size:11px;color:var(--muted);"><?= count($tx) ?> transaction(s)</span>
  </div>
  <?php if(!$tx): ?>
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

<script>
(function(){
  var wAmt=document.getElementById('wdr_amt'),
      wBox=document.getElementById('wdr_summary'),
      wsAmt=document.getElementById('ws_amt'),
      wsFee=document.getElementById('ws_fee'),
      wsTot=document.getElementById('ws_total'),
      wsAft=document.getElementById('ws_after'),
      bal=<?= (float)$a['balance'] ?>;
  function fmt(n){return 'ETB '+n.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});}
  function upd(){
    var v=parseFloat(wAmt.value);
    if(isNaN(v)||v<=0){wBox.style.display='none';return;}
    var fee=(v>500&&v<=75000)?12:0, tot=v+fee, after=bal-tot;
    wBox.style.display='block';
    wsAmt.textContent=fmt(v);
    wsFee.textContent=fee>0?fmt(fee):'Free';
    wsTot.textContent=fmt(tot);
    wsAft.textContent=fmt(after);
    wsAft.style.color=after<100?'var(--bad)':'var(--ok)';
  }
  if(wAmt)wAmt.oninput=upd;
})();
</script>
<?php page_bottom(); ?>
