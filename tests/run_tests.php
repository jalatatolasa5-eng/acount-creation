<?php
// Run: php tests/run_tests.php   (uses a throwaway database, your real data is untouched)
$tmp = sys_get_temp_dir() . '/cbe_test_' . getmypid() . '.sqlite';
@unlink($tmp);
require __DIR__ . '/../lib.php';
$db = db_connect($tmp);
$db->exec("INSERT INTO settings(key,value) VALUES('today','2026-10-12')");
$off = 1; $sup = 2; // seeded user ids
$pass = 0; $fail = 0;
function t($name, $cond) { global $pass, $fail; if ($cond) { $pass++; echo "PASS  $name\n"; } else { $fail++; echo "FAIL  $name\n"; } }

function app(array $o = []) {
    return array_merge(['first_name' => 'Abebe', 'last_name' => 'Kebede', 'dob' => '1995-03-04', 'gender' => 'Male', 'id_type' => 'National ID', 'id_number' => 'ID-1001',
        'phone' => '0912345678', 'address' => 'Addis Ababa', 'account_type' => 'Ordinary savings', 'opening_deposit' => '1000', 'source_of_funds' => '', 'card_fee' => '',
        'signature' => 'Abebe Kebede', 'id_checked' => 1, 'kfs_given' => 1, 'agreement_signed' => 1, 'conditions_explained' => 1], $o);
}

// Application validation
[$ok, $r, $a1] = submit_application($db, app(), $off);
t('valid application is accepted as Pending', $ok && get_account($db, $a1)['status'] === 'Pending');
t('missing identity check is rejected', !submit_application($db, app(['id_number' => 'ID-2', 'id_checked' => 0]), $off)[0]);
t('missing agreement is rejected', !submit_application($db, app(['id_number' => 'ID-3', 'agreement_signed' => 0]), $off)[0]);
t('same ID with another name is rejected', !submit_application($db, app(['first_name' => 'Chala', 'signature' => 'Chala Kebede']), $off)[0]);
t('duplicate pending account of same type is rejected', !submit_application($db, app(), $off)[0]);
t('bad phone is rejected', !submit_application($db, app(['id_number' => 'ID-4', 'phone' => '12345']), $off)[0]);
t('signature must match name', !submit_application($db, app(['id_number' => 'ID-5', 'signature' => 'Someone Else']), $off)[0]);
t('under 100 needs source of funds', !submit_application($db, app(['id_number' => 'ID-6', 'opening_deposit' => '0']), $off)[0]);
t('minor needs guardian', !submit_application($db, app(['id_number' => 'ID-7', 'dob' => '2012-01-01']), $off)[0]);
t("women's savings needs a female customer", !submit_application($db, app(['id_number' => 'ID-8', 'account_type' => "Women's savings"]), $off)[0]);
t('negative deposit is rejected', !submit_application($db, app(['id_number' => 'ID-9', 'opening_deposit' => '-5']), $off)[0]);

// Maker-checker and approval
t('maker cannot approve own application', !approve_account($db, $a1, $off)[0]);
t('reject needs a reason', !reject_account($db, $a1, '', $sup)[0]);
t('supervisor approves', approve_account($db, $a1, $sup)[0]);
$a = get_account($db, $a1);
t('account becomes Active with a number and balance 1000', $a['status'] === 'Active' && strlen($a['account_no']) === 13 && (float)$a['balance'] === 1000.0);
t('normal opening has no funding deadline', $a['funding_status'] === 'Not required' && $a['funding_deadline'] === null);
t('approved application cannot be approved twice', !approve_account($db, $a1, $sup)[0]);

// Withdrawals and fees
t('withdraw 400 is free', withdraw($db, $a1, 400, $off)[0] && (float)get_account($db, $a1)['balance'] === 600.0);
t('withdraw 450 is free (under 500)', withdraw($db, $a1, 450, $off)[0] && (float)get_account($db, $a1)['balance'] === 150.0);
$bal = (float)get_account($db, $a1)['balance'];
t('withdrawal that breaks the 100 minimum is refused', !withdraw($db, $a1, 600, $off)[0] && (float)get_account($db, $a1)['balance'] === $bal);
t('fee tiers: 500 free, 501 costs 12, 75001 refused', withdrawal_fee(500)[0] == 0 && withdrawal_fee(501)[0] == 12 && withdrawal_fee(75000)[0] == 12 && withdrawal_fee(75001)[1] !== null);
t('zero withdrawal refused', !withdraw($db, $a1, 0, $off)[0]);
deposit($db, $a1, 1000, $off);
$before = (float)get_account($db, $a1)['balance'];
withdraw($db, $a1, 600, $off);
t('600 withdrawal posts 600 + 12 fee', abs($before - (float)get_account($db, $a1)['balance'] - 612) < 0.001);
t('deposit of zero refused', !deposit($db, $a1, 0, $off)[0]);

