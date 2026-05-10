{{-- Template: Luxury (Premium Tier) — Page View --}}
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
  <title>{{ $page->title }} — {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
  @if ($settings)
      <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
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
      height: 55vh;
      min-height: 420px;
      display: flex;
      align-items: flex-end;
      overflow: hidden;
    }

    .hero-detail-bg {
      position: absolute;
      inset: 0;
      background:
        linear-gradient(to top, rgba(26,18,8,0.9) 30%, rgba(26,18,8,0.2) 70%, transparent),
        url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1800&q=80') center/cover no-repeat;
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
      margin-bottom: 0;
    }

    /* ── PAGE CONTENT ── */
    .page-content {
      max-width: 860px;
      margin: 0 auto;
      padding: 60px 60px 80px;
    }

    .page-content .content-body {
      font-size: 0.95rem;
      line-height: 1.85;
      color: #444;
      white-space: pre-line;
    }

    .page-content .content-body h1,
    .page-content .content-body h2,
    .page-content .content-body h3 {
      font-family: var(--font-heading);
      color: var(--dark);
      margin-top: 1.5em;
      margin-bottom: 0.5em;
    }

    .page-content .content-body p {
      margin-bottom: 1em;
    }

    .page-content .content-body a {
      color: var(--accent);
      text-decoration: underline;
    }

    .page-content .content-body ul,
    .page-content .content-body ol {
      margin-bottom: 1em;
      padding-left: 1.5em;
    }

    .page-content .content-body img {
      max-width: 100%;
      border-radius: 12px;
      margin: 1em 0;
    }

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

    @media (max-width: 960px) {
      nav { padding: 18px 24px; }
      nav ul { display: none; }
      .hamburger { display: block; }
      .mobile-menu.open { display: block; }
      .hero-detail-content { padding: 0 24px 50px; }
      .page-content { padding: 40px 24px 60px; }
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
      <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" {{ $p->slug === $page->slug ? 'style="color:var(--accent)"' : '' }}>{{ $p->title }}</a></li>
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
  <div class="hero-detail-bg"></div>
  <div class="hero-detail-content">
    <div class="breadcrumb">
      <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
      <span>›</span>
      <span style="color:rgba(255,255,255,0.85)">{{ $page->title }}</span>
    </div>
    <h1>{{ $page->title }}</h1>
  </div>
</div>

<!-- PAGE CONTENT -->
<div class="page-content">
  <div class="content-body">{{ $page->content }}</div>
</div>

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
