<?php
// Public About page. Static, no database access.
header('Content-Type: text/html; charset=utf-8');
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>About | Savings Account Demo</title>
<style>
  :root{--p:#5b1a6e;--g:#d4a017;--bg:#faf8fb;--ink:#241a2b;--mute:#6d6275;--line:#e8e1ec;}
  *{box-sizing:border-box}
  body{margin:0;font-family:system-ui,Segoe UI,Arial,sans-serif;background:var(--bg);color:var(--ink);line-height:1.6}
  header{background:var(--p);color:#fff;padding:14px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
  header strong{font-size:16px;letter-spacing:.02em}
  header a{color:#fff;text-decoration:none;font-size:14px;margin-left:18px;opacity:.9}
  header a:hover,header a.on{opacity:1;border-bottom:2px solid var(--g)}
  .hero{padding:56px 24px 36px;max-width:900px;margin:0 auto}
  .hero h1{font-size:34px;margin:0 0 10px;color:var(--p)}
  .hero p{font-size:17px;color:var(--mute);max-width:680px;margin:0}
  main{max-width:900px;margin:0 auto;padding:0 24px 64px}
  section{margin-top:40px}
  h2{font-size:20px;color:var(--p);margin:0 0 14px;border-left:4px solid var(--g);padding-left:10px}
  .fade{opacity:0;transform:translateY(14px);animation:up .6s ease forwards}
  .fade:nth-of-type(2){animation-delay:.1s}.fade:nth-of-type(3){animation-delay:.2s}
  .fade:nth-of-type(4){animation-delay:.3s}.fade:nth-of-type(5){animation-delay:.4s}
  @keyframes up{to{opacity:1;transform:none}}
  @media (prefers-reduced-motion:reduce){.fade{animation:none;opacity:1;transform:none}}
  ol.steps{list-style:none;padding:0;margin:0;display:grid;gap:12px;counter-reset:s}
  ol.steps li{counter-increment:s;background:#fff;border:1px solid var(--line);border-radius:10px;padding:14px 16px 14px 56px;position:relative}
  ol.steps li::before{content:counter(s);position:absolute;left:14px;top:14px;width:28px;height:28px;border-radius:50%;background:var(--p);color:#fff;display:grid;place-items:center;font-weight:700;font-size:13px}
  ol.steps strong{display:block}
  ol.steps span{color:var(--mute);font-size:14px}
  .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:14px}
  .card{background:#fff;border:1px solid var(--line);border-radius:10px;padding:16px}
  .card h3{margin:0 0 6px;font-size:15px}
  .card p,.card li{margin:0;font-size:14px;color:var(--mute)}
  .card ul{margin:0;padding-left:18px}
  .note{background:#fff7e0;border:1px solid #ecd48a;border-radius:10px;padding:14px 16px;font-size:14px}
  .btn{display:inline-block;margin-top:18px;background:var(--p);color:#fff;text-decoration:none;padding:10px 18px;border-radius:8px;font-size:14px}
  .btn:hover{background:#471456}
  footer{border-top:1px solid var(--line);padding:20px 24px;text-align:center;font-size:13px;color:var(--mute)}
</style>
</head>
<body>
<header>
  <strong>Savings Account Demo</strong>
  <nav><a href="landing.php">Home</a><a href="about.php" class="on">About</a></nav>
</header>

<div class="hero fade">
  <h1>About this system</h1>
  <p>A demonstration of how a bank branch opens a savings account: an officer captures the application, a supervisor checks and approves it, and the applicant can follow the status with their CIF number.</p>
</div>

<main>
  <section class="fade">
    <h2>How an application moves</h2>
    <ol class="steps">
      <li><strong>Officer creates the application</strong><span>Identity, customer profile (occupation, income, TIN, nationality), account type and opening deposit are captured.</span></li>
      <li><strong>Supervisor reviews</strong><span>Documents are marked Verified, Missing or Invalid. An application with unverified documents cannot be approved.</span></li>
      <li><strong>Approve or return for correction</strong><span>If something is wrong, the supervisor returns it with a reason. The officer fixes it and resubmits.</span></li>
      <li><strong>Account becomes active</strong><span>Every status change is recorded in the account's history.</span></li>
    </ol>
  </section>

  <section class="fade">
    <h2>What the system does</h2>
    <div class="grid">
      <div class="card"><h3>Maker-checker control</h3><p>The person who creates an application is never the person who approves it.</p></div>
      <div class="card"><h3>Customer profile (KYC)</h3><p>Occupation, monthly income, TIN and nationality are collected and can be corrected after a return.</p></div>
      <div class="card"><h3>Audit and history</h3><p>Status changes and who made them are kept for every application.</p></div>
      <div class="card"><h3>Public status lookup</h3><p>Applicants enter a CIF or account number and see only a status and account type. No personal details or internal reasons are shown.</p></div>
    </div>
  </section>

  <section class="fade">
    <h2>Roles</h2>
    <div class="grid">
      <div class="card"><h3>Applicant</h3><ul><li>Check application status</li><li>View rates and fees</li></ul></div>
      <div class="card"><h3>Officer</h3><ul><li>Create applications</li><li>Correct returned applications</li></ul></div>
      <div class="card"><h3>Supervisor</h3><ul><li>Verify documents</li><li>Approve or return</li></ul></div>
    </div>
  </section>

  <section class="fade">
    <h2>Limits of this demo</h2>
    <div class="note">
      This is a learning project and is not an official Commercial Bank of Ethiopia service. Approval stops at the branch supervisor, with no head-office review. It does not capture photos or fingerprints, and its working-day count skips weekends only, not public holidays.
    </div>
    <a class="btn" href="landing.php">Back to home</a>
  </section>
</main>
<footer>Savings Account Demo &middot; educational project</footer>
</body>
</html>
