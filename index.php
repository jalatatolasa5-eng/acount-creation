<?php
require 'inc.php';
page_top('Home');
?>
<div class="page-heading">
  <div>
    <h1>Dashboard</h1>
    <p class="sub">Overview of savings account operations and key metrics.</p>
  </div>
  <div class="page-heading-actions">
    <a class="btn gold" href="login.php"><?= svg_icon('user') ?> Staff Login</a>
  </div>
</div>

<div class="hero">
  <h1>Commercial Bank of Ethiopia<br>Personal Savings Account</h1>
  <p>Free to open. Start with zero balance if you can reach ETB 100 within 15 days. Ordinary savings pays 7% a year. Identity verification mandatory.</p>
  <div class="hero-actions">
    <a class="btn gold" href="guide.php"><?= svg_icon('guide') ?> Applicant Guide</a>
    <a class="btn alt" href="tariff.php"><?= svg_icon('tariff') ?> Rates &amp; Fees</a>
    <a class="btn alt" href="requirements.php"><?= svg_icon('req') ?> Requirements</a>
  </div>
</div>

<div class="stat-grid">
  <div class="stat stat-ok">
    <div class="stat-icon"><?= svg_icon('ok-ic') ?></div>
    <b>Free</b><span>Opening &amp; first passbook</span>
  </div>
  <div class="stat">
    <div class="stat-icon"><?= svg_icon('bank') ?></div>
    <b>ETB 100</b><span>Minimum balance within 15 days</span>
  </div>
  <div class="stat stat-purple">
    <div class="stat-icon"><?= svg_icon('tariff') ?></div>
    <b>7% – 7.5%</b><span>Yearly interest by account type</span>
  </div>
  <div class="stat stat-blue">
    <div class="stat-icon"><?= svg_icon('warn') ?></div>
    <b>ETB 12</b><span>Withdrawal fee ETB 501–75,000</span>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('guide') ?> What this demo covers</h2>
  </div>
  <ul class="steps-ol">
    <li><a href="guide.php">Applicant Guide</a> — checklist for before, during and after the branch visit, plus an interest calculator.</li>
    <li><a href="tariff.php">Rates &amp; Fees</a> — published rates, charges, and where the sources disagree.</li>
    <li><a href="requirements.php">Requirements &amp; Use Cases</a> — every functional requirement drawn from the study.</li>
    <li><a href="login.php">Staff Login</a> — officer and supervisor accounts covering the full account lifecycle.</li>
  </ul>
</div>
<?php page_bottom(); ?>
