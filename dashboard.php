<?php
require 'inc.php';
require_login();
$n = fn($sql) => (int)$db->query($sql)->fetchColumn();
$pending  = $n("SELECT COUNT(*) FROM accounts WHERE status='Pending'");
$active   = $n("SELECT COUNT(*) FROM accounts WHERE status='Active'");
$awaiting = $n("SELECT COUNT(*) FROM accounts WHERE funding_status='Awaiting funding' AND status='Active'");
$overdue  = $n("SELECT COUNT(*) FROM accounts WHERE funding_status='Overdue'");
$total    = $db->query("SELECT COALESCE(SUM(balance),0) FROM accounts WHERE status='Active'")->fetchColumn();
$interest = $db->query("SELECT COALESCE(SUM(amount),0) FROM transactions WHERE kind='Interest'")->fetchColumn();

// Recent accounts
$recent = $db->query(
    "SELECT a.id, a.account_no, a.type, a.status, a.balance, a.opened_at,
            c.first_name, c.last_name, c.id_number
       FROM accounts a JOIN customers c ON c.id=a.customer_id
      ORDER BY a.id DESC LIMIT 8"
)->fetchAll(PDO::FETCH_ASSOC);

$status_cls = ['Active'=>'t-ok','Pending'=>'t-brand','Closed'=>'t-mute','Rejected'=>'t-bad'];

page_top('Dashboard');
?>
<div class="page-heading">
  <div>
    <h1>Dashboard</h1>
    <p class="sub">Overview of savings account operations and key metrics.</p>
  </div>
  <?php if (role() === 'officer'): ?>
    <div class="page-heading-actions">
      <a class="btn gold" href="new.php"><?= svg_icon('plus') ?> New Application</a>
    </div>
  <?php elseif (role() === 'supervisor'): ?>
    <div class="page-heading-actions">
      <a class="btn gold" href="approvals.php"><?= svg_icon('check') ?> Review Approvals</a>
    </div>
  <?php endif; ?>
</div>

<!-- Stat cards -->
<div class="stat-grid">
  <div class="stat stat-purple">
    <div class="stat-icon"><?= svg_icon('check') ?></div>
    <b><?= $pending ?></b>
    <span>Pending Approvals</span>
  </div>
  <div class="stat stat-green">
    <div class="stat-icon"><?= svg_icon('accounts') ?></div>
    <b><?= number_format($active) ?></b>
    <span>Active Accounts</span>
  </div>
  <div class="stat stat-gold" style="--gold-accent:var(--gold);">
    <div class="stat-icon" style="background:var(--gold-lt);color:var(--gold-dk);"><?= svg_icon('bank') ?></div>
    <b>ETB <?= birr($total) ?></b>
    <span>Total Deposits (ETB)</span>
  </div>
  <div class="stat stat-blue">
    <div class="stat-icon" style="background:var(--info-bg);color:var(--info);"><?= svg_icon('tariff') ?></div>
    <b>ETB <?= birr($interest) ?></b>
    <span>Interest Due</span>
  </div>
  <?php if ($awaiting): ?>
  <div class="stat">
    <div class="stat-icon"><?= svg_icon('warn') ?></div>
    <b><?= $awaiting ?></b>
    <span>Awaiting Funding</span>
  </div>
  <?php endif; ?>
  <?php if ($overdue): ?>
  <div class="stat stat-red">
    <div class="stat-icon"><?= svg_icon('warn') ?></div>
    <b><?= $overdue ?></b>
    <span>Funding Overdue</span>
  </div>
  <?php endif; ?>
</div>

<?php if ($overdue): ?>
<div class="banner bad" style="margin-bottom:14px;">
  <?= svg_icon('warn') ?>
  <div><strong><?= $overdue ?> account(s) missed the 15-day funding deadline.</strong>
  <a href="admin.php">Run the deadline check</a> to flag them for closure review.</div>
</div>
<?php endif; ?>

<!-- Recent accounts table -->
<div class="card">
  <div class="card-header">
    <h2>Recent Accounts</h2>
    <a class="btn sm alt" href="accounts.php"><?= svg_icon('accounts') ?> View All</a>
  </div>
  <?php if (!$recent): ?>
    <p class="empty">No accounts yet. <a href="new.php">Create the first application.</a></p>
  <?php else: ?>
  <div class="scroll">
    <table>
      <thead>
        <tr>
          <th>Account No.</th>
          <th>Customer Name</th>
          <th>ID Number</th>
          <th>Account Type</th>
          <th>Opened Date</th>
          <th class="num">Balance (ETB)</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent as $a):
          $cls = $status_cls[$a['status']] ?? 't-mute';
        ?>
        <tr>
          <td style="font-weight:700;font-family:monospace;font-size:12px;">
            <?= $a['account_no'] ? e($a['account_no']) : '<span class="muted">—</span>' ?>
          </td>
          <td><a href="account.php?id=<?= (int)$a['id'] ?>" style="font-weight:700;color:var(--ink);"><?= e($a['first_name'] . ' ' . $a['last_name']) ?></a></td>
          <td style="font-size:12px;color:var(--muted);"><?= e($a['id_number']) ?></td>
          <td><?= e($a['type']) ?></td>
          <td style="font-size:12px;color:var(--muted);"><?= e($a['opened_at'] ?: '—') ?></td>
          <td class="num"><?= $a['status'] === 'Active' ? birr($a['balance']) : '—' ?></td>
          <td><span class="tag <?= $cls ?>"><?= e($a['status']) ?></span></td>
          <td>
            <a class="btn xs <?= $a['status'] === 'Pending' ? 'gold' : '' ?>"
               href="<?= $a['status'] === 'Pending' ? 'approvals.php' : 'account.php?id=' . (int)$a['id'] ?>">
              <?= $a['status'] === 'Pending' ? 'Review' : 'View' ?>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p style="font-size:11px;color:var(--muted);margin-top:8px;">
    Showing <?= count($recent) ?> of <?= $n("SELECT COUNT(*) FROM accounts") ?> accounts.
    <a href="accounts.php">View all →</a>
  </p>
  <?php endif; ?>
</div>

<!-- Role guidance -->
<div class="card">
  <div class="card-header">
    <h2>
      <?= role() === 'officer' ? svg_icon('plus') . ' Maker Actions' : svg_icon('check') . ' Supervisor Actions' ?>
    </h2>
  </div>
  <?php if (role() === 'officer'): ?>
    <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">As <strong>Maker (Officer)</strong> you register applicants, verify identity, take deposits/withdrawals, and replace passbooks.</p>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a class="btn gold" href="new.php"><?= svg_icon('plus') ?> New Application</a>
      <a class="btn alt" href="accounts.php"><?= svg_icon('accounts') ?> Browse Accounts</a>
    </div>
  <?php else: ?>
    <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">As <strong>Supervisor (Checker)</strong> you approve/reject applications, close accounts, credit interest and monitor deadlines.</p>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a class="btn gold" href="approvals.php"><?= svg_icon('check') ?> Review Applications <?php if($pending): ?><span style="background:rgba(255,255,255,.3);border-radius:8px;padding:0 6px;"><?= $pending ?></span><?php endif; ?></a>
      <a class="btn alt" href="admin.php"><?= svg_icon('admin') ?> Month-end</a>
      <a class="btn alt" href="accounts.php"><?= svg_icon('accounts') ?> Accounts</a>
      <a class="btn alt" href="audit.php"><?= svg_icon('audit') ?> Audit Trail</a>
    </div>
  <?php endif; ?>
</div>
<?php page_bottom(); ?>
