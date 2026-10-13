<?php
require 'inc.php';
require_login(['supervisor']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $act = $_POST['action'] ?? '';
    if ($act === 'date') $r = advance_date($db, $_POST['date'] ?? '', me());
    elseif ($act === 'interest') $r = run_interest($db, me());
    elseif ($act === 'deadlines') $r = check_deadlines($db, me());
    elseif ($act === 'reset' && ($_POST['confirm'] ?? '') === 'RESET') { reset_demo($db, me()); $r = [true, 'Demo data cleared and the demo date reset.']; }
    else $r = [false, 'Nothing was done.'];
    flash($r[1], $r[0] ? 'ok' : 'err');
    redirect('admin.php');
}
$t = today($db); $ym = substr($t, 0, 7); $lbd = last_business_day($ym);
$pending = count(interest_pending_accounts($db));
$due = $db->prepare("SELECT COUNT(*) FROM accounts WHERE status='Active' AND funding_status='Awaiting funding' AND funding_deadline<? AND balance<?");
$due->execute([$t, MIN_BALANCE]);
page_top('Month-end');
?>
<h1>Month-end and deadlines</h1>
<div class="card">
  <h2>Demo date: <?= e($t) ?></h2>
  <p class="sub">The 15-day, six-month and month-end rules depend on dates. Move the demo date forward to see them work. It can only move forward, one month at a time.</p>
  <form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="action" value="date">
    <input type="date" name="date" min="<?= e($t) ?>" value="<?= e($t) ?>" aria-label="New demo date"><button class="btn">Set date</button></form>
  <p class="note">Quick: last business day of <?= e($ym) ?> is <b><?= e($lbd) ?></b> (Saturday and Sunday are treated as non-business days).</p>
</div>
<div class="card">
  <h2>Credit monthly interest</h2>
  <p>Interest = lowest balance in the month &times; annual rate &divide; 12, credited on the last business day. <?= $pending ?> account(s) waiting for <?= e($ym) ?>.</p>
  <?php if ($t < $lbd): ?><p class="banner">Today is before the last business day (<?= e($lbd) ?>). Move the demo date to <?= e($lbd) ?> to credit interest.</p><?php endif; ?>
  <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="interest"><button class="btn">Credit interest for <?= e($ym) ?></button></form>
</div>
<div class="card">
  <h2>Check funding deadlines</h2>
  <p><?= (int)$due->fetchColumn() ?> account(s) opened with under ETB 100 have passed their 15-day deadline without reaching ETB 100.</p>
  <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="deadlines"><button class="btn">Flag overdue accounts</button></form>
</div>
<div class="card">
  <h2>Reset demo</h2>
  <form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="action" value="reset"><input name="confirm" placeholder="Type RESET" aria-label="Type RESET to confirm" required><button class="btn bad">Delete all demo data</button></form>
</div>
<?php page_bottom();
