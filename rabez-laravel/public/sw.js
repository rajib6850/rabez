/**
 * RABEZ PWA Service Worker (sw.js)
 * Enables installable app capability & fast cached asset loading
 */
const CACHE_NAME = "rabez-pwa-v13";
const STATIC_ASSETS = [
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
  // Navigation requests (HTML pages) must always fetch live network to reflect coupon & stock changes
  if (e.request.mode === "navigate") {
    e.respondWith(
      fetch(e.request).catch(() => {
        return caches.match(e.request);
      })
    );
    return;
  }

  // Only cache GET requests for static assets
  if (e.request.method !== "GET" || !e.request.url.startsWith("http")) return;

  e.respondWith(
    fetch(e.request).catch(() => {
      return caches.match(e.request);
    })
  );
});

self.addEventListener("push", (e) => {
  let payload = {
    title: "New Order Received!",
    body: "Tap to view order details in RABEZ Admin",
    url: "/admin",
    tag: "rabez-order-" + Date.now(),
    open_count: 1
  };
  if (e.data) {
    try {
      const parsed = e.data.json();
      if (parsed && typeof parsed === "object") {
        payload = { ...payload, ...parsed };
      }
    } catch (err) {
      payload.body = e.data.text() || payload.body;
    }
  }

  const options = {
    body: payload.body,
    icon: "/icon-192.png",
    badge: "/icon-192.png",
    tag: payload.tag || "rabez-new-order",
    renotify: true,
    vibrate: [250, 120, 250, 120, 300],
    requireInteraction: true,
    data: {
      url: payload.url || "/admin",
      order_id: payload.order_id || null
    }
  };

  e.waitUntil(
    Promise.all([
      self.registration.showNotification(payload.title, options),
      (typeof self.navigator !== "undefined" && typeof self.navigator.setAppBadge === "function")
        ? self.navigator.setAppBadge(Math.max(1, Number(payload.open_count) || 1)).catch(() => {})
        : Promise.resolve(),
      self.clients.matchAll({ type: "window", includeUncontrolled: true }).then((list) => {
        list.forEach((client) => {
          try {
            client.postMessage({ type: "RABEZ_PUSH_ORDER", payload });
          } catch (err) {}
        });
      })
    ])
  );
});

self.addEventListener("notificationclick", (e) => {
  e.notification.close();
  const targetUrl = (e.notification.data && e.notification.data.url) ? e.notification.data.url : "/admin";
  e.waitUntil(
    self.clients.matchAll({ type: "window", includeUncontrolled: true }).then((clientList) => {
      for (const client of clientList) {
        if (client.url.includes("/admin") && "focus" in client) {
          return client.focus();
        }
      }
      if (self.clients.openWindow) {
        return self.clients.openWindow(targetUrl);
      }
    })
  );
});

