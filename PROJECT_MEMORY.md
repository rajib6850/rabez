# RABEZ — Complete Project Master Memory & Architectural Documentation
**Brand:** RABEZ (Official Bangladesh Football Jersey & Sportswear E-Commerce Store)  
**Motto:** *"Wear What You Stand For"*  
**Primary Color Palette:**
- Background Black: `#0A0C0B`
- Dark Surface: `#0D110F` / `#131A16`
- Electric Neon Green (Brand Accent): `#04F404` / `#22C55E`
- Borders: `#1F2823` / `#2B3830`
- Primary Typography: Pure White `#FFFFFF` (Headings) / `#E5E7EB` & `#D1D5DB` (Body text)
- Status Colors: `#EF4444` (Error / Inactive), `#EAB308` (Warning / Warning mix), `#3B82F6` (Info)

---

## 1. Executive Summary & Tech Stack

RABEZ is a full-featured, mobile-first, high-performance football jersey and sportswear e-commerce platform built for the Bangladesh market. It combines an authentic dark-themed storefront with an administrative command center for order fulfillment, inventory management, multi-district shipping rules, discount campaigns, and media asset optimization.

### Tech Stack:
- **Backend Framework:** Laravel 11.x (PHP 8.2+)
- **Database:** SQLite 3 (Fully portable, self-contained at `rabez-laravel/database/database.sqlite`)
- **Frontend Engine:** Laravel Blade Templates + Scoped Vanilla CSS3 Modules + Lightweight Reactive JavaScript
- **Image Processing Engine:** PHP GD / WebP Converter with automatic EXIF orientation correction for iPhone and DSLR camera photos
- **Courier Integration:** Steadfast Courier & Pathao Merchant API endpoints
- **Analytics & Tracking:** Meta / Facebook Pixel & Server-Side Conversions API (CAPI)

---

## 2. Directory Structure & Key Files

```text
e:/My Busniess/rabez/website design/
├── PROJECT_MEMORY.md                # This complete master memory & system documentation
├── README.md                        # Quickstart instructions for GitHub
├── .gitignore                       # Root git ignore file (vendor, storage cache, env)
├── index.html                       # Original static prototype home page
├── shop.html                        # Original static prototype shop catalog
├── product.html                     # Original static prototype single product page
├── checkout.html                    # Original static prototype checkout
├── track-order.html                 # Original static prototype order tracking
├── admin.html                       # Original static prototype dashboard
├── assets/                          # Static CSS and image assets library
│   ├── css/
│   │   ├── 01-variables.css         # CSS design tokens & color variables
│   │   ├── 02-base-reset.css        # Base reset styles
│   │   ├── 03-components.css       # Shared component styles
│   │   └── components/
│   │       ├── header-navigation.css # Sticky dark navbar & mobile drawer
│   │       ├── single-product.css    # Single product gallery, responsive buy row & specs
│   │       ├── product-grid.css      # Jersey card flip, badges, quick view
│   │       ├── cart-checkout.css     # AJAX cart drawer dark theme & checkout styles
│   │       ├── bento-collections.css # Homepage bento grid collections
│   │       ├── hero-banner.css       # Hero carousel & CTA
│   │       ├── footer.css            # Dark footer & social links
│   │       └── size-guide.css        # Player edition size chart modal styles
│   └── images/                      # Optimized jersey photos & banners
└── rabez-laravel/                   # Full Laravel 11 Application
    ├── app/
    │   ├── Http/Controllers/
    │   │   ├── AdminController.php   # Dashboard, orders, products, coupons, media, settings
    │   │   ├── CartController.php    # Session-based cart, coupon apply/remove, AJAX drawer
    │   │   ├── CheckoutController.php# Order processing, COD risk analysis, delivery calculation
    │   │   └── StorefrontController.php# Home, shop catalog, single product detail, track order
    │   └── Models/
    │       ├── Category.php          # Jerseys, hoodies, boots, tracksuits, accessories
    │       ├── Coupon.php            # Flat ৳ or % discounts, min spend, active toggle
    │       ├── Customer.php          # Customer delivery records & COD risk profile
    │       ├── Media.php             # Optimized WebP media assets
    │       ├── Order.php             # Consignment number, delivery tracking, items, totals
    │       ├── OrderItem.php         # Individual ordered jersey, size, custom print
    │       ├── Product.php           # Jersey specs, club, sleeve, front/back view, gallery
    │       ├── ProductVariant.php    # Size-based inventory (S, M, L, XL, XXL)
    │       ├── Setting.php           # Key-value site settings & courier credentials
    │       └── User.php              # Admin staff accounts & authentication
    ├── database/
    │   ├── database.sqlite           # Portable SQLite database file
    │   ├── migrations/               # Database table schemas
    │   └── seeders/DatabaseSeeder.php# Initial 18 products, variants, categories, settings
    ├── public/
    │   ├── assets/                   # Public assets symlink/copy for production serving
    │   └── uploads/media/            # Auto-compressed WebP user uploads (<250KB)
    ├── resources/
    │   └── views/
    │       ├── admin/
    │       │   └── dashboard.blade.php # Unified admin dashboard & control center
    │       ├── auth/
    │       │   ├── login.blade.php   # Admin secure login
    │       │   └── register.blade.php# Staff account registration
    │       ├── layouts/
    │       │   └── storefront.blade.php # Master layout with header, cart drawer, modals
    │       └── storefront/
    │           ├── index.blade.php   # Homepage with bento grid & popular kits
    │           ├── shop.blade.php    # Club filter, category switcher, sorting
    │           ├── product.blade.php # Single product page with front/back gallery & specs
    │           ├── checkout.blade.php# Multi-district checkout with COD/bKash/Nagad
    │           └── track-order.blade.php # Real-time delivery tracking timeline
    └── routes/web.php                # Complete URL routing for storefront and admin
```

