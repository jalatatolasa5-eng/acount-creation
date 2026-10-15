<?php
require_once __DIR__ . '/../src/inc.php';
page_top('Welcome');

$acct = 'tariff.php';
foreach (['open.php','open_account.php','register.php','apply.php','login.php'] as $c) {
  if (file_exists(__DIR__ . '/' . $c)) { $acct = $c; break; }
}

$stats = [
  ['40M+','Account holders'],
  ['1,940+','Branches'],
  ['6.6M+','Mobile banking users'],
  ['17M','CBE Birr users'],
  ['8.3M+','Active ATM card holders'],
];
$timeline = [
  ['1942','The beginning','CBE\'s history starts with the establishment of the State Bank of Ethiopia.'],
  ['1963','A share company','CBE is legally established as a share company and grows alongside the country.'],
  ['Today','Banking everywhere','Branches across Ethiopia, plus mobile, internet, ATM and CBE Birr channels.'],
];
$accounts = [
  ['🐖','Savings account','An interest-bearing deposit account. CBE offers different types of savings accounts.'],
  ['🧾','Demand deposit (current)','A non-interest-bearing account that is operated by cheque.'],
  ['⏳','Time deposit','Deposit your money for an agreed term.'],
  ['💵','Foreign currency account','Hold your money in foreign currency.'],
  ['🌍','Diaspora account','Accounts and services for Ethiopians living abroad.'],
];
$digital = [
  ['📱','Mobile Banking','Transfer to CBE or other banks, pay for telecom and government services, request new or replacement cards, and find ATMs and branches.'],
  ['👛','CBE Birr','A mobile wallet. Register at an agent or branch, or self-register in the app with your ID and photo. Pay bills, airtime, fuel, school fees, taxes and more.'],
  ['💻','Internet Banking','Manage your accounts online from your computer.'],
  ['💳','ATM & cards','Withdraw and pay with your card. Request a new or replacement card through mobile banking or a branch.'],
];
$apps = ['CBE Mobile Banking','CBE Birr','EthioDirect','Merchant App'];
$noor = ['Noor deposit accounts','Noor financing','Noor trade services','Noor for the diaspora'];
$credit = [
  ['Personal loan','A consumer loan for personal or household needs.'],
  ['Business loans','Short-term, medium-term and long-term loans.'],
  ['Money transfer & forex','Account-to-account transfers, remittance and foreign exchange.'],
];
$steps = [
  ['1','Visit the branch','Come to the Wayu branch (Horo Guduru) with your identification and ask which documents are required.'],
  ['2','Choose your account','Tell us what you need to save for and we will open the right account with you.'],
  ['3','Get your passbook','Start saving, and check the Rates & Fees page any time to see what things cost.'],
];
$values = ['Integrity','Service Excellence','Professionalism','Empowerment','Innovative Organization','Teamwork','Respect for Diversity','Corporate Citizenship'];
$faq = [
  ['Lost your phone?','Call 951 or visit any CBE branch and ask them to block your mobile banking user.'],
  ['Forgot your mobile banking PIN?','Visit any CBE branch and ask for a PIN reset.'],
  ['Have another account?','Ask a branch to link additional accounts under the same customer ID to your mobile banking.'],
];
?>
<style>
.cb{--g:var(--gold,#c9a227);--gd:var(--gold-dk,#8a6d12);--ink:#1a0533;--pu:#3b1068;max-width:1040px;margin:0 auto;padding:8px 12px 50px;overflow-x:hidden}
.cb *{box-sizing:border-box}
.cb img,.cb svg{max-width:100%}
.cb .rv{opacity:0;transform:translateY(22px);transition:opacity .6s ease,transform .6s ease}
.cb .rv.in,.cb:not(.js) .rv{opacity:1;transform:none}
.cb h2.sec{margin:0 0 6px;font-size:clamp(21px,3.4vw,28px);color:var(--ink)}
.cb .sub{margin:0 0 20px;color:#666;max-width:640px}
.cb section{margin-top:clamp(36px,7vw,64px);scroll-margin-top:70px}
.cb-hero{background:linear-gradient(135deg,#1a0533,#3b1068);color:#fff;border-radius:20px;padding:clamp(30px,7vw,64px) clamp(18px,5vw,40px);position:relative;overflow:hidden}
.cb-hero:before{content:"";position:absolute;width:280px;height:280px;right:-80px;top:-80px;border-radius:50%;background:radial-gradient(var(--g),transparent 70%);opacity:.35;animation:cbp 6s ease-in-out infinite}
@keyframes cbp{50%{transform:scale(1.25);opacity:.15}}
.cb-hero>*{position:relative}
.cb-kick{display:inline-block;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:999px;padding:5px 14px;font-size:13px;margin-bottom:14px}
.cb-hero h1{margin:0 0 12px;font-size:clamp(28px,6vw,48px);line-height:1.12;max-width:700px}
.cb-hero h1 span{color:#ffd96b}
.cb-hero p{margin:0;max-width:600px;opacity:.9;font-size:clamp(15px,2.2vw,18px)}
.cb-btns{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px}
.cb-btn{display:inline-block;padding:13px 24px;border-radius:999px;font-weight:700;text-decoration:none;transition:transform .2s}
.cb-btn:hover{transform:translateY(-2px)}
.cb-btn.p{background:var(--g);color:var(--ink);box-shadow:0 6px 18px rgba(201,162,39,.4)}
.cb-btn.s{border:2px solid rgba(255,255,255,.5);color:#fff}
.cb-pills{display:flex;gap:10px;flex-wrap:wrap;margin-top:22px}
.cb-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:999px;padding:6px 14px;font-size:13px}
.cb-pill b{color:#ffd96b}
.cb-menu{position:sticky;top:0;z-index:20;display:flex;gap:8px;overflow-x:auto;padding:10px 4px;margin-top:14px;background:rgba(255,255,255,.92);backdrop-filter:blur(8px);border-bottom:1px solid #ece8f3;scrollbar-width:none}
.cb-menu::-webkit-scrollbar{display:none}
.cb-menu a{flex:0 0 auto;padding:7px 14px;border-radius:999px;background:#f3eefb;color:var(--pu);font-size:14px;font-weight:600;text-decoration:none}
.cb-menu a:hover{background:var(--g);color:var(--ink)}
.cb-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px}
.cb-stat{background:#fff;border:1px solid #ece8f3;border-radius:14px;padding:18px 10px;text-align:center;box-shadow:0 4px 14px rgba(26,5,51,.06)}
.cb-stat b{display:block;font-size:clamp(22px,4vw,30px);color:var(--gd)}
.cb-stat small{color:#666}
.cb-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px}
.cb-card{background:#fff;border:1px solid #ece8f3;border-radius:16px;padding:20px 18px;box-shadow:0 2px 10px rgba(26,5,51,.05);transition:transform .25s,box-shadow .25s}
.cb-card:hover{transform:translateY(-4px);box-shadow:0 12px 26px rgba(26,5,51,.12)}
.cb-ico{font-size:28px;margin-bottom:6px}
.cb-card h3{margin:0 0 6px;font-size:18px;color:var(--ink)}
.cb-card p{margin:0;color:#666;font-size:14.5px}
.cb-tl{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}
.cb-yr{display:inline-block;background:var(--g);color:var(--ink);font-weight:800;border-radius:999px;padding:4px 14px;margin-bottom:8px}
.cb-band{background:linear-gradient(135deg,#f7f4fc,#fff8e1);border:1px solid #ece8f3;border-radius:20px;padding:clamp(20px,4vw,34px)}
.cb-chips{display:flex;flex-wrap:wrap;gap:10px;margin-top:14px}
.cb-chip{background:#fff;border:1px solid #e3dcf0;border-radius:999px;padding:7px 14px;font-size:14px;color:var(--pu)}
.cb-split{display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start}
.cb-vis{background:var(--ink);color:#fff;border-radius:16px;padding:22px}
.cb-vis h3{margin:0 0 8px;color:#ffd96b}
.cb-vis p{margin:0;opacity:.9}
.cb-steps{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}
.cb-num{width:46px;height:46px;border-radius:50%;background:var(--g);color:var(--ink);font-weight:800;font-size:19px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;box-shadow:0 0 0 6px rgba(201,162,39,.2)}
.cb-help{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px}
.cb-contact{background:var(--ink);color:#fff;border-radius:20px;padding:clamp(22px,4vw,36px);display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px}
.cb-contact h3{margin:0 0 6px;color:#ffd96b;font-size:16px}
.cb-contact a{color:#fff}
.cb-contact big{font-size:28px;font-weight:800}
.cb-foot{margin-top:34px;padding:16px;border-radius:12px;background:#fff8e1;border:1px solid #f0d98a;color:#6b5200;font-size:13.5px}
.cb-foot a{color:#6b5200;font-weight:700}
@media(max-width:700px){.cb-split{grid-template-columns:1fr}.cb-btn{flex:1 1 100%;text-align:center}}
@media(prefers-reduced-motion:reduce){.cb *,.cb *:before{animation:none!important;transition:none!important}.cb .rv{opacity:1;transform:none}}
</style>

<div class="cb" id="cb">
  <div class="cb-hero rv">
    <span class="cb-kick">Commercial Bank of Ethiopia · Wayu Branch, Horo Guduru</span>
    <h1>Welcome. <span>The bank you can always rely on.</span></h1>
    <p>Open a savings account, see exactly what it costs, and bank from your phone. Everything you need to get started is on this page.</p>
    <div class="cb-btns">
      <a class="cb-btn p" href="<?= e($acct) ?>">Open an account</a>
      <a class="cb-btn s" href="tariff.php">Rates &amp; fees</a>
    </div>
    <div class="cb-pills">
      <span class="cb-pill">Savings interest <b>7% a year</b></span>
      <span class="cb-pill">Help line <b>951</b></span>
    </div>
  </div>

  <nav class="cb-menu" aria-label="Page sections">
    <a href="#about">About CBE</a><a href="#accounts">Accounts</a><a href="#digital">Digital banking</a>
    <a href="#noor">CBE Noor</a><a href="#credit">Loans &amp; forex</a><a href="#start">Get started</a><a href="#help">Help</a>
  </nav>

  <section id="about">
    <h2 class="sec rv">Ethiopia's bank, close to you</h2>
    <p class="sub rv">CBE has served the country since 1942. Here is where it stands today.</p>
    <div class="cb-stats">
      <?php foreach ($stats as $s): ?>
      <div class="cb-stat rv"><b><?= e($s[0]) ?></b><small><?= e($s[1]) ?></small></div>
      <?php endforeach; ?>
    </div>
    <div class="cb-tl" style="margin-top:18px">
      <?php foreach ($timeline as $t): ?>
      <div class="cb-card rv"><span class="cb-yr"><?= e($t[0]) ?></span><h3><?= e($t[1]) ?></h3><p><?= e($t[2]) ?></p></div>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="accounts">
    <h2 class="sec rv">Accounts for every need</h2>
    <p class="sub rv">Choose how you want to save, spend or hold your money.</p>
    <div class="cb-grid">
      <?php foreach ($accounts as $a): ?>
      <div class="cb-card rv"><div class="cb-ico"><?= $a[0] ?></div><h3><?= e($a[1]) ?></h3><p><?= e($a[2]) ?></p></div>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="digital">
    <h2 class="sec rv">Bank from anywhere</h2>
    <p class="sub rv">Your phone can act as your personal branch.</p>
    <div class="cb-grid">
      <?php foreach ($digital as $d): ?>
      <div class="cb-card rv"><div class="cb-ico"><?= $d[0] ?></div><h3><?= e($d[1]) ?></h3><p><?= e($d[2]) ?></p></div>
      <?php endforeach; ?>
    </div>
    <div class="cb-chips rv"><?php foreach ($apps as $a): ?><span class="cb-chip">📲 <?= e($a) ?></span><?php endforeach; ?></div>
  </section>

  <section id="noor">
    <div class="cb-band rv">
      <h2 class="sec">CBE Noor: interest-free banking</h2>
      <p class="sub" style="margin-bottom:0">A Sharia-compliant alternative for people who prefer interest-free banking.</p>
      <div class="cb-chips"><?php foreach ($noor as $n): ?><span class="cb-chip"><?= e($n) ?></span><?php endforeach; ?></div>
    </div>
  </section>

  <section id="credit">
    <h2 class="sec rv">Loans, transfers and forex</h2>
    <p class="sub rv">Grow, send and exchange.</p>
    <div class="cb-grid">
      <?php foreach ($credit as $c): ?>
      <div class="cb-card rv"><h3><?= e($c[0]) ?></h3><p><?= e($c[1]) ?></p></div>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="start">
    <div class="cb-split">
      <div>
        <h2 class="sec rv">Start at the Wayu branch</h2>
        <p class="sub rv">Opening a savings account takes three steps.</p>
        <div class="cb-steps" style="grid-template-columns:1fr">
          <?php foreach ($steps as $s): ?>
          <div class="cb-card rv"><div class="cb-num"><?= e($s[0]) ?></div><h3><?= e($s[1]) ?></h3><p><?= e($s[2]) ?></p></div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="cb-vis rv">
        <h3>Our vision</h3>
        <p>To become a world-class commercial bank financially driving Ethiopia's future.</p>
        <h3 style="margin-top:18px">Our values</h3>
        <div class="cb-chips"><?php foreach ($values as $v): ?><span class="cb-chip" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.25);color:#fff"><?= e($v) ?></span><?php endforeach; ?></div>
      </div>
    </div>
  </section>

  <section id="help">
    <h2 class="sec rv">Quick help</h2>
    <p class="sub rv">Common situations and what to do.</p>
    <div class="cb-help">
      <?php foreach ($faq as $f): ?>
      <div class="cb-card rv"><h3><?= e($f[0]) ?></h3><p><?= e($f[1]) ?></p></div>
      <?php endforeach; ?>
    </div>
    <div class="cb-contact rv" style="margin-top:20px">
      <div><h3>Contact center (Ethiopia)</h3><big><a href="tel:951">951</a></big></div>
      <div><h3>From abroad</h3><a href="tel:+251113000000">+251-113-000000</a></div>
      <div><h3>Email</h3><a href="mailto:cbecontactcenter@cbe.com.et">cbecontactcenter@cbe.com.et</a></div>
      <div><h3>Your branch</h3>Wayu Branch, Horo Guduru</div>
    </div>
  </section>

  <div class="cb-foot rv">This is a demo project, not the official Commercial Bank of Ethiopia website. For current figures, products and tariffs, visit <a href="https://combanketh.et" target="_blank" rel="noopener">combanketh.et</a>.</div>
</div>

<script>
(function(){
  var root=document.getElementById('cb'); root.classList.add('js');
  var els=root.querySelectorAll('.rv');
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(en){en.forEach(function(x){if(x.isIntersecting){x.target.classList.add('in');io.unobserve(x.target);}});},{threshold:.12});
    els.forEach(function(e){io.observe(e);});
  } else els.forEach(function(e){e.classList.add('in');});
})();
</script>
<?php page_bottom(); ?>
