/**
 * RABEZ PWA Service Worker (sw.js)
 * Enables installable app capability & fast cached asset loading
 */
const CACHE_NAME = "rabez-pwa-v2";
const STATIC_ASSETS = [
  "/",
  "/manifest.json",
  "/assets/css/01-variables.css",
  "/assets/css/02-base-reset.css",
  "/assets/css/03-components.css",
  "/assets/js/analytics.js",
  "/assets/js/products-data.js",
  "/assets/js/cart.js",
  "/assets/js/app.js",
  "/assets/images/brand/rabez-emblem.jpg",
  "/assets/images/brand/rabez-neon-logo.png"
];

self.addEventListener("install", (e) => {
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_ASSETS).catch(() => {});
    })
  );
  self.skipWaiting();
});

self.addEventListener("activate", (e) => {
  e.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k))
      );
    })
  );
  self.clients.claim();
});

self.addEventListener("fetch", (e) => {
  // Only cache GET requests for static assets
  if (e.request.method !== "GET" || !e.request.url.startsWith("http")) return;

  e.respondWith(
    fetch(e.request).catch(() => {
      return caches.match(e.request);
    })
  );
});

