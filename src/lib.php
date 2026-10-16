<?php
/**
 * lib.php — Business rules and database layer.
 *
 * No HTML, no CSS, no JavaScript in this file.
 * Every figure comes from the CBE desk study. Verify with the branch.
 */

// ── Constants (do not change these) ──────────────────────────────────────────
const ACCOUNT_TYPES = [
    'Ordinary savings'    => 7.0,
    'Youth savings'       => 7.125,
    'Educational savings' => 7.125,
    "Women's savings"     => 7.125,
    'Wedding savings'     => 7.5,
    'Holiday savings'     => 7.5,
];
const ID_TYPES = ['National ID', 'Passport', 'Driving licence', 'Kebele ID'];
const MIN_BALANCE              = 100.0;
const FUNDING_DAYS             = 15;
const FREE_WITHDRAWAL_LIMIT    = 500.0;
const MAX_PUBLISHED_WITHDRAWAL = 75000.0;
const MID_WITHDRAWAL_FEE       = 12.0;
const PASSBOOK_FEES            = ['Lost' => 115.0, 'Damaged' => 57.5];
const CARD_FEE_OPTIONS         = [
    '50'  => 'ETB 50 (savings Key Facts Statement)',
    '115' => 'ETB 115 incl. VAT (tariff page)',
];

// ── Formatting ────────────────────────────────────────────────────────────────
function birr(mixed $v): string
{
    return number_format((float) $v, 2);
}

// ── Database connection ───────────────────────────────────────────────────────
function db_connect(?array $cfg = null): PDO
{
    // Load config if not passed in
    if ($cfg === null) {
        $cfg = require __DIR__ . '/config.php';
    }

    $driver = $cfg['driver'] ?? 'sqlite';

    if ($driver === 'mysql') {
        $host    = $cfg['host']    ?? '127.0.0.1';
        $port    = $cfg['port']    ?? 3306;
        $dbname  = $cfg['dbname']  ?? 'cbe_savings';
        $charset = $cfg['charset'] ?? 'utf8mb4';
        $dsn     = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
        $db = new PDO($dsn, $cfg['username'] ?? 'root', $cfg['password'] ?? '');
        $db->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES,   false);
        return $db;
    }

    // SQLite (default)
    $path = $cfg['sqlite_path'] ?? (__DIR__ . '/../database/data.sqlite');
    $db   = new PDO('sqlite:' . $path);
    $db->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    _sqlite_bootstrap($db);
    return $db;
}

