<?php
/**
 * CodeFoundry – Support Page with contact form.
 */
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/AuditStore.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_cf_user      = $_SESSION['cf_user'] ?? null;
$ticket_errors = [];
$ticket_sent   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['support_submit'])) {
    // CSRF
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $ticket_errors[] = 'Invalid request. Please try again.';
    } else {
        $t_name    = trim($_POST['name']    ?? '');
        $t_email   = trim($_POST['email']   ?? '');
        $t_subject = trim($_POST['subject'] ?? '');
        $t_message = trim($_POST['message'] ?? '');

        if ($t_name    === '') { $ticket_errors[] = 'Name is required.'; }
        if ($t_email   === '' || !filter_var($t_email, FILTER_VALIDATE_EMAIL)) {
            $ticket_errors[] = 'A valid email address is required.';
        }
        if ($t_subject === '') { $ticket_errors[] = 'Subject is required.'; }
        if (mb_strlen($t_message) < 10) { $ticket_errors[] = 'Message must be at least 10 characters.'; }

        if (empty($ticket_errors)) {
            $username = $_cf_user['username'] ?? '';
            AuditStore::createSupportTicket($username, $t_name, $t_email, $t_subject, $t_message);
            $ticket_sent = true;
        }
    }
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$page_title  = 'Support - CodeFoundry';
$active_page = '';
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
      --button-outline: #ffffff22;
      --button-radius: 8px;
      --maxwidth: 1200px;
      --card-radius: 12px;
      --header-height: 68px;
      --mobile-menu-bg: #0e1828f9;
    }
    html, body {
      background: var(--navy-2);
      color: var(--text);
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
    }
    body { min-height: 100vh; }
    a { color: inherit; text-decoration: none; }

    header {
      background: var(--navy);
      color: var(--text);
      padding: 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid #192746;
    }
    .nav {
      max-width: var(--maxwidth);
      margin: 0 auto;
      padding: 0 40px;
      min-height: var(--header-height);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .brand {
      display: flex;
      align-items: center;
      font-weight: 800;
      font-size: 22px;
      gap: 12px;
      letter-spacing: -0.5px;
    }
    .brand svg {
      width: 28px;
      height: 28px;
      background: var(--primary);
      border-radius: 6px;
      color: #092340;
      padding: 4px;
      margin-right: 4px;
      box-sizing: border-box;
    }
    .nav-menu {
      display: flex;
      gap: 28px;
      align-items: center;
    }
    .nav-link {
      color: var(--text-muted);
      text-decoration: none;
      font-weight: 500;
      font-size: 15px;
      transition: color .2s;
    }
    .nav-link:hover,
    .nav-link.active {
      color: var(--text);
    }
    .nav-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .nav-btn {
      font-family: inherit;
      font-size: 15px;
      font-weight: 700;
      border: 0;
      border-radius: var(--button-radius);
      padding: 10px 18px;
      background: var(--navy-3);
      color: var(--text);
      outline: 0;
      cursor: pointer;
      transition: background .2s, color .2s;
    }
    .nav-btn.primary {
      background: var(--primary);
      color: var(--navy-2);
      font-weight: 700;
    }
    .nav-btn.secondary {
      background: #fff;
      color: var(--navy);
    }
    .mobile-hamburger {
      display: none;
      background: none;
      border: none;
      color: var(--text);
      font-size: 29px;
      padding: 5px 10px;
      margin-left: 10px;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .nav-menu,
      .nav-actions {
        display: none;
      }
      .mobile-hamburger {
        display: block;
      }
      .nav {
        padding: 0 20px;
      }
    }

    .mobile-nav-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      z-index: 2000;
      display: none;
      backdrop-filter: blur(4px);
    }
    .mobile-nav-overlay.open {
      display: flex;
      justify-content: flex-end;
    }
    .mobile-nav-panel {
      width: 80%;
      max-width: 340px;
      background: var(--mobile-menu-bg);
      height: 100%;
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 28px;
      backdrop-filter: blur(20px);
      border-left: 1px solid var(--border-color);
    }
    .mobile-menu-close {
      align-self: flex-end;
      background: none;
      border: none;
      color: var(--text);
      font-size: 28px;
      cursor: pointer;
      padding: 0;
      line-height: 1;
    }
    .mobile-menu-links {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .mobile-menu-links .nav-link {
      font-size: 18px;
      font-weight: 600;
    }
    .mobile-menu-actions {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: auto;
    }
    .mobile-menu-actions .nav-btn {
      width: 100%;
      padding: 14px;
      font-size: 16px;
    }

    main {
      max-width: var(--maxwidth);
      margin: 0 auto;
      padding: 60px 40px;
    }
    @media (max-width: 768px) {
      main {
        padding: 40px 20px;
      }
    }

    .page-header {
      text-align: center;
      margin-bottom: 60px;
    }
    .page-badge {
      display: inline-block;
      background: rgba(24, 179, 255, 0.15);
      color: var(--primary);
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 16px;
    }
    .page-title {
      font-size: 3rem;
      font-weight: 900;
      margin: 0 0 16px 0;
      letter-spacing: -2px;
      line-height: 1.1;
    }
    .page-desc {
      font-size: 1.2rem;
      color: var(--text-muted);
      max-width: 700px;
      margin: 0 auto;
    }
    @media (max-width: 768px) {
      .page-title {
        font-size: 2rem;
      }
      .page-desc {
        font-size: 1rem;
      }
    }

    .support-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 24px;
      margin-top: 40px;
    }
    @media (max-width: 768px) {
      .support-grid {
        grid-template-columns: 1fr;
      }
    }

    .support-card {
      background: var(--navy-3);
      border: 1px solid var(--border-color);
      border-radius: var(--card-radius);
      padding: 32px;
      transition: transform 0.2s, border-color 0.2s;
    }
    .support-card:hover {
      transform: translateY(-4px);
      border-color: var(--primary);
    }
    .support-icon {
      font-size: 48px;
      color: var(--primary);
      margin-bottom: 20px;
    }
    .support-title {
      font-size: 1.5rem;
      font-weight: 800;
      margin: 0 0 12px 0;
      letter-spacing: -0.5px;
    }
    .support-desc {
      color: var(--text-muted);
      line-height: 1.6;
      margin-bottom: 20px;
    }
    .support-link {
      color: var(--primary);
      font-weight: 700;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .support-link:hover {
      color: var(--primary-hover);
    }
    .contact-info {
      background: var(--navy-3);
      border: 1px solid var(--border-color);
      border-radius: var(--card-radius);
      padding: 40px;
      margin-top: 60px;
      text-align: center;
    }
    .contact-info h2 {
      font-size: 2rem;
      font-weight: 800;
      margin: 0 0 20px 0;
      letter-spacing: -1px;
    }
    .contact-info p {
      color: var(--text-muted);
      margin-bottom: 24px;
    }
    .contact-methods {
      display: flex;
      justify-content: center;
      gap: 32px;
      flex-wrap: wrap;
      margin-top: 32px;
    }
    .contact-method {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
    }
    .contact-method-icon {
      font-size: 32px;
      color: var(--primary);
    }
    .contact-method-label {
      font-size: 14px;
      color: var(--text-subtle);
      font-weight: 600;
    }
    .contact-method-value {
      color: var(--primary);
      font-weight: 700;
    }

    .footer-section {
      background: var(--navy);
      color: var(--text-muted);
      padding: 70px 0 22px 0;
      margin-top: 80px;
    }
    .footer-row {
      max-width: var(--maxwidth);
      margin: 0 auto;
      padding: 0 40px;
      display: flex;
      gap: 44px;
      flex-wrap: wrap;
      border-bottom: 1px solid #1a2942;
      padding-bottom: 38px;
    }
    @media (max-width: 768px) {
      .footer-row {
        padding: 0 20px 38px 20px;
      }
    }
    .footer-brand {
      flex: 1 1 260px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .footer-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 800;
      font-size: 21px;
      color: #fff;
    }
    .footer-logo svg {
      width: 28px;
      height: 28px;
      background: var(--primary);
      border-radius: 6px;
      color: #011c2f;
      padding: 4px;
    }
    .footer-link-list {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 7px;
    }
    .footer-col {
      flex: 1 1 140px;
    }
    .footer-col-title {
      font-weight: 700;
      color: #fff;
      font-size: 15.5px;
      margin-bottom: 11px;
    }
    .footer-link {
      color: var(--text-muted);
      font-size: 15px;
      text-decoration: none;
      font-weight: 500;
    }
    .footer-link:hover {
      color: var(--primary);
    }
    .footer-social {
      display: flex;
      gap: 14px;
      margin-top: 6px;
    }
    .footer-social a {
      color: var(--primary);
      background: var(--navy-3);
      border-radius: 5px;
      padding: 6px 9px;
      display: flex;
      align-items: center;
    }
    .footer-social a:hover {
      background: var(--primary);
      color: var(--navy);
    }
    .footer-legal {
      max-width: 1200px;
      margin: 0 auto;
      padding: 23px 40px 0 40px;
      font-size: 13.2px;
      color: #6e7b97;
      display: flex;
      gap: 19px;
      flex-wrap: wrap;
      justify-content: space-between;
    }
    @media (max-width: 768px) {
      .footer-legal {
        padding: 23px 20px 0 20px;
      }
    }
    .contact-form-section {
      max-width: 640px;
      margin: 48px auto 0;
      padding: 0 20px 60px;
    }
    .contact-form-section h2 {
      font-size: 22px;
      font-weight: 800;
      margin: 0 0 8px;
      color: var(--text);
    }
    .contact-form-section > p {
      color: var(--text-muted);
      font-size: 14px;
      margin: 0 0 24px;
    }
    .ticket-success {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(24,179,255,.1);
      border: 1px solid rgba(24,179,255,.25);
      border-radius: 10px;
      padding: 16px 20px;
      color: var(--primary);
      font-size: 14px;
      font-weight: 600;
    }
    .ticket-errors {
      background: rgba(239,68,68,.1);
      border: 1px solid rgba(239,68,68,.3);
      border-radius: 10px;
      padding: 14px 18px;
      color: #f87171;
      font-size: 13px;
      margin-bottom: 20px;
    }
    .ticket-errors div + div { margin-top: 4px; }
    .contact-form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .cf-field {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .cf-field label {
      font-size: 13px;
      font-weight: 600;
      color: var(--text-muted);
    }
    .cf-field input,
    .cf-field textarea {
      background: var(--navy);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 10px 14px;
      color: var(--text);
      font-size: 14px;
      font-family: inherit;
      outline: none;
      transition: border-color .2s;
      resize: vertical;
    }
    .cf-field input:focus,
    .cf-field textarea:focus {
      border-color: var(--primary);
    }
    .cf-btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 11px 24px;
      background: var(--primary);
      color: var(--navy);
      font-weight: 700;
      font-size: 14px;
      border-radius: var(--button-radius);
      border: none;
      cursor: pointer;
      transition: background .2s;
      text-decoration: none;
      align-self: flex-start;
    }
    .cf-btn-primary:hover { background: var(--primary-hover); }
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
<div class="mobile-nav-overlay" id="mobileNav">
  <div class="mobile-nav-panel">
    <button class="mobile-menu-close" id="closeMobileNav" aria-label="Close menu">
      <iconify-icon icon="lucide:x"></iconify-icon>
    </button>
    <div class="mobile-menu-links">
      <a href="/#services" class="nav-link" onclick="closeMobileNav()">Services</a>
      <a href="/#solutions" class="nav-link" onclick="closeMobileNav()">Solutions</a>
      <a href="/#industries" class="nav-link" onclick="closeMobileNav()">Industries</a>
      <a href="/CaseStudies/" class="nav-link" onclick="closeMobileNav()">Case Studies</a>
      <a href="/AboutUs/" class="nav-link" onclick="closeMobileNav()">About</a>
    </div>
    <div class="mobile-menu-actions">
      <a href="/#services" class="nav-btn primary">Get Started</a>
    </div>
  </div>
</div>

<main>
  <div class="page-header">
    <span class="page-badge">We're Here to Help</span>
    <h1 class="page-title">Support Center</h1>
    <p class="page-desc">
      Get the help you need with our comprehensive support resources and dedicated assistance team.
    </p>
  </div>

  <div class="support-grid">
    <div class="support-card">
      <div class="support-icon">
        <iconify-icon icon="lucide:message-circle"></iconify-icon>
      </div>
      <h3 class="support-title">Live Chat</h3>
      <p class="support-desc">
        Connect with our support team instantly through live chat for immediate assistance with your questions.
      </p>
      <a href="#" class="support-link">
        Start Chat
        <iconify-icon icon="lucide:arrow-right"></iconify-icon>
      </a>
    </div>

    <div class="support-card">
      <div class="support-icon">
        <iconify-icon icon="lucide:mail"></iconify-icon>
      </div>
      <h3 class="support-title">Email Support</h3>
      <p class="support-desc">
        Send us a detailed message and receive a comprehensive response within 24 hours.
      </p>
      <a href="mailto:support@codefoundry.cloud" class="support-link">
        Send Email
        <iconify-icon icon="lucide:arrow-right"></iconify-icon>
      </a>
    </div>

    <div class="support-card">
      <div class="support-icon">
        <iconify-icon icon="lucide:phone"></iconify-icon>
      </div>
      <h3 class="support-title">Phone Support</h3>
      <p class="support-desc">
        Speak directly with a support specialist for urgent issues or complex questions.
      </p>
      <a href="tel:+1234567890" class="support-link">
        Call Now
        <iconify-icon icon="lucide:arrow-right"></iconify-icon>
      </a>
    </div>

    <div class="support-card">
      <div class="support-icon">
        <iconify-icon icon="lucide:book-open"></iconify-icon>
      </div>
      <h3 class="support-title">Knowledge Base</h3>
      <p class="support-desc">
        Browse our extensive library of articles, guides, and FAQs to find answers to common questions.
      </p>
      <a href="/Documentation/" class="support-link">
        Browse Articles
        <iconify-icon icon="lucide:arrow-right"></iconify-icon>
      </a>
    </div>

    <div class="support-card">
      <div class="support-icon">
        <iconify-icon icon="lucide:users"></iconify-icon>
      </div>
      <h3 class="support-title">Community Forum</h3>
      <p class="support-desc">
        Join our community to discuss best practices and connect with other CodeFoundry users.
      </p>
      <a href="#" class="support-link">
        Visit Forum
        <iconify-icon icon="lucide:arrow-right"></iconify-icon>
      </a>
    </div>

    <div class="support-card">
      <div class="support-icon">
        <iconify-icon icon="lucide:video"></iconify-icon>
      </div>
      <h3 class="support-title">Video Tutorials</h3>
      <p class="support-desc">
        Watch step-by-step video tutorials covering various features and common workflows.
      </p>
      <a href="#" class="support-link">
        Watch Videos
        <iconify-icon icon="lucide:arrow-right"></iconify-icon>
      </a>
    </div>
  </div>

  <div class="contact-info">
    <h2>Need Direct Assistance?</h2>
    <p>Our support team is available 24/7 to help you with any questions or concerns.</p>
    
    <div class="contact-methods">
      <div class="contact-method">
        <div class="contact-method-icon">
          <iconify-icon icon="lucide:mail"></iconify-icon>
        </div>
        <div class="contact-method-label">Email</div>
        <a href="mailto:support@codefoundry.cloud" class="contact-method-value">support@codefoundry.cloud</a>
      </div>
      
      <div class="contact-method">
        <div class="contact-method-icon">
          <iconify-icon icon="lucide:phone"></iconify-icon>
        </div>
        <div class="contact-method-label">Phone</div>
        <a href="tel:+1234567890" class="contact-method-value">+1 234 567 890</a>
      </div>
      
      <div class="contact-method">
        <div class="contact-method-icon">
          <iconify-icon icon="lucide:clock"></iconify-icon>
        </div>
        <div class="contact-method-label">Hours</div>
        <div class="contact-method-value">24/7 Support</div>
      </div>
    </div>
  </div>

  <!-- Contact Form -->
  <div class="contact-form-section">
    <h2>Submit a Support Request</h2>
    <p>Fill out the form below and our team will get back to you as soon as possible.</p>

    <?php if ($ticket_sent): ?>
      <div class="ticket-success">
        <iconify-icon icon="lucide:check-circle-2"></iconify-icon>
        Your support request has been submitted. We'll be in touch shortly.
      </div>
    <?php else: ?>
      <?php if (!empty($ticket_errors)): ?>
        <div class="ticket-errors">
          <?php foreach ($ticket_errors as $e): ?>
            <div><?= cf_e($e) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <form class="contact-form" method="post" action="/Support/">
        <input type="hidden" name="csrf_token" value="<?= cf_e($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="support_submit" value="1">
        <div class="cf-field">
          <label for="t_name">Name</label>
          <input type="text" id="t_name" name="name" value="<?= cf_e($_cf_user['display'] ?? '') ?>" placeholder="Your name" required>
        </div>
        <div class="cf-field">
          <label for="t_email">Email</label>
          <input type="email" id="t_email" name="email" placeholder="you@example.com" required>
        </div>
        <div class="cf-field">
          <label for="t_subject">Subject</label>
          <input type="text" id="t_subject" name="subject" placeholder="Brief summary of your issue" required>
        </div>
        <div class="cf-field">
          <label for="t_message">Message</label>
          <textarea id="t_message" name="message" rows="5" placeholder="Describe your issue in detail…" required></textarea>
        </div>
        <button type="submit" class="cf-btn-primary">
          <iconify-icon icon="lucide:send"></iconify-icon> Send Request
        </button>
      </form>
    <?php endif; ?>
  </div>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>