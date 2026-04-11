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
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($page_title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/site.css" />
<?php if ($page_styles !== ''): ?>
  <style>
<?= $page_styles ?>
  </style>
<?php endif; ?>
  <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
  <script>(function(){var t=localStorage.getItem('cf-theme');if(t)document.documentElement.setAttribute('data-cf-theme',t);})();</script>
</head>
<body>
<header>
  <div class="nav">
    <a href="/" class="brand">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 16l4-4l-4-4M6 8l-4 4l4 4m8.5-12l-5 16"/></svg>
      CodeFoundry
    </a>
    <nav class="nav-menu">
      <a href="/#services"    class="nav-link<?= cf_active('services') ?>">Services</a>
      <a href="/#solutions"   class="nav-link<?= cf_active('solutions') ?>">Solutions</a>
      <a href="/#industries"  class="nav-link<?= cf_active('industries') ?>">Industries</a>
      <a href="/CaseStudies/" class="nav-link<?= cf_active('case-studies') ?>">Case Studies</a>
      <a href="/Training/"    class="nav-link<?= cf_active('training') ?>">Training</a>
      <?php if ($_cf_user): ?>
      <a href="/Tools/"       class="nav-link<?= cf_active('tools') ?>">Tools</a>
      <?php endif; ?>
      <a href="/Pricing/"     class="nav-link<?= cf_active('pricing') ?>">Pricing</a>
      <a href="/AboutUs/"     class="nav-link<?= cf_active('about') ?>">About</a>

    </nav>
    <div class="nav-actions">
      <!-- Theme Picker (desktop) -->
      <div class="theme-picker" id="themePicker" aria-label="Choose color theme">
        <button class="theme-picker-btn" id="themePickerBtn" aria-haspopup="true" aria-expanded="false" title="Change theme">
          <iconify-icon icon="lucide:palette" width="18"></iconify-icon>
        </button>
        <div class="theme-picker-panel" id="themePickerPanel" role="menu">
          <div class="theme-picker-title">Color Theme</div>
          <div class="theme-swatches" id="themeSwatches">
            <button class="theme-swatch" data-theme="ocean"    title="Ocean" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#0e1828,#18b3ff)"></span><span class="theme-swatch-label">Ocean</span></button>
            <button class="theme-swatch" data-theme="midnight" title="Midnight" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#12102a,#9d7aff)"></span><span class="theme-swatch-label">Midnight</span></button>
            <button class="theme-swatch" data-theme="forest"   title="Forest" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#0e1f16,#2dc653)"></span><span class="theme-swatch-label">Forest</span></button>
            <button class="theme-swatch" data-theme="crimson"  title="Crimson" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#200e10,#ff4d6d)"></span><span class="theme-swatch-label">Crimson</span></button>
            <button class="theme-swatch" data-theme="amber"    title="Amber" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#1e1600,#ffb700)"></span><span class="theme-swatch-label">Amber</span></button>
            <button class="theme-swatch" data-theme="teal"     title="Teal" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#0a1e1f,#00d4d4)"></span><span class="theme-swatch-label">Teal</span></button>
            <button class="theme-swatch" data-theme="sunset"   title="Sunset" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#1e100a,#ff6b35)"></span><span class="theme-swatch-label">Sunset</span></button>
            <button class="theme-swatch" data-theme="arctic"   title="Arctic" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#e2eaf2,#0077cc)"></span><span class="theme-swatch-label">Arctic</span></button>
            <button class="theme-swatch" data-theme="graphite" title="Graphite" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#1a1a1a,#c0c0c0)"></span><span class="theme-swatch-label">Graphite</span></button>
            <button class="theme-swatch" data-theme="neon"     title="Neon" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#000,#00ff41)"></span><span class="theme-swatch-label">Neon</span></button>
            <button class="theme-swatch" data-theme="rose"     title="Rose" role="menuitem"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#1f1218,#f0a0b8)"></span><span class="theme-swatch-label">Rose</span></button>
          </div>
        </div>
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
        <a href="/#services"    class="nav-link" onclick="closeMobileNav()">Services</a>
        <a href="/#solutions"   class="nav-link" onclick="closeMobileNav()">Solutions</a>
        <a href="/#industries"  class="nav-link" onclick="closeMobileNav()">Industries</a>
        <a href="/CaseStudies/" class="nav-link" onclick="closeMobileNav()">Case Studies</a>
        <a href="/Training/"    class="nav-link" onclick="closeMobileNav()">Training</a>
        <?php if ($_cf_user): ?>
        <a href="/Tools/"       class="nav-link" onclick="closeMobileNav()">Tools</a>
        <?php endif; ?>
        <a href="/Pricing/"     class="nav-link" onclick="closeMobileNav()">Pricing</a>
        <a href="/AboutUs/"     class="nav-link" onclick="closeMobileNav()">About</a>

      </div>
      <div class="mobile-theme-picker">
        <div class="theme-picker-title">Color Theme</div>
        <div class="mobile-theme-swatches">
          <button class="theme-swatch" data-theme="ocean"    title="Ocean"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#0e1828,#18b3ff)"></span><span class="theme-swatch-label">Ocean</span></button>
          <button class="theme-swatch" data-theme="midnight" title="Midnight"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#12102a,#9d7aff)"></span><span class="theme-swatch-label">Midnight</span></button>
          <button class="theme-swatch" data-theme="forest"   title="Forest"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#0e1f16,#2dc653)"></span><span class="theme-swatch-label">Forest</span></button>
          <button class="theme-swatch" data-theme="crimson"  title="Crimson"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#200e10,#ff4d6d)"></span><span class="theme-swatch-label">Crimson</span></button>
          <button class="theme-swatch" data-theme="amber"    title="Amber"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#1e1600,#ffb700)"></span><span class="theme-swatch-label">Amber</span></button>
          <button class="theme-swatch" data-theme="teal"     title="Teal"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#0a1e1f,#00d4d4)"></span><span class="theme-swatch-label">Teal</span></button>
          <button class="theme-swatch" data-theme="sunset"   title="Sunset"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#1e100a,#ff6b35)"></span><span class="theme-swatch-label">Sunset</span></button>
          <button class="theme-swatch" data-theme="arctic"   title="Arctic"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#e2eaf2,#0077cc)"></span><span class="theme-swatch-label">Arctic</span></button>
          <button class="theme-swatch" data-theme="graphite" title="Graphite"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#1a1a1a,#c0c0c0)"></span><span class="theme-swatch-label">Graphite</span></button>
          <button class="theme-swatch" data-theme="neon"     title="Neon"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#000,#00ff41)"></span><span class="theme-swatch-label">Neon</span></button>
          <button class="theme-swatch" data-theme="rose"     title="Rose"><span class="theme-swatch-dot" style="background:linear-gradient(135deg,#1f1218,#f0a0b8)"></span><span class="theme-swatch-label">Rose</span></button>
        </div>
      </div>
      <div class="mobile-menu-actions">
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
