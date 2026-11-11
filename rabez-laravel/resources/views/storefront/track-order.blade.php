@extends('layouts.storefront')

@section('title', 'Track Your Jersey Order & Invoice - RABEZ Official Store Bangladesh')

@push('styles')
  <style>
    .rabez-track-hero {
      background:
        radial-gradient(circle at 82% 18%, rgba(4, 244, 4, 0.16), transparent 42%),
        radial-gradient(circle at 14% 85%, rgba(4, 244, 4, 0.06), transparent 36%),
        #0A0C0B;
      color: #FFFFFF;
      padding: 54px 0 56px;
      border-bottom: 1px solid #1E2421;
    }
    .rabez-track-search-box {
      background: #FFFFFF;
      border: 1px solid #DCE3DF;
      border-radius: 16px;
      padding: 24px 26px;
      box-shadow: 0 18px 42px rgba(10, 12, 11, 0.08);
      margin-top: -34px;
      position: relative;
      z-index: 10;
    }
    .rabez-track-form {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 12px;
    }
    .rabez-track-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .rabez-track-input-wrap svg {
      position: absolute;
      left: 16px;
      width: 18px;
      height: 18px;
      color: #6B7570;
      pointer-events: none;
    }
    .rabez-track-input {
      width: 100%;
      height: 50px;
      padding: 0 16px 0 44px;
      border: 1.5px solid #CED7D2;
      border-radius: 10px;
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--rabez-text-main);
      background: #F9FBFA;
      transition: all 0.18s ease;
    }
    .rabez-track-input:focus {
      outline: none;
      background: #FFFFFF;
      border-color: #0A0C0B;
      box-shadow: 0 0 0 3px rgba(4, 244, 4, 0.18);
    }
    .rabez-demo-pills {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 14px;
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--rabez-text-muted);
    }
    .rabez-demo-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 12px;
      background: #F3F6F4;
      border: 1px solid #DCE3DF;
      border-radius: 99px;
      font-weight: 700;
      color: #1A211E;
      cursor: pointer;
      transition: all 0.16s ease;
    }
    .rabez-demo-pill:hover,
    .rabez-demo-pill.is-active {
      background: #0A0C0B;
      color: var(--rabez-neon);
      border-color: #0A0C0B;
    }

    /* Order Status Card & 5-Step Connected Timeline */
    .rabez-track-card {
      background: #FFFFFF;
      border: 1px solid #DCE3DF;
      border-radius: 16px;
      padding: 30px;
      margin-top: 24px;
      box-shadow: 0 12px 32px rgba(10, 12, 11, 0.05);
    }
    .rabez-track-header {
      display: flex;
      flex-wrap: wrap;
      align-items: flex-start;
      justify-content: space-between;
      gap: 16px;
      padding-bottom: 22px;
      border-bottom: 1px solid #E6ECE8;
    }
    .rabez-track-header__meta {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 6px;
    }
    .rabez-track-id-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 11px;
      border-radius: 6px;
      background: #0A0C0B;
      color: var(--rabez-neon);
      font-size: 0.75rem;
      font-weight: 800;
      letter-spacing: 0.05em;
      text-transform: uppercase;
    }
    .rabez-track-date-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 10px;
      border-radius: 6px;
      background: #F2F5F3;
      color: #4A544F;
      font-size: 0.75rem;
      font-weight: 700;
    }

    .rabez-track-steps {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 14px;
      margin: 26px 0;
      position: relative;
    }
    .rabez-track-step {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      text-align: left;
      padding: 16px;
      border-radius: 12px;
      background: #F8FAF9;
      border: 1px solid #E2E8E5;
      position: relative;
      transition: all 0.2s ease;
    }
    .rabez-track-step.is-done {
      background: #F2FDF2;
      border-color: #9CE69C;
    }
    .rabez-track-step.is-current {
      background: #0A0C0B;
      border-color: var(--rabez-neon);
      color: #FFFFFF;
      box-shadow: 0 12px 26px rgba(4, 244, 4, 0.16);
    }
    .rabez-track-step__top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      margin-bottom: 12px;
    }
    .rabez-track-step__dot {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 0.84rem;
      background: #E5EBE8;
      color: #4A524E;
    }
    .rabez-track-step__dot svg {
      width: 18px;
      height: 18px;
    }
    .rabez-track-step.is-done .rabez-track-step__dot {
      background: var(--rabez-neon);
      color: #080A09;
    }
    .rabez-track-step.is-current .rabez-track-step__dot {
      background: var(--rabez-neon);
      color: #080A09;
      box-shadow: 0 0 0 4px rgba(4, 244, 4, 0.2);
    }
    .rabez-track-step__tag {
      font-size: 0.65rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      padding: 2px 7px;
      border-radius: 99px;
      background: rgba(10, 12, 11, 0.06);
      color: #5C6661;
    }
    .rabez-track-step.is-done .rabez-track-step__tag {
      background: #D7F9D7;
      color: #056805;
    }
    .rabez-track-step.is-current .rabez-track-step__tag {
      background: rgba(4, 244, 4, 0.16);
      color: var(--rabez-neon);
    }
    .rabez-track-step__title {
      font-size: 0.86rem;
      font-weight: 800;
      margin-bottom: 3px;
    }
    .rabez-track-step__sub {
      font-size: 0.73rem;
      color: var(--rabez-text-muted);
      line-height: 1.35;
    }
    .rabez-track-step.is-current .rabez-track-step__sub {
      color: #B4BDB8;
    }

    /* Courier Consignment Banner */
    .rabez-courier-box {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 20px 24px;
      background: linear-gradient(135deg, #0A0C0B 0%, #141917 100%);
      color: #FFFFFF;
      border: 1px solid #232B27;
      border-left: 4px solid var(--rabez-neon);
      border-radius: 12px;
      margin-bottom: 24px;
    }
    .rabez-courier-box__left {
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .rabez-courier-box__icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: rgba(4, 244, 4, 0.12);
      border: 1px solid rgba(4, 244, 4, 0.3);
      color: var(--rabez-neon);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .rabez-courier-box__icon svg {
      width: 22px;
      height: 22px;
    }

    /* Invoice Grid & Product Thumbnails */
    .rabez-invoice-grid {
      display: grid;
      grid-template-columns: 1.35fr 1fr;
      gap: 22px;
    }
    .rabez-invoice-panel {
      background: #F8FAF9;
      border: 1px solid #E2E8E5;
      border-radius: 12px;
      padding: 22px;
    }
    .rabez-invoice-panel__head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-bottom: 12px;
      margin-bottom: 12px;
      border-bottom: 1px solid #E2E8E5;
    }
    .rabez-invoice-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      padding: 12px;
      background: #FFFFFF;
      border: 1px solid #E6ECE8;
      border-radius: 10px;
      margin-bottom: 10px;
    }
    .rabez-invoice-item__left {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
    }
    .rabez-invoice-item__thumb {
      width: 48px;
      height: 54px;
      border-radius: 8px;
      object-fit: cover;
      background: #F0F3F1;
      border: 1px solid #DCE3DF;
      flex-shrink: 0;
    }
    .rabez-invoice-item__title {
      font-size: 0.88rem;
      font-weight: 800;
      color: #0D110F;
      line-height: 1.3;
    }
    .rabez-invoice-item__meta {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 6px;
      margin-top: 4px;
      font-size: 0.75rem;
      color: #5E6863;
      font-weight: 600;
    }
    .rabez-invoice-item__print {
      display: inline-flex;
      align-items: center;
      padding: 2px 7px;
      border-radius: 4px;
      background: #E8FBE8;
      color: #056805;
      font-weight: 800;
      font-size: 0.7rem;
    }

    @media (max-width: 960px) {
      .rabez-track-steps {
        grid-template-columns: 1fr;
        gap: 10px;
      }
      .rabez-track-step {
        flex-direction: row;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
      }
      .rabez-track-step__top {
        width: auto;
        margin-bottom: 0;
      }
      .rabez-track-step__tag {
        display: none;
      }
      .rabez-invoice-grid {
        grid-template-columns: 1fr;
      }
    }
    @media (max-width: 600px) {
      .rabez-track-form {
        grid-template-columns: 1fr;
      }
      .rabez-track-card {
        padding: 20px 16px;
      }
      .rabez-track-search-box {
        padding: 18px 16px;
      }
    }
    @media print {
      .rabez-topbar, .rabez-header, .rabez-track-search-box, .rabez-footer, .rabez-mobile-bar, .js-no-print {
        display: none !important;
      }
      .rabez-track-card {
        border: none;
        box-shadow: none;
        padding: 0;
      }
    }
  </style>
