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

        const cartItems = JSON.parse(localStorage.getItem("rabez_wc_cart_v1") || "[]");
        if (!cartItems.length) {
          alert("আপনার ব্যাগে কোনো জার্সি বা হুডি নেই! অনুগ্রহ করে শপ থেকে প্রোডাক্ট যুক্ত করুন।");
          window.location.href = "/shop";
          return;
        }

        const submitBtn = codForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : "Confirm Order";
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = "অর্ডার প্রসেসিং হচ্ছে...";
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        fetch("/checkout", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken || ""
          },
          body: JSON.stringify({
            customer_name: name,
            customer_phone: phone,
            customer_address: address,
            shipping_zone: shipping,
            cart_items: cartItems.map((c) => ({
              id: c.id,
              name: c.name,
              size: c.size,
              qty: c.qty,
              price: c.price,
              custom_name: c.customName || "",
              custom_number: c.customNumber || "",
              image: c.image || ""
            }))
          })
        })
          .then((res) => res.json())
          .then((data) => {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = originalBtnText;
            }

            if (!data.success) {
              alert(data.message || (data.errors ? Object.values(data.errors).flat().join("\n") : "অর্ডার প্লেস করতে সমস্যা হয়েছে।"));
              return;
            }

            const orderId = data.order_id;
            const subtotal = cartItems.reduce((s, i) => s + i.price * i.qty, 0);

            // Record locally for fallback & offline
            const savedOrders = JSON.parse(localStorage.getItem("rabez_orders_v1") || "[]");
            const newOrder = {
              id: orderId,
              date: "Just Now",
              customer: name,
              phone: phone,
              address: address,
              zone: shipping === 70 ? "Inside Dhaka (৳70)" : "Outside Dhaka (৳130)",
              statusStep: 1,
              statusLabel: "Order Received — Pending Confirmation",
              courier: "Steadfast Courier",
              consignmentId: "Pending Dispatch",
              subtotal: subtotal,
              shipping: shipping,
              total: subtotal + shipping,
              items: cartItems.map((c) => ({
                name: c.name,
                size: c.size,
                qty: c.qty,
                price: c.price,
                print: c.customName ? `${c.customName} #${c.customNumber}` : ""
              }))
            };
            savedOrders.unshift(newOrder);
            localStorage.setItem("rabez_orders_v1", JSON.stringify(savedOrders));

            // Clear Cart
            localStorage.removeItem("rabez_wc_cart_v1");
            document.querySelectorAll(".js-cart-count").forEach((el) => (el.textContent = "0"));

            // Analytics: Meta Pixel Purchase event
            if (window.RABEZ_ANALYTICS) {
              window.RABEZ_ANALYTICS.recordOrderPlaced();
              window.RABEZ_ANALYTICS.trackPurchase({
                id: orderId,
                total: subtotal + shipping,
                eventId: data.event_id,
                items: cartItems
              });
            }

            // Success UI
            const successBox = document.querySelector(".js-order-success-banner");
            if (successBox) {
              successBox.style.display = "block";
              successBox.innerHTML = `
                <div style="background:#0A0C0B; color:#FFFFFF; border-left:4px solid #04F404; padding:22px; border-radius:6px; margin-bottom:24px;">
                  <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:14px;">
                    <div>
                      <span style="display:inline-block; background:#04F404; color:#080A09; font-weight:800; font-size:0.72rem; padding:3px 8px; border-radius:3px; margin-bottom:6px;">ORDER #${orderId}</span>
                      <h3 style="color:#04F404; font-size:1.35rem; margin-bottom:4px;">Order Received! (Cash on Delivery)</h3>
                      <p style="font-size:0.9rem; color:#D2D8D4;">ধন্যবাদ <strong>${name}</strong> (${phone})। আপনার অর্ডারটি ডাটাবেসে সফলভাবে যুক্ত হয়েছে। ডেলিভারি ঠিকানা: <em>${address}</em>।</p>
                    </div>
                    <a href="/track-order?order=${orderId}" class="rabez-btn rabez-btn--primary rabez-btn--sm">
                      Track Order &amp; Invoice &rarr;
                    </a>
                  </div>
                </div>
              `;
              successBox.scrollIntoView({ behavior: "smooth", block: "center" });
            }
          })
          .catch((err) => {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = originalBtnText;
            }
            console.error("Order error:", err);
            alert("সার্ভারে অর্ডার পাঠাতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।");
          });
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

