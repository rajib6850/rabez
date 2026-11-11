@extends('layouts.storefront')

@section('title', 'Shop Football Jerseys in Bangladesh - RABEZ | Half & Full Sleeve Kits')

@section('content')
  <!-- Shop Archive Header Banner -->
  <section class="rabez-section--dark" style="padding: 48px 0; border-bottom: 1px solid #222824;">
    <div class="rabez-container" style="display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:20px;">
      <div>
        <div class="rabez-eyebrow">Official Jersey Catalog</div>
        <h1 class="rabez-section-title" style="color:#FFFFFF;">All Club, National &amp; Retro Jerseys</h1>
      </div>
      <p style="font-size:0.94rem; line-height:1.6; color:#9BA59F; max-width:440px;">
        Select your size directly on any jersey card and add to bag. All kits are in ready stock for immediate Cash on Delivery across Bangladesh.
      </p>
    </div>
  </section>

  <!-- Club & National Crest Filter Bar (Clean Single-Row Strip) -->
  <div id="catalog" class="rabez-filter-bar">
    <div class="rabez-container rabez-filter-bar__inner">
      <div class="rabez-club-pills" role="tablist" aria-label="Filter by Football Club or National Team">
        <button type="button" class="rabez-club-pill is-active js-club-filter" data-club="all">
          <span class="rabez-club-pill__logo">
            <img src="assets/images/clubs/all-kits.svg" alt="All Kits" width="36" height="36">
          </span>
          <span class="rabez-club-pill__name">All Kits</span>
        </button>

        <button type="button" class="rabez-club-pill js-club-filter" data-club="real-madrid">
          <span class="rabez-club-pill__logo">
            <img src="assets/images/clubs/real-madrid.svg" alt="Real Madrid Logo" width="36" height="36">
          </span>
          <span class="rabez-club-pill__name">Real Madrid</span>
        </button>

        <button type="button" class="rabez-club-pill js-club-filter" data-club="barcelona">
          <span class="rabez-club-pill__logo">
            <img src="assets/images/clubs/barcelona.svg" alt="FC Barcelona Logo" width="36" height="36">
          </span>
          <span class="rabez-club-pill__name">Barcelona</span>
        </button>

        <button type="button" class="rabez-club-pill js-club-filter" data-club="argentina">
          <span class="rabez-club-pill__logo">
            <img src="assets/images/clubs/argentina.svg" alt="Argentina AFA Logo" width="36" height="36">
          </span>
          <span class="rabez-club-pill__name">Argentina</span>
        </button>

        <button type="button" class="rabez-club-pill js-club-filter" data-club="arsenal">
          <span class="rabez-club-pill__logo">
            <img src="assets/images/clubs/arsenal.svg" alt="Arsenal FC Logo" width="36" height="36">
          </span>
          <span class="rabez-club-pill__name">Arsenal</span>
        </button>

        <button type="button" class="rabez-club-pill js-club-filter" data-club="man-utd">
          <span class="rabez-club-pill__logo">
            <img src="assets/images/clubs/man-utd.svg" alt="Manchester United Logo" width="36" height="36">
          </span>
          <span class="rabez-club-pill__name">Man United</span>
        </button>

        <button type="button" class="rabez-club-pill js-club-filter" data-club="bayern">
          <span class="rabez-club-pill__logo">
            <img src="assets/images/clubs/bayern.svg" alt="Bayern Munich Logo" width="36" height="36">
          </span>
          <span class="rabez-club-pill__name">Bayern</span>
        </button>

        <button type="button" class="rabez-club-pill js-club-filter" data-club="liverpool">
          <span class="rabez-club-pill__logo">
            <img src="assets/images/clubs/liverpool.svg" alt="Liverpool FC Logo" width="36" height="36">
          </span>
          <span class="rabez-club-pill__name">Liverpool</span>
        </button>

        <button type="button" class="rabez-club-pill js-club-filter" data-club="bangladesh">
          <span class="rabez-club-pill__logo">
            <img src="assets/images/clubs/bangladesh.svg" alt="Bangladesh BFF Crest" width="36" height="36">
          </span>
          <span class="rabez-club-pill__name">Bangladesh</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Full Product Grid (Same Heading + Unified Filter/Count/Sort Row System) -->
  <main class="rabez-section rabez-section--sm woocommerce">
    <div class="rabez-container">
      <div class="rabez-section-head" style="margin-bottom: 20px;">
        <h2 class="rabez-section-title">Matchday Jersey Collection</h2>
      </div>

      <div class="rabez-filter-controls">
        <div class="rabez-sleeve-switch" role="group" aria-label="Filter by Category & Sleeve">
          <button type="button" class="rabez-sleeve-btn is-active js-sleeve-filter" data-sleeve="all">All Kits</button>
          <button type="button" class="rabez-sleeve-btn js-sleeve-filter" data-sleeve="half-sleeve">Half Sleeve (&#2547;1,150)</button>
          <button type="button" class="rabez-sleeve-btn js-sleeve-filter" data-sleeve="full-sleeve">Full Sleeve (&#2547;1,300)</button>
          <button type="button" class="rabez-sleeve-btn js-sleeve-filter" data-sleeve="winter-hoodie">Winter Hoodies (&#2547;1,450)</button>
        </div>

        <div style="display:flex; align-items:center; flex-wrap:wrap; gap:12px;">
          <span class="js-product-result-count" style="font-size:0.84rem; font-weight:700; color:#6E7672;">Showing 18 Items</span>
          <label for="shop-catalog-sort" class="screen-reader-text">Sort Jerseys</label>
          <select id="shop-catalog-sort" class="rabez-sort-select js-sort-select" aria-label="Sort Jerseys">
            <option value="default">Sort: Featured</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
          </select>
        </div>
      </div>

      <ul class="products columns-4 js-rabez-product-grid" data-limit="0"></ul>
    </div>
  </main>

  <!-- Footer -->
  <footer class="rabez-footer" role="contentinfo">
    <div class="rabez-container">
      <div class="rabez-footer__grid">
        <div>
          <img src="assets/images/brand/rabez-neon-logo.png" alt="RABEZ - Wear What you stand for." class="rabez-footer__logo" width="220" height="73" loading="lazy">
          <p class="rabez-footer__bio">
            Your destination for premium jersey collections. Discover the perfect blend of style, comfort, and quality with RABEZ.
          </p>
          <div class="rabez-social-links">
            <a href="https://www.facebook.com/rabezshop/" target="_blank" rel="noopener" class="rabez-social-link" aria-label="RABEZ Facebook Page">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z"/>
              </svg>
            </a>
            <a href="https://api.whatsapp.com/send/?phone=8801612359652" target="_blank" rel="noopener" class="rabez-social-link" aria-label="RABEZ WhatsApp">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984a9.964 9.964 0 0 0 1.333 4.993L2 22l5.233-1.237a9.994 9.994 0 0 0 4.779 1.217h.004c5.505 0 9.985-4.48 9.988-9.984 0-2.669-1.037-5.178-2.922-7.066C17.19 3.04 14.684 2 12.012 2z"/>
              </svg>
            </a>
          </div>
        </div>

        <div>
          <h3 class="rabez-footer__heading">Jersey &amp; Hoodie Categories</h3>
          <ul class="rabez-footer__links">
            <li><a href="shop.html">All Jerseys &amp; Hoodies (31)</a></li>
            <li><a href="shop.html?sleeve=half-sleeve">Half Sleeve Kits (&#2547;1,150)</a></li>
            <li><a href="shop.html?sleeve=full-sleeve">Full Sleeve Kits (&#2547;1,300)</a></li>
            <li><a href="shop.html?sleeve=winter-hoodie">Winter Hoodies with Jersey Logo (&#2547;1,450)</a></li>
            <li><a href="index.html#custom-print">Custom Name &amp; Number (+&#2547;250)</a></li>
          </ul>
        </div>

        <div>
          <h3 class="rabez-footer__heading">Customer Care</h3>
          <ul class="rabez-footer__links">
            <li><a href="track-order.html">Track Your Order &amp; Invoice</a></li>
            <li><a href="index.html#size-guide">Bangladesh Size Chart</a></li>
            <li><a href="checkout.html">Cash on Delivery Info</a></li>
            <li><a href="checkout.html">Size Exchange Policy</a></li>
            <li><a href="admin.html">Admin App (Staff Only)</a></li>
          </ul>
        </div>

        <div>
          <h3 class="rabez-footer__heading">Direct Order &amp; Support</h3>
          <ul class="rabez-footer__contact-list">
            <li class="rabez-footer__contact-item">
              <span class="rabez-footer__contact-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
              </span>
              <div class="rabez-footer__contact-text">
                <span class="rabez-footer__contact-label">WhatsApp &amp; Hotline</span>
                <a href="https://api.whatsapp.com/send/?phone=8801612359652" target="_blank" rel="noopener" class="rabez-footer__contact-val">+880 1612-359652</a>
              </div>
            </li>

            <li class="rabez-footer__contact-item">
              <span class="rabez-footer__contact-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                </svg>
              </span>
              <div class="rabez-footer__contact-text">
                <span class="rabez-footer__contact-label">Official Facebook</span>
                <a href="https://www.facebook.com/rabezshop/" target="_blank" rel="noopener" class="rabez-footer__contact-val">facebook.com/rabezshop</a>
              </div>
            </li>

            <li class="rabez-footer__contact-item">
              <span class="rabez-footer__contact-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </span>
              <div class="rabez-footer__contact-text">
                <span class="rabez-footer__contact-label">Store Location</span>
                <span class="rabez-footer__contact-val">Road-10, College Avenue, Barishal</span>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <div class="rabez-footer__bottom">
        <p>&copy; 2026 RABEZ (<a href="https://rabez.shop/" style="color:#FFFFFF;">rabez.shop</a>) &ndash; All Rights Reserved.</p>
        <div class="rabez-payment-pills">
          <span class="rabez-pay-pill">Cash On Delivery</span>
          <span class="rabez-pay-pill">bKash</span>
          <span class="rabez-pay-pill">Nagad</span>
          <span class="rabez-pay-pill">Steadfast / Pathao Courier</span>
        </div>
      </div>
    </div>
  </footer>

@endsection
