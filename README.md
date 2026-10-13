# CBE Personal Savings Account Demo (PHP + SQLite)

A student demo built from the study "Opening a Personal Savings Account at the Commercial Bank of Ethiopia". It is not a real bank system.

## Run it
1. Install PHP 8+ with `pdo_sqlite` (XAMPP has it).
2. In this folder: `php -S localhost:8000`, then open http://localhost:8000
   (or copy the folder into XAMPP `htdocs`).

## Logins
- officer / officer123: applications, deposits, withdrawals, passbook replacement
- supervisor / super123: approvals, closing, month-end interest, deadlines, audit

Change the passwords in `lib.php` (`db_connect`) before sharing it.

## Try the full story (5 minutes)
1. Log in as officer, start a **New application** with deposit 0 and a source of funds.
2. Log out, log in as supervisor, **Approve** it. A 15-day deadline starts.
3. Open **Month-end**, move the demo date forward, run **Flag overdue accounts**.
4. Log in as officer, deposit 100 or more on another account, then withdraw 600 to see the ETB 12 fee.
5. As supervisor, move the date to the last business day and **Credit interest**.

## Pages
Public: `index.php`, `guide.php` (checklist + calculator), `tariff.php`, `requirements.php` (20 requirements, 11 use cases, assumptions).
Staff: `dashboard.php`, `new.php`, `approvals.php`, `accounts.php`, `account.php`, `admin.php`, `audit.php`.
Rules live in `lib.php`.

## Tests
`php tests/run_tests.php` runs the business rules against a throwaway database.

## Notes
- The study did not settle some points. See "Assumptions to confirm" on `requirements.php`.
- Figures come from published documents and may change. Confirm with the branch.
- To use MySQL, change the connection in `lib.php` and `AUTOINCREMENT` to `AUTO_INCREMENT`.
