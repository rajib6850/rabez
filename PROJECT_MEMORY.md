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
18. **Mobile Navigation Drawer Utilities & Quick Social Row:**
    - **Context:** On mobile/tablet screens `<= 1120px`, topbar right links (`Track Order`, `Facebook Page`, `WhatsApp`, `Sign In`, `Register`) are intentionally hidden to keep the header minimal and prevent horizontal blowout.
    - **Integrated Drawer Utilities (`.rabez-nav-mobile-utilities`):**
        - **Featured Track Order Card:** Full-width glassmorphic card with consignment box SVG icon, crisp white typography, and vibrant green `LIVE` pill badge linking to `/track-order`.
        - **2-Column Quick Support Row:**
            - **WhatsApp:** Direct link (`+880 1612-359652`) with green outline WhatsApp SVG and subtle green surface tint.
            - **Facebook Page:** Direct link (`rabezshop`) with blue outline Facebook SVG.
        - **Responsive Auth Row:**
            - **Guest:** Side-by-side 2-column buttons for **Sign In** (with user profile icon) and **Create Account** (with user plus icon in neon green).
            - **Authenticated:** User greeting card with active dot, user name, role badge (`ADMIN`), direct link to **Admin Panel**, and red **Sign Out** button.
        - **Zero Desktop Clutter:** Strictly hidden on desktop (`@media (min-width: 1121px) { display: none !important; }`).
        - **Safe Mobile Scroll:** Added `max-height: calc(100vh - 75px)` and `overflow-y: auto` to `.rabez-nav` so even on smaller smartphone screens or landscape orientation, the entire menu scrolls smoothly.
19. **Backend Admin Media Gallery Mobile Overhaul (WordPress / Shopify Library Grid):**
    - **Context & Diagnosis:** Previously in `admin/dashboard.blade.php`, `.adm-gallery-grid` used `minmax(180px, 1fr)` and each item rendered a giant card containing full-width photo, product title, file metadata, and Copy/Delete buttons. On mobile screens, this forced a single-column layout where each photo took up 350px+ of vertical space, looking like an e-commerce product feed ("product showcase") rather than an image library. Additionally, the `<section class="adm-panel" data-panel="gallery">` DOM block was duplicated 5 times.
    - **Single Canonical Panel:** Removed the 4 redundant duplicate sections; preserved a single clean panel located logically under the Catalog group.
    - **Mobile-First 3-Column Thumbnail Grid:**
        - Standardized on a compact 3-column square thumbnail grid (`grid-template-columns: repeat(3, 1fr) !important; gap: 8px !important; aspect-ratio: 1/1`) on mobile `<= 640px` (2 columns on `<= 380px`).
        - Removed clunky full-width card text and buttons from the grid view, replacing them with clean photo tiles featuring a subtle multi-select checkbox, green `WEBP` micro badge, and discreet bottom gradient filename label.
    - **Interactive Photo Inspector Sheet (`adm-photo-sheet`):**
        - Tapping any photo tile opens an app-like inspector modal/bottom sheet displaying full-res image preview, filename, dimensions, file size in KB, format, and direct URL input.
        - Includes 1-tap **"Copy URL"** (with instant clipboard toast) and **"Delete"** actions.
        - **Touch-Target Pointer Events Fix:** Added `pointer-events: none` to the desktop action overlay `.adm-gallery-item__actions` and set `display: none !important` on screens `<= 768px`. Previously, an invisible overlay (`opacity: 0`) with z-index 4 covered the thumbnail, causing fingers on touch devices to inadvertently trigger the hidden delete button prompt.

    - **Condensed Mobile Header & Uploader:**
        - Transformed the 3 stacked vertical stat cards into a compact 3-column KPI summary bar (`Total Media`, `Auto WebP`, `EXIF Fix`).
        - Added a streamlined **`📷 Select Photos from Device`** button optimized for mobile cameras and photo pickers.

