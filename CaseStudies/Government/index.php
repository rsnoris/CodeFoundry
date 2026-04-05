<?php
$page_title  = 'CodeFoundry Case Study: Government & Public Sector';
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
  <h2 class="section-title">Government & Public Sector Use Cases</h2>
  <p class="section-desc">
    CodeFoundry partners with government agencies for citizen-focused digital services, operational efficiency, and secure, compliant solutions.
  </p>
</section>
<main class="scenarios-main">
  <div class="scenario-card">
    <div class="scenario-title">1. e-Government Citizen Self-Service Portal</div>
    <div class="scenario-text">A state government built a unified portal to offer 40+ citizen and business digital services in one place.</div>
    <ul class="scenario-features-list">
      <li>Single sign-on for driver’s licenses, tax filings, permits, benefits, and payments.</li>
      <li>Mobile-first UX, accessibility (ADA/WCAG), and language support.</li>
      <li>Automated notifications for deadlines, renewals, and status changes.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">2. Smart City IoT and Public Safety Analytics</div>
    <div class="scenario-text">A municipality modernized core infrastructure and public safety with real-time IoT and data.</div>
    <ul class="scenario-features-list">
      <li>Connected traffic, lighting, parking, and public transit sensors on a cloud platform.</li>
      <li>Central dashboard for incident, traffic, and emergency response analytics.</li>
      <li>Increased response time, reduced congestion/crime rates.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">3. Secure Digital Voting Platform</div>
    <div class="scenario-text">A government commission developed a secure, accessible digital voting system for absentee and overseas voters.</div>
    <ul class="scenario-features-list">
      <li>Multi-factor ID verification, blockchain audit trail, and real-time anomaly monitoring.</li>
      <li>Audit-ready logs, privacy controls, and compliance with all federal/state voting laws.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">4. Digital Case Management for Social Services</div>
    <div class="scenario-text">A human services agency transformed case management for foster care and public assistance recipients.</div>
    <ul class="scenario-features-list">
      <li>Unified case files, automated intake eligibility, and mobile app for field agents.</li>
      <li>Analytics-driven outcomes, flagging high-risk situations early.</li>
      <li>Reduced paperwork, improved program outcomes.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">5. Regulatory e-Filing and Workflow Automation</div>
    <div class="scenario-text">A regulator digitized environmental, health, and business filings from 1000s of companies and citizens.</div>
    <ul class="scenario-features-list">
      <li>User portal with guided form submission and integrated e-signature.</li>
      <li>Workflow automation for review/approval, routing, and compliance notifications.</li>
      <li>Expedited approval cycle by 58%, improved compliance tracking.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">6. Open Data Platform & API Initiative</div>
    <div class="scenario-text">A city launched an open data hub/API so developers, researchers, and citizens can access real-time city datasets.</div>
    <ul class="scenario-features-list">
      <li>Secure, anonymized datasets; API gateway with usage and throttling controls.</li>
      <li>Community dashboard for visualization and city project collaboration.</li>
    </ul>
  </div>
</main>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>