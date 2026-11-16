@extends('layouts.storefront')

@section('title', 'Order Confirmed #' . $order->order_number . ' - RABEZ Official Store Bangladesh')

@push('styles')
  <style>
    .rabez-confirm-hero {
      background:
        radial-gradient(circle at 80% 18%, rgba(4, 244, 4, 0.16), transparent 44%),
        radial-gradient(circle at 12% 85%, rgba(4, 244, 4, 0.08), transparent 36%),
        #0A0C0B;
      color: #FFFFFF;
      padding: 48px 0 64px;
      border-bottom: 1px solid #1E2421;
    }
    .rabez-confirm-hero__inner {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }
    .rabez-confirm-hero__left {
      display: flex;
      align-items: flex-start;
      gap: 18px;
      max-width: 680px;
    }
    .rabez-confirm-check {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: rgba(4, 244, 4, 0.14);
      border: 2px solid var(--rabez-neon);
      color: var(--rabez-neon);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 0 24px rgba(4, 244, 4, 0.28);
      animation: rabezConfirmPop 0.45s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes rabezConfirmPop {
      0% { transform: scale(0.7); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
    .rabez-confirm-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--rabez-neon);
      color: #080A09;
      font-weight: 800;
      font-size: 0.76rem;
      letter-spacing: 0.05em;
      padding: 4px 11px;
      border-radius: 5px;
      text-transform: uppercase;
      margin-bottom: 8px;
    }
    .rabez-confirm-title {
      font-family: var(--font-heading);
      font-size: clamp(1.7rem, 3vw, 2.35rem);
      font-weight: 800;
      color: #FFFFFF;
      line-height: 1.15;
      margin: 0 0 6px;
      letter-spacing: -0.01em;
    }
    .rabez-confirm-sub {
      color: #B4BDB8;
      font-size: 0.94rem;
      line-height: 1.5;
      margin: 0;
    }
    .rabez-confirm-meta-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 14px;
    }
    .rabez-confirm-meta-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 11px;
      background: #131815;
      border: 1px solid #26302B;
      border-radius: 6px;
      font-size: 0.78rem;
      font-weight: 600;
      color: #D2D8D4;
    }
    .rabez-confirm-meta-pill strong {
      color: #FFFFFF;
    }

    /* Main Container Overlapping Hero */
    .rabez-confirm-main {
      margin-top: -34px;
      position: relative;
      z-index: 10;
      padding-bottom: 56px;
    }
    .rabez-confirm-card {
      background: #FFFFFF;
      border: 1px solid #DCE3DF;
      border-radius: 16px;
      padding: 28px;
      box-shadow: 0 16px 38px rgba(10, 12, 11, 0.06);
    }

    /* Compact 5-step Timeline */
    .rabez-confirm-steps {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 10px;
      padding-bottom: 24px;
      margin-bottom: 24px;
      border-bottom: 1px solid #E5EBE7;
    }
    .rabez-confirm-step {
      padding: 12px 12px;
      border-radius: 10px;
      border: 1px solid #E5EBE7;
      background: #F8FAF9;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .rabez-confirm-step.is-active {
      background: #0A0C0B;
      border-color: var(--rabez-neon);
      color: #FFFFFF;
    }
    .rabez-confirm-step__dot {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: #E2E8E5;
      color: #4A544F;
      font-size: 0.74rem;
      font-weight: 800;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .rabez-confirm-step.is-active .rabez-confirm-step__dot {
      background: var(--rabez-neon);
      color: #080A09;
    }
    .rabez-confirm-step__label {
      font-size: 0.82rem;
      font-weight: 800;
      line-height: 1.2;
    }
    .rabez-confirm-step__sub {
      font-size: 0.71rem;
      color: #6E7672;
      margin-top: 2px;
    }
    .rabez-confirm-step.is-active .rabez-confirm-step__sub {
      color: #B4BDB8;
    }

    /* Two Column Grid */
    .rabez-confirm-grid {
      display: grid;
      grid-template-columns: 1.25fr 1fr;
      gap: 24px;
      align-items: start;
    }
    .rabez-confirm-grid > div,
    .rabez-confirm-steps > div {
      min-width: 0;
    }
    .rabez-confirm-panel {
      background: #F9FBFA;
      border: 1px solid #E2E8E5;
      border-radius: 12px;
      padding: 20px 22px;
      margin-bottom: 18px;
    }
    .rabez-confirm-panel__title {
      font-size: 1rem;
      font-weight: 800;
      color: #0A0C0B;
      margin: 0 0 14px;
      padding-bottom: 10px;
      border-bottom: 1px solid #E5EBE7;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }
    .rabez-info-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 14px 18px;
    }
    .rabez-info-item--full {
      grid-column: 1 / -1;
    }
    .rabez-info-label {
      font-size: 0.74rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #6B7570;
      margin-bottom: 3px;
    }
    .rabez-info-val {
      font-size: 0.92rem;
      font-weight: 700;
      color: #111827;
      line-height: 1.4;
      word-break: break-word;
    }

    /* Next Steps Box */
    .rabez-next-steps {
      background: #FFFFFF;
      border: 1px solid #E2E8E5;
      border-left: 3px solid var(--rabez-neon);
      border-radius: 10px;
      padding: 16px 18px;
      margin-bottom: 18px;
    }
    .rabez-next-steps ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: grid;
      gap: 9px;
    }
    .rabez-next-steps li {
      display: flex;
      align-items: flex-start;
      gap: 9px;
      font-size: 0.85rem;
      color: #374151;
      line-height: 1.45;
    }
    .rabez-next-steps__num {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #0A0C0B;
      color: var(--rabez-neon);
      font-size: 0.7rem;
      font-weight: 800;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }

    /* Action Buttons */
    .rabez-confirm-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
    }
    .rabez-btn-outline-dark {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      padding: 11px 16px;
      border-radius: 8px;
      border: 1.5px solid #CBD5D0;
      background: #FFFFFF;
      color: #0A0C0B;
      font-weight: 700;
      font-size: 0.85rem;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.16s ease;
    }
    .rabez-btn-outline-dark:hover {
      border-color: #0A0C0B;
      background: #F2F5F3;
    }

    /* Summary Card */
    .rabez-summary-box {
      background: #FFFFFF;
      border: 1.5px solid #DCE3DF;
      border-radius: 12px;
      padding: 20px 22px;
    }
    .rabez-summary-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid #EEF2EF;
    }
    .rabez-summary-item:last-of-type {
      border-bottom: none;
    }
    .rabez-summary-item__img {
      width: 54px;
      height: 66px;
      object-fit: cover;
      border-radius: 7px;
      border: 1px solid #DCE3DF;
      background: #F2F5F3;
      flex-shrink: 0;
    }
    .rabez-summary-item__info {
      flex: 1;
      min-width: 0;
    }
    .rabez-summary-item__name {
      font-family: 'Red Hat Text', sans-serif;
      font-size: 0.9rem;
      font-weight: 700;
      color: #0A0C0B;
      line-height: 1.3;
      margin-bottom: 3px;
    }
    .rabez-summary-item__meta {
      font-size: 0.77rem;
      color: #5C6661;
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      align-items: center;
    }
    .rabez-summary-item__badge {
      background: #EEF2EF;
      color: #1F2937;
      padding: 2px 7px;
      border-radius: 4px;
      font-weight: 700;
      font-size: 0.72rem;
    }
    .rabez-summary-totals {
      border-top: 1px solid #DCE3DF;
      margin-top: 8px;
      padding-top: 14px;
      display: grid;
      gap: 8px;
      font-size: 0.88rem;
      color: #374151;
    }
    .rabez-summary-total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .rabez-summary-grand {
      border-top: 1.5px solid #DCE3DF;
      margin-top: 6px;
      padding-top: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 1.15rem;
      font-weight: 800;
      color: #0A0C0B;
    }

    @media (max-width: 960px) {
      .rabez-confirm-steps {
        grid-template-columns: repeat(2, 1fr);
      }
      .rabez-confirm-grid {
        grid-template-columns: 1fr;
      }
    }
    @media (max-width: 600px) {
      .rabez-confirm-hero {
        padding: 36px 0 54px;
      }
      .rabez-confirm-hero__left {
        flex-direction: column;
        gap: 14px;
      }
      .rabez-confirm-card {
        padding: 18px 16px;
        border-radius: 12px;
      }
      .rabez-confirm-steps {
        grid-template-columns: 1fr;
        gap: 8px;
      }
      .rabez-info-grid {
        grid-template-columns: 1fr;
      }
      .rabez-confirm-actions {
        flex-direction: column;
        align-items: stretch;
      }
      .rabez-confirm-actions > * {
        width: 100%;
        justify-content: center;
      }
    }
    @media print {
      .rabez-topbar, .rabez-header, .rabez-footer, .rabez-mobile-bar, .js-no-print {
        display: none !important;
      }
      .rabez-confirm-hero {
        background: #FFFFFF !important;
        color: #000000 !important;
        padding: 16px 0 !important;
        border-bottom: 2px solid #000000 !important;
      }
      .rabez-confirm-title, .rabez-confirm-sub {
        color: #000000 !important;
      }
      .rabez-confirm-main {
        margin-top: 16px !important;
      }
      .rabez-confirm-card {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
      }
    }
  </style>
