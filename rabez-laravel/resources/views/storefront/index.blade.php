@extends('layouts.storefront')

@section('title', 'RABEZ - Football Jersey Store Bangladesh | Half Sleeve, Full Sleeve & Winter Hoodies')

@section('content')
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
            <a href="#custom-print" class="rabez-btn rabez-btn--hero-secondary">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
              </svg>
              <span>Custom Name &amp; Number</span>
            </a>
          </div>
        </div>

        <!-- Right Interactive 3-Jersey Fan Stage -->
        <div class="rabez-hero-stage js-hero-stage" aria-label="Interactive Matchday Kit Showcase">
          <div class="rabez-hero-stage__glow"></div>

          <div class="rabez-hero-deck">
            <!-- Card 0: Real Madrid (Center Active) -->
            <article class="rabez-hero-deck__card is-active js-hero-deck-card" data-index="0" data-product-id="1324">
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
            <article class="rabez-hero-deck__card is-next js-hero-deck-card" data-index="1" data-product-id="1411">
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
            <article class="rabez-hero-deck__card js-hero-deck-card" data-index="2" data-product-id="1349">
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
            <article class="rabez-hero-deck__card js-hero-deck-card" data-index="3" data-product-id="1320">
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
            <article class="rabez-hero-deck__card is-prev js-hero-deck-card" data-index="4" data-product-id="1501">
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

          <div style="display:flex; align-items:center; flex-wrap:wrap; gap:12px;">
            <span class="js-product-result-count" style="font-size:0.84rem; font-weight:700; color:#6E7672;">Showing 18 Items</span>
            <a href="shop.html" class="rabez-btn rabez-btn--outline rabez-btn--sm">All 31 Kits &rarr;</a>
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
          <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--dark">
            View Full Jersey Catalog (28 Kits)
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
              <span class="rabez-badge rabez-badge--neon">21 Kits</span>
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
         WIDGET 07: LIVE CUSTOM NAME & NUMBER BACK PRINT PREVIEW
         (elementor-widget-rabez-customizer)
         ====================================================================== -->
    <section id="custom-print" class="rabez-section rabez-section--sm rabez-section--dark">
      <div class="rabez-container">
        <div class="rabez-section-head">
          <h2 class="rabez-section-title">Custom Name &amp; Number Print (+৳250)</h2>
        </div>

        <div class="rabez-customizer js-jersey-customizer">
          <div class="rabez-customizer__form">
            <div>
              <label style="display:block; font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#B4BDB8; margin-bottom:10px;">
                1. Select Kit Color:
              </label>
              <div class="rabez-kit-color-options">
                <button type="button" class="rabez-kit-swatch is-active js-kit-theme-swatch"
                        data-bg="#F7F8F7" data-trim="#006A4E" data-text="#111111" data-product-id="1324">
                  <span class="rabez-kit-swatch__circle" style="background:#FFFFFF;"></span>
                  Real Madrid Home
                </button>
                <button type="button" class="rabez-kit-swatch js-kit-theme-swatch"
                        data-bg="#151817" data-trim="#E5C158" data-text="#F5D76E" data-product-id="1388">
                  <span class="rabez-kit-swatch__circle" style="background:#111111;"></span>
                  Madrid Black Teka
                </button>
                <button type="button" class="rabez-kit-swatch js-kit-theme-swatch"
                        data-bg="#8C3B4A" data-trim="#F3E5AB" data-text="#F3E5AB" data-product-id="1326">
                  <span class="rabez-kit-swatch__circle" style="background:#8C3B4A;"></span>
                  Madrid Rose 3rd
                </button>
                <button type="button" class="rabez-kit-swatch js-kit-theme-swatch"
                        data-bg="#173F8A" data-trim="#9B1B30" data-text="#F5C518" data-product-id="1320">
                  <span class="rabez-kit-swatch__circle" style="background:#173F8A;"></span>
                  Barcelona Home
                </button>
                <button type="button" class="rabez-kit-swatch js-kit-theme-swatch"
                        data-bg="#0B4F36" data-trim="#C92A2A" data-text="#FFFFFF" data-product-id="1384">
                  <span class="rabez-kit-swatch__circle" style="background:#0B4F36;"></span>
                  Bangladesh Green
                </button>
                <button type="button" class="rabez-kit-swatch js-kit-theme-swatch"
                        data-bg="#0A0C0B" data-trim="#04F404" data-text="#FFFFFF" data-product-id="1501">
                  <span class="rabez-kit-swatch__circle" style="background:#0A0C0B; border-color:#04F404;"></span>
                  Winter Hoodie (Jersey Logo)
                </button>
              </div>
            </div>

            <div class="rabez-field-row">
              <div class="rabez-field">
                <label for="cust-name">2. Back Name</label>
                <input type="text" id="cust-name" class="js-cust-name" maxlength="12" value="RABEZ" placeholder="e.g. BELLINGHAM">
              </div>
              <div class="rabez-field">
                <label for="cust-number">3. Number</label>
                <input type="text" id="cust-number" class="js-cust-number" maxlength="2" value="10" placeholder="10">
              </div>
            </div>

            <div class="rabez-field-row">
              <div class="rabez-field">
                <label for="cust-kit-select">Select Jersey or Hoodie</label>
                <select id="cust-kit-select" class="js-cust-kit">
                  <option value="1324">Real Madrid Home Half Sleeve (৳1,150 + ৳250)</option>
                  <option value="1501">Winter Hoodie with Club Crest &amp; # (৳1,450 + ৳250)</option>
                  <option value="1388">Real Madrid Retro Teka Full Sleeve (৳1,300 + ৳250)</option>
                  <option value="1326">Real Madrid 3rd Rose Half Sleeve (৳1,150 + ৳250)</option>
                  <option value="1320">Barcelona Home Half Sleeve (৳1,150 + ৳250)</option>
                  <option value="1411">Messi Celebration Full Sleeve (৳1,300 + ৳250)</option>
                  <option value="1384">Bangladesh (BFF) Home Jersey (৳1,150 + ৳250)</option>
                </select>
              </div>
              <div class="rabez-field">
                <label for="cust-size-select">Size</label>
                <select id="cust-size-select" class="js-cust-size">
                  <option value="S">S (Player 36" / Retro 38")</option>
                  <option value="M">M (Player 38" / Retro 40")</option>
                  <option value="L" selected>L (Player 40" / Retro 42")</option>
                  <option value="XL">XL (Player 42" / Retro 44")</option>
                  <option value="XXL">XXL (Player 44" / Retro 46")</option>
                </select>
              </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap:12px; padding-top:4px;">
              <button type="button" class="rabez-btn rabez-btn--primary rabez-btn--full js-add-custom-kit">
                Add Custom Jersey to Bag
              </button>
              <a href="https://api.whatsapp.com/send/?phone=8801612359652&text=%E0%A6%86%E0%A6%B8%E0%A6%B8%E0%A6%BE%E0%A6%B2%E0%A6%BE%E0%A6%AE%E0%A7%81+%E0%A6%86%E0%A6%B2%E0%A6%BE%E0%A6%87%E0%A6%95%E0%A7%81%E0%A6%AE+RABEZ%2C+%E0%A6%86%E0%A6%AE%E0%A6%BE%E0%A6%A6%E0%A7%87%E0%A6%B0+%E0%A6%9F%E0%A6%BF%E0%A6%AE%E0%A7%87%E0%A6%B0+%E0%A6%9C%E0%A6%A8%E0%A7%8D%E0%A6%AF+%E0%A6%95%E0%A6%BE%E0%A6%B8%E0%A7%8D%E0%A6%9F%E0%A6%AE+%E0%A6%A8%E0%A6%BE%E0%A6%AE+%E0%A6%93+%E0%A6%A8%E0%A6%BE%E0%A6%AE%E0%A7%8D%E0%A6%AC%E0%A6%BE%E0%A6%B0+%E0%A6%B8%E0%A6%B9+%E0%A6%9C%E0%A6%BE%E0%A6%B0%E0%A7%8D%E0%A6%B8%E0%A6%BF+%E0%A6%85%E0%A6%B0%E0%A7%8D%E0%A6%A1%E0%A6%BE%E0%A6%B0+%E0%A6%95%E0%A6%B0%E0%A6%A4%E0%A7%87+%E0%A6%9A%E0%A6%BE%E0%A6%87%E0%A5%A4"
                 target="_blank" rel="noopener" class="rabez-btn rabez-btn--outline-light rabez-btn--full">
                Team Bulk Order (WhatsApp)
              </a>
            </div>
          </div>

          <div class="rabez-jersey-preview-stage">
            <div class="rabez-jersey-svg-wrap">
              <svg viewBox="0 0 260 300" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path class="js-svg-jersey-body"
                      d="M78 20 L102 26 Q130 34 158 26 L182 20 L242 66 L218 118 L194 102 L194 278 Q130 286 66 278 L66 102 L42 118 L18 66 Z"
                      fill="#F7F8F7" stroke="#2C3430" stroke-width="2"/>
                <path class="js-svg-jersey-trim"
                      d="M98 24 Q130 38 162 24 M38 110 L62 94 M222 110 L198 94"
                      fill="none" stroke="#006A4E" stroke-width="5" stroke-linecap="round"/>
              </svg>
              <div class="rabez-jersey-back-overlay">
                <div class="rabez-jersey-back-name js-preview-name" style="color:#111111;">RABEZ</div>
                <div class="rabez-jersey-back-num js-preview-num" style="color:#111111;">10</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

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
