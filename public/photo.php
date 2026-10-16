<?php
// Serves a customer photo to a logged-in officer or supervisor only.
require_once __DIR__ . '/../src/inc.php';
require_once __DIR__ . '/../src/photo.php';
require_login(['officer', 'supervisor']);

photo_ensure_column($db);
$s = $db->prepare('SELECT c.photo_path FROM accounts a JOIN customers c ON c.id = a.customer_id WHERE a.id = ?');
$s->execute([(int) ($_GET['id'] ?? 0)]);
$p = basename((string) ($s->fetchColumn() ?: ''));
$file = photo_dir() . '/' . $p;
if ($p === '' || !is_file($file)) { http_response_code(404); exit('No photo.'); }

header('Content-Type: ' . (strtolower(pathinfo($p, PATHINFO_EXTENSION)) === 'png' ? 'image/png' : 'image/jpeg'));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, max-age=300');
header('Content-Length: ' . filesize($file));
readfile($file);
