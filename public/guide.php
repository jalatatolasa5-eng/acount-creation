<?php
require_once __DIR__ . '/../src/inc.php';
page_top('Applicant Guide');

$sections = [
  'before' => ['Before the Visit',  'calendar', 'Prepare before you go to the branch', [
    'Call or visit the branch and ask which original identification documents it accepts.',
    'Decide which account type fits you (ordinary, youth, educational, women\'s, wedding or holiday) and note its rate.',
    'Plan how you will reach ETB 100 if you rely on the zero-balance option.',
  ]],
  'at' => ['At the Branch', 'bank', 'What to do when you arrive', [
    'Ask for an individual personal savings account and the application form.',
    'Complete the form and submit it with your original identification.',
    'Go through the bank\'s identity verification process.',
    'Ask for the Key Facts Statement and the current tariff for your account.',
    'Confirm the 15-day opening-balance condition and the cost of any debit card or digital service.',
    'Read the account-opening agreement, sign it, and keep a copy of every document you receive.',
  ]],
  'after' => ['After Opening', 'check', 'Keep your account healthy', [
    'Deposit enough to reach ETB 100 within 15 days.',
    'Keep at least ETB 100 so the account stays open.',
    'Check that interest is credited on the last business day of the month.',
    'Protect your account credentials and report suspicious activity through official CBE channels.',
  ]],
];

$total_steps = array_sum(array_map(fn($s) => count($s[3]), $sections));
$phase_colors = [
  'before' => ['var(--gold-dk)',  'var(--gold-lt)'],
  'at'     => ['var(--brand)',    'var(--brand-lt)'],
  'after'  => ['var(--ok)',       'var(--ok-bg)'],
];
?>

