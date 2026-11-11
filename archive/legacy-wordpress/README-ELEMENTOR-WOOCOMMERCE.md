# RABEZ Jersey Store – Elementor & WooCommerce Architecture Guide

This HTML/CSS/JS design is tailored specifically for **https://rabez.shop/** using only the official **RABEZ** logo and brand color palette (`#04F404` Electric Pitch Green, `#0A0C0B` Obsidian Black, `#FFFFFF` Crisp White, `#3E2723` Espresso accent).

---

## 1. Folder Structure (1:1 Elementor Widget & WooCommerce Mapping)

```text
website design/
├── index.html                                  # Main Storefront (10 modular Elementor Widget sections)
├── shop.html                                   # WooCommerce Archive / Shop template (archive-product.php)
├── product.html                                # WooCommerce Variable Single Product (single-product.php)
├── checkout.html                               # Express 1-Step Bangladesh COD Checkout (form-checkout.php)
├── assets/
│   ├── css/
│   │   ├── 01-variables.css                    # Maps --rabez-* tokens to --e-global-color-* & --wc-*
│   │   ├── 02-base-reset.css                   # Typography (Barlow Condensed + Red Hat Text) & buttons
│   │   ├── 03-woocommerce-compat.css           # Standard WooCommerce classes (.woocommerce ul.products, .price, etc.)
│   │   └── widgets/                            # Isolated per-widget CSS files for wp_register_style()
│   │       ├── widget-header-navigation.css
│   │       ├── widget-hero-banner.css
│   │       ├── widget-club-filter-bar.css
│   │       ├── widget-product-grid.css
│   │       ├── widget-bento-collections.css
│   │       ├── widget-jersey-customizer.css
│   │       ├── widget-size-guide.css
│   │       ├── widget-single-product.css
│   │       ├── widget-cart-checkout.css
│   │       └── widget-footer.css
│   ├── js/
│   │   ├── products-data.js                    # Synced with rabez.shop product IDs, SKUs & BDT prices
│   │   ├── woocommerce-cart.js                 # Variable size swatches, Mini-Cart drawer & WhatsApp checkout
│   │   └── elementor-widgets.js                # Hooks into elementorFrontend.hooks.addAction()
│   └── images/
│       ├── brand/                              # Official RABEZ logo PNG, circular R emblem & clean SVGs
│       └── products/                           # Studio wooden-slat hanger product photography
├── elementor-widgets/
│   ├── 01-header-nav.html                      # Isolated HTML widget partials
│   ├── 02-hero-showcase.html
│   ├── 03-club-category-filter.html
│   ├── 04-woo-product-card.html
│   ├── 05-sleeve-bento-grid.html
│   ├── 06-custom-name-number.html
│   ├── 07-size-guide-table.html
│   └── rabez-elementor-widget-boilerplate.php  # Native Elementor PHP Widget class
└── woocommerce-templates/
    └── content-product.php                     # Drop into yourtheme/woocommerce/content-product.php
```

---

## 2. How to Convert to Elementor & WooCommerce

1. **Global Styles (`01-variables.css`)**:
   - In Elementor **Site Settings → Global Colors**, set:
     - Primary: `#04F404`
     - Secondary: `#0A0C0B`
     - Text: `#3D4340`
     - Accent: `#3E2723`
   - In **Global Fonts**, set Primary Heading to `Barlow Condensed` (700/800 Uppercase) and Body Text to `Red Hat Text` (400/600/700).

2. **WooCommerce Variable Products (`content-product.php`)**:
   - Copy `woocommerce-templates/content-product.php` into `wp-content/themes/your-child-theme/woocommerce/content-product.php`.
   - Enqueue `assets/css/03-woocommerce-compat.css` and `assets/css/widgets/widget-product-grid.css`.
   - Your variable jersey products (`Half Sleeve` ৳1,150 & `Full Sleeve` ৳1,300) will automatically display clickable `S / M / L / XL / XXL` swatches right on the shop grid!

3. **Elementor Custom Widgets**:
   - Use `elementor-widgets/rabez-elementor-widget-boilerplate.php` to register each section in `assets/css/widgets/` as a drag-and-drop Elementor widget.

