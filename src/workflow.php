<?php
/** workflow.php — correction workflow, status history, document checks, review monitoring. */
const WF_REVIEW_LIMIT_DAYS = 5;
const WF_DOC_TYPES    = ['ID', 'Signature', 'Source of funds', 'Guardian document'];
const WF_DOC_STATUSES = ['Unchecked', 'Verified', 'Missing', 'Invalid'];

function wf_migrate(PDO $db): void
{
    if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') { wf_migrate_mysql($db); return; }
    $have = $db->query("PRAGMA table_info(accounts)")->fetchAll(PDO::FETCH_COLUMN, 1);
    foreach (['decided_at TEXT', 'returned_reason TEXT', 'returned_by INTEGER', 'returned_at TEXT'] as $def) {
        if (!in_array(explode(' ', $def)[0], $have, true)) $db->exec("ALTER TABLE accounts ADD COLUMN $def");
    }
    $db->exec("
    CREATE TABLE IF NOT EXISTS status_history(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        account_id INTEGER NOT NULL,
        old_status TEXT,
        new_status TEXT NOT NULL,
        changed_by INTEGER,
        changed_at TEXT NOT NULL,
        note TEXT NOT NULL DEFAULT ''
    );
    CREATE INDEX IF NOT EXISTS idx_sh_account ON status_history(account_id);
    CREATE TABLE IF NOT EXISTS application_documents(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        account_id INTEGER NOT NULL,
        doc_type TEXT NOT NULL,
        status TEXT NOT NULL DEFAULT 'Unchecked' CHECK(status IN ('Unchecked','Verified','Missing','Invalid')),
        checked_by INTEGER,
        checked_at TEXT,
        UNIQUE(account_id, doc_type)
    );");
}

function wf_migrate_mysql(PDO $db): void
{
    $q = $db->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='accounts'");
    $have = array_map('strtolower', $q->fetchAll(PDO::FETCH_COLUMN));
    $add = ['decided_at' => 'DATE NULL', 'returned_reason' => 'TEXT NULL', 'returned_by' => 'INT NULL', 'returned_at' => 'DATE NULL'];
    foreach ($add as $col => $def) {
        if (!in_array($col, $have, true)) $db->exec("ALTER TABLE accounts ADD COLUMN $col $def");
    }
    $db->exec("CREATE TABLE IF NOT EXISTS status_history(
        id INT AUTO_INCREMENT PRIMARY KEY,
        account_id INT NOT NULL,
        old_status VARCHAR(40) NULL,
        new_status VARCHAR(40) NOT NULL,
        changed_by INT NULL,
        changed_at DATETIME NOT NULL,
        note TEXT NULL,
        INDEX idx_sh_account (account_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $db->exec("CREATE TABLE IF NOT EXISTS application_documents(
        id INT AUTO_INCREMENT PRIMARY KEY,
        account_id INT NOT NULL,
        doc_type VARCHAR(40) NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'Unchecked',
        checked_by INT NULL,
        checked_at DATE NULL,
        UNIQUE KEY uq_doc (account_id, doc_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function wf_role(PDO $db, ?int $uid): ?string
{
    if (!$uid) return null;
    $s = $db->prepare("SELECT role FROM users WHERE id=?");
    $s->execute([$uid]);
    $r = $s->fetchColumn();
    return $r === false ? null : (string) $r;
}

function wf_status_log(PDO $db, int $acct, ?string $old, string $new, ?int $uid, string $note = ''): void
{
    $db->prepare("INSERT INTO status_history(account_id,old_status,new_status,changed_by,changed_at,note) VALUES(?,?,?,?,?,?)")
       ->execute([$acct, $old, $new, $uid, date('Y-m-d H:i:s'), $note]);
}

function wf_history(PDO $db, int $acct): array
{
    $s = $db->prepare("SELECT h.*, u.username FROM status_history h LEFT JOIN users u ON u.id=h.changed_by
                        WHERE h.account_id=? ORDER BY h.id");
    $s->execute([$acct]);
    return $s->fetchAll();
}

function wf_working_days(string $from, string $to): int
{
    if ($to <= $from) return 0;
    $d = new DateTime($from); $end = new DateTime($to); $n = 0;
    while ($d < $end) {
        $d->modify('+1 day');
        if ((int) $d->format('N') < 6) $n++;
    }
    return $n;
}

function wf_pending_list(PDO $db): array
{
    $t = today($db);
    $rows = $db->query(
        "SELECT a.id, a.type, a.applied_at, a.returned_at, a.returned_reason, c.cif, c.first_name, c.last_name
           FROM accounts a JOIN customers c ON c.id=a.customer_id
          WHERE a.status='Pending' ORDER BY a.applied_at, a.id"
    )->fetchAll();
    foreach ($rows as &$r) {
        $r['waiting_days'] = wf_working_days($r['applied_at'], $t);
        $r['over_limit']   = $r['waiting_days'] > WF_REVIEW_LIMIT_DAYS;
        $r['returned']     = !empty($r['returned_at']);
    }
    return $rows;
}

// ── Documents ────────────────────────────────────────────────────────────────
function wf_ensure_doc(PDO $db, int $acct, string $type): void
{
    $c = $db->prepare("SELECT COUNT(*) FROM application_documents WHERE account_id=? AND doc_type=?");
    $c->execute([$acct, $type]);
    if ((int) $c->fetchColumn() === 0)
        $db->prepare("INSERT INTO application_documents(account_id,doc_type,status) VALUES(?,?,'Unchecked')")->execute([$acct, $type]);
}

function wf_init_documents(PDO $db, int $acct, array $v, int $uid): void
{
    $t = today($db);
    $ins = $db->prepare("INSERT INTO application_documents(account_id,doc_type,status,checked_by,checked_at) VALUES(?,?,?,?,?)");
    $ins->execute([$acct, 'ID', 'Verified', $uid, $t]);
    $ins->execute([$acct, 'Signature', 'Verified', $uid, $t]);
    if ((float) $v['opening_deposit'] < MIN_BALANCE) wf_ensure_doc($db, $acct, 'Source of funds');
    if (age_on($v['dob'], $t) < 18)                  wf_ensure_doc($db, $acct, 'Guardian document');
}

function wf_blocking_documents(PDO $db, int $acct): array
{
    $s = $db->prepare("SELECT doc_type FROM application_documents WHERE account_id=? AND status IN ('Missing','Invalid') ORDER BY id");
    $s->execute([$acct]);
    return $s->fetchAll(PDO::FETCH_COLUMN);
}

function wf_documents(PDO $db, int $acct): array
{
    $s = $db->prepare("SELECT d.*, u.username FROM application_documents d LEFT JOIN users u ON u.id=d.checked_by
                        WHERE d.account_id=? ORDER BY d.id");
    $s->execute([$acct]);
    return $s->fetchAll();
}

function wf_set_document(PDO $db, int $id, string $doc, string $status, int $uid): array
{
    if (!in_array(wf_role($db, $uid), ['officer', 'supervisor'], true)) return [false, 'Staff only.'];
    if (!in_array($doc, WF_DOC_TYPES, true))       return [false, 'Unknown document.'];
    if (!in_array($status, WF_DOC_STATUSES, true)) return [false, 'Choose Verified, Missing or Invalid.'];
    return in_tx($db, function () use ($db, $id, $doc, $status, $uid) {
        $a = get_account($db, $id);
        if (!$a || $a['status'] !== 'Pending') return [false, 'Documents can only be checked while the application is pending.'];
        $e = $db->prepare("SELECT COUNT(*) FROM application_documents WHERE account_id=? AND doc_type=?");
        $e->execute([$id, $doc]);
        if ((int) $e->fetchColumn() === 0) return [false, 'This application has no "' . $doc . '" document.'];
        $db->prepare("UPDATE application_documents SET status=?, checked_by=?, checked_at=? WHERE account_id=? AND doc_type=?")
           ->execute([$status, $uid, today($db), $id, $doc]);
        audit($db, $uid, 'document', $a['cif'] . ": $doc -> $status");
        return [true, "$doc marked $status."];
    });
}

// ── Correction workflow ──────────────────────────────────────────────────────
function wf_return_application(PDO $db, int $id, string $reason, int $uid): array
{
    if (wf_role($db, $uid) !== 'supervisor') return [false, 'Only a supervisor can return an application.'];
    if (trim($reason) === '') return [false, 'Enter the reason for returning the application.'];
    return in_tx($db, function () use ($db, $id, $reason, $uid) {
        $a = get_account($db, $id);
        if (!$a || $a['status'] !== 'Pending') return [false, 'Only a pending application can be returned.'];
        if (!empty($a['returned_at']))          return [false, 'This application is already waiting for correction.'];
        $db->prepare("UPDATE accounts SET returned_reason=?, returned_by=?, returned_at=? WHERE id=?")
           ->execute([trim($reason), $uid, today($db), $id]);
        wf_status_log($db, $id, 'Pending', 'Needs correction', $uid, trim($reason));
        audit($db, $uid, 'return', $a['cif'] . ': ' . trim($reason));
        return [true, 'Application returned to the officer for correction.'];
    });
}

function wf_resubmit(PDO $db, int $id, array $in, int $uid): array
{
    return in_tx($db, function () use ($db, $id, $in, $uid) {
        $a = get_account($db, $id);
        if (!$a || $a['status'] !== 'Pending' || empty($a['returned_at']))
            return [false, 'This application is not waiting for correction.'];
        if ((int) $a['created_by'] !== $uid)
            return [false, 'Only the officer who made the application can resubmit it.'];
        $note = trim((string) ($in['note'] ?? ''));
        if ($note === '') return [false, 'Describe what was corrected.'];

        $phone = isset($in['phone']) ? preg_replace('/\s+/', '', trim((string) $in['phone'])) : $a['phone'];
        if (!preg_match('/^(\+251|0)?9\d{8}$/', $phone))
            return [false, 'Enter a valid Ethiopian mobile number, e.g. 0912345678.'];
        $addr = isset($in['address']) ? trim((string) $in['address']) : $a['address'];
        if (strlen($addr) < 3 || strlen($addr) > 200) return [false, 'Address must be 3 to 200 characters.'];

        $dep = (isset($in['opening_deposit']) && $in['opening_deposit'] !== '') ? $in['opening_deposit'] : $a['opening_deposit'];
        if (!is_numeric($dep) || (float) $dep < 0) return [false, 'Enter the opening deposit (0 or more).'];
        $dep = round((float) $dep, 2);
        $src = isset($in['source_of_funds']) ? trim((string) $in['source_of_funds']) : (string) $a['source_of_funds'];
        if ($dep < MIN_BALANCE && strlen($src) < 5) return [false, 'A deposit under ETB 100 needs a reasonable source of funds.'];
        if ($dep >= MIN_BALANCE) $src = '';

        $g = isset($in['guardian_id']) ? trim((string) $in['guardian_id']) : (string) $a['guardian_id'];
        $t = today($db);
        if (age_on($a['dob'], $t) < 18 && $g === '') return [false, 'A guardian ID is required for applicants under 18.'];

        $db->prepare("UPDATE customers SET phone=?, address=?, guardian_id=? WHERE id=?")
           ->execute([$phone, $addr, $g === '' ? null : $g, $a['customer_id']]);
        $db->prepare("UPDATE accounts SET opening_deposit=?, source_of_funds=?,
                      returned_reason=NULL, returned_by=NULL, returned_at=NULL WHERE id=?")
           ->execute([$dep, $src, $id]);
        $db->prepare("UPDATE application_documents SET status='Unchecked', checked_by=NULL, checked_at=NULL
                       WHERE account_id=? AND status IN ('Missing','Invalid')")->execute([$id]);
        if ($dep < MIN_BALANCE)            wf_ensure_doc($db, $id, 'Source of funds');
        if (age_on($a['dob'], $t) < 18)    wf_ensure_doc($db, $id, 'Guardian document');

        wf_status_log($db, $id, 'Needs correction', 'Pending', $uid, 'Resubmitted: ' . $note);
        audit($db, $uid, 'resubmit', $a['cif'] . ': ' . $note);
        return [true, 'Application resubmitted for review.'];
    });
}

function wf_reset(PDO $db): void
{
    foreach (['status_history', 'application_documents'] as $t) {
        try { $db->exec("DELETE FROM $t"); } catch (Throwable $x) {}
    }
    if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
        try { $db->exec("DELETE FROM sqlite_sequence WHERE name IN ('status_history','application_documents')"); }
        catch (Throwable $x) {}
    }
}