@endpush

@section('content')
  <section class="rabez-track-hero">
    <div class="rabez-container">
      <div class="rabez-eyebrow" style="color:#04F404;">Live Courier &amp; Custom Print Tracker</div>
      <h1 class="rabez-section-title" style="color:#FFFFFF; margin-bottom:8px;">Track Your Order &amp; Digital Invoice</h1>
      <p style="color:#A3ACA7; font-size:0.94rem; max-width:580px;">
        Enter your Order ID (e.g. <strong>RBZ-10024</strong>) or Mobile Number to check real-time heat-press printing &amp; Steadfast/Pathao courier delivery status with zero page reload.
      </p>
    </div>
  </section>

  <main class="rabez-section rabez-section--sm rabez-section--alt" style="padding-top:0;">
    <div class="rabez-container">
      <!-- Instant No-Reload Search Box -->
      <div class="rabez-track-search-box">
        <form class="rabez-track-form js-track-order-form">
          <div class="rabez-track-input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" class="rabez-track-input js-track-query" value="RBZ-10024" placeholder="Enter Order ID (e.g. RBZ-10024) or Mobile Number (017XXXXXXXX)..." aria-label="Order ID or Phone Number">
          </div>
          <button type="submit" class="rabez-btn rabez-btn--primary">Track Instantly</button>
        </form>
        <div class="rabez-demo-pills">
          <span>Instant Sample Orders:</span>
          <button type="button" class="rabez-demo-pill is-active js-demo-order-btn" data-order="RBZ-10024">#RBZ-10024 &bull; Steadfast Courier</button>
          <button type="button" class="rabez-demo-pill js-demo-order-btn" data-order="RBZ-10025">#RBZ-10025 &bull; Custom Print Queue</button>
          <button type="button" class="rabez-demo-pill js-demo-order-btn" data-order="RBZ-10026">#RBZ-10026 &bull; Delivered</button>
        </div>
      </div>

      <!-- Dynamic Order Result Container -->
      <div id="track-result-root"></div>
    </div>
  </main>

