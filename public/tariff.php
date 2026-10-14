<?php
require_once __DIR__ . '/../src/inc.php';
page_top('Rates and Fees');
$fees=[
  ['Opening an account','Free',true],
  ['First passbook at opening','Free',true],
  ['Replacing a lost passbook','ETB 115.00 incl. VAT',false],
  ['Replacing a damaged passbook','ETB 57.50 incl. VAT',false],
  ['Branch cash withdrawal up to ETB 500','Free',true],
  ['Branch cash withdrawal ETB 501–75,000','ETB 12.00 incl. VAT',false],
  ['Closing a savings account before six months','Free',true],
];
?>
<div class="page-heading">
  <div>
    <h1>Rates &amp; Fees</h1>
    <p class="sub">Published savings account rates and charges from CBE. Confirm current figures with the branch.</p>
  </div>
</div>

<div class="banner">
  <?= svg_icon('warn') ?>
  <div><strong>Discrepancy notice.</strong> The Key Facts Statement lists the first debit card at ETB 50. The tariff lists it at ETB 115 incl. VAT. Ask the branch which applies. Neither source shows an effective date.</div>
</div>

<div class="card">
  <div class="card-header"><h2><?= svg_icon('tariff') ?> Annual Interest Rates</h2><span class="tag t-info">By account type</span></div>
  <p class="sub" style="margin-bottom:12px;">Interest calculated on the <strong>minimum balance</strong> kept during the month, credited on the last business day.</p>
  <div class="scroll">
    <table>
      <thead><tr><th>Account Type</th><th class="num">Min. Balance (ETB)</th><th class="num">Annual Rate</th><th class="num">Monthly (on ETB 1,000)</th></tr></thead>
      <tbody>
        <?php foreach(ACCOUNT_TYPES as $t=>$r): $mo=round(1000*$r/100/12,2); ?>
        <tr>
          <td style="font-weight:700;"><?= e($t) ?></td>
          <td class="num">100</td>
          <td class="num"><strong><?= rtrim(rtrim(number_format($r,3),'0'),'.') ?>%</strong></td>
          <td class="num">ETB <?= number_format($mo,2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2><?= svg_icon('audit') ?> Published Charges</h2></div>
  <div class="scroll">
    <table>
      <thead><tr><th>Service</th><th>Charge</th></tr></thead>
      <tbody>
        <?php foreach($fees as [$svc,$chg,$free]): ?>
        <tr><td><?= e($svc) ?></td><td><?= $free?'<span class="tag t-ok">Free</span>':e($chg) ?></td></tr>
        <?php endforeach; ?>
        <tr style="background:var(--warn-bg);">
          <td>First debit card</td>
          <td><span class="tag t-warn">ETB 50 <em>or</em> ETB 115 incl. VAT</span> — sources differ</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2><?= svg_icon('guide') ?> Key Account Conditions</h2></div>
  <ul class="steps-ol">
    <li>Open with zero balance if a source of funds will bring the balance to ETB 100 within <strong>15 days</strong>.</li>
    <li>Keep at least <strong>ETB 100</strong> at all times so the account stays open.</li>
    <li>Identity verification is <strong>mandatory</strong>. Accepted original documents confirmed at the branch.</li>
    <li>Interest calculated on <strong>minimum balance</strong> held during the month, credited on the last business day.</li>
  </ul>
</div>
<?php page_bottom(); ?>
