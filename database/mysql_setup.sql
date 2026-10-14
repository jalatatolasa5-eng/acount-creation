-- ============================================================
-- CBE Savings Account Demo -- MySQL Setup
-- Run with: mysql -u root cbe_savings < mysql_setup.sql
-- ============================================================

USE cbe_savings;

SET FOREIGN_KEY_CHECKS = 0;

-- ── Settings ─────────────────────────────────────────────────
DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
    `key`   VARCHAR(50)  NOT NULL PRIMARY KEY,
    `value` VARCHAR(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Users ────────────────────────────────────────────────────
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('officer','supervisor') NOT NULL,
    created_at DATE NOT NULL DEFAULT (CURRENT_DATE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Customers ────────────────────────────────────────────────
DROP TABLE IF EXISTS customers;
CREATE TABLE customers (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cif         VARCHAR(20)  NOT NULL UNIQUE,
    first_name  VARCHAR(60)  NOT NULL,
    last_name   VARCHAR(60)  NOT NULL,
    dob         DATE         NOT NULL,
    gender      ENUM('Female','Male') NOT NULL,
    phone       VARCHAR(15)  NOT NULL,
    address     VARCHAR(200) NOT NULL,
    id_type     VARCHAR(50)  NOT NULL,
    id_number   VARCHAR(30)  NOT NULL UNIQUE,
    guardian_id VARCHAR(30)  DEFAULT NULL,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Accounts ─────────────────────────────────────────────────
DROP TABLE IF EXISTS accounts;
CREATE TABLE accounts (
    id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_no           VARCHAR(20)  UNIQUE DEFAULT NULL,
    customer_id          INT UNSIGNED NOT NULL,

    type                 ENUM(
                             'Ordinary savings','Youth savings',
                             'Educational savings',"Women's savings",
                             'Wedding savings','Holiday savings'
                         ) NOT NULL,
    rate                 DECIMAL(6,4) NOT NULL,

    status               ENUM('Pending','Active','Rejected','Closed')
                         NOT NULL DEFAULT 'Pending',
    balance              DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    opening_deposit      DECIMAL(15,2) NOT NULL DEFAULT 0.00,

    zero_balance         TINYINT(1)   NOT NULL DEFAULT 0,
    source_of_funds      TEXT         DEFAULT NULL,
    funding_status       ENUM(
                             'Not required','Awaiting funding',
                             'Funded','Overdue','Closed unfunded'
                         ) NOT NULL DEFAULT 'Not required',
    funding_deadline     DATE         DEFAULT NULL,

    id_verified          TINYINT(1)   NOT NULL DEFAULT 0,
    kfs_given            TINYINT(1)   NOT NULL DEFAULT 0,
    agreement_signed     TINYINT(1)   NOT NULL DEFAULT 0,
    conditions_explained TINYINT(1)   NOT NULL DEFAULT 0,
    signature            VARCHAR(150) NOT NULL DEFAULT '',

    card_fee             DECIMAL(8,2) DEFAULT NULL,
    passbook_replacements SMALLINT UNSIGNED NOT NULL DEFAULT 0,

    created_by           INT UNSIGNED NOT NULL,
    decided_by           INT UNSIGNED DEFAULT NULL,
    reject_reason        TEXT         DEFAULT NULL,

    applied_at           DATE         NOT NULL DEFAULT (CURRENT_DATE),
    opened_at            DATE         DEFAULT NULL,
    closed_at            DATE         DEFAULT NULL,
    close_reason         TEXT         DEFAULT NULL,
    last_interest_month  CHAR(7)      DEFAULT NULL,

    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (created_by)  REFERENCES users(id),
    FOREIGN KEY (decided_by)  REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Transactions ─────────────────────────────────────────────
DROP TABLE IF EXISTS transactions;
CREATE TABLE transactions (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_id    INT UNSIGNED NOT NULL,
    tx_date       DATE         NOT NULL,
    kind          ENUM(
                      'Opening','Deposit','Withdrawal','Fee',
                      'Interest','Passbook fee','Closing payout'
                  ) NOT NULL,
    amount        DECIMAL(15,2) NOT NULL,
    balance_after DECIMAL(15,2) NOT NULL,
    note          VARCHAR(255)  NOT NULL DEFAULT '',
    user_id       INT UNSIGNED  DEFAULT NULL,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (account_id) REFERENCES accounts(id),
    FOREIGN KEY (user_id)    REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Audit log ────────────────────────────────────────────────
DROP TABLE IF EXISTS audit_log;
CREATE TABLE audit_log (
    id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED DEFAULT NULL,
    action  VARCHAR(50)  NOT NULL,
    detail  TEXT         NOT NULL,
    at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Indexes ──────────────────────────────────────────────────
CREATE INDEX idx_acct_customer       ON accounts(customer_id);
CREATE INDEX idx_acct_status         ON accounts(status);
CREATE INDEX idx_acct_funding        ON accounts(funding_status);
CREATE INDEX idx_acct_opened         ON accounts(opened_at);
CREATE INDEX idx_acct_interest_month ON accounts(last_interest_month);
CREATE INDEX idx_acct_type           ON accounts(type);
CREATE INDEX idx_tx_account_date     ON transactions(account_id, tx_date);
CREATE INDEX idx_tx_kind             ON transactions(kind);
CREATE INDEX idx_cust_id_number      ON customers(id_number);
CREATE INDEX idx_cust_phone          ON customers(phone);
CREATE INDEX idx_cust_name           ON customers(last_name, first_name);
CREATE INDEX idx_audit_at            ON audit_log(at);
CREATE INDEX idx_audit_user          ON audit_log(user_id);

-- ── Seed users ───────────────────────────────────────────────
INSERT INTO users (username, password, role) VALUES
  ('officer',    '$2y$10$UM4m6QCw90ujHksrChGNned/lPJ1goLYHfkzIGmHnsGf63Jotc7k6', 'officer'),
  ('supervisor', '$2y$10$eX6baevDo5hlB7qhtmlQrOqnq06wn1kmPAiFMT2xyROECEthrNMtu', 'supervisor');

-- ── Import existing data ──────────────────────────────────────
INSERT INTO customers (id, cif, first_name, last_name, dob, gender, phone, address, id_type, id_number, guardian_id, created_at) VALUES
  (1, 'CIF100001', 'jalata',  'Franco', '2002-08-13', 'Female', '0967234566', 'wayu',                           'National ID',      '123345596969997', NULL, NOW()),
  (2, 'CIF100002', 'James',   'Franco', '2001-06-13', 'Male',   '0967234566', 'maya',                           'National ID',      '485867887686585', NULL, NOW()),
  (3, 'CIF100003', 'eyob',    'dereje', '2000-02-23', 'Male',   '0931665052', 'east wolega zone , oromia region','Driving licence',  '1245678',         NULL, NOW());

INSERT INTO accounts (id, account_no, customer_id, type, rate, status, balance, opening_deposit, zero_balance, source_of_funds, funding_status, funding_deadline, id_verified, kfs_given, agreement_signed, conditions_explained, signature, card_fee, passbook_replacements, created_by, decided_by, reject_reason, applied_at, opened_at, closed_at, close_reason, last_interest_month) VALUES
  (1, '1000368207005', 1, 'Ordinary savings', 7.0, 'Active',  100.00, 100.00, 0, '', 'Not required', NULL, 1,1,1,1, 'jalata franco', NULL, 0, 1, 2, NULL, '2026-10-13', '2026-10-13', NULL,         NULL,     NULL),
  (2, '1000649856707', 2, 'Ordinary savings', 7.0, 'Closed',    0.00, 100.00, 0, '', 'Not required', NULL, 1,1,1,1, 'James Franco',  NULL, 0, 1, 2, NULL, '2026-10-13', '2026-10-13', '2026-10-14', 'ddgber', NULL),
  (3, NULL,            3, 'Ordinary savings', 7.0, 'Pending',   0.00,2340.00, 0, '', 'Not required', NULL, 1,1,1,1, 'eyob dereje',   NULL, 0, 1, NULL,NULL, '2026-10-14', NULL,         NULL,         NULL,     NULL);

INSERT INTO transactions (id, account_id, tx_date, kind, amount, balance_after, note, user_id, created_at) VALUES
  (1, 1, '2026-10-13', 'Opening',    100.00,   100.00, 'Opening deposit', 2, NOW()),
  (2, 2, '2026-10-13', 'Opening',    100.00,   100.00, 'Opening deposit', 2, NOW()),
  (3, 2, '2026-10-14', 'Withdrawal', -100.00,    0.00, 'Closing payout',  2, NOW());

SET FOREIGN_KEY_CHECKS = 1;
