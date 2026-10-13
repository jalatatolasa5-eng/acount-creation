<?php
require 'inc.php';
page_top('Rates and Fees');
$fees = [
    ['Opening an account',                          'Free'],
    ['First passbook at opening',                   'Free'],
    ['Replacing a lost passbook',                   'ETB 115.00 incl. VAT'],
    ['Replacing a damaged passbook',                'ETB 57.50 incl. VAT'],
    ['Branch cash withdrawal up to ETB 500',        'Free'],
    ['Branch cash withdrawal ETB 501 – 75,000',     'ETB 12.00 incl. VAT'],
    ['Closing a savings account before six months', 'Free'],
];
?>
<div class="page-heading">
  <div>
    <h1><?= icon('tariff') ?> Rates &amp; Fees</h1>
    <p class="sub">Published by the Commercial Bank of Ethiopia. Confirm current rates with the branch before signing.</p>
  </div>
</div>

<div class="banner">
  <?= icon('warn') ?>
  <div>
    <strong>Check before you sign.</strong> The savings Key Facts Statement lists the first debit card at ETB 50.
    The tariff page lists domestic debit card issuance at ETB 115 including VAT.
    Ask the branch which applies to your account. Neither source shows an effective date.
  </div>
</div>

<!-- Interest rates -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('tariff') ?> Annual Interest Rates</h2>
  </div>
  <p class="sub" style="margin-bottom:12px;">Interest is calculated on the <strong>minimum balance</strong> kept during the month and credited on the last business day.</p>
  <div class="scroll">
    <table>
      <thead>
        <tr>
          <th>Account Type</th>
          <th class="num">Annual Rate</th>
          <th class="num">Monthly (on ETB 1,000)</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach (ACCOUNT_TYPES as $t => $r):
        $monthly = 1000 * $r / 100 / 12;
      ?>
        <tr>
          <td><?= e($t) ?></td>
          <td class="num"><?= rtrim(rtrim(number_format($r, 3), '0'), '.') ?>%</td>
          <td class="num">ETB <?= number_format($monthly, 2) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p class="note" style="margin-top:10px;"><?= icon('info') ?> Example: ETB 1,000 kept for a full year at 7% earns ETB 70. This is an illustration, not a guarantee.</p>
</div>

<!-- Charges -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('audit') ?> Published Charges</h2>
  </div>
  <div class="scroll">
    <table>
      <thead>
        <tr>
          <th>Service</th>
          <th>Charge</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($fees as [$svc, $chg]): ?>
        <tr>
          <td><?= e($svc) ?></td>
          <td><?= $chg === 'Free' ? '<span class="tag t-ok">Free</span>' : e($chg) ?></td>
        </tr>
      <?php endforeach; ?>
        <tr>
          <td>First debit card</td>
          <td><span class="tag t-warn">ETB 50 <em>or</em> ETB 115 incl. VAT</span> &mdash; sources differ, ask the branch</td>
        </tr>
      </tbody>
    </table>
  </div>
  <p class="note" style="margin-top:10px;"><?= icon('info') ?> Not published in the sources: withdrawals above ETB 75,000 and account closing after six months. The demo states this rather than inventing a figure.</p>
</div>

<!-- Conditions -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('guide') ?> Key Account Conditions</h2>
  </div>
  <ul class="steps" style="padding-left:18px;margin-bottom:0;">
    <li>Open with a <strong>zero balance</strong> if you have a reasonable source of funds that brings the balance to at least ETB 100 within 15 days.</li>
    <li>Keep at least <strong>ETB 100</strong> so the account stays open and eligible for interest.</li>
    <li>Identity verification is <strong>mandatory</strong>. Accepted original documents are confirmed at the branch.</li>
    <li>Interest is calculated on the <strong>minimum balance</strong> held during the month, credited on the last business day.</li>
  </ul>
</div>
<?php page_bottom(); ?>
