-- ============================================================
-- CBE Personal Savings Account Demo – SQLite schema v2
-- Wayu Branch, Horo Guduru
-- ============================================================
-- Design notes:
--   • Money stored as REAL; PHP rounds to 2 dp before every write.
--   • Dates as ISO TEXT: YYYY-MM-DD  |  timestamps: YYYY-MM-DD HH:MM:SS
--   • All enum-like columns carry CHECK constraints.
--   • Every NOT NULL column that has a sensible default carries one.
--   • FK references declared; enable with PRAGMA foreign_keys=ON.
--   • Indexes cover every WHERE / JOIN / ORDER BY column used by the app.
-- ============================================================

PRAGMA foreign_keys = ON;

-- ── Settings (key/value – holds the demo date) ────────────────
CREATE TABLE IF NOT EXISTS settings (
    key   TEXT NOT NULL PRIMARY KEY,
    value TEXT NOT NULL
);

-- ── Users (staff logins) ──────────────────────────────────────
-- password: bcrypt hash only, never plain text
CREATE TABLE IF NOT EXISTS users (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    username   TEXT    NOT NULL UNIQUE
                       CHECK (length(username) BETWEEN 3 AND 50),
    password   TEXT    NOT NULL,
    role       TEXT    NOT NULL
                       CHECK (role IN ('officer','supervisor')),
    created_at TEXT    NOT NULL DEFAULT (date('now'))
);

-- ── Customers (one row per natural person) ────────────────────
-- cif auto-generated: CIF + zero-padded sequential id
-- id_number is unique case-insensitively
CREATE TABLE IF NOT EXISTS customers (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    cif         TEXT    NOT NULL UNIQUE
                        CHECK (cif LIKE 'CIF%'),
    first_name  TEXT    NOT NULL CHECK (length(first_name)  BETWEEN 1 AND 60),
    last_name   TEXT    NOT NULL CHECK (length(last_name)   BETWEEN 1 AND 60),
    dob         TEXT    NOT NULL,
    gender      TEXT    NOT NULL CHECK (gender IN ('Female','Male')),
    phone       TEXT    NOT NULL CHECK (length(phone) BETWEEN 10 AND 15),
    address     TEXT    NOT NULL CHECK (length(address) BETWEEN 3 AND 200),
    id_type     TEXT    NOT NULL,
    id_number   TEXT    NOT NULL UNIQUE COLLATE NOCASE
                        CHECK (length(id_number) BETWEEN 4 AND 30),
    guardian_id TEXT,            -- NULL for adults; required for under-18
    created_at  TEXT    NOT NULL DEFAULT (datetime('now'))
);

