@extends('layouts.storefront')

@section('title', 'RABEZ - Football Jersey Store Bangladesh | Half Sleeve, Full Sleeve & Winter Hoodies')

@section('content')
    @php
      $heroProductId = function (string $sku, int $fallbackId) use ($storefrontProducts) {
          $found = isset($storefrontProducts) ? $storefrontProducts->firstWhere('sku', $sku) : null;
          return $found?->id ?? (isset($storefrontProducts) ? ($storefrontProducts->first()?->id ?? $fallbackId) : $fallbackId);
      };
    @endphp
    <section class="rabez-hero">
      <div class="rabez-container rabez-hero__grid">
        <div class="rabez-hero__content">
          <h1 class="rabez-hero__title">
            Wear What You
            <span>Stand For.</span>
          </h1>
          <p class="rabez-hero__desc">
            Premium Player Version, Fan Version &amp; Full Sleeve football jerseys. Pick your club, choose your size, and order with Cash on Delivery anywhere in Bangladesh.
          </p>

          <div class="rabez-hero__cta-row">
            <a href="#catalog" class="rabez-btn rabez-btn--primary">
              <span>Shop Jerseys</span>
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </a>
            @if(isset($customPrintProducts) && $customPrintProducts->isNotEmpty())
              <a href="#custom-print" class="rabez-btn rabez-btn--hero-secondary">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
                </svg>
                <span>Custom Name &amp; Number</span>
              </a>
            @else
              <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--hero-secondary">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                  <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                  <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                  <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                </svg>
                <span>Explore Full Catalog</span>
              </a>
            @endif
          </div>
        </div>

        <!-- Right Interactive 3-Jersey Fan Stage -->
        <div class="rabez-hero-stage js-hero-stage" aria-label="Interactive Matchday Kit Showcase">
          <div class="rabez-hero-stage__glow"></div>

          <div class="rabez-hero-deck">
            <!-- Card 0: Real Madrid (Center Active) -->
            <article class="rabez-hero-deck__card is-active js-hero-deck-card" data-index="0" data-product-id="{{ $heroProductId('RM-HOME-26', 19) }}">
              <div class="rabez-hero-deck__top">
                <span class="rabez-hero-deck__badge">Best Seller</span>
                <span class="rabez-hero-deck__crest">
                  <img src="assets/images/clubs/real-madrid.svg" alt="Real Madrid" width="24" height="24">
                </span>
              </div>
              <img src="assets/images/products/real-madrid-home-hs.jpg" alt="Real Madrid 26/27 Home Half Sleeve Jersey" class="rabez-hero-deck__img" fetchpriority="high" width="500" height="667">
              <div class="rabez-hero-deck__caption">
                <div class="rabez-hero-deck__meta">
                  <div class="rabez-hero-deck__title">Real Madrid 26/27 Home</div>
                  <div class="rabez-hero-deck__price">৳ 1,150 &bull; Half Sleeve</div>
                </div>
                <span class="rabez-hero-deck__action" aria-hidden="true">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                  </svg>
                </span>
              </div>
            </article>

            <!-- Card 1: Messi Celebration (Right Fan) -->
            <article class="rabez-hero-deck__card is-next js-hero-deck-card" data-index="1" data-product-id="{{ $heroProductId('ARG-MESSI-FS', 3) }}">
              <div class="rabez-hero-deck__top">
                <span class="rabez-hero-deck__badge">Full Sleeve</span>
                <span class="rabez-hero-deck__crest">
                  <img src="assets/images/clubs/argentina.svg" alt="Argentina AFA" width="24" height="24">
                </span>
              </div>
              <img src="assets/images/products/messi-celebration-fs.jpg" alt="Messi #10 Celebration Full Sleeve Jersey" class="rabez-hero-deck__img" width="500" height="667">
              <div class="rabez-hero-deck__caption">
                <div class="rabez-hero-deck__meta">
                  <div class="rabez-hero-deck__title">Messi #10 Collector Kit</div>
                  <div class="rabez-hero-deck__price">৳ 1,300 &bull; Full Sleeve</div>
                </div>
                <span class="rabez-hero-deck__action" aria-hidden="true">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                  </svg>
                </span>
              </div>
            </article>

            <!-- Card 2: Man United (Hidden/Back) -->
            <article class="rabez-hero-deck__card js-hero-deck-card" data-index="2" data-product-id="{{ $heroProductId('MUN-HOME-HS', 5) }}">
              <div class="rabez-hero-deck__top">
                <span class="rabez-hero-deck__badge">Ready Stock</span>
                <span class="rabez-hero-deck__crest">
                  <img src="assets/images/clubs/man-utd.svg" alt="Manchester United" width="24" height="24">
                </span>
              </div>
              <img src="assets/images/products/man-utd-home-hs.jpg" alt="Manchester United Home Half Sleeve Jersey" class="rabez-hero-deck__img" width="500" height="667">
              <div class="rabez-hero-deck__caption">
                <div class="rabez-hero-deck__meta">
                  <div class="rabez-hero-deck__title">Man United Home Kit</div>
                  <div class="rabez-hero-deck__price">৳ 1,150 &bull; Half Sleeve</div>
                </div>
                <span class="rabez-hero-deck__action" aria-hidden="true">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                  </svg>
                </span>
              </div>
            </article>

            <!-- Card 3: FC Barcelona (Hidden/Back) -->
            <article class="rabez-hero-deck__card js-hero-deck-card" data-index="3" data-product-id="{{ $heroProductId('FCB-HOME-HS', 2) }}">
              <div class="rabez-hero-deck__top">
                <span class="rabez-hero-deck__badge">Hot Drop</span>
                <span class="rabez-hero-deck__crest">
                  <img src="assets/images/clubs/barcelona.svg" alt="FC Barcelona" width="24" height="24">
                </span>
              </div>
              <img src="assets/images/products/barcelona-home-hs.jpg" alt="FC Barcelona Home Half Sleeve Jersey" class="rabez-hero-deck__img" width="500" height="667">
              <div class="rabez-hero-deck__caption">
                <div class="rabez-hero-deck__meta">
                  <div class="rabez-hero-deck__title">FC Barcelona Home Kit</div>
                  <div class="rabez-hero-deck__price">৳ 1,150 &bull; Half Sleeve</div>
                </div>
                <span class="rabez-hero-deck__action" aria-hidden="true">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                  </svg>
                </span>
              </div>
            </article>

            <!-- Card 4: Winter Hoodie with Jersey Logo (Left Fan) -->
            <article class="rabez-hero-deck__card is-prev js-hero-deck-card" data-index="4" data-product-id="{{ $heroProductId('HD-MESSI-BARCA-ARG', 16) }}">
              <div class="rabez-hero-deck__top">
                <span class="rabez-hero-deck__badge">Winter Drop</span>
                <span class="rabez-hero-deck__crest">
                  <img src="assets/images/clubs/argentina.svg" alt="Barca x Argentina Hoodie" width="24" height="24">
                </span>
              </div>
              <img src="assets/images/products/messi-barca-arg-hoodie.jpg" alt="Messi #10 Barca x Argentina Winter Hoodie" class="rabez-hero-deck__img" width="500" height="667">
              <div class="rabez-hero-deck__caption">
                <div class="rabez-hero-deck__meta">
                  <div class="rabez-hero-deck__title">Messi #10 Winter Hoodie</div>
                  <div class="rabez-hero-deck__price">৳ 1,450 &bull; Jersey Crest</div>
                </div>
                <span class="rabez-hero-deck__action" aria-hidden="true">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                  </svg>
                </span>
              </div>
            </article>
          </div>

          <!-- Club Crest Switcher Dock -->
          <div class="rabez-hero-dock" role="tablist" aria-label="Switch Spotlight Jersey">
            <button type="button" class="rabez-hero-dock__btn is-active js-hero-dock-btn" data-index="0">
              <img src="assets/images/clubs/real-madrid.svg" alt="Real Madrid" width="20" height="20">
              <span>Real Madrid</span>
            </button>
            <button type="button" class="rabez-hero-dock__btn js-hero-dock-btn" data-index="1">
              <img src="assets/images/clubs/argentina.svg" alt="Argentina" width="20" height="20">
              <span>Messi #10</span>
            </button>
            <button type="button" class="rabez-hero-dock__btn js-hero-dock-btn" data-index="4">
              <img src="assets/images/clubs/barcelona.svg" alt="Winter Hoodie" width="20" height="20">
              <span>Winter Hoodie</span>
            </button>
            <button type="button" class="rabez-hero-dock__btn js-hero-dock-btn" data-index="3">
              <img src="assets/images/clubs/barcelona.svg" alt="Barcelona" width="20" height="20">
              <span>Barcelona</span>
            </button>
            <button type="button" class="rabez-hero-dock__btn js-hero-dock-btn" data-index="2">
              <img src="assets/images/clubs/man-utd.svg" alt="Man United" width="20" height="20">
              <span>Man Utd</span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- ======================================================================
         WIDGET 04: CLUB & NATIONAL CREST FILTER BAR (elementor-widget-rabez-filter)
         Clean Crest + Club Name Cards (Swipeable on Mobile, 9-Col on Desktop)
         ====================================================================== -->
    <div id="catalog" class="rabez-filter-bar">
      <div class="rabez-container rabez-filter-bar__inner">
        <div class="rabez-club-pills" role="tablist" aria-label="Filter by Football Club or National Team">
          <button type="button" class="rabez-club-pill is-active js-club-filter" data-club="all">
            <span class="rabez-club-pill__logo">
              <img src="assets/images/clubs/all-kits.svg" alt="All Football Jerseys" width="36" height="36">
            </span>
            <span class="rabez-club-pill__name">All Kits</span>
          </button>

          <button type="button" class="rabez-club-pill js-club-filter" data-club="real-madrid">
            <span class="rabez-club-pill__logo">
              <img src="assets/images/clubs/real-madrid.svg" alt="Real Madrid Jersey" width="36" height="36">
            </span>
            <span class="rabez-club-pill__name">Real Madrid</span>
          </button>

          <button type="button" class="rabez-club-pill js-club-filter" data-club="barcelona">
            <span class="rabez-club-pill__logo">
              <img src="assets/images/clubs/barcelona.svg" alt="FC Barcelona Jersey" width="36" height="36">
            </span>
            <span class="rabez-club-pill__name">Barcelona</span>
          </button>

          <button type="button" class="rabez-club-pill js-club-filter" data-club="argentina">
            <span class="rabez-club-pill__logo">
              <img src="assets/images/clubs/argentina.svg" alt="Argentina Messi Jersey" width="36" height="36">
            </span>
            <span class="rabez-club-pill__name">Argentina</span>
          </button>

          <button type="button" class="rabez-club-pill js-club-filter" data-club="arsenal">
            <span class="rabez-club-pill__logo">
              <img src="assets/images/clubs/arsenal.svg" alt="Arsenal Jersey" width="36" height="36">
            </span>
            <span class="rabez-club-pill__name">Arsenal</span>
          </button>

          <button type="button" class="rabez-club-pill js-club-filter" data-club="man-utd">
            <span class="rabez-club-pill__logo">
              <img src="assets/images/clubs/man-utd.svg" alt="Manchester United Jersey" width="36" height="36">
            </span>
            <span class="rabez-club-pill__name">Man United</span>
          </button>

          <button type="button" class="rabez-club-pill js-club-filter" data-club="bayern">
            <span class="rabez-club-pill__logo">
              <img src="assets/images/clubs/bayern.svg" alt="Bayern Munich Jersey" width="36" height="36">
            </span>
            <span class="rabez-club-pill__name">Bayern</span>
          </button>

          <button type="button" class="rabez-club-pill js-club-filter" data-club="liverpool">
            <span class="rabez-club-pill__logo">
              <img src="assets/images/clubs/liverpool.svg" alt="Liverpool Jersey" width="36" height="36">
            </span>
            <span class="rabez-club-pill__name">Liverpool</span>
          </button>

          <button type="button" class="rabez-club-pill js-club-filter" data-club="bangladesh">
            <span class="rabez-club-pill__logo">
              <img src="assets/images/clubs/bangladesh.svg" alt="Bangladesh National Football Jersey" width="36" height="36">
            </span>
            <span class="rabez-club-pill__name">Bangladesh</span>
          </button>
        </div>
      </div>
    </div>

    <!-- ======================================================================
         WIDGET 05: WOOCOMMERCE JERSEY PRODUCT GRID (elementor-widget-rabez-products)
         ====================================================================== -->
    <section class="rabez-section rabez-section--sm woocommerce">
      <div class="rabez-container">
        <div class="rabez-section-head" style="margin-bottom: 20px;">
          <h2 class="rabez-section-title">Matchday Jersey Collection</h2>
        </div>

        <!-- Sleeve Switch, Result Count, All Jerseys Link & Price Sort (Single Unified Row) -->
        <div class="rabez-filter-controls">
          <div class="rabez-sleeve-switch" role="group" aria-label="Filter by Category & Sleeve">
            <button type="button" class="rabez-sleeve-btn is-active js-sleeve-filter" data-sleeve="all">All Kits</button>
            <button type="button" class="rabez-sleeve-btn js-sleeve-filter" data-sleeve="half-sleeve">Half Sleeve (৳1,150)</button>
            <button type="button" class="rabez-sleeve-btn js-sleeve-filter" data-sleeve="full-sleeve">Full Sleeve (৳1,300)</button>
            <button type="button" class="rabez-sleeve-btn js-sleeve-filter" data-sleeve="winter-hoodie">Winter Hoodies (৳1,450)</button>
          </div>

          @php
            $totalCatalogCount = isset($storefrontProducts) && $storefrontProducts->count() > 0
              ? $storefrontProducts->count()
              : \App\Models\Product::count();
            $halfSleeveCount = isset($storefrontProducts) && $storefrontProducts->count() > 0
              ? $storefrontProducts->where('sleeve', 'half-sleeve')->count()
              : \App\Models\Product::where('sleeve', 'half-sleeve')->count();
          @endphp
          <div style="display:flex; align-items:center; flex-wrap:wrap; gap:12px;">
            <span class="js-product-result-count" style="font-size:0.84rem; font-weight:700; color:#6E7672;">Showing {{ $totalCatalogCount }} Items</span>
            <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--outline rabez-btn--sm">All {{ $totalCatalogCount }} Kits &rarr;</a>
            <label for="catalog-sort" class="screen-reader-text">Sort Jerseys</label>
            <select id="catalog-sort" class="rabez-sort-select js-sort-select">
              <option value="default">Sort: Featured</option>
              <option value="price-asc">Price: Low to High</option>
              <option value="price-desc">Price: High to Low</option>
            </select>
          </div>
        </div>

        <!-- WooCommerce Standard ul.products.columns-4 -->
        <ul class="products columns-4 js-rabez-product-grid" data-limit="8">
          <!-- Pre-rendered SEO Product Cards for Googlebot & Facebook Crawlers -->
          <li class="product type-product rabez-product-card has-back-view" data-product-id="1324">
            <div class="rabez-product-card__media">
              <a href="/product/real-madrid-26-27-home-jersey" class="woocommerce-LoopProduct-link">
                <img src="{{ asset('assets/images/products/real-madrid-home-hs.jpg') }}" class="rabez-card-img-front" alt="Real Madrid 26/27 Home Half Sleeve Jersey - Front View" width="500" height="667">
                <img src="{{ asset('assets/images/products/real-madrid-polo-white-hs.jpg') }}" class="rabez-card-img-back" alt="Real Madrid 26/27 Home Half Sleeve Jersey - Back View" width="500" height="667" loading="lazy">
                <span class="rabez-card-flip-pill">Back View</span>
              </a>
              <div class="rabez-product-card__badges">
                <span class="rabez-badge rabez-badge--neon">BEST SELLER</span>
              </div>
              <div class="rabez-product-card__actions">
                <button type="button" class="rabez-card-icon-btn js-wishlist-toggle" data-product-id="1324" aria-label="Add to Wishlist" title="Wishlist">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
                <button type="button" class="rabez-card-icon-btn js-quick-view" data-product-id="1324" aria-label="Quick View" title="Quick View">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>
            <div class="rabez-product-card__body">
              <h3 class="rabez-product-card__title"><a href="/product/real-madrid-26-27-home-jersey">Real Madrid 26/27 Home Half Sleeve Jersey</a></h3>
              <div class="price rabez-product-card__price">৳ 1,150</div>
            </div>
          </li>
          <li class="product type-product rabez-product-card has-back-view" data-product-id="1411">
            <div class="rabez-product-card__media">
              <a href="/product/argentina-3-star-messi-10-celebration-full-sleeve" class="woocommerce-LoopProduct-link">
                <img src="{{ asset('assets/images/products/messi-celebration-fs.jpg') }}" class="rabez-card-img-front" alt="Messi Celebration Kit Full Sleeve Jersey - Front View" width="500" height="667" loading="lazy">
                <img src="{{ asset('assets/images/products/real-madrid-away-fs.jpg') }}" class="rabez-card-img-back" alt="Messi Celebration Kit Full Sleeve Jersey - Back View" width="500" height="667" loading="lazy">
                <span class="rabez-card-flip-pill">Back View</span>
              </a>
              <div class="rabez-product-card__badges">
                <span class="rabez-badge rabez-badge--coral">FULL SLEEVE</span>
              </div>
              <div class="rabez-product-card__actions">
                <button type="button" class="rabez-card-icon-btn js-wishlist-toggle" data-product-id="1411" aria-label="Add to Wishlist" title="Wishlist">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
                <button type="button" class="rabez-card-icon-btn js-quick-view" data-product-id="1411" aria-label="Quick View" title="Quick View">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>
            <div class="rabez-product-card__body">
              <h3 class="rabez-product-card__title"><a href="/product/argentina-3-star-messi-10-celebration-full-sleeve">Messi Celebration Kit Full Sleeve Jersey</a></h3>
              <div class="price rabez-product-card__price">৳ 1,300</div>
            </div>
          </li>
          <li class="product type-product rabez-product-card has-back-view" data-product-id="1320">
            <div class="rabez-product-card__media">
              <a href="/product/fc-barcelona-26-27-home-half-sleeve-player-edition" class="woocommerce-LoopProduct-link">
                <img src="{{ asset('assets/images/products/barcelona-home-hs.jpg') }}" class="rabez-card-img-front" alt="Barcelona Home Half Sleeve Jersey - Front View" width="500" height="667" loading="lazy">
                <img src="{{ asset('assets/images/products/messi-barca-arg-hoodie.jpg') }}" class="rabez-card-img-back" alt="Barcelona Home Half Sleeve Jersey - Back View" width="500" height="667" loading="lazy">
                <span class="rabez-card-flip-pill">Back View</span>
              </a>
              <div class="rabez-product-card__badges">
                <span class="rabez-badge rabez-badge--gold">HOT DROP</span>
              </div>
              <div class="rabez-product-card__actions">
                <button type="button" class="rabez-card-icon-btn js-wishlist-toggle" data-product-id="1320" aria-label="Add to Wishlist" title="Wishlist">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
                <button type="button" class="rabez-card-icon-btn js-quick-view" data-product-id="1320" aria-label="Quick View" title="Quick View">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>
            <div class="rabez-product-card__body">
              <h3 class="rabez-product-card__title"><a href="/product/fc-barcelona-26-27-home-half-sleeve-player-edition">Barcelona Home Half Sleeve Jersey</a></h3>
              <div class="price rabez-product-card__price">৳ 1,150</div>
            </div>
          </li>
          <li class="product type-product rabez-product-card has-back-view" data-product-id="1384">
            <div class="rabez-product-card__media">
              <a href="/product/bangladesh-national-team-26-27-home-half-sleeve" class="woocommerce-LoopProduct-link">
                <img src="{{ asset('assets/images/products/bangladesh-home-hs.jpg') }}" class="rabez-card-img-front" alt="Bangladesh BFF Home Half Sleeve Jersey - Front View" width="500" height="667" loading="lazy">
                <img src="{{ asset('assets/images/products/real-madrid-teal-trefoil-hs.jpg') }}" class="rabez-card-img-back" alt="Bangladesh BFF Home Half Sleeve Jersey - Back View" width="500" height="667" loading="lazy">
                <span class="rabez-card-flip-pill">Back View</span>
              </a>
              <div class="rabez-product-card__badges">
                <span class="rabez-badge rabez-badge--neon">POPULAR</span>
              </div>
              <div class="rabez-product-card__actions">
                <button type="button" class="rabez-card-icon-btn js-wishlist-toggle" data-product-id="1384" aria-label="Add to Wishlist" title="Wishlist">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
                <button type="button" class="rabez-card-icon-btn js-quick-view" data-product-id="1384" aria-label="Quick View" title="Quick View">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>
            <div class="rabez-product-card__body">
              <h3 class="rabez-product-card__title"><a href="/product/bangladesh-national-team-26-27-home-half-sleeve">Bangladesh (BFF) Home Half Sleeve Jersey</a></h3>
              <div class="price rabez-product-card__price">৳ 1,150</div>
            </div>
          </li>
        </ul>

        <div style="text-align: center; margin-top: 48px;">
          <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--dark js-view-full-catalog-btn">
            View Full Jersey Catalog ({{ $totalCatalogCount }} Kits)
          </a>
        </div>
      </div>
    </section>

    <!-- ======================================================================
         WIDGET 06: 4-CARD SLEEVE, WINTER HOODIE & EDITION BENTO SHOWCASE
         ====================================================================== -->
    <section class="rabez-section rabez-section--sm rabez-section--alt">
      <div class="rabez-container">
        <div class="rabez-section-head">
          <h2 class="rabez-section-title">Shop By Sleeve, Hoodie &amp; Edition</h2>
        </div>

        <div class="rabez-bento-grid">
          <article class="rabez-bento-card">
            <img src="assets/images/products/barcelona-home-hs.jpg" alt="Half Sleeve Club Football Jerseys" class="rabez-bento-card__bg" loading="lazy">
            <div class="rabez-bento-card__top">
              <span class="rabez-badge rabez-badge--neon">{{ $halfSleeveCount ? $halfSleeveCount . ' Kits' : 'Half Sleeve' }}</span>
              <span class="rabez-bento-card__price-pill">৳ 1,150</span>
            </div>
            <div class="rabez-bento-card__bottom">
              <h3 class="rabez-bento-card__title">Half Sleeve Match Kits</h3>
              <p class="rabez-bento-card__desc">
                Home, Away &amp; 3rd kits for Real Madrid, Barcelona, Arsenal, Man Utd &amp; Bangladesh.
              </p>
              <a href="#catalog" class="rabez-bento-card__link" onclick="document.querySelector('[data-sleeve=\'half-sleeve\']')?.click()">
                Shop Half Sleeve &rarr;
              </a>
            </div>
          </article>

          <article class="rabez-bento-card">
            <img src="assets/images/products/messi-celebration-fs.jpg" alt="Full Sleeve Collector Jerseys" class="rabez-bento-card__bg" loading="lazy">
            <div class="rabez-bento-card__top">
              <span class="rabez-badge rabez-badge--neon">Full Sleeve</span>
              <span class="rabez-bento-card__price-pill">৳ 1,300</span>
            </div>
            <div class="rabez-bento-card__bottom">
              <h3 class="rabez-bento-card__title">Full Sleeve Collector Kits</h3>
              <p class="rabez-bento-card__desc">
                Messi Celebration Kit, Real Madrid Away, Bayern Munich, Liverpool &amp; Arsenal long-sleeve jerseys.
              </p>
              <a href="#catalog" class="rabez-bento-card__link" onclick="document.querySelector('[data-sleeve=\'full-sleeve\']')?.click()">
                Shop Full Sleeve &rarr;
              </a>
            </div>
          </article>

          <article class="rabez-bento-card">
            <img src="assets/images/products/messi-barca-arg-hoodie.jpg" alt="Winter Hoodies with Football Jersey Crest Logo" class="rabez-bento-card__bg" loading="lazy">
            <div class="rabez-bento-card__top">
              <span class="rabez-badge rabez-badge--neon">Winter Drop</span>
              <span class="rabez-bento-card__price-pill">৳ 1,450</span>
            </div>
            <div class="rabez-bento-card__bottom">
              <h3 class="rabez-bento-card__title">Winter Hoodies (Jersey Logo)</h3>
              <p class="rabez-bento-card__desc">
                Heavyweight 320 GSM brushed fleece hoodies with Barcelona, Argentina #10 &amp; Real Madrid crests.
              </p>
              <a href="#catalog" class="rabez-bento-card__link" onclick="document.querySelector('[data-sleeve=\'winter-hoodie\']')?.click()">
                Shop Winter Hoodies &rarr;
              </a>
            </div>
          </article>

          <article class="rabez-bento-card">
            <img src="assets/images/products/real-madrid-away-fs.jpg" alt="Retro Vintage Football Jerseys" class="rabez-bento-card__bg" loading="lazy">
            <div class="rabez-bento-card__top">
              <span class="rabez-badge rabez-badge--retro">Retro Series</span>
              <span class="rabez-bento-card__price-pill">৳ 1,150 &ndash; ৳ 1,300</span>
            </div>
            <div class="rabez-bento-card__bottom">
              <h3 class="rabez-bento-card__title">Retro &amp; Trefoil Classics</h3>
              <p class="rabez-bento-card__desc">
                Iconic 99/00 Teka editions, buttoned polo collars, and Originals Trefoil heritage kits.
              </p>
              <a href="#catalog" class="rabez-bento-card__link" onclick="document.querySelector('[data-club=\'real-madrid\']')?.click()">
                Explore Retro Drops &rarr;
              </a>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- ======================================================================
         WIDGET 07: LIVE CUSTOM NAME & NUMBER BACK PRINT STUDIO
         ====================================================================== -->
    @if(isset($customPrintProducts) && $customPrintProducts->isNotEmpty())
    @php
      $printFee = intval($customPrintFee ?? 250);
      $defaultCustProduct = $customPrintProducts->firstWhere('sku', 'RM-HOME-26') ?: $customPrintProducts->first();

      $resolveKitTheme = function ($prod) {
          $sku = strtoupper((string) ($prod->sku ?? ''));
          $name = strtolower((string) ($prod->name ?? ''));
          $club = strtolower((string) ($prod->club ?? ''));

          if ($sku === 'RMA-AWAY-FS' || str_contains($name, 'orange') || str_contains($name, 'away orange')) {
              return ['bgTop' => '#E85D1A', 'bgBot' => '#C2410C', 'trim' => '#18181B', 'text' => '#111111', 'colorName' => 'Black', 'isLight' => false];
          }
          if ($sku === 'RMA-TEAL-TREFOIL' || str_contains($name, 'teal')) {
              return ['bgTop' => '#0F5C5C', 'bgBot' => '#093B3B', 'trim' => '#E2E8F0', 'text' => '#FFFFFF', 'colorName' => 'White', 'isLight' => false];
          }
          if ($sku === 'RMA-3RD-HS' || str_contains($name, 'charcoal') || str_contains($name, '3rd') || str_contains($name, 'dark') || str_contains($name, 'black')) {
              return ['bgTop' => '#242926', 'bgBot' => '#121614', 'trim' => '#F5D76E', 'text' => '#F5D76E', 'colorName' => 'Gold', 'isLight' => false];
          }
          if ($club === 'real-madrid') {
              return ['bgTop' => '#F8FAF9', 'bgBot' => '#E5ECE8', 'trim' => '#006A4E', 'text' => '#111111', 'colorName' => 'Black', 'isLight' => true];
          }
          if ($club === 'barcelona') {
              return ['bgTop' => '#163A82', 'bgBot' => '#0D2454', 'trim' => '#A61B34', 'text' => '#F5D76E', 'colorName' => 'Gold', 'isLight' => false];
          }
          if ($club === 'argentina') {
              return ['bgTop' => '#74ACDF', 'bgBot' => '#4A8CC7', 'trim' => '#FFFFFF', 'text' => '#111111', 'colorName' => 'Black', 'isLight' => true];
          }
          if ($club === 'arsenal') {
              return ['bgTop' => '#D91E2A', 'bgBot' => '#9E111B', 'trim' => '#FFFFFF', 'text' => '#FFFFFF', 'colorName' => 'White', 'isLight' => false];
          }
          if ($club === 'man-utd') {
              return ['bgTop' => '#C8102E', 'bgBot' => '#8F0A20', 'trim' => '#111111', 'text' => '#FFFFFF', 'colorName' => 'White', 'isLight' => false];
          }
          if ($club === 'bangladesh') {
              return ['bgTop' => '#0B5D3B', 'bgBot' => '#073D26', 'trim' => '#D9232E', 'text' => '#FFFFFF', 'colorName' => 'White', 'isLight' => false];
          }
          if ($club === 'bayern') {
              return ['bgTop' => '#DC052D', 'bgBot' => '#9C031F', 'trim' => '#FFFFFF', 'text' => '#FFFFFF', 'colorName' => 'White', 'isLight' => false];
          }
          if ($club === 'liverpool') {
              return ['bgTop' => '#C8102E', 'bgBot' => '#8B091E', 'trim' => '#F5D76E', 'text' => '#FFFFFF', 'colorName' => 'White', 'isLight' => false];
          }
          return ['bgTop' => '#1A221E', 'bgBot' => '#0E1210', 'trim' => '#04F404', 'text' => '#FFFFFF', 'colorName' => 'White', 'isLight' => false];
      };

      $defaultTheme = $resolveKitTheme($defaultCustProduct);
      $defaultPrice = intval($defaultCustProduct->price ?? 1150);
      $defaultImg = $defaultCustProduct->image
          ? (str_starts_with($defaultCustProduct->image, 'http') ? $defaultCustProduct->image : asset(ltrim($defaultCustProduct->image, '/')))
          : asset('assets/images/products/real-madrid-home-hs.jpg');
      $defaultSleeve = $defaultCustProduct->sleeve ?: 'half-sleeve';
      $defaultSleeveLabel = $defaultCustProduct->sleeve_label ?: ucwords(str_replace('-', ' ', $defaultSleeve));

      $defaultVariants = $defaultCustProduct->variants ?? collect();
      $defaultFallbackSizes = $defaultSleeve === 'footwear' ? ['39', '40', '41', '42', '43', '44'] : ['S', 'M', 'L', 'XL', 'XXL'];
      $defaultAllSizes = $defaultVariants->isNotEmpty() ? $defaultVariants->pluck('size')->filter()->values()->all() : $defaultFallbackSizes;
      $defaultInStockSizes = $defaultVariants->isNotEmpty() ? $defaultVariants->where('is_in_stock', true)->pluck('size')->filter()->values()->all() : $defaultFallbackSizes;
      if (empty($defaultAllSizes)) {
          $defaultAllSizes = $defaultFallbackSizes;
          $defaultInStockSizes = $defaultFallbackSizes;
      }
      $defaultSelectedSize = in_array('L', $defaultInStockSizes, true) ? 'L' : ($defaultInStockSizes[0] ?? ($defaultAllSizes[0] ?? 'L'));

      $customClubs = $customPrintProducts
          ->filter(fn ($p) => !empty($p->club) && $p->club !== 'all')
          ->mapWithKeys(function ($p) {
              $label = $p->club_label ?: ucwords(str_replace('-', ' ', $p->club));
              return [$p->club => $label];
          })
          ->all();

      $vinylColors = [
          ['hex' => '#FFFFFF', 'name' => 'White', 'title' => 'Pure White', 'border' => 'rgba(255,255,255,0.28)'],
          ['hex' => '#F5D76E', 'name' => 'Gold', 'title' => 'Champion Gold', 'border' => 'rgba(255,255,255,0.25)'],
          ['hex' => '#111111', 'name' => 'Black', 'title' => 'Pitch Black', 'border' => '#555555'],
          ['hex' => '#00E5FF', 'name' => 'Cyan', 'title' => 'Electric Cyan', 'border' => 'rgba(255,255,255,0.25)'],
          ['hex' => '#E63946', 'name' => 'Red', 'title' => 'Crimson Red', 'border' => 'rgba(255,255,255,0.25)'],
      ];
    @endphp
    <section id="custom-print" class="rabez-section rabez-section--sm rabez-section--dark rabez-home-studio-section">
      <div class="rabez-container">
        <div class="rabez-home-studio-head">
          <div class="rabez-home-studio-head__left">
            <div class="rabez-home-studio-eyebrow">
              <span class="rabez-home-studio-dot" aria-hidden="true"></span>
              <span>OFFICIAL HEAT-PRESS STUDIO</span>
            </div>
            <h2 class="rabez-section-title rabez-home-studio-title">
              Custom Name &amp; Number Print
              <span class="rabez-home-studio-fee-badge">+৳{{ $printFee }}</span>
            </h2>
            <p class="rabez-home-studio-sub">
              Pick your jersey, preview your back print in real time, choose vinyl finish &amp; size, then add straight to bag.
            </p>
          </div>
          <div class="rabez-home-studio-trust">
            <span class="rabez-home-trust-chip">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              Authentic Player Vinyl
            </span>
            <span class="rabez-home-trust-chip">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
              Wash &amp; Peel Guaranteed
            </span>
            <span class="rabez-home-trust-chip">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/><path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>
              Cash on Delivery
            </span>
          </div>
        </div>

        <div class="rabez-customizer rabez-home-studio js-jersey-customizer" data-print-fee="{{ $printFee }}">
          <!-- Left Controls Column -->
          <div class="rabez-customizer__form rabez-home-studio__controls">
            <!-- Step 1: Club Filter + Visual Kit Rail -->
            <div class="rabez-home-step-block">
              <div class="rabez-home-step-top">
                <span class="rabez-home-step-label">1. Select Jersey Kit</span>
                <div class="rabez-home-rail-nav">
                  <button type="button" class="rabez-home-rail-btn js-home-rail-prev" aria-label="Scroll kits left" title="Previous kits">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                  </button>
                  <button type="button" class="rabez-home-rail-btn js-home-rail-next" aria-label="Scroll kits right" title="Next kits">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                  </button>
                </div>
              </div>

              @if(count($customClubs) > 1)
                <div class="rabez-home-club-tabs" role="tablist" aria-label="Filter customizable kits by club">
                  <button type="button" class="rabez-home-club-tab is-active js-home-club-filter" data-club="all">
                    All ({{ $customPrintProducts->count() }})
                  </button>
                  @foreach($customClubs as $clubSlug => $clubLabel)
                    <button type="button" class="rabez-home-club-tab js-home-club-filter" data-club="{{ $clubSlug }}">
                      {{ $clubLabel }}
                    </button>
                  @endforeach
                </div>
              @endif

              <div class="rabez-home-kit-rail js-home-kit-rail" role="listbox" aria-label="Customizable Jersey Kits">
                @foreach($customPrintProducts as $cp)
                  @php
                    $theme = $resolveKitTheme($cp);
                    $cpImg = $cp->image
                        ? (str_starts_with($cp->image, 'http') ? $cp->image : asset(ltrim($cp->image, '/')))
                        : asset('assets/images/products/real-madrid-home-hs.jpg');
                    $cpSleeve = $cp->sleeve ?: 'half-sleeve';
                    $cpSleeveLabel = $cp->sleeve_label ?: ucwords(str_replace('-', ' ', $cpSleeve));
                    $isActiveKit = intval($cp->id) === intval($defaultCustProduct->id);
                  @endphp
                  <button type="button"
                          class="rabez-home-kit-card js-kit-theme-swatch {{ $isActiveKit ? 'is-active' : '' }}"
                          role="option"
                          aria-selected="{{ $isActiveKit ? 'true' : 'false' }}"
                          data-product-id="{{ $cp->id }}"
                          data-club="{{ $cp->club ?: 'all' }}"
                          data-bg="{{ $theme['bgTop'] }}"
                          data-bg-end="{{ $theme['bgBot'] }}"
                          data-trim="{{ $theme['trim'] }}"
                          data-text="{{ $theme['text'] }}"
                          data-color-name="{{ $theme['colorName'] }}"
                          data-is-light="{{ $theme['isLight'] ? '1' : '0' }}"
                          data-sleeve="{{ $cpSleeve }}"
                          data-sleeve-label="{{ $cpSleeveLabel }}"
                          data-price="{{ floatval($cp->price) }}"
                          data-name="{{ $cp->name }}"
                          data-slug="{{ $cp->slug ?: $cp->id }}"
                          data-sku="{{ $cp->sku ?: ('RBZ-' . $cp->id) }}"
                          data-image="{{ $cpImg }}">
                    <img src="{{ $cpImg }}" alt="{{ $cp->name }}" class="rabez-home-kit-card__img" width="42" height="52" loading="lazy">
                    <div class="rabez-home-kit-card__info">
                      <div class="rabez-home-kit-card__title">{{ $cp->name }}</div>
                      <div class="rabez-home-kit-card__meta">
                        <span class="rabez-home-kit-card__sleeve">{{ $cpSleeveLabel }}</span>
                        <span class="rabez-home-kit-card__price">৳{{ number_format($cp->price) }}</span>
                      </div>
                    </div>
                    <span class="rabez-home-kit-card__dot" style="background:{{ $theme['bgTop'] }}; border-color:{{ $theme['trim'] }};" aria-hidden="true"></span>
                  </button>
                @endforeach
              </div>

              <!-- Synced Screen-Reader Accessible Select -->
              <label for="cust-kit-select" class="screen-reader-text">Select Customizable Jersey</label>
              <select id="cust-kit-select" class="js-cust-kit screen-reader-text">
                @foreach($customPrintProducts as $cp)
                  <option value="{{ $cp->id }}" {{ intval($cp->id) === intval($defaultCustProduct->id) ? 'selected' : '' }}>
                    {{ $cp->name }} (৳{{ number_format($cp->price) }} + ৳{{ $printFee }})
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Step 2: 1-Click Popular Legends Presets -->
            <div class="rabez-home-step-block">
              <div class="rabez-home-presets-label">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>2. Popular Legends (1-Click Fill):</span>
              </div>
              <div class="rabez-home-presets-list">
                <button type="button" class="rabez-home-preset-pill js-home-preset-pill" data-name="MESSI" data-num="10">MESSI 10</button>
                <button type="button" class="rabez-home-preset-pill js-home-preset-pill" data-name="RONALDO" data-num="7">RONALDO 7</button>
                <button type="button" class="rabez-home-preset-pill js-home-preset-pill" data-name="BELLINGHAM" data-num="5">BELLINGHAM 5</button>
                <button type="button" class="rabez-home-preset-pill js-home-preset-pill" data-name="VINICIUS" data-num="7">VINI JR 7</button>
                <button type="button" class="rabez-home-preset-pill js-home-preset-pill" data-name="YAMAL" data-num="19">YAMAL 19</button>
                <button type="button" class="rabez-home-preset-pill js-home-preset-pill" data-name="MBAPPE" data-num="9">MBAPPE 9</button>
                <button type="button" class="rabez-home-preset-pill js-home-preset-pill" data-name="MODRIC" data-num="10">MODRIC 10</button>
                <button type="button" class="rabez-home-preset-pill js-home-preset-pill" data-name="HAALAND" data-num="9">HAALAND 9</button>
                <button type="button" class="rabez-home-preset-pill js-home-preset-pill" data-name="SALAH" data-num="11">SALAH 11</button>
              </div>
            </div>

            <!-- Step 3: Back Name & Squad Number -->
            <div class="rabez-home-step-block">
              <div class="rabez-field-row rabez-home-field-row">
                <div class="rabez-field rabez-home-input-group">
                  <div class="rabez-home-input-top">
                    <label for="cust-name">3. Back Name</label>
                    <span class="rabez-home-char-count js-cust-name-count">5/14</span>
                  </div>
                  <div class="rabez-home-input-wrap">
                    <input type="text" id="cust-name" class="js-cust-name" maxlength="14" value="RABEZ" placeholder="e.g. BELLINGHAM" autocomplete="off" spellcheck="false">
                    <button type="button" class="rabez-home-clear-btn js-cust-clear-name" aria-label="Clear back name" title="Clear Name">&times;</button>
                  </div>
                </div>

                <div class="rabez-field rabez-home-input-group">
                  <div class="rabez-home-input-top">
                    <label for="cust-number">Number</label>
                    <span class="rabez-home-char-count js-cust-num-count">2/2</span>
                  </div>
                  <div class="rabez-home-input-wrap">
                    <input type="text" id="cust-number" class="js-cust-number" maxlength="2" inputmode="numeric" pattern="[0-9]*" value="10" placeholder="10" autocomplete="off">
                    <button type="button" class="rabez-home-clear-btn js-cust-clear-num" aria-label="Clear squad number" title="Clear Number">&times;</button>
                  </div>
                </div>
              </div>
              <div class="rabez-home-error js-cust-error" role="alert" style="display:none;"></div>
            </div>

            <!-- Step 4: Vinyl Print Color + Size Pills & Quantity -->
            <div class="rabez-home-config-grid">
              <!-- Vinyl Print Color -->
              <div class="rabez-home-config-box">
                <div class="rabez-home-config-top">
                  <span class="rabez-home-step-label">4. Vinyl Color</span>
                  <span class="rabez-home-color-name js-home-color-name">{{ $defaultTheme['colorName'] }}</span>
                </div>
                <div class="rabez-home-color-row">
                  @foreach($vinylColors as $vc)
                    <button type="button"
                            class="rabez-home-color-btn js-home-color-btn {{ $defaultTheme['colorName'] === $vc['name'] ? 'is-active' : '' }}"
                            data-color="{{ $vc['hex'] }}"
                            data-color-name="{{ $vc['name'] }}"
                            title="{{ $vc['title'] }}"
                            aria-label="{{ $vc['title'] }}">
                      <span class="rabez-home-color-swatch" style="background:{{ $vc['hex'] }}; border-color:{{ $vc['border'] }};"></span>
                    </button>
                  @endforeach
                </div>
                <div class="rabez-home-contrast-warn js-home-contrast-warn" style="display:none;">
                  Low contrast on this kit &mdash; switch color for sharper print visibility.
                </div>
              </div>

              <!-- Size & Qty -->
              <div class="rabez-home-config-box">
                <div class="rabez-home-config-top">
                  <span class="rabez-home-step-label">Size &amp; Qty</span>
                  <a href="#size-guide" class="rabez-home-size-guide-link">Size Chart &darr;</a>
                </div>
                <div class="rabez-home-size-qty-row">
                  <div class="rabez-home-sizes js-home-size-pills" role="radiogroup" aria-label="Select Jersey Size">
                    @foreach($defaultAllSizes as $sz)
                      @php $szInStock = in_array($sz, $defaultInStockSizes, true); @endphp
                      <button type="button"
                              class="rabez-home-size-pill js-home-size-pill {{ $sz === $defaultSelectedSize ? 'is-active' : '' }}"
                              data-size="{{ $sz }}"
                              {{ !$szInStock ? 'disabled' : '' }}>
                        {{ $sz }}
                      </button>
                    @endforeach
                  </div>
                  <div class="rabez-home-qty-stepper" aria-label="Quantity">
                    <button type="button" class="rabez-home-qty-btn js-home-qty-minus" aria-label="Decrease quantity">&minus;</button>
                    <span class="rabez-home-qty-val js-home-qty-val">1</span>
                    <button type="button" class="rabez-home-qty-btn js-home-qty-plus" aria-label="Increase quantity">&plus;</button>
                  </div>
                </div>
                <label for="cust-size-select" class="screen-reader-text">Size</label>
                <select id="cust-size-select" class="js-cust-size screen-reader-text">
                  @foreach($defaultAllSizes as $sz)
                    <option value="{{ $sz }}" {{ $sz === $defaultSelectedSize ? 'selected' : '' }}>{{ $sz }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Step 5: CTAs & Guarantee Footer -->
            <div class="rabez-home-cta-group">
              <div class="rabez-home-cta-row">
                <button type="button" class="rabez-btn rabez-btn--primary rabez-btn--full rabez-home-cta-add js-add-custom-kit">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5a1.5 1.5 0 0 1-1.5 1.5h-14A1.5 1.5 0 0 1 3.5 21l1-12.5z"/>
                  </svg>
                  <span>Add Custom Jersey to Bag</span>
                  <span class="rabez-home-cta-price js-home-btn-price">৳ {{ number_format($defaultPrice + $printFee) }}</span>
                </button>
                <button type="button" class="rabez-btn rabez-btn--outline-light rabez-btn--full rabez-home-cta-wa js-home-wa-order">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                  </svg>
                  <span>Order via WhatsApp</span>
                </button>
              </div>
              <div class="rabez-home-studio-footer">
                <span class="rabez-home-guarantee">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                  </svg>
                  Official Heat-Press Vinyl &bull; No Peel Guarantee
                </span>
                <a href="https://api.whatsapp.com/send/?phone=8801612359652&text=%E0%A6%86%E0%A6%B8%E0%A6%B8%E0%A6%BE%E0%A6%B2%E0%A6%BE%E0%A6%AE%E0%A7%81+%E0%A6%86%E0%A6%B2%E0%A6%BE%E0%A6%87%E0%A6%95%E0%A7%81%E0%A6%AE+RABEZ%2C+%E0%A6%86%E0%A6%AE%E0%A6%BE%E0%A6%A6%E0%A7%87%E0%A6%B0+%E0%A6%9F%E0%A6%BF%E0%A6%AE%E0%A7%87%E0%A6%B0+%E0%A6%9C%E0%A6%A8%E0%A7%8D%E0%A6%AF+%E0%A6%95%E0%A6%BE%E0%A6%B8%E0%A7%8D%E0%A6%9F%E0%A6%AE+%E0%A6%A8%E0%A6%BE%E0%A6%AE+%E0%A6%93+%E0%A6%A8%E0%A6%BE%E0%A6%AE%E0%A7%8D%E0%A6%AC%E0%A6%BE%E0%A6%B0+%E0%A6%B8%E0%A6%B9+%E0%A6%9C%E0%A6%BE%E0%A6%B0%E0%A7%8D%E0%A6%B8%E0%A6%BF+%E0%A6%85%E0%A6%B0%E0%A7%8D%E0%A6%A1%E0%A6%BE%E0%A6%B0+%E0%A6%95%E0%A6%B0%E0%A6%A4%E0%A7%87+%E0%A6%9A%E0%A6%BE%E0%A6%87%E0%A5%A4"
                   target="_blank" rel="noopener" class="rabez-home-bulk-link">
                  Team / Turf Bulk Order (10+ Kits)? Chat on WhatsApp &rarr;
                </a>
              </div>
            </div>
          </div>

          <!-- Right Live Stadium Back Preview Stage -->
          <div class="rabez-jersey-preview-stage rabez-home-studio__stage" style="--stage-glow: {{ $defaultTheme['trim'] }};">
            <div class="rabez-stage-topbar">
              <div class="rabez-stage-live-pill">
                <span class="rabez-stage-live-dot" aria-hidden="true"></span>
                <span>LIVE BACK PREVIEW</span>
              </div>
              <div class="rabez-stage-topbar__right">
                <span class="rabez-stage-sleeve-pill js-home-stage-sleeve">{{ $defaultSleeveLabel }}</span>
                <button type="button" class="rabez-stage-reset-btn js-home-reset-btn" title="Reset print preview">Reset</button>
              </div>
            </div>

            <div class="rabez-stage-canvas">
              <div class="rabez-jersey-svg-wrap">
                <svg viewBox="0 0 280 320" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <defs>
                    <linearGradient id="rbzHomeJerseyGrad" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" class="js-svg-grad-top" stop-color="{{ $defaultTheme['bgTop'] }}"/>
                      <stop offset="100%" class="js-svg-grad-bot" stop-color="{{ $defaultTheme['bgBot'] }}"/>
                    </linearGradient>
                    <linearGradient id="rbzHomeShadeGrad" x1="0" y1="0" x2="1" y2="0">
                      <stop offset="0%" stop-color="#000000" stop-opacity="0.22"/>
                      <stop offset="22%" stop-color="#FFFFFF" stop-opacity="0.06"/>
                      <stop offset="50%" stop-color="#FFFFFF" stop-opacity="0.1"/>
                      <stop offset="78%" stop-color="#000000" stop-opacity="0.06"/>
                      <stop offset="100%" stop-color="#000000" stop-opacity="0.24"/>
                    </linearGradient>
                    <pattern id="rbzHomeMeshPat" width="6" height="6" patternUnits="userSpaceOnUse">
                      <circle cx="1.5" cy="1.5" r="0.65" fill="#000000" fill-opacity="0.12"/>
                      <circle cx="4.5" cy="4.5" r="0.65" fill="#FFFFFF" fill-opacity="0.08"/>
                    </pattern>
                    <filter id="rbzHomeDropShadow" x="-12%" y="-10%" width="124%" height="124%">
                      <feDropShadow dx="0" dy="14" stdDeviation="12" flood-color="#000000" flood-opacity="0.55"/>
                    </filter>
                  </defs>

                  <!-- Full-Sleeve Extensions (toggled when full-sleeve product selected) -->
                  <g class="js-svg-long-sleeves" style="{{ $defaultSleeve === 'full-sleeve' ? '' : 'display:none;' }}">
                    <path d="M42 125 L14 190 L38 200 L70 112 Z" fill="url(#rbzHomeJerseyGrad)" stroke="rgba(44,52,48,0.65)" stroke-width="1.6"/>
                    <path d="M238 125 L266 190 L242 200 L210 112 Z" fill="url(#rbzHomeJerseyGrad)" stroke="rgba(44,52,48,0.65)" stroke-width="1.6"/>
                    <path d="M16 187 L38 197 M264 187 L242 197" class="js-svg-jersey-trim" fill="none" stroke="{{ $defaultTheme['trim'] }}" stroke-width="4.5" stroke-linecap="round"/>
                  </g>

                  <!-- Main Torso Silhouette -->
                  <path class="js-svg-jersey-body"
                        d="M84 22 L110 29 Q140 38 170 29 L196 22 L262 72 L236 128 L208 109 L208 292 Q140 302 72 292 L72 109 L44 128 L18 72 Z"
                        fill="url(#rbzHomeJerseyGrad)" stroke="rgba(44,52,48,0.72)" stroke-width="1.8" filter="url(#rbzHomeDropShadow)"/>

                  <!-- Subtle Mesh Texture & Cylindrical 3D Torso Shading -->
                  <path d="M84 22 L110 29 Q140 38 170 29 L196 22 L262 72 L236 128 L208 109 L208 292 Q140 302 72 292 L72 109 L44 128 L18 72 Z"
                        fill="url(#rbzHomeMeshPat)" pointer-events="none"/>
                  <path d="M84 22 L110 29 Q140 38 170 29 L196 22 L262 72 L236 128 L208 109 L208 292 Q140 302 72 292 L72 109 L44 128 L18 72 Z"
                        fill="url(#rbzHomeShadeGrad)" pointer-events="none"/>

                  <!-- Collar Rib, Short Sleeve Cuffs & Raglan Seams -->
                  <path class="js-svg-jersey-trim"
                        d="M105 26 Q140 42 175 26"
                        fill="none" stroke="{{ $defaultTheme['trim'] }}" stroke-width="5.5" stroke-linecap="round"/>
                  <path class="js-svg-jersey-trim js-svg-short-cuffs"
                        d="M40 120 L67 102 M240 120 L213 102"
                        fill="none" stroke="{{ $defaultTheme['trim'] }}" stroke-width="5.5" stroke-linecap="round"
                        style="{{ $defaultSleeve === 'full-sleeve' ? 'display:none;' : '' }}"/>
                  <path d="M86 28 Q94 74 74 108 M194 28 Q186 74 206 108"
                        fill="none" stroke="rgba(255,255,255,0.16)" stroke-width="1.2" stroke-dasharray="3 3"/>
                  <path d="M80 120 L80 282 M200 120 L200 282"
                        fill="none" stroke="rgba(0,0,0,0.16)" stroke-width="1.2"/>

                  <!-- Hem Authenticity Jock-Tag -->
                  <rect x="122" y="274" width="36" height="12" rx="3" fill="rgba(10,12,11,0.72)" stroke="rgba(255,255,255,0.22)" stroke-width="0.8"/>
                  <text x="140" y="282.5" text-anchor="middle" fill="#04F404" font-family="Barlow Condensed, sans-serif" font-size="6.5" font-weight="800" letter-spacing="0.8">RABEZ 26</text>
                </svg>

                <div class="rabez-jersey-back-overlay">
                  <div class="rabez-jersey-back-name js-preview-name" style="color:{{ $defaultTheme['text'] }};">RABEZ</div>
                  <div class="rabez-jersey-back-num js-preview-num" style="color:{{ $defaultTheme['text'] }};">10</div>
                </div>
              </div>
            </div>

            <!-- Selected Kit Live Summary Footer -->
            <div class="rabez-stage-summary">
              <div class="rabez-stage-summary__kit">
                <img src="{{ $defaultImg }}" alt="{{ $defaultCustProduct->name }}" class="rabez-stage-summary__img js-home-summary-img" width="44" height="54">
                <div class="rabez-stage-summary__text">
                  <div class="rabez-stage-summary__name js-home-summary-name">{{ $defaultCustProduct->name }}</div>
                  <div class="rabez-stage-summary__meta js-home-summary-meta">
                    Size {{ $defaultSelectedSize }} &bull; {{ $defaultTheme['colorName'] }} Vinyl &bull; Qty 1
                  </div>
                  <a href="{{ route('product.detail', $defaultCustProduct->slug ?: $defaultCustProduct->id) }}" class="rabez-stage-summary__link js-home-summary-link">
                    View Full Kit &rarr;
                  </a>
                </div>
              </div>
              <div class="rabez-stage-summary__pricing">
                <div class="rabez-stage-summary__breakdown js-home-price-breakdown">Kit ৳{{ number_format($defaultPrice) }} + Print ৳{{ $printFee }}</div>
                <div class="rabez-stage-summary__total js-home-summary-total">৳ {{ number_format($defaultPrice + $printFee) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

    <!-- ======================================================================
         WIDGET 08: OFFICIAL RABEZ SIZE CHART (Player Edition & Retro)
         ====================================================================== -->
    <section id="size-guide" class="rabez-section rabez-section--sm">
      <div class="rabez-container">
        <div class="rabez-section-head">
          <h2 class="rabez-section-title">RABEZ Official Size Chart (Inches)</h2>
        </div>

        <div class="rabez-size-guide-box js-size-guide">
          <div class="rabez-size-guide-head">
            <div class="rabez-fit-tabs" role="tablist">
              <button type="button" class="rabez-fit-tab is-active js-fit-tab" data-fit="player">
                Player Edition Jersey
              </button>
              <button type="button" class="rabez-fit-tab js-fit-tab" data-fit="retro">
                Retro Size Chart
              </button>
            </div>
          </div>

          <!-- Panel 1: Player Edition Jersey (Chest 36-44, Length 27-31) -->
          <div class="js-fit-panel" data-fit-panel="player">
            <div class="rabez-size-panel-grid">
              <div>
                <div class="rabez-size-table-wrap">
                  <table class="rabez-size-table">
                    <thead>
                      <tr>
                        <th>Size</th>
                        <th>Chest (Inch)</th>
                        <th>Length (Inch)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><strong>S</strong></td>
                        <td>36"</td>
                        <td>27"</td>
                      </tr>
                      <tr>
                        <td><strong>M</strong></td>
                        <td>38"</td>
                        <td>28"</td>
                      </tr>
                      <tr>
                        <td><strong>L</strong></td>
                        <td>40"</td>
                        <td>29"</td>
                      </tr>
                      <tr>
                        <td><strong>XL</strong></td>
                        <td>42"</td>
                        <td>30"</td>
                      </tr>
                      <tr>
                        <td><strong>XXL</strong></td>
                        <td>44"</td>
                        <td>31"</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="rabez-size-note">
                  <strong>N.B:</strong> 0.5 inch may differ from jersey to jersey.
                </div>
              </div>

              <div class="rabez-size-visual">
                <img src="assets/images/brand/rabez-player-size-chart.jpg" alt="RABEZ Player Edition Jersey Size Chart (Chest 36-44, Length 27-31)" width="600" height="480">
              </div>
            </div>
          </div>

          <!-- Panel 2: Retro Size Chart (Chest 38-46, Length 27-31) -->
          <div class="js-fit-panel" data-fit-panel="retro" style="display:none;">
            <div class="rabez-size-panel-grid">
              <div>
                <div class="rabez-size-table-wrap">
                  <table class="rabez-size-table">
                    <thead>
                      <tr>
                        <th>Size</th>
                        <th>Length (Inch)</th>
                        <th>Chest (Inch)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><strong>S</strong></td>
                        <td>27"</td>
                        <td>38"</td>
                      </tr>
                      <tr>
                        <td><strong>M</strong></td>
                        <td>28"</td>
                        <td>40"</td>
                      </tr>
                      <tr>
                        <td><strong>L</strong></td>
                        <td>29"</td>
                        <td>42"</td>
                      </tr>
                      <tr>
                        <td><strong>XL</strong></td>
                        <td>30"</td>
                        <td>44"</td>
                      </tr>
                      <tr>
                        <td><strong>XXL</strong></td>
                        <td>31"</td>
                        <td>46"</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="rabez-size-note">
                  <strong>**</strong> The measurement may vary 0.5&ndash;0 inch from jersey to jersey.
                </div>
              </div>

              <div class="rabez-size-visual">
                <img src="assets/images/brand/rabez-retro-size-chart.jpg" alt="RABEZ Retro Jersey Size Chart (Length 27-31, Chest 38-46)" loading="lazy" width="600" height="600">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======================================================================
         WIDGET 09: TRUST & DELIVERY GUARANTEES (elementor-widget-rabez-trust)
         ====================================================================== -->
    <section class="rabez-trust-strip">
      <div class="rabez-container rabez-trust-grid">
        <div class="rabez-trust-item">
          <div class="rabez-trust-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
              <path d="M14 9h4l3 3v5c0 .6-.4 1-1 1h-2"/>
              <circle cx="7" cy="18" r="2"/>
              <circle cx="17" cy="18" r="2"/>
            </svg>
          </div>
          <div>
            <div class="rabez-trust-title">Cash on Delivery (All BD)</div>
            <div class="rabez-trust-desc">Check your jersey at your doorstep before paying.</div>
          </div>
        </div>

        <div class="rabez-trust-item">
          <div class="rabez-trust-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3L12 3z"/>
            </svg>
          </div>
          <div>
            <div class="rabez-trust-title">Premium Jacquard Fabric</div>
            <div class="rabez-trust-desc">Breathable mesh &amp; durable embroidery built for turf &amp; casual wear.</div>
          </div>
        </div>

        <div class="rabez-trust-item">
          <div class="rabez-trust-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 12a9 9 0 0 1 15-6.7L21 8"/>
              <path d="M21 3v5h-5"/>
              <path d="M21 12a9 9 0 0 1-15 6.7L3 16"/>
              <path d="M3 21v-5h5"/>
            </svg>
          </div>
          <div>
            <div class="rabez-trust-title">Easy 3-Day Size Exchange</div>
            <div class="rabez-trust-desc">Instant rider size exchange if your jersey doesn't fit right.</div>
          </div>
        </div>

        <div class="rabez-trust-item">
          <div class="rabez-trust-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
          </div>
          <div>
            <div class="rabez-trust-title">Direct WhatsApp Order</div>
            <div class="rabez-trust-desc">Order on website, WhatsApp (+880 1612-359652), or Facebook page.</div>
          </div>
        </div>
      </div>
    </section>
@endsection
