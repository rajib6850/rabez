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
  <link rel="stylesheet" href="{{ asset('assets/css/01-variables.css') }}?v=2.1">
  <link rel="stylesheet" href="{{ asset('assets/css/02-base-reset.css') }}?v=2.1">
  <link rel="stylesheet" href="{{ asset('assets/css/03-components.css') }}?v=2.1">
  <link rel="stylesheet" href="{{ asset('assets/css/components/header-navigation.css') }}?v=2.5">
  <link rel="stylesheet" href="{{ asset('assets/css/components/hero-banner.css') }}?v=2.4">
  <link rel="stylesheet" href="{{ asset('assets/css/components/club-filter-bar.css') }}?v=2.4">
  <link rel="stylesheet" href="{{ asset('assets/css/components/product-grid.css') }}?v=2.1">
  <link rel="stylesheet" href="{{ asset('assets/css/components/bento-collections.css') }}?v=2.1">
  <link rel="stylesheet" href="{{ asset('assets/css/components/jersey-customizer.css') }}?v=2.1">
  <link rel="stylesheet" href="{{ asset('assets/css/components/size-guide.css') }}?v=2.2">
  <link rel="stylesheet" href="{{ asset('assets/css/components/single-product.css') }}?v=2.8">
  <link rel="stylesheet" href="{{ asset('assets/css/components/cart-checkout.css') }}?v=2.8">
  <link rel="stylesheet" href="{{ asset('assets/css/components/footer.css') }}?v=2.6">

  @stack('styles')
