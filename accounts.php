<?php
require 'inc.php';
require_login();

$q  = trim($_GET['q']      ?? '');
$st = $_GET['status']      ?? '';

$sql  = "SELECT a.*, c.first_name, c.last_name, c.cif FROM accounts a JOIN customers c ON c.id=a.customer_id WHERE 1=1";
$args = [];
if ($q !== '') {
    $sql  .= " AND (CONCAT(c.first_name, ' ', c.last_name) LIKE ? OR a.account_no LIKE ? OR c.id_number LIKE ? OR c.phone LIKE ?)";
    $args  = ["%$q%", "%$q%", "%$q%", "%$q%"];
}
if (in_array($st, ['Pending', 'Active', 'Closed', 'Rejected'], true)) {
    $sql  .= " AND a.status=?";
    $args[] = $st;
}
$s = $db->prepare($sql . " ORDER BY a.id DESC LIMIT 200");
$s->execute($args);
$rows = $s->fetchAll(PDO::FETCH_ASSOC);

$status_cls = [
    'Active'   => 't-ok',
    'Funded'   => 't-ok',
    'Pending'  => 't-warn',
    'Closed'   => 't-mute',
    'Rejected' => 't-bad',
];

page_top('Accounts');
?>
<div class="page-heading">
  <div>
    <h1><?= icon('accounts') ?> Accounts</h1>
    <p class="sub">Search and browse all savings accounts at the branch.</p>
  </div>
</div>

<!-- Filter bar -->
<div class="filter-bar">
  <form method="get" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;width:100%;">
    <label style="flex:2;min-width:180px;margin-bottom:0;">
      Search
      <input name="q" value="<?= e($q) ?>" placeholder="Name, account number, ID or phone" style="margin-top:4px;">
    </label>
    <label style="flex:1;min-width:140px;margin-bottom:0;">
      Status
      <select name="status" style="margin-top:4px;">
        <option value="">All statuses</option>
        <?php foreach (['Pending', 'Active', 'Closed', 'Rejected'] as $o): ?>
          <option<?= $st === $o ? ' selected' : '' ?>><?= $o ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="btn" type="submit" style="margin-bottom:0;"><?= icon('audit') ?> Search</button>
    <?php if ($q || $st): ?>
      <a class="btn alt sm" href="accounts.php">Clear</a>
    <?php endif; ?>
  </form>
</div>

<div class="card">
  <?php if (!$rows): ?>
    <p class="empty"><?= icon('accounts') ?> No accounts match your search.</p>
  <?php else: ?>
    <p class="sub" style="margin-bottom:12px;">Showing <?= count($rows) ?> result(s)</p>
    <ul class="acct-list">
    <?php foreach ($rows as $a):
      $initials = strtoupper(substr($a['first_name'], 0, 1) . substr($a['last_name'], 0, 1));
      $cls      = $status_cls[$a['status']] ?? 't-mute';
    ?>
      <li class="acct-item">
        <div class="acct-avatar"><?= e($initials) ?></div>
        <div class="acct-info">
          <a href="account.php?id=<?= (int)$a['id'] ?>"><?= e($a['first_name'] . ' ' . $a['last_name']) ?></a>
          <div class="acct-meta">
            <?= $a['account_no'] ? 'Account ' . e($a['account_no']) . ' &middot; ' : '' ?>
            <?= e($a['cif']) ?> &middot; <?= e($a['type']) ?>
          </div>
        </div>
        <div class="acct-right">
          <div style="display:flex;gap:5px;flex-wrap:wrap;justify-content:flex-end;">
            <?php if ($a['funding_status'] === 'Overdue'): ?>
              <span class="tag t-bad">Funding Overdue</span>
            <?php elseif ($a['funding_status'] === 'Awaiting funding' && $a['status'] === 'Active'): ?>
              <span class="tag t-warn">Awaiting Funding</span>
            <?php endif; ?>
            <span class="tag <?= $cls ?>"><?= e($a['status']) ?></span>
          </div>
          <?php if ($a['status'] === 'Active'): ?>
            <div class="acct-bal">ETB <?= birr($a['balance']) ?></div>
          <?php endif; ?>
        </div>
      </li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
<?php page_bottom(); ?>
