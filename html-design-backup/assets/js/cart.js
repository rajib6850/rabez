/**
 * RABEZ Modern Store Cart, Variations, Filters & Checkout Engine (assets/js/cart.js)
 * Official WhatsApp: +8801612359652 | Currency: BDT (৳)
 */
(function () {
  "use strict";

  const STORAGE_CART_KEY = "rabez_wc_cart_v1";
  const STORAGE_WISHLIST_KEY = "rabez_wc_wishlist_v1";
  const WHATSAPP_NUMBER = "8801612359652";
  const CUSTOM_PRINT_FEE = 250;

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
      image: "assets/images/products/real-madrid-home-hs.jpg"
    }
  ]);
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

  function getProductById(id) {
    return (window.RABEZ_PRODUCTS || []).find((p) => Number(p.id) === Number(id));
  }

  function isProductInCart(id) {
    return cart.some((item) => Number(item.id) === Number(id));
  }

  function getAddButtonInnerHTML(isAdded) {
    if (isAdded) {
      return `
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span>Added</span>
      `;
    }
    return `
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
        <line x1="3" y1="6" x2="21" y2="6"/>
        <path d="M16 10a4 4 0 0 1-8 0"/>
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

    return `
      <li class="product type-product post-${product.id} status-publish instock product_cat-${product.sleeve} product-type-variable rabez-product-card"
          data-product-id="${product.id}"
          data-club="${product.club}"
          data-sleeve="${product.sleeve}"
          data-price="${product.price}">
        <div class="rabez-product-card__media">
          <div class="rabez-product-card__badges">
            ${badgeHTML}
          </div>
          <div class="rabez-product-card__actions">
            <button type="button" class="rabez-card-icon-btn js-wishlist-toggle ${isWished ? "is-active" : ""}"
                    data-product-id="${product.id}" aria-label="Add to Wishlist" title="Wishlist">
              <svg viewBox="0 0 24 24" fill="${isWished ? "currentColor" : "none"}" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
              </svg>
            </button>
            <button type="button" class="rabez-card-icon-btn js-quick-view"
                    data-product-id="${product.id}" aria-label="Quick View" title="Quick View">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
          <a href="product.html?id=${product.id}" class="woocommerce-LoopProduct-link">
            <img src="${product.image}" alt="${product.name} - RABEZ Official Jersey Store Bangladesh" loading="lazy" width="500" height="667">
          </a>
        </div>

        <div class="rabez-product-card__body">
          <div class="rabez-product-card__meta">
            <span>${product.clubLabel}</span>
            <span class="rabez-product-card__sleeve">${product.sleeveLabel}</span>
          </div>

          <h3 class="rabez-product-card__title woocommerce-loop-product__title">
            <a href="product.html?id=${product.id}">${product.name}</a>
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
  function addToCart({ id, size, qty = 1, customName = "", customNumber = "" }) {
    const product = getProductById(id);
    if (!product) return;

    const cleanName = (customName || "").trim().toUpperCase();
    const cleanNum = (customNumber || "").trim();
    const hasCustom = Boolean(cleanName || cleanNum);
    const unitPrice = product.price + (hasCustom ? CUSTOM_PRINT_FEE : 0);
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
        image: product.image
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
            <p style="font-weight:700; color:#111111; margin-bottom:6px;">Your jersey bag is empty</p>
            <p style="font-size:0.84rem; margin-bottom:18px;">Pick your club kit & size to start your order.</p>
            <a href="shop.html" class="rabez-btn rabez-btn--dark rabez-btn--sm">Browse Jerseys</a>
          </div>
        `;
      } else {
        drawerBody.innerHTML = cart
          .map((item) => {
            const customMeta =
              item.customName || item.customNumber
                ? `<div style="color:#008A00; font-weight:700; font-size:0.73rem;">Print: ${item.customName} #${item.customNumber} (+৳250)</div>`
                : "";
            return `
              <div class="rabez-cart-item woocommerce-mini-cart-item mini_cart_item">
                <img src="${item.image}" alt="${item.name}" class="rabez-cart-item__img">
                <div>
                  <div class="rabez-cart-item__title">${item.name}</div>
                  <div class="rabez-cart-item__meta">Size: <strong>${item.size}</strong> &times; ${item.qty}</div>
                  ${customMeta}
                  <div class="rabez-cart-item__price">${formatBDT(item.price * item.qty)}</div>
                </div>
                <button type="button" class="rabez-cart-item__remove js-remove-cart-item" data-key="${item.key}" aria-label="Remove item">Remove</button>
              </div>
            `;
          })
          .join("");
      }
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

    const isAdded = isProductInCart(product.id);
    const swatches = product.sizes
      .map(
        (s) =>
          `<button type="button" class="rabez-size-swatch ${
            s === product.defaultSize ? "is-selected" : ""
          }" data-size="${s}">${s}</button>`
      )
      .join("");

    modalContent.innerHTML = `
      <div class="rabez-single-product" data-product-id="${product.id}" style="gap:28px;">
        <div class="rabez-gallery__main">
          <img src="${product.image}" alt="${product.name}">
        </div>
        <div class="summary entry-summary">
          <div class="rabez-summary__breadcrumb">${product.clubLabel} &bull; ${product.sleeveLabel} &bull; SKU: ${product.sku}</div>
          <h2 class="rabez-summary__title" style="font-size:1.85rem;">${product.name}</h2>
          <div class="rabez-summary__price-row">
            <span class="rabez-summary__price">${formatBDT(product.price)}</span>
            <span class="rabez-summary__stock">Ready Stock (BD)</span>
          </div>
          <p class="rabez-summary__desc">${product.shortDesc}</p>
          <div class="rabez-variation-block">
            <div class="rabez-variation-header">
              <span>Select Size:</span>
              <a href="#size-guide" class="js-close-quickview" style="color:#008A00; text-decoration:underline;">Size Chart</a>
            </div>
            <div class="rabez-size-swatches js-modal-swatches">
              ${swatches}
            </div>
          </div>
          <div style="display:grid; gap:10px; margin-top:20px;">
            <button type="button" class="rabez-btn rabez-btn--primary rabez-btn--full js-modal-add-cart ${isAdded ? "is-added added" : ""}" data-product-id="${product.id}">
              ${isAdded ? `&#10003; Added to Bag &mdash; ${formatBDT(product.price)}` : `Add to Bag &mdash; ${formatBDT(product.price)}`}
            </button>
            <a href="product.html?id=${product.id}" class="rabez-btn rabez-btn--outline rabez-btn--full">
              View Full Product &amp; Custom Print
            </a>
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

  /* --- Checkout Page Order Summary --- */
  function renderCheckoutOrderSummary() {
    const container = document.querySelector(".js-checkout-items");
    if (!container) return;

    const shippingRadio = document.querySelector('input[name="shipping_zone"]:checked');
    const shippingFee = shippingRadio ? Number(shippingRadio.value) : 70;
    const { subtotal } = getCartTotals();
    const grandTotal = subtotal + (cart.length ? shippingFee : 0);

    if (!cart.length) {
      container.innerHTML = `<p style="padding:16px 0; color:#6E7672;">No jerseys in your bag yet. <a href="shop.html" style="color:#008A00; font-weight:700;">Browse Shop &rarr;</a></p>`;
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
  }

  /* --- Single Product Page Loader (product.html?id=...) --- */
  function initSingleProductPage() {
    const root = document.querySelector(".js-single-product-root");
    if (!root || !window.RABEZ_PRODUCTS) return;

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

    // Add to Cart from Quick View Modal
    const modalAddBtn = e.target.closest(".js-modal-add-cart");
    if (modalAddBtn) {
      const id = Number(modalAddBtn.dataset.productId);
      const modal = modalAddBtn.closest(".rabez-single-product");
      const selectedSwatch = modal?.querySelector(".rabez-size-swatch.is-selected");
      const size = selectedSwatch ? selectedSwatch.dataset.size : "L";
      addToCart({ id, size, qty: 1 });
      document.querySelector(".js-quickview-backdrop")?.classList.remove("is-open");
      openCartDrawer();
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
  });

  // Expose API globally for RABEZ UI components and Livewire/SPA integrations
  window.RABEZ_STORE = {
    addToCart,
    openCartDrawer,
    openQuickView,
    formatBDT,
    buildWhatsAppOrderURL
  };

  document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const paramSleeve = params.get("sleeve");
    const paramClub = params.get("club");

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

    renderProductGrids();
    initSingleProductPage();
    updateCartUI();
    updateWishlistUI();
  });
})();

