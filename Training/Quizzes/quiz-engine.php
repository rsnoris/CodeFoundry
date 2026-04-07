<?php
/**
 * CodeFoundry Quiz Engine – Shared Template
 *
 * Required variables (set by the caller before require_once):
 *   string $quiz_title  – Display name of the quiz, e.g. "HTML"
 *   string $quiz_slug   – Kebab-case slug used for localStorage key, e.g. "html"
 *   array  $quiz_tiers  – Exactly 5 tiers; each entry:
 *                          ['label' => string, 'questions' => array]
 *                         Each question: ['question'=>string,'options'=>string[4],'correct'=>int]
 *
 * UI: 100 levels (5 tiers × 20 levels). Selecting a level picks 20 random
 * questions from that tier's question bank. Completed levels are persisted in
 * localStorage under the key  cf_quiz_{slug}.
 */
$page_title  = htmlspecialchars($quiz_title, ENT_QUOTES, 'UTF-8') . ' Quiz – 100 Levels – CodeFoundry';
$active_page = 'training';

// Serialize tier data for JS (questions only; labels are known client-side too)
$tiers_json = json_encode(array_values($quiz_tiers), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$slug_js    = json_encode($quiz_slug);
$title_js   = json_encode($quiz_title);

$page_styles = <<<'PAGECSS'
:root {
  --navy: #0e1828;
  --navy-2: #121c2b;
  --navy-3: #161f2f;
  --primary: #18b3ff;
  --primary-hover: #009de0;
  --text: #fff;
  --text-muted: #92a3bb;
  --text-subtle: #627193;
  --border-color: #1a2942;
  --button-outline: #ffffff22;
  --button-radius: 8px;
  --maxwidth: 1200px;
  --card-radius: 12px;
  --header-height: 68px;
  --mobile-menu-bg: #0e1828f9;
}
html, body {
  background: var(--navy-2);
  color: var(--text);
  font-family: 'Inter', sans-serif;
  margin: 0;
  padding: 0;
}
body { min-height: 100vh; }
a { color: inherit; text-decoration: none; }

header {
  background: var(--navy);
  color: var(--text);
  padding: 0;
  position: sticky;
  top: 0;
  z-index: 1000;
  border-bottom: 1px solid #192746;
}
.nav {
  max-width: var(--maxwidth);
  margin: 0 auto;
  padding: 0 40px;
  min-height: var(--header-height);
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.brand {
  display: flex;
  align-items: center;
  font-weight: 800;
  font-size: 22px;
  gap: 12px;
  letter-spacing: -0.5px;
}
.brand svg {
  width: 28px;
  height: 28px;
  background: var(--primary);
  border-radius: 6px;
  color: #092340;
  padding: 4px;
  margin-right: 4px;
  box-sizing: border-box;
}
.nav-menu { display: flex; gap: 28px; align-items: center; }
.nav-link {
  color: var(--text-muted);
  text-decoration: none;
  font-weight: 500;
  font-size: 15px;
  transition: color .2s;
}
.nav-link:hover, .nav-link.active { color: var(--text); }
.nav-actions { display: flex; align-items: center; gap: 16px; }
.nav-btn {
  font-family: inherit;
  font-size: 15px;
  font-weight: 700;
  border: 0;
  border-radius: var(--button-radius);
  padding: 10px 18px;
  background: var(--navy-3);
  cursor: pointer;
  color: var(--text);
  transition: background .2s, color .2s;
}
.nav-btn.primary  { background: var(--primary); color: var(--navy); }
.nav-btn.secondary { background: transparent; border: 1px solid var(--button-outline); }
.nav-btn:hover    { background: var(--primary-hover); color: var(--navy); }

.mobile-hamburger {
  display: none;
  background: transparent;
  border: none;
  color: var(--text);
  font-size: 28px;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  align-items: center;
  justify-content: center;
}
.mobile-nav-overlay {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: var(--mobile-menu-bg);
  z-index: 2000;
  backdrop-filter: blur(10px);
}
.mobile-nav-overlay.active { display: block; }
.mobile-nav-panel {
  background: var(--navy);
  height: 100%;
  width: 300px;
  max-width: 85%;
  margin-left: auto;
  display: flex;
  flex-direction: column;
  border-left: 1px solid var(--border-color);
}

/* ── Quiz layout ─────────────────────────────────────────────── */
.quiz-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 60px 24px 80px;
}
.quiz-header { margin-bottom: 32px; }
.quiz-header h1 {
  font-size: 2.4rem;
  font-weight: 800;
  margin: 0;
  letter-spacing: -1px;
}
.quiz-header .back-link {
  color: var(--primary);
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: color 0.2s;
  margin-bottom: 20px;
}
.quiz-header .back-link:hover { color: var(--primary-hover); }
.quiz-content {
  background: var(--navy);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 40px;
}

/* ── Tier / Level selector ───────────────────────────────────── */
.tier-section { margin-bottom: 32px; }
.tier-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 14px;
}
.tier-badge {
  font-size: 0.75rem;
  font-weight: 700;
  padding: 3px 12px;
  border-radius: 20px;
  text-transform: uppercase;
  letter-spacing: .5px;
}
.tier-badge.t0 { background: rgba(16,185,129,.18); color: #10b981; }
.tier-badge.t1 { background: rgba(24,179,255,.18); color: #18b3ff; }
.tier-badge.t2 { background: rgba(245,158,11,.18);  color: #f59e0b; }
.tier-badge.t3 { background: rgba(239,68,68,.18);   color: #ef4444; }
.tier-badge.t4 { background: rgba(168,85,247,.18);  color: #a855f7; }
.tier-title {
  font-weight: 700;
  font-size: 1rem;
  color: var(--text);
}
.tier-range {
  font-size: 0.8rem;
  color: var(--text-subtle);
}
.level-grid {
  display: grid;
  grid-template-columns: repeat(10, 1fr);
  gap: 8px;
}
.level-btn {
  aspect-ratio: 1;
  background: var(--navy-3);
  border: 2px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-muted);
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.18s;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  gap: 2px;
  position: relative;
  padding: 0;
}
.level-btn:hover { border-color: var(--primary); color: var(--text); background: rgba(24,179,255,.07); }
.level-btn.done  { border-color: currentColor; }
.level-btn.done .checkmark { display: block; }
.checkmark { display: none; font-size: 0.65rem; }
.level-btn.t0 { color: #10b981; }
.level-btn.t1 { color: #18b3ff; }
.level-btn.t2 { color: #f59e0b; }
.level-btn.t3 { color: #ef4444; }
.level-btn.t4 { color: #a855f7; }
.level-btn.t0.done { background: rgba(16,185,129,.1); }
.level-btn.t1.done { background: rgba(24,179,255,.1); }
.level-btn.t2.done { background: rgba(245,158,11,.1);  }
.level-btn.t3.done { background: rgba(239,68,68,.1);   }
.level-btn.t4.done { background: rgba(168,85,247,.1);  }

/* ── Progress bar ────────────────────────────────────────────── */
.quiz-progress {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding: 12px 16px;
  background: var(--navy-3);
  border-radius: 8px;
  font-size: 0.9rem;
}
.progress-pill {
  font-size: 0.72rem;
  font-weight: 700;
  padding: 2px 10px;
  border-radius: 20px;
  text-transform: uppercase;
  display: inline-block;
  margin-right: 6px;
}
.progress-pill.t0 { background: rgba(16,185,129,.18); color: #10b981; }
.progress-pill.t1 { background: rgba(24,179,255,.18); color: #18b3ff; }
.progress-pill.t2 { background: rgba(245,158,11,.18);  color: #f59e0b; }
.progress-pill.t3 { background: rgba(239,68,68,.18);   color: #ef4444; }
.progress-pill.t4 { background: rgba(168,85,247,.18);  color: #a855f7; }

/* ── Question ────────────────────────────────────────────────── */
.quiz-question { margin-bottom: 24px; }
.quiz-question-text {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 24px;
  line-height: 1.55;
}
.quiz-options { display: flex; flex-direction: column; gap: 12px; }
.quiz-option {
  background: var(--navy-3);
  border: 2px solid var(--border-color);
  border-radius: 8px;
  padding: 16px 20px;
  cursor: pointer;
  transition: all 0.18s;
  font-size: 1rem;
  line-height: 1.4;
}
.quiz-option:hover   { border-color: var(--primary); background: var(--navy-2); }
.quiz-option.selected { border-color: var(--primary); background: rgba(24,179,255,.1); }

/* ── Navigation ──────────────────────────────────────────────── */
.quiz-navigation {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 30px;
  padding-top: 24px;
  border-top: 1px solid var(--border-color);
}
.simulator-btn-primary {
  background: var(--primary);
  color: var(--navy-2);
  border: none;
  padding: 12px 28px;
  border-radius: 6px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
  font-size: 15px;
  font-family: inherit;
}
.simulator-btn-primary:hover:not(:disabled) { background: var(--primary-hover); }
.simulator-btn-primary:disabled { opacity: .5; cursor: not-allowed; }
.simulator-btn-secondary {
  background: var(--navy-3);
  color: var(--text);
  border: 1px solid var(--border-color);
  padding: 12px 24px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 15px;
  font-family: inherit;
}
.simulator-btn-secondary:hover:not(:disabled) { border-color: var(--primary); background: var(--navy-2); }
.simulator-btn-secondary:disabled { opacity: .5; cursor: not-allowed; }

/* ── Results ─────────────────────────────────────────────────── */
.quiz-results { text-align: center; padding: 40px 20px; }
.quiz-results h2 { font-size: 2rem; font-weight: 700; margin: 0 0 16px 0; }
.quiz-score {
  font-size: 4rem;
  font-weight: 800;
  color: var(--primary);
  margin: 20px 0;
}
.quiz-result-details {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 16px;
  margin: 24px 0;
}
.quiz-result-stat { background: var(--navy-3); padding: 22px; border-radius: 8px; }
.quiz-result-stat-value { font-size: 2.4rem; font-weight: 700; color: var(--primary); }
.quiz-result-stat-label { color: var(--text-muted); font-size: 0.9rem; margin-top: 6px; }
.results-actions { margin-top: 28px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

/* ── Learning Mode toggle ────────────────────────────────────── */
.learning-mode-toggle {
  margin-bottom: 28px;
  padding: 16px 20px;
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: 10px;
}
.lm-label {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  cursor: pointer;
}
.lm-label input[type="checkbox"] {
  width: 20px;
  height: 20px;
  accent-color: var(--primary);
  cursor: pointer;
  flex-shrink: 0;
  margin-top: 3px;
}
.lm-icon { font-size: 1.5rem; flex-shrink: 0; }
.lm-title { font-weight: 700; font-size: 1rem; color: var(--text); }
.lm-desc  { color: var(--text-muted); font-size: 0.88rem; margin-top: 3px; }

/* ── Answer Feedback ─────────────────────────────────────────── */
.quiz-option.correct-answer {
  border-color: #10b981 !important;
  background: rgba(16,185,129,.15) !important;
}
.quiz-option.wrong-answer {
  border-color: #ef4444 !important;
  background: rgba(239,68,68,.13) !important;
}
.quiz-option.revealed { pointer-events: none; cursor: default; }
.answer-explanation {
  margin-top: 14px;
  padding: 14px 18px;
  border-radius: 8px;
  font-size: 0.97rem;
  line-height: 1.5;
}
.answer-explanation.correct {
  background: rgba(16,185,129,.12);
  border: 1px solid rgba(16,185,129,.35);
  color: #10b981;
}
.answer-explanation.incorrect {
  background: rgba(239,68,68,.10);
  border: 1px solid rgba(239,68,68,.3);
  color: #ef4444;
}
.answer-explanation.neutral {
  background: rgba(24,179,255,.08);
  border: 1px solid rgba(24,179,255,.25);
  color: var(--primary);
}

/* ── Results Breakdown ───────────────────────────────────────── */
.results-breakdown { margin-top: 32px; text-align: left; }
.results-breakdown-title {
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 16px;
  color: var(--text);
}
.result-question-item {
  padding: 16px 18px;
  border-radius: 8px;
  margin-bottom: 10px;
  border: 1px solid var(--border-color);
  background: var(--navy-3);
}
.result-question-item.rq-correct  { border-left: 4px solid #10b981; }
.result-question-item.rq-incorrect { border-left: 4px solid #ef4444; }
.rq-header { display: flex; align-items: flex-start; gap: 10px; }
.rq-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
.rq-question-text { font-weight: 600; font-size: 0.95rem; color: var(--text); }
.rq-answers { margin-top: 8px; font-size: 0.88rem; padding-left: 28px; }
.rq-your-answer   { color: var(--text-muted); margin-bottom: 3px; }
.rq-correct-label { color: #10b981; }
.rq-unanswered    { color: #f59e0b; font-style: italic; margin-bottom: 3px; }

/* ── Certificate Modal ───────────────────────────────────────── */
.cert-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.7);
  z-index: 3000;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(6px);
  padding: 20px;
  box-sizing: border-box;
}
.cert-overlay.active { display: flex; }
.cert-modal {
  background: var(--navy);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 40px;
  max-width: 580px;
  width: 100%;
  position: relative;
  max-height: 90vh;
  overflow-y: auto;
}
.cert-modal h3 { font-size: 1.4rem; font-weight: 700; margin: 0 0 8px 0; }
.cert-modal > p { color: var(--text-muted); margin: 0 0 24px 0; font-size: 0.95rem; }
.cert-name-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  background: var(--navy-3);
  color: var(--text);
  font-size: 1rem;
  font-family: inherit;
  margin-bottom: 20px;
  box-sizing: border-box;
  transition: border-color .2s;
}
.cert-name-input:focus { outline: none; border-color: var(--primary); }
.cert-modal-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.cert-close-btn {
  position: absolute;
  top: 16px; right: 20px;
  background: transparent;
  border: none;
  color: var(--text-muted);
  font-size: 22px;
  cursor: pointer;
  padding: 0;
  line-height: 1;
}
.cert-close-btn:hover { color: var(--text); }

/* ── Certificate Display ─────────────────────────────────────── */
.certificate-display {
  display: none;
  margin-top: 24px;
  padding: 40px 32px;
  background: #fff;
  color: #1a1a2e;
  border-radius: 12px;
  text-align: center;
  border: 5px solid #18b3ff;
}
.cert-disp-seal   { font-size: 2.6rem; margin-bottom: 8px; }
.cert-disp-logo   { font-size: 2rem; font-weight: 900; color: #18b3ff; letter-spacing: -1px; margin-bottom: 4px; }
.cert-disp-sub    { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 3px; color: #6b7280; margin-bottom: 22px; }
.cert-disp-presents { font-size: 0.9rem; color: #6b7280; margin-bottom: 6px; }
.cert-disp-name   { font-size: 1.9rem; font-weight: 700; color: #0e1828; margin-bottom: 14px; padding-bottom: 14px; border-bottom: 2px solid #18b3ff; }
.cert-disp-body   { color: #374151; font-size: 0.97rem; line-height: 1.7; margin-bottom: 14px; }
.cert-disp-score  { font-size: 2.5rem; font-weight: 800; color: #18b3ff; }
.cert-disp-score-label { font-size: 0.82rem; color: #9ca3af; margin-bottom: 20px; }
.cert-disp-date   { font-size: 0.82rem; color: #9ca3af; margin-top: 6px; }

@media print {
  body > *:not(#certPrintArea) { display: none !important; }
  #certPrintArea { display: flex !important; position: fixed; inset: 0; background: rgba(0,0,0,0); z-index: 9999; align-items: center; justify-content: center; }
  #certPrintArea .cert-modal { max-height: none; overflow: visible; border: none; background: #fff; }
  #certPrintArea .cert-close-btn,
  #certPrintArea h3,
  #certPrintArea > p,
  #certPrintArea .cert-name-input,
  #certPrintArea .cert-modal-actions { display: none !important; }
  #certPrintArea .certificate-display { display: block !important; box-shadow: none; margin: 0; }
}

/* ── Responsive ──────────────────────────────────────────────── */
@media (max-width: 768px) {
  .nav-menu, .nav-actions { display: none; }
  .mobile-hamburger { display: flex; }
  .quiz-content { padding: 24px 16px; }
  .quiz-header h1 { font-size: 1.8rem; }
  .quiz-question-text { font-size: 1.1rem; }
  .quiz-score { font-size: 3rem; }
  .level-grid { grid-template-columns: repeat(5, 1fr); }
}
@media (max-width: 480px) {
  .level-grid { grid-template-columns: repeat(4, 1fr); }
}
PAGECSS;

$page_scripts = '';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="quiz-container">
  <div class="quiz-header">
    <a href="/Training/" class="back-link">← Back to Training</a>
    <h1><?= htmlspecialchars($quiz_title, ENT_QUOTES, 'UTF-8') ?> Quiz</h1>
  </div>
  <div class="quiz-content" id="quizContent"></div>
</div>

<!-- Certificate Modal -->
<div class="cert-overlay" id="certPrintArea">
  <div class="cert-modal">
    <button class="cert-close-btn" onclick="closeCertificateModal()" aria-label="Close">✕</button>
    <h3>🏆 Generate Your Certificate</h3>
    <p>You scored 85%+ — enter your full name to generate a certificate of achievement.</p>
    <input type="text" id="certNameInput" class="cert-name-input"
           placeholder="Enter your full name" maxlength="80"
           onkeydown="if(event.key==='Enter')generateCertificate()">
    <div class="cert-modal-actions">
      <button class="simulator-btn-primary" id="certGenerateBtn" onclick="generateCertificate()">Generate Certificate</button>
      <button class="simulator-btn-primary" id="certPrintBtn" style="display:none;background:#10b981;" onclick="printCertificate()">🖨️ Print / Save</button>
      <button class="simulator-btn-secondary" onclick="closeCertificateModal()">Cancel</button>
    </div>
    <div class="certificate-display" id="certDisplay"></div>
  </div>
</div>

<script>
(function () {
  'use strict';

  /* ── Data injected from PHP ────────────────────────────────── */
  const SLUG   = <?= $slug_js ?>;
  const TITLE  = <?= $title_js ?>;
  const TIERS  = <?= $tiers_json ?>;   // array[5] of {label, questions[]}

  const TIER_NAMES  = ['Introduction', 'Beginner', 'Intermediate', 'Advanced', 'Expert'];
  const TIER_RANGES = ['1–20', '21–40', '41–60', '61–80', '81–100'];
  const LS_KEY      = 'cf_quiz_' + SLUG;
  const QUESTIONS_PER_LEVEL = 20;

  /* ── State ─────────────────────────────────────────────────── */
  let completedLevels = [];   // array of level numbers (1-100)
  let currentLevel    = null; // 1-100
  let currentTierIdx  = null; // 0-4
  let activeQuestions = [];   // 20 shuffled questions for current level
  let currentQIdx     = 0;
  let userAnswers     = [];
  let learningMode    = false; // learning mode: show answer & explanation
  let answerRevealed  = false; // whether answer is revealed for current question

  /* ── localStorage helpers ──────────────────────────────────── */
  function loadCompleted() {
    try { completedLevels = JSON.parse(localStorage.getItem(LS_KEY)) || []; }
    catch (e) { completedLevels = []; }
    if (!Array.isArray(completedLevels)) completedLevels = [];
  }

  function saveCompleted() {
    try { localStorage.setItem(LS_KEY, JSON.stringify(completedLevels)); }
    catch (e) { /* storage unavailable */ }
  }

  function markLevelDone(level) {
    if (!completedLevels.includes(level)) {
      completedLevels.push(level);
      saveCompleted();
    }
  }

  /* ── Random selection ──────────────────────────────────────── */
  function pickQuestions(tierIdx) {
    const pool = TIERS[tierIdx].questions.slice();
    // Fisher-Yates shuffle
    for (let i = pool.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [pool[i], pool[j]] = [pool[j], pool[i]];
    }
    return pool.slice(0, QUESTIONS_PER_LEVEL);
  }

  /* ── Screen: Level Selector ────────────────────────────────── */
  function showLevelSelector() {
    loadCompleted();
    const el = document.getElementById('quizContent');
    let html = '<h2 style="font-size:1.25rem;font-weight:700;margin:0 0 24px 0;">Select a Level</h2>';

    html += `
      <div class="learning-mode-toggle">
        <label class="lm-label">
          <input type="checkbox" id="learningModeCheck" ${learningMode ? 'checked' : ''}
                 onchange="learningMode = this.checked">
          <span class="lm-icon">📚</span>
          <div>
            <div class="lm-title">Learning Mode</div>
            <div class="lm-desc">Click "Show Answer &amp; Explanation" during each question to reveal the correct answer before moving on.</div>
          </div>
        </label>
      </div>`;

    TIERS.forEach((tier, ti) => {
      const startLevel = ti * 20 + 1;
      const label      = tier.label || TIER_NAMES[ti];
      html += `
        <div class="tier-section">
          <div class="tier-header">
            <span class="tier-badge t${ti}">${label}</span>
            <span class="tier-title">Levels ${TIER_RANGES[ti]}</span>
          </div>
          <div class="level-grid">`;

      for (let i = 0; i < 20; i++) {
        const lvl  = startLevel + i;
        const done = completedLevels.includes(lvl);
        html += `
          <button class="level-btn t${ti}${done ? ' done' : ''}"
                  onclick="startLevel(${lvl}, ${ti})"
                  title="Level ${lvl}${done ? ' ✓ Completed' : ''}">
            <span>${lvl}</span>
            <span class="checkmark">✓</span>
          </button>`;
      }
      html += '</div></div>';
    });

    el.innerHTML = html;
  }

  /* ── Start a level ─────────────────────────────────────────── */
  window.startLevel = function (level, tierIdx) {
    currentLevel    = level;
    currentTierIdx  = tierIdx;
    activeQuestions = pickQuestions(tierIdx);
    currentQIdx     = 0;
    userAnswers     = new Array(activeQuestions.length).fill(null);
    answerRevealed  = false;
    showQuestion();
  };

  /* ── Screen: Question ──────────────────────────────────────── */
  function showQuestion() {
    const q      = activeQuestions[currentQIdx];
    const total  = activeQuestions.length;
    const pct    = Math.round(((currentQIdx + 1) / total) * 100);
    const label  = (TIERS[currentTierIdx] && TIERS[currentTierIdx].label) || TIER_NAMES[currentTierIdx];
    const el     = document.getElementById('quizContent');
    const isLast = currentQIdx === total - 1;
    const selected = userAnswers[currentQIdx];

    // Build options with optional reveal feedback
    const opts = q.options.map((opt, idx) => {
      let cls = 'quiz-option';
      if (selected === idx) cls += ' selected';
      if (answerRevealed) {
        cls += ' revealed';
        if (idx === q.correct)   cls += ' correct-answer';
        else if (selected === idx) cls += ' wrong-answer';
      }
      const clickAttr = answerRevealed ? '' : ` onclick="selectAnswer(${idx})"`;
      return `<div class="${cls}"${clickAttr}>${escHtml(opt)}</div>`;
    }).join('');

    // Explanation shown after reveal (learning mode)
    let explanationHtml = '';
    if (learningMode && answerRevealed) {
      if (selected === null) {
        explanationHtml = `<div class="answer-explanation neutral">✔ Correct Answer: <strong>${escHtml(q.options[q.correct])}</strong></div>`;
      } else if (selected === q.correct) {
        explanationHtml = `<div class="answer-explanation correct">✅ Correct! <strong>${escHtml(q.options[q.correct])}</strong> is the right answer.</div>`;
      } else {
        explanationHtml = `<div class="answer-explanation incorrect">❌ Incorrect. The correct answer is: <strong>${escHtml(q.options[q.correct])}</strong></div>`;
      }
    }

    // Show Answer button (learning mode, not yet revealed)
    const showAnswerBtn = (learningMode && !answerRevealed)
      ? `<div style="text-align:center;margin-top:18px;">
           <button class="simulator-btn-secondary" onclick="revealAnswer()">📖 Show Answer &amp; Explanation</button>
         </div>`
      : '';

    const lmBadge = learningMode
      ? `<span style="color:#f59e0b;font-weight:600;">📚 Learning Mode</span> &nbsp;·&nbsp; `
      : '';

    el.innerHTML = `
      <div class="quiz-progress">
        <span>Question ${currentQIdx + 1} of ${total}</span>
        <span>
          <span class="progress-pill t${currentTierIdx}">${label}</span>
          ${lmBadge}Level ${currentLevel} &nbsp;·&nbsp; ${pct}% Complete
        </span>
      </div>
      <div class="quiz-question">
        <div class="quiz-question-text">${escHtml(q.question)}</div>
        <div class="quiz-options">${opts}</div>
        ${explanationHtml}
        ${showAnswerBtn}
      </div>
      <div class="quiz-navigation">
        <button class="simulator-btn-secondary" onclick="prevQuestion()"
                ${currentQIdx === 0 ? 'disabled' : ''}>Previous</button>
        <button class="simulator-btn-primary"
                onclick="${isLast ? 'finishQuiz()' : 'nextQuestion()'}">
          ${isLast ? 'Finish Quiz' : 'Next'}
        </button>
      </div>`;
  }

  window.selectAnswer = function (idx) {
    userAnswers[currentQIdx] = idx;
    showQuestion();
  };

  window.revealAnswer = function () {
    answerRevealed = true;
    showQuestion();
  };

  window.nextQuestion = function () {
    if (currentQIdx < activeQuestions.length - 1) {
      currentQIdx++;
      answerRevealed = false;
      showQuestion();
    }
  };

  window.prevQuestion = function () {
    if (currentQIdx > 0) {
      currentQIdx--;
      answerRevealed = false;
      showQuestion();
    }
  };

  /* ── Screen: Results ───────────────────────────────────────── */
  window.finishQuiz = function () {
    let correct = 0;
    activeQuestions.forEach((q, i) => { if (userAnswers[i] === q.correct) correct++; });
    const total = activeQuestions.length;
    const pct   = Math.round((correct / total) * 100);
    const label = (TIERS[currentTierIdx] && TIERS[currentTierIdx].label) || TIER_NAMES[currentTierIdx];
    const nextLevel = currentLevel < 100 ? currentLevel + 1 : null;
    const nextTierIdx = nextLevel ? Math.floor((nextLevel - 1) / 20) : null;

    if (pct >= 60) markLevelDone(currentLevel);

    const msg = pct >= 80
      ? 'Excellent work! You have a strong grasp of this level.'
      : pct >= 60
        ? 'Good job! Review the topics you missed to improve further.'
        : 'Keep learning! Study the material and try again.';

    const nextBtn = nextLevel
      ? `<button class="simulator-btn-primary"
              onclick="startLevel(${nextLevel}, ${nextTierIdx})">Next Level (${nextLevel})</button>`
      : '';

    const certBtn = pct >= 85
      ? `<button class="simulator-btn-primary" onclick="showCertificateModal()"
               style="background:#10b981;">🏆 Generate Certificate</button>`
      : '';

    const certNote = pct >= 85
      ? `<p style="color:#10b981;font-weight:600;font-size:0.92rem;margin:0 0 4px;">
           🏆 You scored 85%+ — you qualify for a certificate of achievement!
         </p>`
      : '';

    // Per-question breakdown
    let breakdownHtml = `<div class="results-breakdown">
      <div class="results-breakdown-title">Question Review</div>`;
    activeQuestions.forEach((q, i) => {
      const userAns   = userAnswers[i];
      const isCorrect = userAns === q.correct;
      breakdownHtml += `
        <div class="result-question-item ${isCorrect ? 'rq-correct' : 'rq-incorrect'}">
          <div class="rq-header">
            <span class="rq-icon">${isCorrect ? '✅' : '❌'}</span>
            <span class="rq-question-text">Q${i + 1}: ${escHtml(q.question)}</span>
          </div>
          <div class="rq-answers">`;
      if (userAns === null) {
        breakdownHtml += `<div class="rq-unanswered">Not answered</div>
          <div class="rq-correct-label">✔ Correct: ${escHtml(q.options[q.correct])}</div>`;
      } else if (isCorrect) {
        breakdownHtml += `<div class="rq-your-answer">Your answer: ${escHtml(q.options[userAns])} ✓</div>`;
      } else {
        breakdownHtml += `<div class="rq-your-answer" style="color:#ef4444;">Your answer: ${escHtml(q.options[userAns])} ✗</div>
          <div class="rq-correct-label">✔ Correct: ${escHtml(q.options[q.correct])}</div>`;
      }
      breakdownHtml += `</div></div>`;
    });
    breakdownHtml += '</div>';

    document.getElementById('quizContent').innerHTML = `
      <div class="quiz-results">
        <h2>Quiz Complete!</h2>
        <span class="progress-pill t${currentTierIdx}" style="font-size:.85rem;padding:4px 14px;">${label} · Level ${currentLevel}</span>
        <div class="quiz-score">${pct}%</div>
        <div class="quiz-result-details">
          <div class="quiz-result-stat">
            <div class="quiz-result-stat-value" style="color:#10b981;">${correct}</div>
            <div class="quiz-result-stat-label">✅ Correct</div>
          </div>
          <div class="quiz-result-stat">
            <div class="quiz-result-stat-value" style="color:#ef4444;">${total - correct}</div>
            <div class="quiz-result-stat-label">❌ Incorrect</div>
          </div>
          <div class="quiz-result-stat">
            <div class="quiz-result-stat-value">${total}</div>
            <div class="quiz-result-stat-label">Total Questions</div>
          </div>
        </div>
        <p style="color:var(--text-muted);font-size:1.05rem;">${msg}</p>
        ${certNote}
        <div class="results-actions">
          <button class="simulator-btn-secondary" onclick="showLevelSelector()">Back to Levels</button>
          <button class="simulator-btn-secondary" onclick="startLevel(${currentLevel}, ${currentTierIdx})">Retry</button>
          ${nextBtn}
          ${certBtn}
        </div>
        ${breakdownHtml}
      </div>`;
  };

  /* ── Certificate ────────────────────────────────────────────── */
  window.showCertificateModal = function () {
    const overlay = document.getElementById('certPrintArea');
    overlay.classList.add('active');
    const input = document.getElementById('certNameInput');
    input.value = '';
    input.style.borderColor = '';
    document.getElementById('certDisplay').style.display = 'none';
    document.getElementById('certGenerateBtn').style.display = 'inline-block';
    document.getElementById('certPrintBtn').style.display   = 'none';
    setTimeout(() => input.focus(), 100);
  };

  window.closeCertificateModal = function () {
    document.getElementById('certPrintArea').classList.remove('active');
  };

  window.generateCertificate = function () {
    const nameInput = document.getElementById('certNameInput');
    const name = nameInput.value.trim();
    if (!name) {
      nameInput.style.borderColor = '#ef4444';
      nameInput.focus();
      return;
    }
    nameInput.style.borderColor = '';

    let correct = 0;
    activeQuestions.forEach((q, i) => { if (userAnswers[i] === q.correct) correct++; });
    const pct   = Math.round((correct / activeQuestions.length) * 100);
    const label = (TIERS[currentTierIdx] && TIERS[currentTierIdx].label) || TIER_NAMES[currentTierIdx];
    const dateStr = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

    const certDisplay = document.getElementById('certDisplay');
    certDisplay.innerHTML = `
      <div class="cert-disp-seal">🏆</div>
      <div class="cert-disp-logo">CodeFoundry</div>
      <div class="cert-disp-sub">Certificate of Achievement</div>
      <div class="cert-disp-presents">This is to certify that</div>
      <div class="cert-disp-name">${escHtml(name)}</div>
      <div class="cert-disp-body">
        has successfully completed the<br>
        <strong>${escHtml(TITLE)} Quiz</strong><br>
        ${escHtml(label)} · Level ${currentLevel}
      </div>
      <div class="cert-disp-score">${pct}%</div>
      <div class="cert-disp-score-label">Score</div>
      <div class="cert-disp-date">Issued on ${dateStr}</div>`;
    certDisplay.style.display = 'block';

    document.getElementById('certGenerateBtn').style.display = 'none';
    document.getElementById('certPrintBtn').style.display    = 'inline-block';
  };

  window.printCertificate = function () {
    window.print();
  };

  /* ── Utility ────────────────────────────────────────────────── */
  function escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /* ── Mobile menu ────────────────────────────────────────────── */
  const mobileMenuBtn  = document.getElementById('mobileMenuBtn');
  const mobileNav      = document.getElementById('mobileNav');
  const closeMobileNav = document.getElementById('closeMobileNav');
  mobileMenuBtn?.addEventListener('click', () => mobileNav.classList.add('active'));
  closeMobileNav?.addEventListener('click', () => mobileNav.classList.remove('active'));
  mobileNav?.addEventListener('click', e => { if (e.target === mobileNav) mobileNav.classList.remove('active'); });

  /* ── Boot ───────────────────────────────────────────────────── */
  showLevelSelector();
}());
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
