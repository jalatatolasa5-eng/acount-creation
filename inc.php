<?php
// Shared setup: session, database (SQLite, nothing to install), helpers and page layout.
require_once __DIR__ . '/lib.php';
session_start();
$db = db_connect(getenv('ACCT_DB') ?: (is_file(__DIR__ . '/use_mysql.txt') ? 'mysql' : __DIR__ . '/data.sqlite'));

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function csrf() { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function csrf_field() { return '<input type="hidden" name="csrf" value="' . e(csrf()) . '">'; }
function check_csrf() { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(400); exit('Invalid form token. Go back and try again.'); } }
function flash($msg, $type = 'ok') { $_SESSION['flash'][] = [$type, $msg]; }
function redirect($url) { header('Location: ' . $url); exit; }
function me() { return $_SESSION['uid'] ?? null; }
function role() { return $_SESSION['role'] ?? ''; }
function log_action($a, $d = '') { global $db; audit($db, me(), $a, $d); }
function require_login(array $roles = []) {
    if (empty($_SESSION['uid'])) redirect('login.php');
    if ($roles && !in_array($_SESSION['role'], $roles, true)) { http_response_code(403); exit('Access denied for your role.'); }
}
function field_err($errors, $k) {
    return isset($errors[$k])
        ? '<div class="err"><svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="2"/><path d="M8 5v3.5M8 11h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>' . e($errors[$k]) . '</div>'
        : '';
}

/* ── SVG icon helper ──────────────────────────────────────── */
function icon($name) {
    $icons = [
        'home'     => '<path d="M3 9.5L8 5l5 4.5V14a1 1 0 01-1 1h-3v-3H9v3H6a1 1 0 01-1-1V9.5z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linejoin="round"/>',
        'plus'     => '<path d="M8 4v8M4 8h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'check'    => '<path d="M4 8.5l3 3 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'accounts' => '<rect x="3" y="5" width="10" height="8" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M6 5V4a2 2 0 014 0v1" stroke="currentColor" stroke-width="1.5" fill="none"/>',
        'tariff'   => '<path d="M4 4h8v1.5L8 9l-4-3.5V4zM8 9v5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/><path d="M5 14h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'audit'    => '<path d="M5 4h6M5 8h6M5 12h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="2" width="10" height="13" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/>',
        'admin'    => '<circle cx="8" cy="8" r="2.5" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M8 2v1.5M8 12.5V14M2 8h1.5M12.5 8H14M3.6 3.6l1.1 1.1M11.3 11.3l1.1 1.1M3.6 12.4l1.1-1.1M11.3 4.7l1.1-1.1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'guide'    => '<path d="M4 3h5l3 3v9H4V3z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linejoin="round"/><path d="M9 3v3h3M6 8h4M6 11h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'logout'   => '<path d="M10 8H4M7 5l-3 3 3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 4h4v8H8" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/>',
        'req'      => '<rect x="3" y="3" width="10" height="11" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M6 7h4M6 10h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'user'     => '<circle cx="8" cy="6" r="2.5" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M3 14c0-2.76 2.24-5 5-5s5 2.24 5 5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/>',
        'calendar' => '<rect x="3" y="4" width="10" height="10" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M6 2v2M10 2v2M3 8h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'bank'     => '<path d="M2 7l6-4 6 4" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="7" width="2" height="5" fill="currentColor" rx=".5"/><rect x="7" y="7" width="2" height="5" fill="currentColor" rx=".5"/><rect x="11" y="7" width="2" height="5" fill="currentColor" rx=".5"/><path d="M2 12h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'warn'     => '<path d="M8 3L2 13h12L8 3z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linejoin="round"/><path d="M8 8v2.5M8 12h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'info'     => '<circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M8 7v4M8 5.5h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'ok-ic'    => '<circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M5.5 8l2 2 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
    ];
    $path = $icons[$name] ?? '';
    return '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">' . $path . '</svg>';
}

/* ── Sidebar nav link helper ─────────────────────────────── */
function nav_link($href, $label, $icon_name, $current_page) {
    $active = (basename($_SERVER['PHP_SELF']) === $href) ? ' active' : '';
    return '<li><a href="' . e($href) . '" class="' . ltrim($active) . '">' . icon($icon_name) . ' ' . e($label) . '</a></li>';
}

