<?php
// Public About page. Static, no database access.
header('Content-Type: text/html; charset=utf-8');
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>About | CBE Horo Guduru Wayu Branch</title>
<style>
  :root{--p:#5b1a6e;--g:#d4a017;--bg:#faf8fb;--ink:#241a2b;--mute:#6d6275;--line:#e8e1ec;}
  *{box-sizing:border-box}
  body{margin:0;font-family:system-ui,Segoe UI,Arial,sans-serif;background:var(--bg);color:var(--ink);line-height:1.6}
  header{background:var(--p);color:#fff;padding:14px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
  header strong{font-size:16px;letter-spacing:.02em}
  header a{color:#fff;text-decoration:none;font-size:14px;margin-left:18px;opacity:.9}
  header a:hover,header a.on{opacity:1;border-bottom:2px solid var(--g)}
  .hero{padding:56px 24px 36px;max-width:900px;margin:0 auto}
  .pill{display:inline-block;margin:0 0 14px;padding:6px 16px;border:1.5px solid var(--g);border-radius:40px;background:#fff;color:var(--p);font-weight:700;font-size:14px}
  .hero h1{font-size:34px;margin:0 0 10px;color:var(--p)}
  .hero p{font-size:17px;color:var(--mute);max-width:700px;margin:0}
  main{max-width:900px;margin:0 auto;padding:0 24px 64px}
  section{margin-top:40px}
  h2{font-size:20px;color:var(--p);margin:0 0 14px;border-left:4px solid var(--g);padding-left:10px}
  p.lead{margin:0 0 14px;color:var(--ink)}
  .fade{opacity:0;transform:translateY(14px);animation:up .6s ease forwards}
  .fade:nth-of-type(2){animation-delay:.1s}.fade:nth-of-type(3){animation-delay:.2s}
  .fade:nth-of-type(4){animation-delay:.3s}.fade:nth-of-type(5){animation-delay:.4s}
  @keyframes up{to{opacity:1;transform:none}}
  @media (prefers-reduced-motion:reduce){.fade{animation:none;opacity:1;transform:none}}
  .facts{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px}
  .fact{background:#fff;border:1px solid var(--line);border-top:3px solid var(--g);border-radius:10px;padding:16px}
  .fact b{display:block;font-size:24px;color:var(--p)}
  .fact span{font-size:13px;color:var(--mute)}
  .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:14px}
  .card{background:#fff;border:1px solid var(--line);border-radius:10px;padding:16px}
  .card h3{margin:0 0 6px;font-size:15px}
  .card p{margin:0;font-size:14px;color:var(--mute)}
  ol.steps{list-style:none;padding:0;margin:0;display:grid;gap:12px;counter-reset:s}
  ol.steps li{counter-increment:s;background:#fff;border:1px solid var(--line);border-radius:10px;padding:14px 16px 14px 56px;position:relative}
  ol.steps li::before{content:counter(s);position:absolute;left:14px;top:14px;width:28px;height:28px;border-radius:50%;background:var(--p);color:#fff;display:grid;place-items:center;font-weight:700;font-size:13px}
  ol.steps strong{display:block}
  ol.steps span{color:var(--mute);font-size:14px}
  .note{background:#fff7e0;border:1px solid #ecd48a;border-radius:10px;padding:14px 16px;font-size:14px}
  .small{font-size:12px;color:var(--mute);margin-top:10px}
  .btn{display:inline-block;margin-top:18px;background:var(--p);color:#fff;text-decoration:none;padding:10px 18px;border-radius:8px;font-size:14px}
  .btn:hover{background:#471456}
  footer{border-top:1px solid var(--line);padding:20px 24px;text-align:center;font-size:13px;color:var(--mute)}
</style>
</head>
<body>
<header>
  <strong>Commercial Bank of Ethiopia &middot; Horo Guduru Wayu Branch</strong>
  <nav><a href="landing.php">Home</a><a href="about.php" class="on">About</a></nav>
</header>

<div class="hero fade">
  <span class="pill">&#128205; Commercial Bank of Ethiopia &middot; Horo Guduru Wayu Branch</span>
  <h1>At Horo Guduru Wayu, the branch is CBE</h1>
  <p>For most customers, Commercial Bank of Ethiopia is the branch they walk into. Horo Guduru Wayu Branch is where an account is opened, where identity is checked, and where trust starts. This system shows how the branch does that work.</p>
</div>

<main>
  <section class="fade">
    <h2>Commercial Bank of Ethiopia</h2>
    <p class="lead">CBE's history goes back to the State Bank of Ethiopia, founded in 1942, and it was legally established as a share company in 1963. Today it serves the country through a branch network that reaches across Ethiopia, and Horo Guduru Wayu Branch is part of it.</p>
    <div class="facts">
      <div class="fact"><b>1963</b><span>Established as a share company</span></div>
      <div class="fact"><b>40 million+</b><span>Account holders</span></div>
      <div class="fact"><b>1,940+</b><span>Branches</span></div>
    </div>
    <p class="small">Figures are from CBE's published profile and change over time. Confirm current numbers with the bank.</p>
  </section>

  <section class="fade">
    <h2>What the branch stands for</h2>
    <div class="grid">
      <div class="card"><h3>One bank at every counter</h3><p>A customer who opens an account at Horo Guduru Wayu Branch is dealing with CBE itself. The officer and the supervisor act for the bank, so the same checks matter at every branch.</p></div>
      <div class="card"><h3>Know your customer</h3><p>The branch records who the customer is: identity document, photo, occupation, income, TIN and nationality. The account is only opened once this is complete.</p></div>
      <div class="card"><h3>Two people, not one</h3><p>The officer who captures an application is never the person who approves it. A supervisor reviews it first, so one person cannot open an account alone.</p></div>
    </div>
  </section>

  <section class="fade">
    <h2>Opening an account at Horo Guduru Wayu Branch</h2>
    <ol class="steps">
      <li><strong>The applicant visits the branch</strong><span>They bring their ID, a recent photo and their opening deposit, and give them to the officer.</span></li>
      <li><strong>The officer captures the application</strong><span>Identity, customer profile, account type and deposit are entered, and the photo is uploaded with the form.</span></li>
      <li><strong>The supervisor reviews</strong><span>Documents and the photo are checked. If something is wrong, the application goes back to the officer with a reason.</span></li>
      <li><strong>The account is opened</strong><span>The customer gets an account number, and every status change is kept in the account's history.</span></li>
    </ol>
  </section>

  <section class="fade">
    <a class="btn" href="landing.php">Back to home</a>
  </section>
</main>
<footer>CBE Horo Guduru Wayu Branch &middot; Savings account opening</footer>
</body>
</html>
