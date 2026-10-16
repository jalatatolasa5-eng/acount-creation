<?php
require_once __DIR__ . '/../src/inc.php';
require_login(['supervisor']);

$users = $db->query("SELECT id,username,role FROM users ORDER BY id")->fetchAll();

page_top('Admin');
?>
<div class="page-heading">
  <div>
    <h1>System Administration</h1>
    <p class="sub">Users and roles in the account approval process.</p>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('accounts') ?> User Management</h2>
  </div>
  <div class="scroll">
    <table>
      <thead><tr><th>#</th><th>Username</th><th>Role</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td style="color:var(--muted);font-size:12px;"><?= (int)$u['id'] ?></td>
          <td style="font-weight:700;"><?= e($u['username']) ?></td>
          <td><span class="tag <?= $u['role']==='supervisor'?'t-gold':'t-brand' ?>"><?= e(ucfirst($u['role'])) ?></span></td>
          <td><span class="tag t-ok">Active</span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php page_bottom(); ?>
