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
      padding: 40px 0 46px;
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

    /* Interactive KPI Cards */
    .rabez-acc-stats {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 14px;
      margin-bottom: 24px;
    }
    .rabez-acc-stat {
      background: #FFFFFF;
      border: 1.5px solid #DCE3DF;
      border-radius: 14px;
      padding: 16px 18px;
      box-shadow: 0 8px 24px rgba(10, 12, 11, 0.04);
      text-align: left;
      transition: all 0.18s ease;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    button.rabez-acc-stat {
      cursor: pointer;
    }
    button.rabez-acc-stat:hover {
      border-color: #0A0C0B;
      transform: translateY(-2px);
      box-shadow: 0 12px 26px rgba(10, 12, 11, 0.08);
    }
    button.rabez-acc-stat.is-active {
      border-color: #0A0C0B;
      background: linear-gradient(135deg, #FFFFFF 0%, #F2FDF2 100%);
      box-shadow: 0 0 0 3px rgba(4, 244, 4, 0.18);
    }
    .rabez-acc-stat__top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
      margin-bottom: 8px;
    }
    .rabez-acc-stat__label {
      font-size: 0.74rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #6B7570;
    }
    .rabez-acc-stat__chip {
      font-size: 0.68rem;
      font-weight: 700;
      color: #166534;
      background: #DCFCE7;
      padding: 2px 7px;
      border-radius: 999px;
      white-space: nowrap;
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

    /* Main Grid */
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
      padding: 22px;
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
      margin-bottom: 16px;
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

    /* Smart Filter & Search Toolbar */
    .rabez-acc-toolbar {
      display: grid;
      gap: 12px;
      margin-bottom: 18px;
    }
    .rabez-acc-filter-bar {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }
    .rabez-acc-filter-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 13px;
      border-radius: 999px;
      border: 1.5px solid #DCE3DF;
      background: #F8FAF9;
      color: #334155;
      font-size: 0.8rem;
      font-weight: 700;
      cursor: pointer;
      white-space: nowrap;
      transition: all 0.15s ease;
    }
    .rabez-acc-filter-btn:hover {
      border-color: #0A0C0B;
      color: #0A0C0B;
    }
    .rabez-acc-filter-btn.is-active {
      background: #0A0C0B;
      border-color: #0A0C0B;
      color: #04F404;
    }
    .rabez-acc-filter-count {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 20px;
      height: 18px;
      padding: 0 5px;
      border-radius: 999px;
      font-size: 0.7rem;
      font-weight: 800;
      background: rgba(10, 12, 11, 0.08);
      color: #475569;
    }
    .rabez-acc-filter-btn.is-active .rabez-acc-filter-count {
      background: rgba(4, 244, 4, 0.18);
      color: #04F404;
    }
    .rabez-acc-search-row {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 10px;
      align-items: center;
    }
    .rabez-acc-search-wrap {
      position: relative;
      display: flex;
      align-items: center;
      min-width: 0;
    }
    .rabez-acc-search-wrap svg.search-icon {
      position: absolute;
      left: 12px;
      color: #64748B;
      pointer-events: none;
    }
    .rabez-acc-search-input {
      width: 100%;
      height: 40px;
      padding: 0 34px 0 36px;
      border: 1.5px solid #CBD5E1;
      border-radius: 9px;
      font-size: 0.86rem;
      font-weight: 600;
      color: #0A0C0B;
      background: #FFFFFF;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .rabez-acc-search-input:focus {
      outline: none;
      border-color: #0A0C0B;
      box-shadow: 0 0 0 3px rgba(4, 244, 4, 0.16);
    }
    .rabez-acc-search-clear {
      position: absolute;
      right: 8px;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: #E2E8F0;
      color: #334155;
      font-size: 0.85rem;
      font-weight: 800;
      display: none;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }
    .rabez-acc-search-clear.is-visible {
      display: inline-flex;
    }
    .rabez-acc-sort-select {
      height: 40px;
      padding: 0 12px;
      border: 1.5px solid #CBD5E1;
      border-radius: 9px;
      font-size: 0.84rem;
      font-weight: 700;
      color: #0A0C0B;
      background: #FFFFFF;
      cursor: pointer;
    }

    /* Order Cards */
    .rabez-acc-order-list {
      display: grid;
      gap: 16px;
    }
    .rabez-acc-order {
      border: 1.5px solid #DCE3DF;
      border-radius: 14px;
      padding: 18px;
      background: #FCFDFC;
      transition: border-color 0.16s ease, box-shadow 0.16s ease;
      min-width: 0;
    }
    .rabez-acc-order:hover {
      border-color: #0A0C0B;
      box-shadow: 0 8px 22px rgba(10, 12, 11, 0.05);
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
      white-space: nowrap;
    }
    .rabez-acc-copy-btn {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 8px;
      border-radius: 5px;
      background: #F1F5F9;
      border: 1px solid #DCE3DF;
      color: #475569;
      font-size: 0.72rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .rabez-acc-copy-btn:hover {
      border-color: #0A0C0B;
      color: #0A0C0B;
    }
    .rabez-acc-copy-btn.is-copied {
      background: #DCFCE7;
      border-color: #22C55E;
      color: #166534;
    }
    .rabez-acc-order__date {
      font-size: 0.8rem;
      color: #64748B;
      font-weight: 600;
    }
    .rabez-acc-status {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 11px;
      border-radius: 999px;
      font-size: 0.75rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      white-space: nowrap;
      flex-shrink: 0;
    }
    .rabez-acc-status--pending { background: #FEF3C7; color: #92400E; }
    .rabez-acc-status--printing { background: #E0F2FE; color: #075985; }
    .rabez-acc-status--shipped { background: #EDE9FE; color: #5B21B6; }
    .rabez-acc-status--delivered { background: #DCFCE7; color: #166534; }
    .rabez-acc-status--cancelled { background: #FEE2E2; color: #991B1B; }

    /* Compact Inline Order Progress */
    .rabez-acc-progress {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 6px;
      margin-bottom: 14px;
      padding: 10px 12px;
      background: #F4F7F5;
      border-radius: 9px;
    }
    .rabez-acc-prog-step {
      display: flex;
      flex-direction: column;
      gap: 5px;
      min-width: 0;
    }
    .rabez-acc-prog-bar {
      height: 5px;
      border-radius: 999px;
      background: #DCE3DF;
    }
    .rabez-acc-prog-step.is-done .rabez-acc-prog-bar {
      background: #00A800;
    }
    .rabez-acc-prog-step.is-current .rabez-acc-prog-bar {
      background: #04F404;
      box-shadow: 0 0 8px rgba(4, 244, 4, 0.55);
    }
    .rabez-acc-prog-label {
      font-size: 0.69rem;
      font-weight: 700;
      color: #64748B;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .rabez-acc-prog-step.is-done .rabez-acc-prog-label,
    .rabez-acc-prog-step.is-current .rabez-acc-prog-label {
      color: #0A0C0B;
      font-weight: 800;
    }

    .rabez-acc-items {
      display: grid;
      gap: 10px;
      margin-bottom: 12px;
    }
    .rabez-acc-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 9px 11px;
      background: #FFFFFF;
      border: 1px solid #EAEFEF;
      border-radius: 10px;
    }
    .rabez-acc-item img {
      width: 50px;
      height: 56px;
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
      font-family: 'Red Hat Text', sans-serif;
      font-size: 0.89rem;
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
      font-size: 0.74rem;
      color: #475569;
    }
    .rabez-acc-tag {
      background: #F1F5F9;
      padding: 2px 7px;
      border-radius: 4px;
      font-weight: 700;
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
      flex-shrink: 0;
    }

    .rabez-acc-order__meta {
      font-size: 0.81rem;
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
      gap: 10px;
      flex-wrap: wrap;
    }
    .rabez-acc-order__total {
      font-size: 1.02rem;
      font-weight: 800;
      color: #0A0C0B;
      white-space: nowrap;
    }
    .rabez-acc-order__total span {
      color: #008A00;
    }
    .rabez-acc-order__btns {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }
    .rabez-acc-mini-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 0.8rem;
      font-weight: 700;
      border: 1.5px solid #CBD5E1;
      background: #FFFFFF;
      color: #0A0C0B;
      text-decoration: none;
      cursor: pointer;
      white-space: nowrap;
      transition: all 0.15s ease;
    }
    .rabez-acc-mini-btn:hover {
      border-color: #0A0C0B;
      background: #F1F5F9;
    }
    .rabez-acc-mini-btn--reorder {
      border-color: #16A34A;
      color: #15803D;
      background: #F0FDF4;
    }
    .rabez-acc-mini-btn--reorder:hover {
      background: #04F404;
      color: #080A09;
      border-color: #04F404;
    }

    /* Right Sidebar Tabs */
    .rabez-acc-side-tabs {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 6px;
      padding: 4px;
      background: #F1F5F9;
      border-radius: 10px;
      margin-bottom: 18px;
    }
    .rabez-acc-side-tab {
      padding: 9px 10px;
      border-radius: 8px;
      font-size: 0.82rem;
      font-weight: 800;
      color: #475569;
      text-align: center;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .rabez-acc-side-tab.is-active {
      background: #0A0C0B;
      color: #04F404;
    }
    .rabez-acc-side-panel {
      display: none;
    }
    .rabez-acc-side-panel.is-active {
      display: block;
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
    @media (max-width: 560px) {
      .rabez-acc-hero {
        padding: 28px 0 30px;
      }
      .rabez-acc-card {
        padding: 18px 14px;
      }
      .rabez-acc-stat {
        padding: 14px 13px;
      }
      .rabez-acc-stat__top {
        flex-wrap: wrap;
        gap: 4px;
      }
      .rabez-acc-filter-bar {
        flex-wrap: wrap;
        gap: 6px;
      }
      .rabez-acc-filter-btn {
        padding: 6px 10px;
        font-size: 0.75rem;
      }
      .rabez-acc-search-row {
        grid-template-columns: 1fr;
      }
      .rabez-acc-item {
        align-items: flex-start;
      }
      .rabez-acc-order__foot {
        flex-direction: column;
        align-items: stretch;
      }
      .rabez-acc-order__btns {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
      }
      .rabez-acc-order__btns .rabez-acc-mini-btn {
        flex: 1 1 0;
      }
      .rabez-acc-order__btns .rabez-btn {
        flex: 1 0 100%;
      }
    }

    /* Profile Picture Setup & Security Badges */
    .rabez-acc-avatar-wrap {
      position: relative;
      width: 64px;
      height: 64px;
      flex-shrink: 0;
    }
    .rabez-acc-avatar-cam {
      position: absolute;
      right: -4px;
      bottom: -4px;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: #0A0C0B;
      color: #04F404;
      border: 2px solid #04F404;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform 0.15s ease, background 0.15s ease;
      padding: 0;
    }
    .rabez-acc-avatar-cam:hover {
      transform: scale(1.08);
      background: #04F404;
      color: #000000;
    }
    .rabez-acc-photo-box {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 12px 14px;
      background: #F9FAFB;
      border: 1px dashed #D1D5DB;
      border-radius: 10px;
    }
    .rabez-acc-photo-preview {
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: #0A0C0B;
      border: 2px solid rgba(4, 244, 4, 0.55);
      color: #04F404;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-heading, 'Bebas Neue', sans-serif);
      font-size: 1.5rem;
      font-weight: 700;
      overflow: hidden;
      flex-shrink: 0;
    }
    .rabez-acc-photo-preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .rabez-acc-photo-actions {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 6px;
    }
    .rabez-acc-connected-box {
      margin-top: 16px;
      padding-top: 14px;
      border-top: 1px solid #E5E7EB;
    }
    .rabez-acc-connected-title {
      font-size: 0.78rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #4B5563;
      margin-bottom: 8px;
    }
    .rabez-acc-connected-list {
      display: grid;
      gap: 6px;
    }
    .rabez-acc-connected-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 0.8rem;
      padding: 7px 10px;
      border-radius: 7px;
      background: #F9FAFB;
      border: 1px solid #E5E7EB;
      color: #111827;
      font-weight: 600;
    }
    .rabez-acc-conn-badge {
      font-size: 0.68rem;
      font-weight: 800;
      padding: 2px 7px;
      border-radius: 999px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .rabez-acc-conn-badge--on {
      background: #DCFCE7;
      color: #166534;
    }
    .rabez-acc-conn-badge--off {
      background: #F3F4F6;
      color: #6B7280;
    }
  </style>
@endpush

@section('content')
  @php
    $openSecurityTab = $errors->has('current_password') || $errors->has('password');
    $waNumber = preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '8801612359652');
    $resolvedAvatar = !empty($user->avatar)
      ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset(ltrim($user->avatar, '/')))
      : null;
    $userInitial = strtoupper(mb_substr($user->name ?: 'R', 0, 1));
  @endphp

  <section class="rabez-acc-hero">
    <div class="rabez-container rabez-acc-hero__inner" style="max-width:1200px;">
      <div class="rabez-acc-user">
        <div class="rabez-acc-avatar-wrap">
          <div class="rabez-acc-avatar">
            @if($resolvedAvatar)
              <img src="{{ $resolvedAvatar }}" alt="{{ $user->name }}" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
              <span style="display:none; width:100%; height:100%; align-items:center; justify-content:center;">{{ $userInitial }}</span>
            @else
              {{ $userInitial }}
            @endif
          </div>
          <button type="button" class="rabez-acc-avatar-cam js-acc-hero-avatar-btn" title="Change Profile Picture" aria-label="Change Profile Picture">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          </button>
        </div>
        <div class="rabez-acc-meta">
          <div class="rabez-acc-badges">
            <span class="rabez-acc-pill">{{ $user->isAdmin() ? 'ADMIN ACCOUNT' : 'RABEZ MEMBER' }}</span>
            @if(!empty($user->phone))
              <span class="rabez-acc-pill rabez-acc-pill--muted">{{ $user->phone }}</span>
            @endif
          </div>
          <h1 class="rabez-acc-title">Welcome, {{ $user->name }}</h1>
          <p class="rabez-acc-sub">{{ $user->email }} &bull; Track orders, reorder kits, and manage delivery details</p>
        </div>
      </div>

      <div class="rabez-acc-actions">
        <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--primary" style="padding:10px 18px; font-size:0.86rem;">Shop Jerseys</a>
        <a href="{{ route('wishlist') }}" class="rabez-acc-btn-outline" style="display:inline-flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#04F404" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          <span>Wishlist (<span class="js-wishlist-count">0</span>)</span>
        </a>
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

      <!-- Interactive Summary KPIs (Click to Filter) -->
      <div class="rabez-acc-stats">
        <button type="button" class="rabez-acc-stat js-kpi-filter {{ $initialStatus === 'all' ? 'is-active' : '' }}" data-filter="all">
          <div class="rabez-acc-stat__top">
            <span class="rabez-acc-stat__label">Total Orders</span>
            <span class="rabez-acc-stat__chip">View All</span>
          </div>
          <div class="rabez-acc-stat__value">{{ $stats['total_orders'] }}</div>
        </button>

        <button type="button" class="rabez-acc-stat js-kpi-filter {{ $initialStatus === 'pending' ? 'is-active' : '' }}" data-filter="pending">
          <div class="rabez-acc-stat__top">
            <span class="rabez-acc-stat__label">Pending</span>
            @if(($stats['active_orders'] - $stats['pending_orders']) > 0)
              <span class="rabez-acc-stat__chip" style="background:#E0F2FE; color:#075985;">+{{ $stats['active_orders'] - $stats['pending_orders'] }} Active</span>
            @endif
          </div>
          <div class="rabez-acc-stat__value">{{ $stats['pending_orders'] }}</div>
        </button>

        <button type="button" class="rabez-acc-stat js-kpi-filter {{ $initialStatus === 'delivered' ? 'is-active' : '' }}" data-filter="delivered">
          <div class="rabez-acc-stat__top">
            <span class="rabez-acc-stat__label">Delivered</span>
            <span class="rabez-acc-stat__chip">Completed</span>
          </div>
          <div class="rabez-acc-stat__value">{{ $stats['delivered_orders'] }}</div>
        </button>

        <div class="rabez-acc-stat rabez-acc-stat--highlight">
          <div class="rabez-acc-stat__top">
            <span class="rabez-acc-stat__label">Total Spent</span>
          </div>
          <div class="rabez-acc-stat__value">&#2547; {{ number_format($stats['total_spent']) }}</div>
        </div>
      </div>

      <div class="rabez-acc-grid">
        <!-- Left Column: Smart Orders Hub -->
        <div class="rabez-acc-col">
          <section class="rabez-acc-card" id="my-orders-section">
            <div class="rabez-acc-card__head">
              <div>
                <h2 class="rabez-acc-card__title">My Orders</h2>
                <div class="rabez-acc-card__sub">Filter by status, search orders, reorder or track live courier</div>
              </div>
              <span class="rabez-acc-tag js-visible-orders-badge" style="font-size:0.8rem; white-space:nowrap;">{{ $orders->count() }} Orders</span>
            </div>

            @if($orders->isEmpty())
              <div style="text-align:center; padding:34px 16px; border:1px dashed #CBD5E1; border-radius:12px; background:#F8FAF9;">
                <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" style="margin:0 auto 12px;">
                  <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                  <line x1="3" y1="6" x2="21" y2="6"></line>
                  <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <div style="font-size:1.05rem; font-weight:800; color:#0A0C0B; margin-bottom:6px;">No orders found in your account yet</div>
                <p style="font-size:0.86rem; color:#64748B; max-width:420px; margin:0 auto 18px;">
                  Placed an order as a guest before? Save that 11-digit mobile number in your profile form &mdash; your previous orders will link automatically!
                </p>
                <a href="{{ route('shop') }}" class="rabez-btn rabez-btn--primary" style="display:inline-flex;">Browse Jerseys &rarr;</a>
              </div>
            @else
              <!-- Filter Pills & Instant Search -->
              <div class="rabez-acc-toolbar">
                <div class="rabez-acc-filter-bar" role="tablist" aria-label="Filter orders by status">
                  @php
                    $filterTabs = [
                      'all' => 'All',
                      'pending' => 'Pending',
                      'printing' => 'Printing',
                      'shipped' => 'Shipped',
                      'delivered' => 'Delivered',
                      'cancelled' => 'Cancelled',
                    ];
                  @endphp
                  @foreach($filterTabs as $fKey => $fLabel)
                    @if($fKey !== 'cancelled' || ($statusCounts['cancelled'] ?? 0) > 0)
                      <button type="button"
                        class="rabez-acc-filter-btn js-order-filter-btn {{ $initialStatus === $fKey ? 'is-active' : '' }}"
                        data-filter="{{ $fKey }}">
                        <span>{{ $fLabel }}</span>
                        <span class="rabez-acc-filter-count">{{ $statusCounts[$fKey] ?? 0 }}</span>
                      </button>
                    @endif
                  @endforeach
                </div>

                <div class="rabez-acc-search-row">
                  <div class="rabez-acc-search-wrap">
                    <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="11" cy="11" r="8"></circle>
                      <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <input type="text" id="js-acc-order-search" class="rabez-acc-search-input" placeholder="Search Order #, jersey name, custom print, address...">
                    <button type="button" id="js-acc-search-clear" class="rabez-acc-search-clear" title="Clear search">&times;</button>
                  </div>

                  <select id="js-acc-order-sort" class="rabez-acc-sort-select" aria-label="Sort orders">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="total_desc">Amount: High to Low</option>
                    <option value="total_asc">Amount: Low to High</option>
                  </select>
                </div>
              </div>

              <div class="rabez-acc-order-list" id="js-acc-order-list">
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
                    $stepVal = match($statusSlug) {
                      'printing' => 2,
                      'shipped' => 3,
                      'delivered' => 4,
                      'cancelled' => 0,
                      default => 1,
                    };
                    $searchHaystack = strtolower(implode(' ', array_filter([
                      $order->order_number,
                      $order->customer_phone,
                      $order->customer_address,
                      $order->status,
                      $order->courier,
                      $order->consignment_id,
                      $order->items->pluck('product_name')->implode(' '),
                      $order->items->pluck('custom_name')->implode(' '),
                    ])));
                    $reorderItems = $order->items->map(function ($it) {
                      return [
                        'id' => $it->product_id,
                        'variantId' => $it->variant_id,
                        'variantTitle' => $it->variant_title ?: $it->size,
                        'name' => $it->product_name,
                        'size' => $it->size ?: ($it->variant_title ?: 'L'),
                        'qty' => (int) ($it->qty ?: 1),
                        'price' => (float) $it->unit_price,
                        'customName' => $it->custom_name ?: '',
                        'customNumber' => $it->custom_number ?: '',
                        'image' => $it->image ? asset(ltrim($it->image, '/')) : asset('assets/images/products/real-madrid-home-hs.jpg'),
                      ];
                    })->values();
                    $waHelpText = rawurlencode("Hello RABEZ! Regarding my Order #{$order->order_number}: ");
                  @endphp
                  <article class="rabez-acc-order js-acc-order-card"
                    data-status="{{ $statusSlug }}"
                    data-date="{{ $order->created_at ? $order->created_at->timestamp : 0 }}"
                    data-total="{{ (float) $order->total }}"
                    data-search="{{ $searchHaystack }}">
                    <div class="rabez-acc-order__top">
                      <div class="rabez-acc-order__id">
                        <span class="rabez-acc-order__num">#{{ $order->order_number }}</span>
                        <button type="button" class="rabez-acc-copy-btn js-copy-order-id" data-order="{{ $order->order_number }}" title="Copy Order ID">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                          </svg>
                          <span>Copy</span>
                        </button>
                        <span class="rabez-acc-order__date">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '' }}</span>
                      </div>
                      <span class="rabez-acc-status {{ $badgeClass }}">{{ $order->status ?? 'Pending' }}</span>
                    </div>

                    @if($statusSlug === 'cancelled')
                      <div style="padding:8px 12px; border-radius:8px; background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; font-size:0.78rem; font-weight:700; margin-bottom:12px;">
                        This order was cancelled. Need help? Message us on WhatsApp anytime.
                      </div>
                    @else
                      <div class="rabez-acc-progress" aria-label="Order progress">
                        @php
                          $miniSteps = [
                            1 => 'Placed',
                            2 => 'Printing / QC',
                            3 => 'In Courier',
                            4 => 'Delivered',
                          ];
                        @endphp
                        @foreach($miniSteps as $sNum => $sTitle)
                          @php
                            $sCls = $stepVal > $sNum ? 'is-done' : ($stepVal === $sNum ? 'is-current' : '');
                          @endphp
                          <div class="rabez-acc-prog-step {{ $sCls }}">
                            <div class="rabez-acc-prog-bar"></div>
                            <span class="rabez-acc-prog-label">{{ $sTitle }}</span>
                          </div>
                        @endforeach
                      </div>
                    @endif

                    <div class="rabez-acc-items">
                      @foreach($order->items as $item)
                        @php
                          $variantText = $item->variant_title ?: $item->size;
                        @endphp
                        <div class="rabez-acc-item">
                          <img src="{{ asset(ltrim($item->image ?: 'assets/images/products/real-madrid-home-hs.jpg', '/')) }}" alt="{{ $item->product_name }}" onerror="this.src='/assets/images/products/real-madrid-home-hs.jpg'">
                          <div class="rabez-acc-item__info">
                            <div class="rabez-acc-item__name">{{ $item->product_name }}</div>
                            <div class="rabez-acc-item__tags">
                              <span class="rabez-acc-tag">{{ $item->variant_title ? $variantText : 'Size: ' . $variantText }}</span>
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
                      <div><strong>Delivery Address:</strong> {{ $order->customer_address }}</div>
                      <div>
                        <strong>Phone:</strong> {{ $order->customer_phone }}
                        @if(!empty($order->courier))
                          &bull; <strong>Courier:</strong> {{ $order->courier }}
                          @if(!empty($order->consignment_id) && $order->consignment_id !== 'Pending Dispatch')
                            ({{ $order->consignment_id }})
                          @endif
                        @endif
                      </div>
                    </div>

                    <div class="rabez-acc-order__foot">
                      <div class="rabez-acc-order__total">
                        Total: <span>&#2547; {{ number_format($order->total) }}</span>
                      </div>
                      <div class="rabez-acc-order__btns">
                        @if($order->items->isNotEmpty())
                          <button type="button" class="rabez-acc-mini-btn rabez-acc-mini-btn--reorder js-acc-reorder-btn" data-items='@json($reorderItems)'>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                              <polyline points="23 4 23 10 17 10"></polyline>
                              <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                            </svg>
                            <span>Reorder</span>
                          </button>
                        @endif
                        <a href="{{ route('order.confirmed', ['orderNumber' => $order->order_number]) }}" class="rabez-acc-mini-btn">
                          <span>Receipt</span>
                        </a>
                        @if(in_array($statusSlug, ['pending', 'printing', 'shipped']))
                          <a href="https://api.whatsapp.com/send/?phone={{ $waNumber }}&text={{ $waHelpText }}" target="_blank" rel="noopener" class="rabez-acc-mini-btn">
                            <span>Help</span>
                          </a>
                        @endif
                        <a href="{{ route('track-order', ['order' => $order->order_number]) }}" class="rabez-btn rabez-btn--dark" style="padding:8px 14px; font-size:0.8rem; min-height:36px;">
                          Track &amp; Invoice &rarr;
                        </a>
                      </div>
                    </div>
                  </article>
                @endforeach
              </div>

              <!-- Empty State for Active Filter / Search -->
              <div id="js-acc-empty-filter" style="display:none; text-align:center; padding:32px 16px; border:1px dashed #CBD5E1; border-radius:12px; background:#F8FAF9;">
                <div style="font-size:1rem; font-weight:800; color:#0A0C0B; margin-bottom:6px;">No matching orders found</div>
                <p style="font-size:0.84rem; color:#64748B; margin:0 0 14px;">Try switching the status filter or clearing your search query.</p>
                <button type="button" id="js-acc-reset-filters" class="rabez-btn rabez-btn--primary" style="min-height:38px; padding:0 18px; font-size:0.82rem;">Show All Orders</button>
              </div>
            @endif
          </section>
        </div>

        <!-- Right Column: Smart Tabbed Profile & Security -->
        <div class="rabez-acc-col">
          <section class="rabez-acc-card">
            <div class="rabez-acc-side-tabs" role="tablist" aria-label="Account settings tabs">
              <button type="button" class="rabez-acc-side-tab js-acc-side-tab {{ !$openSecurityTab ? 'is-active' : '' }}" data-tab="profile">
                Delivery Profile
              </button>
              <button type="button" class="rabez-acc-side-tab js-acc-side-tab {{ $openSecurityTab ? 'is-active' : '' }}" data-tab="security">
                Password &amp; Security
              </button>
            </div>

            <!-- Panel 1: Profile & Saved Address -->
            <div class="rabez-acc-side-panel js-acc-side-panel {{ !$openSecurityTab ? 'is-active' : '' }}" data-panel="profile">
              <div class="rabez-acc-card__head" style="padding-top:0;">
                <div>
                  <h2 class="rabez-acc-card__title" style="font-size:1.12rem;">Profile &amp; Delivery Address</h2>
                  <div class="rabez-acc-card__sub">Auto-fills at checkout for 1-click COD ordering</div>
                </div>
              </div>

              <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data" class="rabez-acc-form">
                @csrf
                <div class="rabez-acc-photo-box">
                  <div class="rabez-acc-photo-preview js-acc-photo-preview" data-initial="{{ $userInitial }}">
                    @if($resolvedAvatar)
                      <img src="{{ $resolvedAvatar }}" alt="{{ $user->name }}" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline';">
                      <span style="display:none;">{{ $userInitial }}</span>
                    @else
                      <span>{{ $userInitial }}</span>
                    @endif
                  </div>
                  <div style="flex:1; min-width:0;">
                    <div style="font-size:0.84rem; font-weight:800; color:#0A0C0B; margin-bottom:4px;">Profile Picture</div>
                    <div class="rabez-acc-photo-actions">
                      <label for="acc-avatar-input" class="rabez-acc-mini-btn" style="cursor:pointer;">{{ $resolvedAvatar ? 'Change Photo' : 'Upload Photo' }}</label>
                      <input type="file" id="acc-avatar-input" name="avatar" accept="image/jpeg,image/png,image/webp,image/gif" class="js-acc-avatar-input" hidden>
                      <input type="hidden" name="remove_avatar" value="0" class="js-acc-remove-avatar-flag">
                      <button type="button" class="rabez-acc-mini-btn js-acc-remove-avatar-btn" style="color:#B91C1C; border-color:#FECACA; background:#FEF2F2; {{ $resolvedAvatar ? '' : 'display:none;' }}">Remove</button>
                    </div>
                    <div class="rabez-acc-hint js-acc-photo-status" style="margin-top:4px;">JPG, PNG or WebP (max 4MB). Save below to apply.</div>
                  </div>
                </div>

                <div class="rabez-acc-field">
                  <label for="acc-name">Full Name *</label>
                  <input type="text" id="acc-name" name="name" value="{{ old('name', $user->name) }}" required maxlength="100" placeholder="Your Full Name">
                </div>

                <div class="rabez-acc-field">
                  <label for="acc-phone">Mobile Number (11 Digits) *</label>
                  <input type="tel" id="acc-phone" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="01XXXXXXXXX">
                  <span class="rabez-acc-hint">Previous guest orders on this number link automatically.</span>
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
            </div>

            <!-- Panel 2: Password & Security -->
            <div class="rabez-acc-side-panel js-acc-side-panel {{ $openSecurityTab ? 'is-active' : '' }}" data-panel="security">
              <div class="rabez-acc-card__head" style="padding-top:0;">
                <div>
                  <h2 class="rabez-acc-card__title" style="font-size:1.12rem;">Change Password</h2>
                  <div class="rabez-acc-card__sub">Keep your RABEZ account secure</div>
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

              <div class="rabez-acc-connected-box">
                <div class="rabez-acc-connected-title">Sign-In &amp; Security Overview</div>
                <div class="rabez-acc-connected-list">
                  <div class="rabez-acc-connected-row">
                    <span>Email &amp; Password</span>
                    <span class="rabez-acc-conn-badge {{ !empty($user->password) ? 'rabez-acc-conn-badge--on' : 'rabez-acc-conn-badge--off' }}">{{ !empty($user->password) ? 'Configured' : 'Not Set' }}</span>
                  </div>
                  <div class="rabez-acc-connected-row">
                    <span>Google Sign-In</span>
                    <span class="rabez-acc-conn-badge {{ !empty($user->google_id) ? 'rabez-acc-conn-badge--on' : 'rabez-acc-conn-badge--off' }}">{{ !empty($user->google_id) ? 'Connected' : 'Not Linked' }}</span>
                  </div>
                  <div class="rabez-acc-connected-row">
                    <span>Facebook Sign-In</span>
                    <span class="rabez-acc-conn-badge {{ !empty($user->facebook_id) ? 'rabez-acc-conn-badge--on' : 'rabez-acc-conn-badge--off' }}">{{ !empty($user->facebook_id) ? 'Connected' : 'Not Linked' }}</span>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Quick Shortcuts Card -->
          <section class="rabez-acc-card" style="padding:18px 20px;">
            <div style="font-size:0.9rem; font-weight:800; color:#0A0C0B; margin-bottom:10px;">Quick Shortcuts</div>
            <div style="display:grid; gap:8px;">
              <a href="{{ route('wishlist') }}" class="rabez-acc-mini-btn" style="justify-content:space-between; padding:10px 12px;">
                <span>Saved Wishlist Jerseys</span>
                <span style="color:#008A00; font-weight:800;"><span class="js-wishlist-count">0</span> items &rarr;</span>
              </a>
              <a href="{{ route('track-order') }}" class="rabez-acc-mini-btn" style="justify-content:space-between; padding:10px 12px;">
                <span>Track Another Order ID</span>
                <span>&rarr;</span>
              </a>
              <a href="https://api.whatsapp.com/send/?phone={{ $waNumber }}" target="_blank" rel="noopener" class="rabez-acc-mini-btn" style="justify-content:space-between; padding:10px 12px;">
                <span>Direct WhatsApp Support</span>
                <span style="color:#16A34A; font-weight:800;">Chat &rarr;</span>
              </a>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const orderListEl = document.getElementById("js-acc-order-list");
      const orderCards = Array.from(document.querySelectorAll(".js-acc-order-card"));
      const filterBtns = document.querySelectorAll(".js-order-filter-btn");
      const kpiFilterBtns = document.querySelectorAll(".js-kpi-filter");
      const searchInput = document.getElementById("js-acc-order-search");
      const clearSearchBtn = document.getElementById("js-acc-search-clear");
      const sortSelect = document.getElementById("js-acc-order-sort");
      const emptyFilterEl = document.getElementById("js-acc-empty-filter");
      const visibleBadge = document.querySelector(".js-visible-orders-badge");
      const resetFiltersBtn = document.getElementById("js-acc-reset-filters");

      let activeFilter = @json($initialStatus ?? 'all');
      let searchQuery = "";
      let activeSort = "newest";

      function applyOrderFilters() {
        if (!orderListEl) return;

        // Sort DOM cards first
        const sorted = [...orderCards].sort((a, b) => {
          const da = Number(a.dataset.date || 0);
          const db = Number(b.dataset.date || 0);
          const ta = Number(a.dataset.total || 0);
          const tb = Number(b.dataset.total || 0);
          if (activeSort === "oldest") return da - db;
          if (activeSort === "total_desc") return tb - ta;
          if (activeSort === "total_asc") return ta - tb;
          return db - da;
        });
        sorted.forEach(card => orderListEl.appendChild(card));

        let visibleCount = 0;
        const q = searchQuery.trim().toLowerCase();

        sorted.forEach(card => {
          const st = (card.dataset.status || "pending").toLowerCase();
          const haystack = (card.dataset.search || "").toLowerCase();

          let statusMatch = true;
          if (activeFilter === "active") {
            statusMatch = ["pending", "printing", "shipped"].includes(st);
          } else if (activeFilter !== "all") {
            statusMatch = st === activeFilter;
          }

          const searchMatch = !q || haystack.includes(q);
          const show = statusMatch && searchMatch;
          card.style.display = show ? "" : "none";
          if (show) visibleCount++;
        });

        if (emptyFilterEl) {
          emptyFilterEl.style.display = visibleCount === 0 ? "block" : "none";
        }
        if (visibleBadge) {
          const total = orderCards.length;
          visibleBadge.textContent = (activeFilter === "all" && !q)
            ? `${total} ${total === 1 ? "Order" : "Orders"}`
            : `${visibleCount} of ${total} Orders`;
        }
      }

      function setActiveStatusFilter(filterKey, scrollToOrders = false) {
        activeFilter = filterKey || "all";
        filterBtns.forEach(btn => {
          btn.classList.toggle("is-active", btn.dataset.filter === activeFilter);
        });
        kpiFilterBtns.forEach(btn => {
          btn.classList.toggle("is-active", btn.dataset.filter === activeFilter);
        });
        applyOrderFilters();

        if (scrollToOrders) {
          document.getElementById("my-orders-section")?.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      }

      filterBtns.forEach(btn => {
        btn.addEventListener("click", () => setActiveStatusFilter(btn.dataset.filter, false));
      });

      kpiFilterBtns.forEach(btn => {
        btn.addEventListener("click", () => setActiveStatusFilter(btn.dataset.filter, true));
      });

      searchInput?.addEventListener("input", () => {
        searchQuery = searchInput.value || "";
        if (clearSearchBtn) {
          clearSearchBtn.classList.toggle("is-visible", searchQuery.length > 0);
        }
        applyOrderFilters();
      });

      clearSearchBtn?.addEventListener("click", () => {
        if (searchInput) searchInput.value = "";
        searchQuery = "";
        clearSearchBtn.classList.remove("is-visible");
        applyOrderFilters();
      });

      sortSelect?.addEventListener("change", () => {
        activeSort = sortSelect.value || "newest";
        applyOrderFilters();
      });

      resetFiltersBtn?.addEventListener("click", () => {
        if (searchInput) searchInput.value = "";
        searchQuery = "";
        clearSearchBtn?.classList.remove("is-visible");
        if (sortSelect) sortSelect.value = "newest";
        activeSort = "newest";
        setActiveStatusFilter("all", false);
      });

      if (orderCards.length > 0) {
        applyOrderFilters();
      }

      // Copy Order Number Button
      document.querySelectorAll(".js-copy-order-id").forEach(btn => {
        btn.addEventListener("click", () => {
          const id = btn.dataset.order || "";
          if (!id) return;
          navigator.clipboard?.writeText(id).catch(() => {});
          btn.classList.add("is-copied");
          const span = btn.querySelector("span");
          if (span) span.textContent = "Copied!";
          setTimeout(() => {
            btn.classList.remove("is-copied");
            if (span) span.textContent = "Copy";
          }, 1500);
        });
      });

      // 1-Tap Reorder Button
      document.querySelectorAll(".js-acc-reorder-btn").forEach(btn => {
        btn.addEventListener("click", () => {
          let items = [];
          try {
            items = JSON.parse(btn.dataset.items || "[]");
          } catch (e) {}
          if (!items.length || !window.RABEZ_STORE || typeof window.RABEZ_STORE.addToCart !== "function") return;

          items.forEach((it, idx) => {
            const hasCustom = Boolean(it.customName || it.customNumber);
            const customFee = Number(window.RABEZ_CUSTOM_PRINT_FEE || 250);
            const liveProd = Array.isArray(window.RABEZ_PRODUCTS)
              ? window.RABEZ_PRODUCTS.find(p => Number(p.id) === Number(it.id))
              : null;
            let basePrice = Number(it.price || 1150);
            if (hasCustom && liveProd && liveProd.hasCustomPrint === true && basePrice > customFee) {
              basePrice = basePrice - customFee;
            }

            window.RABEZ_STORE.addToCart({
              id: it.id || (90000 + idx),
              variantId: it.variantId || null,
              variantTitle: it.variantTitle || it.size || "L",
              name: it.name || "RABEZ Jersey",
              size: it.size || it.variantTitle || "L",
              qty: it.qty || 1,
              price: basePrice,
              customName: it.customName || "",
              customNumber: it.customNumber || "",
              image: it.image || "",
              silentToast: idx < items.length - 1,
              customToast: idx === items.length - 1 ? {
                title: "Added to Bag",
                message: `${items.length} ${items.length === 1 ? "item" : "items"} from your past order added to bag.`
              } : null
            });
          });

          if (typeof window.RABEZ_STORE.openCartDrawer === "function") {
            window.RABEZ_STORE.openCartDrawer();
          }
        });
      });

      // Right Sidebar Tabs (Delivery Profile / Password & Security)
      const sideTabs = document.querySelectorAll(".js-acc-side-tab");
      const sidePanels = document.querySelectorAll(".js-acc-side-panel");
      const activateSideTab = (target) => {
        sideTabs.forEach(t => t.classList.toggle("is-active", t.dataset.tab === target));
        sidePanels.forEach(p => p.classList.toggle("is-active", p.dataset.panel === target));
      };
      sideTabs.forEach(tab => {
        tab.addEventListener("click", () => activateSideTab(tab.dataset.tab));
      });

      // Profile Picture Upload Preview & Hero Camera Trigger
      const avatarInput = document.querySelector(".js-acc-avatar-input");
      const photoPreview = document.querySelector(".js-acc-photo-preview");
      const removeFlag = document.querySelector(".js-acc-remove-avatar-flag");
      const removeBtn = document.querySelector(".js-acc-remove-avatar-btn");
      const photoStatus = document.querySelector(".js-acc-photo-status");
      const heroCamBtn = document.querySelector(".js-acc-hero-avatar-btn");

      if (heroCamBtn && avatarInput) {
        heroCamBtn.addEventListener("click", () => {
          activateSideTab("profile");
          const box = document.querySelector(".rabez-acc-photo-box");
          if (box) box.scrollIntoView({ behavior: "smooth", block: "center" });
          avatarInput.click();
        });
      }

      if (avatarInput && photoPreview) {
        avatarInput.addEventListener("change", () => {
          const file = avatarInput.files && avatarInput.files[0];
          if (!file) return;
          if (removeFlag) removeFlag.value = "0";
          if (removeBtn) removeBtn.style.display = "inline-flex";
          const reader = new FileReader();
          reader.onload = (ev) => {
            photoPreview.innerHTML = "";
            const img = document.createElement("img");
            img.src = ev.target.result;
            img.alt = "Selected profile picture";
            photoPreview.appendChild(img);
          };
          reader.readAsDataURL(file);
          if (photoStatus) {
            photoStatus.textContent = "New photo selected — click Save Profile & Address below to apply.";
            photoStatus.style.color = "#15803D";
          }
        });
      }

      if (removeBtn && photoPreview) {
        removeBtn.addEventListener("click", () => {
          if (avatarInput) avatarInput.value = "";
          if (removeFlag) removeFlag.value = "1";
          removeBtn.style.display = "none";
          const initial = photoPreview.dataset.initial || "R";
          photoPreview.innerHTML = "";
          const span = document.createElement("span");
          span.textContent = initial;
          photoPreview.appendChild(span);
          if (photoStatus) {
            photoStatus.textContent = "Photo marked for removal — click Save Profile & Address to confirm.";
            photoStatus.style.color = "#B91C1C";
          }
        });
      }
    });
  </script>
@endpush
