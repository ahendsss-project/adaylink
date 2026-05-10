{{-- Template: Luxury (Premium Tier) — Tour Detail View --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
    $tourUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $tour->slug : '/tour/' . $tour->slug;
    $primaryColor = $settings->primary_color ?? '#C8883A';
    $secondaryColor = $settings->secondary_color ?? '#333333';
    $fontHeading = $settings->font_heading ?? 'Playfair Display';
    $fontBody = $settings->font_body ?? 'DM Sans';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale ?? 'id' }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $tour->title }} — {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
  @if ($settings)
      <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($tour->description ?? ''), 160) }}" />
  @endif
  <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:ital,wght@0,400;0,700;1,400&family={{ urlencode($fontBody) }}:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --cream: #F5F0E8;
      --sand: #E8DCC8;
      --brown: #8B6914;
      --dark: #1A1208;
      --green: #2D5016;
      --accent: {{ $primaryColor }};
      --white: #FFFFFF;
      --gray: #6B6B6B;
      --secondary: {{ $secondaryColor }};
      --font-heading: '{{ $fontHeading }}', serif;
      --font-body: '{{ $fontBody }}', sans-serif;
    }

    html { scroll-behavior: smooth; }
    body {
      font-family: var(--font-body);
      background: var(--cream);
      color: var(--dark);
      overflow-x: hidden;
    }

    /* ── NAVBAR ── */
    nav {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 60px;
      background: rgba(26, 18, 8, 0.92);
      backdrop-filter: blur(12px);
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }

    .logo-icon {
      width: 38px; height: 38px;
      background: var(--accent);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      overflow: hidden;
    }

    .logo-icon img {
      width: 100%; height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }

    .logo-text {
      font-family: var(--font-heading);
      font-size: 1.3rem;
      color: var(--white);
    }

    .logo-text span { color: var(--accent); }

    nav ul {
      list-style: none;
      display: flex;
      gap: 36px;
    }

    nav ul a {
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      font-size: 0.88rem;
      font-weight: 500;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      transition: color 0.2s;
    }

    nav ul a:hover { color: var(--accent); }

    .nav-cta {
      background: var(--accent);
      color: var(--white) !important;
      padding: 9px 22px;
      border-radius: 30px;
      transition: background 0.2s, transform 0.2s !important;
    }

    .nav-cta:hover {
      background: var(--brown) !important;
      transform: translateY(-1px);
    }

    /* Mobile hamburger */
    .hamburger {
      display: none;
      background: none;
      border: none;
      color: var(--white);
      font-size: 1.5rem;
      cursor: pointer;
      padding: 4px;
    }

    .mobile-menu {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 99;
      background: rgba(26, 18, 8, 0.97);
      backdrop-filter: blur(16px);
      padding: 80px 24px 30px;
      list-style: none;
    }

    .mobile-menu a {
      display: block;
      color: rgba(255,255,255,0.85);
      text-decoration: none;
      font-size: 1rem;
      font-weight: 500;
      letter-spacing: 0.8px;
      padding: 14px 0;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      transition: color 0.2s;
    }

    .mobile-menu a:hover { color: var(--accent); }

    /* ── HERO DETAIL ── */
    .hero-detail {
      position: relative;
      height: 60vh;
      min-height: 460px;
      display: flex;
      align-items: flex-end;
      overflow: hidden;
    }

    .hero-detail-bg {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .hero-detail-content {
      position: relative;
      z-index: 2;
      padding: 0 60px 60px;
      width: 100%;
      animation: fadeUp 0.9s ease-out both;
    }

    @keyframes fadeUp {
      from { opacity:0; transform: translateY(30px); }
      to   { opacity:1; transform: translateY(0); }
    }

    .breadcrumb {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.8rem;
      color: rgba(255,255,255,0.6);
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .breadcrumb a {
      color: rgba(255,255,255,0.6);
      text-decoration: none;
      transition: color 0.2s;
    }
    .breadcrumb a:hover { color: var(--accent); }
    .breadcrumb span { color: rgba(255,255,255,0.3); }

    .hero-detail h1 {
      font-family: var(--font-heading);
      font-size: clamp(2rem, 4vw, 3.2rem);
      color: var(--white);
      line-height: 1.15;
      margin-bottom: 16px;
    }

    .hero-badges {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(8px);
      color: var(--white);
      font-size: 0.82rem;
      font-weight: 500;
      padding: 7px 16px;
      border-radius: 30px;
      border: 1px solid rgba(255,255,255,0.15);
    }

    .hero-price-box {
      position: absolute;
      right: 60px;
      bottom: 60px;
      z-index: 3;
      background: rgba(26, 18, 8, 0.85);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(200, 136, 58, 0.3);
      border-radius: 20px;
      padding: 28px 32px;
      text-align: center;
      color: var(--white);
      animation: fadeUp 1.1s ease-out both;
    }

    .hero-price-box .label {
      font-size: 0.78rem;
      color: rgba(255,255,255,0.6);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 6px;
    }

    .hero-price-box .price {
      font-family: var(--font-heading);
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--accent);
    }

    /* ── MAIN LAYOUT ── */
    .detail-layout {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 40px;
      max-width: 1240px;
      margin: 0 auto;
      padding: 60px 60px 80px;
    }

    .detail-main { min-width: 0; }
    .detail-sidebar { position: relative; }

    /* ── CONTENT SECTIONS ── */
    .detail-section {
      margin-bottom: 48px;
    }

    .detail-section-title {
      font-family: var(--font-heading);
      font-size: 1.4rem;
      color: var(--dark);
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 2px solid var(--sand);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .detail-section-title .icon {
      font-size: 1.2rem;
    }

    .description-text {
      font-size: 0.95rem;
      line-height: 1.85;
      color: #444;
      white-space: pre-line;
    }

    /* ── ITINERARY ── */
    .itinerary-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .itinerary-item {
      display: flex;
      gap: 16px;
      background: var(--white);
      border-radius: 14px;
      padding: 20px;
      border: 1px solid var(--sand);
      transition: border-color 0.2s;
    }

    .itinerary-item:hover {
      border-color: var(--accent);
    }

    .itinerary-day {
      flex-shrink: 0;
      width: 52px;
      height: 52px;
      background: var(--dark);
      color: var(--accent);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-heading);
      font-weight: 700;
      font-size: 0.85rem;
    }

    .itinerary-content {
      flex: 1;
      min-width: 0;
    }

    .itinerary-content h4 {
      font-family: var(--font-heading);
      font-size: 1rem;
      margin-bottom: 6px;
      color: var(--dark);
    }

    .itinerary-content p {
      font-size: 0.88rem;
      color: var(--gray);
      line-height: 1.65;
    }

    /* ── INCLUDES / EXCLUDES ── */
    .check-list {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
    }

    .check-list-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.9rem;
      color: #444;
      padding: 8px 0;
    }

    .check-icon {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      flex-shrink: 0;
    }

    .check-icon.include {
      background: #ECFDF5;
      color: #059669;
    }

    .check-icon.exclude {
      background: #FEF2F2;
      color: #DC2626;
    }

    /* ── NOTES ── */
    .notes-box {
      background: #FFFBEB;
      border: 1px solid #FDE68A;
      border-radius: 14px;
      padding: 24px;
      font-size: 0.9rem;
      line-height: 1.75;
      color: #92400E;
      white-space: pre-line;
    }

    /* ── GALLERY ── */
    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }

    .gallery-thumb {
      border-radius: 12px;
      overflow: hidden;
      cursor: pointer;
      position: relative;
      aspect-ratio: 4/3;
    }

    .gallery-thumb img {
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform 0.4s;
    }

    .gallery-thumb:hover img { transform: scale(1.08); }

    .gallery-thumb-overlay {
      position: absolute;
      inset: 0;
      background: rgba(26,18,8,0.3);
      opacity: 0;
      transition: opacity 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
    }

    .gallery-thumb:hover .gallery-thumb-overlay { opacity: 1; }

    /* ── SIDEBAR ── */
    .sidebar-card {
      background: var(--white);
      border-radius: 20px;
      padding: 30px;
      margin-bottom: 24px;
      border: 1px solid var(--sand);
    }

    .sidebar-card.sticky {
      position: sticky;
      top: 100px;
    }

    .sidebar-price-label {
      font-size: 0.8rem;
      color: var(--gray);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 4px;
    }

    .sidebar-price {
      font-family: var(--font-heading);
      font-size: 2rem;
      font-weight: 700;
      color: var(--accent);
      margin-bottom: 6px;
    }

    .sidebar-price-note {
      font-size: 0.8rem;
      color: var(--gray);
      margin-bottom: 24px;
    }

    .sidebar-info {
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin-bottom: 24px;
    }

    .sidebar-info-item {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 0.9rem;
      color: #444;
    }

    .sidebar-info-icon {
      width: 36px;
      height: 36px;
      background: var(--cream);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }

    .btn-wa {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      width: 100%;
      background: #25D366;
      color: white;
      text-decoration: none;
      padding: 16px 24px;
      border-radius: 40px;
      font-size: 0.95rem;
      font-weight: 500;
      transition: background 0.2s, transform 0.2s;
      border: none;
      cursor: pointer;
    }

    .btn-wa:hover {
      background: #1DA851;
      transform: translateY(-2px);
    }

    .sidebar-highlights {
      list-style: none;
    }

    .sidebar-highlights li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 0.88rem;
      color: #444;
      padding: 8px 0;
      border-bottom: 1px solid var(--sand);
    }

    .sidebar-highlights li:last-child { border-bottom: none; }

    .sidebar-highlights .hl-icon {
      color: var(--accent);
      flex-shrink: 0;
      margin-top: 2px;
    }

    /* ── RELATED TOURS ── */
    .related-section {
      background: var(--white);
      padding: 80px 60px;
    }

    .related-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
      max-width: 1240px;
      margin: 0 auto;
    }

    .tour-card {
      border-radius: 16px;
      overflow: hidden;
      background: var(--cream);
      transition: transform 0.3s, box-shadow 0.3s;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .tour-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 50px rgba(26,18,8,0.15);
    }

    .tour-img {
      position: relative;
      height: 200px;
      overflow: hidden;
    }

    .tour-img img {
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform 0.4s;
    }

    .tour-card:hover .tour-img img { transform: scale(1.06); }

    .tour-badge {
      position: absolute;
      top: 14px; left: 14px;
      background: var(--accent);
      color: var(--white);
      font-size: 0.7rem;
      font-weight: 500;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      padding: 5px 12px;
      border-radius: 20px;
    }

    .tour-body { padding: 22px; }

    .tour-meta {
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 0.8rem;
      color: var(--gray);
      margin-bottom: 10px;
    }

    .tour-meta span { display: flex; align-items: center; gap: 5px; }

    .tour-body h3 {
      font-family: var(--font-heading);
      font-size: 1.15rem;
      margin-bottom: 8px;
      line-height: 1.3;
    }

    .tour-body p {
      font-size: 0.85rem;
      color: var(--gray);
      line-height: 1.6;
      margin-bottom: 18px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .tour-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .tour-price .from { font-size: 0.75rem; color: var(--gray); }
    .tour-price .amount {
      font-family: var(--font-heading);
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--accent);
    }

    .tour-btn {
      background: var(--dark);
      color: var(--white);
      text-decoration: none;
      padding: 9px 20px;
      border-radius: 30px;
      font-size: 0.8rem;
      font-weight: 500;
      transition: background 0.2s;
    }

    .tour-btn:hover { background: var(--accent); }

    /* ── FOOTER ── */
    footer {
      background: var(--dark);
      color: rgba(255,255,255,0.65);
      padding: 50px 60px 28px;
    }

    .footer-top {
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr 1fr;
      gap: 50px;
      margin-bottom: 50px;
    }

    .footer-brand .logo-text { color: var(--white); }

    .footer-desc {
      font-size: 0.85rem;
      line-height: 1.7;
      margin-top: 14px;
    }

    .footer-col h4 {
      color: var(--white);
      font-size: 0.88rem;
      font-weight: 500;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 18px;
    }

    .footer-col ul { list-style: none; }
    .footer-col ul li { margin-bottom: 10px; }
    .footer-col ul a {
      color: rgba(255,255,255,0.55);
      text-decoration: none;
      font-size: 0.85rem;
      transition: color 0.2s;
    }
    .footer-col ul a:hover { color: var(--accent); }

    .footer-bottom {
      border-top: 1px solid rgba(255,255,255,0.1);
      padding-top: 26px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.8rem;
    }

    /* ── LIGHTBOX ── */
    .lightbox-overlay {
      position: fixed;
      inset: 0;
      z-index: 200;
      background: rgba(0,0,0,0.92);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .lightbox-overlay img {
      max-width: 90vw;
      max-height: 85vh;
      object-fit: contain;
      border-radius: 8px;
    }

    .lightbox-close {
      position: absolute;
      top: 20px; right: 24px;
      background: none;
      border: none;
      color: white;
      font-size: 2rem;
      cursor: pointer;
      z-index: 10;
    }

    .lightbox-prev, .lightbox-next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255,255,255,0.15);
      border: none;
      color: white;
      font-size: 1.5rem;
      padding: 12px 16px;
      border-radius: 50%;
      cursor: pointer;
      z-index: 10;
      transition: background 0.2s;
    }

    .lightbox-prev { left: 20px; }
    .lightbox-next { right: 20px; }
    .lightbox-prev:hover, .lightbox-next:hover { background: rgba(255,255,255,0.3); }

    .lightbox-counter {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      color: rgba(255,255,255,0.5);
      font-size: 0.85rem;
    }

    /* ── SOCIAL SHARE FAB ── */
    .share-fab-btn {
      position: fixed;
      bottom: 24px;
      left: 24px;
      z-index: 99;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: var(--dark);
      color: var(--white);
      border: none;
      font-size: 1.2rem;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.2s, background 0.2s;
    }

    .share-fab-btn:hover { transform: scale(1.1); background: var(--accent); }

    .share-options {
      position: fixed;
      bottom: 82px;
      left: 24px;
      z-index: 99;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .share-options a, .share-options button {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-decoration: none;
      border: none;
      cursor: pointer;
      font-size: 1rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      transition: transform 0.2s;
    }

    .share-options a:hover, .share-options button:hover { transform: scale(1.1); }

    /* ── SECTION LABEL ── */
    .section-label {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-size: 0.78rem;
      font-weight: 500;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 14px;
    }

    .section-label::before {
      content: '';
      width: 28px; height: 1.5px;
      background: var(--accent);
    }

    .section-title {
      font-family: var(--font-heading);
      font-size: clamp(1.8rem, 3.5vw, 2.8rem);
      line-height: 1.2;
      color: var(--dark);
    }

    .section-title em { font-style: italic; color: var(--accent); }

    .section-head {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 50px;
      flex-wrap: wrap;
      gap: 20px;
    }

    @media (max-width: 960px) {
      nav { padding: 18px 24px; }
      nav ul { display: none; }
      .hamburger { display: block; }
      .mobile-menu.open { display: block; }
      .hero-detail-content { padding: 0 24px 50px; }
      .hero-price-box {
        position: relative;
        right: auto;
        bottom: auto;
        margin: 16px 24px 0;
        display: inline-block;
      }
      .detail-layout {
        grid-template-columns: 1fr;
        padding: 40px 24px 60px;
      }
      .sidebar-card.sticky { position: relative; top: auto; }
      .check-list { grid-template-columns: 1fr; }
      .gallery-grid { grid-template-columns: repeat(2, 1fr); }
      .related-section { padding: 60px 24px; }
      .related-grid { grid-template-columns: 1fr; }
      footer { padding: 40px 24px 24px; }
      .footer-top { grid-template-columns: 1fr 1fr; }
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav x-data="{ mobileOpen: false }">
  <a href="{{ $homeUrl }}" class="logo">
    <div class="logo-icon">
      @if($website->logo_url)
        <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
      @else
        <i class="fas fa-umbrella-beach"></i>
      @endif
    </div>
    <span class="logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
  </a>
  <ul>
    <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
    @foreach($pages as $p)
      <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
    @endforeach
    @if($website->contact_whatsapp)
      <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="nav-cta">{{ __('messages.book_now') }}</a></li>
    @endif
  </ul>
<x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
  <button class="hamburger" @click="mobileOpen = !mobileOpen" aria-label="Toggle menu">
    <span x-show="!mobileOpen"><i class="fas fa-bars"></i></span>
    <span x-show="mobileOpen"><i class="fas fa-times"></i></span>
  </button>
</nav>

<!-- Mobile Menu -->
<ul class="mobile-menu" :class="{ 'open': mobileOpen }" x-show="mobileOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click.away="mobileOpen = false">
  <li><a href="{{ $homeUrl }}" @click="mobileOpen = false">{{ __('messages.home') }}</a></li>
  @foreach($pages as $p)
    <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" @click="mobileOpen = false">{{ $p->title }}</a></li>
  @endforeach
  @if($website->contact_whatsapp)
    <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="mobileOpen = false"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
  @endif
</ul>

<!-- HERO DETAIL -->
<div class="hero-detail">
  <div class="hero-detail-bg" style="background-image: linear-gradient(to top, rgba(26,18,8,0.9) 30%, rgba(26,18,8,0.2) 70%, transparent), url('{{ $tour->thumbnail_url ?? 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1800&q=80' }}');"></div>
  <div class="hero-detail-content">
    <div class="breadcrumb">
      <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
      <span>›</span>
      <a href="{{ $homeUrl }}#tours">{{ __('messages.tours') }}</a>
      <span>›</span>
      <span style="color:rgba(255,255,255,0.85)">{{ $tour->title }}</span>
    </div>
    <h1>{{ $tour->title }}</h1>
    <div class="hero-badges">
      @if($tour->duration_text)
        <div class="hero-badge"><i class="far fa-clock"></i> {{ $tour->duration_text }}</div>
      @endif
      @if($tour->is_featured)
        <div class="hero-badge"><i class="fas fa-star"></i> Best Seller</div>
      @endif
    </div>
  </div>
  @if($tour->price_start_from)
    <div class="hero-price-box">
      <div class="label">{{ __('messages.starting_from') }}</div>
      <div class="price">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
    </div>
  @endif
</div>

<!-- MAIN CONTENT -->
<div class="detail-layout">
  <div class="detail-main">

    {{-- Description --}}
    @if($tour->description)
    <div class="detail-section">
      <h2 class="detail-section-title"><span class="icon"><i class="fas fa-pen"></i></span> Deskripsi</h2>
      <div class="description-text">{{ $tour->description }}</div>
    </div>
    @endif

    {{-- Itinerary --}}
    @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
    <div class="detail-section">
      <h2 class="detail-section-title"><span class="icon"><i class="fas fa-map"></i></span> Itinerary</h2>
      <div class="itinerary-list">
        @foreach($tour->itinerary as $i => $item)
          <div class="itinerary-item">
            <div class="itinerary-day">D{{ $i + 1 }}</div>
            <div class="itinerary-content">
              @if(is_array($item))
                <h4>{{ $item['title'] ?? 'Hari ' . ($i + 1) }}</h4>
                <p>{{ $item['description'] ?? '' }}</p>
              @else
                <h4>Hari {{ $i + 1 }}</h4>
                <p>{{ $item }}</p>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Includes --}}
    @if(is_array($tour->includes) && count($tour->includes) > 0)
    <div class="detail-section">
      <h2 class="detail-section-title"><span class="icon"><i class="fas fa-check-circle"></i></span> Termasuk</h2>
      <div class="check-list">
        @foreach($tour->includes as $item)
          <div class="check-list-item">
            <div class="check-icon include"><i class="fas fa-check"></i></div>
            <span>{{ $item }}</span>
          </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Excludes --}}
    @if(is_array($tour->excludes) && count($tour->excludes) > 0)
    <div class="detail-section">
      <h2 class="detail-section-title"><span class="icon"><i class="fas fa-times-circle"></i></span> Tidak Termasuk</h2>
      <div class="check-list">
        @foreach($tour->excludes as $item)
          <div class="check-list-item">
            <div class="check-icon exclude"><i class="fas fa-times"></i></div>
            <span>{{ $item }}</span>
          </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Notes --}}
    @if($tour->notes)
    <div class="detail-section">
      <h2 class="detail-section-title"><span class="icon"><i class="fas fa-clipboard-list"></i></span> Catatan</h2>
      <div class="notes-box">{{ $tour->notes }}</div>
    </div>
    @endif

    {{-- Gallery --}}
    @if($tour->images->count() > 0)
    <div class="detail-section" x-data="tourGallery(@js($tour->images->values()->all()))" x-init="init()">
      <h2 class="detail-section-title"><span class="icon"><i class="fas fa-camera"></i></span> Galeri Foto</h2>
      <div class="gallery-grid">
        @foreach($tour->images as $index => $img)
          <div class="gallery-thumb" @click="open({{ $index }})">
            <img src="{{ $img->url }}" alt="{{ $img->alt_text ?? $tour->title }}"/>
            <div class="gallery-thumb-overlay"><i class="fas fa-search-plus"></i></div>
          </div>
        @endforeach
      </div>

      {{-- Lightbox --}}
      <template x-if="isOpen">
        <div class="lightbox-overlay" @click.self="close()" @keydown.escape="close()"
             x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
          <button class="lightbox-close" @click="close()"><i class="fas fa-times"></i></button>
          <button class="lightbox-prev" @click.stop="prev()"><i class="fas fa-chevron-left"></i></button>
          <button class="lightbox-next" @click.stop="next()"><i class="fas fa-chevron-right"></i></button>
          <img :src="images[currentIndex]?.url || ''" :alt="images[currentIndex]?.alt_text || ''" @click.stop/>
          <div class="lightbox-counter"><span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span></div>
        </div>
      </template>
    </div>
    @endif

  </div>

  {{-- Sidebar --}}
  <div class="detail-sidebar">
    <div class="sidebar-card sticky">
      @if($tour->price_start_from)
        <div class="sidebar-price-label">{{ __('messages.starting_from') }}</div>
        <div class="sidebar-price">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
        <div class="sidebar-price-note">Per orang (harga dapat berubah)</div>
      @endif

      <div class="sidebar-info">
        @if($tour->duration_text)
          <div class="sidebar-info-item">
            <div class="sidebar-info-icon"><i class="far fa-clock"></i></div>
            <span>{{ $tour->duration_text }}</span>
          </div>
        @endif
        @if(is_array($tour->includes) && count($tour->includes) > 0)
          <div class="sidebar-info-item">
            <div class="sidebar-info-icon"><i class="fas fa-check-circle"></i></div>
            <span>{{ count($tour->includes) }} item termasuk</span>
          </div>
        @endif
        @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
          <div class="sidebar-info-item">
            <div class="sidebar-info-icon"><i class="fas fa-calendar-alt"></i></div>
            <span>{{ count($tour->itinerary) }} hari perjalanan</span>
          </div>
        @endif
      </div>

      @if($website->contact_whatsapp)
        @php $waMsg = "Halo, saya tertarik dengan paket tour *{$tour->title}*."; @endphp
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode($waMsg) }}" target="_blank" class="btn-wa">
          <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
        </a>
      @endif
    </div>

    {{-- Highlights card --}}
    @if(is_array($tour->includes) && count($tour->includes) > 0)
    <div class="sidebar-card">
      <h4 style="font-family:var(--font-heading);font-size:1.05rem;margin-bottom:16px;"><i class="fas fa-sparkles"></i> Highlight</h4>
      <ul class="sidebar-highlights">
        @foreach(array_slice($tour->includes, 0, 5) as $item)
          <li><span class="hl-icon"><i class="fas fa-star" style="font-size:0.7em"></i></span> {{ $item }}</li>
        @endforeach
      </ul>
    </div>
    @endif
  </div>
