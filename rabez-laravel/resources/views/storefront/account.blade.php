@extends('layouts.storefront')

@section('title', 'My Account - RABEZ Official Jersey Store')

@push('styles')
  <style>
    .rabez-acc-hero {
      background:
        radial-gradient(circle at 82% 18%, rgba(4, 244, 4, 0.16), transparent 42%),
        radial-gradient(circle at 12% 85%, rgba(4, 244, 4, 0.06), transparent 36%),
        #0A0C0B;
      color: #FFFFFF;
      padding: 42px 0 46px;
      border-bottom: 1px solid #1E2421;
    }
    .rabez-acc-hero__inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      flex-wrap: wrap;
    }
    .rabez-acc-user {
      display: flex;
      align-items: center;
      gap: 16px;
      min-width: 0;
    }
    .rabez-acc-avatar {
      width: 62px;
      height: 62px;
      border-radius: 50%;
      background: rgba(4, 244, 4, 0.14);
      border: 2px solid #04F404;
      color: #04F404;
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 1.65rem;
      font-weight: 800;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
    }
    .rabez-acc-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .rabez-acc-meta {
      min-width: 0;
    }
    .rabez-acc-badges {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 6px;
    }
    .rabez-acc-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 10px;
      border-radius: 999px;
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      background: rgba(4, 244, 4, 0.14);
      color: #04F404;
      border: 1px solid rgba(4, 244, 4, 0.35);
    }
    .rabez-acc-pill--muted {
      background: rgba(255, 255, 255, 0.07);
      color: #CBD5E1;
      border-color: rgba(255, 255, 255, 0.14);
      text-transform: none;
      letter-spacing: 0;
    }
    .rabez-acc-title {
      font-size: clamp(1.5rem, 3vw, 2.15rem);
      font-weight: 800;
      color: #FFFFFF;
      line-height: 1.1;
      margin: 0;
      word-break: break-word;
    }
    .rabez-acc-sub {
      color: #A7B3AD;
      font-size: 0.88rem;
      margin-top: 4px;
      word-break: break-word;
    }
    .rabez-acc-actions {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
    }
    .rabez-acc-btn-outline {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 15px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.16);
      color: #FFFFFF;
      font-size: 0.84rem;
      font-weight: 700;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.16s ease;
    }
    .rabez-acc-btn-outline:hover {
      border-color: #04F404;
      color: #04F404;
    }
    .rabez-acc-btn-danger {
      background: rgba(239, 68, 68, 0.12);
      border-color: rgba(239, 68, 68, 0.35);
      color: #FCA5A5;
    }
    .rabez-acc-btn-danger:hover {
      background: rgba(239, 68, 68, 0.22);
      border-color: #EF4444;
      color: #FFFFFF;
    }

    .rabez-acc-stats {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 14px;
      margin-bottom: 26px;
    }
    .rabez-acc-stat {
      background: #FFFFFF;
      border: 1px solid #DCE3DF;
      border-radius: 14px;
      padding: 18px 20px;
      box-shadow: 0 8px 24px rgba(10, 12, 11, 0.04);
    }
    .rabez-acc-stat__label {
      font-size: 0.76rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #6B7570;
      margin-bottom: 6px;
    }
    .rabez-acc-stat__value {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 2rem;
      font-weight: 800;
      line-height: 1;
      color: #0A0C0B;
    }
    .rabez-acc-stat--highlight .rabez-acc-stat__value {
      color: #008A00;
    }

    .rabez-acc-grid {
      display: grid;
      grid-template-columns: 1.55fr 1fr;
      gap: 24px;
      align-items: start;
    }
    .rabez-acc-col {
      min-width: 0;
      display: grid;
      gap: 20px;
    }
    .rabez-acc-card {
      background: #FFFFFF;
      border: 1px solid #DCE3DF;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 10px 28px rgba(10, 12, 11, 0.05);
      min-width: 0;
    }
    .rabez-acc-card__head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
      padding-bottom: 14px;
      margin-bottom: 18px;
      border-bottom: 1px solid #E6ECE8;
    }
    .rabez-acc-card__title {
      font-size: 1.25rem;
      font-weight: 800;
      color: #0A0C0B;
      margin: 0;
    }
    .rabez-acc-card__sub {
      font-size: 0.82rem;
      color: #64748B;
      margin-top: 3px;
    }

    .rabez-acc-order-list {
      display: grid;
      gap: 16px;
    }
    .rabez-acc-order {
      border: 1px solid #DCE3DF;
      border-radius: 14px;
      padding: 18px;
      background: #FCFDFC;
      transition: border-color 0.16s ease;
    }
    .rabez-acc-order:hover {
      border-color: #0A0C0B;
    }
    .rabez-acc-order__top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
      padding-bottom: 12px;
      border-bottom: 1px solid #E8EEEC;
      margin-bottom: 12px;
    }
    .rabez-acc-order__id {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }
    .rabez-acc-order__num {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 6px;
      background: #0A0C0B;
      color: #04F404;
      font-size: 0.78rem;
      font-weight: 800;
      letter-spacing: 0.04em;
    }
    .rabez-acc-order__date {
      font-size: 0.8rem;
      color: #64748B;
      font-weight: 600;
    }
    .rabez-acc-status {
      display: inline-flex;
      align-items: center;
      padding: 4px 11px;
      border-radius: 999px;
      font-size: 0.76rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }
    .rabez-acc-status--pending { background: #FEF3C7; color: #92400E; }
    .rabez-acc-status--printing { background: #E0F2FE; color: #075985; }
    .rabez-acc-status--shipped { background: #EDE9FE; color: #5B21B6; }
    .rabez-acc-status--delivered { background: #DCFCE7; color: #166534; }
    .rabez-acc-status--cancelled { background: #FEE2E2; color: #991B1B; }

    .rabez-acc-items {
      display: grid;
      gap: 10px;
      margin-bottom: 14px;
    }
    .rabez-acc-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 10px;
      background: #FFFFFF;
      border: 1px solid #EAEFEF;
      border-radius: 10px;
    }
    .rabez-acc-item img {
      width: 50px;
      height: 50px;
      border-radius: 8px;
      object-fit: cover;
      flex-shrink: 0;
      border: 1px solid #E5E7EB;
    }
    .rabez-acc-item__info {
      flex: 1;
      min-width: 0;
    }
    .rabez-acc-item__name {
      font-size: 0.88rem;
      font-weight: 700;
      color: #0A0C0B;
      line-height: 1.3;
      word-break: break-word;
    }
    .rabez-acc-item__tags {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 4px;
      font-size: 0.75rem;
      color: #475569;
    }
    .rabez-acc-tag {
      background: #F1F5F9;
      padding: 2px 7px;
      border-radius: 4px;
      font-weight: 600;
    }
    .rabez-acc-tag--print {
      background: #DCFCE7;
      color: #166534;
    }
    .rabez-acc-item__price {
      font-weight: 800;
      font-size: 0.9rem;
      color: #0A0C0B;
      white-space: nowrap;
    }

    .rabez-acc-order__meta {
      font-size: 0.82rem;
      color: #475569;
      background: #F4F7F5;
      border-radius: 8px;
      padding: 10px 12px;
      display: grid;
      gap: 4px;
      margin-bottom: 14px;
      line-height: 1.45;
      word-break: break-word;
    }
    .rabez-acc-order__foot {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
    }
    .rabez-acc-order__total {
      font-size: 1.05rem;
      font-weight: 800;
      color: #0A0C0B;
    }
    .rabez-acc-order__total span {
      color: #008A00;
    }

    .rabez-acc-form {
      display: grid;
      gap: 14px;
    }
    .rabez-acc-field label {
      display: block;
      font-size: 0.82rem;
      font-weight: 700;
      color: #1E293B;
      margin-bottom: 6px;
    }
    .rabez-acc-field input,
    .rabez-acc-field textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1.5px solid #CBD5E1;
      border-radius: 9px;
      font-size: 0.9rem;
      font-family: inherit;
      color: #0A0C0B;
      background: #FFFFFF;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .rabez-acc-field input:focus,
    .rabez-acc-field textarea:focus {
      outline: none;
      border-color: #0A0C0B;
      box-shadow: 0 0 0 3px rgba(4, 244, 4, 0.18);
    }
    .rabez-acc-field input[disabled] {
      background: #F1F5F9;
      color: #64748B;
      cursor: not-allowed;
    }
    .rabez-acc-hint {
      font-size: 0.76rem;
      color: #64748B;
      margin-top: 4px;
      display: block;
    }
    .rabez-acc-alert {
      padding: 13px 16px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 0.88rem;
      font-weight: 600;
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }
    .rabez-acc-alert--error {
      background: #FEF2F2;
      border: 1px solid #FCA5A5;
      color: #991B1B;
    }
    .rabez-acc-alert--success {
      background: #F0FDF4;
      border: 1px solid #86EFAC;
      color: #166534;
    }

    @media (max-width: 920px) {
      .rabez-acc-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
      .rabez-acc-grid {
        grid-template-columns: 1fr;
      }
    }
    @media (max-width: 520px) {
      .rabez-acc-hero {
        padding: 30px 0 32px;
      }
      .rabez-acc-card {
        padding: 18px 16px;
      }
      .rabez-acc-item {
        align-items: flex-start;
      }
    }
  </style>
@endpush

@section('content')
  <section class="rabez-acc-hero">
    <div class="rabez-container rabez-acc-hero__inner" style="max-width:1200px;">
      <div class="rabez-acc-user">
        <div class="rabez-acc-avatar">
          @if(!empty($user->avatar))
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" onerror="this.remove();">
          @else
            {{ strtoupper(mb_substr($user->name ?: 'R', 0, 1)) }}
          @endif
        </div>
        <div class="rabez-acc-meta">
          <div class="rabez-acc-badges">
            <span class="rabez-acc-pill">{{ $user->isAdmin() ? 'ADMIN ACCOUNT' : 'RABEZ MEMBER' }}</span>
            @if(!empty($user->phone))
              <span class="rabez-acc-pill rabez-acc-pill--muted">{{ $user->phone }}</span>
            @endif
          </div>
          <h1 class="rabez-acc-title">স্বাগতম, {{ $user->name }}</h1>
          <p class="rabez-acc-sub">{{ $user->email }} &bull; আপনার অর্ডার হিস্ট্রি, ইনভয়েস ও ডেলিভারি তথ্য এখান থেকে ম্যানেজ করুন</p>
        </div>
      </div>

      <div class="rabez-acc-actions">
        <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--primary" style="padding:10px 18px; font-size:0.86rem;">Shop Jerseys</a>
        <a href="{{ route('track-order') }}" class="rabez-acc-btn-outline">Track Order</a>
        @if($user->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="rabez-acc-btn-outline" style="border-color:#04F404; color:#04F404;">Admin Panel</a>
        @endif
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="rabez-acc-btn-outline rabez-acc-btn-danger">Sign Out</button>
        </form>
      </div>
    </div>
  </section>

  <main class="rabez-section rabez-section--alt">
    <div class="rabez-container" style="max-width:1200px;">

      @if(session('success'))
        <div class="rabez-acc-alert rabez-acc-alert--success">
          <span>&#10003;</span>
          <div>{{ session('success') }}</div>
        </div>
      @endif

      @if($errors->any())
        <div class="rabez-acc-alert rabez-acc-alert--error">
          <span>&#9888;</span>
          <div>
            @foreach($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        </div>
      @endif

      <!-- Summary KPIs -->
      <div class="rabez-acc-stats">
        <div class="rabez-acc-stat">
          <div class="rabez-acc-stat__label">Total Orders</div>
          <div class="rabez-acc-stat__value">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="rabez-acc-stat">
          <div class="rabez-acc-stat__label">Active / In Transit</div>
          <div class="rabez-acc-stat__value">{{ $stats['active_orders'] }}</div>
        </div>
        <div class="rabez-acc-stat">
          <div class="rabez-acc-stat__label">Delivered</div>
          <div class="rabez-acc-stat__value">{{ $stats['delivered_orders'] }}</div>
        </div>
        <div class="rabez-acc-stat rabez-acc-stat--highlight">
          <div class="rabez-acc-stat__label">Total Spent</div>
          <div class="rabez-acc-stat__value">&#2547; {{ number_format($stats['total_spent']) }}</div>
        </div>
      </div>

      <div class="rabez-acc-grid">
        <!-- Left Column: Order History -->
        <div class="rabez-acc-col">
          <section class="rabez-acc-card">
            <div class="rabez-acc-card__head">
              <div>
                <h2 class="rabez-acc-card__title">আমার অর্ডারসমূহ (My Orders)</h2>
                <div class="rabez-acc-card__sub">আপনার সব অর্ডার ও কুরিয়ার ট্র্যাকিং স্ট্যাটাস</div>
              </div>
              <span class="rabez-acc-tag" style="font-size:0.8rem;">{{ $orders->count() }} Orders</span>
            </div>

            @if($orders->isEmpty())
              <div style="text-align:center; padding:34px 16px; border:1px dashed #CBD5E1; border-radius:12px; background:#F8FAF9;">
                <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" style="margin:0 auto 12px;">
                  <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                  <line x1="3" y1="6" x2="21" y2="6"></line>
                  <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <div style="font-size:1.05rem; font-weight:800; color:#0A0C0B; margin-bottom:6px;">আপনার একাউন্টে এখনো কোনো অর্ডার নেই</div>
                <p style="font-size:0.86rem; color:#64748B; max-width:420px; margin:0 auto 18px;">
                  আগে লগইন ছাড়া অর্ডার করে থাকলে নিচের প্রোফাইল ফর্মে সেই ১১ ডিজিটের মোবাইল নম্বরটি সেভ করুন &mdash; আপনার সব পুরনো অর্ডার অটোমেটিক চলে আসবে!
                </p>
                <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--primary" style="display:inline-flex;">Browse Jerseys &rarr;</a>
              </div>
            @else
              <div class="rabez-acc-order-list">
                @foreach($orders as $order)
                  @php
                    $statusSlug = strtolower((string) ($order->status ?? 'pending'));
                    $badgeClass = match($statusSlug) {
                      'printing' => 'rabez-acc-status--printing',
                      'shipped' => 'rabez-acc-status--shipped',
                      'delivered' => 'rabez-acc-status--delivered',
                      'cancelled' => 'rabez-acc-status--cancelled',
                      default => 'rabez-acc-status--pending',
                    };
                  @endphp
                  <article class="rabez-acc-order">
                    <div class="rabez-acc-order__top">
                      <div class="rabez-acc-order__id">
                        <span class="rabez-acc-order__num">#{{ $order->order_number }}</span>
                        <span class="rabez-acc-order__date">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '' }}</span>
                      </div>
                      <span class="rabez-acc-status {{ $badgeClass }}">{{ $order->status ?? 'Pending' }}</span>
                    </div>

                    <div class="rabez-acc-items">
                      @foreach($order->items as $item)
                        <div class="rabez-acc-item">
                          <img src="{{ asset(ltrim($item->image ?: 'assets/images/products/real-madrid-home-hs.jpg', '/')) }}" alt="{{ $item->product_name }}">
                          <div class="rabez-acc-item__info">
                            <div class="rabez-acc-item__name">{{ $item->product_name }}</div>
                            <div class="rabez-acc-item__tags">
                              <span class="rabez-acc-tag">Size: {{ $item->size }}</span>
                              <span class="rabez-acc-tag">Qty: {{ $item->qty }}</span>
                              @if(!empty($item->custom_name) || !empty($item->custom_number))
                                <span class="rabez-acc-tag rabez-acc-tag--print">
                                  Print: {{ trim(($item->custom_name ?? '') . ' ' . (!empty($item->custom_number) ? '#' . $item->custom_number : '')) }}
                                </span>
                              @endif
                            </div>
                          </div>
                          <div class="rabez-acc-item__price">&#2547; {{ number_format($item->total_price) }}</div>
                        </div>
                      @endforeach
                    </div>

                    <div class="rabez-acc-order__meta">
                      <div><strong>ডেলিভারি ঠিকানা:</strong> {{ $order->customer_address }}</div>
                      <div>
                        <strong>মোবাইল:</strong> {{ $order->customer_phone }}
                        @if(!empty($order->courier))
                          &bull; <strong>কুরিয়ার:</strong> {{ $order->courier }}
                          @if(!empty($order->consignment_id) && $order->consignment_id !== 'Pending Dispatch')
                            ({{ $order->consignment_id }})
                          @endif
                        @endif
                      </div>
                    </div>

                    <div class="rabez-acc-order__foot">
                      <div class="rabez-acc-order__total">
                        Total Payable: <span>&#2547; {{ number_format($order->total) }}</span>
                      </div>
                      <a href="{{ route('track-order', ['order' => $order->order_number]) }}" class="rabez-btn rabez-btn--dark" style="padding:9px 15px; font-size:0.82rem;">
                        Track &amp; Invoice &rarr;
                      </a>
                    </div>
                  </article>
                @endforeach
              </div>
            @endif
          </section>
        </div>

        <!-- Right Column: Profile, Saved Address & Security -->
        <div class="rabez-acc-col">
          <section class="rabez-acc-card">
            <div class="rabez-acc-card__head">
              <div>
                <h2 class="rabez-acc-card__title">প্রোফাইল ও ডেলিভারি ঠিকানা</h2>
                <div class="rabez-acc-card__sub">এখানে সেভ করা নাম, মোবাইল ও ঠিকানা চেকআউটে অটো-ফিল হবে</div>
              </div>
            </div>

            <form action="{{ route('account.profile.update') }}" method="POST" class="rabez-acc-form">
              @csrf
              <div class="rabez-acc-field">
                <label for="acc-name">Full Name *</label>
                <input type="text" id="acc-name" name="name" value="{{ old('name', $user->name) }}" required maxlength="100" placeholder="আপনার পূর্ণ নাম">
              </div>

              <div class="rabez-acc-field">
                <label for="acc-phone">Mobile Number (11 Digits) *</label>
                <input type="tel" id="acc-phone" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="01XXXXXXXXX">
                <span class="rabez-acc-hint">এই নম্বরে করা আগের অর্ডারগুলো অটোমেটিক আপনার একাউন্টে যুক্ত হয়ে যাবে।</span>
              </div>

              <div class="rabez-acc-field">
                <label for="acc-email">Email Address</label>
                <input type="email" id="acc-email" value="{{ $user->email }}" readonly disabled>
              </div>

              <div class="rabez-acc-field">
                <label for="acc-address">Saved Delivery Address</label>
                <textarea id="acc-address" name="address" rows="3" maxlength="255" placeholder="House, Road, Area, Thana, District">{{ old('address', $user->address) }}</textarea>
              </div>

              <button type="submit" class="rabez-btn rabez-btn--primary rabez-btn--full" style="font-weight:800; padding:12px;">
                Save Profile &amp; Address
              </button>
            </form>
          </section>

          <section class="rabez-acc-card">
            <div class="rabez-acc-card__head">
              <div>
                <h2 class="rabez-acc-card__title">পাসওয়ার্ড পরিবর্তন</h2>
                <div class="rabez-acc-card__sub">আপনার একাউন্টের নিরাপত্তার জন্য পাসওয়ার্ড আপডেট করুন</div>
              </div>
            </div>

            <form action="{{ route('account.password.update') }}" method="POST" class="rabez-acc-form">
              @csrf
              <div class="rabez-acc-field">
                <label for="acc-current-password">Current Password *</label>
                <input type="password" id="acc-current-password" name="current_password" required autocomplete="current-password" placeholder="••••••••">
              </div>

              <div class="rabez-acc-field">
                <label for="acc-new-password">New Password *</label>
                <input type="password" id="acc-new-password" name="password" required autocomplete="new-password" placeholder="Minimum 8 characters">
              </div>

              <div class="rabez-acc-field">
                <label for="acc-new-password-confirm">Confirm New Password *</label>
                <input type="password" id="acc-new-password-confirm" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat new password">
              </div>

              <button type="submit" class="rabez-btn rabez-btn--dark rabez-btn--full" style="font-weight:700; padding:12px;">
                Update Password
              </button>
            </form>
          </section>
        </div>
      </div>
    </div>
  </main>
@endsection
