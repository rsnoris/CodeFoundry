<?php
$page_title  = 'Certification Programs - CodeFoundry Training';
$active_page = 'training';
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
}
html, body {
  background: var(--navy-2);
  color: var(--text);
  font-family: 'Inter', sans-serif;
  margin: 0; padding: 0;
}
body { min-height: 100vh; }
a { color: inherit; text-decoration: none; }

/* ── Hero ─────────────────────────────────────────────── */
.cert-hero {
  background: linear-gradient(135deg, var(--navy) 0%, #0d1e36 60%, #0a1826 100%);
  border-bottom: 1px solid var(--border-color);
  padding: 80px 40px 72px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.cert-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse 900px 500px at 50% -80px, rgba(24,179,255,.13) 0%, transparent 70%);
  pointer-events: none;
}
.cert-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(24,179,255,.1);
  border: 1px solid rgba(24,179,255,.25);
  color: var(--primary);
  font-size: .75rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  padding: 6px 16px;
  border-radius: 100px;
  margin-bottom: 24px;
}
.cert-hero h1 {
  font-size: clamp(2rem, 5vw, 3.25rem);
  font-weight: 900;
  line-height: 1.1;
  margin: 0 0 20px;
  background: linear-gradient(135deg, #fff 40%, var(--primary));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.cert-hero p {
  max-width: 640px;
  margin: 0 auto 36px;
  color: var(--text-muted);
  font-size: 1.1rem;
  line-height: 1.65;
}
.hero-stats {
  display: flex;
  justify-content: center;
  gap: 48px;
  flex-wrap: wrap;
}
.hero-stat-item { text-align: center; }
.hero-stat-value {
  font-size: 2rem;
  font-weight: 800;
  color: var(--primary);
  display: block;
}
.hero-stat-label {
  font-size: .8rem;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .08em;
}

/* ── Shared section layout ────────────────────────────── */
.cert-section {
  max-width: var(--maxwidth);
  margin: 0 auto;
  padding: 80px 40px;
}
.section-heading { text-align: center; margin-bottom: 56px; }
.section-badge {
  display: inline-block;
  background: rgba(24,179,255,.1);
  border: 1px solid rgba(24,179,255,.2);
  color: var(--primary);
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  padding: 5px 14px;
  border-radius: 100px;
  margin-bottom: 16px;
}
.section-title {
  font-size: clamp(1.6rem, 3.5vw, 2.4rem);
  font-weight: 800;
  margin: 0 0 14px;
}
.section-desc {
  color: var(--text-muted);
  font-size: 1rem;
  max-width: 580px;
  margin: 0 auto;
  line-height: 1.6;
}

/* ── Standards grid ───────────────────────────────────── */
.standards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
}
.standard-card {
  background: var(--navy);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 28px 28px 24px;
  transition: border-color .2s, transform .2s;
}
.standard-card:hover {
  border-color: rgba(24,179,255,.4);
  transform: translateY(-2px);
}
.standard-icon {
  width: 44px; height: 44px;
  background: rgba(24,179,255,.12);
  border: 1px solid rgba(24,179,255,.2);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  color: var(--primary);
  font-size: 1.3rem;
  margin-bottom: 16px;
}
.standard-title {
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 10px;
}
.standard-items {
  list-style: none;
  margin: 0; padding: 0;
  display: flex; flex-direction: column; gap: 7px;
}
.standard-items li {
  display: flex;
  align-items: center;
  gap: 9px;
  color: var(--text-muted);
  font-size: .875rem;
  line-height: 1.4;
}
.standard-items li::before {
  content: '';
  flex-shrink: 0;
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--primary);
  opacity: .7;
}

/* ── Tier section background ──────────────────────────── */
.tiers-section {
  background: var(--navy-3);
  border-top: 1px solid var(--border-color);
  border-bottom: 1px solid var(--border-color);
}

