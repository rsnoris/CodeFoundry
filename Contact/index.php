<?php
$page_title  = 'Contact CodeFoundry';
$active_page = 'contact';
$page_styles = <<<'PAGECSS'
    :root {
      --navy: #0e1828; --navy-2: #121c2b; --navy-3: #161f2f;
      --primary: #18b3ff; --primary-hover: #009de0;
      --text: #fff; --text-muted: #92a3bb; --text-subtle: #627193;
      --border-color: #1a2942; --button-outline: #ffffff22;
      --button-radius: 8px; --maxwidth: 1200px; --card-radius: 12px;
      --header-height: 68px; --mobile-menu-bg: #0e1828f9;
    }
    html, body { background: var(--navy-2); color: var(--text); font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
    body { min-height: 100vh; }
    a { color: inherit; text-decoration: none; }
    .main-content { max-width: 600px; background: var(--navy-3); border-radius: var(--card-radius); border:1px solid var(--border-color); margin: 44px auto 40px auto; padding: 40px 26px; }
    @media (max-width: 700px) { .main-content { margin: 28px 6px 28px 6px; padding: 18px 6px;} }
    .section-heading { text-align: center; margin-bottom: 34px;}
    .section-title { font-size: 2rem; font-weight: 800; margin-bottom: 7px; letter-spacing: -1.2px;}
    .section-desc { color: var(--text-muted);}
    .contact-details { margin: 29px auto 29px auto; text-align: left; color: var(--text-muted);}
    .contact-form label { display: block; margin-top: 14px; font-weight: 600; color: var(--primary);}
    .contact-form input, .contact-form textarea { width: 100%; padding: 9px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 1rem; background: var(--navy-2); color: var(--text); margin-top: 7px;}
    .contact-form textarea { min-height: 95px; resize: vertical;}
    .contact-form button { margin-top: 19px; background: var(--primary); color: var(--navy-2); font-weight: 800; border: none; border-radius: 8px; padding: 12px 24px; font-size: 1.1rem; cursor: pointer;}
    .contact-form button:hover { background: var(--primary-hover);}
    .form-message { margin-top: 16px; padding: 12px 16px; border-radius: 8px; font-weight: 600; text-align: center; }
    .form-message.success { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid #10b981; }
    .form-message.error { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid #ef4444; }
PAGECSS;
$page_scripts = <<<'PAGEJS'
// Contact form handling
  const contactForm = document.getElementById('contactForm');
  const formMessage = document.getElementById('formMessage');

  contactForm.addEventListener('submit', function(e) {
    e.preventDefault();

    // Use HTML5 form validation
    if (!contactForm.checkValidity()) {
      showMessage('Please fill in all fields correctly.', 'error');
      return;
    }

    // TODO: Replace with actual API call for production deployment
    showMessage('Thank you for your inquiry! We will get back to you within 24 hours.', 'success');

    // Reset form
    contactForm.reset();
  });

  function showMessage(text, type) {
    formMessage.textContent = text;
    // Validate type parameter and use classList for safer class assignment
    const validType = (type === 'success' || type === 'error') ? type : 'error';
    formMessage.className = 'form-message';
    formMessage.classList.add(validType);
    formMessage.style.display = 'block';

    // Auto-hide success messages after 5 seconds
    if (validType === 'success') {
      setTimeout(function() {
        formMessage.style.display = 'none';
      }, 5000);
    }
  }
PAGEJS;
require_once __DIR__ . '/../includes/header.php';
?>
<main class="main-content">
  <section class="section-heading">
    <h2 class="section-title">Contact CodeFoundry</h2>
    <div class="section-desc">Reach out to start your digital transformation journey, or just to say hello. We'll respond promptly!</div>
  </section>
  <div class="contact-details">
    <strong>Email:</strong> <a href="mailto:hello@codefoundry.cloud" style="color:var(--primary);">hello@codefoundry.cloud</a><br />
    <strong>Phone:</strong> <a href="tel:+1234567890" style="color:var(--primary);">+1 234 567 890</a><br />
    <strong>HQ:</strong> 156 Foundry Ave., Suite 502, New York, NY, USA
  </div>
  <form class="contact-form" id="contactForm" autocomplete="off">
    <label for="name">Full Name</label>
    <input type="text" id="name" name="name" placeholder="Your Name" required />
    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" placeholder="Email" required />
    <label for="msg">How can we help?</label>
    <textarea id="msg" name="message" placeholder="Your message..." required></textarea>
    <button type="submit">Send Inquiry</button>
    <div id="formMessage" class="form-message" style="display: none;"></div>
  </form>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>