function _sqlite_bootstrap(PDO $db): void
{
    $db->exec("
    CREATE TABLE IF NOT EXISTS users(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL CHECK(length(username) BETWEEN 3 AND 50),
        password TEXT NOT NULL,
        role TEXT NOT NULL CHECK(role IN ('officer','supervisor')),
        created_at TEXT NOT NULL DEFAULT(date('now'))
    );
    CREATE TABLE IF NOT EXISTS settings(
        key TEXT PRIMARY KEY NOT NULL,
        value TEXT NOT NULL
    );
    CREATE TABLE IF NOT EXISTS customers(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        cif TEXT UNIQUE NOT NULL,
        first_name TEXT NOT NULL CHECK(length(first_name) BETWEEN 1 AND 60),
        last_name TEXT NOT NULL CHECK(length(last_name) BETWEEN 1 AND 60),
        dob TEXT NOT NULL,
        gender TEXT NOT NULL CHECK(gender IN ('Female','Male')),
        phone TEXT NOT NULL,
        address TEXT NOT NULL,
        id_type TEXT NOT NULL,
        id_number TEXT UNIQUE COLLATE NOCASE NOT NULL CHECK(length(id_number) BETWEEN 4 AND 30),
        guardian_id TEXT,
        created_at TEXT NOT NULL DEFAULT(datetime('now'))
    );
    CREATE TABLE IF NOT EXISTS accounts(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        account_no TEXT UNIQUE,
        customer_id INTEGER NOT NULL,
        type TEXT NOT NULL,
        rate REAL NOT NULL CHECK(rate>0),
        status TEXT NOT NULL DEFAULT 'Pending' CHECK(status IN ('Pending','Active','Rejected','Closed')),
        balance REAL NOT NULL DEFAULT 0 CHECK(balance>=0),
        opening_deposit REAL NOT NULL DEFAULT 0 CHECK(opening_deposit>=0),
        zero_balance INTEGER NOT NULL DEFAULT 0 CHECK(zero_balance IN (0,1)),
        source_of_funds TEXT,
        funding_status TEXT NOT NULL DEFAULT 'Not required'
            CHECK(funding_status IN ('Not required','Awaiting funding','Funded','Overdue','Closed unfunded')),
        funding_deadline TEXT,
        id_verified INTEGER NOT NULL DEFAULT 0 CHECK(id_verified IN (0,1)),
        kfs_given INTEGER NOT NULL DEFAULT 0 CHECK(kfs_given IN (0,1)),
        agreement_signed INTEGER NOT NULL DEFAULT 0 CHECK(agreement_signed IN (0,1)),
        conditions_explained INTEGER NOT NULL DEFAULT 0 CHECK(conditions_explained IN (0,1)),
        signature TEXT NOT NULL DEFAULT '',
        card_fee REAL,
        passbook_replacements INTEGER NOT NULL DEFAULT 0 CHECK(passbook_replacements>=0),
        created_by INTEGER NOT NULL,
        decided_by INTEGER,
        reject_reason TEXT,
        applied_at TEXT NOT NULL DEFAULT(date('now')),
        opened_at TEXT,
        closed_at TEXT,
        close_reason TEXT,
        last_interest_month TEXT
    );
    CREATE TABLE IF NOT EXISTS transactions(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        account_id INTEGER NOT NULL,
        tx_date TEXT NOT NULL,
        kind TEXT NOT NULL,
        amount REAL NOT NULL,
        balance_after REAL NOT NULL CHECK(balance_after>=0),
        note TEXT NOT NULL DEFAULT '',
        user_id INTEGER,
        created_at TEXT NOT NULL DEFAULT(datetime('now'))
    );
    CREATE TABLE IF NOT EXISTS audit_log(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        action TEXT NOT NULL,
        detail TEXT NOT NULL DEFAULT '',
        at TEXT NOT NULL DEFAULT(datetime('now'))
    );
    CREATE INDEX IF NOT EXISTS idx_acct_customer ON accounts(customer_id);
    CREATE INDEX IF NOT EXISTS idx_acct_status   ON accounts(status);
    CREATE INDEX IF NOT EXISTS idx_acct_funding  ON accounts(funding_status);
    CREATE INDEX IF NOT EXISTS idx_acct_opened   ON accounts(opened_at);
    CREATE INDEX IF NOT EXISTS idx_acct_interest ON accounts(last_interest_month);
    CREATE INDEX IF NOT EXISTS idx_tx_acct_date  ON transactions(account_id, tx_date);
    CREATE INDEX IF NOT EXISTS idx_tx_kind       ON transactions(kind);
    CREATE INDEX IF NOT EXISTS idx_cust_phone    ON customers(phone);
    CREATE INDEX IF NOT EXISTS idx_cust_name     ON customers(last_name, first_name);
    CREATE INDEX IF NOT EXISTS idx_audit_at      ON audit_log(at);
    CREATE INDEX IF NOT EXISTS idx_audit_user    ON audit_log(user_id);
    ");

    wf_migrate($db);

    if ((int) $db->query("SELECT COUNT(*) FROM users")->fetchColumn() === 0) {
        $s = $db->prepare("INSERT INTO users(username,password,role) VALUES(?,?,?)");
        $s->execute(['officer',    password_hash('officer123', PASSWORD_DEFAULT), 'officer']);
        $s->execute(['supervisor', password_hash('super123',   PASSWORD_DEFAULT), 'supervisor']);
    }
}

function setting_upsert_sql(PDO $db): string
{
    return $db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
        ? "INSERT INTO settings(`key`,`value`) VALUES('today',?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)"
        : "INSERT INTO settings(key,value) VALUES('today',?) ON CONFLICT(key) DO UPDATE SET value=excluded.value";
}

// ── Transactions ──────────────────────────────────────────────────────────────
function in_tx(PDO $db, callable $f): mixed
{
    $db->beginTransaction();
    try {
        $r = $f();
        $db->commit();
        return $r;
    } catch (Throwable $t) {
        if ($db->inTransaction()) $db->rollBack();
        throw $t;
    }
}

function audit(PDO $db, ?int $uid, string $action, string $detail = ''): void
{
    $db->prepare("INSERT INTO audit_log(user_id,action,detail,at) VALUES(?,?,?,?)")
       ->execute([$uid, $action, $detail, date('Y-m-d H:i:s')]);
}

// ── Date helpers ─────────────────────────────────────────────────────────────
function today(PDO $db): string
{
    $v = $db->query("SELECT value FROM settings WHERE `key`='today'")->fetchColumn();
    return $v ?: date('Y-m-d');
}

function add_days(string $d, int $n): string
{
    return (new DateTime($d))->modify(($n >= 0 ? '+' : '') . "$n days")->format('Y-m-d');
}

function age_on(string $dob, string $on): int
{
    return (new DateTime($dob))->diff(new DateTime($on))->y;
}

function last_business_day(string $ym): string
{
    $d = new DateTime($ym . '-01');
    $d->modify('last day of this month');
    while ((int) $d->format('N') >= 6) $d->modify('-1 day');
    return $d->format('Y-m-d');
}

function is_early_closure(string $opened, string $today): bool
{
    return new DateTime($today) < (new DateTime($opened))->modify('+6 months');
}

function in_tx_safe_date(string $d): bool
{
    return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)
        && checkdate((int) substr($d, 5, 2), (int) substr($d, 8, 2), (int) substr($d, 0, 4));
}