---

## 3. Database Schema & Models

### `products` Table
- `id`: BigInt Primary Key
- `name`: String (e.g. "Real Madrid 26/27 Home Half Sleeve Jersey")
- `slug`: String Unique (e.g. `real-madrid-2627-home-jersey-19`)
- `sku`: String (e.g. `RMA-HOME-HS`)
- `category_id`: Foreign key to `categories.id`
- `club`: Slug (e.g. `real-madrid`, `barcelona`, `argentina`, `brazil`)
- `club_label`: Formatted club name
- `sleeve`: Slug (e.g. `half-sleeve`, `full-sleeve`, `winter-hoodie`)
- `sleeve_label`: Formatted sleeve type
- `regular_price`: Decimal (e.g. `1350`)
- `price`: Decimal (e.g. `1150`)
- `short_desc`: Text (Highlighted overview quote in green border box)
- `description`: LongText (Detailed specs, fabric, washing instructions)
- `image`: String (Featured front view jersey image)
- `gallery`: JSON array of image URLs (Back view is `gallery[0]`, followed by details)
- `badges`: JSON array of strings (e.g. `["Player Edition", "Best Seller"]`)
- `is_featured`: Boolean
- `is_ready_stock`: Boolean

### `coupons` Table
- `id`: BigInt Primary Key
- `code`: String Unique (Uppercase, e.g. `RABEZ100`, `EID2026`)
- `discount_type`: Enum (`flat` for fixed ৳ cash off, `percent` for % off)
- `discount_value`: Decimal (e.g. `100` or `10`)
- `min_spend`: Decimal (e.g. `1500`, 0 for no minimum)
- `description`: String (Campaign note, e.g. "৳100 discount on orders above ৳1500")
- `is_active`: Boolean (1 = Active, 0 = Inactive)
- `expires_at`: Timestamp Nullable

### `settings` Table
Key-value store initialized with defaults:
- `shipping_mode`: `'flat'` (Round figure all over Bangladesh) or `'zone'` (Inside vs Outside Dhaka)
- `shipping_flat_fee`: `130` (৳130 round figure across 64 districts)
- `shipping_inside_dhaka`: `70` (৳70)
- `shipping_outside_dhaka`: `130` (৳130)
- `custom_print_fee`: `250` (৳250 for player name & squad number print)
- `free_shipping_min`: `3500` (৳3,500 threshold for free delivery reward)
- `steadfast_api_key`: Secret API key for courier automation
- `pathao_store_id`: Registered Pathao store ID

### `media` Table
- `id`: BigInt Primary Key
- `filename`: Generated unique file name (e.g. `img_1794042124.webp`)
- `original_name`: Original uploaded filename
- `path`: Relative server path (`uploads/media/...`)
- `url`: Public asset URL
- `mime_type`: `image/webp`
- `file_size`: Integer (bytes, guaranteed < 250KB)
- `width` / `height`: Dimensions
- `is_optimized`: Boolean (True)

---

## 4. Key Frontend Features & UI System

### 1. Product Cards (Home & Shop)
- **Front & Back View Flip:** Hovering on desktop or viewing thumbnails displays the jersey's front view (`product.image`) and seamlessly transitions to the back view (`product.gallery[0]`).
- **Wishlist Toggle:** Client-side wishlist with heart animation.
- **Quick View Popup:** Full interactive modal showing swatches, front/back preview, quantity, and Add to Bag without leaving the page.
- **Badges:** Neon badges for `BEST SELLER`, `WINTER DROP`, `FULL SLEEVE`, `MATCH KIT`.

