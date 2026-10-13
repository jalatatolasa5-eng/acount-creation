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
<div class="page-heading">
  <div>
    <h1><?= icon('check') ?> Review Applications</h1>
    <p class="sub">Step 2 of maker-checker. Compare details with the documents, then approve or reject. You cannot approve an application you submitted.</p>
  </div>
  <span class="tag <?= $rows ? 't-warn' : 't-mute' ?>" style="font-size:13px;padding:5px 14px;">
    <?= count($rows) ?> pending
  </span>
</div>

<?php if (!$rows): ?>
  <div class="card"><p class="empty"><?= icon('check') ?> No applications waiting for approval.</p></div>
<?php endif; ?>

<?php foreach ($rows as $id):
  $a    = get_account($db, (int)$id);
  $zero = $a['opening_deposit'] < MIN_BALANCE;
?>
<div class="approval-card">
  <!-- Card header -->
  <div class="approval-card-head">
    <div style="flex:1;">
      <h2><?= e($a['first_name'] . ' ' . $a['last_name']) ?></h2>
      <span class="cif">CIF: <?= e($a['cif']) ?> &middot; <?= e($a['type']) ?> &middot; <?= e($a['rate']) ?>% p.a.</span>
    </div>
    <span class="tag t-warn">Pending</span>
  </div>

  <!-- Card body: detail grid -->
  <div class="approval-card-body">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
      <div>
        <p class="sub" style="margin-bottom:6px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;font-size:11px;">Customer</p>
        <dl class="info-grid">
          <dt>Born</dt><dd><?= e($a['dob']) ?> (<?= e($a['gender']) ?>)</dd>
          <dt>Phone</dt><dd><?= e($a['phone']) ?></dd>
          <dt>Address</dt><dd><?= e($a['address']) ?></dd>
          <?php if ($a['guardian_id']): ?>
          <dt>Guardian ID</dt><dd><?= e($a['guardian_id']) ?></dd>
          <?php endif; ?>
        </dl>
      </div>
      <div>
        <p class="sub" style="margin-bottom:6px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;font-size:11px;">Account &amp; Identity</p>
        <dl class="info-grid">
          <dt>ID</dt><dd><?= e($a['id_type']) ?>: <?= e($a['id_number']) ?></dd>
          <dt>Opening deposit</dt><dd><strong>ETB <?= birr($a['opening_deposit']) ?></strong></dd>
          <?php if ($a['card_fee']): ?>
          <dt>Debit card</dt><dd>Requested &mdash; collect ETB <?= birr($a['card_fee']) ?> cash</dd>
          <?php endif; ?>
          <dt>Signature</dt><dd><?= e($a['signature']) ?></dd>
        </dl>
      </div>
    </div>

    <!-- Officer checks -->
    <div style="margin-top:14px;padding:10px 14px;background:var(--bg);border-radius:7px;border:1px solid var(--line);">
      <p class="sub" style="font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px;">Officer Checks</p>
      <div style="display:flex;flex-wrap:wrap;gap:8px 20px;font-size:13px;">
        <?php
        $checks = [
          [$a['id_verified'],         'Identity verified'],
          [$a['kfs_given'],           'KFS given'],
          [$a['conditions_explained'],'Conditions explained'],
          [$a['agreement_signed'],    'Agreement signed'],
        ];
        foreach ($checks as [$ok_c, $lbl]):
        ?>
        <span style="display:flex;align-items:center;gap:5px;color:<?= $ok_c ? 'var(--ok)' : 'var(--bad)' ?>;">
          <?= $ok_c ? icon('ok-ic') : icon('warn') ?> <?= e($lbl) ?>
        </span>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if ($zero): ?>
    <div class="banner" style="margin-top:12px;">
      <?= icon('warn') ?>
      <div><strong>Zero-balance opening.</strong> Source of funds: <em><?= e($a['source_of_funds']) ?></em>. Approving this application starts a 15-day funding deadline.</div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Card footer: actions -->
  <div class="approval-card-foot noprint">
    <form method="post" style="display:flex;gap:0;">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$id ?>">
      <button class="btn ok" name="action" value="approve"><?= icon('check') ?> Approve &amp; Open Account</button>
    </form>
    <form method="post" style="display:flex;gap:8px;align-items:center;flex:1;min-width:260px;">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$id ?>">
      <input name="reason" placeholder="Reason for rejection" aria-label="Reason for rejecting" required
             style="flex:1;min-width:0;">
      <button class="btn bad" name="action" value="reject"><?= icon('warn') ?> Reject</button>
    </form>
  </div>
</div>
<?php endforeach; ?>
<?php page_bottom(); ?>
