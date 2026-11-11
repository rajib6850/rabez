# RABEZ (`rabez.shop`) — Master Architecture & Implementation Plan
**Stack:** Laravel + Livewire 3 / SPA Mode (100% No-Reload Website & Mobile App) + MySQL + REST API  
**Store Location:** Road-10, College Avenue, Barishal | **Hotline/WhatsApp:** `+8801612359652`

---

## 1. Core Vision & Goals
1. **100% No-Loading (Zero Page Reload / SPA) Experience:**
   - Instant page transitions (`Home` ↔ `Shop` ↔ `Product` ↔ `Checkout` ↔ `Admin App`) using **Laravel Livewire 3 (`wire:navigate` SPA Mode)** + instant client-side state so the website and mobile app never flash white or reload the browser.
2. **Multi-Category E-Commerce (Jersey Now → All Sportswear Later):**
   - **Current Focus:** Football Jerseys (`Half Sleeve`, `Full Sleeve`, `Player Edition`, `Fan Edition`, `Retro Edition`, plus `+৳250 Custom Name & Number Print`).
   - **Future Expansion Ready:** Football Boots, Tracksuits, Joggers/Trousers, Kits for Teams, Socks, Footballs, and Casual Streetwear—each with its own size system and attribute rules.
2. **100% Mobile-Controllable Business (Desktop + Mobile App Parity):**
   - Every task that can be done on a computer—uploading products, managing stock by size, checking customer fake-order history, booking Steadfast/Pathao courier, printing invoices, and checking daily sales—must work effortlessly from a **Mobile App interface** on Android & iPhone.
3. **High-Speed Storefront for Facebook/Instagram Ads:**
   - Sub-1-second page loads inside the Facebook Mobile In-App Browser with full **Meta Pixel + Server-Side Conversion API (CAPI)** tracking.

---

## 2. System Architecture (Laravel + Mobile App Ready)

```
┌──────────────────────────────────────────────────────────────────────┐
│                   RABEZ UNIFIED LARAVEL PLATFORM                     │
├───────────────────────┬──────────────────────┬───────────────────────┤
│  CUSTOMER STOREFRONT  │  MOBILE & WEB ADMIN  │   REST API (/api/v1)  │
│  (Blade + Fast JS +   │  (Installable PWA    │   (Ready for Future   │
│   Installable PWA)    │   Bottom-Nav App)    │    Flutter/RN App)    │
└───────────┬───────────┴──────────┬───────────┴───────────┬───────────┘
            │                      │                       │
            ▼                      ▼                       ▼
┌──────────────────────────────────────────────────────────────────────┐
│        LARAVEL CORE (Products, Variants, Orders, Print Queue)        │
├───────────────────────┬──────────────────────┬───────────────────────┤
│   Steadfast / Pathao  │   BD Courier Fraud   │   Meta Pixel + CAPI   │
│   1-Tap Booking API   │   Success % Checker  │   & WhatsApp Alerts   │
└───────────────────────┴──────────────────────┴───────────────────────┘
```

---

## 3. Database & Multi-Product Schema Plan

### A. `categories` (Supports Jerseys + Future Products)
- `id`, `name`, `slug`, `icon`, `is_active`, `sort_order`
- `product_type`:
  - `jersey` (Shows Sleeve Filter, Edition Badge, Player/Retro Size Chart, Custom Name/Number Print option)
  - `footwear` (Shows Shoe Sizes `39, 40, 41, 42, 43, 44`, hides Jersey Print option)
  - `apparel` (Tracksuits, Trousers, T-Shirts — Sizes `S, M, L, XL, XXL`, custom size chart)
  - `accessory` (Footballs, Socks, Shin Guards, Bags — Free Size or Variant Size)

### B. `brands_or_clubs`
- `id`, `name`, `slug`, `logo_path`, `type` (`club`, `national_team`, `sports_brand`)
- Supports current football clubs (Real Madrid, Barcelona, Argentina, Arsenal, Man United, Bayern, Liverpool, Bangladesh) and future sports brands (Nike, Adidas, Puma).

### C. `products` & `product_variants`
- **`products`**:
  - `id`, `category_id`, `club_id`, `name`, `slug`, `sku`
  - `regular_price`, `sale_price`
  - `sleeve_type` (`half`, `full`, `none`)
  - `edition` (`player`, `fan`, `retro`, `standard`)
  - `size_chart_type` (`player_chart`, `retro_chart`, `footwear_chart`, `none`)
  - `allow_custom_print` (`boolean`) & `custom_print_price` (default `250`)
  - `badge_text` (`BEST SELLER`, `NEW DROP`, `RETRO 99/00`, `HOT`), `badge_style` (`neon`, `dark`, `sale`, `retro`)
  - `thumbnail`, `gallery_images` (JSON), `short_description`, `is_featured`, `status`