### 2. Single Product Page (`/product/{slug}`)
- **Responsive Gallery:**
  - Thumbnails horizontal touch scroll on mobile (`overflow-x: auto; flex-wrap: nowrap; -webkit-overflow-scrolling: touch;`).
  - Smooth switcher between Front View, Back View, and detail shots.
- **Size Selector:**
  - Standard sizes (`S`, `M`, `L`, `XL`, `XXL`).
  - Interactive Size Chart popup modal showing exact chest and length measurements in inches for both Player Edition and Retro jerseys.
- **Custom Name & Number Add-on (+৳250):**
  - Toggle checkbox dynamically updates button price.
  - Responsive inputs for Back Name (uppercase) and Squad Number that stack cleanly on mobile without clipping.
- **Purchase Actions:**
  - Quantity stepper (`−` and `+`).
  - "Add to Bag — ৳ X,XXX" button.
  - "Order Instantly on WhatsApp (+880 1612-359652)" button with wrapped responsive typography (`min-height: 46px;`).
- **Specifications & Description Section:**
  - Desktop: 3 Tab navigation (`Product Details & Specifications`, `Fabric & Care`, `Delivery & Exchange Policy`). First tab has zero left padding; last tab has zero right padding.
  - Mobile: Smooth accordion dropdowns with `+` and `−` indicators, independent expand/collapse, generous touch target padding (`15px 18px`).
  - Readability: Pure white headings (`#FFFFFF`), `#D1D5DB` body copy, `1.85` line-height, generous spacing.
- **Related Products:** 4-column responsive grid with front/back hover flip.

### 3. AJAX Slide-Over Cart Drawer
- **Dark Theme:** Surface `#0D110F`, item cards `#131A16` with `#1F2823` border, neon green accent `#04F404`.
- **In-Drawer Coupon System:** Customers can input coupon codes directly inside the drawer. Validates against database, calculates discount, and recalculates totals instantly.
- **In-Drawer Quantity Control:** Increment and decrement quantities with instant price recalculation.
- **Free Shipping Progress Bar:** Dynamic progress bar showing how much more to add for free shipping (e.g. ৳3,500 goal).
- **Dual Checkout:** One-click WhatsApp checkout or standard Web Checkout.

---

## 5. Admin Dashboard (`/admin`)

The admin dashboard is a unified, reactive control center:
1. **Sidebar Navigation:**
   - Overview / Dashboard
   - Orders (Steadfast & Pathao integration)
   - Products (Stock by size, pricing, front/back photos)
   - Add Product (Full Description, Short Description, Dynamic media picker)
   - Categories
   - Gallery (Auto-WebP engine, iPhone camera EXIF rotation)
   - Customers (COD risk analysis, phone lookup)
   - Users & Access (Roles: Admin, Editor, Staff)
   - **Discount (Dedicated Menu under Users):**
     - 4 Live Statistics KPI Cards: Active Coupons, Total Campaigns, Discount Mix (% vs ৳), Average Min Spend.
     - Real-time search filter input (`.js-coupon-search-input`).
     - Create, Edit, Toggle Active/Inactive, and Delete coupons.
     - One-click copy coupon code.
   - **Settings (Full-width card layout):**
     - Delivery Charge System: Flat Rate round figure (৳130 all over Bangladesh) vs Zone-based (Inside Dhaka ৳70 / Outside Dhaka ৳130).
     - Custom Print fee & Free delivery threshold.
     - Courier API Credentials (Steadfast, Pathao).
     - Meta Facebook Pixel ID & Server-Side Conversions API (CAPI) token.

---

## 6. How to Run Locally

### Prerequisites:
- PHP 8.2 or 8.5 with `pdo_sqlite`, `sqlite3`, `gd`, `fileinfo`, `mbstring`, `curl`, `openssl` enabled.
- Composer.

### Commands:
```bash
# 1. Navigate to laravel root
cd "rabez-laravel"

# 2. Install dependencies (if needed)
composer install

# 3. Create .env if not present
cp .env.example .env
php artisan key:generate

# 4. Migrate and seed database
php artisan migrate --seed

# 5. Start development server
php artisan serve --port=8000
```

### Default Admin Credentials:
- **URL:** `http://127.0.0.1:8000/admin`
- **Email:** `admin@rabez.shop`
- **Password:** `password`

---

## 7. History of Completed Revisions & User Decisions

