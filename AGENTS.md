# CBE savings account demo
- Plain PHP + PDO app served by XAMPP at http://localhost/cbe-savings-account-demo/
- Database: SQLite (data.sqlite) by default; MySQL database `cbe_savings` (user root, no password) when the file use_mysql.txt exists in the project root. All SQL must work on both.
- Logins: officer/officer123, supervisor/super123.
- Business rules and DB code are in lib.php; pages are new.php, accounts.php, guide.php; tests are run_tests.php.
- PHP is at C:\xampp\php\php.exe (run `php -l file.php` after every edit and `php run_tests.php` before finishing).
- Do not delete use_mysql.txt, and do not change business figures in the constants at the top of lib.php.
