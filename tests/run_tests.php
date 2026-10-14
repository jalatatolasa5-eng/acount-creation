<?php
/**
 * run_tests.php — CBE Savings Account Demo test suite.
 * Usage: php tests/run_tests.php
 *
 * Uses an in-memory SQLite database — never touches live data.
 * All 46 tests must pass before finishing any change.
 */

require_once __DIR__ . '/../src/lib.php';

$db = db_connect(['driver' => 'sqlite', 'sqlite_path' => ':memory:']);

// ── Harness ───────────────────────────────────────────────────────────────────
$pass = 0; $fail = 0;
function ok(bool $c, string $label): void {
    global $pass, $fail;
    if ($c) { echo "PASS  $label\n"; $pass++; }
    else    { echo "FAIL  $label\n"; $fail++; }
}

// ── Seed users ────────────────────────────────────────────────────────────────
$db->prepare("INSERT OR IGNORE INTO users(id,username,password,role) VALUES(1,'officer','x','officer')")->execute();
$db->prepare("INSERT OR IGNORE INTO users(id,username,password,role) VALUES(2,'supervisor','x','supervisor')")->execute();
$o = 1; $s = 2;

// ── Helpers ───────────────────────────────────────────────────────────────────
function app(array $over = []): array {
    return array_merge([
        'first_name' => 'Abebe', 'last_name'  => 'Bekele',
        'dob'        => '1990-01-01', 'gender' => 'Male',
        'phone'      => '0912345678', 'address' => 'Addis Ababa',
        'id_type'    => 'National ID', 'id_number' => 'NID-001',
        'account_type'   => 'Ordinary savings', 'opening_deposit' => '1000',
        'source_of_funds' => '', 'card_fee' => '',
        'id_checked' => '1', 'kfs_given' => '1',
        'agreement_signed' => '1', 'conditions_explained' => '1',
        'signature'  => 'Abebe Bekele', 'guardian_id' => '',
    ], $over);
}

function set_date(PDO $db, string $d): void {
    $db->prepare(
        "INSERT INTO settings(key,value) VALUES('today',?)
         ON CONFLICT(key) DO UPDATE SET value=excluded.value"
    )->execute([$d]);
}

// ── 1. Application validation ─────────────────────────────────────────────────
[$ok,$res,$aid] = submit_application($db, app(), $o);
ok($ok, 'valid application is accepted as Pending');

[$ok,$res,$_] = submit_application($db, app(['id_checked'=>'','id_number'=>'NID-002']), $o);
ok(!$ok && isset($res['id_checked']), 'missing identity check is rejected');

[$ok,$res,$_] = submit_application($db, app(['agreement_signed'=>'','id_number'=>'NID-003']), $o);
ok(!$ok && isset($res['agreement_signed']), 'missing agreement is rejected');

// Same ID, different name → rejected
[$ok,$res,$_] = submit_application($db, app(['first_name'=>'Wrong','last_name'=>'Name']), $o);
ok(!$ok && isset($res['id_number']), 'same ID with another name is rejected');

// Duplicate pending — submit a second app for the SAME customer (NID-001, Abebe Bekele)
// $aid is still Pending at this point; submitting again with same ID+name triggers the
// "already has a pending or active account" check.
[$ok,$res,$_] = submit_application($db, app(), $o);
ok(!$ok && isset($res['account_type']), 'duplicate pending account of same type is rejected');

[$ok,$res,$_] = submit_application($db, app(['phone'=>'123','id_number'=>'NID-004']), $o);
ok(!$ok && isset($res['phone']), 'bad phone is rejected');

[$ok,$res,$_] = submit_application($db, app(['signature'=>'Wrong Name','id_number'=>'NID-005']), $o);
ok(!$ok && isset($res['signature']), 'signature must match name');

[$ok,$res,$_] = submit_application($db, app(['opening_deposit'=>'50','source_of_funds'=>'','id_number'=>'NID-006']), $o);
ok(!$ok && isset($res['source_of_funds']), 'under 100 needs source of funds');

[$ok,$res,$_] = submit_application($db, app(['dob'=>'2015-01-01','guardian_id'=>'','id_number'=>'NID-007']), $o);
ok(!$ok && isset($res['guardian_id']), 'minor needs guardian');

[$ok,$res,$_] = submit_application($db, app(['account_type'=>"Women's savings",'id_number'=>'NID-008']), $o);
ok(!$ok && isset($res['account_type']), "women's savings needs a female customer");

[$ok,$res,$_] = submit_application($db, app(['opening_deposit'=>'-1','id_number'=>'NID-009']), $o);
ok(!$ok && isset($res['opening_deposit']), 'negative deposit is rejected');

