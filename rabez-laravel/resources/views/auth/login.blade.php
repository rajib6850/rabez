<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In &mdash; RABEZ Admin &amp; Store</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
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
      padding: 24px 16px;
      position: relative;
      overflow-x: hidden;
    }
    /* Subtle background grid & glow */
    body::before {
      content: "";
      position: absolute;
      top: -150px;
      left: 50%;
      transform: translateX(-50%);
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }
    .auth-container {
      width: 100%;
      max-width: 440px;
      position: relative;
      z-index: 1;
    }
    .brand-header {
      text-align: center;
      margin-bottom: 28px;
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
    .brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 10px;
      padding: 4px 12px;
      border-radius: 9999px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--card-border);
      font-size: 11.5px;
      font-weight: 600;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: var(--text-muted);
    }
    .brand-badge::before {
      content: "";
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--success);
      box-shadow: 0 0 8px var(--success);
    }
    .auth-card {
      background: var(--card);
      border: 1px solid var(--card-border);
      border-radius: 16px;
      padding: 32px 28px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45), 0 0 1px rgba(255, 255, 255, 0.1);
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
      margin-bottom: 22px;
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
    .alert--success {
      background: var(--success-bg);
      border: 1px solid rgba(34, 197, 94, 0.3);
      color: #86EFAC;
    }
    .form-group {
      margin-bottom: 18px;
    }
    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #D1D5DB;
      margin-bottom: 7px;
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
      height: 44px;
      padding: 0 42px 0 42px;
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 10px;
      font-size: 14px;
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
    .input-toggle-pw {
      position: absolute;
      right: 12px;
      background: none;
      border: none;
      color: #6B7280;
      cursor: pointer;
      padding: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.15s ease;
    }
    .input-toggle-pw:hover {
      color: #E5E7EB;
    }
    .form-row-extra {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 22px;
      font-size: 13px;
    }
    .checkbox-label {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      color: var(--text-muted);
      user-select: none;
    }
    .checkbox-label input[type="checkbox"] {
      width: 16px;
      height: 16px;
      accent-color: var(--primary);
      border-radius: 4px;
      cursor: pointer;
    }
    .btn-submit {
      width: 100%;
      height: 46px;
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
    .btn-submit:active {
      transform: translateY(0);
    }
    /* Demo Credentials Callout */
    .demo-box {
      margin-top: 22px;
      padding: 12px 14px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px dashed rgba(255, 255, 255, 0.12);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }
    .demo-info {
      font-size: 12px;
      line-height: 1.45;
      color: var(--text-muted);
    }
    .demo-info strong {
      color: #E5E7EB;
      font-family: 'JetBrains Mono', monospace;
    }
    .btn-fill-demo {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #fff;
      font-size: 11.5px;
      font-weight: 600;
      padding: 6px 10px;
      border-radius: 6px;
      cursor: pointer;
      white-space: nowrap;
      transition: background 0.15s ease;
    }
    .btn-fill-demo:hover {
      background: rgba(255, 255, 255, 0.16);
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
      <div>
        <span class="brand-badge">Secure Portal Access</span>
      </div>
    </div>

    <div class="auth-card">
      <h1 class="auth-title">Sign In</h1>
      <p class="auth-desc">Enter your credentials to access the RABEZ admin dashboard or manage your account.</p>

      {{-- Success Banner --}}
      @if (session('success'))
        <div class="alert alert--success">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          <div>{{ session('success') }}</div>
        </div>
      @endif

      {{-- Error Banner --}}
      @if (session('error'))
        <div class="alert alert--danger">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <div>{{ session('error') }}</div>
        </div>
      @endif

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

      <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group">
          <label class="form-label" for="login-email">Email Address</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            </span>
            <input type="email" id="login-email" name="email" class="form-input" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@rabez.shop">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="login-password">Password</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" id="login-password" name="password" class="form-input" required autocomplete="current-password" placeholder="••••••••">
            <button type="button" class="input-toggle-pw js-toggle-pw" aria-label="Toggle password visibility">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <div class="form-row-extra">
          <label class="checkbox-label">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <span>Remember this device</span>
          </label>
        </div>

        <button type="submit" class="btn-submit">
          <span>Sign In to Dashboard</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
      </form>

      {{-- Demo Pill --}}
      <div class="demo-box">
        <div class="demo-info">
          <div>Admin: <strong>admin@rabez.shop</strong></div>
          <div>Password: <strong>admin1234</strong></div>
        </div>
        <button type="button" class="btn-fill-demo js-fill-demo" title="Auto fill demo admin credentials">
          Fill Demo
        </button>
      </div>
    </div>

    <div class="auth-footer">
      Don't have an account? <a href="{{ route('register') }}">Create Account</a>
    </div>

    <a href="{{ route('home') }}" class="back-store">
      &larr; Return to RABEZ Storefront
    </a>
  </div>

  <script>
    // Password toggle
    const toggleBtn = document.querySelector('.js-toggle-pw');
    const pwInput = document.getElementById('login-password');
    if (toggleBtn && pwInput) {
      toggleBtn.addEventListener('click', () => {
        const isPw = pwInput.type === 'password';
        pwInput.type = isPw ? 'text' : 'password';
        toggleBtn.innerHTML = isPw
          ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
          : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
      });
    }

    // Demo Fill
    document.querySelector('.js-fill-demo')?.addEventListener('click', () => {
      document.getElementById('login-email').value = 'admin@rabez.shop';
      document.getElementById('login-password').value = 'admin1234';
      document.getElementById('login-email').focus();
    });
  </script>
</body>
</html>

