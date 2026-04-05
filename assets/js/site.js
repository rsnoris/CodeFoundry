/**
 * CodeFoundry – Shared Site JavaScript
 *
 * Mobile navigation toggle, usable on every page that includes
 * the standard header markup produced by includes/header.php.
 */

(function () {
  'use strict';

  /**
   * Initialise the mobile navigation overlay.
   * Called once the DOM is ready.
   */
  function initMobileNav() {
    const menuBtn  = document.getElementById('mobileMenuBtn');
    const mobileNav = document.getElementById('mobileNav');
    const closeBtn  = document.getElementById('closeMobileNav');

    if (!menuBtn || !mobileNav || !closeBtn) {
      return; // elements absent on this page – nothing to do
    }

    function openMobileNav() {
      mobileNav.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileNav() {
      mobileNav.classList.remove('open');
      document.body.style.overflow = '';
    }

    menuBtn.addEventListener('click', openMobileNav);
    closeBtn.addEventListener('click', closeMobileNav);

    // Close when tapping the translucent backdrop (outside the panel)
    mobileNav.addEventListener('click', function (e) {
      if (e.target === mobileNav) {
        closeMobileNav();
      }
    });

    // Expose globally so inline onclick="closeMobileNav()" attributes work
    window.closeMobileNav = closeMobileNav;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileNav);
  } else {
    initMobileNav();
  }
}());
