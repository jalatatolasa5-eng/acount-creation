<div class="topbar" role="banner">
  <div class="topbar-title">CBE Savings Account System</div>
  <div class="topbar-right">

    <div class="topbar-date">
      <?= svg_icon('calendar') ?>
      Business Date: <?= e($data['today']) ?>
    </div>

    <?php if ($data['user']): ?>
      <?php
        $is_sup   = $data['role'] === 'supervisor';
        $initials = strtoupper(substr($data['user'], 0, 2));
        $role_lbl = $is_sup ? 'Supervisor' : 'Maker';
        $pill_cls = $is_sup ? 'role-pill supervisor' : 'role-pill';
      ?>
      <div class="topbar-user">
        <div class="user-avatar"><?= e($initials) ?></div>
        <div class="topbar-user-info">
          <div class="topbar-user-name"><?= e($data['user']) ?></div>
          <div class="topbar-user-role"><?= e($role_lbl) ?></div>
        </div>
        <span class="<?= $pill_cls ?>"><?= e($role_lbl) ?></span>
      </div>
      <a href="<?= url('logout.php') ?>" class="topbar-logout">
        <?= svg_icon('logout') ?> Logout
      </a>
    <?php endif; ?>

  </div>
</div>
