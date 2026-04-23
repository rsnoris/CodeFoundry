<?php
/**
 * CodeFoundry – Shared Page Header
 *
 * Expected variables (set by the calling page before require_once):
 *   string $page_title   – Full <title> text (required)
 *   string $active_page  – Nav-link ID to mark active, e.g. 'about' (optional)
 *   string $page_styles  – Additional CSS to inject inside <style> (optional)
 *
 * This file outputs everything from <!DOCTYPE html> through </header>.
 */
$page_title  = $page_title  ?? 'CodeFoundry';
$active_page = $active_page ?? '';
$page_styles = $page_styles ?? '';

// Start session once (guard against pages that already called session_start())
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_cf_user = $_SESSION['cf_user'] ?? null;

/**
 * Helper: return 'active' class string when $id matches $active_page.
 */
function cf_active(string $id): string {
    global $active_page;
    return $id === $active_page ? ' active' : '';
}

/**
 * Helper: whether any of the provided IDs match $active_page.
 */
function cf_is_active_any(array $ids): bool {
    global $active_page;
    return in_array($active_page, $ids, true);
}

/**
 * Helper: return 'active' when any of the provided IDs match $active_page.
 */
function cf_active_any(array $ids): string {
    return cf_is_active_any($ids) ? ' active' : '';
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($page_title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></title>
  <script>
    (function () {
      var config = {
        storageKey: 'cf-theme',
        defaultTheme: 'dark',
        themes: ['dark', 'light', 'ocean']
      };
      window.CF_THEME_CONFIG = config;

      var allowedThemes = {};
      config.themes.forEach(function (theme) {
        allowedThemes[theme] = true;
      });
      try {
        var savedTheme = localStorage.getItem(config.storageKey);
        var theme = allowedThemes[savedTheme] ? savedTheme : config.defaultTheme;
        document.documentElement.setAttribute('data-theme', theme);
      } catch (e) {
        document.documentElement.setAttribute('data-theme', config.defaultTheme);
      }
    }());
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/site.css" />
<?php if ($page_styles !== ''): ?>
  <style>
<?= $page_styles ?>
  </style>
<?php endif; ?>
  <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
</head>
<body>
<header>
  <div class="nav">
    <a href="/" class="brand">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 16l4-4l-4-4M6 8l-4 4l4 4m8.5-12l-5 16"/></svg>
      CodeFoundry
    </a>
    <nav class="nav-menu">
      <div class="nav-item-dropdown<?= cf_is_active_any(['solutions', 'services']) ? ' open' : '' ?>">
        <a href="/#solutions" class="nav-link<?= cf_active_any(['solutions', 'services']) ?>" aria-haspopup="true" aria-expanded="<?= cf_is_active_any(['solutions', 'services']) ? 'true' : 'false' ?>">Solutions</a>
        <div class="nav-submenu" role="menu" aria-label="Solutions submenu">
          <a href="/#services" class="nav-link nav-sub-link<?= cf_active('services') ?>" role="menuitem">Services</a>
        </div>
      </div>
      <div class="nav-item-dropdown<?= cf_is_active_any(['industries', 'case-studies']) ? ' open' : '' ?>">
        <a href="/#industries" class="nav-link<?= cf_active_any(['industries', 'case-studies']) ?>" aria-haspopup="true" aria-expanded="<?= cf_is_active_any(['industries', 'case-studies']) ? 'true' : 'false' ?>">Industries</a>
        <div class="nav-submenu" role="menu" aria-label="Industries submenu">
          <a href="/CaseStudies/" class="nav-link nav-sub-link<?= cf_active('case-studies') ?>" role="menuitem">Case Studies</a>
        </div>
      </div>
      <?php if ($_cf_user): ?>
      <a href="/VIRAL/"       class="nav-link<?= cf_active('viral') ?>">VIRAL Agents</a>
      <?php endif; ?>
      <a href="/Pricing/"     class="nav-link<?= cf_active('pricing') ?>">Pricing</a>
      <a href="/AboutUs/"     class="nav-link<?= cf_active('about') ?>">About</a>

    </nav>
    <div class="nav-actions">
      <div class="theme-switcher">
        <label for="themeSelect" class="theme-switcher-label">Theme</label>
        <select id="themeSelect" class="theme-switcher-select" aria-label="Select theme">
          <option value="dark">Dark</option>
          <option value="light">Light</option>
          <option value="ocean">Ocean</option>
        </select>
      </div>
      <?php if ($_cf_user): ?>
        <div class="nav-user-menu" id="navUserMenu">
          <button class="nav-user-btn" id="navUserBtn" aria-haspopup="true" aria-expanded="false" aria-label="User menu">
            <iconify-icon icon="lucide:user-circle-2"></iconify-icon>
            <span class="nav-user-name"><?= htmlspecialchars($_cf_user['display'] ?? $_cf_user['username'] ?? 'User', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
            <iconify-icon icon="lucide:chevron-down" class="nav-user-caret"></iconify-icon>
          </button>
          <div class="nav-user-dropdown" id="navUserDropdown" role="menu">
            <div class="nav-user-info">
              <div class="nav-user-display"><?= htmlspecialchars($_cf_user['display'] ?? $_cf_user['username'] ?? 'User', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
              <div class="nav-user-role"><?= htmlspecialchars(ucfirst($_cf_user['role'] ?? 'user'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
            </div>
            <a href="/Dashboard/" class="nav-user-dropdown-item" role="menuitem">
              <iconify-icon icon="lucide:layout-dashboard"></iconify-icon>
              Dashboard
            </a>
            <?php if (($_cf_user['role'] ?? '') === 'admin'): ?>
            <a href="/Admin/" class="nav-user-dropdown-item" role="menuitem">
              <iconify-icon icon="lucide:shield-check"></iconify-icon>
              Control Panel
            </a>
            <?php endif; ?>
            <a href="/Login/logout.php" class="nav-user-dropdown-item" role="menuitem">
              <iconify-icon icon="lucide:log-out"></iconify-icon>
              Sign Out
            </a>
          </div>
        </div>
      <?php else: ?>
        <a href="/Login/" class="nav-login-btn<?= cf_active('login') ?>" aria-label="Login">
          <iconify-icon icon="lucide:log-in"></iconify-icon>
          <span>Login</span>
        </a>
      <?php endif; ?>
    </div>
    <button class="mobile-hamburger" id="mobileMenuBtn" aria-label="Open menu">
      <iconify-icon icon="lucide:menu"></iconify-icon>
    </button>
  </div>
  <!-- Mobile Menu Overlay -->
  <div class="mobile-nav-overlay" id="mobileNav">
    <div class="mobile-nav-panel">
      <button class="mobile-menu-close" id="closeMobileNav" aria-label="Close menu">
        <iconify-icon icon="lucide:x"></iconify-icon>
      </button>
      <div class="mobile-menu-links">
        <div class="mobile-nav-group">
          <a href="/#solutions" class="nav-link" onclick="closeMobileNav()">Solutions</a>
          <a href="/#services" class="nav-link nav-sub-link" onclick="closeMobileNav()">Services</a>
        </div>
        <div class="mobile-nav-group">
          <a href="/#industries" class="nav-link" onclick="closeMobileNav()">Industries</a>
          <a href="/CaseStudies/" class="nav-link nav-sub-link" onclick="closeMobileNav()">Case Studies</a>
        </div>
        <?php if ($_cf_user): ?>
        <a href="/VIRAL/"       class="nav-link" onclick="closeMobileNav()">VIRAL Agents</a>
        <?php endif; ?>
        <a href="/Pricing/"     class="nav-link" onclick="closeMobileNav()">Pricing</a>
        <a href="/AboutUs/"     class="nav-link" onclick="closeMobileNav()">About</a>

      </div>
      <div class="mobile-menu-actions">
        <div class="theme-switcher mobile-theme-switcher">
          <label for="mobileThemeSelect" class="theme-switcher-label">Theme</label>
          <select id="mobileThemeSelect" class="theme-switcher-select" aria-label="Select theme">
            <option value="dark">Dark</option>
            <option value="light">Light</option>
            <option value="ocean">Ocean</option>
          </select>
        </div>
        <?php if ($_cf_user): ?>
          <div class="mobile-user-info">
            <iconify-icon icon="lucide:user-circle-2"></iconify-icon>
            <span><?= htmlspecialchars($_cf_user['display'] ?? $_cf_user['username'] ?? 'User', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
          </div>
          <a href="/Login/logout.php" class="nav-btn secondary" onclick="closeMobileNav()">Sign Out</a>
        <?php else: ?>
          <a href="/Login/" class="nav-btn primary" onclick="closeMobileNav()">
            <iconify-icon icon="lucide:log-in"></iconify-icon>
            Login
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
