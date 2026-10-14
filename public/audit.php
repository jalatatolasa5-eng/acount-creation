<?php
require_once __DIR__ . '/../src/inc.php';
require_login(['supervisor']);

$log = $db->query(
    "SELECT a.at, a.action, a.detail, u.username, u.role
       FROM audit_log a LEFT JOIN users u ON u.id=a.user_id
      ORDER BY a.id DESC LIMIT 200"
)->fetchAll();

page_top('Audit Trail');
?>
<div class="page-heading">
  <div>
    <h1>Audit Trail</h1>
    <p class="sub">Last 200 staff actions. Visible to supervisors only.</p>
  </div>
  <span class="tag t-mute"><?= count($log) ?> entries</span>
</div>

<div class="filter-bar" style="margin-bottom:14px;">
  <div class="input-wrap" style="flex:2;min-width:200px;margin-top:0;">
    <span class="input-icon"><?= svg_icon('search') ?></span>
    <input placeholder="Filter coming soon…" disabled style="margin-top:0;padding-left:34px;">
  </div>
  <select disabled style="flex:1;min-width:110px;"><option>All Users</option></select>
  <select disabled style="flex:1;min-width:110px;"><option>All Actions</option></select>
</div>

<div class="card">
  <?php if (!$log): ?>
    <p class="empty">Nothing logged yet.</p>
  <?php else: ?>
  <div class="scroll">
    <table>
      <thead>
        <tr>
          <th style="width:160px;">Date &amp; Time</th>
          <th style="width:110px;">User</th>
          <th style="width:100px;">Role</th>
          <th style="width:160px;">Action</th>
          <th>Detail</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($log as $l): ?>
        <tr>
          <td style="font-size:11px;color:var(--muted);white-space:nowrap;font-family:monospace;"><?= e($l['at']) ?></td>
          <td style="font-weight:700;"><?= e($l['username'] ?? '—') ?></td>
          <td>
            <?php $rl = $l['role'] ?? ''; ?>
            <span class="tag <?= $rl === 'supervisor' ? 't-gold' : 't-brand' ?>">
              <?= $rl ? e(ucfirst($rl)) : '—' ?>
            </span>
          </td>
          <td><span class="tag t-info"><?= e($l['action']) ?></span></td>
          <td style="font-size:12px;color:var(--muted);"><?= e($l['detail']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p style="font-size:11px;color:var(--muted);margin-top:8px;">Showing <?= count($log) ?> of last 200 entries.</p>
  <?php endif; ?>
</div>
<?php page_bottom(); ?>