20. **Mobile App Installation & Direct APK Download System:**
    - **Dual Installation Workflow:**
        - **1-Tap Direct Install (PWA):** Powered by `manifest.json` (with standalone display, OLED pitch-black `#0A0C0B` theme, and 512x512/192x192 PNG icons) and `sw.js` service worker. Automatically captures `beforeinstallprompt` on Android Chrome/Edge/Samsung Internet for 1-tap installation with 0 MB storage. For iOS Safari, provides an intuitive 3-step visual guide (Share ⎋ -> Add to Home Screen ⊞).
        - **Direct Android APK Package:** Standalone 1.7 MB APK package hosted directly at `/downloads/RABEZ-Official.apk` and `/downloads/rabez.apk`. Includes Digital Asset Links verification at `/.well-known/assetlinks.json` for full native chrome-free display.
    - **High-Converting Entry Points:**
        - **Footer Customer Care:** Dedicated entry `Download & Install App` with neon outline smartphone SVG icon and `APK / PWA` badge.
        - **Footer Contact Column:** Direct mobile app download card with `Official Mobile App` header and arrow.
        - **Mobile Navigation Drawer:** Dedicated glassmorphic app banner (`.rabez-nav-mobile-app-card`) with neon smartphone icon, `INSTALL` CTA pill, and instant modal trigger.
        - **Desktop Topbar:** Quick `App Install` pill link beside Track Order.
    - **Interactive App Install Modal (`#rabez-install-modal`):**
        - App-like bottom sheet on mobile (`max-height: 85vh`, `border-radius: 20px 20px 0 0`) and centered modal on desktop.
        - Shows RABEZ app icon with green verified checkmark.
        - Provides two crystal-clear choice cards: "1-Tap Direct Install (Instant, 0 MB)" and "Download Android APK (1.7 MB)" with 3-step sideload instructions.

21. **Mobile Quick View Full Display & Zero Horizontal Scroll Overhaul:**
    - **Full Display Edge-to-Edge Experience:**
        - On mobile devices `<= 768px`, removed the old `20px` backdrop padding that caused the 2-3% empty borders around the modal.
        - Modal now expands to 100% full screen (`width: 100vw !important`, `height: 100dvh !important`, `border-radius: 0 !important`, `border: none !important`), matching modern high-fashion and luxury e-commerce mobile experiences.
    - **Zero Horizontal Scrolling (`overflow-x: hidden`):**
        - Strictly enforced `overflow-x: hidden !important` and `box-sizing: border-box !important` across `.rabez-modal`, `.rabez-qv-layout`, `.rabez-qv-gallery`, `.rabez-qv-summary`, and child elements.
        - Set `grid-template-columns: repeat(3, minmax(0, 1fr))` on trust perks so narrow phone viewports never force layout expansion.
        - Disabled horizontal scrollbars on thumbnail rail (`scrollbar-width: none`).
    - **Floating Persistent Close Control:**
        - Upgraded the close button to a glassmorphic floating circular pill (`position: fixed`, `top: 14px`, `right: 14px`, `z-index: 1000`, `background: rgba(255,255,255,0.94)`, `backdrop-filter: blur(10px)`). The user can dismiss the modal instantly from anywhere in the scroll depth without having to scroll all the way back to the top.
    - **Background Scroll Locking & Keyboard Accessibility:**
        - When Quick View opens, `document.body.style.overflow = "hidden"` prevents the underlying storefront from dragging or shifting.
        - Restored upon closing via close button, backdrop click, or pressing the `Escape` key.

22. **Checkout Page Mobile Layout & Zero Horizontal Scroll Fix:**
    - **Context & Diagnosis:** On mobile screens `<= 768px`, `.rabez-checkout-card` used fixed `padding: 36px` and `.rabez-checkout-grid` used `grid-template-columns: 1fr` without `minmax(0, 1fr)` or `min-width: 0`. Intrinsic input sizes and long button labels caused cards to size at 436px wide, spilling outside the 360px-390px mobile viewports and triggering an unsightly horizontal scrollbar.
    - **Responsive Grid & Card Sizing:**
        - Standardized `.rabez-checkout-grid` to `grid-template-columns: minmax(0, 1fr)`.
        - Added `min-width: 0; max-width: 100%; width: 100%; box-sizing: border-box;` across `.rabez-checkout-card`, `.rabez-form-grid`, `.rabez-form-group`, and all input/textarea elements.
        - Reduced card padding to `20px 16px` on screens `<= 768px` for optimal breathing room and maximum form utility.
    - **Responsive Shipping Flat Card & CTA Buttons:**
        - Converted `.rabez-shipping-flat-card` to responsive column alignment on mobile, preventing flat rate fee badges from overlapping or pushing width out.
        - Allowed flex-wrapping on checkout CTA buttons (`#place_order`, `.js-whatsapp-checkout`) with `white-space: normal` and `word-break: break-word`.
    - **Global Viewport Overflow Prevention:**
        - Added `overflow-x: hidden` to `html` to prevent horizontal elasticity or bounce across all mobile webkit browsers.