</div>

{{-- RELATED TOURS --}}
@if($relatedTours->count() > 0)
<section class="related-section">
  <div class="section-head" style="max-width:1240px;margin:0 auto 50px;">
    <div>
      <div class="section-label">{{ __('messages.related_tours') }}</div>
      <h2 class="section-title">Paket Tour <em>Lainnya</em></h2>
    </div>
  </div>
  <div class="related-grid">
    @foreach($relatedTours as $related)
      <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $related->slug : '/tour/' . $related->slug }}" class="tour-card">
        <div class="tour-img">
          @if($related->thumbnail_url)
            <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}"/>
          @else
            <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=600&q=80" alt="{{ $related->title }}"/>
          @endif
          @if($related->is_featured)
            <div class="tour-badge">Best Seller</div>
          @endif
        </div>
        <div class="tour-body">
          <div class="tour-meta">
            @if($related->duration_text)
              <span><i class="far fa-clock"></i> {{ $related->duration_text }}</span>
            @endif
          </div>
          <h3>{{ $related->title }}</h3>
          @if($related->description)
            <p>{{ $related->description }}</p>
          @endif
          <div class="tour-footer">
            @if($related->price_start_from)
              <div class="tour-price">
                <div class="from">{{ __('messages.starting_from') }}</div>
                <div class="amount">Rp {{ number_format($related->price_start_from, 0, ',', '.') }}</div>
              </div>
            @endif
            <span class="tour-btn">Detail</span>
          </div>
        </div>
      </a>
    @endforeach
  </div>
