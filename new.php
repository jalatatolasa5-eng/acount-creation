<?php
require 'inc.php';
require_login(['officer']);

$errors = []; $v = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    [$ok, $res, $aid] = submit_application($db, $_POST, me());
    if ($ok) { flash($res); redirect('account.php?id=' . $aid); }
    $errors = $res;
    flash('Fix the highlighted fields.', 'err');
}
$val = fn($k, $d = '') => e($_POST[$k] ?? $d);
$chk = fn($k) => !empty($_POST[$k]) ? ' checked' : '';
$sel = fn($k, $o) => (($_POST[$k] ?? '') === (string)$o) ? ' selected' : '';
page_top('New application');
?>
<h1>New savings account application</h1>
<p class="sub">Step 1 of the maker-checker process. A supervisor must approve before the account opens.</p>
<form class="card" method="post" novalidate>
  <?= csrf_field() ?>
  <fieldset><legend>Applicant</legend>
    <div class="row">
      <label>First name<input name="first_name" value="<?= $val('first_name') ?>"><?= field_err($errors, 'first_name') ?></label>
      <label>Last name<input name="last_name" value="<?= $val('last_name') ?>"><?= field_err($errors, 'last_name') ?></label>
    </div>
    <div class="row">
      <label>Date of birth<input type="date" name="dob" value="<?= $val('dob') ?>"><?= field_err($errors, 'dob') ?></label>
      <label>Gender<select name="gender"><?php foreach (['Female', 'Male'] as $g) echo '<option' . $sel('gender', $g) . '>' . $g . '</option>'; ?></select><?= field_err($errors, 'gender') ?></label>
    </div>
    <div class="row">
      <label>Phone<input name="phone" placeholder="09XXXXXXXX" value="<?= $val('phone') ?>"><?= field_err($errors, 'phone') ?></label>
      <label>Address<input name="address" placeholder="City, sub-city, woreda" value="<?= $val('address') ?>"><?= field_err($errors, 'address') ?></label>
    </div>
    <label>Guardian ID number (only if under 18)<input name="guardian_id" value="<?= $val('guardian_id') ?>"><?= field_err($errors, 'guardian_id') ?></label>
  </fieldset>

  <fieldset><legend>Identity verification</legend>
    <div class="row">
      <label>ID type<select name="id_type"><?php foreach (ID_TYPES as $t) echo '<option' . $sel('id_type', $t) . '>' . e($t) . '</option>'; ?></select><?= field_err($errors, 'id_type') ?></label>
      <label>ID number<input name="id_number" value="<?= $val('id_number') ?>"><?= field_err($errors, 'id_number') ?></label>
    </div>
    <label class="chk"><input type="checkbox" name="id_checked" value="1"<?= $chk('id_checked') ?>> I checked the original ID and verified the applicant's identity.</label><?= field_err($errors, 'id_checked') ?>
    <p class="note">The study found no universal list of accepted documents. Confirm accepted originals with your branch policy.</p>
  </fieldset>

  <fieldset><legend>Account</legend>
    <div class="row">
      <label>Account type<select name="account_type" id="at"><?php foreach (ACCOUNT_TYPES as $t => $r) echo '<option value="' . e($t) . '"' . $sel('account_type', $t) . '>' . e($t) . ' (' . $r . '%)</option>'; ?></select><?= field_err($errors, 'account_type') ?></label>
      <label>Opening deposit (ETB)<input type="number" step="0.01" min="0" name="opening_deposit" id="dep" value="<?= $val('opening_deposit', '0') ?>"><?= field_err($errors, 'opening_deposit') ?></label>
    </div>
    <div id="sof" hidden>
      <label>Source of funds (needed when opening under ETB 100)<input name="source_of_funds" value="<?= $val('source_of_funds') ?>" placeholder="e.g. salary due on the 25th"><?= field_err($errors, 'source_of_funds') ?></label>
      <p class="banner">Zero-balance opening: the account must reach ETB 100 within 15 days of approval or it is flagged for closure review.</p>
    </div>
    <label>Debit card (optional)<select name="card_fee">
      <option value="">Not requested</option>
      <?php foreach (CARD_FEE_OPTIONS as $k => $t) echo '<option value="' . $k . '"' . $sel('card_fee', $k) . '>' . e($t) . '</option>'; ?>
    </select><?= field_err($errors, 'card_fee') ?></label>
    <p class="note">The sources disagree on the card fee. Pick the fee the branch confirms. It is collected in cash, not taken from the account.</p>
  </fieldset>

  <fieldset><legend>Disclosure and agreement</legend>
    <label class="chk"><input type="checkbox" name="kfs_given" value="1"<?= $chk('kfs_given') ?>> I gave the applicant the Key Facts Statement and current tariff.</label><?= field_err($errors, 'kfs_given') ?>
    <label class="chk"><input type="checkbox" name="conditions_explained" value="1"<?= $chk('conditions_explained') ?>> I explained the ETB 100 minimum and the 15-day opening condition.</label><?= field_err($errors, 'conditions_explained') ?>
    <label class="chk"><input type="checkbox" name="agreement_signed" value="1"<?= $chk('agreement_signed') ?>> The agreement was read and signed, and a copy was given to the applicant.</label><?= field_err($errors, 'agreement_signed') ?>
    <label>Signature specimen (type the applicant's full name)<input name="signature" value="<?= $val('signature') ?>"><?= field_err($errors, 'signature') ?></label>
  </fieldset>
  <button class="btn" type="submit">Submit for approval</button>
</form>
<script>
(function(){var d=document.getElementById('dep'),s=document.getElementById('sof');function f(){var n=parseFloat(d.value);s.hidden=!(isNaN(n)||n<100)}d.oninput=f;f()})();
</script>
<?php page_bottom();