23. **Collapsible Coupon Toggle ("Have a Discount Coupon?") in Checkout & Drawer Bag:**
    - **Clean Collapsed Initial State:**
        - On both the **Checkout Page** (`checkout.blade.php`) and the **Cart Drawer / Bag Drawer** (`storefront.blade.php`), the coupon code input field and Apply button are hidden by default (`display: none`).
        - Only the clickable question/link **"Have a Discount Coupon?"** is visible with an official coupon icon and an interactive chevron indicator (`▾`).
        - Renamed Drawer's old text from "Have a Promo Coupon?" to "Have a Discount Coupon?" for complete storewide consistency.
    - **Smooth Expand / Collapse UX:**
        - Clicking the question smoothly reveals the exact current coupon form (uppercase input field, "Apply" button, and error/success message container).
        - Clicking it again collapses the input section, while rotating the chevron icon seamlessly with CSS transitions (`transition: transform 0.25s ease`).
        - Automatically focuses the coupon input field upon expansion for frictionless typing.
    - **Smart Feedback Visibility:**
        - Whenever a user applies a coupon code or encounters an error message (e.g. invalid code or empty input), the coupon fields container automatically forces itself open (`toggleCheckoutCoupon(true)` / `toggleDrawerCoupon(true)`) so messages are never hidden.
        - Applied coupon code and discount row remain visible in the price summary (`Discount (CODE) -৳XX`), complete with "Remove" functionality.

24. **Admin Dashboard "Discount & Promotional Coupons" Button & Mobile Fix:**
    - **Duplicate Plus (+) Sign Removed:**
        - Fixed the "+ New Coupon" button which previously had both an SVG vector plus icon and literal `+` inside text (`[+] + New Coupon`). Removed the redundant text `+` to display a single, crisp vector icon with label: `[+] New Coupon`.
        - Also updated empty table state prompt from `Click "+ New Coupon"` to `Click "New Coupon"`.
    - **Mobile Viewport Overflow Prevention:**
        - Previously on mobile viewports (`<= 640px` and `<= 390px`), fixed `width: 230px` on the search input combined with the button (~140px) demanded 380px+, forcing the button completely out of the viewport on narrow screens.
        - Introduced responsive `.adm-coupons-toolbar`:
            - On screens `<= 640px`: Toolbar expands to `width: 100% !important`, search input uses `flex: 1 1 140px; min-width: 0; width: auto !important;`, and button has `flex-shrink: 0; white-space: nowrap;`. Both fit side-by-side with zero horizontal overflow.
            - On ultra-small screens `<= 380px`: Elements stack vertically at `100% width` with comfortable touch targets.
        - Card header title now uses `flex-wrap: wrap` to allow the active badge to wrap gracefully without blowing out card width.
        - Added `max-height: 92vh; overflow-y: auto;` to coupon modal dialog for mobile screens.

25. **Fresh Store Launch Clean Reset (Zeroing Mock Orders While Preserving All Product Data):**
    - **Product Ecosystem 100% Preserved:**
        - All 17 live products (jerseys, winter hoodies, retro kits), descriptions, high-res images, pricing, and 85 size/stock variants (S, M, L, XL, XXL) remain completely intact.
        - All 5 categories ('Football Jerseys', 'Winter Hoodies', 'Football Boots', 'Tracksuits & Pants', 'Accessories') preserved.
        - All store configuration settings (delivery charge mode, flat rate ৳130 / zone rates ৳70, hotline, WhatsApp number) and admin login credentials preserved.
    - **Clean Wipe of Test & Mock Data:**
        - **Orders & Items:** Completely emptied (`orders = 0`, `order_items = 0`) across both local and live production database (`database.sqlite`).
        - **Dashboard Overview Stats:** Replaced hardcoded demo statistics (`৳8,420` revenue, `42` orders, fake 7-day bars) with dynamic live calculations that cleanly reflect real order status starting at ৳0.
        - **Print Queue:** Converted from static hardcoded rows to a dynamic print manager (`renderPrintQueue()`) showing an empty queue placeholder until orders with custom back print (+৳250) are received.
        - **Recent Orders & Customers:** Clean empty state messaging indicating orders and customers will appear live upon actual customer purchases.
        - **Database Seeder Updated:** Removed mock orders `RBZ-10024` through `RBZ-10027` from `DatabaseSeeder.php` to guarantee future database seeds only initialize product catalogs.
        - **Media Library Cleaned:** Removed one-off test upload image while preserving all official product assets.

