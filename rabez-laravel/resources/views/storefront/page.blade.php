@extends('layouts.storefront')

@section('title', ($page->meta_title ?: $page->title) . ' - RABEZ Official Store Bangladesh')
@if($page->meta_description || $page->excerpt)
  @section('meta_description', $page->meta_description ?: $page->excerpt)
@endif

@push('styles')
  <style>
    .rabez-cms-hero {
      background:
        radial-gradient(circle at 82% 18%, rgba(4, 244, 4, 0.16), transparent 44%),
        radial-gradient(circle at 14% 85%, rgba(4, 244, 4, 0.06), transparent 36%),
        #0A0C0B;
      color: #FFFFFF;
      padding: 46px 0 58px;
      border-bottom: 1px solid #1E2421;
    }

    .rabez-cms-breadcrumb {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px;
      font-size: 0.8rem;
      font-weight: 600;
      color: #93A099;
      margin-bottom: 14px;
    }

    .rabez-cms-breadcrumb a {
      color: #D2DCD7;
      text-decoration: none;
      transition: color 0.15s ease;
    }

    .rabez-cms-breadcrumb a:hover {
      color: #04F404;
    }

    .rabez-cms-title-row {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 10px;
    }

    .rabez-cms-title {
      margin: 0;
      font-family: var(--font-heading, 'Barlow Condensed', sans-serif);
      font-size: clamp(2rem, 4vw, 2.85rem);
      font-weight: 800;
      line-height: 1.04;
      letter-spacing: 0.02em;
      text-transform: uppercase;
      color: #FFFFFF;
    }

    .rabez-cms-draft-pill {
      display: inline-flex;
      align-items: center;
      padding: 4px 10px;
      border-radius: 999px;
      background: rgba(245, 158, 11, 0.16);
      border: 1px solid rgba(245, 158, 11, 0.45);
      color: #FCD34D;
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }

    .rabez-cms-excerpt {
      margin: 0;
      max-width: 720px;
      color: #BDC7C1;
      font-size: 0.98rem;
      line-height: 1.6;
    }

    .rabez-cms-meta {
      margin-top: 14px;
      font-size: 0.78rem;
      font-weight: 600;
      color: #7E8B84;
    }

    .rabez-cms-body-wrap {
      background: #F4F6F5;
      padding: 0 0 64px;
    }

    .rabez-cms-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 310px;
      gap: 24px;
      margin-top: -28px;
      align-items: start;
    }

    .rabez-cms-article {
      background: #FFFFFF;
      border: 1px solid #DCE3DF;
      border-radius: 16px;
      padding: 32px 34px;
      box-shadow: 0 18px 42px rgba(10, 12, 11, 0.06);
      color: #1D2521;
      font-size: 0.98rem;
      line-height: 1.75;
      overflow-wrap: anywhere;
    }

    .rabez-cms-article > *:first-child {
      margin-top: 0;
    }

    .rabez-cms-article h1,
    .rabez-cms-article h2,
    .rabez-cms-article h3,
    .rabez-cms-article h4 {
      font-family: var(--font-heading, 'Barlow Condensed', sans-serif);
      color: #0B110E;
      font-weight: 800;
      letter-spacing: 0.015em;
      margin: 1.65em 0 0.55em;
      line-height: 1.2;
    }

    .rabez-cms-article h2 {
      font-size: 1.55rem;
      padding-bottom: 8px;
      border-bottom: 1px solid #EAEFED;
    }

    .rabez-cms-article h3 {
      font-size: 1.25rem;
    }

    .rabez-cms-article p {
      margin: 0 0 1.05em;
      color: #2B3430;
    }

    .rabez-cms-article ul,
    .rabez-cms-article ol {
      margin: 0 0 1.15em 1.25rem;
      padding: 0;
      color: #2B3430;
    }

    .rabez-cms-article li {
      margin-bottom: 0.45em;
    }

    .rabez-cms-article a {
      color: #0E7A20;
      font-weight: 700;
      text-decoration: underline;
      text-underline-offset: 2px;
    }

    .rabez-cms-article blockquote {
      margin: 1.2em 0;
      padding: 14px 18px;
      background: #F4FAF5;
      border-left: 4px solid #04F404;
      border-radius: 8px;
      color: #17201B;
      font-weight: 500;
    }

    .rabez-cms-article img {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
      margin: 12px 0;
    }

    .rabez-cms-article table {
      width: 100%;
      border-collapse: collapse;
      margin: 16px 0;
      display: block;
      overflow-x: auto;
    }

    .rabez-cms-article th,
    .rabez-cms-article td {
      border: 1px solid #DDE5E1;
      padding: 10px 12px;
      text-align: left;
      font-size: 0.9rem;
    }

    .rabez-cms-article th {
      background: #0F1411;
      color: #FFFFFF;
      font-weight: 700;
    }

    .rabez-cms-sidebar {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .rabez-cms-sidecard {
      background: #FFFFFF;
      border: 1px solid #DCE3DF;
      border-radius: 14px;
      padding: 20px;
      box-shadow: 0 10px 26px rgba(10, 12, 11, 0.04);
    }

    .rabez-cms-sidecard--dark {
      background: #0A0C0B;
      border-color: #1E2421;
      color: #FFFFFF;
    }

    .rabez-cms-sidecard h3 {
      margin: 0 0 12px;
      font-family: var(--font-heading, 'Barlow Condensed', sans-serif);
      font-size: 1.15rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      color: #0B110E;
    }

    .rabez-cms-sidecard--dark h3 {
      color: #FFFFFF;
    }

    .rabez-cms-pagelist {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .rabez-cms-pagelist a {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 9px 12px;
      border-radius: 8px;
      background: #F6F8F7;
      border: 1px solid #E4EAE6;
      color: #18211C;
      font-size: 0.86rem;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.16s ease;
    }

    .rabez-cms-pagelist a:hover {
      border-color: #04F404;
      background: #F0FFF0;
      color: #09110D;
    }

    .rabez-cms-cta-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      padding: 11px 16px;
      border-radius: 9px;
      background: #04F404;
      color: #09100C;
      font-weight: 800;
      font-size: 0.88rem;
      text-decoration: none;
      transition: filter 0.16s ease;
      margin-top: 12px;
    }

    .rabez-cms-cta-btn:hover {
      filter: brightness(0.95);
    }

    .rabez-cms-cta-btn--outline {
      background: transparent;
      border: 1px solid #2A342F;
      color: #FFFFFF;
      margin-top: 8px;
    }

    @media (max-width: 960px) {
      .rabez-cms-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 640px) {
      .rabez-cms-hero {
        padding: 34px 0 44px;
      }
      .rabez-cms-article {
        padding: 22px 18px;
        border-radius: 12px;
        font-size: 0.94rem;
      }
      .rabez-cms-article h2 {
        font-size: 1.35rem;
      }
    }
  </style>
@endpush

@section('content')
  <section class="rabez-cms-hero">
    <div class="rabez-container">
      <nav class="rabez-cms-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <span>{{ $page->title }}</span>
      </nav>

      <div class="rabez-cms-title-row">
        <h1 class="rabez-cms-title">{{ $page->title }}</h1>
        @if(!empty($isPreview))
          <span class="rabez-cms-draft-pill">Draft Preview</span>
        @endif
      </div>

      @if($page->excerpt)
        <p class="rabez-cms-excerpt">{{ $page->excerpt }}</p>
      @endif

      <div class="rabez-cms-meta">
        Last updated {{ ($page->updated_at ?? $page->created_at)?->format('F j, Y') }}
      </div>
    </div>
  </section>

  <section class="rabez-cms-body-wrap">
    <div class="rabez-container">
      <div class="rabez-cms-grid">
        <article class="rabez-cms-article">
          @if(!empty($safeContent))
            {!! $safeContent !!}
          @else
            <p>This page has no content yet. Update it anytime from the RABEZ Admin Dashboard under Pages.</p>
          @endif
        </article>

        <aside class="rabez-cms-sidebar">
          @if(isset($relatedPages) && $relatedPages->isNotEmpty())
            <div class="rabez-cms-sidecard">
              <h3>Store Policies &amp; Info</h3>
              <ul class="rabez-cms-pagelist">
                @foreach($relatedPages as $other)
                  <li>
                    <a href="{{ url('/' . ltrim($other->slug, '/')) }}">
                      <span>{{ $other->title }}</span>
                      <span>&rarr;</span>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="rabez-cms-sidecard rabez-cms-sidecard--dark">
            <h3>Need Quick Support?</h3>
            <p style="margin:0; font-size:0.86rem; line-height:1.55; color:#B7C2BC;">
              Questions about your order, sizing, or custom name &amp; number printing? Chat directly with RABEZ support.
            </p>
            <a href="https://api.whatsapp.com/send/?phone=8801612359652" target="_blank" rel="noopener" class="rabez-cms-cta-btn">
              WhatsApp: +880 1612-359652
            </a>
            <a href="{{ route('shop') }}" class="rabez-cms-cta-btn rabez-cms-cta-btn--outline">
              Browse All Jerseys
            </a>
          </div>
        </aside>
      </div>
    </div>
  </section>
@endsection
