<?php
$page_title  = 'CodeFoundry Case Study: Legal, Compliance & Justice';
$active_page = 'case-studies';
$page_styles = <<<'PAGECSS'
:root { --navy: #0e1828; --navy-2: #121c2b; --navy-3: #161f2f; --primary: #18b3ff; --text: #fff; --text-muted: #92a3bb; --border-color: #1a2942; --card-radius: 12px; }
    body { background: var(--navy-2); color: var(--text); font-family: 'Inter', sans-serif; margin: 0;}
    .nav, .footer-row { max-width: 1200px; margin:0 auto; }
    .nav { display: flex; align-items: center; justify-content: space-between; padding: 0 40px; min-height: 68px; }
    .brand { display: flex; align-items: center; font-weight: 800; font-size: 22px; gap: 12px;}
    .brand svg { width: 28px; height: 28px; background: var(--primary); border-radius: 6px; color: #092340; padding: 4px; margin-right: 4px;}
    .nav-menu { display: flex; gap: 28px; }
    .nav-link { color: var(--text-muted); text-decoration: none; font-weight: 500;}
    .nav-link.active, .nav-link:hover { color: var(--text);}
    .nav-actions { display: flex; gap: 16px;}
    .nav-btn { padding: 10px 18px; border-radius: 8px; border: 0; font-weight: 700; font-size: 15px;}
    .nav-btn.primary { background: var(--primary); color: var(--navy-2);}
    .nav-btn.secondary { background: #fff; color: var(--navy);}
    .section-heading { max-width: 1200px; margin:70px auto 35px auto; text-align:center;}
    .section-badge { background: var(--navy-3); color: var(--primary); padding:7px 22px; border-radius:18px; display:inline-block; margin-bottom:18px;}
    .section-title { font-size:2.1rem; font-weight:800; letter-spacing:-1.5px; color: var(--text);}
    .section-desc { color: var(--text-muted);}
    .scenarios-main {max-width:1200px;margin:0 auto 40px auto;padding:0 18px;}
    .scenario-card { background: var(--navy-3); border-radius: var(--card-radius); border:1px solid var(--border-color); margin-bottom:32px; padding:32px 28px 22px 28px;}
    .scenario-title { color: var(--primary); font-weight:800; font-size:1.18rem; margin-bottom:11px;}
    .scenario-text { color: var(--text-muted); font-size:15px; font-weight:500; margin-bottom:8px; }
    .scenario-features-list { color: var(--primary); margin-top:8px; margin-bottom:0; padding-left:21px;}
    .back-link { display:inline-block; color:var(--primary); font-size:15px; margin:18px 0 18px 8px; text-decoration:underline; font-weight: 700;}
    .footer-section { background: var(--navy); color: var(--text-muted); padding:70px 0 22px 0; margin-top:48px;}
    .footer-row { display: flex; gap:44px; flex-wrap:wrap; border-bottom: 1px solid #1a2942; padding-bottom:38px;}
    .footer-brand { flex:1 1 260px; display:flex; flex-direction:column; gap:10px;}
    .footer-logo {display:flex;align-items:center;gap:10px;font-weight:800;font-size:21px;color:#fff;}
    .footer-logo svg{width:28px;height:28px;background:var(--primary);border-radius:6px;color:#011c2f;padding:4px;}
    .footer-link-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:7px;}
    .footer-col{flex:1 1 140px;}
    .footer-col-title{font-weight:700;color:#fff;font-size:15.5px;margin-bottom:11px;}
    .footer-link{color:var(--text-muted);font-size:15px;text-decoration:none;font-weight:500;}
    .footer-link:hover{color:var(--primary);}
    .footer-social{display:flex;gap:14px;margin-top:6px;}
    .footer-social a{color:var(--primary);background:var(--navy-3);border-radius:5px;padding:6px 9px;display:flex;align-items:center;}
    .footer-social a:hover{background:var(--primary);color:var(--navy);}
    .footer-legal{max-width:1200px;margin:0 auto;font-size:13.2px;color:#6e7b97;padding-top:23px;display:flex;gap:19px;flex-wrap:wrap;justify-content:space-between;}
PAGECSS;
$page_scripts = '';
require_once __DIR__ . '/../../includes/header.php';
?>
<a href="/CaseStudies/" class="back-link"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Case Studies</a>
<section class="section-heading">
  <span class="section-badge">CASE STUDY</span>
  <h2 class="section-title">Legal, Compliance & Justice Use Cases</h2>
  <p class="section-desc">
    CodeFoundry transforms legal, regulatory, and justice organizations—case management, compliance analytics, and AI document automation.
  </p>
</section>
<main class="scenarios-main">
  <div class="scenario-card">
    <div class="scenario-title">1. Digital Case Management for Law Firms</div>
    <div class="scenario-text">A multisite legal group deploys cloud-native case management and secure doc automation.</div>
    <ul class="scenario-features-list">
      <li>Role-based dashboards, secure file storage, multi-level doc annotation.</li>
      <li>Automated billing, scheduling, and client updates.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">2. AI Contract Drafting & Review</div>
    <div class="scenario-text">LegalTech startup uses NLP and generative AI for smart contract review and drafting.</div>
    <ul class="scenario-features-list">
      <li>Automated risk flagging, redlining, and regulatory compliance checks.</li>
      <li>Versioning, collaboration, and signature workflows.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">3. Court & Judicial Workflow Automation</div>
    <div class="scenario-text">District courts and agencies digitalize filings, scheduling, attorney & case interaction.</div>
    <ul class="scenario-features-list">
      <li>Guided form submission, real-time notification, and evidence management.</li>
      <li>Open data/statistics and analytics for time, risk, case backlog.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">4. Regulatory Compliance Monitoring & Analytics</div>
    <div class="scenario-text">Financial or pharma group uses dashboards and ML to track regulatory compliance (AML, GDPR, HIPAA, Sarbanes-Oxley, etc).</div>
    <ul class="scenario-features-list">
      <li>Automatic alerts, evidence logs, and drill-down by region, team, and product.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">5. Client Portal and Secure Messaging Suite</div>
    <div class="scenario-text">Legal firm and enterprise compliance group deploys self-service client dashboards and fully encrypted communications.</div>
    <ul class="scenario-features-list">
      <li>Status tracking, document request/approval, secure chat, and appointment management.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">6. Legal Research & Predictive Analytics</div>
    <div class="scenario-text">Law schools and private practice use AI to speed up case law, precedent, and risk analytics, with search and citation recommendations.</div>
    <ul class="scenario-features-list">
      <li>Document ingestion, citation extraction, and semantic similarity search.</li>
    </ul>
  </div>
</main>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>