-- ── Accounts ──────────────────────────────────────────────────
-- One row covers the full lifecycle:
-- application (Pending) → Active / Rejected → Closed
CREATE TABLE IF NOT EXISTS accounts (
    id                   INTEGER PRIMARY KEY AUTOINCREMENT,
    account_no           TEXT    UNIQUE,        -- issued on approval

    customer_id          INTEGER NOT NULL REFERENCES customers(id),

    -- Product
    type                 TEXT    NOT NULL
                                 CHECK (type IN (
                                     'Ordinary savings','Youth savings',
                                     'Educational savings',"Women's savings",
                                     'Wedding savings','Holiday savings')),
    rate                 REAL    NOT NULL CHECK (rate > 0),

    -- Lifecycle
    status               TEXT    NOT NULL DEFAULT 'Pending'
                                 CHECK (status IN ('Pending','Active','Rejected','Closed')),
    balance              REAL    NOT NULL DEFAULT 0   CHECK (balance >= 0),
    opening_deposit      REAL    NOT NULL DEFAULT 0   CHECK (opening_deposit >= 0),

    -- Zero-balance opening
    zero_balance         INTEGER NOT NULL DEFAULT 0   CHECK (zero_balance IN (0,1)),
    source_of_funds      TEXT,
    funding_status       TEXT    NOT NULL DEFAULT 'Not required'
                                 CHECK (funding_status IN (
                                     'Not required','Awaiting funding',
                                     'Funded','Overdue','Closed unfunded')),
    funding_deadline     TEXT,   -- NULL when not required

    -- Maker checklist (all must be 1 before approval is allowed)
    id_verified          INTEGER NOT NULL DEFAULT 0 CHECK (id_verified          IN (0,1)),
    kfs_given            INTEGER NOT NULL DEFAULT 0 CHECK (kfs_given            IN (0,1)),
    agreement_signed     INTEGER NOT NULL DEFAULT 0 CHECK (agreement_signed     IN (0,1)),
    conditions_explained INTEGER NOT NULL DEFAULT 0 CHECK (conditions_explained IN (0,1)),
    signature            TEXT    NOT NULL DEFAULT '',

    -- Optional debit card (fee collected in cash, not from account)
    card_fee             REAL    CHECK (card_fee IS NULL OR card_fee > 0),

    -- Passbook
    passbook_replacements INTEGER NOT NULL DEFAULT 0 CHECK (passbook_replacements >= 0),

    -- Maker-checker references
    created_by           INTEGER NOT NULL REFERENCES users(id),
    decided_by           INTEGER REFERENCES users(id),  -- NULL until decided
    reject_reason        TEXT,

    -- Timestamps (ISO dates)
    applied_at           TEXT    NOT NULL DEFAULT (date('now')),
    opened_at            TEXT,   -- set on approval
    closed_at            TEXT,   -- set on closure
    close_reason         TEXT,

    -- Interest tracking
    last_interest_month  TEXT    -- YYYY-MM of last credited month
);

-- ── Transactions ──────────────────────────────────────────────
-- One row per money movement.
-- amount is signed: positive = credit, negative = debit.
CREATE TABLE IF NOT EXISTS transactions (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id    INTEGER NOT NULL REFERENCES accounts(id),
    tx_date       TEXT    NOT NULL,
    kind          TEXT    NOT NULL
                          CHECK (kind IN (
                              'Opening','Deposit','Withdrawal','Fee',
                              'Interest','Passbook fee','Closing payout')),
    amount        REAL    NOT NULL,
    balance_after REAL    NOT NULL CHECK (balance_after >= 0),
    note          TEXT    NOT NULL DEFAULT '',
    user_id       INTEGER REFERENCES users(id),
    created_at    TEXT    NOT NULL DEFAULT (datetime('now'))
);

-- ── Audit log ─────────────────────────────────────────────────
-- Every staff action with timestamp.
CREATE TABLE IF NOT EXISTS audit_log (
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER REFERENCES users(id),
    action  TEXT    NOT NULL,
    detail  TEXT    NOT NULL DEFAULT '',
    at      TEXT    NOT NULL DEFAULT (datetime('now'))
);

-- ── Indexes ───────────────────────────────────────────────────
-- accounts
CREATE INDEX IF NOT EXISTS idx_acct_customer       ON accounts(customer_id);
CREATE INDEX IF NOT EXISTS idx_acct_status         ON accounts(status);
CREATE INDEX IF NOT EXISTS idx_acct_funding        ON accounts(funding_status);
CREATE INDEX IF NOT EXISTS idx_acct_opened         ON accounts(opened_at);
CREATE INDEX IF NOT EXISTS idx_acct_interest_month ON accounts(last_interest_month);
CREATE INDEX IF NOT EXISTS idx_acct_type           ON accounts(type);

-- transactions
CREATE INDEX IF NOT EXISTS idx_tx_account_date     ON transactions(account_id, tx_date);
CREATE INDEX IF NOT EXISTS idx_tx_kind             ON transactions(kind);

-- customers
CREATE INDEX IF NOT EXISTS idx_cust_id_number      ON customers(id_number);
CREATE INDEX IF NOT EXISTS idx_cust_phone          ON customers(phone);
CREATE INDEX IF NOT EXISTS idx_cust_name           ON customers(last_name, first_name);

-- audit
CREATE INDEX IF NOT EXISTS idx_audit_at            ON audit_log(at);
CREATE INDEX IF NOT EXISTS idx_audit_user          ON audit_log(user_id);
