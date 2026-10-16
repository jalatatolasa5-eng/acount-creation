<?php
require_once __DIR__ . '/../src/inc.php';
require_login(['supervisor']);
require_once __DIR__ . '/../src/photo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $id  = (int)($_POST['id'] ?? 0);
    $act = $_POST['action'] ?? '';
    if     ($act === 'approve') [$ok,$msg] = photo_has($db,$id)
        ? approve_account($db,$id,me())
        : [false,'No customer photo on file. Return the application for correction so the officer can attach one.'];
    elseif ($act === 'reject')  [$ok,$msg] = reject_account($db,$id,$_POST['reason']??'',me());
    elseif ($act === 'return')  [$ok,$msg] = wf_return_application($db,$id,$_POST['reason']??'',me());
    elseif ($act === 'doc')     [$ok,$msg] = wf_set_document($db,$id,$_POST['doc']??'',$_POST['status']??'',me());
    else                        [$ok,$msg] = [false,'Unknown action.'];
    flash($msg,$ok?'ok':'err');
    redirect(url('approvals.php'));
}

$rows = wf_pending_list($db);
$late = count(array_filter($rows, fn($r) => $r['over_limit']));

page_top('Approvals');
?>
<div class="page-heading">
  <div><h1>Approvals</h1><p class="sub">Review and action pending applications. Reviews should finish within <?= (int)WF_REVIEW_LIMIT_DAYS ?> working days.</p></div>
  <div style="display:flex;gap:8px;align-items:center;">
    <?php if($late): ?><span class="tag t-warn" style="font-size:12px;padding:5px 12px;"><?= $late ?> over <?= (int)WF_REVIEW_LIMIT_DAYS ?> days</span><?php endif; ?>
    <span class="tag <?= $rows?'t-brand':'t-mute' ?>" style="font-size:12px;padding:5px 12px;"><?= count($rows) ?> pending</span>
  </div>
</div>

<?php if(!$rows): ?>
  <div class="card"><p class="empty"><?= svg_icon('check') ?> No applications waiting.</p></div>
<?php endif; ?>

<?php foreach($rows as $r):
  $id   = (int)$r['id'];
  $a    = get_account($db,$id);
  $zero = $a['opening_deposit'] < MIN_BALANCE;
  $docs = wf_documents($db,$id);
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
      <span style="font-size:11px;color:var(--muted);">CIF: <?= e($a['cif']) ?> &middot; <?= e($a['type']) ?> &middot; <?= e($a['rate']) ?>% p.a. &middot; Applied <?= e($a['applied_at']) ?></span>
    </div>
    <span class="tag <?= $r['over_limit']?'t-warn':'t-mute' ?>" title="Working days since the application was submitted">
      <?= (int)$r['waiting_days'] ?> working day<?= $r['waiting_days']==1?'':'s' ?> waiting</span>
    <span class="tag <?= $r['returned']?'t-warn':'t-brand' ?>"><?= $r['returned']?'Needs correction':'Pending' ?></span>
  </div>
  <div class="approval-card-body">
    <?php if($r['returned']): ?>
    <div class="banner" style="margin-bottom:12px;">
      <?= svg_icon('warn') ?>
      <div><strong>Returned for correction on <?= e($r['returned_at']) ?>.</strong> Reason: <em><?= e($r['returned_reason']) ?></em>. Waiting for the officer to resubmit. Approval is on hold.</div>
    </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
      <div>
        <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:7px;">Customer</p>
        <?php if(photo_has($db,$id)): ?>
        <a href="photo.php?id=<?= $id ?>" target="_blank" title="Open full size"><img src="photo.php?id=<?= $id ?>" alt="Customer photo" style="display:block;width:84px;height:104px;object-fit:cover;border-radius:6px;border:1px solid #ddd;margin-bottom:8px;"></a>
        <?php else: ?>
        <p style="font-size:12px;font-weight:700;color:var(--bad);margin-bottom:8px;">No photo on file.</p>
        <?php endif; ?>
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
          <?php if(!empty($a['occupation'])): ?>
          <dt>Occupation</dt><dd><?= e($a['occupation']) ?></dd>
          <dt>Income</dt><dd>ETB <?= birr($a['monthly_income']) ?> / month</dd>
          <dt>TIN</dt><dd><?= e($a['tin'] ?: 'none (minor)') ?></dd>
          <dt>Nationality</dt><dd><?= e($a['nationality']) ?></dd>
          <?php endif; ?>
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

    <div style="margin-top:14px;">
      <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:7px;">Documents</p>
      <?php if(!$docs): ?>
        <p style="font-size:12px;color:var(--muted);">No document records. This application was submitted before document checks were added.</p>
      <?php endif; ?>
      <?php foreach($docs as $d):
        $col = ['Verified'=>'var(--ok)','Missing'=>'var(--bad)','Invalid'=>'var(--bad)'][$d['status']] ?? 'var(--muted)'; ?>
      <form method="post" class="noprint" style="display:flex;gap:8px;align-items:center;padding:5px 0;border-top:1px dashed #eee;flex-wrap:wrap;">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="doc" value="<?= e($d['doc_type']) ?>">
        <span style="flex:1;min-width:140px;font-size:13px;"><?= e($d['doc_type']) ?></span>
        <span style="font-size:12px;font-weight:700;color:<?= $col ?>;min-width:72px;"><?= e($d['status']) ?></span>
        <select name="status" style="font-size:12px;">
          <?php foreach(['Verified','Missing','Invalid'] as $st): ?>
          <option <?= $d['status']===$st?'selected':'' ?>><?= $st ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn alt sm" name="action" value="doc">Save</button>
        <?php if($d['checked_at']): ?><span style="font-size:11px;color:var(--muted);">by <?= e($d['username'] ?? '?') ?>, <?= e($d['checked_at']) ?></span><?php endif; ?>
      </form>
      <?php endforeach; ?>
    </div>

    <?php if($zero): ?>
    <div class="banner" style="margin-top:12px;">
      <?= svg_icon('warn') ?>
      <div><strong>Zero-balance opening.</strong> Source of funds: <em><?= e($a['source_of_funds']) ?></em>. Approving starts a 15-day funding deadline.</div>
    </div>
    <?php endif; ?>
  </div>

  <div class="approval-card-foot noprint" style="flex-wrap:wrap;">
    <form method="post"><?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $id ?>">
      <button class="btn ok" name="action" value="approve" <?= $r['returned']?'disabled title="Waiting for the officer to resubmit"':'' ?>><?= svg_icon('check') ?> Approve Application</button>
    </form>
    <?php if(!$r['returned']): ?>
    <form method="post" style="display:flex;gap:8px;align-items:center;flex:1;min-width:240px;">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $id ?>">
      <input name="reason" placeholder="Reason for returning (required)" required style="flex:1;min-width:0;">
      <button class="btn alt" name="action" value="return"><?= svg_icon('warn') ?> Return for correction</button>
    </form>
    <?php endif; ?>
    <form method="post" style="display:flex;gap:8px;align-items:center;flex:1;min-width:240px;">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $id ?>">
      <input name="reason" placeholder="Reason for rejection (required)" required style="flex:1;min-width:0;">
      <button class="btn bad" name="action" value="reject"><?= svg_icon('warn') ?> Reject</button>
    </form>
  </div>
</div>
<?php endforeach; ?>
<?php page_bottom(); ?>
