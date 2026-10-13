<?php
require 'inc.php';
require_login();

$q = trim($_GET['q'] ?? ''); $st = $_GET['status'] ?? '';
$sql = "SELECT a.*, c.first_name, c.last_name, c.cif FROM accounts a JOIN customers c ON c.id=a.customer_id WHERE 1=1";
$args = [];
if ($q !== '') { $sql .= " AND (c.first_name || ' ' || c.last_name LIKE ? OR a.account_no LIKE ? OR c.id_number LIKE ? OR c.phone LIKE ?)"; $args = ["%$q%", "%$q%", "%$q%", "%$q%"]; }
if (in_array($st, ['Pending', 'Active', 'Closed', 'Rejected'], true)) { $sql .= " AND a.status=?"; $args[] = $st; }
$s = $db->prepare($sql . " ORDER BY a.id DESC LIMIT 200");
$s->execute($args);
$rows = $s->fetchAll(PDO::FETCH_ASSOC);
$cls = ['Active' => 't-ok', 'Pending' => 't-warn', 'Closed' => 't-mute', 'Rejected' => 't-bad'];
page_top('Accounts');
?>
<h1>Accounts</h1>
<form class="card" method="get">
  <div class="row">
    <label>Search<input name="q" value="<?= e($q) ?>" placeholder="Name, account number, ID number or phone"></label>
    <label>Status<select name="status"><option value="">All</option><?php foreach (['Pending', 'Active', 'Closed', 'Rejected'] as $o) echo '<option' . ($st === $o ? ' selected' : '') . '>' . $o . '</option>'; ?></select></label>
  </div>
  <button class="btn" type="submit">Search</button>
</form>
<div class="card">
  <?php if (!$rows): ?><p class="empty">No accounts match.</p><?php endif; ?>
  <?php foreach ($rows as $a): ?>
  <div class="item">
    <div>
      <b><a href="account.php?id=<?= (int)$a['id'] ?>"><?= e($a['first_name'] . ' ' . $a['last_name']) ?></a></b>
      <small><?= $a['account_no'] ? 'Account ' . e($a['account_no']) . ' &middot; ' : '' ?><?= e($a['cif']) ?> &middot; <?= e($a['type']) ?><?= $a['status'] === 'Active' ? ' &middot; Balance ETB ' . birr($a['balance']) : '' ?></small>
    </div>
    <span>
      <?php if ($a['funding_status'] === 'Overdue'): ?><span class="tag t-bad">Funding overdue</span> <?php elseif ($a['funding_status'] === 'Awaiting funding' && $a['status'] === 'Active'): ?><span class="tag t-warn">Awaiting funding</span> <?php endif; ?>
      <span class="tag <?= $cls[$a['status']] ?? '' ?>"><?= e($a['status']) ?></span>
    </span>
  </div>
  <?php endforeach; ?>
</div>
<?php page_bottom();
