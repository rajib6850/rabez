# RABEZ — Official Football Jersey E-Commerce Platform Bangladesh

![RABEZ Banner](rabez-laravel/public/assets/images/hero-messi-bg.jpg)

**RABEZ** is a modern, dark-themed football jersey and sportswear e-commerce application built with **Laravel 11**, designed specifically for the Bangladesh market. It features a responsive storefront, front/back jersey preview flipper, custom player name & number heat-press addon, slide-over dark AJAX cart with in-drawer coupon redemption, multi-district delivery system, and an administrative control panel with an automated WebP image optimizer and coupon campaign manager.

---

## ⚡ Quick Start

```bash
# 1. Clone repository
git clone <repo-url>
cd <project-folder>/rabez-laravel

# 2. Install PHP dependencies
composer install

# 3. Environment Setup
cp .env.example .env
php artisan key:generate

# 4. Migrate and Seed Database
php artisan migrate --seed

# 5. Start Application Server
php artisan serve --port=8000
```

- **Storefront:** [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Admin Dashboard:** [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)
  - **Email:** `admin@rabez.shop`
  - **Password:** `password`

---

## 🚀 Key Features

### Storefront Experience
- **Jersey Front/Back Flip:** Seamlessly flips between front view (`image`) and back view (`gallery[0]`) on hover and touch.
- **Single Product Page:**
  - Thumbnails horizontal touch scroll gallery on mobile.
  - Interactive Size Chart Modal with chest/length measurements in inches (Player Edition & Retro).
  - Custom Name & Number print toggle (+৳250) with auto-calculated pricing.
  - High-readability dark specifications tabs (Desktop) and accordions (Mobile) with generous 1.85 line-height.
  - Direct WhatsApp quick order button with pre-filled order text.
- **Slide-Over Dark Cart Drawer:**
  - In-drawer coupon application with instant discount deduction.
  - In-drawer quantity adjustments (`+` and `−`).
  - Free delivery goal progress bar (৳3,500 goal).
- **Checkout & Tracking:**
  - Round-figure delivery charge (৳130 Flat Rate across all 64 districts) or Zone-based delivery (৳70 Inside / ৳130 Outside Dhaka).
  - Cash on Delivery (COD), bKash, and Nagad payment options.
  - Live order tracking timeline by order number & phone.

### Admin Command Center
- **Overview:** Daily revenue, open orders, COD with courier, and recent orders feed.
- **Products Catalog:** Stock management by size (S, M, L, XL, XXL), pricing, badges, short description, and long description.
- **Auto-WebP Media Engine:** Automatically converts high-MB iPhone camera and DSLR photos into lossless WebP (<250KB) and fixes upside-down EXIF orientation.
- **Dedicated Discounts Panel:**
  - Placed under "Users" with 4 live statistical KPI cards (Active Coupons, Total Campaigns, Discount Mix, Average Min Spend).
  - Real-time search filter and one-click code copy.
  - Create, edit, toggle active/inactive, and delete coupon codes.
- **Delivery & Courier Settings:**
  - 100% full-width layout with zero empty margins.
  - Toggle between Nationwide Flat Rate (৳130) and Zone-Based fees.
  - Steadfast Courier & Pathao Merchant API credentials.
  - Meta Pixel & Server-Side Conversions API (CAPI) tokens.

---

## 📖 Complete Documentation
For the full architectural specification, database schemas, and revisions log, see [PROJECT_MEMORY.md](PROJECT_MEMORY.md).

