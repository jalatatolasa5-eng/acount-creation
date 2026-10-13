<?php
require 'inc.php';
require_login();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$a = get_account($db, $id);
if (!$a) { http_response_code(404); page_top('Not found'); echo '<p class="empty">Account not found. <a href="accounts.php">Back to accounts</a></p>'; page_bottom(); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $act = $_POST['action'] ?? '';
    $amt = is_numeric($_POST['amount'] ?? '') ? (float)$_POST['amount'] : 0.0;
    if ($act === 'deposit' && role() === 'officer') $r = deposit($db, $id, $amt, me());
    elseif ($act === 'withdraw' && role() === 'officer') $r = withdraw($db, $id, $amt, me());
    elseif ($act === 'passbook' && role() === 'officer') $r = replace_passbook($db, $id, $_POST['kind'] ?? '', $_POST['method'] ?? '', me());
    elseif ($act === 'close' && role() === 'supervisor') $r = close_account($db, $id, $_POST['reason'] ?? '', me());
    else $r = [false, 'That action is not available to your role or for this account.'];
    flash($r[1], $r[0] ? 'ok' : 'err');
    redirect('account.php?id=' . $id);
}
$tx = $db->prepare("SELECT * FROM transactions WHERE account_id=? ORDER BY id");
$tx->execute([$id]);
$tx = $tx->fetchAll(PDO::FETCH_ASSOC);
$active = $a['status'] === 'Active';
page_top('Account ' . ($a['account_no'] ?: $a['cif']));
?>
<h1><?= e($a['first_name'] . ' ' . $a['last_name']) ?></h1>
<?php if ($a['status'] === 'Pending'): ?><p class="banner">This application is waiting for supervisor approval. No account number has been issued yet.</p><?php endif; ?>
<?php if ($a['status'] === 'Rejected'): ?><p class="banner">Rejected: <?= e($a['reject_reason']) ?></p><?php endif; ?>
<?php if ($a['funding_status'] === 'Overdue'): ?><p class="banner"><b>Funding overdue.</b> The balance was not ETB 100 or more by <?= e($a['funding_deadline']) ?>. Flagged for closure review.</p>
<?php elseif ($a['funding_status'] === 'Awaiting funding' && $active): ?><p class="banner">Zero-balance opening. Deposit enough to reach ETB 100 by <b><?= e($a['funding_deadline']) ?></b>.</p><?php endif; ?>

<div class="card">
  <dl>
    <dt>Account number</dt><dd><?= $a['account_no'] ? e($a['account_no']) : 'Not issued' ?></dd>
    <dt>Status</dt><dd><?= e($a['status']) ?><?= $a['closed_at'] ? ' on ' . e($a['closed_at']) . ' (' . e($a['close_reason']) . ')' : '' ?></dd>
    <dt>Type and rate</dt><dd><?= e($a['type']) ?>, <?= e($a['rate']) ?>% a year</dd>
    <dt>Customer</dt><dd><?= e($a['cif']) ?> &middot; <?= e($a['id_type']) ?> <?= e($a['id_number']) ?> &middot; <?= e($a['phone']) ?></dd>
    <dt>Opened</dt><dd><?= e($a['opened_at'] ?: 'Not yet') ?></dd>
    <dt>Balance</dt><dd><b>ETB <?= birr($a['balance']) ?></b></dd>
    <dt>Passbook</dt><dd>First passbook free<?= $a['passbook_replacements'] ? ', replaced ' . (int)$a['passbook_replacements'] . ' time(s)' : '' ?></dd>
  </dl>
  <p class="noprint"><button class="btn alt" onclick="window.print()">Print statement</button></p>
</div>

<?php if ($active && role() === 'officer'): ?>
<div class="card noprint">
  <h2>Teller actions</h2>
  <div class="item">
    <form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="action" value="deposit">
      <input type="number" name="amount" min="0.01" step="0.01" placeholder="Amount (ETB)" aria-label="Deposit amount" required><button class="btn ok">Deposit</button></form>
    <form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="action" value="withdraw">
      <input type="number" name="amount" min="0.01" step="0.01" placeholder="Amount (ETB)" aria-label="Withdrawal amount" required><button class="btn">Withdraw</button></form>
  </div>
  <p class="note">Withdrawals up to ETB 500 are free. ETB 501 to 75,000 costs ETB 12. The account must keep at least ETB 100.</p>
  <div class="item">
    <form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="action" value="passbook">
      <select name="kind" aria-label="Passbook problem" style="width:auto"><option value="Lost">Lost (ETB 115)</option><option value="Damaged">Damaged (ETB 57.50)</option></select>
      <select name="method" aria-label="Fee payment" style="width:auto"><option value="Cash">Pay cash</option><option value="Account">Debit account</option></select>
      <button class="btn alt">Replace passbook</button></form>
  </div>
</div>
<?php endif; ?>
<?php if ($active && role() === 'supervisor'): ?>
<div class="card noprint">
  <h2>Close account</h2>
  <form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="action" value="close">
    <input name="reason" placeholder="Reason for closing" aria-label="Reason for closing" required style="width:260px"><button class="btn bad" onclick="return confirm('Close this account and pay out the balance?')">Close and pay out</button></form>
  <p class="note"><?= is_early_closure($a['opened_at'], today($db)) ? 'Opened less than six months ago: closing is free.' : 'Open for six months or more: the study publishes no closing charge for this case.' ?></p>
</div>
<?php endif; ?>

<div class="card">
  <h2>Statement</h2>
  <?php if (!$tx): ?><p class="empty">No transactions yet.</p><?php else: ?>
  <div class="scroll"><table><tr><th>Date</th><th>Type</th><th>Note</th><th class="num">Amount</th><th class="num">Balance</th></tr>
  <?php foreach ($tx as $t): ?><tr><td><?= e($t['tx_date']) ?></td><td><?= e($t['kind']) ?></td><td><?= e($t['note']) ?></td><td class="num"><?= $t['amount'] >= 0 ? '+' : '' ?><?= birr($t['amount']) ?></td><td class="num"><?= birr($t['balance_after']) ?></td></tr><?php endforeach; ?>
  </table></div><?php endif; ?>
</div>
<?php page_bottom();
