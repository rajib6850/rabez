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
    .rabez-wl-page {
      padding: 32px 0 64px;
      min-height: 62vh;
    }

    .rabez-wl-head {
      background: #FFFFFF;
      border: 1px solid #E2E8E4;
      border-radius: 14px;
      padding: 20px 22px;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
    }

    .rabez-wl-crumb {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 0.78rem;
      color: #6B7280;
      margin-bottom: 6px;
    }

    .rabez-wl-crumb a {
      color: #374151;
      text-decoration: none;
      font-weight: 600;
    }

    .rabez-wl-crumb a:hover {
      color: #008A00;
    }

    .rabez-wl-title-row {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .rabez-wl-title {
      font-size: clamp(1.45rem, 2.4vw, 1.85rem);
      font-weight: 900;
      color: #0A0C0B;
      margin: 0;
      line-height: 1.2;
      letter-spacing: -0.02em;
    }

    .rabez-wl-count-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 11px;
      border-radius: 999px;
      background: #ECFDF3;
      border: 1px solid #BBF7D0;
      color: #15803D;
      font-size: 0.78rem;
      font-weight: 800;
    }

    .rabez-wl-head__actions {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }

    .rabez-wl-toolbar {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }

    .rabez-wl-action-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      height: 38px;
      padding: 0 14px;
      border-radius: 8px;
      font-size: 0.82rem;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      white-space: nowrap;
      transition: all 0.16s ease;
    }

    .rabez-wl-action-btn--primary {
      background: #04F404;
      border: 1px solid #04F404;
      color: #080A09;
      font-weight: 800;
    }

    .rabez-wl-action-btn--primary:hover {
      background: #02DC02;
      border-color: #02DC02;
    }

    .rabez-wl-action-btn--danger {
      background: #FEF2F2;
      border: 1px solid #FECACA;
      color: #B91C1C;
    }

    .rabez-wl-action-btn--danger:hover {
      background: #FEE2E2;
    }

    .rabez-wl-action-btn--outline {
      background: #FFFFFF;
      border: 1px solid #D1D9D4;
      color: #111827;
    }

    .rabez-wl-action-btn--outline:hover {
      border-color: #0A0C0B;
      background: #F8FAF9;
    }

    .rabez-wl-banner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
      padding: 10px 14px;
      border-radius: 10px;
      margin-bottom: 16px;
      font-size: 0.83rem;
      background: #FFFFFF;
      border: 1px solid #E2E8E4;
      color: #374151;
    }

    .rabez-wl-banner--shared {
      background: #ECFDF3;
      border-color: #86EFAC;
      color: #166534;
      font-weight: 600;
    }

    /* Horizontal Row Stack */
    .rabez-wl-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .rabez-wl-row {
      display: grid;
      grid-template-columns: 88px minmax(200px, 1.5fr) auto minmax(110px, auto) auto;
      align-items: center;
      gap: 20px;
      padding: 14px 18px;
      background: #FFFFFF;
      border: 1px solid #E2E8E4;
      border-radius: 12px;
      min-width: 0;
      transition: border-color 0.16s ease, box-shadow 0.16s ease;
    }

    .rabez-wl-row:hover {
      border-color: #C7D2CC;
      box-shadow: 0 6px 18px rgba(10, 12, 11, 0.05);
    }

    .rabez-wl-row__thumb {
      width: 88px;
      height: 108px;
      border-radius: 8px;
      overflow: hidden;
      background: #F4F6F5;
      border: 1px solid #ECEFED;
      display: block;
      flex-shrink: 0;
    }

    .rabez-wl-row__thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .rabez-wl-row__info {
      display: flex;
      flex-direction: column;
      gap: 6px;
      min-width: 0;
    }

    .rabez-wl-row__meta {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
      font-size: 0.74rem;
      font-weight: 700;
      color: #6B7280;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }

    .rabez-wl-row__status {
      display: inline-flex;
      align-items: center;
      padding: 2px 8px;
      border-radius: 999px;
      font-size: 0.69rem;
      font-weight: 800;
      letter-spacing: 0.02em;
      text-transform: none;
    }

    .rabez-wl-row__status--in {
      background: #ECFDF3;
      color: #15803D;
      border: 1px solid #BBF7D0;
    }

    .rabez-wl-row__status--out {
      background: #FEF2F2;
      color: #B91C1C;
      border: 1px solid #FECACA;
    }

    .rabez-wl-row__title {
      margin: 0;
      font-size: 1rem;
      font-weight: 800;
      line-height: 1.3;
      color: #0A0C0B;
    }

    .rabez-wl-row__title a {
      color: inherit;
      text-decoration: none;
    }

    .rabez-wl-row__title a:hover {
      color: #008A00;
    }

    .rabez-wl-row__size {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
      min-width: 0;
    }

    .rabez-wl-row__size-label {
      font-size: 0.76rem;
      font-weight: 700;
      color: #4B5563;
      white-space: nowrap;
    }

    .rabez-wl-row__size .rabez-size-swatches {
      display: flex;
      align-items: center;
      gap: 5px;
      flex-wrap: wrap;
    }

    .rabez-wl-row__size .rabez-size-swatch {
      min-width: 30px;
      height: 28px;
      padding: 0 7px;
      font-size: 0.74rem;
    }

    .rabez-wl-row__price {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 2px;
      white-space: nowrap;
    }

    .rabez-wl-row__price-now {
      font-size: 1.08rem;
      font-weight: 900;
      color: #0A0C0B;
    }

    .rabez-wl-row__price-was {
      font-size: 0.8rem;
      color: #8A938E;
    }

    .rabez-wl-row__actions {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
    }

    .rabez-wl-remove-btn {
      width: 38px;
      height: 38px;
      border-radius: 8px;
      border: 1px solid #E2E8E4;
      background: #FFFFFF;
      color: #6B7280;
      display: grid;
      place-items: center;
      cursor: pointer;
      flex-shrink: 0;
      transition: all 0.16s ease;
    }

    .rabez-wl-remove-btn:hover {
      background: #FEF2F2;
      border-color: #FECACA;
      color: #DC2626;
    }

    .rabez-wl-empty-card {
      background: #FFFFFF;
      border: 1px dashed #CBD5D0;
      border-radius: 14px;
      padding: 48px 22px;
      text-align: center;
    }

    .rabez-wl-empty-icon {
      width: 58px;
      height: 58px;
      border-radius: 14px;
      background: #F3F6F4;
      border: 1px solid #E2E7E4;
      display: grid;
      place-items: center;
      margin: 0 auto 14px;
      color: #0A0C0B;
    }

    @media (max-width: 960px) {
      .rabez-wl-row {
        grid-template-columns: 84px minmax(0, 1fr) auto;
        gap: 10px 16px;
        align-items: center;
      }
      .rabez-wl-row__thumb {
        width: 84px;
        height: 102px;
        grid-column: 1;
        grid-row: 1 / span 2;
      }
      .rabez-wl-row__info {
        grid-column: 2;
        grid-row: 1;
      }
      .rabez-wl-row__price {
        grid-column: 3;
        grid-row: 1;
        align-self: center;
      }
      .rabez-wl-row__size {
        grid-column: 2;
        grid-row: 2;
      }
      .rabez-wl-row__actions {
        grid-column: 3;
        grid-row: 2;
        justify-self: end;
      }
    }

    @media (max-width: 640px) {
      .rabez-wl-page {
        padding: 20px 0 48px;
      }
      .rabez-wl-head {
        padding: 16px;
      }
      .rabez-wl-head__actions {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 8px;
      }
      .rabez-wl-toolbar {
        width: 100%;
        gap: 8px;
      }
      .rabez-wl-toolbar .js-wl-move-all {
        flex: 1;
      }
      .rabez-wl-head__actions > .rabez-wl-action-btn--outline {
        width: 100%;
      }
      .rabez-wl-action-btn {
        height: 36px;
        padding: 0 11px;
        font-size: 0.78rem;
      }
      .rabez-wl-row {
        grid-template-columns: 76px minmax(0, 1fr);
        gap: 8px 12px;
        padding: 12px;
        align-items: center;
      }
      .rabez-wl-row__thumb {
        width: 76px;
        height: 94px;
        grid-column: 1;
        grid-row: 1 / span 2;
        align-self: start;
      }
      .rabez-wl-row__info {
        grid-column: 2;
        grid-row: 1;
        align-self: start;
      }
      .rabez-wl-row__price {
        grid-column: 2;
        grid-row: 2;
        flex-direction: row;
        align-items: baseline;
        justify-content: flex-start;
        gap: 8px;
        align-self: end;
      }
      .rabez-wl-row__size {
        grid-column: 1 / -1;
        grid-row: 3;
        justify-content: space-between;
        padding-top: 8px;
        border-top: 1px solid #F1F5F3;
      }
      .rabez-wl-row__actions {
        grid-column: 1 / -1;
        grid-row: 4;
        width: 100%;
        justify-self: stretch;
      }
      .rabez-wl-row__actions .js-wl-move-item {
        flex: 1;
      }
    }
  </style>

  <main class="rabez-section--alt rabez-wl-page">
    <div class="rabez-container">

      <!-- Compact Header Bar -->
      <div class="rabez-wl-head">
        <div>
          <div class="rabez-wl-crumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Wishlist</span>
          </div>
          <div class="rabez-wl-title-row">
            <h1 class="rabez-wl-title">My Wishlist</h1>
            <span class="rabez-wl-count-pill">
              <span class="js-wl-stat-total">0</span> Saved Jerseys
            </span>
          </div>
        </div>

        <div class="rabez-wl-head__actions">
          <div class="rabez-wl-toolbar js-wl-toolbar" style="display:none;">
            <button type="button" class="rabez-wl-action-btn rabez-wl-action-btn--primary js-wl-move-all">
              Move All In-Stock to Bag
            </button>
            <button type="button" class="rabez-wl-action-btn rabez-wl-action-btn--danger js-wl-clear">
              Clear Wishlist
            </button>
          </div>
          <a href="{{ route('shop') }}" class="rabez-wl-action-btn rabez-wl-action-btn--outline">
            Continue Shopping &rarr;
          </a>
        </div>
      </div>

      <!-- Shared Wishlist Imported Banner -->
      <div class="rabez-wl-banner rabez-wl-banner--shared js-wl-shared-banner" style="display:none;">
        <span>&#10003; Shared wishlist jerseys have been added to your saved list.</span>
        <a href="{{ route('wishlist') }}" style="color:#15803D; font-weight:800; text-decoration:underline;">Dismiss</a>
      </div>

      @guest
        <div class="rabez-wl-banner">
          <span>Saved in this browser. Sign in to sync your wishlist permanently across devices.</span>
          <a href="{{ route('login') }}" style="color:#15803D; font-weight:800; text-decoration:none; white-space:nowrap;">Sign In &rarr;</a>
        </div>
      @endguest

      <!-- Horizontal Row Wishlist Items -->
      <div class="rabez-wl-list js-wishlist-page-grid" style="display:none;"></div>

      <!-- Empty Wishlist State -->
      <div class="rabez-wl-empty-card js-wl-empty">
        <div class="rabez-wl-empty-icon">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
        </div>
        <h2 style="font-size:1.25rem; font-weight:900; color:#0A0C0B; margin:0 0 8px;">Your Wishlist is Empty</h2>
        <p style="font-size:0.9rem; color:#64748B; max-width:420px; margin:0 auto 18px; line-height:1.5;">
          Tap the heart icon on any jersey to save it here, pick your size, and move it to your bag anytime.
        </p>
        <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--primary">Browse All Jerseys &rarr;</a>
      </div>

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