<!-- Guide-specific styles (scoped to this page) -->
<style>
.guide-hero{background:linear-gradient(135deg,var(--brand-dk) 0%,var(--brand) 100%);border-radius:14px;padding:28px 30px;margin-bottom:22px;color:#fff;display:flex;align-items:center;gap:24px;flex-wrap:wrap;}
.guide-hero-text h1{color:#fff;font-size:22px;margin-bottom:6px;}
.guide-hero-text p{color:rgba(255,255,255,.75);font-size:13px;max-width:55ch;margin-bottom:0;}
.guide-progress-ring{flex-shrink:0;position:relative;width:80px;height:80px;}
.progress-bar-wrap{background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
.progress-track{flex:1;min-width:200px;height:8px;background:var(--line);border-radius:99px;overflow:hidden;}
.progress-fill{height:100%;background:linear-gradient(90deg,var(--brand),var(--gold));border-radius:99px;transition:width .4s ease;}
.progress-pct{font-size:22px;font-weight:800;color:var(--brand);}
.phase-card{background:var(--surface);border:1.5px solid var(--line);border-radius:12px;margin-bottom:14px;overflow:hidden;transition:border-color .2s;}
.phase-header{display:flex;align-items:center;gap:12px;padding:16px 18px;cursor:pointer;user-select:none;transition:background .15s;}
.phase-header:hover{background:#faf8ff;}
.phase-icon-wrap{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s,color .2s;}
.phase-title{flex:1;}
.phase-title h3{font-size:14px;font-weight:800;color:var(--ink);margin-bottom:1px;}
.phase-title p{font-size:11px;color:var(--muted);margin:0;}
.phase-meta{display:flex;align-items:center;gap:10px;}
.phase-done-count{font-size:11px;font-weight:700;color:var(--muted);}
.phase-chevron{transition:transform .25s;color:var(--muted);}
.phase-card.open .phase-chevron{transform:rotate(180deg);}
.mini-ring{position:relative;width:32px;height:32px;flex-shrink:0;}
.mini-ring svg{transform:rotate(-90deg);}
.mini-ring-pct{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:800;color:var(--brand);}
.phase-body{display:none;padding:0 18px 16px;}
.phase-card.open .phase-body{display:block;}
.check-item{display:flex;align-items:flex-start;gap:12px;padding:10px 12px;border-radius:8px;cursor:pointer;transition:background .12s;margin-bottom:4px;border:1px solid transparent;}
.check-item:hover{background:#faf8ff;}
.check-item.checked{background:#f0faf4;border-color:#a8d8b8;}
.check-box{width:22px;height:22px;border-radius:6px;border:2px solid var(--line);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .15s;margin-top:1px;background:#fff;}
.check-item.checked .check-box{background:var(--ok);border-color:var(--ok);}
.check-box svg{opacity:0;transition:opacity .15s;}
.check-item.checked .check-box svg{opacity:1;}
.check-text{font-size:13px;color:var(--ink);line-height:1.55;transition:color .15s;}
.check-item.checked .check-text{color:var(--muted);text-decoration:line-through;text-decoration-color:rgba(107,33,168,.3);}
.check-num{font-size:10px;font-weight:700;color:var(--muted);margin-top:4px;}
.calc-card{background:var(--surface);border:1.5px solid var(--line);border-radius:12px;margin-bottom:14px;overflow:hidden;}
.calc-header{background:linear-gradient(90deg,var(--gold-lt),#fff);border-bottom:1px solid var(--line);padding:14px 18px;display:flex;align-items:center;gap:10px;}
.calc-body{padding:18px;}
.calc-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;}
@media(max-width:560px){.calc-grid{grid-template-columns:1fr;}}
.calc-result{background:linear-gradient(135deg,var(--brand-dk),var(--brand));border-radius:10px;padding:18px 20px;color:#fff;display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.calc-result-item{text-align:center;}
.calc-result-label{font-size:11px;color:rgba(255,255,255,.65);margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;}
.calc-result-value{font-size:24px;font-weight:800;color:#fff;font-variant-numeric:tabular-nums;}
.calc-result-sub{font-size:11px;color:rgba(255,255,255,.55);margin-top:2px;}
input[type=range]{-webkit-appearance:none;appearance:none;width:100%;height:6px;border-radius:99px;background:var(--line);outline:none;padding:0;margin:8px 0 0;cursor:pointer;}
input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:18px;height:18px;border-radius:50%;background:var(--brand);border:2px solid #fff;box-shadow:0 2px 6px rgba(107,33,168,.3);cursor:pointer;}
.range-labels{display:flex;justify-content:space-between;font-size:10px;color:var(--muted);margin-top:4px;}
.rights-card{background:var(--surface);border:1.5px solid var(--line);border-radius:12px;margin-bottom:14px;overflow:hidden;}
.rights-header{background:linear-gradient(90deg,var(--info-bg),#fff);border-bottom:1px solid var(--line);padding:14px 18px;display:flex;align-items:center;gap:10px;}
.rights-body{padding:18px;}
.right-item{display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid var(--line);font-size:13px;}
.right-item:last-child{border-bottom:none;}
.right-icon{width:28px;height:28px;border-radius:7px;background:var(--info-bg);color:var(--info);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;}
</style>

<!-- Hero -->
<div class="guide-hero">
  <div class="guide-hero-text">
    <h1>Applicant Guide</h1>
    <p>Follow these steps to open your CBE Personal Savings Account. Tick each item as you complete it — your progress is saved in this browser.</p>
  </div>
  <div class="guide-progress-ring">
    <svg width="80" height="80" viewBox="0 0 80 80" aria-hidden="true">
      <circle cx="40" cy="40" r="32" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="7"/>
      <circle id="ring_arc" cx="40" cy="40" r="32" fill="none" stroke="var(--gold)"
              stroke-width="7" stroke-linecap="round"
              stroke-dasharray="201" stroke-dashoffset="201"
              style="transform:rotate(-90deg);transform-origin:40px 40px;transition:stroke-dashoffset .5s ease;"/>
    </svg>
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">
      <div style="font-size:18px;font-weight:800;color:#fff;" id="ring_pct">0%</div>
      <div style="font-size:9px;color:rgba(255,255,255,.6);">done</div>
    </div>
  </div>
</div>

<!-- Progress bar -->
<div class="progress-bar-wrap">
  <div>
    <div class="progress-pct" id="bar_pct">0%</div>
    <div style="font-size:11px;color:var(--muted);">overall progress</div>
  </div>
  <div class="progress-track">
    <div class="progress-fill" id="bar_fill" style="width:0%"></div>
  </div>
  <div style="font-size:13px;font-weight:700;color:var(--ink);white-space:nowrap;" id="bar_label">
    0 of <?= $total_steps ?> steps completed
  </div>
  <button class="btn alt sm" id="reset_btn">↺ Reset</button>
</div>

<!-- Phase cards -->
<?php $global_i = 0; foreach ($sections as $key => [$title, $ic, $desc, $items]):
  [$icon_color, $icon_bg] = $phase_colors[$key];
?>
<div class="phase-card" id="phase_<?= $key ?>" data-phase="<?= $key ?>">
  <div class="phase-header">
    <div class="phase-icon-wrap" style="background:<?= $icon_bg ?>;color:<?= $icon_color ?>;">
      <?= svg_icon($ic) ?>
    </div>
    <div class="phase-title">
      <h3><?= e($title) ?></h3>
      <p><?= e($desc) ?></p>
    </div>
    <div class="phase-meta">
      <div class="mini-ring" id="ring_<?= $key ?>">
        <svg width="32" height="32" viewBox="0 0 32 32">
          <circle cx="16" cy="16" r="12" fill="none" stroke="var(--line)" stroke-width="3"/>
          <circle class="mini-arc" cx="16" cy="16" r="12" fill="none"
                  stroke="<?= $icon_color ?>" stroke-width="3" stroke-linecap="round"
                  stroke-dasharray="75.4" stroke-dashoffset="75.4"
                  style="transform:rotate(-90deg);transform-origin:16px 16px;transition:stroke-dashoffset .3s;"/>
        </svg>
        <div class="mini-ring-pct" id="pct_<?= $key ?>">0</div>
      </div>
      <div class="phase-done-count" id="cnt_<?= $key ?>">0/<?= count($items) ?></div>
      <svg class="phase-chevron" width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
  </div>
  <div class="phase-body">
    <?php foreach ($items as $i => $text): $global_i++; ?>
    <div class="check-item" data-checklist-key="<?= e($key.$i) ?>">
      <div class="check-box">
        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
          <path d="M2 6l3 3 5-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div style="flex:1;">
        <div class="check-text"><?= e($text) ?></div>
        <div class="check-num">Step <?= $global_i ?> of <?= $total_steps ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endforeach; ?>

<!-- Interest Calculator -->
<div class="calc-card">
  <div class="calc-header">
    <div style="width:36px;height:36px;background:var(--gold-lt);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--gold-dk);"><?= svg_icon('tariff') ?></div>
    <div>
      <h2 style="margin-bottom:1px;">Interest Calculator</h2>
      <p class="sub">Based on the lowest balance kept during the month. Estimate only — not a guarantee.</p>
    </div>
  </div>
  <div class="calc-body">
    <div class="calc-grid">
      <label style="margin-bottom:0;">Account Type
        <select id="ct" style="margin-top:6px;">
          <?php foreach (ACCOUNT_TYPES as $t => $r): ?>
            <option value="<?= $r ?>"><?= e($t) ?> (<?= $r ?>%)</option>
          <?php endforeach; ?>
        </select>
      </label>
      <label style="margin-bottom:0;">Lowest Balance Kept (ETB)
        <input id="cb" type="number" min="0" step="100" value="1000" style="margin-top:6px;">
        <input type="range" id="cb_range" min="0" max="50000" step="100" value="1000">
        <div class="range-labels"><span>ETB 0</span><span>ETB 10k</span><span>ETB 25k</span><span>ETB 50k</span></div>
      </label>
    </div>
    <div class="calc-result">
      <div class="calc-result-item">
        <div class="calc-result-label">Monthly Interest</div>
        <div class="calc-result-value" id="cr_monthly">ETB 5.83</div>
        <div class="calc-result-sub">credited last business day</div>
      </div>
      <div class="calc-result-item">
        <div class="calc-result-label">Annual Interest</div>
        <div class="calc-result-value" id="cr_annual">ETB 70.00</div>
        <div class="calc-result-sub">at current rate</div>
      </div>
    </div>
    <div id="calc_out" style="margin-top:8px;font-size:12px;color:var(--bad);display:none;"></div>
  </div>
</div>

<!-- Consumer Rights -->
<div class="rights-card">
  <div class="rights-header">
    <div style="width:36px;height:36px;background:var(--info-bg);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--info);"><?= svg_icon('info') ?></div>
    <div><h2 style="margin-bottom:1px;">Your Consumer Rights</h2>
    <p class="sub">National Bank of Ethiopia — consumer protection framework</p></div>
  </div>
  <div class="rights-body">
    <?php $rights = [
      ['info',  'Right to Information',   'You have the right to receive the Key Facts Statement and the current tariff before you sign anything.'],
      ['check', 'Right to Fair Treatment','The bank must treat every customer fairly and explain all fees, rates and conditions clearly.'],
      ['user',  'Right to Choice',        'You can choose the account type that fits your needs. Ask about all six types and their rates.'],
      ['audit', 'Right to Privacy',       'Your personal and financial information is protected.'],
      ['guide', 'Right to Redress',       'If something goes wrong, you have the right to file a complaint. Ask the branch for the procedure.'],
      ['ok-ic', 'Remote Opening',         'CBE Docs advertises remote account opening, digital forms and e-signatures. The study did not test it.'],
    ];
    foreach ($rights as [$ic, $title, $text]): ?>
    <div class="right-item">
      <div class="right-icon"><?= svg_icon($ic) ?></div>
      <div>
        <strong style="font-size:13px;"><?= e($title) ?></strong>
        <p style="font-size:12px;color:var(--muted);margin:3px 0 0;"><?= e($text) ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php page_bottom(); ?>