// ── Account queries ───────────────────────────────────────────────────────────
function get_account(PDO $db, int $id): array|false
{
    $s = $db->prepare(
        "SELECT a.*, c.cif, c.first_name, c.last_name, c.dob, c.gender,
                c.phone, c.address, c.id_type, c.id_number, c.guardian_id
           FROM accounts a
           JOIN customers c ON c.id = a.customer_id
          WHERE a.id = ?"
    );
    $s->execute([$id]);
    return $s->fetch();
}

function post_tx(PDO $db, int $acct, string $kind, float $amount,
                 string $note, ?int $uid, ?string $date = null): float
{
    $bal = (float) $db->query("SELECT balance FROM accounts WHERE id=" . (int) $acct)->fetchColumn();
    $new = round($bal + $amount, 2);
    $db->prepare("UPDATE accounts SET balance=? WHERE id=?")->execute([$new, $acct]);
    $db->prepare(
        "INSERT INTO transactions(account_id,tx_date,kind,amount,balance_after,note,user_id,created_at)
         VALUES(?,?,?,?,?,?,?,?)"
    )->execute([$acct, $date ?? today($db), $kind, $amount, $new, $note, $uid, date('Y-m-d H:i:s')]);
    return $new;
}

// ── Withdrawal fee ────────────────────────────────────────────────────────────
function withdrawal_fee(float $amt): array
{
    if ($amt <= 0)                           return [0.0, 'Enter an amount greater than zero.'];
    if ($amt <= FREE_WITHDRAWAL_LIMIT)       return [0.0, null];
    if ($amt <= MAX_PUBLISHED_WITHDRAWAL)    return [MID_WITHDRAWAL_FEE, null];
    return [0.0, 'Withdrawals above ETB 75,000 have no published charge. Refer to the branch manager.'];
}

