/**
 * CodeFoundry – Theme Bootstrap
 *
 * Applies the saved theme before CSS loads to reduce flash and exposes
 * shared configuration for other scripts.
 */
(function () {
  'use strict';

  var config = {
    storageKey: 'cf-theme',
    defaultTheme: 'dark',
    themes: ['dark', 'light', 'ocean']
  };

  var allowedThemes = {};
  config.themes.forEach(function (theme) {
    allowedThemes[theme] = true;
  });

  window.CF_THEME_CONFIG = config;

  var theme = config.defaultTheme;
  try {
    var savedTheme = localStorage.getItem(config.storageKey);
    if (allowedThemes[savedTheme]) {
      theme = savedTheme;
    }
  } catch (e) {
    // ignore storage access failures
  }

  document.documentElement.setAttribute('data-theme', theme);
}());