1. **Product Single Page Navigation & URL routing:** Fixed slug routing so `/product/{slug}` and `/product/{id}` load seamlessly.
2. **Auto-WebP Media Gallery Engine:** Added server-side image compression that converts high-MB iPhone and DSLR photos into WebP (<250KB) and fixes upside-down EXIF orientation.
3. **Front & Back Jersey View System:**
   - Featured image represents Front view.
   - First gallery image (`gallery[0]`) represents Back view.
   - Product cards on Home and Shop flip between front and back on hover.
4. **Single Product Front/Back Buttons Removed:** Removed redundant front/back buttons from the image; user interacts with gallery thumbnails naturally.
5. **Add to Bag & Drawer Fixes:** Resolved cart addition logic, dark styled drawer, in-drawer coupon apply/remove, and in-drawer quantity buttons.
6. **Round Figure Delivery Charge (৳130):** Added settings option for a single nationwide flat rate (৳130) across Bangladesh while preserving optional zone-based fees. Removed all Bengali text from admin settings for clean English UI.
7. **Product Description & Rich Text:** Added Short Description and Long Description to both Add Product and Edit Product forms. Formatted frontend single product description with rich typography, white headings, `#D1D5DB` body text, and `1.85` line-height.
8. **Size Guide Popup Modal:** Moved size guide into an interactive modal popup with Player Edition and Retro sizing in inches.
9. **Dedicated "Discount" Sidebar Menu:** Moved coupons out of settings into their own menu under "Users" with 4 live statistical summary cards and real-time search.
10. **Full-Width Delivery & Courier Card:** Re-engineered the settings page to eliminate awkward empty space on the right side.
11. **Mobile Responsiveness Fix:** Eliminated horizontal overflow blowout on mobile by constraining grid tracks (`minmax(0, 1fr)`) and enabling text wrap on full-width buttons.
12. **Live Production Deployment to cPanel (`rabez.shop`):**
    - **Production URL:** `https://rabez.shop/`
    - **Live Admin Login:** `https://rabez.shop/login` (Admin: `admin@rabez.shop`)
    - **Hosting Environment:** cPanel on CloudLinux / LiteSpeed (`rain.cloudswebserver.com:2083`, IP: `103.112.62.103`)
    - **PHP Runtime:** Upgraded to **PHP 8.5** (`ea-php85` / Version 8.5.12) with native SQLite, GD, WebP, cURL, and OpenSSL.
    - **Launch Readiness:** All demo credential callouts, sample order pills, test orders, and dummy tokens were removed for official launch.
13. **Hero Secondary CTA Redesign:**
    - Replaced cryptic `CUSTOM NAME & #` with professional **`CUSTOM NAME & NUMBER`**.
    - Upgraded design from flat faint wireframe outline to a sleek **Dark Glassmorphic button** (`rgba(255,255,255,0.06)`, `backdrop-filter: blur(12px)`, soft drop shadow, and `#04F404` neon hover glow).
    - Added an authentic **Squad Kit / Jersey Icon** with neon green accent.
    - Added `scroll-margin-top: 86px` on `#custom-print` for zero-overlap smooth scrolling down to the interactive kit preview widget.
14. **Club Filter Bar & Mobile Alignment (User Decision):**
    - **Clean White Background Restored:** Retained the clean `#FFFFFF` daylight background for `.rabez-filter-bar` and `#F6F7F5` card tiles based on user preference.
    - **18px Left & Right Container Alignment:** Added `padding: 2px 18px 4px 18px;` and `scroll-padding: 0 18px;` to `.rabez-club-pills` so the active `All Kits` card no longer clips against the left edge of the screen and aligns with the 18px layout gutter.
    - **Hero Dock & Swipe:** Restored the interactive hero dock on mobile, providing smooth horizontal swipe with snap alignment.
15. **Mobile-First Size Chart Bottom Sheet Overhaul:**
    - **App-Like Bottom Sheet:** On screens `<= 640px`, the modal slides up as a smooth bottom sheet with top pull handle (`20px 20px 0 0` border radius), eliminating the bulky desktop scrollbar and centered floating card.
    - **Weight Fit Guidance:** Added recommended body weight column (`50-60 kg`, `60-70 kg`, `70-78 kg`, `78-86 kg`, `86-96 kg`) alongside Chest and Length in inches.
    - **1-Tap Quick Select:** Added bottom quick-select pills (`S`, `M`, `L`, `XL`, `XXL`) and clickable table rows that immediately select the size on the product page and smoothly close the sheet.
    - **Active Size Sync:** Opens with the user's currently selected product size highlighted in neon green.
    - **Collapsible Graphic Chart:** Clean toggle button (`View Official Graphic Chart`) preserves fast loading and prevents giant redundant images from forcing endless scrolling on small screens.
    - **Dynamic Fit Alert:** Clear visual cues explaining Player Edition (Athletic Slim Fit - order 1 size up for loose fit) vs Retro Series (Regular Comfort Fit - standard T-shirt size).