// ── Teller actions ────────────────────────────────────────────────────────────
function deposit(PDO $db, int $id, float $amt, ?int $uid): array
{
    return in_tx($db, function () use ($db, $id, $amt, $uid) {
        $a = get_account($db, $id);
        if (!$a || $a['status'] !== 'Active') return [false, 'Only active accounts can receive deposits.'];
        if ($amt <= 0) return [false, 'Enter an amount greater than zero.'];
        $new = post_tx($db, $id, 'Deposit', round($amt, 2), 'Cash deposit', $uid);
        $msg = 'Deposited ETB ' . birr($amt) . '. New balance ETB ' . birr($new) . '.';
        if (in_array($a['funding_status'], ['Awaiting funding', 'Overdue'], true) && $new >= MIN_BALANCE) {
            $db->prepare("UPDATE accounts SET funding_status='Funded' WHERE id=?")->execute([$id]);
            $msg .= ' The account now meets the ETB 100 minimum.';
        }
        audit($db, $uid, 'deposit', $a['account_no'] . ' +' . birr($amt));
        return [true, $msg];
    });
}

function withdraw(PDO $db, int $id, float $amt, ?int $uid): array
{
    return in_tx($db, function () use ($db, $id, $amt, $uid) {
        $a = get_account($db, $id);
        if (!$a || $a['status'] !== 'Active') return [false, 'Only active accounts allow withdrawals.'];
        [$fee, $err] = withdrawal_fee($amt);
        if ($err) return [false, $err];
        $after = round((float) $a['balance'] - $amt - $fee, 2);
        if ($after < MIN_BALANCE)
            return [false, 'The account must keep at least ETB 100. After this withdrawal'
                . ($fee ? ' and the ETB 12 fee' : '') . ' the balance would be ETB ' . birr($after) . '.'];
        post_tx($db, $id, 'Withdrawal', -round($amt, 2), 'Branch cash withdrawal', $uid);
        if ($fee > 0) post_tx($db, $id, 'Fee', -$fee, 'Withdrawal fee (501 to 75,000 birr)', $uid);
        audit($db, $uid, 'withdraw',
              $a['account_no'] . ' -' . birr($amt) . ($fee ? ' fee ' . birr($fee) : ''));
        return [true, 'Withdrew ETB ' . birr($amt)
            . ($fee ? ' plus ETB 12.00 fee' : ' (no fee)')
            . '. New balance ETB ' . birr($after) . '.'];
    });
}

function replace_passbook(PDO $db, int $id, string $kind, string $method, ?int $uid): array
{
    return in_tx($db, function () use ($db, $id, $kind, $method, $uid) {
        $a = get_account($db, $id);
        if (!$a || $a['status'] !== 'Active') return [false, 'Only active accounts can have a passbook replaced.'];
        if (!isset(PASSBOOK_FEES[$kind]))      return [false, 'Choose lost or damaged.'];
        if (!in_array($method, ['Cash', 'Account'], true)) return [false, 'Choose how the fee is paid.'];
        $fee = PASSBOOK_FEES[$kind];
        if ($method === 'Account') {
            if ((float) $a['balance'] - $fee < MIN_BALANCE)
                return [false, 'Paying from the account would take it below ETB 100. Collect the fee in cash instead.'];
            post_tx($db, $id, 'Fee', -$fee, $kind . ' passbook replacement', $uid);
        }
        $db->prepare("UPDATE accounts SET passbook_replacements=passbook_replacements+1 WHERE id=?")
           ->execute([$id]);
        audit($db, $uid, 'passbook', $a['account_no'] . " $kind fee " . birr($fee) . " by $method");
        return [true, "Replacement passbook issued. Fee ETB " . birr($fee) . " incl. VAT, paid by " . strtolower($method) . '.'];
    });
}

