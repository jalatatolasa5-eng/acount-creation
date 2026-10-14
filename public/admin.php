<?php
require_once __DIR__ . '/../src/inc.php';
require_login(['supervisor']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $act = $_POST['action'] ?? '';
    if      ($act === 'date')      $r = advance_date($db, $_POST['date'] ?? '', me());
    elseif  ($act === 'interest')  $r = run_interest($db, me());
    elseif  ($act === 'deadlines') $r = check_deadlines($db, me());
    elseif  ($act === 'reset' && ($_POST['confirm'] ?? '') === 'RESET') {
        reset_demo($db, me());
        $r = [true, 'Demo data cleared and date reset.'];
    } else $r = [false, 'Nothing was done.'];
    flash($r[1], $r[0] ? 'ok' : 'err');
    redirect('/public/admin.php');
}

$t         = today($db);
$ym        = substr($t, 0, 7);
$lbd       = last_business_day($ym);
$pending   = count(interest_pending_accounts($db));
$due_q     = $db->prepare(
    "SELECT COUNT(*) FROM accounts
      WHERE status='Active' AND funding_status='Awaiting funding'
        AND funding_deadline < ? AND balance < ?"
);
$due_q->execute([$t, MIN_BALANCE]);
$overdue_c = (int) $due_q->fetchColumn();
$users     = $db->query("SELECT id,username,role FROM users ORDER BY id")->fetchAll();

page_top('Admin');
?>
<div class="page-heading">
  <div>
    <h1>System Administration</h1>
    <p class="sub">Manage demo controls and users.</p>
  </div>
</div>

<!-- User Management -->
<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('accounts') ?> User Management</h2>
  </div>
  <div class="scroll">
    <table>
      <thead><tr><th>#</th><th>Username</th><th>Role</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td style="color:var(--muted);font-size:12px;"><?= (int)$u['id'] ?></td>
          <td style="font-weight:700;"><?= e($u['username']) ?></td>
          <td><span class="tag <?= $u['role']==='supervisor'?'t-gold':'t-brand' ?>"><?= e(ucfirst($u['role'])) ?></span></td>
          <td><span class="tag t-ok">Active</span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Demo controls 2×2 grid -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

  <div class="card" style="margin-bottom:0;">
    <div class="card-header">
      <h2><?= svg_icon('calendar') ?> Demo Date</h2>
      <span class="tag t-info"><?= e($t) ?></span>
    </div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">
      Move forward to test time-based rules. Max one month at a time, no going back.
    </p>
    <form method="post" class="acts">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="date">
      <input type="date" name="date" min="<?= e($t) ?>" value="<?= e($t) ?>">
      <button class="btn sm"><?= svg_icon('calendar') ?> Set Date</button>
    </form>
    <p class="note" style="margin-top:8px;">Last business day of <?= e($ym) ?>: <strong><?= e($lbd) ?></strong></p>
  </div>

  <div class="card" style="margin-bottom:0;">
    <div class="card-header">
      <h2><?= svg_icon('tariff') ?> Monthly Interest</h2>
      <span class="tag <?= $pending ? 't-warn' : 't-mute' ?>"><?= $pending ?> waiting</span>
    </div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">
      Credit interest for <?= e($ym) ?> on the last business day (<?= e($lbd) ?>).
    </p>
    <?php if ($t < $lbd): ?>
      <div class="banner" style="margin-bottom:10px;">
        <?= svg_icon('warn') ?><div>Date must be at least <?= e($lbd) ?> to run interest.</div>
      </div>
    <?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="interest">
      <button class="btn gold sm"><?= svg_icon('tariff') ?> Credit Interest for <?= e($ym) ?></button>
    </form>
  </div>

  <div class="card" style="margin-bottom:0;">
    <div class="card-header">
      <h2><?= svg_icon('warn') ?> Funding Deadlines</h2>
      <span class="tag <?= $overdue_c ? 't-bad' : 't-ok' ?>"><?= $overdue_c ?> overdue</span>
    </div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">
      Flag accounts that opened with under ETB 100 and missed the 15-day deadline.
    </p>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="deadlines">
      <button class="btn sm"><?= svg_icon('check') ?> Flag Overdue Accounts</button>
    </form>
  </div>

  <div class="card" style="margin-bottom:0;border-color:#fca5a5;">
    <div class="card-header" style="background:var(--bad-bg);border-radius:8px 8px 0 0;margin:-18px -20px 14px;padding:10px 20px;">
      <h2 style="color:var(--bad);"><?= svg_icon('warn') ?> Reset Demo</h2>
    </div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">
      Delete all demo accounts, transactions and audit records. <strong>Cannot be undone.</strong>
    </p>
    <form method="post" class="acts" onsubmit="return confirm('Delete ALL demo data? This cannot be undone.')">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="reset">
      <input name="confirm" placeholder="Type RESET to confirm" required style="max-width:180px;">
      <button class="btn bad sm"><?= svg_icon('warn') ?> Reset</button>
    </form>
  </div>

</div>
<?php page_bottom(); ?>
