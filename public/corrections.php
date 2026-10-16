<?php
require_once __DIR__ . '/../src/inc.php';
require_login(['officer']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    [$ok,$msg] = wf_resubmit($db,(int)($_POST['id'] ?? 0),$_POST,me());
    flash($msg,$ok?'ok':'err');
    redirect(url('corrections.php'));
}

$s = $db->prepare("SELECT id FROM accounts WHERE status='Pending' AND returned_at IS NOT NULL AND created_by=? ORDER BY returned_at, id");
$s->execute([me()]);
$ids = $s->fetchAll(PDO::FETCH_COLUMN);

page_top('Corrections');
?>
<div class="page-heading">
  <div><h1>Corrections</h1><p class="sub">Applications a supervisor sent back to you. Fix them and resubmit.</p></div>
  <span class="tag <?= $ids?'t-warn':'t-mute' ?>" style="font-size:12px;padding:5px 12px;"><?= count($ids) ?> to correct</span>
</div>

<?php if(!$ids): ?>
  <div class="card"><p class="empty"><?= svg_icon('check') ?> Nothing waiting for correction.</p></div>
<?php endif; ?>

<?php foreach($ids as $id):
  $a     = get_account($db,(int)$id);
  $minor = age_on($a['dob'], today($db)) < 18;
  $bad   = array_filter(wf_documents($db,(int)$id), fn($d) => in_array($d['status'],['Missing','Invalid'],true));
  $hist  = wf_history($db,(int)$id);
?>
<div class="approval-card">
  <div class="approval-card-head">
    <div style="flex:1;">
      <h2><?= e($a['first_name'].' '.$a['last_name']) ?></h2>
      <span style="font-size:11px;color:var(--muted);">CIF: <?= e($a['cif']) ?> &middot; <?= e($a['type']) ?> &middot; Returned <?= e($a['returned_at']) ?></span>
    </div>
    <span class="tag t-warn">Needs correction</span>
  </div>
  <div class="approval-card-body">
    <div class="banner">
      <?= svg_icon('warn') ?>
      <div><strong>Supervisor's reason:</strong> <em><?= e($a['returned_reason']) ?></em></div>
    </div>
    <?php if($bad): ?>
    <p style="margin:12px 0 4px;font-size:13px;"><strong>Documents to provide again:</strong>
      <?php foreach($bad as $d): ?><span class="tag t-warn"><?= e($d['doc_type']) ?> (<?= e($d['status']) ?>)</span> <?php endforeach; ?></p>
    <?php endif; ?>

    <form method="post" style="margin-top:14px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$id ?>">
      <label>Phone<input name="phone" value="<?= e($a['phone']) ?>" required></label>
      <label>Address<input name="address" value="<?= e($a['address']) ?>" required></label>
      <label>Opening deposit (ETB)<input name="opening_deposit" type="number" step="0.01" min="0" value="<?= e($a['opening_deposit']) ?>"></label>
      <label>Source of funds<input name="source_of_funds" value="<?= e($a['source_of_funds']) ?>"></label>
      <?php if($minor): ?><label>Guardian ID<input name="guardian_id" value="<?= e($a['guardian_id']) ?>"></label><?php endif; ?>
      <label style="grid-column:1/-1;">What did you correct? (required)
        <input name="note" placeholder="e.g. Attached a clear copy of the ID" required></label>
      <div style="grid-column:1/-1;"><button class="btn ok"><?= svg_icon('check') ?> Resubmit for review</button></div>
    </form>

    <?php if($hist): ?>
    <p style="margin:16px 0 6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);">History</p>
    <?php foreach($hist as $h): ?>
    <div style="font-size:12px;padding:4px 0;border-top:1px dashed #eee;">
      <?= e($h['changed_at']) ?> &middot; <strong><?= e($h['old_status'] ?? 'New') ?> &rarr; <?= e($h['new_status']) ?></strong>
      &middot; <?= e($h['username'] ?? '?') ?><?= $h['note'] !== '' ? ' &middot; ' . e($h['note']) : '' ?>
    </div>
    <?php endforeach; endif; ?>
  </div>
</div>
<?php endforeach; ?>
<?php page_bottom(); ?>
