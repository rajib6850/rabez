@extends('layouts.storefront')

@section('title', 'My Wishlist - RABEZ Official Jersey Store')

@section('content')
  @php
    $shippingMode = $settings['shipping_mode'] ?? 'flat';
    $flatFee = intval($settings['shipping_flat_fee'] ?? 130);
    $insideFee = intval($settings['shipping_inside_dhaka'] ?? 70);
    $outsideFee = intval($settings['shipping_outside_dhaka'] ?? 130);
    $freeShippingMin = intval($settings['free_shipping_min'] ?? 3500);
    $printFee = intval($settings['custom_print_fee'] ?? 250);
  @endphp

  <style>
    .rabez-wl-hero {
      background: linear-gradient(135deg, #080A09 0%, #101713 55%, #0D1E13 100%);
      color: #FFFFFF;
      padding: 34px 0 38px;
      border-bottom: 1px solid #1E2822;
    }

    .rabez-wl-hero__inner {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      gap: 24px;
      flex-wrap: wrap;
    }

    .rabez-wl-crumb {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      font-size: 0.78rem;
      color: #9BA59F;
      margin-bottom: 10px;
    }

    .rabez-wl-crumb a {
      color: #CBD5D0;
      text-decoration: none;
    }

    .rabez-wl-crumb a:hover {
      color: #04F404;
    }

    .rabez-wl-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 0.7rem;
      font-weight: 800;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      background: rgba(4, 244, 4, 0.14);
      border: 1px solid rgba(4, 244, 4, 0.35);
      color: #04F404;
      margin-bottom: 10px;
    }

    .rabez-wl-title {
      font-size: clamp(1.6rem, 2.8vw, 2.2rem);
      font-weight: 900;
      line-height: 1.15;
      color: #FFFFFF;
      margin: 0 0 8px;
      letter-spacing: -0.02em;
    }

    .rabez-wl-sub {
      font-size: 0.9rem;
      color: #B4C0B9;
      margin: 0;
      max-width: 600px;
      line-height: 1.5;
    }

    .rabez-wl-hero__actions {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .rabez-wl-btn-ghost {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      height: 40px;
      padding: 0 15px;
      border-radius: 8px;
      border: 1px solid #2D3831;
      background: rgba(255, 255, 255, 0.04);
      color: #FFFFFF;
      font-size: 0.84rem;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.18s ease;
    }

    .rabez-wl-btn-ghost:hover {
      border-color: #04F404;
      color: #04F404;
      background: rgba(4, 244, 4, 0.08);
    }

    .rabez-wl-btn-wa {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      height: 40px;
      padding: 0 16px;
      border-radius: 8px;
      border: 1px solid #16A34A;
      background: #16A34A;
      color: #FFFFFF;
      font-size: 0.84rem;
      font-weight: 800;
      cursor: pointer;
      transition: background 0.18s ease;
    }

    .rabez-wl-btn-wa:hover {
      background: #15803D;
    }

    .rabez-wl-banner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
      padding: 12px 16px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 0.85rem;
      line-height: 1.45;
    }

    .rabez-wl-banner--shared {
      background: #ECFDF3;
      border: 1px solid #86EFAC;
      color: #166534;
      font-weight: 600;
    }

    .rabez-wl-banner--guest {
      background: #FFFFFF;
      border: 1px solid #DCE2DE;
      color: #374151;
    }

    .rabez-wl-stats {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 14px;
      margin-bottom: 22px;
    }

    .rabez-wl-stat {
      background: #FFFFFF;
      border: 1px solid #E2E7E4;
      border-radius: 12px;
      padding: 16px 18px;
      min-width: 0;
    }

    .rabez-wl-stat--accent {
      background: #0B0F0D;
      border-color: #1D2922;
      color: #FFFFFF;
    }

    .rabez-wl-stat__label {
      font-size: 0.74rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #6B7280;
      margin-bottom: 6px;
    }

    .rabez-wl-stat--accent .rabez-wl-stat__label {
      color: #9BA59F;
    }

    .rabez-wl-stat__val {
      font-size: 1.45rem;
      font-weight: 900;
      color: #0A0C0B;
      line-height: 1.2;
    }

    .rabez-wl-stat--accent .rabez-wl-stat__val {
      color: #04F404;
    }

    .rabez-wl-stat__sub {
      font-size: 0.76rem;
      color: #6E7672;
      margin-top: 4px;
    }

    .rabez-wl-stat--accent .rabez-wl-stat__sub {
      color: #B4C0B9;
    }

    .rabez-wl-toolbar {
      background: #FFFFFF;
      border: 1px solid #E2E7E4;
      border-radius: 12px;
      padding: 12px 14px;
      margin-bottom: 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      flex-wrap: wrap;
    }

    .rabez-wl-filters {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }

    .rabez-wl-filter-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      height: 36px;
      padding: 0 13px;
      border-radius: 8px;
      border: 1px solid #DCE2DE;
      background: #F7F9F8;
      color: #374151;
      font-size: 0.82rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.16s ease;
    }

    .rabez-wl-filter-btn:hover {
      border-color: #0A0C0B;
      color: #0A0C0B;
    }

    .rabez-wl-filter-btn.is-active {
      background: #0A0C0B;
      border-color: #0A0C0B;
      color: #FFFFFF;
    }

    .rabez-wl-filter-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 20px;
      height: 20px;
      padding: 0 6px;
      border-radius: 999px;
      font-size: 0.7rem;
      font-weight: 800;
      background: rgba(0, 0, 0, 0.08);
      color: inherit;
    }

    .rabez-wl-filter-btn.is-active .rabez-wl-filter-badge {
      background: #04F404;
      color: #080A09;
    }

    .rabez-wl-controls {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }

    .rabez-wl-select {
      height: 36px;
      padding: 0 12px;
      border-radius: 8px;
      border: 1px solid #D3DBD6;
      background: #FFFFFF;
      color: #111827;
      font-size: 0.82rem;
      font-weight: 600;
    }

    .rabez-wl-bulk-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      height: 36px;
      padding: 0 13px;
      border-radius: 8px;
      border: none;
      font-size: 0.82rem;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.16s ease;
    }

    .rabez-wl-bulk-btn--move {
      background: #04F404;
      color: #080A09;
    }

    .rabez-wl-bulk-btn--move:hover {
      background: #02DC02;
    }

    .rabez-wl-bulk-btn--clear {
      background: #FEF2F2;
      color: #B91C1C;
      border: 1px solid #FECACA;
    }

    .rabez-wl-bulk-btn--clear:hover {
      background: #FEE2E2;
    }

    .rabez-wl-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 20px;
    }

    .rabez-wl-card {
      background: #FFFFFF;
      border: 1px solid #E2E7E4;
      border-radius: 14px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      min-width: 0;
      box-shadow: 0 4px 16px rgba(10, 12, 11, 0.04);
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .rabez-wl-card:hover {
      transform: translateY(-2px);
      border-color: #C7D2CC;
      box-shadow: 0 12px 26px rgba(10, 12, 11, 0.08);
    }

    .rabez-wl-card__media {
      position: relative;
      aspect-ratio: 4 / 5;
      background: #F4F6F5;
      overflow: hidden;
    }

    .rabez-wl-card__media a {
      display: block;
      width: 100%;
      height: 100%;
    }

    .rabez-wl-card__img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: opacity 0.25s ease, transform 0.35s ease;
    }

    .rabez-wl-card__img--back {
      position: absolute;
      inset: 0;
      opacity: 0;
    }

    .rabez-wl-card.has-back-view:hover .rabez-wl-card__img--back {
      opacity: 1;
    }

    .rabez-wl-card:hover .rabez-wl-card__img--front {
      transform: scale(1.03);
    }

    .rabez-wl-card__badges {
      position: absolute;
      top: 10px;
      left: 10px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 5px;
      z-index: 3;
    }

    .rabez-wl-tag {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 9px;
      border-radius: 6px;
      font-size: 0.7rem;
      font-weight: 800;
      letter-spacing: 0.02em;
    }

    .rabez-wl-tag--stock {
      background: #ECFDF3;
      color: #15803D;
      border: 1px solid #BBF7D0;
    }

    .rabez-wl-tag--out {
      background: #FEF2F2;
      color: #B91C1C;
      border: 1px solid #FECACA;
    }

    .rabez-wl-tag--off {
      background: #0A0C0B;
      color: #04F404;
    }

    .rabez-wl-tag--print {
      background: #EFF6FF;
      color: #1D4ED8;
      border: 1px solid #DBEAFE;
    }

    .rabez-wl-card__top-btns {
      position: absolute;
      top: 10px;
      right: 10px;
      display: flex;
      flex-direction: column;
      gap: 6px;
      z-index: 3;
    }

    .rabez-wl-icon-act {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      border: 1px solid rgba(0, 0, 0, 0.1);
      background: rgba(255, 255, 255, 0.94);
      color: #111827;
      display: grid;
      place-items: center;
      cursor: pointer;
      transition: all 0.16s ease;
    }

    .rabez-wl-icon-act:hover {
      background: #0A0C0B;
      color: #04F404;
      border-color: #0A0C0B;
    }

    .rabez-wl-icon-act--remove:hover {
      background: #DC2626;
      color: #FFFFFF;
      border-color: #DC2626;
    }

    .rabez-wl-card__body {
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 11px;
      flex: 1;
      min-width: 0;
    }

    .rabez-wl-card__meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #6B7280;
    }

    .rabez-wl-card__sku {
      font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
      font-size: 0.69rem;
      background: #F3F5F4;
      color: #4B5563;
      padding: 2px 6px;
      border-radius: 4px;
    }

    .rabez-wl-card__title {
      margin: 0;
      font-size: 1rem;
      font-weight: 800;
      line-height: 1.3;
      color: #0A0C0B;
    }

    .rabez-wl-card__title a {
      color: inherit;
      text-decoration: none;
    }

    .rabez-wl-card__title a:hover {
      color: #008A00;
    }

    .rabez-wl-card__desc {
      margin: 0;
      font-size: 0.8rem;
      color: #5E6662;
      line-height: 1.45;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .rabez-wl-specs {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 7px;
      background: #F8FAF9;
      border: 1px solid #E8ECE9;
      border-radius: 9px;
      padding: 10px;
    }

    .rabez-wl-spec {
      min-width: 0;
    }

    .rabez-wl-spec__k {
      display: block;
      font-size: 0.67rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #7A827D;
    }

    .rabez-wl-spec__v {
      display: block;
      font-size: 0.77rem;
      font-weight: 700;
      color: #111827;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .rabez-wl-price-row {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      gap: 8px;
      flex-wrap: wrap;
    }

    .rabez-wl-price-main {
      display: flex;
      align-items: baseline;
      gap: 8px;
    }

    .rabez-wl-price-now {
      font-size: 1.2rem;
      font-weight: 900;
      color: #0A0C0B;
    }

    .rabez-wl-price-was {
      font-size: 0.85rem;
      color: #8A938E;
    }

    .rabez-wl-save-pill {
      font-size: 0.72rem;
      font-weight: 800;
      color: #15803D;
      background: #ECFDF3;
      border: 1px solid #BBF7D0;
      padding: 2px 8px;
      border-radius: 999px;
    }

    .rabez-wl-sizes-wrap {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .rabez-wl-sizes-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 0.76rem;
      font-weight: 700;
      color: #374151;
    }

    .rabez-wl-sizes-head strong {
      color: #008A00;
    }

    .rabez-wl-card__actions {
      margin-top: auto;
      padding-top: 10px;
      border-top: 1px solid #ECEFED;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
    }

    .rabez-wl-card__actions .rabez-wl-act-wa {
      grid-column: 1 / -1;
    }

    .rabez-wl-empty-card {
      background: #FFFFFF;
      border: 1px dashed #CBD5D0;
      border-radius: 16px;
      padding: 46px 22px;
      text-align: center;
      margin-bottom: 34px;
    }

    .rabez-wl-empty-icon {
      width: 62px;
      height: 62px;
      border-radius: 16px;
      background: #F3F6F4;
      border: 1px solid #E2E7E4;
      display: grid;
      place-items: center;
      margin: 0 auto 16px;
      color: #0A0C0B;
    }

    @media (max-width: 1024px) {
      .rabez-wl-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 900px) {
      .rabez-wl-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 640px) {
      .rabez-wl-grid {
        grid-template-columns: 1fr;
      }
      .rabez-wl-hero__actions {
        width: 100%;
      }
      .rabez-wl-hero__actions > * {
        flex: 1;
      }
    }
  </style>

  <!-- Dark Wishlist Hero -->
  <section class="rabez-wl-hero">
    <div class="rabez-container">
      <div class="rabez-wl-hero__inner">
        <div>
          <div class="rabez-wl-crumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Wishlist</span>
          </div>
          <span class="rabez-wl-pill">SAVED COLLECTION</span>
          <h1 class="rabez-wl-title">My Saved Jerseys &amp; Kits</h1>
          <p class="rabez-wl-sub">
            @auth
              Synced to your account ({{ Auth::user()->email }}) &bull; Your saved jerseys, sizes, and live stock availability are always ready here.
            @else
              Compare specs, lock in your preferred size, check real-time stock, or move kits directly into your shopping bag.
            @endauth
          </p>
        </div>

        <div class="rabez-wl-hero__actions">
          <button type="button" class="rabez-wl-btn-ghost js-wl-share">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
              <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
            </svg>
            <span>Share Wishlist</span>
          </button>

          <button type="button" class="rabez-wl-btn-wa js-wl-whatsapp-all">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
            <span>Order All on WhatsApp</span>
          </button>

          <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--primary" style="height:40px; padding:0 16px; display:inline-flex; align-items:center;">
            Continue Shopping &rarr;
          </a>
        </div>
      </div>
    </div>
  </section>

  <main class="rabez-section rabez-section--alt">
    <div class="rabez-container">

      <!-- Shared Wishlist Imported Banner -->
      <div class="rabez-wl-banner rabez-wl-banner--shared js-wl-shared-banner" style="display:none;">
        <span>&#10003; Shared wishlist jerseys have been imported into your collection!</span>
        <a href="{{ route('wishlist') }}" style="color:#15803D; font-weight:800; text-decoration:underline;">View Clean URL</a>
      </div>

      @guest
        <div class="rabez-wl-banner rabez-wl-banner--guest">
          <span><strong>Cross-Device Sync Tip:</strong> Your wishlist is saved on this browser right now. Sign in to sync these jerseys permanently with your RABEZ account.</span>
          <a href="{{ route('login') }}" style="color:#15803D; font-weight:800; text-decoration:none; white-space:nowrap;">Sign In to Sync &rarr;</a>
        </div>
      @endguest

      <!-- 4 Live KPI Summary Cards -->
      <div class="rabez-wl-stats">
        <div class="rabez-wl-stat">
          <div class="rabez-wl-stat__label">Saved Jerseys</div>
          <div class="rabez-wl-stat__val num js-wl-stat-total">0</div>
          <div class="rabez-wl-stat__sub">Items in your list</div>
        </div>
        <div class="rabez-wl-stat">
          <div class="rabez-wl-stat__label">Ready In Stock</div>
          <div class="rabez-wl-stat__val num js-wl-stat-stock">0</div>
          <div class="rabez-wl-stat__sub">Ready for dispatch today</div>
        </div>
        <div class="rabez-wl-stat">
          <div class="rabez-wl-stat__label">Wishlist Total Value</div>
          <div class="rabez-wl-stat__val num js-wl-stat-value">&#2547; 0</div>
          <div class="rabez-wl-stat__sub">Combined sale value</div>
        </div>
        <div class="rabez-wl-stat rabez-wl-stat--accent">
          <div class="rabez-wl-stat__label">Total Bundle Savings</div>
          <div class="rabez-wl-stat__val num js-wl-stat-savings">&#2547; 0</div>
          <div class="rabez-wl-stat__sub">Discount vs regular price</div>
        </div>
      </div>

      <!-- Filter, Sort & Bulk Toolbar -->
      <div class="rabez-wl-toolbar js-wl-toolbar" style="display:none;">
        <div class="rabez-wl-filters" role="tablist" aria-label="Filter wishlist items">
          <button type="button" class="rabez-wl-filter-btn is-active js-wl-filter" data-filter="all">
            <span>All Saved</span>
            <span class="rabez-wl-filter-badge js-wl-count-all">0</span>
          </button>
          <button type="button" class="rabez-wl-filter-btn js-wl-filter" data-filter="in-stock">
            <span>Ready Stock</span>
            <span class="rabez-wl-filter-badge js-wl-count-instock">0</span>
          </button>
          <button type="button" class="rabez-wl-filter-btn js-wl-filter" data-filter="custom-print">
            <span>Custom Print Ready</span>
            <span class="rabez-wl-filter-badge js-wl-count-custom">0</span>
          </button>
          <button type="button" class="rabez-wl-filter-btn js-wl-filter" data-filter="on-sale">
            <span>On Sale</span>
            <span class="rabez-wl-filter-badge js-wl-count-sale">0</span>
          </button>
        </div>

        <div class="rabez-wl-controls">
          <label for="wl-sort-select" class="screen-reader-text">Sort wishlist</label>
          <select id="wl-sort-select" class="rabez-wl-select js-wl-sort" aria-label="Sort saved jerseys">
            <option value="recent">Sort: Recently Saved</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
            <option value="discount-desc">Biggest Discount</option>
          </select>

          <button type="button" class="rabez-wl-bulk-btn rabez-wl-bulk-btn--move js-wl-move-all">
            Move All In-Stock to Bag
          </button>
          <button type="button" class="rabez-wl-bulk-btn rabez-wl-bulk-btn--clear js-wl-clear">
            Clear Wishlist
          </button>
        </div>
      </div>

      <!-- Rich Pro Wishlist Cards Grid -->
      <div class="rabez-wl-grid js-wishlist-page-grid" style="display:none;"></div>

      <!-- Filtered Empty Message -->
      <div class="rabez-wl-empty-card js-wl-filter-empty" style="display:none;">
        <h2 style="font-size:1.25rem; font-weight:800; color:#0A0C0B; margin:0 0 8px;">No saved items match this filter</h2>
        <p style="font-size:0.9rem; color:#64748B; margin:0 0 18px;">Switch back to All Saved jerseys to see everything in your wishlist.</p>
        <button type="button" class="rabez-btn rabez-btn--dark js-wl-reset-filter">Show All Saved Items</button>
      </div>

      <!-- Empty Wishlist State -->
      <div class="rabez-wl-empty-card js-wl-empty">
        <div class="rabez-wl-empty-icon">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
        </div>
        <h2 style="font-size:1.35rem; font-weight:900; color:#0A0C0B; margin:0 0 8px;">Your Wishlist is Empty</h2>
        <p style="font-size:0.92rem; color:#64748B; max-width:460px; margin:0 auto 20px; line-height:1.55;">
          Tap the heart icon on any jersey card or product detail page to save kits here, lock in your size, and compare before ordering.
        </p>
        <div style="display:flex; justify-content:center; gap:10px; flex-wrap:wrap;">
          <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--primary">Browse All Jerseys &rarr;</a>
          <a href="{{ route('home') }}" class="rabez-btn rabez-btn--outline">Back to Home</a>
        </div>
      </div>

      <!-- Trending Jerseys Section -->
      @if($trendingProducts->isNotEmpty())
        <section style="margin-top:42px;">
          <div class="rabez-section-head" style="margin-bottom:18px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
              <h2 class="rabez-section-title" style="margin:0;">Trending Matchday Jerseys</h2>
              <p style="margin:4px 0 0; font-size:0.86rem; color:#64748B;">Popular player edition kits &amp; hoodies ready for fast Bangladesh delivery</p>
            </div>
            <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--dark rabez-btn--sm">View Full Catalog &rarr;</a>
          </div>

          <ul class="products columns-4">
            @foreach($trendingProducts as $tp)
              @php
                $tpFront = $tp->image ? asset(ltrim($tp->image, '/')) : asset('assets/images/products/real-madrid-home-hs.jpg');
                $tpGallery = is_array($tp->gallery) ? $tp->gallery : (json_decode($tp->gallery ?? '[]', true) ?: []);
                $tpBack = !empty($tpGallery) ? asset(ltrim($tpGallery[0], '/')) : null;
                $tpDef = $tp->sleeve === 'footwear' ? ['39', '40', '41', '42', '43', '44'] : ['S', 'M', 'L', 'XL', 'XXL'];
                $tpHv = $tp->variants->isNotEmpty();
                $tpAllSizes = $tpHv ? $tp->variants->pluck('size')->toArray() : $tpDef;
                $tpInStock = $tpHv ? $tp->variants->where('is_in_stock', true)->pluck('size')->toArray() : $tpAllSizes;
                $tpDefaultSize = in_array('L', $tpInStock, true) ? 'L' : ($tpInStock[0] ?? ($tpAllSizes[0] ?? ''));
              @endphp
              <li class="product type-product rabez-product-card {{ $tpBack ? 'has-back-view' : '' }}" data-product-id="{{ $tp->id }}">
                <div class="rabez-product-card__media">
                  <a href="{{ route('product.detail', $tp->slug ?: $tp->id) }}" class="woocommerce-LoopProduct-link">
                    <img src="{{ $tpFront }}" class="rabez-card-img-front" alt="{{ $tp->name }} - Front View" loading="lazy" width="500" height="667">
                    @if($tpBack)
                      <img src="{{ $tpBack }}" class="rabez-card-img-back" alt="{{ $tp->name }} - Back View" loading="lazy" width="500" height="667">
                      <span class="rabez-card-flip-pill">Back View</span>
                    @endif
                  </a>
                  <div class="rabez-product-card__badges">
                    @if($tp->sleeve === 'winter-hoodie')
                      <span class="rabez-badge rabez-badge--neon">Winter Drop</span>
                    @elseif(!$tp->is_ready_stock)
                      <span class="rabez-badge rabez-badge--dark">Out of Stock</span>
                    @endif
                  </div>
                  <div class="rabez-product-card__actions">
                    <button type="button" class="rabez-card-icon-btn js-wishlist-toggle" data-product-id="{{ $tp->id }}" aria-label="Add to Wishlist" title="Wishlist">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                      </svg>
                    </button>
                    <button type="button" class="rabez-card-icon-btn js-quick-view" data-product-id="{{ $tp->id }}" aria-label="Quick View" title="Quick View">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                        <circle cx="12" cy="12" r="3"/>
                      </svg>
                    </button>
                  </div>
                </div>

                <div class="rabez-product-card__body">
                  <div class="rabez-product-card__meta">
                    <span>{{ $tp->club_label ?: ucwords(str_replace('-', ' ', $tp->club ?: 'Club')) }}</span>
                    <span class="rabez-product-card__sleeve">{{ $tp->sleeve_label ?: ucwords(str_replace('-', ' ', $tp->sleeve ?: 'Jersey')) }}</span>
                  </div>
                  <h3 class="rabez-product-card__title woocommerce-loop-product__title">
                    <a href="{{ route('product.detail', $tp->slug ?: $tp->id) }}">{{ $tp->name }}</a>
                  </h3>
                  <div class="rabez-product-card__sizes" role="group" aria-label="Select Jersey Size">
                    <div class="rabez-size-swatches js-card-swatches">
                      @foreach($tpAllSizes as $s)
                        @php $avail = in_array($s, $tpInStock, true); $sel = $avail && $s === $tpDefaultSize; @endphp
                        <button type="button" class="rabez-size-swatch {{ $sel ? 'is-selected' : '' }} {{ !$avail ? 'is-out-of-stock' : '' }}" data-size="{{ $s }}" aria-pressed="{{ $sel ? 'true' : 'false' }}" @if(!$avail) disabled title="Out of stock" @endif>{{ $s }}</button>
                      @endforeach
                    </div>
                  </div>
                  <div class="rabez-product-card__footer">
                    <div class="price rabez-product-card__price">
                      @if($tp->regular_price && $tp->regular_price > $tp->price)
                        <del><span class="woocommerce-Price-amount amount">&#2547; {{ number_format($tp->regular_price) }}</span></del>
                      @endif
                      <ins><span class="woocommerce-Price-amount amount">&#2547; {{ number_format($tp->price) }}</span></ins>
                    </div>
                    <button type="button" class="rabez-add-cart-btn add_to_cart_button ajax_add_to_cart js-add-to-cart" data-product-id="{{ $tp->id }}" @if(empty($tpInStock)) disabled style="opacity:0.55; cursor:not-allowed;" @endif>
                      {{ !empty($tpInStock) ? 'Add' : 'Sold Out' }}
                    </button>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
        </section>
      @endif
    </div>
  </main>

  <script>
    window.RABEZ_SHARED_WISHLIST = @json($sharedProductIds ?? []);
    window.RABEZ_WISHLIST_SETTINGS = {
      shippingMode: @json($shippingMode),
      flatFee: {{ $flatFee }},
      insideFee: {{ $insideFee }},
      outsideFee: {{ $outsideFee }},
      freeShippingMin: {{ $freeShippingMin }},
      printFee: {{ $printFee }}
    };
  </script>
@endsection
