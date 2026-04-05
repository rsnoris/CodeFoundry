<?php
$page_title  = 'CodeFoundry Case Study: EdTech Startups';
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
  <h2 class="section-title">EdTech Startups Use Cases</h2>
  <p class="section-desc">
    CodeFoundry partners with EdTech innovators to deliver smart platforms, adaptive learning, VR/AR, mobile-first education, and digital credentialing at startup scale.
  </p>
</section>
<main class="scenarios-main">
  <div class="scenario-card">
    <div class="scenario-title">1. Adaptive Learning SaaS Platform</div>
    <div class="scenario-text">A seed-stage EdTech startup built a platform with adaptive lessons, quizzes, and analytics to tailor education for every learner.</div>
    <ul class="scenario-features-list">
      <li>Cloud-native, scalable architecture; plug-and-play content APIs.</li>
      <li>Machine learning for skill gap analysis and personalized paths.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">2. Social Learning & Community Gamification</div>
    <div class="scenario-text">Startup deployed community features for peer learning, Q&A, leaderboard, and digital achievements.</div>
    <ul class="scenario-features-list">
      <li>Custom gamification engine and mobile push notifications.</li>
      <li>Teacher/mentor admin tools, moderation and reporting dashboards.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">3. Mobile Exam Prep and Credentialing Apps</div>
    <div class="scenario-text">A mobile-first EdTech targets professional exams and skill micro-credentialing with rich content, practice tests and analytics.</div>
    <ul class="scenario-features-list">
      <li>Offline support, microtransactions, instant scoring, certification PDFs.</li>
      <li>Automated exam analytics for educators and institutions.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">4. AR/VR STEM and Simulation Platforms</div>
    <div class="scenario-text">Series A EdTech uses AR/VR platform for science, technical, and medical training at scale.</div>
    <ul class="scenario-features-list">
      <li>Cross-device content authoring, simulation, and remote collaboration.</li>
      <li>Role-based analytics for teachers, parents, and administrators.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">5. Digital Credentialing and Portfolio Service</div>
    <div class="scenario-text">Startup provides digital wallets for e-certificates, badges, skills, and work samples, verifiable on blockchain and shared securely to employers.</div>
    <ul class="scenario-features-list">
      <li>API integration for schools, skills, and hiring platforms.</li>
      <li>Privacy and sharing controls, reporting for students and institutions.</li>
    </ul>
  </div>
  <div class="scenario-card">
    <div class="scenario-title">6. School Management & Collaboration Suite</div>
    <div class="scenario-text">A young EdTech built a SaaS platform for class management, communication, assignment workflow and progress analytics for K12 and private schools.</div>
    <ul class="scenario-features-list">
      <li>Parent/school/teacher dashboards, automation for attendance & reminders, secure document sharing.</li>
      <li>Mobile and web interface for admin, teachers, families and students.</li>
    </ul>
  </div>
</main>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>