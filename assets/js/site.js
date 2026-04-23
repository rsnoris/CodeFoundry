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

  /**
   * Initialise desktop nav dropdown accessibility state.
   */
  function initNavDropdowns() {
    const dropdowns = document.querySelectorAll('.nav-item-dropdown');
    if (!dropdowns.length) {
      return;
    }

    function getTrigger(dropdown) {
      const el = dropdown.firstElementChild;
      if (el && el.classList.contains('nav-link') && el.getAttribute('aria-haspopup') === 'true') {
        return el;
      }
      return null;
    }

    function setOpen(dropdown, isOpen) {
      dropdown.classList.toggle('open', isOpen);
      const trigger = getTrigger(dropdown);
      if (trigger) {
        trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      }
    }

    dropdowns.forEach(function (dropdown) {
      const trigger = getTrigger(dropdown);
      const firstSubLink = dropdown.querySelector('.nav-submenu .nav-sub-link');

      dropdown.addEventListener('mouseenter', function () {
        setOpen(dropdown, true);
      });
      dropdown.addEventListener('mouseleave', function () {
        setOpen(dropdown, false);
      });
      dropdown.addEventListener('focusin', function () {
        setOpen(dropdown, true);
      });
      dropdown.addEventListener('focusout', function () {
        setTimeout(function () {
          if (!dropdown.contains(document.activeElement)) {
            setOpen(dropdown, false);
          }
        }, 0);
      });

      if (!trigger) {
        return;
      }

      trigger.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowDown' && firstSubLink) {
          e.preventDefault();
          setOpen(dropdown, true);
          firstSubLink.focus();
          return;
        }
        if (e.key === 'Escape') {
          setOpen(dropdown, false);
        }
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        dropdowns.forEach(function (dropdown) {
          setOpen(dropdown, false);
        });
      }
    });
  }

  /**
   * Initialise shared floating theme toggle and persistence.
   */
  function initThemeControls() {
    const themeConfig = window.CF_THEME_CONFIG || {};
    const storageKey = themeConfig.storageKey || 'cf-theme';
    const allowedThemes = Array.isArray(themeConfig.themes) && themeConfig.themes.length
      ? themeConfig.themes
      : ['dark', 'light', 'ocean'];
    const defaultTheme = themeConfig.defaultTheme && allowedThemes.indexOf(themeConfig.defaultTheme) !== -1
      ? themeConfig.defaultTheme
      : 'dark';
    const toggleBtn = document.getElementById('themeToggleBtn');
    const toggleIcon = document.getElementById('themeToggleIcon');
    const toggleLabel = document.getElementById('themeToggleLabel');
    if (!toggleBtn) {
      return;
    }
    const themeMeta = {
      dark: { label: 'Dark', icon: 'lucide:moon-star' },
      light: { label: 'Light', icon: 'lucide:sun' },
      ocean: { label: 'Ocean', icon: 'lucide:waves' }
    };

    function isThemeAllowed(theme) {
      return allowedThemes.indexOf(theme) !== -1;
    }

    function applyTheme(theme) {
      const nextTheme = isThemeAllowed(theme) ? theme : defaultTheme;
      document.documentElement.setAttribute('data-theme', nextTheme);
      const meta = themeMeta[nextTheme];
      if (!meta) {
        console.warn('[CodeFoundry] Unknown theme metadata for:', nextTheme);
      }
      const activeMeta = meta || { label: nextTheme, icon: 'lucide:paintbrush' };
      if (toggleLabel) {
        toggleLabel.textContent = activeMeta.label;
      }
      if (toggleIcon) {
        toggleIcon.setAttribute('icon', activeMeta.icon);
      }
      toggleBtn.setAttribute('title', 'Theme: ' + activeMeta.label);
      try {
        localStorage.setItem(storageKey, nextTheme);
      } catch (e) {
        // ignore storage errors
      }
    }

    var currentTheme = document.documentElement.getAttribute('data-theme');
    if (!isThemeAllowed(currentTheme)) {
      currentTheme = defaultTheme;
    }
    applyTheme(currentTheme);

    toggleBtn.addEventListener('click', function () {
      const current = document.documentElement.getAttribute('data-theme');
      const currentIndex = allowedThemes.indexOf(current);
      const nextTheme = allowedThemes[(currentIndex + 1) % allowedThemes.length];
      applyTheme(nextTheme);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initMobileNav();
      initUserMenu();
      initNavDropdowns();
      initThemeControls();
    });
  } else {
    initMobileNav();
    initUserMenu();
    initNavDropdowns();
    initThemeControls();
  }
}());
