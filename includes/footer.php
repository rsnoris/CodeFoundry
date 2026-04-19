<?php
/**
 * CodeFoundry – Shared Page Footer
 *
 * Optional variable (set by the calling page):
 *   string $page_scripts – Additional JavaScript to inject before </body> (optional)
 *
 * This file outputs the <footer> element, shared site.js script tag,
 * any page-specific scripts, and closes </body></html>.
 */
$page_scripts = $page_scripts ?? '';
$_cf_user = $_cf_user ?? null;
?>
<footer class="footer-section">
  <div class="footer-row">
    <div class="footer-brand">
      <div class="footer-logo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 16l4-4l-4-4M6 8l-4 4l4 4m8.5-12l-5 16"/></svg>
        CodeFoundry
      </div>
      <div>Empowering businesses with next-generation technology solutions and strategic leadership.</div>
      <div class="footer-social">
        <a href="#" aria-label="LinkedIn"><iconify-icon icon="lucide:linkedin"></iconify-icon></a>
        <a href="#" aria-label="Twitter"><iconify-icon icon="lucide:twitter"></iconify-icon></a>
        <a href="#" aria-label="GitHub"><iconify-icon icon="lucide:github"></iconify-icon></a>
      </div>
    </div>
    <div class="footer-col">
      <div class="footer-col-title">Services</div>
      <ul class="footer-link-list">
        <li><a href="/Services/SoftwareDevelopment/" class="footer-link">Software Development</a></li>
        <li><a href="/Services/CloudConsulting/"     class="footer-link">Cloud Consulting</a></li>
        <li><a href="/Services/Fractional/"          class="footer-link">Fractional CTO</a></li>
        <li><a href="/Services/ITStrategy/"          class="footer-link">IT Strategy</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <div class="footer-col-title">Company</div>
      <ul class="footer-link-list">
        <li><a href="/AboutUs/"     class="footer-link">About Us</a></li>
        <li><a href="/CaseStudies/" class="footer-link">Case Studies</a></li>

      </ul>
    </div>
    <div class="footer-col">
      <div class="footer-col-title">Resources</div>
      <ul class="footer-link-list">
        <li><a href="/Blog/"          class="footer-link">Blog</a></li>
        <?php if ($_cf_user): ?>
        <li><a href="/Tools/"         class="footer-link">Developer Tools</a></li>
        <?php endif; ?>
        <li><a href="/IDE/"           class="footer-link">Online IDE</a></li>
        <li><a href="/Pricing/"       class="footer-link">Pricing</a></li>
        <li><a href="/Documentation/" class="footer-link">Documentation</a></li>
        <li><a href="/Training/"      class="footer-link">Training</a></li>
        <li><a href="/Support/"       class="footer-link">Support</a></li>
        <li><a href="/PrivacyPolicy/" class="footer-link">Privacy Policy</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-legal">
    <div>&copy; 2024 CodeFoundry. All rights reserved.</div>
    <div>Crafted with innovation and excellence</div>
  </div>
</footer>
<script src="/assets/js/site.js"></script>
<script>
(function () {
  var _cfPageLoad = Date.now();
  var _cfCurrentPage = window.location.pathname;

  function _cfSendView(page, referrer, timeOnPage) {
    try {
      var payload = JSON.stringify({ page: page, referrer: referrer, time_on_page: timeOnPage });
      if (navigator.sendBeacon) {
        navigator.sendBeacon('/track.php', new Blob([payload], { type: 'application/json' }));
      } else {
        fetch('/track.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: payload,
          keepalive: true
        });
      }
    } catch (e) { /* ignore */ }
  }

  // Record the current page view on load
  _cfSendView(_cfCurrentPage, document.referrer ? new URL(document.referrer).pathname : '', 0);

  // On unload, send the time spent on the current page
  window.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'hidden') {
      var spent = Math.round((Date.now() - _cfPageLoad) / 1000);
      _cfSendView(_cfCurrentPage, '', spent);
    }
  });
}());
</script>
<?php if ($page_scripts !== ''): ?>
<script>
<?= $page_scripts ?>
</script>
<?php endif; ?>
</body>
</html>
