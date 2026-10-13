<?php
require 'inc.php';
require_login(['officer']);

$errors = []; $v = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    [$ok, $res, $aid] = submit_application($db, $_POST, me());
    if ($ok) { flash($res); redirect('account.php?id=' . $aid); }
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
    <h1><?= icon('plus') ?> New Savings Account Application</h1>
    <p class="sub">Step 1 of the maker-checker process. A supervisor must approve before the account is opened.</p>
  </div>
</div>

<form method="post" novalidate>
  <?= csrf_field() ?>

  <!-- Section 1: Applicant -->
  <div class="card">
    <fieldset style="border:none;padding:0;margin:0;">
      <legend style="font-size:14px;font-weight:800;color:var(--brand);text-transform:uppercase;letter-spacing:.06em;margin-bottom:14px;display:block;">
        <?= icon('user') ?> 1. Applicant Details
      </legend>
      <div class="row">
        <label for="first_name">First Name <span class="required-mark"></span>
          <input id="first_name" name="first_name" value="<?= $val('first_name') ?>" placeholder="e.g. Jalata" required>
          <?= field_err($errors, 'first_name') ?>
        </label>
        <label for="last_name">Last Name <span class="required-mark"></span>
          <input id="last_name" name="last_name" value="<?= $val('last_name') ?>" placeholder="e.g. Tolasa" required>
          <?= field_err($errors, 'last_name') ?>
        </label>
      </div>
      <div class="row">
        <label for="dob">Date of Birth <span class="required-mark"></span>
          <input id="dob" type="date" name="dob" value="<?= $val('dob') ?>" required>
          <?= field_err($errors, 'dob') ?>
        </label>
        <label for="gender">Gender <span class="required-mark"></span>
          <select id="gender" name="gender">
            <?php foreach (['Female', 'Male'] as $g): ?>
              <option<?= $sel('gender', $g) ?>><?= $g ?></option>
            <?php endforeach; ?>
          </select>
          <?= field_err($errors, 'gender') ?>
        </label>
      </div>
      <div class="row">
        <label for="phone">Phone Number <span class="required-mark"></span>
          <input id="phone" name="phone" placeholder="09XXXXXXXX" value="<?= $val('phone') ?>" required>
          <?= field_err($errors, 'phone') ?>
        </label>
        <label for="address">Address <span class="required-mark"></span>
          <input id="address" name="address" placeholder="City, sub-city, woreda" value="<?= $val('address') ?>" required>
          <?= field_err($errors, 'address') ?>
        </label>
      </div>
      <label for="guardian_id">Guardian ID Number
        <input id="guardian_id" name="guardian_id" value="<?= $val('guardian_id') ?>" placeholder="Required only if applicant is under 18">
        <?= field_err($errors, 'guardian_id') ?>
      </label>
    </fieldset>
  </div>

  <!-- Section 2: Identity -->
  <div class="card">
    <fieldset style="border:none;padding:0;margin:0;">
      <legend style="font-size:14px;font-weight:800;color:var(--brand);text-transform:uppercase;letter-spacing:.06em;margin-bottom:14px;display:block;">
        <?= icon('audit') ?> 2. Identity Verification
      </legend>
      <div class="row">
        <label for="id_type">ID Type <span class="required-mark"></span>
          <select id="id_type" name="id_type">
            <?php foreach (ID_TYPES as $t): ?>
              <option<?= $sel('id_type', $t) ?>><?= e($t) ?></option>
            <?php endforeach; ?>
          </select>
          <?= field_err($errors, 'id_type') ?>
        </label>
        <label for="id_number">ID Number <span class="required-mark"></span>
          <input id="id_number" name="id_number" value="<?= $val('id_number') ?>" required>
          <?= field_err($errors, 'id_number') ?>
        </label>
      </div>
      <label class="chk">
        <input type="checkbox" name="id_checked" value="1"<?= $chk('id_checked') ?>>
        <span>I checked the original ID document and verified the applicant's identity in person.</span>
      </label>
      <?= field_err($errors, 'id_checked') ?>
      <p class="note" style="margin-top:8px;"><?= icon('info') ?> The accepted list of original documents is confirmed at the branch. Ask your branch policy if unsure.</p>
    </fieldset>
  </div>

  <!-- Section 3: Account -->
  <div class="card">
    <fieldset style="border:none;padding:0;margin:0;">
      <legend style="font-size:14px;font-weight:800;color:var(--brand);text-transform:uppercase;letter-spacing:.06em;margin-bottom:14px;display:block;">
        <?= icon('accounts') ?> 3. Account Details
      </legend>
      <div class="row">
        <label for="account_type">Account Type <span class="required-mark"></span>
          <select id="at" name="account_type">
            <?php foreach (ACCOUNT_TYPES as $t => $r): ?>
              <option value="<?= e($t) ?>"<?= $sel('account_type', $t) ?>><?= e($t) ?> (<?= $r ?>% p.a.)</option>
            <?php endforeach; ?>
          </select>
          <?= field_err($errors, 'account_type') ?>
        </label>
        <label for="opening_deposit">Opening Deposit (ETB) <span class="required-mark"></span>
          <input id="dep" type="number" step="0.01" min="0" name="opening_deposit" value="<?= $val('opening_deposit', '0') ?>" required>
          <?= field_err($errors, 'opening_deposit') ?>
        </label>
      </div>

      <div id="sof" hidden>
        <label for="source_of_funds">Source of Funds <span class="required-mark"></span>
          <input id="source_of_funds" name="source_of_funds" value="<?= $val('source_of_funds') ?>" placeholder="e.g. salary due on the 25th">
          <?= field_err($errors, 'source_of_funds') ?>
        </label>
        <div class="banner">
          <?= icon('warn') ?>
          <div><strong>Zero-balance opening:</strong> the account must reach ETB 100 within 15 days of approval or it will be flagged for closure review.</div>
        </div>
      </div>

      <label for="card_fee">Debit Card (optional)
        <select id="card_fee" name="card_fee">
          <option value="">Not requested</option>
          <?php foreach (CARD_FEE_OPTIONS as $k => $t): ?>
            <option value="<?= $k ?>"<?= $sel('card_fee', $k) ?>><?= e($t) ?></option>
          <?php endforeach; ?>
        </select>
        <?= field_err($errors, 'card_fee') ?>
      </label>
      <p class="note"><?= icon('info') ?> Sources disagree on the card fee (ETB 50 vs ETB 115 incl. VAT). Confirm with the branch. Fee is collected in cash, not debited from the account.</p>
    </fieldset>
  </div>

  <!-- Section 4: Disclosure -->
  <div class="card">
    <fieldset style="border:none;padding:0;margin:0;">
      <legend style="font-size:14px;font-weight:800;color:var(--brand);text-transform:uppercase;letter-spacing:.06em;margin-bottom:14px;display:block;">
        <?= icon('guide') ?> 4. Disclosure &amp; Agreement
      </legend>
      <div style="display:flex;flex-direction:column;gap:10px;">
        <label class="chk">
          <input type="checkbox" name="kfs_given" value="1"<?= $chk('kfs_given') ?>>
          <span>I gave the applicant the Key Facts Statement and the current tariff.</span>
        </label>
        <?= field_err($errors, 'kfs_given') ?>
        <label class="chk">
          <input type="checkbox" name="conditions_explained" value="1"<?= $chk('conditions_explained') ?>>
          <span>I explained the ETB 100 minimum balance and the 15-day opening condition.</span>
        </label>
        <?= field_err($errors, 'conditions_explained') ?>
        <label class="chk">
          <input type="checkbox" name="agreement_signed" value="1"<?= $chk('agreement_signed') ?>>
          <span>The account-opening agreement was read and signed, and a copy was given to the applicant.</span>
        </label>
        <?= field_err($errors, 'agreement_signed') ?>
      </div>
      <label for="signature" style="margin-top:14px;">Signature Specimen <span class="required-mark"></span>
        <input id="signature" name="signature" value="<?= $val('signature') ?>" placeholder="Type the applicant's full name as the signature specimen">
        <?= field_err($errors, 'signature') ?>
      </label>
    </fieldset>
  </div>

  <div style="display:flex;gap:10px;align-items:center;">
    <button class="btn" type="submit"><?= icon('plus') ?> Submit for Approval</button>
    <a class="btn alt" href="dashboard.php">Cancel</a>
  </div>
</form>

<script>
(function(){
  var d=document.getElementById('dep'), s=document.getElementById('sof');
  function f(){ var n=parseFloat(d.value); s.hidden=!(isNaN(n)||n<100); }
  d.oninput=f; f();
})();
</script>
<?php page_bottom(); ?>
