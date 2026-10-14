<?php
require_once __DIR__ . '/../src/inc.php';
require_login(['supervisor']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $id = (int)($_POST['id'] ?? 0);
    [$ok,$msg] = ($_POST['action']??'') === 'approve'
        ? approve_account($db,$id,me())
        : reject_account($db,$id,$_POST['reason']??'',me());
    flash($msg,$ok?'ok':'err');
    redirect(url('approvals.php'));
}

$rows = $db->query(
    "SELECT a.id FROM accounts a WHERE status='Pending' ORDER BY a.id"
)->fetchAll(PDO::FETCH_COLUMN);

page_top('Approvals');
?>
<div class="page-heading">
  <div><h1>Approvals</h1><p class="sub">Review and action pending applications.</p></div>
  <span class="tag <?= $rows?'t-brand':'t-mute' ?>" style="font-size:12px;padding:5px 12px;">
    <?= count($rows) ?> pending</span>
</div>

<?php if(!$rows): ?>
  <div class="card"><p class="empty"><?= svg_icon('check') ?> No applications waiting.</p></div>
<?php endif; ?>

<?php foreach($rows as $id):
  $a    = get_account($db,(int)$id);
  $zero = $a['opening_deposit'] < MIN_BALANCE;
  $checks = [
    [$a['id_verified'],         'Identity Verified'],
    [$a['kfs_given'],           'KFS Given'],
    [$a['conditions_explained'],'Conditions Explained'],
    [$a['agreement_signed'],    'Agreement Signed'],
  ];
?>
<div class="approval-card">
  <div class="approval-card-head">
    <div class="acct-avatar" style="width:36px;height:36px;font-size:13px;">
      <?= strtoupper(substr($a['first_name'],0,1).substr($a['last_name'],0,1)) ?>
    </div>
    <div style="flex:1;">
      <h2><?= e($a['first_name'].' '.$a['last_name']) ?></h2>
      <span style="font-size:11px;color:var(--muted);">CIF: <?= e($a['cif']) ?> &middot; <?= e($a['type']) ?> &middot; <?= e($a['rate']) ?>% p.a.</span>
    </div>
    <span class="tag t-brand">Pending</span>
  </div>
  <div class="approval-card-body">
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
      <div>
        <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:7px;">Customer</p>
        <dl class="info-grid" style="grid-template-columns:80px 1fr;">
          <dt>Born</dt><dd><?= e($a['dob']) ?></dd>
          <dt>Gender</dt><dd><?= e($a['gender']) ?></dd>
          <dt>Phone</dt><dd><?= e($a['phone']) ?></dd>
          <dt>Address</dt><dd><?= e($a['address']) ?></dd>
          <?php if($a['guardian_id']): ?><dt>Guardian</dt><dd><?= e($a['guardian_id']) ?></dd><?php endif; ?>
        </dl>
      </div>
      <div>
        <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:7px;">Account</p>
        <dl class="info-grid" style="grid-template-columns:80px 1fr;">
          <dt>Type</dt><dd><?= e($a['type']) ?></dd>
          <dt>Deposit</dt><dd><strong>ETB <?= birr($a['opening_deposit']) ?></strong></dd>
          <dt>ID</dt><dd><?= e($a['id_type']) ?>: <?= e($a['id_number']) ?></dd>
          <?php if($a['card_fee']): ?><dt>Card fee</dt><dd>ETB <?= birr($a['card_fee']) ?> cash</dd><?php endif; ?>
          <dt>Signature</dt><dd><?= e($a['signature']) ?></dd>
        </dl>
      </div>
      <div>
        <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:7px;">Officer Checks</p>
        <div style="display:flex;flex-direction:column;gap:6px;">
          <?php foreach($checks as [$ok_c,$lbl]): ?>
          <span style="display:flex;align-items:center;gap:6px;font-size:12px;color:<?= $ok_c?'var(--ok)':'var(--bad)' ?>;">
            <?= svg_icon($ok_c?'ok-ic':'warn') ?> <?= e($lbl) ?>
          </span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php if($zero): ?>
    <div class="banner" style="margin-top:12px;">
      <?= svg_icon('warn') ?>
      <div><strong>Zero-balance opening.</strong> Source of funds: <em><?= e($a['source_of_funds']) ?></em>. Approving starts a 15-day funding deadline.</div>
    </div>
    <?php endif; ?>
  </div>
  <div class="approval-card-foot noprint">
    <form method="post"><?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$id ?>">
      <button class="btn ok" name="action" value="approve"><?= svg_icon('check') ?> Approve Application</button>
    </form>
    <form method="post" style="display:flex;gap:8px;align-items:center;flex:1;min-width:240px;">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$id ?>">
      <input name="reason" placeholder="Reason for rejection (required)" required style="flex:1;min-width:0;">
      <button class="btn bad" name="action" value="reject"><?= svg_icon('warn') ?> Reject</button>
    </form>
  </div>
</div>
<?php endforeach; ?>
<?php page_bottom(); ?>