/* ── Tier cards ───────────────────────────────────────── */
.tiers-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 24px;
}
.tier-card {
  background: var(--navy);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: border-color .2s, transform .2s, box-shadow .2s;
}
.tier-card:hover {
  border-color: rgba(24,179,255,.4);
  transform: translateY(-3px);
  box-shadow: 0 12px 40px rgba(0,0,0,.3);
}
.tier-card.featured {
  border-color: rgba(24,179,255,.5);
  box-shadow: 0 0 0 1px rgba(24,179,255,.15);
}
.tier-header {
  padding: 26px 28px 20px;
  border-bottom: 1px solid var(--border-color);
  display: flex;
  align-items: flex-start;
  gap: 16px;
}
.tier-icon-wrap {
  width: 48px; height: 48px;
  background: rgba(24,179,255,.12);
  border: 1px solid rgba(24,179,255,.2);
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  color: var(--primary);
  font-size: 1.4rem;
  flex-shrink: 0;
}
.tier-meta { flex: 1; }
.tier-level-badge {
  display: inline-block;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  padding: 3px 10px;
  border-radius: 100px;
  margin-bottom: 6px;
}
.badge-foundation  { background: rgba(34,197,94,.12);  color: #22c55e; border: 1px solid rgba(34,197,94,.25); }
.badge-professional { background: rgba(99,102,241,.12); color: #818cf8; border: 1px solid rgba(99,102,241,.25); }
.badge-expert       { background: rgba(234,179,8,.12);  color: #fbbf24; border: 1px solid rgba(234,179,8,.25); }
.badge-specialist   { background: rgba(239,68,68,.12);  color: #f87171; border: 1px solid rgba(239,68,68,.25); }
.badge-team         { background: rgba(24,179,255,.12); color: var(--primary); border: 1px solid rgba(24,179,255,.25); }
.tier-name {
  font-size: 1.05rem;
  font-weight: 700;
  line-height: 1.25;
  margin: 0;
}
.tier-pricing {
  display: flex;
  align-items: baseline;
  gap: 6px;
  margin-top: 4px;
}
.tier-price {
  font-size: 1.35rem;
  font-weight: 800;
  color: var(--primary);
}
.tier-price-label {
  font-size: .78rem;
  color: var(--text-muted);
}
.tier-body { padding: 22px 28px; flex: 1; display: flex; flex-direction: column; gap: 18px; }
.tier-desc { color: var(--text-muted); font-size: .9rem; line-height: 1.6; }
.tier-info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}
.tier-info-item {
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 10px 12px;
}
.tier-info-label {
  font-size: .7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: var(--text-subtle);
  margin-bottom: 4px;
}
.tier-info-value {
  font-size: .85rem;
  color: var(--text);
  font-weight: 600;
}
.tier-section-title {
  font-size: .75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .1em;
  color: var(--text-subtle);
  margin-bottom: 8px;
}
.tier-list {
  list-style: none; margin: 0; padding: 0;
  display: flex; flex-direction: column; gap: 6px;
}
.tier-list li {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: .875rem;
  color: var(--text-muted);
}
.tier-list li iconify-icon {
  color: var(--primary);
  font-size: 1rem;
  flex-shrink: 0;
}
.skill-tags {
  display: flex; flex-wrap: wrap; gap: 6px;
}
.skill-tag {
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  color: var(--text-muted);
  font-size: .75rem;
  padding: 3px 10px;
  border-radius: 100px;
}
.tier-footer { padding: 0 28px 26px; }
.tier-btn {
  display: block;
  width: 100%;
  padding: 12px;
  background: var(--primary);
  color: #fff;
  font-weight: 700;
  font-size: .9rem;
  text-align: center;
  border-radius: var(--button-radius);
  transition: background .2s;
  box-sizing: border-box;
}
.tier-btn:hover { background: var(--primary-hover); }
.tier-btn.outline {
  background: transparent;
  border: 1px solid rgba(24,179,255,.4);
  color: var(--primary);
}
.tier-btn.outline:hover {
  background: rgba(24,179,255,.08);
  border-color: var(--primary);
}
.tier-exam-format {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 12px 14px;
  font-size: .875rem;
  color: var(--text-muted);
  line-height: 1.5;
}
.tier-exam-format iconify-icon {
  color: var(--primary);
  font-size: 1.1rem;
  margin-top: 1px;
  flex-shrink: 0;
}

/* ── Process section ──────────────────────────────────── */
.process-section { background: var(--navy); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
.process-steps {
  display: flex;
  align-items: flex-start;
  gap: 0;
  flex-wrap: wrap;
  justify-content: center;
}
.process-step {
  flex: 1;
  min-width: 180px;
  max-width: 220px;
  text-align: center;
  padding: 16px 8px;
  position: relative;
}
.process-step:not(:last-child)::after {
  content: '';
  position: absolute;
  top: 36px;
  right: -20px;
  width: 40px;
  height: 2px;
  background: linear-gradient(90deg, var(--border-color), transparent);
}
.step-number {
  width: 52px; height: 52px;
  border-radius: 50%;
  background: rgba(24,179,255,.12);
  border: 2px solid rgba(24,179,255,.3);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 14px;
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--primary);
}
.step-title {
  font-size: .95rem;
  font-weight: 700;
  margin-bottom: 6px;
}
.step-desc {
  font-size: .82rem;
  color: var(--text-muted);
  line-height: 1.5;
}

/* ── CTA section ──────────────────────────────────────── */
.cta-section {
  text-align: center;
  padding: 80px 40px;
  max-width: var(--maxwidth);
  margin: 0 auto;
}
.cta-section h2 {
  font-size: clamp(1.6rem, 3.5vw, 2.2rem);
  font-weight: 800;
  margin-bottom: 16px;
}
.cta-section p {
  color: var(--text-muted);
  font-size: 1rem;
  max-width: 520px;
  margin: 0 auto 36px;
  line-height: 1.6;
}
.cta-btn-group { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--primary);
  color: #fff;
  font-weight: 700;
  font-size: .95rem;
  padding: 13px 28px;
  border-radius: var(--button-radius);
  transition: background .2s;
}
.btn-primary:hover { background: var(--primary-hover); }
.btn-outline {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: transparent;
  color: var(--primary);
  font-weight: 700;
  font-size: .95rem;
  padding: 13px 28px;
  border-radius: var(--button-radius);
  border: 1px solid rgba(24,179,255,.4);
  transition: background .2s, border-color .2s;
}
.btn-outline:hover { background: rgba(24,179,255,.08); border-color: var(--primary); }

/* ── Responsive ───────────────────────────────────────── */
@media (max-width: 768px) {
  .cert-hero { padding: 56px 20px 48px; }
  .cert-section { padding: 56px 20px; }
  .hero-stats { gap: 28px; }
  .process-step:not(:last-child)::after { display: none; }
  .tier-info-grid { grid-template-columns: 1fr; }
  .cta-section { padding: 56px 20px; }
}
PAGECSS;
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- ─────────────────────────────────────────────────────────────
     HERO
──────────────────────────────────────────────────────────────── -->
<section class="cert-hero">
  <div class="cert-hero-badge">
    <iconify-icon icon="lucide:award"></iconify-icon>
    Professional Certifications
  </div>
  <h1>Certification Programs</h1>
  <p>Earn industry-recognized credentials that validate your engineering expertise. Our rigorous certification programs are designed by senior engineers and aligned with real-world professional standards.</p>
  <div class="hero-stats">
    <div class="hero-stat-item">
      <span class="hero-stat-value">6</span>
      <span class="hero-stat-label">Certification Tiers</span>
    </div>
    <div class="hero-stat-item">
      <span class="hero-stat-value">6</span>
      <span class="hero-stat-label">Quality Standards</span>
    </div>
    <div class="hero-stat-item">
      <span class="hero-stat-value">12–20</span>
      <span class="hero-stat-label">Week Programs</span>
    </div>
    <div class="hero-stat-item">
      <span class="hero-stat-value">100%</span>
      <span class="hero-stat-label">Industry-Aligned</span>
    </div>
  </div>
</section>

<!-- ─────────────────────────────────────────────────────────────
     CERTIFICATION STANDARDS
──────────────────────────────────────────────────────────────── -->
<section class="cert-section">
  <div class="section-heading">
    <span class="section-badge">What We Evaluate</span>
    <h2 class="section-title">Certification Standards</h2>
    <p class="section-desc">Every certification is assessed against six rigorous professional standards that mirror what top engineering teams require in production environments.</p>
  </div>

  <div class="standards-grid">

    <div class="standard-card">
      <div class="standard-icon"><iconify-icon icon="lucide:code-2"></iconify-icon></div>
      <div class="standard-title">Code Quality Standards</div>
      <ul class="standard-items">
        <li>Clean, readable, and maintainable code</li>
        <li>SOLID principles and design patterns</li>
        <li>Structured peer code review practices</li>
        <li>Test coverage of ≥80% across all modules</li>
      </ul>
    </div>

    <div class="standard-card">
      <div class="standard-icon"><iconify-icon icon="lucide:shield-check"></iconify-icon></div>
      <div class="standard-title">Security Standards</div>
      <ul class="standard-items">
        <li>OWASP Top 10 vulnerability awareness &amp; mitigation</li>
        <li>Robust input validation and sanitization</li>
        <li>Secure authentication and session management</li>
        <li>Data encryption at rest and in transit</li>
      </ul>
    </div>

    <div class="standard-card">
      <div class="standard-icon"><iconify-icon icon="lucide:layers"></iconify-icon></div>
      <div class="standard-title">Architecture Standards</div>
      <ul class="standard-items">
        <li>Scalable and resilient system design</li>
        <li>Microservices and distributed system patterns</li>
        <li>Cloud-native architectural principles</li>
        <li>Domain-driven design and bounded contexts</li>
      </ul>
    </div>

    <div class="standard-card">
      <div class="standard-icon"><iconify-icon icon="lucide:zap"></iconify-icon></div>
      <div class="standard-title">Performance Standards</div>
      <ul class="standard-items">
        <li>Time &amp; space complexity analysis (Big-O)</li>
        <li>Algorithmic and database query optimization</li>
        <li>Caching strategies (Redis, CDN, in-memory)</li>
        <li>Load testing and performance benchmarking</li>
      </ul>
    </div>

    <div class="standard-card">
      <div class="standard-icon"><iconify-icon icon="lucide:git-branch"></iconify-icon></div>
      <div class="standard-title">Collaboration Standards</div>
      <ul class="standard-items">
        <li>Git workflows (feature branching, GitFlow)</li>
        <li>Structured code review and PR processes</li>
        <li>Comprehensive technical documentation</li>
        <li>Agile methodology and sprint practices</li>
      </ul>
    </div>

    <div class="standard-card">
      <div class="standard-icon"><iconify-icon icon="lucide:rocket"></iconify-icon></div>
      <div class="standard-title">Deployment Standards</div>
      <ul class="standard-items">
        <li>CI/CD pipeline design and implementation</li>
        <li>Containerization with Docker and Kubernetes</li>
        <li>Infrastructure as Code (Terraform, Ansible)</li>
        <li>Production monitoring, alerting, and SRE</li>
      </ul>
    </div>

  </div>
</section>

<!-- ─────────────────────────────────────────────────────────────
     CERTIFICATION TIERS
──────────────────────────────────────────────────────────────── -->
<section class="tiers-section">
  <div class="cert-section">
    <div class="section-heading">
      <span class="section-badge">All Programs</span>
      <h2 class="section-title">Certification Tiers</h2>
      <p class="section-desc">Choose the program that matches your experience level and career goals. Each tier builds on the previous one, forming a complete engineering career pathway.</p>
    </div>

    <div class="tiers-grid">

      <!-- ── 1. Foundation ──────────────────────────── -->
      <div class="tier-card">
        <div class="tier-header">
          <div class="tier-icon-wrap"><iconify-icon icon="lucide:award"></iconify-icon></div>
          <div class="tier-meta">
            <span class="tier-level-badge badge-foundation">Foundation</span>
            <p class="tier-name">Certified Software Developer</p>
            <div class="tier-pricing">
              <span class="tier-price">$599</span>
              <span class="tier-price-label">per person &nbsp;·&nbsp; 12 weeks</span>
            </div>
          </div>
        </div>
        <div class="tier-body">
          <p class="tier-desc">Entry-level certification covering fundamental programming concepts, the software development lifecycle, and coding best practices.</p>

          <div class="tier-info-grid">
            <div class="tier-info-item">
              <div class="tier-info-label">Who It's For</div>
              <div class="tier-info-value">0–2 years experience</div>
            </div>
            <div class="tier-info-item">
              <div class="tier-info-label">Prerequisites</div>
              <div class="tier-info-value">Basic programming knowledge</div>
            </div>
          </div>

          <div>
            <div class="tier-section-title">What You'll Learn</div>
            <ul class="tier-list">
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Programming fundamentals and problem-solving</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Software development lifecycle (SDLC)</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Version control with Git and GitHub</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Basic web development (HTML/CSS/JS)</li>
            </ul>
          </div>

          <div>
            <div class="tier-section-title">Skills Assessed</div>
            <div class="skill-tags">
              <span class="skill-tag">Python / JavaScript</span>
              <span class="skill-tag">HTML &amp; CSS</span>
              <span class="skill-tag">Git</span>
              <span class="skill-tag">Basic Algorithms</span>
            </div>
          </div>

          <div class="tier-exam-format">
            <iconify-icon icon="lucide:clipboard-list"></iconify-icon>
            <span><strong>Exam:</strong> 80-question multiple choice + hands-on coding project</span>
          </div>
        </div>
        <div class="tier-footer">
          <a href="/Support/" class="tier-btn">Enroll Now</a>
        </div>
      </div>

      <!-- ── 2. Professional ────────────────────────── -->
      <div class="tier-card featured">
        <div class="tier-header">
          <div class="tier-icon-wrap"><iconify-icon icon="lucide:trophy"></iconify-icon></div>
          <div class="tier-meta">
            <span class="tier-level-badge badge-professional">Professional</span>
            <p class="tier-name">Full-Stack Web Developer</p>
            <div class="tier-pricing">
              <span class="tier-price">$999</span>
              <span class="tier-price-label">per person &nbsp;·&nbsp; 16 weeks</span>
            </div>
          </div>
        </div>
        <div class="tier-body">
          <p class="tier-desc">Advanced certification for building complete web applications with modern frontend and backend technologies, databases, and production deployment.</p>

          <div class="tier-info-grid">
            <div class="tier-info-item">
              <div class="tier-info-label">Who It's For</div>
              <div class="tier-info-value">2–4 years experience</div>
            </div>
            <div class="tier-info-item">
              <div class="tier-info-label">Prerequisites</div>
              <div class="tier-info-value">Foundation cert or equivalent</div>
            </div>
          </div>

          <div>
            <div class="tier-section-title">What You'll Learn</div>
            <ul class="tier-list">
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>React &amp; Next.js frontend development</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Node.js / Express backend APIs</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>SQL &amp; NoSQL databases</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>REST API design, JWT authentication &amp; deployment</li>
            </ul>
          </div>

          <div>
            <div class="tier-section-title">Skills Assessed</div>
            <div class="skill-tags">
              <span class="skill-tag">React</span>
              <span class="skill-tag">Node.js</span>
              <span class="skill-tag">Databases</span>
              <span class="skill-tag">API Design</span>
              <span class="skill-tag">Deployment</span>
            </div>
          </div>

          <div class="tier-exam-format">
            <iconify-icon icon="lucide:clipboard-list"></iconify-icon>
            <span><strong>Exam:</strong> 100-question multiple choice + full-stack capstone project</span>
          </div>
        </div>
        <div class="tier-footer">
          <a href="/Support/" class="tier-btn">Enroll Now</a>
        </div>
      </div>

      <!-- ── 3. Expert ──────────────────────────────── -->
      <div class="tier-card">
        <div class="tier-header">
          <div class="tier-icon-wrap"><iconify-icon icon="lucide:crown"></iconify-icon></div>
          <div class="tier-meta">
            <span class="tier-level-badge badge-expert">Expert</span>
            <p class="tier-name">Cloud Solutions Architect</p>
            <div class="tier-pricing">
              <span class="tier-price">$1,499</span>
              <span class="tier-price-label">per person &nbsp;·&nbsp; 20 weeks</span>
            </div>
          </div>
        </div>
        <div class="tier-body">
          <p class="tier-desc">Expert-level certification for designing and implementing scalable cloud architectures on AWS, Azure, or Google Cloud Platform.</p>

          <div class="tier-info-grid">
            <div class="tier-info-item">
              <div class="tier-info-label">Who It's For</div>
              <div class="tier-info-value">4+ years, architects</div>
            </div>
            <div class="tier-info-item">
              <div class="tier-info-label">Prerequisites</div>
              <div class="tier-info-value">Professional cert or equivalent</div>
            </div>
          </div>

          <div>
            <div class="tier-section-title">What You'll Learn</div>
            <ul class="tier-list">
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>AWS / Azure / GCP architecture patterns</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Serverless &amp; event-driven architecture</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Infrastructure as Code with Terraform</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>High availability &amp; disaster recovery design</li>
            </ul>
          </div>

          <div>
            <div class="tier-section-title">Skills Assessed</div>
            <div class="skill-tags">
              <span class="skill-tag">Cloud Platforms</span>
              <span class="skill-tag">System Design</span>
              <span class="skill-tag">Security Architecture</span>
              <span class="skill-tag">Cost Optimization</span>
            </div>
          </div>

          <div class="tier-exam-format">
            <iconify-icon icon="lucide:clipboard-list"></iconify-icon>
            <span><strong>Exam:</strong> 120-question multiple choice + architecture design review</span>
          </div>
        </div>
        <div class="tier-footer">
          <a href="/Support/" class="tier-btn">Enroll Now</a>
        </div>
      </div>

      <!-- ── 4. Specialist – DevOps ─────────────────── -->
      <div class="tier-card">
        <div class="tier-header">
          <div class="tier-icon-wrap"><iconify-icon icon="lucide:shield"></iconify-icon></div>
          <div class="tier-meta">
            <span class="tier-level-badge badge-specialist">Specialist</span>
            <p class="tier-name">DevOps Engineer</p>
            <div class="tier-pricing">
              <span class="tier-price">$1,199</span>
              <span class="tier-price-label">per person &nbsp;·&nbsp; 14 weeks</span>
            </div>
          </div>
        </div>
        <div class="tier-body">
          <p class="tier-desc">Specialized certification covering CI/CD pipelines, infrastructure as code, containerization, and continuous monitoring for production systems.</p>

          <div class="tier-info-grid">
            <div class="tier-info-item">
              <div class="tier-info-label">Who It's For</div>
              <div class="tier-info-value">Developers / ops with CI/CD experience</div>
            </div>
            <div class="tier-info-item">
              <div class="tier-info-label">Prerequisites</div>
              <div class="tier-info-value">Professional cert or equivalent</div>
            </div>
          </div>

          <div>
            <div class="tier-section-title">What You'll Learn</div>
            <ul class="tier-list">
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Docker &amp; Kubernetes containerization</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>GitHub Actions &amp; CI/CD pipeline design</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Terraform &amp; infrastructure as code</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Monitoring, alerting &amp; SRE practices</li>
            </ul>
          </div>

          <div>
            <div class="tier-section-title">Skills Assessed</div>
            <div class="skill-tags">
              <span class="skill-tag">Containerization</span>
              <span class="skill-tag">CI/CD</span>
              <span class="skill-tag">IaC</span>
              <span class="skill-tag">Observability</span>
            </div>
          </div>

          <div class="tier-exam-format">
            <iconify-icon icon="lucide:clipboard-list"></iconify-icon>
            <span><strong>Exam:</strong> 100-question multiple choice + live pipeline implementation</span>
          </div>
        </div>
        <div class="tier-footer">
          <a href="/Support/" class="tier-btn">Enroll Now</a>
        </div>
      </div>

      <!-- ── 5. Specialist – Security ───────────────── -->
      <div class="tier-card">
        <div class="tier-header">
          <div class="tier-icon-wrap"><iconify-icon icon="lucide:lock"></iconify-icon></div>
          <div class="tier-meta">
            <span class="tier-level-badge badge-specialist">Specialist</span>
            <p class="tier-name">Security Engineer</p>
            <div class="tier-pricing">
              <span class="tier-price">$1,299</span>
              <span class="tier-price-label">per person &nbsp;·&nbsp; 16 weeks</span>
            </div>
          </div>
        </div>
        <div class="tier-body">
          <p class="tier-desc">Comprehensive security certification covering application security, penetration testing, secure coding, and compliance with industry frameworks.</p>

          <div class="tier-info-grid">
            <div class="tier-info-item">
              <div class="tier-info-label">Who It's For</div>
              <div class="tier-info-value">Developers focused on security</div>
            </div>
            <div class="tier-info-item">
              <div class="tier-info-label">Prerequisites</div>
              <div class="tier-info-value">Professional cert or security background</div>
            </div>
          </div>

          <div>
            <div class="tier-section-title">What You'll Learn</div>
            <ul class="tier-list">
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Penetration testing &amp; OWASP Top 10</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Secure coding patterns &amp; code auditing</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Compliance frameworks: SOC 2 &amp; GDPR</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Incident response &amp; threat modeling</li>
            </ul>
          </div>

          <div>
            <div class="tier-section-title">Skills Assessed</div>
            <div class="skill-tags">
              <span class="skill-tag">Vulnerability Assessment</span>
              <span class="skill-tag">Secure Code Review</span>
              <span class="skill-tag">Threat Modeling</span>
              <span class="skill-tag">Compliance</span>
            </div>
          </div>

          <div class="tier-exam-format">
            <iconify-icon icon="lucide:clipboard-list"></iconify-icon>
            <span><strong>Exam:</strong> 100-question multiple choice + security audit project</span>
          </div>
        </div>
        <div class="tier-footer">
          <a href="/Support/" class="tier-btn">Enroll Now</a>
        </div>
      </div>

      <!-- ── 6. Team ─────────────────────────────────── -->
      <div class="tier-card">
        <div class="tier-header">
          <div class="tier-icon-wrap"><iconify-icon icon="lucide:users"></iconify-icon></div>
          <div class="tier-meta">
            <span class="tier-level-badge badge-team">Team</span>
            <p class="tier-name">Team Development Program</p>
            <div class="tier-pricing">
              <span class="tier-price">Custom</span>
              <span class="tier-price-label">pricing &nbsp;·&nbsp; flexible duration</span>
            </div>
          </div>
        </div>
        <div class="tier-body">
          <p class="tier-desc">Customized training program for teams of 5+ members. Fully tailored curriculum, dedicated mentors, group projects, and team-wide certification.</p>

          <div class="tier-info-grid">
            <div class="tier-info-item">
              <div class="tier-info-label">Who It's For</div>
              <div class="tier-info-value">Development teams (5+ members)</div>
            </div>
            <div class="tier-info-item">
              <div class="tier-info-label">Prerequisites</div>
              <div class="tier-info-value">None (custom assessment)</div>
            </div>
          </div>

          <div>
            <div class="tier-section-title">What You'll Learn</div>
            <ul class="tier-list">
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Custom curriculum based on team skill gaps</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Interactive workshops &amp; live mentoring sessions</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Collaborative group capstone projects</li>
              <li><iconify-icon icon="lucide:check-circle-2"></iconify-icon>Team-level assessments &amp; progress reports</li>
            </ul>
          </div>

          <div>
            <div class="tier-section-title">Skills Assessed</div>
            <div class="skill-tags">
              <span class="skill-tag">Custom – org requirements</span>
            </div>
          </div>

          <div class="tier-exam-format">
            <iconify-icon icon="lucide:clipboard-list"></iconify-icon>
            <span><strong>Format:</strong> Workshops, mentoring, group projects &amp; team assessments</span>
          </div>
        </div>
        <div class="tier-footer">
          <a href="/Support/" class="tier-btn outline">Contact Us</a>
        </div>
      </div>

    </div><!-- /tiers-grid -->
  </div>
</section>

<!-- ─────────────────────────────────────────────────────────────
     CERTIFICATION PROCESS
──────────────────────────────────────────────────────────────── -->
<section class="process-section">
  <div class="cert-section">
    <div class="section-heading">
      <span class="section-badge">How It Works</span>
      <h2 class="section-title">Certification Process</h2>
      <p class="section-desc">A clear, structured pathway from enrolment to earning your industry-recognized credential.</p>
    </div>

    <div class="process-steps">
      <div class="process-step">
        <div class="step-number">1</div>
        <div class="step-title">Enroll</div>
        <div class="step-desc">Choose your tier, complete the application, and get paired with your program cohort and mentor.</div>
      </div>
      <div class="process-step">
        <div class="step-number">2</div>
        <div class="step-title">Study</div>
        <div class="step-desc">Work through structured curriculum modules, video lessons, and guided readings at your own pace.</div>
      </div>
      <div class="process-step">
        <div class="step-number">3</div>
        <div class="step-title">Practice</div>
        <div class="step-desc">Build hands-on projects in the CodeFoundry IDE and complete graded coding labs to reinforce each skill.</div>
      </div>
      <div class="process-step">
        <div class="step-number">4</div>
        <div class="step-title">Exam</div>
        <div class="step-desc">Sit a proctored assessment combining a multiple-choice exam and a practical project submission for evaluation.</div>
      </div>
      <div class="process-step">
        <div class="step-number">5</div>
        <div class="step-title">Certification</div>
        <div class="step-desc">Receive your digital certificate and shareable credential badge upon passing — valid for 2 years.</div>
      </div>
    </div>
  </div>
</section>

<!-- ─────────────────────────────────────────────────────────────
     CTA
──────────────────────────────────────────────────────────────── -->
<section>
  <div class="cta-section">
    <h2>Ready to Get Certified?</h2>
    <p>Take the next step in your engineering career. Enroll in a program, or get in touch and our team will help you find the right certification pathway.</p>
    <div class="cta-btn-group">
      <a href="/Support/" class="btn-primary">
        <iconify-icon icon="lucide:send"></iconify-icon>
        Start Enrollment
      </a>
      <a href="/Training/" class="btn-outline">
        <iconify-icon icon="lucide:arrow-left"></iconify-icon>
        Back to Training
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
