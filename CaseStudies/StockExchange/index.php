<?php
$page_title  = 'CodeFoundry Case Study: Stock Exchange & Capital Markets';
$active_page = 'case-studies';
$page_styles = <<<'PAGECSS'
:root {
      --navy: #0e1828; --navy-2: #121c2b; --navy-3: #161f2f; 
      --primary: #18b3ff; --text: #fff; --text-muted: #92a3bb; 
      --border-color: #1a2942; --card-radius: 12px;
    }
    html, body { background: var(--navy-2); color: var(--text); font-family: 'Inter', sans-serif; margin:0; padding:0;}
    body { min-height: 100vh; }
    a { color: inherit; text-decoration: none;}
    .nav, .footer-row { max-width: 1200px; margin:0 auto;}
    .nav { display: flex; align-items: center; justify-content: space-between; padding: 0 40px; min-height: 68px;}
    .brand { display: flex; align-items: center; font-weight: 800; font-size: 22px; gap: 12px;}
    .brand svg { width: 28px; height: 28px; background: var(--primary); border-radius: 6px; color: #092340; padding: 4px; margin-right: 4px;}
    .nav-menu { display: flex; gap: 28px;}
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
    .scenario-card {
      background: var(--navy-3);
      border-radius: var(--card-radius);
      border:1px solid var(--border-color);
      margin-bottom:32px;
      padding:32px 28px 22px 28px;
      transition: box-shadow 0.18s, border-color 0.18s;
    }
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
    @media (max-width: 900px) {
      .nav, .footer-row, .section-heading, .scenarios-main { padding-left:8px !important; padding-right:8px !important;}
      .nav { flex-direction: column; gap: 8px; }
      .footer-row { flex-direction: column; gap: 26px; }
      .nav-menu { flex-wrap: wrap; gap: 11px;}
    }
    @media (max-width: 600px) {
      .section-heading { font-size: 1.32rem; }
      .section-title { font-size: 1.12rem; }
      .scenario-card { padding:17px 8px 12px 8px;}
      .footer-section { padding: 38px 0 16px 0; }
      .nav, .footer-row { padding-left:2px !important; padding-right:2px !important;}
      .nav-btn { padding: 8px 10px; font-size: 13px;}
    }
PAGECSS;
$page_scripts = '';
require_once __DIR__ . '/../../includes/header.php';
?>
<a href="/CaseStudies/" class="back-link"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Case Studies</a>
<section class="section-heading">
  <span class="section-badge">CASE STUDY</span>
  <h2 class="section-title">Stock Exchange &amp; Capital Markets Use Cases</h2>
  <p class="section-desc">
    CodeFoundry supports exchanges, fintechs and capital markets infrastructure firms with secure trading, regulatory automation, analytics, and mobile platforms.
  </p>
</section>
<main class="scenarios-main">
  <div class="scenario-card">
    <div class="scenario-title">1. Real-Time Trading & Order Management</div>
    <div class="scenario-text">Exchange launches ultra-low latency trading with multi-venue order routing, matching engine, and risk controls.</div>
    <ul class="scenario-features-list">
      <li>FIX API integration, multi-market order routing, real-time risk checks.</li>
      <li>High-availability cloud, capacity scaling, trader dashboards, and incident alerts.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">2. Market Surveillance & Regulatory Reporting</div>
    <div class="scenario-text">Automated surveillance and analytics across all asset classes for insider trading, AML, market abuse, and regulatory flagging.</div>
    <ul class="scenario-features-list">
      <li>AI/ML for anomaly detection; automated filings for SEC, MiFID II, ESMA, CFTC etc.</li>
      <li>Audit trails, real-time alerts, compliance dashboards, full reporting suite.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">3. Investor & Broker Onboarding Portal</div>
    <div class="scenario-text">Digital onboarding and KYC suite for brokers, funds, and direct retail investors.</div>
    <ul class="scenario-features-list">
      <li>Automated identity verification, risk scoring, document vaults, and onboarding workflow.</li>
      <li>Mobile, multilingual, e-signature and API integrations.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">4. Mobile Trading & Portfolio Analytics App</div>
    <div class="scenario-text">A retail broker launches mobile platform for trading, live market data, research, notifications, and dashboard analytics.</div>
    <ul class="scenario-features-list">
      <li>Push alerts, AI-driven recommendations, secure trade execution, and account management.</li>
      <li>API for integrations with external asset tracking apps.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">5. Institutional API Platform & Data Feeds</div>
    <div class="scenario-text">An exchange and fintechs provide API-driven connectivity, smart data feeds, and cloud scaling for institutional trading, clearing, and research.</div>
    <ul class="scenario-features-list">
      <li>REST/FIX/WS APIs, cloud authorization, real-time push/pull analytics delivery.</li>
      <li>Billing/usage dashboard and developer portal.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">6. Risk, Margin, and Portfolio Analytics Suite</div>
    <div class="scenario-text">Trading desk and asset manager leverage cloud dashboard for real-time margin, VaR, stress tests, and portfolio optimization.</div>
    <ul class="scenario-features-list">
      <li>Automated scenario reporting, regulatory rule engines, visualization, and what-if analytics.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">7. Blockchain Clearing & Digital Settlement Service</div>
    <div class="scenario-text">Exchange and DLT fintech deliver digital asset settlement, clearing, and proof of ownership for stocks, bonds, and new digital securities.</div>
    <ul class="scenario-features-list">
      <li>Smart contracts, distributed ledger, compliance attestations, and stakeholder dashboard.</li>
    </ul>
  </div>
</main>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>