function close_account(PDO $db, int $id, string $reason, ?int $uid): array
{
    return in_tx($db, function () use ($db, $id, $reason, $uid) {
        $a = get_account($db, $id);
        if (!$a || $a['status'] !== 'Active') return [false, 'Only active accounts can be closed.'];
        if (trim($reason) === '') return [false, 'Enter the reason for closing.'];
        $t      = today($db);
        $early  = is_early_closure($a['opened_at'], $t);
        $payout = (float) $a['balance'];
        if ($payout > 0) post_tx($db, $id, 'Withdrawal', -$payout, 'Closing payout', $uid);
        $db->prepare(
            "UPDATE accounts SET status='Closed', closed_at=?, close_reason=?,
             funding_status=CASE WHEN funding_status IN ('Awaiting funding','Overdue')
             THEN 'Closed unfunded' ELSE funding_status END WHERE id=?"
        )->execute([$t, trim($reason), $id]);
        audit($db, $uid, 'close',
              $a['account_no'] . ' payout ' . birr($payout) . ($early ? ' (before six months, free)' : ''));
        return [true, 'Account closed. Paid out ETB ' . birr($payout) . '. '
            . ($early
                ? 'Closed before six months: no closing charge.'
                : 'Open for more than six months: no closing charge is published; confirm with the branch tariff.')];
    });
}

// ── Interest ──────────────────────────────────────────────────────────────────
function min_balance_in_month(PDO $db, int $acct, string $ym): float
{
    $start  = $ym . '-01';
    $end    = (new DateTime($start))->modify('last day of this month')->format('Y-m-d');
    $opened = (string) $db->query("SELECT opened_at FROM accounts WHERE id=" . (int) $acct)->fetchColumn();
    $c      = [];
    if ($opened < $start) {
        $s = $db->prepare(
            "SELECT balance_after FROM transactions WHERE account_id=? AND tx_date<? ORDER BY id DESC LIMIT 1"
        );
        $s->execute([$acct, $start]);
        $b = $s->fetchColumn();
        if ($b !== false) $c[] = (float) $b;
    }
    $s = $db->prepare(
        "SELECT balance_after FROM transactions WHERE account_id=? AND tx_date>=? AND tx_date<=?"
    );
    $s->execute([$acct, $start, $end]);
    foreach ($s->fetchAll(PDO::FETCH_COLUMN) as $b) $c[] = (float) $b;
    return $c ? min($c) : 0.0;
}

function interest_pending_accounts(PDO $db): array
{
    $t  = today($db);
    $ym = substr($t, 0, 7);
    $s  = $db->prepare(
        "SELECT id FROM accounts
          WHERE status='Active'
            AND opened_at <= ?
            AND (last_interest_month IS NULL OR last_interest_month < ?)"
    );
    $s->execute([$t, $ym]);
    return $s->fetchAll(PDO::FETCH_COLUMN);
}

function run_interest(PDO $db, ?int $uid): array
{
    return in_tx($db, function () use ($db, $uid) {
        $t   = today($db);
        $ym  = substr($t, 0, 7);
        $lbd = last_business_day($ym);
        if ($t < $lbd)
            return [false, "Interest is credited on the last business day. For $ym that is $lbd. Move the demo date to $lbd or later first."];
        $n = 0; $total = 0.0;
        foreach (interest_pending_accounts($db) as $id) {
            $a   = get_account($db, (int) $id);
            $min = min_balance_in_month($db, (int) $id, $ym);
            $int = round($min * ((float) $a['rate'] / 100) / 12, 2);
            if ($int > 0) {
                post_tx($db, (int) $id, 'Interest', $int,
                        "Interest $ym on minimum balance " . birr($min) . " at " . $a['rate'] . '%', $uid);
                $n++; $total += $int;
            }
            $db->prepare("UPDATE accounts SET last_interest_month=? WHERE id=?")->execute([$ym, $id]);
        }
        audit($db, $uid, 'interest_run', "$ym: $n accounts credited, total " . birr($total));
        return [true, "Interest for $ym credited to $n account(s), total ETB " . birr($total) . '.'];
    });
}

function advance_date(PDO $db, string $new, ?int $uid): array
{
    $t = today($db);
    if (!in_tx_safe_date($new))  return [false, 'Enter a valid date.'];
    if ($new < $t)               return [false, 'The demo date can only move forward.'];
    $m = fn($d) => (int) substr($d, 0, 4) * 12 + (int) substr($d, 5, 2);
    if ($m($new) - $m($t) > 1)  return [false, 'Move forward one month at a time.'];
    if ($m($new) > $m($t) && interest_pending_accounts($db))
        return [false, "Run this month's interest before moving into the next month."];
    $db->prepare(setting_upsert_sql($db))->execute([$new]);
    audit($db, $uid, 'set_date', "$t -> $new");
    return [true, "Demo date is now $new."];
}