// Passbook
$b = (float)get_account($db, $a1)['balance'];
t('lost passbook paid by account costs 115', replace_passbook($db, $a1, 'Lost', 'Account', $off)[0] && abs($b - 115 - (float)get_account($db, $a1)['balance']) < 0.001);
t('passbook fee from account refused if below minimum', !replace_passbook($db, $a1, 'Damaged', 'Account', $off)[0] || (float)get_account($db, $a1)['balance'] >= 100);

// Zero-balance account and deadline
[$ok, , $z] = submit_application($db, app(['id_number' => 'ID-Z1', 'first_name' => 'Hana', 'last_name' => 'Tesfaye', 'gender' => 'Female', 'signature' => 'Hana Tesfaye', 'opening_deposit' => '0', 'source_of_funds' => 'Salary due on the 25th', 'account_type' => "Women's savings"]), $off);
t('zero-balance application with source of funds is accepted', $ok);
approve_account($db, $z, $sup);
$za = get_account($db, $z);
t('zero-balance account awaits funding with deadline today+15', $za['funding_status'] === 'Awaiting funding' && $za['funding_deadline'] === '2026-10-27');
[$ok, , $z2] = submit_application($db, app(['id_number' => 'ID-Z2', 'first_name' => 'Lemma', 'last_name' => 'Bekele', 'signature' => 'Lemma Bekele', 'opening_deposit' => '50', 'source_of_funds' => 'Payment from a client', 'account_type' => 'Wedding savings']), $off);
approve_account($db, $z2, $sup);
deposit($db, $z2, 60, $off);
t('deposit reaching 100 marks the account Funded', get_account($db, $z2)['funding_status'] === 'Funded');
t('deadline check flags nothing before the deadline', strpos(check_deadlines($db, $sup)[1], 'No account') === 0);

// Date and month end
t('date cannot move backwards', !advance_date($db, '2026-10-01', $sup)[0]);
t('last business day of Oct 2026 is the 30th', last_business_day('2026-10') === '2026-10-30');
t('interest refused before last business day', !run_interest($db, $sup)[0]);
t('cannot enter next month before interest is run', !advance_date($db, '2026-11-02', $sup)[0]);
t('date can move within the month', advance_date($db, '2026-10-28', $sup)[0]);
t('deadline check flags the unfunded account after 27 Oct', strpos(check_deadlines($db, $sup)[1], '1 account') === 0 && get_account($db, $z)['funding_status'] === 'Overdue');
advance_date($db, '2026-10-30', $sup);
$min = min_balance_in_month($db, $a1, '2026-10');
$expect = round($min * 0.07 / 12, 2);
$balBefore = (float)get_account($db, $a1)['balance'];
t('interest runs on the last business day', run_interest($db, $sup)[0]);
t('interest equals min balance x 7% / 12', abs((float)get_account($db, $a1)['balance'] - $balBefore - $expect) < 0.001 && $expect > 0);
$balAfter = (float)get_account($db, $a1)['balance'];
run_interest($db, $sup);
t('second run credits nothing', (float)get_account($db, $a1)['balance'] === $balAfter);
t('zero-balance month earns no interest', (float)get_account($db, $z)['balance'] === 0.0);
t('can enter November after interest', advance_date($db, '2026-11-02', $sup)[0]);
t('cannot skip a whole month', !advance_date($db, '2027-01-05', $sup)[0]);

// Closing
t('closing needs a reason', !close_account($db, $a1, '', $sup)[0]);
$cb = (float)get_account($db, $a1)['balance'];
[$ok, $msg] = close_account($db, $a1, 'Customer request', $sup);
t('early closure is free and pays out the balance', $ok && strpos($msg, 'no closing charge') !== false && (float)get_account($db, $a1)['balance'] === 0.0 && get_account($db, $a1)['status'] === 'Closed');
t('closed account refuses deposits and withdrawals', !deposit($db, $a1, 10, $off)[0] && !withdraw($db, $a1, 10, $off)[0]);
t('six-month rule', is_early_closure('2026-10-12', '2027-04-11') && !is_early_closure('2026-10-12', '2027-04-12'));

echo "\n$pass passed, $fail failed\n";
@unlink($tmp);
exit($fail ? 1 : 0);
