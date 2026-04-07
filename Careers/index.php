<?php
$page_title  = 'Careers at CodeFoundry';
$active_page = 'careers';
$page_styles = <<<'PAGECSS'
    .main-content { max-width: 800px; background: var(--navy-3); border-radius: var(--card-radius); border:1px solid var(--border-color); margin: 44px auto 40px auto; padding: 40px 26px; }
    @media (max-width: 700px) { .main-content { margin: 28px 6px 28px 6px; padding: 18px 6px;} }
    .section-heading { text-align: center; margin-bottom: 34px;}
    .section-title { font-size: 2rem; font-weight: 800; margin-bottom: 7px; letter-spacing: -1.2px;}
    .section-desc { color: var(--text-muted);}
    .career-list { margin: 22px 0 0 0; padding: 0; list-style: none;}
    .career-card { background: var(--navy-2); border: 1px solid var(--border-color); border-radius: 9px; margin-bottom: 18px; padding: 23px 18px 18px 18px;}
    .career-role { font-weight: 800; color: var(--primary); font-size: 1.14rem; margin-bottom: 7px;}
    .career-location { color: var(--text-muted); font-size: 0.97rem;}
    .career-desc { margin-top: 6px; color: var(--text-muted);}
    .career-apply { display: inline-block; margin-top: 13px; color: var(--primary); font-weight: 700; text-decoration: underline; font-size: 15px;}
PAGECSS;
$page_scripts = <<<'PAGEJS'
const menuBtn = document.getElementById('mobileMenuBtn');
  const mobileNav = document.getElementById('mobileNav');
  const closeBtn = document.getElementById('closeMobileNav');
  function closeMobileNav() { mobileNav.classList.remove('open'); }
  menuBtn.onclick = () => mobileNav.classList.add('open');
  closeBtn.onclick = closeMobileNav;
  mobileNav.onclick = (e) => {
    if(e.target === mobileNav) closeMobileNav();
  };
PAGEJS;
require_once __DIR__ . '/../includes/header.php';
?>
<main class="main-content">
  <section class="section-heading">
    <h2 class="section-title">Build the Future at CodeFoundry</h2>
    <div class="section-desc">Join a high-impact team shaping the next generation of digital transformation.</div>
  </section>
  <ul class="career-list">
    <li class="career-card">
      <div class="career-role">Senior Software Engineer</div>
      <div class="career-location">Remote (USA preferred)</div>
      <div class="career-desc">Lead the design and delivery of cloud-native apps. Work with Node.js, Python, Azure/AWS, containers, and CI/CD pipelines in a collaborative agile environment.</div>
      <a href="mailto:careers@codefoundry.cloud?subject=Application Senior Software Engineer" class="career-apply">Apply Now</a>
    </li>
    <li class="career-card">
      <div class="career-role">Frontend Developer</div>
      <div class="career-location">New York, NY / Remote</div>
      <div class="career-desc">Build beautiful, responsive UIs using React, TypeScript, and Next.js. Drive UX best practices from design to deployment.</div>
      <a href="mailto:careers@codefoundry.cloud?subject=Application Frontend Developer" class="career-apply">Apply Now</a>
    </li>
    <li class="career-card">
      <div class="career-role">Cloud Architect</div>
      <div class="career-location">Remote (Global)</div>
      <div class="career-desc">Architect and implement scalable microservices and IaC solutions for global clients on AWS, Azure, or GCP. You will mentor teams and advise on best practices.</div>
      <a href="mailto:careers@codefoundry.cloud?subject=Application Cloud Architect" class="career-apply">Apply Now</a>
    </li>
    <li class="career-card">
      <div class="career-role">Project Manager (Agile)</div>
      <div class="career-location">Hybrid (New York HQ / Remote)</div>
      <div class="career-desc">Facilitate agile delivery, manage multiple client projects, ensure milestones, budgets, and communication excellence. Scrum/Agile exp required.</div>
      <a href="mailto:careers@codefoundry.cloud?subject=Application Project Manager" class="career-apply">Apply Now</a>
    </li>
    <li class="career-card">
      <div class="career-role">Intern: Software Engineering</div>
      <div class="career-location">Remote or HQ - Summer 2024</div>
      <div class="career-desc">Work on real-world digital projects and get mentored by senior engineers. CS or related majors, currently enrolled.</div>
      <a href="mailto:careers@codefoundry.cloud?subject=Application Internship" class="career-apply">Apply Now</a>
    </li>
  </ul>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>