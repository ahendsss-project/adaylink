{{-- Template: Luxury (Premium Tier) — Elegant design with Playfair Display + DM Sans --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
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
  <title>{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
  @if ($settings)
      <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
  @endif
  <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:ital,wght@0,400;0,700;1,400&family={{ urlencode($fontBody) }}:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  @if (($features['reviews'] ?? false) && isset($reviewSchema) && $reviewSchema)
      <script type="application/ld+json">{{ json_encode($reviewSchema) }}</script>
  @endif
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
      background: rgba(26, 18, 8, 0.85);
      backdrop-filter: blur(12px);
      transition: background 0.3s;
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
      letter-spacing: 0.5px;
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

    /* ── HERO ── */
    .hero {
      position: relative;
      height: 100vh;
      min-height: 640px;
      display: flex;
      align-items: flex-end;
      overflow: hidden;
    }

    .hero-bg {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      transform: scale(1.05);
      animation: heroZoom 10s ease-out forwards;
    }

    @keyframes heroZoom {
      from { transform: scale(1.05); }
      to { transform: scale(1); }
    }

    .hero-content {
      position: relative;
      z-index: 2;
      padding: 0 60px 80px;
      max-width: 760px;
      animation: heroFadeUp 1.2s ease-out both;
    }

    @keyframes heroFadeUp {
      from { opacity: 0; transform: translateY(40px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .hero-tag {
      display: inline-block;
      background: var(--accent);
      color: var(--white);
      font-size: 0.75rem;
      font-weight: 500;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 6px 16px;
      border-radius: 30px;
      margin-bottom: 20px;
    }

    .hero h1 {
      font-family: var(--font-heading);
      font-size: clamp(2.8rem, 6vw, 5rem);
      line-height: 1.1;
      color: var(--white);
      margin-bottom: 20px;
    }

    .hero h1 em {
      font-style: italic;
      color: var(--accent);
    }

    .hero p {
      font-size: 1.05rem;
      color: rgba(255,255,255,0.75);
      line-height: 1.7;
      max-width: 500px;
      margin-bottom: 36px;
    }

    .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; }

    .btn-primary {
      background: var(--accent);
      color: var(--white);
      text-decoration: none;
      padding: 14px 32px;
      border-radius: 40px;
      font-size: 0.92rem;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: background 0.2s, transform 0.2s;
    }

    .btn-primary:hover {
      background: var(--brown);
      transform: translateY(-2px);
    }

    .btn-ghost {
      border: 1.5px solid rgba(255,255,255,0.5);
      color: var(--white);
      text-decoration: none;
      padding: 14px 32px;
      border-radius: 40px;
      font-size: 0.92rem;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: border-color 0.2s, transform 0.2s;
    }

    .btn-ghost:hover {
      border-color: var(--white);
      transform: translateY(-2px);
    }

    .hero-stats {
      position: absolute;
      right: 60px;
      bottom: 80px;
      z-index: 2;
      display: flex;
      gap: 40px;
      animation: heroFadeUp 1.4s ease-out both;
    }

    .stat { text-align: center; }
    .stat .num {
      font-family: var(--font-heading);
      font-size: 2rem;
      font-weight: 700;
      color: var(--white);
      display: block;
    }
    .stat .lbl {
      font-size: 0.78rem;
      color: rgba(255,255,255,0.6);
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    /* ── SECTIONS SHARED ── */
    section { padding: 100px 60px; }

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

    .see-all {
      color: var(--accent);
      text-decoration: none;
      font-size: 0.88rem;
      font-weight: 500;
      border-bottom: 1px solid var(--accent);
      padding-bottom: 2px;
      transition: opacity 0.2s;
    }

    .see-all:hover { opacity: 0.7; }

    /* ── TOUR PACKAGES ── */
    .packages { background: var(--white); }

    .tour-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
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
      height: 220px;
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
      font-size: 1.2rem;
      margin-bottom: 8px;
      line-height: 1.3;
    }

    .tour-body p {
      font-size: 0.85rem;
      color: var(--gray);
      line-height: 1.6;
      margin-bottom: 18px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
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
      font-size: 1.25rem;
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

    /* ── GALLERY ── */
    .gallery { background: var(--cream); }

    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-template-rows: 200px 200px;
      gap: 14px;
    }

    .gallery-item {
      border-radius: 12px;
      overflow: hidden;
      position: relative;
      cursor: pointer;
    }

    .gallery-item img {
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform 0.4s;
    }

    .gallery-item:hover img { transform: scale(1.08); }

    .gallery-item:first-child {
      grid-column: 1 / 3;
      grid-row: 1 / 3;
    }

    .gallery-item:nth-child(4) { grid-row: 1 / 3; }

    .gallery-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(26,18,8,0.5), transparent);
      opacity: 0;
      transition: opacity 0.3s;
      display: flex;
      align-items: flex-end;
      padding: 16px;
      color: var(--white);
      font-size: 0.85rem;
      font-weight: 500;
    }

    .gallery-item:hover .gallery-overlay { opacity: 1; }

    /* ── VEHICLES ── */
    .vehicles { background: var(--dark); }

    .vehicles .section-title { color: var(--white); }
    .vehicles .section-label { color: var(--accent); }

    .vehicle-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 22px;
    }

    .vehicle-card {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 14px;
      overflow: hidden;
      transition: transform 0.3s, border-color 0.3s;
      text-decoration: none;
      color: inherit;
    }

    .vehicle-card:hover {
      transform: translateY(-5px);
      border-color: var(--accent);
    }

    .vehicle-img {
      height: 160px;
      overflow: hidden;
    }

    .vehicle-img img {
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform 0.4s;
      filter: brightness(0.85);
    }

    .vehicle-card:hover .vehicle-img img {
      transform: scale(1.05);
      filter: brightness(1);
    }

    .vehicle-body { padding: 18px; }

    .vehicle-type {
      font-size: 0.7rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 6px;
    }

    .vehicle-body h3 {
      font-family: var(--font-heading);
      font-size: 1.05rem;
      color: var(--white);
      margin-bottom: 10px;
    }

    .vehicle-specs {
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
      margin-bottom: 14px;
    }

    .spec {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.78rem;
      color: rgba(255,255,255,0.55);
    }

    .vehicle-price {
      font-size: 0.85rem;
      color: rgba(255,255,255,0.7);
    }

    .vehicle-price strong {
      font-family: var(--font-heading);
      font-size: 1.1rem;
      color: var(--accent);
    }

    /* ── REVIEWS ── */
    .reviews { background: var(--white); }

    .reviews-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 26px;
    }

    .review-card {
      background: var(--cream);
      border-radius: 16px;
      padding: 30px;
      position: relative;
    }

    .review-quote {
      font-size: 4rem;
      line-height: 1;
      color: var(--sand);
      font-family: var(--font-heading);
      position: absolute;
      top: 14px;
      right: 24px;
    }

    .stars {
      color: var(--accent);
      font-size: 1rem;
      margin-bottom: 14px;
      letter-spacing: 2px;
    }

    .review-card p {
      font-size: 0.9rem;
      line-height: 1.7;
      color: #444;
      margin-bottom: 22px;
      font-style: italic;
    }

    .reviewer {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .reviewer-avatar {
      width: 44px; height: 44px;
      border-radius: 50%;
      object-fit: cover;
      background: var(--sand);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      font-weight: 600;
      color: var(--brown);
      flex-shrink: 0;
    }

    .reviewer-name {
      font-weight: 500;
      font-size: 0.92rem;
    }

    .reviewer-loc {
      font-size: 0.78rem;
      color: var(--gray);
    }

    /* ── FOOTER ── */
    footer {
      background: var(--dark);
      color: rgba(255,255,255,0.65);
      padding: 60px 60px 30px;
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

    /* ── EMPTY STATE ── */
    .empty-state {
      text-align: center;
      padding: 80px 24px;
    }

    .empty-state .icon { font-size: 3rem; margin-bottom: 16px; }
    .empty-state h3 { font-family: var(--font-heading); font-size: 1.3rem; margin-bottom: 8px; color: var(--gray); }
    .empty-state p { font-size: 0.9rem; color: var(--gray); }

    @media (max-width: 900px) {
      nav { padding: 18px 24px; }
      nav ul { display: none; }
      .hamburger { display: block; }
      .mobile-menu.open { display: block; }
      section { padding: 70px 24px; }
      .hero-content { padding: 0 24px 70px; }
      .hero-stats { display: none; }
      .tour-grid { grid-template-columns: 1fr; }
      .gallery-grid { grid-template-columns: repeat(2,1fr); grid-template-rows: auto; }
      .gallery-item:first-child { grid-column: auto; grid-row: auto; }
      .gallery-item:nth-child(4) { grid-row: auto; }
      .vehicle-grid { grid-template-columns: repeat(2,1fr); }
      .reviews-grid { grid-template-columns: 1fr; }
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

<!-- HERO -->
<section class="hero">
  <div class="hero-bg" style="background-image: linear-gradient(to top, rgba(26,18,8,0.85) 30%, rgba(26,18,8,0.2) 70%, transparent), url('{{ $settings->hero_image_url ?? 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1800&q=80' }}');"></div>
  <div class="hero-content">
    <h1>{!! ($settings->hero_title ?? $website->site_name ?? 'Welcome') !!}</h1>
    @if($settings->hero_subtitle)
      <p>{{ $settings->hero_subtitle }}</p>
    @endif
    <div class="hero-btns">
      @if($tourPackages->isNotEmpty())
        <a href="#tours" class="btn-primary">{{ __('messages.tours') }}</a>
      @endif
      @if(($features['gallery_lightbox'] ?? false) && $galleryImages->isNotEmpty())
        <a href="#gallery" class="btn-ghost">{{ __('messages.gallery') }}</a>
      @endif
    </div>
  </div>
  <div class="hero-stats">
    @if($tourPackages->isNotEmpty())
      <div class="stat">
        <span class="num">{{ $tourPackages->count() }}+</span>
        <span class="lbl">{{ __('messages.tours') }}</span>
      </div>
    @endif
    @if($reviews->isNotEmpty())
      <div class="stat">
        <span class="num">{{ number_format($reviews->avg('rating'), 1) }} <i class="fas fa-star" style="font-size:0.7em"></i></span>
        <span class="lbl">Rating</span>
      </div>
    @endif
    @if($vehicles->isNotEmpty())
      <div class="stat">
        <span class="num">{{ $vehicles->count() }}</span>
        <span class="lbl">Kendaraan</span>
      </div>
    @endif
  </div>
</section>

{{-- TOUR PACKAGES --}}
@if($tourPackages->isNotEmpty())
<section class="packages" id="tours">
  <div class="section-head">
    <div>
      <div class="section-label">Paket Pilihan</div>
      <h2 class="section-title">Tour <em>Terpopuler</em></h2>
    </div>
  </div>
  <div class="tour-grid">
    @foreach($tourPackages as $tour)
      <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $tour->slug : '/tour/' . $tour->slug }}" class="tour-card">
        <div class="tour-img">
          @if($tour->thumbnail_url)
            <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}"/>
          @else
            <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=600&q=80" alt="{{ $tour->title }}"/>
          @endif
          @if($tour->is_featured)
            <div class="tour-badge">Best Seller</div>
          @endif
        </div>
        <div class="tour-body">
          <div class="tour-meta">
            @if($tour->duration_text)
              <span><i class="far fa-clock"></i> {{ $tour->duration_text }}</span>
            @endif
          </div>
          <h3>{{ $tour->title }}</h3>
          @if($tour->description)
            <p>{{ $tour->description }}</p>
          @endif
          <div class="tour-footer">
            @if($tour->price_start_from)
              <div class="tour-price">
                <div class="from">{{ __('messages.starting_from') }}</div>
                <div class="amount">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
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

{{-- GALLERY --}}
@if(($features['gallery_lightbox'] ?? false) && $galleryImages->isNotEmpty())
<section class="gallery" id="gallery"
         x-data="galleryLightbox(@js($galleryImages->values()->all()))" x-init="init()">
  <div class="section-head">
    <div>
      <div class="section-label">{{ __('messages.gallery_title') }}</div>
      <h2 class="section-title">Momen <em>Tak Terlupakan</em></h2>
    </div>
  </div>
  <div class="gallery-grid">
    @foreach($galleryImages as $index => $image)
      <div class="gallery-item" @click="open({{ $index }})">
        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? 'Gallery' }}"/>
        <div class="gallery-overlay">{{ $image['alt'] ?? 'Gallery' }}</div>
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
      <img :src="images[currentIndex]?.url || ''" :alt="images[currentIndex]?.alt || ''" @click.stop/>
      <div class="lightbox-counter"><span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span></div>
    </div>
  </template>
