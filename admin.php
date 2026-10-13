<?php
require 'inc.php';
require_login(['supervisor']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $act = $_POST['action'] ?? '';
    if      ($act === 'date')      $r = advance_date($db, $_POST['date'] ?? '', me());
    elseif  ($act === 'interest')  $r = run_interest($db, me());
    elseif  ($act === 'deadlines') $r = check_deadlines($db, me());
    elseif  ($act === 'reset' && ($_POST['confirm'] ?? '') === 'RESET') {
        reset_demo($db, me());
        $r = [true, 'Demo data cleared and the demo date reset.'];
    }
    else $r = [false, 'Nothing was done.'];
    flash($r[1], $r[0] ? 'ok' : 'err');
    redirect('admin.php');
}

$t       = today($db);
$ym      = substr($t, 0, 7);
$lbd     = last_business_day($ym);
$pending = count(interest_pending_accounts($db));

$due_q = $db->prepare(
    "SELECT COUNT(*) FROM accounts
      WHERE status='Active'
        AND funding_status='Awaiting funding'
        AND funding_deadline < ?
        AND balance < ?"
);
$due_q->execute([$t, MIN_BALANCE]);
$overdue_count = (int)$due_q->fetchColumn();

page_top('Month-end');
?>
<div class="page-heading">
  <div>
    <h1><?= icon('admin') ?> Month-end &amp; Deadlines</h1>
    <p class="sub">Advance the demo date, credit interest, and check funding deadlines.</p>
  </div>
</div>

<!-- Demo date -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('calendar') ?> Demo Date</h2>
    <span class="tag t-info" style="font-size:13px;padding:5px 14px;"><?= e($t) ?></span>
  </div>
  <p style="margin-bottom:12px;font-size:13.5px;color:var(--muted);">
    The 15-day, six-month and month-end rules all depend on the date. Move it forward to see them work.
    It can only move forward and no more than one month at a time.
  </p>
  <form method="post" class="acts">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="date">
    <input type="date" name="date" min="<?= e($t) ?>" value="<?= e($t) ?>" aria-label="New demo date">
    <button class="btn" type="submit"><?= icon('calendar') ?> Set Date</button>
  </form>
  <p class="note" style="margin-top:10px;">
    <?= icon('info') ?> Last business day of <?= e($ym) ?> is <strong><?= e($lbd) ?></strong>
    (Saturday and Sunday treated as non-business days).
  </p>
</div>

<!-- Interest -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('tariff') ?> Credit Monthly Interest</h2>
    <span class="tag <?= $pending ? 't-warn' : 't-mute' ?>"><?= $pending ?> account(s) waiting</span>
  </div>
  <p style="margin-bottom:12px;font-size:13.5px;color:var(--muted);">
    Interest = lowest balance in the month &times; annual rate &divide; 12.
    Credited on the last business day of each month, once per month.
  </p>
  <?php if ($t < $lbd): ?>
    <div class="banner" style="margin-bottom:12px;">
      <?= icon('warn') ?>
      <div>Today is before the last business day (<strong><?= e($lbd) ?></strong>).
      Move the demo date to <?= e($lbd) ?> before running interest.</div>
    </div>
  <?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="interest">
    <button class="btn gold" type="submit"><?= icon('tariff') ?> Credit Interest for <?= e($ym) ?></button>
  </form>
</div>

<!-- Deadlines -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('warn') ?> Check Funding Deadlines</h2>
    <span class="tag <?= $overdue_count ? 't-bad' : 't-ok' ?>"><?= $overdue_count ?> overdue</span>
  </div>
  <p style="margin-bottom:12px;font-size:13.5px;color:var(--muted);">
    Accounts opened with under ETB 100 must reach the minimum within 15 days. Flagging marks them
    <strong>Overdue</strong> for closure review.
  </p>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="deadlines">
    <button class="btn" type="submit"><?= icon('check') ?> Flag Overdue Accounts</button>
  </form>
</div>

<!-- Reset -->
<div class="card" style="border-color:#f5b7b2;">
  <div class="card-header" style="background:var(--bad-bg);">
    <h2 style="color:var(--bad);"><?= icon('warn') ?> Reset Demo</h2>
  </div>
  <p style="margin-bottom:12px;font-size:13.5px;color:var(--muted);">
    Deletes all demo accounts, customers, transactions and audit records, and resets the date.
    <strong>This cannot be undone.</strong>
  </p>
  <form method="post" class="acts" onsubmit="return confirm('Delete ALL demo data? This cannot be undone.')">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="reset">
    <input name="confirm" placeholder="Type RESET to confirm" aria-label="Type RESET to confirm" required style="max-width:220px;">
    <button class="btn bad" type="submit"><?= icon('warn') ?> Delete All Demo Data</button>
  </form>
</div>
<?php page_bottom(); ?>