function check_deadlines(PDO $db, ?int $uid): array
{
    $t = today($db);
    $s = $db->prepare(
        "UPDATE accounts SET funding_status='Overdue'
          WHERE status='Active'
            AND funding_status='Awaiting funding'
            AND funding_deadline < ?
            AND balance < ?"
    );
    $s->execute([$t, MIN_BALANCE]);
    $n = $s->rowCount();
    audit($db, $uid, 'deadline_check', "$n account(s) flagged overdue");
    return [true, $n
        ? "$n account(s) missed the 15-day funding deadline and were flagged for closure review."
        : 'No account has missed its funding deadline.'];
}

// ── Application validation & submission ──────────────────────────────────────
function validate_application(PDO $db, array $in): array
{
    $keys = ['first_name','last_name','dob','gender','id_type','id_number',
             'phone','address','guardian_id','account_type','opening_deposit',
             'source_of_funds','card_fee','signature'];
    $v = [];
    foreach ($keys as $k) $v[$k] = trim((string) ($in[$k] ?? ''));
    foreach (['id_checked','kfs_given','agreement_signed','conditions_explained'] as $k)
        $v[$k] = !empty($in[$k]) ? 1 : 0;

    $e = [];
    $t = today($db);

    if ($v['first_name'] === '')          $e['first_name'] = 'Enter the first name.';
    elseif (strlen($v['first_name']) > 60) $e['first_name'] = 'First name must be 60 characters or fewer.';

    if ($v['last_name'] === '')           $e['last_name'] = 'Enter the last name.';
    elseif (strlen($v['last_name']) > 60)  $e['last_name'] = 'Last name must be 60 characters or fewer.';

    $dobOk = in_tx_safe_date($v['dob']) && $v['dob'] < $t && age_on($v['dob'], $t) <= 120;
    if (!$dobOk) $e['dob'] = 'Enter a valid date of birth (must be in the past, age at most 120).';
    if ($dobOk && age_on($v['dob'], $t) < 18 && $v['guardian_id'] === '')
        $e['guardian_id'] = 'A guardian ID is required for applicants under 18.';

    if (!in_array($v['gender'], ['Female','Male'], true)) $e['gender'] = 'Choose a gender.';

    if (!isset(ACCOUNT_TYPES[$v['account_type']]))
        $e['account_type'] = 'Choose an account type.';
    elseif ($v['account_type'] === "Women's savings" && $v['gender'] !== 'Female')
        $e['account_type'] = "Women's savings is for female customers.";

    if (!in_array($v['id_type'], ID_TYPES, true)) $e['id_type'] = 'Choose an ID type.';

    if (!preg_match('/^[A-Za-z0-9\/\-]{4,30}$/', $v['id_number']))
        $e['id_number'] = 'Enter the ID number (4 to 30 letters, digits, / or -).';

    if (!preg_match('/^(\+251|0)?9\d{8}$/', preg_replace('/\s+/', '', $v['phone'])))
        $e['phone'] = 'Enter a valid Ethiopian mobile number, e.g. 0912345678.';

    if ($v['address'] === '')              $e['address'] = 'Enter the address.';
    elseif (strlen($v['address']) < 3)     $e['address'] = 'Address is too short (at least 3 characters).';
    elseif (strlen($v['address']) > 200)   $e['address'] = 'Address must be 200 characters or fewer.';

    $dep = is_numeric($v['opening_deposit']) ? round((float) $v['opening_deposit'], 2) : null;
    if ($dep === null || $dep < 0)
        $e['opening_deposit'] = 'Enter the opening deposit (0 or more).';
    elseif ($dep < MIN_BALANCE && strlen($v['source_of_funds']) < 5)
        $e['source_of_funds'] = 'A deposit under ETB 100 needs a reasonable source of funds.';

    if (!in_array($v['card_fee'], ['','50','115'], true)) $e['card_fee'] = 'Choose a valid option.';

    $full = $v['first_name'] . ' ' . $v['last_name'];
    if ($v['signature'] === '')
        $e['signature'] = 'The signature specimen is required.';
    elseif (strcasecmp(trim($v['signature']), trim($full)) !== 0)
        $e['signature'] = "The signature specimen must match the applicant's full name ($full).";

    if (!$v['id_checked'])          $e['id_checked']          = 'Identity verification is required.';
    if (!$v['kfs_given'])           $e['kfs_given']           = 'Give the applicant the Key Facts Statement.';
    if (!$v['conditions_explained'])$e['conditions_explained']= 'Explain the 15-day and ETB 100 conditions.';
    if (!$v['agreement_signed'])    $e['agreement_signed']    = 'The agreement must be read, signed and a copy given.';

    if (!isset($e['id_number'])) {
        $s = $db->prepare("SELECT * FROM customers WHERE id_number=?");
        $s->execute([$v['id_number']]);
        if ($c = $s->fetch()) {
            if (strcasecmp($c['first_name'].' '.$c['last_name'], $full) !== 0 || $c['dob'] !== $v['dob'])
                $e['id_number'] = 'This ID is already registered to a different name or date of birth.';
            elseif (isset(ACCOUNT_TYPES[$v['account_type']])) {
                $p = $db->prepare(
                    "SELECT COUNT(*) FROM accounts
                      WHERE customer_id=? AND type=? AND status IN ('Pending','Active')"
                );
                $p->execute([$c['id'], $v['account_type']]);
                if ($p->fetchColumn() > 0)
                    $e['account_type'] = 'This customer already has a pending or active ' . $v['account_type'] . ' account.';
            }
        }
    }

    return [$e, $v];
}

