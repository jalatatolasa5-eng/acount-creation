<?php
require 'inc.php';
require_login(['supervisor']);
$log = $db->query(
    "SELECT a.at, a.action, a.detail, u.username
       FROM audit_log a
       LEFT JOIN users u ON u.id = a.user_id
      ORDER BY a.id DESC LIMIT 200"
)->fetchAll(PDO::FETCH_ASSOC);
page_top('Audit Trail');
?>
<div class="page-heading">
  <div>
    <h1><?= icon('audit') ?> Audit Trail</h1>
    <p class="sub">Last 200 staff actions. Visible to supervisors only.</p>
  </div>
  <span class="tag t-mute"><?= count($log) ?> entries</span>
</div>

<div class="card">
  <?php if (!$log): ?>
    <p class="empty"><?= icon('audit') ?> Nothing logged yet.</p>
  <?php else: ?>
    <div class="scroll">
      <table>
        <thead>
          <tr>
            <th style="width:170px;">When</th>
            <th style="width:110px;">User</th>
            <th style="width:160px;">Action</th>
            <th>Detail</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($log as $l): ?>
          <tr>
            <td style="white-space:nowrap;color:var(--muted);font-size:12px;"><?= e($l['at']) ?></td>
            <td><strong><?= e($l['username']) ?></strong></td>
            <td><span class="tag t-info"><?= e($l['action']) ?></span></td>
            <td style="font-size:13px;"><?= e($l['detail']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?php page_bottom(); ?>
