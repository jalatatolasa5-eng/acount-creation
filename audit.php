<?php
require 'inc.php';
require_login(['supervisor']);
$log = $db->query("SELECT a.at, a.action, a.detail, u.username FROM audit_log a LEFT JOIN users u ON u.id=a.user_id ORDER BY a.id DESC LIMIT 200")->fetchAll(PDO::FETCH_ASSOC);
page_top('Audit trail');
?>
<h1>Audit trail</h1>
<div class="card">
  <?php if (!$log): ?><p class="empty">Nothing logged yet.</p><?php else: ?>
  <div class="scroll"><table><tr><th>When</th><th>User</th><th>Action</th><th>Detail</th></tr>
  <?php foreach ($log as $l): ?><tr><td><?= e($l['at']) ?></td><td><?= e($l['username']) ?></td><td><?= e($l['action']) ?></td><td><?= e($l['detail']) ?></td></tr><?php endforeach; ?>
  </table></div><?php endif; ?>
</div>
<?php page_bottom();
