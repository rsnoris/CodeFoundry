/**
 * CodeFoundry – Shared Site JavaScript
 *
 * Mobile navigation toggle and user menu dropdown, usable on every page that
 * includes the standard header markup produced by includes/header.php.
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

  /**
   * Initialise the desktop user-menu dropdown.
   */
  function initUserMenu() {
    const menu    = document.getElementById('navUserMenu');
    const btn     = document.getElementById('navUserBtn');
    const dropdown = document.getElementById('navUserDropdown');

    if (!menu || !btn || !dropdown) {
      return;
    }

    function openMenu() {
      menu.classList.add('open');
      btn.setAttribute('aria-expanded', 'true');
    }

    function closeMenu() {
      menu.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      menu.classList.contains('open') ? closeMenu() : openMenu();
    });

    // Close when clicking anywhere outside the menu
    document.addEventListener('click', function (e) {
      if (!menu.contains(e.target)) {
        closeMenu();
      }
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeMenu();
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initMobileNav();
      initUserMenu();
    });
  } else {
    initMobileNav();
    initUserMenu();
  }
}());
