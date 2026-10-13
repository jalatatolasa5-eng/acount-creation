<?php
// Shared setup: session, database (SQLite, nothing to install), helpers and page layout.
require_once __DIR__ . '/lib.php';
session_start();
$db = db_connect(getenv('ACCT_DB') ?: __DIR__ . '/data.sqlite');

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
function field_err($errors, $k) { return isset($errors[$k]) ? '<div class="err">' . e($errors[$k]) . '</div>' : ''; }

function page_top($title) {
    global $db;
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
       . '<title>' . e($title) . ' - CBE Savings Account Demo</title><link rel="stylesheet" href="style.css"></head><body>'
       . '<header><div class="wrap"><b>CBE Personal Savings Account</b><span>Student demo, not a real bank system &middot; demo date ' . e(today($db)) . '</span></div></header>'
       . '<nav><div class="wrap"><a href="index.php">Home</a><a href="guide.php">Applicant guide</a><a href="tariff.php">Rates &amp; fees</a><a href="requirements.php">Requirements &amp; use cases</a><span class="sp"></span>';
    if (!empty($_SESSION['uid'])) {
        echo '<a href="dashboard.php">Dashboard</a>';
        if (role() === 'officer') echo '<a href="new.php">New application</a>';
        if (role() === 'supervisor') echo '<a href="approvals.php">Approvals</a><a href="admin.php">Month-end</a><a href="audit.php">Audit</a>';
        echo '<a href="accounts.php">Accounts</a><a href="logout.php">Log out (' . e($_SESSION['user']) . ')</a>';
    } else echo '<a href="login.php">Staff login</a>';
    echo '</div></nav><main class="wrap">';
    foreach ($_SESSION['flash'] ?? [] as [$t, $m]) echo '<p class="msg ' . ($t === 'err' ? 'msg-err' : 'msg-ok') . '" role="status">' . e($m) . '</p>';
    $_SESSION['flash'] = [];
}
function page_bottom() { echo '</main><footer class="wrap">Figures come from a desk study of CBE\'s published savings terms. Confirm current rates, fees and documents with the branch before relying on them.</footer></body></html>'; }
