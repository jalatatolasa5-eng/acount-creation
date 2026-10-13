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
<form class="card" method="post" style="max-width:420px;margin:auto">
  <h2>Staff login</h2>
  <?= csrf_field() ?>
  <label>Username<input name="username" required autofocus autocomplete="username"></label>
  <label>Password<input name="password" type="password" required autocomplete="current-password"></label>
  <button class="btn" type="submit">Log in</button>
  <p class="note">Demo users: officer / officer123 and supervisor / super123</p>
</form>
<?php page_bottom();
