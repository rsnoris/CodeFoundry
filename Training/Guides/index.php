<?php
$page_title  = 'Implementation Guides – CodeFoundry Training';
$active_page = 'training';
$page_styles = <<<'PAGECSS'
:root {
  --navy:         #0e1828;
  --navy-2:       #121c2b;
  --navy-3:       #161f2f;
  --primary:      #18b3ff;
  --primary-hover:#009de0;
  --text:         #fff;
  --text-muted:   #92a3bb;
  --text-subtle:  #627193;
  --border-color: #1a2942;
  --button-radius:8px;
  --maxwidth:     1200px;
  --card-radius:  12px;
  --header-height:68px;
}
html, body { background:var(--navy-2); color:var(--text); font-family:'Inter',sans-serif; margin:0; padding:0; }
body { min-height:100vh; }
a { color:inherit; text-decoration:none; }

.breadcrumb { max-width:var(--maxwidth); margin:0 auto; padding:20px 40px 0; display:flex; align-items:center; gap:8px; font-size:.85rem; color:var(--text-muted); flex-wrap:wrap; }
.breadcrumb a { color:var(--text-muted); transition:color .2s; }
.breadcrumb a:hover { color:var(--primary); }
.breadcrumb-sep { color:var(--text-subtle); }
.breadcrumb-current { color:var(--text); font-weight:600; }

.hero { background:linear-gradient(135deg,var(--navy) 0%,#0d1e36 60%,#0a1826 100%); border-bottom:1px solid var(--border-color); padding:72px 40px 64px; text-align:center; position:relative; overflow:hidden; }
.hero::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 900px 500px at 50% -80px,rgba(24,179,255,.13) 0%,transparent 70%); pointer-events:none; }
.hero-inner { position:relative; max-width:700px; margin:0 auto; }
.hero-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(24,179,255,.1); border:1px solid rgba(24,179,255,.25); color:var(--primary); font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; padding:5px 14px; border-radius:100px; margin-bottom:20px; }
.hero h1 { font-size:clamp(2rem,5vw,3rem); font-weight:900; line-height:1.1; margin:0 0 16px; background:linear-gradient(135deg,#fff 40%,var(--primary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.hero p { color:var(--text-muted); font-size:1.05rem; line-height:1.7; margin:0 0 36px; }
.hero-stats { display:flex; justify-content:center; gap:48px; flex-wrap:wrap; }
.hero-stat { text-align:center; }
.hero-stat-value { font-size:1.8rem; font-weight:800; color:var(--primary); display:block; }
.hero-stat-label { font-size:.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.08em; }

.guides-section { max-width:var(--maxwidth); margin:0 auto; padding:56px 40px; }
@media(max-width:700px){ .guides-section { padding:40px 20px; } }
.section-title { font-size:1.3rem; font-weight:800; margin:0 0 8px; }
.section-subtitle { color:var(--text-muted); font-size:.9rem; margin:0 0 32px; }
.guides-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(340px,1fr)); gap:24px; }
@media(max-width:760px){ .guides-grid { grid-template-columns:1fr; } }

