<?php
require 'inc.php';
require_login(['supervisor']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $id = (int)($_POST['id'] ?? 0);
    [$ok, $msg] = ($_POST['action'] ?? '') === 'approve'
        ? approve_account($db, $id, me())
        : reject_account($db, $id, $_POST['reason'] ?? '', me());
    flash($msg, $ok ? 'ok' : 'err');
    redirect('approvals.php');
}
$rows = $db->query("SELECT a.id FROM accounts a WHERE status='Pending' ORDER BY a.id")->fetchAll(PDO::FETCH_COLUMN);
page_top('Approvals');
?>
<h1>Review applications</h1>
<p class="sub">Step 2 of maker-checker. Compare the details with the documents, then approve or reject. You cannot approve an application you submitted.</p>
<?php if (!$rows): ?><div class="card"><p class="empty">No applications waiting.</p></div><?php endif; ?>
<?php foreach ($rows as $id): $a = get_account($db, (int)$id); $zero = $a['opening_deposit'] < MIN_BALANCE; ?>
<div class="card">
  <h2><?= e($a['first_name'] . ' ' . $a['last_name']) ?> <small><?= e($a['cif']) ?></small></h2>
  <dl>
    <dt>Account</dt><dd><?= e($a['type']) ?> at <?= e($a['rate']) ?>% a year</dd>
    <dt>Opening deposit</dt><dd>ETB <?= birr($a['opening_deposit']) ?></dd>
    <dt>ID</dt><dd><?= e($a['id_type']) ?>: <?= e($a['id_number']) ?></dd>
    <dt>Born</dt><dd><?= e($a['dob']) ?> (<?= e($a['gender']) ?>)</dd>
    <dt>Contact</dt><dd><?= e($a['phone']) ?>, <?= e($a['address']) ?></dd>
    <?php if ($a['guardian_id']): ?><dt>Guardian ID</dt><dd><?= e($a['guardian_id']) ?></dd><?php endif; ?>
    <?php if ($a['card_fee']): ?><dt>Debit card</dt><dd>Requested. Collect ETB <?= birr($a['card_fee']) ?> in cash.</dd><?php endif; ?>
    <dt>Checks by officer</dt><dd>
      <?= $a['id_verified'] ? 'Identity verified' : 'Identity NOT verified' ?> &middot;
      <?= $a['kfs_given'] ? 'Key Facts Statement given' : 'KFS not given' ?> &middot;
      <?= $a['conditions_explained'] ? 'Conditions explained' : 'Conditions not explained' ?> &middot;
      <?= $a['agreement_signed'] ? 'Agreement signed' : 'Agreement not signed' ?>
    </dd>
    <dt>Signature specimen</dt><dd><?= e($a['signature']) ?></dd>
  </dl>
  <?php if ($zero): ?><p class="banner">Opens with under ETB 100. Source of funds: <b><?= e($a['source_of_funds']) ?></b>. Approving starts a 15-day funding deadline.</p><?php endif; ?>
  <div class="item noprint">
    <form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$id ?>"><button class="btn ok" name="action" value="approve">Approve and open account</button></form>
    <form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$id ?>"><input name="reason" placeholder="Reason for rejecting" aria-label="Reason for rejecting" required><button class="btn bad" name="action" value="reject">Reject</button></form>
  </div>
</div>
<?php endforeach; ?>
<?php page_bottom();
