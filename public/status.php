<?php
// Public status lookup: returns only status + account type. No name, phone or balance.
require_once __DIR__ . '/../src/lib.php';
header('Content-Type: application/json; charset=utf-8');

$ref = trim($_GET['ref'] ?? '');
if ($ref === '' || strlen($ref) > 30 || !preg_match('/^[A-Za-z0-9\-]+$/', $ref)) {
    echo json_encode(['ok' => false, 'msg' => 'Enter a valid CIF or account number.']);
    exit;
}

try {
    $cfg = require __DIR__ . '/../src/config.php';
    $db  = db_connect($cfg);
    $st  = $db->prepare(
        'SELECT a.type, a.status, a.funding_status, a.funding_deadline
           FROM accounts a JOIN customers c ON c.id = a.customer_id
          WHERE c.cif = ? OR a.account_no = ?
          ORDER BY a.id DESC LIMIT 1'
    );
    $st->execute([$ref, $ref]);
    $r = $st->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'msg' => 'Service is not available right now.']);
    exit;
}

if (!$r) {
    echo json_encode(['ok' => false, 'msg' => 'No application found. Check the number with your officer.']);
    exit;
}

$map = [
    'Pending'  => 'Under review. A supervisor has not decided yet.',
    'Active'   => 'Approved. Your account is active.',
    'Rejected' => 'Declined. Ask your officer at the branch.',
    'Closed'   => 'This account is closed.',
];
$msg = $map[$r['status']] ?? $r['status'];
if ($r['status'] === 'Active' && $r['funding_status'] === 'Awaiting funding' && $r['funding_deadline']) {
    $msg .= ' Complete your deposit and documents by ' . $r['funding_deadline'] . '.';
}
if ($r['status'] === 'Active' && $r['funding_status'] === 'Overdue') {
    $msg .= ' Your deadline has passed. Visit the branch.';
}
echo json_encode(['ok' => true, 'status' => $r['status'], 'type' => $r['type'], 'msg' => $msg]);
