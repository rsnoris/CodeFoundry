<?php
$page_title  = 'CodeFoundry Case Study: Pharmaceuticals & Life Sciences';
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
  <h2 class="section-title">Pharmaceuticals & Life Sciences Use Cases</h2>
  <p class="section-desc">
    CodeFoundry delivers transformation for pharmaceutical, biotech, and research organizations: data-driven R&D, regulatory, patient engagement, and supply chain digitalization.
  </p>
</section>
<main class="scenarios-main">
  <div class="scenario-card">
    <div class="scenario-title">1. Digital Clinical Trial Platform</div>
    <div class="scenario-text">A global pharma enterprise launched a full-service clinical trial cloud to accelerate research and enhance compliance.</div>
    <ul class="scenario-features-list">
      <li>Patient recruitment, eConsent, remote visit scheduling, and EDC integration.</li>
      <li>Role-based dashboards for researchers, CROs, and trial monitors.</li>
      <li>Audit-ready data export and regulatory document workflow.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">2. Real-world Evidence (RWE) Analytics</div>
    <div class="scenario-text">A biopharma company built a secure data lake for post-market pharmacovigilance analytics and regulatory reporting.</div>
    <ul class="scenario-features-list">
      <li>Data ingestion from EHR, claims, and registry sources (de-identification/consent applied).</li>
      <li>ML for adverse event pattern discovery and cohort analytics.</li>
      <li>Automated FDA/EMA reporting and publication support.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">3. Digital Chemistry & Compound Library Management</div>
    <div class="scenario-text">A research division needed digital workflows for millions of compounds across discovery, screening, and QA.</div>
    <ul class="scenario-features-list">
      <li>Cloud-native LIMs, structure search, and high-throughput screening workflow.</li>
      <li>Data lake and reporting for patent applications and research teams.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">4. Regulatory Submission Automation</div>
    <div class="scenario-text">A global pharma improved its speed/accuracy in submitting IND, NDA, and eCTD packages to regulators.</div>
    <ul class="scenario-features-list">
      <li>Template-based composer with integrated validation and document control.</li>
      <li>Automated pre-submission checks and role-based workflow for QA/RA teams.</li>
      <li>Regulatory library dashboards for country-by-country rules/tracking.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">5. AI-Assisted Drug Discovery</div>
    <div class="scenario-text">A biotech startup used CodeFoundry to scale up in silico compound screening and candidate selection.</div>
    <ul class="scenario-features-list">
      <li>ML models for target prediction, toxicity, and ADMET profiling.</li>
      <li>Automated virtual screening of large chemical libraries on GPU clusters.</li>
      <li>Shortened discovery phase and reduced pre-clinical attrition rates.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">6. Supply Chain Serialization & Compliance</div>
    <div class="scenario-text">A global pharma manufacturer deployed serialization and track & trace per DSCSA, FMD, etc.</div>
    <ul class="scenario-features-list">
      <li>End-to-end product ID/QR traceability, integration with packaging and distribution.</li>
      <li>Real-time dashboards for recalls, inventory, and anti-counterfeit measures.</li>
      <li>Automated compliance and reporting per region.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">7. Patient Engagement Mobile Suite</div>
    <div class="scenario-text">Large pharma brand delivered a patient app for adherence, scheduling, support, and outcomes reporting.</div>
    <ul class="scenario-features-list">
      <li>Integration with EHRs, pharmacies, push-notifications and telehealth.</li>
      <li>In-app support and feedback workflows.</li>
      <li>Demonstrated improvement in trial participation and therapy continuity.</li>
    </ul>
  </div>
</main>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>