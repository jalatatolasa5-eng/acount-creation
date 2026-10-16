<?php
// Run: php tests/test_workflow.php   (in-memory database, live data untouched)
require_once __DIR__ . '/../src/lib.php';
$db = db_connect(['driver' => 'sqlite', 'sqlite_path' => ':memory:']);
$db->prepare(setting_upsert_sql($db))->execute(['2026-10-12']);   // a Monday
$o = 1; $s = 2;                                                    // seeded officer / supervisor
$pass = 0; $fail = 0;
function ok($c, $n) { global $pass, $fail; if ($c) { $pass++; echo "PASS  $n\n"; } else { $fail++; echo "FAIL  $n\n"; } }
function app(array $x = []): array {
    static $i = 0; $i++;
    $f = $x['first_name'] ?? 'Test'; $l = $x['last_name'] ?? "User$i";
    return array_merge([
        'first_name' => $f, 'last_name' => $l, 'dob' => '1990-01-01', 'gender' => 'Male',
        'id_type' => 'National ID', 'id_number' => 'WF-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
        'phone' => '0912345678', 'address' => 'Wayu, Horo Guduru', 'guardian_id' => '',
        'account_type' => 'Ordinary savings', 'opening_deposit' => '1000', 'source_of_funds' => '',
        'card_fee' => '', 'signature' => "$f $l",
        'id_checked' => 1, 'kfs_given' => 1, 'agreement_signed' => 1, 'conditions_explained' => 1,
    ], $x);
}
function mk($db, $o, $x = []) { return submit_application($db, app($x), $o)[2]; }
function hist($db, $id) { return array_column($db->query("SELECT new_status FROM status_history WHERE account_id=$id ORDER BY id")->fetchAll(), 'new_status'); }
function docs($db, $id) { return $db->query("SELECT doc_type, status FROM application_documents WHERE account_id=$id ORDER BY id")->fetchAll(PDO::FETCH_KEY_PAIR); }

// Migration
$cols = $db->query("PRAGMA table_info(accounts)")->fetchAll(PDO::FETCH_COLUMN, 1);
ok(in_array('returned_at', $cols) && in_array('decided_at', $cols), 'migration adds the new account columns');
ok((int) $db->query("SELECT COUNT(*) FROM sqlite_master WHERE name IN ('status_history','application_documents')")->fetchColumn() === 2, 'migration creates history and document tables');

// Submission writes history and documents
$a1 = mk($db, $o);
ok($a1 !== null, 'application is submitted');
ok(hist($db, $a1) === ['Pending'], 'submission writes a status history row');
ok(docs($db, $a1) === ['ID' => 'Verified', 'Signature' => 'Verified'], 'ID and signature documents are recorded as verified');
$low = mk($db, $o, ['opening_deposit' => '50', 'source_of_funds' => 'salary next week']);
ok((docs($db, $low)['Source of funds'] ?? '') === 'Unchecked', 'low deposit creates a source of funds document');
$minor = mk($db, $o, ['dob' => '2012-01-01', 'guardian_id' => 'GUARD-1234', 'account_type' => 'Youth savings']);
ok((docs($db, $minor)['Guardian document'] ?? '') === 'Unchecked', 'minor creates a guardian document');

// Return and resubmit
ok(!wf_return_application($db, $a1, '', $s)[0], 'return needs a reason');
ok(!wf_return_application($db, $a1, 'blurry ID copy', $o)[0], 'an officer cannot return an application');
ok(wf_return_application($db, $a1, 'blurry ID copy', $s)[0], 'supervisor returns an application');
ok(!wf_return_application($db, $a1, 'again', $s)[0], 'an already returned application cannot be returned twice');
ok(!approve_account($db, $a1, $s)[0], 'a returned application cannot be approved');
ok(!wf_resubmit($db, $a1, ['note' => 'fixed'], $s)[0], 'only the maker can resubmit');
ok(!wf_resubmit($db, $a1, ['note' => ''], $o)[0], 'resubmit needs a note');
ok(!wf_resubmit($db, $a1, ['note' => 'fixed', 'phone' => '123'], $o)[0], 'resubmit validates the phone');
ok(wf_resubmit($db, $a1, ['note' => 'new ID copy attached', 'phone' => '0911223344'], $o)[0], 'maker resubmits with corrections');
ok($db->query("SELECT phone FROM customers WHERE id=(SELECT customer_id FROM accounts WHERE id=$a1)")->fetchColumn() === '0911223344', 'correction is saved');
ok(empty(get_account($db, $a1)['returned_at']), 'resubmit clears the returned flag');
ok(!wf_resubmit($db, $a1, ['note' => 'x'], $o)[0], 'a pending application that is not returned cannot be resubmitted');
ok(approve_account($db, $a1, $s)[0], 'approval works after resubmit');
ok(hist($db, $a1) === ['Pending', 'Needs correction', 'Pending', 'Active'], 'history keeps every status change in order');
ok(get_account($db, $a1)['decided_at'] === today($db), 'approval records the decision date');

// Documents block approval
$a2 = mk($db, $o);
ok(!wf_set_document($db, $a2, 'Passport', 'Verified', $s)[0], 'unknown document is refused');
ok(!wf_set_document($db, $a2, 'ID', 'Maybe', $s)[0], 'unknown document status is refused');
ok(wf_set_document($db, $a2, 'ID', 'Invalid', $s)[0], 'staff mark a document invalid');
ok(!approve_account($db, $a2, $s)[0], 'approval refused with an invalid document');
ok(wf_set_document($db, $a2, 'ID', 'Missing', $o)[0], 'officer can mark a document missing');
ok(!approve_account($db, $a2, $s)[0], 'approval refused with a missing document');
ok(wf_set_document($db, $a2, 'ID', 'Verified', $s)[0], 'staff mark the document verified');
ok(approve_account($db, $a2, $s)[0], 'approval works when all documents are verified');
ok(!wf_set_document($db, $a2, 'ID', 'Invalid', $s)[0], 'documents are locked once the application is decided');

// Rejection records the date and history
$a3 = mk($db, $o);
ok(reject_account($db, $a3, 'incomplete papers', $s)[0], 'rejection works');
ok(get_account($db, $a3)['decided_at'] === today($db), 'rejection records the decision date');
ok(hist($db, $a3) === ['Pending', 'Rejected'], 'rejection is in the history');

// Working days
ok(wf_working_days('2026-10-16', '2026-10-19') === 1, 'Friday to Monday is 1 working day');
ok(wf_working_days('2026-10-17', '2026-10-19') === 1, 'Saturday to Monday is 1 working day');
ok(wf_working_days('2026-10-16', '2026-10-23') === 5, 'Friday to next Friday is 5 working days');
ok(wf_working_days('2026-10-12', '2026-10-12') === 0, 'same day is 0 working days');

// Review monitoring (moves the demo date, so keep it last)
$a4 = mk($db, $o);
$row = array_values(array_filter(wf_pending_list($db), fn($r) => (int) $r['id'] === (int) $a4))[0];
ok($row['waiting_days'] === 0 && !$row['over_limit'], 'new application has waited 0 days');
advance_date($db, '2026-10-19', $s);
$row = array_values(array_filter(wf_pending_list($db), fn($r) => (int) $r['id'] === (int) $a4))[0];
ok($row['waiting_days'] === 5 && !$row['over_limit'], '5 working days is not yet over the limit');
advance_date($db, '2026-10-20', $s);
$row = array_values(array_filter(wf_pending_list($db), fn($r) => (int) $r['id'] === (int) $a4))[0];
ok($row['waiting_days'] === 6 && $row['over_limit'], '6 working days is flagged over the limit');

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
