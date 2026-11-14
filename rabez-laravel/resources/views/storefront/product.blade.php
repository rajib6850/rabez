@extends('layouts.storefront')

@section('title', $product->name . ' – RABEZ Football Jersey Store Bangladesh')

@section('content')
  @php
    $featuredImg = $product->image ? asset($product->image) : asset('assets/images/products/real-madrid-home-hs.jpg');
    $gallery = is_array($product->gallery) ? $product->gallery : (json_decode($product->gallery ?? '[]', true) ?: []);
    $backImg = !empty($gallery) ? asset($gallery[0]) : null;
    $allImages = array_unique(array_merge([$featuredImg], array_map(fn($g) => asset($g), $gallery)));
    $sizes = $product->variants->isNotEmpty() ? $product->variants->pluck('size')->toArray() : ['S', 'M', 'L', 'XL', 'XXL'];
    $defaultSize = in_array('L', $sizes) ? 'L' : ($sizes[0] ?? 'L');
  @endphp

  <!-- WooCommerce Single Product Container -->
  <main class="rabez-section">
    <div class="rabez-container">
      <div class="product type-product rabez-single-product js-single-product-root" data-product-id="{{ $product->id }}">
        <!-- Left: Gallery & Front/Back Switcher -->
        <div class="rabez-gallery woocommerce-product-gallery">
          <div class="rabez-gallery__thumbs js-sp-thumbs">
            @foreach($allImages as $idx => $imgUrl)
              <button type="button" class="rabez-gallery__thumb {{ $idx === 0 ? 'is-active' : '' }} js-sp-thumb-btn" data-src="{{ $imgUrl }}" title="{{ $idx === 0 ? 'Front View' : ($idx === 1 && $backImg ? 'Back View' : 'Detail View #' . $idx) }}">
                <img src="{{ $imgUrl }}" alt="{{ $product->name }} Thumbnail {{ $idx + 1 }}">
              </button>
            @endforeach
          </div>
          <div class="rabez-gallery__main">
            <img src="{{ $featuredImg }}" alt="{{ $product->name }} - Official RABEZ Bangladesh" class="js-sp-main-img" width="600" height="800">
          </div>
        </div>

        <!-- Right: Summary & Interactive Buying Form -->
        <div class="summary entry-summary">
          <div class="rabez-summary__breadcrumb">
            <a href="{{ route('home') }}">Home</a> / <a href="{{ route('shop') }}">Jerseys</a> / 
            <span>{{ $product->club_label ?: ucwords(str_replace('-', ' ', $product->club ?: 'Football')) }} &bull; {{ $product->sleeve_label ?: ucwords(str_replace('-', ' ', $product->sleeve ?: 'Jersey')) }}</span> 
            &bull; SKU: <strong class="js-sp-sku">{{ $product->sku }}</strong>
          </div>

          <h1 class="product_title entry-title rabez-summary__title js-sp-title">
            {{ $product->name }}
          </h1>

          <div class="rabez-summary__price-row">
            <span class="price rabez-summary__price js-sp-price-display">৳ {{ number_format($product->price) }}</span>
            @if($product->regular_price && $product->regular_price > $product->price)
              <del style="color:#7D8782; font-size:1.15rem; font-weight:600; margin-left:8px;">৳ {{ number_format($product->regular_price) }}</del>
            @endif
            @if($product->is_ready_stock)
              <span class="rabez-summary__stock">&#10003; Ready Stock</span>
            @else
              <span class="rabez-summary__stock" style="background:rgba(239,68,68,0.15); color:#EF4444; border-color:rgba(239,68,68,0.3);">Pre-Order Only</span>
            @endif
          </div>

          <p class="rabez-summary__desc js-sp-desc">
            {{ $product->short_desc ?: 'Premium Player Edition football jersey with authentic double-knit jacquard weave, moisture-wicking aerodynamic fabric, and high-density heat-pressed silicone club crest.' }}
          </p>

          <!-- Size Swatches (pa_size) -->
          <div class="rabez-variation-block variations_form">
            <div class="rabez-variation-header">
              <span>Select Jersey Size: <strong id="js-selected-size-label" style="color:var(--rabez-neon);">{{ $defaultSize }}</strong></span>
              <button type="button" class="rabez-size-chart-link js-open-size-chart-modal" style="background:none; border:none; color:#04F404; text-decoration:underline; font-weight:700; font-size:13px; cursor:pointer; display:inline-flex; align-items:center; gap:5px; padding:0;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21.3 15.3l-6.6-6.6a2 2 0 0 0-2.8 0L2.7 17.9a2 2 0 0 0 0 2.8l1.4 1.4a2 2 0 0 0 2.8 0l9.2-9.2a2 2 0 0 0 0-2.8l-1.4-1.4a2 2 0 0 0-2.8 0L3.7 18.9"></path>
                  <path d="M14.5 4.5l5 5"></path>
                </svg>
                <span>Size Chart</span>
              </button>
            </div>
            <div class="rabez-size-swatches js-sp-swatches">
              @foreach($sizes as $s)
                <button type="button" class="rabez-size-swatch {{ $s === $defaultSize ? 'is-selected' : '' }}" data-size="{{ $s }}" aria-pressed="{{ $s === $defaultSize ? 'true' : 'false' }}">{{ $s }}</button>
              @endforeach
            </div>
          </div>

          @if($product->has_custom_print)
            <!-- Optional Custom Name & Number Addon -->
            <div class="rabez-print-addon" style="border:1px solid #2B332E; border-radius:6px; padding:12px 14px; margin:18px 0; background:#121614;">
              <label style="display:flex; align-items:center; justify-content:space-between; gap:8px; cursor:pointer; user-select:none;">
                <span style="font-weight:700; font-size:14px; color:#fff; display:flex; align-items:center; gap:8px; min-width:0;">
                  <input type="checkbox" id="js-custom-print-toggle" style="accent-color:#04F404; width:16px; height:16px; flex-shrink:0; cursor:pointer;">
                  <span>Custom Name &amp; Number</span>
                </span>
                <span style="color:#04F404; font-weight:800; font-size:13px; white-space:nowrap; flex-shrink:0;">+ ৳ {{ intval($customPrintFee ?? ($settings['custom_print_fee'] ?? 250)) }}</span>
              </label>
              <div id="js-custom-print-inputs" style="display:none; margin-top:12px; gap:10px; grid-template-columns:2fr 1fr;">
                <input type="text" id="js-sp-custom-name" maxlength="12" placeholder="Name on Back (e.g. MESSI)" style="height:38px; background:#191E1B; border:1px solid #2D3631; border-radius:4px; padding:0 10px; color:#fff; font-size:12.5px; text-transform:uppercase;">
                <input type="text" id="js-sp-custom-num" maxlength="2" placeholder="Number (10)" style="height:38px; background:#191E1B; border:1px solid #2D3631; border-radius:4px; padding:0 10px; color:#fff; font-size:12.5px;">
              </div>
            </div>
          @endif

          <!-- Quantity + Add to Cart -->
          <div class="rabez-purchase-row">
            <div class="rabez-qty-box">
              <button type="button" class="rabez-qty-btn js-sp-qty-minus" aria-label="Decrease quantity">&minus;</button>
              <input type="number" class="rabez-qty-input js-sp-qty" value="1" min="1" max="50" aria-label="Quantity" readonly>
              <button type="button" class="rabez-qty-btn js-sp-qty-plus" aria-label="Increase quantity">+</button>
            </div>
            <button type="button" id="js-sp-add-to-cart-btn" class="rabez-btn rabez-btn--primary rabez-btn--full" style="font-size:15px; font-weight:800; display:inline-flex; align-items:center; justify-content:center; gap:8px;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5a1.5 1.5 0 0 1-1.5 1.5h-14A1.5 1.5 0 0 1 3.5 21l1-12.5z"/>
              </svg>
              <span>Add to Bag &mdash; <span id="js-sp-btn-price">৳ {{ number_format($product->price) }}</span></span>
            </button>
          </div>

          <button type="button" id="js-sp-whatsapp-order-btn" class="rabez-btn rabez-btn--dark rabez-btn--full" style="margin-top:10px; display:flex; align-items:center; justify-content:center; gap:8px;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
              <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
            <span>Order via WhatsApp</span>
          </button>

          <ul class="rabez-delivery-list" style="margin-top:20px;">
            @if(($settings['shipping_mode'] ?? 'flat') === 'flat')
              <li>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                  <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
                  <path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/>
                  <circle cx="7" cy="18" r="2"/>
                  <circle cx="17" cy="18" r="2"/>
                </svg>
                <span><strong>Home Delivery (All Bangladesh):</strong> ৳ {{ intval($settings['shipping_flat_fee'] ?? 130) }} (Flat Rate)</span>
              </li>
            @else
              <li>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                  <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
                  <path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/>
                  <circle cx="7" cy="18" r="2"/>
                  <circle cx="17" cy="18" r="2"/>
                </svg>
                <span><strong>Inside Dhaka:</strong> ৳ {{ intval($settings['shipping_inside_dhaka'] ?? 70) }} (24&ndash;48 Hours Delivery)</span>
              </li>
              <li>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                  <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
                  <path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/>
                  <circle cx="7" cy="18" r="2"/>
                  <circle cx="17" cy="18" r="2"/>
                </svg>
                <span><strong>Outside Dhaka:</strong> ৳ {{ intval($settings['shipping_outside_dhaka'] ?? 130) }} (2&ndash;3 Days Courier Service)</span>
              </li>
            @endif
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                <rect x="2" y="6" width="20" height="12" rx="2"/>
                <circle cx="12" cy="12" r="2"/>
                <path d="M6 12h.01M18 12h.01"/>
              </svg>
              <span><strong>Payment:</strong> Cash on Delivery, bKash, Nagad available &bull; Open parcel &amp; check jersey</span>
            </li>
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
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
    </div>
  </main>

  <!-- Scoped Styles for Dark Description & Size Modal -->
  <style>
    .rabez-product-specs-section {
      background: #0B0F0D !important;
      border-top: 1px solid #1C2420 !important;
      padding-top: 50px !important;
      padding-bottom: 60px !important;
    }
    .rabez-desc-panel,
    .rabez-desc-panel * {
      box-sizing: border-box;
    }
    .rabez-desc-panel {
      color: #E5E7EB !important;
      font-size: 15px !important;
      line-height: 1.85 !important;
      padding: 10px 0 20px !important;
    }
    .rabez-desc-panel p {
      color: #D1D5DB !important;
      line-height: 1.85 !important;
      font-size: 15px !important;
      margin: 0 0 18px 0 !important;
    }
    .rabez-desc-panel h3 {
      color: #FFFFFF !important;
      font-weight: 800 !important;
      font-size: 19px !important;
      line-height: 1.35 !important;
      margin: 0 0 14px 0 !important;
      letter-spacing: -0.01em;
    }
    .rabez-desc-panel h4 {
      color: #FFFFFF !important;
      font-weight: 800 !important;
      font-size: 16.5px !important;
      line-height: 1.4 !important;
      margin: 24px 0 12px 0 !important;
      letter-spacing: -0.01em;
    }
    .rabez-desc-panel h5 {
      color: #FFFFFF !important;
      font-weight: 700 !important;
      font-size: 15px !important;
      line-height: 1.4 !important;
      margin: 18px 0 8px 0 !important;
    }
    .rabez-desc-panel ul {
      color: #E5E7EB !important;
      padding-left: 22px !important;
      margin: 12px 0 22px 0 !important;
      display: grid !important;
      gap: 11px !important;
    }
    .rabez-desc-panel li {
      color: #E5E7EB !important;
      line-height: 1.8 !important;
      font-size: 14.5px !important;
    }
    .rabez-desc-panel strong {
      color: #FFFFFF !important;
      font-weight: 700 !important;
    }
    .rabez-rich-description {
      color: #E5E7EB !important;
      line-height: 1.85 !important;
      font-size: 15px !important;
      word-break: break-word;
    }

    /* Desktop Tab Bar */
    .rabez-desc-tabs {
      display: flex;
      border-bottom: 2px solid #1C2420;
      margin-bottom: 30px;
      gap: 0;
    }
    .rabez-desc-tab {
      background: none;
      border: none;
      padding: 14px 24px;
      font-weight: 700;
      font-size: 15px;
      color: #9CA3AF;
      border-bottom: 3px solid transparent;
      margin-bottom: -2px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: color 0.15s, border-color 0.15s;
    }
    .rabez-desc-tab:first-child {
      padding-left: 0 !important;
    }
    .rabez-desc-tab:last-child {
      padding-right: 0 !important;
    }
    .rabez-desc-tab:hover {
      color: #FFFFFF !important;
    }
    .rabez-desc-tab.is-active {
      color: #FFFFFF !important;
      font-weight: 800 !important;
      border-bottom-color: #04F404 !important;
    }

    /* Accordion buttons on desktop are hidden */
    .rabez-accordion-btn {
      display: none !important;
    }

    /* Mobile Accordion Styles */
    @media (max-width: 768px) {
      .rabez-product-specs-section {
        padding-top: 32px !important;
        padding-bottom: 44px !important;
      }
      .rabez-desc-tabs {
        display: none !important;
      }
      .rabez-desc-accordion-item {
        margin-bottom: 12px;
        border-radius: 8px;
        overflow: hidden;
      }
      .rabez-accordion-btn {
        display: flex !important;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 15px 18px;
        background: #131A16 !important;
        border: 1px solid #233128 !important;
        border-radius: 8px;
        color: #FFFFFF !important;
        font-weight: 700;
        font-size: 14.5px;
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s;
        text-align: left;
      }
      .rabez-accordion-btn.is-active {
        background: #18221D !important;
        border-color: #04F404 !important;
        border-radius: 8px 8px 0 0 !important;
      }
      .rabez-accordion-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #FFFFFF;
      }
      .rabez-accordion-arrow {
        color: #04F404;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        margin-left: 10px;
        flex-shrink: 0;
      }
      .rabez-accordion-btn.is-active .rabez-accordion-arrow {
        transform: rotate(180deg);
      }
      .rabez-desc-accordion-item .rabez-desc-panel {
        display: none !important;
        padding: 20px 18px 24px !important;
        background: #0E1310 !important;
        border: 1px solid #233128 !important;
        border-top: none !important;
        border-radius: 0 0 8px 8px !important;
      }
      .rabez-desc-accordion-item .rabez-desc-panel.is-open-mobile {
        display: block !important;
      }
    }

  </style>

  <!-- Product Detailed Description & Specifications Section -->
  <section class="rabez-section rabez-section--sm rabez-product-specs-section">
    <div class="rabez-container">
      <div style="max-width: 900px; margin: 0 auto;">
        <!-- Desktop Tab Navigation -->
        <div class="rabez-desc-tabs">
          <button type="button" class="rabez-desc-tab is-active js-desc-tab" data-tab="details">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
            <span>Product Details &amp; Specifications</span>
          </button>
          <button type="button" class="rabez-desc-tab js-desc-tab" data-tab="care">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"></path>
            </svg>
            <span>Fabric &amp; Care</span>
          </button>
          <button type="button" class="rabez-desc-tab js-desc-tab" data-tab="shipping">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
              <path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/>
              <circle cx="7" cy="18" r="2"/>
              <circle cx="17" cy="18" r="2"/>
            </svg>
            <span>Delivery &amp; Exchange Policy</span>
          </button>
        </div>

        <!-- Section 1: Detailed Description -->
        <div class="rabez-desc-accordion-item" data-accordion="details">
          <button type="button" class="rabez-accordion-btn is-active js-accordion-btn" data-target="details">
            <span class="rabez-accordion-title">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
              </svg>
              <span>Product Details &amp; Specifications</span>
            </span>
            <span class="rabez-accordion-arrow">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </span>
          </button>
          <div class="rabez-desc-panel js-desc-panel is-open-mobile" data-panel="details" style="display:block;">
            @if(!empty($product->description))
              @if(!empty($product->short_desc))
                <div style="font-size:15.5px; font-weight:600; color:#FFFFFF; margin-bottom:22px; padding:18px 22px; background:#141C16; border-left:4px solid #04F404; border-radius:8px; line-height:1.75; box-shadow:0 4px 14px rgba(0,0,0,0.3);">
                  {{ $product->short_desc }}
                </div>
              @endif
              <div class="rabez-rich-description" style="font-size:15px; color:#E5E7EB; line-height:1.85; white-space:pre-line;">
                {!! nl2br(e($product->description)) !!}
              </div>
            @else
              <div style="font-size:15px;">
                <h3 style="font-size:19px; font-weight:800; color:#FFFFFF; margin-bottom:12px;">{{ $product->name }} &ndash; Official Overview</h3>
                <p style="margin-bottom:18px; color:#D1D5DB; line-height:1.85;">
                  {{ $product->short_desc ?: 'Crafted for dedicated supporters and athletes, this jersey combines lightweight aerodynamics with authentic club heritage.' }}
                </p>
                
                <h4 style="font-size:16.5px; font-weight:800; color:#FFFFFF; margin:24px 0 12px;">Key Design &amp; Build Highlights:</h4>
                <ul style="padding-left:22px; margin-bottom:20px; display:grid; gap:11px; color:#E5E7EB;">
                  <li><strong style="color:#04F404;">Authentic Club Detailing:</strong> High-density silicone heat-pressed crest and official sponsor branding for matchday authenticity.</li>
                  <li><strong style="color:#04F404;">Engineered Breathability:</strong> Double-knit aerodynamic jacquard mesh fabric with micro-ventilation panels for superior airflow.</li>
                  <li><strong style="color:#04F404;">Athletic Cut:</strong> Tailored to drape cleanly with ribbed sleeve cuffs and reinforced neckline for premium durability.</li>
                  <li><strong style="color:#04F404;">Custom Name &amp; Number Ready:</strong> Authentic squad typography heat-sealed at high pressure for peeling resistance.</li>
                </ul>
              </div>
            @endif
          </div>
        </div>

        <!-- Section 2: Fabric & Care -->
        <div class="rabez-desc-accordion-item" data-accordion="care">
          <button type="button" class="rabez-accordion-btn js-accordion-btn" data-target="care">
            <span class="rabez-accordion-title">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"></path>
              </svg>
              <span>Fabric Composition &amp; Care</span>
            </span>
            <span class="rabez-accordion-arrow">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </span>
          </button>
          <div class="rabez-desc-panel js-desc-panel" data-panel="care" style="display:none;">
            <h3 style="font-size:19px; font-weight:800; color:#FFFFFF; margin-bottom:12px;">Fabric Composition &amp; Washing Instructions</h3>
            <p style="margin-bottom:16px; color:#D1D5DB; line-height:1.85;">Keep your jersey in peak condition with proper washing care:</p>
            <ul style="padding-left:22px; display:grid; gap:11px; color:#E5E7EB; margin-bottom:20px;">
              <li><strong style="color:#FFFFFF;">Material:</strong> 100% Recycled Double-Knit Aeroready / Dri-FIT Polyester.</li>
              <li><strong style="color:#FFFFFF;">Weight:</strong> 190&ndash;210 GSM high-density player edition fabric.</li>
              <li><strong style="color:#FFFFFF;">Washing:</strong> Machine wash cold (30&deg;C) inside out, or hand wash gently in cold water.</li>
              <li><strong style="color:#FFFFFF;">Ironing:</strong> Do not iron directly over silicone crest, badges, or printed name/number.</li>
              <li><strong style="color:#FFFFFF;">Drying:</strong> Hang dry in shade. Avoid tumble dry and high heat.</li>
            </ul>
          </div>
        </div>

        <!-- Section 3: Delivery & Exchange -->
        <div class="rabez-desc-accordion-item" data-accordion="shipping">
          <button type="button" class="rabez-accordion-btn js-accordion-btn" data-target="shipping">
            <span class="rabez-accordion-title">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
                <path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/>
                <circle cx="7" cy="18" r="2"/>
                <circle cx="17" cy="18" r="2"/>
              </svg>
              <span>Delivery &amp; Exchange Policy</span>
            </span>
            <span class="rabez-accordion-arrow">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </span>
          </button>
          <div class="rabez-desc-panel js-desc-panel" data-panel="shipping" style="display:none;">
            <h3 style="font-size:19px; font-weight:800; color:#FFFFFF; margin-bottom:12px;">Fast Delivery &amp; 3-Day Hassle-Free Size Exchange</h3>
            <p style="margin-bottom:16px; color:#D1D5DB; line-height:1.85;">We deliver to all 64 districts across Bangladesh:</p>
            <ul style="padding-left:22px; display:grid; gap:11px; color:#E5E7EB; margin-bottom:20px;">
              @if(($settings['shipping_mode'] ?? 'flat') === 'flat')
                <li><strong style="color:#FFFFFF;">Flat Rate Delivery:</strong> ৳ {{ intval($settings['shipping_flat_fee'] ?? 130) }} all over Bangladesh via Steadfast / Pathao Courier.</li>
              @else
                <li><strong style="color:#FFFFFF;">Inside Dhaka Delivery:</strong> ৳ {{ intval($settings['shipping_inside_dhaka'] ?? 70) }} (24&ndash;48 Hours Delivery).</li>
                <li><strong style="color:#FFFFFF;">Outside Dhaka Delivery:</strong> ৳ {{ intval($settings['shipping_outside_dhaka'] ?? 130) }} (2&ndash;3 Days Home Delivery).</li>
              @endif
              <li><strong style="color:#04F404;">Check Before Payment:</strong> Open your parcel in front of the courier rider, inspect your jersey, and then pay.</li>
              <li><strong style="color:#04F404;">Size Exchange:</strong> If the size does not fit, contact us within 3 days via WhatsApp for an immediate size exchange.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Official RABEZ Size Guide Popup Modal (Mobile Bottom Sheet & Desktop Dialog) -->
  <div id="size-guide" class="rabez-modal-backdrop rabez-size-modal-backdrop js-size-chart-modal" role="dialog" aria-modal="true" aria-labelledby="size-modal-title">
    <div class="rabez-size-modal-card">
      <div class="rabez-size-modal-handle" aria-hidden="true"></div>

      <!-- Modal Header -->
      <div class="rabez-size-modal-header">
        <div class="rabez-size-modal-header__main">
          <div class="rabez-size-modal-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21.3 15.3l-6.6-6.6a2 2 0 0 0-2.8 0L2.7 17.9a2 2 0 0 0 0 2.8l1.4 1.4a2 2 0 0 0 2.8 0l9.2-9.2a2 2 0 0 0 0-2.8l-1.4-1.4a2 2 0 0 0-2.8 0L3.7 18.9"></path><path d="M14.5 4.5l5 5"></path></svg>
          </div>
          <div>
            <div style="display:flex; align-items:center; gap:8px;">
              <h2 id="size-modal-title" class="rabez-size-modal-title">Jersey Size Guide</h2>
              <span class="rabez-size-badge">Inches / ইঞ্চি</span>
            </div>
            <p class="rabez-size-modal-sub">Measure around chest in inches with a measuring tape for exact fit</p>
          </div>
        </div>
        <button type="button" class="rabez-size-modal-close js-close-size-chart-modal" aria-label="Close size chart">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <!-- Fit Tabs -->
      <div class="rabez-size-modal-tabs" role="tablist">
        <button type="button" class="rabez-size-modal-tab is-active js-fit-tab" data-fit="player">
          <span class="rabez-tab-dot"></span>
          Player Edition (Slim Fit)
        </button>
        <button type="button" class="rabez-size-modal-tab js-fit-tab" data-fit="retro">
          <span class="rabez-tab-dot"></span>
          Retro Series (Regular Fit)
        </button>
      </div>

      <!-- Tab Content: Player -->
      <div class="js-fit-panel" data-fit-panel="player">
        <div class="rabez-size-panel-grid rabez-size-modal-grid">
          <div>
            <div class="rabez-size-table-wrap">
              <table class="rabez-size-modal-table">
                <thead>
                  <tr>
                    <th>Size</th>
                    <th>Chest (ইন)</th>
                    <th>Length (ইন)</th>
                    <th>Weight Fit</th>
                  </tr>
                </thead>
                <tbody>
                  <tr data-row-size="S">
                    <td class="td-size"><strong>S</strong></td>
                    <td>36"</td>
                    <td>27"</td>
                    <td class="td-weight">50 – 60 kg</td>
                  </tr>
                  <tr data-row-size="M">
                    <td class="td-size"><strong>M</strong></td>
                    <td>38"</td>
                    <td>28"</td>
                    <td class="td-weight">60 – 70 kg</td>
                  </tr>
                  <tr data-row-size="L" class="is-highlight">
                    <td class="td-size">
                      <span class="td-size-wrap">
                        <strong>L</strong>
                        <span class="rabez-size-tag">Popular</span>
                      </span>
                    </td>
                    <td>40"</td>
                    <td>29"</td>
                    <td class="td-weight">70 – 78 kg</td>
                  </tr>
                  <tr data-row-size="XL">
                    <td class="td-size"><strong>XL</strong></td>
                    <td>42"</td>
                    <td>30"</td>
                    <td class="td-weight">78 – 86 kg</td>
                  </tr>
                  <tr data-row-size="XXL">
                    <td class="td-size"><strong>XXL</strong></td>
                    <td>44"</td>
                    <td>31"</td>
                    <td class="td-weight">86 – 96 kg</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="rabez-size-alert">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
              <span><strong>Athletic Slim Fit:</strong> Player Edition jerseys have an athletic tapered cut. If you prefer a loose fit, choose <strong>1 size up</strong>.</span>
            </div>

            <!-- Mobile Visual Toggle -->
            <div class="rabez-size-graphic-toggle-wrap">
              <button type="button" class="rabez-size-graphic-toggle js-toggle-graphic">
                <span style="display:flex; align-items:center; gap:6px;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                  <span>View Official Graphic Chart</span>
                </span>
                <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
              </button>
            </div>
          </div>

          <div class="rabez-size-graphic-box js-graphic-container">
            <img src="{{ asset('assets/images/brand/rabez-player-size-chart.jpg') }}" alt="RABEZ Player Edition Size Chart" loading="lazy">
          </div>
        </div>
      </div>

      <!-- Tab Content: Retro -->
      <div class="js-fit-panel" data-fit-panel="retro" style="display:none;">
        <div class="rabez-size-panel-grid rabez-size-modal-grid">
          <div>
            <div class="rabez-size-table-wrap">
              <table class="rabez-size-modal-table">
                <thead>
                  <tr>
                    <th>Size</th>
                    <th>Chest (ইন)</th>
                    <th>Length (ইন)</th>
                    <th>Weight Fit</th>
                  </tr>
                </thead>
                <tbody>
                  <tr data-row-size="S">
                    <td class="td-size"><strong>S</strong></td>
                    <td>38"</td>
                    <td>27"</td>
                    <td class="td-weight">55 – 65 kg</td>
                  </tr>
                  <tr data-row-size="M">
                    <td class="td-size"><strong>M</strong></td>
                    <td>40"</td>
                    <td>28"</td>
                    <td class="td-weight">65 – 74 kg</td>
                  </tr>
                  <tr data-row-size="L" class="is-highlight">
                    <td class="td-size">
                      <span class="td-size-wrap">
                        <strong>L</strong>
                        <span class="rabez-size-tag">Standard</span>
                      </span>
                    </td>
                    <td>42"</td>
                    <td>29"</td>
                    <td class="td-weight">74 – 82 kg</td>
                  </tr>
                  <tr data-row-size="XL">
                    <td class="td-size"><strong>XL</strong></td>
                    <td>44"</td>
                    <td>30"</td>
                    <td class="td-weight">82 – 90 kg</td>
                  </tr>
                  <tr data-row-size="XXL">
                    <td class="td-size"><strong>XXL</strong></td>
                    <td>46"</td>
                    <td>31"</td>
                    <td class="td-weight">90 – 100 kg</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="rabez-size-alert rabez-size-alert--retro">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
              <span><strong>Regular Comfort Fit:</strong> Retro edition jerseys are relaxed cut. Choose your normal standard T-shirt size.</span>
            </div>

            <!-- Mobile Visual Toggle -->
            <div class="rabez-size-graphic-toggle-wrap">
              <button type="button" class="rabez-size-graphic-toggle js-toggle-graphic">
                <span style="display:flex; align-items:center; gap:6px;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                  <span>View Official Graphic Chart</span>
                </span>
                <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
              </button>
            </div>
          </div>

          <div class="rabez-size-graphic-box js-graphic-container">
            <img src="{{ asset('assets/images/brand/rabez-retro-size-chart.jpg') }}" alt="RABEZ Retro Size Chart" loading="lazy">
          </div>
        </div>
      </div>

      <!-- Quick Select Footer Action Bar -->
      <div class="rabez-size-modal-footer">
        <span class="rabez-size-footer-label">Tap to Choose Size:</span>
        <div class="rabez-size-quick-pills">
          <button type="button" class="rabez-size-quick-pill js-quick-select" data-size="S">S</button>
          <button type="button" class="rabez-size-quick-pill js-quick-select" data-size="M">M</button>
          <button type="button" class="rabez-size-quick-pill js-quick-select" data-size="L">L</button>
          <button type="button" class="rabez-size-quick-pill js-quick-select" data-size="XL">XL</button>
          <button type="button" class="rabez-size-quick-pill js-quick-select" data-size="XXL">XXL</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Related Jerseys -->
  @if($relatedProducts->isNotEmpty())
    <section class="rabez-section rabez-section--alt woocommerce">
      <div class="rabez-container">
        <div class="rabez-section-head" style="margin-bottom: 24px;">
          <h2 class="rabez-section-title">Related Jerseys &amp; Kits</h2>
          <p style="color:#7D8782; font-size:14px; margin-top:4px;">You might also like these matching club &amp; national kits.</p>
        </div>
        <ul class="rabez-products-grid products columns-4" style="list-style:none; padding:0; margin:0;">
          @foreach($relatedProducts as $rel)
            @php
              $relImg = $rel->image ? asset($rel->image) : asset('assets/images/products/real-madrid-home-hs.jpg');
              $relGallery = is_array($rel->gallery) ? $rel->gallery : (json_decode($rel->gallery ?? '[]', true) ?: []);
              $relBackImg = !empty($relGallery) ? asset($relGallery[0]) : null;
              $relBadge = $rel->is_featured ? 'BEST SELLER' : ($rel->sleeve === 'winter-hoodie' ? 'WINTER DROP' : ($rel->sleeve === 'full-sleeve' ? 'FULL SLEEVE' : 'MATCH KIT'));
            @endphp
            <li class="product type-product rabez-product-card {{ $relBackImg ? 'has-back-view' : '' }}" data-product-id="{{ $rel->id }}">
              <div class="rabez-product-card__media">
                <a href="{{ route('product.detail', $rel->slug ?: $rel->id) }}" class="woocommerce-LoopProduct-link">
                  <img src="{{ $relImg }}" class="rabez-card-img-front" alt="{{ $rel->name }} - Front View" loading="lazy" width="500" height="667">
                  @if($relBackImg)
                    <img src="{{ $relBackImg }}" class="rabez-card-img-back" alt="{{ $rel->name }} - Back View" loading="lazy" width="500" height="667">
                    <span class="rabez-card-flip-pill">Back View</span>
                  @endif
                </a>
                <div class="rabez-product-card__badges">
                  <span class="rabez-badge rabez-badge--neon">{{ $relBadge }}</span>
                </div>
                <div class="rabez-product-card__actions">
                  <button type="button" class="rabez-card-icon-btn js-wishlist-toggle" data-product-id="{{ $rel->id }}" aria-label="Add to Wishlist" title="Wishlist">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                  </button>
                  <button type="button" class="rabez-card-icon-btn js-quick-view" data-product-id="{{ $rel->id }}" aria-label="Quick View" title="Quick View">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
              </div>
              <div class="rabez-product-card__body">
                <div class="rabez-product-card__meta">
                  <span>{{ $rel->club_label ?: ucwords(str_replace('-', ' ', $rel->club ?: 'Club')) }}</span>
                  <span class="rabez-product-card__sleeve">{{ $rel->sleeve_label ?: ucwords(str_replace('-', ' ', $rel->sleeve ?: 'Jersey')) }}</span>
                </div>
                <h3 class="rabez-product-card__title woocommerce-loop-product__title">
                  <a href="{{ route('product.detail', $rel->slug ?: $rel->id) }}">{{ $rel->name }}</a>
                </h3>

                <div class="rabez-product-card__sizes" role="group" aria-label="Select Jersey Size">
                  <div class="rabez-size-swatches js-card-swatches">
                    <button type="button" class="rabez-size-swatch" data-size="S">S</button>
                    <button type="button" class="rabez-size-swatch" data-size="M">M</button>
                    <button type="button" class="rabez-size-swatch is-selected" data-size="L">L</button>
                    <button type="button" class="rabez-size-swatch" data-size="XL">XL</button>
                    <button type="button" class="rabez-size-swatch" data-size="XXL">XXL</button>
                  </div>
                </div>

                <div class="rabez-product-card__footer">
                  <div class="price rabez-product-card__price">
                    <del aria-hidden="true"><span class="woocommerce-Price-amount amount">৳ {{ number_format($rel->price + 200) }}</span></del>
                    <ins><span class="woocommerce-Price-amount amount">৳ {{ number_format($rel->price) }}</span></ins>
                  </div>
                  <button type="button" class="rabez-add-cart-btn add_to_cart_button ajax_add_to_cart js-add-to-cart" data-product-id="{{ $rel->id }}">
                    <svg class="rabez-btn-icon-cart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" width="14" height="14">
                      <path d="M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5a1.5 1.5 0 0 1-1.5 1.5h-14A1.5 1.5 0 0 1 3.5 21l1-12.5z"></path>
                    </svg>
                    <span>Add</span>
                  </button>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </section>
  @endif

  @push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const basePrice = {{ (float) $product->price }};
      const allowCustomPrint = {{ $product->has_custom_print ? 'true' : 'false' }};
      const customPrintFee = Number({{ intval($customPrintFee ?? ($settings['custom_print_fee'] ?? 250)) }});
      let hasCustomPrint = false;
      let currentSize = "{{ $defaultSize }}";
      let qty = 1;

      const mainImg = document.querySelector(".js-sp-main-img");
      const thumbBtns = document.querySelectorAll(".js-sp-thumb-btn");
      const viewToggles = document.querySelectorAll(".js-sp-view-toggle");
      const priceDisplay = document.querySelector(".js-sp-price-display");
      const customPrintToggle = document.querySelector("#js-custom-print-toggle");
      const customPrintInputs = document.querySelector("#js-custom-print-inputs");
      const customNameInput = document.querySelector("#js-sp-custom-name");
      const customNumInput = document.querySelector("#js-sp-custom-num");
      const addBtn = document.querySelector("#js-sp-add-to-cart-btn");
      const waBtn = document.querySelector("#js-sp-whatsapp-order-btn");
      const sizeBtns = document.querySelectorAll(".js-sp-swatches .rabez-size-swatch");
      const sizeLabel = document.querySelector("#js-selected-size-label");
      const qtyInput = document.querySelector(".js-sp-qty");
      const qtyMinus = document.querySelector(".js-sp-qty-minus");
      const qtyPlus = document.querySelector(".js-sp-qty-plus");

      function updatePriceDisplay() {
        const itemPrice = basePrice + ((allowCustomPrint && hasCustomPrint) ? customPrintFee : 0);
        const totalPrice = itemPrice * qty;
        if (priceDisplay) {
          priceDisplay.textContent = `৳ ${totalPrice.toLocaleString("en-IN")}`;
        }
        const btnPrice = document.querySelector("#js-sp-btn-price");
        if (btnPrice) {
          btnPrice.textContent = `৳ ${totalPrice.toLocaleString("en-IN")}`;
        }
      }

      // 1. Gallery Thumbnail clicks
      thumbBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
          thumbBtns.forEach((b) => b.classList.remove("is-active"));
          btn.classList.add("is-active");
          const src = btn.dataset.src;
          if (mainImg && src) {
            mainImg.style.opacity = "0.4";
            mainImg.src = src;
            setTimeout(() => { mainImg.style.opacity = "1"; }, 100);
          }
          viewToggles.forEach((vt) => {
            vt.classList.toggle("is-active", vt.dataset.src === src);
          });
        });
      });

      // 2. Description Tabs Switcher (Desktop)
      document.querySelectorAll(".js-desc-tab").forEach((tab) => {
        tab.addEventListener("click", () => {
          document.querySelectorAll(".js-desc-tab").forEach((t) => {
            t.classList.remove("is-active");
            t.style.borderBottomColor = "transparent";
            t.style.color = "#9CA3AF";
            t.style.fontWeight = "700";
          });
          tab.classList.add("is-active");
          tab.style.borderBottomColor = "#04F404";
          tab.style.color = "#FFFFFF";
          tab.style.fontWeight = "800";

          const targetPanel = tab.dataset.tab;
          document.querySelectorAll(".js-desc-panel").forEach((panel) => {
            panel.style.display = panel.dataset.panel === targetPanel ? "block" : "none";
          });
        });
      });

      // 2b. Description Accordion Toggles (Mobile)
      document.querySelectorAll(".js-accordion-btn").forEach((accBtn) => {
        accBtn.addEventListener("click", () => {
          const targetKey = accBtn.dataset.target;
          const panel = document.querySelector(`.js-desc-panel[data-panel="${targetKey}"]`);
          const arrow = accBtn.querySelector(".rabez-accordion-arrow");
          const isCurrentlyActive = accBtn.classList.contains("is-active");

          if (isCurrentlyActive) {
            accBtn.classList.remove("is-active");
            if (panel) panel.classList.remove("is-open-mobile");
          } else {
            accBtn.classList.add("is-active");
            if (panel) panel.classList.add("is-open-mobile");
          }
        });
      });

      // 3. Size selector
      sizeBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
          sizeBtns.forEach((b) => {
            b.classList.remove("is-selected");
            b.setAttribute("aria-pressed", "false");
          });
          btn.classList.add("is-selected");
          btn.setAttribute("aria-pressed", "true");
          currentSize = btn.dataset.size;
          if (sizeLabel) sizeLabel.textContent = currentSize;
        });
      });

      // 4. Quantity Stepper
      qtyMinus?.addEventListener("click", () => {
        if (qty > 1) {
          qty--;
          qtyInput.value = qty;
          updatePriceDisplay();
        }
      });
      qtyPlus?.addEventListener("click", () => {
        if (qty < 50) {
          qty++;
          qtyInput.value = qty;
          updatePriceDisplay();
        }
      });

      // 5. Custom Print Checkbox
      if (allowCustomPrint && customPrintToggle) {
        customPrintToggle.addEventListener("change", (e) => {
          hasCustomPrint = e.target.checked;
          if (customPrintInputs) {
            customPrintInputs.style.display = hasCustomPrint ? "grid" : "none";
          }
          updatePriceDisplay();
        });
      }

      // Ensure product is globally registered in RABEZ store
      const currentProductData = {
        id: {{ $product->id }},
        sku: @json($product->sku ?: ('RBZ-' . $product->id)),
        name: @json($product->name),
        price: Number({{ $product->price }}),
        regularPrice: Number({{ $product->regular_price ?? ($product->price + 200) }}),
        image: @json(asset($product->image ?: 'assets/images/products/real-madrid-home-hs.jpg')),
        sizes: ["S", "M", "L", "XL", "XXL"],
        defaultSize: currentSize || "L",
        hasCustomPrint: allowCustomPrint
      };
      window.RABEZ_CURRENT_PRODUCT = currentProductData;
      window.RABEZ_PRODUCTS = window.RABEZ_PRODUCTS || [];
      const existingIdx = window.RABEZ_PRODUCTS.findIndex(p => Number(p.id) === Number(currentProductData.id));
      if (existingIdx >= 0) {
        window.RABEZ_PRODUCTS[existingIdx] = currentProductData;
      } else {
        window.RABEZ_PRODUCTS.push(currentProductData);
      }

      @foreach($relatedProducts as $rel)
        @php
          $rGallery = is_array($rel->gallery) ? $rel->gallery : (json_decode($rel->gallery ?? '[]', true) ?: []);
        @endphp
        if (!window.RABEZ_PRODUCTS.some(p => Number(p.id) === {{ $rel->id }})) {
          window.RABEZ_PRODUCTS.push({
            id: {{ $rel->id }},
            sku: "{{ $rel->sku }}",
            name: "{{ addslashes($rel->name) }}",
            slug: "{{ $rel->slug ?: $rel->id }}",
            club: "{{ $rel->club }}",
            clubLabel: "{{ $rel->club_label ?: ucwords(str_replace('-', ' ', $rel->club ?: 'Club')) }}",
            sleeve: "{{ $rel->sleeve }}",
            sleeveLabel: "{{ $rel->sleeve_label ?: ucwords(str_replace('-', ' ', $rel->sleeve ?: 'Jersey')) }}",
            price: {{ (float) $rel->price }},
            regularPrice: {{ (float) ($rel->price + 200) }},
            image: "{{ $rel->image ? asset($rel->image) : asset('assets/images/products/real-madrid-home-hs.jpg') }}",
            gallery: @json(array_map('asset', $rGallery)),
            defaultSize: "L",
            sizes: ["S", "M", "L", "XL", "XXL"],
            hasCustomPrint: {{ $rel->has_custom_print ? 'true' : 'false' }}
          });
        }
      @endforeach

      // 6. Add to Bag
      addBtn?.addEventListener("click", () => {
        const prodId = {{ $product->id }};
        const customName = (allowCustomPrint && hasCustomPrint) ? (customNameInput?.value.trim().toUpperCase() || "") : "";
        const customNum = (allowCustomPrint && hasCustomPrint) ? (customNumInput?.value.trim() || "") : "";

        if (window.RABEZ_STORE && typeof window.RABEZ_STORE.addToCart === "function") {
          window.RABEZ_STORE.addToCart({
            id: prodId,
            sku: currentProductData.sku,
            name: currentProductData.name,
            price: currentProductData.price,
            image: currentProductData.image,
            size: currentSize,
            qty: qty,
            customName: customName,
            customNumber: customNum
          });
          if (typeof window.RABEZ_STORE.openCartDrawer === "function") {
            window.RABEZ_STORE.openCartDrawer();
          }
        } else {
          alert("Jersey added to bag!");
        }
      });

      // Size Chart Modal Open / Close & Interactive Features
      function syncSizeChartSelection(size) {
        document.querySelectorAll(".rabez-size-modal-table tr[data-row-size]").forEach((tr) => {
          tr.classList.toggle("is-active-size", tr.dataset.rowSize === size);
        });
        document.querySelectorAll(".js-quick-select").forEach((btn) => {
          btn.classList.toggle("is-selected", btn.dataset.size === size);
        });
      }

      function openSizeChartModal() {
        const modal = document.querySelector(".js-size-chart-modal");
        if (modal) {
          modal.classList.add("is-open");
          document.body.style.overflow = "hidden";
          syncSizeChartSelection(currentSize);
        }
      }

      function closeSizeChartModal() {
        const modal = document.querySelector(".js-size-chart-modal");
        if (modal) {
          modal.classList.remove("is-open");
          document.body.style.overflow = "";
        }
      }

      document.querySelectorAll(".js-open-size-chart-modal, a[href='#size-guide']").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          openSizeChartModal();
        });
      });

      document.querySelectorAll(".js-close-size-chart-modal").forEach((btn) => {
        btn.addEventListener("click", closeSizeChartModal);
      });

      document.querySelector(".js-size-chart-modal")?.addEventListener("click", (e) => {
        if (e.target.classList.contains("js-size-chart-modal")) closeSizeChartModal();
      });

      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeSizeChartModal();
      });

      // Size Chart Fit Tabs
      document.querySelectorAll(".js-fit-tab").forEach((tab) => {
        tab.addEventListener("click", () => {
          document.querySelectorAll(".js-fit-tab").forEach((t) => t.classList.remove("is-active"));
          tab.classList.add("is-active");
          const fit = tab.dataset.fit;
          document.querySelectorAll(".js-fit-panel").forEach((p) => {
            p.style.display = p.dataset.fitPanel === fit ? "block" : "none";
          });
        });
      });

      // Mobile Graphic Chart Toggle
      document.querySelectorAll(".js-toggle-graphic").forEach((btn) => {
        btn.addEventListener("click", () => {
          const panel = btn.closest(".js-fit-panel");
          const container = panel?.querySelector(".js-graphic-container");
          if (container) {
            const isVisible = container.classList.toggle("is-visible");
            btn.classList.toggle("is-active", isVisible);
            const label = btn.querySelector("span span") || btn.querySelector("span:not(.chevron)");
            if (label) {
              label.textContent = isVisible ? "Hide Official Graphic Chart" : "View Official Graphic Chart";
            }
          }
        });
      });

      // Quick Size Selection inside Modal
      document.querySelectorAll(".js-quick-select").forEach((btn) => {
        btn.addEventListener("click", () => {
          const size = btn.dataset.size;
          const targetBtn = document.querySelector(`.js-sp-swatches .rabez-size-swatch[data-size="${size}"]`);
          if (targetBtn) {
            targetBtn.click();
            syncSizeChartSelection(size);
            setTimeout(closeSizeChartModal, 240);
          }
        });
      });

      // Table Row Click to Select
      document.querySelectorAll(".rabez-size-modal-table tr[data-row-size]").forEach((row) => {
        row.style.cursor = "pointer";
        row.addEventListener("click", () => {
          const size = row.dataset.rowSize;
          const targetBtn = document.querySelector(`.js-sp-swatches .rabez-size-swatch[data-size="${size}"]`);
          if (targetBtn) {
            targetBtn.click();
            syncSizeChartSelection(size);
          }
        });
      });

      // 7. WhatsApp Order Direct
      waBtn?.addEventListener("click", () => {
        const customName = (allowCustomPrint && hasCustomPrint) ? (customNameInput?.value.trim().toUpperCase() || "") : "";
        const customNum = (allowCustomPrint && hasCustomPrint) ? (customNumInput?.value.trim() || "") : "";
        let msg = `Hello RABEZ! I want to order this jersey:\n\n`;
        msg += `Product: {{ $product->name }}\n`;
        msg += `SKU: {{ $product->sku }}\n`;
        msg += `Size: ${currentSize}\n`;
        msg += `Quantity: ${qty}\n`;
        if (allowCustomPrint && hasCustomPrint && (customName || customNum)) {
          msg += `Custom Print: ${customName} #${customNum} (+৳${customPrintFee})\n`;
        }
        const unitPrice = basePrice + ((allowCustomPrint && hasCustomPrint) ? customPrintFee : 0);
        msg += `Total Amount: ৳ ${(unitPrice * qty).toLocaleString("en-IN")}\n`;
        msg += `\nPlease confirm my order!`;

        const waUrl = `https://api.whatsapp.com/send/?phone=8801612359652&text=${encodeURIComponent(msg)}`;
        window.open(waUrl, "_blank");
      });
    });
  </script>
  @endpush
@endsection
