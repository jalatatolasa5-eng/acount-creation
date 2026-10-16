<?php
// Run: php tests/test_kyc.php   (in-memory database, live data untouched)
require_once __DIR__ . '/../src/lib.php';
$db = db_connect(['driver' => 'sqlite', 'sqlite_path' => ':memory:']);
$db->prepare(setting_upsert_sql($db))->execute(['2026-10-12']);
$o = 1; $s = 2; $pass = 0; $fail = 0;
function ok($c, $n) { global $pass, $fail; if ($c) { $pass++; echo "PASS  $n\n"; } else { $fail++; echo "FAIL  $n\n"; } }
function app(array $x = []): array {
    static $i = 0; $i++;
    $f = $x['first_name'] ?? 'Kyc'; $l = $x['last_name'] ?? "Person$i";
    return array_merge([
        'first_name' => $f, 'last_name' => $l, 'dob' => '1990-01-01', 'gender' => 'Male',
        'id_type' => 'National ID', 'id_number' => 'KY-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
        'phone' => '0912345678', 'address' => 'Wayu, Horo Guduru', 'guardian_id' => '',
        'account_type' => 'Ordinary savings', 'opening_deposit' => '1000', 'source_of_funds' => '',
        'card_fee' => '', 'signature' => "$f $l",
        'id_checked' => 1, 'kfs_given' => 1, 'agreement_signed' => 1, 'conditions_explained' => 1,
        'tin' => '0012345678', 'occupation' => 'Teacher', 'monthly_income' => '5000', 'nationality' => 'Ethiopian',
    ], $x);
}
function sub($db, $o, $x = []) { return submit_application($db, app($x), $o); }

[$r, $m, $id] = sub($db, $o);
ok($r, 'application with full KYC profile is accepted');
$c = get_account($db, $id);
ok($c['tin'] === '0012345678' && $c['occupation'] === 'Teacher' && (float) $c['monthly_income'] === 5000.0 && $c['nationality'] === 'Ethiopian', 'KYC fields are stored and returned by get_account');
ok(isset(sub($db, $o, ['occupation' => ''])[1]['occupation']), 'missing occupation is rejected');
ok(isset(sub($db, $o, ['tin' => ''])[1]['tin']), 'adult without TIN is rejected');
ok(isset(sub($db, $o, ['tin' => 'ab'])[1]['tin']), 'malformed TIN is rejected');
ok(sub($db, $o, ['dob' => '2012-01-01', 'guardian_id' => 'GUARD-1234', 'account_type' => 'Youth savings', 'tin' => ''])[0], 'minor without TIN is accepted');
ok(isset(sub($db, $o, ['monthly_income' => '-5'])[1]['monthly_income']), 'negative income is rejected');
ok(isset(sub($db, $o, ['monthly_income' => 'lots'])[1]['monthly_income']), 'non-numeric income is rejected');
ok(sub($db, $o, ['monthly_income' => '0', 'occupation' => 'Student'])[0], 'zero income is accepted (student or dependant)');
ok(isset(sub($db, $o, ['nationality' => 'Martian'])[1]['nationality']), 'unknown nationality is rejected');
ok(isset(sub($db, $o, ['nationality' => 'Other'])[1]['nationality']), 'Ordinary savings refuses non-Ethiopian customers');
$legacy = array_diff_key(app(['dob' => '2012-01-01', 'guardian_id' => 'GUARD-9999']), array_flip(['tin', 'occupation', 'monthly_income', 'nationality']));
ok(isset(submit_application($db, $legacy, $o)[1]['account_type']), 'Ordinary savings refuses a 17-or-younger customer even with a guardian');
ok(approve_account($db, $id, $s)[0], 'KYC application is approved normally');
ok(!approve_account($db, $id, $s)[0], 'second approval is refused');

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
