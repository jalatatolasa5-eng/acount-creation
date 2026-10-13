<?php
require 'inc.php';
require_login();
$n = fn($sql) => (int)$db->query($sql)->fetchColumn();
$pending  = $n("SELECT COUNT(*) FROM accounts WHERE status='Pending'");
$active   = $n("SELECT COUNT(*) FROM accounts WHERE status='Active'");
$awaiting = $n("SELECT COUNT(*) FROM accounts WHERE funding_status='Awaiting funding' AND status='Active'");
$overdue  = $n("SELECT COUNT(*) FROM accounts WHERE funding_status='Overdue'");
$total    = $db->query("SELECT COALESCE(SUM(balance),0) FROM accounts WHERE status='Active'")->fetchColumn();
page_top('Dashboard');
?>
<div class="page-heading">
  <div>
    <h1><?= icon('home') ?> Dashboard</h1>
    <p class="sub">Welcome back, <strong><?= e($_SESSION['user'] ?? '') ?></strong>. Here's the current branch snapshot.</p>
  </div>
</div>

<div class="stat-grid">
  <div class="stat stat-brand">
    <b><?= $pending ?></b>
    <span>Applications awaiting approval</span>
  </div>
  <div class="stat stat-ok">
    <b><?= $active ?></b>
    <span>Active accounts</span>
  </div>
  <div class="stat">
    <b><?= $awaiting ?></b>
    <span>Awaiting ETB 100 funding</span>
  </div>
  <div class="stat stat-bad">
    <b><?= $overdue ?></b>
    <span>Missed the 15-day deadline</span>
  </div>
  <div class="stat stat-ok">
    <b>ETB <?= birr($total) ?></b>
    <span>Total active balances</span>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2>
      <?php if (role() === 'officer'): ?>
        <?= icon('plus') ?> Maker actions
      <?php else: ?>
        <?= icon('check') ?> Supervisor actions
      <?php endif; ?>
    </h2>
  </div>

  <?php if (role() === 'officer'): ?>
    <p style="margin-bottom:14px;color:var(--muted);font-size:13.5px;">
      As <strong>Maker (Officer)</strong> you register applicants, verify identity, take deposits and withdrawals, and replace passbooks.
    </p>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <a class="btn" href="new.php"><?= icon('plus') ?> New Application</a>
      <a class="btn alt" href="accounts.php"><?= icon('accounts') ?> Browse Accounts</a>
    </div>
  <?php else: ?>
    <p style="margin-bottom:14px;color:var(--muted);font-size:13.5px;">
      As <strong>Supervisor (Checker)</strong> you approve or reject applications, close accounts, credit interest and monitor the funding deadline.
    </p>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <a class="btn" href="approvals.php"><?= icon('check') ?> Review Applications <?php if ($pending): ?><span style="background:var(--gold);color:#3d2200;border-radius:10px;padding:1px 7px;font-size:11px;margin-left:2px;"><?= $pending ?></span><?php endif; ?></a>
      <a class="btn alt" href="admin.php"><?= icon('admin') ?> Month-end &amp; Deadlines</a>
      <a class="btn alt" href="accounts.php"><?= icon('accounts') ?> Browse Accounts</a>
    </div>
  <?php endif; ?>
</div>

<?php if ($overdue > 0): ?>
<div class="banner">
  <?= icon('warn') ?>
  <div><strong><?= $overdue ?> account(s) have missed the 15-day funding deadline.</strong>
  A supervisor should run the deadline check in <a href="admin.php">Month-end &amp; Deadlines</a> and review them for closure.</div>
</div>
<?php endif; ?>

<?php if ($pending > 0 && role() === 'supervisor'): ?>
<div class="banner info">
  <?= icon('info') ?>
  <div><strong><?= $pending ?> application(s) are waiting for your approval.</strong>
  Go to <a href="approvals.php">Approvals</a> to review them.</div>
</div>
<?php endif; ?>
<?php page_bottom(); ?>
