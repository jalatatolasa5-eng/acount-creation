/**
 * app.js — CBE Savings Account System
 * All client-side JavaScript for the application.
 * No inline scripts in PHP pages.
 */

document.addEventListener('DOMContentLoaded', function () {

  // ── New Application: source-of-funds toggle ─────────────────────────────
  var depInput = document.getElementById('dep');
  var sofBox   = document.getElementById('sof');
  if (depInput && sofBox) {
    function toggleSof() {
      var n = parseFloat(depInput.value);
      sofBox.hidden = !(isNaN(n) || n < 100);
    }
    depInput.addEventListener('input', toggleSof);
    toggleSof();
  }

  // ── Account detail: live withdrawal fee summary ──────────────────────────
  var wdrAmt   = document.getElementById('wdr_amt');
  var wdrBox   = document.getElementById('wdr_summary');
  var wsAmt    = document.getElementById('ws_amt');
  var wsFee    = document.getElementById('ws_fee');
  var wsTot    = document.getElementById('ws_total');
  var wsAft    = document.getElementById('ws_after');
  var wdrBal   = window.CBE_ACCOUNT_BALANCE; // set by account.php via data attr

  if (wdrAmt && wdrBox && typeof wdrBal !== 'undefined') {
    var FREE_UP  = 500;
    var FEE      = 12;
    var MAX_WDR  = 75000;
    var MIN_BAL  = 100;

    function fmtETB(n) {
      return 'ETB ' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateWdr() {
      var v = parseFloat(wdrAmt.value);
      if (isNaN(v) || v <= 0) { wdrBox.style.display = 'none'; return; }
      var fee   = (v > FREE_UP && v <= MAX_WDR) ? FEE : 0;
      var total = v + fee;
      var after = wdrBal - total;
      wdrBox.style.display  = 'block';
      wsAmt.textContent     = fmtETB(v);
      wsFee.textContent     = fee > 0 ? fmtETB(fee) : 'Free';
      wsTot.textContent     = fmtETB(total);
      wsAft.textContent     = fmtETB(after);
      wsAft.style.color     = after < MIN_BAL ? 'var(--bad)' : 'var(--ok)';
    }

    wdrAmt.addEventListener('input', updateWdr);
  }

  // ── Guide: interest calculator ───────────────────────────────────────────
  var ctEl = document.getElementById('ct');
  var cbEl = document.getElementById('cb');
  var cbRange = document.getElementById('cb_range');
  var calcOut = document.getElementById('calc_out');

  if (ctEl && cbEl && calcOut) {
    function fmtETB2(n) {
      return 'ETB ' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function calcInterest() {
      var r  = parseFloat(ctEl.value) || 0;
      var b  = parseFloat(cbEl.value) || 0;
      var mo = b * r / 100 / 12;
      var yr = b * r / 100;
      calcOut.innerHTML =
        '<dl style="display:grid;grid-template-columns:max-content 1fr;gap:4px 20px;font-size:13px;">'
        + '<dt style="color:var(--muted);">Monthly interest</dt><dd><strong>' + fmtETB2(mo) + '</strong></dd>'
        + '<dt style="color:var(--muted);">Annual interest</dt><dd><strong>'  + fmtETB2(yr) + '</strong></dd>'
        + '</dl>'
        + (b > 0 && b < 100
            ? '<p style="color:var(--bad);font-size:11px;margin-top:6px;">Remember: account must keep at least ETB 100.</p>'
            : '');
    }

    function syncSlider() {
      if (cbRange) cbRange.value = Math.min(parseFloat(cbEl.value) || 0, 50000);
    }
    function syncInput() {
      cbEl.value = cbRange ? cbRange.value : cbEl.value;
    }

    ctEl.addEventListener('change', calcInterest);
    cbEl.addEventListener('input',  function () { syncSlider(); calcInterest(); });
    if (cbRange) cbRange.addEventListener('input', function () { syncInput(); calcInterest(); });
    calcInterest();
  }

  // ── Guide: checklist persistence ────────────────────────────────────────
  var checkBoxes = document.querySelectorAll('[data-checklist-key]');
  if (checkBoxes.length) {
    var store = {};
    try { store = JSON.parse(localStorage.getItem('cbe-guide') || '{}'); } catch (e) {}

    checkBoxes.forEach(function (el) {
      var key = el.dataset.checklistKey;
      // Restore state
      if (store[key]) el.closest('.check-item').classList.add('checked');

      el.addEventListener('click', function () {
        var item = el.closest('.check-item');
        item.classList.toggle('checked');
        store[key] = item.classList.contains('checked');
        try { localStorage.setItem('cbe-guide', JSON.stringify(store)); } catch (e) {}
        updateProgress();
      });
    });

    function updateProgress() {
      var total = checkBoxes.length;
      var done  = document.querySelectorAll('.check-item.checked').length;
      var pct   = total ? Math.round(done / total * 100) : 0;

      // Global ring
      var arc   = document.getElementById('ring_arc');
      var rPct  = document.getElementById('ring_pct');
      var bFill = document.getElementById('bar_fill');
      var bPct  = document.getElementById('bar_pct');
      var bLbl  = document.getElementById('bar_label');
      var CIRC  = 201;

      if (arc)   arc.style.strokeDashoffset   = CIRC - (CIRC * pct / 100);
      if (rPct)  rPct.textContent             = pct + '%';
      if (bFill) bFill.style.width            = pct + '%';
      if (bPct)  bPct.textContent             = pct + '%';
      if (bLbl)  bLbl.textContent             = done + ' of ' + total + ' steps completed';

      // Per-phase rings
      document.querySelectorAll('.phase-card').forEach(function (card) {
        var phase    = card.dataset.phase;
        var items    = card.querySelectorAll('.check-item');
        var doneP    = card.querySelectorAll('.check-item.checked').length;
        var totalP   = items.length;
        var pctP     = totalP ? Math.round(doneP / totalP * 100) : 0;
        var miniArc  = card.querySelector('.mini-arc');
        var miniPct  = document.getElementById('pct_' + phase);
        var cntEl    = document.getElementById('cnt_' + phase);
        var MINI     = 75.4;

        if (miniArc) miniArc.style.strokeDashoffset = MINI - (MINI * pctP / 100);
        if (miniPct) miniPct.textContent = pctP;
        if (cntEl)   cntEl.textContent   = doneP + '/' + totalP;

        if      (pctP === 100) card.style.borderColor = 'var(--ok)';
        else if (pctP > 0)     card.style.borderColor = 'var(--brand)';
        else                   card.style.borderColor = 'var(--line)';
      });
    }

    // Restore checked state from store
    checkBoxes.forEach(function (el) {
      if (store[el.dataset.checklistKey]) {
        el.closest('.check-item').classList.add('checked');
      }
    });
    updateProgress();
  }

  // ── Guide: phase accordion ───────────────────────────────────────────────
  document.querySelectorAll('.phase-header').forEach(function (header) {
    header.addEventListener('click', function () {
      var card = header.closest('.phase-card');
      if (card) card.classList.toggle('open');
    });
  });

  // Open first phase by default
  var firstPhase = document.querySelector('.phase-card');
  if (firstPhase && !firstPhase.classList.contains('open')) {
    firstPhase.classList.add('open');
  }

  // ── Guide: reset button ──────────────────────────────────────────────────
  var resetBtn = document.getElementById('reset_btn');
  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      if (!confirm('Clear all ticks and start over?')) return;
      try { localStorage.removeItem('cbe-guide'); } catch (e) {}
      document.querySelectorAll('.check-item.checked').forEach(function (el) {
        el.classList.remove('checked');
      });
      // Re-trigger progress update
      document.querySelectorAll('[data-checklist-key]')[0] &&
        document.querySelectorAll('[data-checklist-key]')[0].dispatchEvent(new Event('click'));
    });
  }

});
