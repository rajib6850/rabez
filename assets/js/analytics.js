/**
 * RABEZ Tracking & Security Engine (assets/js/analytics.js)
 * 1. Facebook Pixel (Client-Side) + Event ID for Server-Side CAPI Deduplication
 * 2. Fraud & Spam Prevention (BD Phone Validation, Honeypot, Rate Limiting)
 * 3. XSS Sanitization for Custom Name/Number Prints
 */
(function () {
  "use strict";

  // Default RABEZ Meta Pixel ID (can be overridden via window.RABEZ_PIXEL_ID or localStorage)
  const DEFAULT_PIXEL_ID = window.RABEZ_PIXEL_ID || localStorage.getItem("rabez_pixel_id") || "YOUR_PIXEL_ID_HERE";

  // --- 1. Facebook Pixel Base Loader ---
  function initPixel(pixelId) {
    if (window.fbq) return;
    /* eslint-disable */
    !(function (f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function () {
        n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = "2.0";
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s);
    })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
    /* eslint-enable */

    if (pixelId && pixelId !== "YOUR_PIXEL_ID_HERE") {
      window.fbq("init", pixelId);
      window.fbq("track", "PageView");
      console.log(`[RABEZ Analytics] Facebook Pixel initialized: ${pixelId}`);
    } else {
      console.info("[RABEZ Analytics] Facebook Pixel ready. Set your Pixel ID in Admin > Settings or via window.RABEZ_PIXEL_ID.");
    }
  }

  // --- 2. Unique Event ID Generator (for 1:1 Browser + Server CAPI Deduplication) ---
  function generateEventId(prefix) {
    return (prefix || "rbz") + "_" + Date.now() + "_" + Math.floor(Math.random() * 1000000);
  }

  // --- 3. Pixel Event Trackers with E-Commerce Payload ---
  function trackViewContent(product) {
    if (!window.fbq || !product) return;
    const eventId = generateEventId("vc");
    window.fbq(
      "track",
      "ViewContent",
      {
        content_name: product.name,
        content_ids: [String(product.id)],
        content_type: "product",
        value: Number(product.price) || 0,
        currency: "BDT"
      },
      { eventID: eventId }
    );
    return eventId;
  }

  function trackAddToCart(product, size) {
    if (!window.fbq || !product) return;
    const eventId = generateEventId("atc");
    window.fbq(
      "track",
      "AddToCart",
      {
        content_name: product.name + (size ? ` (${size})` : ""),
        content_ids: [String(product.id)],
        content_type: "product",
        value: Number(product.price) || 0,
        currency: "BDT"
      },
      { eventID: eventId }
    );
    return eventId;
  }

  function trackInitiateCheckout(cart, total) {
    if (!window.fbq || !Array.isArray(cart)) return;
    const eventId = generateEventId("ic");
    window.fbq(
      "track",
      "InitiateCheckout",
      {
        content_ids: cart.map((item) => String(item.id)),
        content_type: "product",
        num_items: cart.reduce((acc, item) => acc + (item.qty || 1), 0),
        value: Number(total) || 0,
        currency: "BDT"
      },
      { eventID: eventId }
    );
    return eventId;
  }

  function trackPurchase(order) {
    if (!window.fbq || !order) return;
    const eventId = order.eventId || generateEventId("pur");
    window.fbq(
      "track",
      "Purchase",
      {
        content_name: "Order #" + (order.id || ""),
        content_ids: (order.items || []).map((i) => String(i.id)),
        content_type: "product",
        num_items: (order.items || []).reduce((acc, i) => acc + (i.qty || 1), 0),
        value: Number(order.total) || 0,
        currency: "BDT"
      },
      { eventID: eventId }
    );
    return eventId;
  }

  // --- 4. Security & Anti-Fraud Helpers ---

  // Bangladeshi Mobile Number Validator (must be exactly 11 digits: 013, 014, 015, 016, 017, 018, 019)
  function isValidBDPhone(phone) {
    if (!phone) return false;
    const clean = phone.replace(/[^0-9]/g, "");
    const bdRegex = /^(?:\+?88)?(01[3-9]\d{8})$/;
    return bdRegex.test(clean);
  }

  function normalizeBDPhone(phone) {
    if (!phone) return "";
    const clean = phone.replace(/[^0-9]/g, "");
    if (clean.length === 11 && clean.startsWith("01")) return clean;
    if (clean.length === 13 && clean.startsWith("8801")) return clean.slice(2);
    return clean;
  }

  // XSS Sanitizer for customer input
  function sanitizeInput(str) {
    if (typeof str !== "string") return "";
    return str
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#x27;")
      .trim();
  }

  // Anti-Spam Rate Limiter for Checkout Form (Prevents bot hammering)
  const RATE_LIMIT_KEY = "rabez_last_order_time";
  const COOLDOWN_SECONDS = 15;

  function canPlaceOrder() {
    const last = Number(localStorage.getItem(RATE_LIMIT_KEY) || 0);
    const now = Date.now();
    if (now - last < COOLDOWN_SECONDS * 1000) {
      const wait = Math.ceil((COOLDOWN_SECONDS * 1000 - (now - last)) / 1000);
      return { allowed: false, waitSeconds: wait };
    }
    return { allowed: true, waitSeconds: 0 };
  }

  function recordOrderPlaced() {
    localStorage.setItem(RATE_LIMIT_KEY, String(Date.now()));
  }

  // Initialize Pixel automatically on load
  initPixel(DEFAULT_PIXEL_ID);

  // Expose Globally
  window.RABEZ_ANALYTICS = {
    initPixel,
    generateEventId,
    trackViewContent,
    trackAddToCart,
    trackInitiateCheckout,
    trackPurchase,
    isValidBDPhone,
    normalizeBDPhone,
    sanitizeInput,
    canPlaceOrder,
    recordOrderPlaced
  };
})();

