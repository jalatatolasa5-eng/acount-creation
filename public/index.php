<?php require_once __DIR__ . '/../src/inc.php'; page_top('Home'); ?>

<div class="page-heading">
  <div>
    <h1>Personal Savings Account &mdash; Wayu Branch</h1>
    <p class="sub">Horo Guduru Wollega Zone &middot; Commercial Bank of Ethiopia</p>
  </div>
  <div class="page-heading-actions">
    <a class="btn gold" href="<?= url('login.php') ?>"><?= svg_icon('user') ?> Staff Login</a>
  </div>
</div>

<div class="hero">
  <h1>Open your savings account today</h1>
  <p>Free to open. Start with zero balance if you can reach ETB 100 within 15 days. Ordinary savings pays 7% a year. Identity verification mandatory.</p>
  <div class="hero-actions">
    <a class="btn gold" href="<?= url('guide.php') ?>"><?= svg_icon('guide') ?> Applicant Guide</a>
    <a class="btn alt"  href="<?= url('tariff.php') ?>"><?= svg_icon('tariff') ?> Rates &amp; Fees</a>
    <a class="btn alt"  href="<?= url('requirements.php') ?>"><?= svg_icon('req') ?> Requirements</a>
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

<!-- What this demo covers -->
<div style="margin-bottom:6px;">
  <h2 style="font-size:16px;font-weight:800;color:var(--ink);margin-bottom:4px;">What this demo covers</h2>
  <p class="sub">Explore the full savings account workflow — from applicant preparation to staff operations.</p>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-bottom:20px;">

  <a href="<?= url('guide.php') ?>" class="feature-card">
    <div class="feature-card-icon" style="background:var(--brand-lt);color:var(--brand);"><?= svg_icon('guide') ?></div>
    <h3>Applicant Guide</h3>
    <p>Checklist for before, during and after the branch visit, plus an interest calculator.</p>
    <span class="feature-card-link" style="color:var(--brand);">Open guide →</span>
  </a>

  <a href="<?= url('tariff.php') ?>" class="feature-card">
    <div class="feature-card-icon" style="background:var(--gold-lt);color:var(--gold-dk);"><?= svg_icon('tariff') ?></div>
    <h3>Rates &amp; Fees</h3>
    <p>Published rates, charges, and where the sources disagree.</p>
    <span class="feature-card-link" style="color:var(--gold-dk);">View tariff →</span>
  </a>

  <a href="<?= url('requirements.php') ?>" class="feature-card">
    <div class="feature-card-icon" style="background:var(--info-bg);color:var(--info);"><?= svg_icon('req') ?></div>
    <h3>Requirements &amp; Use Cases</h3>
    <p>Every functional requirement drawn from the study, with the page that implements it.</p>
    <span class="feature-card-link" style="color:var(--info);">View requirements →</span>
  </a>

  <a href="<?= url('login.php') ?>" class="feature-card feature-card-dark">
    <div class="feature-card-icon" style="background:rgba(255,255,255,.15);color:#fff;"><?= svg_icon('user') ?></div>
    <h3 style="color:#fff;">Staff Login</h3>
    <p style="color:rgba(255,255,255,.75);">Officer and supervisor accounts covering the full account lifecycle.</p>
    <span class="feature-card-link" style="color:var(--gold);">Sign in →</span>
  </a>

</div>

<?php page_bottom(); ?>
