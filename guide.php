<?php
require 'inc.php';
page_top('Applicant Guide');
$lists = [
  'before' => ['Before the Visit', 'calendar', [
    'Call or visit the branch and ask which original identification documents it accepts.',
    'Decide which account type fits you (ordinary, youth, educational, women\'s, wedding or holiday) and note its rate.',
    'Plan how you will reach ETB 100 if you rely on the zero-balance option.',
  ]],
  'at' => ['At the Branch', 'bank', [
    'Ask for an individual personal savings account and the application form.',
    'Complete the form and submit it with your original identification.',
    'Go through the bank\'s identity verification.',
    'Ask for the Key Facts Statement and the current tariff for your account.',
    'Confirm the 15-day opening-balance condition and the cost of any debit card or digital service.',
    'Read the account-opening agreement, sign it, and keep a copy of every document you receive.',
  ]],
  'after' => ['After Opening', 'check', [
    'Deposit enough to reach ETB 100 within 15 days.',
    'Keep at least ETB 100 so the account stays open.',
    'Check that interest is credited on the last business day of the month.',
    'Protect your account credentials and report suspicious activity through official CBE channels.',
  ]],
];
?>
<div class="page-heading">
  <div>
    <h1><?= icon('guide') ?> Applicant Guide</h1>
    <p class="sub">A step-by-step guide to opening a CBE personal savings account. Tick items as you go — your ticks stay in this browser only.</p>
  </div>
</div>

<?php foreach ($lists as $key => [$title, $ic, $items]): ?>
<div class="card">
  <div class="card-header">
    <h2><?= icon($ic) ?> <?= e($title) ?></h2>
  </div>
  <ul class="checklist">
    <?php foreach ($items as $i => $text): ?>
      <li>
        <label>
          <input type="checkbox" data-k="<?= e($key . $i) ?>">
          <span><?= e($text) ?></span>
        </label>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endforeach; ?>

<!-- Interest calculator -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('tariff') ?> Interest Calculator</h2>
  </div>
  <p class="sub" style="margin-bottom:14px;">Interest is based on the lowest balance you kept during the month. This is an estimate, not a guarantee.</p>
  <div class="row" style="margin-bottom:12px;">
    <label for="ct">Account type
      <select id="ct">
        <?php foreach (ACCOUNT_TYPES as $t => $r): ?>
          <option value="<?= $r ?>"><?= e($t) ?> (<?= $r ?>%)</option>
        <?php endforeach; ?>
      </select>
    </label>
    <label for="cb">Lowest balance kept (ETB)
      <input id="cb" type="number" min="0" step="0.01" value="1000">
    </label>
  </div>
  <div id="calc-result" style="background:var(--gold-lt);border:1px solid #f0d080;border-radius:8px;padding:14px 16px;font-size:14px;" role="status">
    <!-- filled by JS -->
  </div>
</div>

<!-- Consumer rights -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('info') ?> Your Consumer Rights</h2>
  </div>
  <p style="margin-bottom:8px;font-size:14px;">The National Bank of Ethiopia lists rights to information, choice, fair treatment, privacy, education and redress. Use them to ask for the Key Facts Statement and fee details before you sign.</p>
  <p class="note"><?= icon('info') ?> CBE Docs advertises remote account opening, digital forms, identity verification and e-signatures. The study did not test it.</p>
</div>

<script>
(function(){
  /* Restore checklist ticks */
  var boxes = document.querySelectorAll('input[data-k]'), store = {};
  try { store = JSON.parse(localStorage.getItem('cbe-checklist') || '{}'); } catch(e){}
  boxes.forEach(function(b){
    b.checked = !!store[b.dataset.k];
    b.onchange = function(){
      store[b.dataset.k] = b.checked;
      try { localStorage.setItem('cbe-checklist', JSON.stringify(store)); } catch(e){}
    };
  });

  /* Interest calculator */
  var ctEl = document.getElementById('ct');
  var cbEl = document.getElementById('cb');
  var out  = document.getElementById('calc-result');

  function fmt(n){ return 'ETB ' + n.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }

  function calc(){
    var r = parseFloat(ctEl.value);
    var b = parseFloat(cbEl.value);
    if (isNaN(b) || b < 0) { out.textContent = 'Enter a balance of 0 or more.'; return; }
    var yearly  = b * r / 100;
    var monthly = yearly / 12;
    out.innerHTML =
      '<dl style="display:grid;grid-template-columns:max-content 1fr;gap:4px 20px;">'
      + '<dt style="color:var(--muted);">Monthly interest</dt><dd><strong>' + fmt(monthly) + '</strong></dd>'
      + '<dt style="color:var(--muted);">Annual interest</dt><dd><strong>' + fmt(yearly) + '</strong></dd>'
      + '</dl>'
      + (b < 100 ? '<p style="color:var(--bad);font-size:12px;margin-top:8px;">Remember: the account must keep at least ETB 100.</p>' : '');
  }

  ctEl.onchange = calc;
  cbEl.oninput  = calc;
  calc();
})();
</script>
<?php page_bottom(); ?>
