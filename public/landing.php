<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Open Your Savings Account | Commercial Bank of Ethiopia (Demo)</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Caveat:wght@600&display=swap" rel="stylesheet">
<style>
:root{--g:#0b3d3a;--g2:#0f5a52;--gold:#f5b82e;--gold2:#ffe9b0;--ink:#12302d;--mut:#5b6f6c;--bg:#f4f8f6;--card:#fff}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:var(--ink);background:var(--bg);line-height:1.55}
a{color:inherit;text-decoration:none}
.wrap{max-width:1200px;margin:0 auto;padding:0 24px}
#bar{position:fixed;top:0;left:0;height:3px;width:0;background:var(--gold);z-index:100}
header{position:fixed;top:0;left:0;right:0;z-index:50;background:var(--g);color:#fff;transition:padding .3s,box-shadow .3s;padding:14px 0}
header.small{padding:7px 0;box-shadow:0 6px 24px #0005}
header .wrap{display:flex;align-items:center;justify-content:space-between;gap:20px}
.logo{display:flex;align-items:center;gap:12px}
.logo i{width:44px;height:44px;border-radius:50%;background:radial-gradient(circle at 30% 30%,#ffd76a,#b8860b);display:grid;place-items:center;font-style:normal;font-weight:800;color:var(--g);font-size:13px}
.logo b{display:block;font-size:15px}.logo small{color:var(--gold);font-size:11px}
nav{display:flex;gap:28px;font-size:14px;font-weight:500}
nav a{padding:6px 0;border-bottom:2px solid transparent;transition:.25s}
nav a:hover,nav a.on{border-color:var(--gold)}
.btn{display:inline-flex;align-items:center;gap:10px;padding:14px 26px;border-radius:40px;font-weight:600;font-size:15px;border:1px solid transparent;transition:transform .2s,box-shadow .2s;cursor:pointer;font-family:inherit}
.btn:hover{transform:translateY(-3px);box-shadow:0 10px 22px #0003}
.gold{background:var(--gold);color:var(--g)}
.dark{background:var(--g2);color:#fff}
.out{background:#fffc;border-color:var(--g);color:var(--g)}
header .btn{padding:10px 20px}
.hero{position:relative;min-height:600px;padding:150px 0 110px;overflow:hidden;background:linear-gradient(180deg,#fbe3bf 0%,#f6efe0 45%,#d6ead9 100%)}
.hero svg.sky{position:absolute;left:0;bottom:0;width:100%;height:60%}
.hero .wrap{position:relative;z-index:2}
.tag{display:inline-block;background:var(--gold2);border:1px solid #e9c564;padding:6px 16px;border-radius:30px;font-size:12px;font-weight:700;color:#7a5a05;margin-bottom:20px}
.hero h1{font-size:clamp(36px,5vw,58px);line-height:1.08;font-weight:800;max-width:620px;color:#0a2a27}
.hero p{max-width:480px;margin:20px 0 30px;font-size:17px;color:#223}
.hero .cta{display:flex;gap:16px;flex-wrap:wrap}
.script{position:absolute;right:6%;top:150px;font-family:'Caveat',cursive;font-size:40px;line-height:1;color:#12302d;transform:rotate(-8deg);animation:float 5s ease-in-out infinite;z-index:2}
.script:after{content:"";display:block;height:3px;width:130%;background:var(--gold);margin-top:8px;transform-origin:left;animation:draw 1.2s .8s both}
.person{position:absolute;right:16%;bottom:0;width:300px;height:470px;z-index:1;animation:rise 1.2s .3s both}
@keyframes float{50%{transform:rotate(-8deg) translateY(-12px)}}
@keyframes draw{from{transform:scaleX(0)}}
@keyframes rise{from{transform:translateY(60px);opacity:0}}
.hero h1,.hero p,.hero .cta,.tag{animation:in .8s both}
.tag{animation-delay:.1s}.hero h1{animation-delay:.25s}.hero p{animation-delay:.4s}.hero .cta{animation-delay:.55s}
@keyframes in{from{opacity:0;transform:translateY(26px)}}
.trust{background:#fff;padding:34px 0}
.trust .wrap{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
.t{display:flex;gap:16px;align-items:center;padding:0 12px;border-right:1px solid #e3eae8}
.t:last-child{border:0}
.ic{flex:none;width:56px;height:56px;border-radius:50%;background:#e4f1ed;display:grid;place-items:center;color:var(--g2);transition:transform .4s}
.t:hover .ic{transform:rotateY(180deg) scale(1.1)}
.ic svg{width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.t b{font-size:15px}.t span{display:block;font-size:13px;color:var(--mut)}
section{padding:90px 0}
.kick{font-size:13px;font-weight:700;color:#a07100;margin-bottom:8px}
h2{font-size:clamp(28px,3.4vw,40px);line-height:1.15;margin-bottom:12px}
.lead{color:var(--mut);max-width:560px}
.panel{background:#fff;border-radius:24px;padding:40px;box-shadow:0 12px 40px #0b3d3a14}
.grid2{display:grid;grid-template-columns:2fr 1fr;gap:28px;align-items:start}
.steps{display:grid;grid-template-columns:repeat(4,1fr);gap:26px;margin-top:34px;position:relative}
.steps:before{content:"";position:absolute;top:34px;left:8%;height:2px;width:0;background:repeating-linear-gradient(90deg,var(--gold) 0 8px,transparent 8px 16px);transition:width 1.6s .3s}
.steps.vis:before{width:84%}
.step .ic{width:68px;height:68px;position:relative;z-index:1;background:#e4f1ed;box-shadow:0 0 0 8px #fff}
.step .n{display:inline-grid;place-items:center;width:28px;height:28px;border-radius:50%;background:var(--g);color:#fff;font-size:11px;font-weight:700;margin:14px 0 8px}
.step b{display:block;color:var(--g2)}.step span{font-size:13.5px;color:var(--mut)}
.track{position:relative;border-radius:24px;overflow:hidden;color:#fff;padding:32px;background:linear-gradient(160deg,#0b3d3a,#0f5a52 60%,#1e7a63);min-height:300px}
.track:after{content:"";position:absolute;inset:auto -40px -40px auto;width:220px;height:220px;border-radius:50%;background:#f5b82e22;animation:pulse 4s ease-in-out infinite}
@keyframes pulse{50%{transform:scale(1.25);opacity:.5}}
.track>*{position:relative;z-index:1}
.track h3{font-size:20px;margin-bottom:8px}.track p{font-size:14px;opacity:.9;margin-bottom:18px}
.track input{width:100%;padding:14px 16px;border-radius:10px;border:0;font:inherit;margin-bottom:12px}
.track input:focus{outline:3px solid var(--gold)}
.track .btn{width:100%;justify-content:center;border-radius:10px}
#res{margin-top:14px;font-size:13px;min-height:20px;color:var(--gold2)}
.quote{margin-top:24px;border-radius:24px;padding:28px;background:linear-gradient(135deg,#f9efd6,#fff6e0)}
.quote q{font-size:20px;font-weight:700;display:block;margin-bottom:8px}
.mini{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:28px;padding-top:28px;border-top:1px solid #e3eae8}
.mini h4{margin-bottom:10px}
.mini li{list-style:none;font-size:14px;margin:7px 0;color:var(--mut)}
.mini li:before{content:"\2713";display:inline-grid;place-items:center;width:18px;height:18px;border-radius:50%;background:#14806a;color:#fff;font-size:11px;margin-right:10px}
.mini a{display:block;color:var(--g2);font-size:14px;margin:7px 0}
.mini a:hover{text-decoration:underline}
.dark-sec{background:var(--g);color:#fff}
.dark-sec .lead{color:#b9d1cc}.dark-sec .kick{color:var(--gold)}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:44px}
.pc{background:#ffffff10;border:1px solid #ffffff22;border-radius:20px;padding:30px;transition:transform .3s,background .3s}
.pc:hover{transform:translateY(-8px);background:#ffffff1c}
.pc .big{font-size:44px;font-weight:800;color:var(--gold);line-height:1}
.pc h3{margin:12px 0 8px}.pc p{font-size:14px;color:#c4dad6}
.pc ul{margin-top:14px;padding-left:18px;font-size:13.5px;color:#dbeae7}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;text-align:center}
.stats b{display:block;font-size:46px;font-weight:800;color:var(--g2)}
.stats span{color:var(--mut);font-size:14px}
.tl{position:relative;max-width:760px;margin:44px auto 0;padding-left:36px}
.tl:before{content:"";position:absolute;left:11px;top:0;bottom:0;width:3px;background:linear-gradient(var(--gold) var(--p,0%),#d9e5e1 var(--p,0%))}
.ti{position:relative;background:#fff;border-radius:16px;padding:22px 26px;margin-bottom:22px;box-shadow:0 8px 24px #0b3d3a10}
.ti:before{content:"";position:absolute;left:-33px;top:26px;width:16px;height:16px;border-radius:50%;background:#fff;border:3px solid var(--gold)}
.ti b{color:var(--g2)}.ti p{font-size:14px;color:var(--mut)}
.faq{max-width:780px;margin:40px auto 0}
details{background:#fff;border-radius:14px;margin-bottom:12px;padding:0 24px;box-shadow:0 4px 16px #0b3d3a0d}
summary{cursor:pointer;padding:20px 0;font-weight:600;list-style:none;display:flex;justify-content:space-between}
summary:after{content:"+";font-size:22px;color:var(--g2);transition:transform .3s}
details[open] summary:after{transform:rotate(45deg)}
details p{padding-bottom:20px;color:var(--mut);font-size:14.5px}
.final{text-align:center;background:linear-gradient(120deg,var(--g),var(--g2));color:#fff;border-radius:28px;padding:70px 30px;position:relative;overflow:hidden}
.final p{margin:10px auto 28px;max-width:480px;opacity:.9}
footer{background:var(--g);color:#cfe3df;padding:40px 0;font-size:13px}
footer .wrap{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px}
footer a{margin-right:22px}
.rv{opacity:0;transform:translateY(40px);transition:opacity .8s,transform .8s}
.rv.vis{opacity:1;transform:none}
.d1{transition-delay:.12s}.d2{transition-delay:.24s}.d3{transition-delay:.36s}
.tt{position:fixed;right:22px;bottom:22px;width:46px;height:46px;border-radius:50%;background:var(--gold);color:var(--g);border:0;font-size:20px;cursor:pointer;opacity:0;transform:translateY(20px);transition:.3s;z-index:60}
.tt.show{opacity:1;transform:none}
@media(max-width:960px){
 nav{display:none}.script,.person{display:none}
 .trust .wrap,.stats{grid-template-columns:1fr 1fr}.t{border:0}
 .grid2,.cards,.mini{grid-template-columns:1fr}
 .steps{grid-template-columns:1fr 1fr}.steps:before{display:none}
 section{padding:60px 0}.panel{padding:26px}
}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}.rv{opacity:1;transform:none}}
a:focus-visible,button:focus-visible,summary:focus-visible{outline:3px solid var(--gold);outline-offset:3px}
.burger{display:none;width:44px;height:44px;border:0;border-radius:12px;background:#ffffff18;cursor:pointer;position:relative;flex:none}
.burger span,.burger:before,.burger:after{content:"";position:absolute;left:12px;right:12px;height:2px;background:#fff;border-radius:2px;transition:.3s}
.burger:before{top:14px}.burger span{top:21px}.burger:after{top:28px}
.burger.open:before{top:21px;transform:rotate(45deg)}.burger.open span{opacity:0}.burger.open:after{top:21px;transform:rotate(-45deg)}
@media(max-width:960px){
 .burger{display:block}
 header .wrap{gap:10px}
 header .btn{margin-left:auto}
 nav{display:flex;flex-direction:column;gap:0;position:absolute;left:0;right:0;top:100%;background:var(--g);padding:6px 24px 18px;opacity:0;visibility:hidden;transform:translateY(-12px);transition:.3s;box-shadow:0 14px 30px #0005}
 nav.open{opacity:1;visibility:visible;transform:none}
 nav a{padding:14px 0;border-bottom:1px solid #ffffff1a;font-size:16px}
 .logo small{display:none}
}
@media(max-width:560px){
 .logo b{font-size:13px}.logo i{width:38px;height:38px}
 header .btn{padding:8px 14px;font-size:13px}
 .hero{padding:120px 0 70px}
 .hero .cta .btn{width:100%;justify-content:center}
 .stats,.trust .wrap,.steps{grid-template-columns:1fr}
 .t{padding:0}
 footer .wrap{flex-direction:column;text-align:center}
}
</style>
</head>
<body>
<div id="bar"></div>

<header id="hd"><div class="wrap">
  <a class="logo" href="landing.php"><i>CBE</i><span><b>Commercial Bank of Ethiopia</b><small>The Bank for a Brighter Future</small></span></a>
  <nav>
    <a class="on" href="#top">Home</a><a href="#products">Savings Accounts</a><a href="#requirements">Requirements</a><a href="#how">How It Works</a><a href="#faq">Help &amp; Support</a>
  </nav>
  <a class="btn gold" href="login.php">Sign In</a>
  <button class="burger" id="bg" aria-label="Menu" aria-expanded="false"><span></span></button>
</div></header>

<main id="top">
<div class="hero">
  <svg class="sky" viewBox="0 0 1440 400" preserveAspectRatio="none" aria-hidden="true">
    <path d="M0 400V250L180 160 340 240 520 120 700 230 900 140 1100 230 1300 150 1440 220V400Z" fill="#b9cdbb" opacity=".55"/>
    <path d="M0 400V300L220 230 420 290 640 200 860 290 1080 220 1260 290 1440 250V400Z" fill="#8fb398" opacity=".7"/>
    <g fill="#2f6b5c" opacity=".85"><rect x="980" y="120" width="90" height="280"/><rect x="1080" y="200" width="60" height="200"/><rect x="900" y="250" width="70" height="150"/><rect x="1160" y="240" width="80" height="160"/><rect x="760" y="280" width="80" height="120"/></g>
    <path d="M0 400V340Q300 300 700 340T1440 330V400Z" fill="#3f8f6a"/>
  </svg>
  <div class="script">Your Future<br>Starts Here</div>
  <svg class="person" viewBox="0 0 300 470" aria-hidden="true">
    <path d="M40 470C40 330 90 290 150 290s110 40 110 180Z" fill="#d9c6a8"/>
    <path d="M110 290l40 120 40-120Z" fill="#fff"/>
    <rect x="132" y="240" width="36" height="60" rx="14" fill="#8a5a3b"/>
    <ellipse cx="150" cy="190" rx="52" ry="62" fill="#9b6a47"/>
    <path d="M92 190C80 90 150 60 205 110c30 30 15 90 5 120-6-40-20-70-60-80-35 10-50 30-58 40Z" fill="#2a1710"/>
    <path d="M128 210q22 22 44 0" stroke="#fff" stroke-width="5" fill="none" stroke-linecap="round"/>
  </svg>
  <div class="wrap">
    <span class="tag">SAVINGS ACCOUNT SERVICES</span>
    <h1>Open Your Savings Account with CBE</h1>
    <p>Secure your future today. Apply for a savings account, provide the required information and documents, and follow your application through the review process.</p>
    <div class="cta">
      <a class="btn dark" href="new.php">Start an Application &rarr;</a>
      <a class="btn out" href="#products">Learn About Our Savings Accounts</a>
    </div>
  </div>
</div>

<div class="trust"><div class="wrap">
  <div class="t rv"><div class="ic"><svg viewBox="0 0 24 24"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M8.5 12l2.5 2.5L16 9.5"/></svg></div><div><b>Safe &amp; Trusted</b><span>Your information is secure with CBE.</span></div></div>
  <div class="t rv d1"><div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div><div><b>Simple &amp; Convenient</b><span>Complete your application online in a few steps.</span></div></div>
  <div class="t rv d2"><div class="ic"><svg viewBox="0 0 24 24"><path d="M7 3h8l4 4v14H7z"/><path d="M10 12h6M10 16h6"/></svg></div><div><b>Multiple Savings Products</b><span>Choose the account that fits your needs.</span></div></div>
  <div class="t rv d3"><div class="ic"><svg viewBox="0 0 24 24"><path d="M4 14v-2a8 8 0 0116 0v2"/><rect x="3" y="14" width="4" height="6" rx="1"/><rect x="17" y="14" width="4" height="6" rx="1"/></svg></div><div><b>Need Help?</b><span>Our support team is ready to assist you.</span></div></div>
</div></div>

<section id="how"><div class="wrap grid2">
  <div>
    <div class="panel rv">
      <div class="kick">THE APPLICATION PROCESS</div>
      <h2>It's easy. Just follow these steps.</h2>
      <div class="steps" id="steps">
        <div class="step"><div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-5 4-7 8-7s8 2 8 7"/></svg></div><div class="n">01</div><b>Provide Your Details</b><span>Enter your personal information and select the savings account you want to apply for.</span></div>
        <div class="step"><div class="ic"><svg viewBox="0 0 24 24"><path d="M7 3h8l4 4v14H7z"/><path d="M10 12h6M10 16h6"/></svg></div><div class="n">02</div><b>Submit Your Documents</b><span>Upload the required information and supporting documents.</span></div>
        <div class="step"><div class="ic"><svg viewBox="0 0 24 24"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 13l2 2 4-4"/></svg></div><div class="n">03</div><b>Application Review</b><span>The authorized bank reviewer checks your application and records a decision.</span></div>
        <div class="step"><div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></svg></div><div class="n">04</div><b>Receive the Outcome</b><span>See whether your application has been approved, requires correction, or has been declined.</span></div>
      </div>
    </div>
    <div class="panel rv d1" style="margin-top:24px;padding:28px">
      <div class="mini" style="margin:0;padding:0;border:0">
        <div><h4>Before You Begin</h4><ul><li>Valid identification document (e.g., National ID, Passport)</li><li>Proof of address (e.g., utility bill, residence letter)</li><li>Your contact information (phone number and email)</li></ul></div>
        <div><h4>Useful Information</h4><a href="#requirements">View document requirements &rarr;</a><a href="#products">Savings account types and benefits &rarr;</a><a href="#faq">Frequently asked questions &rarr;</a></div>
      </div>
    </div>
  </div>
  <aside>
    <div class="track rv d1">
      <h3>Track Your Application</h3>
      <p>Already applied? Check the status of your application using your reference number.</p>
      <input id="ref" placeholder="Enter your reference number" aria-label="Reference number">
      <button class="btn gold" id="chk">Check Status &rarr;</button>
      <div id="res" aria-live="polite"></div>
    </div>
    <div class="quote rv d2"><q>A stronger tomorrow starts with you.</q>Save a little, often.</div>
  </aside>
</div></section>

<section style="padding:20px 0 70px"><div class="wrap stats">
  <div class="rv"><b data-n="4">0</b><span>Steps to apply</span></div>
  <div class="rv d1"><b data-n="15">0</b><span>Days to complete documents</span></div>
  <div class="rv d2"><b data-n="3">0</b><span>Savings products</span></div>
  <div class="rv d3"><b data-n="24">0</b><span>Hour online access</span></div>
</div></section>

<section class="dark-sec" id="products"><div class="wrap">
  <div class="kick rv">SAVINGS PRODUCTS</div>
  <h2 class="rv">Choose the account that fits you.</h2>
  <p class="lead rv">Compare the demo products below. Check the tariff page for fees and rates.</p>
  <div class="cards">
    <div class="pc rv"><div class="big">Basic</div><h3>Personal Savings</h3><p>An everyday account to start building savings.</p><ul><li>Low opening deposit</li><li>Passbook included</li><li>Interest credited monthly</li></ul></div>
    <div class="pc rv d1"><div class="big">Youth</div><h3>Student &amp; Youth Savings</h3><p>Made for students and first-time savers.</p><ul><li>Simple requirements</li><li>Guardian option for minors</li><li>Goal-based saving</li></ul></div>
    <div class="pc rv d2"><div class="big">Plus</div><h3>Fixed-Term Savings</h3><p>Lock in your money for a higher return.</p><ul><li>Choose your term</li><li>Higher interest</li><li>Clear withdrawal rules</li></ul></div>
  </div>
  <div style="text-align:center;margin-top:40px" class="rv"><a class="btn gold" href="tariff.php">See rates &amp; fees &rarr;</a></div>
</div></section>

<section id="requirements"><div class="wrap">
  <div style="text-align:center"><div class="kick rv">WHAT HAPPENS NEXT</div><h2 class="rv">From first form to active account</h2><p class="lead rv" style="margin:0 auto">Follow the line as you scroll. Each stage tells you what the bank does and what you do.</p></div>
  <div class="tl" id="tl">
    <div class="ti rv"><b>Application submitted</b><p>Your officer records your details, source of funds and any opening deposit.</p></div>
    <div class="ti rv"><b>Supervisor approval</b><p>A supervisor reviews the file. Approval starts a 15-day deadline for any missing documents.</p></div>
    <div class="ti rv"><b>Documents completed</b><p>Bring your ID and proof of address to the branch before the deadline.</p></div>
    <div class="ti rv"><b>Account active</b><p>Deposit, withdraw and earn month-end interest. Fees are listed on the tariff page.</p></div>
  </div>
</div></section>

<section id="faq" style="padding-top:20px"><div class="wrap">
  <div style="text-align:center"><div class="kick rv">HELP &amp; SUPPORT</div><h2 class="rv">Frequently asked questions</h2></div>
  <div class="faq">
    <details class="rv"><summary>What do I need to open an account?</summary><p>A valid ID (National ID or passport), proof of address, and your phone number and email.</p></details>
    <details class="rv"><summary>How long does review take?</summary><p>A supervisor reviews your application. Once approved, you have 15 days to complete any missing documents.</p></details>
    <details class="rv"><summary>Can I open an account with no deposit?</summary><p>In this demo, yes: you can start with a deposit of 0 and give a source of funds.</p></details>
    <details class="rv"><summary>Are these figures official?</summary><p>No. This is a student demo. Rates and fees come from published documents and may change. Confirm with the branch.</p></details>
  </div>
</div></section>

<section style="padding-top:0"><div class="wrap"><div class="final rv">
  <h2>Ready to start saving?</h2><p>It takes a few minutes. Start your application now.</p>
  <a class="btn gold" href="new.php">Start an Application &rarr;</a>
</div></div></section>
</main>

<footer><div class="wrap">
  <span>Commercial Bank of Ethiopia &middot; The Bank for a Brighter Future</span>
  <span><a href="#">Privacy Policy</a><a href="#">Terms &amp; Conditions</a><a href="#">Accessibility</a></span>
  <span>&copy; 2025 Student demo. Not a real bank system.</span>
</div></footer>

<button class="tt" id="tt" aria-label="Back to top">&uarr;</button>

<script>
const $=s=>document.querySelector(s),hd=$('#hd'),bar=$('#bar'),tt=$('#tt'),tl=$('#tl');
function onScroll(){
  const y=scrollY,h=document.documentElement.scrollHeight-innerHeight;
  bar.style.width=(y/h*100)+'%';
  hd.classList.toggle('small',y>40);
  tt.classList.toggle('show',y>600);
  const r=tl.getBoundingClientRect();
  tl.style.setProperty('--p',Math.max(0,Math.min(100,(innerHeight*.6-r.top)/r.height*100))+'%');
}
addEventListener('scroll',onScroll,{passive:true});onScroll();
tt.onclick=()=>scrollTo({top:0,behavior:'smooth'});
const io=new IntersectionObserver(es=>es.forEach(e=>{
  if(!e.isIntersecting)return;
  e.target.classList.add('vis');
  const n=e.target.querySelector&&e.target.querySelector('[data-n]');
  if(n)count(n);
  io.unobserve(e.target);
}),{threshold:.15});
document.querySelectorAll('.rv,#steps').forEach(el=>io.observe(el));
function count(el){
  const to=+el.dataset.n;let t0;
  const f=t=>{t0=t0||t;const p=Math.min((t-t0)/1200,1);el.textContent=Math.round(to*(1-Math.pow(1-p,3)));if(p<1)requestAnimationFrame(f)};
  requestAnimationFrame(f);
}
const links=[...document.querySelectorAll('nav a')];
const so=new IntersectionObserver(es=>es.forEach(e=>{
  if(e.isIntersecting){links.forEach(a=>a.classList.toggle('on',a.getAttribute('href')==='#'+e.target.id))}
}),{rootMargin:'-45% 0px -50% 0px'});
['top','products','requirements','how','faq'].forEach(id=>{const el=document.getElementById(id);el&&so.observe(el)});
$('#chk').onclick=()=>{
  const v=$('#ref').value.trim(),r=$('#res');
  r.textContent=v?('Demo: no live lookup yet for "'+v+'". Log in to see applications.'):'Enter a reference number first.';
};
var bg=document.getElementById('bg'),nv=document.querySelector('nav');
function shut(){bg.classList.remove('open');nv.classList.remove('open');bg.setAttribute('aria-expanded','false')}
bg.onclick=function(){var o=nv.classList.toggle('open');bg.classList.toggle('open',o);bg.setAttribute('aria-expanded',o)};
nv.addEventListener('click',function(e){if(e.target.tagName==='A')shut()});
addEventListener('keydown',function(e){if(e.key==='Escape')shut()});
addEventListener('resize',function(){if(innerWidth>960)shut()});
</script>
</body>
</html>
