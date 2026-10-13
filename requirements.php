<?php
require 'inc.php';
$fr = [
 ['FR-01','Register an applicant with personal details (name, date of birth, gender, phone, address).','3 Findings; 4 At the branch','new.php'],
 ['FR-02','Make identity verification mandatory. An application cannot be submitted or approved without it.','Abstract; 3 Eligibility and identification','new.php, approvals.php'],
 ['FR-03','Record ID type and number, and prevent duplicate customers. The same ID with a different name is rejected.','3 Findings; 5 Recommendations','new.php (lib: validate_application)'],
 ['FR-04','Let the officer choose among six account types and show each annual rate (7%, 7.125%, 7.5%).','3 Interest table','new.php, tariff.php'],
 ['FR-05','Opening is free and the first passbook is free: no fee is charged at opening.','3 Fees','approvals.php, account.php'],
 ['FR-06','Allow zero-balance opening when a source of funds is stated, with a 15-day deadline to reach ETB 100.','3 Opening deposit and balance','new.php, lib: approve_account'],
 ['FR-07','Enforce the ETB 100 minimum balance on withdrawals and fees.','3 Opening deposit and balance','account.php (lib: withdraw)'],
 ['FR-08','Monitor the 15-day deadline: show it, clear it once funded, and flag overdue accounts for closure review.','3 Opening deposit; 4 After opening','account.php, admin.php, dashboard.php'],
 ['FR-09','Record that the KFS and tariff were given, that conditions were explained, and that the agreement was signed.','4 At the branch steps 4 to 6','new.php, approvals.php'],
 ['FR-10','Require supervisor approval (maker-checker). The approver cannot be the submitter. Approval issues the account number.','4 Account-opening process (synthesis)','approvals.php'],
 ['FR-11','Accept deposits into active accounts.','3 Opening deposit; 4 After opening','account.php'],
 ['FR-12','Process branch cash withdrawals: free up to ETB 500, ETB 12 from 501 to 75,000, refuse amounts above 75,000.','3 Fees','account.php (lib: withdraw)'],
 ['FR-13','Calculate interest on the minimum balance kept in the month and credit it on the last business day, once per month.','3 Interest','admin.php (lib: run_interest)'],
 ['FR-14','Replace a passbook: ETB 115 if lost, ETB 57.50 if damaged, both including VAT.','3 Fees','account.php'],
 ['FR-15','Close an account: free before six months, pay out the balance, and block further transactions.','3 Fees','account.php (supervisor)'],
 ['FR-16','Support an optional debit card and show that the sources disagree on its fee (ETB 50 vs ETB 115).','3 Inconsistency','new.php, tariff.php'],
 ['FR-17','Give applicants a public guide: checklist, rates, interest calculator, and consumer rights.','3 to 5; 2 Consumer-protection context','guide.php, tariff.php'],
 ['FR-18','Show each account with its statement and allow it to be printed.','5 Recommendations','account.php'],
 ['FR-19','Role-based access (officer, supervisor), secure login, and an audit trail of every action.','Implied by identity and fee controls','login.php, audit.php'],
 ['FR-20','Warn that fees differ between documents and that no effective date is shown.','3 Inconsistency; 5 Recommendations for CBE','tariff.php, new.php'],
];
$uc = [
 ['UC-01','Prepare for a branch visit','Applicant',
  ['Open the applicant guide.','Tick the before, at-branch and after items.','Use the calculator to estimate interest.'],
  'Browser storage unavailable: the checklist still works but ticks are not remembered. A balance below 0 shows an error.'],
 ['UC-02','Apply for a savings account','Officer',
  ['Open New Application.','Enter applicant, ID, account type and opening deposit.','Confirm ID check, KFS, conditions and agreement.','Submit. The application becomes Pending.'],
  'Missing or invalid field: highlighted, nothing saved. ID registered to a different name: rejected. Same customer already has that type pending or active: rejected. Women\'s savings for a male customer: rejected. Under 18 without guardian ID: rejected. Deposit under ETB 100 without a source of funds: rejected. Any checkbox unticked: rejected.'],
 ['UC-03','Approve or reject an application','Supervisor',
  ['Open Approvals.','Compare details with the officer\'s checks.','Approve: account number issued, opening transaction posted.','Or reject with a reason.'],
  'Supervisor is the submitter: blocked. Application already decided: blocked. Reject without reason: blocked. Zero-balance opening: a 15-day funding deadline is set.'],
 ['UC-04','Deposit money','Officer',
  ['Open the account.','Enter the amount and deposit.','Balance and statement update.'],
  'Amount zero or negative: rejected. Account pending, closed or rejected: rejected. Deposit lifts a zero-balance account to ETB 100: marked Funded.'],
 ['UC-05','Withdraw cash','Officer',
  ['Open the account.','Enter the amount.','Fee is applied by tier and the withdrawal posts.'],
  'Balance after amount and fee would drop below ETB 100: refused. Amount above ETB 75,000: refused. Amount zero or negative: refused.'],
 ['UC-06','Fund a zero-balance account','Officer / Supervisor',
  ['Account opens with under ETB 100.','Officer deposits until the balance reaches ETB 100.','Status changes to Funded.'],
  'Deadline passes first: supervisor runs the deadline check and the account is flagged Overdue for closure review.'],
 ['UC-07','Credit monthly interest','Supervisor',
  ['Move the demo date to the last business day.','Run the interest credit.','Each active account gets interest on its lowest balance of the month.'],
  'Run before the last business day: refused. Run twice in a month: second run credits nothing. Moving into the next month before running interest: blocked. Zero lowest balance: no interest.'],
 ['UC-08','Replace a passbook','Officer',
  ['Open the account.','Choose lost or damaged and how the fee is paid.','Passbook issued.'],
  'Paying from the account would go below ETB 100: refused, collect cash instead.'],
 ['UC-09','Close an account','Supervisor',
  ['Open the account.','Enter a reason and confirm.','Balance is paid out and the account becomes Closed.'],
  'No reason: refused. Account not active: refused. Older than six months: no charge published, none applied.'],
 ['UC-10','Check the fees and rates','Applicant / Staff',
  ['Open Rates and Fees.','Read the discrepancy notice on the debit card fee.'],
  'Not published in the study: withdrawals above ETB 75,000 and closing after six months. The demo states this instead of inventing a number.'],
 ['UC-11','Review the audit trail','Supervisor',
  ['Open Audit Trail.','See who did what and when.'],
  'Staff without the supervisor role are denied.'],
];
$as = [
 'The accepted ID list is not published in the study, so four common types are offered. Confirm with the branch.',
 'Interest is lowest balance in the month × annual rate / 12. The study gives the basis but not the monthly formula.',
 'Saturday and Sunday are non-business days. Public holidays are not modelled.',
 'Applicants under 18 need a guardian ID. The study does not state age rules for youth or educational accounts.',
 "Women's savings is limited to female customers.",
 'The debit card fee is collected in cash and is not deducted from the account, because the study does not settle which fee applies.',
 'A zero-balance account that misses the deadline is flagged for closure review. The study does not say it closes automatically.',
 'The "demo date" is a teaching tool. A real system uses the real date.',
];
page_top('Requirements and Use Cases');
?>
<div class="page-heading">
  <div>
    <h1><?= icon('req') ?> Requirements &amp; Use Cases</h1>
    <p class="sub">Inferred from the study "Opening a Personal Savings Account at the Commercial Bank of Ethiopia" (12 Oct 2026).</p>
  </div>
