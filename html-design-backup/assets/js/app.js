/**
 * RABEZ Frontend Application Controllers (assets/js/app.js)
 * Clean, lightweight vanilla JS for UI components:
 * Sticky Header & Mobile Nav, Hero Showcase, Jersey Customizer, Size Guide Modal, Single Product Controls
 */
(function () {
  "use strict";

  /* 1. Sticky Header & Mobile Nav Controller */
  function initHeaderWidget() {
    const header = document.querySelector(".rabez-header");
    const toggle = document.querySelector(".js-mobile-nav-toggle");
    const nav = document.querySelector(".js-main-nav");

    if (header) {
      window.addEventListener(
        "scroll",
        () => {
          header.classList.toggle("is-scrolled", window.scrollY > 24);
        },
        { passive: true }
      );
    }

    if (toggle && nav) {
      toggle.addEventListener("click", () => {
        const isOpen = nav.classList.toggle("is-open");
        toggle.setAttribute("aria-expanded", String(isOpen));
      });

      nav.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", () => {
          nav.classList.remove("is-open");
          toggle.setAttribute("aria-expanded", "false");
        });
      });

      document.addEventListener("click", (e) => {
        if (!e.target.closest(".rabez-header") && nav.classList.contains("is-open")) {
          nav.classList.remove("is-open");
          toggle.setAttribute("aria-expanded", "false");
        }
      });
    }
  }

  /* 2. Hero Interactive 3-Jersey Fan Stage Widget */
  function initHeroShowcaseWidget() {
    const stage = document.querySelector(".js-hero-stage");
    if (!stage) return;

    const cards = Array.from(stage.querySelectorAll(".js-hero-deck-card"));
    const dockBtns = Array.from(stage.querySelectorAll(".js-hero-dock-btn"));
    const total = cards.length;
    if (!total) return;

    let currentIndex = 0;
    let autoTimer = null;

    function setActiveSlide(index) {
      currentIndex = ((index % total) + total) % total;
      const prevIndex = (currentIndex - 1 + total) % total;
      const nextIndex = (currentIndex + 1) % total;

      cards.forEach((card, idx) => {
        card.classList.remove("is-active", "is-prev", "is-next");
        if (idx === currentIndex) {
          card.classList.add("is-active");
        } else if (idx === prevIndex) {
          card.classList.add("is-prev");
        } else if (idx === nextIndex) {
          card.classList.add("is-next");
        }
      });

      dockBtns.forEach((btn) => {
        btn.classList.toggle("is-active", Number(btn.dataset.index) === currentIndex);
      });
    }

    function startAutoPlay() {
      stopAutoPlay();
      autoTimer = window.setInterval(() => {
        setActiveSlide(currentIndex + 1);
      }, 3800);
    }

    function stopAutoPlay() {
      if (autoTimer) {
        window.clearInterval(autoTimer);
        autoTimer = null;
      }
    }

    cards.forEach((card, idx) => {
      card.addEventListener("click", () => {
        if (idx !== currentIndex) {
          setActiveSlide(idx);
          startAutoPlay();
        } else {
          // Clicking the center spotlight jersey opens Quick View for instant size selection
          const prodId = Number(card.dataset.productId || 1324);
          if (window.RABEZ_STORE && typeof window.RABEZ_STORE.openQuickView === "function") {
            window.RABEZ_STORE.openQuickView(prodId);
          } else {
            window.location.href = `product.html?id=${prodId}`;
          }
        }
      });
    });

    dockBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        const targetIdx = Number(btn.dataset.index || 0);
        setActiveSlide(targetIdx);
        startAutoPlay();
      });
    });

    stage.addEventListener("mouseenter", stopAutoPlay);
    stage.addEventListener("mouseleave", startAutoPlay);
    stage.addEventListener("touchstart", stopAutoPlay, { passive: true });

    setActiveSlide(0);
    startAutoPlay();
  }

  /* 3. Interactive Jersey Back Name & Number Customizer Widget */
  function initJerseyCustomizerWidget() {
    const wrap = document.querySelector(".js-jersey-customizer");
    if (!wrap) return;

    const nameInput = wrap.querySelector(".js-cust-name");
    const numInput = wrap.querySelector(".js-cust-number");
    const kitSelect = wrap.querySelector(".js-cust-kit");
    const sizeSelect = wrap.querySelector(".js-cust-size");
    const previewName = wrap.querySelector(".js-preview-name");
    const previewNum = wrap.querySelector(".js-preview-num");
    const svgBody = wrap.querySelector(".js-svg-jersey-body");
    const svgTrim = wrap.querySelector(".js-svg-jersey-trim");
    const swatches = wrap.querySelectorAll(".js-kit-theme-swatch");
    const addCustomBtn = wrap.querySelector(".js-add-custom-kit");

    function syncPreview() {
      const nameVal = (nameInput?.value || "RABEZ").trim().toUpperCase().slice(0, 12);
      const numVal = (numInput?.value || "10").trim().slice(0, 2);
      if (previewName) previewName.textContent = nameVal || "YOUR NAME";
      if (previewNum) previewNum.textContent = numVal || "10";
    }

    nameInput?.addEventListener("input", syncPreview);
    numInput?.addEventListener("input", syncPreview);

    swatches.forEach((sw) => {
      sw.addEventListener("click", () => {
        swatches.forEach((s) => s.classList.remove("is-active"));
        sw.classList.add("is-active");
        const bg = sw.dataset.bg || "#FFFFFF";
        const trim = sw.dataset.trim || "#04F404";
        const text = sw.dataset.text || "#111111";
        const prodId = sw.dataset.productId || "1324";

        if (svgBody) svgBody.setAttribute("fill", bg);
        if (svgTrim) svgTrim.setAttribute("stroke", trim);
        if (previewName) previewName.style.color = text;
        if (previewNum) previewNum.style.color = text;
        if (kitSelect) kitSelect.value = prodId;
      });
    });

    addCustomBtn?.addEventListener("click", () => {
      const id = Number(kitSelect?.value || 1324);
      const size = sizeSelect?.value || "L";
      const customName = nameInput?.value || "RABEZ";
      const customNumber = numInput?.value || "10";
      if (window.RABEZ_STORE) {
        window.RABEZ_STORE.addToCart({ id, size, qty: 1, customName, customNumber });
        window.RABEZ_STORE.openCartDrawer();
      }
    });
  }

  /* 4. Size Guide Tab Switcher (Fan Version vs Player Version) */
  function initSizeGuideWidget() {
    const box = document.querySelector(".js-size-guide");
    if (!box) return;

    const tabs = box.querySelectorAll(".js-fit-tab");
    const panels = box.querySelectorAll(".js-fit-panel");

    tabs.forEach((tab) => {
      tab.addEventListener("click", () => {
        const target = tab.dataset.fit;
        tabs.forEach((t) => t.classList.remove("is-active"));
        tab.classList.add("is-active");
        panels.forEach((p) => {
          p.style.display = p.dataset.fitPanel === target ? "block" : "none";
        });
      });
    });
  }

  /* 5. Single Product Quantity & Custom Print Toggle */
  function initSingleProductControls() {
    const printToggle = document.querySelector(".js-toggle-print-addon");
    const printFields = document.querySelector(".js-print-addon-fields");
    if (printToggle && printFields) {
      printToggle.addEventListener("click", () => {
        printFields.classList.toggle("is-open");
      });
    }

    document.querySelectorAll(".js-qty-minus, .js-qty-plus").forEach((btn) => {
      btn.addEventListener("click", () => {
        const input = btn.parentElement.querySelector(".js-sp-qty");
        if (!input) return;
        let val = Number(input.value) || 1;
        val = btn.classList.contains("js-qty-plus") ? val + 1 : Math.max(1, val - 1);
        input.value = val;
      });
    });

    // Checkout COD Form Submission
    const codForm = document.querySelector(".js-cod-checkout-form");
    if (codForm) {
      codForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const rawName = codForm.querySelector('[name="customer_name"]')?.value || "Customer";
        const rawPhone = codForm.querySelector('[name="customer_phone"]')?.value || "";
        const rawAddress = codForm.querySelector('[name="customer_address"]')?.value || "";
        const zoneInput = codForm.querySelector('input[name="shipping_zone"]:checked');
        const shipping = zoneInput ? Number(zoneInput.value) : 70;

        // Security: Fraud & Anti-Spam Checks
        if (window.RABEZ_ANALYTICS) {
          if (!window.RABEZ_ANALYTICS.isValidBDPhone(rawPhone)) {
            alert("অনুগ্রহ করে সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 01712345678 / 018... / 019...)");
            const phoneInput = codForm.querySelector('[name="customer_phone"]');
            if (phoneInput) phoneInput.focus();
            return;
          }

          const rateCheck = window.RABEZ_ANALYTICS.canPlaceOrder();
          if (!rateCheck.allowed) {
            alert(`অনুগ্রহ করে ${rateCheck.waitSeconds} সেকেন্ড অপেক্ষা করুন।`);
            return;
          }
        }

        const name = window.RABEZ_ANALYTICS ? window.RABEZ_ANALYTICS.sanitizeInput(rawName) : rawName;
        const phone = window.RABEZ_ANALYTICS ? window.RABEZ_ANALYTICS.normalizeBDPhone(rawPhone) : rawPhone;
        const address = window.RABEZ_ANALYTICS ? window.RABEZ_ANALYTICS.sanitizeInput(rawAddress) : rawAddress;
        const orderId = "RBZ-" + Math.floor(10030 + Math.random() * 900);

        try {
          const cartItems = JSON.parse(localStorage.getItem("rabez_wc_cart_v1") || "[]");
          const subtotal = cartItems.reduce((s, i) => s + i.price * i.qty, 0) || 1150;
          const savedOrders = JSON.parse(localStorage.getItem("rabez_orders_v1") || "[]");
          const newOrder = {
            id: orderId,
            date: "Just Now",
            customer: name,
            phone: phone,
            address: address,
            zone: shipping === 70 ? "Inside Dhaka (৳70)" : "Outside Dhaka (৳130)",
            statusStep: 2,
            statusLabel: "Order Confirmed — Preparing Dispatch",
            courier: "Steadfast Courier",
            consignmentId: "Pending Booking",
            subtotal: subtotal,
            shipping: shipping,
            total: subtotal + shipping,
            items: cartItems.length
              ? cartItems.map((c) => ({
                  name: c.name,
                  size: c.size,
                  qty: c.qty,
                  price: c.price,
                  print: c.customName ? `${c.customName} #${c.customNumber}` : ""
                }))
              : [{ name: "Real Madrid 26/27 Home Half Sleeve Jersey", size: "L", qty: 1, price: 1150, print: "" }]
          };
          savedOrders.unshift(newOrder);
          localStorage.setItem("rabez_orders_v1", JSON.stringify(savedOrders));

          // Security: Record order timestamp for rate limiter & fire Pixel Purchase event
          if (window.RABEZ_ANALYTICS) {
            window.RABEZ_ANALYTICS.recordOrderPlaced();
            window.RABEZ_ANALYTICS.trackPurchase(newOrder);
          }
        } catch (err) {}

        const successBox = document.querySelector(".js-order-success-banner");
        if (successBox) {
          successBox.style.display = "block";
          successBox.innerHTML = `
            <div style="background:#0A0C0B; color:#FFFFFF; border-left:4px solid #04F404; padding:22px; border-radius:6px; margin-bottom:24px;">
              <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:14px;">
                <div>
                  <span style="display:inline-block; background:#04F404; color:#080A09; font-weight:800; font-size:0.72rem; padding:3px 8px; border-radius:3px; margin-bottom:6px;">ORDER #${orderId}</span>
                  <h3 style="color:#04F404; font-size:1.35rem; margin-bottom:4px;">Order Received! (Cash on Delivery)</h3>
                  <p style="font-size:0.9rem; color:#D2D8D4;">Thank you <strong>${name}</strong> (${phone}). Our team will confirm your size before dispatch to <em>${address}</em>.</p>
                </div>
                <a href="track-order.html?order=${orderId}" class="rabez-btn rabez-btn--primary rabez-btn--sm">
                  Track Order &amp; Invoice &rarr;
                </a>
              </div>
            </div>
          `;
          successBox.scrollIntoView({ behavior: "smooth", block: "center" });
        }
      });
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    initHeaderWidget();
    initHeroShowcaseWidget();
    initJerseyCustomizerWidget();
    initSizeGuideWidget();
    initSingleProductControls();
  });
})();