/* ── page_top: outputs full HTML head + sidebar + topbar ─── */
function page_top($title) {
    global $db;
    $today  = today($db);
    $is_login = (basename($_SERVER['PHP_SELF']) === 'login.php');

    echo '<!DOCTYPE html><html lang="en"><head>'
       . '<meta charset="utf-8">'
       . '<meta name="viewport" content="width=device-width,initial-scale=1">'
       . '<title>' . e($title) . ' – CBE Savings Back-office</title>'
       . '<link rel="stylesheet" href="style.css">'
       . '</head>';

    /* Login page has its own layout */
    if ($is_login) {
        echo '<body class="login-page">';
        return;
    }

    $user   = $_SESSION['user']   ?? '';
    $r      = $_SESSION['role']   ?? '';
    $cur    = basename($_SERVER['PHP_SELF']);
    $is_sup = ($r === 'supervisor');

    /* ── Sidebar ── */
    echo '<body>';
    echo '<nav class="sidebar" aria-label="Main navigation">';

    /* Logo */
    echo '<a class="sidebar-logo" href="index.php">'
       . '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true">'
       . '<rect width="32" height="32" rx="7" fill="#c8962e"/>'
       . '<path d="M4 21l12-11 12 11" stroke="#fff" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>'
       . '<rect x="7" y="16" width="3" height="7" fill="#fff" rx="1"/>'
       . '<rect x="14.5" y="16" width="3" height="7" fill="#fff" rx="1"/>'
       . '<rect x="22" y="16" width="3" height="7" fill="#fff" rx="1"/>'
       . '<path d="M4 21h24" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/>'
       . '</svg>'
       . '<div><strong style="font-size:13px;font-weight:800;color:#f0c040;display:block">CBE Savings</strong>'
       . '<span>Wayu Branch</span></div></a>';

    /* Public nav */
    echo '<div class="sidebar-section">Public</div><ul class="sidebar-nav">';
    echo nav_link('index.php',    'Home',          'home',    $cur);
    echo nav_link('guide.php',    'Applicant Guide','guide',  $cur);
    echo nav_link('tariff.php',   'Rates &amp; Fees','tariff', $cur);
    echo nav_link('requirements.php', 'Requirements', 'req',  $cur);
    echo '</ul>';

    /* Staff nav (only when logged in) */
    if (!empty($_SESSION['uid'])) {
        echo '<div class="sidebar-section">Staff</div><ul class="sidebar-nav">';
        echo nav_link('dashboard.php', 'Dashboard',    'home',     $cur);
        if (!$is_sup)
            echo nav_link('new.php',   'New Application','plus',   $cur);
        if ($is_sup)
            echo nav_link('approvals.php','Approvals', 'check',    $cur);
        echo nav_link('accounts.php',  'Accounts',    'accounts',  $cur);
        if ($is_sup) {
            echo nav_link('admin.php', 'Month-end',   'admin',     $cur);
            echo nav_link('audit.php', 'Audit Trail', 'audit',     $cur);
        }
        echo '</ul>';
    } else {
        echo '<ul class="sidebar-nav">';
        echo nav_link('login.php', 'Staff Login', 'user', $cur);
        echo '</ul>';
    }

    /* Sidebar footer */
    echo '<div class="sidebar-footer">Demo – not a real system</div>';
    echo '</nav>'; /* end .sidebar */

    /* ── Top bar ── */
    echo '<div class="topbar" role="banner">';
    echo '<div class="topbar-title">Commercial Bank of Ethiopia &mdash; Personal Savings</div>';
    echo '<div class="topbar-meta">';
    echo '<div class="topbar-date">' . icon('calendar') . ' ' . e($today) . '</div>';
    if ($user) {
        $badge_cls = $is_sup ? 'role-badge supervisor' : 'role-badge';
        $role_lbl  = $is_sup ? 'Supervisor' : 'Maker / Officer';
        echo '<div class="topbar-user">'
           . icon('user')
           . '<span class="topbar-user-name">' . e($user) . '</span>'
           . '<span class="' . $badge_cls . '">' . e($role_lbl) . '</span>'
           . '</div>';
        echo '<a href="logout.php" class="topbar-logout">' . icon('logout') . ' Log out</a>';
    }
    echo '</div></div>'; /* end .topbar-meta + .topbar */

    /* ── Page body ── */
    echo '<div class="page-body"><div class="page-content">';

    /* Flash messages */
    if (!empty($_SESSION['flash'])) {
        echo '<div class="flash-wrap">';
        foreach ($_SESSION['flash'] as [$t, $m]) {
            $cls = ($t === 'err') ? 'msg msg-err' : 'msg msg-ok';
            $ic  = ($t === 'err') ? icon('warn') : icon('ok-ic');
            echo '<div class="' . $cls . '" role="status">' . $ic . ' ' . e($m) . '</div>';
        }
        echo '</div>';
        $_SESSION['flash'] = [];
    }
}

function page_bottom() {
    $is_login = (basename($_SERVER['PHP_SELF']) === 'login.php');
    if (!$is_login) {
        echo '</div></div>'; /* end .page-content + .page-body */
        echo '<footer class="page-footer">Figures come from a desk study of CBE\'s published savings terms. '
           . 'Confirm current rates, fees and documents with the branch before relying on them.</footer>';
    }
    echo '</body></html>';
}
