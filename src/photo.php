<?php
// Customer photo helpers. Files live in storage/photos (outside public/) and are served only by public/photo.php.
const PHOTO_MAX_BYTES = 2097152;

function photo_dir(): string {
    $d = dirname(__DIR__) . '/storage/photos';
    if (!is_dir($d)) @mkdir($d, 0775, true);
    $ht = $d . '/.htaccess';
    if (is_dir($d) && !is_file($ht)) @file_put_contents($ht, "Require all denied\n");
    return $d;
}

function photo_type(string $tmp): ?string {
    if (!is_file($tmp)) return null;
    $i = @getimagesize($tmp);
    if ($i === false) return null;
    return [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png'][$i[2]] ?? null;
}

function photo_check($f, bool $required): ?string {
    if (!is_array($f) || ($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE)
        return $required ? 'Attach the customer photo.' : null;
    if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE) return 'The photo is larger than 2 MB.';
    if ($f['error'] !== UPLOAD_ERR_OK) return 'The photo upload failed. Try again.';
    $tmp = (string) ($f['tmp_name'] ?? '');
    if (!is_file($tmp)) return 'The photo upload failed. Try again.';
    if (filesize($tmp) > PHOTO_MAX_BYTES) return 'The photo is larger than 2 MB.';
    if (photo_type($tmp) === null) return 'The photo must be a JPEG or PNG image.';
    return null;
}

function photo_ensure_column(PDO $db): void {
    try { $ok = $db->query('SELECT photo_path FROM customers LIMIT 1') !== false; }
    catch (Throwable $e) { $ok = false; }
    if (!$ok) $db->exec('ALTER TABLE customers ADD COLUMN photo_path VARCHAR(80) NULL');
}

// Saves the upload for the customer of account $aid. Returns false if there is no valid file.
function photo_attach(PDO $db, int $aid, $f): bool {
    if (!is_array($f) || photo_check($f, false) !== null || ($f['error'] ?? 4) !== UPLOAD_ERR_OK) return false;
    photo_ensure_column($db);
    $s = $db->prepare('SELECT c.id, c.photo_path FROM accounts a JOIN customers c ON c.id = a.customer_id WHERE a.id = ?');
    $s->execute([$aid]);
    $r = $s->fetch(PDO::FETCH_ASSOC);
    if (!$r) return false;
    $name = bin2hex(random_bytes(16)) . '.' . photo_type($f['tmp_name']);
    $dest = photo_dir() . '/' . $name;
    $moved = is_uploaded_file($f['tmp_name']) ? move_uploaded_file($f['tmp_name'], $dest) : copy($f['tmp_name'], $dest);
    if (!$moved) return false;
    $db->prepare('UPDATE customers SET photo_path = ? WHERE id = ?')->execute([$name, $r['id']]);
    if (!empty($r['photo_path'])) @unlink(photo_dir() . '/' . basename($r['photo_path']));
    return true;
}
