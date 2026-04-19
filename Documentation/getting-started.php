<?php
$page_title  = 'Getting Started - CodeFoundry Documentation';
$active_page = '';
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

main {
  max-width: var(--maxwidth);
  margin: 0 auto;
  padding: 60px 40px;
}
@media (max-width: 768px) {
  main { padding: 40px 20px; }
}

/* Breadcrumb */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: var(--text-muted);
  margin-bottom: 40px;
  flex-wrap: wrap;
}
.breadcrumb a { color: var(--primary); font-weight: 600; }
.breadcrumb a:hover { color: var(--primary-hover); }
.breadcrumb-sep { color: var(--text-subtle); }

/* Page header */
.page-header {
  margin-bottom: 60px;
}
.page-badge {
  display: inline-block;
  background: rgba(24, 179, 255, 0.15);
  color: var(--primary);
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  margin-bottom: 16px;
}
.page-title {
  font-size: 2.8rem;
  font-weight: 900;
  margin: 0 0 16px 0;
  letter-spacing: -1.5px;
  line-height: 1.1;
}
.page-desc {
  font-size: 1.15rem;
  color: var(--text-muted);
  max-width: 680px;
  line-height: 1.6;
}
@media (max-width: 768px) {
  .page-title { font-size: 2rem; }
  .page-desc  { font-size: 1rem; }
}

/* Section headings */
.section { margin-bottom: 64px; }
.section-title {
  font-size: 1.6rem;
  font-weight: 800;
  margin: 0 0 8px 0;
  letter-spacing: -0.5px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.section-title iconify-icon {
  color: var(--primary);
  font-size: 1.4rem;
}
.section-subtitle {
  color: var(--text-muted);
  font-size: 1rem;
  margin: 0 0 28px 0;
  line-height: 1.6;
}
.section-divider {
  border: none;
  border-top: 1px solid var(--border-color);
  margin: 0 0 28px 0;
}

/* Step cards */
.steps-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 24px;
}
@media (max-width: 768px) { .steps-grid { grid-template-columns: 1fr; } }

.step-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 32px;
  position: relative;
  transition: border-color 0.2s, transform 0.2s;
}
.step-card:hover {
  border-color: var(--primary);
  transform: translateY(-3px);
}
.step-number {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: var(--primary);
  color: #061522;
  font-weight: 900;
  font-size: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
}
.step-card h3 {
  font-size: 1.2rem;
  font-weight: 800;
  margin: 0 0 10px 0;
}
.step-card p {
  color: var(--text-muted);
  line-height: 1.6;
  margin: 0;
  font-size: 0.95rem;
}
.step-card .step-action {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--primary);
  font-weight: 700;
  font-size: 14px;
  margin-top: 16px;
}

/* Platform overview cards */
.platform-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 20px;
}
@media (max-width: 768px) { .platform-grid { grid-template-columns: 1fr; } }

.platform-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 28px;
  transition: border-color 0.2s, transform 0.2s;
}
.platform-card:hover {
  border-color: var(--primary);
  transform: translateY(-3px);
}
.platform-icon {
  font-size: 2.4rem;
  color: var(--primary);
  margin-bottom: 16px;
  display: block;
}
.platform-card h3 {
  font-size: 1.1rem;
  font-weight: 800;
  margin: 0 0 8px 0;
}
.platform-card p {
  color: var(--text-muted);
  font-size: 0.9rem;
  line-height: 1.6;
  margin: 0 0 12px 0;
}
.platform-tag {
  display: inline-block;
  background: rgba(24,179,255,0.12);
  color: var(--primary);
  border-radius: 6px;
  padding: 3px 10px;
  font-size: 12px;
  font-weight: 700;
}

/* Checklist */
.checklist {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 16px;
}
@media (max-width: 768px) { .checklist { grid-template-columns: 1fr; } }

.checklist li {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: 10px;
  padding: 18px 22px;
  display: flex;
  align-items: flex-start;
  gap: 14px;
  font-size: 0.95rem;
  line-height: 1.5;
}
.checklist li .check-icon {
  color: var(--primary);
  font-size: 1.3rem;
  flex-shrink: 0;
  margin-top: 1px;
}
.checklist li strong { display: block; font-weight: 700; margin-bottom: 4px; }
.checklist li span { color: var(--text-muted); font-size: 0.88rem; }

/* Next steps */
.next-steps-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 16px;
}
@media (max-width: 768px) { .next-steps-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 480px) { .next-steps-grid { grid-template-columns: 1fr; } }