26. **Per-Product Custom Name & Number (`has_custom_print`) & Conditional Storefront Coupon Box:**
    - **Selective Custom Name & Number System:**
        - **Admin Controls (`AdminController.php`, `dashboard.blade.php`):** Added `has_custom_print` boolean validation/persistence to `storeProduct()` & `updateProduct()`, added checkboxes to **Add Product** (unchecked by default) and **Edit Product** drawer (`#edit-prod-custom-print`), and added an instant 1-click **"Custom Print: ON / OFF"** toggle button in the Admin Products table.
        - **Database Migration (`2026_11_14_000001_default_false_custom_print_on_products.php`):** Resets existing seeded products cleanly to `has_custom_print = false` so Custom Name & Number appears ONLY on selected products enabled by the admin.
        - **Live Storefront DB Hydration (`AppServiceProvider.php`, `layouts/storefront.blade.php`):** Shared live DB `$storefrontProductsJs`, `$customPrintProducts`, `$customPrintFee`, and `$hasActiveCoupons` via View Composer, ensuring Homepage, Shop Grid, Quick View, and Single Product views always reflect live database settings.
        - **Storefront Enforcement (`product.blade.php`, `index.blade.php`, `cart.js`, `app.js`):**
            - Single Product Page & Quick View Modal render the Custom Name & Number addon ONLY when `has_custom_print` is enabled on that product (Quick View CTA adapts between `View Full Details & Custom Print →` and `View Full Details →`).
            - Homepage Customizer section (`#custom-print`) & nav link dynamically display only customizable products and hide completely if none are enabled.
            - `cart.js` strips custom print fields/fees for non-customizable products on both `addToCart` and `localStorage` cart hydration.
    - **Conditional Coupon Box Visibility (`layouts/storefront.blade.php`, `checkout.blade.php`, `cart.js`, `StorefrontController.php`):**
        - Hidden "Have a Discount Coupon?" toggle/form and discount summary row in both **Cart Drawer** and **Checkout Page** whenever zero active coupons exist in the Admin Dashboard (`Coupon::where('is_active', true)->exists() === false`).
        - Cleared any stale cached coupon in `localStorage` (`rabez_applied_coupon_v1`) when no active coupons exist, and updated `/api/apply-coupon` to reject cleanly when inactive.

27. **Concise Single-Line Mobile Labels for Custom Name & Number and WhatsApp Order Buttons:**
    - Shortened `+ Add Custom Name & Number Print` to `Custom Name & Number` in both Single Product (`product.blade.php`) and Quick View (`cart.js`) alongside mobile font/wrap tuning (`single-product.css`) so the label and `+ ৳ 250` fee always fit on one clean row down to `360px` mobile screens.
    - Shortened `Order Instantly on WhatsApp (+880 1612-359652)` (`product.blade.php`) and `Order Instantly on WhatsApp` (Cart Drawer in `layouts/storefront.blade.php`) to `Order via WhatsApp` (matching Quick View) so WhatsApp CTA buttons remain crisp and single-line across mobile and desktop.
    - Bumped asset versions (`single-product.css?v=2.9`, `cart.js?v=2.9`, `sw.js` `rabez-pwa-v3`).

28. **Direct Visible Coupon Form, Dynamic Active Coupon Check API, and Service Worker Network-First Fix:**
    - **Visible Coupon Form (`layouts/storefront.blade.php`, `checkout.blade.php`):** Uncollapsed the coupon input field and Apply button (`display:block;`) so customers immediately see the code input field and Apply button whenever active coupons exist, rather than hiding it behind a collapsed accordion.
    - **Live Dynamic Active Coupons API (`StorefrontController.php`, `routes/web.php`, `cart.js`):** Added `/api/coupons/active-status` endpoint returning live active coupon existence and count. `cart.js` syncs coupon box visibility in real-time on `DOMContentLoaded` and whenever `openCartDrawer()` is triggered, toggling `.rabez-drawer-coupon` and `.rabez-checkout-coupon` between `block` and `none` without requiring full hard refreshes.
    - **Service Worker Cache Invalidation (`sw.js`):** Removed `"/"` from `STATIC_ASSETS` and enforced network-first on all page navigations (`e.request.mode === "navigate"`), preventing mobile browsers from serving stale HTML when coupons or catalog items change. Bumped PWA cache to `rabez-pwa-v4` and script to `cart.js?v=3.0`.