16. **Browser Tab Title Encoding & SEO Clean Slugs (Fixed):**
    - **Broken Character Fix:** Replaced corrupt `\xef\xbf\xbd` (``) in `index.blade.php` title with clean standard hyphen: `RABEZ - Football Jersey Store Bangladesh | Half Sleeve, Full Sleeve & Winter Hoodies`.
    - **SEO Friendly Slugs:** Converted all random-number and ID-suffixed slugs (e.g. `fc-barcelona-2627-home-half-sleeve-player-edition-2` -> `fc-barcelona-26-27-home-half-sleeve-player-edition`, `real-madrid-2627-home-jersey-19` -> `real-madrid-26-27-home-jersey`).
    - **Season Slashes Preserved:** Updated AdminController so slashes in season names (e.g. `26/27`) convert cleanly to `26-27` instead of stripping to `2627`.
    - **Collision-Safe Generator:** Removed random `rand(10, 99)` and ID appending; slug generation now checks database uniqueness cleanly with incremental counters only on duplicate names.
17. **Full-Site Luxury Shopify E-Commerce Icon Redesign (Prestige / Dawn Aesthetic):**
    - **Unified Geometry & Stroke:** Standardized all icons across the platform to a minimalist vector stroke geometry (`stroke-width="1.5"`, `stroke-linecap="round"`, `stroke-linejoin="round"`, and `viewBox="0 0 24 24"`), replacing outdated heavy 2.5px strokes and blocky supermarket icons.
    - **Header & Navigation:**
        - **Boutique Tote Bag:** Replaced square wire shopping cart crate with an arched-handle boutique shopping tote bag (`M16 11V7a4 4 0 0 0-8 0v4M4.5 8.5h15l1 12.5...`).
        - **Curved Wishlist Heart:** Replaced clunky generic heart with a delicate, sensual curved luxury heart outline.
        - **Angled Search Lens:** Replaced straight-stem search icon with a crisp 45° angled stem magnifying glass.
        - **High-Fashion Menu Toggle:** Replaced 3-bar industrial hamburger with a sleek 2-line minimalist toggle.
        - **Top Perk & User Menu:** Sleek delivery flash van, refined chevron down, and 1.5px admin/order/sign-out icons.
    - **Homepage & Shop Grid:**
        - **Editorial CTAs:** Clean line-arrow (`→`) for "Shop Jerseys" and refined squad kit line art for "Custom Name & Number".
        - **Pre-rendered & Dynamic Product Cards:** Upgraded wishlist heart and quick-view eye across both Blade templates and client-side dynamic JavaScript rendering (`cart.js`).
        - **Trust & Delivery Strip:** Replaced all 4 feature icons with luxury Shopify outline icons: Home Delivery (courier van), Premium Fabric (Jacquard sparkle diamond), Size Exchange (circular 2-way exchange loop), and Direct WhatsApp (clean outline).
        - **Duplicate Footer Cleanup:** Eliminated redundant static `<footer>` tags from `index.blade.php` and `shop.blade.php` so all pages cleanly inherit the master storefront layout footer.
    - **Single Product Page (`product.blade.php`):**
        - **Tailor Ruler:** Upgraded "Size Chart" link with an authentic tailor measuring tape icon.
        - **Add to Bag CTA:** Added boutique luxury tote bag icon alongside the price.
        - **Delivery Perks Strip:** Dedicated outline icons for All BD Delivery (van), Payment & COD (shield/card), and Exchange Policy (curved exchange loop).
        - **Rotating Accordion Chevron:** Replaced harsh text entities (`&plus;` / `&minus;`) with a smooth 180° CSS rotating SVG chevron (`.rabez-accordion-arrow`).
    - **Cart Drawer & Checkout:**
        - **Empty State Illustration:** Boutique luxury tote bag line art with zero visual clutter.
        - **Item Delete Action:** Refined SVG outline trash bin replacing clunky red text.
        - **Discount Coupon:** High-end boutique tag with punched hole.
        - **Checkout CTA:** Secure lock shield and editorial forward arrow.
    - **Order Tracker (`track-order.blade.php`):**
        - Upgraded input search lens, empty state icon, courier consignment van, and 5-stage order progress timeline icons.





