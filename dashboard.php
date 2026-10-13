<?php
require 'inc.php';
require_login();
$n = fn($sql) => (int)$db->query($sql)->fetchColumn();
page_top('Dashboard');
?>
<h1>Dashboard</h1>
<div class="grid">
  <div class="stat"><b><?= $n("SELECT COUNT(*) FROM accounts WHERE status='Pending'") ?></b><span>Applications waiting for approval</span></div>
  <div class="stat"><b><?= $n("SELECT COUNT(*) FROM accounts WHERE status='Active'") ?></b><span>Active accounts</span></div>
  <div class="stat"><b><?= $n("SELECT COUNT(*) FROM accounts WHERE funding_status='Awaiting funding' AND status='Active'") ?></b><span>Awaiting the ETB 100 funding</span></div>
  <div class="stat"><b><?= $n("SELECT COUNT(*) FROM accounts WHERE funding_status='Overdue'") ?></b><span>Missed the 15-day deadline</span></div>
  <div class="stat"><b>ETB <?= birr($db->query("SELECT COALESCE(SUM(balance),0) FROM accounts WHERE status='Active'")->fetchColumn()) ?></b><span>Total balances</span></div>
</div>
<div class="card">
  <h2>What to do next</h2>
  <?php if (role() === 'officer'): ?>
    <p><a class="btn" href="new.php">Start a new application</a> <a class="btn alt" href="accounts.php">Find an account</a></p>
    <p class="sub">Officers (makers) register applicants, verify identity, take deposits and withdrawals, and replace passbooks.</p>
  <?php else: ?>
    <p><a class="btn" href="approvals.php">Review applications</a> <a class="btn alt" href="admin.php">Month-end and deadlines</a></p>
    <p class="sub">Supervisors (checkers) approve or reject applications, close accounts, credit interest and monitor the funding deadline.</p>
  <?php endif; ?>
</div>
<?php page_bottom();
