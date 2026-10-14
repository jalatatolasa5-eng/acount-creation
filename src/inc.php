<?php
/**
 * inc.php — Bootstrap file.
 *
 * Loaded by every public page. Sets up:
 *   - Autoloaded business logic (lib.php)
 *   - Database connection (via config.php)
 *   - Session helpers
 *   - CSRF helpers
 *   - Flash message helpers
 *   - Auth helpers
 *   - Page layout renderer (delegates to src/views/)
 *
 * No HTML, CSS or JavaScript lives in this file.
 */

require_once __DIR__ . '/lib.php';

session_start();

// ── Database ──────────────────────────────────────────────────────────────────
$cfg = require __DIR__ . '/config.php';
$db  = db_connect($cfg);

// ── Output helpers ────────────────────────────────────────────────────────────
function e(mixed $v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

// ── CSRF ──────────────────────────────────────────────────────────────────────
function csrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf()) . '">';
}

function check_csrf(): void
{
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid form token. Go back and try again.');
    }
}

// ── Flash messages ────────────────────────────────────────────────────────────
function flash(string $msg, string $type = 'ok'): void
{
    $_SESSION['flash'][] = [$type, $msg];
}

function get_flash(): array
{
    $f = $_SESSION['flash'] ?? [];
    $_SESSION['flash'] = [];
    return $f;
}

// ── Routing helpers ───────────────────────────────────────────────────────────
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

// ── Auth helpers ──────────────────────────────────────────────────────────────
function me(): ?int
{
    return isset($_SESSION['uid']) ? (int) $_SESSION['uid'] : null;
}

function role(): string
{
    return $_SESSION['role'] ?? '';
}

function require_login(array $roles = []): void
{
    if (empty($_SESSION['uid'])) redirect(url('login.php'));
    if ($roles && !in_array($_SESSION['role'], $roles, true)) {
        http_response_code(403);
        exit('Access denied for your role.');
    }
}

function log_action(string $a, string $d = ''): void
{
    global $db;
    audit($db, me(), $a, $d);
}

// ── Form helpers ──────────────────────────────────────────────────────────────
function field_err(array $errors, string $k): string
{
    if (!isset($errors[$k])) return '';
    return '<div class="err">'
        . svg_icon('warn-sm')
        . ' ' . e($errors[$k])
        . '</div>';
}

// ── URL helper ────────────────────────────────────────────────────────────────
// Always use url('path') instead of hardcoding /public/... links.
function url(string $path = ''): string {
    global $cfg;
    $base = rtrim($cfg['base_url'] ?? '', '/');
    $path = $path === '' ? '' : '/' . ltrim($path, '/');
    return $base . $path;
}
/**
 * Renders the full page shell: <head>, sidebar, topbar, flash messages.
 * Each view partial lives in src/views/layout/.
 */
function page_top(string $title): void
{
    global $db;
    $is_login = basename($_SERVER['PHP_SELF']) === 'login.php';
    $data = [
        'title'    => $title,
        'today'    => today($db),
        'user'     => $_SESSION['user']  ?? '',
        'role'     => $_SESSION['role']  ?? '',
        'is_login' => $is_login,
        'flash'    => get_flash(),
        'cur_page' => basename($_SERVER['PHP_SELF']),
        'pending'  => 0,
    ];

    // Pending count for supervisor badge
    if (!$is_login && !empty($_SESSION['uid']) && $data['role'] === 'supervisor') {
        try {
            $data['pending'] = (int) $db->query(
                "SELECT COUNT(*) FROM accounts WHERE status='Pending'"
            )->fetchColumn();
        } catch (Exception) {}
    }

    require __DIR__ . '/views/layout/head.php';

    if ($is_login) return;

    require __DIR__ . '/views/layout/sidebar.php';
    require __DIR__ . '/views/layout/topbar.php';

    echo '<div class="page-body"><div class="page-content">';

    if ($data['flash']) {
        require __DIR__ . '/views/partials/flash.php';
    }
}

function page_bottom(): void
{
    $is_login = basename($_SERVER['PHP_SELF']) === 'login.php';
    if (!$is_login) {
        echo '</div></div>'; // .page-content .page-body
        require __DIR__ . '/views/layout/footer.php';
    }
    echo '</body></html>';
}

// ── SVG icon library ─────────────────────────────────────────────────────────
// Icons are defined here as pure SVG strings — no CSS classes, no HTML wrappers.
function svg_icon(string $name): string
{
    static $icons = [
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
    $path = $icons[$name] ?? '';
    return '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">' . $path . '</svg>';
}