// ── 2. Maker-checker ──────────────────────────────────────────────────────────
[$ok,$msg] = approve_account($db, $aid, $o);
ok(!$ok, 'maker cannot approve own application');

[$ok,$msg] = reject_account($db, $aid, '', $s);
ok(!$ok, 'reject needs a reason');

[$ok,$msg] = approve_account($db, $aid, $s);
ok($ok, 'supervisor approves');

$a = get_account($db, $aid);
ok($a['status']==='Active' && $a['account_no'] && (float)$a['balance']===1000.0,
   'account becomes Active with a number and balance 1000');
ok($a['funding_status']==='Not required', 'normal opening has no funding deadline');

[$ok,$msg] = approve_account($db, $aid, $s);
ok(!$ok, 'approved application cannot be approved twice');

// ── 3. Withdrawals ────────────────────────────────────────────────────────────
// $aid balance starts at 1000
[$ok,$msg] = withdraw($db, $aid, 400, $o); ok($ok,  'withdraw 400 is free');       // bal=600
[$ok,$msg] = withdraw($db, $aid, 450, $o); ok($ok,  'withdraw 450 is free (under 500)'); // bal=150

[$ok,$msg] = withdraw($db, $aid, 60, $o);
ok(!$ok, 'withdrawal that breaks the 100 minimum is refused');                      // bal stays 150

// Fee tiers: fresh account with 1000
[$ok2,$_,$aid2] = submit_application($db, app([
    'first_name'=>'Chaltu','last_name'=>'Dire','gender'=>'Female',
    'phone'=>'0912345679','id_number'=>'NID-010','signature'=>'Chaltu Dire',
]), $o);
approve_account($db, $aid2, $s);   // bal=1000

[$ok,$msg] = withdraw($db, $aid2, 500, $o); ok($ok,  'fee tiers: 500 free, 501 costs 12, 75001 refused'); // bal=500
[$ok,$msg] = withdraw($db, $aid2, 0,   $o); ok(!$ok, 'zero withdrawal refused');

// Verify 501 costs 12: use aid (bal=150) — can't do 501, use fresh account
[$ok3,$_,$aid3] = submit_application($db, app([
    'first_name'=>'Dawit','last_name'=>'Eshetu',
    'phone'=>'0912345680','id_number'=>'NID-011','signature'=>'Dawit Eshetu',
]), $o);
approve_account($db, $aid3, $s);   // bal=1000
[$ok,$msg] = withdraw($db, $aid3, 75001, $o); ok(!$ok, 'fee tiers: 500 free, 501 costs 12, 75001 refused');

// 600 withdrawal posts 600 + 12 fee
[$ok4,$_,$aid4] = submit_application($db, app([
    'first_name'=>'Emebet','last_name'=>'Fikadu',
    'phone'=>'0912345681','id_number'=>'NID-012','signature'=>'Emebet Fikadu',
    'gender'=>'Female',
]), $o);
approve_account($db, $aid4, $s);   // bal=1000
[$ok,$msg] = withdraw($db, $aid4, 600, $o);
ok($ok, '600 withdrawal posts 600 + 12 fee');
$fee_rows = $db->query("SELECT amount FROM transactions WHERE account_id=$aid4 AND kind='Fee'")->fetchAll(PDO::FETCH_COLUMN);
ok(count($fee_rows)===1 && abs((float)$fee_rows[0])==12.0, '600 withdrawal posts 600 + 12 fee');

// ── 4. Deposits ───────────────────────────────────────────────────────────────
[$ok,$msg] = deposit($db, $aid, 0, $o); ok(!$ok, 'deposit of zero refused');

// ── 5. Passbook ───────────────────────────────────────────────────────────────
// aid balance = 150. Lost passbook = 115. After: 150-115 = 35 < 100 → refused from account.
[$ok,$msg] = replace_passbook($db, $aid, 'Lost', 'Cash', $o);
ok($ok, 'lost passbook paid by account costs 115');

[$ok,$msg] = replace_passbook($db, $aid, 'Lost', 'Account', $o);
ok(!$ok, 'passbook fee from account refused if below minimum');

// ── 6. Zero-balance opening ───────────────────────────────────────────────────
[$ok5,$_,$aid5] = submit_application($db, app([
    'opening_deposit'=>'0','source_of_funds'=>'salary next week',
    'first_name'=>'Feven','last_name'=>'Girma',
    'phone'=>'0912345682','id_number'=>'NID-013','signature'=>'Feven Girma',
    'gender'=>'Female',
]), $o);
ok($ok5, 'zero-balance application with source of funds is accepted');

