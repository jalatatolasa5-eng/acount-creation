<?php
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
        ? '<div class="err">' . svg_icon('warn-sm') . ' ' . e($errors[$k]) . '</div>'
        : '';
}

function svg_icon($name) {
    $p = [
        'home'     => '<path d="M3 9.5L8 5l5 4.5V14a1 1 0 01-1 1h-3v-3H9v3H6a1 1 0 01-1-1V9.5z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linejoin="round"/>',
        'plus'     => '<path d="M8 4v8M4 8h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'check'    => '<path d="M3 8.5l3.5 3.5 6.5-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'accounts' => '<path d="M10 8a3 3 0 100-6 3 3 0 000 0M4 14s-1 0-1-1 1-4 7-4 7 3 7 4-1 1-1 1H4z" stroke="currentColor" stroke-width="1.5" fill="none"/>',
        'tariff'   => '<path d="M12 2H4a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1V3a1 1 0 00-1-1zM6 6h4M6 9h4M6 12h2" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/>',
        'audit'    => '<path d="M5 4h6M5 7.5h6M5 11h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="2" width="10" height="13" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/>',
        'admin'    => '<circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M8 1v2M8 13v2M1 8h2M13 8h2M3.1 3.1l1.4 1.4M11.5 11.5l1.4 1.4M3.1 12.9l1.4-1.4M11.5 4.5l1.4-1.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'guide'    => '<path d="M4 2h8a1 1 0 011 1v11a1 1 0 01-1 1H4a1 1 0 01-1-1V3a1 1 0 011-1zM9 2v3l-1-.5L7 5V2" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linejoin="round"/><path d="M5 8h6M5 11h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'logout'   => '<path d="M11 8H5M8 5l-3 3 3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 4h3a1 1 0 011 1v6a1 1 0 01-1 1H9" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/>',
        'req'      => '<rect x="2" y="2" width="12" height="13" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M5 6h6M5 9h6M5 12h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'user'     => '<circle cx="8" cy="5.5" r="2.5" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M2.5 14c0-3 2.5-5 5.5-5s5.5 2 5.5 5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/>',
        'calendar' => '<rect x="2" y="3" width="12" height="11" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M5 1v2M11 1v2M2 7h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'warn'     => '<path d="M8 3L2 13h12L8 3z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linejoin="round"/><path d="M8 7.5v2.5M8 12h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'warn-sm'  => '<path d="M7 2.5L1.5 12h11L7 2.5z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linejoin="round"/><path d="M7 6.5v2.5M7 11h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'info'     => '<circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M8 7v4M8 5.5h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'ok-ic'    => '<circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M5.5 8l2 2 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
        'bank'     => '<path d="M2 7.5L8 3l6 4.5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/><rect x="3.5" y="7.5" width="2" height="5" fill="currentColor" rx=".5"/><rect x="7" y="7.5" width="2" height="5" fill="currentColor" rx=".5"/><rect x="10.5" y="7.5" width="2" height="5" fill="currentColor" rx=".5"/><path d="M2 12.5h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
        'print'    => '<rect x="3" y="6" width="10" height="7" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M5 6V3h6v3M5 13v-3h6v3" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/>',
        'search'   => '<circle cx="7" cy="7" r="4.5" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M10.5 10.5l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
    ];
    $d = $p[$name] ?? '';
    return '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">' . $d . '</svg>';
}

function nav_link($href, $label, $icon, $cur, $badge = 0) {
    $active = basename($_SERVER['PHP_SELF']) === $href ? ' active' : '';
    $bdg = $badge ? '<span class="nav-badge">' . (int)$badge . '</span>' : '';
    return '<li><a href="' . e($href) . '" class="' . ltrim($active) . '">' . svg_icon($icon) . ' ' . e($label) . $bdg . '</a></li>';
}

