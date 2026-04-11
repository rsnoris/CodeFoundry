/**
 * CodeFoundry – Shared Site JavaScript
 *
 * Mobile navigation toggle, user menu dropdown, and theme switcher,
 * usable on every page that includes the standard header markup produced
 * by includes/header.php.
 */

(function () {
  'use strict';

  // -------------------------------------------------------------------------
  // Theme Manager
  // -------------------------------------------------------------------------

  var THEME_KEY     = 'cf-theme';
  var DEFAULT_THEME = 'ocean';

  /**
   * Apply a theme by setting data-cf-theme on <html> and persisting the
   * choice.  Also syncs the active state on all swatch buttons.
   */
  function applyTheme(theme) {
    document.documentElement.setAttribute('data-cf-theme', theme);
    try { localStorage.setItem(THEME_KEY, theme); } catch (e) { /* ignore */ }
    document.querySelectorAll('.theme-swatch').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.theme === theme);
    });
  }

  function initThemePicker() {
    // Desktop picker
    var picker   = document.getElementById('themePicker');
    var pickerBtn = document.getElementById('themePickerBtn');
    var panel    = document.getElementById('themePickerPanel');

    if (picker && pickerBtn && panel) {
      pickerBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = picker.classList.contains('open');
        picker.classList.toggle('open', !isOpen);
        pickerBtn.setAttribute('aria-expanded', String(!isOpen));
      });

      document.addEventListener('click', function (e) {
        if (!picker.contains(e.target)) {
          picker.classList.remove('open');
          pickerBtn.setAttribute('aria-expanded', 'false');
        }
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          picker.classList.remove('open');
          pickerBtn.setAttribute('aria-expanded', 'false');
        }
      });
    }

    // Swatch buttons (both desktop and mobile share the same handler)
    document.querySelectorAll('.theme-swatch').forEach(function (btn) {
      btn.addEventListener('click', function () {
        applyTheme(btn.dataset.theme);
        // Close desktop panel
        if (picker) {
          picker.classList.remove('open');
          if (pickerBtn) pickerBtn.setAttribute('aria-expanded', 'false');
        }
      });
    });

    // Sync active swatch with the already-applied theme (set in <head>)
    var current = document.documentElement.getAttribute('data-cf-theme') || DEFAULT_THEME;
    document.querySelectorAll('.theme-swatch').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.theme === current);
    });
  }

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
      initThemePicker();
      initMobileNav();
      initUserMenu();
    });
  } else {
    initThemePicker();
    initMobileNav();
    initUserMenu();
  }
}());
