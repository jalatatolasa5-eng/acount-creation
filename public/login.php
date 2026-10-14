<?php
require_once __DIR__ . '/../src/inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $s = $db->prepare("SELECT * FROM users WHERE username = ?");
    $s->execute([trim($_POST['username'] ?? '')]);
    $u = $s->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['uid']  = (int) $u['id'];
        $_SESSION['user'] = $u['username'];
        $_SESSION['role'] = $u['role'];
        log_action('login');
        redirect('/public/dashboard.php');
    }
    flash('Wrong username or password.', 'err');
    redirect('/public/login.php');
}

page_top('Staff Login');
?>

<div class="login-left">
  <div class="login-brand">
    <div class="login-brand-icon">
      <svg width="40" height="40" viewBox="0 0 40 40" fill="none" aria-hidden="true">
        <path d="M4 27L20 10l16 17" stroke="#1a0533" stroke-width="3" fill="none"
              stroke-linecap="round" stroke-linejoin="round"/>
        <rect x="7"    y="20" width="5"  height="10" fill="#1a0533" rx="1"/>
        <rect x="17.5" y="20" width="5"  height="10" fill="#1a0533" rx="1"/>
        <rect x="28"   y="20" width="5"  height="10" fill="#1a0533" rx="1"/>
        <path d="M4 27h32" stroke="#1a0533" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </div>
    <h1>CBE<br>Savings Account System</h1>
    <p>Secure &nbsp;&middot;&nbsp; Reliable &nbsp;&middot;&nbsp; For a Brighter Future</p>
  </div>
  <div class="login-tagline">
    <span>Secure</span><span>Reliable</span><span>Modern</span>
  </div>
  <div class="login-eth">
    Commercial Bank of Ethiopia<br>
    ንግድ ባንክ ኢትዮጵያ
  </div>
</div>

<div class="login-right">
  <div class="login-form-wrap">
    <h2>Welcome Back</h2>
    <p class="sub">Sign in to access the savings account system.</p>

    <?php foreach (get_flash() as [$type, $msg]): ?>
      <div class="msg <?= $type === 'err' ? 'msg-err' : 'msg-ok' ?>" role="alert" style="margin-bottom:12px;">
        <?= svg_icon($type === 'err' ? 'warn' : 'ok-ic') ?> <?= e($msg) ?>
      </div>
    <?php endforeach; ?>

    <div class="card">
      <form method="post" novalidate>
        <?= csrf_field() ?>
        <label for="username">Username
          <div class="input-wrap">
            <span class="input-icon"><?= svg_icon('user') ?></span>
            <input id="username" name="username" required autofocus
                   autocomplete="username" placeholder="Enter your username">
          </div>
        </label>
        <label for="password">Password
          <div class="input-wrap">
            <span class="input-icon"><?= svg_icon('audit') ?></span>
            <input id="password" name="password" type="password" required
                   autocomplete="current-password" placeholder="Enter your password">
          </div>
        </label>
        <button class="btn gold" type="submit"
                style="width:100%;justify-content:center;margin-top:6px;padding:11px;">
          Sign In &nbsp;→
        </button>
      </form>
    </div>

    <p style="text-align:center;font-size:11px;color:var(--muted);margin-top:12px;">
      For support, contact your system administrator.
    </p>
    <p style="text-align:center;font-size:11px;color:var(--muted);margin-top:4px;">
      Demo: <strong>officer / officer123</strong> &nbsp;&middot;&nbsp; <strong>supervisor / super123</strong>
    </p>
  </div>
</div>

<?php page_bottom(); ?>
