-- CBE Personal Savings Account Demo (Wayu Branch, Horo Guduru)
-- SQLite schema. Based on the tables in lib.php (db_connect), with:
--   * foreign keys, NOT NULL rules and CHECK constraints
--   * indexes on the columns the pages search by
--   * a branches table for the Wayu Branch
-- Money is stored as REAL like the current code; round to 2 decimals in PHP.
-- Dates are ISO text: YYYY-MM-DD (created_at / at: YYYY-MM-DD HH:MM:SS).

PRAGMA foreign_keys = ON;

-- Branch (new)
CREATE TABLE IF NOT EXISTS branches (
    id    INTEGER PRIMARY KEY AUTOINCREMENT,
    name  TEXT NOT NULL UNIQUE,
    zone  TEXT
);
INSERT OR IGNORE INTO branches (id, name, zone) VALUES (1, 'Wayu Branch', 'Horo Guduru');

-- Staff logins (password stores a PHP password_hash, never plain text)
CREATE TABLE IF NOT EXISTS users (
    id        INTEGER PRIMARY KEY AUTOINCREMENT,
    username  TEXT NOT NULL UNIQUE,
    password  TEXT NOT NULL,
    role      TEXT NOT NULL CHECK (role IN ('officer', 'supervisor')),
    branch_id INTEGER NOT NULL DEFAULT 1 REFERENCES branches(id)
);

-- Demo date and other settings
CREATE TABLE IF NOT EXISTS settings (
    key   TEXT PRIMARY KEY,
    value TEXT
);

-- Customers (one row per person; guardian_id is used for minors)
CREATE TABLE IF NOT EXISTS customers (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    cif         TEXT UNIQUE,
    first_name  TEXT NOT NULL,
    last_name   TEXT NOT NULL,
    dob         TEXT,
    gender      TEXT,
    phone       TEXT,
    address     TEXT,
    id_type     TEXT,
    id_number   TEXT UNIQUE COLLATE NOCASE,
    guardian_id TEXT,
    created_at  TEXT
);

-- Accounts (application, approval and account state live in one row)
CREATE TABLE IF NOT EXISTS accounts (
    id                    INTEGER PRIMARY KEY AUTOINCREMENT,
    account_no            TEXT UNIQUE,
    customer_id           INTEGER NOT NULL REFERENCES customers(id),
    branch_id             INTEGER NOT NULL DEFAULT 1 REFERENCES branches(id),
    type                  TEXT,
    rate                  REAL,
    status                TEXT,
    balance               REAL NOT NULL DEFAULT 0 CHECK (balance >= 0),
    opening_deposit       REAL,
    zero_balance          INTEGER NOT NULL DEFAULT 0 CHECK (zero_balance IN (0, 1)),
    source_of_funds       TEXT,
    funding_status        TEXT DEFAULT 'Not required',
    funding_deadline      TEXT,
    id_verified           INTEGER CHECK (id_verified IN (0, 1)),
    kfs_given             INTEGER CHECK (kfs_given IN (0, 1)),
    agreement_signed      INTEGER CHECK (agreement_signed IN (0, 1)),
    conditions_explained  INTEGER CHECK (conditions_explained IN (0, 1)),
    signature             TEXT,
    card_fee              REAL,
    passbook_replacements INTEGER NOT NULL DEFAULT 0,
    created_by            INTEGER REFERENCES users(id),
    decided_by            INTEGER REFERENCES users(id),
    reject_reason         TEXT,
    applied_at            TEXT,
    opened_at             TEXT,
    closed_at             TEXT,
    close_reason          TEXT,
    last_interest_month   TEXT
);

-- Deposits, withdrawals, fees and interest (one row per movement)
CREATE TABLE IF NOT EXISTS transactions (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id    INTEGER NOT NULL REFERENCES accounts(id),
    tx_date       TEXT,
    kind          TEXT,
    amount        REAL,
    balance_after REAL,
    note          TEXT,
    user_id       INTEGER REFERENCES users(id),
    created_at    TEXT
);

-- Who did what, and when
CREATE TABLE IF NOT EXISTS audit_log (
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER REFERENCES users(id),
    action  TEXT,
    detail  TEXT,
    at      TEXT
);

-- Indexes for the lookups the pages use
CREATE INDEX IF NOT EXISTS idx_accounts_customer ON accounts(customer_id);
CREATE INDEX IF NOT EXISTS idx_accounts_status   ON accounts(status);
CREATE INDEX IF NOT EXISTS idx_tx_account_date   ON transactions(account_id, tx_date);
CREATE INDEX IF NOT EXISTS idx_audit_at          ON audit_log(at);
