<?php
require 'inc.php';
$fr=[
 ['FR-01','Register an applicant with personal details (name, DOB, gender, phone, address).','3 Findings; 4 At the branch','new.php'],
 ['FR-02','Make identity verification mandatory.','Abstract; 3 Eligibility','new.php, approvals.php'],
 ['FR-03','Record ID type and number, prevent duplicate customers.','3 Findings; 5 Recommendations','new.php (lib: validate_application)'],
 ['FR-04','Choose among six account types, show each annual rate.','3 Interest table','new.php, tariff.php'],
 ['FR-05','Opening is free; first passbook is free.','3 Fees','approvals.php, account.php'],
 ['FR-06','Allow zero-balance opening with a source of funds; 15-day deadline.','3 Opening deposit','new.php, lib: approve_account'],
 ['FR-07','Enforce ETB 100 minimum balance on withdrawals and fees.','3 Opening deposit','account.php (lib: withdraw)'],
 ['FR-08','Monitor 15-day deadline: show, clear once funded, flag overdue.','3 Opening deposit; 4 After opening','account.php, admin.php'],
 ['FR-09','Record KFS given, conditions explained, agreement signed.','4 At the branch steps 4–6','new.php, approvals.php'],
 ['FR-10','Supervisor approval (maker-checker); approver ≠ submitter; approval issues account number.','4 Account-opening process','approvals.php'],
 ['FR-11','Accept deposits into active accounts.','3 Opening deposit','account.php'],
 ['FR-12','Branch cash withdrawals: free ≤ ETB 500, ETB 12 for 501–75,000, refuse >75,000.','3 Fees','account.php (lib: withdraw)'],
 ['FR-13','Calculate interest on minimum monthly balance; credit on last business day, once/month.','3 Interest','admin.php (lib: run_interest)'],
 ['FR-14','Replace passbook: ETB 115 (lost), ETB 57.50 (damaged), incl. VAT.','3 Fees','account.php'],
 ['FR-15','Close account: free before six months, pay out balance, block further transactions.','3 Fees','account.php (supervisor)'],
 ['FR-16','Optional debit card; sources disagree on fee (ETB 50 vs ETB 115).','3 Inconsistency','new.php, tariff.php'],
 ['FR-17','Public guide: checklist, rates, interest calculator, consumer rights.','3–5','guide.php, tariff.php'],
 ['FR-18','Show account with statement; allow printing.','5 Recommendations','account.php'],
 ['FR-19','Role-based access, secure login, audit trail.','Implied','login.php, audit.php'],
 ['FR-20','Warn that fees differ and no effective date is shown.','3 Inconsistency','tariff.php, new.php'],
];
page_top('Requirements');
?>
<div class="page-heading">
  <div>
    <h1>Requirements &amp; Use Cases</h1>
    <p class="sub">Inferred from the study "Opening a Personal Savings Account at CBE" (12 Oct 2026).</p>
  </div>
  <span class="tag t-info"><?= count($fr) ?> requirements</span>
</div>

<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('req') ?> Functional Requirements</h2>
  </div>
  <div class="scroll">
    <table>
      <thead>
        <tr>
          <th style="width:70px;">ID</th>
          <th>Requirement</th>
          <th style="width:160px;">Source</th>
          <th style="width:150px;">Demo page</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($fr as $r): ?>
        <tr>
          <td><span class="tag t-brand"><?= e($r[0]) ?></span></td>
          <td style="font-size:12px;"><?= e($r[1]) ?></td>
          <td style="font-size:11px;color:var(--muted);"><?= e($r[2]) ?></td>
          <td style="font-size:11px;font-family:monospace;"><?= e($r[3]) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2><?= svg_icon('info') ?> Non-functional Requirements</h2>
  </div>
  <ul class="steps-ol">
    <li>Works on laptop and tablet, no CDN or external fonts needed.</li>
    <li>Passwords hashed (bcrypt), forms carry CSRF tokens, queries prepared, all output escaped.</li>
    <li>Money rounded to two decimals; every balance change leaves a transaction row.</li>
    <li>Compatible with SQLite (default) and MySQL (<code>use_mysql.txt</code> present in project root).</li>
    <li>Accessible: visible focus, labels on every input, roles and status communicated by text, not colour alone.</li>
  </ul>
</div>
<?php page_bottom(); ?>
