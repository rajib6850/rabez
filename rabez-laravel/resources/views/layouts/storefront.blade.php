<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'RABEZ - Football Jersey Store Bangladesh | Half Sleeve, Full Sleeve & Winter Hoodies')</title>
  <meta name="description" content="@yield('meta_description', 'Buy Player Version, Fan Version, Full Sleeve jerseys and Winter Hoodies in Bangladesh at RABEZ (rabez.shop) with Cash on Delivery.')">
  
  <!-- PWA Manifest & Icons -->
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#0A0C0B">
  <link rel="icon" type="image/jpeg" href="{{ asset('assets/images/brand/rabez-emblem.jpg') }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/images/brand/rabez-emblem.jpg') }}">

  <!-- Google Fonts: Barlow Condensed + Red Hat Text -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Red+Hat+Text:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- RABEZ Modular CSS Architecture -->
  <link rel="stylesheet" href="{{ asset('assets/css/01-variables.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/02-base-reset.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/03-components.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/header-navigation.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/hero-banner.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/club-filter-bar.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/product-grid.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/bento-collections.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/jersey-customizer.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/size-guide.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/single-product.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/cart-checkout.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/footer.css') }}">

  @stack('styles')
</head>
<body class="rabez-storefront">

  <!-- Topbar -->
  <div class="rabez-topbar">
    <div class="rabez-container rabez-topbar__inner">
      <div class="rabez-topbar__perks">
        <span class="rabez-topbar__perk"><span class="rabez-topbar__dot"></span> Fast Delivery Across Bangladesh &bull; Easy 3-Day Size Swap</span>
      </div>
      <div class="rabez-topbar__links">
        <a href="https://www.facebook.com/rabezshop/" target="_blank" rel="noopener">Facebook Page</a>
        <span class="rabez-topbar__sep">&bull;</span>
        <a href="https://api.whatsapp.com/send/?phone=8801612359652" target="_blank" rel="noopener">WhatsApp</a>
        <span class="rabez-topbar__sep">&bull;</span>
        @auth
          <div class="rabez-topbar-dropdown js-topbar-dropdown">
            <button type="button" class="rabez-topbar-user js-topbar-dropdown-toggle" aria-haspopup="true" aria-expanded="false" title="Account Menu">
              <span class="rabez-user-dot"></span>
              <span>Hi, {{ Auth::user()->name }}</span>
              @if(Auth::user()->isAdmin())
                <span class="rabez-role-badge">ADMIN</span>
              @endif
              <svg class="rabez-dropdown-arrow" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>
            <div class="rabez-topbar-dropdown__menu" role="menu">
              <div class="rabez-topbar-dropdown__header">
                <span class="rabez-topbar-dropdown__name">{{ Auth::user()->name }}</span>
                <span class="rabez-topbar-dropdown__email">{{ Auth::user()->email }}</span>
              </div>
              <div class="rabez-topbar-dropdown__divider"></div>
              @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="rabez-topbar-dropdown__item rabez-topbar-dropdown__item--admin" role="menuitem">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                  </svg>
                  <span>Admin Panel</span>
                </a>
              @endif
              <a href="{{ route('track-order') }}" class="rabez-topbar-dropdown__item" role="menuitem">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                <span>Track Order</span>
              </a>
              <div class="rabez-topbar-dropdown__divider"></div>
              <a href="{{ route('logout') }}" class="rabez-topbar-dropdown__item rabez-topbar-dropdown__item--danger" role="menuitem">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                  <polyline points="16 17 21 12 16 7"></polyline>
                  <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Sign Out</span>
              </a>
            </div>
          </div>
        @else
          <a href="{{ route('track-order') }}" style="color:#04F404; font-weight:700;">Track Order</a>
          <span class="rabez-topbar__sep">&bull;</span>
          <a href="{{ route('login') }}" style="color:#fff; font-weight:600;">Sign In</a>
          <span class="rabez-topbar__sep">&bull;</span>
          <a href="{{ route('register') }}" style="color:#04F404; font-weight:600;">Register</a>
        @endauth
      </div>
    </div>
  </div>

  @if(session('success') || session('error') || session('warning'))
    <div style="background:#111513; border-bottom:1px solid {{ session('error') ? '#EF4444' : '#22C55E' }}; padding:11px 16px; font-size:13.5px; text-align:center; display:flex; align-items:center; justify-content:center; gap:12px; color:#fff; position:relative; z-index:999;">
      <span>{{ session('error') ? '⚠️ ' . session('error') : '✅ ' . (session('success') ?: session('warning')) }}</span>
      <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; color:#9CA3AF; cursor:pointer; font-size:18px; line-height:1; padding:0 6px;" title="Dismiss">&times;</button>
    </div>
  @endif

  <!-- Main Sticky Header -->
  <header class="rabez-header" role="banner">
    <div class="rabez-container rabez-header__inner">
      <a href="{{ route('home') }}" class="rabez-logo" aria-label="RABEZ Official Jersey Store Home">
        <img src="{{ asset('assets/images/brand/rabez-neon-logo.png') }}" alt="RABEZ - Wear What You Stand For" width="210" height="70">
      </a>
      <nav class="rabez-nav js-main-nav" aria-label="Main Navigation">
        <a href="{{ route('home') }}" class="rabez-nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
        <a href="{{ route('shop') }}" class="rabez-nav__link {{ request()->routeIs('shop') ? 'is-active' : '' }}">All Jerseys</a>
        <a href="{{ route('shop', ['sleeve' => 'winter-hoodie']) }}" class="rabez-nav__link">Winter Hoodies <span class="rabez-nav__tag">NEW</span></a>
        <a href="{{ route('shop', ['sleeve' => 'full-sleeve']) }}" class="rabez-nav__link">Full Sleeve</a>
        <a href="{{ route('shop', ['sleeve' => 'retro']) }}" class="rabez-nav__link">Retro Kits</a>
        <a href="{{ route('home') }}#custom-print" class="rabez-nav__link">Custom Print</a>
        <a href="{{ route('home') }}#size-guide" class="rabez-nav__link">Size Chart</a>

        @auth
          <div class="rabez-nav-auth-mobile" style="margin-top:12px; padding-top:12px; border-top:1px solid #1E2421; display:flex; flex-direction:column; gap:8px;">
            <div style="font-size:12px; color:#9CA3AF; display:flex; align-items:center; gap:6px;">
              <span class="rabez-user-dot"></span>
              Signed in as <strong style="color:#fff;">{{ Auth::user()->name }}</strong>
              @if(Auth::user()->isAdmin())
                <span class="rabez-role-badge">ADMIN</span>
              @endif
            </div>
            @if(Auth::user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" style="color:#22C55E; font-weight:700;">⚡ Admin Dashboard</a>
            @endif
            <a href="{{ route('logout') }}" style="color:#EF4444; font-size:13px; text-decoration:none;">Sign Out</a>
          </div>
        @else
          <div class="rabez-nav-auth-mobile" style="margin-top:12px; padding-top:12px; border-top:1px solid #1E2421; display:flex; gap:12px;">
            <a href="{{ route('login') }}" style="color:#fff; font-weight:600; font-size:13px; text-decoration:none;">Sign In</a>
            <span style="color:#4B5563;">•</span>
            <a href="{{ route('register') }}" style="color:#22C55E; font-weight:600; font-size:13px; text-decoration:none;">Create Account</a>
          </div>
        @endauth
      </nav>
      <div class="rabez-header__actions">
        <form action="{{ route('shop') }}" method="GET" class="rabez-header-search" role="search">
          <label for="header-search-input" class="screen-reader-text">Search football jerseys</label>
          <input type="search" name="search" id="header-search-input" class="rabez-header-search__input js-search-input" placeholder="Search club, kit, Messi..." autocomplete="off">
          <button type="submit" class="rabez-header-search__btn" aria-label="Submit search" style="background:none; border:none; cursor:pointer; padding:0;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </button>
        </form>

        <a href="{{ request()->routeIs('home') ? '#catalog' : route('home') . '#catalog' }}" class="rabez-icon-btn" aria-label="Wishlist" title="View Wishlist">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
          </svg>
          <span class="rabez-count-pill js-wishlist-count">0</span>
        </a>

        <button type="button" class="rabez-icon-btn js-open-cart-drawer" aria-label="Open Shopping Bag" title="Shopping Bag">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
          </svg>
          <span class="rabez-cart-total-label js-cart-subtotal">৳ 0</span>
          <span class="rabez-count-pill js-cart-count">0</span>
        </button>

        <button type="button" class="rabez-mobile-toggle js-mobile-nav-toggle" aria-label="Toggle Menu" aria-expanded="false">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
      </div>
    </div>
  </header>

  <main>
    @yield('content')
  </main>

  <!-- Pitch Black Footer -->
  <footer class="rabez-footer">
    <div class="rabez-container">
      <div class="rabez-footer__grid">
        <div class="rabez-footer__brand">
          <img src="{{ asset('assets/images/brand/rabez-neon-logo.png') }}" alt="RABEZ Official Store" width="190" height="63">
          <p class="rabez-footer__tagline">Wear What You Stand For. Authentic player-edition details, premium double-knit jacquard football jerseys, and heavyweight winter hoodies with Cash on Delivery across Bangladesh.</p>
        </div>
        <div class="rabez-footer__nav-col">
          <h4 class="rabez-footer__heading">Shop by Sleeve</h4>
          <ul class="rabez-footer__links">
            <li><a href="{{ route('shop', ['sleeve' => 'half-sleeve']) }}">Half Sleeve Jerseys (৳1,150)</a></li>
            <li><a href="{{ route('shop', ['sleeve' => 'full-sleeve']) }}">Full Sleeve Jerseys (৳1,300)</a></li>
            <li><a href="{{ route('shop', ['sleeve' => 'winter-hoodie']) }}">Winter Hoodies with Jersey Logo (৳1,450)</a></li>
            <li><a href="{{ route('shop', ['sleeve' => 'retro']) }}">Classic Retro Kits (৳1,300)</a></li>
          </ul>
        </div>
        <div class="rabez-footer__nav-col">
          <h4 class="rabez-footer__heading">Customer Care</h4>
          <ul class="rabez-footer__links">
            <li><a href="{{ route('track-order') }}">Track Your Order &amp; Invoice</a></li>
            <li><a href="{{ route('checkout') }}">1-Page COD Checkout</a></li>
            <li><a href="https://api.whatsapp.com/send/?phone=8801612359652" target="_blank">Direct WhatsApp Support</a></li>
            <li><a href="{{ route('admin.dashboard') }}">RABEZ Admin Panel</a></li>
          </ul>
        </div>
        <div class="rabez-footer__contact">
          <h4 class="rabez-footer__heading">Direct Hotline &amp; Store</h4>
          <ul class="rabez-footer__contact-list">
            <li class="rabez-footer__contact-item">
              <span class="rabez-footer__contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
              <div class="rabez-footer__contact-text">
                <span class="rabez-footer__contact-label">Hotline &amp; WhatsApp</span>
                <a href="https://api.whatsapp.com/send/?phone=8801612359652" class="rabez-footer__contact-val">+880 1612-359652</a>
              </div>
            </li>
            <li class="rabez-footer__contact-item">
              <span class="rabez-footer__contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span>
              <div class="rabez-footer__contact-text">
                <span class="rabez-footer__contact-label">Store Location</span>
                <span class="rabez-footer__contact-val">Road-10, College Avenue, Barishal</span>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="rabez-footer__bottom">
        <p>&copy; {{ date('Y') }} RABEZ (<a href="https://rabez.shop/" style="color:#FFFFFF;">rabez.shop</a>) &ndash; All Rights Reserved.</p>
        <div class="rabez-payment-pills">
          <span class="rabez-pay-pill">Cash On Delivery</span>
          <span class="rabez-pay-pill">bKash</span>
          <span class="rabez-pay-pill">Nagad</span>
          <span class="rabez-pay-pill">Steadfast / Pathao Courier</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Mini-Cart Offcanvas Drawer -->
  <div class="rabez-drawer-backdrop js-cart-backdrop"></div>
  <aside class="rabez-cart-drawer js-cart-drawer" aria-label="Shopping Bag">
    <div class="rabez-cart-drawer__head">
      <div class="rabez-cart-drawer__title">
        <span>Your Jersey Bag</span>
        <span class="rabez-count-pill js-cart-count">0</span>
      </div>
      <button type="button" class="rabez-cart-drawer__close js-close-cart-drawer" aria-label="Close bag">&#10005;</button>
    </div>
    <div class="rabez-cart-drawer__body js-cart-drawer-items"></div>
    <div class="rabez-cart-drawer__foot">
      <!-- Drawer Coupon Box -->
      <div class="rabez-drawer-coupon" style="margin-bottom:12px; padding:10px 12px; background:#141916; border:1px dashed #2B3830; border-radius:8px;">
        <div style="font-size:11.5px; font-weight:700; color:#CBD5E1; margin-bottom:6px; display:flex; align-items:center; gap:5px;">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="2.2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
          <span>Have a Promo Coupon?</span>
        </div>
        <div style="display:flex; gap:6px;">
          <input type="text" id="drawer-coupon-code" placeholder="e.g. RABEZ100" style="flex:1; text-transform:uppercase; font-size:12px; font-weight:700; padding:7px 10px; background:#0A0D0B; border:1px solid #2B3830; color:#fff; border-radius:5px; letter-spacing:0.04em;">
          <button type="button" id="js-drawer-apply-coupon" style="background:#04F404; color:#0A0C0B; font-weight:800; font-size:12px; padding:7px 14px; border:none; border-radius:5px; cursor:pointer; white-space:nowrap; transition:background 0.15s;" onmouseover="this.style.background='#22C55E'" onmouseout="this.style.background='#04F404'">Apply</button>
        </div>
        <div id="drawer-coupon-msg" style="font-size:11.5px; margin-top:5px; display:none; line-height:1.3;"></div>
      </div>

      <div class="rabez-cart-subtotal" style="display:flex; justify-content:space-between; margin-bottom:5px; font-size:13.5px; color:#9CA3AF;">
        <span>Subtotal:</span>
        <strong class="js-cart-subtotal" style="color:#FFFFFF;">৳ 0</strong>
      </div>
      <div class="js-drawer-discount-row" style="display:none; justify-content:space-between; align-items:center; margin-bottom:6px; font-size:13px; color:#22C55E; font-weight:700;">
        <span>Coupon (<span class="js-drawer-applied-code"></span>) <button type="button" class="js-drawer-remove-coupon" style="background:none; border:none; color:#EF4444; font-size:11px; cursor:pointer; text-decoration:underline; margin-left:4px;">Remove</button>:</span>
        <span>- <strong class="js-drawer-discount-val">৳ 0</strong></span>
      </div>
      <div class="js-drawer-total-row" style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:15px; font-weight:800; color:#FFFFFF; border-top:1px solid #1F2823; padding-top:8px;">
        <span>Estimated Total:</span>
        <strong class="js-drawer-total" style="color:#04F404;">৳ 0</strong>
      </div>

      <div class="rabez-cart-actions">
        <a href="{{ route('checkout') }}" class="rabez-btn rabez-btn--primary rabez-btn--full" style="background:#04F404; color:#0A0C0B; font-weight:800; font-size:14.5px; padding:11px 18px; border-radius:6px; text-align:center; display:flex; align-items:center; justify-content:center; gap:6px;">
          <span>Proceed to Checkout</span>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </a>
        <button type="button" class="rabez-btn rabez-btn--full js-whatsapp-checkout" style="background:#131A16; border:1px solid #2B3830; color:#FFFFFF; font-weight:700; font-size:13.5px; padding:10px 18px; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:border-color 0.15s, background 0.15s;" onmouseover="this.style.background='#1A241F'; this.style.borderColor='#04F404';" onmouseout="this.style.background='#131A16'; this.style.borderColor='#2B3830';">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="2.2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          <span>Order Instantly on WhatsApp</span>
        </button>
      </div>
    </div>
  </aside>

  <!-- Quick View Modal -->
  <div class="rabez-modal-backdrop js-quickview-backdrop" role="dialog" aria-modal="true" aria-label="Product Quick View">
    <div class="rabez-modal">
      <button type="button" class="rabez-modal__close js-close-quickview" aria-label="Close Quick View">&#10005;</button>
      <div class="js-quickview-content"></div>
    </div>
  </div>

  <!-- Mobile Sticky Bottom Conversion Bar -->
  <nav class="rabez-mobile-bar" aria-label="Mobile Quick Actions">
    <a href="{{ route('shop') }}" class="rabez-mobile-bar__btn">Jerseys</a>
    <a href="https://api.whatsapp.com/send/?phone=8801612359652" target="_blank" rel="noopener" class="rabez-mobile-bar__btn">WhatsApp</a>
    <button type="button" class="rabez-mobile-bar__btn rabez-mobile-bar__btn--primary js-open-cart-drawer">
      Bag (<span class="js-cart-count">0</span>) &bull; <span class="js-cart-subtotal">৳ 0</span>
    </button>
  </nav>

  <!-- PWA Service Worker Registration -->
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
      });
    }
  </script>

  <!-- Analytics & Tracking Engine (Meta Pixel + CAPI Deduplication + Anti-Spam) -->
  <script src="{{ asset('assets/js/analytics.js') }}"></script>
  <script src="{{ asset('assets/js/products-data.js') }}"></script>
  <script src="{{ asset('assets/js/cart.js') }}"></script>
  <script src="{{ asset('assets/js/app.js') }}"></script>

  @stack('scripts')
</body>
</html>