</section>
@endif

<!-- FOOTER -->
<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <div class="logo">
        <div class="logo-icon">
          @if($website->logo_url)
            <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
          @else
            <i class="fas fa-umbrella-beach"></i>
          @endif
        </div>
        <span class="logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
      </div>
      <p class="footer-desc">{{ $settings->description ?? 'Powered by adaylink — platform website untuk driver dan agen wisata.' }}</p>
    </div>
    <div class="footer-col">
      <h4>Halaman</h4>
      <ul>
        @foreach($pages as $p)
          <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
        @endforeach
      </ul>
    </div>
    <div class="footer-col">
      <h4>Kontak</h4>
      <ul>
        @if($settings->phone ?? null)
          <li><a href="tel:{{ $settings->phone }}"><i class="fas fa-phone"></i> {{ $settings->phone }}</a></li>
        @endif
        @if($settings->email ?? null)
          <li><a href="mailto:{{ $settings->email }}"><i class="fas fa-envelope"></i> {{ $settings->email }}</a></li>
        @endif
        @if($settings->address ?? null)
          <li><a href="#"><i class="fas fa-map-marker-alt"></i> {{ $settings->address }}</a></li>
        @endif
        @if($settings->social_instagram ?? null)
          <li><a href="{{ $settings->social_instagram }}" target="_blank">Instagram</a></li>
        @endif
        @if($website->contact_whatsapp)
          <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank">WhatsApp</a></li>
        @endif
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}. All rights reserved.</span>
    <span>Powered by <span style="color:var(--accent)">adaylink</span></span>
  </div>
