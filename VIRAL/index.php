<?php
/**
 * CodeFoundry VIRAL – AI Agents for Every Job Role
 *
 * Landing page listing all available role-specific AI agents.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';

$page_title  = 'VIRAL Agents – AI for Every Job Role | CodeFoundry';
$active_page = 'viral';
$page_styles = <<<'PAGECSS'
  /* ── Hero ── */
  .viral-hero {
    text-align: center;
    padding: 72px 24px 48px;
    position: relative;
    overflow: hidden;
  }
  .viral-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 70% 50% at 50% 0%, #18b3ff18 0%, transparent 70%);
    pointer-events: none;
  }
  .viral-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #18b3ff18;
    border: 1px solid #18b3ff44;
    color: #18b3ff;
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .5px;
    margin-bottom: 20px;
  }
  .viral-hero h1 {
    font-size: clamp(32px, 6vw, 52px);
    font-weight: 900;
    letter-spacing: -1.5px;
    margin: 0 0 18px;
    line-height: 1.1;
  }
  .viral-hero h1 span { color: #18b3ff; }
  .viral-hero p {
    color: #92a3bb;
    font-size: clamp(15px, 2vw, 18px);
    max-width: 580px;
    margin: 0 auto 36px;
    line-height: 1.7;
  }
  /* ── Search bar ── */
  .viral-search-wrap {
    max-width: 480px;
    margin: 0 auto;
    position: relative;
  }
  .viral-search-wrap iconify-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #627193;
    font-size: 18px;
    pointer-events: none;
  }
  .viral-search-wrap input {
    width: 100%;
    background: #0e1828;
    border: 1px solid #1e2e48;
    color: #fff;
    border-radius: 10px;
    padding: 11px 16px 11px 42px;
    font-size: 15px;
    font-family: inherit;
    outline: none;
    transition: border-color .2s;
    box-sizing: border-box;
  }
  .viral-search-wrap input:focus { border-color: #18b3ff; }
  /* ── Category tabs ── */
  .viral-categories {
    max-width: 1200px;
    margin: 48px auto 0;
    padding: 0 24px;
  }
  .cat-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 36px;
  }
  .cat-tab {
    background: #0e1828;
    border: 1px solid #1e2e48;
    color: #92a3bb;
    border-radius: 20px;
    padding: 7px 16px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all .2s;
  }
  .cat-tab:hover, .cat-tab.active {
    background: #18b3ff18;
    border-color: #18b3ff66;
    color: #18b3ff;
  }
  /* ── Grid ── */
  .viral-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 20px;
  }
  /* ── Agent card ── */
  .agent-card {
    background: #0d1626;
    border: 1px solid #1a2942;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: border-color .25s, transform .25s, box-shadow .25s;
    text-decoration: none;
    color: inherit;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .agent-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle 120px at var(--mx, 50%) var(--my, 0%), var(--accent)0c 0%, transparent 70%);
    pointer-events: none;
    opacity: 0;
    transition: opacity .3s;
  }
  .agent-card:hover::before { opacity: 1; }
  .agent-card:hover {
    border-color: var(--accent, #18b3ff)88;
    transform: translateY(-3px);
    box-shadow: 0 12px 40px var(--accent, #18b3ff)14;
  }
  .agent-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent, #18b3ff)1a;
    border: 1px solid var(--accent, #18b3ff)44;
    flex-shrink: 0;
  }
  .agent-card-icon iconify-icon {
    font-size: 24px;
    color: var(--accent, #18b3ff);
  }
  .agent-card h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
  }
  .agent-card p {
    margin: 0;
    font-size: 13px;
    color: #92a3bb;
    line-height: 1.6;
    flex: 1;
  }
  .agent-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 4px;
  }
  .agent-tag {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 10px;
    background: var(--accent, #18b3ff)18;
    color: var(--accent, #18b3ff);
    border: 1px solid var(--accent, #18b3ff)33;
    letter-spacing: .3px;
  }
  .agent-cta {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    font-weight: 600;
    color: var(--accent, #18b3ff);
    opacity: 0;
    transform: translateX(-6px);
    transition: opacity .2s, transform .2s;
  }
  .agent-card:hover .agent-cta { opacity: 1; transform: none; }
  /* No results */
  .no-results {
    grid-column: 1/-1;
    text-align: center;
    color: #627193;
    padding: 60px 0;
    font-size: 15px;
  }
  /* ── Stats bar ── */
  .viral-stats {
    max-width: 1200px;
    margin: 60px auto 0;
    padding: 0 24px 60px;
    display: flex;
    gap: 32px;
    flex-wrap: wrap;
    justify-content: center;
  }
  .stat-pill {
    background: #0d1626;
    border: 1px solid #1a2942;
    border-radius: 12px;
    padding: 16px 28px;
    text-align: center;
    min-width: 140px;
  }
  .stat-pill .stat-num { font-size: 28px; font-weight: 900; color: #18b3ff; }
  .stat-pill .stat-lbl { font-size: 13px; color: #627193; margin-top: 2px; }
  @media (max-width: 640px) {
    .viral-hero { padding: 48px 16px 32px; }
    .viral-categories { padding: 0 16px; }
    .viral-stats { padding: 0 16px 40px; }
  }
PAGECSS;

// Agent definitions
$agents = [
  // Engineering
  ['slug'=>'software-engineer',    'label'=>'Software Engineer',    'icon'=>'lucide:code-2',             'accent'=>'#18b3ff', 'desc'=>'Code, debug, architecture & technical decisions.',       'category'=>'Engineering'],
  ['slug'=>'devops-engineer',      'label'=>'DevOps Engineer',      'icon'=>'lucide:server',             'accent'=>'#38bdf8', 'desc'=>'CI/CD pipelines, cloud infra & SRE best practices.',      'category'=>'Engineering'],
  ['slug'=>'qa-engineer',          'label'=>'QA Engineer',          'icon'=>'lucide:check-circle-2',     'accent'=>'#2dd4bf', 'desc'=>'Test plans, automation scripts & quality assurance.',      'category'=>'Engineering'],
  ['slug'=>'security-expert',      'label'=>'Security Expert',      'icon'=>'lucide:shield-check',       'accent'=>'#f87171', 'desc'=>'Vulnerability assessment & secure architecture.',          'category'=>'Engineering'],
  ['slug'=>'data-scientist',       'label'=>'Data Scientist',       'icon'=>'lucide:chart-bar',           'accent'=>'#34d399', 'desc'=>'ML models, statistical analysis & data insights.',        'category'=>'Engineering'],
  // Business & Strategy
  ['slug'=>'product-manager',      'label'=>'Product Manager',      'icon'=>'lucide:layout-dashboard',   'accent'=>'#a78bfa', 'desc'=>'Roadmaps, user stories & product strategy.',              'category'=>'Business'],
  ['slug'=>'business-analyst',     'label'=>'Business Analyst',     'icon'=>'lucide:briefcase',          'accent'=>'#60a5fa', 'desc'=>'Requirements, process modeling & gap analysis.',          'category'=>'Business'],
  ['slug'=>'project-manager',      'label'=>'Project Manager',      'icon'=>'lucide:kanban',             'accent'=>'#f59e0b', 'desc'=>'Planning, sprint facilitation & risk management.',         'category'=>'Business'],
  ['slug'=>'financial-analyst',    'label'=>'Financial Analyst',    'icon'=>'lucide:trending-up',        'accent'=>'#4ade80', 'desc'=>'Financial models, forecasts & investment analysis.',       'category'=>'Business'],
  ['slug'=>'cto-advisor',          'label'=>'CTO Advisor',          'icon'=>'lucide:cpu',                'accent'=>'#818cf8', 'desc'=>'Tech strategy, team scaling & executive decisions.',       'category'=>'Business'],
  // Marketing & Growth
  ['slug'=>'marketing-manager',    'label'=>'Marketing Manager',    'icon'=>'lucide:megaphone',          'accent'=>'#f97316', 'desc'=>'Campaigns, copy & go-to-market strategy.',                'category'=>'Marketing'],
  ['slug'=>'sales-agent',          'label'=>'Sales Agent',          'icon'=>'lucide:badge-dollar-sign',  'accent'=>'#fbbf24', 'desc'=>'Sales scripts, outreach & deal-closing tactics.',         'category'=>'Marketing'],
  ['slug'=>'seo-specialist',       'label'=>'SEO Specialist',       'icon'=>'lucide:search',             'accent'=>'#facc15', 'desc'=>'Keyword research, on-page & technical SEO audits.',       'category'=>'Marketing'],
  ['slug'=>'content-writer',       'label'=>'Content Writer',       'icon'=>'lucide:file-text',          'accent'=>'#a3e635', 'desc'=>'Blog posts, long-form articles & brand copy.',            'category'=>'Marketing'],
  ['slug'=>'social-media-manager', 'label'=>'Social Media Manager', 'icon'=>'lucide:share-2',           'accent'=>'#e879f9', 'desc'=>'Viral content, calendars & community growth.',            'category'=>'Marketing'],
  // People & Operations
  ['slug'=>'hr-manager',           'label'=>'HR Manager',           'icon'=>'lucide:users',              'accent'=>'#f472b6', 'desc'=>'Hiring, onboarding, performance & HR policies.',          'category'=>'People'],
  ['slug'=>'recruiter',            'label'=>'Recruiter',            'icon'=>'lucide:user-search',        'accent'=>'#fb923c', 'desc'=>'Talent sourcing, job posts & structured interviewing.',   'category'=>'People'],
  ['slug'=>'customer-support',     'label'=>'Customer Support',     'icon'=>'lucide:headphones',         'accent'=>'#22d3ee', 'desc'=>'Empathetic responses, escalation & issue resolution.',    'category'=>'People'],
  // Design & Legal
  ['slug'=>'ux-designer',          'label'=>'UX Designer',          'icon'=>'lucide:pen-tool',           'accent'=>'#fb7185', 'desc'=>'User research, wireframes & design critique.',            'category'=>'Design & Legal'],
  ['slug'=>'legal-counsel',        'label'=>'Legal Counsel',        'icon'=>'lucide:scale',              'accent'=>'#c084fc', 'desc'=>'Contracts, compliance & legal risk guidance.',            'category'=>'Design & Legal'],
];

$categories = ['All', 'Engineering', 'Business', 'Marketing', 'People', 'Design & Legal'];

require_once dirname(__DIR__) . '/includes/header.php';
?>

<main>

  <!-- Hero -->
  <section class="viral-hero">
    <div class="viral-badge">
      <iconify-icon icon="lucide:zap"></iconify-icon>
      VIRAL AI Agents
    </div>
    <h1>AI Agents for <span>Every Job Role</span></h1>
    <p>Pick a role and get an expert AI agent ready to work. From engineering to marketing, legal to HR — every job covered.</p>
    <div class="viral-search-wrap">
      <iconify-icon icon="lucide:search"></iconify-icon>
      <input type="text" id="agentSearch" placeholder="Search agents…" autocomplete="off" />
    </div>
  </section>

  <!-- Agents grid -->
  <div class="viral-categories">
    <!-- Category tabs -->
    <div class="cat-tabs" id="catTabs">
      <?php foreach ($categories as $cat): ?>
        <button class="cat-tab<?= $cat === 'All' ? ' active' : '' ?>" data-cat="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>">
          <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="viral-grid" id="agentGrid">
      <?php foreach ($agents as $a): ?>
        <a
          href="/VIRAL/agent.php?role=<?= urlencode($a['slug']) ?>"
          class="agent-card"
          style="--accent: <?= htmlspecialchars($a['accent'], ENT_QUOTES, 'UTF-8') ?>"
          data-label="<?= htmlspecialchars(strtolower($a['label']), ENT_QUOTES, 'UTF-8') ?>"
          data-cat="<?= htmlspecialchars($a['category'], ENT_QUOTES, 'UTF-8') ?>"
        >
          <div class="agent-card-icon">
            <iconify-icon icon="<?= htmlspecialchars($a['icon'], ENT_QUOTES, 'UTF-8') ?>"></iconify-icon>
          </div>
          <h3><?= htmlspecialchars($a['label'], ENT_QUOTES, 'UTF-8') ?></h3>
          <p><?= htmlspecialchars($a['desc'],  ENT_QUOTES, 'UTF-8') ?></p>
          <div class="agent-card-footer">
            <span class="agent-tag"><?= htmlspecialchars($a['category'], ENT_QUOTES, 'UTF-8') ?></span>
            <span class="agent-cta">
              Chat now
              <iconify-icon icon="lucide:arrow-right"></iconify-icon>
            </span>
          </div>
        </a>
      <?php endforeach; ?>
      <div class="no-results" id="noResults" style="display:none;">No agents match your search.</div>
    </div>
  </div>

  <!-- Stats -->
  <div class="viral-stats">
    <div class="stat-pill">
      <div class="stat-num">20</div>
      <div class="stat-lbl">AI Agents</div>
    </div>
    <div class="stat-pill">
      <div class="stat-num">5</div>
      <div class="stat-lbl">Departments</div>
    </div>
    <div class="stat-pill">
      <div class="stat-num">∞</div>
      <div class="stat-lbl">Conversations</div>
    </div>
    <div class="stat-pill">
      <div class="stat-num">Free</div>
      <div class="stat-lbl">No login required</div>
    </div>
  </div>

</main>

<?php
$page_scripts = <<<'PAGEJS'
(function () {
  const searchInput = document.getElementById('agentSearch');
  const cards       = document.querySelectorAll('.agent-card');
  const noResults   = document.getElementById('noResults');
  const catTabs     = document.querySelectorAll('.cat-tab');
  let activeCat     = 'All';

  function filterCards() {
    const q = searchInput.value.trim().toLowerCase();
    let visible = 0;
    cards.forEach(function (card) {
      const labelMatch = card.dataset.label.includes(q);
      const catMatch   = activeCat === 'All' || card.dataset.cat === activeCat;
      const show       = labelMatch && catMatch;
      card.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    noResults.style.display = visible === 0 ? 'block' : 'none';
  }

  searchInput.addEventListener('input', filterCards);

  catTabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      catTabs.forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');
      activeCat = tab.dataset.cat;
      filterCards();
    });
  });

  // Mouse spotlight effect on cards
  cards.forEach(function (card) {
    card.addEventListener('mousemove', function (e) {
      const rect = card.getBoundingClientRect();
      card.style.setProperty('--mx', (e.clientX - rect.left) + 'px');
      card.style.setProperty('--my', (e.clientY - rect.top) + 'px');
    });
  });
}());
PAGEJS;

require_once dirname(__DIR__) . '/includes/footer.php';