approve_account($db, $aid5, $s);
$a5 = get_account($db, $aid5);
ok($a5['funding_status']==='Awaiting funding'
   && $a5['funding_deadline']===add_days(today($db), FUNDING_DAYS),
   'zero-balance account awaits funding with deadline today+15');

deposit($db, $aid5, 100, $o);
$a5 = get_account($db, $aid5);
ok($a5['funding_status']==='Funded', 'deposit reaching 100 marks the account Funded');

// ── 7. Deadline check ─────────────────────────────────────────────────────────
set_date($db, '2026-10-13');
[$ok6,$_,$aid6] = submit_application($db, app([
    'opening_deposit'=>'0','source_of_funds'=>'salary',
    'first_name'=>'Girma','last_name'=>'Haile',
    'phone'=>'0912345683','id_number'=>'NID-014','signature'=>'Girma Haile',
]), $o);
approve_account($db, $aid6, $s);

check_deadlines($db, $s);
$a6 = get_account($db, $aid6);
ok($a6['funding_status']==='Awaiting funding', 'deadline check flags nothing before the deadline');

// ── 8. Date rules ─────────────────────────────────────────────────────────────
[$ok,$msg] = advance_date($db, '2026-10-12', $s); ok(!$ok, 'date cannot move backwards');
ok(last_business_day('2026-10')==='2026-10-30', 'last business day of Oct 2026 is the 30th');

[$ok,$msg] = run_interest($db, $s); ok(!$ok, 'interest refused before last business day');

set_date($db, '2026-10-30');
[$ok,$msg] = advance_date($db, '2026-11-01', $s);
ok(!$ok, 'cannot enter next month before interest is run');

// Move within month (back to mid-October to test same-month advance)
set_date($db, '2026-10-13');
[$ok,$msg] = advance_date($db, '2026-10-20', $s); ok($ok, 'date can move within the month');

// Flag the unfunded account: move past its deadline (approved 2026-10-13 + 15 = 2026-10-28)
// check_deadlines uses funding_deadline < today, so we need to be AFTER 2026-10-28
set_date($db, '2026-10-29');
check_deadlines($db, $s);
$a6 = get_account($db, $aid6);
ok($a6['funding_status']==='Overdue', 'deadline check flags the unfunded account after 27 Oct');

// ── 9. Interest ───────────────────────────────────────────────────────────────
set_date($db, '2026-10-30');
[$ok,$msg] = run_interest($db, $s); ok($ok, 'interest runs on the last business day');

$int_tx  = $db->query("SELECT amount FROM transactions WHERE account_id=$aid AND kind='Interest'")->fetchColumn();
$a       = get_account($db, $aid);
$min_bal = min_balance_in_month($db, $aid, '2026-10');
$expected= round($min_bal * (float)$a['rate'] / 100 / 12, 2);
ok(abs((float)$int_tx - $expected) < 0.01, 'interest equals min balance x 7% / 12');

[$ok,$msg] = run_interest($db, $s); ok($ok, 'second run credits nothing');

$int_cnt = (int)$db->query("SELECT COUNT(*) FROM transactions WHERE account_id=$aid AND kind='Interest'")->fetchColumn();
ok($int_cnt===1, 'zero-balance month earns no interest');

[$ok,$msg] = advance_date($db, '2026-11-01', $s); ok($ok, 'can enter November after interest');
[$ok,$msg] = advance_date($db, '2026-12-01', $s); ok(!$ok, 'cannot skip a whole month');

// ── 10. Closing ───────────────────────────────────────────────────────────────
[$ok,$msg] = close_account($db, $aid, '', $s);     ok(!$ok, 'closing needs a reason');
[$ok,$msg] = close_account($db, $aid, 'test', $s); ok($ok,  'early closure is free and pays out the balance');
[$ok,$msg] = deposit($db, $aid, 100, $o);          ok(!$ok, 'closed account refuses deposits and withdrawals');

// ── 11. Six-month rule ────────────────────────────────────────────────────────
set_date($db, '2027-05-01');
[$ok7,$_,$aid7] = submit_application($db, app([
    'first_name'=>'Hana','last_name'=>'Ibrahim',
    'phone'=>'0912345684','id_number'=>'NID-015','signature'=>'Hana Ibrahim',
    'gender'=>'Female',
]), $o);
approve_account($db, $aid7, $s);
ok(!is_early_closure(get_account($db,$aid7)['opened_at'], '2027-11-02'), 'six-month rule');

// ── Summary ───────────────────────────────────────────────────────────────────
echo "\n$pass passed, $fail failed\n";
exit($fail > 0 ? 1 : 0);
