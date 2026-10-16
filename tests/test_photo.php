<?php
require_once __DIR__ . '/../src/photo.php';
$pass = 0; $fail = 0;
function ok($c, $m) { global $pass, $fail; if ($c) { $pass++; echo "  ok   $m\n"; } else { $fail++; echo "  FAIL $m\n"; } }
function png() {
    $ch = fn($t, $d) => pack('N', strlen($d)) . $t . $d . pack('N', crc32($t . $d));
    return "\x89PNG\r\n\x1a\n" . $ch('IHDR', pack('NNCCCCC', 1, 1, 8, 2, 0, 0, 0)) . $ch('IDAT', gzcompress("\x00\xff\x00\x00")) . $ch('IEND', '');
}
function up($bytes, $err = UPLOAD_ERR_OK) {
    $t = tempnam(sys_get_temp_dir(), 'ph'); file_put_contents($t, $bytes);
    return ['name' => 'x.png', 'tmp_name' => $t, 'error' => $err, 'size' => strlen($bytes)];
}
ok(photo_check(null, true) !== null, 'required photo missing is rejected');
ok(photo_check(['error' => UPLOAD_ERR_NO_FILE], false) === null, 'optional photo may be absent');
ok(photo_check(up(png()), true) === null, 'a valid PNG is accepted');
ok(photo_check(up("just some text, renamed x.png"), true) !== null, 'a text file named .png is rejected');
ok(photo_check(up(png() . str_repeat('0', 2100000)), true) !== null, 'a file over 2 MB is rejected');
ok(photo_check(up(png(), UPLOAD_ERR_PARTIAL), true) !== null, 'a failed upload is rejected');
ok(photo_check(['error' => UPLOAD_ERR_OK, 'tmp_name' => '/no/such/file'], true) !== null, 'a missing temp file is rejected');
echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
