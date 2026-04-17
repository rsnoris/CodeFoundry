<?php
/**
 * CodeFoundry VIRAL – AI Agents for Every Job Role
 *
 * Landing page listing all available role-specific AI agents.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/config.php';   // VIRAL_AGENTS constant

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
  /* ── Task categories + model guide ── */
  .viral-extra {
    max-width: 1200px;
    margin: 56px auto 0;
    padding: 0 24px;
    display: grid;
    gap: 20px;
  }
  .viral-panel {
    background: #0d1626;
    border: 1px solid #1a2942;
    border-radius: 16px;
    padding: 22px;
  }
  .viral-panel h2 {
    margin: 0 0 8px;
    font-size: 18px;
    font-weight: 800;
  }
  .viral-panel p {
    margin: 0 0 14px;
    color: #92a3bb;
    font-size: 13px;
    line-height: 1.6;
  }
  .task-groups {
    display: grid;
    gap: 14px;
  }
  .task-group-title {
    margin: 0 0 8px;
    color: #c9d6ea;
    font-size: 13px;
    font-weight: 700;
  }
  .task-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }
  .task-tag {
    font-size: 12px;
    color: #a9b8cf;
    border: 1px solid #1e2e48;
    background: #0e1828;
    border-radius: 999px;
    padding: 5px 10px;
  }
  .model-guide-wrap {
    overflow-x: auto;
  }
  .model-guide {
    width: 100%;
    border-collapse: collapse;
    min-width: 780px;
  }
  .model-guide th,
  .model-guide td {
    border-bottom: 1px solid #1a2942;
    padding: 10px 8px;
    text-align: left;
    vertical-align: top;
    font-size: 12px;
    line-height: 1.5;
  }
  .model-guide th {
    color: #dce7f8;
    font-size: 12px;
    font-weight: 700;
  }
  .model-guide td {
    color: #92a3bb;
  }
  .model-chip {
    display: inline-flex;
    align-items: center;
    border: 1px solid #1e2e48;
    background: #0e1828;
    border-radius: 999px;
    padding: 3px 8px;
    font-size: 11px;
    color: #dce7f8;
    font-weight: 600;
  }
  @media (max-width: 640px) {
    .viral-hero { padding: 48px 16px 32px; }
    .viral-categories { padding: 0 16px; }
    .viral-stats { padding: 0 16px 40px; }
    .viral-extra { padding: 0 16px; }
  }
PAGECSS;

// Build the agent list from the shared VIRAL_AGENTS constant so
// there is a single source of truth for all agent metadata.
$agents = [];
foreach (VIRAL_AGENTS as $slug => $a) {
    $agents[] = [
        'slug'     => $slug,
        'label'    => $a['label'],
        'icon'     => $a['icon'],
        'accent'   => $a['accent'],
        'desc'     => $a['desc'],
        'category' => $a['category'],
    ];
}

$agentRegistryValues = array_values(VIRAL_AGENTS);
$categories = array_column($agentRegistryValues, 'category');
$categories = array_values(array_filter($categories, static fn($cat): bool => is_string($cat) && trim($cat) !== ''));
$categories = array_values(array_unique($categories));
sort($categories, SORT_NATURAL | SORT_FLAG_CASE);
array_unshift($categories, 'All');

$taskCategoryGroups = VIRAL_TASK_CATEGORY_GROUPS;
$modelGuide         = VIRAL_MODEL_RECOMMENDATIONS;
$agentCount      = count(VIRAL_AGENTS);
$categoryCount   = count(array_unique(array_column($agentRegistryValues, 'category')));

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
          data-desc="<?= htmlspecialchars(strtolower($a['desc']),  ENT_QUOTES, 'UTF-8') ?>"
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

  <div class="viral-extra">
    <section class="viral-panel">
      <h2>AI Task Categories</h2>
      <p>Added for all VIRAL user roles to map role workflows to the right AI task type.</p>
      <div class="task-groups">
        <?php foreach ($taskCategoryGroups as $groupName => $groupTasks): ?>
          <div>
            <h3 class="task-group-title"><?= htmlspecialchars((string)$groupName, ENT_QUOTES, 'UTF-8') ?></h3>
            <div class="task-tags">
              <?php foreach ($groupTasks as $task): ?>
                <span class="task-tag"><?= htmlspecialchars((string)$task, ENT_QUOTES, 'UTF-8') ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="viral-panel">
      <h2>Model Selection Recommendations</h2>
      <p>Choose by priority: highest output quality, best quality / performance balance, or lowest token/cost usage for your task category.</p>
      <div class="model-guide-wrap">
        <table class="model-guide">
          <thead>
            <tr>
              <th>Task Group</th>
              <th>Highest Quality</th>
              <th>Best Balance (Quality + Performance)</th>
              <th>Cost/Token Efficient</th>
              <th>Selection Guidance</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($modelGuide as $row): ?>
              <tr>
                <td><?= htmlspecialchars((string)$row['task_group'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><span class="model-chip"><?= htmlspecialchars((string)$row['quality'], ENT_QUOTES, 'UTF-8') ?></span></td>
                <td><span class="model-chip"><?= htmlspecialchars((string)$row['balanced'], ENT_QUOTES, 'UTF-8') ?></span></td>
                <td><span class="model-chip"><?= htmlspecialchars((string)$row['efficient'], ENT_QUOTES, 'UTF-8') ?></span></td>
                <td><?= htmlspecialchars((string)$row['note'], ENT_QUOTES, 'UTF-8') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>

  <!-- Stats -->
  <div class="viral-stats">
    <div class="stat-pill">
      <div class="stat-num"><?= $agentCount ?></div>
      <div class="stat-lbl">AI Agents</div>
    </div>
    <div class="stat-pill">
      <div class="stat-num"><?= $categoryCount ?></div>
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
      const labelMatch = card.dataset.label.includes(q) || card.dataset.desc.includes(q);
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
