<?php
/**
 * CodeFoundry Tutorial Engine – Shared Template
 *
 * Required variables set by the caller before require_once:
 *   string $tutorial_title – Display name, e.g. "JavaScript"
 *   string $tutorial_slug  – Kebab-case slug, e.g. "javascript"
 *   string $quiz_slug      – Matching quiz file slug (or '' if none)
 *   array  $tutorial_tiers – Exactly 5 tiers; each entry:
 *     [
 *       'label'    => string,          // tier label
 *       'overview' => string,          // HTML string (2-3 <p> tags)
 *       'concepts' => string[],        // 5-8 key concept bullets
 *       'code'     => null | [         // optional code example
 *                       'title'   => string,
 *                       'lang'    => string,
 *                       'content' => string
 *                     ],
 *       'tips'     => string[],        // 3-5 practice tips
 *     ]
 */
$page_title  = htmlspecialchars($tutorial_title, ENT_QUOTES, 'UTF-8') . ' Tutorial – CodeFoundry';
$active_page = 'training';

$tiers_json   = json_encode(array_values($tutorial_tiers), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$slug_js      = json_encode($tutorial_slug);
$title_js     = json_encode($tutorial_title);

/* ── Sidebar navigation ─────────────────────────────────────── */
$tutorial_nav = [
    ['label' => 'Web Fundamentals', 'items' => [
        ['title' => 'Intro to Programming', 'slug' => 'intro-programming',       'file' => 'intro-programming.php'],
        ['title' => 'Intro to HTML & CSS',  'slug' => 'intro-html-css',          'file' => 'intro-html-css.php'],
        ['title' => 'HTML',                 'slug' => 'html',                    'file' => 'html.php'],
        ['title' => 'CSS',                  'slug' => 'css',                     'file' => 'css.php'],
        ['title' => 'XML',                  'slug' => 'xml',                     'file' => 'xml.php'],
    ]],
    ['label' => 'JavaScript', 'items' => [
        ['title' => 'JS Fundamentals',      'slug' => 'javascript-fundamentals', 'file' => 'javascript-fundamentals.php'],
        ['title' => 'JavaScript',           'slug' => 'javascript',              'file' => 'javascript.php'],
        ['title' => 'TypeScript',           'slug' => 'typescript',              'file' => 'typescript.php'],
        ['title' => 'jQuery',               'slug' => 'jquery',                  'file' => 'jquery.php'],
    ]],
    ['label' => 'Frontend Frameworks', 'items' => [
        ['title' => 'React',                'slug' => 'react',                   'file' => 'react.php'],
        ['title' => 'React & Modern Web',   'slug' => 'react-modern-web',        'file' => 'react-modern-web.php'],
        ['title' => 'Angular',              'slug' => 'angular',                 'file' => 'angular.php'],
        ['title' => 'AngularJS',            'slug' => 'angularjs',               'file' => 'angularjs.php'],
        ['title' => 'Vue.js',               'slug' => 'vuejs',                   'file' => 'vuejs.php'],
        ['title' => 'Svelte',               'slug' => 'svelte',                  'file' => 'svelte.php'],
        ['title' => 'Bootstrap',            'slug' => 'bootstrap',               'file' => 'bootstrap.php'],
        ['title' => 'SASS/SCSS',            'slug' => 'sass',                    'file' => 'sass.php'],
    ]],
    ['label' => 'Backend', 'items' => [
        ['title' => 'Node.js',              'slug' => 'nodejs',                  'file' => 'nodejs.php'],
        ['title' => 'PHP',                  'slug' => 'php',                     'file' => 'php.php'],
        ['title' => 'Django',               'slug' => 'django',                  'file' => 'django.php'],
        ['title' => 'ASP.NET',              'slug' => 'asp',                     'file' => 'asp.php'],
        ['title' => 'GraphQL',              'slug' => 'graphql',                 'file' => 'graphql.php'],
        ['title' => 'Backend & API',        'slug' => 'backend-api',             'file' => 'backend-api.php'],
    ]],
    ['label' => 'Languages', 'items' => [
        ['title' => 'Python',               'slug' => 'python',                  'file' => 'python.php'],
        ['title' => 'Java',                 'slug' => 'java',                    'file' => 'java.php'],
        ['title' => 'C',                    'slug' => 'c-lang',                  'file' => 'c-lang.php'],
        ['title' => 'C++',                  'slug' => 'cpp',                     'file' => 'cpp.php'],
        ['title' => 'C#',                   'slug' => 'csharp',                  'file' => 'csharp.php'],
        ['title' => 'Go',                   'slug' => 'golang',                  'file' => 'golang.php'],
        ['title' => 'Kotlin',               'slug' => 'kotlin',                  'file' => 'kotlin.php'],
        ['title' => 'Ruby',                 'slug' => 'ruby',                    'file' => 'ruby.php'],
        ['title' => 'Rust',                 'slug' => 'rust',                    'file' => 'rust.php'],
        ['title' => 'Swift',                'slug' => 'swift',                   'file' => 'swift.php'],
        ['title' => 'Bash',                 'slug' => 'bash',                    'file' => 'bash.php'],
        ['title' => 'R',                    'slug' => 'r-lang',                  'file' => 'r-lang.php'],
        ['title' => 'Flutter',              'slug' => 'flutter',                 'file' => 'flutter.php'],
    ]],
    ['label' => 'Data & Databases', 'items' => [
        ['title' => 'SQL',                  'slug' => 'sql',                     'file' => 'sql.php'],
        ['title' => 'MySQL',                'slug' => 'mysql',                   'file' => 'mysql.php'],
        ['title' => 'PostgreSQL',           'slug' => 'postgresql',              'file' => 'postgresql.php'],
        ['title' => 'MongoDB',              'slug' => 'mongodb',                 'file' => 'mongodb.php'],
        ['title' => 'Redis',                'slug' => 'redis',                   'file' => 'redis.php'],
        ['title' => 'DSA',                  'slug' => 'dsa',                     'file' => 'dsa.php'],
        ['title' => 'Excel',                'slug' => 'excel',                   'file' => 'excel.php'],
    ]],
    ['label' => 'DevOps & Cloud', 'items' => [
        ['title' => 'Git',                  'slug' => 'git',                     'file' => 'git.php'],
        ['title' => 'Docker',               'slug' => 'docker',                  'file' => 'docker.php'],
        ['title' => 'AWS',                  'slug' => 'aws',                     'file' => 'aws.php'],
        ['title' => 'Cloud & DevOps',       'slug' => 'cloud-devops',            'file' => 'cloud-devops.php'],
    ]],
    ['label' => 'AI & Data Science', 'items' => [
        ['title' => 'AI',                   'slug' => 'ai',                      'file' => 'ai.php'],
        ['title' => 'Generative AI',        'slug' => 'genai',                   'file' => 'genai.php'],
        ['title' => 'AI Tool Development',  'slug' => 'ai-tools',                'file' => 'ai-tools.php'],
        ['title' => 'Machine Learning',     'slug' => 'machine-learning',        'file' => 'machine-learning.php'],
        ['title' => 'Data Science',         'slug' => 'data-science',            'file' => 'data-science.php'],
        ['title' => 'NumPy',                'slug' => 'numpy',                   'file' => 'numpy.php'],
        ['title' => 'Pandas',               'slug' => 'pandas',                  'file' => 'pandas.php'],
        ['title' => 'SciPy',                'slug' => 'scipy',                   'file' => 'scipy.php'],
    ]],
    ['label' => 'Security', 'items' => [
        ['title' => 'Security Practices',   'slug' => 'security-practices',      'file' => 'security-practices.php'],
    ]],
];

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
  --button-radius: 8px;
  --maxwidth: 1200px;
  --card-radius: 12px;
  --header-height: 68px;
  --sidebar-width: 260px;
}
html, body { background: var(--navy-2); color: var(--text); font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
body { min-height: 100vh; }
a { color: inherit; text-decoration: none; }

header { background: var(--navy); padding: 0; position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid #192746; }
.nav { max-width: var(--maxwidth); margin: 0 auto; padding: 0 40px; min-height: var(--header-height); display: flex; align-items: center; justify-content: space-between; }
.brand { display: flex; align-items: center; font-weight: 800; font-size: 22px; gap: 12px; letter-spacing: -0.5px; }
.brand svg { width: 28px; height: 28px; background: var(--primary); border-radius: 6px; color: #092340; padding: 4px; margin-right: 4px; box-sizing: border-box; }
.nav-menu { display: flex; gap: 28px; align-items: center; }
.nav-link { color: var(--text-muted); text-decoration: none; font-weight: 500; font-size: 15px; transition: color .2s; }
.nav-link:hover, .nav-link.active { color: var(--text); }
.nav-actions { display: flex; align-items: center; gap: 16px; }
.nav-btn { font-family: inherit; font-size: 15px; font-weight: 700; border: 0; border-radius: var(--button-radius); padding: 10px 18px; background: var(--navy-3); cursor: pointer; color: var(--text); transition: background .2s, color .2s; }
.nav-btn.primary { background: var(--primary); color: var(--navy); }
.nav-btn.secondary { background: transparent; border: 1px solid #ffffff22; }
.nav-btn:hover { background: var(--primary-hover); color: var(--navy); }
.mobile-hamburger { display: none; background: transparent; border: none; color: var(--text); font-size: 28px; cursor: pointer; padding: 0; width: 32px; height: 32px; align-items: center; justify-content: center; }
.mobile-nav-overlay { display: none; position: fixed; inset: 0; background: rgba(14,24,40,.95); z-index: 9999; }
.mobile-nav-overlay.active { display: flex; align-items: flex-start; justify-content: flex-end; }
.mobile-nav-panel { background: var(--navy); width: 280px; height: 100%; padding: 24px; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; }
.mobile-menu-close { background: none; border: none; color: var(--text); font-size: 28px; cursor: pointer; align-self: flex-end; padding: 0; }
.mobile-menu-links { display: flex; flex-direction: column; gap: 18px; }
.mobile-menu-actions { display: flex; flex-direction: column; gap: 12px; margin-top: auto; }

/* Layout */
.tut-wrap { display: flex; min-height: calc(100vh - var(--header-height)); }

/* Sidebar */
.tut-sidebar {
  width: var(--sidebar-width);
  min-width: var(--sidebar-width);
  background: var(--navy);
  border-right: 1px solid var(--border-color);
  overflow-y: auto;
  position: sticky;
  top: var(--header-height);
  height: calc(100vh - var(--header-height));
  padding: 16px 0 32px;
}
.sidebar-header { padding: 0 16px 12px; border-bottom: 1px solid var(--border-color); margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; }
.sidebar-header-title { font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); }
.sidebar-back { color: var(--primary); font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 4px; }
.sidebar-back:hover { color: var(--primary-hover); }
.sidebar-cat-label { font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: var(--text-subtle); padding: 10px 16px 4px; }
.sidebar-link { display: block; padding: 7px 16px 7px 20px; font-size: 14px; color: var(--text-muted); border-left: 2px solid transparent; transition: color .15s, border-color .15s, background .15s; }
.sidebar-link:hover { color: var(--text); background: rgba(255,255,255,.04); }
.sidebar-link.active { color: var(--primary); border-left-color: var(--primary); background: rgba(24,179,255,.06); font-weight: 600; }

/* Sidebar FAB (mobile) */
.sidebar-fab { display: none; position: fixed; bottom: 24px; left: 24px; z-index: 500; background: var(--primary); color: var(--navy); border: none; border-radius: 50%; width: 48px; height: 48px; font-size: 22px; cursor: pointer; align-items: center; justify-content: center; box-shadow: 0 4px 16px rgba(0,0,0,.3); }
.sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 400; }
.sidebar-backdrop.active { display: block; }

/* Main */
.tut-main { flex: 1; padding: 40px; min-width: 0; }
.tut-title-bar { margin-bottom: 32px; }
.tut-title-bar h1 { font-size: 2.2rem; font-weight: 800; margin: 0 0 8px; letter-spacing: -1px; }
.tut-title-bar p { color: var(--text-muted); font-size: 1rem; margin: 0; }

/* Progress */
.prog-wrap { margin-bottom: 28px; }
.prog-label-row { display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted); margin-bottom: 8px; }
.prog-bar { background: var(--navy-3); border-radius: 4px; height: 6px; overflow: hidden; }
.prog-fill { background: var(--primary); height: 100%; border-radius: 4px; transition: width .4s; }

/* Tier tabs */
.tier-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 28px; }
.tier-tab { font-family: inherit; font-size: 14px; font-weight: 600; padding: 8px 20px; border-radius: 20px; border: 2px solid var(--border-color); background: var(--navy-3); color: var(--text-muted); cursor: pointer; transition: all .2s; display: flex; align-items: center; gap: 6px; }
.tier-tab:hover { border-color: var(--primary); color: var(--text); }
.tier-tab.active { background: var(--primary); color: var(--navy); border-color: var(--primary); }