@endsection

@push('scripts')
<script>
  <script src="assets/js/app.js"></script>
  <script>
    (function () {
      const DEFAULT_ORDERS = [
        {
          id: "RBZ-10024",
          date: "Today, 11:15 AM",
          customer: "Tanvir Ahmed",
          phone: "01712345678",
          address: "House 14, Road 6, Dhanmondi, Dhaka",
          zone: "Inside Dhaka (&#2547;70)",
          statusStep: 4,
          statusLabel: "In Transit – Steadfast Courier",
          courier: "Steadfast Courier",
          consignmentId: "SF-88492014",
          subtotal: 2600,
          shipping: 70,
          total: 2670,
          items: [
            {
              name: "Real Madrid 26/27 Home Half Sleeve Jersey",
              size: "L",
              qty: 1,
              price: 1150,
              print: "",
              img: "assets/images/products/real-madrid-home-hs.jpg"
            },
            {
              name: "Messi #10 Barca Ã— Argentina 'The Best' Winter Hoodie",
              size: "L",
              qty: 1,
              price: 1450,
              print: "",
              img: "assets/images/products/messi-barca-arg-hoodie.jpg"
            }
          ]
        },
        {
          id: "RBZ-10025",
          date: "Today, 10:05 AM",
          customer: "Mehedi Hasan",
          phone: "01819223344",
          address: "Sadhur More, Boalia, Rajshahi",
          zone: "Outside Dhaka (&#2547;130)",
          statusStep: 3,
          statusLabel: "Custom Name & Number Heat-Press Printing",
          courier: "Pathao Parcel (Pending Dispatch)",
          consignmentId: "PTH-551209",
          subtotal: 1400,
          shipping: 130,
          total: 1530,
          items: [
            {
              name: "Real Madrid 26/27 Home Half Sleeve Jersey",
              size: "XL",
              qty: 1,
              price: 1400,
              print: "BELLINGHAM #5 (+&#2547;250)",
              img: "assets/images/products/real-madrid-home-hs.jpg"
            }
          ]
        },
        {
          id: "RBZ-10026",
          date: "Yesterday, 4:30 PM",
          customer: "Sabbir Rahman",
          phone: "01612359652",
          address: "Road-10, College Avenue, Barishal",
          zone: "Barishal Express (&#2547;70)",
          statusStep: 5,
          statusLabel: "Delivered & COD Paid",
          courier: "Steadfast Courier",
          consignmentId: "SF-88410082",
          subtotal: 1300,
          shipping: 70,
          total: 1370,
          items: [
            {
              name: "Messi Celebration Kit Full Sleeve Jersey",
              size: "M",
              qty: 1,
              price: 1300,
              print: "",
              img: "assets/images/products/messi-celebration-fs.jpg"
            }
          ]
        }
      ];

      function getAllOrders() {
        try {
          const saved = JSON.parse(localStorage.getItem("rabez_orders_v1") || "[]");
          return [...saved, ...DEFAULT_ORDERS];
        } catch (e) {
          return DEFAULT_ORDERS;
        }
      }

      const STEP_ICONS = {
        1: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>',
        2: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
        3: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"></path></svg>',
        4: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>',
        5: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"></path></svg>'
      };

      function renderOrder(query) {
        const clean = (query || "").trim().toUpperCase();
        const orders = getAllOrders();
        const match =
          orders.find((o) => o.id.toUpperCase() === clean || o.phone.includes(clean)) || orders[0];

        const steps = [
          { num: 1, title: "Order Placed", sub: match.date },
          { num: 2, title: "Confirmed", sub: "Stock Verified" },
          { num: 3, title: "Custom Print & Pack", sub: "QC Checked" },
          { num: 4, title: "In Courier", sub: match.courier },
          { num: 5, title: "Delivered", sub: "Cash on Delivery" }
        ];

        const stepsHTML = steps
          .map((s) => {
            const stateClass =
              s.num < match.statusStep
                ? "is-done"
                : s.num === match.statusStep
                ? "is-current"
                : "";
            const stateLabel =
              s.num < match.statusStep
                ? "Done"
                : s.num === match.statusStep
                ? "Live"
                : "Next";
            return `
              <div class="rabez-track-step ${stateClass}">
                <div class="rabez-track-step__top">
                  <span class="rabez-track-step__dot">${STEP_ICONS[s.num]}</span>
                  <span class="rabez-track-step__tag">${stateLabel}</span>
                </div>
                <div>
                  <div class="rabez-track-step__title">${s.title}</div>
                  <div class="rabez-track-step__sub">${s.sub}</div>
                </div>
              </div>
            `;
          })
          .join("");

        const itemsHTML = match.items
          .map(
            (item) => `
            <div class="rabez-invoice-item">
              <div class="rabez-invoice-item__left">
                <img src="${item.img || "assets/images/products/real-madrid-home-hs.jpg"}" alt="${item.name}" class="rabez-invoice-item__thumb">
                <div>
                  <div class="rabez-invoice-item__title">${item.name}</div>
                  <div class="rabez-invoice-item__meta">
                    <span>Size: <strong>${item.size}</strong></span>
                    <span>&bull;</span>
                    <span>Qty: <strong>${item.qty}</strong></span>
                    ${item.print ? `<span class="rabez-invoice-item__print">Print: ${item.print}</span>` : ""}
                  </div>
                </div>
              </div>
              <strong style="font-size:0.94rem; white-space:nowrap;">&#2547; ${(item.price * item.qty).toLocaleString("en-IN")}</strong>
            </div>
          `
          )
          .join("");

        document.getElementById("track-result-root").innerHTML = `
          <div class="rabez-track-card">
            <div class="rabez-track-header">
              <div>
                <div class="rabez-track-header__meta">
                  <span class="rabez-track-id-pill">Invoice #${match.id}</span>
                  <span class="rabez-track-date-pill">${match.date}</span>
                </div>
                <h2 style="font-size:1.7rem; margin-top:4px;">${match.statusLabel}</h2>
              </div>
              <div class="js-no-print" style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="button" class="rabez-btn rabez-btn--outline rabez-btn--sm" onclick="window.print()">
                  Print / Save PDF Invoice
                </button>
                <a href="https://api.whatsapp.com/send/?phone=8801612359652&text=Order%20ID:%20${match.id}" target="_blank" rel="noopener" class="rabez-btn rabez-btn--dark rabez-btn--sm">
                  WhatsApp Support
                </a>
              </div>
            </div>

            <div class="rabez-track-steps">
              ${stepsHTML}
            </div>

            <div class="rabez-courier-box">
              <div class="rabez-courier-box__left">
                <span class="rabez-courier-box__icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                  </svg>
                </span>
                <div>
                  <div style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.08em; color:#04F404; font-weight:800;">
                    Official Courier Consignment
                  </div>
                  <div style="font-size:1.05rem; font-weight:800; margin-top:2px;">
                    ${match.courier} &bull; Consignment ID: <span style="color:#04F404;">${match.consignmentId}</span>
                  </div>
                </div>
              </div>
              <div style="font-size:0.88rem; color:#D2D8D4;">
                Payable on Delivery (COD): <strong style="color:#04F404; font-size:1.12rem;">&#2547; ${match.total.toLocaleString("en-IN")}</strong>
              </div>
            </div>

            <div class="rabez-invoice-grid">
              <div class="rabez-invoice-panel">
                <div class="rabez-invoice-panel__head">
                  <h3 style="font-size:1.12rem;">Ordered Items (${match.items.length})</h3>
                  <span style="font-size:0.74rem; font-weight:700; color:#5A645F;">QC Verified</span>
                </div>
                ${itemsHTML}
                <div style="display:grid; gap:7px; padding-top:12px; font-size:0.88rem;">
                  <div style="display:flex; justify-content:space-between;">
                    <span style="color:#5A645F;">Items Subtotal:</span>
                    <strong>&#2547; ${match.subtotal.toLocaleString("en-IN")}</strong>
                  </div>
                  <div style="display:flex; justify-content:space-between;">
                    <span style="color:#5A645F;">Delivery Fee (${match.zone}):</span>
                    <strong>&#2547; ${match.shipping.toLocaleString("en-IN")}</strong>
                  </div>
                  <div style="display:flex; justify-content:space-between; font-size:1.08rem; font-weight:800; padding-top:8px; margin-top:4px; border-top:1px solid #D5DDD8;">
                    <span>Total Cash on Delivery:</span>
                    <span style="color:#008A00;">&#2547; ${match.total.toLocaleString("en-IN")}</span>
                  </div>
                </div>
              </div>

              <div class="rabez-invoice-panel">
                <div class="rabez-invoice-panel__head">
                  <h3 style="font-size:1.12rem;">Delivery &amp; Store Details</h3>
                  <span style="font-size:0.74rem; font-weight:700; color:#056805;">COD Active</span>
                </div>
                <div style="display:grid; gap:12px; font-size:0.86rem;">
                  <div>
                    <div style="color:#6E7672; font-size:0.72rem; text-transform:uppercase; font-weight:800; letter-spacing:0.05em;">Recipient</div>
                    <div style="font-weight:800; font-size:0.94rem; margin-top:2px;">${match.customer}</div>
                    <div style="color:#4A544F; font-weight:600;">${match.phone}</div>
                  </div>
                  <div>
                    <div style="color:#6E7672; font-size:0.72rem; text-transform:uppercase; font-weight:800; letter-spacing:0.05em;">Shipping Address</div>
                    <div style="font-weight:600; margin-top:2px;">${match.address}</div>
                  </div>
                  <div style="padding-top:12px; border-top:1px solid #DCE3DF;">
                    <div style="color:#6E7672; font-size:0.72rem; text-transform:uppercase; font-weight:800; letter-spacing:0.05em;">Dispatched From</div>
                    <div style="font-weight:800; margin-top:2px;">RABEZ Official Store (rabez.shop)</div>
                    <div style="color:#4A544F;">Road-10, College Avenue, Barishal</div>
                    <div style="color:#4A544F;">Support Hotline: +880 1612-359652</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        `;
      }

      document.querySelector(".js-track-order-form")?.addEventListener("submit", function (e) {
        e.preventDefault();
        const val = document.querySelector(".js-track-query")?.value || "RBZ-10024";
        renderOrder(val);
      });

      document.querySelectorAll(".js-demo-order-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
          document.querySelectorAll(".js-demo-order-btn").forEach((b) => b.classList.remove("is-active"));
          btn.classList.add("is-active");
          const code = btn.dataset.order;
          const input = document.querySelector(".js-track-query");
          if (input) input.value = code;
          renderOrder(code);
        });
      });

      const params = new URLSearchParams(window.location.search);
      const initialOrder = params.get("order") || "RBZ-10024";
      const input = document.querySelector(".js-track-query");
      if (input) input.value = initialOrder;
      renderOrder(initialOrder);
    })();
</script>
@endpush
