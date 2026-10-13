<?php
require 'inc.php';
page_top('Rates and fees');
$fees = [
    ['Opening an account', 'Free'], ['First passbook at opening', 'Free'],
    ['Replacing a lost passbook', 'ETB 115.00 incl. VAT'], ['Replacing a damaged passbook', 'ETB 57.50 incl. VAT'],
    ['Branch cash withdrawal up to ETB 500', 'Free'], ['Branch cash withdrawal of ETB 501 to 75,000', 'ETB 12.00 incl. VAT'],
    ['Closing a savings account before six months', 'Free'],
];
?>
<h1>Rates and fees</h1>
<div class="banner"><b>Check before you sign.</b> The savings Key Facts Statement lists the first debit card at ETB 50. The tariff page lists domestic debit card issuance at ETB 115 including VAT. Ask the branch which applies to your account. The sources also show no effective date, so ask for the current tariff.</div>
<div class="card">
  <h2>Annual interest rates</h2>
  <p class="sub">Interest is calculated on the minimum balance kept during the month and credited on the last business day of the month.</p>
  <div class="scroll"><table><tr><th>Account type</th><th class="num">Annual rate</th></tr>
  <?php foreach (ACCOUNT_TYPES as $t => $r) echo '<tr><td>' . e($t) . '</td><td class="num">' . rtrim(rtrim(number_format($r, 3), '0'), '.') . '%</td></tr>'; ?>
  </table></div>
  <p class="note">Example: ETB 1,000 kept for a full year at 7% earns ETB 70. This is an illustration, not a guarantee.</p>
</div>
<div class="card">
  <h2>Published charges</h2>
  <div class="scroll"><table><tr><th>Service</th><th>Charge</th></tr>
  <?php foreach ($fees as [$s, $c]) echo '<tr><td>' . e($s) . '</td><td>' . e($c) . '</td></tr>'; ?>
  <tr><td>First debit card</td><td>ETB 50 or ETB 115 incl. VAT (sources differ)</td></tr>
  </table></div>
  <p class="note">Not published in the sources: withdrawals above ETB 75,000 and closing after six months. The demo does not invent a charge for these.</p>
</div>
<div class="card">
  <h2>Account conditions</h2>
  <ul>
    <li>Open with a zero balance if you have a reasonable source of funds that brings the balance to at least ETB 100 within 15 days.</li>
    <li>Keep at least ETB 100 so the account stays open.</li>
    <li>Identity verification is mandatory. Accepted original documents are confirmed at the branch.</li>
  </ul>
</div>
<?php page_bottom();
