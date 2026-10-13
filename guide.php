<?php
require 'inc.php';
page_top('Applicant Guide');
$lists=[
  'before'=>['Before the Visit','calendar',[
    'Call or visit the branch and ask which original identification documents it accepts.',
    'Decide which account type fits you (ordinary, youth, educational, women\'s, wedding or holiday) and note its rate.',
    'Plan how you will reach ETB 100 if you rely on the zero-balance option.',
  ]],
  'at'=>['At the Branch','bank',[
    'Ask for an individual personal savings account and the application form.',
    'Complete the form and submit it with your original identification.',
    'Go through the bank\'s identity verification process.',
    'Ask for the Key Facts Statement and the current tariff for your account.',
    'Confirm the 15-day opening-balance condition and the cost of any debit card or digital service.',
    'Read the account-opening agreement, sign it, and keep a copy of every document you receive.',
  ]],
  'after'=>['After Opening','check',[
    'Deposit enough to reach ETB 100 within 15 days.',
    'Keep at least ETB 100 so the account stays open.',
    'Check that interest is credited on the last business day of the month.',
    'Protect your account credentials and report suspicious activity through official CBE channels.',
  ]],
];
?>
<div class="page-heading">
  <div>
    <h1>Applicant Guide</h1>
    <p class="sub">Step-by-step guide to opening a CBE personal savings account. Tick items as you go — saved in this browser only.</p>
  </div>
</div>

<?php foreach($lists as $key=>[$title,$ic,$items]): ?>
<div class="card">
  <div class="card-header">
    <h2><?= svg_icon($ic) ?> <?= e($title) ?></h2>
  </div>
  <ul class="checklist">
    <?php foreach($items as $i=>$text): ?>
    <li>
      <label>
        <input type="checkbox" data-k="<?= e($key.$i) ?>">
        <span style="font-size:13px;"><?= e($text) ?></span>
      </label>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endforeach; ?>

<!-- Interest calculator -->
<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('tariff') ?> Interest Calculator</h2>
  </div>
  <p class="sub" style="margin-bottom:14px;">Based on the lowest balance kept during the month. Estimate only — not a guarantee.</p>
  <div class="row" style="margin-bottom:14px;">
    <label>Account Type
      <select id="ct">
        <?php foreach(ACCOUNT_TYPES as $t=>$r): ?>
          <option value="<?= $r ?>"><?= e($t) ?> (<?= $r ?>% p.a.)</option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Lowest Balance Kept (ETB)
      <input id="cb" type="number" min="0" step="0.01" value="1000">
    </label>
  </div>
  <div id="calc_out" style="background:var(--gold-lt);border:1px solid #fde68a;border-radius:8px;padding:14px 16px;" role="status"></div>
</div>

<!-- Consumer rights -->
<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('info') ?> Your Consumer Rights</h2>
  </div>
  <p style="font-size:13px;margin-bottom:8px;">The National Bank of Ethiopia lists rights to information, choice, fair treatment, privacy, education and redress. Use them to ask for the Key Facts Statement and fee details before you sign.</p>
  <p class="note"><?= svg_icon('info') ?> CBE Docs advertises remote account opening, digital forms, identity verification and e-signatures. The study did not test it.</p>
</div>

<script>
(function(){
  var boxes=document.querySelectorAll('input[data-k]'),store={};
  try{store=JSON.parse(localStorage.getItem('cbe-checklist')||'{}');}catch(e){}
  boxes.forEach(function(b){
    b.checked=!!store[b.dataset.k];
    b.onchange=function(){store[b.dataset.k]=b.checked;try{localStorage.setItem('cbe-checklist',JSON.stringify(store));}catch(e){}};
  });
  function fmt(n){return 'ETB '+n.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});}
  function calc(){
    var r=parseFloat(document.getElementById('ct').value),b=parseFloat(document.getElementById('cb').value),o=document.getElementById('calc_out');
    if(isNaN(b)||b<0){o.textContent='Enter a balance of 0 or more.';return;}
    var mo=b*r/100/12,yr=b*r/100;
    o.innerHTML='<dl style="display:grid;grid-template-columns:max-content 1fr;gap:4px 20px;font-size:13px;">'
      +'<dt style="color:var(--muted);">Monthly interest</dt><dd><strong>'+fmt(mo)+'</strong></dd>'
      +'<dt style="color:var(--muted);">Annual interest</dt><dd><strong>'+fmt(yr)+'</strong></dd>'
      +'</dl>'+(b<100?'<p style="color:var(--bad);font-size:11px;margin-top:6px;">Remember: account must keep at least ETB 100.</p>':'');
  }
  document.getElementById('ct').onchange=calc;
  document.getElementById('cb').oninput=calc;
  calc();
})();
</script>
<?php page_bottom(); ?>