/* Tier card */
.tier-card { background: var(--navy); border: 1px solid var(--border-color); border-radius: var(--card-radius); padding: 36px; margin-bottom: 28px; }
.tier-label-badge { display: inline-block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; padding: 4px 12px; border-radius: 20px; margin-bottom: 20px; }
.tier-label-badge.introduction { background: rgba(139,92,246,.15); color: #a78bfa; }
.tier-label-badge.beginner     { background: rgba(16,185,129,.15);  color: #10b981; }
.tier-label-badge.intermediate { background: rgba(245,158,11,.15);  color: #f59e0b; }
.tier-label-badge.advanced     { background: rgba(239,68,68,.15);   color: #ef4444; }
.tier-label-badge.expert       { background: rgba(236,72,153,.15);  color: #ec4899; }

.tier-overview { color: var(--text-muted); line-height: 1.8; margin-bottom: 28px; font-size: 1rem; }
.tier-overview p { margin: 0 0 12px; }
.tier-overview p:last-child { margin-bottom: 0; }

.sec-title { font-size: 1rem; font-weight: 700; color: var(--text); margin: 0 0 14px; display: flex; align-items: center; gap: 8px; }
.sec-title iconify-icon { color: var(--primary); }

.concepts-grid { list-style: none; padding: 0; margin: 0 0 28px; display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 10px; }
.concept-item { background: var(--navy-3); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 10px; font-size: 14px; color: var(--text-muted); line-height: 1.5; }
.concept-item::before { content: '◆'; color: var(--primary); font-size: 8px; margin-top: 4px; flex-shrink: 0; }

.code-block { background: #0a1120; border: 1px solid var(--border-color); border-radius: 10px; margin-bottom: 28px; overflow: hidden; }
.code-block-header { background: var(--navy-3); padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); }
.code-block-title { font-size: 13px; font-weight: 600; color: var(--text-muted); }
.code-lang-badge { font-size: 11px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: .5px; background: rgba(24,179,255,.1); padding: 2px 8px; border-radius: 20px; }
.code-block pre { margin: 0; padding: 20px; overflow-x: auto; }
.code-block code { font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.6; color: #e2e8f0; white-space: pre; }

.tips-list { list-style: none; padding: 0; margin: 0 0 28px; display: flex; flex-direction: column; gap: 10px; }
.tip-item { background: rgba(24,179,255,.05); border: 1px solid rgba(24,179,255,.15); border-radius: 8px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 10px; font-size: 14px; color: var(--text-muted); line-height: 1.5; }
.tip-item iconify-icon { color: var(--primary); flex-shrink: 0; margin-top: 1px; }

/* Card footer */
.card-footer-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding-top: 8px; }
.nav-tier-btns { display: flex; gap: 10px; }
.nav-tier-btn { font-family: inherit; font-size: 14px; font-weight: 600; padding: 10px 20px; border-radius: var(--button-radius); border: 1px solid var(--border-color); background: var(--navy-3); color: var(--text); cursor: pointer; transition: all .2s; display: flex; align-items: center; gap: 6px; }
.nav-tier-btn:hover { border-color: var(--primary); color: var(--primary); }
.complete-btn { font-family: inherit; font-size: 14px; font-weight: 700; padding: 10px 24px; border-radius: var(--button-radius); border: 2px solid var(--primary); background: transparent; color: var(--primary); cursor: pointer; transition: all .2s; display: flex; align-items: center; gap: 8px; }
.complete-btn:hover, .complete-btn.done { background: var(--primary); color: var(--navy); }

/* Quiz CTA */
.quiz-cta { background: var(--navy); border: 1px solid var(--border-color); border-radius: var(--card-radius); padding: 32px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.quiz-cta-text h3 { font-size: 1.2rem; font-weight: 700; margin: 0 0 6px; }
.quiz-cta-text p { color: var(--text-muted); font-size: .9rem; margin: 0; }
.quiz-cta-btn { display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: var(--navy); font-weight: 700; font-size: 15px; padding: 12px 28px; border-radius: var(--button-radius); transition: background .2s; white-space: nowrap; }
.quiz-cta-btn:hover { background: var(--primary-hover); }

/* Responsive */
@media (max-width: 992px) {
  .nav-menu, .nav-actions { display: none; }
  .mobile-hamburger { display: flex; }
  .tut-sidebar { position: fixed; left: 0; top: 0; height: 100vh; z-index: 450; transform: translateX(-100%); transition: transform .3s; }
  .tut-sidebar.open { transform: translateX(0); }
  .sidebar-fab { display: flex; }
  .tut-main { padding: 24px 20px; }
}
@media (max-width: 600px) {
  .tier-tabs { gap: 6px; }
  .tier-tab { padding: 7px 14px; font-size: 13px; }
  .tut-title-bar h1 { font-size: 1.6rem; }
  .tier-card { padding: 20px; }
  .concepts-grid { grid-template-columns: 1fr; }
}
PAGECSS;

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mobile-nav-overlay" id="mobileNav">
  <div class="mobile-nav-panel">
    <button class="mobile-menu-close" id="closeMobileNav" aria-label="Close menu">&#10005;</button>
    <nav class="mobile-menu-links">
      <a href="/#services"    class="nav-link">Services</a>
      <a href="/#solutions"   class="nav-link">Solutions</a>
      <a href="/#industries"  class="nav-link">Industries</a>
      <a href="/CaseStudies/" class="nav-link">Case Studies</a>
      <a href="/Training/"    class="nav-link active">Training</a>
      <a href="/Blog/"        class="nav-link">Blog</a>
    </nav>
    <div class="mobile-menu-actions">
      <a href="/#services" class="nav-btn primary" style="text-align:center">Get Started</a>
    </div>
  </div>
</div>

<div class="tut-wrap">
  <!-- Sidebar -->
  <aside class="tut-sidebar" id="tutSidebar">
    <div class="sidebar-header">
      <span class="sidebar-header-title">Tutorials</span>
      <a href="/Training/" class="sidebar-back"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back</a>
    </div>
    <?php foreach ($tutorial_nav as $cat): ?>
    <div>
      <div class="sidebar-cat-label"><?= htmlspecialchars($cat['label'], ENT_QUOTES, 'UTF-8') ?></div>
      <?php foreach ($cat['items'] as $item): ?>
        <a href="/Training/Tutorials/<?= htmlspecialchars($item['file'], ENT_QUOTES, 'UTF-8') ?>"
           class="sidebar-link<?= ($item['slug'] === $tutorial_slug) ? ' active' : '' ?>">
          <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </aside>

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
  <button class="sidebar-fab" id="sidebarFab" aria-label="Browse tutorials">
    <iconify-icon icon="lucide:menu"></iconify-icon>
  </button>

  <!-- Main content -->
  <main class="tut-main">
    <div class="tut-title-bar">
      <h1><?= htmlspecialchars($tutorial_title, ENT_QUOTES, 'UTF-8') ?></h1>
      <p>Concept-oriented tutorial &mdash; 5 progressive levels from Introduction to Expert</p>
    </div>

    <!-- Progress bar -->
    <div class="prog-wrap">
      <div class="prog-label-row">
        <span id="progLabel">0 of 5 levels completed</span>
        <span id="progPct">0%</span>
      </div>
      <div class="prog-bar"><div class="prog-fill" id="progFill" style="width:0%"></div></div>
    </div>

    <!-- Tier tabs -->
    <div class="tier-tabs" id="tierTabs"></div>

    <!-- Tier content rendered by JS -->
    <div id="tierContent"></div>

    <!-- Quiz CTA -->
    <?php if (!empty($quiz_slug)): ?>
    <div class="quiz-cta">
      <div class="quiz-cta-text">
        <h3>Ready to test your knowledge?</h3>
        <p>Take the <?= htmlspecialchars($tutorial_title, ENT_QUOTES, 'UTF-8') ?> quiz &mdash; 100 levels across all 5 tiers.</p>
      </div>
      <a href="/Training/Quizzes/<?= htmlspecialchars($quiz_slug . '.php', ENT_QUOTES, 'UTF-8') ?>" class="quiz-cta-btn">
        <iconify-icon icon="lucide:brain"></iconify-icon> Take the Quiz
      </a>
    </div>
    <?php endif; ?>
  </main>
</div>

<script>
(function () {
  var TIERS = <?= $tiers_json ?>;
  var SLUG  = <?= $slug_js ?>;
  var KEY   = 'cf_tut_' + SLUG;
  var NAMES = ['Introduction','Beginner','Intermediate','Advanced','Expert'];
  var CLS   = ['introduction','beginner','intermediate','advanced','expert'];

  var currentTier = 0;
  var completed   = load();

  function load()  { try { return JSON.parse(localStorage.getItem(KEY) || '[]'); } catch(_){ return []; } }
  function save()  { try { localStorage.setItem(KEY, JSON.stringify(completed)); } catch(_){} }
  function isDone(i)  { return completed.indexOf(i) !== -1; }
  function markDone(i){ if (!isDone(i)) { completed.push(i); save(); } }
  function unmark(i)  { completed = completed.filter(function(x){ return x !== i; }); save(); }

  function updateProg() {
    var n   = completed.length;
    var pct = Math.round(n / TIERS.length * 100);
    document.getElementById('progLabel').textContent = n + ' of ' + TIERS.length + ' levels completed';
    document.getElementById('progPct').textContent   = pct + '%';
    document.getElementById('progFill').style.width  = pct + '%';
  }

  function esc(s) {
    return String(s)
      .replace(/&/g,'&amp;').replace(/</g,'&lt;')
      .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function renderTabs() {
    document.getElementById('tierTabs').innerHTML = TIERS.map(function(t, i) {
      var label = t.label || NAMES[i];
      var done  = isDone(i);
      return '<button class="tier-tab' + (i === currentTier ? ' active' : '') + '" onclick="TUT.show(' + i + ')" data-tier="' + i + '">'
           + (done ? '<iconify-icon icon="lucide:check-circle-2" style="color:#10b981"></iconify-icon>' : '')
           + esc(label) + '</button>';
    }).join('');
  }

  function renderContent(idx) {
    var t    = TIERS[idx];
    var lbl  = t.label || NAMES[idx];
    var cls  = CLS[idx] || '';
    var done = isDone(idx);
    var html = '<div class="tier-card">'
             + '<span class="tier-label-badge ' + cls + '">' + esc(lbl) + '</span>'
             + '<div class="tier-overview">' + (t.overview || '') + '</div>';

    if (t.concepts && t.concepts.length) {
      html += '<h4 class="sec-title"><iconify-icon icon="lucide:list-checks"></iconify-icon>Key Concepts</h4>'
            + '<ul class="concepts-grid">'
            + t.concepts.map(function(c){ return '<li class="concept-item">' + esc(c) + '</li>'; }).join('')
            + '</ul>';
    }

    if (t.code) {
      html += '<h4 class="sec-title"><iconify-icon icon="lucide:code-2"></iconify-icon>' + esc(t.code.title) + '</h4>'
            + '<div class="code-block">'
            + '<div class="code-block-header">'
            + '<span class="code-block-title">' + esc(t.code.title) + '</span>'
            + '<span class="code-lang-badge">' + esc(t.code.lang) + '</span>'
            + '</div>'
            + '<pre><code>' + esc(t.code.content) + '</code></pre>'
            + '</div>';
    }

    if (t.tips && t.tips.length) {
      html += '<h4 class="sec-title"><iconify-icon icon="lucide:lightbulb"></iconify-icon>Practice Tips</h4>'
            + '<ul class="tips-list">'
            + t.tips.map(function(tp){ return '<li class="tip-item"><iconify-icon icon="lucide:zap"></iconify-icon>' + esc(tp) + '</li>'; }).join('')
            + '</ul>';
    }

    html += '<div class="card-footer-row">'
          + '<div class="nav-tier-btns">'
          + (idx > 0 ? '<button class="nav-tier-btn" onclick="TUT.show(' + (idx-1) + ')"><iconify-icon icon="lucide:chevron-left"></iconify-icon>Previous</button>' : '')
          + (idx < TIERS.length - 1 ? '<button class="nav-tier-btn" onclick="TUT.show(' + (idx+1) + ')">Next<iconify-icon icon="lucide:chevron-right"></iconify-icon></button>' : '')
          + '</div>'
          + '<button class="complete-btn' + (done ? ' done' : '') + '" onclick="TUT.toggle(' + idx + ')">'
          + '<iconify-icon icon="' + (done ? 'lucide:check-circle-2' : 'lucide:circle') + '"></iconify-icon>'
          + (done ? 'Completed' : 'Mark as Complete')
          + '</button>'
          + '</div>'
          + '</div>';
    return html;
  }

  window.TUT = {
    show: function(idx) {
      currentTier = idx;
      renderTabs();
      document.getElementById('tierContent').innerHTML = renderContent(idx);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    toggle: function(idx) {
      if (isDone(idx)) { unmark(idx); } else {
        markDone(idx);
        if (idx < TIERS.length - 1) setTimeout(function(){ TUT.show(idx + 1); }, 400);
      }
      renderTabs();
      document.getElementById('tierContent').innerHTML = renderContent(idx);
      updateProg();
    }
  };

  /* Mobile nav */
  var mobileMenuBtn  = document.getElementById('mobileMenuBtn');
  var mobileNav      = document.getElementById('mobileNav');
  var closeMobileNav = document.getElementById('closeMobileNav');
  if (mobileMenuBtn)  mobileMenuBtn.addEventListener('click',  function(){ mobileNav.classList.add('active'); });
  if (closeMobileNav) closeMobileNav.addEventListener('click', function(){ mobileNav.classList.remove('active'); });
  if (mobileNav)      mobileNav.addEventListener('click', function(e){ if (e.target === mobileNav) mobileNav.classList.remove('active'); });

  /* Sidebar FAB */
  var fab      = document.getElementById('sidebarFab');
  var sidebar  = document.getElementById('tutSidebar');
  var backdrop = document.getElementById('sidebarBackdrop');
  if (fab)      fab.addEventListener('click',      function(){ sidebar.classList.add('open');    backdrop.classList.add('active'); });
  if (backdrop) backdrop.addEventListener('click', function(){ sidebar.classList.remove('open'); backdrop.classList.remove('active'); });

  /* Scroll active link into view */
  var activeLink = sidebar ? sidebar.querySelector('.sidebar-link.active') : null;
  if (activeLink) requestAnimationFrame(function(){ activeLink.scrollIntoView({ block: 'center', behavior: 'instant' }); });

  updateProg();
  TUT.show(0);
}());
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