</footer>

{{-- Floating WhatsApp --}}
@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
     target="_blank" rel="noopener noreferrer"
     style="position:fixed;bottom:24px;right:24px;z-index:99;background:#25D366;color:white;width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,0.2);text-decoration:none;font-size:24px;"
     x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(20px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},1000)">
    <i class="fab fa-whatsapp" style="font-size:24px;"></i>
  </a>
@endif

{{-- Social Share FAB --}}
@if($features['social_share'] ?? false)
  <div x-data="socialShare()" x-init="init()" style="position:fixed;bottom:24px;left:24px;z-index:99;">
    <div class="share-options" x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95 translate-y-2">
      <a :href="'https://wa.me/?text=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#25D366;"><i class="fab fa-whatsapp"></i></a>
      <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#1877F2;"><i class="fab fa-facebook-f"></i></a>
      <a :href="'https://twitter.com/intent/tweet?url=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#000;"><i class="fab fa-x-twitter"></i></a>
      <button @click="copyLink()" style="background:var(--dark);" :style="copied ? 'background:#059669' : ''">
        <i class="fas" :class="copied ? 'fa-check' : 'fa-link'"></i>
      </button>
    </div>
    <button @click="isOpen = !isOpen" class="share-fab-btn"><i class="fas fa-share-alt"></i></button>
  </div>
@endif

{{-- Alpine.js Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
  function tourGallery(images) {
    return {
      images,
      isOpen: false,
      currentIndex: 0,
      init() {
        document.addEventListener('keydown', (e) => {
          if (!this.isOpen) return;
          if (e.key === 'Escape') this.close();
          if (e.key === 'ArrowLeft') this.prev();
          if (e.key === 'ArrowRight') this.next();
        });
      },
      open(i) {
        this.currentIndex = i;
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
      },
      close() {
        this.isOpen = false;
        document.body.style.overflow = '';
      },
      prev() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
      },
      next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
      },
    };
  }

  function socialShare() {
    return {
      isOpen: false,
      pageUrl: window.location.href,
      copied: false,
      init() {
        document.addEventListener('click', (e) => {
          if (!this.$el.contains(e.target)) this.isOpen = false;
        });
      },
      copyLink() {
        navigator.clipboard.writeText(this.pageUrl).then(() => {
          this.copied = true;
          setTimeout(() => this.copied = false, 2000);
        });
      },
    };
  }
</script>
</body>
</html>
