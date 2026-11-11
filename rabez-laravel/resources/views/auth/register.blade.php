<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account &mdash; RABEZ</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #0B0D0C;
      --card: #141716;
      --card-border: rgba(255, 255, 255, 0.08);
      --input-bg: #1B1E1D;
      --input-border: #2B302E;
      --text: #F3F4F6;
      --text-muted: #9CA3AF;
      --primary: #16A34A;
      --primary-hover: #15803D;
      --primary-glow: rgba(22, 163, 74, 0.25);
      --danger: #EF4444;
      --danger-bg: rgba(239, 68, 68, 0.12);
      --success: #22C55E;
      --success-bg: rgba(34, 197, 94, 0.12);
    }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 32px 16px;
      position: relative;
      overflow-x: hidden;
    }
    body::before {
      content: "";
      position: absolute;
      top: -150px;
      left: 50%;
      transform: translateX(-50%);
      width: 650px;
      height: 650px;
      background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }
    .auth-container {
      width: 100%;
      max-width: 460px;
      position: relative;
      z-index: 1;
    }
    .brand-header {
      text-align: center;
      margin-bottom: 24px;
    }
    .brand-logo {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: #fff;
    }
    .brand-logo img {
      height: 42px;
      width: auto;
      object-fit: contain;
    }
    .brand-logo-fallback {
      font-size: 26px;
      font-weight: 800;
      letter-spacing: -0.5px;
      color: #fff;
    }
    .brand-logo-fallback span {
      color: var(--primary);
    }
    .auth-card {
      background: var(--card);
      border: 1px solid var(--card-border);
      border-radius: 16px;
      padding: 32px 28px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
      backdrop-filter: blur(12px);
    }
    .auth-title {
      font-size: 20px;
      font-weight: 700;
      letter-spacing: -0.3px;
      margin-bottom: 6px;
      color: #FFFFFF;
    }
    .auth-desc {
      font-size: 13.5px;
      color: var(--text-muted);
      margin-bottom: 20px;
      line-height: 1.5;
    }
    .alert {
      padding: 12px 14px;
      border-radius: 10px;
      font-size: 13px;
      line-height: 1.45;
      margin-bottom: 18px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }
    .alert--danger {
      background: var(--danger-bg);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #FCA5A5;
    }
    .form-group {
      margin-bottom: 16px;
    }
    .form-label {
      display: block;
      font-size: 12.5px;
      font-weight: 600;
      color: #D1D5DB;
      margin-bottom: 6px;
    }
    .input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .input-icon {
      position: absolute;
      left: 14px;
      width: 18px;
      height: 18px;
      color: #6B7280;
      pointer-events: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .form-input {
      width: 100%;
      height: 42px;
      padding: 0 14px 0 42px;
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 10px;
      font-size: 13.5px;
      color: #fff;
      font-family: inherit;
      transition: all 0.18s ease;
    }
    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
      background: #1F2321;
    }
    .btn-submit {
      width: 100%;
      height: 46px;
      margin-top: 8px;
      background: var(--primary);
      color: #FFFFFF;
      font-family: inherit;
      font-size: 14.5px;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s cubic-bezier(0.2, 0.8, 0.2, 1);
      box-shadow: 0 4px 14px var(--primary-glow);
    }
    .btn-submit:hover {
      background: var(--primary-hover);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(22, 163, 74, 0.4);
    }
    .auth-footer {
      text-align: center;
      margin-top: 24px;
      font-size: 13.5px;
      color: var(--text-muted);
    }
    .auth-footer a {
      color: #22C55E;
      text-decoration: none;
      font-weight: 600;
    }
    .auth-footer a:hover {
      text-decoration: underline;
    }
    .back-store {
      display: block;
      text-align: center;
      margin-top: 18px;
      font-size: 12.5px;
      color: #6B7280;
      text-decoration: none;
      transition: color 0.15s ease;
    }
    .back-store:hover {
      color: #9CA3AF;
    }
  </style>
</head>
<body>

  <div class="auth-container">
    <div class="brand-header">
      <a href="{{ route('home') }}" class="brand-logo" title="RABEZ Storefront">
        <img src="{{ asset('assets/images/logo.png') }}" alt="RABEZ" onerror="this.style.display='none'; document.getElementById('logo-text').style.display='inline-block';">
        <span id="logo-text" class="brand-logo-fallback" style="display:none;">RA<span>BEZ</span></span>
      </a>
    </div>

    <div class="auth-card">
      <h1 class="auth-title">Create Account</h1>
      <p class="auth-desc">Join RABEZ to track your orders, save shipping details, and access member perks.</p>

      @if ($errors->any())
        <div class="alert alert--danger">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <div>
            @foreach ($errors->all() as $err)
              <div>{{ $err }}</div>
            @endforeach
          </div>
        </div>
      @endif

      <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group">
          <label class="form-label" for="reg-name">Full Name</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <input type="text" id="reg-name" name="name" class="form-input" value="{{ old('name') }}" required autofocus placeholder="e.g. Tanvir Ahmed">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="reg-email">Email Address</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            </span>
            <input type="email" id="reg-email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="you@example.com">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="reg-phone">Phone Number (Optional)</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </span>
            <input type="tel" id="reg-phone" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
          </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div class="form-group">
            <label class="form-label" for="reg-password">Password</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              </span>
              <input type="password" id="reg-password" name="password" class="form-input" required placeholder="Min 8 chars">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="reg-password-confirm">Confirm</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </span>
              <input type="password" id="reg-password-confirm" name="password_confirmation" class="form-input" required placeholder="Repeat">
            </div>
          </div>
        </div>

        <button type="submit" class="btn-submit">
          <span>Create My Account</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
      </form>
    </div>

    <div class="auth-footer">
      Already have an account? <a href="{{ route('login') }}">Sign In</a>
    </div>

    <a href="{{ route('home') }}" class="back-store">
      &larr; Return to RABEZ Storefront
    </a>
  </div>

</body>
</html>