</section>
@endif

{{-- VEHICLES --}}
@if($vehicles->isNotEmpty())
<section class="vehicles" id="vehicles">
  <div class="section-head">
    <div>
      <div class="section-label">{{ __('messages.our_fleet') }}</div>
      <h2 class="section-title" style="color:var(--white)">Kendaraan <em>Nyaman & Aman</em></h2>
    </div>
  </div>
  <div class="vehicle-grid">
    @foreach($vehicles as $vehicle)
      <div class="vehicle-card">
        <div class="vehicle-img">
          @if($vehicle->image_url)
            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model_name }}"/>
          @else
            <img src="https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=500&q=80" alt="{{ $vehicle->model_name }}"/>
          @endif
        </div>
        <div class="vehicle-body">
          @if($vehicle->type)
            <div class="vehicle-type">{{ $vehicle->type }}</div>
          @endif
          <h3>{{ $vehicle->model_name }}</h3>
          <div class="vehicle-specs">
            @if($vehicle->capacity_people)
              <span class="spec"><i class="fas fa-users"></i> {{ $vehicle->capacity_people }} Kursi</span>
            @endif
            <span class="spec"><i class="fas fa-snowflake"></i> AC</span>
          </div>
          @if($vehicle->price_per_day)
            <div class="vehicle-price">Mulai <strong>Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</strong>/hari</div>
          @endif
        </div>
      </div>
    @endforeach
  </div>