function submit_application(PDO $db, array $in, int $uid): array
{
    [$e, $v] = validate_application($db, $in);
    if ($e) return [false, $e, null];

    return in_tx($db, function () use ($db, $v, $uid) {
        $t = today($db);
        $s = $db->prepare("SELECT * FROM customers WHERE id_number=?");
        $s->execute([$v['id_number']]);
        $c = $s->fetch();

        if (!$c) {
            $n   = (int) $db->query("SELECT COALESCE(MAX(id),0)+1 FROM customers")->fetchColumn();
            $cif = 'CIF' . (100000 + $n);
            $db->prepare(
                "INSERT INTO customers(cif,first_name,last_name,dob,gender,phone,address,
                 id_type,id_number,guardian_id,created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?)"
            )->execute([
                $cif, $v['first_name'], $v['last_name'], $v['dob'], $v['gender'],
                $v['phone'], $v['address'], $v['id_type'], $v['id_number'], $v['guardian_id'], $t,
            ]);
            $cid = (int) $db->lastInsertId();
        } else {
            $cid = (int) $c['id'];
            $cif = $c['cif'];
        }

        $dep = round((float) $v['opening_deposit'], 2);
        $db->prepare(
            "INSERT INTO accounts(customer_id,type,rate,status,opening_deposit,source_of_funds,
             id_verified,kfs_given,agreement_signed,conditions_explained,signature,card_fee,
             created_by,applied_at) VALUES(?,?,?,'Pending',?,?,?,?,?,?,?,?,?,?)"
        )->execute([
            $cid, $v['account_type'], ACCOUNT_TYPES[$v['account_type']],
            $dep, $dep < MIN_BALANCE ? $v['source_of_funds'] : '',
            $v['id_checked'], $v['kfs_given'], $v['agreement_signed'], $v['conditions_explained'],
            $v['signature'], $v['card_fee'] === '' ? null : (float) $v['card_fee'], $uid, $t,
        ]);
        $aid = (int) $db->lastInsertId();
        audit($db, $uid, 'submit', "$cif {$v['account_type']} deposit " . birr($dep));
        wf_status_log($db, $aid, null, 'Pending', $uid, 'Submitted');
        wf_init_documents($db, $aid, $v, $uid);
        return [true, "Application for $cif submitted for supervisor approval.", $aid];
    });
}

