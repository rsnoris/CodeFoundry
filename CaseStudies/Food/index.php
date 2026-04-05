<?php
$page_title  = 'CodeFoundry Case Study: Food & Beverage';
$active_page = 'case-studies';
$page_styles = <<<'PAGECSS'
:root { --navy: #0e1828; --navy-2: #121c2b; --navy-3: #161f2f; --primary: #18b3ff; --text: #fff; --text-muted: #92a3bb; --border-color: #1a2942; --card-radius: 12px; }
    html, body { background: var(--navy-2); color: var(--text); font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
    body { min-height: 100vh; }
    a { color: inherit; text-decoration: none; }
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
  <h2 class="section-title">Food & Beverage Use Cases</h2>
  <p class="section-desc">
    CodeFoundry transforms food and beverage brands and manufacturers with digital supply chain, smart operations, and engaging consumer platforms.
  </p>
</section>
<main class="scenarios-main">
  <div class="scenario-card">
    <div class="scenario-title">1. Digital Food Traceability & Compliance</div>
    <div class="scenario-text">A packaged goods company implements end-to-end traceability from farm to shelf.</div>
    <ul class="scenario-features-list">
      <li>QR codes, ingredient batch tracking, digital lot and recall management.</li>
      <li>Automated compliance reporting for regulators, partners, and consumers.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">2. Smart Restaurant & Hospitality Operations</div>
    <div class="scenario-text">A chain launches mobile POS, smart inventory/ordering, digital loyalty, and kitchen IoT integration.</div>
    <ul class="scenario-features-list">
      <li>Mobile in-table ordering, QR code menus, kitchen timing and analytics.</li>
      <li>Integrated deals, reservation platform, and push notifications.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">3. Direct-to-Consumer eCommerce Platform</div>
    <div class="scenario-text">Brand launches scalable web/mobile ecommerce for direct food/beverage sales, subscriptions, and digital sampling.</div>
    <ul class="scenario-features-list">
      <li>Personalized offers, recurring billing, loyalty/rewards engine and digital content.</li>
      <li>Self-service returns, support, and omnichannel order tracking.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">4. IoT Cold Chain & Freshness Monitoring</div>
    <div class="scenario-text">Global distributor implements real-time sensor network for temperature, humidity, and spoilage alerts during transport and storage.</div>
    <ul class="scenario-features-list">
      <li>Automated route optimization for freshness and SLA enforcement.</li>
      <li>Customer portal for live shipment status and environment records.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">5. Social Influencer and Recipe Platform</div>
    <div class="scenario-text">CPG launches digital social campaign and recipe content engine, engaging creators and fans with shoppable content.</div>
    <ul class="scenario-features-list">
      <li>User-generated content, promo code campaigns, influencer gamification and analytics dashboard.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">6. Sustainability & ESG for Food and Beverage Brands</div>
    <div class="scenario-text">Brand launches ingredient carbon analytics, packaging sustainability dashboard, and consumer engagement portal.</div>
    <ul class="scenario-features-list">
      <li>Batch/ingredient scoring, supplier ESG audits, and transparency for consumer trust.</li>
    </ul>
  </div>
</main>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>