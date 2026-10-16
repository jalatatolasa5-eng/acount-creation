<?php
$cur    = $data['cur_page'];
$is_sup = $data['role'] === 'supervisor';
$logged = !empty($_SESSION['uid']);

function _nav(string $href, string $label, string $icon, string $cur, int $badge = 0): string {
    $active = $cur === $href ? ' active' : '';
    $bdg    = $badge ? '<span class="nav-badge">' . $badge . '</span>' : '';
    return '<li><a href="' . url($href) . '" class="' . ltrim($active) . '">'
         . svg_icon($icon) . ' ' . e($label) . $bdg . '</a></li>';
}
?>
<nav class="sidebar" aria-label="Main navigation">

  <a class="sidebar-logo" href="<?= url($logged ? 'dashboard.php' : 'index.php') ?>">
    <div class="sidebar-logo-icon">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
        <path d="M2 13l8-8 8 8" stroke="#1a0533" stroke-width="2" fill="none"
              stroke-linecap="round" stroke-linejoin="round"/>
        <rect x="4"    y="10" width="2.5" height="6" fill="#1a0533" rx=".5"/>
        <rect x="8.75" y="10" width="2.5" height="6" fill="#1a0533" rx=".5"/>
        <rect x="13.5" y="10" width="2.5" height="6" fill="#1a0533" rx=".5"/>
      </svg>
    </div>
    <div class="sidebar-logo-text">
      <strong>CBE</strong>
      <span>Savings Account System</span>
    </div>
  </a>

  <ul class="sidebar-nav">

    <?php if ($logged): ?>

      <?= _nav('dashboard.php', 'Dashboard',       'home',     $cur) ?>
      <?= _nav('accounts.php',  'Accounts',        'accounts', $cur) ?>

      <?php if (!$is_sup): ?>
        <!-- Officer (Maker) only -->
        <?= _nav('new.php', 'New Application', 'plus', $cur) ?>
        <?= _nav('corrections.php', 'Corrections', 'warn', $cur) ?>
      <?php endif; ?>

      <?php if ($is_sup): ?>
        <!-- Supervisor (Checker) only -->
        <?= _nav('approvals.php', 'Approvals', 'check', $cur, $data['pending']) ?>
        <?= _nav('admin.php',     'Month-end &amp; Admin', 'admin', $cur) ?>
        <?= _nav('audit.php',     'Audit Trail',  'audit', $cur) ?>
      <?php endif; ?>

    <?php else: ?>

      <!-- Public (not logged in) -->
      <?= _nav('index.php',        'Home',          'home',  $cur) ?>
      <?= _nav('guide.php',        'Applicant Guide','guide', $cur) ?>
      <?= _nav('tariff.php',       'Rates &amp; Fees','tariff',$cur) ?>
      <?= _nav('login.php',        'Staff Login',   'user',  $cur) ?>

    <?php endif; ?>

  </ul>

  <?php if ($logged): ?>
  <div class="sidebar-footer">
    <div style="font-size:11px;color:var(--sb-muted);margin-bottom:3px;">Signed in as</div>
    <div style="color:var(--sb-ink);font-weight:700;font-size:12px;"><?= e($data['user']) ?></div>
  </div>
  <?php endif; ?>

</nav>
