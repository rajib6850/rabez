<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RABEZ Admin - Dashboard</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#0B0D0C">
  <link rel="icon" type="image/jpeg" href="{{ asset('assets/') }}/images/brand/rabez-emblem.jpg">
  <link rel="apple-touch-icon" href="{{ asset('assets/') }}/images/brand/rabez-emblem.jpg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Barlow+Condensed:wght@700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/') }}/css/01-variables.css">
  <link rel="stylesheet" href="{{ asset('assets/') }}/css/02-base-reset.css">

  <style>
    /* ==========================================================================
       RABEZ ADMIN — Clean Modern Dashboard
       Inter typeface • Sentence case • Neutral surfaces • Neon used only as accent
       ========================================================================== */
    :root {
      --adm-bg: #F6F7F6;
      --adm-surface: #FFFFFF;
      --adm-subtle: #FAFBFA;
      --adm-border: #E6E9E7;
      --adm-border-strong: #D5DAD7;
      --adm-text: #111412;
      --adm-text-2: #4B524E;
      --adm-muted: #7A827E;
      --adm-ink: #0B0D0C;
      --adm-brand: #04F404;
      --adm-radius: 12px;
    }

    body.adm {
      font-family: "Inter", system-ui, -apple-system, "Segoe UI", sans-serif;
      font-size: 14px;
      line-height: 1.5;
      background: var(--adm-bg);
      color: var(--adm-text);
      -webkit-font-smoothing: antialiased;
    }

    .adm h1, .adm h2, .adm h3, .adm h4 {
      font-family: inherit;
      text-transform: none;
      letter-spacing: -0.01em;
      line-height: 1.3;
      font-weight: 600;
      color: var(--adm-text);
    }

    .num { font-variant-numeric: tabular-nums; }

    /* ---------- Layout ---------- */
    .adm-layout {
      display: grid;
      grid-template-columns: 248px minmax(0, 1fr);
      min-height: 100vh;
    }

    /* ---------- Sidebar ---------- */
    .adm-sidebar {
      position: sticky;
      top: 0;
      height: 100vh;
      display: flex;
      flex-direction: column;
      background: var(--adm-ink);
      border-right: 1px solid #161A18;
      padding: 18px 12px;
      overflow-y: auto;
    }

    .adm-brand {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 2px 8px 16px;
    }

    .adm-brand img { height: 30px; width: auto; }

    .adm-brand__tag {
      font-size: 11px;
      font-weight: 500;
      color: #8B938F;
      border: 1px solid #262B28;
      border-radius: 6px;
      padding: 2px 7px;
    }

    .adm-nav-group { margin-top: 18px; }

    .adm-nav-label {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: #59615D;
      padding: 0 10px 6px;
    }

    .adm-nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      height: 36px;
      padding: 0 10px;
      margin-bottom: 2px;
      border-radius: 8px;
      color: #A3ABA7;
      font-size: 13.5px;
      font-weight: 500;
      text-align: left;
      transition: background 0.15s ease, color 0.15s ease;
    }

    .adm-nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
    .adm-nav-item:hover { background: rgba(255, 255, 255, 0.04); color: #FFFFFF; }
    .adm-nav-item.is-active { background: rgba(255, 255, 255, 0.08); color: #FFFFFF; }
    .adm-nav-item.is-active svg { color: var(--adm-brand); }

    .adm-nav-count {
      margin-left: auto;
      font-size: 11.5px;
      font-weight: 500;
      color: #8B938F;
      background: rgba(255, 255, 255, 0.06);
      padding: 0 7px;
      line-height: 20px;
      border-radius: 999px;
    }

    .adm-sidebar__foot {
      margin-top: auto;
      padding: 14px;
      border: 1px solid #1C211E;
      border-radius: 10px;
      background: #111412;
    }

    .adm-sidebar__foot-title { color: #FFFFFF; font-size: 13px; font-weight: 600; }
    .adm-sidebar__foot-sub { color: #7D8581; font-size: 12px; margin: 2px 0 10px; }

    .adm-sidebar__link {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      height: 32px;
      border-radius: 7px;
      background: rgba(255, 255, 255, 0.06);
      color: #FFFFFF;
      font-size: 12.5px;
      font-weight: 500;
    }

    .adm-sidebar__link:hover { background: rgba(255, 255, 255, 0.1); }

    /* ---------- Main + Topbar ---------- */
    .adm-main { min-width: 0; display: flex; flex-direction: column; }

    .adm-topbar {
      position: sticky;
      top: 0;
      z-index: 50;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 0 28px;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--adm-border);
    }

    .adm-topbar__left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .adm-topbar__menu, .adm-topbar__logo { display: none; }
    .adm-topbar__title { font-size: 17px; font-weight: 600; letter-spacing: -0.01em; }

    .adm-topbar__sub {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12.5px;
      color: var(--adm-muted);
    }

    .adm-live {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #16A34A;
      box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
    }

    .adm-topbar__actions { display: flex; align-items: center; gap: 8px; }

    .adm-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: var(--adm-ink);
      color: var(--adm-brand);
      font-size: 12px;
      font-weight: 600;
      display: grid;
      place-items: center;
      margin-left: 4px;
    }

    .adm-content {
      width: 100%;
      max-width: 1320px;
      padding: 24px 28px 48px;
    }

    .adm-panel { display: none; }
    .adm-panel.is-active { display: block; }

    /* ---------- Buttons ---------- */
    .adm-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      height: 36px;
      padding: 0 14px;
      border-radius: 8px;
      border: 1px solid transparent;
      font-size: 13px;
      font-weight: 500;
      white-space: nowrap;
      transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }

    .adm-btn svg { width: 15px; height: 15px; }
    .adm-btn--primary { background: var(--adm-ink); color: #FFFFFF; }
    .adm-btn--primary:hover { background: #262B28; }
    .adm-btn--secondary { background: #FFFFFF; border-color: var(--adm-border-strong); color: var(--adm-text); }
    .adm-btn--secondary:hover { background: var(--adm-subtle); border-color: #BFC6C2; }
    .adm-btn--brand { background: var(--adm-brand); color: var(--adm-ink); }
    .adm-btn--brand:hover { background: #03DA03; }
    .adm-btn--sm { height: 30px; padding: 0 10px; font-size: 12.5px; border-radius: 7px; }
    .adm-btn--block { width: 100%; }
    .adm-btn[disabled] { opacity: 0.55; cursor: default; }

    .adm-icon-btn {
      width: 30px;
      height: 30px;
      display: inline-grid;
      place-items: center;
      border-radius: 7px;
      border: 1px solid var(--adm-border);
      color: var(--adm-text-2);
      background: #FFFFFF;
      transition: background 0.15s ease;
    }

    .adm-icon-btn svg { width: 15px; height: 15px; }
    .adm-icon-btn:hover { background: var(--adm-subtle); color: var(--adm-text); }

    /* ---------- KPI ---------- */
    .adm-kpis {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 16px;
      margin-bottom: 20px;
    }

    .adm-kpi {
      background: var(--adm-surface);
      border: 1px solid var(--adm-border);
      border-radius: var(--adm-radius);
      padding: 16px 18px;
    }

    .adm-kpi__head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 13px;
      font-weight: 500;
      color: var(--adm-muted);
    }

    .adm-kpi__icon {
      width: 28px;
      height: 28px;
      border-radius: 7px;
      background: #F3F5F4;
      color: var(--adm-text-2);
      display: grid;
      place-items: center;
    }

    .adm-kpi__icon svg { width: 15px; height: 15px; }

    .adm-kpi__value {
      margin-top: 8px;
      font-size: 26px;
      font-weight: 600;
      letter-spacing: -0.02em;
      line-height: 1.2;
      font-variant-numeric: tabular-nums;
    }

    .adm-kpi__foot { margin-top: 4px; font-size: 12.5px; color: var(--adm-muted); }
    .adm-up { color: #15803D; font-weight: 500; }

    /* ---------- Card ---------- */
    .adm-card {
      background: var(--adm-surface);
      border: 1px solid var(--adm-border);
      border-radius: var(--adm-radius);
      overflow: hidden;
    }

    .adm-card + .adm-card { margin-top: 20px; }

    .adm-card__head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      padding: 16px 20px;
      border-bottom: 1px solid var(--adm-border);
    }

    .adm-card__title { font-size: 15px; font-weight: 600; }
    .adm-card__desc { margin-top: 2px; font-size: 13px; color: var(--adm-muted); }
    .adm-card__body { padding: 20px; }

    /* ---------- Tabs + Toolbar ---------- */
    .adm-tabs {
      display: flex;
      gap: 4px;
      padding: 0 16px;
      border-bottom: 1px solid var(--adm-border);
      overflow-x: auto;
      scrollbar-width: none;
    }

    .adm-tabs::-webkit-scrollbar { display: none; }

    .adm-tab {
      position: relative;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      height: 46px;
      padding: 0 10px;
      font-size: 13px;
      font-weight: 500;
      color: var(--adm-muted);
      white-space: nowrap;
    }

    .adm-tab:hover { color: var(--adm-text); }
    .adm-tab.is-active { color: var(--adm-text); }

    .adm-tab.is-active::after {
      content: "";
      position: absolute;
      left: 8px;
      right: 8px;
      bottom: -1px;
      height: 2px;
      border-radius: 2px;
      background: var(--adm-ink);
    }

    .adm-tab__count {
      font-size: 11.5px;
      line-height: 18px;
      padding: 0 6px;
      border-radius: 999px;
      background: #F1F3F2;
      color: var(--adm-text-2);
    }

    .adm-tab.is-active .adm-tab__count { background: var(--adm-ink); color: #FFFFFF; }

    .adm-toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      padding: 12px 20px;
      border-bottom: 1px solid var(--adm-border);
    }

    .adm-toolbar__group { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

    .adm-search { position: relative; width: 280px; max-width: 100%; }

    .adm-search svg {
      position: absolute;
      left: 11px;
      top: 50%;
      transform: translateY(-50%);
      width: 15px;
      height: 15px;
      color: var(--adm-muted);
      pointer-events: none;
    }

    .adm-search input {
      width: 100%;
      height: 36px;
      padding: 0 12px 0 33px;
      border: 1px solid var(--adm-border-strong);
      border-radius: 8px;
      background: #FFFFFF;
      font-size: 13px;
      color: var(--adm-text);
    }

    .adm-search input:focus,
    .adm-input:focus,
    .adm-select:focus,
    .adm-textarea:focus {
      outline: none;
      border-color: var(--adm-ink);
      box-shadow: 0 0 0 3px rgba(11, 13, 12, 0.07);
    }

    .adm-filter-chips { display: flex; gap: 6px; flex-wrap: wrap; }

    .adm-filter-chip {
      height: 30px;
      padding: 0 12px;
      border-radius: 999px;
      border: 1px solid var(--adm-border-strong);
      font-size: 12.5px;
      font-weight: 500;
      color: var(--adm-text-2);
      background: #FFFFFF;
    }

    .adm-filter-chip:hover { border-color: #BFC6C2; color: var(--adm-text); }
    .adm-filter-chip.is-active { background: var(--adm-ink); border-color: var(--adm-ink); color: #FFFFFF; }

    /* ---------- Shared cells ---------- */
    .adm-cell-main { font-size: 13.5px; font-weight: 500; color: var(--adm-text); }
    .adm-cell-sub { font-size: 12.5px; color: var(--adm-muted); margin-top: 1px; }
    .adm-list-head-row { font-size: 12px; font-weight: 500; color: var(--adm-muted); background: var(--adm-subtle); }

    .adm-empty {
      padding: 48px 20px;
      text-align: center;
      font-size: 13.5px;
      color: var(--adm-muted);
    }

    /* ---------- Chips ---------- */
    .adm-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      height: 24px;
      padding: 0 9px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 500;
      white-space: nowrap;
    }

    .adm-chip::before {
      content: "";
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: currentColor;
    }

    .adm-chip--plain::before { display: none; }
    .adm-chip--pending { background: #FFF7E6; color: #B45309; }
    .adm-chip--printing { background: #F4F0FF; color: #6D28D9; }
    .adm-chip--shipped { background: #EFF4FF; color: #1D4ED8; }
    .adm-chip--delivered { background: #ECFDF3; color: #15803D; }
    .adm-chip--neutral { background: #F1F3F2; color: #4B524E; }
    .adm-chip--danger { background: #FEF2F2; color: #B91C1C; }

    .adm-risk { display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 500; color: #15803D; }
    .adm-risk__bar { width: 44px; height: 4px; border-radius: 4px; background: #EEF0EF; overflow: hidden; }
    .adm-risk__bar span { display: block; height: 100%; background: #16A34A; border-radius: 4px; }
    .adm-risk--warn { color: #B91C1C; }
    .adm-risk--warn .adm-risk__bar span { background: #DC2626; }

    .adm-code {
      font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
      font-size: 12px;
      background: #F3F5F4;
      color: var(--adm-text-2);
      padding: 2px 6px;
      border-radius: 5px;
    }

    /* ---------- Orders list ---------- */
    .adm-order-head,
    .adm-order-row {
      display: grid;
      grid-template-columns: minmax(120px, 1fr) minmax(170px, 1.4fr) minmax(130px, 1.1fr) 104px 116px 112px 116px;
      gap: 16px;
      align-items: center;
      padding: 0 20px;
    }

    .adm-order-head { height: 40px; border-bottom: 1px solid var(--adm-border); }

    .adm-order-row {
      min-height: 68px;
      padding-top: 12px;
      padding-bottom: 12px;
      border-bottom: 1px solid var(--adm-border);
      cursor: pointer;
      transition: background 0.12s ease;
    }

    .adm-order-row:hover { background: var(--adm-subtle); }
    .adm-order-row:last-child { border-bottom: none; }
    .adm-order-row .c-actions { display: flex; justify-content: flex-end; gap: 6px; }

    .adm-thumbs { display: flex; align-items: center; }

    .adm-thumbs img {
      width: 32px;
      height: 38px;
      object-fit: cover;
      border-radius: 6px;
      border: 2px solid #FFFFFF;
      box-shadow: 0 0 0 1px var(--adm-border);
      margin-left: -10px;
    }

    .adm-thumbs img:first-child { margin-left: 0; }
    .adm-thumbs__text { margin-left: 10px; font-size: 12.5px; color: var(--adm-muted); }

    /* ---------- Products list ---------- */
    .adm-prod-head,
    .adm-prod-row {
      display: grid;
      grid-template-columns: minmax(220px, 2fr) 110px minmax(180px, 1.2fr) 95px 80px 140px;
      gap: 16px;
      align-items: center;
      padding: 0 20px;
    }

    .adm-prod-head { height: 40px; border-bottom: 1px solid var(--adm-border); }

    .adm-prod-row {
      min-height: 72px;
      padding-top: 12px;
      padding-bottom: 12px;
      border-bottom: 1px solid var(--adm-border);
    }

    .adm-prod-row:last-child { border-bottom: none; }
    .adm-prod-cell { display: flex; align-items: center; gap: 12px; min-width: 0; }

    .adm-prod-cell img {
      width: 40px;
      height: 48px;
      object-fit: cover;
      border-radius: 7px;
      border: 1px solid var(--adm-border);
      flex-shrink: 0;
    }

    .adm-prod-cell .adm-cell-main {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .adm-sizes { display: flex; gap: 5px; flex-wrap: wrap; }

    .adm-size {
      min-width: 34px;
      height: 28px;
      padding: 0 8px;
      border-radius: 6px;
      border: 1px solid var(--adm-border-strong);
      background: #FFFFFF;
      font-size: 12px;
      font-weight: 500;
      color: var(--adm-text);
      transition: all 0.12s ease;
    }

    .adm-size:hover { border-color: var(--adm-ink); }

    .adm-size.is-out {
      background: #F6F7F6;
      border-style: dashed;
      color: #A9AFAC;
      text-decoration: line-through;
    }

    /* ---------- Forms ---------- */
    .adm-split {
      display: grid;
      grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr);
      gap: 20px;
      align-items: start;
    }

    .adm-split > .adm-card + .adm-card { margin-top: 0; }

    .adm-form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .adm-field { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    .adm-field--full { grid-column: 1 / -1; }
    .adm-label { font-size: 13px; font-weight: 500; color: var(--adm-text); }
    .adm-hint { font-size: 12px; color: var(--adm-muted); }

    .adm-input,
    .adm-select,
    .adm-textarea {
      width: 100%;
      height: 38px;
      padding: 0 12px;
      border: 1px solid var(--adm-border-strong);
      border-radius: 8px;
      background: #FFFFFF;
      font-size: 13.5px;
      color: var(--adm-text);
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .adm-select {
      appearance: none;
      -webkit-appearance: none;
      padding-right: 32px;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%237A827E' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 10px center;
      cursor: pointer;
    }

    .adm-input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .adm-form__actions { grid-column: 1 / -1; display: flex; justify-content: flex-end; gap: 8px; padding-top: 4px; }

    .adm-dropzone {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 4px;
      padding: 24px 16px;
      border: 1px dashed var(--adm-border-strong);
      border-radius: 10px;
      background: var(--adm-subtle);
      text-align: center;
      cursor: pointer;
      transition: border-color 0.15s ease, background 0.15s ease;
    }

    .adm-dropzone:hover { border-color: var(--adm-ink); background: #FFFFFF; }

    .adm-dropzone__icon {
      width: 36px;
      height: 36px;
      border-radius: 9px;
      background: #FFFFFF;
      border: 1px solid var(--adm-border);
      display: grid;
      place-items: center;
      color: var(--adm-text-2);
      margin-bottom: 6px;
    }

    .adm-dropzone__icon svg { width: 18px; height: 18px; }

    /* ---------- Simple lists & tables ---------- */
    .adm-rows > div {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid var(--adm-border);
      font-size: 13px;
    }

    .adm-rows > div:first-child { padding-top: 0; }
    .adm-rows > div:last-child { border-bottom: none; padding-bottom: 0; }

    .adm-table-wrap { overflow-x: auto; }
    .adm-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }

    .adm-table th {
      text-align: left;
      padding: 10px 20px;
      background: var(--adm-subtle);
      border-bottom: 1px solid var(--adm-border);
      font-size: 12px;
      font-weight: 500;
      color: var(--adm-muted);
      white-space: nowrap;
    }

    .adm-table td {
      padding: 14px 20px;
      border-bottom: 1px solid var(--adm-border);
      vertical-align: middle;
      white-space: nowrap;
    }

    .adm-table tr:last-child td { border-bottom: none; }

    /* ---------- Print queue ---------- */
    .adm-print-row {
      display: grid;
      grid-template-columns: 44px minmax(0, 1fr) 210px auto;
      gap: 16px;
      align-items: center;
      padding: 16px 20px;
      border-bottom: 1px solid var(--adm-border);
    }

    .adm-print-row:last-child { border-bottom: none; }

    .adm-print-row img {
      width: 44px;
      height: 52px;
      object-fit: cover;
      border-radius: 7px;
      border: 1px solid var(--adm-border);
    }

    .adm-print-label {
      padding: 8px 12px;
      border: 1px solid var(--adm-border);
      border-radius: 8px;
      background: var(--adm-subtle);
      text-align: center;
    }

    .adm-print-label__cap { font-size: 11px; color: var(--adm-muted); }

    .adm-print-label__text {
      font-family: "Barlow Condensed", sans-serif;
      font-size: 20px;
      font-weight: 700;
      letter-spacing: 0.06em;
      line-height: 1.2;
      color: var(--adm-text);
    }

    /* ---------- Callout ---------- */
    .adm-callout {
      padding: 14px;
      border-radius: 10px;
      border: 1px solid #CDEFD8;
      background: #F3FCF6;
    }

    .adm-callout--warn { border-color: #F7D4D4; background: #FEF6F6; }

    /* ---------- Dashboard 7-Day Chart ---------- */
    .adm-chart {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 12px;
      align-items: end;
      height: 148px;
      padding: 12px 4px 0;
    }

    .adm-chart__col {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      height: 100%;
      justify-content: flex-end;
    }

    .adm-chart__val {
      font-size: 11px;
      font-weight: 500;
      color: var(--adm-muted);
      font-variant-numeric: tabular-nums;
    }

    .adm-chart__bar {
      width: 100%;
      max-width: 34px;
      border-radius: 6px 6px 3px 3px;
      background: #E8ECEA;
      transition: background 0.15s ease;
    }

    .adm-chart__col:hover .adm-chart__bar {
      background: #C8D0CC;
    }

    .adm-chart__col.is-today .adm-chart__bar {
      background: var(--adm-ink);
    }

    .adm-chart__col.is-today .adm-chart__val,
    .adm-chart__col.is-today .adm-chart__day {
      color: var(--adm-text);
      font-weight: 600;
    }

    .adm-chart__day {
      font-size: 11.5px;
      color: var(--adm-muted);
    }

    /* ---------- Drawer ---------- */
    .adm-backdrop {
      position: fixed;
      inset: 0;
      z-index: 1000;
      background: rgba(11, 13, 12, 0.32);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.2s ease;
    }

    .adm-drawer {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      z-index: 1001;
      width: 480px;
      max-width: 100vw;
      display: flex;
      flex-direction: column;
      background: #FFFFFF;
      border-left: 1px solid var(--adm-border);
      transform: translateX(100%);
      transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .adm-drawer.js-order-drawer {
      width: 580px;
    }
    .adm-drawer.js-prod-drawer {
      width: 980px;
      max-width: 96vw;
    }
    .adm-drawer.js-page-drawer {
      width: 780px;
      max-width: 96vw;
    }

    body.is-drawer-open .adm-backdrop,
    body.is-prod-drawer-open .adm-backdrop,
    body.is-cat-drawer-open .adm-backdrop,
    body.is-cust-drawer-open .adm-backdrop,
    body.is-user-drawer-open .adm-backdrop,
    body.is-page-drawer-open .adm-backdrop,
    body.is-nav-open .adm-backdrop { opacity: 1; pointer-events: auto; }
    body.is-drawer-open .adm-drawer.js-order-drawer { transform: translateX(0); }
    body.is-prod-drawer-open .adm-drawer.js-prod-drawer { transform: translateX(0); }
    body.is-cat-drawer-open .adm-drawer.js-cat-drawer { transform: translateX(0); }
    body.is-cust-drawer-open .adm-drawer.js-cust-drawer { transform: translateX(0); }
    body.is-user-drawer-open .adm-drawer.js-user-drawer { transform: translateX(0); }
    body.is-page-drawer-open .adm-drawer.js-page-drawer { transform: translateX(0); }

    /* Bulk Action Bar */
    .adm-bulk-bar {
      display: none;
      align-items: center;
      gap: 12px;
      background: #F0FDF4;
      border: 1px solid #BBF7D0;
      padding: 10px 16px;
      border-radius: 8px;
      margin-bottom: 12px;
    }
    .adm-bulk-bar.is-visible { display: flex !important; }

    /* Column Show/Hide Dropdown */
    .adm-col-menu-wrap { position: relative; display: inline-block; }
    .adm-col-dropdown {
      display: none;
      position: absolute;
      top: calc(100% + 6px);
      right: 0;
      z-index: 1050;
      background: #FFFFFF;
      border: 1px solid var(--adm-border);
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      padding: 12px 16px;
      min-width: 200px;
    }
    .adm-col-dropdown.is-open { display: block !important; }
    .adm-col-dropdown label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      padding: 5px 0;
      cursor: pointer;
      user-select: none;
      color: var(--adm-text-2);
    }
    .adm-col-dropdown label:hover { color: var(--adm-ink); font-weight: 500; }

    /* Clean Checkbox */
    input[type="checkbox"].adm-cb {
      width: 17px;
      height: 17px;
      accent-color: #0B0D0C;
      cursor: pointer;
      vertical-align: middle;
      border-radius: 4px;
    }

    /* Column Hiding Rules */
    .table-orders.hide-col-id [data-col="id"],
    .table-orders.hide-col-customer [data-col="customer"],
    .table-orders.hide-col-items [data-col="items"],
    .table-orders.hide-col-total [data-col="total"],
    .table-orders.hide-col-risk [data-col="risk"],
    .table-orders.hide-col-courier [data-col="courier"],
    .table-orders.hide-col-status [data-col="status"],
    .table-orders.hide-col-actions [data-col="actions"] { display: none !important; }

    .table-products.hide-col-product [data-col="product"],
    .table-products.hide-col-category [data-col="category"],
    .table-products.hide-col-sizes [data-col="sizes"],
    .table-products.hide-col-stock [data-col="stock"],
    .table-products.hide-col-price [data-col="price"],
    .table-products.hide-col-actions [data-col="actions"] { display: none !important; }

    .table-categories.hide-col-name [data-col="name"],
    .table-categories.hide-col-slug [data-col="slug"],
    .table-categories.hide-col-type [data-col="type"],
    .table-categories.hide-col-sort [data-col="sort"],
    .table-categories.hide-col-count [data-col="count"],
    .table-categories.hide-col-status [data-col="status"],
    .table-categories.hide-col-actions [data-col="actions"] { display: none !important; }

    .table-customers.hide-col-name [data-col="name"],
    .table-customers.hide-col-phone [data-col="phone"],
    .table-customers.hide-col-location [data-col="location"],
    .table-customers.hide-col-orders [data-col="orders"],
    .table-customers.hide-col-spent [data-col="spent"],
    .table-customers.hide-col-ratio [data-col="ratio"],
    .table-customers.hide-col-status [data-col="status"],
    .table-customers.hide-col-actions [data-col="actions"] { display: none !important; }

    .table-users.hide-col-user [data-col="user"],
    .table-users.hide-col-email [data-col="email"],
    .table-users.hide-col-phone [data-col="phone"],
    .table-users.hide-col-role [data-col="role"],
    .table-users.hide-col-status [data-col="status"],
    .table-users.hide-col-created [data-col="created"],
    .table-users.hide-col-actions [data-col="actions"] { display: none !important; }

    .adm-drawer__head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
      padding: 18px 20px;
      border-bottom: 1px solid var(--adm-border);
    }

    .adm-drawer__body {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      display: grid;
      align-content: start;
      gap: 22px;
    }

    .adm-drawer__foot {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
      padding: 14px 20px;
      border-top: 1px solid var(--adm-border);
    }

    .adm-section-label { font-size: 12px; font-weight: 500; color: var(--adm-muted); margin-bottom: 8px; }

    .adm-kv {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      padding: 4px 0;
      font-size: 13px;
      color: var(--adm-text-2);
    }

    .adm-kv strong { color: var(--adm-text); font-weight: 500; }

    .adm-kv--total {
      margin-top: 6px;
      padding-top: 10px;
      border-top: 1px solid var(--adm-border);
      font-size: 14px;
      color: var(--adm-text);
      font-weight: 600;
    }

    .adm-kv--total strong { font-weight: 600; }

    .adm-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid var(--adm-border);
    }

    .adm-item:last-child { border-bottom: none; }

    .adm-item img {
      width: 44px;
      height: 52px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid var(--adm-border);
      flex-shrink: 0;
    }

    .adm-quick-actions { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }

    /* ---------- Toast ---------- */
    .adm-toast {
      position: fixed;
      left: 50%;
      bottom: 24px;
      z-index: 2000;
      display: flex;
      align-items: center;
      gap: 10px;
      max-width: calc(100% - 32px);
      padding: 12px 16px;
      border-radius: 10px;
      background: var(--adm-ink);
      color: #FFFFFF;
      font-size: 13px;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
      opacity: 0;
      pointer-events: none;
      transform: translate(-50%, 12px);
      transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .adm-toast.is-visible { opacity: 1; transform: translate(-50%, 0); }
    .adm-toast__dot { width: 8px; height: 8px; border-radius: 50%; background: var(--adm-brand); flex-shrink: 0; }
    .adm-toast__sub { color: #A3ABA7; font-size: 12.5px; }

    /* ---------- Bottom nav (mobile) ---------- */
    .adm-bottom-nav { display: none; }

    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */
    @media (max-width: 1180px) {
      .adm-order-head { display: none; }
      .adm-order-row {
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-areas:
          "id status"
          "cust total"
          "items items"
          "risk actions";
        gap: 10px 16px;
        padding-top: 16px;
        padding-bottom: 16px;
      }
      .adm-order-row .c-id { grid-area: id; }
      .adm-order-row .c-cust { grid-area: cust; }
      .adm-order-row .c-items { grid-area: items; }
      .adm-order-row .c-total { grid-area: total; text-align: right; }
      .adm-order-row .c-risk { grid-area: risk; }
      .adm-order-row .c-status { grid-area: status; justify-self: end; }
      .adm-order-row .c-actions { grid-area: actions; }

      .adm-prod-head { display: none; }
      .adm-prod-row {
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-areas:
          "prod price"
          "sizes sizes"
          "cat stock";
        gap: 10px 16px;
        padding-top: 14px;
        padding-bottom: 14px;
      }
      .adm-prod-row .c-prod { grid-area: prod; }
      .adm-prod-row .c-cat { grid-area: cat; }
      .adm-prod-row .c-sizes { grid-area: sizes; }
      .adm-prod-row .c-price { grid-area: price; text-align: right; }
      .adm-prod-row .c-stock { grid-area: stock; justify-self: end; }
      .adm-prod-row .c-link { display: none; }

      .adm-split { grid-template-columns: 1fr; }
    }

    @media (max-width: 960px) {
      body.adm { padding-bottom: 68px; }
      .adm-layout { grid-template-columns: 1fr; }

      .adm-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 1100;
        width: 272px;
        transform: translateX(-100%);
        transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
      }

      body.is-nav-open .adm-sidebar { transform: translateX(0); }

      .adm-topbar {
        height: 56px;
        padding: 0 14px;
        background: var(--adm-ink);
        border-bottom: 1px solid #1A1F1C;
      }

      .adm-topbar__menu {
        display: inline-grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        color: #FFFFFF;
        background: rgba(255, 255, 255, 0.06);
      }

      .adm-topbar__menu svg { width: 18px; height: 18px; }
      .adm-topbar__logo { display: block; height: 24px; width: auto; }
      .adm-topbar__heading { display: none; }
      .adm-topbar__actions .adm-btn--secondary,
      .adm-topbar__actions .adm-avatar { display: none; }

      .adm-content { padding: 16px 14px 32px; }
      .adm-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin-bottom: 16px; }
      .adm-kpi { padding: 14px; }
      .adm-kpi__value { font-size: 22px; }

      .adm-mobile-title {
        display: block !important;
        font-size: 18px;
        font-weight: 600;
        letter-spacing: -0.01em;
        margin-bottom: 12px;
      }

      .adm-bottom-nav {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 900;
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        padding: 6px 6px 8px;
        background: var(--adm-ink);
        border-top: 1px solid #1A1F1C;
      }

      .adm-bottom-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        padding: 6px 2px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 500;
        color: #8B938F;
      }

      .adm-bottom-btn svg { width: 19px; height: 19px; }
      .adm-bottom-btn.is-active { color: #FFFFFF; background: rgba(255, 255, 255, 0.06); }
      .adm-bottom-btn.is-active svg { color: var(--adm-brand); }

      .adm-toast { bottom: 84px; }
    }

    @media (max-width: 640px) {
      .adm-form { grid-template-columns: 1fr; }
      .adm-toolbar { padding: 12px 14px; }
      .adm-toolbar__group, .adm-search { width: 100%; }
      .adm-toolbar__group .adm-btn { flex: 1; }
      .adm-card__head, .adm-card__body { padding-left: 14px; padding-right: 14px; }
      .adm-order-row, .adm-prod-row { padding-left: 14px; padding-right: 14px; }
      .adm-tabs { padding: 0 8px; }
      .adm-print-row {
        grid-template-columns: 44px minmax(0, 1fr);
        padding: 14px;
      }
      .adm-print-row .adm-print-label,
      .adm-print-row .adm-btn { grid-column: 1 / -1; }
      .adm-table th, .adm-table td { padding-left: 14px; padding-right: 14px; }
      .adm-drawer { width: 100%; }
      .adm-coupons-toolbar {
        width: 100% !important;
        display: flex !important;
        flex-direction: row !important;
        gap: 8px !important;
        margin-top: 4px;
      }
      .adm-coupons-toolbar .js-coupon-search-input {
        flex: 1 1 140px !important;
        width: auto !important;
        min-width: 0 !important;
      }
      .adm-coupons-toolbar .js-open-coupon-modal {
        flex-shrink: 0 !important;
        white-space: nowrap !important;
      }
    }
    @media (max-width: 380px) {
      .adm-coupons-toolbar {
        flex-direction: column !important;
        gap: 8px !important;
      }
      .adm-coupons-toolbar .js-coupon-search-input,
      .adm-coupons-toolbar .js-open-coupon-modal {
        width: 100% !important;
        justify-content: center !important;
      }
    }

    /* ==========================================================================
       MEDIA GALLERY (Mobile-First Compact Media Library Grid)
       ========================================================================== */
    .adm-gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
      gap: 12px;
    }
    @media (max-width: 640px) {
      .adm-gallery-grid {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 8px !important;
      }
      .adm-gallery-stats {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 8px !important;
        margin-bottom: 12px !important;
      }
      .adm-gallery-stats .adm-card {
        padding: 10px 8px !important;
        text-align: center;
      }
      .adm-gallery-stats .num {
        font-size: 18px !important;
        margin-top: 2px !important;
      }
      .adm-gallery-stats .adm-card div:first-child {
        font-size: 10px !important;
      }
      .adm-gallery-stats .adm-card div:last-child {
        display: none !important;
      }
      .adm-gallery-uploader-card {
        margin-bottom: 12px !important;
      }
      .adm-gallery-uploader-card .adm-media-uploader {
        padding: 16px 12px !important;
      }
    }
    @media (max-width: 380px) {
      .adm-gallery-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 6px !important;
      }
    }

    .adm-gallery-item {
      position: relative;
      aspect-ratio: 1 / 1;
      border: 1.5px solid var(--adm-border);
      border-radius: 8px;
      overflow: hidden;
      background: #0F1311;
      cursor: pointer;
      transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
      user-select: none;
    }
    .adm-gallery-item:hover {
      border-color: #22C55E;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.35);
    }
    .adm-gallery-item.is-selected {
      border-color: #22C55E;
      box-shadow: 0 0 0 2px rgba(34,197,94,0.45);
    }
    .adm-gallery-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      pointer-events: none;
    }
    .adm-gallery-item__cb {
      position: absolute;
      top: 6px;
      left: 6px;
      z-index: 3;
      width: 17px;
      height: 17px;
      accent-color: #22C55E;
      cursor: pointer;
    }
    .adm-gallery-item__badge {
      position: absolute;
      top: 6px;
      right: 6px;
      z-index: 2;
      background: rgba(10, 12, 11, 0.82);
      backdrop-filter: blur(4px);
      color: #22C55E;
      font-size: 9px;
      font-weight: 800;
      padding: 2px 5px;
      border-radius: 4px;
      letter-spacing: 0.5px;
      line-height: 1;
      border: 1px solid rgba(34,197,94,0.3);
    }
    .adm-gallery-item__meta {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 6px 8px;
      background: linear-gradient(0deg, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.6) 60%, transparent 100%);
      color: #FFFFFF;
      font-size: 10.5px;
      font-weight: 600;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      pointer-events: none;
    }
    .adm-gallery-item__actions {
      position: absolute;
      inset: 0;
      background: rgba(10, 12, 11, 0.78);
      backdrop-filter: blur(2px);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 6px;
      opacity: 0;
      pointer-events: none; /* Crucial: never intercept clicks/touches when invisible */
      transition: opacity 0.16s ease;
      z-index: 4;
    }
    @media (hover: hover) {
      .adm-gallery-item:hover .adm-gallery-item__actions {
        opacity: 1;
        pointer-events: auto; /* Allow clicks only on actual mouse hover */
      }
    }
    @media (max-width: 768px) {
      .adm-gallery-item__actions {
        display: none !important; /* On mobile/touch devices, completely disable hover overlay so thumbnail tap opens photo sheet */
      }
    }
    .adm-gallery-strip-item {
      position: relative;
      width: 76px;
      height: 76px;
      border-radius: 8px;
      overflow: hidden;
      border: 2px solid #2B332E;
      background: #0F1311;
      flex-shrink: 0;
    }
    .adm-gallery-strip-item.is-back-view {
      border-color: #22C55E;
    }
    .adm-gallery-strip-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .adm-gallery-strip-item .adm-strip-actions {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.65);
      opacity: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 4px;
      transition: opacity 0.15s ease;
    }
    .adm-gallery-strip-item:hover .adm-strip-actions {
      opacity: 1;
    }
    .adm-strip-btn {
      background: #111513;
      border: 1px solid #374151;
      color: #fff;
      font-size: 10px;
      font-weight: 600;
      padding: 2px 6px;
      border-radius: 4px;
      cursor: pointer;
    }
    .adm-strip-btn:hover {
      background: #22C55E;
      color: #000;
      border-color: #22C55E;
    }
    .adm-strip-btn--danger:hover {
      background: #EF4444;
      color: #fff;
      border-color: #EF4444;
    }
    .adm-modal-tile:hover {
      border-color: #22C55E !important;
      transform: scale(1.02);
    }

    /* ==========================================================================
       CMS PAGES RICH TEXT EDITOR & MULTI-LEVEL MENU BUILDER
       ========================================================================== */
    .adm-rte-wrap {
      border: 1px solid var(--adm-border-strong);
      border-radius: 10px;
      background: var(--adm-surface);
      overflow: hidden;
    }

    .adm-rte-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 4px;
      padding: 8px 10px;
      background: var(--adm-subtle);
      border-bottom: 1px solid var(--adm-border);
    }

    .adm-rte-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 30px;
      height: 30px;
      padding: 0 9px;
      border-radius: 6px;
      border: 1px solid var(--adm-border);
      background: var(--adm-surface);
      color: var(--adm-text);
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.14s ease;
    }

    .adm-rte-btn:hover {
      border-color: #22C55E;
      background: #F0FDF4;
    }

    .adm-rte-btn.is-active {
      background: #0B0D0C;
      color: #04F404;
      border-color: #0B0D0C;
    }

    .adm-rte-sep {
      width: 1px;
      height: 18px;
      background: var(--adm-border-strong);
      margin: 0 3px;
    }

    .adm-rte-editor {
      min-height: 240px;
      max-height: 420px;
      overflow-y: auto;
      padding: 14px 16px;
      font-size: 14px;
      line-height: 1.65;
      color: var(--adm-text);
      outline: none;
    }

    .adm-rte-editor h2 {
      font-size: 18px;
      font-weight: 700;
      margin: 14px 0 6px;
      color: #0B0D0C;
    }

    .adm-rte-editor h3 {
      font-size: 15.5px;
      font-weight: 700;
      margin: 12px 0 4px;
      color: #0B0D0C;
    }

    .adm-rte-editor p {
      margin: 0 0 10px;
    }

    .adm-rte-editor ul,
    .adm-rte-editor ol {
      margin: 0 0 10px 20px;
      padding: 0;
    }

    .adm-rte-editor blockquote {
      margin: 10px 0;
      padding: 10px 14px;
      border-left: 3px solid #22C55E;
      background: #F4FAF6;
      border-radius: 6px;
    }

    .adm-rte-source {
      width: 100%;
      min-height: 240px;
      border: none;
      padding: 14px 16px;
      font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
      font-size: 12.5px;
      line-height: 1.55;
      color: #0B0D0C;
      background: #F8FAF9;
      resize: vertical;
      outline: none;
    }

    .adm-menu-loc-tabs {
      display: inline-flex;
      gap: 6px;
      padding: 4px;
      background: var(--adm-surface);
      border: 1px solid var(--adm-border);
      border-radius: 10px;
    }

    .adm-menu-loc-btn {
      padding: 7px 14px;
      border-radius: 7px;
      border: none;
      background: transparent;
      color: var(--adm-text-2);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.15s ease;
    }

    .adm-menu-loc-btn.is-active {
      background: #0B0D0C;
      color: #04F404;
    }

    .adm-menu-grid {
      display: grid;
      grid-template-columns: 350px minmax(0, 1fr);
      gap: 18px;
      align-items: start;
    }

    .adm-menu-tree {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .adm-menu-node {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      padding: 11px 14px;
      margin-left: calc(var(--depth, 0) * 26px);
      background: var(--adm-surface);
      border: 1px solid var(--adm-border);
      border-left: 3px solid var(--depth-accent, #0B0D0C);
      border-radius: 10px;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .adm-menu-node[data-depth="0"] { --depth-accent: #0B0D0C; }
    .adm-menu-node[data-depth="1"] { --depth-accent: #16A34A; background: #FAFDFB; }
    .adm-menu-node[data-depth="2"] { --depth-accent: #2563EB; background: #F8FAFF; }
    .adm-menu-node[data-depth="3"] { --depth-accent: #9333EA; background: #FCF9FF; }

    .adm-menu-node.is-inactive {
      opacity: 0.58;
      background: #F4F5F4;
    }

    .adm-menu-node.is-editing {
      border-color: #16A34A;
      box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.16);
    }

    .adm-menu-node__info {
      min-width: 0;
      flex: 1 1 auto;
    }

    .adm-menu-node__top {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 7px;
    }

    .adm-menu-depth-pill {
      display: inline-flex;
      align-items: center;
      padding: 2px 7px;
      border-radius: 5px;
      font-size: 10.5px;
      font-weight: 700;
      letter-spacing: 0.03em;
      background: #EEF2EF;
      color: #27322C;
    }

    .adm-menu-node[data-depth="1"] .adm-menu-depth-pill { background: #DCFCE7; color: #15803D; }
    .adm-menu-node[data-depth="2"] .adm-menu-depth-pill { background: #DBEAFE; color: #1D4ED8; }
    .adm-menu-node[data-depth="3"] .adm-menu-depth-pill { background: #F3E8FF; color: #7E22CE; }

    .adm-menu-node__title {
      font-weight: 700;
      font-size: 13.5px;
      color: var(--adm-text);
      overflow-wrap: anywhere;
    }

    .adm-menu-node__badge {
      padding: 1px 6px;
      border-radius: 4px;
      background: #04F404;
      color: #0B0D0C;
      font-size: 10px;
      font-weight: 800;
    }

    .adm-menu-node__url {
      margin-top: 3px;
      font-size: 11.5px;
      color: var(--adm-muted);
      overflow-wrap: anywhere;
    }

    .adm-menu-actions {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 4px;
      flex-shrink: 0;
    }

    .adm-mbtn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      height: 28px;
      min-width: 28px;
      padding: 0 7px;
      border-radius: 6px;
      border: 1px solid var(--adm-border-strong);
      background: var(--adm-surface);
      color: var(--adm-text);
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.14s ease;
    }

    .adm-mbtn:hover:not(:disabled) {
      border-color: #16A34A;
      background: #F0FDF4;
    }

    .adm-mbtn:disabled {
      opacity: 0.35;
      cursor: not-allowed;
    }

    .adm-mbtn--danger:hover:not(:disabled) {
      border-color: #EF4444;
      background: #FEF2F2;
      color: #B91C1C;
    }

    @media (max-width: 980px) {
      .adm-menu-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 640px) {
      .adm-menu-node {
        flex-direction: column;
        align-items: stretch;
        gap: 9px;
        margin-left: calc(var(--depth, 0) * 14px);
        padding: 10px 11px;
      }
      .adm-menu-actions {
        justify-content: flex-start;
      }
    }

  </style>
</head>
<body class="adm">

  <div class="adm-layout">
    <!-- ======================================================================
         SIDEBAR
         ====================================================================== -->
    <aside class="adm-sidebar" aria-label="Admin navigation">
      <div class="adm-brand">
        <a href="index.html" aria-label="RABEZ store home">
          <img src="{{ asset('assets/') }}/images/brand/rabez-neon-logo.png" alt="RABEZ">
        </a>
        <span class="adm-brand__tag">Admin</span>
      </div>

      <div class="adm-nav-group" style="margin-top:6px;">
        <button type="button" class="adm-nav-item is-active js-tab" data-tab="dashboard">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
          <span>Dashboard</span>
        </button>
      </div>

      <div class="adm-nav-group">
        <div class="adm-nav-label">Sales</div>
        <button type="button" class="adm-nav-item js-tab" data-tab="orders">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
          <span>Orders</span>
          <span class="adm-nav-count js-order-count">4</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="print-queue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          <span>Print queue</span>
          <span class="adm-nav-count js-print-count">2</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="pos-order">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          <span>Manual order</span>
        </button>
      </div>

      <div class="adm-nav-group">
        <div class="adm-nav-label">Catalog</div>
        <button type="button" class="adm-nav-item js-tab" data-tab="products">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
          <span>Products</span>
          <span class="adm-nav-count js-prod-count">18</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="add-product">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
          <span>Add product</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="categories">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
          <span>Categories</span>
          <span class="adm-nav-count">5</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="gallery">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          <span>Gallery</span>
          <span class="adm-nav-count js-gallery-count-badge">{{ count($media ?? []) }}</span>
        </button>
      </div>

      <div class="adm-nav-group">
        <div class="adm-nav-label">Store & Access</div>
        <button type="button" class="adm-nav-item js-tab" data-tab="customers">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>Customers</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="users">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><circle cx="19" cy="11" r="2"/></svg>
          <span>Users</span>
          <span class="adm-nav-count js-users-count-badge">{{ count($users ?? []) }}</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="discounts">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
          <span>Discount</span>
          <span class="adm-nav-count js-coupons-count-badge">{{ count($coupons ?? []) }}</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="pages">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          <span>Pages</span>
          <span class="adm-nav-count js-pages-count-badge">0</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="menus">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="20" y2="12"/><line x1="12" y1="18" x2="20" y2="18"/><circle cx="4.5" cy="12" r="1"/><circle cx="8.5" cy="18" r="1"/></svg>
          <span>Menu Builder</span>
        </button>
        <button type="button" class="adm-nav-item js-tab" data-tab="settings">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
          <span>Settings</span>
        </button>
      </div>

      <div class="adm-sidebar__foot" style="background:#111412; border:1px solid #1F2421; border-radius:10px; padding:12px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
          <span class="adm-avatar" style="width:32px; height:32px; font-size:11px; background:#16A34A; color:#fff; border:none;">
            {{ strtoupper(substr(Auth::user()->name ?? 'RZ', 0, 2)) }}
          </span>
          <div style="min-width:0; flex:1;">
            <div class="adm-sidebar__foot-title" style="font-size:12.5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ Auth::user()->name ?? 'RABEZ Admin' }}</div>
            <div style="font-size:11px; color:#9CA3AF; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ Auth::user()->email ?? 'admin@rabez.shop' }}</div>
          </div>
        </div>

        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
          <span class="adm-chip adm-chip--delivered" style="font-size:10px; padding:1px 6px;">
            {{ strtoupper(Auth::user()->role ?? 'Admin') }}
          </span>
          <span style="font-size:11px; color:#7D8581;">Verified Session</span>
        </div>

        <a href="{{ route('home') }}" target="_blank" class="adm-sidebar__link" style="display:flex; align-items:center; justify-content:space-between; padding:6px 0; color:#D1D5DB; text-decoration:none; font-size:12px; border-top:1px solid #1C211E;">
          <span>View Storefront</span>
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7"/><polyline points="7 7 17 7 17 17"/></svg>
        </a>

        <form action="{{ route('logout') }}" method="POST" style="margin-top:8px;">
          @csrf
          <button type="submit" class="adm-btn adm-btn--secondary adm-btn--sm" style="width:100%; justify-content:center; gap:6px; color:#F87171; border-color:rgba(239,68,68,0.25); background:rgba(239,68,68,0.08); font-size:12px; padding:6px 10px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Sign Out
          </button>
        </form>
      </div>
    </aside>

    <!-- ======================================================================
         MAIN
         ====================================================================== -->
    <div class="adm-main">
      <header class="adm-topbar">
        <div class="adm-topbar__left">
          <button type="button" class="adm-topbar__menu js-nav-toggle" aria-label="Open menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
          </button>
          <img src="{{ asset('assets/') }}/images/brand/rabez-neon-logo.png" alt="RABEZ" class="adm-topbar__logo">
          <div class="adm-topbar__heading">
            <h1 class="adm-topbar__title js-page-title">Dashboard</h1>
            <div class="adm-topbar__sub">
              <span class="adm-live"></span>
              <span class="js-page-sub">Store overview &amp; live activity</span>
            </div>
          </div>
        </div>
        <div class="adm-topbar__actions">
          <button type="button" class="adm-btn adm-btn--secondary js-tab" data-tab="pos-order">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New order
          </button>
          <button type="button" class="adm-btn adm-btn--primary js-tab" data-tab="add-product">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add product
          </button>
          <a href="{{ route('logout') }}" class="adm-btn adm-btn--secondary adm-btn--sm" title="Sign out" style="padding:6px 10px; color:#F87171; font-size:12px; display:inline-flex; align-items:center; gap:4px; text-decoration:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Sign Out
          </a>
          <span class="adm-avatar" title="{{ Auth::user()->name ?? 'RABEZ Admin' }}">
            {{ strtoupper(substr(Auth::user()->name ?? 'RZ', 0, 2)) }}
          </span>
        </div>
      </header>

      <main class="adm-content">
        <h2 class="adm-mobile-title js-page-title" style="display:none;">Dashboard</h2>

        <!-- ==================================================================
             DASHBOARD (OVERVIEW)
             ================================================================== -->
        <section class="adm-panel is-active" data-panel="dashboard">
          <div class="adm-kpis">
            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Revenue today</span>
                <span class="adm-kpi__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></span>
              </div>
              <div class="adm-kpi__value js-kpi-revenue">৳ {{ number_format($revenueToday ?? 0) }}</div>
              <div class="adm-kpi__foot"><span class="adm-up js-kpi-revenue-trend">&#10003; Realtime</span> live sales</div>
            </div>
            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Open orders</span>
                <span class="adm-kpi__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg></span>
              </div>
              <div class="adm-kpi__value js-kpi-open">{{ $openOrdersCount ?? 0 }}</div>
              <div class="adm-kpi__foot js-kpi-open-sub">{{ ($openOrdersCount ?? 0) > 0 ? ($openOrdersCount . ' orders to process') : 'No open orders' }}</div>
            </div>
            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>COD with courier</span>
                <span class="adm-kpi__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></span>
              </div>
              <div class="adm-kpi__value js-kpi-cod">৳ {{ number_format($codWithCourier ?? 0) }}</div>
              <div class="adm-kpi__foot">Settles in 2-3 days</div>
            </div>
            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Products live</span>
                <span class="adm-kpi__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg></span>
              </div>
              <div class="adm-kpi__value js-kpi-products">{{ $productsCount ?? count($products ?? []) }}</div>
              <div class="adm-kpi__foot">Jerseys &amp; winter hoodies</div>
            </div>
          </div>

          <div class="adm-split" style="margin-bottom:20px;">
            <div class="adm-card">
              <div class="adm-card__head">
                <div>
                  <h2 class="adm-card__title">Revenue &middot; Last 7 days</h2>
                  <p class="adm-card__desc js-chart-desc">Total ৳ 0 across 0 orders</p>
                </div>
                <span class="adm-chip js-chart-chip" style="background:rgba(34,197,94,0.12); color:#22c55e;">Live Analytics</span>
              </div>
              <div class="adm-card__body">
                <div class="adm-chart js-revenue-chart">
                  <div class="adm-chart__col">
                    <span class="adm-chart__val">0</span>
                    <div class="adm-chart__bar" style="height:0%;"></div>
                    <span class="adm-chart__day">Fri</span>
                  </div>
                  <div class="adm-chart__col">
                    <span class="adm-chart__val">0</span>
                    <div class="adm-chart__bar" style="height:0%;"></div>
                    <span class="adm-chart__day">Sat</span>
                  </div>
                  <div class="adm-chart__col">
                    <span class="adm-chart__val">0</span>
                    <div class="adm-chart__bar" style="height:0%;"></div>
                    <span class="adm-chart__day">Sun</span>
                  </div>
                  <div class="adm-chart__col">
                    <span class="adm-chart__val">0</span>
                    <div class="adm-chart__bar" style="height:0%;"></div>
                    <span class="adm-chart__day">Mon</span>
                  </div>
                  <div class="adm-chart__col">
                    <span class="adm-chart__val">0</span>
                    <div class="adm-chart__bar" style="height:0%;"></div>
                    <span class="adm-chart__day">Tue</span>
                  </div>
                  <div class="adm-chart__col">
                    <span class="adm-chart__val">0</span>
                    <div class="adm-chart__bar" style="height:0%;"></div>
                    <span class="adm-chart__day">Wed</span>
                  </div>
                  <div class="adm-chart__col is-today">
                    <span class="adm-chart__val">0</span>
                    <div class="adm-chart__bar" style="height:0%;"></div>
                    <span class="adm-chart__day">Today</span>
                  </div>
                </div>

                <div class="adm-rows js-channel-breakdown" style="margin-top:18px; padding-top:14px; border-top:1px solid var(--adm-border);">
                  <div><span>Website COD</span><strong class="num js-channel-web">৳ 0 (0%)</strong></div>
                  <div><span>Facebook &amp; WhatsApp</span><strong class="num js-channel-social">৳ 0 (0%)</strong></div>
                  <div><span>Barishal showroom</span><strong class="num js-channel-pos">৳ 0 (0%)</strong></div>
                </div>
              </div>
            </div>

            <div class="adm-card">
              <div class="adm-card__head">
                <div>
                  <h2 class="adm-card__title">Top selling items</h2>
                  <p class="adm-card__desc">Most ordered kits</p>
                </div>
                <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-tab" data-tab="products">All products</button>
              </div>
              <div class="adm-card__body js-top-selling-body" style="padding-top:10px; padding-bottom:10px;">
                <div style="text-align:center; padding:38px 16px; color:var(--adm-muted); font-size:13px; line-height:1.5;">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" style="margin-bottom:8px; opacity:0.5;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                  <div>No sales recorded yet.</div>
                  <div style="font-size:12px; margin-top:2px;">Your best-selling kits will rank here automatically as customer orders are placed.</div>
                </div>
              </div>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card__head">
              <div>
                <h2 class="adm-card__title">Recent orders</h2>
                <p class="adm-card__desc">Click any order to view details or book courier.</p>
              </div>
              <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-tab" data-tab="orders">View all orders</button>
            </div>
            <div class="adm-order-head adm-list-head-row">
              <span>Order</span>
              <span>Customer</span>
              <span>Items</span>
              <span>Total</span>
              <span>Delivery rate</span>
              <span>Status</span>
              <span></span>
            </div>
            <div id="adm-recent-orders"></div>
          </div>
        </section>

        <!-- ==================================================================
             ORDERS
             ================================================================== -->
        <section class="adm-panel" data-panel="orders">
          <div class="adm-card">
            <div class="adm-tabs" role="tablist">
              <button type="button" class="adm-tab is-active js-order-filter" data-status="all">All <span class="adm-tab__count" data-count="all">0</span></button>
              <button type="button" class="adm-tab js-order-filter" data-status="Pending">Pending <span class="adm-tab__count" data-count="Pending">0</span></button>
              <button type="button" class="adm-tab js-order-filter" data-status="Printing">Printing <span class="adm-tab__count" data-count="Printing">0</span></button>
              <button type="button" class="adm-tab js-order-filter" data-status="Shipped">In courier <span class="adm-tab__count" data-count="Shipped">0</span></button>
              <button type="button" class="adm-tab js-order-filter" data-status="Delivered">Delivered <span class="adm-tab__count" data-count="Delivered">0</span></button>
            </div>
            <div class="adm-toolbar">
              <label class="adm-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="search" class="js-order-search" placeholder="Search order, name or phone">
              </label>
              <div class="adm-toolbar__group">
                <!-- Column Show/Hide Menu -->
                <div class="adm-col-menu-wrap">
                  <button type="button" class="adm-btn adm-btn--secondary js-toggle-col-menu" data-target="cols-orders" title="Show or hide columns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                    Columns
                  </button>
                  <div id="cols-orders" class="adm-col-dropdown">
                    <div style="font-size:11px; font-weight:700; color:var(--adm-muted); margin-bottom:8px; text-transform:uppercase;">Show Columns</div>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="orders" data-col="id" checked> Order ID</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="orders" data-col="customer" checked> Customer</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="orders" data-col="items" checked> Items</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="orders" data-col="total" checked> Total</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="orders" data-col="risk" checked> Delivery Rate</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="orders" data-col="courier" checked> Courier</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="orders" data-col="status" checked> Status</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="orders" data-col="actions" checked> Actions</label>
                  </div>
                </div>

                <button type="button" class="adm-btn adm-btn--secondary js-refresh-orders" title="Sync live orders from database">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="16" height="16"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                  Sync DB
                </button>
                <button type="button" class="adm-btn adm-btn--secondary js-bulk-book">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                  Book all pending
                </button>
              </div>
            </div>

            <!-- Bulk Selection Action Bar -->
            <div class="adm-bulk-bar js-bulk-bar-orders">
              <span style="font-weight:600; font-size:13px; color:#166534;"><span class="js-bulk-count-orders">0</span> orders selected</span>
              <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-bulk-delete-orders" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">
                🗑 Delete Selected
              </button>
              <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-bulk-ship-orders">
                🚚 Ship with Steadfast
              </button>
              <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-bulk-clear-orders" style="margin-left:auto;">
                Cancel
              </button>
            </div>

            <div class="adm-table-wrap">
              <table class="adm-table table-orders" id="table-orders">
                <thead>
                  <tr>
                    <th style="width:36px;"><input type="checkbox" class="adm-cb js-select-all" data-type="orders" title="Select All"></th>
                    <th data-col="id">Order</th>
                    <th data-col="customer">Customer</th>
                    <th data-col="items">Items</th>
                    <th data-col="total">Total</th>
                    <th data-col="risk">Delivery rate</th>
                    <th data-col="courier">Courier</th>
                    <th data-col="status">Status</th>
                    <th data-col="actions" style="text-align:right;">Actions</th>
                  </tr>
                </thead>
                <tbody id="adm-orders"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             PRODUCTS
             ================================================================== -->
        <section class="adm-panel" data-panel="products">
          <div class="adm-card">
            <div class="adm-toolbar">
              <div class="adm-filter-chips">
                <button type="button" class="adm-filter-chip is-active js-cat-filter" data-cat="all">All</button>
                <button type="button" class="adm-filter-chip js-cat-filter" data-cat="half-sleeve">Half sleeve</button>
                <button type="button" class="adm-filter-chip js-cat-filter" data-cat="full-sleeve">Full sleeve</button>
                <button type="button" class="adm-filter-chip js-cat-filter" data-cat="winter-hoodie">Winter hoodies</button>
              </div>
              <label class="adm-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="search" class="js-prod-search" placeholder="Search product or SKU">
              </label>

              <div class="adm-toolbar__group">
                <!-- Column Show/Hide Menu -->
                <div class="adm-col-menu-wrap">
                  <button type="button" class="adm-btn adm-btn--secondary js-toggle-col-menu" data-target="cols-products" title="Show or hide columns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                    Columns
                  </button>
                  <div id="cols-products" class="adm-col-dropdown">
                    <div style="font-size:11px; font-weight:700; color:var(--adm-muted); margin-bottom:8px; text-transform:uppercase;">Show Columns</div>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="products" data-col="product" checked> Product</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="products" data-col="category" checked> Category</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="products" data-col="sizes" checked> Sizes in stock</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="products" data-col="stock" checked> Inventory</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="products" data-col="price" checked> Price</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="products" data-col="actions" checked> Actions</label>
                  </div>
                </div>

                <button type="button" class="adm-btn adm-btn--primary js-tab" data-tab="add-product">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  New Product
                </button>
              </div>
            </div>

            <!-- Bulk Selection Action Bar -->
            <div class="adm-bulk-bar js-bulk-bar-products">
              <span style="font-weight:600; font-size:13px; color:#166534;"><span class="js-bulk-count-products">0</span> products selected</span>
              <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-bulk-delete-products" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">
                🗑 Delete Selected
              </button>
              <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-bulk-clear-products" style="margin-left:auto;">
                Cancel
              </button>
            </div>

            <div class="adm-table-wrap">
              <table class="adm-table table-products" id="table-products">
                <thead>
                  <tr>
                    <th style="width:36px;"><input type="checkbox" class="adm-cb js-select-all" data-type="products" title="Select All"></th>
                    <th data-col="product">Product</th>
                    <th data-col="category">Category</th>
                    <th data-col="sizes">Sizes in stock (tap to toggle)</th>
                    <th data-col="stock">Inventory</th>
                    <th data-col="price" style="text-align:right;">Price</th>
                    <th data-col="actions" style="text-align:right;">Actions</th>
                  </tr>
                </thead>
                <tbody id="adm-products"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             ADD PRODUCT
             ================================================================== -->
        <section class="adm-panel" data-panel="add-product">
          <div class="adm-split">
            <div class="adm-card">
              <div class="adm-card__head">
                <div>
                  <h2 class="adm-card__title">Product details</h2>
                  <p class="adm-card__desc">Sizes and price fill in automatically based on the category.</p>
                </div>
              </div>
              <div class="adm-card__body">
                <form id="adm-add-form" class="adm-form">
                                    <!-- 1. FEATURED IMAGE (FRONT VIEW) -->
                  <div class="adm-field adm-field--full" style="background:var(--adm-subtle); padding:16px; border-radius:10px; border:1px solid var(--adm-border);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                      <div>
                        <span class="adm-label" style="margin:0; font-weight:700; font-size:14px; color:var(--adm-ink);">1. Featured Image — Front View</span>
                        <div style="font-size:12px; color:var(--adm-muted); margin-top:2px;">Primary photo on catalog cards, cart &amp; initial view.</div>
                      </div>
                      <span style="font-size:11px; background:rgba(34,197,94,0.15); color:#22C55E; font-weight:700; padding:3px 8px; border-radius:4px;">FRONT VIEW</span>
                    </div>
                    <div style="display:flex; gap:16px; align-items:flex-start; flex-wrap:wrap;">
                      <div id="adm-add-featured-preview-box" style="width:130px; height:130px; border-radius:8px; border:2px dashed var(--adm-border-strong); background:var(--adm-surface); display:flex; align-items:center; justify-content:center; overflow:hidden; position:relative; flex-shrink:0;">
                        <img id="adm-add-featured-preview" src="/assets/images/products/messi-barca-arg-hoodie.jpg" alt="Featured Front" style="width:100%; height:100%; object-fit:cover;">
                      </div>
                      <div style="flex:1; min-width:200px; display:flex; flex-direction:column; gap:8px;">
                        <input type="hidden" id="adm-add-featured-url" value="/assets/images/products/messi-barca-arg-hoodie.jpg">
                        <input type="file" id="adm-add-featured-file" accept="image/*" hidden>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                          <button type="button" class="adm-btn adm-btn--primary adm-btn--sm" onclick="document.getElementById('adm-add-featured-file').click()">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            Upload Front Photo
                          </button>
                          <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm" onclick="chooseFeaturedForAdd()">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            Choose from Gallery
                          </button>
                        </div>
                        <div style="font-size:11.5px; color:var(--adm-muted); line-height:1.4;">
                          Auto WebP compression &amp; iPhone EXIF rotation enabled. High-MB files automatically reduced to &lt;250KB.
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- 2. GALLERY IMAGES (BACK VIEW & DETAILS) -->
                  <div class="adm-field adm-field--full" style="background:var(--adm-subtle); padding:16px; border-radius:10px; border:1px solid var(--adm-border); margin-top:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; flex-wrap:wrap; gap:8px;">
                      <div>
                        <span class="adm-label" style="margin:0; font-weight:700; font-size:14px; color:var(--adm-ink);">2. Product Gallery — Back View &amp; Angles</span>
                        <div style="font-size:12px; color:var(--adm-muted); margin-top:2px;">
                          1st image automatically becomes <strong style="color:#22C55E;">Back View</strong> for card hover &amp; 360 viewer.
                        </div>
                      </div>
                      <div style="display:flex; gap:8px;">
                        <input type="file" id="adm-add-gallery-file" multiple accept="image/*" hidden>
                        <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm" onclick="document.getElementById('adm-add-gallery-file').click()">
                          + Upload More
                        </button>
                        <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm" onclick="chooseGalleryForAdd()">
                          Pick from Gallery
                        </button>
                      </div>
                    </div>
                    <div id="adm-add-gallery-strip" style="display:flex; gap:10px; flex-wrap:wrap; min-height:86px; padding:10px; border-radius:8px; background:var(--adm-surface); border:1px dashed var(--adm-border-strong); align-items:center;">
                      <!-- Rendered by JS -->
                    </div>
                  </div>

                  <div class="adm-field adm-field--full">
                    <label class="adm-label" for="adm-name">Title</label>
                    <input type="text" id="adm-name" class="adm-input" required placeholder="e.g. Real Madrid #7 Winter Fleece Hoodie">
                  </div>

                  <div class="adm-field">
                    <label class="adm-label" for="adm-cat">Category</label>
                    <select id="adm-cat" class="adm-select">
                      <option value="half-sleeve">Jersey — Half sleeve</option>
                      <option value="full-sleeve">Jersey — Full sleeve</option>
                      <option value="winter-hoodie" selected>Winter hoodie</option>
                      <option value="footwear">Boots / Turf shoes</option>
                      <option value="tracksuit">Tracksuit</option>
                    </select>
                  </div>

                  <div class="adm-field">
                    <label class="adm-label" for="adm-club">Club / Team</label>
                    <select id="adm-club" class="adm-select">
                      <option value="real-madrid">Real Madrid</option>
                      <option value="barcelona">FC Barcelona</option>
                      <option value="argentina">Argentina</option>
                      <option value="arsenal">Arsenal</option>
                      <option value="man-utd">Manchester United</option>
                      <option value="bayern">Bayern Munich</option>
                      <option value="liverpool">Liverpool</option>
                      <option value="bangladesh">Bangladesh</option>
                    </select>
                  </div>

                  <div class="adm-field">
                    <label class="adm-label" for="adm-price">Sale price (৳)</label>
                    <input type="number" id="adm-price" class="adm-input num" value="1450" required>
                  </div>

                  <div class="adm-field">
                    <label class="adm-label" for="adm-reg-price">Regular price (৳) <span class="adm-hint">optional</span></label>
                    <input type="number" id="adm-reg-price" class="adm-input num" value="1690">
                  </div>

                  <div class="adm-field">
                    <label class="adm-label" for="adm-sku">SKU</label>
                    <input type="text" id="adm-sku" class="adm-input" placeholder="e.g. HOODIE-RMA-07">
                  </div>

                  <div class="adm-field">
                    <label class="adm-label" for="adm-fabric">Fabric</label>
                    <input type="text" id="adm-fabric" class="adm-input" placeholder="e.g. 320 GSM brushed fleece">
                  </div>

                  <div class="adm-field adm-field--full" style="background:var(--adm-subtle); padding:12px 14px; border-radius:8px; border:1px solid var(--adm-border);">
                    <label style="display:flex; align-items:center; justify-content:space-between; gap:12px; cursor:pointer; user-select:none;">
                      <div>
                        <div style="font-weight:700; font-size:13.5px; color:var(--adm-ink);">Enable Custom Name &amp; Number Print (+৳{{ intval($settings['custom_print_fee'] ?? 250) }})</div>
                        <div style="font-size:12px; color:var(--adm-muted); margin-top:2px;">Keep unchecked unless this specific product allows custom back name &amp; number printing.</div>
                      </div>
                      <input type="checkbox" id="adm-custom-print" style="width:18px; height:18px; accent-color:#04F404; cursor:pointer; flex-shrink:0;">
                    </label>
                  </div>

                  <div class="adm-field adm-field--full">
                    <span class="adm-label">Sizes in stock</span>
                    <div class="adm-sizes" id="adm-new-sizes"></div>
                    <span class="adm-hint">Tap a size to mark it out of stock.</span>
                  </div>

                  <!-- Descriptions for New Product -->
                  <div class="adm-field adm-field--full" style="background:var(--adm-subtle); padding:16px; border-radius:10px; border:1px solid var(--adm-border); display:flex; flex-direction:column; gap:16px;">
                    <div>
                      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <label class="adm-label" for="adm-short-desc" style="font-weight:700; margin:0;">
                          Short Description (Summary)
                        </label>
                        <span style="font-size:11px; color:var(--adm-muted);">Shown on product card, quick view &amp; summary</span>
                      </div>
                      <textarea id="adm-short-desc" class="adm-input js-dynamic-textarea" rows="3" style="min-height:90px; resize:vertical; line-height:1.5;" placeholder="e.g. Official Player Edition. Double-knit jacquard with moisture wicking."></textarea>
                    </div>

                    <div>
                      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <label class="adm-label" for="adm-long-desc" style="font-weight:700; margin:0;">
                          Detailed Long Description (Product Overview Tab)
                        </label>
                        <span style="font-size:11px; color:var(--adm-muted);">Shown on Single Product Page &bull; Full Overview Tab</span>
                      </div>
                      <textarea id="adm-long-desc" class="adm-input js-dynamic-textarea" rows="8" style="min-height:220px; resize:vertical; line-height:1.6; font-size:14px;" placeholder="Full authentic overview, technology, wash care guidelines, bullet points..."></textarea>
                    </div>
                  </div>

                  <div class="adm-form__actions">
                    <button type="reset" class="adm-btn adm-btn--secondary">Clear</button>
                    <button type="submit" class="adm-btn adm-btn--primary">Publish product</button>
                  </div>
                </form>
              </div>
            </div>

            <div class="adm-card">
              <div class="adm-card__head">
                <div>
                  <h2 class="adm-card__title">Default pricing</h2>
                  <p class="adm-card__desc">Applied when you pick a category.</p>
                </div>
              </div>
              <div class="adm-card__body adm-rows">
                <div><div><div class="adm-cell-main">Half sleeve jersey</div><div class="adm-cell-sub">S - XXL</div></div><strong class="num">৳ 1,150</strong></div>
                <div><div><div class="adm-cell-main">Full sleeve jersey</div><div class="adm-cell-sub">S - XXL</div></div><strong class="num">৳ 1,300</strong></div>
                <div><div><div class="adm-cell-main">Winter hoodie</div><div class="adm-cell-sub">S - XXL &middot; 320 GSM</div></div><strong class="num">৳ 1,450</strong></div>
                <div><div><div class="adm-cell-main">Boots / Turf shoes</div><div class="adm-cell-sub">EU 39 - 44</div></div><strong class="num">৳ 2,450</strong></div>
                <div><div><div class="adm-cell-main">Custom name &amp; number</div><div class="adm-cell-sub">Add-on</div></div><strong class="num">+ ৳ 250</strong></div>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             CATEGORIES
             ================================================================== -->
        <section class="adm-panel" data-panel="categories">
          <div class="adm-card">
            <div class="adm-toolbar">
              <label class="adm-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="search" class="js-cat-search" placeholder="Search category or slug">
              </label>

              <div class="adm-toolbar__group">
                <!-- Column Show/Hide Menu -->
                <div class="adm-col-menu-wrap">
                  <button type="button" class="adm-btn adm-btn--secondary js-toggle-col-menu" data-target="cols-categories" title="Show or hide columns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                    Columns
                  </button>
                  <div id="cols-categories" class="adm-col-dropdown">
                    <div style="font-size:11px; font-weight:700; color:var(--adm-muted); margin-bottom:8px; text-transform:uppercase;">Show Columns</div>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="categories" data-col="name" checked> Name</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="categories" data-col="slug" checked> Slug</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="categories" data-col="type" checked> Product Type</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="categories" data-col="sort" checked> Sort Order</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="categories" data-col="count" checked> Products Count</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="categories" data-col="status" checked> Status</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="categories" data-col="actions" checked> Actions</label>
                  </div>
                </div>

                <button type="button" class="adm-btn adm-btn--primary js-open-add-cat-btn">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  New Category
                </button>
              </div>
            </div>

            <!-- Bulk Selection Action Bar -->
            <div class="adm-bulk-bar js-bulk-bar-categories">
              <span style="font-weight:600; font-size:13px; color:#166534;"><span class="js-bulk-count-categories">0</span> categories selected</span>
              <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-bulk-delete-categories" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">
                🗑 Delete Selected
              </button>
              <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-bulk-clear-categories" style="margin-left:auto;">
                Cancel
              </button>
            </div>

            <div class="adm-table-wrap">
              <table class="adm-table table-categories" id="table-categories">
                <thead>
                  <tr>
                    <th style="width:36px;"><input type="checkbox" class="adm-cb js-select-all" data-type="categories" title="Select All"></th>
                    <th data-col="name">Name</th>
                    <th data-col="slug">Slug</th>
                    <th data-col="type">Product Type</th>
                    <th data-col="sort">Sort Order</th>
                    <th data-col="count">Products</th>
                    <th data-col="status">Status</th>
                    <th data-col="actions" style="text-align:right;">Actions</th>
                  </tr>
                </thead>
                <tbody id="adm-categories"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             MEDIA GALLERY & WEBP OPTIMIZATION ENGINE
             ================================================================== -->
        <section class="adm-panel" data-panel="gallery">
          <!-- Gallery Top Stats -->
          <div class="adm-gallery-stats" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:14px; margin-bottom:16px;">
            <div class="adm-card" style="padding:16px;">
              <div style="font-size:12px; font-weight:600; color:var(--adm-muted); text-transform:uppercase;">Total Media Files</div>
              <div class="num js-stat-media-total" style="font-size:26px; font-weight:800; color:#fff; margin-top:4px;">{{ count($media ?? []) }}</div>
              <div style="font-size:11px; color:#22C55E; margin-top:2px;">All jerseys &amp; uploads</div>
            </div>
            <div class="adm-card" style="padding:16px;">
              <div style="font-size:12px; font-weight:600; color:var(--adm-muted); text-transform:uppercase;">Auto WebP Engine</div>
              <div class="num" style="font-size:26px; font-weight:800; color:#22C55E; margin-top:4px;">Active</div>
              <div style="font-size:11px; color:var(--adm-muted); margin-top:2px;">80-90% lossless compression</div>
            </div>
            <div class="adm-card" style="padding:16px;">
              <div style="font-size:12px; font-weight:600; color:var(--adm-muted); text-transform:uppercase;">iPhone / EXIF Rotation</div>
              <div class="num" style="font-size:26px; font-weight:800; color:#3B82F6; margin-top:4px;">Auto-Fix</div>
              <div style="font-size:11px; color:var(--adm-muted); margin-top:2px;">Never upside-down or tilted</div>
            </div>
          </div>

          <!-- Drag & Drop / Mobile Photo Uploader Card -->
          <div class="adm-card adm-gallery-uploader-card" style="margin-bottom:18px;">
            <div class="adm-card__body" style="padding:18px;">
              <div class="adm-media-uploader" id="js-gallery-dropzone" style="border:2px dashed #2B332E; border-radius:10px; padding:22px 16px; text-align:center; background:#0F1311; cursor:pointer; transition:all 0.2s ease;">
                <input type="file" id="js-gallery-file-input" multiple accept="image/*" hidden>
                <div style="display:flex; flex-direction:column; align-items:center; gap:8px;">
                  <span style="display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:50%; background:rgba(34,197,94,0.12); color:#22C55E;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                    </svg>
                  </span>
                  <div>
                    <span style="font-weight:700; color:#fff; font-size:14px;">Upload Photos &bull; Auto-Convert to WebP</span>
                    <div style="font-size:11.5px; color:#7D8782; margin-top:2px;">
                      iPhone Photos, JPG, PNG &bull; Auto-compressed to &lt;250KB WebP
                    </div>
                  </div>
                  <button type="button" class="adm-btn adm-btn--primary adm-btn--sm" style="margin-top:2px;" onclick="document.getElementById('js-gallery-file-input').click(); event.stopPropagation();">
                    📷 Select Photos from Device
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Gallery List Card -->
          <div class="adm-card">
            <div class="adm-card__head" style="flex-wrap:wrap; gap:12px;">
              <div>
                <h2 class="adm-card__title">Media Library</h2>
                <p class="adm-card__desc">Tap any photo to view details, copy URL, or use in products.</p>
              </div>
              <div style="display:flex; align-items:center; gap:10px;">
                <input type="text" class="adm-input js-media-search" placeholder="Search photos..." style="width:180px; height:34px; font-size:12.5px;">
                <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-media-refresh-btn" title="Refresh Media">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                </button>
              </div>
            </div>

            <!-- Bulk action bar for Media -->
            <div class="adm-bulk-bar js-bulk-bar-media" style="margin:0 16px 12px; display:none;">
              <span class="adm-bulk-bar__count"><strong class="js-bulk-count-media">0</strong> selected</span>
              <button type="button" class="adm-btn adm-btn--danger adm-btn--sm" onclick="handleBulkDelete('media')">Delete Selected</button>
              <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm" onclick="clearBulkSelection('media')">Cancel</button>
            </div>

            <div class="adm-card__body">
              <div id="adm-gallery-grid" class="adm-gallery-grid">
                <!-- Rendered as compact square thumbnail tiles by JS -->
              </div>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             PRINT QUEUE
             ================================================================== -->
        <section class="adm-panel" data-panel="print-queue">
          <div class="adm-card">
            <div class="adm-card__head">
              <div>
                <h2 class="adm-card__title">Print queue</h2>
                <p class="adm-card__desc">Check spelling and number before heat-pressing.</p>
              </div>
              <span class="adm-chip adm-chip--printing js-print-chip">0 waiting</span>
            </div>
            <div id="adm-print-queue-list">
              <div style="text-align:center; padding:44px 20px; color:var(--adm-muted); font-size:13.5px;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:8px; opacity:0.6;"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                <div>No custom back prints waiting in the print queue.</div>
                <div style="font-size:12px; margin-top:4px;">When customers order custom jersey name &amp; number printing (+৳250), they will appear here.</div>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             MANUAL / POS ORDER
             ================================================================== -->
        <section class="adm-panel" data-panel="pos-order">
          <div class="adm-split">
            <div class="adm-card">
              <div class="adm-card__head">
                <div>
                  <h2 class="adm-card__title">Create order</h2>
                  <p class="adm-card__desc">For Facebook, WhatsApp or showroom orders.</p>
                </div>
              </div>
              <div class="adm-card__body">
                <form id="adm-pos-form" class="adm-form">
                  <div class="adm-field">
                    <label class="adm-label" for="pos-phone">Phone</label>
                    <input type="tel" id="pos-phone" class="adm-input num" required value="" placeholder="01XXXXXXXXX">
                  </div>
                  <div class="adm-field">
                    <label class="adm-label" for="pos-name">Customer name</label>
                    <input type="text" id="pos-name" class="adm-input" required placeholder="Full name">
                  </div>
                  <div class="adm-field adm-field--full">
                    <label class="adm-label" for="pos-address">Delivery address</label>
                    <input type="text" id="pos-address" class="adm-input" required placeholder="House, road, area, district">
                  </div>
                  <div class="adm-field adm-field--full">
                    <label class="adm-label" for="pos-product">Product</label>
                    <select id="pos-product" class="adm-select"></select>
                  </div>
                  <div class="adm-field">
                    <label class="adm-label" for="pos-size">Size</label>
                    <select id="pos-size" class="adm-select">
                      <option>S</option><option>M</option><option selected>L</option><option>XL</option><option>XXL</option>
                    </select>
                  </div>
                  <div class="adm-field">
                    <label class="adm-label" for="pos-zone">Delivery zone</label>
                    <select id="pos-zone" class="adm-select">
                      <option value="70">Inside Dhaka — ৳ 70</option>
                      <option value="130">Outside Dhaka — ৳ 130</option>
                      <option value="0">Showroom pickup — ৳ 0</option>
                    </select>
                  </div>
                  <div class="adm-field">
                    <label class="adm-label" for="pos-print">Name &amp; number <span class="adm-hint">+ ৳ 250, optional</span></label>
                    <input type="text" id="pos-print" class="adm-input" placeholder="e.g. RONALDO 7">
                  </div>
                  <div class="adm-field">
                    <label class="adm-label" for="pos-source">Channel</label>
                    <select id="pos-source" class="adm-select">
                      <option>Facebook</option>
                      <option>WhatsApp</option>
                      <option>Showroom</option>
                    </select>
                  </div>
                  <div class="adm-form__actions">
                    <button type="reset" class="adm-btn adm-btn--secondary">Clear</button>
                    <button type="submit" class="adm-btn adm-btn--primary">Create &amp; book Steadfast</button>
                  </div>
                </form>
              </div>
            </div>

            <div class="adm-card">
              <div class="adm-card__head">
                <div>
                  <h2 class="adm-card__title">Delivery history</h2>
                  <p class="adm-card__desc js-pos-phone-label">01715889900</p>
                </div>
              </div>
              <div class="adm-card__body">
                <div class="adm-callout" style="margin-bottom:16px;">
                  <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span class="adm-cell-main">Safe for COD</span>
                    <span class="adm-cell-main num" style="color:#15803D;">95%</span>
                  </div>
                  <div class="adm-cell-sub">19 of 20 parcels delivered</div>
                </div>
                <div class="adm-rows">
                  <div><span>Steadfast</span><span class="num">11 / 11</span></div>
                  <div><span>Pathao</span><span class="num">6 / 7</span></div>
                  <div><span>RedX</span><span class="num">2 / 2</span></div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             CUSTOMERS
             ================================================================== -->
        <section class="adm-panel" data-panel="customers">
          <div class="adm-card">
            <div class="adm-card__head">
              <div>
                <h2 class="adm-card__title">Check a phone number (Safe for COD)</h2>
                <p class="adm-card__desc">Live courier delivery history & risk analysis before shipping COD parcels.</p>
              </div>
            </div>
            <div class="adm-card__body">
              <form class="js-check-form" style="display:flex; gap:8px; flex-wrap:wrap; max-width:540px;">
                <input type="tel" class="adm-input num js-check-phone" style="flex:1; min-width:220px;" value="" placeholder="e.g. 017XXXXXXXX">
                <button type="submit" class="adm-btn adm-btn--primary">Check Risk</button>
              </form>
              <div class="js-check-result" style="margin-top:14px;"></div>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-toolbar">
              <label class="adm-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="search" class="js-cust-search" placeholder="Search customer, phone, or location">
              </label>

              <div class="adm-toolbar__group">
                <!-- Column Show/Hide Menu -->
                <div class="adm-col-menu-wrap">
                  <button type="button" class="adm-btn adm-btn--secondary js-toggle-col-menu" data-target="cols-customers" title="Show or hide columns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                    Columns
                  </button>
                  <div id="cols-customers" class="adm-col-dropdown">
                    <div style="font-size:11px; font-weight:700; color:var(--adm-muted); margin-bottom:8px; text-transform:uppercase;">Show Columns</div>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="customers" data-col="name" checked> Customer</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="customers" data-col="phone" checked> Phone</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="customers" data-col="location" checked> Location</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="customers" data-col="orders" checked> Orders</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="customers" data-col="spent" checked> Total Spent</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="customers" data-col="ratio" checked> Delivery Rate</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="customers" data-col="status" checked> Status</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="customers" data-col="actions" checked> Actions</label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Bulk Selection Action Bar -->
            <div class="adm-bulk-bar js-bulk-bar-customers">
              <span style="font-weight:600; font-size:13px; color:#166534;"><span class="js-bulk-count-customers">0</span> customers selected</span>
              <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-bulk-delete-customers" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">
                🗑 Delete Selected
              </button>
              <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-bulk-clear-customers" style="margin-left:auto;">
                Cancel
              </button>
            </div>

            <div class="adm-table-wrap">
              <table class="adm-table table-customers" id="table-customers">
                <thead>
                  <tr>
                    <th style="width:36px;"><input type="checkbox" class="adm-cb js-select-all" data-type="customers" title="Select All"></th>
                    <th data-col="name">Customer</th>
                    <th data-col="phone">Phone</th>
                    <th data-col="location">Location</th>
                    <th data-col="orders">Orders</th>
                    <th data-col="spent">Spent</th>
                    <th data-col="ratio">Delivery rate</th>
                    <th data-col="status">Status</th>
                    <th data-col="actions" style="text-align:right;">Actions</th>
                  </tr>
                </thead>
                <tbody id="adm-customers"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             USERS & ACCESS CONTROL
             ================================================================== -->
        <section class="adm-panel" data-panel="users">
          <!-- Role Stats Summary Cards -->
          <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:14px; margin-bottom:16px;">
            <div class="adm-card" style="padding:16px;">
              <div style="font-size:12px; font-weight:600; color:var(--adm-muted); text-transform:uppercase;">Total Registered</div>
              <div class="num js-stat-users-total" style="font-size:26px; font-weight:800; color:#fff; margin-top:4px;">{{ count($users ?? []) }}</div>
              <div style="font-size:11.5px; color:#9CA3AF; margin-top:2px;">All system &amp; customer accounts</div>
            </div>
            <div class="adm-card" style="padding:16px; border-left:3px solid #16A34A;">
              <div style="font-size:12px; font-weight:600; color:#22C55E; text-transform:uppercase;">⚡ Administrators</div>
              <div class="num js-stat-users-admin" style="font-size:26px; font-weight:800; color:#22C55E; margin-top:4px;">0</div>
              <div style="font-size:11.5px; color:#9CA3AF; margin-top:2px;">Full portal &amp; settings control</div>
            </div>
            <div class="adm-card" style="padding:16px; border-left:3px solid #38BDF8;">
              <div style="font-size:12px; font-weight:600; color:#38BDF8; text-transform:uppercase;">🛡️ Managers &amp; Staff</div>
              <div class="num js-stat-users-staff" style="font-size:26px; font-weight:800; color:#38BDF8; margin-top:4px;">0</div>
              <div style="font-size:11.5px; color:#9CA3AF; margin-top:2px;">Orders &amp; catalog operations</div>
            </div>
            <div class="adm-card" style="padding:16px; border-left:3px solid #94A3B8;">
              <div style="font-size:12px; font-weight:600; color:#CBD5E1; text-transform:uppercase;">👤 Customers / Members</div>
              <div class="num js-stat-users-customer" style="font-size:26px; font-weight:800; color:#CBD5E1; margin-top:4px;">0</div>
              <div style="font-size:11.5px; color:#9CA3AF; margin-top:2px;">Storefront registered buyers</div>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-toolbar">
              <label class="adm-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="search" class="js-user-search" placeholder="Search user by name, email, phone, or role...">
              </label>

              <div class="adm-filter-pills js-user-role-filters" style="display:flex; gap:6px; flex-wrap:wrap;">
                <button type="button" class="adm-pill is-active js-user-filter" data-role="all">All Roles</button>
                <button type="button" class="adm-pill js-user-filter" data-role="admin">Admins</button>
                <button type="button" class="adm-pill js-user-filter" data-role="staff">Staff &amp; Managers</button>
                <button type="button" class="adm-pill js-user-filter" data-role="customer">Customers</button>
              </div>

              <div class="adm-toolbar__group" style="margin-left:auto; display:flex; gap:8px;">
                <!-- Column Show/Hide Menu -->
                <div class="adm-col-menu-wrap">
                  <button type="button" class="adm-btn adm-btn--secondary js-toggle-col-menu" data-target="cols-users" title="Show or hide columns">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                    Columns
                  </button>
                  <div id="cols-users" class="adm-col-dropdown">
                    <div style="font-size:11px; font-weight:700; color:var(--adm-muted); margin-bottom:8px; text-transform:uppercase;">Show Columns</div>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="users" data-col="user" checked> User &amp; Name</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="users" data-col="email" checked> Email</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="users" data-col="phone" checked> Phone</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="users" data-col="role" checked> Role &amp; Access</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="users" data-col="status" checked> Status</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="users" data-col="created" checked> Created Date</label>
                    <label><input type="checkbox" class="adm-cb js-col-toggle" data-table="users" data-col="actions" checked> Actions</label>
                  </div>
                </div>

                <button type="button" class="adm-btn adm-btn--primary js-open-add-user-btn" style="background:#16A34A; color:#fff;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  Add User
                </button>
              </div>
            </div>

            <!-- Bulk Selection Action Bar -->
            <div class="adm-bulk-bar js-bulk-bar-users">
              <span style="font-weight:600; font-size:13px; color:#166534;"><span class="js-bulk-count-users">0</span> users selected</span>
              <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-bulk-delete-users" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">
                🗑 Delete Selected
              </button>
              <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-bulk-clear-users" style="margin-left:auto;">
                Cancel
              </button>
            </div>

            <div class="adm-table-wrap">
              <table class="adm-table table-users" id="table-users">
                <thead>
                  <tr>
                    <th style="width:36px;"><input type="checkbox" class="adm-cb js-select-all" data-type="users" title="Select All"></th>
                    <th data-col="user">User</th>
                    <th data-col="email">Email</th>
                    <th data-col="phone">Phone</th>
                    <th data-col="role">Role / Access</th>
                    <th data-col="status">Status</th>
                    <th data-col="created">Joined</th>
                    <th data-col="actions" style="text-align:right;">Actions</th>
                  </tr>
                </thead>
                <tbody id="adm-users"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             DISCOUNT & PROMOTIONAL COUPONS
             ================================================================== -->
        <section class="adm-panel" data-panel="discounts">
          <!-- Top KPI Statistics Cards -->
          <div class="adm-kpis" style="margin-bottom: 20px;">
            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Active Coupons</span>
                <span class="adm-kpi__icon" style="background:rgba(34,197,94,0.15); color:#22c55e;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </span>
              </div>
              <div class="adm-kpi__value js-stat-coupons-active" style="color:#04F404;">{{ isset($coupons) ? collect($coupons)->where('is_active', true)->count() : 0 }}</div>
              <div class="adm-kpi__foot"><span class="adm-up">&#10003; Live</span> on storefront &amp; cart</div>
            </div>

            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Total Coupons</span>
                <span class="adm-kpi__icon" style="background:rgba(59,130,246,0.15); color:#3b82f6;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                </span>
              </div>
              <div class="adm-kpi__value js-stat-coupons-total">{{ count($coupons ?? []) }}</div>
              <div class="adm-kpi__foot"><span class="js-stat-coupons-inactive-sub" style="color:#9CA3AF;">0 inactive / expired</span></div>
            </div>

            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Discount Types</span>
                <span class="adm-kpi__icon" style="background:rgba(234,179,8,0.15); color:#eab308;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </span>
              </div>
              <div class="adm-kpi__value js-stat-coupons-mix" style="font-size:22px; color:#FFFFFF;">Flat ৳ &amp; %</div>
              <div class="adm-kpi__foot"><span class="js-stat-coupons-mix-sub">Mixed promotional campaigns</span></div>
            </div>

            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Average Min. Spend</span>
                <span class="adm-kpi__icon" style="background:rgba(168,85,247,0.15); color:#a855f7;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M7 15h0M2 9.5h20"/></svg>
                </span>
              </div>
              <div class="adm-kpi__value js-stat-coupons-min-spend" style="color:#FFFFFF;">৳0</div>
              <div class="adm-kpi__foot">Protects profit margins</div>
            </div>
          </div>

          <!-- Coupons Management Card -->
          <div class="adm-card">
            <div class="adm-card__head" style="flex-wrap:wrap; gap:14px; align-items:center;">
              <div style="flex:1; min-width:0; max-width:100%;">
                <h2 class="adm-card__title" style="display:flex; align-items:center; flex-wrap:wrap; gap:8px;">
                  <span>Discount &amp; Promotional Coupons</span>
                  <span class="adm-badge adm-badge--success" style="font-size:11px; padding:3px 9px; border-radius:999px; background:rgba(34,197,94,0.15); color:#22c55e; border:1px solid rgba(34,197,94,0.3); font-weight:700;"><span class="js-coupons-count">0</span> Active</span>
                </h2>
                <p class="adm-card__desc">Create and manage coupon promo codes, set percentage or flat ৳ discounts, and minimum order requirements.</p>
              </div>
              <div class="adm-coupons-toolbar">
                <input type="text" class="adm-input js-coupon-search-input" placeholder="Search coupon code or note..." style="width:230px; height:36px; font-size:12.5px;">
                <button type="button" class="adm-btn js-open-coupon-modal" style="background:#04F404; color:#0A0C0B; font-weight:800; font-size:12.5px; padding:8px 16px; border-radius:6px; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:transform 0.15s, background 0.15s;" onmouseover="this.style.background='#22C55E'" onmouseout="this.style.background='#04F404'">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                  <span>New Coupon</span>
                </button>
              </div>
            </div>
            <div class="adm-card__body adm-card__body--flush">
              <div class="adm-table-wrap">
                <table class="adm-table" id="table-coupons" style="width:100%;">
                  <thead>
                    <tr>
                      <th style="min-width:180px;">Coupon Code</th>
                      <th style="min-width:130px;">Discount</th>
                      <th style="min-width:140px;">Min. Order Spend</th>
                      <th>Description / Note</th>
                      <th style="min-width:110px;">Status</th>
                      <th style="text-align:right; min-width:170px;">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="adm-coupons-list">
                    <!-- Rendered dynamically by JS -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             SETTINGS
             ================================================================== -->
        <section class="adm-panel" data-panel="settings">
          <div style="display:flex; flex-direction:column; gap:24px;">
            <div class="adm-card" style="width:100%;">
              <div class="adm-card__head">
                <div>
                  <h2 class="adm-card__title">Delivery &amp; Courier Settings</h2>
                  <p class="adm-card__desc">Configure shipping modes (Flat Rate / Round Figure vs Zone-Based), rates, and courier API keys.</p>
                </div>
              </div>
              <div class="adm-card__body">
                <form class="adm-form js-settings-form" style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:18px;">
                  <!-- Delivery Charge Mode (Full Width Highlight Block) -->
                  <div class="adm-field" style="grid-column:1 / -1; background:var(--adm-subtle); padding:16px; border-radius:10px; border:1px solid var(--adm-border);">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                      <label class="adm-label" style="font-weight:800; color:#22C55E; font-size:14px; margin:0;">Delivery Charge System</label>
                      <span class="adm-chip adm-chip--delivered" style="font-size:11px; padding:2px 8px;">Active Mode</span>
                    </div>
                    <select name="shipping_mode" id="setting-shipping-mode" class="adm-select" style="font-weight:700; font-size:13.5px; height:42px;">
                      <option value="flat" {{ ($settings['shipping_mode'] ?? 'flat') === 'flat' ? 'selected' : '' }}>Flat Rate — Round Figure ৳130 (All Over Bangladesh)</option>
                      <option value="zone" {{ ($settings['shipping_mode'] ?? 'flat') === 'zone' ? 'selected' : '' }}>Zone-Based (Inside Dhaka ৳70 vs Outside Dhaka ৳130)</option>
                    </select>
                    <span class="adm-cell-sub" style="margin-top:6px; display:block; color:#9CA3AF;">Choose 'Flat Rate' to charge one single round figure delivery fee for all districts, or 'Zone-Based' to let customers select their delivery zone.</span>
                  </div>

                  <!-- Flat Rate Delivery Fee (Round Figure) -->
                  <div class="adm-field js-shipping-flat-field" style="{{ ($settings['shipping_mode'] ?? 'flat') === 'zone' ? 'display:none;' : '' }} grid-column:1 / -1;">
                    <label class="adm-label" style="font-weight:700;">Flat Delivery Fee — All Over Bangladesh (৳)</label>
                    <input type="number" name="shipping_flat_fee" class="adm-input num" value="{{ $settings['shipping_flat_fee'] ?? '130' }}" placeholder="130" style="font-weight:700; font-size:15px; color:#04F404;">
                    <span class="adm-cell-sub">Default round figure delivery charge for all customers across Bangladesh (e.g. ৳130).</span>
                  </div>

                  <!-- Zone Based Fees -->
                  <div class="adm-field js-shipping-zone-fields" style="grid-column:1 / -1; display:{{ ($settings['shipping_mode'] ?? 'flat') === 'zone' ? 'grid' : 'none' }}; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="adm-field">
                      <label class="adm-label" style="font-weight:700;">Inside Dhaka Delivery Fee (৳)</label>
                      <input type="number" name="shipping_inside_dhaka" class="adm-input num" value="{{ $settings['shipping_inside_dhaka'] ?? '70' }}" placeholder="70" style="font-weight:700;">
                      <span class="adm-cell-sub">Delivery within Dhaka metro (24–48 hours).</span>
                    </div>
                    <div class="adm-field">
                      <label class="adm-label" style="font-weight:700;">Outside Dhaka Delivery Fee (৳)</label>
                      <input type="number" name="shipping_outside_dhaka" class="adm-input num" value="{{ $settings['shipping_outside_dhaka'] ?? '130' }}" placeholder="130" style="font-weight:700;">
                      <span class="adm-cell-sub">All districts outside Dhaka (2–3 days).</span>
                    </div>
                  </div>

                  <!-- Name & number print fee -->
                  <div class="adm-field">
                    <label class="adm-label" style="font-weight:600;">Custom Name &amp; Number Print Fee (৳)</label>
                    <input type="number" name="custom_print_fee" class="adm-input num" value="{{ $settings['custom_print_fee'] ?? '250' }}">
                    <span class="adm-cell-sub">Added automatically when customer opts for custom heat-seal print.</span>
                  </div>

                  <!-- Free delivery above -->
                  <div class="adm-field">
                    <label class="adm-label" style="font-weight:600;">Free Delivery Order Minimum (৳)</label>
                    <input type="number" name="free_shipping_min" class="adm-input num" value="{{ $settings['free_shipping_min'] ?? '3500' }}">
                    <span class="adm-cell-sub">Free delivery reward threshold (e.g. orders above ৳3,500).</span>
                  </div>

                  <!-- Steadfast API key -->
                  <div class="adm-field">
                    <label class="adm-label" style="font-weight:600;">Steadfast Courier API Key</label>
                    <input type="password" name="steadfast_api_key" class="adm-input" value="{{ $settings['steadfast_api_key'] ?? 'sf_live_rabez_9928174x' }}">
                    <span class="adm-cell-sub">API key for automated order booking &amp; consignment tracking.</span>
                  </div>

                  <!-- Pathao store ID -->
                  <div class="adm-field">
                    <label class="adm-label" style="font-weight:600;">Pathao Courier Store ID</label>
                    <input type="text" name="pathao_store_id" class="adm-input" value="{{ $settings['pathao_store_id'] ?? 'PTH-BARISHAL-1042' }}">
                    <span class="adm-cell-sub">Store ID registered with Pathao Merchant panel.</span>
                  </div>

                  <div class="adm-form__actions" style="grid-column:1 / -1; margin-top:8px;">
                    <button type="submit" class="adm-btn adm-btn--primary" style="padding:10px 24px; font-weight:700;">Save Delivery &amp; Courier Settings</button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Meta / Facebook Pixel & CAPI -->
            <div class="adm-card" style="width:100%;">
              <div class="adm-card__head">
                <div>
                  <h2 class="adm-card__title">Meta / Facebook Pixel &amp; CAPI</h2>
                  <p class="adm-card__desc">Browser Pixel tracking &amp; Server-Side Conversions API for iOS 14+ ad campaigns.</p>
                </div>
              </div>
              <div class="adm-card__body">
                <form class="adm-form js-pixel-settings-form">
                  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:16px;">
                    <div class="adm-field">
                      <label class="adm-label">Meta Pixel ID</label>
                      <input type="text" class="adm-input js-pixel-id-input" placeholder="e.g. 1029384756102" value="">
                      <span class="adm-cell-sub">Fires PageView, ViewContent, AddToCart, InitiateCheckout &amp; Purchase events.</span>
                    </div>
                    <div class="adm-field">
                      <label class="adm-label">Server-Side CAPI Access Token</label>
                      <input type="password" class="adm-input" placeholder="EAABw..." value="">
                      <span class="adm-cell-sub">Server events bypass ad-blockers and iOS privacy restrictions with 100% accuracy.</span>
                    </div>
                  </div>
                  <div class="adm-form__actions" style="margin-top:14px;">
                    <button type="submit" class="adm-btn adm-btn--primary">Save Tracking Settings</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             CMS PAGES MANAGEMENT
             ================================================================== -->
        <section class="adm-panel" data-panel="pages">
          <div class="adm-kpis" style="margin-bottom: 20px;">
            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Published Pages</span>
                <span class="adm-kpi__icon" style="background:rgba(34,197,94,0.15); color:#16A34A;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </span>
              </div>
              <div class="adm-kpi__value js-stat-pages-published" style="color:#16A34A;">0</div>
              <div class="adm-kpi__foot">Live &amp; accessible on storefront</div>
            </div>

            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Draft Pages</span>
                <span class="adm-kpi__icon" style="background:rgba(245,158,11,0.15); color:#D97706;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                </span>
              </div>
              <div class="adm-kpi__value js-stat-pages-drafts">0</div>
              <div class="adm-kpi__foot">Hidden until published</div>
            </div>

            <div class="adm-kpi">
              <div class="adm-kpi__head">
                <span>Total Pages</span>
                <span class="adm-kpi__icon" style="background:rgba(59,130,246,0.15); color:#2563EB;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </span>
              </div>
              <div class="adm-kpi__value js-stat-pages-total">0</div>
              <div class="adm-kpi__foot">Policies, Info &amp; Landing Pages</div>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card__head" style="flex-wrap:wrap; gap:14px; align-items:center;">
              <div style="flex:1; min-width:0;">
                <h2 class="adm-card__title">Storefront Pages (Privacy Policy, Terms, Custom Info)</h2>
                <p class="adm-card__desc">Create and edit custom HTML/rich-text pages at clean URLs like <code>/privacy-policy</code> or <code>/terms-and-conditions</code>.</p>
              </div>
              <div class="adm-coupons-toolbar" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <input type="text" class="adm-input js-page-search-input" placeholder="Search page title or slug..." style="width:230px; height:36px; font-size:12.5px;">
                <button type="button" class="adm-btn js-open-page-drawer" style="background:#04F404; color:#0A0C0B; font-weight:800; font-size:12.5px; padding:8px 16px; border-radius:6px; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                  <span>New Page</span>
                </button>
              </div>
            </div>

            <div class="adm-card__body adm-card__body--flush">
              <div class="adm-table-wrap">
                <table class="adm-table" id="table-pages" style="width:100%;">
                  <thead>
                    <tr>
                      <th style="min-width:220px;">Page Title &amp; Excerpt</th>
                      <th style="min-width:170px;">URL Slug</th>
                      <th style="min-width:110px;">Status</th>
                      <th style="min-width:150px;">Last Updated</th>
                      <th style="text-align:right; min-width:240px;">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="adm-pages-list">
                    <tr><td colspan="5" style="text-align:center; padding:28px; color:var(--adm-muted);">Loading pages...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================================================================
             MULTI-LEVEL MENU BUILDER
             ================================================================== -->
        <section class="adm-panel" data-panel="menus">
          <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
            <div class="adm-menu-loc-tabs" role="tablist" aria-label="Menu locations">
              <button type="button" class="adm-menu-loc-btn is-active js-menu-loc-tab" data-location="header">
                Header Navigation (Multi-Level)
              </button>
              <button type="button" class="adm-menu-loc-btn js-menu-loc-tab" data-location="footer_care">
                Footer Customer Care
              </button>
            </div>

            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
              <a href="{{ route('home') }}" target="_blank" class="adm-btn adm-btn--secondary adm-btn--sm">
                View Storefront &nearr;
              </a>
            </div>
          </div>

          <div class="adm-menu-grid">
            <!-- Left Column: Add / Edit Menu Item -->
            <div class="adm-card">
              <div class="adm-card__head">
                <div>
                  <h2 class="adm-card__title js-menu-form-title">Add Menu Item</h2>
                  <p class="adm-card__desc js-menu-form-desc">Link a CMS Page, store category, or custom URL to your navigation.</p>
                </div>
              </div>
              <div class="adm-card__body">
                <form id="js-menu-item-form" style="display:grid; gap:13px;">
                  <input type="hidden" id="menu-edit-id" value="">

                  <div class="adm-field">
                    <label class="adm-label" for="menu-item-type">Link Source</label>
                    <select id="menu-item-type" class="adm-select">
                      <option value="page">CMS Page (Privacy Policy, Terms, etc.)</option>
                      <option value="route">Storefront Preset Link</option>
                      <option value="custom">Custom URL / Dropdown Heading (#)</option>
                    </select>
                  </div>

                  <div class="adm-field js-menu-page-wrap">
                    <label class="adm-label" for="menu-item-page-id">Select Page</label>
                    <select id="menu-item-page-id" class="adm-select">
                      <option value="">-- Choose a page --</option>
                    </select>
                  </div>

                  <div class="adm-field js-menu-preset-wrap" style="display:none;">
                    <label class="adm-label" for="menu-item-preset">Choose Preset Destination</label>
                    <select id="menu-item-preset" class="adm-select">
                      <option value="">-- Select quick link --</option>
                      <option value="/|Home">Home (/)</option>
                      <option value="/shop|All Jerseys">All Jerseys (/shop)</option>
                      <option value="/shop?sleeve=half-sleeve|Half Sleeve Kits">Half Sleeve Kits</option>
                      <option value="/shop?sleeve=full-sleeve|Full Sleeve Kits">Full Sleeve Kits</option>
                      <option value="/shop?sleeve=winter-hoodie|Winter Hoodies">Winter Hoodies</option>
                      <option value="/shop?sleeve=retro|Retro Kits">Retro Kits</option>
                      <option value="/track-order|Track Your Order">Track Your Order (/track-order)</option>
                      <option value="/checkout|1-Page COD Checkout">1-Page COD Checkout (/checkout)</option>
                      <option value="/#size-guide|Size Chart">Size Chart (/#size-guide)</option>
                      <option value="https://api.whatsapp.com/send/?phone=8801612359652|Direct WhatsApp Support">WhatsApp Support</option>
                      <option value="/admin|RABEZ Admin Panel">RABEZ Admin Panel (/admin)</option>
                    </select>
                  </div>

                  <div class="adm-field js-menu-url-wrap" style="display:none;">
                    <label class="adm-label" for="menu-item-url">Destination URL</label>
                    <input type="text" id="menu-item-url" class="adm-input" placeholder="/shop?club=real-madrid or # for folder">
                    <span class="adm-cell-sub">Use <code>#</code> if this item is only a dropdown parent group.</span>
                  </div>

                  <div class="adm-field">
                    <label class="adm-label" for="menu-item-title">Menu Label *</label>
                    <input type="text" id="menu-item-title" class="adm-input" required placeholder="e.g. Privacy Policy or Shop Kits">
                  </div>

                  <div class="adm-field">
                    <label class="adm-label" for="menu-item-parent-id">Parent Menu (For Multi-Level Dropdown)</label>
                    <select id="menu-item-parent-id" class="adm-select">
                      <option value="">— Top Level (Root) —</option>
                    </select>
                    <span class="adm-cell-sub">Pick any parent item to nest Level 2, Level 3, or deeper submenus.</span>
                  </div>

                  <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div class="adm-field">
                      <label class="adm-label" for="menu-item-badge">Badge (Optional)</label>
                      <input type="text" id="menu-item-badge" class="adm-input" maxlength="20" placeholder="e.g. NEW">
                    </div>
                    <div class="adm-field">
                      <label class="adm-label" for="menu-item-target">Open In</label>
                      <select id="menu-item-target" class="adm-select">
                        <option value="_self">Same Tab</option>
                        <option value="_blank">New Tab</option>
                      </select>
                    </div>
                  </div>

                  <label style="display:flex; align-items:center; gap:9px; padding:9px 12px; border:1px solid var(--adm-border); border-radius:8px; cursor:pointer; background:var(--adm-subtle);">
                    <input type="checkbox" id="menu-item-active" checked style="width:16px; height:16px; accent-color:#04F404;">
                    <span style="font-size:13px; font-weight:600;">Visible on Storefront</span>
                  </label>

                  <div style="display:flex; gap:8px; padding-top:4px;">
                    <button type="submit" class="adm-btn adm-btn--primary js-menu-save-btn" style="flex:1; font-weight:700;">
                      Add to Menu
                    </button>
                    <button type="button" class="adm-btn adm-btn--secondary js-menu-cancel-edit" style="display:none;">
                      Cancel
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Right Column: Interactive Multi-Level Tree -->
            <div class="adm-card">
              <div class="adm-card__head" style="flex-wrap:wrap; gap:10px;">
                <div>
                  <h2 class="adm-card__title js-menu-tree-heading">Header Menu Hierarchy</h2>
                  <p class="adm-card__desc">Use <strong>&uarr; &darr;</strong> to reorder and <strong>&rarr; Nest / &larr; Outdent</strong> to build multi-level dropdowns. Mobile drawer adapts automatically.</p>
                </div>
              </div>
              <div class="adm-card__body">
                <div class="adm-menu-tree" id="adm-menu-tree-wrap">
                  <div style="text-align:center; padding:28px; color:var(--adm-muted);">Loading menu tree...</div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>

  <!-- ========================================================================
       ORDER DETAIL DRAWER & PRODUCT EDIT DRAWER
       ======================================================================== -->
  <div class="adm-backdrop js-backdrop"></div>
  <aside class="adm-drawer js-order-drawer" aria-label="Order details">
    <div class="adm-drawer__head">
      <div>
        <div class="adm-cell-sub js-d-date">—</div>
        <h2 style="font-size:18px; margin-top:2px;" class="js-d-id">Order</h2>
        <div style="margin-top:8px;" class="js-d-status"></div>
      </div>
      <button type="button" class="adm-icon-btn js-drawer-close" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="adm-drawer__body js-d-body"></div>
    <div class="adm-drawer__foot js-d-foot"></div>
  </aside>

  <!-- PRODUCT EDIT DRAWER -->
  <aside class="adm-drawer js-prod-drawer" aria-label="Product details">
    <div class="adm-drawer__head" style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--adm-border); padding:16px 24px;">
      <div>
        <div class="adm-cell-sub" style="text-transform:uppercase; letter-spacing:0.05em; font-size:11px; color:#22C55E; font-weight:700;">Product Management &bull; Database</div>
        <h2 style="font-size:20px; font-weight:800; margin-top:3px; color:var(--adm-text);" class="js-p-title">Edit Product</h2>
      </div>
      <div style="display:flex; align-items:center; gap:8px;">
        <a id="edit-prod-view-btn" href="#" target="_blank" class="adm-btn adm-btn--secondary adm-btn--sm" title="View live page on store" style="display:inline-flex; align-items:center; gap:5px; text-decoration:none;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
          View Live
        </a>
        <button type="button" class="adm-icon-btn js-drawer-close" aria-label="Close">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </div>
    <div class="adm-drawer__body js-p-body" style="padding:24px; overflow-y:auto; flex:1;">
      <form id="js-edit-product-form" class="adm-form" style="display:flex; flex-direction:column; gap:22px;">
        <input type="hidden" id="edit-prod-id">

        <!-- Top 2-Column Section: Left Images | Right Core Info -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(340px, 1fr)); gap:20px; align-items:start;">

          <!-- LEFT COLUMN: Product Images & Gallery -->
          <div style="display:flex; flex-direction:column; gap:16px;">
            <!-- 1. Featured Image (Front View) -->
            <div style="background:var(--adm-subtle); padding:16px; border-radius:10px; border:1px solid var(--adm-border);">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <label class="adm-label" style="margin:0; font-weight:700; font-size:13px; color:var(--adm-text);">1. Featured Image (Front View)</label>
                <span style="font-size:10px; background:rgba(34,197,94,0.18); color:#22C55E; font-weight:800; padding:2px 8px; border-radius:4px; letter-spacing:0.04em;">FRONT VIEW</span>
              </div>
              <div style="display:flex; gap:14px; align-items:center;">
                <div id="edit-prod-featured-preview-box" style="width:84px; height:84px; border-radius:8px; overflow:hidden; border:1px solid var(--adm-border); background:#0F1311; flex-shrink:0;">
                  <img id="edit-prod-featured-preview" src="" alt="Front View" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <div style="display:flex; flex-direction:column; gap:8px; flex:1;">
                  <input type="file" id="edit-prod-featured-file" accept="image/*" hidden>
                  <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <button type="button" class="adm-btn adm-btn--primary adm-btn--sm" onclick="document.getElementById('edit-prod-featured-file').click()">
                      Upload New
                    </button>
                    <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm" onclick="chooseFeaturedForEdit()">
                      Media Library
                    </button>
                  </div>
                  <span style="font-size:11px; color:var(--adm-muted);">Auto-converts to WebP &amp; fixes iPhone rotation</span>
                </div>
              </div>
            </div>

            <!-- 2. Product Gallery (Back View & Details) -->
            <div style="background:var(--adm-subtle); padding:16px; border-radius:10px; border:1px solid var(--adm-border);">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; flex-wrap:wrap; gap:6px;">
                <div>
                  <label class="adm-label" style="margin:0; font-weight:700; font-size:13px; color:var(--adm-text);">2. Product Gallery</label>
                  <div style="font-size:11px; color:var(--adm-muted); margin-top:2px;">1st image in gallery = <strong style="color:#22C55E;">Back View</strong> (shown on card hover flip).</div>
                </div>
                <div style="display:flex; gap:6px;">
                  <input type="file" id="edit-prod-gallery-file" multiple accept="image/*" hidden>
                  <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm" onclick="document.getElementById('edit-prod-gallery-file').click()">+ Upload</button>
                  <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm" onclick="chooseGalleryForEdit()">Pick Library</button>
                </div>
              </div>
              <div id="edit-prod-gallery-strip" style="display:flex; gap:8px; flex-wrap:wrap; min-height:88px; padding:10px; border-radius:8px; background:var(--adm-surface); border:1px dashed var(--adm-border-strong); align-items:center;">
                <!-- Rendered by JS -->
              </div>
            </div>
          </div>

          <!-- RIGHT COLUMN: Core Specs & Pricing -->
          <div style="display:flex; flex-direction:column; gap:14px; background:var(--adm-subtle); padding:18px; border-radius:10px; border:1px solid var(--adm-border);">
            <div class="adm-field">
              <label class="adm-label" for="edit-prod-name" style="font-weight:700;">Product Title</label>
              <input type="text" id="edit-prod-name" class="adm-input" required placeholder="e.g. Real Madrid 2024/25 Home Jersey">
            </div>

            <div class="adm-field">
              <label class="adm-label" for="edit-prod-sku" style="font-weight:700;">SKU / Product Code</label>
              <input type="text" id="edit-prod-sku" class="adm-input" required placeholder="e.g. RBZ-RMA-25-HM">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
              <div class="adm-field">
                <label class="adm-label" for="edit-prod-price" style="font-weight:700;">Sale Price (৳)</label>
                <input type="number" id="edit-prod-price" class="adm-input" required placeholder="1150">
              </div>
              <div class="adm-field">
                <label class="adm-label" for="edit-prod-reg-price" style="font-weight:700;">Regular Price (৳)</label>
                <input type="number" id="edit-prod-reg-price" class="adm-input" placeholder="1350">
              </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
              <div class="adm-field">
                <label class="adm-label" for="edit-prod-cat" style="font-weight:700;">Category / Sleeve</label>
                <select id="edit-prod-cat" class="adm-select">
                  <option value="half-sleeve">Half Sleeve Jersey</option>
                  <option value="full-sleeve">Full Sleeve Jersey</option>
                  <option value="winter-hoodie">Winter Hoodie</option>
                  <option value="retro">Classic Retro Kit</option>
                  <option value="footwear">Footwear / Boots</option>
                  <option value="tracksuit">Tracksuit</option>
                </select>
              </div>
              <div class="adm-field">
                <label class="adm-label" for="edit-prod-club" style="font-weight:700;">Club / Team</label>
                <select id="edit-prod-club" class="adm-select">
                  <option value="all">General / Other</option>
                  <option value="real-madrid">Real Madrid</option>
                  <option value="barcelona">Barcelona</option>
                  <option value="man-utd">Manchester United</option>
                  <option value="arsenal">Arsenal</option>
                  <option value="argentina">Argentina</option>
                  <option value="bangladesh">Bangladesh</option>
                </select>
              </div>
            </div>

            <div class="adm-field">
              <label class="adm-label" for="edit-prod-stock" style="font-weight:700;">Stock Availability</label>
              <select id="edit-prod-stock" class="adm-select">
                <option value="1">Ready Stock (In Stock & Available)</option>
                <option value="0">Out of Stock / Sold Out</option>
              </select>
            </div>

            <div class="adm-field" style="background:var(--adm-surface); padding:12px 14px; border-radius:8px; border:1px solid var(--adm-border);">
              <label style="display:flex; align-items:center; justify-content:space-between; gap:12px; cursor:pointer; user-select:none;">
                <div>
                  <div style="font-weight:700; font-size:13px; color:var(--adm-ink);">Custom Name &amp; Number Print (+৳{{ intval($settings['custom_print_fee'] ?? 250) }})</div>
                  <div style="font-size:11.5px; color:var(--adm-muted); margin-top:2px;">Show Custom Name &amp; Number box on this product only.</div>
                </div>
                <input type="checkbox" id="edit-prod-custom-print" style="width:18px; height:18px; accent-color:#04F404; cursor:pointer; flex-shrink:0;">
              </label>
            </div>
          </div>
        </div>

        <!-- FULL-WIDTH SECTION: Descriptions (Short + Long) -->
        <div style="background:var(--adm-subtle); padding:18px; border-radius:10px; border:1px solid var(--adm-border); display:flex; flex-direction:column; gap:16px;">
          <div class="adm-field">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
              <label class="adm-label" for="edit-prod-desc" style="font-weight:700; margin:0;">
                Short Description (সংক্ষিপ্ত বিবরণ)
              </label>
              <span style="font-size:11px; color:var(--adm-muted);">Shown on product cards, quick view &amp; summary</span>
            </div>
            <textarea id="edit-prod-desc" class="adm-input js-dynamic-textarea" rows="3" style="min-height:90px; resize:vertical; line-height:1.5;" placeholder="e.g. Official Player Issue Thai Edition. Premium double-knit moisture wicking fabric."></textarea>
          </div>

          <div class="adm-field">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
              <label class="adm-label" for="edit-prod-long-desc" style="font-weight:700; margin:0;">
                Detailed Long Description (বিস্তারিত বিবরণ)
              </label>
              <span style="font-size:11px; color:var(--adm-muted);">Shown on Single Product Page &bull; Product Overview Tab</span>
            </div>
            <textarea id="edit-prod-long-desc" class="adm-input js-dynamic-textarea" rows="8" style="min-height:220px; resize:vertical; line-height:1.6; font-size:14px;" placeholder="Write full details about authentic fabric texture, badges, heat-pressed silicone crest, fit guide, wash care instructions, etc."></textarea>
          </div>
        </div>

        <!-- Sticky Drawer Bottom Actions -->
        <div style="display:flex; gap:12px; align-items:center; padding-top:8px; border-top:1px solid var(--adm-border);">
          <button type="submit" class="adm-btn adm-btn--primary" style="flex:1; padding:12px 20px; font-weight:700; font-size:14px;">
            Save Product Changes
          </button>
          <button type="button" class="adm-btn adm-btn--danger js-delete-prod-btn" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5; padding:12px 18px; font-weight:600;">
            Delete Product
          </button>
        </div>
      </form>
    </div>
  </aside>

  <!-- CATEGORY EDIT / ADD DRAWER -->
  <aside class="adm-drawer js-cat-drawer" aria-label="Category details">
    <div class="adm-drawer__head">
      <div>
        <div class="adm-cell-sub">Category Management (Database)</div>
        <h2 style="font-size:18px; margin-top:2px;" class="js-c-title">Edit Category</h2>
      </div>
      <button type="button" class="adm-icon-btn js-drawer-close" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="adm-drawer__body js-c-body">
      <form id="js-edit-cat-form" class="adm-form" style="display:grid; gap:16px;">
        <input type="hidden" id="edit-cat-id">
        <div class="adm-field">
          <label class="adm-label" for="edit-cat-name">Category Name</label>
          <input type="text" id="edit-cat-name" class="adm-input" required placeholder="e.g. Half Sleeve Jersey">
        </div>
        <div class="adm-field">
          <label class="adm-label" for="edit-cat-slug">Slug (URL)</label>
          <input type="text" id="edit-cat-slug" class="adm-input" required placeholder="e.g. half-sleeve">
        </div>
        <div class="adm-field">
          <label class="adm-label" for="edit-cat-type">Product Type</label>
          <select id="edit-cat-type" class="adm-select">
            <option value="jersey">Jersey</option>
            <option value="hoodie">Winter Hoodie</option>
            <option value="footwear">Footwear / Boots</option>
            <option value="tracksuit">Tracksuit</option>
            <option value="other">Other / Accessories</option>
          </select>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div class="adm-field">
            <label class="adm-label" for="edit-cat-sort">Sort Order</label>
            <input type="number" id="edit-cat-sort" class="adm-input" value="0">
          </div>
          <div class="adm-field">
            <label class="adm-label" for="edit-cat-status">Status</label>
            <select id="edit-cat-status" class="adm-select">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
        <div style="display:flex; gap:10px; margin-top:12px;">
          <button type="submit" class="adm-btn adm-btn--primary" style="flex:1;">Save Category</button>
          <button type="button" class="adm-btn adm-btn--danger js-delete-cat-btn" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Delete</button>
        </div>
      </form>
    </div>
  </aside>

  <!-- CUSTOMER EDIT DRAWER -->
  <aside class="adm-drawer js-cust-drawer" aria-label="Customer details">
    <div class="adm-drawer__head">
      <div>
        <div class="adm-cell-sub">Customer Management</div>
        <h2 style="font-size:18px; margin-top:2px;" class="js-cust-title">Edit Customer</h2>
      </div>
      <button type="button" class="adm-icon-btn js-drawer-close" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="adm-drawer__body js-cust-body">
      <form id="js-edit-cust-form" class="adm-form" style="display:grid; gap:16px;">
        <input type="hidden" id="edit-cust-orig-phone">
        <div class="adm-field">
          <label class="adm-label" for="edit-cust-name">Customer Full Name</label>
          <input type="text" id="edit-cust-name" class="adm-input" required>
        </div>
        <div class="adm-field">
          <label class="adm-label" for="edit-cust-phone">Phone Number (11 Digits)</label>
          <input type="text" id="edit-cust-phone" class="adm-input" required>
        </div>
        <div class="adm-field">
          <label class="adm-label" for="edit-cust-address">Delivery Address</label>
          <textarea id="edit-cust-address" class="adm-input" rows="3"></textarea>
        </div>
        <div class="adm-callout js-cust-risk-callout" style="margin-top:4px;">
          <!-- Dynamically populated delivery risk info -->
        </div>
        <div style="display:flex; gap:10px; margin-top:12px;">
          <button type="submit" class="adm-btn adm-btn--primary" style="flex:1;">Update Customer</button>
          <button type="button" class="adm-btn adm-btn--danger js-delete-cust-btn" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Delete Customer</button>
        </div>
      </form>
    </div>
  </aside>

  <!-- USER EDIT / ADD DRAWER -->
  <aside class="adm-drawer js-user-drawer" aria-label="User details">
    <div class="adm-drawer__head">
      <div>
        <div class="adm-cell-sub">Team &amp; Access Control</div>
        <h2 style="font-size:18px; margin-top:2px;" class="js-user-drawer-title">Add New User</h2>
      </div>
      <button type="button" class="adm-icon-btn js-drawer-close" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="adm-drawer__body js-user-body">
      <form id="js-user-form" class="adm-form" style="display:grid; gap:16px;">
        <input type="hidden" id="edit-user-id">

        <div class="adm-field">
          <label class="adm-label" for="edit-user-name">Full Name *</label>
          <input type="text" id="edit-user-name" class="adm-input" required placeholder="e.g. Md. Rajib">
        </div>

        <div class="adm-field">
          <label class="adm-label" for="edit-user-email">Email Address *</label>
          <input type="email" id="edit-user-email" class="adm-input" required placeholder="admin@rabez.shop">
        </div>

        <div class="adm-field">
          <label class="adm-label" for="edit-user-phone">Phone Number</label>
          <input type="tel" id="edit-user-phone" class="adm-input num" placeholder="01XXXXXXXXX">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div class="adm-field">
            <label class="adm-label" for="edit-user-role">Role &amp; Privileges *</label>
            <select id="edit-user-role" class="adm-select" required>
              <option value="admin">⚡ Administrator (Full Access)</option>
              <option value="manager">🛡️ Manager (Catalog &amp; Orders)</option>
              <option value="staff">💼 Staff (Print &amp; Fulfillment)</option>
              <option value="customer">👤 Customer (Storefront Member)</option>
            </select>
          </div>

          <div class="adm-field">
            <label class="adm-label" for="edit-user-status">Account Status *</label>
            <select id="edit-user-status" class="adm-select" required>
              <option value="active">Active (Can Login)</option>
              <option value="inactive">Inactive / Suspended</option>
            </select>
          </div>
        </div>

        <div style="padding:14px; background:rgba(255,255,255,0.02); border:1px solid var(--adm-border); border-radius:8px;">
          <div class="adm-field">
            <label class="adm-label" for="edit-user-password">
              Password <span class="js-pwd-hint" style="font-weight:normal; color:#9CA3AF; font-size:12px;">(Min 8 chars)</span>
            </label>
            <input type="password" id="edit-user-password" class="adm-input" placeholder="••••••••">
          </div>
          <div class="adm-cell-sub js-pwd-edit-note" style="font-size:11.5px; color:#9CA3AF; margin-top:6px;">
            Required for new users. When editing, leave empty to keep the existing password.
          </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:12px;">
          <button type="submit" class="adm-btn adm-btn--primary js-save-user-submit-btn" style="flex:1;">Save User</button>
          <button type="button" class="adm-btn adm-btn--danger js-delete-user-modal-btn" style="display:none; background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Delete</button>
        </div>
      </form>
    </div>
  </aside>

  <!-- CMS PAGE EDITOR DRAWER -->
  <aside class="adm-drawer js-page-drawer" aria-label="Page Editor">
    <div class="adm-drawer__head">
      <div>
        <div class="adm-cell-sub">Storefront CMS Page</div>
        <h2 style="font-size:18px; margin-top:2px;" class="js-page-drawer-title">Create New Page</h2>
      </div>
      <div style="display:flex; gap:8px; align-items:center;">
        <a href="#" target="_blank" class="adm-btn adm-btn--secondary adm-btn--sm js-page-preview-link" style="display:none;">Preview &nearr;</a>
        <button type="button" class="adm-icon-btn js-drawer-close" aria-label="Close">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </div>

    <div class="adm-drawer__body">
      <form id="js-page-editor-form" style="display:grid; gap:15px;">
        <input type="hidden" id="page-edit-id" value="">

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
          <div class="adm-field">
            <label class="adm-label" for="page-edit-title">Page Title *</label>
            <input type="text" id="page-edit-title" class="adm-input" required placeholder="e.g. Privacy Policy">
          </div>
          <div class="adm-field">
            <label class="adm-label" for="page-edit-slug">URL Slug *</label>
            <input type="text" id="page-edit-slug" class="adm-input" placeholder="e.g. privacy-policy">
            <span class="adm-cell-sub js-page-slug-preview" style="font-size:11px; color:#16A34A;">{{ url('/') }}/privacy-policy</span>
          </div>
        </div>

        <div class="adm-field">
          <label class="adm-label" for="page-edit-excerpt">Subtitle / Short Summary (Optional)</label>
          <input type="text" id="page-edit-excerpt" class="adm-input" placeholder="Shown in the dark hero banner below page title">
        </div>

        <div class="adm-field">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px; gap:8px; flex-wrap:wrap;">
            <label class="adm-label" style="margin:0;">Page Content (Visual Editor &amp; HTML)</label>
            <button type="button" class="adm-rte-btn js-rte-toggle-source" style="height:26px; font-size:11px;">&lt;/&gt; HTML Source</button>
          </div>
          <div class="adm-rte-wrap">
            <div class="adm-rte-toolbar js-rte-toolbar">
              <button type="button" class="adm-rte-btn js-rte-cmd" data-cmd="formatBlock" data-val="h2" title="Heading 2">H2</button>
              <button type="button" class="adm-rte-btn js-rte-cmd" data-cmd="formatBlock" data-val="h3" title="Heading 3">H3</button>
              <button type="button" class="adm-rte-btn js-rte-cmd" data-cmd="formatBlock" data-val="p" title="Paragraph">P</button>
              <span class="adm-rte-sep"></span>
              <button type="button" class="adm-rte-btn js-rte-cmd" data-cmd="bold" title="Bold"><b>B</b></button>
              <button type="button" class="adm-rte-btn js-rte-cmd" data-cmd="italic" title="Italic"><i>I</i></button>
              <button type="button" class="adm-rte-btn js-rte-cmd" data-cmd="underline" title="Underline"><u>U</u></button>
              <span class="adm-rte-sep"></span>
              <button type="button" class="adm-rte-btn js-rte-cmd" data-cmd="insertUnorderedList" title="Bullet List">&bull; List</button>
              <button type="button" class="adm-rte-btn js-rte-cmd" data-cmd="insertOrderedList" title="Numbered List">1. List</button>
              <button type="button" class="adm-rte-btn js-rte-cmd" data-cmd="formatBlock" data-val="blockquote" title="Callout Quote">&ldquo; Quote</button>
              <button type="button" class="adm-rte-btn js-rte-link-btn" title="Insert Link">Link</button>
            </div>
            <div id="page-edit-rte" class="adm-rte-editor" contenteditable="true"></div>
            <textarea id="page-edit-html" class="adm-rte-source" style="display:none;" placeholder="Write or paste clean HTML here..."></textarea>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
          <div class="adm-field">
            <label class="adm-label" for="page-edit-meta-title">SEO Meta Title (Optional)</label>
            <input type="text" id="page-edit-meta-title" class="adm-input" placeholder="Defaults to Page Title">
          </div>
          <div class="adm-field">
            <label class="adm-label" for="page-edit-meta-desc">SEO Meta Description (Optional)</label>
            <input type="text" id="page-edit-meta-desc" class="adm-input" placeholder="Search snippet summary">
          </div>
        </div>

        <label style="display:flex; align-items:center; justify-content:space-between; padding:11px 14px; border:1px solid var(--adm-border); border-radius:8px; background:var(--adm-subtle); cursor:pointer;">
          <div>
            <div style="font-weight:700; font-size:13px;">Publish Page Live</div>
            <div style="font-size:11.5px; color:var(--adm-muted);">Uncheck to save as Draft (hidden from visitors &amp; menu).</div>
          </div>
          <input type="checkbox" id="page-edit-published" checked style="width:18px; height:18px; accent-color:#04F404;">
        </label>

        <div style="display:flex; gap:10px; padding-top:8px; border-top:1px solid var(--adm-border);">
          <button type="submit" class="adm-btn adm-btn--primary js-save-page-btn" style="flex:1; font-weight:700;">Save Page</button>
          <button type="button" class="adm-btn adm-btn--danger js-delete-page-drawer-btn" style="display:none; background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Delete Page</button>
        </div>
      </form>
    </div>
  </aside>

  <!-- ========================================================================
       MOBILE BOTTOM NAV
       ======================================================================== -->
  <nav class="adm-bottom-nav" aria-label="Mobile navigation">
    <button type="button" class="adm-bottom-btn is-active js-tab" data-tab="dashboard">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
      Dashboard
    </button>
    <button type="button" class="adm-bottom-btn js-tab" data-tab="orders">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
      Orders
    </button>
    <button type="button" class="adm-bottom-btn js-tab" data-tab="products">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
      Products
    </button>
    <button type="button" class="adm-bottom-btn js-tab" data-tab="add-product">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="4"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
      Add
    </button>
    <button type="button" class="adm-bottom-btn js-tab" data-tab="pos-order">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
      New order
    </button>
  </nav>

  
  <!-- ========================================================================
       MEDIA PHOTO DETAIL MODAL / BOTTOM SHEET (Mobile & Desktop)
       ======================================================================== -->
  <div id="adm-photo-sheet" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:16px;">
    <div class="adm-photo-sheet__card" style="background:#111513; border:1px solid #233027; border-radius:14px; width:100%; max-width:440px; max-height:90vh; overflow-y:auto; box-shadow:0 24px 48px rgba(0,0,0,0.6); display:flex; flex-direction:column;">
      <div style="padding:14px 18px; border-bottom:1px solid #1E2722; display:flex; align-items:center; justify-content:space-between;">
        <div style="font-weight:700; color:#fff; font-size:14px; display:flex; align-items:center; gap:8px;">
          <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#22C55E;"></span>
          Image Details
        </div>
        <button type="button" onclick="closePhotoSheet()" style="background:none; border:none; color:#9CA3AF; cursor:pointer; font-size:22px; line-height:1; padding:0 4px;" title="Close">&times;</button>
      </div>
      <div style="padding:16px; display:flex; flex-direction:column; gap:14px;">
        <div style="width:100%; aspect-ratio:1; max-height:280px; background:#070908; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; border:1px solid #1D2520;">
          <img id="photo-sheet-img" src="" alt="" style="max-width:100%; max-height:100%; object-fit:contain;">
        </div>
        <div style="background:#0D110F; border:1px solid #1B241E; border-radius:8px; padding:12px; display:flex; flex-direction:column; gap:8px; font-size:12px;">
          <div style="display:flex; justify-content:space-between; gap:10px;">
            <span style="color:#7D8782; flex-shrink:0;">File Name:</span>
            <span id="photo-sheet-name" style="color:#fff; font-weight:600; text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"></span>
          </div>
          <div style="display:flex; justify-content:space-between;">
            <span style="color:#7D8782;">Resolution:</span>
            <span id="photo-sheet-dim" style="color:#fff; font-weight:600;"></span>
          </div>
          <div style="display:flex; justify-content:space-between;">
            <span style="color:#7D8782;">File Size:</span>
            <span id="photo-sheet-size" style="color:#22C55E; font-weight:700;"></span>
          </div>
          <div style="display:flex; justify-content:space-between;">
            <span style="color:#7D8782;">Format:</span>
            <span id="photo-sheet-format" style="color:#fff;"></span>
          </div>
          <div style="display:flex; flex-direction:column; gap:4px; margin-top:4px; padding-top:6px; border-top:1px solid #1A221D;">
            <span style="color:#7D8782; font-size:11px;">Direct File URL:</span>
            <input type="text" id="photo-sheet-url-input" readonly style="background:#070908; border:1px solid #232E27; border-radius:4px; color:#A9B3AE; font-size:11px; padding:6px 8px; width:100%; outline:none;" onclick="this.select()">
          </div>
        </div>
        <div style="display:flex; gap:10px;">
          <button type="button" id="photo-sheet-copy-btn" class="adm-btn adm-btn--primary" style="flex:1;">
            📋 Copy URL
          </button>
          <button type="button" id="photo-sheet-del-btn" class="adm-btn adm-btn--danger" style="background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">
            🗑 Delete
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================================
       REUSABLE MEDIA PICKER MODAL (Auto-WebP + iPhone EXIF)
       ======================================================================== -->
  <div id="adm-media-modal" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,0.8); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
    <div style="background:var(--adm-surface); border:1px solid var(--adm-border); border-radius:12px; width:100%; max-width:880px; max-height:85vh; display:flex; flex-direction:column; box-shadow:0 20px 40px rgba(0,0,0,0.4); overflow:hidden;">
      <!-- Header -->
      <div style="padding:16px 20px; border-bottom:1px solid var(--adm-border); display:flex; align-items:center; justify-content:space-between; background:var(--adm-subtle);">
        <div>
          <h3 id="adm-modal-title" style="color:var(--adm-ink); font-size:16px; font-weight:700; margin:0;">Choose from Media Library</h3>
          <span id="adm-modal-subtitle" style="font-size:12px; color:var(--adm-muted);">Select an image or upload new</span>
        </div>
        <button type="button" class="adm-icon-btn" onclick="closeMediaPicker()" style="font-size:20px; line-height:1;">&times;</button>
      </div>
      <!-- Toolbar -->
      <div style="padding:12px 20px; border-bottom:1px solid var(--adm-border); display:flex; gap:12px; align-items:center; background:var(--adm-surface);">
        <input type="text" id="adm-modal-search" class="adm-input" placeholder="Search images by name..." style="flex:1; height:36px; font-size:13px;" oninput="renderMediaModalGrid()">
        <input type="file" id="adm-modal-file-input" multiple accept="image/*" hidden onchange="handleModalUpload(this.files)">
        <button type="button" class="adm-btn adm-btn--primary adm-btn--sm" onclick="document.getElementById('adm-modal-file-input').click()">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
          Upload New (Auto-WebP)
        </button>
      </div>
      <!-- Grid -->
      <div id="adm-modal-grid" style="flex:1; overflow-y:auto; padding:16px 20px; display:grid; grid-template-columns:repeat(auto-fill, minmax(130px, 1fr)); gap:12px; max-height:480px; background:var(--adm-subtle);">
        <!-- Tiles rendered by JS -->
      </div>
      <!-- Footer -->
      <div style="padding:14px 20px; border-top:1px solid var(--adm-border); display:flex; justify-content:space-between; align-items:center; background:var(--adm-surface);">
        <span style="font-size:13px; color:var(--adm-text-2);"><strong id="adm-modal-selected-count" style="color:#22C55E;">0</strong> item(s) selected</span>
        <div style="display:flex; gap:10px;">
          <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm" onclick="closeMediaPicker()">Cancel</button>
          <button type="button" id="adm-modal-confirm-btn" class="adm-btn adm-btn--primary adm-btn--sm" onclick="confirmMediaPicker()">Use Selected</button>
        </div>
      </div>
    </div>
  </div>

  <!-- COUPON CREATE / EDIT MODAL -->
  <div class="adm-modal js-coupon-modal" style="display:none; position:fixed; inset:0; z-index:1002; background:rgba(0,0,0,0.7); align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--adm-surface); border:1px solid var(--adm-border); border-radius:12px; width:100%; max-width:460px; max-height:92vh; overflow-y:auto; padding:24px; box-shadow:0 20px 40px rgba(0,0,0,0.5);">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <h3 class="js-coupon-modal-title" style="font-size:18px; font-weight:800; color:var(--adm-text); margin:0;">Create Discount Coupon</h3>
        <button type="button" class="adm-icon-btn js-close-coupon-modal" aria-label="Close" style="cursor:pointer; background:none; border:none; color:var(--adm-muted);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <form id="js-coupon-form" class="adm-form" style="display:flex; flex-direction:column; gap:14px;">
        <input type="hidden" id="coupon-id" value="">
        <div class="adm-field">
          <label class="adm-label" for="coupon-code">Coupon Code *</label>
          <input type="text" id="coupon-code" class="adm-input" required placeholder="e.g. RABEZ150" style="text-transform:uppercase; font-weight:700; letter-spacing:0.05em;">
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div class="adm-field">
            <label class="adm-label" for="coupon-type">Discount Type</label>
            <select id="coupon-type" class="adm-select">
              <option value="fixed">Fixed BDT (৳)</option>
              <option value="percent">Percentage (%)</option>
            </select>
          </div>
          <div class="adm-field">
            <label class="adm-label" for="coupon-val">Discount Value *</label>
            <input type="number" id="coupon-val" class="adm-input num" required placeholder="100">
          </div>
        </div>
        <div class="adm-field">
          <label class="adm-label" for="coupon-min-spend">Minimum Spend (৳)</label>
          <input type="number" id="coupon-min-spend" class="adm-input num" value="0" placeholder="0 = No minimum">
        </div>
        <div class="adm-field">
          <label class="adm-label" for="coupon-desc">Short Note / Description</label>
          <input type="text" id="coupon-desc" class="adm-input" placeholder="e.g. ৳ 100 off on any 2 jerseys">
        </div>
        <div style="display:flex; gap:10px; margin-top:8px;">
          <button type="submit" class="adm-btn js-coupon-submit-btn" style="flex:1; background:#04F404; color:#0A0C0B; font-weight:800; border:none; padding:10px 16px; border-radius:6px; cursor:pointer;">Create &amp; Activate Coupon</button>
          <button type="button" class="adm-btn adm-btn--secondary js-close-coupon-modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <div class="adm-toast js-toast" role="status" aria-live="polite">
    <span class="adm-toast__dot"></span>
    <div>
      <div class="js-toast-title"></div>
      <div class="adm-toast__sub js-toast-sub"></div>
    </div>
  </div>

  <script src="{{ asset('assets/') }}/js/analytics.js"></script>
  <script src="{{ asset('assets/') }}/js/products-data.js"></script>
  <script>
    (function () {
      "use strict";

      /* ---------- Helpers ---------- */
      const $ = (s, root = document) => root.querySelector(s);
      const $$ = (s, root = document) => Array.from(root.querySelectorAll(s));
      const money = (n) => "৳ " + Number(n).toLocaleString("en-IN");
      const city = (addr) => (addr.split(",").pop() || "").trim();

      const STATUS = {
        Pending: { label: "Pending", cls: "pending" },
        Printing: { label: "Printing", cls: "printing" },
        Shipped: { label: "In courier", cls: "shipped" },
        Delivered: { label: "Delivered", cls: "delivered" }
      };

      const ICON = {
        truck: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
        phone: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
        chat: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
        file: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
        chevron: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>',
        external: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M7 17L17 7"/><polyline points="7 7 17 7 17 17"/></svg>'
      };

      /* ---------- Toast ---------- */
      let toastTimer;
      function toast(title, sub) {
        $(".js-toast-title").textContent = title;
        $(".js-toast-sub").textContent = sub || "";
        $(".js-toast").classList.add("is-visible");
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => $(".js-toast").classList.remove("is-visible"), 2800);
      }

      /* ---------- Data (Synced with Laravel DB) ---------- */
      const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}";

      function mapOrderFromDB(o) {
        let dateStr = "Recently";
        if (o.created_at) {
          const createdDate = new Date(o.created_at);
          const diffMins = Math.round((new Date() - createdDate) / 60000);
          dateStr = diffMins < 1 ? "Just now" : diffMins < 60 ? `${diffMins} min ago` : diffMins < 1440 ? `${Math.floor(diffMins/60)} hours ago` : createdDate.toLocaleDateString();
        }
        return {
          id: o.order_number || ('RBZ-' + o.id),
          dbId: o.id,
          date: dateStr,
          source: o.source || "Website",
          customer: o.customer_name || "Customer",
          phone: o.customer_phone || "",
          address: o.customer_address || "",
          zone: o.zone || "Inside Dhaka (৳70)",
          ratio: o.delivery_ratio || (o.customer_phone ? 94 : 50),
          parcels: o.parcels_delivered || "12 of 12",
          status: o.status || "Pending",
          courier: o.courier || "",
          consignment: (o.consignment_id && o.consignment_id !== 'Pending Dispatch') ? o.consignment_id : "",
          subtotal: Number(o.subtotal) || 0,
          shipping: Number(o.shipping_fee) || 70,
          total: Number(o.total) || 0,
          items: (o.items || []).map((i) => ({
            id: i.id,
            name: i.product_name,
            size: i.size,
            qty: i.qty,
            price: Number(i.unit_price),
            print: (i.custom_name ? (i.custom_name + (i.custom_number ? ' ' + i.custom_number : '')) : ''),
            img: i.image || '/assets/images/products/real-madrid-home-hs.jpg'
          }))
        };
      }

      function mapProductFromDB(p) {
        const labels = { "half-sleeve": "Half Sleeve", "full-sleeve": "Full Sleeve", "winter-hoodie": "Winter Hoodie", "footwear": "Boots", "tracksuit": "Tracksuit" };
        const inStockSizes = (p.variants || []).filter(v => v.is_in_stock).map(v => v.size);
        const allSizes = (p.variants && p.variants.length) ? p.variants.map(v => v.size) : (p.sleeve === "footwear" ? ["39", "40", "41", "42", "43", "44"] : ["S", "M", "L", "XL", "XXL"]);
        return {
          id: p.id,
          sku: p.sku || ("RBZ-" + p.id),
          name: p.name,
          club: p.club || "all",
          clubLabel: p.club ? p.club.toUpperCase() : "Club",
          sleeve: p.sleeve || "half-sleeve",
          sleeveLabel: labels[p.sleeve] || p.sleeve,
          price: Number(p.price),
          regularPrice: Number(p.regular_price || p.price),
          image: p.image || '/assets/images/products/real-madrid-home-hs.jpg',
          gallery: Array.isArray(p.gallery) ? p.gallery : (typeof p.gallery === 'string' ? (JSON.parse(p.gallery || '[]') || []) : []),
          shortDesc: p.short_desc || p.name,
          description: p.description || '',
          slug: p.slug || '',
          sizes: inStockSizes.length ? inStockSizes : allSizes,
          allSizes: allSizes,
          isReadyStock: !!p.is_ready_stock,
          hasCustomPrint: !!p.has_custom_print
        };
      }

      const initialServerOrders = @json($allOrders);
      let orders = (initialServerOrders && initialServerOrders.length)
        ? initialServerOrders.map(mapOrderFromDB)
        : [];

      const initialServerProducts = @json($products);
      let products = (initialServerProducts && initialServerProducts.length)
        ? initialServerProducts.map(mapProductFromDB)
        : (window.RABEZ_PRODUCTS || []);

      const initialServerCategories = @json($categories);
      let categories = (initialServerCategories && initialServerCategories.length)
        ? initialServerCategories
        : [];

      const initialServerCustomers = @json($customers);
      let customers = (initialServerCustomers && initialServerCustomers.length)
        ? initialServerCustomers
        : [];

      let orderFilter = "all";
      let orderQuery = "";
      let prodFilter = "all";
      let prodQuery = "";
      let catQuery = "";
      let custQuery = "";
      let openOrderId = null;
      let uploadedImage = "assets/images/products/messi-barca-arg-hoodie.jpg";

      // Selected IDs tracking for bulk actions
      const selectedMap = {
        orders: new Set(),
        products: new Set(),
        categories: new Set(),
        customers: new Set(),
        users: new Set(),
        media: new Set()
      };

      const currentUserId = {{ Auth::id() ?? 0 }};
      const initialServerUsers = @json($users ?? []);
      let users = (initialServerUsers && initialServerUsers.length)
        ? initialServerUsers
        : [];
      let userFilter = "all";
      let userQuery = "";

      /* ---------- Coupons State ---------- */
      const initialServerCoupons = @json($coupons ?? []);
      let couponsList = Array.isArray(initialServerCoupons) ? initialServerCoupons : [];

      /* ---------- Media Gallery & Product Images State ---------- */
      const initialServerMedia = @json($media ?? []);
      let mediaList = Array.isArray(initialServerMedia) ? initialServerMedia : [];
      let mediaQuery = "";

      // Add Product Image State
      let addFeaturedImage = "/assets/images/products/messi-barca-arg-hoodie.jpg";
      let addGalleryImages = [];

      // Edit Product Image State
      let editFeaturedImage = "";
      let editGalleryImages = [];

      /* ---------- Tabs ---------- */
      const PAGES = {
        "dashboard": ["Dashboard", "Store overview & live activity"],
        "orders": ["Orders", "Steadfast & Pathao connected"],
        "products": ["Products", "Manage stock by size"],
        "add-product": ["Add product", "Publish to the storefront"],
        "categories": ["Categories", "Size sets & default prices"],
        "print-queue": ["Print queue", "Custom name & number"],
        "pos-order": ["New order", "Facebook, WhatsApp or showroom"],
        "customers": ["Customers", "Courier delivery history & COD Risk"],
        "users": ["Users & Access", "Team roles, staff & customer accounts"],
        "gallery": ["Media Gallery", "iPhone & DSLR auto-WebP optimizer & asset library"],
        "discounts": ["Discounts & Promo Coupons", "Manage coupon campaigns, discount codes & minimum spend rules"],
        "pages": ["Storefront Pages (CMS)", "Create & manage custom pages like Privacy Policy, Terms & custom info"],
        "menus": ["Navigation Menu Builder", "Organize multi-level dropdown menus for Desktop Header & Footer"],
        "settings": ["Settings", "Delivery, courier credentials & Meta Pixel"]
      };

      function showTab(key) {
        $$(".js-tab").forEach((b) => {
          if (b.classList.contains("adm-nav-item") || b.classList.contains("adm-bottom-btn")) {
            b.classList.toggle("is-active", b.dataset.tab === key);
          }
        });
        $$(".adm-panel").forEach((p) => p.classList.toggle("is-active", p.dataset.panel === key));
        const page = PAGES[key] || PAGES.dashboard;
        $$(".js-page-title").forEach((el) => (el.textContent = page[0]));
        $(".js-page-sub").textContent = page[1];
        document.body.classList.remove("is-nav-open");
        window.scrollTo({ top: 0, behavior: "smooth" });
        if (key === "dashboard") { renderOrders(); renderOverviewStats(); }
        if (key === "orders") renderOrders();
        if (key === "print-queue") renderPrintQueue();
        if (key === "customers") renderCustomers();
        if (key === "gallery") renderMedia();
        if (key === "discounts") renderCoupons();
      }

      /* ---------- Bulk Actions & Bar ---------- */
      function updateBulkBar(type) {
        const count = selectedMap[type].size;
        const countEl = $(`.js-bulk-count-${type}`);
        if (countEl) countEl.textContent = count;
        const bar = $(`.js-bulk-bar-${type}`);
        if (bar) bar.classList.toggle("is-visible", count > 0);

        const master = $(`.js-select-all[data-type="${type}"]`);
        if (master) {
          const rowCbs = $$(`.js-row-cb[data-type="${type}"]`);
          if (rowCbs.length === 0) {
            master.checked = false;
            master.indeterminate = false;
          } else {
            const checkedCount = rowCbs.filter(cb => cb.checked).length;
            master.checked = checkedCount === rowCbs.length && checkedCount > 0;
            master.indeterminate = checkedCount > 0 && checkedCount < rowCbs.length;
          }
        }
      }

      function clearBulkSelection(type) {
        selectedMap[type].clear();
        $$(`.js-row-cb[data-type="${type}"]`).forEach(cb => cb.checked = false);
        updateBulkBar(type);
      }

      async function handleBulkDelete(type) {
        const ids = Array.from(selectedMap[type]);
        if (!ids.length) return;
        if (!confirm(`Are you sure you want to permanently delete ${ids.length} selected ${type}?`)) return;

        try {
          const res = await fetch(`/admin/${type}/bulk-delete`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({ ids: ids, _token: CSRF_TOKEN })
          });
          const data = await res.json();
          if (data.success) {
            toast(`${ids.length} ${type} deleted`, "Database updated successfully");
            selectedMap[type].clear();
            if (type === 'media') {
              mediaList = mediaList.filter(m => !ids.includes(String(m.id)));
              renderMedia();
            } else if (type === 'orders') {
              orders = orders.filter(o => !ids.includes(String(o.id)) && !ids.includes(String(o.dbId)));
              renderOrders();
            } else if (type === 'products') {
              products = products.filter(p => !ids.includes(String(p.id)));
              renderProducts();
            } else if (type === 'categories') {
              categories = categories.filter(c => !ids.includes(String(c.id)));
              renderCategories();
            } else if (type === 'customers') {
              customers = customers.filter(c => !ids.includes(String(c.phone)));
              renderCustomers();
              await syncOrdersFromDB();
            } else if (type === 'users') {
              users = users.filter(u => !ids.includes(String(u.id)));
              renderUsers();
      renderMedia();
      renderAddImages();
            }
            updateBulkBar(type);
          } else {
            alert("Bulk delete failed: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Bulk delete error", err.message);
        }
      }

      /* ---------- Column Show / Hide State & Init ---------- */
      function initColumnToggles() {
        const tables = ['orders', 'products', 'categories', 'customers', 'users'];
        tables.forEach(table => {
          const tableEl = $(`#table-${table}`);
          if (!tableEl) return;
          $$(`.js-col-toggle[data-table="${table}"]`).forEach(cb => {
            const col = cb.dataset.col;
            const saved = localStorage.getItem(`rabez_col_${table}_${col}`);
            if (saved !== null) {
              const visible = saved === '1';
              cb.checked = visible;
              tableEl.classList.toggle(`hide-col-${col}`, !visible);
            }
          });
        });
      }

      /* ---------- Orders ---------- */
      function riskHTML(o) {
        const warn = o.ratio < 60;
        return `<span class="adm-risk ${warn ? "adm-risk--warn" : ""}"><span class="adm-risk__bar"><span style="width:${o.ratio}%"></span></span>${o.ratio}%</span>`;
      }

      function chipHTML(status) {
        const s = STATUS[status] || { label: status, cls: "neutral" };
        return `<span class="adm-chip adm-chip--${s.cls}">${s.label}</span>`;
      }

      // Card row for Dashboard Recent Orders widget
      function orderRowCardHTML(o) {
        const thumbs = o.items.slice(0, 3).map((i) => `<img src="${i.img}" alt="">`).join("");
        const count = o.items.reduce((s, i) => s + i.qty, 0);
        let action = "";
        if (o.status === "Pending" && o.ratio >= 60) {
          action = `<button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-book" data-id="${o.id}" data-courier="Steadfast">Book</button>`;
        } else if (o.status === "Pending") {
          action = `<a href="tel:${o.phone}" class="adm-btn adm-btn--secondary adm-btn--sm js-stop">Call first</a>`;
        }
        return `
          <div class="adm-order-row js-order-row" data-id="${o.id}">
            <div class="c-id">
              <div class="adm-cell-main">#${o.id}</div>
              <div class="adm-cell-sub">${o.date} &middot; ${o.source}</div>
            </div>
            <div class="c-cust">
              <div class="adm-cell-main">${o.customer}</div>
              <div class="adm-cell-sub num">${o.phone} &middot; ${city(o.address)}</div>
            </div>
            <div class="c-items">
              <div class="adm-thumbs">${thumbs}<span class="adm-thumbs__text">${count} ${count > 1 ? "items" : "item"}</span></div>
            </div>
            <div class="c-total">
              <div class="adm-cell-main num">${money(o.total)}</div>
              <div class="adm-cell-sub">COD</div>
            </div>
            <div class="c-risk">${riskHTML(o)}</div>
            <div class="c-status">${chipHTML(o.status)}</div>
            <div class="c-actions">
              ${action}
              <span class="adm-icon-btn" aria-hidden="true">${ICON.chevron}</span>
            </div>
          </div>`;
      }

      // Table Row for Full Orders Management Table
      function orderTableRowHTML(o) {
        const thumbs = o.items.slice(0, 3).map((i) => `<img src="${i.img}" alt="" style="width:28px; height:28px; object-fit:cover; border-radius:4px; border:1px solid #E6E9E7;">`).join("");
        const count = o.items.reduce((s, i) => s + i.qty, 0);
        const isChecked = selectedMap.orders.has(String(o.id)) || selectedMap.orders.has(String(o.dbId));
        let action = "";
        if (o.status === "Pending" && o.ratio >= 60) {
          action = `<button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-book" data-id="${o.id}" data-courier="Steadfast" style="padding:4px 8px; font-size:12px;">Book</button>`;
        } else if (o.status === "Pending") {
          action = `<a href="tel:${o.phone}" class="adm-btn adm-btn--secondary adm-btn--sm js-stop" style="padding:4px 8px; font-size:12px;">Call</a>`;
        }
        return `
          <tr class="js-order-table-row" data-id="${o.id}">
            <td style="width:36px;">
              <input type="checkbox" class="adm-cb js-row-cb" data-type="orders" data-id="${o.id}" ${isChecked ? 'checked' : ''}>
            </td>
            <td data-col="id">
              <div class="adm-cell-main"><strong style="color:var(--adm-ink);">#${o.id}</strong></div>
              <div class="adm-cell-sub">${o.date} &middot; ${o.source}</div>
            </td>
            <td data-col="customer">
              <div class="adm-cell-main">${o.customer}</div>
              <div class="adm-cell-sub num">${o.phone} &middot; ${city(o.address)}</div>
            </td>
            <td data-col="items">
              <div style="display:flex; align-items:center; gap:6px;">
                ${thumbs}
                <span class="adm-cell-sub">${count} ${count > 1 ? "items" : "item"}</span>
              </div>
            </td>
            <td data-col="total">
              <div class="adm-cell-main num">${money(o.total)}</div>
              <div class="adm-cell-sub">COD</div>
            </td>
            <td data-col="risk">${riskHTML(o)}</td>
            <td data-col="courier">
              ${o.consignment
                ? `<div><span class="adm-cell-main">${o.courier}</span><div class="adm-cell-sub num">${o.consignment}</div></div>`
                : `<span class="adm-cell-sub">Unassigned</span>`}
            </td>
            <td data-col="status">${chipHTML(o.status)}</td>
            <td data-col="actions" style="text-align:right;">
              <div style="display:inline-flex; gap:6px; align-items:center;">
                ${action}
                <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-edit-order" data-id="${o.id}" style="padding:4px 8px; font-size:12px;">Edit</button>
                <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-delete-order-btn" data-id="${o.id}" style="padding:4px 8px; font-size:12px; background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Del</button>
              </div>
            </td>
          </tr>`;
      }

      function renderOrders() {
        const counts = { all: orders.length, Pending: 0, Printing: 0, Shipped: 0, Delivered: 0, Cancelled: 0 };
        orders.forEach((o) => {
          if (counts[o.status] !== undefined) counts[o.status]++;
        });
        $$("[data-count]").forEach((el) => {
          if (counts[el.dataset.count] !== undefined) el.textContent = counts[el.dataset.count];
        });
        $$(".js-order-count").forEach((el) => (el.textContent = counts.Pending + counts.Printing));

        const open = counts.Pending + counts.Printing;
        $$(".js-kpi-open").forEach((el) => (el.textContent = open));
        const readyCount = orders.filter((o) => o.status === "Pending" && o.ratio >= 60).length;
        $$(".js-kpi-open-sub").forEach((el) => (el.textContent = open > 0 ? `${readyCount} ready to ship` : 'No open orders'));

        // Render Dashboard recent orders
        const recentRoot = $("#adm-recent-orders");
        if (recentRoot) {
          if (!orders.length) {
            recentRoot.innerHTML = `<div style="text-align:center; padding:38px 16px; color:var(--adm-muted); font-size:13.5px;">No customer orders placed yet. As customers checkout on rabez.shop, orders will appear here in real-time.</div>`;
          } else {
            recentRoot.innerHTML = orders.slice(0, 5).map(orderRowCardHTML).join("");
          }
        }

        // Filter and render Full Orders Table
        let list = orders.slice();
        if (orderFilter !== "all") list = list.filter((o) => o.status === orderFilter);
        if (orderQuery) {
          const q = orderQuery.toLowerCase();
          list = list.filter((o) => o.id.toLowerCase().includes(q) || o.customer.toLowerCase().includes(q) || o.phone.includes(q));
        }

        const root = $("#adm-orders");
        if (root) {
          if (!list.length) {
            root.innerHTML = `<tr><td colspan="9" class="adm-empty" style="text-align:center; padding:32px;">No orders found matching filters.</td></tr>`;
          } else {
            root.innerHTML = list.map(orderTableRowHTML).join("");
          }
        }
        updateBulkBar('orders');
        renderOverviewStats();
        renderPrintQueue();
      }

      function renderOverviewStats() {
        const totalRevenue = orders.reduce((sum, o) => sum + (Number(o.total) || 0), 0);
        const openCount = orders.filter(o => ['Pending', 'Printing'].includes(o.status)).length;
        const codCount = orders.filter(o => o.status === 'Shipped').reduce((sum, o) => sum + (Number(o.total) || 0), 0);
        
        const revEl = document.querySelector(".js-kpi-revenue");
        if (revEl) revEl.textContent = money(totalRevenue);
        const openEl = document.querySelector(".js-kpi-open");
        if (openEl) openEl.textContent = openCount;
        const openSub = document.querySelector(".js-kpi-open-sub");
        if (openSub) openSub.textContent = openCount > 0 ? `${openCount} orders to process` : 'No open orders';
        const codEl = document.querySelector(".js-kpi-cod");
        if (codEl) codEl.textContent = money(codCount);

        const chartDesc = document.querySelector(".js-chart-desc");
        if (chartDesc) chartDesc.textContent = `Total ${money(totalRevenue)} across ${orders.length} orders`;

        const webTotal = orders.filter(o => (o.source || '').toLowerCase().includes('web') || !o.source).reduce((s, o) => s + (Number(o.total) || 0), 0);
        const socialTotal = orders.filter(o => (o.source || '').toLowerCase().includes('facebook') || (o.source || '').toLowerCase().includes('whatsapp')).reduce((s, o) => s + (Number(o.total) || 0), 0);
        const posTotal = orders.filter(o => (o.source || '').toLowerCase().includes('showroom') || (o.source || '').toLowerCase().includes('pos')).reduce((s, o) => s + (Number(o.total) || 0), 0);
        
        const webPct = totalRevenue > 0 ? Math.round((webTotal / totalRevenue) * 100) : 0;
        const socialPct = totalRevenue > 0 ? Math.round((socialTotal / totalRevenue) * 100) : 0;
        const posPct = totalRevenue > 0 ? Math.round((posTotal / totalRevenue) * 100) : 0;

        const wEl = document.querySelector(".js-channel-web");
        if (wEl) wEl.textContent = `${money(webTotal)} (${webPct}%)`;
        const sEl = document.querySelector(".js-channel-social");
        if (sEl) sEl.textContent = `${money(socialTotal)} (${socialPct}%)`;
        const pEl = document.querySelector(".js-channel-pos");
        if (pEl) pEl.textContent = `${money(posTotal)} (${posPct}%)`;

        const topSellBody = document.querySelector(".js-top-selling-body");
        if (topSellBody) {
          if (!orders.length) {
            topSellBody.innerHTML = `
              <div style="text-align:center; padding:38px 16px; color:var(--adm-muted); font-size:13px; line-height:1.5;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" style="margin-bottom:8px; opacity:0.5;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                <div>No sales recorded yet.</div>
                <div style="font-size:12px; margin-top:2px;">Your best-selling kits will rank here automatically as customer orders are placed.</div>
              </div>
            `;
          } else {
            const itemMap = {};
            orders.forEach(o => {
              (o.items || []).forEach(it => {
                const k = it.name || it.product_name || 'Jersey';
                if (!itemMap[k]) itemMap[k] = { name: k, image: it.image || '', count: 0, revenue: 0, price: it.unit_price || it.price || 0 };
                itemMap[k].count += Number(it.qty || 1);
                itemMap[k].revenue += Number(it.total_price || (it.unit_price * (it.qty || 1)) || 0);
              });
            });
            const topItems = Object.values(itemMap).sort((a, b) => b.count - a.count).slice(0, 3);
            topSellBody.innerHTML = topItems.map(it => `
              <div class="adm-item">
                <img src="${it.image || '/assets/images/products/real-madrid-home-hs.jpg'}" alt="${it.name}">
                <div style="flex:1; min-width:0;">
                  <div class="adm-cell-main">${it.name}</div>
                  <div class="adm-cell-sub">${it.count} sold &middot; ${money(it.price)}</div>
                </div>
                <span class="adm-cell-main num">${money(it.revenue)}</span>
              </div>
            `).join("");
          }
        }
      }

      function renderPrintQueue() {
        const container = document.getElementById("adm-print-queue-list");
        const chip = document.querySelector(".js-print-chip");
        if (!container) return;
        
        const printItems = [];
        orders.forEach(o => {
          if (['Pending', 'Printing'].includes(o.status) && Array.isArray(o.items)) {
            o.items.forEach(it => {
              if (it.custom_name || it.custom_number) {
                printItems.push({ order: o, item: it });
              }
            });
          }
        });

        if (chip) chip.textContent = `${printItems.length} waiting`;

        if (!printItems.length) {
          container.innerHTML = `
            <div style="text-align:center; padding:44px 20px; color:var(--adm-muted); font-size:13.5px;">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:8px; opacity:0.6;"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
              <div>No custom back prints waiting in the print queue.</div>
              <div style="font-size:12px; margin-top:4px;">When customers order custom jersey name &amp; number printing (+৳250), they will appear here.</div>
            </div>
          `;
          return;
        }

        container.innerHTML = printItems.map(p => `
          <div class="adm-print-row">
            <img src="${p.item.image || '/assets/images/products/real-madrid-home-hs.jpg'}" alt="${p.item.name}">
            <div>
              <div class="adm-cell-main">${p.item.name} &middot; ${p.item.size || 'L'}</div>
              <div class="adm-cell-sub">#${p.order.id} &middot; ${p.order.customer}, ${city(p.order.address)}</div>
            </div>
            <div class="adm-print-label">
              <div class="adm-print-label__cap">Back print</div>
              <div class="adm-print-label__text">${(p.item.custom_name || '').toUpperCase()} &middot; ${p.item.custom_number || ''}</div>
            </div>
            <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-mark-printed" data-id="${p.order.id}">Mark as printed</button>
          </div>
        `).join("");
      }

      async function syncOrdersFromDB() {
        try {
          const res = await fetch("/admin/api/orders");
          const data = await res.json();
          if (data.success && Array.isArray(data.orders)) {
            orders = data.orders.map(mapOrderFromDB);
            renderOrders();
            toast("Orders Synced", `${orders.length} orders loaded from database`);
          }
        } catch (err) {
          console.error(err);
          toast("Sync Failed", err.message);
        }
      }

      async function bookOrder(id, courier) {
        const o = orders.find((x) => x.id === id);
        if (!o) return;

        try {
          const res = await fetch(`/admin/orders/${o.dbId || o.id}/book-courier`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({ courier: courier, _token: CSRF_TOKEN })
          });
          const data = await res.json();
          if (data.success) {
            o.courier = data.courier || courier;
            o.consignment = data.consignment_id;
            o.status = "Shipped";
            renderOrders();
            if (openOrderId === id) openFullOrderDrawer(id);
            toast(`Booked with ${courier}`, `#${o.id} · ${o.consignment}`);
          } else {
            alert("Booking failed: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Booking error", err.message);
        }
      }

      async function deleteOrder(id) {
        const o = orders.find((x) => x.id === id);
        if (!o) return;

        if (!confirm(`Are you sure you want to permanently delete Order #${o.id}?`)) {
          return;
        }

        try {
          const res = await fetch(`/admin/orders/${o.dbId || o.id}`, {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({ _token: CSRF_TOKEN })
          });
          const data = await res.json();
          if (data.success) {
            orders = orders.filter((x) => x.id !== id);
            closeOverlays();
            renderOrders();
            toast("Order Deleted", `#${id} removed permanently`);
          } else {
            alert("Failed to delete order: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Delete failed", err.message);
        }
      }

      /* ---------- Full Order Edit Drawer ---------- */
      function renderOrderItemRow(item, index) {
        return `
          <div class="adm-order-edit-item" data-index="${index}" style="background:#fff; border:1px solid #E6E9E7; border-radius:8px; padding:10px; display:grid; gap:8px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-size:12px; font-weight:700; color:var(--adm-ink);">Item #${index + 1}</span>
              <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-remove-order-item" data-index="${index}" style="padding:2px 8px; font-size:11px; background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Remove</button>
            </div>
            <div class="adm-field">
              <label class="adm-label" style="font-size:11px;">Product Title</label>
              <input type="text" class="adm-input js-item-name" value="${item.name || ''}" required style="padding:6px 10px; font-size:13px;">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px;">
              <div class="adm-field">
                <label class="adm-label" style="font-size:11px;">Size</label>
                <input type="text" class="adm-input js-item-size" value="${item.size || 'L'}" required style="padding:6px 10px; font-size:13px;">
              </div>
              <div class="adm-field">
                <label class="adm-label" style="font-size:11px;">Qty</label>
                <input type="number" min="1" class="adm-input num js-item-qty" value="${item.qty || 1}" required style="padding:6px 10px; font-size:13px;">
              </div>
              <div class="adm-field">
                <label class="adm-label" style="font-size:11px;">Unit Price (৳)</label>
                <input type="number" min="0" class="adm-input num js-item-price" value="${item.price || 0}" required style="padding:6px 10px; font-size:13px;">
              </div>
            </div>
            <div class="adm-field">
              <label class="adm-label" style="font-size:11px;">Custom Name & Number (Optional)</label>
              <input type="text" class="adm-input js-item-print" value="${item.print || ''}" placeholder="e.g. MESSI 10" style="padding:6px 10px; font-size:13px;">
            </div>
          </div>`;
      }

      function calcOrderTotals() {
        let subtotal = 0;
        $$(".adm-order-edit-item").forEach(itemEl => {
          const qty = Number(itemEl.querySelector(".js-item-qty")?.value) || 0;
          const price = Number(itemEl.querySelector(".js-item-price")?.value) || 0;
          subtotal += (qty * price);
        });
        const shipping = Number($("#full-edit-shipping")?.value) || 0;
        const total = subtotal + shipping;
        if ($(".js-calc-subtotal")) $(".js-calc-subtotal").textContent = money(subtotal);
        if ($(".js-calc-shipping")) $(".js-calc-shipping").textContent = money(shipping);
        if ($(".js-calc-total")) $(".js-calc-total").textContent = money(total);
        return { subtotal, shipping, total };
      }

      function openFullOrderDrawer(id) {
        const o = orders.find((x) => x.id === id);
        if (!o) return;
        openOrderId = id;

        $(".js-d-date").textContent = `${o.date} · ${o.source}`;
        $(".js-d-id").textContent = `Order #${o.id}`;
        $(".js-d-status").innerHTML = chipHTML(o.status);

        const itemsHTML = o.items.map((item, idx) => renderOrderItemRow(item, idx)).join("");
        const warn = o.ratio < 60;

        $(".js-d-body").innerHTML = `
          <div style="margin-bottom:14px;">
            <div class="adm-section-label">Quick Actions</div>
            <div class="adm-quick-actions" style="margin-top:4px;">
              <a href="tel:${o.phone}" class="adm-btn adm-btn--secondary adm-btn--sm">${ICON.phone} Call</a>
              <a href="https://api.whatsapp.com/send/?phone=88${o.phone}" target="_blank" rel="noopener" class="adm-btn adm-btn--secondary adm-btn--sm">${ICON.chat} WhatsApp</a>
              <a href="/track-order?order=${o.id}" target="_blank" class="adm-btn adm-btn--secondary adm-btn--sm">${ICON.file} View Invoice</a>
            </div>
          </div>

          <form id="js-full-order-form" style="display:grid; gap:16px;">
            <!-- Customer Details -->
            <div style="background:#FAFBFA; border:1px solid #E6E9E7; border-radius:10px; padding:12px;">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <span class="adm-section-label" style="margin:0; font-weight:700;">Customer & Delivery Address</span>
                <span class="adm-chip adm-chip--neutral" style="font-size:11px;">Database Live</span>
              </div>
              <div style="display:grid; gap:8px;">
                <div class="adm-field">
                  <label class="adm-label" style="font-size:11.5px; margin-bottom:2px;">Customer Name</label>
                  <input type="text" id="full-edit-name" class="adm-input" value="${o.customer}" required style="padding:6px 10px; font-size:13px;">
                </div>
                <div class="adm-field">
                  <label class="adm-label" style="font-size:11.5px; margin-bottom:2px;">Phone Number (11 digits)</label>
                  <input type="text" id="full-edit-phone" class="adm-input num" value="${o.phone}" required style="padding:6px 10px; font-size:13px;">
                </div>
                <div class="adm-field">
                  <label class="adm-label" style="font-size:11.5px; margin-bottom:2px;">Delivery Address</label>
                  <textarea id="full-edit-address" class="adm-input" rows="2" required style="padding:6px 10px; font-size:13px;">${o.address}</textarea>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                  <div class="adm-field">
                    <label class="adm-label" style="font-size:11.5px; margin-bottom:2px;">Zone</label>
                    <select id="full-edit-zone" class="adm-select" style="padding:6px 10px; font-size:13px;">
                      <option value="Inside Dhaka (৳70)" ${o.zone.includes("70") || o.zone.toLowerCase().includes("inside") ? "selected" : ""}>Inside Dhaka (৳70)</option>
                      <option value="Outside Dhaka (৳130)" ${o.zone.includes("130") || o.zone.toLowerCase().includes("outside") ? "selected" : ""}>Outside Dhaka (৳130)</option>
                    </select>
                  </div>
                  <div class="adm-field">
                    <label class="adm-label" style="font-size:11.5px; margin-bottom:2px;">Delivery Fee (৳)</label>
                    <input type="number" id="full-edit-shipping" class="adm-input num" value="${o.shipping}" style="padding:6px 10px; font-size:13px;">
                  </div>
                </div>
              </div>
            </div>

            <!-- Status & Logistics -->
            <div style="background:#FAFBFA; border:1px solid #E6E9E7; border-radius:10px; padding:12px;">
              <span class="adm-section-label" style="margin:0 0 10px 0; font-weight:700;">Status & Logistics</span>
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div class="adm-field">
                  <label class="adm-label" style="font-size:11.5px; margin-bottom:2px;">Order Status</label>
                  <select id="full-edit-status" class="adm-select" style="padding:6px 10px; font-size:13px;">
                    ${Object.keys(STATUS).map((k) => `<option value="${k}" ${o.status === k ? "selected" : ""}>${STATUS[k].label}</option>`).join("")}
                    <option value="Cancelled" ${o.status === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                  </select>
                </div>
                <div class="adm-field">
                  <label class="adm-label" style="font-size:11.5px; margin-bottom:2px;">Courier</label>
                  <select id="full-edit-courier" class="adm-select" style="padding:6px 10px; font-size:13px;">
                    <option value="" ${!o.courier ? 'selected' : ''}>None / Unassigned</option>
                    <option value="Steadfast" ${o.courier === 'Steadfast' ? 'selected' : ''}>Steadfast</option>
                    <option value="Pathao" ${o.courier === 'Pathao' ? 'selected' : ''}>Pathao</option>
                    <option value="RedX" ${o.courier === 'RedX' ? 'selected' : ''}>RedX</option>
                    <option value="Paperfly" ${o.courier === 'Paperfly' ? 'selected' : ''}>Paperfly</option>
                  </select>
                </div>
              </div>
              <div class="adm-field" style="margin-top:8px;">
                <label class="adm-label" style="font-size:11.5px; margin-bottom:2px;">Consignment / Tracking ID</label>
                <input type="text" id="full-edit-consignment" class="adm-input num" value="${o.consignment || ''}" placeholder="e.g. ST-8839210" style="padding:6px 10px; font-size:13px;">
              </div>
            </div>

            <!-- Items Section -->
            <div style="background:#FAFBFA; border:1px solid #E6E9E7; border-radius:10px; padding:12px;">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <span class="adm-section-label" style="margin:0; font-weight:700;">Order Items</span>
                <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-add-order-item-btn" style="padding:3px 8px; font-size:11.5px;">+ Add Item</button>
              </div>
              <div id="full-order-items-list" style="display:grid; gap:10px;">
                ${itemsHTML}
              </div>
            </div>

            <!-- Price Breakdown -->
            <div style="background:#FAFBFA; border:1px solid #E6E9E7; border-radius:10px; padding:12px;">
              <div class="adm-section-label" style="margin:0 0 10px 0; font-weight:700;">Payment Breakdown</div>
              <div class="adm-kv"><span>Subtotal</span><strong class="num js-calc-subtotal">${money(o.subtotal)}</strong></div>
              <div class="adm-kv"><span>Delivery</span><strong class="num js-calc-shipping">${money(o.shipping)}</strong></div>
              <div class="adm-kv adm-kv--total"><span>Collect on Delivery (COD)</span><strong class="num js-calc-total">${money(o.total)}</strong></div>
            </div>

            <button type="submit" class="adm-btn adm-btn--primary" style="padding:10px 16px; font-size:14px; font-weight:600;">
              💾 Save Full Order Details
            </button>
          </form>`;

        const booked = !!o.consignment;
        $(".js-d-foot").innerHTML = `
          <button type="button" class="adm-btn adm-btn--secondary js-book" data-id="${o.id}" data-courier="Pathao" ${booked ? "disabled" : ""}>Book Pathao</button>
          <button type="button" class="adm-btn adm-btn--primary js-book" data-id="${o.id}" data-courier="Steadfast" ${booked ? "disabled" : ""}>${ICON.truck} ${booked ? "Booked" : "Book Steadfast"}</button>
          <button type="button" class="adm-btn adm-btn--danger js-delete-order-btn" data-id="${o.id}" style="grid-column: 1 / -1; background:#fee2e2; color:#b91c1c; border-color:#fca5a5; margin-top:4px;">Delete Order</button>`;

        document.body.classList.add("is-drawer-open");
      }

      async function saveFullOrder(e) {
        e.preventDefault();
        const o = orders.find(x => x.id === openOrderId);
        if (!o) return;

        const items = [];
        $$(".adm-order-edit-item").forEach(itemEl => {
          const name = itemEl.querySelector(".js-item-name")?.value.trim() || "Item";
          const size = itemEl.querySelector(".js-item-size")?.value.trim() || "L";
          const qty = Number(itemEl.querySelector(".js-item-qty")?.value) || 1;
          const price = Number(itemEl.querySelector(".js-item-price")?.value) || 0;
          const print = itemEl.querySelector(".js-item-print")?.value.trim() || "";
          let customName = "";
          let customNumber = "";
          if (print) {
            const parts = print.split(" ");
            customNumber = parts.pop();
            customName = parts.join(" ") || customNumber;
          }
          items.push({
            product_name: name,
            size: size,
            qty: qty,
            unit_price: price,
            custom_name: customName,
            custom_number: customNumber
          });
        });

        const shipping = Number($("#full-edit-shipping").value) || 0;
        const customerName = $("#full-edit-name").value.trim();
        const customerPhone = $("#full-edit-phone").value.trim();
        const customerAddress = $("#full-edit-address").value.trim();
        const zone = $("#full-edit-zone").value;
        const status = $("#full-edit-status").value;
        const courier = $("#full-edit-courier").value;
        const consignmentId = $("#full-edit-consignment").value.trim();

        const payload = {
          customer_name: customerName,
          customer_phone: customerPhone,
          customer_address: customerAddress,
          zone: zone,
          shipping_fee: shipping,
          status: status,
          courier: courier,
          consignment_id: consignmentId,
          items: items,
          _token: CSRF_TOKEN
        };

        try {
          const res = await fetch(`/admin/orders/${o.dbId || o.id}/update-full`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.success) {
            o.customer = customerName;
            o.phone = customerPhone;
            o.address = customerAddress;
            o.zone = zone;
            o.shipping = shipping;
            o.status = status;
            o.courier = courier;
            o.consignment = consignmentId;
            o.items = items.map(i => ({
              id: Math.floor(Math.random() * 1000),
              name: i.product_name,
              size: i.size,
              qty: i.qty,
              price: i.unit_price,
              print: (i.custom_name ? (i.custom_name + (i.custom_number ? ' ' + i.custom_number : '')) : ''),
              img: '/assets/images/products/real-madrid-home-hs.jpg'
            }));
            const { subtotal, total } = calcOrderTotals();
            o.subtotal = subtotal;
            o.total = total;

            closeOverlays();
            renderOrders();
            toast("Order Updated", `#${o.id} full details saved to database`);
          } else {
            alert("Failed to update order: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Save error", err.message);
        }
      }

      function closeOverlays() {
        document.body.classList.remove(
          "is-drawer-open",
          "is-prod-drawer-open",
          "is-cat-drawer-open",
          "is-cust-drawer-open",
          "is-user-drawer-open",
          "is-page-drawer-open",
          "is-nav-open"
        );
        openOrderId = null;
      }

      /* ---------- Products ---------- */
      function sizesFor(p) {
        return p.allSizes || (p.sleeve === "footwear" ? ["39", "40", "41", "42", "43", "44"] : ["S", "M", "L", "XL", "XXL"]);
      }

      function productTableRowHTML(p) {
        const all = sizesFor(p);
        const inStock = all.filter((s) => p.sizes.includes(s)).length;
        const stock = inStock === 0
          ? `<span class="adm-chip adm-chip--danger">Sold out</span>`
          : inStock < all.length
            ? `<span class="adm-chip adm-chip--pending">${inStock} of ${all.length}</span>`
            : `<span class="adm-chip adm-chip--delivered">In stock</span>`;
        const sizes = all.map((s) => `<button type="button" class="adm-size ${p.sizes.includes(s) ? "" : "is-out"} js-size" data-id="${p.id}" data-size="${s}">${s}</button>`).join("");
        const isChecked = selectedMap.products.has(String(p.id));
        return `
          <tr data-id="${p.id}">
            <td style="width:36px;">
              <input type="checkbox" class="adm-cb js-row-cb" data-type="products" data-id="${p.id}" ${isChecked ? 'checked' : ''}>
            </td>
            <td data-col="product">
              <div style="display:flex; align-items:center; gap:10px;">
                <img src="${p.image}" alt="" style="width:40px; height:48px; object-fit:cover; border-radius:6px; border:1px solid #E6E9E7;" loading="lazy">
                <div style="min-width:0;">
                  <div class="adm-cell-main">${p.name}</div>
                  <div class="adm-cell-sub">${p.sku || "—"} &middot; ${p.clubLabel || ""}</div>
                  <button type="button" class="adm-chip js-toggle-custom-print" data-id="${p.id}" style="margin-top:5px; font-size:11px; padding:2px 8px; cursor:pointer; ${p.hasCustomPrint ? 'background:rgba(4,244,4,0.14); color:#047857; border:1px solid #10B981; font-weight:700;' : 'background:#F3F4F6; color:#6B7280; border:1px solid #D1D5DB; font-weight:600;'}" title="Tap to enable or disable Custom Name &amp; Number on this product">
                    ${p.hasCustomPrint ? '✓ Custom Print: ON' : 'Custom Print: OFF'}
                  </button>
                </div>
              </div>
            </td>
            <td data-col="category"><span class="adm-chip adm-chip--neutral adm-chip--plain">${p.sleeveLabel}</span></td>
            <td data-col="sizes"><div class="adm-sizes">${sizes}</div></td>
            <td data-col="stock">${stock}</td>
            <td data-col="price" class="adm-cell-main num" style="text-align:right;">${money(p.price)}</td>
            <td data-col="actions" style="text-align:right;">
              <div style="display:inline-flex; gap:6px; justify-content:flex-end; align-items:center;">
                <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-edit-product" data-id="${p.id}" style="padding:4px 8px; font-size:12px;">Edit</button>
                <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-delete-product" data-id="${p.id}" style="padding:4px 8px; font-size:12px; background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Del</button>
                <a href="/product/${p.id}" target="_blank" class="adm-icon-btn" title="View on store">${ICON.external}</a>
              </div>
            </td>
          </tr>`;
      }

      function renderProducts() {
        $$(".js-prod-count").forEach((el) => (el.textContent = products.length));
        $$(".js-kpi-products").forEach((el) => (el.textContent = products.length));

        let list = products.slice();
        if (prodFilter !== "all") list = list.filter((p) => p.sleeve === prodFilter);
        if (prodQuery) {
          const q = prodQuery.toLowerCase();
          list = list.filter((p) => p.name.toLowerCase().includes(q) || (p.sku || "").toLowerCase().includes(q));
        }

        const root = $("#adm-products");
        if (root) {
          if (!list.length) {
            root.innerHTML = `<tr><td colspan="7" class="adm-empty" style="text-align:center; padding:32px;">No products match your search.</td></tr>`;
          } else {
            root.innerHTML = list.map(productTableRowHTML).join("");
          }
        }

        const pos = $("#pos-product");
        if (pos) {
          const current = pos.value;
          pos.innerHTML = products.map((p) => `<option value="${p.id}">${p.name} — ${money(p.price)}</option>`).join("");
          if (current) pos.value = current;
        }
        updateBulkBar('products');
      }

      
      /* ========================================================================
         MEDIA GALLERY & PICKER MODAL ENGINE (Auto WebP & iPhone EXIF)
         ======================================================================== */
      function renderMedia() {
        const grid = document.getElementById("adm-gallery-grid");
        const totalEls = document.querySelectorAll(".js-stat-media-total, .js-gallery-count-badge");
        totalEls.forEach(el => el.textContent = mediaList.length);

        if (!grid) return;

        let list = mediaList.slice();
        if (mediaQuery) {
          const q = mediaQuery.toLowerCase();
          list = list.filter(m => (m.original_name || m.filename || '').toLowerCase().includes(q));
        }

        if (list.length === 0) {
          grid.innerHTML = `
            <div style="grid-column: 1 / -1; padding: 48px 20px; text-align: center; color: var(--adm-muted); background: var(--adm-subtle); border: 1px dashed var(--adm-border); border-radius: 8px;">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:8px; opacity:0.5;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              <div style="font-weight: 600; font-size: 14px; color: var(--adm-ink);">No media photos found</div>
              <div style="font-size: 12px; margin-top: 4px;">Upload photos from device or drag &amp; drop high-MB jersey photos.</div>
            </div>`;
          return;
        }

        grid.innerHTML = list.map(m => {
          const isSelected = selectedMap.media.has(String(m.id));
          const sizeKB = m.file_size ? Math.round(m.file_size / 1024) : null;
          const format = (m.mime_type || '').includes('webp') ? 'WEBP' : ((m.mime_type || '').includes('png') ? 'PNG' : 'JPG');
          const dim = (m.width && m.height) ? `${m.width}×${m.height}` : '';
          const imgUrl = (m.url.startsWith('http') || m.url.startsWith('/')) ? m.url : ('/' + m.url);
          const name = m.original_name || m.filename || 'Jersey photo';

          return `
            <div class="adm-gallery-item ${isSelected ? 'is-selected' : ''}" data-id="${m.id}" data-url="${imgUrl}" data-name="${name}" data-size="${sizeKB ? `${sizeKB} KB` : 'WebP'}" data-dim="${dim}" data-format="${format}">
              <input type="checkbox" class="adm-cb js-select-row adm-gallery-item__cb" data-type="media" data-id="${m.id}" ${isSelected ? 'checked' : ''} onclick="event.stopPropagation()">
              <span class="adm-gallery-item__badge">${format}</span>
              <img src="${imgUrl}" alt="${name}" loading="lazy">
              <div class="adm-gallery-item__meta">${name}</div>
              <div class="adm-gallery-item__actions">
                <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-media-copy-url" data-url="${imgUrl}" style="padding:4px 8px; font-size:11px;" title="Copy URL">Copy URL</button>
                <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-media-del-btn" data-id="${m.id}" style="padding:4px 8px; font-size:11px; background:#fee2e2; color:#b91c1c; border-color:#fca5a5;" title="Delete">Del</button>
              </div>
            </div>`;
        }).join('');
      }

      window.openPhotoSheet = function(m) {
        const sheet = document.getElementById('adm-photo-sheet');
        if (!sheet) return;
        document.getElementById('photo-sheet-img').src = m.url;
        document.getElementById('photo-sheet-name').textContent = m.name;
        document.getElementById('photo-sheet-dim').textContent = m.dim || 'Auto Resolution';
        document.getElementById('photo-sheet-size').textContent = m.size || 'WebP';
        document.getElementById('photo-sheet-format').textContent = m.format || 'WEBP';
        const urlInput = document.getElementById('photo-sheet-url-input');
        const directUrl = m.url.startsWith('http') ? m.url : (window.location.origin + (m.url.startsWith('/') ? m.url : '/' + m.url));
        if (urlInput) urlInput.value = directUrl;

        document.getElementById('photo-sheet-copy-btn').onclick = () => {
          navigator.clipboard.writeText(directUrl).then(() => {
            toast("URL Copied to Clipboard", directUrl);
          }).catch(() => {
            prompt("Copy URL manually:", directUrl);
          });
        };
        document.getElementById('photo-sheet-del-btn').onclick = () => {
          closePhotoSheet();
          deleteMedia(m.id);
        };
        sheet.style.display = 'flex';
      };

      window.closePhotoSheet = function() {
        const sheet = document.getElementById('adm-photo-sheet');
        if (sheet) sheet.style.display = 'none';
      };

      async function uploadMediaFiles(files, onComplete) {
        if (!files || files.length === 0) return;
        const fd = new FormData();
        for (let i = 0; i < files.length; i++) {
          fd.append('files[]', files[i]);
        }
        fd.append('_token', CSRF_TOKEN);

        toast("Optimizing & Converting to WebP...", `Processing ${files.length} photo(s)`);

        try {
          const res = await fetch('/admin/media/upload', {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: fd
          });
          const data = await res.json();
          if (data.success && data.media && data.media.length) {
            data.media.forEach(newM => {
              if (!mediaList.some(m => m.id === newM.id)) {
                mediaList.unshift(newM);
              }
            });
            renderMedia();
            toast("Upload & WebP Complete", `${data.media.length} image(s) optimized under 250KB`);
            if (typeof onComplete === 'function') onComplete(data.media);
          } else {
            alert("Upload failed: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error("Upload error:", err);
          toast("Upload failed", err.message);
        }
      }

      async function deleteMedia(id) {
        if (!confirm("Are you sure you want to delete this media image?")) return;
        try {
          const res = await fetch(`/admin/media/${id}`, {
            method: 'DELETE',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': CSRF_TOKEN
            }
          });
          const data = await res.json();
          if (data.success) {
            mediaList = mediaList.filter(m => m.id != id);
            selectedMap.media.delete(String(id));
            renderMedia();
            updateBulkBar('media');
            toast("Image deleted", "Media library updated");
          } else {
            alert("Delete failed: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          toast("Delete failed", err.message);
        }
      }

      /* ---------- Reusable Media Picker Modal ---------- */
      let mediaPickerState = {
        mode: 'single',
        title: 'Choose from Media Library',
        selected: new Set(),
        onSelect: null
      };

      window.openMediaPicker = function({ mode = 'single', title = 'Choose from Media Library', selected = [], onSelect }) {
        mediaPickerState.mode = mode;
        mediaPickerState.title = title;
        mediaPickerState.onSelect = onSelect;
        mediaPickerState.selected = new Set(selected.map(String));

        document.getElementById("adm-modal-title").textContent = title;
        document.getElementById("adm-modal-subtitle").textContent = mode === 'single'
          ? 'Select 1 image for Front View'
          : 'Select multiple images for Gallery & Back View';

        const searchInput = document.getElementById("adm-modal-search");
        if (searchInput) searchInput.value = "";

        renderMediaModalGrid();
        document.getElementById("adm-media-modal").style.display = "flex";
      };

      window.closeMediaPicker = function() {
        document.getElementById("adm-media-modal").style.display = "none";
      };

      window.confirmMediaPicker = function() {
        if (!mediaPickerState.onSelect) {
          closeMediaPicker();
          return;
        }
        const selectedUrls = [];
        mediaPickerState.selected.forEach(idOrUrl => {
          const m = mediaList.find(x => String(x.id) === String(idOrUrl) || x.url === idOrUrl);
          if (m) selectedUrls.push(m.url);
          else selectedUrls.push(idOrUrl);
        });
        mediaPickerState.onSelect(mediaPickerState.mode === 'single' ? (selectedUrls[0] || null) : selectedUrls);
        closeMediaPicker();
      };

      window.renderMediaModalGrid = function() {
        const grid = document.getElementById("adm-modal-grid");
        const countEl = document.getElementById("adm-modal-selected-count");
        if (!grid) return;

        const searchInput = document.getElementById("adm-modal-search");
        const q = searchInput ? searchInput.value.toLowerCase().trim() : "";
        let list = mediaList.slice();
        if (q) {
          list = list.filter(m => (m.original_name || m.filename || '').toLowerCase().includes(q));
        }

        if (countEl) countEl.textContent = mediaPickerState.selected.size;

        if (list.length === 0) {
          grid.innerHTML = '<div style="grid-column:1/-1; padding:30px; text-align:center; color:#7D8782;">No media found. Click "Upload New" above.</div>';
          return;
        }

        grid.innerHTML = list.map(m => {
          const isChecked = mediaPickerState.selected.has(String(m.id)) || mediaPickerState.selected.has(m.url);
          const imgUrl = (m.url.startsWith('http') || m.url.startsWith('/')) ? m.url : ('/' + m.url);
          const name = m.original_name || m.filename || 'Jersey';

          return `
            <div class="adm-modal-tile ${isChecked ? 'is-selected' : ''}" data-id="${m.id}" data-url="${m.url}" style="position:relative; aspect-ratio:1; border-radius:6px; overflow:hidden; border:2px solid ${isChecked ? '#22C55E' : 'var(--adm-border)'}; cursor:pointer; background:#0F1311; transition:all 0.15s ease;">
              <img src="${imgUrl}" alt="${name}" loading="lazy" style="width:100%; height:100%; object-fit:cover;">
              ${isChecked ? `<span style="position:absolute; top:4px; right:4px; width:20px; height:20px; border-radius:50%; background:#22C55E; color:#0A0C0B; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; box-shadow:0 2px 6px rgba(0,0,0,0.4);">✓</span>` : ''}
              <div style="position:absolute; bottom:0; inset-inline:0; background:rgba(0,0,0,0.8); padding:3px 6px; font-size:9.5px; color:#fff; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                ${name}
              </div>
            </div>`;
        }).join('');
      };

      window.handleModalUpload = function(files) {
        if (!files || files.length === 0) return;
        uploadMediaFiles(files, (newMedia) => {
          if (newMedia && newMedia.length) {
            if (mediaPickerState.mode === 'single') {
              mediaPickerState.selected.clear();
              mediaPickerState.selected.add(newMedia[0].url);
            } else {
              newMedia.forEach(m => mediaPickerState.selected.add(m.url));
            }
            renderMediaModalGrid();
          }
        });
      };

      // Modal tile click listener
      document.getElementById("adm-modal-grid")?.addEventListener("click", (e) => {
        const tile = e.target.closest(".adm-modal-tile");
        if (!tile) return;
        const url = tile.dataset.url;
        const id = tile.dataset.id;
        const key = url || id;

        if (mediaPickerState.mode === 'single') {
          mediaPickerState.selected.clear();
          mediaPickerState.selected.add(key);
          renderMediaModalGrid();
        } else {
          if (mediaPickerState.selected.has(key)) {
            mediaPickerState.selected.delete(key);
          } else {
            mediaPickerState.selected.add(key);
          }
          renderMediaModalGrid();
        }
      });

      // Modal tile double click listener for instant confirm in single mode
      document.getElementById("adm-modal-grid")?.addEventListener("dblclick", (e) => {
        const tile = e.target.closest(".adm-modal-tile");
        if (!tile || mediaPickerState.mode !== 'single') return;
        mediaPickerState.selected.clear();
        mediaPickerState.selected.add(tile.dataset.url || tile.dataset.id);
        confirmMediaPicker();
      });

      /* ---------- Add Product Image Management ---------- */
      function renderAddImages() {
        const preview = document.getElementById("adm-add-featured-preview");
        const hiddenUrl = document.getElementById("adm-add-featured-url");
        if (preview) {
          const src = addFeaturedImage.startsWith('http') || addFeaturedImage.startsWith('/') ? addFeaturedImage : ('/' + addFeaturedImage);
          preview.src = src;
        }
        if (hiddenUrl) hiddenUrl.value = addFeaturedImage;

        const strip = document.getElementById("adm-add-gallery-strip");
        if (!strip) return;

        if (addGalleryImages.length === 0) {
          strip.innerHTML = `<div style="color:var(--adm-muted); font-size:12px; padding:12px 14px; width:100%; text-align:center;">
            No gallery photos added yet. Upload or pick photos &mdash; the <strong>1st image will automatically be used as the Back View</strong> on card hover &amp; 360 viewer.
          </div>`;
          return;
        }

        strip.innerHTML = addGalleryImages.map((img, idx) => {
          const src = img.startsWith('http') || img.startsWith('/') ? img : ('/' + img);
          const isBack = (idx === 0);
          return `
            <div class="adm-gallery-strip-item ${isBack ? 'is-back-view' : ''}" title="${isBack ? 'Back View (Card Hover)' : `Angle #${idx+1}`}">
              <img src="${src}" alt="Gallery ${idx+1}">
              <span style="position:absolute; top:3px; left:3px; z-index:2; font-size:8px; font-weight:800; padding:1px 4px; border-radius:3px; background:${isBack ? '#22C55E' : '#111513'}; color:${isBack ? '#000' : '#fff'};">
                ${isBack ? 'BACK' : `#${idx+1}`}
              </span>
              <div class="adm-strip-actions">
                <button type="button" class="adm-strip-btn" onclick="makeFrontInAdd(${idx})" title="Set as Front View">Make Front</button>
                <button type="button" class="adm-strip-btn adm-strip-btn--danger" onclick="removeGalleryInAdd(${idx})" title="Remove">Remove</button>
              </div>
            </div>`;
        }).join('');
      }

      window.chooseFeaturedForAdd = function() {
        openMediaPicker({
          mode: 'single',
          title: 'Select Featured Image (Front View)',
          selected: addFeaturedImage ? [addFeaturedImage] : [],
          onSelect: (url) => {
            if (url) {
              addFeaturedImage = url;
              renderAddImages();
            }
          }
        });
      };

      window.chooseGalleryForAdd = function() {
        openMediaPicker({
          mode: 'multiple',
          title: 'Select Gallery Images (Back View & Angles)',
          selected: addGalleryImages,
          onSelect: (urls) => {
            if (urls && urls.length) {
              urls.forEach(u => {
                if (!addGalleryImages.includes(u)) addGalleryImages.push(u);
              });
              renderAddImages();
            }
          }
        });
      };

      window.makeFrontInAdd = function(idx) {
        if (idx < 0 || idx >= addGalleryImages.length) return;
        const oldFront = addFeaturedImage;
        addFeaturedImage = addGalleryImages[idx];
        addGalleryImages[idx] = oldFront;
        renderAddImages();
        toast("Front View Swapped", "Image promoted to Featured Front View");
      };

      window.removeGalleryInAdd = function(idx) {
        if (idx < 0 || idx >= addGalleryImages.length) return;
        addGalleryImages.splice(idx, 1);
        renderAddImages();
      };

      /* ---------- Edit Product Image Management ---------- */
      function renderEditImages() {
        const preview = document.getElementById("edit-prod-featured-preview");
        if (preview) {
          const src = editFeaturedImage ? ((editFeaturedImage.startsWith('http') || editFeaturedImage.startsWith('/')) ? editFeaturedImage : ('/' + editFeaturedImage)) : '/assets/images/brand/rabez-emblem.jpg';
          preview.src = src;
        }

        const strip = document.getElementById("edit-prod-gallery-strip");
        if (!strip) return;

        if (editGalleryImages.length === 0) {
          strip.innerHTML = `<div style="color:var(--adm-muted); font-size:11.5px; padding:10px; width:100%; text-align:center;">
            No gallery images. The 1st image added here becomes the <strong>Back View</strong>.
          </div>`;
          return;
        }

        strip.innerHTML = editGalleryImages.map((img, idx) => {
          const src = img.startsWith('http') || img.startsWith('/') ? img : ('/' + img);
          const isBack = (idx === 0);
          return `
            <div class="adm-gallery-strip-item ${isBack ? 'is-back-view' : ''}" title="${isBack ? 'Back View (Card Hover)' : `Angle #${idx+1}`}">
              <img src="${src}" alt="Gallery ${idx+1}">
              <span style="position:absolute; top:3px; left:3px; z-index:2; font-size:8px; font-weight:800; padding:1px 4px; border-radius:3px; background:${isBack ? '#22C55E' : '#111513'}; color:${isBack ? '#000' : '#fff'};">
                ${isBack ? 'BACK' : `#${idx+1}`}
              </span>
              <div class="adm-strip-actions">
                <button type="button" class="adm-strip-btn" onclick="makeFrontInEdit(${idx})" title="Set as Front View">Make Front</button>
                <button type="button" class="adm-strip-btn adm-strip-btn--danger" onclick="removeGalleryInEdit(${idx})" title="Remove">Remove</button>
              </div>
            </div>`;
        }).join('');
      }

      window.chooseFeaturedForEdit = function() {
        openMediaPicker({
          mode: 'single',
          title: 'Select Featured Image (Front View)',
          selected: editFeaturedImage ? [editFeaturedImage] : [],
          onSelect: (url) => {
            if (url) {
              editFeaturedImage = url;
              renderEditImages();
            }
          }
        });
      };

      window.chooseGalleryForEdit = function() {
        openMediaPicker({
          mode: 'multiple',
          title: 'Select Gallery Images (Back View & Angles)',
          selected: editGalleryImages,
          onSelect: (urls) => {
            if (urls && urls.length) {
              urls.forEach(u => {
                if (!editGalleryImages.includes(u)) editGalleryImages.push(u);
              });
              renderEditImages();
            }
          }
        });
      };

      window.makeFrontInEdit = function(idx) {
        if (idx < 0 || idx >= editGalleryImages.length) return;
        const oldFront = editFeaturedImage;
        editFeaturedImage = editGalleryImages[idx];
        editGalleryImages[idx] = oldFront;
        renderEditImages();
        toast("Front View Swapped", "Image promoted to Featured Front View");
      };

      window.removeGalleryInEdit = function(idx) {
        if (idx < 0 || idx >= editGalleryImages.length) return;
        editGalleryImages.splice(idx, 1);
        renderEditImages();
      };

      /* ---------- Product Edit & Delete Handlers ---------- */
      function openProductDrawer(id) {
        const p = products.find((x) => x.id == id);
        if (!p) return;

        $("#edit-prod-id").value = p.id;
        $("#edit-prod-name").value = p.name;
        $("#edit-prod-sku").value = p.sku || "";
        $("#edit-prod-price").value = p.price;
        $("#edit-prod-reg-price").value = p.regularPrice || p.price;
        $("#edit-prod-cat").value = p.sleeve || "half-sleeve";
        $("#edit-prod-club").value = p.club || "all";
        $("#edit-prod-stock").value = p.isReadyStock ? "1" : "0";
        if ($("#edit-prod-custom-print")) {
          $("#edit-prod-custom-print").checked = !!p.hasCustomPrint;
        }
        $("#edit-prod-desc").value = p.shortDesc || p.name;
        $("#edit-prod-long-desc").value = p.description || "";

        const viewBtn = $("#edit-prod-view-btn");
        if (viewBtn) {
          viewBtn.href = `/product/${p.slug || p.id}`;
        }

        // Populate Images
        editFeaturedImage = p.image || "";
        editGalleryImages = Array.isArray(p.gallery) ? [...p.gallery] : [];
        renderEditImages();

        $(".js-p-title").textContent = `Edit #${p.sku || p.id}`;
        document.body.classList.add("is-prod-drawer-open");
      }

      async function saveProductEdit(e) {
        e.preventDefault();
        const id = $("#edit-prod-id").value;
        const p = products.find((x) => x.id == id);
        if (!p) return;

        const payload = {
          name: $("#edit-prod-name").value.trim(),
          sku: $("#edit-prod-sku").value.trim(),
          price: Number($("#edit-prod-price").value),
          regular_price: Number($("#edit-prod-reg-price").value),
          sleeve: $("#edit-prod-cat").value,
          club: $("#edit-prod-club").value,
          is_ready_stock: $("#edit-prod-stock").value === "1" ? 1 : 0,
          has_custom_print: $("#edit-prod-custom-print")?.checked ? 1 : 0,
          short_desc: $("#edit-prod-desc").value.trim(),
          description: $("#edit-prod-long-desc").value.trim(),
          image: editFeaturedImage,
          gallery: editGalleryImages,
          _token: CSRF_TOKEN
        };

        try {
          const res = await fetch(`/admin/products/${id}/update`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.success) {
            p.name = payload.name;
            p.sku = payload.sku;
            p.price = payload.price;
            p.regularPrice = payload.regular_price;
            p.sleeve = payload.sleeve;
            p.club = payload.club;
            p.isReadyStock = !!payload.is_ready_stock;
            p.hasCustomPrint = !!payload.has_custom_print;
            p.shortDesc = payload.short_desc;
            p.description = payload.description;
            if (data.product && data.product.slug) p.slug = data.product.slug;
            p.image = payload.image;
            p.gallery = payload.gallery;
            const labels = { "half-sleeve": "Half Sleeve", "full-sleeve": "Full Sleeve", "winter-hoodie": "Winter Hoodie", "retro": "Retro", "footwear": "Boots", "tracksuit": "Tracksuit" };
            p.sleeveLabel = labels[p.sleeve] || p.sleeve;

            closeOverlays();
            renderProducts();
            toast("Product Updated", p.name);
          } else {
            alert("Failed to update product: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Update failed", err.message);
        }
      }

      async function deleteProduct(id) {
        const p = products.find((x) => x.id == id);
        if (!p) return;

        if (!confirm(`Are you sure you want to delete '${p.name}'? This will remove it from the store permanently.`)) {
          return;
        }

        try {
          const res = await fetch(`/admin/products/${id}`, {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({ _token: CSRF_TOKEN })
          });
          const data = await res.json();
          if (data.success) {
            products = products.filter((x) => x.id != id);
            closeOverlays();
            renderProducts();
            toast("Product Deleted", p.name);
          } else {
            alert("Failed to delete product: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Delete failed", err.message);
        }
      }

      async function syncProductsFromDB() {
        try {
          const res = await fetch("/admin/api/products");
          const data = await res.json();
          if (data.success && Array.isArray(data.products)) {
            products = data.products.map(mapProductFromDB);
            renderProducts();
            toast("Products Synced", `${products.length} products loaded`);
          }
        } catch (err) {
          console.error(err);
        }
      }

      /* ---------- Categories Management ---------- */
      function categoryTableRowHTML(c) {
        const isChecked = selectedMap.categories.has(String(c.id));
        return `
          <tr data-id="${c.id}">
            <td style="width:36px;">
              <input type="checkbox" class="adm-cb js-row-cb" data-type="categories" data-id="${c.id}" ${isChecked ? 'checked' : ''}>
            </td>
            <td data-col="name"><strong class="adm-cell-main">${c.name}</strong></td>
            <td data-col="slug"><code style="font-size:12px; background:#F4F6F5; padding:2px 6px; border-radius:4px;">${c.slug}</code></td>
            <td data-col="type"><span class="adm-chip adm-chip--neutral">${c.product_type || 'jersey'}</span></td>
            <td data-col="sort" class="num">${c.sort_order ?? 0}</td>
            <td data-col="count"><span class="adm-chip adm-chip--delivered">${c.products_count ?? 0} products</span></td>
            <td data-col="status">
              ${c.is_active ? `<span class="adm-chip adm-chip--delivered">Active</span>` : `<span class="adm-chip adm-chip--danger">Inactive</span>`}
            </td>
            <td data-col="actions" style="text-align:right;">
              <div style="display:inline-flex; gap:6px; justify-content:flex-end; align-items:center;">
                <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-edit-cat" data-id="${c.id}" style="padding:4px 8px; font-size:12px;">Edit</button>
                <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-delete-cat" data-id="${c.id}" style="padding:4px 8px; font-size:12px; background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Del</button>
              </div>
            </td>
          </tr>`;
      }

      function renderCategories() {
        let list = categories.slice();
        if (catQuery) {
          const q = catQuery.toLowerCase();
          list = list.filter((c) => c.name.toLowerCase().includes(q) || c.slug.toLowerCase().includes(q));
        }

        const root = $("#adm-categories");
        if (root) {
          if (!list.length) {
            root.innerHTML = `<tr><td colspan="8" class="adm-empty" style="text-align:center; padding:32px;">No categories found.</td></tr>`;
          } else {
            root.innerHTML = list.map(categoryTableRowHTML).join("");
          }
        }
        updateBulkBar('categories');
      }

      function openCatDrawer(id) {
        if (id) {
          const c = categories.find((x) => x.id == id);
          if (!c) return;
          $("#edit-cat-id").value = c.id;
          $("#edit-cat-name").value = c.name;
          $("#edit-cat-slug").value = c.slug;
          $("#edit-cat-type").value = c.product_type || "jersey";
          $("#edit-cat-sort").value = c.sort_order ?? 0;
          $("#edit-cat-status").value = c.is_active ? "1" : "0";
          $(".js-c-title").textContent = `Edit Category: ${c.name}`;
          $(".js-delete-cat-btn").style.display = "inline-flex";
        } else {
          $("#edit-cat-id").value = "";
          $("#edit-cat-name").value = "";
          $("#edit-cat-slug").value = "";
          $("#edit-cat-type").value = "jersey";
          $("#edit-cat-sort").value = "0";
          $("#edit-cat-status").value = "1";
          $(".js-c-title").textContent = "New Category";
          $(".js-delete-cat-btn").style.display = "none";
        }
        document.body.classList.add("is-cat-drawer-open");
      }

      async function saveCategory(e) {
        e.preventDefault();
        const id = $("#edit-cat-id").value;
        const payload = {
          name: $("#edit-cat-name").value.trim(),
          slug: $("#edit-cat-slug").value.trim(),
          product_type: $("#edit-cat-type").value,
          sort_order: Number($("#edit-cat-sort").value) || 0,
          is_active: $("#edit-cat-status").value === "1" ? 1 : 0,
          _token: CSRF_TOKEN
        };

        const url = id ? `/admin/categories/${id}/update` : `/admin/categories`;
        try {
          const res = await fetch(url, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.success) {
            if (id) {
              const c = categories.find((x) => x.id == id);
              if (c) Object.assign(c, data.category);
              toast("Category Updated", payload.name);
            } else {
              categories.unshift(data.category);
              toast("Category Created", payload.name);
            }
            closeOverlays();
            renderCategories();
          } else {
            alert("Category save failed: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Category error", err.message);
        }
      }

      async function deleteCategory(id) {
        const c = categories.find((x) => x.id == id);
        if (!c) return;
        if (!confirm(`Are you sure you want to delete category '${c.name}'?`)) return;

        try {
          const res = await fetch(`/admin/categories/${id}`, {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({ _token: CSRF_TOKEN })
          });
          const data = await res.json();
          if (data.success) {
            categories = categories.filter((x) => x.id != id);
            closeOverlays();
            renderCategories();
            toast("Category Deleted", c.name);
          } else {
            alert("Failed to delete category: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Delete error", err.message);
        }
      }

      function customerTableRowHTML(cust) {
        const isChecked = selectedMap.customers.has(String(cust.phone));
        return `
          <tr data-phone="${cust.phone}">
            <td style="width:36px;">
              <input type="checkbox" class="adm-cb js-row-cb" data-type="customers" data-id="${cust.phone}" ${isChecked ? 'checked' : ''}>
            </td>
            <td data-col="name"><div class="adm-cell-main">${cust.name}</div></td>
            <td data-col="phone"><span class="num">${cust.phone}</span></td>
            <td data-col="location"><div class="adm-cell-sub">${cust.address || 'Dhaka'}</div></td>
            <td data-col="orders" class="num">${cust.total_orders ?? cust.orders_count ?? 1}</td>
            <td data-col="spent" class="num">${money(cust.total_spent ?? 0)}</td>
            <td data-col="ratio">
              <span class="adm-risk ${cust.delivery_ratio < 60 ? 'adm-risk--warn' : ''}">
                <span class="adm-risk__bar"><span style="width:${cust.delivery_ratio}%"></span></span>${cust.delivery_ratio}%
              </span>
            </td>
            <td data-col="status">
              ${cust.delivery_ratio >= 60 ? `<span class="adm-chip adm-chip--delivered">Safe COD</span>` : `<span class="adm-chip adm-chip--danger">High Risk</span>`}
            </td>
            <td data-col="actions" style="text-align:right;">
              <div style="display:inline-flex; gap:6px; justify-content:flex-end; align-items:center;">
                <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-edit-cust" data-phone="${cust.phone}" style="padding:4px 8px; font-size:12px;">Edit</button>
                <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-delete-cust" data-phone="${cust.phone}" style="padding:4px 8px; font-size:12px; background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Del</button>
              </div>
            </td>
          </tr>`;
      }

      function renderCustomers() {
        let list = customers.slice();
        if (custQuery) {
          const q = custQuery.toLowerCase();
          list = list.filter((c) => c.name.toLowerCase().includes(q) || c.phone.includes(q) || (c.address || "").toLowerCase().includes(q));
        }

        const root = $("#adm-customers");
        if (root) {
          if (!list.length) {
            root.innerHTML = `<tr><td colspan="9" class="adm-empty" style="text-align:center; padding:32px;">No customers found.</td></tr>`;
          } else {
            root.innerHTML = list.map(customerTableRowHTML).join("");
          }
        }
        updateBulkBar('customers');
      }

      function openCustDrawer(phone) {
        const cust = customers.find((x) => x.phone === phone);
        if (!cust) return;
        $("#edit-cust-orig-phone").value = cust.phone;
        $("#edit-cust-name").value = cust.name;
        $("#edit-cust-phone").value = cust.phone;
        $("#edit-cust-address").value = cust.address || "";
        $(".js-cust-title").textContent = `Edit Customer: ${cust.name}`;
        $(".js-cust-risk-callout").innerHTML = `
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <strong>${cust.total_orders ?? cust.orders_count ?? 1} orders &middot; ${money(cust.total_spent ?? 0)} spent</strong>
            <span class="adm-risk ${cust.delivery_ratio < 60 ? 'adm-risk--warn' : ''}">
              <span class="adm-risk__bar"><span style="width:${cust.delivery_ratio}%"></span></span>${cust.delivery_ratio}% Delivery Rate
            </span>
          </div>`;
        document.body.classList.add("is-cust-drawer-open");
      }

      async function saveCustomer(e) {
        e.preventDefault();
        const origPhone = $("#edit-cust-orig-phone").value;
        const payload = {
          original_phone: origPhone,
          customer_name: $("#edit-cust-name").value.trim(),
          customer_phone: $("#edit-cust-phone").value.trim(),
          customer_address: $("#edit-cust-address").value.trim(),
          _token: CSRF_TOKEN
        };

        try {
          const res = await fetch(`/admin/customers/update`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.success) {
            const cust = customers.find((x) => x.phone === origPhone);
            if (cust) {
              cust.name = payload.customer_name;
              cust.phone = payload.customer_phone;
              cust.address = payload.customer_address;
            }
            orders.forEach((o) => {
              if (o.phone === origPhone) {
                o.customer = payload.customer_name;
                o.phone = payload.customer_phone;
                o.address = payload.customer_address;
              }
            });
            closeOverlays();
            renderCustomers();
            renderOrders();
            toast("Customer Updated", payload.customer_name);
          } else {
            alert("Customer update failed: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Customer error", err.message);
        }
      }

      async function deleteCustomer(phone) {
        const cust = customers.find((x) => x.phone === phone);
        if (!cust) return;
        if (!confirm(`Are you sure you want to delete customer '${cust.name}' and all their orders?`)) return;

        try {
          const res = await fetch(`/admin/customers/delete`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({ phone: phone, _token: CSRF_TOKEN })
          });
          const data = await res.json();
          if (data.success) {
            customers = customers.filter((x) => x.phone !== phone);
            orders = orders.filter((x) => x.phone !== phone);
            closeOverlays();
            renderCustomers();
            renderOrders();
            toast("Customer Deleted", cust.name);
          } else {
            alert("Delete failed: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Delete error", err.message);
        }
      }

      /* ---------- Users & Team Management ---------- */
      function userRoleBadge(role) {
        role = (role || 'customer').toLowerCase();
        if (role === 'admin') {
          return '<span class="adm-chip" style="background:#14532D; color:#4ADE80; border:1px solid #16A34A; font-weight:700;">⚡ ADMIN</span>';
        } else if (role === 'manager') {
          return '<span class="adm-chip" style="background:#0C4A6E; color:#38BDF8; border:1px solid #0284C7; font-weight:700;">🛡️ MANAGER</span>';
        } else if (role === 'staff') {
          return '<span class="adm-chip" style="background:#312E81; color:#A5B4FC; border:1px solid #4F46E5; font-weight:700;">💼 STAFF</span>';
        } else {
          return '<span class="adm-chip" style="background:#1F2937; color:#9CA3AF; border:1px solid #374151; font-weight:600;">👤 CUSTOMER</span>';
        }
      }

      function userTableRowHTML(u) {
        const isChecked = selectedMap.users.has(String(u.id));
        const isSelf = Number(u.id) === Number(currentUserId);
        const initials = (u.name || "U").split(" ").map(w => w[0]).join("").substring(0, 2).toUpperCase();
        const role = (u.role || 'customer').toLowerCase();
        const status = (u.status || 'active').toLowerCase();

        return `
          <tr data-id="${u.id}">
            <td style="width:36px;">
              <input type="checkbox" class="adm-cb js-row-cb" data-type="users" data-id="${u.id}" ${isChecked ? 'checked' : ''} ${isSelf ? 'disabled title="Cannot select your own account"' : ''}>
            </td>
            <td data-col="user">
              <div style="display:flex; align-items:center; gap:10px;">
                <span class="adm-avatar" style="width:32px; height:32px; font-size:11px; font-weight:700; background:${role === 'admin' ? '#16A34A' : role === 'manager' ? '#0284C7' : role === 'staff' ? '#4F46E5' : '#374151'}; color:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;">
                  ${initials}
                </span>
                <div>
                  <div class="adm-cell-main" style="display:flex; align-items:center; gap:6px;">
                    <strong>${u.name}</strong>
                    ${isSelf ? '<span style="font-size:10px; padding:1px 5px; border-radius:4px; background:rgba(34,197,94,0.18); color:#22C55E; font-weight:700;">YOU</span>' : ''}
                  </div>
                </div>
              </div>
            </td>
            <td data-col="email"><span class="num">${u.email}</span></td>
            <td data-col="phone"><span class="num">${u.phone || '—'}</span></td>
            <td data-col="role">${userRoleBadge(u.role)}</td>
            <td data-col="status">
              ${status === 'active' 
                ? '<span style="display:inline-flex; align-items:center; gap:5px; font-size:12px; color:#22C55E;"><span style="width:7px; height:7px; border-radius:50%; background:#22C55E;"></span> Active</span>'
                : '<span style="display:inline-flex; align-items:center; gap:5px; font-size:12px; color:#EF4444;"><span style="width:7px; height:7px; border-radius:50%; background:#EF4444;"></span> Inactive</span>'}
            </td>
            <td data-col="created"><div class="adm-cell-sub" title="${u.created_at}">${u.time_ago || (u.created_at ? new Date(u.created_at).toLocaleDateString() : '—')}</div></td>
            <td data-col="actions" style="text-align:right;">
              <div style="display:inline-flex; gap:6px; justify-content:flex-end; align-items:center;">
                <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-edit-user" data-id="${u.id}" style="padding:4px 8px; font-size:12px;">Edit</button>
                ${isSelf ? '' : `<button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-delete-user" data-id="${u.id}" style="padding:4px 8px; font-size:12px; background:#fee2e2; color:#b91c1c; border-color:#fca5a5;">Del</button>`}
              </div>
            </td>
          </tr>
        `;
      }

      function renderUsers() {
        let list = users.slice();
        if (userFilter !== "all") {
          if (userFilter === "admin") {
            list = list.filter((u) => (u.role || "").toLowerCase() === "admin");
          } else if (userFilter === "staff") {
            list = list.filter((u) => ["manager", "staff"].includes((u.role || "").toLowerCase()));
          } else if (userFilter === "customer") {
            list = list.filter((u) => (u.role || "customer").toLowerCase() === "customer");
          }
        }

        if (userQuery) {
          const q = userQuery.toLowerCase();
          list = list.filter((u) =>
            (u.name || "").toLowerCase().includes(q) ||
            (u.email || "").toLowerCase().includes(q) ||
            (u.phone || "").includes(q) ||
            (u.role || "").toLowerCase().includes(q)
          );
        }

        const root = $("#adm-users");
        if (root) {
          if (!list.length) {
            root.innerHTML = `<tr><td colspan="8" class="adm-empty" style="text-align:center; padding:32px;">No users found matching your filters.</td></tr>`;
          } else {
            root.innerHTML = list.map(userTableRowHTML).join("");
          }
        }

        // Update stats
        const total = users.length;
        const adminCount = users.filter(u => (u.role || '').toLowerCase() === 'admin').length;
        const staffCount = users.filter(u => ['manager', 'staff'].includes((u.role || '').toLowerCase())).length;
        const customerCount = users.filter(u => (u.role || 'customer').toLowerCase() === 'customer').length;

        if ($(".js-stat-users-total")) $(".js-stat-users-total").textContent = total;
        if ($(".js-stat-users-admin")) $(".js-stat-users-admin").textContent = adminCount;
        if ($(".js-stat-users-staff")) $(".js-stat-users-staff").textContent = staffCount;
        if ($(".js-stat-users-customer")) $(".js-stat-users-customer").textContent = customerCount;
        if ($(".js-users-count-badge")) $(".js-users-count-badge").textContent = total;

        updateBulkBar('users');
      }

      function openUserDrawer(id) {
        const drawer = $(".js-user-drawer");
        if (!drawer) return;
        const form = $("#js-user-form");
        if (form) form.reset();

        if (id) {
          const u = users.find((x) => String(x.id) === String(id));
          if (!u) return;
          $("#edit-user-id").value = u.id;
          $("#edit-user-name").value = u.name;
          $("#edit-user-email").value = u.email;
          $("#edit-user-phone").value = u.phone || "";
          $("#edit-user-role").value = (u.role || "customer").toLowerCase();
          $("#edit-user-status").value = (u.status || "active").toLowerCase();
          $("#edit-user-password").value = "";
          $("#edit-user-password").required = false;
          $(".js-user-drawer-title").textContent = `Edit User: ${u.name}`;
          $(".js-save-user-submit-btn").textContent = "Update User";
          $(".js-pwd-hint").textContent = "(Leave blank to keep current password)";
          const isSelf = Number(u.id) === Number(currentUserId);
          const delBtn = $(".js-delete-user-modal-btn");
          if (delBtn) {
            delBtn.style.display = isSelf ? "none" : "inline-block";
          }
        } else {
          $("#edit-user-id").value = "";
          $("#edit-user-role").value = "staff";
          $("#edit-user-status").value = "active";
          $("#edit-user-password").required = true;
          $(".js-user-drawer-title").textContent = "Add New Team / Admin User";
          $(".js-save-user-submit-btn").textContent = "Create User";
          $(".js-pwd-hint").textContent = "(Min 8 characters, required)";
          const delBtn = $(".js-delete-user-modal-btn");
          if (delBtn) delBtn.style.display = "none";
        }

        document.body.classList.add("is-user-drawer-open");
      }

      async function saveUser(e) {
        e.preventDefault();
        const id = $("#edit-user-id").value;
        const name = $("#edit-user-name").value.trim();
        const email = $("#edit-user-email").value.trim();
        const phone = $("#edit-user-phone").value.trim();
        const role = $("#edit-user-role").value;
        const status = $("#edit-user-status").value;
        const password = $("#edit-user-password").value;

        if (!id && (!password || password.length < 8)) {
          alert("Password is required and must be at least 8 characters long.");
          return;
        }

        const payload = {
          name,
          email,
          phone,
          role,
          status,
          _token: CSRF_TOKEN
        };
        if (password) payload.password = password;

        const url = id ? `/admin/users/${id}/update` : `/admin/users`;

        try {
          const res = await fetch(url, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.success) {
            if (id) {
              const u = users.find((x) => String(x.id) === String(id));
              if (u) {
                u.name = name;
                u.email = email;
                u.phone = phone;
                u.role = role;
                u.status = status;
              }
              toast("User Updated", name);
            } else {
              const newUser = data.user || {
                id: Date.now(),
                name,
                email,
                phone,
                role,
                status,
                created_at: new Date().toISOString(),
                time_ago: "Just now"
              };
              users.unshift(newUser);
              toast("User Created", `${name} (${role.toUpperCase()})`);
            }
            closeOverlays();
            renderUsers();
          } else {
            alert("Save failed: " + (data.message || (data.errors ? Object.values(data.errors).flat().join("\n") : "Validation error")));
          }
        } catch (err) {
          console.error(err);
          toast("User error", err.message);
        }
      }

      async function deleteUser(id) {
        if (Number(id) === Number(currentUserId)) {
          alert("You cannot delete your own logged-in account.");
          return;
        }
        const u = users.find((x) => String(x.id) === String(id));
        if (!u) return;
        if (!confirm(`Are you sure you want to delete user '${u.name}' (${u.email})? This action cannot be undone.`)) return;

        try {
          const res = await fetch(`/admin/users/${id}`, {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            }
          });
          const data = await res.json();
          if (data.success) {
            users = users.filter((x) => String(x.id) !== String(id));
            closeOverlays();
            renderUsers();
            toast("User Deleted", u.name);
          } else {
            alert("Delete failed: " + (data.message || "Unknown error"));
          }
        } catch (err) {
          console.error(err);
          toast("Delete error", err.message);
        }
      }

      /* ---------- Clipboard Helper ---------- */
      function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(text).catch(() => fallbackCopy(text));
        } else {
          fallbackCopy(text);
        }
      }
      function fallbackCopy(text) {
        const ta = document.createElement("textarea");
        ta.value = text;
        ta.style.position = "fixed";
        ta.style.opacity = "0";
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand("copy"); } catch (e) {}
        document.body.removeChild(ta);
      }

      /* ---------- Coupons Management & Statistics Engine ---------- */
      let couponSearchQuery = "";

      function updateCouponStats() {
        const total = couponsList.length;
        const activeList = couponsList.filter(c => c.is_active);
        const activeCount = activeList.length;
        const inactiveCount = total - activeCount;

        // Header and Sidebar Badges
        $$(".js-coupons-count").forEach(el => el.textContent = activeCount);
        $$(".js-coupons-count-badge").forEach(el => el.textContent = total);

        // Stats Card 1: Active
        const statActive = document.querySelector(".js-stat-coupons-active");
        if (statActive) statActive.textContent = activeCount;

        // Stats Card 2: Total
        const statTotal = document.querySelector(".js-stat-coupons-total");
        if (statTotal) statTotal.textContent = total;
        const statInactiveSub = document.querySelector(".js-stat-coupons-inactive-sub");
        if (statInactiveSub) {
          statInactiveSub.textContent = inactiveCount > 0 ? `${inactiveCount} inactive / expired` : "All coupons live & active";
        }

        // Stats Card 3: Discount Type Mix
        const percentCount = couponsList.filter(c => c.discount_type === 'percent').length;
        const flatCount = couponsList.filter(c => c.discount_type === 'flat').length;
        const statMix = document.querySelector(".js-stat-coupons-mix");
        const statMixSub = document.querySelector(".js-stat-coupons-mix-sub");
        if (statMix) {
          if (percentCount > 0 && flatCount > 0) {
            statMix.textContent = `${percentCount} % / ${flatCount} ৳`;
            if (statMixSub) statMixSub.textContent = "Mixed promotional mix";
          } else if (percentCount > 0) {
            statMix.textContent = `${percentCount} Percentage`;
            if (statMixSub) statMixSub.textContent = "100% percentage discounts";
          } else if (flatCount > 0) {
            statMix.textContent = `${flatCount} Flat ৳`;
            if (statMixSub) statMixSub.textContent = "100% flat cash discounts";
          } else {
            statMix.textContent = "None";
            if (statMixSub) statMixSub.textContent = "No campaigns created yet";
          }
        }

        // Stats Card 4: Min Order Spend
        const validMinSpends = couponsList.map(c => Number(c.min_spend) || 0).filter(v => v > 0);
        const avgMinSpend = validMinSpends.length ? Math.round(validMinSpends.reduce((a,b) => a+b, 0) / validMinSpends.length) : 0;
        const statMinSpend = document.querySelector(".js-stat-coupons-min-spend");
        if (statMinSpend) {
          statMinSpend.textContent = avgMinSpend > 0 ? `৳${avgMinSpend.toLocaleString()}` : "৳0 (No Min)";
        }
      }

      function renderCoupons() {
        const container = document.getElementById("adm-coupons-list");
        if (!container) return;
        updateCouponStats();

        // Search Filter
        const q = (couponSearchQuery || "").toLowerCase().trim();
        const filtered = couponsList.filter(c => {
          if (!q) return true;
          return (c.code || "").toLowerCase().includes(q) || (c.description || "").toLowerCase().includes(q);
        });

        if (filtered.length === 0) {
          container.innerHTML = `
            <tr>
              <td colspan="6" style="padding:36px 20px; text-align:center; color:var(--adm-muted); font-size:13px;">
                ${couponsList.length === 0
                  ? 'No discount coupons found. Click "New Coupon" above to create your first coupon.'
                  : `No coupons matched "${escapeHtml(q)}". <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm" onclick="document.querySelector('.js-coupon-search-input').value=''; couponSearchQuery=''; renderCoupons();" style="margin-left:8px;">Clear search</button>`}
              </td>
            </tr>`;
          return;
        }

        container.innerHTML = filtered.map(c => {
          const discountLabel = c.discount_type === 'percent' ? `${c.discount_value}% OFF` : `৳${Number(c.discount_value).toLocaleString()} OFF`;
          const minSpendLabel = Number(c.min_spend) > 0 ? `৳${Number(c.min_spend).toLocaleString()}` : '<span style="color:#6B7280;">No min.</span>';
          const activeBadge = c.is_active
            ? `<span style="font-size:11px; padding:3px 9px; border-radius:999px; background:rgba(34,197,94,0.15); color:#22c55e; font-weight:700; border:1px solid rgba(34,197,94,0.3); display:inline-flex; align-items:center; gap:5px;"><span style="width:5px; height:5px; background:#22c55e; border-radius:50%;"></span>ACTIVE</span>`
            : `<span style="font-size:11px; padding:3px 9px; border-radius:999px; background:rgba(239,68,68,0.15); color:#ef4444; font-weight:700; border:1px solid rgba(239,68,68,0.3); display:inline-flex; align-items:center; gap:5px;"><span style="width:5px; height:5px; background:#ef4444; border-radius:50%;"></span>INACTIVE</span>`;

          return `
            <tr>
              <td>
                <div style="display:inline-flex; align-items:center; gap:8px;">
                  <span style="background:#080C0A; border:1.5px dashed #04F404; border-radius:6px; padding:5px 11px; font-family:monospace; font-size:13.5px; font-weight:800; color:#04F404; letter-spacing:0.06em;">
                    ${c.code}
                  </span>
                  <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-copy-coupon" data-code="${c.code}" style="padding:4px 8px; font-size:11px; display:inline-flex; align-items:center; gap:4px; border-color:rgba(255,255,255,0.15);" title="Copy Code">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span>Copy</span>
                  </button>
                </div>
              </td>
              <td>
                <span style="font-size:12.5px; font-weight:800; color:#FFFFFF; background:#1C2420; padding:4px 10px; border-radius:6px; border:1px solid #2B3830; display:inline-block;">
                  ${discountLabel}
                </span>
              </td>
              <td>
                <span style="font-size:13px; font-weight:700; color:#04F404;">
                  ${minSpendLabel}
                </span>
              </td>
              <td>
                <div style="font-size:13px; color:#D1D5DB; max-width:280px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="${c.description || ''}">
                  ${c.description || '<span style="color:#6B7280;">Special promotion</span>'}
                </div>
              </td>
              <td>
                ${activeBadge}
              </td>
              <td style="text-align:right;">
                <div style="display:inline-flex; align-items:center; gap:6px;">
                  <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-edit-coupon" data-id="${c.id}" style="padding:4px 10px; font-size:11.5px; display:inline-flex; align-items:center; gap:4px; font-weight:600;" title="Edit Coupon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span>Edit</span>
                  </button>
                  <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-toggle-coupon" data-id="${c.id}" style="padding:4px 9px; font-size:11.5px;">
                    ${c.is_active ? 'Deactivate' : 'Activate'}
                  </button>
                  <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-delete-coupon" data-id="${c.id}" data-code="${c.code}" style="padding:4px 8px; font-size:11.5px;" title="Delete Coupon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          `;
        }).join("");
      }

      async function toggleCoupon(id) {
        try {
          const res = await fetch(`/admin/coupons/${id}/toggle`, {
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": CSRF_TOKEN,
              "Accept": "application/json"
            }
          });
          const data = await res.json();
          if (data.success && data.coupon) {
            const idx = couponsList.findIndex(c => String(c.id) === String(id));
            if (idx !== -1) couponsList[idx] = data.coupon;
            renderCoupons();
            toast("Coupon Updated", `${data.coupon.code} is now ${data.coupon.is_active ? 'Active' : 'Inactive'}`);
          }
        } catch (err) {
          console.error(err);
          toast("Coupon Error", err.message);
        }
      }

      async function deleteCoupon(id, code) {
        if (!confirm(`Are you sure you want to delete coupon ${code}?`)) return;
        try {
          const res = await fetch(`/admin/coupons/${id}`, {
            method: "DELETE",
            headers: {
              "X-CSRF-TOKEN": CSRF_TOKEN,
              "Accept": "application/json"
            }
          });
          const data = await res.json();
          if (data.success) {
            couponsList = couponsList.filter(c => String(c.id) !== String(id));
            renderCoupons();
            toast("Coupon Deleted", code);
          }
        } catch (err) {
          console.error(err);
          toast("Coupon Error", err.message);
        }
      }

      /* ---------- Add product sizes ---------- */
      function renderNewSizes(cat) {
        const list = cat === "footwear" ? ["39", "40", "41", "42", "43", "44"] : ["S", "M", "L", "XL", "XXL"];
        $("#adm-new-sizes").innerHTML = list.map((s) => `<button type="button" class="adm-size js-new-size" data-size="${s}">${s}</button>`).join("");
      }

      /* ---------- Event Listeners ---------- */
      document.addEventListener("click", (e) => {
        const t = e.target;

        // Tabs
        const tab = t.closest(".js-tab");
        if (tab) { showTab(tab.dataset.tab); return; }

        // Coupon Modal & Actions
        if (t.closest(".js-open-coupon-modal")) {
          const modal = document.querySelector(".js-coupon-modal");
          if (modal) {
            document.getElementById("js-coupon-form")?.reset();
            const idInput = document.getElementById("coupon-id");
            if (idInput) idInput.value = "";
            const titleEl = document.querySelector(".js-coupon-modal-title");
            if (titleEl) titleEl.textContent = "Create Discount Coupon";
            const submitBtn = document.querySelector(".js-coupon-submit-btn");
            if (submitBtn) submitBtn.textContent = "Create & Activate Coupon";
            modal.style.display = "flex";
            const codeInput = document.getElementById("coupon-code");
            if (codeInput) codeInput.focus();
          }
          return;
        }

        const editCouponBtn = t.closest(".js-edit-coupon");
        if (editCouponBtn) {
          e.stopPropagation();
          const couponId = editCouponBtn.dataset.id;
          const coupon = couponsList.find(c => String(c.id) === String(couponId));
          if (coupon) {
            const modal = document.querySelector(".js-coupon-modal");
            document.getElementById("coupon-id").value = coupon.id;
            document.getElementById("coupon-code").value = coupon.code;
            document.getElementById("coupon-type").value = coupon.discount_type;
            document.getElementById("coupon-val").value = coupon.discount_value;
            document.getElementById("coupon-min-spend").value = coupon.min_spend || 0;
            document.getElementById("coupon-desc").value = coupon.description || "";
            const titleEl = document.querySelector(".js-coupon-modal-title");
            if (titleEl) titleEl.textContent = `Edit Coupon: ${coupon.code}`;
            const submitBtn = document.querySelector(".js-coupon-submit-btn");
            if (submitBtn) submitBtn.textContent = "Save Changes";
            if (modal) modal.style.display = "flex";
          }
          return;
        }

        const copyCouponBtn = t.closest(".js-copy-coupon");
        if (copyCouponBtn) {
          e.stopPropagation();
          const code = copyCouponBtn.dataset.code;
          if (code) {
            copyToClipboard(code);
            const span = copyCouponBtn.querySelector("span");
            const originalText = span ? span.textContent : "Copy";
            if (span) span.textContent = "Copied!";
            copyCouponBtn.style.borderColor = "#04F404";
            copyCouponBtn.style.color = "#04F404";
            setTimeout(() => {
              if (span) span.textContent = originalText;
              copyCouponBtn.style.borderColor = "";
              copyCouponBtn.style.color = "";
            }, 1500);
            toast("Coupon Code Copied", code);
          }
          return;
        }

        if (t.closest(".js-close-coupon-modal")) {
          const modal = document.querySelector(".js-coupon-modal");
          if (modal) modal.style.display = "none";
          return;
        }
        const toggleCouponBtn = t.closest(".js-toggle-coupon");
        if (toggleCouponBtn) {
          e.stopPropagation();
          toggleCoupon(toggleCouponBtn.dataset.id);
          return;
        }
        const deleteCouponBtn = t.closest(".js-delete-coupon");
        if (deleteCouponBtn) {
          e.stopPropagation();
          deleteCoupon(deleteCouponBtn.dataset.id, deleteCouponBtn.dataset.code);
          return;
        }

        if (t.closest(".js-nav-toggle")) { document.body.classList.add("is-nav-open"); return; }
        if (t.closest(".js-backdrop") || t.closest(".js-drawer-close")) { closeOverlays(); return; }

        // Refresh Orders
        if (t.closest(".js-refresh-orders")) { syncOrdersFromDB(); return; }

        // Column Toggle Dropdown open/close
        const colMenuBtn = t.closest(".js-toggle-col-menu");
        if (colMenuBtn) {
          e.stopPropagation();
          const targetId = colMenuBtn.dataset.target;
          const dropdown = document.getElementById(targetId);
          if (dropdown) {
            const isOpen = dropdown.classList.contains("is-open");
            $$(".adm-col-dropdown").forEach(d => d.classList.remove("is-open"));
            if (!isOpen) dropdown.classList.add("is-open");
          }
          return;
        }

        // Close dropdown when clicking outside
        if (!t.closest(".adm-col-dropdown")) {
          $$(".adm-col-dropdown").forEach(d => d.classList.remove("is-open"));
        }

        // Bulk Delete Buttons
        if (t.closest(".js-bulk-delete-orders")) { handleBulkDelete('orders'); return; }
        if (t.closest(".js-bulk-delete-products")) { handleBulkDelete('products'); return; }
        if (t.closest(".js-bulk-delete-categories")) { handleBulkDelete('categories'); return; }
        if (t.closest(".js-bulk-delete-customers")) { handleBulkDelete('customers'); return; }
        if (t.closest(".js-bulk-delete-users")) { handleBulkDelete('users'); return; }

        // Clear Bulk Selection Buttons
        if (t.closest(".js-bulk-clear-orders")) { clearBulkSelection('orders'); return; }
        if (t.closest(".js-bulk-clear-products")) { clearBulkSelection('products'); return; }
        if (t.closest(".js-bulk-clear-categories")) { clearBulkSelection('categories'); return; }
        if (t.closest(".js-bulk-clear-customers")) { clearBulkSelection('customers'); return; }
        if (t.closest(".js-bulk-clear-users")) { clearBulkSelection('users'); return; }

        // Orders: Edit & Delete buttons
        const editOrder = t.closest(".js-edit-order");
        if (editOrder) { e.stopPropagation(); openFullOrderDrawer(editOrder.dataset.id); return; }

        const delOrderBtn = t.closest(".js-delete-order-btn");
        if (delOrderBtn) { deleteOrder(delOrderBtn.dataset.id); return; }

        // Products: Edit & Delete buttons
        
        const copyBtn = t.closest(".js-media-copy-url");
        if (copyBtn) {
          e.stopPropagation();
          const url = copyBtn.dataset.url;
          navigator.clipboard.writeText(url).then(() => {
            toast("URL Copied to Clipboard", url);
          }).catch(() => {
            prompt("Copy URL manually:", url);
          });
          return;
        }

        const delMediaBtn = t.closest(".js-media-del-btn");
        if (delMediaBtn) {
          e.stopPropagation();
          deleteMedia(delMediaBtn.dataset.id);
          return;
        }

        // Media Gallery thumbnail tap -> Open Photo Inspector Sheet
        const galleryItem = t.closest(".adm-gallery-item");
        if (galleryItem && !t.closest(".adm-gallery-item__cb") && !t.closest(".js-media-copy-url") && !t.closest(".js-media-del-btn")) {
          e.stopPropagation();
          openPhotoSheet({
            id: galleryItem.dataset.id,
            url: galleryItem.dataset.url,
            name: galleryItem.dataset.name,
            size: galleryItem.dataset.size,
            dim: galleryItem.dataset.dim,
            format: galleryItem.dataset.format
          });
          return;
        }

        if (t.id === "adm-photo-sheet") {
          closePhotoSheet();
          return;
        }

        const editProd = t.closest(".js-edit-product");
        if (editProd) { e.stopPropagation(); openProductDrawer(editProd.dataset.id); return; }

        const toggleCustomBtn = t.closest(".js-toggle-custom-print");
        if (toggleCustomBtn) {
          e.stopPropagation();
          const pid = toggleCustomBtn.dataset.id;
          const p = products.find((x) => String(x.id) === String(pid));
          if (!p) return;
          const nextState = p.hasCustomPrint ? 0 : 1;
          toggleCustomBtn.disabled = true;
          fetch(`/admin/products/${p.id}/update`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({ has_custom_print: nextState, _token: CSRF_TOKEN })
          })
            .then((r) => r.json())
            .then((data) => {
              if (data.success && data.product) {
                p.hasCustomPrint = !!data.product.has_custom_print;
                renderProducts();
                toast(p.hasCustomPrint ? "Custom Print Enabled" : "Custom Print Disabled", p.name);
              } else {
                alert("Failed to update custom print setting.");
                toggleCustomBtn.disabled = false;
              }
            })
            .catch((err) => {
              console.error(err);
              toast("Update error", err.message);
              toggleCustomBtn.disabled = false;
            });
          return;
        }

        const delProd = t.closest(".js-delete-product");
        if (delProd) { e.stopPropagation(); deleteProduct(delProd.dataset.id); return; }

        // Categories: New, Edit, Delete
        if (t.closest(".js-open-add-cat-btn")) { openCatDrawer(null); return; }

        const editCat = t.closest(".js-edit-cat");
        if (editCat) { e.stopPropagation(); openCatDrawer(editCat.dataset.id); return; }

        const delCat = t.closest(".js-delete-cat");
        if (delCat) { e.stopPropagation(); deleteCategory(delCat.dataset.id); return; }

        // Customers: Edit, Delete
        const editCust = t.closest(".js-edit-cust");
        if (editCust) { e.stopPropagation(); openCustDrawer(editCust.dataset.phone); return; }

        const delCust = t.closest(".js-delete-cust");
        if (delCust) { e.stopPropagation(); deleteCustomer(delCust.dataset.phone); return; }

        // Users: New, Edit, Delete, Filter
        if (t.closest(".js-open-add-user-btn")) { openUserDrawer(null); return; }

        const editUser = t.closest(".js-edit-user");
        if (editUser) { e.stopPropagation(); openUserDrawer(editUser.dataset.id); return; }

        const delUser = t.closest(".js-delete-user");
        if (delUser) { e.stopPropagation(); deleteUser(delUser.dataset.id); return; }

        const delUserModal = t.closest(".js-delete-user-modal-btn");
        if (delUserModal) {
          const uid = $("#edit-user-id").value;
          if (uid) deleteUser(uid);
          return;
        }

        const userFilterPill = t.closest(".js-user-filter");
        if (userFilterPill) {
          $$(".js-user-filter").forEach((p) => p.classList.remove("is-active"));
          userFilterPill.classList.add("is-active");
          userFilter = userFilterPill.dataset.role;
          renderUsers();
          return;
        }

        // Courier Book button
        const book = t.closest(".js-book");
        if (book) { e.stopPropagation(); bookOrder(book.dataset.id, book.dataset.courier); return; }

        if (t.closest(".js-stop")) { e.stopPropagation(); return; }

        // Order rows click opens full editor
        const row = t.closest(".js-order-row") || t.closest(".js-order-table-row");
        if (row && !t.closest(".adm-icon-btn") && !t.closest("input") && !t.closest("button") && !t.closest("a")) {
          openFullOrderDrawer(row.dataset.id);
          return;
        }

        // Add order item inside full editor
        if (t.closest(".js-add-order-item-btn")) {
          const listRoot = $("#full-order-items-list");
          if (listRoot) {
            const nextIdx = $$(".adm-order-edit-item").length;
            const newItem = { name: "Jersey", size: "L", qty: 1, price: 1150, print: "" };
            const div = document.createElement("div");
            div.innerHTML = renderOrderItemRow(newItem, nextIdx);
            listRoot.appendChild(div.firstElementChild);
            calcOrderTotals();
          }
          return;
        }

        // Remove order item inside full editor
        const removeOrderBtn = t.closest(".js-remove-order-item");
        if (removeOrderBtn) {
          const itemWrap = removeOrderBtn.closest(".adm-order-edit-item");
          if (itemWrap) {
            itemWrap.remove();
            calcOrderTotals();
          }
          return;
        }

        // Orders filter
        const filter = t.closest(".js-order-filter");
        if (filter) {
          $$(".js-order-filter").forEach((b) => b.classList.toggle("is-active", b === filter));
          orderFilter = filter.dataset.status;
          renderOrders();
          return;
        }

        // Bulk Book
        if (t.closest(".js-bulk-book")) {
          const ready = orders.filter((o) => o.status === "Pending" && o.ratio >= 60);
          if (!ready.length) { toast("Nothing to book", "No safe pending orders right now"); return; }
          ready.forEach((o) => {
            bookOrder(o.id, "Steadfast");
          });
          return;
        }

        // Product category filter
        const cat = t.closest(".js-cat-filter");
        if (cat) {
          $$(".js-cat-filter").forEach((b) => b.classList.toggle("is-active", b === cat));
          prodFilter = cat.dataset.cat;
          renderProducts();
          return;
        }

        // Size toggling on product row
        const size = t.closest(".js-size");
        if (size) {
          const p = products.find((x) => String(x.id) === size.dataset.id);
          if (!p) return;
          const s = size.dataset.size;
          if (p.sizes.includes(s)) {
            p.sizes = p.sizes.filter((x) => x !== s);
            toast(`Size ${s} marked sold out`, p.name);
          } else {
            p.sizes.push(s);
            toast(`Size ${s} back in stock`, p.name);
          }
          renderProducts();
          return;
        }

        const newSize = t.closest(".js-new-size");
        if (newSize) { newSize.classList.toggle("is-out"); return; }

        const printed = t.closest(".js-mark-printed");
        if (printed) {
          printed.outerHTML = `<span class="adm-chip adm-chip--delivered">Printed</span>`;
          const left = $$(".js-mark-printed").length;
          $(".js-print-chip").textContent = left ? `${left} waiting` : "All done";
          $(".js-print-count").textContent = left;
          toast("Marked as printed", "Ready for packing");
          return;
        }

        const tb = t.closest(".js-toast-btn");
        if (tb) { toast(tb.dataset.title, tb.dataset.sub); return; }
      });

      document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeOverlays(); });

      document.addEventListener("change", (e) => {
        // Master Select-All checkbox
        if (e.target.matches(".js-select-all")) {
          const type = e.target.dataset.type;
          const isChecked = e.target.checked;
          const visibleCbs = $$(`.js-row-cb[data-type="${type}"]`);
          visibleCbs.forEach(cb => {
            cb.checked = isChecked;
            if (isChecked) {
              selectedMap[type].add(String(cb.dataset.id));
            } else {
              selectedMap[type].delete(String(cb.dataset.id));
            }
          });
          updateBulkBar(type);
          return;
        }

        // Row Checkboxes
        if (e.target.matches(".js-row-cb")) {
          const type = e.target.dataset.type;
          const id = String(e.target.dataset.id);
          if (e.target.checked) {
            selectedMap[type].add(id);
          } else {
            selectedMap[type].delete(id);
          }
          updateBulkBar(type);
          return;
        }

        // Column toggle
        if (e.target.matches(".js-col-toggle")) {
          const { table, col } = e.target.dataset;
          const isVisible = e.target.checked;
          const tableEl = $(`#table-${table}`);
          if (tableEl) {
            tableEl.classList.toggle(`hide-col-${col}`, !isVisible);
            localStorage.setItem(`rabez_col_${table}_${col}`, isVisible ? '1' : '0');
          }
        }

        if (e.target.id === "adm-cat") {
          const cat = e.target.value;
          renderNewSizes(cat);
          const prices = { "half-sleeve": 1150, "full-sleeve": 1300, "winter-hoodie": 1450, "footwear": 2450, "tracksuit": 1850 };
          $("#adm-price").value = prices[cat] || 1150;
        }

        if (e.target.id === "full-edit-shipping" || e.target.matches(".js-item-qty") || e.target.matches(".js-item-price")) {
          calcOrderTotals();
        }

        if (e.target.id === "full-edit-zone") {
          const val = e.target.value;
          const ship = val.includes("130") ? 130 : 70;
          if ($("#full-edit-shipping")) $("#full-edit-shipping").value = ship;
          calcOrderTotals();
        }
      });

      document.addEventListener("input", (e) => {
        if (e.target.matches(".js-item-qty") || e.target.matches(".js-item-price") || e.target.id === "full-edit-shipping") {
          calcOrderTotals();
        }
      });

      // Search listeners
      $(".js-order-search")?.addEventListener("input", (e) => { orderQuery = e.target.value.trim(); renderOrders(); });
      $(".js-prod-search")?.addEventListener("input", (e) => { prodQuery = e.target.value.trim(); renderProducts(); });
      $(".js-cat-search")?.addEventListener("input", (e) => { catQuery = e.target.value.trim(); renderCategories(); });
      $(".js-cust-search")?.addEventListener("input", (e) => { custQuery = e.target.value.trim(); renderCustomers(); });
      $(".js-user-search")?.addEventListener("input", (e) => { userQuery = e.target.value.trim(); renderUsers(); });
      $("#pos-phone")?.addEventListener("input", (e) => { $(".js-pos-phone-label").textContent = e.target.value || "—"; });

      // Photo upload preview for add product
      $("#adm-photo")?.addEventListener("change", (e) => {
        const file = e.target.files && e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
          uploadedImage = ev.target.result;
          $("#adm-photo-preview").innerHTML = `
            <img src="${uploadedImage}" alt="" style="width:88px; height:104px; object-fit:cover; border-radius:8px; border:1px solid #E6E9E7; margin-bottom:8px;">
            <span class="adm-cell-main">${file.name}</span>
            <span class="adm-hint">Tap to replace</span>`;
        };
        reader.readAsDataURL(file);
      });

      // Delegate submit on Full Order Edit Form
      document.addEventListener("submit", (e) => {
        if (e.target.id === "js-full-order-form") {
          saveFullOrder(e);
        }
      });

      // Product Edit form submit
      const prodEditForm = $("#js-edit-product-form");
      if (prodEditForm) {
        prodEditForm.addEventListener("submit", saveProductEdit);
      }
      $(".js-delete-prod-btn")?.addEventListener("click", () => {
        const id = $("#edit-prod-id").value;
        if (id) deleteProduct(id);
      });

      // Category Edit form submit & delete
      const catEditForm = $("#js-edit-cat-form");
      if (catEditForm) {
        catEditForm.addEventListener("submit", saveCategory);
      }
      $(".js-delete-cat-btn")?.addEventListener("click", () => {
        const id = $("#edit-cat-id").value;
        if (id) deleteCategory(id);
      });

      // Customer Edit form submit & delete
      const custEditForm = $("#js-edit-cust-form");
      if (custEditForm) {
        custEditForm.addEventListener("submit", saveCustomer);
      }
      $(".js-delete-cust-btn")?.addEventListener("click", () => {
        const phone = $("#edit-cust-orig-phone").value;
        if (phone) deleteCustomer(phone);
      });

      // User Edit / Add form submit
      const userForm = $("#js-user-form");
      if (userForm) {
        userForm.addEventListener("submit", saveUser);
      }

      // Add product form submission to database
      $("#adm-add-form")?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const cat = $("#adm-cat").value;
        const clubSel = $("#adm-club");
        const price = Number($("#adm-price").value) || 1450;
        const regPrice = Number($("#adm-reg-price").value) || (price + 200);
        const name = $("#adm-name").value.trim();
        const sku = $("#adm-sku").value.trim() || ("RBZ-" + Math.floor(100 + Math.random() * 900));

        const payload = {
          name: name,
          sku: sku,
          club: clubSel.value,
          sleeve: cat,
          price: price,
          regular_price: regPrice,
          image: addFeaturedImage,
          gallery: addGalleryImages,
          short_desc: ($("#adm-short-desc")?.value.trim()) || name,
          description: ($("#adm-long-desc")?.value.trim()) || "",
          has_custom_print: $("#adm-custom-print")?.checked ? 1 : 0,
          _token: CSRF_TOKEN
        };

        try {
          const res = await fetch("/admin/products", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.success) {
            toast("Product published to database", data.product.name);
            e.target.reset();
            await syncProductsFromDB();
            showTab("products");
          } else {
            alert("Error creating product: " + (data.message || "Failed"));
          }
        } catch (err) {
          console.error(err);
          toast("Error adding product", err.message);
        }
      });

      // New POS order submission to database
      $("#adm-pos-form")?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const p = products.find((x) => String(x.id) === $("#pos-product").value) || products[0];
        const shipping = Number($("#pos-zone").value);
        const print = $("#pos-print").value.trim();
        const unit = p.price + (print ? 250 : 0);

        const payload = {
          customer_name: $("#pos-name").value.trim(),
          customer_phone: $("#pos-phone").value.trim(),
          customer_address: $("#pos-address").value.trim(),
          shipping_zone: shipping,
          cart_items: [{
            id: p.id,
            name: p.name,
            size: $("#pos-size").value,
            qty: 1,
            price: unit,
            custom_name: print,
            image: p.image
          }],
          _token: CSRF_TOKEN
        };

        try {
          const res = await fetch("/checkout", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.success) {
            toast(`Order #${data.order_id} created`, "Saved to database");
            e.target.reset();
            await syncOrdersFromDB();
            showTab("orders");
          } else {
            alert("Error creating order: " + (data.message || "Validation error"));
          }
        } catch (err) {
          console.error(err);
          toast("Error saving order", err.message);
        }
      });

      
      // Gallery Dropzone & File Input
      const galleryDropzone = document.getElementById("js-gallery-dropzone");
      const galleryFileInput = document.getElementById("js-gallery-file-input");

      if (galleryDropzone && galleryFileInput) {
        galleryDropzone.addEventListener("click", () => galleryFileInput.click());
        galleryDropzone.addEventListener("dragover", (e) => {
          e.preventDefault();
          galleryDropzone.style.borderColor = "#22C55E";
          galleryDropzone.style.background = "#141C16";
        });
        galleryDropzone.addEventListener("dragleave", () => {
          galleryDropzone.style.borderColor = "#2B332E";
          galleryDropzone.style.background = "#0F1311";
        });
        galleryDropzone.addEventListener("drop", (e) => {
          e.preventDefault();
          galleryDropzone.style.borderColor = "#2B332E";
          galleryDropzone.style.background = "#0F1311";
          if (e.dataTransfer && e.dataTransfer.files) {
            uploadMediaFiles(e.dataTransfer.files);
          }
        });
        galleryFileInput.addEventListener("change", (e) => {
          if (e.target.files) uploadMediaFiles(e.target.files);
        });
      }

      // Add Product: Upload New Front Photo
      document.getElementById("adm-add-featured-file")?.addEventListener("change", (e) => {
        if (e.target.files && e.target.files[0]) {
          uploadMediaFiles([e.target.files[0]], (media) => {
            if (media && media[0]) {
              addFeaturedImage = media[0].url;
              renderAddImages();
            }
          });
        }
      });

      // Add Product: Upload More Gallery Photos
      document.getElementById("adm-add-gallery-file")?.addEventListener("change", (e) => {
        if (e.target.files && e.target.files.length) {
          uploadMediaFiles(e.target.files, (newMedia) => {
            if (newMedia) {
              newMedia.forEach(m => {
                if (!addGalleryImages.includes(m.url)) addGalleryImages.push(m.url);
              });
              renderAddImages();
            }
          });
        }
      });

      // Edit Product: Upload New Front Photo
      document.getElementById("edit-prod-featured-file")?.addEventListener("change", (e) => {
        if (e.target.files && e.target.files[0]) {
          uploadMediaFiles([e.target.files[0]], (media) => {
            if (media && media[0]) {
              editFeaturedImage = media[0].url;
              renderEditImages();
            }
          });
        }
      });

      // Edit Product: Upload More Gallery Photos
      document.getElementById("edit-prod-gallery-file")?.addEventListener("change", (e) => {
        if (e.target.files && e.target.files.length) {
          uploadMediaFiles(e.target.files, (newMedia) => {
            if (newMedia) {
              newMedia.forEach(m => {
                if (!editGalleryImages.includes(m.url)) editGalleryImages.push(m.url);
              });
              renderEditImages();
            }
          });
        }
      });

      // Media Search & Refresh
      document.querySelector(".js-media-search")?.addEventListener("input", (e) => {
        mediaQuery = e.target.value.trim();
        renderMedia();
      });

      // Coupon Real-time Search
      document.querySelector(".js-coupon-search-input")?.addEventListener("input", (e) => {
        couponSearchQuery = e.target.value.trim();
        renderCoupons();
      });

      document.querySelector(".js-media-refresh-btn")?.addEventListener("click", async () => {
        try {
          const res = await fetch("/admin/api/media");
          const data = await res.json();
          if (data.success && data.media) {
            mediaList = data.media;
            renderMedia();
            toast("Media Synced", `${mediaList.length} total media items`);
          }
        } catch (err) {
          toast("Sync failed", err.message);
        }
      });

      // Safe for COD Phone Check with Live Delivery Risk Analysis
      $(".js-check-form")?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const phone = $(".js-check-phone").value.trim();
        if (!phone) return;
        const resRoot = $(".js-check-result");
        resRoot.innerHTML = `<div class="adm-cell-sub">Checking delivery history with courier APIs & database...</div>`;

        try {
          const res = await fetch("/admin/api/check-phone", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({ phone: phone, _token: CSRF_TOKEN })
          });
          const data = await res.json();
          if (data.success) {
            const warn = data.warn;
            const ratio = data.ratio;
            const parcels = data.parcels;
            const rec = data.recommendation;
            resRoot.innerHTML = `
              <div class="adm-callout ${warn ? 'adm-callout--warn' : ''}" style="margin-top:10px; border-left: 4px solid ${warn ? '#dc2626' : '#16a34a'};">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                  <div style="display:flex; align-items:center; gap:8px;">
                    <strong class="num" style="font-size:15px;">${data.phone}</strong>
                    <span class="adm-chip ${warn ? 'adm-chip--danger' : 'adm-chip--delivered'}">
                      ${warn ? '⚠ HIGH RETURN RISK' : '✓ SAFE FOR COD'}
                    </span>
                  </div>
                  <span class="adm-risk ${warn ? 'adm-risk--warn' : ''}">
                    <span class="adm-risk__bar"><span style="width:${ratio}%"></span></span>${ratio}%
                  </span>
                </div>
                <div class="adm-cell-sub" style="margin-bottom:6px;">
                  Customer: <strong>${data.customer}</strong> &middot; ${data.location}
                </div>
                <div class="adm-cell-sub" style="margin-bottom:8px;">
                  Delivery Record: <strong>${parcels} parcels delivered</strong>${data.orders_count ? ` (${data.delivered_count} delivered, ${data.cancelled_count} cancelled in store)` : ''}
                </div>
                <div style="font-size:13px; font-weight:600; padding:8px 10px; background:#fff; border-radius:6px; border:1px solid #E6E9E7; color:${warn ? '#991B1B' : '#166534'};">
                  💡 Recommendation: ${rec}
                </div>
              </div>`;
          } else {
            resRoot.innerHTML = `<div class="adm-callout adm-callout--warn">Could not verify phone number: ${data.message || 'Error'}</div>`;
          }
        } catch (err) {
          console.error(err);
          resRoot.innerHTML = `<div class="adm-callout adm-callout--warn">Verification error: ${err.message}</div>`;
        }
      });

      // Delivery mode toggle
      document.getElementById("setting-shipping-mode")?.addEventListener("change", (e) => {
        const isFlat = e.target.value === "flat";
        const flatField = document.querySelector(".js-shipping-flat-field");
        const zoneFields = document.querySelector(".js-shipping-zone-fields");
        if (flatField) flatField.style.display = isFlat ? "block" : "none";
        if (zoneFields) zoneFields.style.display = isFlat ? "none" : "grid";
      });

      // Settings form submit
      $(".js-settings-form")?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append("_token", CSRF_TOKEN);
        try {
          await fetch("/admin/settings", {
            method: "POST",
            body: formData
          });
          toast("Settings saved", "Changes are live on the storefront");
        } catch (err) {
          toast("Saved locally", "Updated");
        }
      });

      // Pixel settings
      const pixelInput = $(".js-pixel-id-input");
      if (pixelInput) {
        pixelInput.value = localStorage.getItem("rabez_pixel_id") || "";
      }
      $(".js-pixel-settings-form")?.addEventListener("submit", (e) => {
        e.preventDefault();
        const val = (pixelInput ? pixelInput.value : "").trim();
        localStorage.setItem("rabez_pixel_id", val);
        toast("Pixel settings saved", val ? `Meta Pixel ID ${val} is now active` : "Pixel ID updated");
      });

      // Dynamic auto-expanding textareas
      document.addEventListener("input", (e) => {
        if (e.target && e.target.classList && e.target.classList.contains("js-dynamic-textarea")) {
          e.target.style.height = "auto";
          e.target.style.height = Math.max(e.target.scrollHeight, parseInt(window.getComputedStyle(e.target).minHeight || 90)) + "px";
        }
      });

      // Create or Update Coupon Form Submit
      $("#js-coupon-form")?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const couponId = document.getElementById("coupon-id") ? document.getElementById("coupon-id").value.trim() : "";
        const code = document.getElementById("coupon-code").value.trim().toUpperCase();
        const type = document.getElementById("coupon-type").value;
        const val = parseFloat(document.getElementById("coupon-val").value);
        const minSpend = parseFloat(document.getElementById("coupon-min-spend").value || 0);
        const desc = document.getElementById("coupon-desc").value.trim();

        if (!code || isNaN(val) || val <= 0) {
          alert("Please fill in coupon code and a valid discount value.");
          return;
        }

        const isEdit = Boolean(couponId);
        const url = isEdit ? `/admin/coupons/${couponId}` : "/admin/coupons";
        const method = isEdit ? "PUT" : "POST";

        try {
          const res = await fetch(url, {
            method: method,
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({
              code: code,
              discount_type: type,
              discount_value: val,
              min_spend: minSpend,
              description: desc,
              is_active: 1
            })
          });
          const data = await res.json();
          if (data.success && data.coupon) {
            if (isEdit) {
              const idx = couponsList.findIndex(c => String(c.id) === String(couponId));
              if (idx !== -1) couponsList[idx] = data.coupon;
              toast("Coupon Updated", `${data.coupon.code} saved successfully`);
            } else {
              couponsList.unshift(data.coupon);
              toast("Coupon Created", `${data.coupon.code} is now live!`);
            }
            renderCoupons();
            const modal = document.querySelector(".js-coupon-modal");
            if (modal) modal.style.display = "none";
            document.getElementById("js-coupon-form").reset();
            if (document.getElementById("coupon-id")) document.getElementById("coupon-id").value = "";
          } else {
            alert("Error: " + (data.message || (data.errors ? Object.values(data.errors).flat().join("\n") : "Failed to save coupon")));
          }
        } catch (err) {
          console.error(err);
          alert("Network error: " + err.message);
        }
      });

      /* ======================================================================
         CMS PAGES & MULTI-LEVEL MENU BUILDER
         ====================================================================== */
      let cmsPages = [];
      let cmsMenuTrees = { header: [], footer_care: [] };
      let activeMenuLocation = "header";
      let pageSearchQuery = "";
      let isRteSourceMode = false;

      const cmsBaseUrl = "{{ rtrim(url('/'), '/') }}";

      function cmsEsc(str) {
        return String(str ?? "")
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#39;");
      }

      function slugifyText(val) {
        return String(val || "")
          .toLowerCase()
          .trim()
          .replace(/[^a-z0-9\s-]/g, "")
          .replace(/\s+/g, "-")
          .replace(/-+/g, "-")
          .replace(/^-|-$/g, "");
      }

      function updateSlugPreview() {
        const slugEl = document.getElementById("page-edit-slug");
        const prevEl = document.querySelector(".js-page-slug-preview");
        if (slugEl && prevEl) {
          const clean = slugifyText(slugEl.value || "") || "your-page-slug";
          prevEl.textContent = `${cmsBaseUrl}/${clean}`;
        }
      }

      function setRteMode(sourceMode) {
        const rte = document.getElementById("page-edit-rte");
        const src = document.getElementById("page-edit-html");
        const toolbar = document.querySelector(".js-rte-toolbar");
        const btn = document.querySelector(".js-rte-toggle-source");
        if (!rte || !src) return;

        if (sourceMode && !isRteSourceMode) {
          src.value = rte.innerHTML;
          rte.style.display = "none";
          src.style.display = "block";
          if (toolbar) toolbar.style.opacity = "0.45";
          if (toolbar) toolbar.style.pointerEvents = "none";
          btn?.classList.add("is-active");
          isRteSourceMode = true;
        } else if (!sourceMode && isRteSourceMode) {
          rte.innerHTML = src.value;
          src.style.display = "none";
          rte.style.display = "block";
          if (toolbar) toolbar.style.opacity = "1";
          if (toolbar) toolbar.style.pointerEvents = "auto";
          btn?.classList.remove("is-active");
          isRteSourceMode = false;
        }
      }

      async function loadCmsPages() {
        try {
          const res = await fetch("/admin/api/pages", { headers: { "Accept": "application/json" } });
          const data = await res.json();
          if (data.success && Array.isArray(data.pages)) {
            cmsPages = data.pages;
            renderPagesTable();
            populateMenuPageSelect();
          }
        } catch (err) {
          console.error("Failed to load pages:", err);
        }
      }

      function renderPagesTable() {
        const tbody = document.getElementById("adm-pages-list");
        const pubCount = cmsPages.filter(p => p.is_published).length;
        const draftCount = cmsPages.length - pubCount;

        document.querySelectorAll(".js-stat-pages-published").forEach(el => el.textContent = pubCount);
        document.querySelectorAll(".js-stat-pages-drafts").forEach(el => el.textContent = draftCount);
        document.querySelectorAll(".js-stat-pages-total").forEach(el => el.textContent = cmsPages.length);
        document.querySelectorAll(".js-pages-count-badge").forEach(el => el.textContent = cmsPages.length);

        if (!tbody) return;
        const q = pageSearchQuery.trim().toLowerCase();
        const filtered = q
          ? cmsPages.filter(p =>
              (p.title || "").toLowerCase().includes(q) ||
              (p.slug || "").toLowerCase().includes(q) ||
              (p.excerpt || "").toLowerCase().includes(q)
            )
          : cmsPages;

        if (!filtered.length) {
          tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:32px; color:var(--adm-muted);">No pages found. Click <strong>New Page</strong> to create one.</td></tr>`;
          return;
        }

        tbody.innerHTML = filtered.map(p => {
          const liveUrl = p.is_published ? `${cmsBaseUrl}/${p.slug}` : `${cmsBaseUrl}/${p.slug}?preview=1`;
          const statusPill = p.is_published
            ? `<span style="display:inline-flex;align-items:center;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:700;background:#DCFCE7;color:#15803D;">Published</span>`
            : `<span style="display:inline-flex;align-items:center;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:700;background:#FEF3C7;color:#B45309;">Draft</span>`;

          return `
            <tr data-page-id="${p.id}">
              <td>
                <div style="font-weight:700; color:var(--adm-text);">${cmsEsc(p.title)}</div>
                ${p.excerpt ? `<div class="adm-cell-sub" style="max-width:340px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${cmsEsc(p.excerpt)}</div>` : ""}
              </td>
              <td>
                <a href="${cmsEsc(liveUrl)}" target="_blank" style="color:#16A34A; font-weight:600; font-size:12.5px; text-decoration:none;">/${cmsEsc(p.slug)} &nearr;</a>
              </td>
              <td>${statusPill}</td>
              <td style="font-size:12px; color:var(--adm-muted);">${cmsEsc(p.updated_at || "—")}</td>
              <td style="text-align:right;">
                <div style="display:inline-flex; gap:6px; flex-wrap:wrap; justify-content:flex-end;">
                  <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-page-add-menu" data-id="${p.id}" title="Add this page to Menu">+ Menu</button>
                  <button type="button" class="adm-btn adm-btn--secondary adm-btn--sm js-page-edit-btn" data-id="${p.id}">Edit</button>
                  <button type="button" class="adm-btn adm-btn--sm js-page-del-btn" data-id="${p.id}" style="background:#FEE2E2; color:#B91C1C; border:1px solid #FECACA;">Delete</button>
                </div>
              </td>
            </tr>
          `;
        }).join("");
      }

      function openPageDrawer(page = null) {
        closeOverlays();
        setRteMode(false);

        const idEl = document.getElementById("page-edit-id");
        const titleEl = document.getElementById("page-edit-title");
        const slugEl = document.getElementById("page-edit-slug");
        const excerptEl = document.getElementById("page-edit-excerpt");
        const rteEl = document.getElementById("page-edit-rte");
        const htmlEl = document.getElementById("page-edit-html");
        const metaTitleEl = document.getElementById("page-edit-meta-title");
        const metaDescEl = document.getElementById("page-edit-meta-desc");
        const pubEl = document.getElementById("page-edit-published");
        const drawerTitle = document.querySelector(".js-page-drawer-title");
        const delBtn = document.querySelector(".js-delete-page-drawer-btn");
        const prevLink = document.querySelector(".js-page-preview-link");

        if (page) {
          idEl.value = page.id;
          titleEl.value = page.title || "";
          slugEl.value = page.slug || "";
          slugEl.dataset.manualSlug = "1";
          excerptEl.value = page.excerpt || "";
          rteEl.innerHTML = page.content || "<p></p>";
          htmlEl.value = page.content || "";
          metaTitleEl.value = page.meta_title || "";
          metaDescEl.value = page.meta_description || "";
          pubEl.checked = Boolean(page.is_published);
          if (drawerTitle) drawerTitle.textContent = `Edit Page: ${page.title}`;
          if (delBtn) delBtn.style.display = "inline-flex";
          if (prevLink) {
            prevLink.href = `${cmsBaseUrl}/${page.slug}${page.is_published ? "" : "?preview=1"}`;
            prevLink.style.display = "inline-flex";
          }
        } else {
          idEl.value = "";
          titleEl.value = "";
          slugEl.value = "";
          delete slugEl.dataset.manualSlug;
          excerptEl.value = "";
          rteEl.innerHTML = "<h2>Section Title</h2><p>Write your page content here...</p>";
          htmlEl.value = rteEl.innerHTML;
          metaTitleEl.value = "";
          metaDescEl.value = "";
          pubEl.checked = true;
          if (drawerTitle) drawerTitle.textContent = "Create New Storefront Page";
          if (delBtn) delBtn.style.display = "none";
          if (prevLink) prevLink.style.display = "none";
        }

        updateSlugPreview();
        document.body.classList.add("is-page-drawer-open");
      }

      async function deletePageById(id) {
        const p = cmsPages.find(x => String(x.id) === String(id));
        if (!p) return;
        if (!confirm(`Delete page "${p.title}" (/${p.slug})? Links pointing to this page will be detached.`)) return;

        try {
          const res = await fetch(`/admin/pages/${id}`, {
            method: "DELETE",
            headers: { "Accept": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN }
          });
          const data = await res.json();
          if (data.success) {
            toast("Page Deleted", `"${p.title}" removed`);
            closeOverlays();
            await loadCmsPages();
            await loadCmsMenus();
          } else {
            alert(data.message || "Failed to delete page");
          }
        } catch (err) {
          alert("Network error: " + err.message);
        }
      }

      /* ---------- Multi-Level Menu Builder ---------- */
      async function loadCmsMenus() {
        try {
          const res = await fetch("/admin/api/menus", { headers: { "Accept": "application/json" } });
          const data = await res.json();
          if (data.success) {
            cmsMenuTrees.header = Array.isArray(data.header_tree) ? data.header_tree : [];
            cmsMenuTrees.footer_care = Array.isArray(data.footer_care_tree) ? data.footer_care_tree : [];
            renderMenuBuilder();
          }
        } catch (err) {
          console.error("Failed to load menus:", err);
        }
      }

      function populateMenuPageSelect() {
        const sel = document.getElementById("menu-item-page-id");
        if (!sel) return;
        const current = sel.value;
        sel.innerHTML = `<option value="">-- Choose a page --</option>` +
          cmsPages.map(p => `<option value="${p.id}">${cmsEsc(p.title)} (/${cmsEsc(p.slug)})${p.is_published ? "" : " [Draft]"}</option>`).join("");
        if (current) sel.value = current;
      }

      function syncMenuTypeFields() {
        const typeEl = document.getElementById("menu-item-type");
        const pageWrap = document.querySelector(".js-menu-page-wrap");
        const presetWrap = document.querySelector(".js-menu-preset-wrap");
        const urlWrap = document.querySelector(".js-menu-url-wrap");
        if (!typeEl) return;

        const t = typeEl.value;
        if (pageWrap) pageWrap.style.display = t === "page" ? "block" : "none";
        if (presetWrap) presetWrap.style.display = t === "route" ? "block" : "none";
        if (urlWrap) urlWrap.style.display = t === "page" ? "none" : "block";
      }

      function collectDescendantIds(nodes, targetId, inside = false, out = new Set()) {
        for (const n of nodes || []) {
          const isMatch = inside || String(n.id) === String(targetId);
          if (isMatch) out.add(String(n.id));
          if (n.children && n.children.length) {
            collectDescendantIds(n.children, targetId, isMatch, out);
          }
        }
        return out;
      }

      function populateMenuParentSelect(excludeSet = new Set()) {
        const sel = document.getElementById("menu-item-parent-id");
        if (!sel) return;
        const currentVal = sel.value;
        const tree = cmsMenuTrees[activeMenuLocation] || [];
        const options = [`<option value="">— Top Level (Root) —</option>`];

        function walk(nodes, depth) {
          for (const n of nodes) {
            if (excludeSet.has(String(n.id))) continue;
            const prefix = depth > 0 ? `${"— ".repeat(depth)}↳ ` : "";
            options.push(`<option value="${n.id}">${prefix}${cmsEsc(n.title)}</option>`);
            if (n.children && n.children.length) {
              walk(n.children, depth + 1);
            }
          }
        }
        walk(tree, 0);
        sel.innerHTML = options.join("");
        if (currentVal && !excludeSet.has(String(currentVal))) {
          sel.value = currentVal;
        }
      }

      function resetMenuForm() {
        const form = document.getElementById("js-menu-item-form");
        if (!form) return;
        form.reset();
        document.getElementById("menu-edit-id").value = "";
        document.getElementById("menu-item-type").value = "page";
        document.getElementById("menu-item-active").checked = true;
        document.querySelector(".js-menu-form-title").textContent = "Add Menu Item";
        document.querySelector(".js-menu-save-btn").textContent = "Add to Menu";
        document.querySelector(".js-menu-cancel-edit").style.display = "none";
        populateMenuPageSelect();
        populateMenuParentSelect(new Set());
        syncMenuTypeFields();
        renderMenuBuilder();
      }

      function findMenuNodeById(nodes, id) {
        for (const n of nodes || []) {
          if (String(n.id) === String(id)) return n;
          if (n.children && n.children.length) {
            const found = findMenuNodeById(n.children, id);
            if (found) return found;
          }
        }
        return null;
      }

      function startEditMenuItem(id) {
        const tree = cmsMenuTrees[activeMenuLocation] || [];
        const node = findMenuNodeById(tree, id);
        if (!node) return;

        document.getElementById("menu-edit-id").value = node.id;
        document.getElementById("menu-item-type").value = node.type || "custom";
        syncMenuTypeFields();

        populateMenuPageSelect();
        if (node.page_id) {
          document.getElementById("menu-item-page-id").value = node.page_id;
        }
        document.getElementById("menu-item-url").value = node.url || "";
        document.getElementById("menu-item-title").value = node.title || "";
        document.getElementById("menu-item-badge").value = node.badge_text || "";
        document.getElementById("menu-item-target").value = node.target || "_self";
        document.getElementById("menu-item-active").checked = Boolean(node.is_active);

        const excludeSet = collectDescendantIds(tree, node.id);
        populateMenuParentSelect(excludeSet);
        document.getElementById("menu-item-parent-id").value = node.parent_id ? String(node.parent_id) : "";

        document.querySelector(".js-menu-form-title").textContent = `Edit: ${node.title}`;
        document.querySelector(".js-menu-save-btn").textContent = "Save Changes";
        document.querySelector(".js-menu-cancel-edit").style.display = "inline-flex";
        renderMenuBuilder();
        document.getElementById("menu-item-title").focus();
      }

      function renderMenuBuilder() {
        const wrap = document.getElementById("adm-menu-tree-wrap");
        const heading = document.querySelector(".js-menu-tree-heading");
        if (heading) {
          heading.textContent = activeMenuLocation === "header"
            ? "Header Navigation Tree (Desktop Dropdown & Mobile Accordion)"
            : "Footer Customer Care Links";
        }
        if (!wrap) return;

        const editingId = document.getElementById("menu-edit-id")?.value || "";
        const tree = cmsMenuTrees[activeMenuLocation] || [];

        if (!editingId) {
          populateMenuParentSelect(new Set());
        }

        if (!tree.length) {
          wrap.innerHTML = `<div style="text-align:center; padding:32px; color:var(--adm-muted); border:1px dashed var(--adm-border-strong); border-radius:10px;">No menu items in this location yet. Add your first link on the left.</div>`;
          return;
        }

        const rows = [];
        function walk(nodes, depth, parentId) {
          nodes.forEach((n, idx) => {
            rows.push({
              node: n,
              depth: depth,
              index: idx,
              siblingCount: nodes.length,
              hasParent: parentId !== null
            });
            if (n.children && n.children.length) {
              walk(n.children, depth + 1, n.id);
            }
          });
        }
        walk(tree, 0, null);

        wrap.innerHTML = rows.map(({ node, depth, index, siblingCount, hasParent }) => {
          const depthLabel = depth === 0 ? "TOP" : `L${depth + 1}`;
          const canUp = index > 0;
          const canDown = index < siblingCount - 1;
          const canIndent = index > 0;
          const canOutdent = hasParent;
          const isEditTarget = String(editingId) === String(node.id);
          const urlDisplay = node.type === "page" && node.page_slug ? `/${node.page_slug}` : (node.url || "#");

          return `
            <div class="adm-menu-node ${node.is_active ? "" : "is-inactive"} ${isEditTarget ? "is-editing" : ""}" style="--depth:${Math.min(depth, 4)};" data-depth="${Math.min(depth, 4)}" data-menu-id="${node.id}">
              <div class="adm-menu-node__info">
                <div class="adm-menu-node__top">
                  <span class="adm-menu-depth-pill">${depthLabel}</span>
                  <span class="adm-menu-node__title">${cmsEsc(node.title)}</span>
                  ${node.badge_text ? `<span class="adm-menu-node__badge">${cmsEsc(node.badge_text)}</span>` : ""}
                  ${!node.is_active ? `<span style="font-size:10.5px; color:#B45309; font-weight:700;">[Hidden]</span>` : ""}
                </div>
                <div class="adm-menu-node__url">
                  ${cmsEsc(urlDisplay)} &bull; ${node.type === "page" ? "CMS Page" : (node.type === "route" ? "Store Link" : "Custom")}
                  ${node.target === "_blank" ? " &bull; New Tab" : ""}
                </div>
              </div>

              <div class="adm-menu-actions">
                <button type="button" class="adm-mbtn js-menu-move" data-id="${node.id}" data-act="up" ${canUp ? "" : "disabled"} title="Move Up">&uarr;</button>
                <button type="button" class="adm-mbtn js-menu-move" data-id="${node.id}" data-act="down" ${canDown ? "" : "disabled"} title="Move Down">&darr;</button>
                <button type="button" class="adm-mbtn js-menu-move" data-id="${node.id}" data-act="outdent" ${canOutdent ? "" : "disabled"} title="Move Out One Level">&larr;</button>
                <button type="button" class="adm-mbtn js-menu-move" data-id="${node.id}" data-act="indent" ${canIndent ? "" : "disabled"} title="Nest Inside Previous Item">&rarr;</button>
                <button type="button" class="adm-mbtn js-menu-add-sub" data-id="${node.id}" title="Add Sub-item under ${cmsEsc(node.title)}">+Sub</button>
                <button type="button" class="adm-mbtn js-menu-edit" data-id="${node.id}" title="Edit Item">Edit</button>
                <button type="button" class="adm-mbtn adm-mbtn--danger js-menu-del" data-id="${node.id}" title="Delete Item">&times;</button>
              </div>
            </div>
          `;
        }).join("");
      }

      async function moveMenuNode(id, action) {
        const treeCopy = JSON.parse(JSON.stringify(cmsMenuTrees[activeMenuLocation] || []));

        function locate(branch, targetId, parentNode = null) {
          for (let i = 0; i < branch.length; i++) {
            if (String(branch[i].id) === String(targetId)) {
              return { list: branch, index: i, node: branch[i], parent: parentNode };
            }
            if (branch[i].children && branch[i].children.length) {
              const found = locate(branch[i].children, targetId, branch[i]);
              if (found) return found;
            }
          }
          return null;
        }

        const loc = locate(treeCopy, id);
        if (!loc) return;

        if (action === "up" && loc.index > 0) {
          const tmp = loc.list[loc.index - 1];
          loc.list[loc.index - 1] = loc.node;
          loc.list[loc.index] = tmp;
        } else if (action === "down" && loc.index < loc.list.length - 1) {
          const tmp = loc.list[loc.index + 1];
          loc.list[loc.index + 1] = loc.node;
          loc.list[loc.index] = tmp;
        } else if (action === "indent" && loc.index > 0) {
          const newParent = loc.list[loc.index - 1];
          loc.list.splice(loc.index, 1);
          newParent.children = newParent.children || [];
          loc.node.parent_id = newParent.id;
          newParent.children.push(loc.node);
        } else if (action === "outdent" && loc.parent) {
          const parentLoc = locate(treeCopy, loc.parent.id);
          if (!parentLoc) return;
          loc.list.splice(loc.index, 1);
          loc.node.parent_id = parentLoc.parent ? parentLoc.parent.id : null;
          parentLoc.list.splice(parentLoc.index + 1, 0, loc.node);
        } else {
          return;
        }

        const payloadItems = [];
        function flatten(nodes, pid) {
          nodes.forEach((n, idx) => {
            payloadItems.push({
              id: Number(n.id),
              parent_id: pid ? Number(pid) : null,
              sort_order: (idx + 1) * 10
            });
            if (n.children && n.children.length) {
              flatten(n.children, n.id);
            }
          });
        }
        flatten(treeCopy, null);

        try {
          const res = await fetch("/admin/menus/reorder", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify({
              location: activeMenuLocation,
              items: payloadItems
            })
          });
          const data = await res.json();
          if (data.success) {
            cmsMenuTrees.header = data.header_tree || cmsMenuTrees.header;
            cmsMenuTrees.footer_care = data.footer_care_tree || cmsMenuTrees.footer_care;
            renderMenuBuilder();
            toast("Menu Updated", "Order & hierarchy saved live");
          } else {
            alert(data.message || "Could not save menu order");
          }
        } catch (err) {
          alert("Network error: " + err.message);
        }
      }

      // Page Events
      document.querySelector(".js-open-page-drawer")?.addEventListener("click", () => openPageDrawer(null));
      document.querySelector(".js-page-search-input")?.addEventListener("input", (e) => {
        pageSearchQuery = e.target.value || "";
        renderPagesTable();
      });

      document.getElementById("page-edit-title")?.addEventListener("input", (e) => {
        const slugEl = document.getElementById("page-edit-slug");
        if (slugEl && !slugEl.dataset.manualSlug) {
          slugEl.value = slugifyText(e.target.value);
          updateSlugPreview();
        }
      });

      document.getElementById("page-edit-slug")?.addEventListener("input", (e) => {
        if (e.target.value.trim() !== "") {
          e.target.dataset.manualSlug = "1";
        } else {
          delete e.target.dataset.manualSlug;
        }
        updateSlugPreview();
      });

      document.querySelector(".js-rte-toggle-source")?.addEventListener("click", () => {
        setRteMode(!isRteSourceMode);
      });

      document.querySelectorAll(".js-rte-cmd").forEach(btn => {
        btn.addEventListener("click", () => {
          if (isRteSourceMode) return;
          const rte = document.getElementById("page-edit-rte");
          if (rte) rte.focus();
          const cmd = btn.dataset.cmd;
          const val = btn.dataset.val || null;
          document.execCommand(cmd, false, val);
        });
      });

      document.querySelector(".js-rte-link-btn")?.addEventListener("click", () => {
        if (isRteSourceMode) return;
        const url = prompt("Enter link URL (e.g. /shop or https://...):", "/shop");
        if (url) {
          document.getElementById("page-edit-rte")?.focus();
          document.execCommand("createLink", false, url);
        }
      });

      document.querySelector(".js-delete-page-drawer-btn")?.addEventListener("click", () => {
        const id = document.getElementById("page-edit-id")?.value;
        if (id) deletePageById(id);
      });

      document.getElementById("js-page-editor-form")?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const id = document.getElementById("page-edit-id").value.trim();
        const rteEl = document.getElementById("page-edit-rte");
        const htmlEl = document.getElementById("page-edit-html");
        const contentHtml = isRteSourceMode ? htmlEl.value : rteEl.innerHTML;

        const payload = {
          title: document.getElementById("page-edit-title").value.trim(),
          slug: document.getElementById("page-edit-slug").value.trim(),
          excerpt: document.getElementById("page-edit-excerpt").value.trim(),
          content: contentHtml,
          meta_title: document.getElementById("page-edit-meta-title").value.trim(),
          meta_description: document.getElementById("page-edit-meta-desc").value.trim(),
          is_published: document.getElementById("page-edit-published").checked ? 1 : 0
        };

        const btn = document.querySelector(".js-save-page-btn");
        if (btn) btn.disabled = true;

        try {
          const url = id ? `/admin/pages/${id}/update` : "/admin/pages";
          const res = await fetch(url, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.success) {
            toast("Page Saved", `"${data.page.title}" is ready at /${data.page.slug}`);
            closeOverlays();
            await loadCmsPages();
            await loadCmsMenus();
          } else {
            alert(data.message || "Failed to save page");
          }
        } catch (err) {
          alert("Network error: " + err.message);
        } finally {
          if (btn) btn.disabled = false;
        }
      });

      document.getElementById("adm-pages-list")?.addEventListener("click", (e) => {
        const editBtn = e.target.closest(".js-page-edit-btn");
        if (editBtn) {
          const page = cmsPages.find(x => String(x.id) === String(editBtn.dataset.id));
          if (page) openPageDrawer(page);
          return;
        }

        const delBtn = e.target.closest(".js-page-del-btn");
        if (delBtn) {
          deletePageById(delBtn.dataset.id);
          return;
        }

        const addMenuBtn = e.target.closest(".js-page-add-menu");
        if (addMenuBtn) {
          const page = cmsPages.find(x => String(x.id) === String(addMenuBtn.dataset.id));
          if (!page) return;
          showTab("menus");
          resetMenuForm();
          document.getElementById("menu-item-type").value = "page";
          syncMenuTypeFields();
          populateMenuPageSelect();
          document.getElementById("menu-item-page-id").value = String(page.id);
          document.getElementById("menu-item-title").value = page.title;
          document.getElementById("menu-item-title").focus();
          toast("Ready to Add", `Selected "${page.title}" in Menu Builder`);
        }
      });

      // Menu Builder Events
      document.querySelectorAll(".js-menu-loc-tab").forEach(btn => {
        btn.addEventListener("click", () => {
          document.querySelectorAll(".js-menu-loc-tab").forEach(b => b.classList.remove("is-active"));
          btn.classList.add("is-active");
          activeMenuLocation = btn.dataset.location || "header";
          resetMenuForm();
        });
      });

      document.getElementById("menu-item-type")?.addEventListener("change", syncMenuTypeFields);

      document.getElementById("menu-item-page-id")?.addEventListener("change", (e) => {
        const page = cmsPages.find(p => String(p.id) === String(e.target.value));
        const titleEl = document.getElementById("menu-item-title");
        if (page && titleEl && !document.getElementById("menu-edit-id").value && !titleEl.value.trim()) {
          titleEl.value = page.title;
        }
      });

      document.getElementById("menu-item-preset")?.addEventListener("change", (e) => {
        const val = e.target.value;
        if (!val) return;
        const [url, label] = val.split("|");
        document.getElementById("menu-item-url").value = url || "/";
        const titleEl = document.getElementById("menu-item-title");
        if (titleEl && !titleEl.value.trim()) {
          titleEl.value = label || "";
        }
      });

      document.querySelector(".js-menu-cancel-edit")?.addEventListener("click", resetMenuForm);

      document.getElementById("js-menu-item-form")?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const editId = document.getElementById("menu-edit-id").value.trim();
        const type = document.getElementById("menu-item-type").value;
        const pageIdVal = document.getElementById("menu-item-page-id").value;
        const parentVal = document.getElementById("menu-item-parent-id").value;

        const payload = {
          location: activeMenuLocation,
          parent_id: parentVal ? Number(parentVal) : null,
          title: document.getElementById("menu-item-title").value.trim(),
          type: type,
          page_id: type === "page" && pageIdVal ? Number(pageIdVal) : null,
          url: type === "page" ? null : document.getElementById("menu-item-url").value.trim(),
          badge_text: document.getElementById("menu-item-badge").value.trim(),
          target: document.getElementById("menu-item-target").value,
          is_active: document.getElementById("menu-item-active").checked ? 1 : 0
        };

        try {
          const url = editId ? `/admin/menus/${editId}/update` : "/admin/menus";
          const res = await fetch(url, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": CSRF_TOKEN
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.success) {
            cmsMenuTrees.header = data.header_tree || cmsMenuTrees.header;
            cmsMenuTrees.footer_care = data.footer_care_tree || cmsMenuTrees.footer_care;
            toast("Menu Saved", editId ? "Menu item updated" : "Menu item added");
            resetMenuForm();
          } else {
            alert(data.message || "Failed to save menu item");
          }
        } catch (err) {
          alert("Network error: " + err.message);
        }
      });

      document.getElementById("adm-menu-tree-wrap")?.addEventListener("click", async (e) => {
        const moveBtn = e.target.closest(".js-menu-move");
        if (moveBtn && !moveBtn.disabled) {
          await moveMenuNode(moveBtn.dataset.id, moveBtn.dataset.act);
          return;
        }

        const subBtn = e.target.closest(".js-menu-add-sub");
        if (subBtn) {
          const parentNode = findMenuNodeById(cmsMenuTrees[activeMenuLocation], subBtn.dataset.id);
          resetMenuForm();
          document.getElementById("menu-item-parent-id").value = String(subBtn.dataset.id);
          document.getElementById("menu-item-title").focus();
          if (parentNode) {
            toast("Adding Submenu", `Child item under "${parentNode.title}"`);
          }
          return;
        }

        const editBtn = e.target.closest(".js-menu-edit");
        if (editBtn) {
          startEditMenuItem(editBtn.dataset.id);
          return;
        }

        const delBtn = e.target.closest(".js-menu-del");
        if (delBtn) {
          const node = findMenuNodeById(cmsMenuTrees[activeMenuLocation], delBtn.dataset.id);
          if (!node) return;
          const hasKids = node.children && node.children.length > 0;
          const msg = hasKids
            ? `Delete "${node.title}" AND all its nested submenu items?`
            : `Delete menu item "${node.title}"?`;
          if (!confirm(msg)) return;

          try {
            const res = await fetch(`/admin/menus/${node.id}`, {
              method: "DELETE",
              headers: { "Accept": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN }
            });
            const data = await res.json();
            if (data.success) {
              cmsMenuTrees.header = data.header_tree || cmsMenuTrees.header;
              cmsMenuTrees.footer_care = data.footer_care_tree || cmsMenuTrees.footer_care;
              toast("Removed", `"${node.title}" deleted from menu`);
              if (document.getElementById("menu-edit-id").value === String(node.id)) {
                resetMenuForm();
              } else {
                renderMenuBuilder();
              }
            } else {
              alert(data.message || "Failed to delete menu item");
            }
          } catch (err) {
            alert("Network error: " + err.message);
          }
        }
      });

      /* ---------- Init ---------- */
      initColumnToggles();
      renderNewSizes("winter-hoodie");
      renderOrders();
      renderProducts();
      renderCategories();
      renderCustomers();
      renderUsers();
      renderCoupons();
      loadCmsPages();
      loadCmsMenus();
      syncMenuTypeFields();
    })();
  </script>
</body>
</html>