</div>

<!-- Functional requirements -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('req') ?> Functional Requirements</h2>
    <span class="tag t-info"><?= count($fr) ?> requirements</span>
  </div>
  <div class="scroll">
    <table>
      <thead>
        <tr>
          <th style="width:70px;">ID</th>
          <th>Requirement</th>
          <th style="width:180px;">Source</th>
          <th style="width:160px;">Demo page</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($fr as $row): ?>
          <tr>
            <td><span class="tag t-info"><?= e($row[0]) ?></span></td>
            <td><?= e($row[1]) ?></td>
            <td style="font-size:12px;color:var(--muted);"><?= e($row[2]) ?></td>
            <td style="font-size:12px;font-family:monospace;"><?= e($row[3]) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Use cases -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('check') ?> Use Cases</h2>
    <span class="tag t-info"><?= count($uc) ?> use cases</span>
  </div>
  <p class="sub" style="margin-bottom:14px;">Each use case lists the normal steps and the failure cases the system handles.</p>
  <?php foreach ($uc as $u): ?>
    <div style="border:1px solid var(--line);border-radius:8px;padding:14px 16px;margin-bottom:12px;">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <span class="tag t-info"><?= e($u[0]) ?></span>
        <strong><?= e($u[1]) ?></strong>
        <span class="tag t-mute"><?= e($u[2]) ?></span>
      </div>
      <ol class="steps" style="margin-bottom:8px;">
        <?php foreach ($u[3] as $step): ?>
          <li><?= e($step) ?></li>
        <?php endforeach; ?>
      </ol>
      <p style="font-size:13px;color:var(--muted);"><strong style="color:var(--ink);">Failure / alternative cases:</strong> <?= e($u[4]) ?></p>
    </div>
  <?php endforeach; ?>
</div>

<!-- Assumptions -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('warn') ?> Assumptions to Confirm</h2>
  </div>
  <p class="sub" style="margin-bottom:10px;">The study did not settle these points, so the demo makes a stated choice.</p>
  <ul class="steps" style="padding-left:18px;">
    <?php foreach ($as as $x): ?>
      <li><?= e($x) ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<!-- Non-functional -->
<div class="card">
  <div class="card-header">
    <h2><?= icon('info') ?> Non-functional Requirements</h2>
  </div>
  <ul class="steps" style="padding-left:18px;margin-bottom:0;">
    <li>Works on a phone and on a desktop, no external CDN or fonts needed.</li>
    <li>Passwords are hashed (bcrypt), forms carry CSRF tokens, database queries are prepared and all output is escaped.</li>
    <li>Money is rounded to two decimals; every balance change leaves a transaction row.</li>
    <li>Compatible with both SQLite (default) and MySQL (when <code>use_mysql.txt</code> exists in the project root).</li>
  </ul>
</div>
<?php page_bottom(); ?>
