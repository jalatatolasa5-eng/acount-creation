<?php
require 'inc.php';
page_top('Home');
?>
<div class="page-heading">
  <div>
    <h1>Personal Savings Account &mdash; Wayu Branch</h1>
    <p class="sub">Horo Guduru Wollega Zone &middot; Commercial Bank of Ethiopia</p>
  </div>
</div>

<div class="hero">
  <h1>Open your savings account today</h1>
  <p>Free to open. Start with a zero balance if you can reach ETB 100 within 15 days. Ordinary savings pays 7% a year. Every applicant must pass identity verification.</p>
  <div class="hero-actions">
    <a class="btn" href="guide.php"><?= icon('guide') ?> Prepare for your visit</a>
    <a class="btn alt" href="tariff.php"><?= icon('tariff') ?> See rates &amp; fees</a>
  </div>
</div>

<div class="stat-grid">
  <div class="stat stat-ok">
    <b>Free</b>
    <span>Opening the account &amp; first passbook</span>
  </div>
  <div class="stat">
    <b>ETB 100</b>
    <span>Minimum balance &mdash; reach it within 15 days if you open with less</span>
  </div>
  <div class="stat stat-brand">
    <b>7% – 7.5%</b>
    <span>Yearly interest, depending on account type</span>
  </div>
  <div class="stat stat-bad">
    <b>ETB 12</b>
    <span>Withdrawal fee for amounts ETB 501 – 75,000</span>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2><?= icon('guide') ?> What this demo covers</h2>
  </div>
  <p class="sub" style="margin-bottom:12px;">Public pages are for applicants. Staff log in to run the account-opening process.</p>
  <ul class="steps" style="padding-left:18px;">
    <li><a href="guide.php">Applicant Guide</a> &mdash; checklist for before, during and after the branch visit, plus an interest calculator.</li>
    <li><a href="tariff.php">Rates &amp; Fees</a> &mdash; published rates, charges, and where the sources disagree.</li>
    <li><a href="requirements.php">Requirements &amp; Use Cases</a> &mdash; every functional requirement drawn from the study, with the page that implements it.</li>
    <li><a href="login.php">Staff Login</a> &mdash; officer and supervisor accounts covering the full account lifecycle.</li>
  </ul>
</div>
<?php page_bottom(); ?>
