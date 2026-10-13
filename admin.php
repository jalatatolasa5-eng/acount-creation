<?php
require 'inc.php';
require_login(['supervisor']);

if($_SERVER['REQUEST_METHOD']==='POST'){
    check_csrf();
    $act=$_POST['action']??'';
    if     ($act==='date')     $r=advance_date($db,$_POST['date']??'',me());
    elseif ($act==='interest') $r=run_interest($db,me());
    elseif ($act==='deadlines')$r=check_deadlines($db,me());
    elseif ($act==='reset'&&($_POST['confirm']??'')==='RESET'){reset_demo($db,me());$r=[true,'Demo data cleared and date reset.'];}
    else   $r=[false,'Nothing was done.'];
    flash($r[1],$r[0]?'ok':'err');
    redirect('admin.php');
}
$t=$db->query("SELECT value FROM settings WHERE key='demo_date'")->fetchColumn();
$t=today($db); $ym=substr($t,0,7); $lbd=last_business_day($ym);
$pending=count(interest_pending_accounts($db));
$due=$db->prepare("SELECT COUNT(*) FROM accounts WHERE status='Active' AND funding_status='Awaiting funding' AND funding_deadline<? AND balance<?");
$due->execute([$t,MIN_BALANCE]);
$overdue_c=(int)$due->fetchColumn();

// User list for "User Management" section
$users=$db->query("SELECT id,username,role FROM users ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

page_top('Admin');
?>
<div class="page-heading">
  <div>
    <h1>System Administration</h1>
    <p class="sub">Manage system settings, users and demo controls.</p>
  </div>
</div>

<!-- Tab bar (visual) -->
<div style="display:flex;gap:0;border-bottom:2px solid var(--line);margin-bottom:16px;">
  <?php foreach(['Users','Roles','System Settings','Backup'] as $i=>$tab): ?>
  <div style="padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer;
       <?= $i===0?'border-bottom:2px solid var(--brand);color:var(--brand);margin-bottom:-2px;':'color:var(--muted);' ?>">
    <?= e($tab) ?>
  </div>
  <?php endforeach; ?>
</div>

<!-- User Management -->
<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('accounts') ?> User Management</h2>
    <button class="btn gold sm" disabled><?= svg_icon('plus') ?> Add User</button>
  </div>
  <div class="scroll">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Username</th>
          <th>Full Name</th>
          <th>Role</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($users as $u): ?>
        <tr>
          <td style="color:var(--muted);font-size:12px;"><?= (int)$u['id'] ?></td>
          <td style="font-weight:700;"><?= e($u['username']) ?></td>
          <td style="color:var(--muted);"><?= e(ucfirst($u['username'])) ?></td>
          <td>
            <span class="tag <?= $u['role']==='supervisor'?'t-gold':'t-brand' ?>">
              <?= e(ucfirst($u['role'])) ?>
            </span>
          </td>
          <td><span class="tag t-ok">Active</span></td>
          <td style="display:flex;gap:6px;">
            <button class="btn xs alt" disabled>Edit</button>
            <button class="btn xs bad" disabled style="opacity:.5;">Delete</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Demo controls -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

  <!-- Date -->
  <div class="card" style="margin-bottom:0;">
    <div class="card-header">
      <h2><?= svg_icon('calendar') ?> Demo Date</h2>
      <span class="tag t-info"><?= e($t) ?></span>
    </div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">Move the demo date forward to test time-based rules. Max one month at a time, no going back.</p>
    <form method="post" class="acts">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="date">
      <input type="date" name="date" min="<?= e($t) ?>" value="<?= e($t) ?>" aria-label="New demo date">
      <button class="btn sm" type="submit"><?= svg_icon('calendar') ?> Set</button>
    </form>
    <p class="note" style="margin-top:8px;">Last business day of <?= e($ym) ?>: <strong><?= e($lbd) ?></strong></p>
  </div>

  <!-- Interest -->
  <div class="card" style="margin-bottom:0;">
    <div class="card-header">
      <h2><?= svg_icon('tariff') ?> Monthly Interest</h2>
      <span class="tag <?= $pending?'t-warn':'t-mute' ?>"><?= $pending ?> waiting</span>
    </div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">Credit interest for <?= e($ym) ?> on the last business day (<?= e($lbd) ?>).</p>
    <?php if($t<$lbd): ?>
      <div class="banner" style="margin-bottom:10px;"><?= svg_icon('warn') ?><div>Date must be at least <?= e($lbd) ?> to run interest.</div></div>
    <?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="interest">
      <button class="btn gold sm"><?= svg_icon('tariff') ?> Credit Interest for <?= e($ym) ?></button>
    </form>
  </div>

  <!-- Deadlines -->
  <div class="card" style="margin-bottom:0;">
    <div class="card-header">
      <h2><?= svg_icon('warn') ?> Funding Deadlines</h2>
      <span class="tag <?= $overdue_c?'t-bad':'t-ok' ?>"><?= $overdue_c ?> overdue</span>
    </div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">Flag accounts that opened with under ETB 100 and missed the 15-day deadline.</p>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="deadlines">
      <button class="btn sm"><?= svg_icon('check') ?> Flag Overdue Accounts</button>
    </form>
  </div>

  <!-- Reset -->
  <div class="card" style="margin-bottom:0;border-color:#fca5a5;">
    <div class="card-header" style="background:var(--bad-bg);border-radius:8px 8px 0 0;margin:-18px -20px 14px;padding:10px 20px;">
      <h2 style="color:var(--bad);"><?= svg_icon('warn') ?> Reset Demo</h2>
    </div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">Delete all demo accounts, transactions and audit records. <strong>Cannot be undone.</strong></p>
    <form method="post" class="acts" onsubmit="return confirm('Delete ALL demo data? This cannot be undone.')">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="reset">
      <input name="confirm" placeholder="Type RESET to confirm" required style="max-width:180px;">
      <button class="btn bad sm"><?= svg_icon('warn') ?> Reset</button>
    </form>
  </div>

</div>
<?php page_bottom(); ?>