29. **Coupon Form Default Collapsed State with Tap-to-Open Everywhere:**
    - Set `#drawer-coupon-fields` and `#checkout-coupon-fields` to `display:none;` by default so only the `"Have a Discount Coupon?"` bar with ticket and chevron icons is visible initially when active coupons exist in the dashboard.
    - Enhanced click delegation in `cart.js` so tapping anywhere across the coupon header box or toggle button smoothly expands/collapses the code input and Apply button, while clicks inside the inputs never accidentally trigger collapse.
    - Kept dynamic visibility intact: if 0 active coupons exist in dashboard, the entire coupon section is completely hidden. Bumped asset to `cart.js?v=3.1`.

30. **Shortened Delivery Method Label on Checkout & Product Pages:**
    - Shortened `All Over Bangladesh Delivery (Home Delivery)` on the Checkout delivery method card (`checkout.blade.php`) to `Home Delivery (All Bangladesh)` with subtitle `Flat rate across all 64 districts in Bangladesh`.
    - Shortened `All Over Bangladesh Home Delivery` on Single Product page (`product.blade.php`) to `Home Delivery (All Bangladesh)`.

31. **Removed Duplicate App Install Link from Footer Customer Care:**
    - Removed duplicate `Download & Install App (APK / PWA)` link from the Customer Care column in the footer (`layouts/storefront.blade.php`), keeping the dedicated "Official Mobile App" action button under Direct Hotline & Store.

32. **Dashboard CMS Pages & Multi-Level Navigation Menu Builder (Desktop + Mobile):**
    - **Database & Models (`2026_11_14_000002_create_pages_and_menus_tables.php`, `Page.php`, `MenuItem.php`):**
        - Added `pages` and self-referential `menu_items` (`location`: `header`, `footer_care`, `parent_id`, `page_id`, `type`, `url`, `badge_text`, `target`, `sort_order`, `is_active`) tables with initial default pages (`Privacy Policy`, `Terms & Conditions`, `Return & Exchange Policy`) and starter multi-level header & footer Customer Care menus.
        - Implemented HTML sanitization (`Page::sanitizeHtml`), reserved system slug protection (`Page::RESERVED_SLUGS`), circular hierarchy detection (`MenuItem::wouldCreateCycle`), and dynamic tree resolution (`MenuItem::buildTree`).
    - **Storefront Dynamic Page & Menu Rendering (`StorefrontController.php`, `AppServiceProvider.php`, `routes/web.php`, `storefront/page.blade.php`, `partials/storefront-menu-items.blade.php`):**
        - Registered clean root URLs (`/{slug}` and `/page/{slug}`) at the bottom of `routes/web.php` with admin draft preview (`?preview=1`) and dark RABEZ responsive page template (`storefront/page.blade.php`).
        - Shared `$headerMenuTree`, `$footerCareMenu`, and `$publishedPages` globally via `AppServiceProvider`, updating Header navigation and Footer Customer Care in `layouts/storefront.blade.php`.
    - **Multi-Level Responsive Navigation (`nav-dropdown.css`, `app.js`, `sw.js`):**
        - Built unlimited-depth desktop hover/keyboard dropdowns (`> 1120px`) with invisible hover bridges and automatic viewport right-edge flip detection (`.flip-left`).
        - Built touch-friendly nested mobile accordion menus (`<= 1120px`) inside the slide-down drawer with dedicated expand/collapse chevron toggles, indented level containers, zero horizontal overflow down to `360px`, and safe bottom scroll clearance above the sticky bar.
        - Bumped asset versions (`header-navigation.css?v=2.6`, `nav-dropdown.css?v=1.1`, `app.js?v=2.8`, `sw.js` `rabez-pwa-v5`).
    - **Admin Dashboard CMS UI (`AdminController.php`, `admin/dashboard.blade.php`):**
        - Added **Pages** management panel and slide-out **Page Editor Drawer** with live slug preview, SEO meta fields, publish/draft toggle, and visual Rich Text toolbar (`H2`, `H3`, `P`, `B`, `I`, `U`, lists, blockquote, links, and raw HTML source toggle).
        - Added **Menu Builder** panel for `Header Navigation` and `Footer Customer Care` supporting Link Source (`CMS Page`, `Storefront Preset`, `Custom URL / #`), live parent picker, optional badges, and instant 1-tap `↑`, `↓`, `← Outdent`, `→ Nest`, `+Sub`, `Edit`, and `Delete` actions.

