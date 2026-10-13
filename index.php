<?php
require 'inc.php';
page_top('Home');
?>
<div class="hero">
  <h1>Opening a personal savings account at CBE Wayu Branch, Horo Guduru</h1>
  <p>Free to open. You can start with a zero balance if you can reach ETB 100 within 15 days. Ordinary savings pays 7% a year. Every applicant must pass identity verification.</p>
  <p><a class="btn" href="guide.php">Prepare for your visit</a> <a class="btn alt" href="tariff.php">See rates and fees</a></p>
</div>
<div class="grid">
  <div class="stat"><b>Free</b><span>Opening the account and the first passbook</span></div>
  <div class="stat"><b>ETB 100</b><span>Minimum balance, reached within 15 days if you open with less</span></div>
  <div class="stat"><b>7% to 7.5%</b><span>Yearly interest, depending on the account type</span></div>
</div>
<div class="card">
  <h2>What this demo covers</h2>
  <p class="sub">Public pages are for applicants. Staff log in to run the account-opening process.</p>
  <ul>
    <li><a href="guide.php">Applicant guide</a>: a checklist for before, during and after the branch visit, and an interest calculator.</li>
    <li><a href="tariff.php">Rates and fees</a>: published rates and charges, and where the sources disagree.</li>
    <li><a href="requirements.php">Requirements and use cases</a>: every functional requirement drawn from the study, with the page that implements it.</li>
    <li><a href="login.php">Staff login</a>: officer and supervisor accounts. Application, identity check, approval, deposits, withdrawals, interest, passbook replacement and closing.</li>
  </ul>
</div>
<?php page_bottom();
