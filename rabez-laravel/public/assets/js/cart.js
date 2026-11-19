;/**
 * RABEZ Modern Store Cart, Variations, Filters & Checkout Engine (assets/js/cart.js)
 * Official WhatsApp: +8801612359652 | Currency: BDT (৳)
 */
(function () {
  "use strict";

  const STORAGE_CART_KEY = "rabez_wc_cart_v1";
  const STORAGE_WISHLIST_KEY = "rabez_wc_wishlist_v1";
  const STORAGE_WISHLIST_SIZES_KEY = "rabez_wc_wishlist_sizes_v1";
  const WHATSAPP_NUMBER = "8801612359652";
  function getCustomPrintFee() {
    const fee = Number(window.RABEZ_CUSTOM_PRINT_FEE);
    return Number.isFinite(fee) && fee >= 0 ? fee : 250;
  }

  function escapeHtml(str) {
    return String(str ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function formatCustomPrintLabel(item) {
    const parts = [];
    if (item.customName) parts.push(escapeHtml(item.customName));
    if (item.customNumber) parts.push(`#${escapeHtml(item.customNumber)}`);
    if (!parts.length) return "";
    const colorSuffix = item.customColor && item.customColor !== "White"
      ? ` (${escapeHtml(item.customColor)})`
      : "";
    return `${parts.join(" ")}${colorSuffix}`;
  }

  function resolveImageURL(src) {
    if (!src) return "/assets/images/products/real-madrid-home-hs.jpg";
    if (src.startsWith("http://") || src.startsWith("https://") || src.startsWith("//") || src.startsWith("data:")) {
      return src;
    }
    if (src.startsWith("/")) {
      return src;
    }
    return "/" + src;
  }

  let rawCart = loadJSON(STORAGE_CART_KEY, []);
  if (!Array.isArray(rawCart)) rawCart = [];
  let cart = rawCart.map((item) => ({
    ...item,
    image: resolveImageURL(item.image)
  }));
  let wishlist = loadJSON(STORAGE_WISHLIST_KEY, []).map((id) => Number(id)).filter((id) => Number.isFinite(id) && id > 0);
  let wishlistSizes = loadJSON(STORAGE_WISHLIST_SIZES_KEY, {});
  if (!wishlistSizes || typeof wishlistSizes !== "object" || Array.isArray(wishlistSizes)) {
    wishlistSizes = {};
  }

  function loadJSON(key, fallback) {
    try {
      const raw = localStorage.getItem(key);
      return raw ? JSON.parse(raw) : fallback;
    } catch (e) {
      return fallback;
    }
  }

  function saveCart() {
    try {
      localStorage.setItem(STORAGE_CART_KEY, JSON.stringify(cart));
    } catch (e) {}
    updateCartUI();
  }

  function saveWishlistSizes() {
    try {
      localStorage.setItem(STORAGE_WISHLIST_SIZES_KEY, JSON.stringify(wishlistSizes));
    } catch (e) {}
  }

  function saveWishlist() {
    wishlist = Array.from(new Set(wishlist.map((id) => Number(id)).filter((id) => Number.isFinite(id) && id > 0)));
    try {
      localStorage.setItem(STORAGE_WISHLIST_KEY, JSON.stringify(wishlist));
    } catch (e) {}
    saveWishlistSizes();
    updateWishlistUI();
  }

  function formatBDT(amount) {
    return "৳ " + Number(amount).toLocaleString("en-IN");
  }

  const LEGACY_SKU_MAP = {
    1324: "RM-HOME-26",
    1411: "ARG-MESSI-FS",
    1501: "HD-MESSI-BARCA-ARG",
    1326: "RMA-3RD-HS",
    1388: "RMA-AWAY-FS",
    1320: "FCB-HOME-HS",
    1352: "ARS-HOME-HS",
    1349: "MUN-HOME-HS",
    1384: "BD-HOME-HS"
  };

  function getProductById(id) {
    const numId = Number(id);
    const list = window.RABEZ_PRODUCTS || [];
    const found = list.find((p) => Number(p.id) === numId);
    if (found) return found;

    if (LEGACY_SKU_MAP[numId]) {
      const bySku = list.find((p) => String(p.sku || "").toUpperCase() === LEGACY_SKU_MAP[numId]);
      if (bySku) return bySku;
    }

    if (window.RABEZ_CURRENT_PRODUCT && Number(window.RABEZ_CURRENT_PRODUCT.id) === numId) {
      return window.RABEZ_CURRENT_PRODUCT;
    }

    const pageContainer = document.querySelector(".rabez-single-product, [data-single-product-id]");
    if (pageContainer) {
      const pageTitle = document.querySelector(".rabez-summary__title")?.textContent.trim();
      const priceText = document.querySelector(".js-sp-price")?.textContent.replace(/[^\d.]/g, "");
      const sku = document.querySelector(".js-sp-sku")?.textContent.trim();
      const img = document.querySelector(".js-sp-main-img")?.src;
      if (pageTitle) {
        const fallbackProd = {
          id: numId,
          sku: sku || ("RBZ-" + numId),
          name: pageTitle,
          price: parseFloat(priceText) || 1150,
          regularPrice: (parseFloat(priceText) || 1150) + 200,
          image: resolveImageURL(img),
          sizes: ["S", "M", "L", "XL", "XXL"],
          defaultSize: "L"
        };
        window.RABEZ_PRODUCTS = window.RABEZ_PRODUCTS || [];
        window.RABEZ_PRODUCTS.push(fallbackProd);
        return fallbackProd;
      }
    }
    return null;
  }

  function isProductInCart(id) {
    return cart.some((item) => Number(item.id) === Number(id));
  }

  function getAddButtonInnerHTML(isAdded) {
    if (isAdded) {
      return `
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span>Added</span>
      `;
    }
    return `
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <path d="M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5a1.5 1.5 0 0 1-1.5 1.5h-14A1.5 1.5 0 0 1 3.5 21l1-12.5z"/>
      </svg>
      <span>Add</span>
    `;
  }

  function syncAddButtonsState() {
    document.querySelectorAll(".js-add-to-cart").forEach((btn) => {
      const id = Number(btn.dataset.productId);
      const added = isProductInCart(id);
      btn.classList.toggle("is-added", added);
      btn.classList.toggle("added", added);
      btn.innerHTML = getAddButtonInnerHTML(added);
    });

    document.querySelectorAll(".js-sp-add-btn").forEach((btn) => {
      const id = Number(btn.dataset.productId);
      const added = isProductInCart(id);
      btn.classList.toggle("is-added", added);
      btn.classList.toggle("added", added);
      btn.innerHTML = added ? "&#10003; Added to Bag" : "Add to Bag";
    });
  }

  function escapeHTML(str) {
    return String(str ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  /* --- Shared Size Swatches Renderer --- */
  function buildSizeSwatchesHTML(product) {
    const allSizes = (Array.isArray(product.allSizes) && product.allSizes.length)
      ? product.allSizes
      : (Array.isArray(product.sizes) ? product.sizes : []);
    const inStock = Array.isArray(product.sizes) ? product.sizes : allSizes;
    return allSizes
      .map((s) => {
        const avail = inStock.includes(s);
        const isSel = avail && s === product.defaultSize;
        const escSize = escapeHTML(s);
        return `<button type="button" class="rabez-size-swatch ${isSel ? "is-selected" : ""} ${!avail ? "is-out-of-stock" : ""}" data-size="${escSize}" aria-pressed="${isSel}" ${!avail ? 'disabled title="Out of stock"' : ""}>${escSize}</button>`;
      })
      .join("");
  }

  /* --- Quick View Custom Print Synchronizer --- */
  function syncQvCustomPrint(modal) {
    if (!modal) return;
    const nameInput = modal.querySelector(".js-qv-custom-name");
    const numInput = modal.querySelector(".js-qv-custom-num");
    const previewName = modal.querySelector(".js-qv-preview-name");
    const previewNum = modal.querySelector(".js-qv-preview-num");
    const nameCount = modal.querySelector(".js-qv-name-count");
    const numCount = modal.querySelector(".js-qv-num-count");
    const clearName = modal.querySelector(".js-qv-clear-name");
    const clearNum = modal.querySelector(".js-qv-clear-num");
    const presets = modal.querySelectorAll(".js-qv-preset-pill");

    const rawName = (nameInput ? nameInput.value : "").toUpperCase();
    const cleanName = rawName.replace(/[^A-Z0-9 .\-]/g, "").slice(0, 14);
    if (nameInput && nameInput.value !== cleanName) nameInput.value = cleanName;

    const rawNum = (numInput ? numInput.value : "");
    const cleanNum = rawNum.replace(/[^0-9]/g, "").slice(0, 2);
    if (numInput && numInput.value !== cleanNum) numInput.value = cleanNum;

    if (nameCount) nameCount.textContent = `${cleanName.length}/14`;
    if (numCount) numCount.textContent = `${cleanNum.length}/2`;

    if (clearName) clearName.style.display = cleanName.length > 0 ? "block" : "none";
    if (clearNum) clearNum.style.display = cleanNum.length > 0 ? "block" : "none";

    if (previewName) {
      previewName.textContent = cleanName || "YOUR NAME";
      previewName.style.opacity = cleanName ? "1" : "0.45";
      previewName.style.fontSize = cleanName.length > 9 ? "9px" : "11px";
    }
    if (previewNum) {
      previewNum.textContent = cleanNum || "00";
      previewNum.style.opacity = cleanNum ? "1" : "0.45";
    }

    presets.forEach(pill => {
      const pName = (pill.dataset.name || "").toUpperCase();
      const pNum = String(pill.dataset.num || "");
      const isMatch = Boolean(cleanName && cleanName === pName && cleanNum === pNum);
      pill.classList.toggle("is-active", isMatch);
    });
  }

  /* --- Render Product Card (WooCommerce li.product structure) --- */
  function createProductCardHTML(product) {
    const isWished = wishlist.includes(product.id);
    const isAdded = isProductInCart(product.id);
    const hasStockSizes = Array.isArray(product.sizes) ? product.sizes.length > 0 : true;
    const badgeHTML = product.badge
      ? `<span class="rabez-badge rabez-badge--${product.badgeType || "neon"}">${product.badge}</span>`
      : "";
    const regPriceHTML = product.regularPrice
      ? `<del><span class="woocommerce-Price-amount amount">৳ ${product.regularPrice.toLocaleString("en-IN")}</span></del>`
      : "";

    const swatchesHTML = buildSizeSwatchesHTML(product);

    const frontImg = resolveImageURL(product.image);
    let backImg = null;
    if (product.gallery && Array.isArray(product.gallery) && product.gallery.length > 0) {
      const candidate = product.gallery.find(g => g && resolveImageURL(g) !== frontImg) || (product.gallery.length > 1 ? product.gallery[1] : null);
      if (candidate) {
        backImg = resolveImageURL(candidate);
      }
    }

    return `
      <li class="product type-product post-${product.id} status-publish instock product_cat-${product.sleeve} product-type-variable rabez-product-card ${backImg ? 'has-back-view' : ''}"
          data-product-id="${product.id}"
          data-club="${product.club}"
          data-sleeve="${product.sleeve}"
          data-price="${product.price}">
        <div class="rabez-product-card__media">
          <a href="/product/${product.slug || product.id}" class="woocommerce-LoopProduct-link">
            <img src="${frontImg}" class="rabez-card-img-front" alt="${product.name} - Front View" loading="lazy" width="500" height="667">
            ${backImg ? `
              <img src="${backImg}" class="rabez-card-img-back" alt="${product.name} - Back View" loading="lazy" width="500" height="667">
              <span class="rabez-card-flip-pill">Back View</span>
            ` : ''}
          </a>
          <div class="rabez-product-card__badges">
            ${badgeHTML}
          </div>
          <div class="rabez-product-card__actions">
            <button type="button" class="rabez-card-icon-btn js-wishlist-toggle ${isWished ? "is-active" : ""}"
                    data-product-id="${product.id}" aria-label="Add to Wishlist" title="Wishlist">
              <svg viewBox="0 0 24 24" fill="${isWished ? "currentColor" : "none"}" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
              </svg>
            </button>
            <button type="button" class="rabez-card-icon-btn js-quick-view"
                    data-product-id="${product.id}" aria-label="Quick View" title="Quick View">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        <div class="rabez-product-card__body">
          <div class="rabez-product-card__meta">
            <span>${product.clubLabel}</span>
            <span class="rabez-product-card__sleeve">${product.sleeveLabel}</span>
          </div>

          <h3 class="rabez-product-card__title woocommerce-loop-product__title">
            <a href="/product/${product.slug || product.id}">${product.name}</a>
          </h3>

          <div class="rabez-product-card__sizes" role="group" aria-label="Select Jersey Size">
            <div class="rabez-size-swatches js-card-swatches">
              ${swatchesHTML}
            </div>
          </div>

          <div class="rabez-product-card__footer">
            <div class="price rabez-product-card__price">
              ${regPriceHTML}
              <ins><span class="woocommerce-Price-amount amount">${formatBDT(product.price)}</span></ins>
            </div>
            <button type="button" class="rabez-add-cart-btn add_to_cart_button ajax_add_to_cart js-add-to-cart ${isAdded ? "is-added added" : ""}"
                    data-product-id="${product.id}" ${!hasStockSizes ? 'disabled style="opacity:0.55; cursor:not-allowed;"' : ""}>
              ${hasStockSizes ? getAddButtonInnerHTML(isAdded) : "Sold Out"}
            </button>
          </div>
        </div>
      </li>
    `;
  }

  /* --- Render Filterable Grids on Home & Shop Pages --- */
  let activeFilter = {
    club: "all",
    sleeve: "all",
    search: "",
    sort: "default"
  };

  function renderProductGrids() {
    const grids = document.querySelectorAll(".js-rabez-product-grid");
    if (!grids.length || !window.RABEZ_PRODUCTS) return;

    grids.forEach((grid) => {
      const limit = Number(grid.dataset.limit || 0);
      let list = [...window.RABEZ_PRODUCTS];

      if (activeFilter.club !== "all") {
        list = list.filter(
          (p) =>
            p.club === activeFilter.club ||
            (Array.isArray(p.clubs) && p.clubs.includes(activeFilter.club))
        );
      }
      if (activeFilter.sleeve !== "all") {
        list = list.filter((p) => p.sleeve === activeFilter.sleeve);
      }
      if (activeFilter.search.trim() !== "") {
        const q = activeFilter.search.toLowerCase();
        list = list.filter(
          (p) =>
            p.name.toLowerCase().includes(q) ||
            p.clubLabel.toLowerCase().includes(q) ||
            p.sleeveLabel.toLowerCase().includes(q)
        );
      }

      if (activeFilter.sort === "price-asc") {
        list.sort((a, b) => a.price - b.price);
      } else if (activeFilter.sort === "price-desc") {
        list.sort((a, b) => b.price - a.price);
      }

      const countEl = document.querySelector(".js-product-result-count");
      if (countEl) {
        const unitLabel =
          activeFilter.sleeve === "winter-hoodie"
            ? list.length === 1
              ? "Hoodie"
              : "Hoodies"
            : list.length === 1
            ? "Item"
            : "Items";
        countEl.textContent = `Showing ${list.length} ${unitLabel}`;
      }

      const fullCatalogBtn = document.querySelector(".js-view-full-catalog-btn");
      if (fullCatalogBtn) {
        const total = (window.RABEZ_PRODUCTS && window.RABEZ_PRODUCTS.length) ? window.RABEZ_PRODUCTS.length : list.length;
        if (activeFilter.sleeve !== "all") {
          const sleeveLabels = {
            "half-sleeve": "Half Sleeve",
            "full-sleeve": "Full Sleeve",
            "winter-hoodie": "Winter Hoodies"
          };
          const lbl = sleeveLabels[activeFilter.sleeve] || "Jerseys";
          fullCatalogBtn.textContent = `View All ${lbl} (${list.length} Kits) \u2192`;
          fullCatalogBtn.href = `/shop?sleeve=${activeFilter.sleeve}`;
        } else if (activeFilter.club !== "all") {
          const clubName = (list[0] && list[0].clubLabel) || "Club";
          fullCatalogBtn.textContent = `View All ${clubName} (${list.length} Kits) \u2192`;
          fullCatalogBtn.href = `/shop?club=${activeFilter.club}`;
        } else {
          fullCatalogBtn.textContent = `View Full Jersey Catalog (${total} Kits)`;
          fullCatalogBtn.href = `/shop`;
        }
      }

      const sliced = limit > 0 ? list.slice(0, limit) : list;

      if (!sliced.length) {
        grid.innerHTML = `
          <li style="grid-column: 1 / -1; text-align:center; padding: 48px 20px; background:#F6F7F5; border-radius:8px;">
            <p style="font-weight:700; margin-bottom:10px;">No matching jerseys found for this filter.</p>
            <button type="button" class="rabez-btn rabez-btn--dark rabez-btn--sm js-reset-filters">Show All Jerseys</button>
          </li>
        `;
        return;
      }

      grid.innerHTML = sliced.map(createProductCardHTML).join("");
    });
  }

  /* --- Add to Cart Logic --- */
  function addToCart(arg1, arg2, arg3, arg4) {
    let id, size, qty = 1, customName = "", customNumber = "", customColor = "";
    let silentToast = false;
    let customToast = null;
    let variantId = null;
    let variantTitle = "";
    let customPrice = null;
    let customImage = "";
    let customSku = "";

    if (typeof arg1 === "object" && arg1 !== null) {
      id = Number(arg1.id);
      size = arg1.size;
      qty = arg1.qty !== undefined ? Number(arg1.qty) : 1;
      customName = arg1.customName || "";
      customNumber = arg1.customNumber || "";
      customColor = arg1.customColor || "";
      silentToast = Boolean(arg1.silentToast);
      if (arg1.customToast) customToast = arg1.customToast;
      if (arg1.variantId) variantId = Number(arg1.variantId);
      if (arg1.variantTitle) variantTitle = String(arg1.variantTitle);
      if (arg1.price !== undefined && arg1.price !== null && !isNaN(Number(arg1.price))) {
        customPrice = Number(arg1.price);
      }
      if (arg1.image) customImage = arg1.image;
      if (arg1.sku) customSku = arg1.sku;
    } else {
      id = Number(arg1);
      size = arg2;
      qty = arg3 !== undefined ? Number(arg3) : 1;
      if (typeof arg4 === "object" && arg4 !== null) {
        customName = arg4.customName || "";
        customNumber = arg4.customNumber || "";
        customColor = arg4.customColor || "";
        silentToast = Boolean(arg4.silentToast);
        if (arg4.customToast) customToast = arg4.customToast;
        if (arg4.variantId) variantId = Number(arg4.variantId);
        if (arg4.variantTitle) variantTitle = String(arg4.variantTitle);
        if (arg4.price !== undefined && arg4.price !== null && !isNaN(Number(arg4.price))) {
          customPrice = Number(arg4.price);
        }
        if (arg4.image) customImage = arg4.image;
        if (arg4.sku) customSku = arg4.sku;
      }
    }

    let product = getProductById(id);
    if (!product && typeof arg1 === "object" && arg1 !== null) {
      product = {
        id: id,
        sku: customSku || arg1.sku || ("RBZ-" + id),
        name: arg1.name || "Football Jersey",
        price: customPrice !== null ? customPrice : (Number(arg1.price) || 1150),
        image: resolveImageURL(customImage || arg1.image),
        defaultSize: size || variantTitle || "L"
      };
      window.RABEZ_PRODUCTS = window.RABEZ_PRODUCTS || [];
      window.RABEZ_PRODUCTS.push(product);
    }
    if (!product) return;

    const baseItemPrice = customPrice !== null ? customPrice : Number(product.price);
    const itemSize = variantTitle || size || product.defaultSize || "L";
    const allowCustomPrint = product.hasCustomPrint === true;
    const cleanName = allowCustomPrint ? (customName || "").trim().toUpperCase() : "";
    const cleanNum = allowCustomPrint ? (customNumber || "").trim() : "";
    const cleanColor = (allowCustomPrint && (cleanName || cleanNum) && customColor) ? String(customColor).trim() : "White";
    const hasCustom = Boolean(allowCustomPrint && (cleanName || cleanNum));
    const unitPrice = baseItemPrice + (hasCustom ? getCustomPrintFee() : 0);
    const key = `${product.id}_${variantId || itemSize}_${cleanName}_${cleanNum}_${cleanColor}`;

    const existing = cart.find((item) => item.key === key);
    if (existing) {
      existing.qty += Number(qty);
    } else {
      cart.push({
        key,
        id: product.id,
        variantId: variantId,
        variantTitle: variantTitle || (variantId ? itemSize : ""),
        sku: customSku || product.sku,
        name: product.name,
        size: itemSize,
        customName: cleanName,
        customNumber: cleanNum,
        customColor: cleanColor,
        price: unitPrice,
        qty: Number(qty),
        image: resolveImageURL(customImage || product.image)
      });
    }

    saveCart();
    if (window.RABEZ_ANALYTICS && typeof window.RABEZ_ANALYTICS.trackAddToCart === "function") {
      window.RABEZ_ANALYTICS.trackAddToCart(product, itemSize);
    }
    if (!silentToast) {
      if (customToast && customToast.title) {
        showToast(customToast.title, customToast.message || "Added to your bag.", customToast.action || null);
      } else {
        showToast("Added to Bag", "Item added to your bag.", {
          label: "View Bag",
          onClick: () => openCartDrawer()
        });
      }
    }
  }

  function removeFromCart(key) {
    cart = cart.filter((item) => item.key !== key);
    saveCart();
  }

  function clearCart() {
    cart = [];
    saveCart();
  }

  function getCartTotals() {
    const count = cart.reduce((acc, item) => acc + item.qty, 0);
    const subtotal = cart.reduce((acc, item) => acc + item.price * item.qty, 0);
    return { count, subtotal };
  }

  /* --- Update Header Counters, Mini-Cart Drawer & Checkout Summary --- */
  function updateCartUI() {
    const { count, subtotal } = getCartTotals();

    document.querySelectorAll(".js-cart-count").forEach((el) => {
      el.textContent = count;
    });
    document.querySelectorAll(".js-cart-subtotal").forEach((el) => {
      el.textContent = formatBDT(subtotal);
    });

    const drawerBody = document.querySelector(".js-cart-drawer-items");
    if (drawerBody) {
      if (!cart.length) {
        drawerBody.innerHTML = `
          <div style="text-align:center; padding: 48px 16px; color:#6E7672;">
            <div style="display:inline-flex; align-items:center; justify-content:center; width:64px; height:64px; border-radius:50%; background:#141C16; border:1px solid #202924; margin-bottom:14px; color:#04F404;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5a1.5 1.5 0 0 1-1.5 1.5h-14A1.5 1.5 0 0 1 3.5 21l1-12.5z"/>
              </svg>
            </div>
            <p style="font-weight:700; color:#FFFFFF; margin-bottom:6px; font-size:15px;">Your jersey bag is empty</p>
            <p style="font-size:0.84rem; color:#9CA3AF; margin-bottom:18px;">Pick your club kit & size to start your order.</p>
            <a href="/shop" class="rabez-btn rabez-btn--primary rabez-btn--sm">Browse Jerseys</a>
          </div>
        `;
      } else {
        drawerBody.innerHTML = cart
          .map((item) => {
            const customPrintLabel = formatCustomPrintLabel(item);
            const customMeta = customPrintLabel
                ? `<div style="color:#04F404; font-weight:700; font-size:0.73rem; margin-top:2px;">Print: ${customPrintLabel} (+৳${getCustomPrintFee()})</div>`
                : "";
            return `
              <div class="rabez-cart-item woocommerce-mini-cart-item mini_cart_item" style="display:flex; align-items:center; gap:12px; padding:12px; background:#131A16; border:1px solid #1F2823; border-radius:8px; margin-bottom:10px;">
                <img src="${resolveImageURL(item.image)}" alt="${item.name}" class="rabez-cart-item__img" style="width:58px; height:74px; object-fit:cover; border-radius:6px; border:1px solid #2B3830; flex-shrink:0; background:#19201C;" onerror="this.src='/assets/images/products/real-madrid-home-hs.jpg'">
                <div style="flex:1; min-width:0;">
                  <div class="rabez-cart-item__title" style="font-weight:700; font-size:13px; color:#FFFFFF; line-height:1.3; margin-bottom:4px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${item.name}</div>
                  <div class="rabez-cart-item__meta" style="display:flex; align-items:center; gap:8px; margin:4px 0;">
                    <span style="font-size:11.5px; color:#9CA3AF;">${item.variantTitle ? 'Variant:' : 'Size:'} <strong style="color:#04F404;">${item.variantTitle || item.size}</strong></span>
                    <div class="rabez-cart-qty-stepper" style="display:inline-flex; align-items:center; background:#18221D; border:1px solid #2B3830; border-radius:5px; height:24px; padding:0 2px;">
                      <button type="button" class="js-cart-qty-btn js-cart-qty-minus" data-key="${item.key}" title="Decrease quantity" style="width:22px; height:100%; background:none; border:none; color:#CBD5E1; cursor:pointer; font-size:13px; font-weight:700; display:flex; align-items:center; justify-content:center; line-height:1; transition:color 0.15s;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#CBD5E1'">&minus;</button>
                      <span style="font-size:11.5px; font-weight:800; color:#FFFFFF; min-width:18px; text-align:center;">${item.qty}</span>
                      <button type="button" class="js-cart-qty-btn js-cart-qty-plus" data-key="${item.key}" title="Increase quantity" style="width:22px; height:100%; background:none; border:none; color:#CBD5E1; cursor:pointer; font-size:13px; font-weight:700; display:flex; align-items:center; justify-content:center; line-height:1; transition:color 0.15s;" onmouseover="this.style.color='#04F404'" onmouseout="this.style.color='#CBD5E1'">&plus;</button>
                    </div>
                  </div>
                  ${customMeta}
                  <div class="rabez-cart-item__price" style="font-weight:800; font-size:13px; color:#04F404; margin-top:2px;">${formatBDT(item.price * item.qty)}</div>
                </div>
                <button type="button" class="rabez-cart-item__remove js-remove-cart-item" data-key="${item.key}" aria-label="Remove item" title="Remove item" style="background:none; border:none; color:#6B7280; cursor:pointer; padding:6px; border-radius:4px; display:inline-flex; align-items:center; justify-content:center; transition:color 0.15s;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#6B7280'">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg>
                </button>
              </div>
            `;
          })
          .join("");
      }
    }

    // Drawer Coupon & Estimated Total Calculation
    const discount = appliedCoupon ? Number(appliedCoupon.discount || 0) : 0;
    const drawerDiscountRow = document.querySelector(".js-drawer-discount-row");
    const drawerAppliedCode = document.querySelector(".js-drawer-applied-code");
    const drawerDiscountVal = document.querySelector(".js-drawer-discount-val");
    const drawerTotal = document.querySelector(".js-drawer-total");
    const drawerCouponInput = document.getElementById("drawer-coupon-code");

    if (drawerDiscountRow) {
      if (appliedCoupon && discount > 0 && cart.length > 0) {
        drawerDiscountRow.style.display = "flex";
        if (drawerAppliedCode) drawerAppliedCode.textContent = appliedCoupon.code;
        if (drawerDiscountVal) drawerDiscountVal.textContent = formatBDT(discount);
        if (drawerCouponInput && !drawerCouponInput.value) drawerCouponInput.value = appliedCoupon.code;
      } else {
        drawerDiscountRow.style.display = "none";
      }
    }

    if (drawerTotal) {
      const estTotal = Math.max(0, subtotal - (appliedCoupon && cart.length > 0 ? discount : 0));
      drawerTotal.textContent = formatBDT(estTotal);
    }

    // Sync all "Add" / "Added" button states across product cards & single product page
    syncAddButtonsState();

    // Update Checkout Page Summary if on checkout.html
    renderCheckoutOrderSummary();
  }

  function getPreferredWishlistSize(product) {
    if (!product) return "";
    const inStock = Array.isArray(product.sizes) ? product.sizes : [];
    const saved = wishlistSizes[String(product.id)] || wishlistSizes[product.id];
    if (saved && (inStock.length === 0 || inStock.includes(saved))) {
      return saved;
    }
    return product.defaultSize || inStock[0] || (Array.isArray(product.allSizes) ? product.allSizes[0] : "") || "";
  }

  function syncWishlistButtonsState() {
    document.querySelectorAll(".js-wishlist-toggle").forEach((btn) => {
      const id = Number(btn.dataset.productId);
      const isWished = wishlist.includes(id);
      btn.classList.toggle("is-active", isWished);
      btn.setAttribute("aria-pressed", isWished ? "true" : "false");
      if (!btn.classList.contains("rabez-wl-icon-act--remove") && !btn.classList.contains("rabez-wl-remove-btn")) {
        btn.setAttribute("title", isWished ? "Saved in Wishlist" : "Save to Wishlist");
        const svg = btn.querySelector("svg");
        if (svg) {
          svg.setAttribute("fill", isWished ? "currentColor" : "none");
        }
      }
      const spLabel = btn.querySelector(".js-sp-wishlist-label");
      if (spLabel) {
        spLabel.textContent = isWished ? "Saved in Wishlist" : "Save to Wishlist";
      }
      if (btn.classList.contains("js-qv-wishlist-btn")) {
        btn.style.background = isWished ? "#ECFDF3" : "";
        btn.style.borderColor = isWished ? "#16A34A" : "";
        btn.style.color = isWished ? "#15803D" : "";
      }
    });
  }

  function updateWishlistUI() {
    document.querySelectorAll(".js-wishlist-count").forEach((el) => {
      el.textContent = wishlist.length;
    });
    syncWishlistButtonsState();
    if (typeof renderWishlistPage === "function") {
      renderWishlistPage();
    }
  }

  /* --- Pro Wishlist Engine & Server Sync --- */
  let activeWishlistFilter = "all";
  let activeWishlistSort = "recent";

  function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || "";
  }

  function persistWishlistToggleToServer(productId, size, mode = "toggle") {
    const endpoint = window.RABEZ_ROUTES?.wishlistToggle;
    if (!endpoint || !Array.isArray(window.RABEZ_AUTH_WISHLIST)) return;
    fetch(endpoint, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": getCsrfToken()
      },
      body: JSON.stringify({
        product_id: Number(productId),
        size: size || null,
        mode: mode
      })
    }).catch(() => {});
  }

  function syncWishlistWithServer() {
    const endpoint = window.RABEZ_ROUTES?.wishlistSync;
    if (!endpoint || !Array.isArray(window.RABEZ_AUTH_WISHLIST)) return Promise.resolve();
    return fetch(endpoint, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": getCsrfToken()
      },
      body: JSON.stringify({
        product_ids: wishlist,
        sizes: wishlistSizes
      })
    })
      .then((res) => (res.ok ? res.json() : null))
      .then((data) => {
        if (data && data.success && Array.isArray(data.product_ids)) {
          wishlist = data.product_ids.map((id) => Number(id)).filter((id) => Number.isFinite(id) && id > 0);
          if (data.sizes && typeof data.sizes === "object" && !Array.isArray(data.sizes)) {
            wishlistSizes = Object.assign({}, wishlistSizes, data.sizes);
          }
          saveWishlist();
        }
      })
      .catch(() => {});
  }

  function persistWishlistClearToServer() {
    const endpoint = window.RABEZ_ROUTES?.wishlistClear;
    if (!endpoint || !Array.isArray(window.RABEZ_AUTH_WISHLIST)) return;
    fetch(endpoint, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": getCsrfToken()
      }
    }).catch(() => {});
  }

  function toggleWishlist(id, opts = {}) {
    const numId = Number(id);
    if (!numId) return false;
    const wasWished = wishlist.includes(numId);

    if (wasWished) {
      wishlist = wishlist.filter((x) => x !== numId);
    } else {
      wishlist.unshift(numId);
      if (opts.size) {
        wishlistSizes[String(numId)] = String(opts.size);
      }
    }

    saveWishlist();
    persistWishlistToggleToServer(numId, wishlistSizes[String(numId)] || null, "toggle");

    if (!opts.silent) {
      const onWishlistPage = Boolean(document.querySelector(".js-wishlist-page-grid"));
      if (!wasWished) {
        showToast(
          "Added to Wishlist",
          "Saved to your wishlist.",
          !onWishlistPage && window.RABEZ_ROUTES?.wishlist
            ? { label: "View Wishlist", href: window.RABEZ_ROUTES.wishlist }
            : null
        );
      } else {
        showToast(
          "Removed from Wishlist",
          "Item removed from your wishlist.",
          null
        );
      }
    }
    return !wasWished;
  }

  function getWishlistProductsList() {
    return wishlist
      .map((id) => getProductById(id))
      .filter((prod) => Boolean(prod));
  }

  function buildWishlistCardHTML(product) {
    const frontImg = resolveImageURL(product.image);
    const inStock = Array.isArray(product.sizes) ? product.sizes : [];
    const allSizes =
      Array.isArray(product.allSizes) && product.allSizes.length ? product.allSizes : inStock;
    const hasStock = inStock.length > 0 && product.isReadyStock !== false;
    const selectedSize = getPreferredWishlistSize(product);

    const curPrice = Number(product.price || 0);
    const regPrice = Number(product.regularPrice || 0);
    const hasDiscount = regPrice > curPrice;

    const sizeSwatchesHTML = allSizes
      .map((s) => {
        const avail = inStock.includes(s);
        const isSel = avail && s === selectedSize;
        const escSize = escapeHTML(s);
        return `<button type="button" class="rabez-size-swatch ${isSel ? "is-selected" : ""} ${!avail ? "is-out-of-stock" : ""}" data-size="${escSize}" aria-pressed="${isSel}" ${!avail ? 'disabled title="Out of stock"' : ""}>${escSize}</button>`;
      })
      .join("");

    const productUrl = `/product/${escapeHTML(product.slug || product.id)}`;
    const safeName = escapeHTML(product.name);
    const safeClub = escapeHTML(product.clubLabel || "Club Kit");
    const safeSleeve = escapeHTML(product.sleeveLabel || "Jersey");

    return `
      <article class="rabez-wl-card rabez-wl-row" data-product-id="${product.id}">
        <a href="${productUrl}" class="rabez-wl-row__thumb">
          <img src="${frontImg}" alt="${safeName}" loading="lazy" width="88" height="108">
        </a>

        <div class="rabez-wl-row__info">
          <div class="rabez-wl-row__meta">
            <span>${safeClub} &bull; ${safeSleeve}</span>
            <span class="rabez-wl-row__status ${hasStock ? "rabez-wl-row__status--in" : "rabez-wl-row__status--out"}">
              ${hasStock ? "In Stock" : "Out of Stock"}
            </span>
          </div>
          <h3 class="rabez-wl-row__title">
            <a href="${productUrl}">${safeName}</a>
          </h3>
        </div>

        <div class="rabez-wl-row__size">
          <span class="rabez-wl-row__size-label">Size:</span>
          <div class="rabez-size-swatches js-wl-size-group">
            ${sizeSwatchesHTML}
          </div>
        </div>

        <div class="rabez-wl-row__price">
          <span class="rabez-wl-row__price-now num">${formatBDT(curPrice)}</span>
          ${hasDiscount ? `<del class="rabez-wl-row__price-was num">${formatBDT(regPrice)}</del>` : ""}
        </div>

        <div class="rabez-wl-row__actions">
          <button type="button" class="rabez-wl-action-btn rabez-wl-action-btn--primary js-wl-move-item" data-product-id="${product.id}" ${!hasStock ? 'disabled style="opacity:0.55; cursor:not-allowed;"' : ""}>
            ${hasStock ? "Move to Bag" : "Out of Stock"}
          </button>
          <button type="button" class="rabez-wl-remove-btn js-wishlist-toggle" data-product-id="${product.id}" aria-label="Remove ${safeName} from Wishlist" title="Remove">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
          </button>
        </div>
      </article>
    `;
  }

  function renderWishlistPage() {
    const grid = document.querySelector(".js-wishlist-page-grid");
    if (!grid) return;

    const toolbar = document.querySelector(".js-wl-toolbar");
    const emptyBox = document.querySelector(".js-wl-empty");
    const moveAllBtn = document.querySelector(".js-wl-move-all");

    const allSaved = getWishlistProductsList();
    const inStockItems = allSaved.filter((p) => Array.isArray(p.sizes) && p.sizes.length > 0 && p.isReadyStock !== false);

    document.querySelectorAll(".js-wl-stat-total").forEach((el) => {
      el.textContent = String(allSaved.length);
    });

    if (moveAllBtn) {
      moveAllBtn.disabled = inStockItems.length === 0;
      moveAllBtn.style.opacity = inStockItems.length === 0 ? "0.5" : "1";
    }

    if (!allSaved.length) {
      grid.style.display = "none";
      grid.innerHTML = "";
      if (toolbar) toolbar.style.display = "none";
      if (emptyBox) emptyBox.style.display = "block";
      return;
    }

    if (emptyBox) emptyBox.style.display = "none";
    if (toolbar) toolbar.style.display = "flex";

    grid.style.display = "flex";
    grid.innerHTML = allSaved.map(buildWishlistCardHTML).join("");
  }

  function initWishlistSyncAndRender() {
    let importedShared = false;
    if (Array.isArray(window.RABEZ_SHARED_WISHLIST) && window.RABEZ_SHARED_WISHLIST.length) {
      window.RABEZ_SHARED_WISHLIST.forEach((rawId) => {
        const pid = Number(rawId);
        if (pid > 0 && !wishlist.includes(pid)) {
          wishlist.unshift(pid);
          importedShared = true;
        }
      });
      const sharedBanner = document.querySelector(".js-wl-shared-banner");
      if (sharedBanner) {
        sharedBanner.style.display = "flex";
      }
    }

    if (Array.isArray(window.RABEZ_AUTH_WISHLIST)) {
      window.RABEZ_AUTH_WISHLIST.forEach((rawId) => {
        const pid = Number(rawId);
        if (pid > 0 && !wishlist.includes(pid)) {
          wishlist.push(pid);
        }
      });
      if (
        window.RABEZ_AUTH_WISHLIST_SIZES &&
        typeof window.RABEZ_AUTH_WISHLIST_SIZES === "object" &&
        !Array.isArray(window.RABEZ_AUTH_WISHLIST_SIZES)
      ) {
        wishlistSizes = Object.assign({}, window.RABEZ_AUTH_WISHLIST_SIZES, wishlistSizes);
      }
      saveWishlist();
      syncWishlistWithServer();
    } else {
      if (importedShared) saveWishlist();
      else updateWishlistUI();
    }
  }

  /* --- Toast Notification --- */
  let toastTimer = null;
  function showToast(title, sub, cta) {
    let toast = document.querySelector(".rabez-wc-notice");
    if (!toast) {
      toast = document.createElement("div");
      toast.className = "rabez-wc-notice";
      toast.setAttribute("role", "status");
      document.body.appendChild(toast);
    }
    let actionHTML = `<button type="button" class="rabez-btn rabez-btn--primary rabez-btn--sm js-open-cart-drawer">View Bag</button>`;
    if (cta && cta.href && cta.label) {
      actionHTML = `<a href="${escapeHTML(cta.href)}" class="rabez-btn rabez-btn--primary rabez-btn--sm">${escapeHTML(cta.label)}</a>`;
    } else if (cta === null) {
      actionHTML = "";
    }
    toast.innerHTML = `
      <div style="flex:1;">
        <div class="rabez-wc-notice__title">${escapeHTML(title)}</div>
        <div class="rabez-wc-notice__sub">${escapeHTML(sub)}</div>
      </div>
      ${actionHTML}
    `;
    requestAnimationFrame(() => toast.classList.add("is-visible"));
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove("is-visible"), 3600);
  }

  /* --- Cart Drawer Open / Close --- */
  function openCartDrawer() {
    document.querySelector(".js-cart-drawer")?.classList.add("is-open");
    document.querySelector(".js-cart-backdrop")?.classList.add("is-open");
    syncActiveCouponsStatus();
  }

  function closeCartDrawer() {
    document.querySelector(".js-cart-drawer")?.classList.remove("is-open");
    document.querySelector(".js-cart-backdrop")?.classList.remove("is-open");
  }

  /* --- Quick View Modal --- */
  function openQuickView(productId) {
    const product = getProductById(productId);
    const modalBackdrop = document.querySelector(".js-quickview-backdrop");
    const modalContent = document.querySelector(".js-quickview-content");
    if (!product || !modalBackdrop || !modalContent) return;

    document.body.style.overflow = "hidden";

    if (window.RABEZ_ANALYTICS && typeof window.RABEZ_ANALYTICS.trackViewContent === "function") {
      window.RABEZ_ANALYTICS.trackViewContent(product);
    }

    const isAdded = isProductInCart(product.id);
    const isWished = wishlist.includes(Number(product.id));
    const swatches = buildSizeSwatchesHTML(product);
    const defaultSize = (product.sizes && product.sizes.find(s => s === 'L')) || (product.sizes && product.sizes[0]) || product.defaultSize || 'L';

    const frontImg = product.image || "assets/images/products/real-madrid-home-hs.jpg";
    let allImages = [{ src: frontImg, label: "Front View", isBack: false }];
    if (product.gallery && Array.isArray(product.gallery)) {
      product.gallery.forEach((g, idx) => {
        if (g && !allImages.some((img) => img.src === g)) {
          allImages.push({
            src: g,
            label: idx === 0 ? "Back View" : `Detail #${idx + 1}`,
            isBack: idx === 0
          });
        }
      });
    }

    modalContent.innerHTML = `
      <div class="rabez-qv-layout" data-product-id="${product.id}">
        <!-- Left: Gallery Thumbnails & Main Image -->
        <div class="rabez-qv-gallery">
          ${allImages.length > 1 ? `
            <div class="rabez-qv-thumbs js-qv-thumbs" role="tablist" aria-label="Product Views">
              ${allImages.map((img, idx) => `
                <button type="button" class="rabez-qv-thumb js-qv-thumb ${idx === 0 ? 'is-active' : ''}" data-src="${img.src}" data-is-back="${img.isBack ? '1' : '0'}" title="${img.label}">
                  <img src="${img.src}" alt="${img.label}" width="64" height="85">
                  <span class="rabez-qv-thumb__tag">${img.isBack ? 'BACK' : (idx === 0 ? 'FRONT' : '#' + idx)}</span>
                </button>
              `).join('')}
            </div>
          ` : ''}
          <div class="rabez-qv-main-media">
            <img src="${frontImg}" class="js-qv-main-img" alt="${product.name}" width="500" height="667">
            <button type="button" class="rabez-zoom-pill js-trigger-lightbox" aria-label="Open Fullscreen Zoom" title="Click to view fullscreen HD photo">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                <line x1="11" y1="8" x2="11" y2="14"></line>
                <line x1="8" y1="11" x2="14" y2="11"></line>
              </svg>
              <span>Zoom</span>
            </button>
            <span class="rabez-card-flip-pill js-qv-view-pill" style="display:none;">Back View</span>
          </div>
        </div>

        <!-- Right: Summary & Interactive Buying Form (1:1 with Single Product Page) -->
        <div class="rabez-qv-summary">
          <div class="rabez-summary__breadcrumb" style="font-size:0.75rem; line-height:1.35; color:var(--rabez-text-muted); margin-bottom:6px;">
            <a href="/" style="color:var(--rabez-text-muted); text-decoration:none;">Home</a> / 
            <a href="/shop" style="color:var(--rabez-text-muted); text-decoration:none;">Jerseys</a> / 
            <span>${escapeHTML(product.clubLabel || 'Club')} &bull; ${escapeHTML(product.sleeveLabel || 'Jersey')}</span> 
            &bull; SKU: <strong style="color:var(--rabez-text-main);">${escapeHTML(product.sku)}</strong>
          </div>

          <h2 class="rabez-summary__title" style="font-family:var(--rabez-font-display); font-size:clamp(1.6rem, 2.3vw, 2.05rem); line-height:1.15; margin-bottom:10px; text-transform:uppercase; font-weight:800; letter-spacing:-0.01em;">
            ${escapeHTML(product.name)}
          </h2>
          
          <div class="rabez-summary__price-row" style="margin-bottom:12px; padding-bottom:12px; display:flex; align-items:baseline; gap:10px; border-bottom:1px solid var(--rabez-border);">
            <span class="rabez-summary__price js-qv-price-display" style="font-size:1.65rem; font-weight:800; color:var(--rabez-text-main);">${formatBDT(product.price)}</span>
            ${(product.regularPrice && product.regularPrice > product.price) ? `
              <del class="js-qv-regular-price" style="color:#7D8782; font-size:1.05rem; font-weight:600; margin-left:4px;">${formatBDT(product.regularPrice)}</del>
            ` : ''}
            <span class="rabez-summary__stock" style="background:#EBFDF2; color:#047857; font-weight:700; font-size:0.75rem; padding:4px 10px; border-radius:99px; border:1px solid #A7F3D0; display:inline-flex; align-items:center; gap:5px; margin-left:auto;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Ready Stock</span>
            </span>
          </div>

          <p class="rabez-summary__desc" style="font-size:0.86rem; line-height:1.55; color:var(--rabez-text-body); margin-bottom:12px;">${escapeHTML(product.shortDesc || product.name)}</p>
          ${product.fabric ? `<div style="display:inline-flex; align-items:center; gap:7px; margin-bottom:12px; padding:5px 10px; border-radius:6px; background:#121614; border:1px solid #2B332E; font-size:0.82rem; color:#D1D5DB;"><span style="color:#9BA59F;">Fabric:</span> <strong style="color:#FFFFFF;">${escapeHTML(product.fabric)}</strong></div>` : ''}

          <!-- Size Selection & Size Chart Toggle -->
          <div class="rabez-variation-block" style="margin-bottom:12px;">
            <div class="rabez-variation-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
              <span style="font-weight:700; font-size:0.86rem; color:var(--rabez-text-main);">
                Select Jersey Size: <strong class="js-qv-selected-size-label" style="color:var(--rabez-neon);">${escapeHTML(defaultSize)}</strong>
              </span>
              <button type="button" class="rabez-size-chart-link js-qv-size-toggle" style="background:none; border:none; color:#008A00; text-decoration:underline; font-weight:700; font-size:0.82rem; cursor:pointer; display:inline-flex; align-items:center; gap:5px; padding:0;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21.3 15.3l-6.6-6.6a2 2 0 0 0-2.8 0L2.7 17.9a2 2 0 0 0 0 2.8l1.4 1.4a2 2 0 0 0 2.8 0l9.2-9.2a2 2 0 0 0 0-2.8l-1.4-1.4a2 2 0 0 0-2.8 0L3.7 18.9"></path>
                  <path d="M14.5 4.5l5 5"></path>
                </svg>
                <span>Size Chart</span>
              </button>
            </div>
            <div class="rabez-size-swatches js-modal-swatches">
              ${swatches}
            </div>

            <!-- Collapsible Size Chart -->
            <div class="rabez-qv-size-chart js-qv-size-chart" style="display:none; margin-top:10px; background:#F8FAF9; border:1px solid var(--rabez-border); border-radius:var(--rabez-radius-sm); padding:10px 12px; font-size:0.8rem;">
              <div style="font-weight:700; margin-bottom:6px; color:var(--rabez-text-main); font-size:0.82rem; display:flex; justify-content:space-between; align-items:center;">
                <span>Official Size Chart (Inches)</span>
                <span style="font-size:0.75rem; color:var(--rabez-text-muted); font-weight:500;">Player Edition Fit</span>
              </div>
              <table style="width:100%; border-collapse:collapse; font-size:0.78rem; text-align:center;">
                <thead>
                  <tr style="background:#E9ECEF; color:#111; font-weight:700;">
                    <th style="padding:4px 6px; border-radius:4px 0 0 4px;">Size</th>
                    <th style="padding:4px 6px;">Chest (Inches)</th>
                    <th style="padding:4px 6px; border-radius:0 4px 4px 0;">Length (Inches)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr style="border-bottom:1px solid #E5E7EB;"><td style="padding:4px 6px;"><strong>S</strong></td><td style="padding:4px 6px;">36" - 38"</td><td style="padding:4px 6px;">27"</td></tr>
                  <tr style="border-bottom:1px solid #E5E7EB;"><td style="padding:4px 6px;"><strong>M</strong></td><td style="padding:4px 6px;">38" - 40"</td><td style="padding:4px 6px;">28"</td></tr>
                  <tr style="border-bottom:1px solid #E5E7EB;"><td style="padding:4px 6px;"><strong>L</strong></td><td style="padding:4px 6px;">40" - 42"</td><td style="padding:4px 6px;">29"</td></tr>
                  <tr style="border-bottom:1px solid #E5E7EB;"><td style="padding:4px 6px;"><strong>XL</strong></td><td style="padding:4px 6px;">42" - 44"</td><td style="padding:4px 6px;">30"</td></tr>
                  <tr><td style="padding:4px 6px;"><strong>XXL</strong></td><td style="padding:4px 6px;">44" - 46"</td><td style="padding:4px 6px;">31"</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Optional Custom Name & Number Personalization Studio (1:1 with Single Product Page) -->
          ${product.hasCustomPrint === true ? `
            <div class="rabez-custom-studio js-qv-custom-studio" style="margin-bottom:14px;">
              <div class="rabez-custom-studio__header">
                <label class="rabez-custom-studio__toggle-label">
                  <input type="checkbox" class="rabez-custom-studio__checkbox js-qv-custom-toggle">
                  <div class="rabez-custom-studio__title-wrap">
                    <div class="rabez-custom-studio__title">
                      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                      </svg>
                      <span>Custom Name &amp; Number</span>
                      <span class="rabez-badge-sparkle">OFFICIAL PRINT</span>
                    </div>
                    <div class="rabez-custom-studio__subtitle">Heat-pressed authentic player typography</div>
                  </div>
                </label>
                <div class="rabez-custom-studio__fee-tag">+ ৳ ${getCustomPrintFee()}</div>
              </div>

              <!-- Collapsible Personalization Studio Body -->
              <div class="rabez-custom-studio__body js-qv-custom-inputs" style="display:none;">
                <!-- 1-Click Legend Presets -->
                <div class="rabez-custom-studio__presets">
                  <div class="rabez-custom-studio__presets-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>Popular Legends (1-Click Fill):</span>
                  </div>
                  <div class="rabez-custom-studio__presets-list js-qv-presets-list">
                    <button type="button" class="rabez-preset-pill js-qv-preset-pill" data-name="MESSI" data-num="10">MESSI 10</button>
                    <button type="button" class="rabez-preset-pill js-qv-preset-pill" data-name="RONALDO" data-num="7">RONALDO 7</button>
                    <button type="button" class="rabez-preset-pill js-qv-preset-pill" data-name="BELLINGHAM" data-num="5">BELLINGHAM 5</button>
                    <button type="button" class="rabez-preset-pill js-qv-preset-pill" data-name="VINICIUS" data-num="7">VINI JR 7</button>
                    <button type="button" class="rabez-preset-pill js-qv-preset-pill" data-name="YAMAL" data-num="19">YAMAL 19</button>
                    <button type="button" class="rabez-preset-pill js-qv-preset-pill" data-name="MODRIC" data-num="10">MODRIĆ 10</button>
                    <button type="button" class="rabez-preset-pill js-qv-preset-pill" data-name="HAALAND" data-num="9">HAALAND 9</button>
                  </div>
                </div>

                <!-- Main Studio Grid: Live SVG Preview + Fields -->
                <div class="rabez-custom-studio__main-grid">
                  <!-- Live Jersey Back Preview Card -->
                  <div class="rabez-custom-studio__preview-card">
                    <div class="rabez-custom-studio__preview-badge">LIVE BACK PREVIEW</div>
                    <div class="rabez-custom-studio__svg-wrap">
                      <svg viewBox="0 0 200 230" class="rabez-custom-studio__svg" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <defs>
                          <linearGradient id="rbzQvGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#1A211D"/>
                            <stop offset="100%" stop-color="#0E1210"/>
                          </linearGradient>
                          <filter id="rbzQvShadow" x="-10%" y="-10%" width="120%" height="120%">
                            <feDropShadow dx="0" dy="4" stdDeviation="5" flood-color="#000" flood-opacity="0.5"/>
                          </filter>
                        </defs>
                        <!-- Jersey Silhouette -->
                        <path d="M60 16 L80 20 Q100 26 120 20 L140 16 L186 52 L168 92 L150 80 L150 216 Q100 224 50 216 L50 80 L32 92 L14 52 Z" 
                              fill="url(#rbzQvGrad)" stroke="#2B3630" stroke-width="1.8" filter="url(#rbzQvShadow)"/>
                        <!-- Collar & Sleeves Athletic Accent Lines -->
                        <path d="M76 19 Q100 29 124 19 M28 86 L48 74 M172 86 L152 74" 
                              fill="none" stroke="var(--rabez-neon)" stroke-width="2.5" stroke-linecap="round"/>
                        <!-- Mesh Fabric Lines -->
                        <line x1="50" y1="120" x2="150" y2="120" stroke="#232C27" stroke-dasharray="3 3" stroke-width="1"/>
                        <line x1="50" y1="160" x2="150" y2="160" stroke="#232C27" stroke-dasharray="3 3" stroke-width="1"/>
                      </svg>
                      <!-- Dynamic Overlay Text -->
                      <div class="rabez-custom-studio__text-layer">
                        <div class="rabez-custom-studio__preview-name js-qv-preview-name" style="color:#FFFFFF;">YOUR NAME</div>
                        <div class="rabez-custom-studio__preview-num js-qv-preview-num" style="color:#FFFFFF;">00</div>
                      </div>
                    </div>
                  </div>

                  <!-- Input Controls & Swatches -->
                  <div class="rabez-custom-studio__fields">
                    <!-- Player Name Field -->
                    <div class="rabez-studio-field">
                      <div class="rabez-studio-field__top">
                        <label>NAME ON BACK</label>
                        <span class="rabez-studio-field__count js-qv-name-count">0/14</span>
                      </div>
                      <div class="rabez-studio-input-wrap">
                        <input type="text" class="rabez-studio-input js-qv-custom-name" maxlength="14" placeholder="e.g. MESSI" autocomplete="off" spellcheck="false">
                        <button type="button" class="rabez-studio-clear-btn js-qv-clear-name" title="Clear Name" style="display:none;">&times;</button>
                      </div>
                    </div>

                    <!-- Number Field -->
                    <div class="rabez-studio-field">
                      <div class="rabez-studio-field__top">
                        <label>SQUAD NUMBER</label>
                        <span class="rabez-studio-field__count js-qv-num-count">0/2</span>
                      </div>
                      <div class="rabez-studio-input-wrap">
                        <input type="text" class="rabez-studio-input js-qv-custom-num" maxlength="2" inputmode="numeric" pattern="[0-9]*" placeholder="e.g. 10" autocomplete="off">
                        <button type="button" class="rabez-studio-clear-btn js-qv-clear-num" title="Clear Number" style="display:none;">&times;</button>
                      </div>
                    </div>

                    <!-- Print Font Color Swatches -->
                    <div class="rabez-studio-field">
                      <div class="rabez-studio-field__top">
                        <label>PRINT COLOR</label>
                        <span class="rabez-studio-color-selected js-qv-color-name">White</span>
                      </div>
                      <div class="rabez-studio-color-row js-qv-color-row">
                        <button type="button" class="rabez-studio-color-btn js-qv-color-btn is-active" data-color="#FFFFFF" data-color-name="White" title="Pure White" aria-label="White">
                          <span class="rabez-studio-color-swatch" style="background:#FFFFFF;"></span>
                        </button>
                        <button type="button" class="rabez-studio-color-btn js-qv-color-btn" data-color="#F5D76E" data-color-name="Gold" title="Champion Gold" aria-label="Gold">
                          <span class="rabez-studio-color-swatch" style="background:#F5D76E;"></span>
                        </button>
                        <button type="button" class="rabez-studio-color-btn js-qv-color-btn" data-color="#111111" data-color-name="Black" title="Pitch Black" aria-label="Black">
                          <span class="rabez-studio-color-swatch" style="background:#111111; border-color:#555;"></span>
                        </button>
                        <button type="button" class="rabez-studio-color-btn js-qv-color-btn" data-color="#00E5FF" data-color-name="Cyan" title="Electric Cyan" aria-label="Cyan">
                          <span class="rabez-studio-color-swatch" style="background:#00E5FF;"></span>
                        </button>
                        <button type="button" class="rabez-studio-color-btn js-qv-color-btn" data-color="#E63946" data-color-name="Red" title="Crimson Red" aria-label="Red">
                          <span class="rabez-studio-color-swatch" style="background:#E63946;"></span>
                        </button>
                      </div>
                    </div>

                    <!-- Quality / Wash Guarantee Micro-note -->
                    <div class="rabez-custom-studio__guarantee">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                      </svg>
                      <span>Official Heat-Press Vinyl &bull; 100% Wash &amp; Peel Guaranteed</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          ` : ''}

          <!-- Purchase Row (Side-by-side Quantity + Add to Bag, 1:1 with Single Product Page) -->
          <div class="rabez-purchase-row" style="display:grid; grid-template-columns:110px 1fr; gap:10px; margin-bottom:10px;">
            <div class="rabez-qty-box" style="display:flex; align-items:center; border:1px solid var(--rabez-border-strong); border-radius:var(--rabez-radius-sm); height:46px; background-color:#FFFFFF;">
              <button type="button" class="rabez-qty-btn js-qv-qty-minus" aria-label="Decrease quantity" style="width:34px; height:100%; font-size:1.1rem; font-weight:700; color:var(--rabez-text-main); background:none; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;">&minus;</button>
              <input type="number" class="rabez-qty-input js-qv-qty-val" value="1" min="1" max="10" aria-label="Quantity" readonly style="width:40px; text-align:center; font-weight:800; font-size:0.95rem; border:none; background:transparent; color:var(--rabez-text-main); padding:0;">
              <button type="button" class="rabez-qty-btn js-qv-qty-plus" aria-label="Increase quantity" style="width:34px; height:100%; font-size:1.1rem; font-weight:700; color:var(--rabez-text-main); background:none; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;">+</button>
            </div>
            <button type="button" class="rabez-btn rabez-btn--primary rabez-btn--full js-modal-add-cart ${isAdded ? "is-added added" : ""}" data-product-id="${product.id}" style="height:46px; font-size:0.95rem; font-weight:800; display:inline-flex; align-items:center; justify-content:center; gap:8px;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5a1.5 1.5 0 0 1-1.5 1.5h-14A1.5 1.5 0 0 1 3.5 21l1-12.5z"/>
              </svg>
              <span class="js-qv-btn-text">Add to Bag &mdash; ${formatBDT(product.price)}</span>
            </button>
          </div>

          <!-- Secondary Actions: WhatsApp & Wishlist Buttons (1:1 with Single Product Page) -->
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
            <button type="button" class="rabez-btn rabez-btn--dark rabez-btn--full js-qv-whatsapp-order" data-product-id="${product.id}" style="display:flex; align-items:center; justify-content:center; gap:8px; height:42px; font-size:0.86rem; font-weight:700; background:#121614; border:1px solid #2B332E; color:#FFFFFF;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
              </svg>
              <span>Order via WhatsApp</span>
            </button>

            <button type="button" class="rabez-btn rabez-btn--outline rabez-btn--full js-wishlist-toggle js-qv-wishlist-btn ${isWished ? "is-active" : ""}" data-product-id="${product.id}" style="display:flex; align-items:center; justify-content:center; gap:8px; height:42px; font-size:0.86rem; font-weight:700; background:#121614; border:1px solid #2B332E; color:#FFFFFF;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="${isWished ? "#04F404" : "none"}" stroke="#04F404" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
              </svg>
              <span class="js-sp-wishlist-label">${isWished ? "Saved in Wishlist" : "Save to Wishlist"}</span>
            </button>
          </div>

          <!-- Direct Link to Single Product Page -->
          <div style="text-align:center; margin-bottom:12px;">
            <a href="/product/${product.slug || product.id}" style="color:var(--rabez-text-muted); font-size:0.82rem; font-weight:600; text-decoration:underline; display:inline-flex; align-items:center; gap:4px; transition:color 0.15s ease;">
              <span>View Full Details, Fabric &amp; Sizing Specs &rarr;</span>
            </a>
          </div>

          <!-- Delivery & Guarantee List (1:1 with Single Product Page) -->
          <ul class="rabez-delivery-list" style="margin-top:12px; padding-top:12px; border-top:1px solid var(--rabez-border); display:grid; gap:8px; font-size:0.82rem; color:var(--rabez-text-body); list-style:none; padding-left:0;">
            ${(window.RABEZ_SHIPPING && window.RABEZ_SHIPPING.mode === 'district') ? `
              <li style="display:flex; align-items:center; gap:8px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#008A00" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                  <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
                  <path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/>
                  <circle cx="7" cy="18" r="2"/>
                  <circle cx="17" cy="18" r="2"/>
                </svg>
                <span><strong>Inside Dhaka:</strong> ৳ ${window.RABEZ_SHIPPING.insideDhaka || 70} (24&ndash;48h) &bull; <strong>Outside:</strong> ৳ ${window.RABEZ_SHIPPING.outsideDhaka || 130}</span>
              </li>
            ` : `
              <li style="display:flex; align-items:center; gap:8px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#008A00" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                  <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
                  <path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/>
                  <circle cx="7" cy="18" r="2"/>
                  <circle cx="17" cy="18" r="2"/>
                </svg>
                <span><strong>Home Delivery (All BD):</strong> ৳ ${(window.RABEZ_SHIPPING && window.RABEZ_SHIPPING.flatFee) || 130} (Flat Rate)</span>
              </li>
            `}
            <li style="display:flex; align-items:center; gap:8px;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#008A00" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                <rect x="2" y="6" width="20" height="12" rx="2"/>
                <circle cx="12" cy="12" r="2"/>
                <path d="M6 12h.01M18 12h.01"/>
              </svg>
              <span><strong>Payment:</strong> Cash on Delivery, bKash, Nagad &bull; Open &amp; check jersey</span>
            </li>
            <li style="display:flex; align-items:center; gap:8px;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#008A00" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                <path d="M3 12a9 9 0 0 1 15-6.7L21 8"/>
                <path d="M21 3v5h-5"/>
                <path d="M21 12a9 9 0 0 1-15 6.7L3 16"/>
                <path d="M3 21v-5h5"/>
              </svg>
              <span><strong>Exchange Policy:</strong> 3-day easy size exchange guarantee</span>
            </li>
          </ul>
        </div>
      </div>
    `;
    modalBackdrop.classList.add("is-open");
  }

  /* --- WhatsApp Direct Order Builder --- */
  function buildWhatsAppOrderURL(customText) {
    if (customText) {
      return `https://api.whatsapp.com/send/?phone=${WHATSAPP_NUMBER}&text=${encodeURIComponent(customText)}`;
    }
    const { subtotal } = getCartTotals();
    if (!cart.length) {
      const msg = "Hello RABEZ! I would like to browse your jersey collection and place an order.";
      return `https://api.whatsapp.com/send/?phone=${WHATSAPP_NUMBER}&text=${encodeURIComponent(msg)}`;
    }
    const lines = cart.map(
      (item, idx) =>
        `${idx + 1}. ${item.name} | ${item.variantTitle ? item.variantTitle : 'Size: ' + item.size} | Qty: ${item.qty}${
          item.customName || item.customNumber ? ` | Print: ${item.customName} #${item.customNumber}${item.customColor && item.customColor !== 'White' ? ' (' + item.customColor + ')' : ''}` : ""
        } = ৳${item.price * item.qty}`
    );
    const fullMsg = `Hello RABEZ,\nI would like to order the following jerseys:\n\n${lines.join(
      "\n"
    )}\n\nSubtotal: ৳${subtotal}\nMy Name & Delivery Address: `;
    return `https://api.whatsapp.com/send/?phone=${WHATSAPP_NUMBER}&text=${encodeURIComponent(fullMsg)}`;
  }

  /* --- Coupon Application State & Persistence --- */
  let appliedCoupon = null;
  if (window.RABEZ_HAS_COUPONS === false) {
    try {
      localStorage.removeItem("rabez_applied_coupon_v1");
    } catch (e) {}
    window.RABEZ_APPLIED_COUPON = null;
  } else {
    try {
      const cachedCoupon = localStorage.getItem("rabez_applied_coupon_v1");
      if (cachedCoupon) {
        appliedCoupon = JSON.parse(cachedCoupon);
        window.RABEZ_APPLIED_COUPON = appliedCoupon;
      }
    } catch (e) {
      appliedCoupon = null;
    }
  }

  function updateCouponBoxesVisibility(hasCoupons) {
    window.RABEZ_HAS_COUPONS = Boolean(hasCoupons);
    const drawerCoupon = document.querySelector(".rabez-drawer-coupon");
    const checkoutCoupon = document.querySelector(".rabez-checkout-coupon");
    const drawerDiscountRow = document.querySelector(".js-drawer-discount-row");
    const checkoutDiscountRow = document.querySelector(".js-checkout-discount-row");

    if (drawerCoupon) {
      drawerCoupon.style.display = hasCoupons ? "block" : "none";
    }
    if (checkoutCoupon) {
      checkoutCoupon.style.display = hasCoupons ? "block" : "none";
    }
    if (!hasCoupons) {
      if (drawerDiscountRow) drawerDiscountRow.style.display = "none";
      if (checkoutDiscountRow) checkoutDiscountRow.style.display = "none";
      appliedCoupon = null;
      window.RABEZ_APPLIED_COUPON = null;
      try {
        localStorage.removeItem("rabez_applied_coupon_v1");
      } catch (e) {}
    }
  }

  async function syncActiveCouponsStatus() {
    try {
      const res = await fetch("/api/coupons/active-status", {
        headers: { "Accept": "application/json" }
      });
      if (res.ok) {
        const data = await res.json();
        if (data && data.success) {
          updateCouponBoxesVisibility(Boolean(data.has_active));
        }
      }
    } catch (e) {}
  }

  function toggleCheckoutCoupon(forceOpen) {
    const fields = document.getElementById("checkout-coupon-fields");
    const chevron = document.querySelector(".js-checkout-coupon-chevron");
    if (!fields) return;
    const isCurrentlyOpen = fields.style.display !== "none" && fields.style.display !== "";
    const shouldOpen = forceOpen !== undefined ? forceOpen : !isCurrentlyOpen;
    fields.style.display = shouldOpen ? "block" : "none";
    if (chevron) {
      chevron.style.transform = shouldOpen ? "rotate(180deg)" : "rotate(0deg)";
    }
    if (shouldOpen) {
      const input = document.getElementById("checkout-coupon-code");
      if (input) input.focus();
    }
  }

  function toggleDrawerCoupon(forceOpen) {
    const fields = document.getElementById("drawer-coupon-fields");
    const chevron = document.querySelector(".js-drawer-coupon-chevron");
    if (!fields) return;
    const isCurrentlyOpen = fields.style.display !== "none" && fields.style.display !== "";
    const shouldOpen = forceOpen !== undefined ? forceOpen : !isCurrentlyOpen;
    fields.style.display = shouldOpen ? "block" : "none";
    if (chevron) {
      chevron.style.transform = shouldOpen ? "rotate(180deg)" : "rotate(0deg)";
    }
    if (shouldOpen) {
      const input = document.getElementById("drawer-coupon-code");
      if (input) input.focus();
    }
  }

  window.toggleCheckoutCoupon = toggleCheckoutCoupon;
  window.toggleDrawerCoupon = toggleDrawerCoupon;

  function setCouponFeedback(message, isSuccess = false) {
    const msgEls = [
      document.getElementById("checkout-coupon-msg"),
      document.getElementById("drawer-coupon-msg")
    ];
    msgEls.forEach((el) => {
      if (!el) return;
      if (!message) {
        el.style.display = "none";
        el.textContent = "";
      } else {
        el.style.display = "block";
        el.style.color = isSuccess ? "#22C55E" : "#EF4444";
        el.textContent = message;
        if (el.id === "checkout-coupon-msg") toggleCheckoutCoupon(true);
        if (el.id === "drawer-coupon-msg") toggleDrawerCoupon(true);
      }
    });
  }

  function setCouponButtonsState(loading = false) {
    const buttons = [
      document.getElementById("js-apply-coupon-btn"),
      document.getElementById("js-drawer-apply-coupon")
    ];
    buttons.forEach((btn) => {
      if (!btn) return;
      btn.disabled = loading;
      btn.textContent = loading ? "Checking..." : "Apply";
    });
  }

  function syncCouponInputs(code = "") {
    const inputs = [
      document.getElementById("checkout-coupon-code"),
      document.getElementById("drawer-coupon-code")
    ];
    inputs.forEach((input) => {
      if (input && (!input.value || input.value !== code)) {
        input.value = code;
      }
    });
  }

  function removeAppliedCoupon() {
    appliedCoupon = null;
    window.RABEZ_APPLIED_COUPON = null;
    try {
      localStorage.removeItem("rabez_applied_coupon_v1");
    } catch (e) {}
    syncCouponInputs("");
    setCouponFeedback("");
    updateCartUI();
    showToast("Coupon Removed", "Discount coupon has been cleared.");
  }

  async function applyCouponCode(code, options = {}) {
    if (window.RABEZ_HAS_COUPONS === false) {
      appliedCoupon = null;
      window.RABEZ_APPLIED_COUPON = null;
      try {
        localStorage.removeItem("rabez_applied_coupon_v1");
      } catch (e) {}
      return;
    }
    const cleanCode = (code || "").trim().toUpperCase();
    if (!cleanCode) {
      if (!options.silent) {
        setCouponFeedback("Please enter a coupon code.", false);
      }
      return;
    }

    const { subtotal } = getCartTotals();
    if (!options.silent) {
      setCouponButtonsState(true);
    }

    try {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content || "";
      const res = await fetch("/api/apply-coupon", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "X-CSRF-TOKEN": csrf
        },
        body: JSON.stringify({ code: cleanCode, subtotal: subtotal })
      });
      const data = await res.json();
      setCouponButtonsState(false);

      if (data.success) {
        appliedCoupon = {
          code: data.code,
          discount: Number(data.discount) || 0,
          description: data.description || ""
        };
        window.RABEZ_APPLIED_COUPON = appliedCoupon;
        try {
          localStorage.setItem("rabez_applied_coupon_v1", JSON.stringify(appliedCoupon));
        } catch (e) {}
        syncCouponInputs(data.code);
        const msg = data.message || `Coupon ${data.code} applied! Saved ৳${data.discount}`;
        setCouponFeedback(msg, true);
        updateCartUI();
        if (!options.silent) {
          showToast("Coupon Applied!", `${data.code} (Saved ৳${data.discount})`);
        }
      } else {
        appliedCoupon = null;
        window.RABEZ_APPLIED_COUPON = null;
        try {
          localStorage.removeItem("rabez_applied_coupon_v1");
        } catch (e) {}
        if (!options.silent) {
          setCouponFeedback(data.message || "Invalid coupon code.", false);
        } else {
          setCouponFeedback("");
        }
        updateCartUI();
      }
    } catch (err) {
      setCouponButtonsState(false);
      if (!options.silent) {
        setCouponFeedback("Error applying coupon: " + err.message, false);
      }
    }
  }

  /* --- Checkout Page Order Summary --- */
  function renderCheckoutOrderSummary() {
    const container = document.querySelector(".js-checkout-items");
    if (!container) return;

    const shippingRadio = document.querySelector('input[name="shipping_zone"]:checked');
    const shippingInput = document.querySelector('input[name="shipping_zone"]');
    const shippingFee = shippingRadio ? Number(shippingRadio.value) : (shippingInput ? Number(shippingInput.value) : 130);
    const { subtotal } = getCartTotals();
    const discount = appliedCoupon ? Number(appliedCoupon.discount || 0) : 0;
    const grandTotal = Math.max(0, subtotal - discount) + (cart.length ? shippingFee : 0);

    if (!cart.length) {
      container.innerHTML = `<p style="padding:16px 0; color:#6E7672;">No jerseys in your bag yet. <a href="/shop" style="color:#008A00; font-weight:700;">Browse Shop &rarr;</a></p>`;
    } else {
      container.innerHTML = cart
        .map(
          (item) => `
          <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #E4E7E4; font-size:0.88rem;">
            <div>
              <strong>${item.name}</strong>
              <div style="font-size:0.76rem; color:#6E7672;">${escapeHtml(item.variantTitle ? item.variantTitle : 'Size: ' + item.size)} &times; ${item.qty} ${
            formatCustomPrintLabel(item) ? `| Print: ${formatCustomPrintLabel(item)}` : ""
          }</div>
            </div>
            <strong>${formatBDT(item.price * item.qty)}</strong>
          </div>
        `
        )
        .join("");
    }

    const subEl = document.querySelector(".js-checkout-subtotal");
    const shipEl = document.querySelector(".js-checkout-shipping");
    const totalEl = document.querySelector(".js-checkout-total");
    if (subEl) subEl.textContent = formatBDT(subtotal);
    if (shipEl) shipEl.textContent = formatBDT(cart.length ? shippingFee : 0);
    if (totalEl) totalEl.textContent = formatBDT(grandTotal);

    const discountRow = document.querySelector(".js-checkout-discount-row");
    const discountCodeEl = document.querySelector(".js-applied-coupon-code");
    const discountEl = document.querySelector(".js-checkout-discount");
    const checkoutCouponInput = document.getElementById("checkout-coupon-code");
    if (discountRow) {
      if (appliedCoupon && discount > 0) {
        discountRow.style.display = "flex";
        if (discountCodeEl) discountCodeEl.textContent = appliedCoupon.code;
        if (discountEl) discountEl.textContent = formatBDT(discount);
        if (checkoutCouponInput && !checkoutCouponInput.value) checkoutCouponInput.value = appliedCoupon.code;
      } else {
        discountRow.style.display = "none";
      }
    }
  }

  /* --- Single Product Page Loader (product.html?id=...) --- */
  function initSingleProductPage() {
    const root = document.querySelector(".js-single-product-root");
    if (!root || root.dataset.productId || !window.RABEZ_PRODUCTS) return;

    const params = new URLSearchParams(window.location.search);
    const idParam = Number(params.get("id")) || 1324;
    const product = getProductById(idParam) || window.RABEZ_PRODUCTS[0];

    document.title = `${product.name} – RABEZ Official Jersey Store`;

    const imgMain = root.querySelector(".js-sp-main-img");
    const thumbsWrap = root.querySelector(".js-sp-thumbs");
    const titleEl = root.querySelector(".js-sp-title");
    const clubEl = root.querySelector(".js-sp-club");
    const skuEl = root.querySelector(".js-sp-sku");
    const priceEl = root.querySelector(".js-sp-price");
    const descEl = root.querySelector(".js-sp-desc");
    const swatchesWrap = root.querySelector(".js-sp-swatches");
    const addBtn = root.querySelector(".js-sp-add-btn");

    if (imgMain) imgMain.src = product.image;
    if (titleEl) titleEl.textContent = product.name;
    if (clubEl) clubEl.textContent = `${product.clubLabel} • ${product.sleeveLabel}`;
    if (skuEl) skuEl.textContent = product.sku;
    if (priceEl) priceEl.textContent = formatBDT(product.price);
    if (descEl) descEl.textContent = product.shortDesc;
    if (addBtn) addBtn.dataset.productId = product.id;

    if (window.RABEZ_ANALYTICS && typeof window.RABEZ_ANALYTICS.trackViewContent === "function") {
      window.RABEZ_ANALYTICS.trackViewContent(product);
    }

    if (thumbsWrap && product.gallery) {
      thumbsWrap.innerHTML = product.gallery
        .map(
          (src, idx) => `
          <button type="button" class="rabez-gallery__thumb ${idx === 0 ? "is-active" : ""} js-sp-thumb-btn" data-src="${src}">
            <img src="${src}" alt="${product.name}">
          </button>
        `
        )
        .join("");
    }

    if (swatchesWrap) {
      swatchesWrap.innerHTML = buildSizeSwatchesHTML(product);
    }
  }

  /* --- Global Event Delegation --- */
  document.addEventListener("click", function (e) {
    // Size swatch click (Product Card, Modal, or Single Product)
    const swatch = e.target.closest(".rabez-size-swatch");
    if (swatch && !swatch.disabled) {
      const group = swatch.parentElement;
      group.querySelectorAll(".rabez-size-swatch").forEach((btn) => {
        btn.classList.remove("is-selected");
        btn.setAttribute("aria-pressed", "false");
      });
      swatch.classList.add("is-selected");
      swatch.setAttribute("aria-pressed", "true");

      // Update selected size label in Quick View modal
      const qvModal = swatch.closest(".rabez-modal") || swatch.closest(".rabez-qv-layout");
      if (qvModal) {
        const qvSizeLabel = qvModal.querySelector(".js-qv-selected-size-label");
        if (qvSizeLabel && swatch.dataset.size) {
          qvSizeLabel.textContent = swatch.dataset.size;
        }
      }

      // Update selected size label in Single Product page
      const spRoot = swatch.closest(".js-single-product-root");
      if (spRoot) {
        const spSizeLabel = spRoot.querySelector("#js-selected-size-label");
        if (spSizeLabel && swatch.dataset.size) {
          spSizeLabel.textContent = swatch.dataset.size;
        }
      }

      const wlCard = swatch.closest(".rabez-wl-card");
      if (wlCard) {
        const pid = Number(wlCard.dataset.productId);
        const chosenSize = swatch.dataset.size || "";
        if (pid && chosenSize) {
          wishlistSizes[String(pid)] = chosenSize;
          saveWishlistSizes();
          const sizeLabelEl = wlCard.querySelector(".js-wl-selected-size");
          if (sizeLabelEl) sizeLabelEl.textContent = chosenSize;
          persistWishlistToggleToServer(pid, chosenSize, "save_size");
        }
      }
      return;
    }

    // Add to Cart from Product Card
    const addBtn = e.target.closest(".js-add-to-cart");
    if (addBtn) {
      const card = addBtn.closest(".rabez-product-card");
      const id = Number(addBtn.dataset.productId);
      const prod = getProductById(id);
      const selectedSwatch = card?.querySelector(".rabez-size-swatch.is-selected");
      const size = selectedSwatch ? selectedSwatch.dataset.size : (prod?.defaultSize || "");
      if (!size || (prod && Array.isArray(prod.sizes) && !prod.sizes.length)) {
        if (window.RABEZ_POPUP) window.RABEZ_POPUP.alert("Sorry, this jersey is currently out of stock in all sizes.", { type: "warning", title: "Out of Stock" });
        return;
      }
      addToCart({ id, size, qty: 1 });
      return;
    }

    // Quick View Gallery Thumb Click
    const qvThumb = e.target.closest(".js-qv-thumb");
    if (qvThumb) {
      const modal = qvThumb.closest(".rabez-modal");
      if (modal) {
        modal.querySelectorAll(".js-qv-thumb").forEach((t) => t.classList.remove("is-active"));
        qvThumb.classList.add("is-active");
        const mainImg = modal.querySelector(".js-qv-main-img");
        const pill = modal.querySelector(".js-qv-view-pill");
        if (mainImg) {
          mainImg.style.opacity = "0.2";
          setTimeout(() => {
            mainImg.src = qvThumb.dataset.src;
            mainImg.style.opacity = "1";
          }, 120);
        }
        if (pill) {
          pill.style.display = qvThumb.dataset.isBack === "1" ? "inline-block" : "none";
        }
      }
      return;
    }

    // Quick View Quantity +/- Click
    function updateQuickViewModalPrice(modal) {
      if (!modal) return;
      const addBtn = modal.querySelector(".js-modal-add-cart");
      const addBtnText = modal.querySelector(".js-qv-btn-text");
      const priceDisplay = modal.querySelector(".js-qv-price-display");
      const qtyValEl = modal.querySelector(".js-qv-qty-val");
      const customToggle = modal.querySelector(".js-qv-custom-toggle");
      const productId = Number(addBtn?.dataset.productId);
      const prod = getProductById(productId);
      if (!prod) return;
      const qty = parseInt(qtyValEl?.value || qtyValEl?.textContent.trim(), 10) || 1;
      const hasCustom = prod.hasCustomPrint === true && Boolean(customToggle?.checked);
      const unitPrice = prod.price + (hasCustom ? getCustomPrintFee() : 0);
      if (priceDisplay) priceDisplay.textContent = formatBDT(unitPrice * qty);
      if (addBtnText) addBtnText.textContent = `Add to Bag \u2014 ${formatBDT(unitPrice * qty)}`;
    }

    const qtyBtn = e.target.closest(".js-qv-qty-plus, .js-qv-qty-minus");
    if (qtyBtn) {
      const modal = qtyBtn.closest(".rabez-modal") || qtyBtn.closest(".rabez-qv-layout");
      const qtyValEl = modal?.querySelector(".js-qv-qty-val");
      if (modal && qtyValEl) {
        let qty = parseInt(qtyValEl.value || qtyValEl.textContent.trim(), 10) || 1;
        if (qtyBtn.classList.contains("js-qv-qty-plus")) {
          qty = Math.min(10, qty + 1);
        } else {
          qty = Math.max(1, qty - 1);
        }
        if (qtyValEl.tagName === "INPUT") {
          qtyValEl.value = qty;
        } else {
          qtyValEl.textContent = qty;
        }
        updateQuickViewModalPrice(modal);
      }
      return;
    }

    // Quick View Size Chart Toggle
    const sizeToggle = e.target.closest(".js-qv-size-toggle");
    if (sizeToggle) {
      const modal = sizeToggle.closest(".rabez-modal") || sizeToggle.closest(".rabez-qv-layout");
      const chart = modal?.querySelector(".js-qv-size-chart");
      if (chart) {
        const isHidden = chart.style.display === "none" || !chart.style.display;
        chart.style.display = isHidden ? "block" : "none";
        const labelSpan = sizeToggle.querySelector("span") || sizeToggle;
        labelSpan.textContent = isHidden ? "Close Size Chart" : "Size Chart";
      }
      return;
    }

    // Quick View WhatsApp 1-Click Order
    const qvWaBtn = e.target.closest(".js-qv-whatsapp-order");
    if (qvWaBtn) {
      const id = Number(qvWaBtn.dataset.productId);
      const prod = getProductById(id);
      const modal = qvWaBtn.closest(".rabez-modal") || qvWaBtn.closest(".rabez-qv-layout");
      const selectedSwatch = modal?.querySelector(".rabez-size-swatch.is-selected");
      const size = selectedSwatch ? selectedSwatch.dataset.size : (prod?.defaultSize || "");
      if (!size || (prod && Array.isArray(prod.sizes) && !prod.sizes.length)) {
        if (window.RABEZ_POPUP) window.RABEZ_POPUP.alert("Sorry, this jersey is currently out of stock in all sizes.", { type: "warning", title: "Out of Stock" });
        return;
      }
      const qty = parseInt(modal?.querySelector(".js-qv-qty-val")?.value || modal?.querySelector(".js-qv-qty-val")?.textContent.trim(), 10) || 1;
      const customToggle = modal?.querySelector(".js-qv-custom-toggle");
      const allowCustom = prod?.hasCustomPrint === true && Boolean(customToggle?.checked);
      const customName = allowCustom ? (modal?.querySelector(".js-qv-custom-name")?.value.trim().toUpperCase() || "") : "";
      const customNumber = allowCustom ? (modal?.querySelector(".js-qv-custom-num")?.value.trim() || "") : "";
      const customColor = (allowCustom && (customName || customNumber))
        ? (modal?.querySelector(".js-qv-color-btn.is-active")?.dataset.colorName || "White")
        : "";
      const unitPrice = prod ? (prod.price + (allowCustom ? getCustomPrintFee() : 0)) : 0;
      const total = unitPrice * qty;
      const colorNote = (customColor && customColor !== 'White') ? ` (${customColor} Print)` : '';
      const customLine = allowCustom && (customName || customNumber)
        ? `\n\u2022 Custom Print: ${customName} #${customNumber}${colorNote} (+৳${getCustomPrintFee()})`
        : "";
      const msg = `Hello RABEZ, I would like to order this jersey from Quick View:\n\n` +
        `\u2022 Jersey: ${prod?.name || 'Jersey'}\n` +
        `\u2022 Size: ${size}\n` +
        `\u2022 Quantity: ${qty}${customLine}\n` +
        `\u2022 Total Price: ${formatBDT(total)}\n\n` +
        `My Name & Delivery Address: `;
      const url = `https://api.whatsapp.com/send/?phone=${WHATSAPP_NUMBER}&text=${encodeURIComponent(msg)}`;
      window.open(url, "_blank");
      return;
    }

    // Add to Cart from Quick View Modal
    const modalAddBtn = e.target.closest(".js-modal-add-cart");
    if (modalAddBtn) {
      const id = Number(modalAddBtn.dataset.productId);
      const prod = getProductById(id);
      const modal = modalAddBtn.closest(".rabez-modal") || modalAddBtn.closest(".rabez-qv-layout") || modalAddBtn.closest(".rabez-single-product");
      const selectedSwatch = modal?.querySelector(".rabez-size-swatch.is-selected");
      const size = selectedSwatch ? selectedSwatch.dataset.size : (prod?.defaultSize || "");
      if (!size || (prod && Array.isArray(prod.sizes) && !prod.sizes.length)) {
        if (window.RABEZ_POPUP) window.RABEZ_POPUP.alert("Sorry, this jersey is currently out of stock in all sizes.", { type: "warning", title: "Out of Stock" });
        return;
      }
      const qty = parseInt(modal?.querySelector(".js-qv-qty-val")?.value || modal?.querySelector(".js-qv-qty-val")?.textContent.trim(), 10) || 1;
      const customToggle = modal?.querySelector(".js-qv-custom-toggle");
      const allowCustom = prod?.hasCustomPrint === true && Boolean(customToggle?.checked);
      const customName = allowCustom ? (modal?.querySelector(".js-qv-custom-name")?.value.trim().toUpperCase() || "") : "";
      const customNumber = allowCustom ? (modal?.querySelector(".js-qv-custom-num")?.value.trim() || "") : "";
      const customColor = (allowCustom && (customName || customNumber))
        ? (modal?.querySelector(".js-qv-color-btn.is-active")?.dataset.colorName || "White")
        : "";
      addToCart({ id, size, qty, customName, customNumber, customColor });
      document.querySelector(".js-quickview-backdrop")?.classList.remove("is-open");
      document.body.style.overflow = "";
      return;
    }

    // Add to Cart from Single Product Page
    const spAddBtn = e.target.closest(".js-sp-add-btn");
    if (spAddBtn) {
      const id = Number(spAddBtn.dataset.productId);
      const root = spAddBtn.closest(".js-single-product-root");
      const size = root?.querySelector(".rabez-size-swatch.is-selected")?.dataset.size || "L";
      const qty = Number(root?.querySelector(".js-sp-qty")?.value || 1);
      const customName = root?.querySelector(".js-sp-custom-name")?.value || "";
      const customNumber = root?.querySelector(".js-sp-custom-num")?.value || "";
      addToCart({ id, size, qty, customName, customNumber });
      openCartDrawer();
      return;
    }

    // Quick View Legend Preset Pill Click
    const qvPreset = e.target.closest(".js-qv-preset-pill");
    if (qvPreset) {
      const modal = qvPreset.closest(".rabez-modal") || qvPreset.closest(".rabez-qv-layout");
      const nameInput = modal?.querySelector(".js-qv-custom-name");
      const numInput = modal?.querySelector(".js-qv-custom-num");
      if (nameInput) nameInput.value = qvPreset.dataset.name || "";
      if (numInput) numInput.value = qvPreset.dataset.num || "";
      syncQvCustomPrint(modal);
      return;
    }

    // Quick View Print Color Swatch Click
    const qvColorBtn = e.target.closest(".js-qv-color-btn");
    if (qvColorBtn) {
      const modal = qvColorBtn.closest(".rabez-modal") || qvColorBtn.closest(".rabez-qv-layout");
      modal?.querySelectorAll(".js-qv-color-btn").forEach(b => b.classList.remove("is-active"));
      qvColorBtn.classList.add("is-active");
      const color = qvColorBtn.dataset.color || "#FFFFFF";
      const colorName = qvColorBtn.dataset.colorName || "White";
      const nameLabel = modal?.querySelector(".js-qv-color-name");
      if (nameLabel) nameLabel.textContent = colorName;
      const pName = modal?.querySelector(".js-qv-preview-name");
      const pNum = modal?.querySelector(".js-qv-preview-num");
      if (pName) pName.style.color = color;
      if (pNum) pNum.style.color = color;
      return;
    }

    // Quick View Clear buttons
    const qvClearName = e.target.closest(".js-qv-clear-name");
    if (qvClearName) {
      const modal = qvClearName.closest(".rabez-modal") || qvClearName.closest(".rabez-qv-layout");
      const nameInput = modal?.querySelector(".js-qv-custom-name");
      if (nameInput) {
        nameInput.value = "";
        syncQvCustomPrint(modal);
        nameInput.focus();
      }
      return;
    }
    const qvClearNum = e.target.closest(".js-qv-clear-num");
    if (qvClearNum) {
      const modal = qvClearNum.closest(".rabez-modal") || qvClearNum.closest(".rabez-qv-layout");
      const numInput = modal?.querySelector(".js-qv-custom-num");
      if (numInput) {
        numInput.value = "";
        syncQvCustomPrint(modal);
        numInput.focus();
      }
      return;
    }

    // Remove item from Mini-Cart
    const removeBtn = e.target.closest(".js-remove-cart-item");
    if (removeBtn) {
      removeFromCart(removeBtn.dataset.key);
      if (appliedCoupon) {
        applyCouponCode(appliedCoupon.code, { silent: true });
      }
      return;
    }

    // Quantity Stepper: Plus (+ in Cart Drawer)
    const plusBtn = e.target.closest(".js-cart-qty-plus");
    if (plusBtn) {
      e.preventDefault();
      const key = plusBtn.dataset.key;
      const item = cart.find((i) => i.key === key);
      if (item) {
        item.qty = (item.qty || 1) + 1;
        saveCart();
        if (appliedCoupon) {
          applyCouponCode(appliedCoupon.code, { silent: true });
        }
      }
      return;
    }

    // Quantity Stepper: Minus (- in Cart Drawer)
    const minusBtn = e.target.closest(".js-cart-qty-minus");
    if (minusBtn) {
      e.preventDefault();
      const key = minusBtn.dataset.key;
      const itemIndex = cart.findIndex((i) => i.key === key);
      if (itemIndex > -1) {
        if (cart[itemIndex].qty > 1) {
          cart[itemIndex].qty -= 1;
          saveCart();
          if (appliedCoupon) {
            applyCouponCode(appliedCoupon.code, { silent: true });
          }
        } else {
          removeFromCart(key);
          if (appliedCoupon) {
            applyCouponCode(appliedCoupon.code, { silent: true });
          }
        }
      }
      return;
    }

    // Remove Coupon in Drawer
    if (e.target.closest(".js-drawer-remove-coupon")) {
      e.preventDefault();
      removeAppliedCoupon();
      return;
    }

    // Open/Close Cart Drawer
    if (e.target.closest(".js-open-cart-drawer")) {
      e.preventDefault();
      openCartDrawer();
      return;
    }
    if (e.target.closest(".js-close-cart-drawer") || e.target.classList.contains("js-cart-backdrop")) {
      closeCartDrawer();
      return;
    }

    // Quick View Open/Close
    const qvBtn = e.target.closest(".js-quick-view");
    if (qvBtn) {
      openQuickView(Number(qvBtn.dataset.productId));
      return;
    }
    if (e.target.closest(".js-close-quickview") || e.target.classList.contains("js-quickview-backdrop")) {
      document.querySelector(".js-quickview-backdrop")?.classList.remove("is-open");
      document.body.style.overflow = "";
      return;
    }

    // Wishlist Toggle (Cards, Quick View, Single Product, Wishlist Page)
    const wishBtn = e.target.closest(".js-wishlist-toggle");
    if (wishBtn) {
      const id = Number(wishBtn.dataset.productId);
      const container = wishBtn.closest(".rabez-product-card, .rabez-single-product, .rabez-qv-layout, .rabez-wl-card");
      const selectedSize = container?.querySelector(".rabez-size-swatch.is-selected")?.dataset.size || "";
      toggleWishlist(id, { size: selectedSize });
      return;
    }

    // Wishlist Page: Add Item to Bag (keep in wishlist)
    const wlAddBtn = e.target.closest(".js-wl-add-item");
    if (wlAddBtn) {
      const id = Number(wlAddBtn.dataset.productId);
      const prod = getProductById(id);
      const card = wlAddBtn.closest(".rabez-wl-card");
      const size = card?.querySelector(".rabez-size-swatch.is-selected")?.dataset.size || getPreferredWishlistSize(prod);
      if (!size || (prod && Array.isArray(prod.sizes) && !prod.sizes.length)) {
        if (window.RABEZ_POPUP) window.RABEZ_POPUP.alert("Sorry, this jersey is currently out of stock in all sizes.", { type: "warning", title: "Out of Stock" });
        return;
      }
      addToCart({ id, size, qty: 1, silentToast: true });
      showToast("Added to Bag", "Item added to your bag.", {
        label: "View Bag",
        onClick: () => openCartDrawer()
      });
      return;
    }

    // Wishlist Page: Move Item to Bag (add + remove from wishlist)
    const wlMoveBtn = e.target.closest(".js-wl-move-item");
    if (wlMoveBtn) {
      const id = Number(wlMoveBtn.dataset.productId);
      const prod = getProductById(id);
      const card = wlMoveBtn.closest(".rabez-wl-card");
      const size = card?.querySelector(".rabez-size-swatch.is-selected")?.dataset.size || getPreferredWishlistSize(prod);
      if (!size || (prod && Array.isArray(prod.sizes) && !prod.sizes.length)) {
        if (window.RABEZ_POPUP) window.RABEZ_POPUP.alert("Sorry, this jersey is currently out of stock in all sizes.", { type: "warning", title: "Out of Stock" });
        return;
      }
      addToCart({ id, size, qty: 1, silentToast: true });
      if (wishlist.includes(id)) {
        toggleWishlist(id, { silent: true });
      }
      showToast("Moved to Bag", "Added to your shopping bag.", {
        label: "View Bag",
        onClick: () => openCartDrawer()
      });
      return;
    }

    // Wishlist Page: Single Item WhatsApp Order
    const wlWaItemBtn = e.target.closest(".js-wl-whatsapp-item");
    if (wlWaItemBtn) {
      const id = Number(wlWaItemBtn.dataset.productId);
      const prod = getProductById(id);
      const card = wlWaItemBtn.closest(".rabez-wl-card");
      const size = card?.querySelector(".rabez-size-swatch.is-selected")?.dataset.size || getPreferredWishlistSize(prod) || "L";
      const msg = `Hello RABEZ, I would like to order this jersey from my Wishlist:\n\n` +
        `\u2022 Jersey: ${prod?.name || "Jersey"} (${prod?.sku || "RBZ-" + id})\n` +
        `\u2022 Preferred Size: ${size}\n` +
        `\u2022 Price: ${formatBDT(prod?.price || 0)}\n\n` +
        `My Name & Delivery Address: `;
      window.open(buildWhatsAppOrderURL(msg), "_blank", "noopener");
      return;
    }

    // Wishlist Page: Filter Tabs
    const wlFilterBtn = e.target.closest(".js-wl-filter");
    if (wlFilterBtn) {
      document.querySelectorAll(".js-wl-filter").forEach((b) => b.classList.remove("is-active"));
      wlFilterBtn.classList.add("is-active");
      activeWishlistFilter = wlFilterBtn.dataset.filter || "all";
      renderWishlistPage();
      return;
    }

    if (e.target.closest(".js-wl-reset-filter")) {
      activeWishlistFilter = "all";
      document.querySelectorAll(".js-wl-filter").forEach((b) => b.classList.toggle("is-active", b.dataset.filter === "all"));
      renderWishlistPage();
      return;
    }

    // Wishlist Page: Move All In-Stock Items to Bag
    if (e.target.closest(".js-wl-move-all")) {
      const list = getWishlistProductsList().filter((p) => Array.isArray(p.sizes) && p.sizes.length > 0 && p.isReadyStock !== false);
      if (!list.length) {
        showToast("No Ready-Stock Jerseys", "None of your saved items are currently in stock.", null);
        return;
      }
      list.forEach((p) => {
        const size = getPreferredWishlistSize(p) || p.sizes[0];
        if (size) {
          addToCart({ id: p.id, size, qty: 1, silentToast: true });
          wishlist = wishlist.filter((x) => x !== Number(p.id));
          persistWishlistToggleToServer(p.id, null, "toggle");
        }
      });
      saveWishlist();
      showToast("Moved to Bag", "All in-stock jerseys moved to your bag.", {
        label: "View Bag",
        onClick: () => openCartDrawer()
      });
      openCartDrawer();
      return;
    }

    // Wishlist Page: Clear Entire Wishlist
    if (e.target.closest(".js-wl-clear")) {
      if (!wishlist.length) return;
      const performClear = () => {
        wishlist = [];
        wishlistSizes = {};
        saveWishlist();
        persistWishlistClearToServer();
        showToast("Wishlist Cleared", "All saved jerseys have been removed.", null);
      };
      if (window.RABEZ_POPUP && typeof window.RABEZ_POPUP.confirm === "function") {
        window.RABEZ_POPUP.confirm("Remove all saved jerseys from your wishlist?", {
          type: "warning",
          title: "Clear Wishlist",
          confirmText: "Clear All",
          cancelText: "Keep Items"
        }).then((confirmed) => {
          if (confirmed) performClear();
        });
      } else {
        performClear();
      }
      return;
    }

    // Wishlist Page: Share Wishlist Link
    if (e.target.closest(".js-wl-share")) {
      const ids = getWishlistProductsList().map((p) => p.id);
      if (!ids.length) {
        showToast("Wishlist is Empty", "Save at least one jersey first to generate a share link.", null);
        return;
      }
      const shareUrl = `${window.location.origin}/wishlist?items=${ids.join(",")}`;
      if (navigator.clipboard && typeof navigator.clipboard.writeText === "function") {
        navigator.clipboard.writeText(shareUrl).then(() => {
          showToast("Share Link Copied!", "Anyone with this link can view your saved jerseys.", null);
        }).catch(() => {
          window.prompt("Copy your shareable Wishlist URL:", shareUrl);
        });
      } else {
        window.prompt("Copy your shareable Wishlist URL:", shareUrl);
      }
      return;
    }

    // Wishlist Page: Order All Saved Items via WhatsApp
    if (e.target.closest(".js-wl-whatsapp-all")) {
      const items = getWishlistProductsList();
      if (!items.length) {
        showToast("Wishlist is Empty", "Save at least one jersey first to order via WhatsApp.", null);
        return;
      }
      const total = items.reduce((s, p) => s + Number(p.price || 0), 0);
      const lines = items.map(
        (p, idx) => `${idx + 1}. ${p.name} | Size: ${getPreferredWishlistSize(p) || "L"} = ${formatBDT(p.price)}`
      );
      const msg = `Hello RABEZ, I would like to order these jerseys from my Wishlist:\n\n${lines.join("\n")}\n\nEstimated Total: ${formatBDT(total)}\nMy Name & Delivery Address: `;
      window.open(buildWhatsAppOrderURL(msg), "_blank", "noopener");
      return;
    }

    // Club Filter Pill
    const clubPill = e.target.closest(".js-club-filter");
    if (clubPill) {
      document.querySelectorAll(".js-club-filter").forEach((el) => el.classList.remove("is-active"));
      clubPill.classList.add("is-active");
      activeFilter.club = clubPill.dataset.club || "all";
      renderProductGrids();
      return;
    }

    // Sleeve Filter Switch
    const sleeveBtn = e.target.closest(".js-sleeve-filter");
    if (sleeveBtn) {
      document.querySelectorAll(".js-sleeve-filter").forEach((el) => el.classList.remove("is-active"));
      sleeveBtn.classList.add("is-active");
      activeFilter.sleeve = sleeveBtn.dataset.sleeve || "all";
      renderProductGrids();
      return;
    }

    // Coupon Box Toggles (Checkout & Drawer)
    if (e.target.closest(".js-toggle-checkout-coupon") || (e.target.closest(".rabez-checkout-coupon") && !e.target.closest("#checkout-coupon-fields"))) {
      e.preventDefault();
      toggleCheckoutCoupon();
      return;
    }

    if (e.target.closest(".js-toggle-drawer-coupon") || (e.target.closest(".rabez-drawer-coupon") && !e.target.closest("#drawer-coupon-fields"))) {
      e.preventDefault();
      toggleDrawerCoupon();
      return;
    }

    // Coupon Apply Buttons (Checkout & Drawer)
    if (e.target.closest("#js-apply-coupon-btn")) {
      e.preventDefault();
      const codeInput = document.getElementById("checkout-coupon-code");
      if (codeInput) applyCouponCode(codeInput.value);
      return;
    }

    if (e.target.closest("#js-drawer-apply-coupon")) {
      e.preventDefault();
      const codeInput = document.getElementById("drawer-coupon-code");
      if (codeInput) applyCouponCode(codeInput.value);
      return;
    }

    // Reset Filters
    if (e.target.closest(".js-reset-filters")) {
      activeFilter = { club: "all", sleeve: "all", search: "", sort: "default" };
      document.querySelectorAll(".js-club-filter").forEach((el) => el.classList.toggle("is-active", el.dataset.club === "all"));
      document.querySelectorAll(".js-sleeve-filter").forEach((el) => el.classList.toggle("is-active", el.dataset.sleeve === "all"));
      renderProductGrids();
      return;
    }

    // WhatsApp Checkout Button
    const waBtn = e.target.closest(".js-whatsapp-checkout");
    if (waBtn) {
      e.preventDefault();
      window.open(buildWhatsAppOrderURL(), "_blank", "noopener");
      return;
    }

    // Single Product Thumbnail Click
    const thumbBtn = e.target.closest(".js-sp-thumb-btn");
    if (thumbBtn) {
      document.querySelectorAll(".js-sp-thumb-btn").forEach((b) => b.classList.remove("is-active"));
      thumbBtn.classList.add("is-active");
      const mainImg = document.querySelector(".js-sp-main-img");
      if (mainImg) mainImg.src = thumbBtn.dataset.src;
      return;
    }
  });

  // Search Input & Sort Select
  document.addEventListener("input", function (e) {
    if (e.target.matches(".js-search-input")) {
      activeFilter.search = e.target.value;
      renderProductGrids();
    }
  });

  document.addEventListener("change", function (e) {
    if (e.target.matches(".js-wl-sort")) {
      activeWishlistSort = e.target.value || "recent";
      renderWishlistPage();
      return;
    }
    if (e.target.matches(".js-sort-select")) {
      activeFilter.sort = e.target.value;
      renderProductGrids();
    }
    if (e.target.matches('input[name="shipping_zone"]')) {
      document.querySelectorAll(".rabez-shipping-card").forEach((c) => c.classList.remove("is-selected"));
      e.target.closest(".rabez-shipping-card")?.classList.add("is-selected");
      renderCheckoutOrderSummary();
    }
    if (e.target.matches(".js-qv-custom-toggle")) {
      const modal = e.target.closest(".rabez-modal") || e.target.closest(".rabez-qv-layout");
      const inputs = modal?.querySelector(".js-qv-custom-inputs");
      const studio = modal?.querySelector(".js-qv-custom-studio");
      if (inputs) {
        inputs.style.display = e.target.checked ? "flex" : "none";
      }
      if (studio) {
        studio.classList.toggle("is-active", e.target.checked);
      }
      if (e.target.checked) {
        syncQvCustomPrint(modal);
      }
      const addBtn = modal?.querySelector(".js-modal-add-cart");
      const addBtnText = modal?.querySelector(".js-qv-btn-text");
      const priceDisplay = modal?.querySelector(".js-qv-price-display");
      const qty = parseInt(modal?.querySelector(".js-qv-qty-val")?.value || modal?.querySelector(".js-qv-qty-val")?.textContent.trim(), 10) || 1;
      const prod = getProductById(Number(addBtn?.dataset.productId));
      if (prod) {
        const hasCustom = prod.hasCustomPrint === true && e.target.checked;
        const unitPrice = prod.price + (hasCustom ? getCustomPrintFee() : 0);
        if (priceDisplay) priceDisplay.textContent = formatBDT(unitPrice * qty);
        if (addBtnText) addBtnText.textContent = `Add to Bag \u2014 ${formatBDT(unitPrice * qty)}`;
      }
    }
  });

  document.addEventListener("input", function (e) {
    if (e.target.matches(".js-qv-custom-name") || e.target.matches(".js-qv-custom-num")) {
      const modal = e.target.closest(".rabez-modal") || e.target.closest(".rabez-qv-layout");
      syncQvCustomPrint(modal);
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      document.querySelector(".js-quickview-backdrop")?.classList.remove("is-open");
      document.body.style.overflow = "";
    }
    if (e.key === "Enter" && e.target) {
      if (e.target.id === "checkout-coupon-code" || e.target.id === "drawer-coupon-code") {
        e.preventDefault();
        applyCouponCode(e.target.value);
      }
    }
  });

  // Expose API globally for RABEZ UI components and Livewire/SPA integrations
  window.RABEZ_STORE = {
    addToCart,
    clearCart,
    getCart: () => cart.slice(),
    toggleWishlist,
    renderWishlistPage,
    openCartDrawer,
    openQuickView,
    formatBDT,
    buildWhatsAppOrderURL,
    applyCouponCode,
    removeAppliedCoupon,
    toggleCheckoutCoupon,
    toggleDrawerCoupon,
    getAppliedCoupon: () => appliedCoupon
  };

  document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const paramSleeve = params.get("sleeve");
    const paramClub = params.get("club");
    const paramSearch = params.get("search");

    if (paramSearch) {
      activeFilter.search = paramSearch;
      document.querySelectorAll(".js-search-input").forEach((el) => {
        el.value = paramSearch;
      });
    }

    if (paramSleeve) {
      activeFilter.sleeve = paramSleeve;
      document.querySelectorAll(".js-sleeve-filter").forEach((el) => {
        el.classList.toggle("is-active", el.dataset.sleeve === paramSleeve);
      });
    }
    if (paramClub) {
      activeFilter.club = paramClub;
      document.querySelectorAll(".js-club-filter").forEach((el) => {
        el.classList.toggle("is-active", el.dataset.club === paramClub);
      });
    }

    // Sanitize cached cart items against live product custom-print eligibility
    if (Array.isArray(window.RABEZ_PRODUCTS) && window.RABEZ_PRODUCTS.length) {
      let cartSanitized = false;
      cart = cart.map((item) => {
        const prod = getProductById(item.id);
        if (prod && prod.hasCustomPrint !== true && (item.customName || item.customNumber)) {
          cartSanitized = true;
          return {
            ...item,
            key: `${prod.id}_${item.variantId || item.size}__`,
            customName: "",
            customNumber: "",
            price: item.price ? (item.price - getCustomPrintFee()) : Number(prod.price)
          };
        }
        return item;
      });
      if (cartSanitized) saveCart();
    }

    renderProductGrids();
    initSingleProductPage();
    updateCartUI();
    initWishlistSyncAndRender();
    syncActiveCouponsStatus();
  });
})();

