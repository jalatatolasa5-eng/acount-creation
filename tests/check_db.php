<?php
$cfg = require __DIR__ . '/../src/config.php';
echo 'Driver : ' . $cfg['driver'] . PHP_EOL;

try {
    $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['dbname']};charset={$cfg['charset']}";
    $db  = new PDO($dsn, $cfg['username'], $cfg['password']);
    echo 'MySQL  : connected' . PHP_EOL;
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo 'Tables : ' . implode(', ', $tables) . PHP_EOL;
    foreach (['users','customers','accounts','transactions','audit_log'] as $t) {
        $n = $db->query("SELECT COUNT(*) FROM $t")->fetchColumn();
        echo "  $t: $n row(s)" . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error  : ' . $e->getMessage() . PHP_EOL;
}
