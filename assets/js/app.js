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
      const closeAllSubmenus = (scope) => {
        (scope || nav).querySelectorAll(".rabez-nav-item.is-submenu-open").forEach((li) => {
          li.classList.remove("is-submenu-open");
          const btn = li.querySelector(":scope > .rabez-nav-item__row > .js-nav-submenu-toggle");
          if (btn) btn.setAttribute("aria-expanded", "false");
        });
      };

      const toggleSubmenuItem = (li) => {
        if (!li) return;
        const willOpen = !li.classList.contains("is-submenu-open");
        const parentList = li.parentElement;
        if (parentList && willOpen) {
          parentList.querySelectorAll(":scope > .rabez-nav-item.is-submenu-open").forEach((sib) => {
            if (sib !== li) {
              sib.classList.remove("is-submenu-open");
              const sibBtn = sib.querySelector(":scope > .rabez-nav-item__row > .js-nav-submenu-toggle");
              if (sibBtn) sibBtn.setAttribute("aria-expanded", "false");
              closeAllSubmenus(sib);
            }
          });
        }
        li.classList.toggle("is-submenu-open", willOpen);
        if (!willOpen) closeAllSubmenus(li);
        const btn = li.querySelector(":scope > .rabez-nav-item__row > .js-nav-submenu-toggle");
        if (btn) btn.setAttribute("aria-expanded", String(willOpen));
      };

      const checkDropdownFlip = (li) => {
        if (window.innerWidth <= 1120) return;
        const dd = li.querySelector(":scope > .rabez-nav__dropdown");
        if (!dd) return;
        li.classList.remove("flip-left");
        const rect = dd.getBoundingClientRect();
        if (rect.right > window.innerWidth - 16) {
          li.classList.add("flip-left");
        }
      };

      toggle.addEventListener("click", () => {
        const isOpen = nav.classList.toggle("is-open");
        toggle.setAttribute("aria-expanded", String(isOpen));
        if (!isOpen) closeAllSubmenus();
      });

      nav.querySelectorAll(".js-nav-submenu-toggle").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          toggleSubmenuItem(btn.closest(".rabez-nav-item"));
        });
      });

      nav.querySelectorAll(".rabez-nav-item.has-children").forEach((li) => {
        li.addEventListener("mouseenter", () => checkDropdownFlip(li));
        li.addEventListener("focusin", () => checkDropdownFlip(li));
      });

      nav.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", (e) => {
          const isPlaceholder = link.dataset.navPlaceholder === "1" || link.getAttribute("href") === "#";
          const parentLi = link.closest(".rabez-nav-item");
          if (isPlaceholder && parentLi && parentLi.classList.contains("has-children")) {
            e.preventDefault();
            if (window.innerWidth <= 1120) {
              toggleSubmenuItem(parentLi);
            }
            return;
          }
          nav.classList.remove("is-open");
          toggle.setAttribute("aria-expanded", "false");
          closeAllSubmenus();
        });
      });

      document.addEventListener("click", (e) => {
        if (!e.target.closest(".rabez-header")) {
          if (nav.classList.contains("is-open")) {
            nav.classList.remove("is-open");
            toggle.setAttribute("aria-expanded", "false");
          }
          closeAllSubmenus();
        }
      });

      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
          nav.classList.remove("is-open");
          toggle.setAttribute("aria-expanded", "false");
          closeAllSubmenus();
        }
      });
    }

    // Topbar user dropdown toggle for touch & clicks
    const topDropdown = document.querySelector(".js-topbar-dropdown");
    if (topDropdown) {
      document.addEventListener("click", (e) => {
        if (e.target.closest(".js-topbar-dropdown-toggle")) {
          e.preventDefault();
          topDropdown.classList.toggle("is-open");
        } else if (!e.target.closest(".js-topbar-dropdown")) {
          topDropdown.classList.remove("is-open");
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
        if (kitSelect && prodId) {
          const matchingOpt = Array.from(kitSelect.options).find((o) => String(o.value) === String(prodId));
          if (matchingOpt) kitSelect.value = prodId;
        }
      });
    });

    addCustomBtn?.addEventListener("click", () => {
      const id = Number(kitSelect?.value || 0);
      if (!id) return;
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
            window.RABEZ_POPUP.alert(
              "অনুগ্রহ করে সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 01712345678 / 018... / 019...)",
              { type: "warning", title: "সঠিক মোবাইল নম্বর দিন", confirmText: "বুঝেছি" }
            ).then(() => {
              const phoneInput = codForm.querySelector('[name="customer_phone"]');
              if (phoneInput) phoneInput.focus();
            });
            return;
          }

          const rateCheck = window.RABEZ_ANALYTICS.canPlaceOrder();
          if (!rateCheck.allowed) {
            window.RABEZ_POPUP.alert(
              `অনুগ্রহ করে ${rateCheck.waitSeconds} সেকেন্ড অপেক্ষা করুন।`,
              { type: "warning", title: "কিছুক্ষণ অপেক্ষা করুন", confirmText: "ঠিক আছে" }
            );
            return;
          }
        }

        const name = window.RABEZ_ANALYTICS ? window.RABEZ_ANALYTICS.sanitizeInput(rawName) : rawName;
        const phone = window.RABEZ_ANALYTICS ? window.RABEZ_ANALYTICS.normalizeBDPhone(rawPhone) : rawPhone;
        const address = window.RABEZ_ANALYTICS ? window.RABEZ_ANALYTICS.sanitizeInput(rawAddress) : rawAddress;

        const cartItems = JSON.parse(localStorage.getItem("rabez_wc_cart_v1") || "[]");
        if (!cartItems.length) {
          window.RABEZ_POPUP.alert(
            "আপনার ব্যাগে কোনো জার্সি বা হুডি নেই! অনুগ্রহ করে শপ থেকে প্রোডাক্ট যুক্ত করুন।",
            { type: "info", title: "আপনার ব্যাগ খালি", confirmText: "শপে যান" }
          ).then(() => {
            window.location.href = "/shop";
          });
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
            coupon_code: (window.RABEZ_STORE && typeof window.RABEZ_STORE.getAppliedCoupon === 'function' && window.RABEZ_STORE.getAppliedCoupon()?.code) || (document.getElementById("checkout-coupon-code")?.value.trim().toUpperCase() || ""),
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
              window.RABEZ_POPUP.alert(
                data.message || (data.errors ? Object.values(data.errors).flat().join("\n") : "অর্ডার প্লেস করতে সমস্যা হয়েছে।"),
                { type: "danger", title: "অর্ডার সম্পন্ন হয়নি", confirmText: "ঠিক আছে" }
              );
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

            // Clear Cart & Coupon
            localStorage.removeItem("rabez_wc_cart_v1");
            localStorage.removeItem("rabez_applied_coupon_v1");
            if (window.RABEZ_STORE && typeof window.RABEZ_STORE.removeAppliedCoupon === 'function') {
              window.RABEZ_STORE.removeAppliedCoupon();
            }
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
            window.RABEZ_POPUP.alert(
              "সার্ভারে অর্ডার পাঠাতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।",
              { type: "danger", title: "সংযোগ সমস্যা", confirmText: "আবার চেষ্টা করুন" }
            );
          });
      });
    }
  }

  /* ==========================================================================
     RABEZ Mobile App Install & PWA Handling
     ========================================================================== */
  let deferredInstallPrompt = null;

  window.addEventListener("beforeinstallprompt", (e) => {
    e.preventDefault();
    deferredInstallPrompt = e;
    const pwaBtnText = document.getElementById("rabez-pwa-btn-text");
    if (pwaBtnText) {
      pwaBtnText.textContent = "Install App on Phone (1-Tap)";
    }
  });

  window.openAppInstallModal = function () {
    const modal = document.getElementById("rabez-install-modal");
    if (!modal) return;
    modal.classList.add("is-open");
    document.body.style.overflow = "hidden";

    // Detect iOS devices (Safari does not support beforeinstallprompt)
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    const iosGuide = document.getElementById("rabez-ios-guide");
    if (iosGuide) {
      iosGuide.style.display = isIOS ? "block" : "none";
    }
  };

  window.closeAppInstallModal = function () {
    const modal = document.getElementById("rabez-install-modal");
    if (!modal) return;
    modal.classList.remove("is-open");
    document.body.style.overflow = "";
  };

  window.installPWAApp = async function () {
    if (deferredInstallPrompt) {
      deferredInstallPrompt.prompt();
      try {
        const choice = await deferredInstallPrompt.userChoice;
        if (choice && choice.outcome === "accepted") {
          window.closeAppInstallModal();
        }
      } catch (err) {
        console.warn("PWA install error:", err);
      }
      deferredInstallPrompt = null;
    } else {
      const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
      if (isIOS) {
        const iosGuide = document.getElementById("rabez-ios-guide");
        if (iosGuide) {
          iosGuide.style.display = "block";
          iosGuide.scrollIntoView({ behavior: "smooth" });
        }
      } else {
        const apkBtn = document.getElementById("rabez-apk-dl-btn");
        if (apkBtn) {
          apkBtn.scrollIntoView({ behavior: "smooth" });
        } else {
          window.location.href = "/downloads/RABEZ-Official.apk";
        }
      }
    }
  };

  /* --------------------------------------------------------------------------
     Storefront Custom Alert & Confirmation Popup (window.RABEZ_POPUP)
  -------------------------------------------------------------------------- */
  let activePopupResolve = null;
  let lastPopupOpener = null;

  const POPUP_ICONS = {
    warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
    danger: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
    success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
    info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
  };

  function getOrCreatePopupDom() {
    let backdrop = document.getElementById("rabez-dialog-backdrop");
    if (!backdrop) {
      backdrop = document.createElement("div");
      backdrop.id = "rabez-dialog-backdrop";
      backdrop.className = "rabez-dialog-backdrop";
      backdrop.setAttribute("aria-hidden", "true");
      backdrop.innerHTML =
        '<div class="rabez-dialog" role="dialog" aria-modal="true" aria-labelledby="rabez-dialog-title" aria-describedby="rabez-dialog-msg">' +
          '<button type="button" class="rabez-dialog__close js-rabez-dialog-close" aria-label="Close">' +
            '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
          '</button>' +
          '<div class="rabez-dialog__icon rabez-dialog__icon--warning js-rabez-dialog-icon" aria-hidden="true"></div>' +
          '<h3 id="rabez-dialog-title" class="rabez-dialog__title js-rabez-dialog-title">Notification</h3>' +
          '<p id="rabez-dialog-msg" class="rabez-dialog__msg js-rabez-dialog-msg"></p>' +
          '<div class="rabez-dialog__actions">' +
            '<button type="button" class="rabez-dialog__btn rabez-dialog__btn--secondary js-rabez-dialog-cancel" hidden>Cancel</button>' +
            '<button type="button" class="rabez-dialog__btn rabez-dialog__btn--primary js-rabez-dialog-confirm">OK</button>' +
          '</div>' +
        '</div>';
      document.body.appendChild(backdrop);

      backdrop.addEventListener("click", (ev) => {
        if (ev.target === backdrop) closeRabezPopup(false);
      });
      backdrop.querySelector(".js-rabez-dialog-close").addEventListener("click", () => closeRabezPopup(false));
      backdrop.querySelector(".js-rabez-dialog-cancel").addEventListener("click", () => closeRabezPopup(false));
      backdrop.querySelector(".js-rabez-dialog-confirm").addEventListener("click", () => closeRabezPopup(true));
    }
    return {
      backdrop,
      icon: backdrop.querySelector(".js-rabez-dialog-icon"),
      title: backdrop.querySelector(".js-rabez-dialog-title"),
      msg: backdrop.querySelector(".js-rabez-dialog-msg"),
      cancelBtn: backdrop.querySelector(".js-rabez-dialog-cancel"),
      confirmBtn: backdrop.querySelector(".js-rabez-dialog-confirm")
    };
  }

  function closeRabezPopup(confirmed) {
    const backdrop = document.getElementById("rabez-dialog-backdrop");
    if (!backdrop || !backdrop.classList.contains("is-open")) return;
    backdrop.classList.remove("is-open");
    backdrop.setAttribute("aria-hidden", "true");
    const resolve = activePopupResolve;
    activePopupResolve = null;
    if (lastPopupOpener && typeof lastPopupOpener.focus === "function") {
      try { lastPopupOpener.focus(); } catch (e) {}
    }
    lastPopupOpener = null;
    if (resolve) resolve(Boolean(confirmed));
  }

  function openRabezPopup(message, opts = {}, mode = "alert") {
    if (activePopupResolve) {
      const prevResolve = activePopupResolve;
      activePopupResolve = null;
      prevResolve(false);
    }
    const dom = getOrCreatePopupDom();
    const type = ["warning", "danger", "success", "info"].includes(opts.type) ? opts.type : (mode === "confirm" ? "warning" : "info");
    const defaultTitles = { warning: "সতর্কতা", danger: "ত্রুটি", success: "সফল হয়েছে", info: "বার্তা" };

    dom.icon.className = "rabez-dialog__icon js-rabez-dialog-icon rabez-dialog__icon--" + type;
    dom.icon.innerHTML = POPUP_ICONS[type] || POPUP_ICONS.info;
    dom.title.textContent = opts.title || defaultTitles[type] || "RABEZ";
    dom.msg.textContent = message == null ? "" : String(message);

    const confirmBtnClass = type === "danger" ? "rabez-dialog__btn--danger" : "rabez-dialog__btn--primary";
    dom.confirmBtn.className = "rabez-dialog__btn js-rabez-dialog-confirm " + confirmBtnClass;
    dom.confirmBtn.textContent = opts.confirmText || (mode === "confirm" ? "নিশ্চিত করুন" : "ঠিক আছে");

    if (mode === "confirm") {
      dom.cancelBtn.hidden = false;
      dom.cancelBtn.textContent = opts.cancelText || "বাতিল";
    } else {
      dom.cancelBtn.hidden = true;
    }

    lastPopupOpener = document.activeElement;
    dom.backdrop.classList.add("is-open");
    dom.backdrop.setAttribute("aria-hidden", "false");

    return new Promise((resolve) => {
      activePopupResolve = resolve;
      requestAnimationFrame(() => {
        dom.confirmBtn.focus();
      });
    });
  }

  window.RABEZ_POPUP = {
    alert: (message, opts = {}) => openRabezPopup(message, opts, "alert"),
    confirm: (message, opts = {}) => openRabezPopup(message, opts, "confirm"),
    close: () => closeRabezPopup(false)
  };

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeRabezPopup(false);
      window.closeAppInstallModal();
    }
  });

  document.addEventListener("DOMContentLoaded", () => {
    initHeaderWidget();
    initHeroShowcaseWidget();
    initJerseyCustomizerWidget();
    initSizeGuideWidget();
    initSingleProductControls();
  });
})();

