<?php
require_once __DIR__ . '/../src/inc.php';
page_top('Rates and Fees');

$fees = [
  ['Opening an account','Free',true],
  ['First passbook at opening','Free',true],
  ['Replacing a lost passbook','ETB 115.00 incl. VAT',false],
  ['Replacing a damaged passbook','ETB 57.50 incl. VAT',false],
  ['Branch cash withdrawal up to ETB 500','Free',true],
  ['Branch cash withdrawal ETB 501–75,000','ETB 12.00 incl. VAT',false],
  ['Closing a savings account before six months','Free',true],
];
$INTEREST = 7;
$FREE_LIMIT = 500; $FEE = 12; $MAX_WD = 75000; $MIN_BAL = 100;

$stages = [
  ['1','Open your account','Walk in with your ID. Nothing to pay to get started.',[0,1]],
  ['2','Use it every day','Withdraw at the branch. Small withdrawals cost nothing.',[4,5]],
  ['3','If something happens','Passbook lost or damaged? Replacing it has a fixed price.',[2,3]],
  ['4','Leaving us','Changed your mind early? Closing is free in the first six months.',[6]],
];
?>
<style>
.tf{--g:var(--gold,#c9a227);--gd:var(--gold-dk,#8a6d12);max-width:960px;margin:0 auto;padding:8px 4px 40px}
.tf *{box-sizing:border-box}
.tf .rv{opacity:0;transform:translateY(22px);transition:opacity .6s ease,transform .6s ease}
.tf .rv.in,.tf:not(.js) .rv{opacity:1;transform:none}
.tf-hero{background:linear-gradient(135deg,#1a0533,#3b1068);color:#fff;border-radius:18px;padding:34px 28px;position:relative;overflow:hidden}
.tf-hero h1{margin:0 0 8px;font-size:28px}
.tf-hero p{margin:0;opacity:.85;max-width:560px}
.tf-hero:after{content:"";position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:radial-gradient(var(--g),transparent 70%);opacity:.35;animation:tfp 5s ease-in-out infinite}
@keyframes tfp{50%{transform:scale(1.25);opacity:.2}}
.tf-pills{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
.tf-pill{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:999px;padding:6px 14px;font-size:13px}
.tf-pill b{color:#ffd96b}
.tf-note{display:flex;gap:10px;background:#fff8e1;border:1px solid #f0d98a;color:#6b5200;border-radius:12px;padding:12px 14px;margin:18px 0;font-size:14px}
.tf h2.sec{margin:34px 0 14px;font-size:20px}
.tf-flow{position:relative;padding-left:56px}
.tf-flow:before{content:"";position:absolute;left:21px;top:6px;bottom:6px;width:3px;background:#e6e1ef;border-radius:3px}
.tf-st{position:relative;margin-bottom:22px}
.tf-st .dot{position:absolute;left:-56px;top:0;width:44px;height:44px;border-radius:50%;background:#fff;border:3px solid #e6e1ef;display:flex;align-items:center;justify-content:center;font-weight:700;color:#999;transition:all .5s .15s}
.tf-st.in .dot{background:var(--g);border-color:var(--g);color:#1a0533;box-shadow:0 0 0 6px rgba(201,162,39,.2)}
.tf-st:not(:last-child):after{content:"";position:absolute;left:-35px;top:44px;width:3px;height:0;background:var(--g);transition:height .8s .3s;border-radius:3px}
.tf-st.in:not(:last-child):after{height:calc(100% - 22px)}
.tf-card{background:#fff;border:1px solid #ece8f3;border-radius:14px;padding:16px 18px;box-shadow:0 2px 10px rgba(26,5,51,.05);transition:transform .25s,box-shadow .25s}
.tf-card:hover{transform:translateY(-3px);box-shadow:0 8px 22px rgba(26,5,51,.1)}
.tf-card h3{margin:0 0 4px;font-size:17px}
.tf-card p{margin:0 0 10px;color:#666;font-size:14px}
.tf-row{display:flex;justify-content:space-between;gap:12px;align-items:center;padding:8px 0;border-top:1px dashed #eee;font-size:14px}
.tf-b{font-weight:700;white-space:nowrap;padding:3px 10px;border-radius:999px;background:#fdf1d0;color:var(--gd)}
.tf-b.free{background:#dcf5e4;color:#14753a}
.tf-calc{background:#fff;border:1px solid #ece8f3;border-radius:18px;padding:22px;box-shadow:0 4px 18px rgba(26,5,51,.07)}
.tf-in{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:600px){.tf-in{grid-template-columns:1fr}.tf-flow{padding-left:48px}}
.tf-in label{font-size:13px;color:#555;display:block;margin-bottom:4px}
.tf-in input[type=number]{width:100%;padding:11px 12px;border:1.5px solid #d9d3e6;border-radius:10px;font-size:16px}
.tf-in input[type=range]{width:100%;accent-color:var(--g)}
.tf-res{margin-top:18px;border-radius:14px;padding:16px;background:#f7f4fc;transition:background .3s}
.tf-res.ok{background:#e9f8ee}.tf-res.no{background:#fdeaea}
.tf-big{font-size:30px;font-weight:800}
.tf-res small{display:block;color:#555;margin-top:6px}
.tf-all{width:100%;border-collapse:collapse;font-size:14px}
.tf-all td{padding:10px 8px;border-bottom:1px solid #eee}
.tf-all td:last-child{text-align:right;font-weight:600}
@media(prefers-reduced-motion:reduce){.tf *,.tf *:before,.tf *:after{animation:none!important;transition:none!important}.tf .rv{opacity:1;transform:none}}
</style>

<div class="tf" id="tf">
  <div class="tf-hero rv">
    <h1><?= svg_icon('tariff') ?> Rates &amp; Fees</h1>
    <p>See exactly what your savings account costs, from opening it to closing it. No surprises.</p>
    <div class="tf-pills">
      <span class="tf-pill">Interest <b><?= (int)$INTEREST ?>% a year</b></span>
      <span class="tf-pill">Minimum balance <b>ETB <?= (int)$MIN_BAL ?></b></span>
      <span class="tf-pill">Withdrawals to ETB <?= number_format($FREE_LIMIT) ?> <b>free</b></span>
    </div>
  </div>

  <div class="tf-note rv"><span>⚠</span><div><strong>Discrepancy notice.</strong> The Key Facts Statement lists the first debit card at ETB 50. The tariff lists it at ETB 115 incl. VAT. Ask the branch which applies. Neither source shows an effective date.</div></div>

  <h2 class="sec rv">Your journey with us</h2>
  <div class="tf-flow">
    <?php foreach ($stages as $s): ?>
    <div class="tf-st rv">
      <div class="dot"><?= e($s[0]) ?></div>
      <div class="tf-card">
        <h3><?= e($s[1]) ?></h3>
        <p><?= e($s[2]) ?></p>
        <?php foreach ($s[3] as $i): $f = $fees[$i]; ?>
        <div class="tf-row"><span><?= e($f[0]) ?></span><span class="tf-b <?= $f[2] ? 'free' : '' ?>"><?= e($f[1]) ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <h2 class="sec rv">What will my withdrawal cost?</h2>
  <div class="tf-calc rv">
    <div class="tf-in">
      <div><label for="tfBal">Your balance (ETB)</label><input type="number" id="tfBal" value="2000" min="0"></div>
      <div><label for="tfAmt">Withdrawal amount (ETB)</label><input type="number" id="tfAmt" value="600" min="0"></div>
    </div>
    <input type="range" id="tfRng" min="0" max="<?= (int)$MAX_WD ?>" step="50" value="600" style="margin-top:12px" aria-label="Withdrawal amount">
    <div class="tf-res" id="tfRes" aria-live="polite"></div>
  </div>

  <h2 class="sec rv">All fees at a glance</h2>
  <div class="tf-calc rv">
    <table class="tf-all">
      <?php foreach ($fees as $f): ?>
      <tr><td><?= e($f[0]) ?></td><td><span class="tf-b <?= $f[2] ? 'free' : '' ?>"><?= e($f[1]) ?></span></td></tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>

<script>
(function(){
  var root=document.getElementById('tf'); root.classList.add('js');
  var els=root.querySelectorAll('.rv');
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(en){en.forEach(function(x){if(x.isIntersecting){x.target.classList.add('in');io.unobserve(x.target);}});},{threshold:.18});
    els.forEach(function(e){io.observe(e);});
  } else els.forEach(function(e){e.classList.add('in');});

  var FREE=<?= (int)$FREE_LIMIT ?>, FEE=<?= (int)$FEE ?>, MAX=<?= (int)$MAX_WD ?>, MIN=<?= (int)$MIN_BAL ?>;
  var bal=document.getElementById('tfBal'), amt=document.getElementById('tfAmt'), rng=document.getElementById('tfRng'), res=document.getElementById('tfRes');
  var shown=0, raf;
  function fmt(n){return 'ETB '+n.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});}
  function tween(el,to){cancelAnimationFrame(raf);var from=shown,t0=null;
    function step(t){if(!t0)t0=t;var p=Math.min((t-t0)/350,1);shown=from+(to-from)*p;el.textContent=fmt(shown);if(p<1)raf=requestAnimationFrame(step);}
    raf=requestAnimationFrame(step);}
  function calc(){
    var b=parseFloat(bal.value)||0, a=parseFloat(amt.value)||0;
    if(a<=0){res.className='tf-res';res.innerHTML='Enter an amount to see the fee.';return;}
    if(a>MAX){res.className='tf-res no';res.innerHTML='<b>Refused.</b> Branch withdrawals above ETB '+MAX.toLocaleString()+' are not allowed.';return;}
    var fee=a>FREE?FEE:0, total=a+fee, left=b-total;
    if(left<MIN){res.className='tf-res no';res.innerHTML='<b>Refused.</b> This would leave your balance below the ETB '+MIN+' minimum. The most you can take out is '+fmt(Math.max(0,b-MIN-(b-MIN>FREE?FEE:0)))+'.';return;}
    res.className='tf-res ok';
    res.innerHTML='<div>Fee</div><div class="tf-big" id="tfFee"></div><small>'+(fee?'Withdrawals above ETB '+FREE+' cost ETB '+FEE+' incl. VAT.':'Free: withdrawals up to ETB '+FREE+' cost nothing.')+' Total taken from your account: '+fmt(total)+'. Balance after: '+fmt(left)+'.</small>';
    tween(document.getElementById('tfFee'),fee);
  }
  amt.addEventListener('input',function(){rng.value=Math.min(amt.value||0,MAX);calc();});
  rng.addEventListener('input',function(){amt.value=rng.value;calc();});
  bal.addEventListener('input',calc);
  calc();
})();
</script>
<?php page_bottom(); ?>