function page_top($title) {
    global $db;
    $today   = today($db);
    $is_login = basename($_SERVER['PHP_SELF']) === 'login.php';

    echo '<!DOCTYPE html><html lang="en"><head>'
       . '<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
       . '<title>' . e($title) . ' – CBE Savings Account System</title>'
       . '<link rel="stylesheet" href="style.css">'
       . '</head>';

    if ($is_login) { echo '<body class="login-body">'; return; }

    $user   = $_SESSION['user'] ?? '';
    $r      = $_SESSION['role'] ?? '';
    $is_sup = ($r === 'supervisor');
    $initials = $user ? strtoupper(substr($user, 0, 2)) : 'CB';

    // pending count for badge
    $pending = 0;
    if ($is_sup && !empty($_SESSION['uid'])) {
        try { $pending = (int)$db->query("SELECT COUNT(*) FROM accounts WHERE status='Pending'")->fetchColumn(); } catch(Exception $e) {}
    }

    echo '<body>';

    /* ── Sidebar ─────────────────────────────────────────── */
    echo '<nav class="sidebar" aria-label="Main navigation">'
       . '<a class="sidebar-logo" href="index.php">'
       . '<div class="sidebar-logo-icon">'
       . '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">'
       . '<path d="M2 13l8-8 8 8" stroke="#1a0533" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>'
       . '<rect x="4" y="10" width="2.5" height="6" fill="#1a0533" rx=".5"/>'
       . '<rect x="8.75" y="10" width="2.5" height="6" fill="#1a0533" rx=".5"/>'
       . '<rect x="13.5" y="10" width="2.5" height="6" fill="#1a0533" rx=".5"/>'
       . '</svg></div>'
       . '<div class="sidebar-logo-text"><strong>CBE</strong><span>Savings Account System</span></div>'
       . '</a>';

    echo '<ul class="sidebar-nav">';
    echo nav_link('index.php', 'Dashboard', 'home', basename($_SERVER['PHP_SELF']));
    if (!empty($_SESSION['uid'])) {
        if (!$is_sup)
            echo nav_link('new.php', 'New Application', 'plus', basename($_SERVER['PHP_SELF']));
        echo nav_link('approvals.php', 'Approvals', 'check', basename($_SERVER['PHP_SELF']), $pending);
        echo nav_link('accounts.php', 'Accounts', 'accounts', basename($_SERVER['PHP_SELF']));
        echo nav_link('tariff.php', 'Tariff', 'tariff', basename($_SERVER['PHP_SELF']));
        if ($is_sup) {
            echo nav_link('audit.php', 'Audit', 'audit', basename($_SERVER['PHP_SELF']));
            echo nav_link('admin.php', 'Admin', 'admin', basename($_SERVER['PHP_SELF']));
        }
    } else {
        echo nav_link('guide.php', 'Guide', 'guide', basename($_SERVER['PHP_SELF']));
        echo nav_link('tariff.php', 'Tariff', 'tariff', basename($_SERVER['PHP_SELF']));
        echo nav_link('requirements.php', 'Requirements', 'req', basename($_SERVER['PHP_SELF']));
    }
    echo '</ul>';

    if (!empty($_SESSION['uid'])) {
        echo '<div class="sidebar-footer">'
           . '<div style="font-size:11px;color:var(--sb-muted);margin-bottom:4px;">Signed in as</div>'
           . '<div style="color:var(--sb-ink);font-weight:700;font-size:12px;">' . e($user) . '</div>'
           . '</div>';
    }
    echo '</nav>';

    /* ── Top bar ─────────────────────────────────────────── */
    echo '<div class="topbar" role="banner">'
       . '<div class="topbar-title">CBE Savings Account System</div>'
       . '<div class="topbar-right">'
       . '<div class="topbar-date">' . svg_icon('calendar') . ' Business Date: ' . e($today) . '</div>';

    if ($user) {
        $role_lbl = $is_sup ? 'Supervisor' : 'Maker';
        $pill_cls = $is_sup ? 'role-pill supervisor' : 'role-pill';
        echo '<div class="topbar-user">'
           . '<div class="user-avatar">' . e($initials) . '</div>'
           . '<div class="topbar-user-info">'
           . '<div class="topbar-user-name">' . e($user) . '</div>'
           . '<div class="topbar-user-role">' . e($role_lbl) . '</div>'
           . '</div>'
           . '<span class="' . $pill_cls . '">' . e($role_lbl) . '</span>'
           . '</div>'
           . '<a href="logout.php" class="topbar-logout">' . svg_icon('logout') . ' Logout</a>';
    }
    echo '</div></div>'; // .topbar-right + .topbar

    /* ── Page body ───────────────────────────────────────── */
    echo '<div class="page-body"><div class="page-content">';

    // Flash
    if (!empty($_SESSION['flash'])) {
        echo '<div class="flash-wrap">';
        foreach ($_SESSION['flash'] as [$t, $m]) {
            $cls = $t === 'err' ? 'msg msg-err' : 'msg msg-ok';
            $ic  = $t === 'err' ? svg_icon('warn') : svg_icon('ok-ic');
            echo '<div class="' . $cls . '" role="status">' . $ic . ' ' . e($m) . '</div>';
        }
        echo '</div>';
        $_SESSION['flash'] = [];
    }
}

function page_bottom() {
    $is_login = basename($_SERVER['PHP_SELF']) === 'login.php';
    if (!$is_login) {
        echo '</div></div>'; // .page-content .page-body
        echo '<footer class="page-footer">Figures from a desk study of CBE\'s published savings terms. Confirm current rates, fees and documents with the branch.</footer>';
    }
    echo '</body></html>';
}