.guide-card { background:var(--navy-3); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:28px; display:flex; flex-direction:column; gap:16px; transition:border-color .2s,transform .2s,box-shadow .2s; }
.guide-card:hover { border-color:rgba(24,179,255,.4); transform:translateY(-3px); box-shadow:0 12px 40px rgba(0,0,0,.3); }
.card-top { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; }
.card-icon { width:48px; height:48px; background:rgba(24,179,255,.1); border:1px solid rgba(24,179,255,.2); border-radius:10px; display:flex; align-items:center; justify-content:center; color:var(--primary); font-size:1.4rem; flex-shrink:0; }
.card-meta { display:flex; flex-direction:column; align-items:flex-end; gap:6px; }
.card-level { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; padding:3px 10px; border-radius:100px; }
.level-intermediate { background:rgba(24,179,255,.1); color:var(--primary); border:1px solid rgba(24,179,255,.2); }
.level-advanced { background:rgba(139,92,246,.1); color:#a78bfa; border:1px solid rgba(139,92,246,.2); }
.level-expert { background:rgba(239,68,68,.1); color:#f87171; border:1px solid rgba(239,68,68,.2); }
.card-time { font-size:.75rem; color:var(--text-muted); display:flex; align-items:center; gap:4px; }
.card-title { font-size:1.1rem; font-weight:800; color:var(--text); margin:0; line-height:1.3; }
.card-desc { color:var(--text-muted); font-size:.88rem; line-height:1.65; margin:0; flex:1; }
.card-tags { display:flex; flex-wrap:wrap; gap:6px; }
.card-tag { background:rgba(255,255,255,.04); border:1px solid var(--border-color); color:var(--text-muted); font-size:.72rem; padding:3px 10px; border-radius:100px; }
.card-link { display:flex; align-items:center; justify-content:space-between; color:var(--primary); font-size:.88rem; font-weight:600; padding-top:12px; border-top:1px solid var(--border-color); transition:gap .2s; }
.card-link iconify-icon { transition:transform .2s; }
.guide-card:hover .card-link iconify-icon { transform:translateX(4px); }

.back-to-training { max-width:var(--maxwidth); margin:0 auto 48px; padding:0 40px; }
.back-btn { display:inline-flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.88rem; border:1px solid var(--border-color); padding:10px 20px; border-radius:8px; transition:all .2s; }
.back-btn:hover { color:var(--primary); border-color:rgba(24,179,255,.4); background:rgba(24,179,255,.05); }
PAGECSS;
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="breadcrumb">
  <a href="/Training/">Training</a>
  <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
  <span class="breadcrumb-current">Implementation Guides</span>
</div>

<section class="hero">
  <div class="hero-inner">
    <div class="hero-badge"><iconify-icon icon="lucide:book-open"></iconify-icon> Implementation Guides</div>
    <h1>Build Real-World Systems</h1>
    <p>Comprehensive, step-by-step guides covering complete implementations of production-ready systems — from architecture decisions to deployment, with real code you can use today.</p>
    <div class="hero-stats">
      <div class="hero-stat">
        <span class="hero-stat-value">6</span>
        <span class="hero-stat-label">Guides</span>
      </div>
      <div class="hero-stat">
        <span class="hero-stat-value">275+</span>
        <span class="hero-stat-label">Minutes of Content</span>
      </div>
      <div class="hero-stat">
        <span class="hero-stat-value">48</span>
        <span class="hero-stat-label">Code Examples</span>
      </div>
    </div>
  </div>
</section>

<div class="guides-section">
  <div class="section-title">All Implementation Guides</div>
  <div class="section-subtitle">End-to-end walkthroughs for building complete, production-grade systems.</div>

  <div class="guides-grid">

    <a href="/Training/Guides/fullstack-web-app.php" class="guide-card">
      <div class="card-top">
        <div class="card-icon"><iconify-icon icon="lucide:layers"></iconify-icon></div>
        <div class="card-meta">
          <span class="card-level level-advanced">Advanced</span>
          <span class="card-time"><iconify-icon icon="lucide:clock"></iconify-icon> 45 min</span>
        </div>
      </div>
      <h2 class="card-title">Building a Full-Stack Web Application</h2>
      <p class="card-desc">End-to-end guide: React frontend, Node.js + Express REST API, MongoDB, JWT authentication, Socket.io real-time features, and Docker deployment.</p>
      <div class="card-tags">
        <span class="card-tag">React</span>
        <span class="card-tag">Node.js</span>
        <span class="card-tag">MongoDB</span>
        <span class="card-tag">JWT</span>
        <span class="card-tag">REST API</span>
      </div>
      <div class="card-link">Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon></div>
    </a>

    <a href="/Training/Guides/microservices-architecture.php" class="guide-card">
      <div class="card-top">
        <div class="card-icon"><iconify-icon icon="lucide:boxes"></iconify-icon></div>
        <div class="card-meta">
          <span class="card-level level-expert">Expert</span>
          <span class="card-time"><iconify-icon icon="lucide:clock"></iconify-icon> 60 min</span>
        </div>
      </div>
      <h2 class="card-title">Microservices Architecture Implementation</h2>
      <p class="card-desc">Design, containerise, and orchestrate a microservices system with Docker, Kubernetes, API gateways, Kafka messaging, and full observability.</p>
      <div class="card-tags">
        <span class="card-tag">Microservices</span>
        <span class="card-tag">Docker</span>
        <span class="card-tag">Kubernetes</span>
        <span class="card-tag">Kafka</span>
      </div>
      <div class="card-link">Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon></div>
    </a>

    <a href="/Training/Guides/cicd-pipeline.php" class="guide-card">
      <div class="card-top">
        <div class="card-icon"><iconify-icon icon="lucide:git-merge"></iconify-icon></div>
        <div class="card-meta">
          <span class="card-level level-intermediate">Intermediate</span>
          <span class="card-time"><iconify-icon icon="lucide:clock"></iconify-icon> 35 min</span>
        </div>
      </div>
      <h2 class="card-title">CI/CD Pipeline Setup</h2>
      <p class="card-desc">Automate your entire delivery pipeline with GitHub Actions — testing, code quality gates, Docker builds, multi-environment deployment, and rollback strategies.</p>
      <div class="card-tags">
        <span class="card-tag">CI/CD</span>
        <span class="card-tag">GitHub Actions</span>
        <span class="card-tag">Testing</span>
        <span class="card-tag">Docker</span>
      </div>
      <div class="card-link">Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon></div>
    </a>

    <a href="/Training/Guides/cloud-native-development.php" class="guide-card">
      <div class="card-top">
        <div class="card-icon"><iconify-icon icon="lucide:cloud"></iconify-icon></div>
        <div class="card-meta">
          <span class="card-level level-advanced">Advanced</span>
          <span class="card-time"><iconify-icon icon="lucide:clock"></iconify-icon> 50 min</span>
        </div>
      </div>
      <h2 class="card-title">Cloud-Native Application Development</h2>
      <p class="card-desc">Build on AWS with Lambda, DynamoDB, and Terraform IaC. Covers event-driven architecture, caching strategies, security best practices, and cost optimisation.</p>
      <div class="card-tags">
        <span class="card-tag">AWS</span>
        <span class="card-tag">Serverless</span>
        <span class="card-tag">Terraform</span>
        <span class="card-tag">DynamoDB</span>
      </div>
      <div class="card-link">Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon></div>
    </a>

    <a href="/Training/Guides/react-native-mobile.php" class="guide-card">
      <div class="card-top">
        <div class="card-icon"><iconify-icon icon="lucide:smartphone"></iconify-icon></div>
        <div class="card-meta">
          <span class="card-level level-intermediate">Intermediate</span>
          <span class="card-time"><iconify-icon icon="lucide:clock"></iconify-icon> 40 min</span>
        </div>
      </div>
      <h2 class="card-title">Mobile App Development with React Native</h2>
      <p class="card-desc">Build cross-platform iOS and Android apps with Expo, React Navigation, Zustand state management, native device APIs, and EAS submission to app stores.</p>
      <div class="card-tags">
        <span class="card-tag">React Native</span>
        <span class="card-tag">Expo</span>
        <span class="card-tag">iOS</span>
        <span class="card-tag">Android</span>
      </div>
      <div class="card-link">Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon></div>
    </a>

    <a href="/Training/Guides/graphql-api.php" class="guide-card">
      <div class="card-top">
        <div class="card-icon"><iconify-icon icon="lucide:share-2"></iconify-icon></div>
        <div class="card-meta">
          <span class="card-level level-advanced">Advanced</span>
          <span class="card-time"><iconify-icon icon="lucide:clock"></iconify-icon> 45 min</span>
        </div>
      </div>
      <h2 class="card-title">GraphQL API Implementation</h2>
      <p class="card-desc">Design and build a production GraphQL API with Apollo Server — schema design, DataLoader N+1 optimisation, real-time subscriptions, auth, and testing.</p>
      <div class="card-tags">
        <span class="card-tag">GraphQL</span>
        <span class="card-tag">Apollo</span>
        <span class="card-tag">DataLoader</span>
        <span class="card-tag">Subscriptions</span>
      </div>
      <div class="card-link">Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon></div>
    </a>

  </div>
</div>

<div class="back-to-training">
  <a href="/Training/" class="back-btn"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Training</a>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