.next-step-link {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 22px 24px;
  display: flex;
  align-items: center;
  gap: 14px;
  font-weight: 700;
  font-size: 0.95rem;
  transition: border-color 0.2s, background 0.2s;
}
.next-step-link:hover {
  border-color: var(--primary);
  background: rgba(24,179,255,0.07);
  color: var(--primary);
}
.next-step-link iconify-icon { font-size: 1.4rem; color: var(--primary); flex-shrink: 0; }

/* Back link */
.back-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: var(--primary);
  font-weight: 700;
  font-size: 14px;
  margin-bottom: 32px;
}
.back-link:hover { color: var(--primary-hover); }
PAGECSS;
$page_scripts = <<<'PAGEJS'
const menuBtn = document.getElementById('mobileMenuBtn');
const mobileNav = document.getElementById('mobileNav');
const closeBtn = document.getElementById('closeMobileNav');
function closeMobileNav() { mobileNav.classList.remove('open'); }
if (menuBtn) menuBtn.onclick = () => mobileNav.classList.add('open');
if (closeBtn) closeBtn.onclick = closeMobileNav;
if (mobileNav) mobileNav.onclick = (e) => { if (e.target === mobileNav) closeMobileNav(); };
PAGEJS;
require_once __DIR__ . '/../includes/header.php';
?>

