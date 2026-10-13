<?php
require 'inc.php';
require_login(['supervisor']);
$log=$db->query(
    "SELECT a.at, a.action, a.detail, u.username
       FROM audit_log a LEFT JOIN users u ON u.id=a.user_id
      ORDER BY a.id DESC LIMIT 200"
)->fetchAll(PDO::FETCH_ASSOC);
page_top('Audit Trail');
?>
<div class="page-heading">
  <div>
    <h1>Audit Trail</h1>
    <p class="sub">Track all system activity and user actions.</p>
  </div>
  <span class="tag t-mute"><?= count($log) ?> entries</span>
</div>

<!-- Filter bar (visual) -->
<div class="filter-bar" style="margin-bottom:14px;">
  <label style="flex:1;min-width:100px;margin-bottom:0;">
    Date From
    <input type="date" disabled style="margin-top:4px;">
  </label>
  <label style="flex:1;min-width:100px;margin-bottom:0;">
    Date To
    <input type="date" disabled style="margin-top:4px;">
  </label>
  <label style="flex:1;min-width:110px;margin-bottom:0;">
    All Users
    <select disabled style="margin-top:4px;"><option>All Users</option></select>
  </label>
  <label style="flex:1;min-width:110px;margin-bottom:0;">
    All Actions
    <select disabled style="margin-top:4px;"><option>All Actions</option></select>
  </label>
  <div class="input-wrap" style="flex:2;min-width:160px;margin-top:0;align-self:flex-end;">
    <span class="input-icon"><?= svg_icon('search') ?></span>
    <input placeholder="Search..." disabled style="margin-top:0;padding-left:34px;">
  </div>
</div>

<div class="card">
  <?php if(!$log): ?>
    <p class="empty">Nothing logged yet.</p>
  <?php else: ?>
    <div class="scroll">
      <table>
        <thead>
          <tr>
            <th style="width:160px;">Date &amp; Time</th>
            <th style="width:110px;">User</th>
            <th style="width:80px;">Role</th>
            <th style="width:160px;">Action</th>
            <th>Detail</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($log as $l): ?>
          <tr>
            <td style="font-size:11px;color:var(--muted);white-space:nowrap;font-family:monospace;"><?= e($l['at']) ?></td>
            <td style="font-weight:700;"><?= e($l['username']??'—') ?></td>
            <td><span class="tag t-mute" style="font-size:10px;">Maker</span></td>
            <td><span class="tag t-brand"><?= e($l['action']) ?></span></td>
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