@endpush

@section('content')
  @php
    $discount = max(0, (float) (($order->subtotal + $order->shipping_fee) - $order->total));
    $waPhone = preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '8801612359652');
    $waText = rawurlencode("Hello RABEZ! I just placed Order #{$order->order_number} (৳" . number_format($order->total) . "). I have a quick question about my order.");
  @endphp

  <section class="rabez-confirm-hero">
    <div class="rabez-container">
      <div class="rabez-confirm-hero__inner">
        <div class="rabez-confirm-hero__left">
          <div class="rabez-confirm-check" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
          <div>
            <span class="rabez-confirm-badge">Order #{{ $order->order_number }}</span>
            <h1 class="rabez-confirm-title">Thank You, {{ $order->customer_name }}!</h1>
            <p class="rabez-confirm-sub">
              Your Cash on Delivery order has been received successfully. Our team will verify your details and dispatch your jersey shortly.
            </p>
            <div class="rabez-confirm-meta-pills">
              <span class="rabez-confirm-meta-pill">
                Status: <strong style="color:#04F404;">{{ $order->status ?: 'Pending Confirmation' }}</strong>
              </span>
              <span class="rabez-confirm-meta-pill">
                Payment: <strong>Cash on Delivery</strong>
              </span>
              <span class="rabez-confirm-meta-pill">
                Date: <strong>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'Just Now' }}</strong>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <main class="rabez-section rabez-section--sm rabez-section--alt" style="padding-top:0;">
    <div class="rabez-container rabez-confirm-main">
      <div class="rabez-confirm-card">

        <!-- Order Progress Steps -->
        <div class="rabez-confirm-steps">
          <div class="rabez-confirm-step is-active">
            <span class="rabez-confirm-step__dot">1</span>
            <div>
              <div class="rabez-confirm-step__label">Order Received</div>
              <div class="rabez-confirm-step__sub">Placed Just Now</div>
            </div>
          </div>
          <div class="rabez-confirm-step">
            <span class="rabez-confirm-step__dot">2</span>
            <div>
              <div class="rabez-confirm-step__label">Confirmed</div>
              <div class="rabez-confirm-step__sub">Phone Verification</div>
            </div>
          </div>
          <div class="rabez-confirm-step">
            <span class="rabez-confirm-step__dot">3</span>
            <div>
              <div class="rabez-confirm-step__label">Packed &amp; QC</div>
              <div class="rabez-confirm-step__sub">Quality Checked</div>
            </div>
          </div>
          <div class="rabez-confirm-step">
            <span class="rabez-confirm-step__dot">4</span>
            <div>
              <div class="rabez-confirm-step__label">In Courier</div>
              <div class="rabez-confirm-step__sub">{{ $order->courier ?: 'Steadfast Courier' }}</div>
            </div>
          </div>
          <div class="rabez-confirm-step">
            <span class="rabez-confirm-step__dot">5</span>
            <div>
              <div class="rabez-confirm-step__label">Delivered</div>
              <div class="rabez-confirm-step__sub">1–3 Business Days</div>
            </div>
          </div>
        </div>

        <div class="rabez-confirm-grid">
          <!-- Left: Delivery Details & Next Steps -->
          <div>
            <div class="rabez-confirm-panel">
              <div class="rabez-confirm-panel__title">
                <span>Delivery &amp; Customer Information</span>
                <span style="font-size:0.76rem; font-weight:700; color:#008A00; background:rgba(4,244,4,0.12); padding:3px 9px; border-radius:4px; white-space:nowrap; flex-shrink:0;">COD Verified</span>
              </div>
              <div class="rabez-info-grid">
                <div>
                  <div class="rabez-info-label">Customer Name</div>
                  <div class="rabez-info-val">{{ $order->customer_name }}</div>
                </div>
                <div>
                  <div class="rabez-info-label">Mobile Number</div>
                  <div class="rabez-info-val">{{ $order->customer_phone }}</div>
                </div>
                <div class="rabez-info-item--full">
                  <div class="rabez-info-label">Delivery Address</div>
                  <div class="rabez-info-val">{{ $order->customer_address }}</div>
                </div>
                <div>
                  <div class="rabez-info-label">Delivery Area</div>
                  <div class="rabez-info-val">{{ $order->zone ?: 'All Over Bangladesh' }}</div>
                </div>
                <div>
                  <div class="rabez-info-label">Payment Method</div>
                  <div class="rabez-info-val">Cash on Delivery (৳ {{ number_format($order->total) }})</div>
                </div>
              </div>
            </div>

            <div class="rabez-next-steps js-no-print">
              <div style="font-size:0.88rem; font-weight:800; color:#0A0C0B; margin-bottom:10px;">What Happens Next?</div>
              <ul>
                <li>
                  <span class="rabez-next-steps__num">1</span>
                  <span>Our team may give you a quick confirmation call on <strong>{{ $order->customer_phone }}</strong> before dispatch.</span>
                </li>
                <li>
                  <span class="rabez-next-steps__num">2</span>
                  <span>Your jersey is quality-checked, heat-pressed (if custom print selected), and handed over to <strong>{{ $order->courier ?: 'Steadfast / Pathao Courier' }}</strong>.</span>
                </li>
                <li>
                  <span class="rabez-next-steps__num">3</span>
                  <span>Delivery takes <strong>1–3 days</strong> across Bangladesh. Check your package on delivery and pay <strong>৳ {{ number_format($order->total) }}</strong> in cash.</span>
                </li>
              </ul>
            </div>

            <div class="rabez-confirm-actions js-no-print">
              <a href="{{ route('track-order', ['order' => $order->order_number]) }}" class="rabez-btn rabez-btn--primary" style="display:inline-flex; align-items:center; gap:7px;">
                <span>Track Order &amp; Invoice</span>
                <span>&rarr;</span>
              </a>
              <button type="button" onclick="window.print()" class="rabez-btn-outline-dark">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 6 2 18 2 18 9"></polyline>
                  <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                  <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Print Receipt</span>
              </button>
              <a href="https://api.whatsapp.com/send/?phone={{ $waPhone }}&text={{ $waText }}" target="_blank" rel="noopener" class="rabez-btn-outline-dark">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                </svg>
                <span>Need Changes? WhatsApp</span>
              </a>
              <a href="{{ route('shop') }}" class="rabez-btn-outline-dark">
                <span>Continue Shopping</span>
              </a>
            </div>
          </div>

          <!-- Right: Ordered Items & Summary -->
          <div>
            <div class="rabez-summary-box">
              <div class="rabez-confirm-panel__title" style="margin-bottom:6px;">
                <span>Order Summary ({{ $order->items->sum('qty') }} {{ $order->items->sum('qty') === 1 ? 'Item' : 'Items' }})</span>
                <span style="font-size:0.78rem; color:#6B7570; font-weight:700; white-space:nowrap; flex-shrink:0;">#{{ $order->order_number }}</span>
              </div>

              <div>
                @foreach($order->items as $item)
                  @php
                    $itemImg = $item->image ?: '/assets/images/products/real-madrid-home-hs.jpg';
                    if (!str_starts_with($itemImg, 'http') && !str_starts_with($itemImg, '/')) {
                      $itemImg = '/' . $itemImg;
                    }
                    $variantLabel = $item->variant_title ?: $item->size;
                  @endphp
                  <div class="rabez-summary-item">
                    <img src="{{ $itemImg }}" alt="{{ $item->product_name }}" class="rabez-summary-item__img" onerror="this.src='/assets/images/products/real-madrid-home-hs.jpg'">
                    <div class="rabez-summary-item__info">
                      <div class="rabez-summary-item__name">{{ $item->product_name }}</div>
                      <div class="rabez-summary-item__meta">
                        <span class="rabez-summary-item__badge">{{ $variantLabel }}</span>
                        <span>Qty: <strong>{{ $item->qty }}</strong></span>
                        @if($item->custom_name || $item->custom_number)
                          <span style="color:#008A00; font-weight:700;">Print: {{ $item->custom_name }} #{{ $item->custom_number }}</span>
                        @endif
                      </div>
                    </div>
                    <strong style="font-size:0.92rem; color:#0A0C0B; white-space:nowrap;">৳ {{ number_format($item->total_price) }}</strong>
                  </div>
                @endforeach
              </div>

              <div class="rabez-summary-totals">
                <div class="rabez-summary-total-row">
                  <span>Subtotal</span>
                  <strong>৳ {{ number_format($order->subtotal) }}</strong>
                </div>
                @if($discount > 0)
                  <div class="rabez-summary-total-row" style="color:#16A34A; font-weight:700;">
                    <span>Coupon Discount</span>
                    <span>- ৳ {{ number_format($discount) }}</span>
                  </div>
                @endif
                <div class="rabez-summary-total-row">
                  <span>Delivery Charge</span>
                  <strong>৳ {{ number_format($order->shipping_fee) }}</strong>
                </div>
              </div>

              <div class="rabez-summary-grand">
                <span>Total Payable (COD)</span>
                <span style="color:#008A00; white-space:nowrap; flex-shrink:0;">৳ {{ number_format($order->total) }}</span>
              </div>
            </div>

            <div class="js-no-print" style="margin-top:14px; padding:14px 16px; border-radius:10px; background:#F3F6F4; border:1px dashed #CBD5D0; display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; font-size:0.82rem; color:#374151;">
              @auth
                <span>View this order anytime from your dashboard.</span>
                <a href="{{ route('account.dashboard') }}" style="color:#008A00; font-weight:800; text-decoration:none; white-space:nowrap;">My Account &rarr;</a>
              @else
                <span>Save your order history by signing in to RABEZ.</span>
                <a href="{{ route('login') }}" style="color:#008A00; font-weight:800; text-decoration:none; white-space:nowrap;">Sign In &rarr;</a>
              @endauth
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      try {
        if (window.RABEZ_STORE && typeof window.RABEZ_STORE.clearCart === "function") {
          window.RABEZ_STORE.clearCart();
        } else {
          localStorage.setItem("rabez_wc_cart_v1", "[]");
        }
        localStorage.removeItem("rabez_applied_coupon_v1");
        document.querySelectorAll(".js-cart-count").forEach((el) => (el.textContent = "0"));
        document.querySelectorAll(".js-cart-subtotal").forEach((el) => (el.textContent = "৳ 0"));
      } catch (e) {}
    });
  </script>
@endpush