<main>
  <!-- Breadcrumb -->
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="/Documentation/">Documentation Hub</a>
    <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
    <span>Getting Started</span>
  </nav>

  <a href="/Documentation/" class="back-link">
    <iconify-icon icon="lucide:arrow-left"></iconify-icon>
    Back to Documentation
  </a>

  <!-- Page header -->
  <div class="page-header">
    <span class="page-badge">Beginner Guide</span>
    <h1 class="page-title">Getting Started with CodeFoundry</h1>
    <p class="page-desc">
      Everything you need to go from zero to productive in minutes. Follow this guide to set up your environment, explore the platform, and deploy your first project.
    </p>
  </div>

  <!-- Quick Start -->
  <section class="section" id="quick-start">
    <h2 class="section-title">
      <iconify-icon icon="lucide:zap"></iconify-icon>
      Quick Start
    </h2>
    <p class="section-subtitle">Get up and running in three simple steps. Each step takes less than five minutes.</p>
    <hr class="section-divider">
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-number">1</div>
        <h3>Create Your Account</h3>
        <p>Sign up for a free CodeFoundry account. Choose a plan that fits your needs — from the free Starter tier to Enterprise. Verify your email and complete your profile to unlock all platform features.</p>
        <a href="/Signup/" class="step-action">
          Create Account <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
      <div class="step-card">
        <div class="step-number">2</div>
        <h3>Choose a Service</h3>
        <p>Select the service that matches your goal: spin up the browser-based IDE for instant coding, use the AI Code Generator to scaffold a new project, or access Cloud Services to provision your first environment.</p>
        <a href="/#services" class="step-action">
          Explore Services <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
      <div class="step-card">
        <div class="step-number">3</div>
        <h3>Deploy Your First Project</h3>
        <p>Push your code to a CodeFoundry project. Connect your Git repository or use our built-in version control, then trigger a one-click deploy to our managed cloud infrastructure. Your app goes live instantly.</p>
        <a href="/Dashboard/" class="step-action">
          Open Dashboard <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
    </div>
  </section>

  <!-- Platform Overview -->
  <section class="section" id="platform-overview">
    <h2 class="section-title">
      <iconify-icon icon="lucide:layout-grid"></iconify-icon>
      Platform Overview
    </h2>
    <p class="section-subtitle">CodeFoundry brings together four core capabilities under one unified platform. Here's a brief overview of each.</p>
    <hr class="section-divider">
    <div class="platform-grid">
      <div class="platform-card">
        <iconify-icon icon="lucide:monitor-code" class="platform-icon"></iconify-icon>
        <h3>Online IDE</h3>
        <p>A fully featured browser-based development environment supporting 30+ languages. Real-time collaboration, IntelliSense, integrated terminal, and Git out of the box.</p>
        <a href="/IDE/" class="platform-tag">Open IDE →</a>
      </div>
      <div class="platform-card">
        <iconify-icon icon="lucide:wand-2" class="platform-icon"></iconify-icon>
        <h3>AI Code Generator</h3>
        <p>Describe what you want to build and our AI assistant generates production-ready scaffolding, functions, tests, and documentation — saving hours of boilerplate work.</p>
        <a href="/Generate/" class="platform-tag">Try Generator →</a>
      </div>
      <div class="platform-card">
        <iconify-icon icon="lucide:graduation-cap" class="platform-icon"></iconify-icon>
        <h3>Training &amp; Learning</h3>
        <p>Structured learning paths, video tutorials, hands-on labs, and certification programs curated for both beginners and experienced engineers.</p>
        <a href="/Training/" class="platform-tag">Start Learning →</a>
      </div>
      <div class="platform-card">
        <iconify-icon icon="lucide:cloud" class="platform-icon"></iconify-icon>
        <h3>Cloud Services</h3>
        <p>Managed infrastructure across AWS, Azure, and GCP. Provision databases, containers, serverless functions, and CI/CD pipelines through a single control plane.</p>
        <a href="/Services/CloudConsulting/" class="platform-tag">Cloud Docs →</a>
      </div>
    </div>
  </section>

  <!-- First Steps Checklist -->
  <section class="section" id="first-steps">
    <h2 class="section-title">
      <iconify-icon icon="lucide:list-checks"></iconify-icon>
      First Steps Checklist
    </h2>
    <p class="section-subtitle">Work through these tasks to make the most of CodeFoundry from day one. Each item links to the relevant feature or guide.</p>
    <hr class="section-divider">
    <ul class="checklist">
      <li>
        <iconify-icon icon="lucide:check-circle-2" class="check-icon"></iconify-icon>
        <div>
          <strong>Set up the Online IDE</strong>
          <span>Open the IDE, choose your default language, install any extensions you need, and connect your GitHub or GitLab account for seamless repository access.</span>
        </div>
      </li>
      <li>
        <iconify-icon icon="lucide:check-circle-2" class="check-icon"></iconify-icon>
        <div>
          <strong>Run Your First Code Snippet</strong>
          <span>Use the IDE's built-in runner to execute a "Hello, World!" program in your preferred language. Verify that output, errors, and the terminal all work as expected.</span>
        </div>
      </li>
      <li>
        <iconify-icon icon="lucide:check-circle-2" class="check-icon"></iconify-icon>
        <div>
          <strong>Try the AI Code Generator</strong>
          <span>Navigate to Generate, enter a plain-English prompt describing a feature or function, and review the generated code. Refine the prompt and iterate to see how the AI adapts.</span>
        </div>
      </li>
      <li>
        <iconify-icon icon="lucide:check-circle-2" class="check-icon"></iconify-icon>
        <div>
          <strong>Invite a Team Member</strong>
          <span>Go to your Dashboard settings and invite a colleague. Collaborate on a shared project in real time using the IDE's live-editing and commenting features.</span>
        </div>
      </li>
      <li>
        <iconify-icon icon="lucide:check-circle-2" class="check-icon"></iconify-icon>
        <div>
          <strong>Configure Your API Key</strong>
          <span>Generate an API key from your account settings. You'll need this key to integrate CodeFoundry services into your existing CI/CD pipelines or external tooling.</span>
        </div>
      </li>
      <li>
        <iconify-icon icon="lucide:check-circle-2" class="check-icon"></iconify-icon>
        <div>
          <strong>Deploy to the Cloud</strong>
          <span>Use the one-click deploy button from any IDE project to push your application to a managed cloud environment. Review the deployment logs in the Dashboard.</span>
        </div>
      </li>
      <li>
        <iconify-icon icon="lucide:check-circle-2" class="check-icon"></iconify-icon>
        <div>
          <strong>Enroll in a Training Path</strong>
          <span>Browse the Training catalogue and enroll in a learning path that matches your skill level. Complete the first module and track your progress on the Training dashboard.</span>
        </div>
      </li>
    </ul>
  </section>

  <!-- Next Steps -->
  <section class="section" id="next-steps">
    <h2 class="section-title">
      <iconify-icon icon="lucide:arrow-right-circle"></iconify-icon>
      Next Steps
    </h2>
    <p class="section-subtitle">Ready to go deeper? Explore the other documentation sections for advanced topics.</p>
    <hr class="section-divider">
    <div class="next-steps-grid">
      <a href="/Documentation/api-reference.php" class="next-step-link">
        <iconify-icon icon="lucide:code-2"></iconify-icon>
        API Reference
      </a>
      <a href="/Documentation/cloud-solutions.php" class="next-step-link">
        <iconify-icon icon="lucide:cloud"></iconify-icon>
        Cloud Solutions
      </a>
      <a href="/Documentation/security-compliance.php" class="next-step-link">
        <iconify-icon icon="lucide:shield-check"></iconify-icon>
        Security &amp; Compliance
      </a>
      <a href="/Documentation/troubleshooting.php" class="next-step-link">
        <iconify-icon icon="lucide:wrench"></iconify-icon>
        Troubleshooting
      </a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
