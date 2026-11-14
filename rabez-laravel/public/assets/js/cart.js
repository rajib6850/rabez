;/**
 * RABEZ Modern Store Cart, Variations, Filters & Checkout Engine (assets/js/cart.js)
 * Official WhatsApp: +8801612359652 | Currency: BDT (৳)
 */
(function () {
  "use strict";

  const STORAGE_CART_KEY = "rabez_wc_cart_v1";
  const STORAGE_WISHLIST_KEY = "rabez_wc_wishlist_v1";
  const WHATSAPP_NUMBER = "8801612359652";
  function getCustomPrintFee() {
    const fee = Number(window.RABEZ_CUSTOM_PRINT_FEE);
    return Number.isFinite(fee) && fee >= 0 ? fee : 250;
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

  let cart = loadJSON(STORAGE_CART_KEY, [
    {
      key: "1324_L_",
      id: 1324,
      sku: "RMA-HOME-HS",
      name: "Real Madrid 26/27 Home Half Sleeve Jersey",
      size: "L",
      customName: "",
      customNumber: "",
      price: 1150,
      qty: 1,
      image: "/assets/images/products/real-madrid-home-hs.jpg"
    }
  ]).map((item) => ({
    ...item,
    image: resolveImageURL(item.image)
  }));
  let wishlist = loadJSON(STORAGE_WISHLIST_KEY, []);

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

  function saveWishlist() {
    try {
      localStorage.setItem(STORAGE_WISHLIST_KEY, JSON.stringify(wishlist));
    } catch (e) {}
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

  /* --- Render Product Card (WooCommerce li.product structure) --- */
  function createProductCardHTML(product) {
    const isWished = wishlist.includes(product.id);
    const isAdded = isProductInCart(product.id);
    const badgeHTML = product.badge
      ? `<span class="rabez-badge rabez-badge--${product.badgeType || "neon"}">${product.badge}</span>`
      : "";
    const regPriceHTML = product.regularPrice
      ? `<del><span class="woocommerce-Price-amount amount">৳ ${product.regularPrice.toLocaleString("en-IN")}</span></del>`
      : "";

    const swatchesHTML = product.sizes
      .map(
        (s) =>
          `<button type="button" class="rabez-size-swatch ${
            s === product.defaultSize ? "is-selected" : ""
          }" data-size="${s}" aria-pressed="${s === product.defaultSize}">${s}</button>`
      )
      .join("");

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
                    data-product-id="${product.id}">
              ${getAddButtonInnerHTML(isAdded)}
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
    let id, size, qty = 1, customName = "", customNumber = "";

    if (typeof arg1 === "object" && arg1 !== null) {
      id = Number(arg1.id);
      size = arg1.size;
      qty = arg1.qty !== undefined ? Number(arg1.qty) : 1;
      customName = arg1.customName || "";
      customNumber = arg1.customNumber || "";
    } else {
      id = Number(arg1);
      size = arg2;
      qty = arg3 !== undefined ? Number(arg3) : 1;
      if (typeof arg4 === "object" && arg4 !== null) {
        customName = arg4.customName || "";
        customNumber = arg4.customNumber || "";
      }
    }

    let product = getProductById(id);
    if (!product && typeof arg1 === "object" && arg1 !== null) {
      product = {
        id: id,
        sku: arg1.sku || ("RBZ-" + id),
        name: arg1.name || "Football Jersey",
        price: Number(arg1.price) || 1150,
        image: resolveImageURL(arg1.image),
        defaultSize: size || "L"
      };
      window.RABEZ_PRODUCTS = window.RABEZ_PRODUCTS || [];
      window.RABEZ_PRODUCTS.push(product);
    }
    if (!product) return;

    const allowCustomPrint = product.hasCustomPrint === true;
    const cleanName = allowCustomPrint ? (customName || "").trim().toUpperCase() : "";
    const cleanNum = allowCustomPrint ? (customNumber || "").trim() : "";
    const hasCustom = Boolean(allowCustomPrint && (cleanName || cleanNum));
    const unitPrice = product.price + (hasCustom ? getCustomPrintFee() : 0);
    const key = `${product.id}_${size}_${cleanName}_${cleanNum}`;

    const existing = cart.find((item) => item.key === key);
    if (existing) {
      existing.qty += Number(qty);
    } else {
      cart.push({
        key,
        id: product.id,
        sku: product.sku,
        name: product.name,
        size: size || product.defaultSize || "L",
        customName: cleanName,
        customNumber: cleanNum,
        price: unitPrice,
        qty: Number(qty),
        image: resolveImageURL(product.image)
      });
    }

    saveCart();
    if (window.RABEZ_ANALYTICS && typeof window.RABEZ_ANALYTICS.trackAddToCart === "function") {
      window.RABEZ_ANALYTICS.trackAddToCart(product, size);
    }
    showToast(`${product.name} (Size ${size})`, hasCustom ? `With Custom Print: ${cleanName} #${cleanNum}` : `Added to your bag — ${formatBDT(unitPrice)}`);
  }

  function removeFromCart(key) {
    cart = cart.filter((item) => item.key !== key);
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
            const customMeta =
              item.customName || item.customNumber
                ? `<div style="color:#04F404; font-weight:700; font-size:0.73rem; margin-top:2px;">Print: ${item.customName} #${item.customNumber} (+৳250)</div>`
                : "";
            return `
              <div class="rabez-cart-item woocommerce-mini-cart-item mini_cart_item" style="display:flex; align-items:center; gap:12px; padding:12px; background:#131A16; border:1px solid #1F2823; border-radius:8px; margin-bottom:10px;">
                <img src="${resolveImageURL(item.image)}" alt="${item.name}" class="rabez-cart-item__img" style="width:58px; height:74px; object-fit:cover; border-radius:6px; border:1px solid #2B3830; flex-shrink:0; background:#19201C;" onerror="this.src='/assets/images/products/real-madrid-home-hs.jpg'">
                <div style="flex:1; min-width:0;">
                  <div class="rabez-cart-item__title" style="font-weight:700; font-size:13px; color:#FFFFFF; line-height:1.3; margin-bottom:4px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${item.name}</div>
                  <div class="rabez-cart-item__meta" style="display:flex; align-items:center; gap:8px; margin:4px 0;">
                    <span style="font-size:11.5px; color:#9CA3AF;">Size: <strong style="color:#04F404;">${item.size}</strong></span>
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

  function updateWishlistUI() {
    document.querySelectorAll(".js-wishlist-count").forEach((el) => {
      el.textContent = wishlist.length;
    });
  }

  /* --- Toast Notification --- */
  let toastTimer = null;
  function showToast(title, sub) {
    let toast = document.querySelector(".rabez-wc-notice");
    if (!toast) {
      toast = document.createElement("div");
      toast.className = "rabez-wc-notice";
      toast.setAttribute("role", "status");
      document.body.appendChild(toast);
    }
    toast.innerHTML = `
      <div style="flex:1;">
        <div class="rabez-wc-notice__title">${title}</div>
        <div class="rabez-wc-notice__sub">${sub}</div>
      </div>
      <button type="button" class="rabez-btn rabez-btn--primary rabez-btn--sm js-open-cart-drawer">View Bag</button>
    `;
    requestAnimationFrame(() => toast.classList.add("is-visible"));
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove("is-visible"), 3600);
  }

  /* --- Cart Drawer Open / Close --- */
  function openCartDrawer() {
    document.querySelector(".js-cart-drawer")?.classList.add("is-open");
    document.querySelector(".js-cart-backdrop")?.classList.add("is-open");
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

    const isAdded = isProductInCart(product.id);
    const swatches = product.sizes
      .map(
        (s) =>
          `<button type="button" class="rabez-size-swatch ${
            s === product.defaultSize ? "is-selected" : ""
          }" data-size="${s}" aria-pressed="${s === product.defaultSize}">${s}</button>`
      )
      .join("");

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
            <span class="rabez-card-flip-pill js-qv-view-pill" style="display:none;">Back View</span>
          </div>
        </div>

        <!-- Right: Summary & Interactive Buying Form -->
        <div class="rabez-qv-summary">
          <div class="rabez-summary__breadcrumb">${product.clubLabel} &bull; ${product.sleeveLabel} &bull; SKU: ${product.sku}</div>
          <h2 class="rabez-summary__title" style="font-size:1.6rem; margin-bottom:6px; line-height:1.2;">${product.name}</h2>
          
          <div class="rabez-summary__price-row" style="margin-bottom:10px; padding-bottom:10px;">
            <span class="rabez-summary__price js-qv-price-display">${formatBDT(product.price)}</span>
            <span class="rabez-summary__stock" style="background:#EBFDF2; color:#047857; font-weight:700; font-size:0.75rem; padding:3px 9px; border-radius:12px; border:1px solid #A7F3D0; display:inline-flex; align-items:center; gap:5px;">
              <span style="display:inline-block; width:6px; height:6px; background:#10B981; border-radius:50%;"></span> Ready Stock (BD)
            </span>
          </div>

          <p class="rabez-summary__desc" style="font-size:0.84rem; line-height:1.45; color:var(--rabez-text-body); margin-bottom:10px;">${product.shortDesc}</p>

          <!-- Size Selection & Size Chart Toggle -->
          <div class="rabez-variation-block" style="margin-bottom:10px;">
            <div class="rabez-variation-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
              <span style="font-weight:700; font-size:0.88rem; color:var(--rabez-text-main);">Select Size:</span>
              <button type="button" class="rabez-qv-size-chart-toggle js-qv-size-toggle" style="background:none; border:none; color:#008A00; font-size:0.82rem; font-weight:700; text-decoration:underline; cursor:pointer;">
                📐 Size Chart
              </button>
            </div>
            <div class="rabez-size-swatches js-modal-swatches">
              ${swatches}
            </div>

            <!-- Collapsible Size Chart -->
            <div class="rabez-qv-size-chart js-qv-size-chart" style="display:none; margin-top:8px; background:#F8FAF9; border:1px solid var(--rabez-border); border-radius:var(--rabez-radius-sm); padding:8px 10px; font-size:0.8rem;">
              <div style="font-weight:700; margin-bottom:4px; color:var(--rabez-text-main); font-size:0.8rem;">Official Size Chart (Inches):</div>
              <table style="width:100%; border-collapse:collapse; font-size:0.78rem; text-align:center;">
                <thead>
                  <tr style="background:#E9ECEF; color:#111;">
                    <th style="padding:3px 5px;">Size</th>
                    <th style="padding:3px 5px;">Chest</th>
                    <th style="padding:3px 5px;">Length</th>
                  </tr>
                </thead>
                <tbody>
                  <tr style="border-bottom:1px solid #DEE2E6;"><td><strong>S</strong></td><td>36" - 38"</td><td>27"</td></tr>
                  <tr style="border-bottom:1px solid #DEE2E6;"><td><strong>M</strong></td><td>38" - 40"</td><td>28"</td></tr>
                  <tr style="border-bottom:1px solid #DEE2E6;"><td><strong>L</strong></td><td>40" - 42"</td><td>29"</td></tr>
                  <tr style="border-bottom:1px solid #DEE2E6;"><td><strong>XL</strong></td><td>42" - 44"</td><td>30"</td></tr>
                  <tr><td><strong>XXL</strong></td><td>44" - 46"</td><td>31"</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Optional Custom Name & Number Addon (Only when enabled on this product) -->
          ${product.hasCustomPrint === true ? `
            <div class="rabez-qv-print-box" style="border:1px solid var(--rabez-border); border-radius:6px; padding:10px 12px; margin-bottom:12px; background:#F8FAF9;">
              <label style="display:flex; align-items:center; justify-content:space-between; gap:8px; cursor:pointer; user-select:none; flex-wrap:wrap;">
                <span style="font-weight:700; font-size:0.84rem; color:var(--rabez-text-main); display:flex; align-items:center; gap:7px;">
                  <input type="checkbox" class="js-qv-custom-toggle" style="accent-color:#04F404; width:15px; height:15px; cursor:pointer;">
                  <span>+ Add Custom Name &amp; Number Print</span>
                </span>
                <span style="color:#008A00; font-weight:800; font-size:0.8rem;">+ ৳ ${getCustomPrintFee()}</span>
              </label>
              <div class="rabez-qv-print-inputs js-qv-custom-inputs" style="display:none; margin-top:10px; gap:8px; grid-template-columns:2fr 1fr;">
                <input type="text" class="js-qv-custom-name" maxlength="12" placeholder="Name (e.g. MESSI)" style="width:100%; height:36px; background:#FFFFFF; border:1px solid var(--rabez-border-strong); border-radius:4px; padding:0 10px; color:#111; font-size:12.5px; text-transform:uppercase;">
                <input type="text" class="js-qv-custom-num" maxlength="2" placeholder="No. (10)" style="width:100%; height:36px; background:#FFFFFF; border:1px solid var(--rabez-border-strong); border-radius:4px; padding:0 10px; color:#111; font-size:12.5px;">
              </div>
            </div>
          ` : ''}

          <!-- Quantity Selector Row -->
          <div class="rabez-qv-qty-row" style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <span style="font-weight:700; font-size:0.88rem; color:var(--rabez-text-main);">Quantity:</span>
            <div class="rabez-qty-control" style="display:inline-flex; align-items:center; border:1.5px solid var(--rabez-border); border-radius:6px; background:#F8FAF9; overflow:hidden;">
              <button type="button" class="rabez-qty-btn js-qv-qty-minus" style="width:32px; height:32px; background:none; border:none; font-size:15px; font-weight:700; cursor:pointer; color:var(--rabez-text-main); display:flex; align-items:center; justify-content:center;" aria-label="Decrease quantity">&minus;</button>
              <span class="js-qv-qty-val" style="width:34px; text-align:center; font-weight:800; font-size:0.92rem; color:var(--rabez-text-main);">1</span>
              <button type="button" class="rabez-qty-btn js-qv-qty-plus" style="width:32px; height:32px; background:none; border:none; font-size:15px; font-weight:700; cursor:pointer; color:var(--rabez-text-main); display:flex; align-items:center; justify-content:center;" aria-label="Increase quantity">&plus;</button>
            </div>
          </div>

          <!-- Action Buttons -->
          <div style="display:grid; gap:7px;">
            <button type="button" class="rabez-btn rabez-btn--primary rabez-btn--full js-modal-add-cart ${isAdded ? "is-added added" : ""}" data-product-id="${product.id}" style="padding:11px 18px; font-weight:800; font-size:0.92rem; display:flex; align-items:center; justify-content:center; gap:8px;">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5a1.5 1.5 0 0 1-1.5 1.5h-14A1.5 1.5 0 0 1 3.5 21l1-12.5z"/></svg>
              <span class="js-qv-btn-text">Add to Bag &mdash; ${formatBDT(product.price)}</span>
            </button>

            <button type="button" class="rabez-btn js-qv-whatsapp-order" data-product-id="${product.id}" style="background:#25D366; color:#FFFFFF; border:none; border-radius:var(--rabez-radius-sm); padding:10px 16px; font-weight:800; font-size:0.9rem; display:flex; align-items:center; justify-content:center; gap:8px; cursor:pointer; transition:background 0.18s ease;">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              <span>Order via WhatsApp</span>
            </button>

            <a href="/product/${product.slug || product.id}" class="rabez-btn rabez-btn--outline rabez-btn--full" style="padding:9px 16px; font-size:0.84rem; text-align:center;">
              ${product.hasCustomPrint === true ? 'View Full Details &amp; Custom Print &rarr;' : 'View Full Details &rarr;'}
            </a>
          </div>

          <!-- Trust Perks Row -->
          <div class="rabez-qv-perks" style="display:grid; grid-template-columns: repeat(3, 1fr); gap:6px; margin-top:12px; padding-top:10px; border-top:1px solid var(--rabez-border); font-size:0.72rem; text-align:center; color:var(--rabez-text-body);">
            <div style="display:flex; flex-direction:column; align-items:center; gap:2px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#008A00" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
                <path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/>
                <circle cx="7" cy="18" r="2"/>
                <circle cx="17" cy="18" r="2"/>
              </svg>
              <span style="font-weight:700; line-height:1.2;">Cash on Delivery<br><small style="color:var(--rabez-text-muted); font-size:0.68rem;">All Over BD</small></span>
            </div>
            <div style="display:flex; flex-direction:column; align-items:center; gap:2px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#008A00" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 0 1 15-6.7L21 8"/>
                <path d="M21 3v5h-5"/>
                <path d="M21 12a9 9 0 0 1-15 6.7L3 16"/>
                <path d="M3 21v-5h5"/>
              </svg>
              <span style="font-weight:700; line-height:1.2;">3-Day Exchange<br><small style="color:var(--rabez-text-muted); font-size:0.68rem;">Easy size swap</small></span>
            </div>
            <div style="display:flex; flex-direction:column; align-items:center; gap:2px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#008A00" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <path d="m9 12 2 2 4-4"/>
              </svg>
              <span style="font-weight:700; line-height:1.2;">100% Quality<br><small style="color:var(--rabez-text-muted); font-size:0.68rem;">Breathable Fabric</small></span>
            </div>
          </div>
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
      const msg = "আসসালামু আলাইকুম, RABEZ-এর জার্সি কালেকশন দেখতে চাই এবং অর্ডার করতে চাই।";
      return `https://api.whatsapp.com/send/?phone=${WHATSAPP_NUMBER}&text=${encodeURIComponent(msg)}`;
    }
    const lines = cart.map(
      (item, idx) =>
        `${idx + 1}. ${item.name} | Size: ${item.size} | Qty: ${item.qty}${
          item.customName || item.customNumber ? ` | Print: ${item.customName} #${item.customNumber}` : ""
        } = ৳${item.price * item.qty}`
    );
    const fullMsg = `আসসালামু আলাইকুম RABEZ,\nআমি নিচের জার্সিগুলো অর্ডার করতে চাই:\n\n${lines.join(
      "\n"
    )}\n\nSubtotal: ৳${subtotal}\nআমার নাম ও ঠিকানা: `;
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
              <div style="font-size:0.76rem; color:#6E7672;">Size: ${item.size} &times; ${item.qty} ${
            item.customName ? `| Print: ${item.customName} #${item.customNumber}` : ""
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
      swatchesWrap.innerHTML = product.sizes
        .map(
          (s) => `
          <button type="button" class="rabez-size-swatch ${
            s === product.defaultSize ? "is-selected" : ""
          }" data-size="${s}">${s}</button>
        `
        )
        .join("");
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
      return;
    }

    // Add to Cart from Product Card
    const addBtn = e.target.closest(".js-add-to-cart");
    if (addBtn) {
      const card = addBtn.closest(".rabez-product-card");
      const id = Number(addBtn.dataset.productId);
      const selectedSwatch = card?.querySelector(".rabez-size-swatch.is-selected");
      const size = selectedSwatch ? selectedSwatch.dataset.size : "L";
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
      const qty = parseInt(qtyValEl?.textContent.trim(), 10) || 1;
      const hasCustom = prod.hasCustomPrint === true && Boolean(customToggle?.checked);
      const unitPrice = prod.price + (hasCustom ? getCustomPrintFee() : 0);
      if (priceDisplay) priceDisplay.textContent = formatBDT(unitPrice * qty);
      if (addBtnText) addBtnText.textContent = `Add to Bag \u2014 ${formatBDT(unitPrice * qty)}`;
    }

    const qtyBtn = e.target.closest(".js-qv-qty-plus, .js-qv-qty-minus");
    if (qtyBtn) {
      const modal = qtyBtn.closest(".rabez-modal");
      const qtyValEl = modal?.querySelector(".js-qv-qty-val");
      if (modal && qtyValEl) {
        let qty = parseInt(qtyValEl.textContent.trim(), 10) || 1;
        if (qtyBtn.classList.contains("js-qv-qty-plus")) {
          qty = Math.min(10, qty + 1);
        } else {
          qty = Math.max(1, qty - 1);
        }
        qtyValEl.textContent = qty;
        updateQuickViewModalPrice(modal);
      }
      return;
    }

    // Quick View Size Chart Toggle
    const sizeToggle = e.target.closest(".js-qv-size-toggle");
    if (sizeToggle) {
      const modal = sizeToggle.closest(".rabez-modal");
      const chart = modal?.querySelector(".js-qv-size-chart");
      if (chart) {
        const isHidden = chart.style.display === "none" || !chart.style.display;
        chart.style.display = isHidden ? "block" : "none";
        sizeToggle.textContent = isHidden ? "\u2715 Close Size Chart" : "\ud83d\udcd0 Size Chart";
      }
      return;
    }

    // Quick View WhatsApp 1-Click Order
    const qvWaBtn = e.target.closest(".js-qv-whatsapp-order");
    if (qvWaBtn) {
      const id = Number(qvWaBtn.dataset.productId);
      const prod = getProductById(id);
      const modal = qvWaBtn.closest(".rabez-modal");
      const selectedSwatch = modal?.querySelector(".rabez-size-swatch.is-selected");
      const size = selectedSwatch ? selectedSwatch.dataset.size : (prod?.defaultSize || "L");
      const qty = parseInt(modal?.querySelector(".js-qv-qty-val")?.textContent.trim(), 10) || 1;
      const customToggle = modal?.querySelector(".js-qv-custom-toggle");
      const allowCustom = prod?.hasCustomPrint === true && Boolean(customToggle?.checked);
      const customName = allowCustom ? (modal?.querySelector(".js-qv-custom-name")?.value.trim().toUpperCase() || "") : "";
      const customNumber = allowCustom ? (modal?.querySelector(".js-qv-custom-num")?.value.trim() || "") : "";
      const unitPrice = prod ? (prod.price + (allowCustom ? getCustomPrintFee() : 0)) : 0;
      const total = unitPrice * qty;
      const customLine = allowCustom && (customName || customNumber)
        ? `\n\u2022 কাস্টম প্রিন্ট: ${customName} #${customNumber} (+৳${getCustomPrintFee()})`
        : "";
      const msg = `আসসালামু আলাইকুম RABEZ, আমি Quick View থেকে সরাসরি এই জার্সিটি অর্ডার করতে চাই:\n\n` +
        `\u2022 জার্সি: ${prod?.name || 'Jersey'}\n` +
        `\u2022 সাইজ: ${size}\n` +
        `\u2022 পরিমাণ: ${qty}${customLine}\n` +
        `\u2022 মোট মূল্য: ${formatBDT(total)}\n\n` +
        `আমার নাম ও ডেলিভারি ঠিকানা: `;
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
      const size = selectedSwatch ? selectedSwatch.dataset.size : "L";
      const qty = parseInt(modal?.querySelector(".js-qv-qty-val")?.textContent.trim(), 10) || 1;
      const customToggle = modal?.querySelector(".js-qv-custom-toggle");
      const allowCustom = prod?.hasCustomPrint === true && Boolean(customToggle?.checked);
      const customName = allowCustom ? (modal?.querySelector(".js-qv-custom-name")?.value.trim().toUpperCase() || "") : "";
      const customNumber = allowCustom ? (modal?.querySelector(".js-qv-custom-num")?.value.trim() || "") : "";
      addToCart({ id, size, qty, customName, customNumber });
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

    // Wishlist Toggle
    const wishBtn = e.target.closest(".js-wishlist-toggle");
    if (wishBtn) {
      const id = Number(wishBtn.dataset.productId);
      if (wishlist.includes(id)) {
        wishlist = wishlist.filter((x) => x !== id);
        wishBtn.classList.remove("is-active");
      } else {
        wishlist.push(id);
        wishBtn.classList.add("is-active");
      }
      saveWishlist();
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
    if (e.target.closest(".js-toggle-checkout-coupon")) {
      e.preventDefault();
      toggleCheckoutCoupon();
      return;
    }

    if (e.target.closest(".js-toggle-drawer-coupon")) {
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
      const modal = e.target.closest(".rabez-modal");
      const inputs = modal?.querySelector(".js-qv-custom-inputs");
      if (inputs) {
        inputs.style.display = e.target.checked ? "grid" : "none";
      }
      const addBtn = modal?.querySelector(".js-modal-add-cart");
      const addBtnText = modal?.querySelector(".js-qv-btn-text");
      const priceDisplay = modal?.querySelector(".js-qv-price-display");
      const qty = parseInt(modal?.querySelector(".js-qv-qty-val")?.textContent.trim(), 10) || 1;
      const prod = getProductById(Number(addBtn?.dataset.productId));
      if (prod) {
        const hasCustom = prod.hasCustomPrint === true && e.target.checked;
        const unitPrice = prod.price + (hasCustom ? getCustomPrintFee() : 0);
        if (priceDisplay) priceDisplay.textContent = formatBDT(unitPrice * qty);
        if (addBtnText) addBtnText.textContent = `Add to Bag \u2014 ${formatBDT(unitPrice * qty)}`;
      }
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
            key: `${prod.id}_${item.size}__`,
            customName: "",
            customNumber: "",
            price: Number(prod.price)
          };
        }
        return item;
      });
      if (cartSanitized) saveCart();
    }

    renderProductGrids();
    initSingleProductPage();
    updateCartUI();
    updateWishlistUI();
  });
})();

