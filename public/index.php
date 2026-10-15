<?php
require_once __DIR__ . '/../src/inc.php';
page_top('Welcome');

$acct = 'tariff.php';
foreach (['open.php','open_account.php','register.php','apply.php','login.php'] as $c) {
  if (file_exists(__DIR__ . '/' . $c)) { $acct = $c; break; }
}
$facts = [
  ['7%','Interest a year'],
  ['Free','Account opening'],
  ['ETB 500','Free withdrawals up to'],
  ['ETB 100','Minimum balance'],
];
$services = [
  ['🏦','Open an account','Start a savings account in a few minutes at the branch.',$acct],
  ['💰','Rates & Fees','See exactly what everything costs, with a withdrawal calculator.','tariff.php'],
  ['📍','Visit the branch','Wayu Branch, Horo Guduru. Bring your ID and we will do the rest.','#visit'],
];
$steps = [
  ['1','Bring your ID','A valid ID is all you need to begin.'],
  ['2','Open your account','Opening is free. We set everything up with you.'],
  ['3','Get your passbook','Your first passbook is free. Start saving today.'],
];
?>
<style>
.hm{--g:var(--gold,#c9a227);--gd:var(--gold-dk,#8a6d12);max-width:960px;margin:0 auto;padding:8px 4px 40px}
.hm *{box-sizing:border-box}
.hm .rv{opacity:0;transform:translateY(22px);transition:opacity .6s ease,transform .6s ease}
.hm .rv.in,.hm:not(.js) .rv{opacity:1;transform:none}
.hm-hero{background:linear-gradient(135deg,#1a0533,#3b1068);color:#fff;border-radius:20px;padding:56px 32px;position:relative;overflow:hidden;text-align:center}
.hm-hero:before,.hm-hero:after{content:"";position:absolute;border-radius:50%;background:radial-gradient(var(--g),transparent 70%);animation:hmp 6s ease-in-out infinite}
.hm-hero:before{width:260px;height:260px;right:-70px;top:-70px;opacity:.35}
.hm-hero:after{width:200px;height:200px;left:-60px;bottom:-80px;opacity:.25;animation-delay:2s}
@keyframes hmp{50%{transform:scale(1.25);opacity:.15}}
.hm-hero>*{position:relative;z-index:1}
.hm-kick{display:inline-block;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:999px;padding:5px 14px;font-size:13px;margin-bottom:16px}
.hm-hero h1{margin:0 0 12px;font-size:38px;line-height:1.15}
.hm-hero h1 span{color:#ffd96b}
.hm-hero p{margin:0 auto;max-width:540px;opacity:.88;font-size:17px}
.hm-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:26px}
.hm-btn{display:inline-block;padding:13px 26px;border-radius:999px;font-weight:700;text-decoration:none;transition:transform .2s,box-shadow .2s}
.hm-btn:hover{transform:translateY(-2px)}
.hm-btn.p{background:var(--g);color:#1a0533;box-shadow:0 6px 18px rgba(201,162,39,.4)}
.hm-btn.s{border:2px solid rgba(255,255,255,.5);color:#fff}
.hm-facts{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin:-26px 18px 0;position:relative;z-index:2}
.hm-fact{background:#fff;border:1px solid #ece8f3;border-radius:14px;padding:16px 10px;text-align:center;box-shadow:0 6px 20px rgba(26,5,51,.1)}
.hm-fact b{display:block;font-size:22px;color:var(--gd)}
.hm-fact small{color:#666;font-size:12.5px}
.hm h2.sec{margin:42px 0 16px;font-size:22px;text-align:center}
.hm-svc{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.hm-card{display:block;background:#fff;border:1px solid #ece8f3;border-radius:16px;padding:22px 18px;text-decoration:none;color:inherit;box-shadow:0 2px 10px rgba(26,5,51,.05);transition:transform .25s,box-shadow .25s}
.hm-card:hover{transform:translateY(-5px);box-shadow:0 12px 26px rgba(26,5,51,.12)}
.hm-ico{font-size:30px;margin-bottom:8px}
.hm-card h3{margin:0 0 6px;font-size:18px}
.hm-card p{margin:0;color:#666;font-size:14px}
.hm-steps{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.hm-step{text-align:center;padding:10px}
.hm-num{width:48px;height:48px;border-radius:50%;background:var(--g);color:#1a0533;font-weight:800;font-size:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;box-shadow:0 0 0 7px rgba(201,162,39,.2)}
.hm-step h3{margin:0 0 4px;font-size:17px}
.hm-step p{margin:0;color:#666;font-size:14px}
.hm-visit{margin-top:42px;background:#f7f4fc;border:1px solid #ece8f3;border-radius:18px;padding:26px;text-align:center}
.hm-visit h2{margin:0 0 6px;font-size:20px}
.hm-visit p{margin:0;color:#555}
@media(max-width:700px){.hm-facts{grid-template-columns:1fr 1fr;margin:-20px 8px 0}.hm-svc,.hm-steps{grid-template-columns:1fr}.hm-hero h1{font-size:28px}.hm-hero{padding:40px 20px}}
@media(prefers-reduced-motion:reduce){.hm *,.hm *:before,.hm *:after{animation:none!important;transition:none!important}.hm .rv{opacity:1;transform:none}}
</style>

<div class="hm" id="hm">
  <div class="hm-hero rv">
    <span class="hm-kick">Commercial Bank of Ethiopia · Wayu Branch</span>
    <h1>Welcome to <span>our bank</span></h1>
    <p>Simple savings with clear rates and no surprise fees. Open your account today and see exactly what it costs.</p>
    <div class="hm-btns">
      <a class="hm-btn p" href="<?= e($acct) ?>">Open an account</a>
      <a class="hm-btn s" href="tariff.php">See rates &amp; fees</a>
    </div>
  </div>

  <div class="hm-facts">
    <?php foreach ($facts as $f): ?>
    <div class="hm-fact rv"><b><?= e($f[0]) ?></b><small><?= e($f[1]) ?></small></div>
    <?php endforeach; ?>
  </div>

  <h2 class="sec rv">What would you like to do?</h2>
  <div class="hm-svc">
    <?php foreach ($services as $s): ?>
    <a class="hm-card rv" href="<?= e($s[3]) ?>">
      <div class="hm-ico"><?= $s[0] ?></div>
      <h3><?= e($s[1]) ?></h3>
      <p><?= e($s[2]) ?></p>
    </a>
    <?php endforeach; ?>
  </div>

  <h2 class="sec rv">Getting started is easy</h2>
  <div class="hm-steps">
    <?php foreach ($steps as $s): ?>
    <div class="hm-step rv">
      <div class="hm-num"><?= e($s[0]) ?></div>
      <h3><?= e($s[1]) ?></h3>
      <p><?= e($s[2]) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="hm-visit rv" id="visit">
    <h2>Visit us</h2>
    <p>Commercial Bank of Ethiopia, Wayu Branch (Horo Guduru). Ask at the branch for opening hours.</p>
  </div>
</div>

<script>
(function(){
  var root=document.getElementById('hm'); root.classList.add('js');
  var els=root.querySelectorAll('.rv');
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(en){en.forEach(function(x){if(x.isIntersecting){x.target.classList.add('in');io.unobserve(x.target);}});},{threshold:.15});
    els.forEach(function(e){io.observe(e);});
  } else els.forEach(function(e){e.classList.add('in');});
})();
</script>
<?php page_bottom(); ?>
