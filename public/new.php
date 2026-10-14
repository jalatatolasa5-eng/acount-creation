<?php
require_once __DIR__ . '/../src/inc.php';
require_login(['officer']);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    [$ok, $res, $aid] = submit_application($db, $_POST, me());
    if ($ok) { flash($res); redirect(url('account.php?id=' . $aid)); }
    $errors = $res;
    flash('Fix the highlighted fields and resubmit.', 'err');
}
$val = fn($k, $d = '') => e($_POST[$k] ?? $d);
$chk = fn($k) => !empty($_POST[$k]) ? ' checked' : '';
$sel = fn($k, $o) => (($_POST[$k] ?? '') === (string)$o) ? ' selected' : '';

page_top('New Application');
?>
<div class="page-heading">
  <div>
    <h1>New Savings Account Application</h1>
    <p class="sub">Complete all sections. A supervisor must approve before the account is opened.</p>
  </div>
</div>

<div class="steps-indicator">
  <?php foreach (['Customer','Identity','Account','Deposit','Disclosure','Signature'] as $i => $s): ?>
  <div class="step-item active">
    <div class="step-circle"><?= $i+1 ?></div>
    <div class="step-label"><?= e($s) ?></div>
  </div>
  <?php endforeach; ?>
</div>

<form method="post" novalidate>
  <?= csrf_field() ?>

  <div class="card">
    <div class="card-header"><h2>1. Customer Details</h2></div>
    <div class="row">
      <label>First Name <span class="req">*</span>
        <input name="first_name" value="<?= $val('first_name') ?>" placeholder="First name" required>
        <?= field_err($errors,'first_name') ?>
      </label>
      <label>Last Name <span class="req">*</span>
        <input name="last_name" value="<?= $val('last_name') ?>" placeholder="Last name" required>
        <?= field_err($errors,'last_name') ?>
      </label>
    </div>
    <div class="row">
      <label>Date of Birth <span class="req">*</span>
        <input type="date" name="dob" value="<?= $val('dob') ?>" required>
        <?= field_err($errors,'dob') ?>
      </label>
      <label>Gender <span class="req">*</span>
        <select name="gender">
          <?php foreach(['Female','Male'] as $g): ?>
            <option<?= $sel('gender',$g) ?>><?= $g ?></option>
          <?php endforeach; ?>
        </select>
        <?= field_err($errors,'gender') ?>
      </label>
    </div>
    <div class="row">
      <label>Phone <span class="req">*</span>
        <input name="phone" placeholder="09XXXXXXXX" value="<?= $val('phone') ?>" required>
        <?= field_err($errors,'phone') ?>
      </label>
      <label>Address <span class="req">*</span>
        <input name="address" placeholder="City, sub-city, woreda" value="<?= $val('address') ?>" required>
        <?= field_err($errors,'address') ?>
      </label>
    </div>
    <label>Guardian ID <span style="color:var(--muted);font-weight:400;">(under 18 only)</span>
      <input name="guardian_id" value="<?= $val('guardian_id') ?>" placeholder="Leave blank if 18 or over">
      <?= field_err($errors,'guardian_id') ?>
    </label>
  </div>

  <div class="card">
    <div class="card-header"><h2>2. Identity Verification</h2></div>
    <div class="row">
      <label>ID Type <span class="req">*</span>
        <select name="id_type">
          <?php foreach(ID_TYPES as $t): ?>
            <option<?= $sel('id_type',$t) ?>><?= e($t) ?></option>
          <?php endforeach; ?>
        </select>
        <?= field_err($errors,'id_type') ?>
      </label>
      <label>ID Number <span class="req">*</span>
        <input name="id_number" value="<?= $val('id_number') ?>" required>
        <?= field_err($errors,'id_number') ?>
      </label>
    </div>
    <label class="chk">
      <input type="checkbox" name="id_checked" value="1"<?= $chk('id_checked') ?>>
      <span>I checked the original ID and verified the applicant's identity in person. <span class="req">*</span></span>
    </label>
    <?= field_err($errors,'id_checked') ?>
  </div>

  <div class="card">
    <div class="card-header"><h2>3. Account &amp; Deposit</h2></div>
    <div class="row">
      <label>Account Type <span class="req">*</span>
        <select name="account_type">
          <?php foreach(ACCOUNT_TYPES as $t=>$r): ?>
            <option value="<?= e($t) ?>"<?= $sel('account_type',$t) ?>><?= e($t) ?> (<?= $r ?>% p.a.)</option>
          <?php endforeach; ?>
        </select>
        <?= field_err($errors,'account_type') ?>
      </label>
      <label>Opening Deposit (ETB) <span class="req">*</span>
        <input id="dep" type="number" step="0.01" min="0" name="opening_deposit"
               value="<?= $val('opening_deposit','0') ?>" required>
        <?= field_err($errors,'opening_deposit') ?>
      </label>
    </div>
    <div id="sof" hidden>
      <label>Source of Funds <span class="req">*</span>
        <input name="source_of_funds" value="<?= $val('source_of_funds') ?>"
               placeholder="e.g. salary due on the 25th">
        <?= field_err($errors,'source_of_funds') ?>
      </label>
      <div class="banner">
        <?= svg_icon('warn') ?>
        <div><strong>Zero-balance opening:</strong> account must reach ETB 100 within 15 days of approval.</div>
      </div>
    </div>
    <label>Debit Card (optional)
      <select name="card_fee">
        <option value="">Not requested</option>
        <?php foreach(CARD_FEE_OPTIONS as $k=>$t): ?>
          <option value="<?= $k ?>"<?= $sel('card_fee',$k) ?>><?= e($t) ?></option>
        <?php endforeach; ?>
      </select>
      <?= field_err($errors,'card_fee') ?>
    </label>
  </div>

  <div class="card">
    <div class="card-header"><h2>5. Disclosure &amp; Signature</h2></div>
    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:14px;">
      <label class="chk">
        <input type="checkbox" name="kfs_given" value="1"<?= $chk('kfs_given') ?>>
        <span>I gave the applicant the Key Facts Statement and current tariff. <span class="req">*</span></span>
      </label><?= field_err($errors,'kfs_given') ?>
      <label class="chk">
        <input type="checkbox" name="conditions_explained" value="1"<?= $chk('conditions_explained') ?>>
        <span>I explained the ETB 100 minimum and the 15-day condition. <span class="req">*</span></span>
      </label><?= field_err($errors,'conditions_explained') ?>
      <label class="chk">
        <input type="checkbox" name="agreement_signed" value="1"<?= $chk('agreement_signed') ?>>
        <span>Agreement read, signed, and a copy given to the applicant. <span class="req">*</span></span>
      </label><?= field_err($errors,'agreement_signed') ?>
    </div>
    <label>Signature Specimen <span class="req">*</span>
      <input name="signature" value="<?= $val('signature') ?>"
             placeholder="Type the applicant's full name">
      <?= field_err($errors,'signature') ?>
    </label>
  </div>

  <div style="display:flex;gap:10px;">
    <button class="btn gold" type="submit"><?= svg_icon('check') ?> Submit for Approval</button>
    <a class="btn alt" href="<?= url('dashboard.php') ?>">Cancel</a>
  </div>
</form>

<?php page_bottom(); ?>
