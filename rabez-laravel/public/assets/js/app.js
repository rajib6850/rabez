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

    const deck = stage.querySelector(".rabez-hero-deck");
    const cards = Array.from(stage.querySelectorAll(".js-hero-deck-card"));
    const dockBtns = Array.from(stage.querySelectorAll(".js-hero-dock-btn"));
    const total = cards.length;
    if (!total) return;

    let currentIndex = 0;
    let autoTimer = null;
    let isDragging = false;
    let hasDragged = false;
    let justDragged = false;
    let startX = 0;
    let startY = 0;
    let lastDx = 0;

    stage.querySelectorAll("img").forEach((img) => {
      img.setAttribute("draggable", "false");
    });

    function clearInlineTransforms() {
      cards.forEach((c) => {
        c.style.transform = "";
      });
    }

    function setActiveSlide(index) {
      clearInlineTransforms();
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
        if (justDragged) return;
        if (idx !== currentIndex) {
          setActiveSlide(idx);
          startAutoPlay();
        } else {
          // Clicking the center spotlight jersey opens Quick View for instant size selection
          const prodId = Number(card.dataset.productId || 1324);
          if (window.RABEZ_STORE && typeof window.RABEZ_STORE.openQuickView === "function") {
            window.RABEZ_STORE.openQuickView(prodId);
          } else {
            window.location.href = `/product/${prodId}`;
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

    if (deck) {
      const startDrag = (clientX, clientY) => {
        isDragging = true;
        hasDragged = false;
        startX = clientX;
        startY = clientY;
        lastDx = 0;
        stopAutoPlay();
      };

      const moveDrag = (clientX, clientY) => {
        if (!isDragging) return;
        const dx = clientX - startX;
        const dy = clientY - startY;

        if (!hasDragged && Math.abs(dy) > Math.abs(dx) && Math.abs(dy) > 10) {
          isDragging = false;
          deck.classList.remove("is-dragging");
          clearInlineTransforms();
          startAutoPlay();
          return;
        }

        if (Math.abs(dx) > 6) {
          hasDragged = true;
          lastDx = dx;
          deck.classList.add("is-dragging");
          const clamped = Math.max(-145, Math.min(145, dx));
          const activeCard = cards[currentIndex];
          if (activeCard) {
            activeCard.style.transform = `translateX(${clamped}px) translateY(-6px) rotate(${(clamped * 0.055).toFixed(2)}deg) scale(1)`;
          }
        }
      };

      const endDrag = () => {
        if (!isDragging) return;
        isDragging = false;
        deck.classList.remove("is-dragging");
        clearInlineTransforms();

        if (hasDragged) {
          justDragged = true;
          window.setTimeout(() => { justDragged = false; }, 140);
          if (lastDx < -42) {
            setActiveSlide(currentIndex + 1);
          } else if (lastDx > 42) {
            setActiveSlide(currentIndex - 1);
          }
        }
        startAutoPlay();
      };

      if (window.PointerEvent) {
        deck.addEventListener("pointerdown", (e) => {
          if (e.pointerType === "mouse" && e.button !== 0) return;
          startDrag(e.clientX, e.clientY);
        });
        window.addEventListener("pointermove", (e) => moveDrag(e.clientX, e.clientY));
        window.addEventListener("pointerup", endDrag);
        window.addEventListener("pointercancel", endDrag);
      } else {
        deck.addEventListener("touchstart", (e) => {
          if (!e.touches.length) return;
          startDrag(e.touches[0].clientX, e.touches[0].clientY);
        }, { passive: true });
        deck.addEventListener("touchmove", (e) => {
          if (!e.touches.length) return;
          moveDrag(e.touches[0].clientX, e.touches[0].clientY);
        }, { passive: true });
        deck.addEventListener("touchend", endDrag);
        deck.addEventListener("touchcancel", endDrag);
      }
    }

    stage.setAttribute("tabindex", "0");
    stage.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft") {
        e.preventDefault();
        setActiveSlide(currentIndex - 1);
        startAutoPlay();
      } else if (e.key === "ArrowRight") {
        e.preventDefault();
        setActiveSlide(currentIndex + 1);
        startAutoPlay();
      }
    });

    stage.addEventListener("mouseenter", stopAutoPlay);
    stage.addEventListener("mouseleave", startAutoPlay);

    setActiveSlide(0);
    startAutoPlay();
  }

  /* 3. Interactive Jersey Back Name & Number Customizer Studio (Homepage) */
  function initJerseyCustomizerWidget() {
    const wrap = document.querySelector(".js-jersey-customizer");
    if (!wrap) return;

    const printFee = Number(wrap.dataset.printFee || window.RABEZ_CUSTOM_PRINT_FEE || 250);

    const nameInput = wrap.querySelector(".js-cust-name");
    const numInput = wrap.querySelector(".js-cust-number");
    const kitSelect = wrap.querySelector(".js-cust-kit");
    const sizeSelect = wrap.querySelector(".js-cust-size");
    const nameCount = wrap.querySelector(".js-cust-name-count");
    const numCount = wrap.querySelector(".js-cust-num-count");
    const clearNameBtn = wrap.querySelector(".js-cust-clear-name");
    const clearNumBtn = wrap.querySelector(".js-cust-clear-num");
    const errorBox = wrap.querySelector(".js-cust-error");

    const previewName = wrap.querySelector(".js-preview-name");
    const previewNum = wrap.querySelector(".js-preview-num");
    const svgGradTop = wrap.querySelector(".js-svg-grad-top");
    const svgGradBot = wrap.querySelector(".js-svg-grad-bot");
    const svgTrims = wrap.querySelectorAll(".js-svg-jersey-trim");
    const svgLongSleeves = wrap.querySelector(".js-svg-long-sleeves");
    const svgShortCuffs = wrap.querySelector(".js-svg-short-cuffs");
    const stageEl = wrap.querySelector(".rabez-home-studio__stage");
    const stageSleeveLabel = wrap.querySelector(".js-home-stage-sleeve");
    const resetBtn = wrap.querySelector(".js-home-reset-btn");

    const kitCards = wrap.querySelectorAll(".js-kit-theme-swatch");
    const clubFilterBtns = wrap.querySelectorAll(".js-home-club-filter");
    const kitRail = wrap.querySelector(".js-home-kit-rail");
    const railPrevBtn = wrap.querySelector(".js-home-rail-prev");
    const railNextBtn = wrap.querySelector(".js-home-rail-next");
    const presetBtns = wrap.querySelectorAll(".js-home-preset-pill");
    const colorBtns = wrap.querySelectorAll(".js-home-color-btn");
    const colorNameLabel = wrap.querySelector(".js-home-color-name");
    const contrastWarn = wrap.querySelector(".js-home-contrast-warn");
    const sizePillsWrap = wrap.querySelector(".js-home-size-pills");
    const qtyMinusBtn = wrap.querySelector(".js-home-qty-minus");
    const qtyPlusBtn = wrap.querySelector(".js-home-qty-plus");
    const qtyValEl = wrap.querySelector(".js-home-qty-val");

    const addCustomBtn = wrap.querySelector(".js-add-custom-kit");
    const waOrderBtn = wrap.querySelector(".js-home-wa-order");

    const summaryImg = wrap.querySelector(".js-home-summary-img");
    const summaryName = wrap.querySelector(".js-home-summary-name");
    const summaryMeta = wrap.querySelector(".js-home-summary-meta");
    const summaryLink = wrap.querySelector(".js-home-summary-link");
    const summaryBreakdown = wrap.querySelector(".js-home-price-breakdown");
    const summaryTotal = wrap.querySelector(".js-home-summary-total");

    const state = {
      productId: 0,
      size: "L",
      qty: 1,
      colorHex: "#111111",
      colorName: "Black",
      defaultColorHex: "#111111",
      defaultColorName: "Black",
      bgTop: "#F8FAF9",
      bgBot: "#E5ECE8",
      trim: "#006A4E",
      isLightKit: true,
      sleeve: "half-sleeve",
      sleeveLabel: "Half Sleeve",
      basePrice: 1150,
      name: "Real Madrid 26/27 Home Jersey",
      slug: "",
      sku: "RM-HOME-26",
      image: "/assets/images/products/real-madrid-home-hs.jpg",
      product: null,
      activeVariant: null,
    };

    function getProductsCatalog() {
      return Array.isArray(window.RABEZ_PRODUCTS) ? window.RABEZ_PRODUCTS : [];
    }

    function findCatalogProduct(id) {
      return getProductsCatalog().find((p) => Number(p.id) === Number(id)) || null;
    }

    function hexLuminance(hex) {
      const clean = String(hex || "").replace("#", "");
      if (clean.length !== 6) return 0.5;
      const r = parseInt(clean.slice(0, 2), 16) / 255;
      const g = parseInt(clean.slice(2, 4), 16) / 255;
      const b = parseInt(clean.slice(4, 6), 16) / 255;
      return 0.2126 * r + 0.7152 * g + 0.0722 * b;
    }

    function showError(msg) {
      if (!errorBox) return;
      if (msg) {
        errorBox.textContent = msg;
        errorBox.style.display = "block";
      } else {
        errorBox.textContent = "";
        errorBox.style.display = "none";
      }
    }

    function resolveActiveVariant() {
      const prod = state.product;
      if (!prod || !Array.isArray(prod.variants) || !prod.variants.length) {
        state.activeVariant = null;
        return;
      }
      const match = prod.variants.find(
        (v) =>
          String(v.size || v.title || "").trim().toLowerCase() === String(state.size || "").trim().toLowerCase() ||
          String(v.title || "").trim().toLowerCase() === String(state.size || "").trim().toLowerCase()
      );
      state.activeVariant = match || null;
      if (match && match.price) {
        state.basePrice = Number(match.price);
      } else if (prod.price) {
        state.basePrice = Number(prod.price);
      }
    }

    function sanitizeFields() {
      const rawName = (nameInput ? nameInput.value : "").toUpperCase();
      const cleanName = rawName.replace(/[^A-Z0-9 .\-]/g, "").slice(0, 14);
      if (nameInput && nameInput.value !== cleanName) nameInput.value = cleanName;

      const rawNum = numInput ? numInput.value : "";
      const cleanNum = rawNum.replace(/[^0-9]/g, "").slice(0, 2);
      if (numInput && numInput.value !== cleanNum) numInput.value = cleanNum;

      if (cleanName || cleanNum) showError("");

      if (nameCount) nameCount.textContent = `${cleanName.length}/14`;
      if (numCount) numCount.textContent = `${cleanNum.length}/2`;

      if (clearNameBtn) clearNameBtn.style.display = cleanName.length > 0 ? "inline-flex" : "none";
      if (clearNumBtn) clearNumBtn.style.display = cleanNum.length > 0 ? "inline-flex" : "none";

      if (previewName) {
        previewName.textContent = cleanName || "YOUR NAME";
        previewName.style.opacity = cleanName ? "1" : "0.45";
        previewName.classList.remove("is-len-md", "is-len-lg");
        if (cleanName.length >= 12) {
          previewName.classList.add("is-len-lg");
        } else if (cleanName.length >= 9) {
          previewName.classList.add("is-len-md");
        }
      }
      if (previewNum) {
        previewNum.textContent = cleanNum || "00";
        previewNum.style.opacity = cleanNum ? "1" : "0.45";
      }

      presetBtns.forEach((pill) => {
        const pName = (pill.dataset.name || "").toUpperCase();
        const pNum = String(pill.dataset.num || "");
        const isMatch = Boolean(cleanName && cleanName === pName && cleanNum === pNum);
        pill.classList.toggle("is-active", isMatch);
      });

      return { cleanName, cleanNum };
    }

    function syncStageVisuals() {
      if (svgGradTop) svgGradTop.setAttribute("stop-color", state.bgTop);
      if (svgGradBot) svgGradBot.setAttribute("stop-color", state.bgBot);
      svgTrims.forEach((el) => el.setAttribute("stroke", state.trim));
      if (stageEl) stageEl.style.setProperty("--stage-glow", state.trim);

      if (svgLongSleeves) {
        svgLongSleeves.style.display = state.sleeve === "full-sleeve" ? "block" : "none";
      }
      if (svgShortCuffs) {
        svgShortCuffs.style.display = state.sleeve === "full-sleeve" ? "none" : "block";
      }
      if (stageSleeveLabel) {
        stageSleeveLabel.textContent = state.sleeveLabel || "Half Sleeve";
      }

      const textShadow = state.colorHex.toUpperCase() === "#111111"
        ? "0 1px 2px rgba(255,255,255,0.35)"
        : "0 2px 6px rgba(0,0,0,0.65)";

      if (previewName) {
        previewName.style.color = state.colorHex;
        previewName.style.textShadow = textShadow;
      }
      if (previewNum) {
        previewNum.style.color = state.colorHex;
        previewNum.style.textShadow = textShadow;
      }

      if (colorNameLabel) colorNameLabel.textContent = state.colorName;
      colorBtns.forEach((btn) => {
        btn.classList.toggle("is-active", btn.dataset.colorName === state.colorName);
      });

      if (contrastWarn) {
        const lum = hexLuminance(state.bgTop);
        const lowContrast = (lum > 0.65 && state.colorName === "White") || (lum < 0.12 && state.colorName === "Black");
        contrastWarn.style.display = lowContrast ? "block" : "none";
      }
    }

    function syncSummaryAndPricing() {
      resolveActiveVariant();
      const unitBase = Number(state.basePrice) || 1150;
      const unitTotal = unitBase + printFee;
      const grandTotal = unitTotal * state.qty;

      if (qtyValEl) qtyValEl.textContent = String(state.qty);
      const currentBtnPrice = wrap.querySelector(".js-home-btn-price");
      if (currentBtnPrice) currentBtnPrice.textContent = `৳ ${grandTotal.toLocaleString("en-IN")}`;

      if (summaryImg) {
        const imgUrl = (state.activeVariant && state.activeVariant.image) ? state.activeVariant.image : state.image;
        summaryImg.src = imgUrl;
        summaryImg.alt = state.name;
      }
      if (summaryName) summaryName.textContent = state.name;
      if (summaryMeta) {
        summaryMeta.textContent = `Size ${state.size} • ${state.colorName} Vinyl • Qty ${state.qty}`;
      }
      if (summaryLink) {
        summaryLink.href = `/product/${state.slug || state.productId}`;
      }
      if (summaryBreakdown) {
        summaryBreakdown.textContent = state.qty > 1
          ? `${state.qty} × (৳${unitBase.toLocaleString("en-IN")} + ৳${printFee})`
          : `Kit ৳${unitBase.toLocaleString("en-IN")} + Print ৳${printFee}`;
      }
      if (summaryTotal) {
        summaryTotal.textContent = `৳ ${grandTotal.toLocaleString("en-IN")}`;
      }
    }

    function renderSizePills() {
      const prod = state.product;
      const fallbackSizes = state.sleeve === "footwear"
        ? ["39", "40", "41", "42", "43", "44"]
        : ["S", "M", "L", "XL", "XXL"];

      const allSizes = (prod && Array.isArray(prod.allSizes) && prod.allSizes.length)
        ? prod.allSizes
        : fallbackSizes;
      const inStockSizes = (prod && Array.isArray(prod.sizes))
        ? prod.sizes
        : allSizes;

      if (!inStockSizes.includes(state.size)) {
        state.size = (prod && prod.defaultSize && inStockSizes.includes(prod.defaultSize))
          ? prod.defaultSize
          : (inStockSizes[0] || allSizes[0] || "L");
      }

      if (sizePillsWrap) {
        sizePillsWrap.innerHTML = allSizes.map((sz) => {
          const available = inStockSizes.length === 0 || inStockSizes.includes(sz);
          const active = sz === state.size && available;
          return `<button type="button" class="rabez-home-size-pill js-home-size-pill ${active ? "is-active" : ""}" data-size="${sz}" ${!available ? "disabled" : ""}>${sz}</button>`;
        }).join("");

        sizePillsWrap.querySelectorAll(".js-home-size-pill").forEach((pill) => {
          pill.addEventListener("click", () => {
            if (pill.disabled) return;
            state.size = pill.dataset.size || "L";
            sizePillsWrap.querySelectorAll(".js-home-size-pill").forEach((p) => {
              p.classList.toggle("is-active", p === pill);
            });
            if (sizeSelect) sizeSelect.value = state.size;
            syncSummaryAndPricing();
          });
        });
      }

      if (sizeSelect) {
        sizeSelect.innerHTML = allSizes.map((sz) => `<option value="${sz}">${sz}</option>`).join("");
        sizeSelect.value = state.size;
      }
    }

    function selectKit(card, applyRecommendedColor = true) {
      if (!card) return;
      kitCards.forEach((c) => {
        const isTarget = c === card;
        c.classList.toggle("is-active", isTarget);
        c.setAttribute("aria-selected", isTarget ? "true" : "false");
      });

      const prodId = Number(card.dataset.productId || 0);
      const catalogProd = findCatalogProduct(prodId);

      state.productId = prodId;
      state.product = catalogProd;
      state.bgTop = card.dataset.bg || "#1A221E";
      state.bgBot = card.dataset.bgEnd || state.bgTop;
      state.trim = card.dataset.trim || "#04F404";
      state.isLightKit = card.dataset.isLight === "1";
      state.sleeve = card.dataset.sleeve || catalogProd?.sleeve || "half-sleeve";
      state.sleeveLabel = card.dataset.sleeveLabel || catalogProd?.sleeveLabel || "Half Sleeve";
      state.basePrice = Number(catalogProd?.price ?? card.dataset.price ?? 1150);
      state.name = catalogProd?.name || card.dataset.name || "Custom Jersey";
      state.slug = catalogProd?.slug || card.dataset.slug || String(prodId);
      state.sku = catalogProd?.sku || card.dataset.sku || `RBZ-${prodId}`;
      state.image = catalogProd?.image || card.dataset.image || "/assets/images/products/real-madrid-home-hs.jpg";

      state.defaultColorHex = card.dataset.text || "#FFFFFF";
      state.defaultColorName = card.dataset.colorName || "White";
      if (applyRecommendedColor) {
        state.colorHex = state.defaultColorHex;
        state.colorName = state.defaultColorName;
      }

      if (kitSelect && prodId) {
        const matchingOpt = Array.from(kitSelect.options).find((o) => String(o.value) === String(prodId));
        if (matchingOpt) kitSelect.value = String(prodId);
      }

      renderSizePills();
      syncStageVisuals();
      syncSummaryAndPricing();
    }

    // Bind Kit Cards
    kitCards.forEach((card) => {
      card.addEventListener("click", () => selectKit(card, true));
    });

    // Club Filter Tabs
    clubFilterBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        const targetClub = btn.dataset.club || "all";
        clubFilterBtns.forEach((b) => b.classList.toggle("is-active", b === btn));
        kitCards.forEach((card) => {
          const matches = targetClub === "all" || card.dataset.club === targetClub;
          card.style.display = matches ? "flex" : "none";
        });
        if (kitRail) kitRail.scrollTo({ left: 0, behavior: "smooth" });
      });
    });

    // Rail Scroll Controls
    railPrevBtn?.addEventListener("click", () => {
      kitRail?.scrollBy({ left: -240, behavior: "smooth" });
    });
    railNextBtn?.addEventListener("click", () => {
      kitRail?.scrollBy({ left: 240, behavior: "smooth" });
    });

    // Legend 1-Click Presets
    presetBtns.forEach((pill) => {
      pill.addEventListener("click", () => {
        if (nameInput) nameInput.value = pill.dataset.name || "";
        if (numInput) numInput.value = pill.dataset.num || "";
        sanitizeFields();
      });
    });

    // Vinyl Color Swatches
    colorBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        state.colorHex = btn.dataset.color || "#FFFFFF";
        state.colorName = btn.dataset.colorName || "White";
        syncStageVisuals();
        syncSummaryAndPricing();
      });
    });

    // Clear Buttons
    clearNameBtn?.addEventListener("click", () => {
      if (nameInput) {
        nameInput.value = "";
        nameInput.focus();
      }
      sanitizeFields();
    });
    clearNumBtn?.addEventListener("click", () => {
      if (numInput) {
        numInput.value = "";
        numInput.focus();
      }
      sanitizeFields();
    });

    // Input Listeners
    nameInput?.addEventListener("input", sanitizeFields);
    numInput?.addEventListener("input", sanitizeFields);

    // Qty Stepper (1 - 10)
    qtyMinusBtn?.addEventListener("click", () => {
      state.qty = Math.max(1, state.qty - 1);
      syncSummaryAndPricing();
    });
    qtyPlusBtn?.addEventListener("click", () => {
      state.qty = Math.min(10, state.qty + 1);
      syncSummaryAndPricing();
    });

    // Reset Preview
    resetBtn?.addEventListener("click", () => {
      if (nameInput) nameInput.value = "RABEZ";
      if (numInput) numInput.value = "10";
      state.qty = 1;
      state.colorHex = state.defaultColorHex;
      state.colorName = state.defaultColorName;
      showError("");
      sanitizeFields();
      syncStageVisuals();
      syncSummaryAndPricing();
    });

    // Add to Bag CTA
    addCustomBtn?.addEventListener("click", () => {
      const { cleanName, cleanNum } = sanitizeFields();
      if (!cleanName && !cleanNum) {
        showError("Please enter a Back Name or Squad Number to customize.");
        nameInput?.focus();
        return;
      }
      if (state.product && state.product.hasCustomPrint === false) {
        showError("This item is not eligible for custom back printing.");
        return;
      }
      if (state.product && Array.isArray(state.product.sizes) && state.product.sizes.length === 0) {
        showError("Sorry, this jersey is currently out of stock.");
        return;
      }

      resolveActiveVariant();
      const variantId = state.activeVariant ? state.activeVariant.id : null;
      const variantTitle = state.activeVariant ? state.activeVariant.title : state.size;
      const finalSku = (state.activeVariant && state.activeVariant.sku) ? state.activeVariant.sku : state.sku;
      const finalImage = (state.activeVariant && state.activeVariant.image) ? state.activeVariant.image : state.image;

      if (window.RABEZ_STORE) {
        window.RABEZ_STORE.addToCart({
          id: state.productId,
          sku: finalSku,
          name: state.name,
          price: state.basePrice,
          variantId: variantId,
          variantTitle: variantTitle,
          image: finalImage,
          size: state.size,
          qty: state.qty,
          customName: cleanName,
          customNumber: cleanNum,
          customColor: state.colorName,
        });
        if (typeof window.RABEZ_STORE.openCartDrawer === "function") {
          window.RABEZ_STORE.openCartDrawer();
        }
      }

      if (addCustomBtn && !addCustomBtn.dataset.feedbackActive) {
        addCustomBtn.dataset.feedbackActive = "1";
        const origHtml = addCustomBtn.innerHTML;
        addCustomBtn.innerHTML = `<span>&#10003; Added to Bag!</span>`;
        setTimeout(() => {
          addCustomBtn.innerHTML = origHtml;
          delete addCustomBtn.dataset.feedbackActive;
          syncSummaryAndPricing();
        }, 1600);
      }
    });

    // Direct WhatsApp Order CTA
    waOrderBtn?.addEventListener("click", () => {
      const { cleanName, cleanNum } = sanitizeFields();
      if (!cleanName && !cleanNum) {
        showError("Please enter a Back Name or Squad Number before ordering.");
        nameInput?.focus();
        return;
      }
      resolveActiveVariant();
      const finalSku = (state.activeVariant && state.activeVariant.sku) ? state.activeVariant.sku : state.sku;
      const totalAmount = (state.basePrice + printFee) * state.qty;
      const printText = [cleanName, cleanNum ? `#${cleanNum}` : ""].filter(Boolean).join(" ");

      let msg = `Hello RABEZ! I want to order a Custom Jersey:\n\n`;
      msg += `Product: ${state.name}\n`;
      msg += `SKU: ${finalSku}\n`;
      msg += `Size: ${state.size}\n`;
      msg += `Quantity: ${state.qty}\n`;
      msg += `Custom Print: ${printText} (${state.colorName} Vinyl) (+৳${printFee})\n`;
      msg += `Total Amount: ৳ ${totalAmount.toLocaleString("en-IN")}\n`;
      msg += `\nPlease confirm my order!`;

      const waUrl = `https://api.whatsapp.com/send/?phone=8801612359652&text=${encodeURIComponent(msg)}`;
      window.open(waUrl, "_blank", "noopener");
    });

    // Initialize with active or first kit card
    const initialCard = wrap.querySelector(".js-kit-theme-swatch.is-active") || kitCards[0];
    if (initialCard) {
      selectKit(initialCard, true);
    }
    sanitizeFields();
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
      const initialCart = (window.RABEZ_STORE && typeof window.RABEZ_STORE.getCart === "function")
        ? window.RABEZ_STORE.getCart()
        : JSON.parse(localStorage.getItem("rabez_wc_cart_v1") || "[]");
      if (initialCart.length > 0 && window.RABEZ_ANALYTICS && typeof window.RABEZ_ANALYTICS.trackInitiateCheckout === "function") {
        const initialSubtotal = initialCart.reduce((s, i) => s + (Number(i.price) || 0) * (Number(i.qty) || 1), 0);
        window.RABEZ_ANALYTICS.trackInitiateCheckout(initialCart, initialSubtotal);
      }

      codForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const rawName = codForm.querySelector('[name="customer_name"]')?.value || "Customer";
        const rawPhone = codForm.querySelector('[name="customer_phone"]')?.value || "";
        const rawAddress = codForm.querySelector('[name="customer_address"]')?.value || "";
        const zoneInput = codForm.querySelector('input[name="shipping_zone"]:checked') || codForm.querySelector('input[name="shipping_zone"][type="hidden"]');
        const shipping = zoneInput ? Number(zoneInput.value) : 130;

        // Security: Fraud & Anti-Spam Checks
        if (window.RABEZ_ANALYTICS) {
          if (!window.RABEZ_ANALYTICS.isValidBDPhone(rawPhone)) {
            window.RABEZ_POPUP.alert(
              "Please enter a valid 11-digit Bangladesh mobile number (e.g. 01712345678).",
              { type: "warning", title: "Invalid Phone Number", confirmText: "Got It" }
            ).then(() => {
              const phoneInput = codForm.querySelector('[name="customer_phone"]');
              if (phoneInput) phoneInput.focus();
            });
            return;
          }

          const rateCheck = window.RABEZ_ANALYTICS.canPlaceOrder();
          if (!rateCheck.allowed) {
            window.RABEZ_POPUP.alert(
              `Please wait ${rateCheck.waitSeconds} seconds before placing another order.`,
              { type: "warning", title: "Please Wait", confirmText: "OK" }
            );
            return;
          }
        }

        const name = window.RABEZ_ANALYTICS ? window.RABEZ_ANALYTICS.sanitizeInput(rawName) : rawName;
        const phone = window.RABEZ_ANALYTICS ? window.RABEZ_ANALYTICS.normalizeBDPhone(rawPhone) : rawPhone;
        const address = window.RABEZ_ANALYTICS ? window.RABEZ_ANALYTICS.sanitizeInput(rawAddress) : rawAddress;

        const cartItems = (window.RABEZ_STORE && typeof window.RABEZ_STORE.getCart === "function")
          ? window.RABEZ_STORE.getCart()
          : JSON.parse(localStorage.getItem("rabez_wc_cart_v1") || "[]");
        if (!cartItems.length) {
          window.RABEZ_POPUP.alert(
            "Your bag is empty! Please add a jersey or hoodie from the shop first.",
            { type: "info", title: "Your Bag is Empty", confirmText: "Browse Shop" }
          ).then(() => {
            window.location.href = "/shop";
          });
          return;
        }

        const submitBtn = codForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : "Confirm Order";
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = "Processing Order...";
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
              variant_id: c.variantId || null,
              variant_title: c.variantTitle || c.size || "",
              qty: c.qty,
              price: c.price,
              custom_name: c.customName || "",
              custom_number: c.customNumber || "",
              custom_color: c.customColor || "",
              image: c.image || ""
            }))
          })
        })
          .then((res) => res.json())
          .then((data) => {
            if (!data.success) {
              if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
              }
              window.RABEZ_POPUP.alert(
                data.message || (data.errors ? Object.values(data.errors).flat().join("\n") : "Could not place your order. Please check your details."),
                { type: "danger", title: "Order Failed", confirmText: "OK" }
              );
              return;
            }

            const orderId = data.order_id;
            const subtotal = cartItems.reduce((s, i) => s + i.price * i.qty, 0);
            const finalTotal = Number(data.total) || (subtotal + shipping);

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
              total: finalTotal,
              items: cartItems.map((c) => ({
                name: c.name,
                size: c.variantTitle || c.size,
                qty: c.qty,
                price: c.price,
                print: c.customName ? `${c.customName} #${c.customNumber}` : ""
              }))
            };
            savedOrders.unshift(newOrder);
            localStorage.setItem("rabez_orders_v1", JSON.stringify(savedOrders));

            // Clear Cart & Coupon
            if (window.RABEZ_STORE && typeof window.RABEZ_STORE.clearCart === 'function') {
              window.RABEZ_STORE.clearCart();
            } else {
              localStorage.setItem("rabez_wc_cart_v1", "[]");
            }
            localStorage.removeItem("rabez_applied_coupon_v1");
            if (window.RABEZ_STORE && typeof window.RABEZ_STORE.removeAppliedCoupon === 'function') {
              window.RABEZ_STORE.removeAppliedCoupon();
            }
            document.querySelectorAll(".js-cart-count").forEach((el) => (el.textContent = "0"));
            document.querySelectorAll(".js-cart-subtotal").forEach((el) => (el.textContent = "৳ 0"));

            // Analytics: Meta Pixel Purchase event
            if (window.RABEZ_ANALYTICS) {
              window.RABEZ_ANALYTICS.recordOrderPlaced();
              window.RABEZ_ANALYTICS.trackPurchase({
                id: orderId,
                total: finalTotal,
                eventId: data.event_id,
                items: cartItems
              });
            }

            if (submitBtn) {
              submitBtn.disabled = true;
              submitBtn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <span>Order Confirmed! Redirecting...</span>`;
            }

            if (data.redirect_url) {
              setTimeout(() => {
                window.location.href = data.redirect_url;
              }, 220);
              return;
            }

            // Fallback Success UI if redirect_url is absent
            const successBox = document.querySelector(".js-order-success-banner");
            if (successBox) {
              successBox.style.display = "block";
              successBox.innerHTML = `
                <div style="background:#0A0C0B; color:#FFFFFF; border-left:4px solid #04F404; padding:22px; border-radius:6px; margin-bottom:24px;">
                  <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:14px;">
                    <div>
                      <span style="display:inline-block; background:#04F404; color:#080A09; font-weight:800; font-size:0.72rem; padding:3px 8px; border-radius:3px; margin-bottom:6px;">ORDER #${orderId}</span>
                      <h3 style="color:#04F404; font-size:1.35rem; margin-bottom:4px;">Order Received! (Cash on Delivery)</h3>
                      <p style="font-size:0.9rem; color:#D2D8D4;">Thank you <strong>${name}</strong> (${phone}). Your order has been placed successfully! Our team will contact you shortly. Delivery Address: <em>${address}</em>.</p>
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
              "Could not connect to the server to place your order. Please try again.",
              { type: "danger", title: "Connection Error", confirmText: "Try Again" }
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
    const defaultTitles = { warning: "Warning", danger: "Error", success: "Success", info: "Notice" };

    dom.icon.className = "rabez-dialog__icon js-rabez-dialog-icon rabez-dialog__icon--" + type;
    dom.icon.innerHTML = POPUP_ICONS[type] || POPUP_ICONS.info;
    dom.title.textContent = opts.title || defaultTitles[type] || "RABEZ";
    dom.msg.textContent = message == null ? "" : String(message);

    const confirmBtnClass = type === "danger" ? "rabez-dialog__btn--danger" : "rabez-dialog__btn--primary";
    dom.confirmBtn.className = "rabez-dialog__btn js-rabez-dialog-confirm " + confirmBtnClass;
    dom.confirmBtn.textContent = opts.confirmText || (mode === "confirm" ? "Confirm" : "OK");

    if (mode === "confirm") {
      dom.cancelBtn.hidden = false;
      dom.cancelBtn.textContent = opts.cancelText || "Cancel";
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