function approve_account(PDO $db, int $id, int $uid): array
{
    return in_tx($db, function () use ($db, $id, $uid) {
        $a = get_account($db, $id);
        if (!$a || $a['status'] !== 'Pending')  return [false, 'This application is not waiting for approval.'];
        if ((int) $a['created_by'] === $uid)     return [false, 'Maker-checker rule: you cannot approve your own application.'];
        if (!$a['id_verified'] || !$a['kfs_given'] || !$a['agreement_signed'] || !$a['conditions_explained'])
            return [false, 'Identity verification or agreement steps are incomplete.'];

        if (!empty($a['returned_at']))
            return [false, 'This application was returned for correction and must be resubmitted first.'];
        if ($bad = wf_blocking_documents($db, $id))
            return [false, 'Documents not verified: ' . implode(', ', $bad) . '.'];

        $t = today($db);
        do {
            $no = '1000' . str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
            $c  = $db->prepare("SELECT COUNT(*) FROM accounts WHERE account_no=?");
            $c->execute([$no]);
        } while ($c->fetchColumn() > 0);

        $zero = (float) $a['opening_deposit'] < MIN_BALANCE;
        $db->prepare(
            "UPDATE accounts SET status='Active', account_no=?, opened_at=?, decided_by=?,
             zero_balance=?, funding_status=?, funding_deadline=? WHERE id=?"
        )->execute([
            $no, $t, $uid,
            $zero ? 1 : 0,
            $zero ? 'Awaiting funding' : 'Not required',
            $zero ? add_days($t, FUNDING_DAYS) : null,
            $id,
        ]);
        post_tx($db, $id, 'Opening', (float) $a['opening_deposit'],
                $zero ? 'Zero or low-balance opening' : 'Opening deposit', $uid, $t);
        audit($db, $uid, 'approve', $a['cif'] . ' -> ' . $no);
        $db->prepare("UPDATE accounts SET decided_at=? WHERE id=?")->execute([$t, $id]);
        wf_status_log($db, $id, 'Pending', 'Active', $uid, 'Approved');
        return [true, "Approved. Account $no is open."
            . ($zero ? ' It must reach ETB 100 by ' . add_days($t, FUNDING_DAYS) . '.' : '')];
    });
}

function reject_account(PDO $db, int $id, string $reason, int $uid): array
{
    if (trim($reason) === '') return [false, 'Enter the reason for rejecting.'];
    return in_tx($db, function () use ($db, $id, $reason, $uid) {
        $a = get_account($db, $id);
        if (!$a || $a['status'] !== 'Pending') return [false, 'This application is not waiting for approval.'];
        $db->prepare("UPDATE accounts SET status='Rejected', reject_reason=?, decided_by=? WHERE id=?")
           ->execute([trim($reason), $uid, $id]);
        audit($db, $uid, 'reject', $a['cif'] . ': ' . trim($reason));
        $db->prepare("UPDATE accounts SET decided_at=? WHERE id=?")->execute([today($db), $id]);
        wf_status_log($db, $id, 'Pending', 'Rejected', $uid, trim($reason));
        return [true, 'Application rejected.'];
    });
}

function reset_demo(PDO $db, ?int $uid): void
{
    if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') {
        $db->exec("DELETE FROM transactions; DELETE FROM accounts; DELETE FROM customers;
                   DELETE FROM settings; DELETE FROM audit_log;");
    } else {
        $db->exec("DELETE FROM transactions; DELETE FROM accounts; DELETE FROM customers;
                   DELETE FROM settings; DELETE FROM audit_log;
                   DELETE FROM sqlite_sequence WHERE name IN
                   ('transactions','accounts','customers','audit_log');");
    }
    wf_reset($db);
    audit($db, $uid, 'reset', 'Demo data cleared');
}

require_once __DIR__ . '/workflow.php';
