<?php
require 'inc.php';
page_top('Tariff');
$fees=[
  ['Opening an account','Free',true],
  ['First passbook at opening','Free',true],
  ['Replacing a lost passbook','ETB 115.00 incl. VAT',false],
  ['Replacing a damaged passbook','ETB 57.50 incl. VAT',false],
  ['Branch cash withdrawal up to ETB 500','Free',true],
  ['Branch cash withdrawal ETB 501–75,000','ETB 12.00 incl. VAT',false],
  ['Closing before six months','Free',true],
];
?>
<div class="page-heading">
  <div>
    <h1>Tariff Management</h1>
    <p class="sub">View and manage tariffs and fees.</p>
  </div>
  <div class="page-heading-actions">
    <button class="btn gold" disabled style="cursor:default;"><?= svg_icon('plus') ?> Add Tariff</button>
  </div>
</div>

<!-- Tab bar (visual) -->
<div style="display:flex;gap:0;border-bottom:2px solid var(--line);margin-bottom:16px;">
  <?php foreach(['Account Tariffs','Service Fees','Interest Rates'] as $i=>$tab): ?>
  <div style="padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer;
       <?= $i===0?'border-bottom:2px solid var(--brand);color:var(--brand);margin-bottom:-2px;':'color:var(--muted);' ?>">
    <?= e($tab) ?>
  </div>
  <?php endforeach; ?>
</div>

<div class="banner" style="margin-bottom:16px;">
  <?= svg_icon('warn') ?>
  <div><strong>Discrepancy notice.</strong> The Key Facts Statement lists the first debit card at ETB 50. The tariff lists domestic debit card issuance at ETB 115 incl. VAT. Ask the branch which applies. Neither source shows an effective date.</div>
</div>

<!-- Interest rates table -->
<div class="card">
  <div class="card-header">
    <h2>Interest Rates by Account Type</h2>
    <span class="tag t-info">Annual rates</span>
  </div>
  <div class="scroll">
    <table>
      <thead>
        <tr>
          <th>Account Type</th>
          <th class="num">Min. Balance (ETB)</th>
          <th class="num">Monthly Rate (%)</th>
          <th class="num">Interest Rate (%)</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach(ACCOUNT_TYPES as $t=>$r):
          $monthly=round($r/12,4);
        ?>
        <tr>
          <td style="font-weight:700;"><?= e($t) ?></td>
          <td class="num">100</td>
          <td class="num"><?= number_format($monthly,2) ?></td>
          <td class="num"><strong><?= rtrim(rtrim(number_format($r,3),'0'),'.') ?></strong></td>
          <td><span class="tag t-ok">Active</span></td>
          <td>
            <button class="btn xs alt" disabled>Edit</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Charges table -->
<div class="card">
  <div class="card-header">
    <h2>Published Charges</h2>
    <span class="tag t-mute"><?= count($fees)+1 ?> items</span>
  </div>
  <div class="scroll">
    <table>
      <thead>
        <tr>
          <th>Service</th>
          <th>Charge</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($fees as [$svc,$chg,$free]): ?>
        <tr>
          <td><?= e($svc) ?></td>
          <td><?= $free?'<span class="tag t-ok">Free</span>':e($chg) ?></td>
          <td><span class="tag t-ok">Active</span></td>
        </tr>
        <?php endforeach; ?>
        <tr style="background:var(--warn-bg);">
          <td>First debit card</td>
          <td><span class="tag t-warn">ETB 50 <em>or</em> ETB 115</span> — sources differ</td>
          <td><span class="tag t-warn">Disputed</span></td>
        </tr>
      </tbody>
    </table>
  </div>
  <p class="note" style="margin-top:8px;"><?= svg_icon('info') ?> Not published: withdrawals above ETB 75,000 and closing after six months. The demo states this rather than inventing a figure.</p>
</div>

<!-- Conditions -->
<div class="card">
  <div class="card-header"><h2>Key Account Conditions</h2></div>
  <ul class="steps-ol">
    <li>Open with zero balance if you have a source of funds that brings balance to ETB 100 within 15 days.</li>
    <li>Keep at least <strong>ETB 100</strong> so the account stays open.</li>
    <li>Identity verification is <strong>mandatory</strong>. Accepted original documents are confirmed at the branch.</li>
    <li>Interest is calculated on the <strong>minimum balance</strong> held during the month, credited on the last business day.</li>
  </ul>
</div>
<?php page_bottom(); ?>
