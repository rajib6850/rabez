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
@endsection