</section>
@endif

{{-- REVIEWS --}}
@if($features['reviews'] ?? false)
<section class="reviews" id="reviews">
  <div class="section-head">
    <div>
      <div class="section-label">Testimoni</div>
      <h2 class="section-title">Kata Mereka<br/><em>Tentang Kami</em></h2>
    </div>
  </div>

  @if(session('review_success'))
    <div style="background:var(--white);border:1px solid var(--sand);border-radius:12px;padding:14px 20px;margin-bottom:24px;text-align:center;font-size:0.9rem;color:var(--green);">
      <i class="fas fa-check-circle"></i> {{ session('review_success') }}
    </div>
  @endif

  @if($reviews->isNotEmpty())
  <div class="reviews-grid">
    @foreach($reviews as $review)
      <div class="review-card">
        <div class="review-quote">"</div>
        <div class="stars">
          @for($i = 1; $i <= 5; $i++)
            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
          @endfor
        </div>
        <p>{{ $review->comment }}</p>
        <div class="reviewer">
          <div class="reviewer-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</div>
          <div>
            <div class="reviewer-name">{{ $review->reviewer_name }}</div>
            <div class="reviewer-loc">{{ $review->created_at->format('d M Y') }}</div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @else
  <div class="empty-state" style="padding:40px 24px;">
    <div class="icon"><i class="far fa-comment-dots"></i></div>
    <h3>Belum Ada Review</h3>
    <p>Jadilah yang pertama memberikan review!</p>
  </div>
  @endif

  {{-- Review Form --}}
  <div style="max-width:500px;margin:40px auto 0;background:var(--cream);border-radius:16px;padding:30px;">
    <h3 style="font-family:var(--font-heading);font-size:1.2rem;margin-bottom:20px;"><i class="fas fa-pen-to-square"></i> Tulis Review</h3>
    <form method="POST" action="{{ route('public.reviews.store', $website->subdomain) }}">
      @csrf
      @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:0.85rem;color:#dc2626;">
          @foreach($errors->all() as $error) <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p> @endforeach
        </div>
      @endif
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.8rem;font-weight:500;color:var(--gray);margin-bottom:6px;">Nama *</label>
        <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}" required
               style="width:100%;padding:11px 14px;border:1.5px solid var(--sand);border-radius:10px;font-family:var(--font-body);font-size:0.88rem;background:var(--white);color:var(--dark);"/>
      </div>
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.8rem;font-weight:500;color:var(--gray);margin-bottom:6px;">Email</label>
        <input type="email" name="reviewer_email" value="{{ old('reviewer_email') }}"
               style="width:100%;padding:11px 14px;border:1.5px solid var(--sand);border-radius:10px;font-family:var(--font-body);font-size:0.88rem;background:var(--white);color:var(--dark);"/>
      </div>
      <div style="margin-bottom:14px;" x-data="{ rating: 0 }">
        <label style="display:block;font-size:0.8rem;font-weight:500;color:var(--gray);margin-bottom:6px;">Rating *</label>
        <div style="display:flex;gap:6px;">
          <template x-for="i in 5" :key="i">
            <button type="button" @click="rating = i; $refs.ratingInput.value = i"
                    style="background:none;border:none;font-size:1.4rem;cursor:pointer;padding:2px;"
                    :style="i <= rating ? 'color:var(--accent)' : 'color:var(--sand)'"><i class="fas fa-star"></i></button>
          </template>
        </div>
        <input type="hidden" name="rating" value="{{ old('rating') }}" x-ref="ratingInput" required/>
      </div>
      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.8rem;font-weight:500;color:var(--gray);margin-bottom:6px;">Komentar *</label>
        <textarea name="comment" rows="3" required
                  style="width:100%;padding:11px 14px;border:1.5px solid var(--sand);border-radius:10px;font-family:var(--font-body);font-size:0.88rem;background:var(--white);color:var(--dark);resize:vertical;">{{ old('comment') }}</textarea>
      </div>
      <button type="submit"
              style="width:100%;background:var(--accent);color:var(--white);border:none;padding:14px;border-radius:40px;font-size:0.92rem;font-weight:500;cursor:pointer;font-family:var(--font-body);">
        <i class="fas fa-paper-plane"></i> Kirim Review
      </button>
      <p style="text-align:center;font-size:0.75rem;color:var(--gray);margin-top:10px;">Review akan ditampilkan setelah disetujui.</p>
    </form>
  </div>
</section>
@endif

{{-- EMPTY STATE --}}
@if($tourPackages->isEmpty() && $vehicles->isEmpty())
  <div class="empty-state">
    <div class="icon"><i class="fas fa-umbrella-beach"></i></div>
    <h3>Website Sedang Disiapkan</h3>
    <p>Konten akan segera tersedia.</p>
  </div>
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
  function galleryLightbox(images) {
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
