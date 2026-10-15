# CBE Personal Savings Account Demo (PHP + MySQL)

A student demo built from the study "Opening a Personal Savings Account at the Commercial Bank of Ethiopia". It is not a real bank system.

## Requirements
- XAMPP (Apache, MySQL/MariaDB and PHP 8+)

## Run it
1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Copy this folder into `C:\xampp\htdocs\`.
3. Open phpMyAdmin (http://localhost/phpmyadmin), create a database named `cbe_savings`, then import `backup.sql` into it.
4. Check the connection in `src/config.php` (driver `mysql`, database `cbe_savings`, and your MySQL username and password; XAMPP's default is `root` with an empty password).
5. Open http://localhost/cbe-savings-account-demo/public/index.php

## Database
- MySQL is the default (see `src/config.php`, `src/lib.php`).
- `backup.sql` is a MariaDB dump of the `cbe_savings` database.
- To use SQLite instead, set `'driver' => 'sqlite'` in `src/config.php`. The file is `database/data.sqlite`.

## Logins
- officer / officer123: applications, deposits, withdrawals, passbook replacement
- supervisor / super123: approvals, closing, month-end interest, deadlines, audit

Change the passwords before sharing it.

## Try the full story (5 minutes)
1. Log in as officer, start a **New application** with deposit 0 and a source of funds.
2. Log out, log in as supervisor, **Approve** it. A 15-day deadline starts.
3. Open **Month-end**, move the demo date forward, run **Flag overdue accounts**.
4. Log in as officer, deposit 100 or more on another account, then withdraw 600 to see the ETB 12 fee.
5. As supervisor, move the date to the last business day and **Credit interest**.

## Project layout
- `public/` : the web pages
- `src/` : configuration and business rules (`config.php`, `lib.php`, `inc.php`)
- `tests/` : business-rule tests
- `backup.sql` : database dump

## Pages (in `public/`)
- `account.php`
- `accounts.php`
- `admin.php`
- `approvals.php`
- `audit.php`
- `dashboard.php`
- `guide.php`
- `index.php`
- `login.php`
- `logout.php`
- `new.php`
- `tariff.php`

## Tests
`php tests/run_tests.php` runs the business rules.

## Notes
- Figures come from published documents and may change. Confirm with the branch.
- The study did not settle some points. See the assumptions on the requirements page.
- `landing.php` is the public home page. Visitors can read about the six account types and check an application's status.
- `status.php` returns only the status and account type for a CIF or account number. It shows no names, phone numbers or balances.
