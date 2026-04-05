<?php
$page_title  = 'CodeFoundry Case Study: Maritime & Shipping';
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
  <h2 class="section-title">Maritime &amp; Shipping Use Cases</h2>
  <p class="section-desc">
    CodeFoundry modernizes the maritime sector with fleet analytics, IoT tracking, compliance, smart ports and logistics platforms.
  </p>
</section>
<main class="scenarios-main">
  <div class="scenario-card">
    <div class="scenario-title">1. Real-Time Fleet GPS and Vessel Analytics</div>
    <div class="scenario-text">A shipping company tracks vessel location, weather, and operational telemetry worldwide.</div>
    <ul class="scenario-features-list">
      <li>IoT integration for engine/telematics, satellite tracking and automated route planning.</li>
      <li>Fleet dashboard with regulatory, environmental, and fuel usage analytics.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">2. Port Automation and Smart Scheduling</div>
    <div class="scenario-text">Major port automates scheduling for docking, transit, cargo handling and customs/workflow.</div>
    <ul class="scenario-features-list">
      <li>Integrated mobile apps for captains, pilots, and cargo managers.</li>
      <li>AI algorithms optimize load/unload, queue, and berth assignment.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">3. Digital Compliance and Safety Audit Platform</div>
    <div class="scenario-text">Operator unifies logs and compliance workflows for ISM, SOLAS, ballast, crew and emissions.</div>
    <ul class="scenario-features-list">
      <li>Mobile e-log, photo and checklist, route and incident audit dashboard.</li>
      <li>Automated reporting for port authority/local regulators and insurance partners.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">4. End-to-End Container/Shipment Tracking</div>
    <div class="scenario-text">Ocean carrier and global shipper provide real-time container status API, tracking, alerts, and predictive ETA platform.</div>
    <ul class="scenario-features-list">
      <li>Live geofence, tamper and environmental sensor integration for high-value freight.</li>
      <li>Cycle time and SLA reporting for all stakeholders.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">5. Maritime Cybersecurity & Data Privacy Suite</div>
    <div class="scenario-text">A global platform secures vessel, crew, and logistics data from phishing, malware and digital piracy.</div>
    <ul class="scenario-features-list">
      <li>Role-based access control, encrypted communications, and automated threat analytics.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">6. ESG Dashboard for Sustainability & Carbon Reporting</div>
    <div class="scenario-text">Shipping line automates carbon, emissions, and sustainability compliance per IMO/ISCC, for customers and regulators.</div>
    <ul class="scenario-features-list">
      <li>Data ingestion from sensors, logs, and supplier declarations.</li>
      <li>Auto-reporting and stakeholder transparency portal.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">7. Crew Welfare & Operations Mobile Platform</div>
    <div class="scenario-text">Global ocean fleet provides a mobile ecosystem for crew training, health, payroll, scheduling and secure communications at sea.</div>
    <ul class="scenario-features-list">
      <li>Offline/low-bandwidth sync, role-based apps, regulatory document storage and alerts.</li>
    </ul>
  </div>
</main>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>