/* CodeFoundry IDE service worker (navigation fallback + shell cache). */
'use strict';

const CACHE_NAME = 'codefoundry-ide-shell-v1';
const SHELL_ASSETS = [
  '/IDE/manifest.webmanifest',
  '/IDE/offline.html',
];

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function (cache) {
      return cache.addAll(SHELL_ASSETS);
    })
  );
});

self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys().then(function (keys) {
      return Promise.all(keys.map(function (k) {
        if (k !== CACHE_NAME) return caches.delete(k);
        return Promise.resolve();
      }));
    })
  );
});

self.addEventListener('fetch', function (event) {
  const req = event.request;
  if (req.method !== 'GET') return;

  if (req.mode === 'navigate' && new URL(req.url).pathname.startsWith('/IDE/')) {
    event.respondWith(
      fetch(req).catch(function () {
        return caches.match('/IDE/offline.html');
      })
    );
    return;
  }

  const url = new URL(req.url);
  if (SHELL_ASSETS.includes(url.pathname)) {
    event.respondWith(
      caches.match(req).then(function (cached) {
        return cached || fetch(req);
      })
    );
  }
});
