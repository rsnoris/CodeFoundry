<?php
$page_title  = 'CodeFoundry Case Study: Consumer Packaged Goods (CPG)';
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
  <h2 class="section-title">Consumer Packaged Goods (CPG) Use Cases</h2>
  <p class="section-desc">
    CodeFoundry powers digital transformation for CPG brands with demand sensing, smart supply, trade promotion, and omnichannel analytics.
  </p>
</section>
<main class="scenarios-main">
  <div class="scenario-card">
    <div class="scenario-title">1. Demand Sensing and Forecasting</div>
    <div class="scenario-text">A global CPG leader deployed AI-driven forecasting for SKU, region, and channel.</div>
    <ul class="scenario-features-list">
      <li>Integrates point-of-sale, online, and weather/social data for high-accuracy prediction.</li>
      <li>Demand spikes, holidays, and promo effects accounted for automatically.</li>
      <li>Reduced waste, lower out-of-stocks, higher service levels.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">2. Smart Supply Chain Platform</div>
    <div class="scenario-text">A CPG company modernized planning and inventory with real-time visibility from raw vendor to shelf.</div>
    <ul class="scenario-features-list">
      <li>IoT sensors and dashboards track location, climate, and dwell at every stage.</li>
      <li>Automated re-order, distribution, and demand-shaping triggers.</li>
      <li>Significantly reduced spoilage, shrink, and expedited shipments.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">3. Promotion Effectiveness Analytics</div>
    <div class="scenario-text">A multi-brand group used dashboards to measure lift, cannibalization, and ROI for campaigns by channel and region.</div>
    <ul class="scenario-features-list">
      <li>Integration with retailer sales, scan, loyalty, and digital ad platforms.</li>
      <li>AI models recommend optimal promo mix and timing.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">4. Omnichannel D2C Consumer Platform</div>
    <div class="scenario-text">A CPG group created direct-to-consumer sales with subscriptions, e-commerce, and engagement across mobile and web.</div>
    <ul class="scenario-features-list">
      <li>Personalized product offers, bundles, loyalty engine, and recurring billing.</li>
      <li>Customer service live chatbots and self-service returns/refunds.</li>
      <li>Expanded margin and direct relationship with key customer segments.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">5. Digital Product Traceability</div>
    <div class="scenario-text">A food and beverage group digitized supply and recalls with product and package serialization/QR code tracking.</div>
    <ul class="scenario-features-list">
      <li>Mobile consumer scan for provenance, sustainability, recall status.</li>
      <li>Automated reporting for internal QA, regulatory compliance, and recalls.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">6. In-Store Experience and IoT Analytics</div>
    <div class="scenario-text">A large CPG partnered with retailers for connected shelf sensors, smart coolers, and in-store traffic analytics.</div>
    <ul class="scenario-features-list">
      <li>Live alerts and planogram compliance validation per location.</li>
      <li>Improved product availability and promotion performance by store region and format.</li>
    </ul>
  </div>
</main>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>