33. **Custom Responsive Confirmation & Alert Popup System (Storefront + Admin Dashboard):**
    - Replaced all 51 plain browser `alert()`, `confirm()`, and `prompt()` dialogs across the entire codebase with themed, responsive modal popups featuring backdrop blur, semantic status icons (`danger`, `warning`, `success`, `info`, `prompt`), `Escape`/`Enter` keyboard handling, focus restoration, and full-width stacked mobile buttons (`<= 480px`).
    - **Storefront (`nav-dropdown.css`, `app.js`, `product.blade.php`, `layouts/storefront.blade.php`, `sw.js`):**
        - Added `window.RABEZ_POPUP = { alert(message, opts), confirm(message, opts), close() }` returning Promises with lazy DOM injection (`#rabez-dialog-backdrop`), updating all 5 checkout/validation alerts in `app.js` and 1 bag-add fallback alert in `product.blade.php`.
        - Bumped storefront assets (`nav-dropdown.css?v=1.3`, `app.js?v=2.9`, `sw.js` `rabez-pwa-v6`) and synced updated files to root `assets/`.
    - **Admin Dashboard (`admin/dashboard.blade.php`):**
        - Built `#adm-dialog-overlay` (`z-index: 110000`) and Promise-based `admAlert()`, `admConfirm()`, and `admPrompt()` helpers with `closeAdmDialog` wired cleanly into `Escape` handling ahead of `closeOverlays()`.
        - Converted all 45 admin dialogs across Orders, Bulk Actions, Media Library, Products, Categories, Customers, Users, Coupons, Custom Print toggle, POS, CMS Pages, Menu Builder, and `.js-rte-link-btn` (preserving/restoring `Selection`/`Range` around `await admPrompt(...)` for WYSIWYG links).

34. **Product Edit & Add — Editable Fabric & Persistent Sizes in Stock:**
    - **Database (`2026_11_14_000003_add_fabric_to_products_table.php`):** Added nullable `fabric` column (`string('fabric', 191)`) to `products` table.
    - **Admin Backend (`AdminController.php`):** Added `syncProductVariants()` shared helper and updated `storeProduct()` & `updateProduct()` to validate and persist `fabric`, `sizes` (in-stock sizes), and `all_sizes` (removing stale sizes cleanly on category switches such as footwear `39–44` vs apparel `S–XXL`).
    - **Admin Dashboard (`admin/dashboard.blade.php`):**
        - Added `Fabric` (`#edit-prod-fabric`) and `Sizes in stock` (`#edit-prod-sizes`) interactive toggle chips to the Edit Product Drawer, wired into `openProductDrawer()`, `#edit-prod-cat` category-change handler, and `saveProductEdit()`.
        - Persisted inline table row size stock toggles (`.js-size`) directly to `POST /admin/products/{id}/update` and wired `#adm-add-form` to submit `#adm-fabric` and `#adm-new-sizes` availability.
        - Fixed `mapProductFromDB()` so marking all sizes out of stock (`inStockSizes.length === 0`) no longer falls back to all sizes in stock.
    - **Storefront Sync (`AppServiceProvider.php`, `StorefrontController.php`, `storefront/product.blade.php`, `cart.js`, `layouts/storefront.blade.php`, `sw.js`):**
        - Exposed `fabric` and `allSizes` (keeping `sizes` accurately empty when all variants are out of stock) and loaded `variants` on related products.
        - Extracted shared `buildSizeSwatchesHTML()` in `cart.js` to render out-of-stock swatches cleanly as `disabled` + `.is-out-of-stock`, displayed product `fabric` in Single Product summary, Quick View, and Fabric Composition & Care tab, and guarded Add to Bag / WhatsApp orders with `RABEZ_POPUP.alert()` when no sizes are in stock.
        - Bumped `cart.js?v=3.2`, `sw.js` `rabez-pwa-v7`, synced root `assets/js/cart.js`, and added automated coverage (`ProductEditFabricAndSizesTest.php`).















