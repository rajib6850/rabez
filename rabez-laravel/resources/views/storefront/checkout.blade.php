@extends('layouts.storefront')

@section('title', 'Order Checkout - RABEZ Official Jersey Store')

@section('content')

  <main class="rabez-section rabez-section--alt">
    <div class="rabez-container">
      <div class="rabez-section-head">
        <h1 class="rabez-section-title">Order Checkout</h1>
      </div>

      <div class="js-order-success-banner" style="display:none;"></div>

      <form class="checkout woocommerce-checkout rabez-checkout-grid js-cod-checkout-form">
        <!-- Left: Customer Delivery Details -->
        <div class="rabez-checkout-card" id="customer_details">
          <h2 style="font-size:1.35rem; margin-bottom:16px;">Delivery Information</h2>

          <div class="rabez-form-grid">
            <div class="rabez-form-group">
              <label for="billing_first_name">Full Name *</label>
              <input type="text" id="billing_first_name" name="customer_name" required placeholder="আপনার পূর্ণ নাম / Your Name">
            </div>

            <div class="rabez-form-group">
              <label for="billing_phone">Mobile Number *</label>
              <input type="tel" id="billing_phone" name="customer_phone" required placeholder="01XXXXXXXXX">
            </div>

            <div class="rabez-form-group rabez-form-group--full">
              <label>Delivery Method &amp; Area *</label>
              @if(($settings['shipping_mode'] ?? 'flat') === 'flat')
                <div class="rabez-shipping-flat-card" style="background:#191E1B; border:1.5px solid #2B332E; border-radius:8px; padding:14px 16px; display:flex; justify-content:space-between; align-items:center;">
                  <div>
                    <div style="font-weight:700; color:#fff; font-size:14px;">All Over Bangladesh Delivery (Home Delivery)</div>
                    <div style="font-size:12px; color:#9BA59F; margin-top:2px;">Flat rate home delivery across all 64 districts in Bangladesh</div>
                  </div>
                  <div style="text-align:right;">
                    <span style="font-size:1.15rem; font-weight:800; color:#04F404;">৳ {{ intval($settings['shipping_flat_fee'] ?? 130) }}</span>
                    <input type="hidden" name="shipping_zone" value="{{ intval($settings['shipping_flat_fee'] ?? 130) }}">
                  </div>
                </div>
              @else
                <div class="rabez-shipping-options">
                  <label class="rabez-shipping-card is-selected">
                    <span>
                      <input type="radio" name="shipping_zone" value="{{ intval($settings['shipping_inside_dhaka'] ?? 70) }}" checked>
                      Inside Dhaka
                    </span>
                    <strong>৳ {{ intval($settings['shipping_inside_dhaka'] ?? 70) }}</strong>
                  </label>
                  <label class="rabez-shipping-card">
                    <span>
                      <input type="radio" name="shipping_zone" value="{{ intval($settings['shipping_outside_dhaka'] ?? 130) }}">
                      Outside Dhaka
                    </span>
                    <strong>৳ {{ intval($settings['shipping_outside_dhaka'] ?? 130) }}</strong>
                  </label>
                </div>
              @endif
            </div>

            <div class="rabez-form-group rabez-form-group--full">
              <label for="billing_address_1">Full Delivery Address (House, Road, Area, District) *</label>
              <input type="text" id="billing_address_1" name="customer_address" required placeholder="e.g. House 12, Road 5, Dhanmondi, Dhaka">
            </div>

            <div class="rabez-form-group rabez-form-group--full">
              <label for="order_comments">Custom Print / Delivery Note (Optional)</label>
              <textarea id="order_comments" name="order_notes" placeholder="Any custom name/number or delivery note..."></textarea>
            </div>
          </div>
        </div>

        <!-- Right: Order Review & Payment -->
        <div class="rabez-checkout-card digi-checkout-order-details" id="order_review">
          <h2 style="font-size:1.35rem; margin-bottom:14px;">Order Summary</h2>

          <div class="js-checkout-items" style="margin-bottom:16px;"></div>

          <!-- Coupon Application Box -->
          <div class="rabez-checkout-coupon" style="padding:10px 12px; background:#191E1B; border:1px dashed #2B332E; border-radius:8px; margin-bottom:14px; transition:border-color 0.2s ease;">
            <button type="button" class="rabez-coupon-toggle js-toggle-checkout-coupon" onclick="if(window.toggleCheckoutCoupon){window.toggleCheckoutCoupon();}else{const f=document.getElementById('checkout-coupon-fields');if(f)f.style.display=f.style.display==='none'?'block':'none';}" style="width:100%; display:flex; align-items:center; justify-content:space-between; background:transparent; border:none; padding:0; cursor:pointer; text-align:left;">
              <div style="font-size:12.5px; font-weight:700; color:#E2E8F0; display:flex; align-items:center; gap:7px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                  <circle cx="7" cy="7" r="1.5" fill="#04F404"></circle>
                </svg>
                <span>Have a Discount Coupon?</span>
              </div>
              <svg class="rabez-coupon-chevron js-checkout-coupon-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition:transform 0.25s ease;">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>
            <div id="checkout-coupon-fields" style="display:none; margin-top:10px; padding-top:10px; border-top:1px dashed #2B332E;">
              <div style="display:flex; gap:8px;">
                <input type="text" id="checkout-coupon-code" name="coupon_code" placeholder="e.g. RABEZ100" style="flex:1; text-transform:uppercase; font-weight:700; font-size:13px; padding:8px 10px; background:#0F1311; border:1px solid #2B332E; color:#fff; border-radius:6px; letter-spacing:0.04em;">
                <button type="button" id="js-apply-coupon-btn" style="background:#04F404; color:#0A0C0B; font-weight:800; font-size:13px; padding:8px 18px; border:none; border-radius:6px; cursor:pointer; white-space:nowrap; letter-spacing:0.04em; transition:all 0.15s; display:inline-flex; align-items:center; gap:6px;" onmouseover="this.style.background='#22C55E'" onmouseout="this.style.background='#04F404'">Apply</button>
              </div>
              <div id="checkout-coupon-msg" style="font-size:12px; margin-top:6px; display:none; line-height:1.3;"></div>
            </div>
          </div>

          <div style="display:grid; gap:10px; font-size:0.92rem; padding:14px 0; border-bottom:1px solid #E4E7E4;">
            <div style="display:flex; justify-content:space-between;">
              <span>Subtotal</span>
              <strong class="js-checkout-subtotal">৳ 1,150</strong>
            </div>
            <div class="js-checkout-discount-row" style="display:none; justify-content:space-between; color:#16a34a; font-weight:600;">
              <span>Discount (<span class="js-applied-coupon-code"></span>)</span>
              <span>- <strong class="js-checkout-discount">৳ 0</strong></span>
            </div>
            <div style="display:flex; justify-content:space-between;">
              <span>Delivery Charge</span>
              <strong class="js-checkout-shipping">৳ {{ intval($settings['shipping_flat_fee'] ?? 130) }}</strong>
            </div>
          </div>

          <div style="display:flex; justify-content:space-between; align-items:center; font-size:1.25rem; font-weight:800; padding:16px 0;">
            <span>Total Payable</span>
            <span class="js-checkout-total" style="color:#008A00;">৳ 1,280</span>
          </div>

          <div style="background:#F6F7F5; border-left:3px solid #04F404; padding:12px 14px; font-size:0.85rem; margin-bottom:16px;">
            <div style="font-weight:700; color:#111; margin-bottom:3px;">Payment Method:</div>
            <div style="color:#4B5563; line-height:1.4;">
              <strong>Cash on Delivery (COD):</strong> Check your jersey upon delivery and pay cash.<br>
              <span style="color:#008A00; font-weight:600;">bKash / Nagad / Cards:</span> Online payment options coming soon.
            </div>
          </div>

          <div style="display:grid; gap:10px;">
            <button type="submit" id="place_order" class="rabez-btn rabez-btn--primary rabez-btn--full" style="font-size:15px; font-weight:800; padding:14px; display:inline-flex; align-items:center; justify-content:center; gap:8px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
              <span>Confirm Order &bull; Cash on Delivery</span>
            </button>
            <button type="button" class="rabez-btn rabez-btn--dark rabez-btn--full js-whatsapp-checkout" style="display:inline-flex; align-items:center; justify-content:center; gap:8px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
              </svg>
              <span>Order via WhatsApp Instead</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </main>

  <!-- Footer -->
@endsection