- **`product_variants` (Size-wise Stock Control)**:
  - `id`, `product_id`, `size_label` (`S`, `M`, `L`, `XL`, `XXL` or `40`, `41`, `42`), `stock_qty`, `price_override`

### D. `orders` & `order_items`
- **`orders`**:
  - `id`, `order_number` (e.g., `RBZ-10024`), `customer_name`, `customer_phone`, `customer_address`, `delivery_zone` (`inside_dhaka` ৳70 / `outside_dhaka` ৳130 / `barishal_pickup`)
  - `subtotal`, `shipping_cost`, `discount_amount`, `grand_total`
  - `payment_method` (`cod`, `bkash`, `nagad`), `payment_status`
  - `order_status` (`pending`, `confirmed`, `printing`, `shipped`, `delivered`, `cancelled`, `returned`)
  - `courier_name` (`steadfast`, `pathao`, `redx`), `courier_tracking_code`, `fraud_score_summary`
  - `order_source` (`website`, `whatsapp`, `facebook_manual`)
- **`order_items`**:
  - `id`, `order_id`, `product_id`, `product_name`, `size`, `qty`, `unit_price`
  - `custom_print_name` (e.g., `MESSI`), `custom_print_number` (e.g., `10`), `custom_print_fee` (`250`)

---

## 4. Mobile-First Admin App Features (Phone = Computer Parity)

The Admin Panel will be built as an **Installable Mobile Web App (PWA)** + **Responsive Desktop Dashboard** with a sticky mobile bottom bar:

1. **📦 Orders & 1-Tap Actions (Mobile Screen 1):**
   - Instant view of `New`, `Confirmed`, `Custom Print Queue`, and `Shipped` orders.
   - **1-Tap Call / WhatsApp Customer** button directly on each order card.
   - **Customer Fraud Check Badge:** Automatically shows the customer phone number's previous parcel delivery success rate (e.g., `92% Delivered | 12/13 Parcels`) before you dispatch.
   - **1-Tap Send to Steadfast / Pathao:** Generates consignment ID & tracking link directly from the phone.
   - **Custom Print Queue View:** A dedicated list showing only jerseys that need Name & Number heat-press printing (`Jersey + Size + Back Name + Back Number`) for the printing staff.

2. **👕 Quick Product Upload from Phone Camera (Mobile Screen 2):**
   - Pick photos directly from phone camera/gallery (auto-compressed & converted to WebP).
   - Select Category (`Jersey`, `Boots`, `Tracksuit`, etc.), Club, Sleeve, Price, and toggle available sizes (`S, M, L, XL, XXL`).
   - **1-Tap Stock Toggle:** Quickly turn any size `In Stock` / `Out of Stock` from mobile without opening a complex form.

3. **➕ Manual / Phone / Facebook Order Entry (Mobile Screen 3):**
   - If a customer orders via Facebook Page inbox or WhatsApp (`+8801612359652`), admins can enter the order in 15 seconds from their phone so stock, invoice, and courier booking stay in one place.

4. **📊 Daily Sales & Stock Alerts (Mobile Screen 4):**
   - Today's Orders, Total Revenue, Pending COD balance, and Low-Stock size alerts.

---

## 5. Phased Development Roadmap

- **Phase 1: Complete Storefront UI & Mobile Admin App UI Prototype**
  - Finalize all storefront pages (`index.html`, `shop.html`, `product.html`, `checkout.html`).
  - Design the interactive **Mobile-First Admin App UI (`admin.html`)** so you can test the mobile & desktop admin experience visually before backend coding.
- **Phase 2: Laravel Project Setup & Multi-Category Database**
  - Initialize Laravel project, migrations, models, and seed existing 15+ RABEZ jerseys & official size charts.
- **Phase 3: Storefront Blade Integration & Dynamic Cart/Checkout**
  - Convert HTML templates into fast Laravel Blade components with live database filtering, search, wishlist, and COD checkout.
- **Phase 4: Mobile Admin Panel, Print Queue & Courier/Fraud API**
  - Build the Admin controllers, mobile camera image uploader, custom print queue, BD Courier fraud checker, and Steadfast/Pathao 1-click booking.
- **Phase 5: PWA Mobile App Packaging, Meta Pixel/CAPI & Live Deployment**
  - Add `manifest.json` & Service Workers for "Add to Home Screen" mobile app installation (both Storefront & Admin App) and deploy to `rabez.shop`.

