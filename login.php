<?php
require 'inc.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $s = $db->prepare("SELECT * FROM users WHERE username = ?");
    $s->execute([trim($_POST['username'] ?? '')]);
    $u = $s->fetch(PDO::FETCH_ASSOC);
    if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$u['id']; $_SESSION['user'] = $u['username']; $_SESSION['role'] = $u['role'];
        log_action('login');
        redirect('dashboard.php');
    }
    flash('Wrong username or password.', 'err');
    redirect('login.php');
}
page_top('Staff login');
?>
<!-- Flash messages on login page -->
<?php foreach ($_SESSION['flash'] ?? [] as [$t, $m]): ?>
<div class="msg <?= $t === 'err' ? 'msg-err' : 'msg-ok' ?>" role="alert" style="width:100%;max-width:400px;margin-bottom:8px;">
  <?= icon($t === 'err' ? 'warn' : 'ok-ic') ?> <?= e($m) ?>
</div>
<?php endforeach; $_SESSION['flash'] = []; ?>

<div class="login-card">
  <!-- Logo -->
  <div class="login-logo">
    <div class="login-logo-icon">
      <svg width="26" height="26" viewBox="0 0 32 32" fill="none" aria-hidden="true">
        <path d="M4 21l12-11 12 11" stroke="#fff" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        <rect x="7" y="16" width="3.5" height="7" fill="#fff" rx="1"/>
        <rect x="14.25" y="16" width="3.5" height="7" fill="#fff" rx="1"/>
        <rect x="21.5" y="16" width="3.5" height="7" fill="#fff" rx="1"/>
        <path d="M4 21h24" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="login-logo-text">
      <strong>Commercial Bank of Ethiopia</strong>
      <span>Wayu Branch &mdash; Savings Back-office</span>
    </div>
  </div>

  <h2>Staff Login</h2>

  <form method="post" novalidate>
    <?= csrf_field() ?>
    <label for="username">Username
      <input id="username" name="username" required autofocus autocomplete="username"
             placeholder="Enter your username">
    </label>
    <label for="password">Password
      <input id="password" name="password" type="password" required
             autocomplete="current-password" placeholder="Enter your password">
    </label>
    <button class="btn" type="submit" style="width:100%;margin-top:4px;justify-content:center;">
      <?= icon('user') ?> Log in
    </button>
  </form>
</div>

<p class="login-demo">Demo credentials: <strong>officer / officer123</strong> &nbsp;&middot;&nbsp; <strong>supervisor / super123</strong></p>
<?php page_bottom(); ?>