</head>
<body class="rabez-storefront">

  <!-- Topbar -->
  <div class="rabez-topbar">
    <div class="rabez-container rabez-topbar__inner">
      <div class="rabez-topbar__perks">
        <span class="rabez-topbar__perk">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
          </svg>
          Fast Delivery Across Bangladesh &bull; Easy 3-Day Size Swap
        </span>
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
              <svg class="rabez-dropdown-arrow" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
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
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                  </svg>
                  <span>Admin Panel</span>
                </a>
              @endif
              <a href="{{ route('track-order') }}" class="rabez-topbar-dropdown__item" role="menuitem">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                <span>Track Order</span>
              </a>
              <div class="rabez-topbar-dropdown__divider"></div>
              <a href="{{ route('logout') }}" class="rabez-topbar-dropdown__item rabez-topbar-dropdown__item--danger" role="menuitem">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                  <polyline points="16 17 21 12 16 7"></polyline>
                  <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Sign Out</span>
              </a>
            </div>
          </div>
        @else
          <a href="javascript:void(0)" onclick="openAppInstallModal()" style="color:#FFFFFF; font-weight:600; display:inline-flex; align-items:center; gap:5px;" title="Install RABEZ Mobile App">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
              <line x1="12" y1="18" x2="12.01" y2="18"></line>
            </svg>
            <span>App Install</span>
          </a>
          <span class="rabez-topbar__sep">&bull;</span>
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
        @if(isset($customPrintProducts) && $customPrintProducts->isNotEmpty())
          <a href="{{ route('home') }}#custom-print" class="rabez-nav__link">Custom Print</a>
        @endif
        <a href="{{ route('home') }}#size-guide" class="rabez-nav__link">Size Chart</a>

        <!-- Mobile Drawer Utilities & Quick Actions -->
        <div class="rabez-nav-mobile-utilities">
          <div class="rabez-nav-mobile-divider"></div>

          <!-- App Install Featured Card -->
          <div class="rabez-nav-mobile-app-card" onclick="openAppInstallModal()">
            <div class="rabez-nav-mobile-app-card__left">
              <span class="rabez-nav-mobile-app-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                  <line x1="12" y1="18" x2="12.01" y2="18"></line>
                </svg>
              </span>
              <div class="rabez-nav-mobile-app-card__text">
                <span class="rabez-nav-mobile-app-title">RABEZ Mobile App</span>
                <span class="rabez-nav-mobile-app-sub">1-Tap Install &bull; APK (1.7 MB)</span>
              </div>
            </div>
            <span class="rabez-nav-mobile-app-cta">INSTALL</span>
          </div>

          <!-- Track Order Featured Card -->
          <a href="{{ route('track-order') }}" class="rabez-nav-mobile-track">
            <span class="rabez-nav-mobile-track__left">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
              </svg>
              <span>Track Your Order & Invoice</span>
            </span>
            <span class="rabez-nav-mobile-track__badge">LIVE</span>
          </a>

          <!-- Social & Direct Support Quick Row -->
          <div class="rabez-nav-mobile-quick-row">
            <a href="https://api.whatsapp.com/send/?phone=8801612359652" target="_blank" rel="noopener" class="rabez-nav-mobile-pill rabez-nav-mobile-pill--whatsapp">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
              </svg>
              <span>WhatsApp</span>
            </a>

            <a href="https://www.facebook.com/rabezshop/" target="_blank" rel="noopener" class="rabez-nav-mobile-pill rabez-nav-mobile-pill--facebook">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
              </svg>
              <span>Facebook Page</span>
            </a>
          </div>

          <!-- Customer Auth Section -->
          @auth
            <div class="rabez-nav-mobile-auth-card">
              <div class="rabez-nav-mobile-auth-user">
                <span class="rabez-user-dot"></span>
                <span class="rabez-nav-mobile-auth-name">Signed in as <strong>{{ Auth::user()->name }}</strong></span>
                @if(Auth::user()->isAdmin())
                  <span class="rabez-role-badge">ADMIN</span>
                @endif
              </div>

              <div class="rabez-nav-mobile-auth-actions">
                @if(Auth::user()->isAdmin())
                  <a href="{{ route('admin.dashboard') }}" class="rabez-nav-mobile-auth-btn rabez-nav-mobile-auth-btn--admin">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                    <span>Admin Panel</span>
                  </a>
                @endif
                <a href="{{ route('logout') }}" class="rabez-nav-mobile-auth-btn rabez-nav-mobile-auth-btn--logout">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                  </svg>
                  <span>Sign Out</span>
                </a>
              </div>
            </div>
          @else
            <div class="rabez-nav-mobile-guest-row">
              <a href="{{ route('login') }}" class="rabez-nav-mobile-auth-btn rabez-nav-mobile-auth-btn--login">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span>Sign In</span>
              </a>

              <a href="{{ route('register') }}" class="rabez-nav-mobile-auth-btn rabez-nav-mobile-auth-btn--register">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <line x1="19" y1="8" x2="19" y2="14"></line>
                  <line x1="22" y1="11" x2="16" y2="11"></line>
                </svg>
                <span>Create Account</span>
              </a>
            </div>
          @endauth
        </div>
      </nav>
      <div class="rabez-header__actions">
        <form action="{{ route('shop') }}" method="GET" class="rabez-header-search" role="search">
          <label for="header-search-input" class="screen-reader-text">Search football jerseys</label>
          <input type="search" name="search" id="header-search-input" class="rabez-header-search__input js-search-input" placeholder="Search club, kit, Messi..." autocomplete="off">
          <button type="submit" class="rabez-header-search__btn" aria-label="Submit search" style="background:none; border:none; cursor:pointer; padding:0;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="7.5"></circle>
              <path d="m20 20-3.6-3.6"></path>
            </svg>
          </button>
        </form>

        <a href="{{ request()->routeIs('home') ? '#catalog' : route('home') . '#catalog' }}" class="rabez-icon-btn" aria-label="Wishlist" title="View Wishlist">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          <span class="rabez-count-pill js-wishlist-count">0</span>
        </a>

        <button type="button" class="rabez-icon-btn js-open-cart-drawer" aria-label="Open Shopping Bag" title="Shopping Bag">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5a1.5 1.5 0 0 1-1.5 1.5h-14A1.5 1.5 0 0 1 3.5 21l1-12.5z"/>
          </svg>
          <span class="rabez-cart-total-label js-cart-subtotal">৳ 0</span>
          <span class="rabez-count-pill js-cart-count">0</span>
        </button>

        <button type="button" class="rabez-mobile-toggle js-mobile-nav-toggle" aria-label="Toggle Menu" aria-expanded="false">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3.5" y1="8" x2="20.5" y2="8"></line>
            <line x1="3.5" y1="16" x2="15.5" y2="16"></line>
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
            <li>
              <a href="javascript:void(0)" onclick="openAppInstallModal()" style="display:inline-flex; align-items:center; gap:6px; color:#FFFFFF; font-weight:700;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                  <line x1="12" y1="18" x2="12.01" y2="18"></line>
                </svg>
                <span>Download &amp; Install App</span>
                <span class="rabez-footer-app-badge">APK / PWA</span>
              </a>
            </li>
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
              <span class="rabez-footer__contact-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
              </span>
              <div class="rabez-footer__contact-text">
                <span class="rabez-footer__contact-label">Hotline &amp; WhatsApp</span>
                <a href="https://api.whatsapp.com/send/?phone=8801612359652" class="rabez-footer__contact-val">+880 1612-359652</a>
              </div>
            </li>
            <li class="rabez-footer__contact-item">
              <span class="rabez-footer__contact-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 21s-7-6.5-7-11a7 7 0 1 1 14 0c0 4.5-7 11-7 11z"/>
                  <circle cx="12" cy="10" r="2.5"/>
                </svg>
              </span>
              <div class="rabez-footer__contact-text">
                <span class="rabez-footer__contact-label">Store Location</span>
                <span class="rabez-footer__contact-val">Road-10, College Avenue, Barishal</span>
              </div>
            </li>
            <li class="rabez-footer__contact-item" style="margin-top: 6px;">
              <div onclick="openAppInstallModal()" class="rabez-footer-app-btn">
                <span class="rabez-footer__contact-icon" style="background:rgba(4,244,4,0.12); color:#04F404; border-color:rgba(4,244,4,0.3);">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                  </svg>
                </span>
                <div class="rabez-footer__contact-text">
                  <span class="rabez-footer__contact-label" style="color:#04F404;">Official Mobile App</span>
                  <span class="rabez-footer__contact-val" style="display:flex; align-items:center; gap:4px; font-size:0.86rem;">
                    Download APK / 1-Tap &rarr;
                  </span>
                </div>
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
      <button type="button" class="rabez-cart-drawer__close js-close-cart-drawer" aria-label="Close bag">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>
    <div class="rabez-cart-drawer__body js-cart-drawer-items"></div>
    <div class="rabez-cart-drawer__foot">
      @if($hasActiveCoupons ?? false)
        <!-- Drawer Coupon Box -->
        <div class="rabez-drawer-coupon" style="margin-bottom:12px; padding:10px 12px; background:#141916; border:1px dashed #2B3830; border-radius:8px; transition:border-color 0.2s ease;">
          <button type="button" class="rabez-coupon-toggle js-toggle-drawer-coupon" style="width:100%; display:flex; align-items:center; justify-content:space-between; background:transparent; border:none; padding:0; cursor:pointer; text-align:left;">
            <div style="font-size:12px; font-weight:700; color:#E2E8F0; display:flex; align-items:center; gap:6px;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                <circle cx="7" cy="7" r="1.5" fill="#04F404"></circle>
              </svg>
              <span>Have a Discount Coupon?</span>
            </div>
            <svg class="rabez-coupon-chevron js-drawer-coupon-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition:transform 0.25s ease;">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </button>
          <div id="drawer-coupon-fields" style="display:none; margin-top:8px; padding-top:8px; border-top:1px dashed #2B3830;">
            <div style="display:flex; gap:6px;">
              <input type="text" id="drawer-coupon-code" placeholder="e.g. RABEZ100" style="flex:1; text-transform:uppercase; font-size:12px; font-weight:700; padding:7px 10px; background:#0A0D0B; border:1px solid #2B3830; color:#fff; border-radius:5px; letter-spacing:0.04em;">
              <button type="button" id="js-drawer-apply-coupon" style="background:#04F404; color:#0A0C0B; font-weight:800; font-size:12px; padding:7px 14px; border:none; border-radius:5px; cursor:pointer; white-space:nowrap; transition:background 0.15s;" onmouseover="this.style.background='#22C55E'" onmouseout="this.style.background='#04F404'">Apply</button>
            </div>
            <div id="drawer-coupon-msg" style="font-size:11.5px; margin-top:5px; display:none; line-height:1.3;"></div>
          </div>
        </div>
      @endif

      <div class="rabez-cart-subtotal" style="display:flex; justify-content:space-between; margin-bottom:5px; font-size:13.5px; color:#9CA3AF;">
        <span>Subtotal:</span>
        <strong class="js-cart-subtotal" style="color:#FFFFFF;">৳ 0</strong>
      </div>
      @if($hasActiveCoupons ?? false)
        <div class="js-drawer-discount-row" style="display:none; justify-content:space-between; align-items:center; margin-bottom:6px; font-size:13px; color:#22C55E; font-weight:700;">
          <span>Coupon (<span class="js-drawer-applied-code"></span>) <button type="button" class="js-drawer-remove-coupon" style="background:none; border:none; color:#EF4444; font-size:11px; cursor:pointer; text-decoration:underline; margin-left:4px;">Remove</button>:</span>
          <span>- <strong class="js-drawer-discount-val">৳ 0</strong></span>
        </div>
      @endif
      <div class="js-drawer-total-row" style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:15px; font-weight:800; color:#FFFFFF; border-top:1px solid #1F2823; padding-top:8px;">
        <span>Estimated Total:</span>
        <strong class="js-drawer-total" style="color:#04F404;">৳ 0</strong>
      </div>

      <div class="rabez-cart-actions">
        <a href="{{ route('checkout') }}" class="rabez-btn rabez-btn--primary rabez-btn--full" style="background:#04F404; color:#0A0C0B; font-weight:800; font-size:14.5px; padding:11px 18px; border-radius:6px; text-align:center; display:flex; align-items:center; justify-content:center; gap:8px;">
          <span>Proceed to Checkout</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </a>
        <button type="button" class="rabez-btn rabez-btn--full js-whatsapp-checkout" style="background:#131A16; border:1px solid #2B3830; color:#FFFFFF; font-weight:700; font-size:13.5px; padding:10px 18px; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:border-color 0.15s, background 0.15s;" onmouseover="this.style.background='#1A241F'; this.style.borderColor='#04F404';" onmouseout="this.style.background='#131A16'; this.style.borderColor='#2B3830';">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
          </svg>
          <span>Order Instantly on WhatsApp</span>
        </button>
      </div>
    </div>
  </aside>

  <!-- Quick View Modal -->
  <div class="rabez-modal-backdrop js-quickview-backdrop" role="dialog" aria-modal="true" aria-label="Product Quick View">
    <div class="rabez-modal">
      <button type="button" class="rabez-modal__close js-close-quickview" aria-label="Close Quick View">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
      <div class="js-quickview-content"></div>
    </div>
  </div>

  <!-- RABEZ App Install & Download Modal -->
  <div id="rabez-install-modal" class="rabez-modal-backdrop rabez-install-modal-backdrop" onclick="if(event.target === this) closeAppInstallModal()">
    <div class="rabez-install-modal" role="dialog" aria-modal="true" aria-labelledby="rabez-install-modal-title">
      <button type="button" class="rabez-install-modal__close" onclick="closeAppInstallModal()" aria-label="Close dialog">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>

      <div class="rabez-install-modal__header">
        <div class="rabez-install-modal__app-badge">
          <img src="{{ asset('icon-192.png') }}" alt="RABEZ App Icon" width="68" height="68">
          <span class="rabez-install-modal__verified-check">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#050606" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </span>
        </div>
        <h3 id="rabez-install-modal-title" class="rabez-install-modal__title">RABEZ Official Mobile App</h3>
        <p class="rabez-install-modal__subtitle">Faster checkout, real-time courier tracking &amp; instant kit drops.</p>
      </div>

      <div class="rabez-install-modal__methods">
        <!-- Method 1: 1-Tap PWA Install -->
        <div class="rabez-install-card rabez-install-card--pwa">
          <div class="rabez-install-card__badge-row">
            <span class="rabez-install-tag rabez-install-tag--green">RECOMMENDED</span>
            <span class="rabez-install-tag">INSTANT &bull; 0 MB STORAGE</span>
          </div>
          <h4 class="rabez-install-card__title">1-Tap Direct Install</h4>
          <p class="rabez-install-card__desc">Adds RABEZ directly to your home screen like a native app. No heavy file downloads, zero storage hassle, always updated.</p>

          <button type="button" class="rabez-install-cta-btn rabez-install-cta-btn--pwa" onclick="installPWAApp()">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
            </svg>
            <span id="rabez-pwa-btn-text">Install App on Phone</span>
          </button>

          <!-- iOS Safari Instructions -->
          <div id="rabez-ios-guide" class="rabez-ios-guide" style="display:none;">
            <div class="rabez-ios-guide__title">iPhone / iPad Safari Guide:</div>
            <ol class="rabez-ios-guide__steps">
              <li>Tap the <strong>Share</strong> button <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg> at bottom of Safari.</li>
              <li>Scroll down and tap <strong>Add to Home Screen</strong> <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>.</li>
              <li>Tap <strong>Add</strong> at top right. Done!</li>
            </ol>
          </div>
        </div>

        <div class="rabez-install-divider">
          <span>OR DIRECT FILE</span>
        </div>

        <!-- Method 2: Android APK Download -->
        <div class="rabez-install-card rabez-install-card--apk">
          <div class="rabez-install-card__badge-row">
            <span class="rabez-install-tag">ANDROID PACKAGE</span>
            <span class="rabez-install-tag">APK &bull; 1.7 MB</span>
          </div>
          <h4 class="rabez-install-card__title">Download Android APK (.apk)</h4>
          <p class="rabez-install-card__desc">Download the standalone installation package to your phone storage. Compatible with Android 8.0+.</p>

          <a href="{{ asset('downloads/RABEZ-Official.apk') }}" download="RABEZ-Official.apk" class="rabez-install-cta-btn rabez-install-cta-btn--apk" id="rabez-apk-dl-btn">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Download Official APK (1.7 MB)</span>
          </a>

          <div class="rabez-apk-steps">
            <div class="rabez-apk-step">
              <span class="rabez-apk-step__num">1</span>
              <span>Download the .apk file</span>
            </div>
            <div class="rabez-apk-step">
              <span class="rabez-apk-step__num">2</span>
              <span>Tap to open &amp; click "Install"</span>
            </div>
            <div class="rabez-apk-step">
              <span class="rabez-apk-step__num">3</span>
              <span>Allow unknown apps if prompted</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Sticky Bottom Conversion Bar -->
  <nav class="rabez-mobile-bar" aria-label="Mobile Quick Actions">
    <a href="{{ route('shop') }}" class="rabez-mobile-bar__btn" style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
      </svg>
      <span>Jerseys</span>
    </a>
    <a href="https://api.whatsapp.com/send/?phone=8801612359652" target="_blank" rel="noopener" class="rabez-mobile-bar__btn" style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
      </svg>
      <span>WhatsApp</span>
    </a>
    <button type="button" class="rabez-mobile-bar__btn rabez-mobile-bar__btn--primary js-open-cart-drawer" style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <path d="M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5a1.5 1.5 0 0 1-1.5 1.5h-14A1.5 1.5 0 0 1 3.5 21l1-12.5z"></path>
      </svg>
      <span>Bag (<strong class="js-cart-count">0</strong>) &bull; <strong class="js-cart-subtotal">৳ 0</strong></span>
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
  <script>
    window.RABEZ_HAS_COUPONS = @json($hasActiveCoupons ?? false);
    window.RABEZ_CUSTOM_PRINT_FEE = {{ intval($customPrintFee ?? 250) }};
    @if(isset($storefrontProductsJs) && count($storefrontProductsJs) > 0)
      window.RABEZ_DB_PRODUCTS = @json($storefrontProductsJs);
      window.RABEZ_PRODUCTS = window.RABEZ_DB_PRODUCTS;
    @endif
  </script>
  <script src="{{ asset('assets/js/cart.js') }}?v=2.8"></script>
  <script src="{{ asset('assets/js/app.js') }}?v=2.7"></script>

  @stack('scripts')
</body>
</html>
