<?php
require 'inc.php';
page_top('Applicant guide');
$lists = [
  'before' => ['Before the visit', ['Call or visit the branch and ask which original identification documents it accepts.', 'Decide which account type fits you (ordinary, youth, educational, women\'s, wedding or holiday) and note its rate.', 'Plan how you will reach ETB 100 if you rely on the zero-balance option.']],
  'at' => ['At the branch', ['Ask for an individual personal savings account and the application form.', 'Complete the form and submit it with your original identification.', 'Go through the bank\'s identity verification.', 'Ask for the Key Facts Statement and the current tariff for your account.', 'Confirm the 15-day opening-balance condition and the cost of any debit card or digital service.', 'Read the account-opening agreement, sign it, and keep a copy of every document you receive.']],
  'after' => ['After opening', ['Deposit enough to reach ETB 100 within 15 days.', 'Keep at least ETB 100 so the account stays open.', 'Check that interest is credited on the last business day of the month.', 'Protect your account credentials and report suspicious activity through official CBE channels.']],
];
?>
<h1>Applicant guide</h1>
<p class="sub">Tick items as you go. Your ticks stay in this browser only.</p>
<?php foreach ($lists as $key => [$title, $items]): ?>
<div class="card">
  <h2><?= e($title) ?></h2>
  <ul class="checklist">
  <?php foreach ($items as $i => $text): ?>
    <li><label><input type="checkbox" data-k="<?= $key . $i ?>"> <span><?= e($text) ?></span></label></li>
  <?php endforeach; ?>
  </ul>
</div>
<?php endforeach; ?>

<div class="card">
  <h2>Interest calculator</h2>
  <p class="sub">Interest is based on the lowest balance you kept during the month. This is an estimate, not a guarantee.</p>
  <div class="row">
    <label>Account type<select id="ct"><?php foreach (ACCOUNT_TYPES as $t => $r) echo '<option value="' . $r . '">' . e($t) . ' (' . $r . '%)</option>'; ?></select></label>
    <label>Lowest balance kept (ETB)<input id="cb" type="number" min="0" step="0.01" value="1000"></label>
  </div>
  <p id="co" role="status"></p>
</div>

<div class="card">
  <h2>Your consumer rights</h2>
  <p>The National Bank of Ethiopia lists rights to information, choice, fair treatment, privacy, education and redress. Use them to ask for the Key Facts Statement and fee details before you sign.</p>
  <p class="note">CBE Docs advertises remote account opening, digital forms, identity verification and e-signatures. The study did not test it.</p>
</div>
<script>
(function(){
  var boxes=document.querySelectorAll('input[data-k]'),store={};
  try{store=JSON.parse(localStorage.getItem('cbe-checklist')||'{}')}catch(e){}
  boxes.forEach(function(b){b.checked=!!store[b.dataset.k];b.onchange=function(){store[b.dataset.k]=b.checked;try{localStorage.setItem('cbe-checklist',JSON.stringify(store))}catch(e){}}});
  function calc(){
    var r=parseFloat(document.getElementById('ct').value),b=parseFloat(document.getElementById('cb').value),o=document.getElementById('co');
    if(isNaN(b)||b<0){o.textContent='Enter a balance of 0 or more.';return}
    var y=b*r/100,m=y/12;
    o.textContent='About ETB '+m.toFixed(2)+' a month, or ETB '+y.toFixed(2)+' over a full year.'+(b<100?' Remember the account must keep at least ETB 100.':'');
  }
  document.getElementById('ct').onchange=calc;document.getElementById('cb').oninput=calc;calc();
})();
</script>
<?php page_bottom();
