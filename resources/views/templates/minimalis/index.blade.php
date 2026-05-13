{{-- Template: Minimalis — Pure minimalist design with Manrope + Inter --}}
@php
    $homeUrl = isset($demoTemplate) ? '/app/demo/' . $demoTemplate : (isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/');
    $pageUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/page' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page' : '/page');
    $tourUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/tour' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour' : '/tour');
    $primaryColor = $settings->primary_color ?? '#111111';
    $secondaryColor = $settings->secondary_color ?? '#555555';
    $fontHeading = $settings->font_heading ?? 'Manrope';
    $fontBody = $settings->font_body ?? 'Inter';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale ?? 'id' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if($settings)
        <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700;800&family={{ urlencode($fontBody) }}:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @if(($features['reviews'] ?? false) && isset($reviewSchema) && $reviewSchema)
        <script type="application/ld+json">{{ json_encode($reviewSchema) }}</script>
    @endif
    <style>
        :root {
            --bg: #FAFAFA;
            --fg: #111111;
            --muted: #888888;
            --accent: {{ $primaryColor }};
            --accent2: {{ $secondaryColor }};
            --card: #FFFFFF;
            --border: #E5E5E5;
            --light: #F5F5F5;
            --font-heading: '{{ $fontHeading }}', sans-serif;
            --font-body: '{{ $fontBody }}', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--fg);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ── NAVBAR ── */
        .min-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(250,250,250,0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .min-nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 32px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .min-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .min-logo-mark {
            width: 28px; height: 28px;
            background: var(--fg);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .min-logo-mark img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .min-logo-name {
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--fg);
            letter-spacing: -0.3px;
        }

        .min-nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
        }

        .min-nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.2px;
            transition: color 0.2s;
        }

        .min-nav-links a:hover { color: var(--fg); }
        .min-nav-links a.active { color: var(--fg); font-weight: 600; }

        .min-nav-cta {
            padding: 7px 18px !important;
            background: var(--fg) !important;
            color: white !important;
            border-radius: 6px !important;
            font-weight: 600 !important;
            letter-spacing: 0 !important;
            transition: opacity 0.2s !important;
        }

        .min-nav-cta:hover { opacity: 0.8; }

        .min-hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--fg);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 6px;
        }

        /* ── MOBILE DRAWER ── */
        .min-drawer {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 200;
            background: rgba(0,0,0,0.2);
        }

        .min-drawer-panel {
            position: absolute;
            top: 0; right: 0;
            width: min(300px, 85vw);
            height: 100%;
            background: var(--card);
            padding: 28px 24px;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .min-drawer.open .min-drawer-panel { transform: translateX(0); }

        .min-drawer-close {
            background: none;
            border: none;
            color: var(--muted);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 4px;
            float: right;
        }

        .min-drawer-links {
            clear: both;
            padding-top: 32px;
        }

        .min-drawer-links a {
            display: block;
            padding: 14px 0;
            color: var(--fg);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            border-bottom: 1px solid var(--border);
        }

        .min-drawer-links a:last-child { border-bottom: none; }

        /* ── HERO ── */
        .min-hero {
            padding-top: 60px;
        }

        .min-hero-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 80px 32px 72px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
            animation: minIn 0.8s ease both;
        }

        @keyframes minIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .min-hero-text { max-width: 480px; }

        .min-hero-label {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .min-hero h1 {
            font-family: var(--font-heading);
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--fg);
            line-height: 1.1;
            letter-spacing: -1.5px;
            margin-bottom: 20px;
        }

        .min-hero-desc {
            font-size: 0.95rem;
            line-height: 1.7;
            color: var(--muted);
            margin-bottom: 32px;
            max-width: 400px;
        }

        .min-hero-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .min-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 12px 28px;
            background: var(--fg);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-family: var(--font-heading);
            font-size: 0.85rem;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .min-btn:hover { opacity: 0.85; }

        .min-btn-outline {
            background: transparent;
            color: var(--fg);
            border: 1px solid var(--border);
        }

        .min-btn-outline:hover { border-color: var(--fg); }

        .min-hero-image {
            position: relative;
        }

        .min-hero-img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            border-radius: 12px;
            display: block;
        }

        .min-hero-img-placeholder {
            width: 100%;
            aspect-ratio: 4/3;
            background: var(--light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 2rem;
        }

        /* ── SECTION COMMON ── */
        .min-section {
            max-width: 1100px;
            margin: 0 auto;
            padding: 72px 32px;
        }

        .min-section-header {
            margin-bottom: 40px;
        }

        .min-section-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .min-section-title {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--fg);
            letter-spacing: -0.5px;
        }

        .min-divider {
            border: none;
            border-top: 1px solid var(--border);
        }

        /* ── TOUR CARDS ── */
        .min-tours-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .min-tour-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: border-color 0.2s, transform 0.2s;
        }

        .min-tour-card:hover {
            border-color: var(--fg);
            transform: translateY(-2px);
        }

        .min-tour-card-img {
            width: 100%;
            aspect-ratio: 16/10;
            object-fit: cover;
            display: block;
        }

        .min-tour-card-img-placeholder {
            width: 100%;
            aspect-ratio: 16/10;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 1.5rem;
        }

        .min-tour-card-body {
            padding: 20px;
        }

        .min-tour-card-title {
            font-family: var(--font-heading);
            font-size: 1rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .min-tour-card-desc {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .min-tour-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .min-tour-price {
            font-family: var(--font-heading);
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--fg);
        }

        .min-tour-price span {
            font-weight: 400;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .min-tour-arrow {
            width: 28px; height: 28px;
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            color: var(--muted);
            transition: all 0.2s;
        }

        .min-tour-card:hover .min-tour-arrow {
            border-color: var(--fg);
            color: var(--fg);
        }

        /* ── GALLERY ── */
        .min-gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .min-gallery-item {
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
            position: relative;
        }

        .min-gallery-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .min-gallery-item:hover img { transform: scale(1.05); }

        /* ── VEHICLES ── */
        .min-vehicles-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .min-vehicle-card {
            display: flex;
            gap: 16px;
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            text-decoration: none;
            color: inherit;
            transition: border-color 0.2s;
        }

        .min-vehicle-card:hover { border-color: var(--fg); }

        .min-vehicle-thumb {
            width: 80px; height: 80px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 1.2rem;
        }

        .min-vehicle-thumb img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .min-vehicle-info {
            flex: 1;
            min-width: 0;
        }

        .min-vehicle-name {
            font-family: var(--font-heading);
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 4px;
        }

        .min-vehicle-desc {
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ── REVIEWS ── */
        .min-reviews-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .min-review-card {
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .min-review-stars {
            display: flex;
            gap: 2px;
            margin-bottom: 12px;
            color: #F59E0B;
            font-size: 0.75rem;
        }

        .min-review-text {
            font-size: 0.88rem;
            line-height: 1.7;
            color: var(--fg);
            margin-bottom: 16px;
            font-style: italic;
        }

        .min-review-author {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--fg);
        }

        .min-review-date {
            font-size: 0.72rem;
            color: var(--muted);
            margin-left: 8px;
        }

        /* ── FOOTER ── */
        .min-footer {
            border-top: 1px solid var(--border);
        }

        .min-footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 32px 32px;
        }

        .min-footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 40px;
        }

        .min-footer-brand-desc {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.7;
            margin-top: 12px;
            max-width: 320px;
        }

        .min-footer-col-title {
            font-family: var(--font-heading);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--fg);
            margin-bottom: 16px;
        }

        .min-footer-col ul { list-style: none; }
        .min-footer-col ul li { margin-bottom: 10px; }
        .min-footer-col ul a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.82rem;
            transition: color 0.2s;
        }
        .min-footer-col ul a:hover { color: var(--fg); }

        .min-footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            font-size: 0.75rem;
            color: var(--muted);
        }

        .min-footer-bottom a {
            color: var(--fg);
            text-decoration: none;
            font-weight: 500;
        }

        /* ── SOCIAL SHARE FAB ── */
        .share-fab {
            position: fixed;
            bottom: 24px; left: 24px;
            z-index: 90;
        }

        .share-fab-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 8px;
        }

        .share-fab-options a,
        .share-fab-options button {
            width: 38px; height: 38px;
            border-radius: 50%;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            transition: transform 0.2s;
        }

        .share-fab-options a:hover,
        .share-fab-options button:hover { transform: scale(1.1); }

        .share-fab-trigger {
            width: 42px; height: 42px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--fg);
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .share-fab-trigger:hover {
            border-color: var(--fg);
        }

        /* ══════════ RESPONSIVE ══════════ */

        /* ── Tablet (≤960px) ── */
        @media (max-width: 960px) {
            .min-nav-links { display: none; }
            .min-hamburger { display: block; }
            .min-drawer { display: block; }

            .min-hero-inner {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 56px 24px 48px;
            }

            .min-hero h1 { font-size: 2.2rem; }

            .min-section {
                padding: 56px 24px;
            }

            .min-tours-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .min-gallery-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .min-footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }
        }

        /* ── Mobile (≤600px) ── */
        @media (max-width: 600px) {
            .min-nav-inner {
                height: 52px;
                padding: 0 16px;
            }

            .min-logo-mark { width: 24px; height: 24px; font-size: 10px; }
            .min-logo-name { font-size: 0.85rem; }

            .min-hero { padding-top: 52px; }

            .min-hero-inner {
                padding: 36px 16px 32px;
                gap: 28px;
            }

            .min-hero h1 {
                font-size: 1.75rem;
                letter-spacing: -1px;
            }

            .min-hero-desc {
                font-size: 0.88rem;
            }

            .min-hero-actions {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .min-btn {
                padding: 10px 22px;
                font-size: 0.82rem;
            }

            .min-section {
                padding: 40px 16px;
            }

            .min-section-title {
                font-size: 1.3rem;
            }

            .min-tours-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .min-tour-card-body {
                padding: 16px;
            }

            .min-gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
            }

            .min-vehicles-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .min-reviews-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .min-review-card {
                padding: 18px;
            }

            .min-footer-inner {
                padding: 36px 16px 24px;
            }

            .min-footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
                margin-bottom: 28px;
            }

            .min-footer-bottom {
                flex-direction: column;
                gap: 6px;
                text-align: center;
            }

            .share-fab { bottom: 16px; left: 16px; }
            .share-fab-trigger { width: 38px; height: 38px; font-size: 13px; }
            .share-fab-options a,
            .share-fab-options button { width: 34px; height: 34px; font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="min-nav">
    <div class="min-nav-inner">
        <a href="{{ $homeUrl }}" class="min-logo">
            <div class="min-logo-mark">
                @if($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                @else
                    <i class="fas fa-compass"></i>
                @endif
            </div>
            <span class="min-logo-name">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="min-nav-links">
            <li><a href="{{ $homeUrl }}" class="active">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ $pageUrlBase . '/' . $p->slug }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="min-nav-cta">Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="min-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- MOBILE DRAWER -->
<div class="min-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="min-drawer-panel">
        <button class="min-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="min-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p)
                <a href="{{ $pageUrlBase . '/' . $p->slug }}"
                   @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false">{{ __('messages.contact') }}</a>
            @endif
        </div>
    </div>
</div>

<!-- HERO -->
<section class="min-hero">
    <div class="min-hero-inner">
        <div class="min-hero-text">
            <div class="min-hero-label">{{ $settings->site_title ?? $website->site_name ?? 'Welcome' }}</div>
            <h1>{{ $settings->hero_title ?? 'Jelajahi Dunia Bersama Kami' }}</h1>
            <p class="min-hero-desc">{{ $settings->hero_subtitle ?? $settings->description ?? 'Temukan pengalaman perjalanan terbaik dengan layanan profesional dan harga terjangkau.' }}</p>
            <div class="min-hero-actions">
                @if($tourPackages->count() > 0)
                    <a href="#tours" class="min-btn">Lihat Tour <i class="fas fa-arrow-down" style="font-size:0.7rem;"></i></a>
                @endif
                @if($website->contact_whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="min-btn min-btn-outline">{{ __('messages.contact') }}</a>
                @endif
            </div>
        </div>
        <div class="min-hero-image">
            @if($settings->hero_image_url)
                <img src="{{ $settings->hero_image_url }}" alt="Hero" class="min-hero-img"/>
            @elseif($galleryImages->count() > 0)
                <img src="{{ $galleryImages->first()['url'] }}" alt="Gallery" class="min-hero-img"/>
            @else
                <div class="min-hero-img-placeholder">
                    <i class="fas fa-image"></i>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- TOUR PACKAGES -->
@if($tourPackages->count() > 0)
<hr class="min-divider" style="max-width:1100px;margin:0 auto;">
<section class="min-section" id="tours">
    <div class="min-section-header">
        <div class="min-section-label">Destinasi</div>
        <h2 class="min-section-title">Tour Packages</h2>
    </div>
    <div class="min-tours-grid">
        @foreach($tourPackages as $tour)
            <a href="{{ $tourUrlBase . '/' . $tour->slug }}" class="min-tour-card">
                @if($tour->thumbnail_url)
                    <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}" class="min-tour-card-img"/>
                @elseif($tour->images->count() > 0)
                    <img src="{{ $tour->images->first()->url }}" alt="{{ $tour->title }}" class="min-tour-card-img"/>
                @else
                    <div class="min-tour-card-img-placeholder"><i class="fas fa-mountain"></i></div>
                @endif
                <div class="min-tour-card-body">
                    <h3 class="min-tour-card-title">{{ $tour->title }}</h3>
                    @if($tour->description)
                        <p class="min-tour-card-desc">{{ strip_tags($tour->description) }}</p>
                    @endif
                    <div class="min-tour-card-footer">
                        @if($tour->price_start_from)
                            <div class="min-tour-price"><span>Mulai </span>Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
                        @else
                            <div></div>
                        @endif
                        <div class="min-tour-arrow"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- GALLERY -->
@if($galleryImages->count() > 0)
<hr class="min-divider" style="max-width:1100px;margin:0 auto;">
<section class="min-section">
    <div class="min-section-header">
        <div class="min-section-label">{{ __('messages.gallery') }}</div>
        <h2 class="min-section-title">{{ __('messages.gallery_description') }}</h2>
    </div>
    <div class="min-gallery-grid"
         x-data="galleryLightbox({{ $galleryImages->toJson() }})"
         @if($features['gallery_lightbox'] ?? false) x-init="init()" @endif>
        @foreach($galleryImages as $i => $img)
            <div class="min-gallery-item"
                 @if($features['gallery_lightbox'] ?? false) @click="open({{ $i }})" @endif>
                <img src="{{ $img['url'] }}" alt="{{ $img['alt'] ?? 'Gallery' }}" loading="lazy"/>
            </div>
        @endforeach

        {{-- Lightbox --}}
        @if($features['gallery_lightbox'] ?? false)
        <template x-if="isOpen">
            <div style="position:fixed;top:0;left:0;right:0;bottom:0;z-index:300;background:rgba(0,0,0,0.92);display:flex;align-items:center;justify-content:center;"
                 @click.self="close()" x-transition>
                <button @click="close()" style="position:absolute;top:20px;right:20px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;padding:8px;">&times;</button>
                <button @click="prev()" style="position:absolute;left:16px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;padding:8px;">&#8249;</button>
                <img :src="images[currentIndex].url" style="max-width:90vw;max-height:85vh;object-fit:contain;border-radius:4px;"/>
                <button @click="next()" style="position:absolute;right:16px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;padding:8px;">&#8250;</button>
                <div style="position:absolute;bottom:20px;color:rgba(255,255,255,0.6);font-size:0.8rem;" x-text="(currentIndex + 1) + ' / ' + images.length"></div>
            </div>
        </template>
        @endif
    </div>
</section>
@endif

<!-- VEHICLES -->
@if($vehicles->count() > 0)
<hr class="min-divider" style="max-width:1100px;margin:0 auto;">
<section class="min-section">
    <div class="min-section-header">
        <div class="min-section-label">{{ __('messages.vehicles') }}</div>
        <h2 class="min-section-title">Kendaraan</h2>
    </div>
    <div class="min-vehicles-grid">
        @foreach($vehicles as $vehicle)
            <div class="min-vehicle-card">
                <div class="min-vehicle-thumb">
                    @if($vehicle->image_url)
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model_name }}"/>
                    @elseif($vehicle->images->count() > 0)
                        <img src="{{ $vehicle->images->first()->url }}" alt="{{ $vehicle->model_name }}"/>
                    @else
                        <i class="fas fa-car"></i>
                    @endif
                </div>
                <div class="min-vehicle-info">
                    <div class="min-vehicle-name">{{ $vehicle->model_name }}</div>
                    <div style="display:flex;align-items:center;gap:12px;margin-top:4px;flex-wrap:wrap;">
                        @if($vehicle->capacity_people)
                            <span style="font-size:0.78rem;color:var(--muted);"><i class="fas fa-users" style="margin-right:4px;font-size:0.7rem;"></i>{{ $vehicle->capacity_people }} Kursi</span>
                        @endif
                        @if($vehicle->price_per_day)
                            <span style="font-size:0.82rem;font-weight:600;color:var(--fg);">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}<span style="font-weight:400;font-size:0.72rem;color:var(--muted);"> /hari</span></span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- REVIEWS -->
@if($features['reviews'] ?? false)
<hr class="min-divider" style="max-width:1100px;margin:0 auto;">
<section class="min-section" id="reviews">
    <div class="min-section-header">
        <div class="min-section-label">Testimoni</div>
        <h2 class="min-section-title">Apa Kata Mereka</h2>
    </div>

    @if(session('review_success'))
        <div style="background:var(--card);border:1px solid var(--border);border-radius:8px;padding:12px 20px;margin-bottom:24px;text-align:center;font-size:0.85rem;color:#059669;max-width:600px;margin-left:auto;margin-right:auto;">
            <i class="fas fa-check-circle"></i> {{ session('review_success') }}
        </div>
    @endif

    @if($reviews->count() > 0)
    <div class="min-reviews-grid">
        @foreach($reviews as $review)
            <div class="min-review-card">
                <div class="min-review-stars">
                    @for($s = 0; $s < 5; $s++)
                        @if($s < $review->rating)
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star" style="color:#D1D5DB;"></i>
                        @endif
                    @endfor
                </div>
                <p class="min-review-text">"{{ $review->comment }}"</p>
                <div>
                    <span class="min-review-author">{{ $review->reviewer_name }}</span>
                    <span class="min-review-date">{{ $review->created_at->format('M Y') }}</span>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:32px 24px;color:var(--muted);font-size:0.9rem;">
        <i class="far fa-comment-dots" style="font-size:1.5rem;display:block;margin-bottom:8px;"></i>
        Belum ada review. Jadilah yang pertama!
    </div>
    @endif

    {{-- Review Form --}}
    <div style="max-width:480px;margin:36px auto 0;padding:28px;border:1px solid var(--border);border-radius:10px;background:var(--card);">
        <h3 style="font-family:var(--font-heading);font-size:1rem;font-weight:700;margin-bottom:20px;color:var(--fg);">Tulis Review</h3>
        <form method="POST" action="{{ route('public.reviews.store', $website->subdomain) }}">
            @csrf
            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:0.82rem;color:#dc2626;">
                    @foreach($errors->all() as $error) <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p> @endforeach
                </div>
            @endif
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--muted);margin-bottom:6px;">Nama *</label>
                <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}" required
                       style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:6px;font-size:0.88rem;font-family:var(--font-body);outline:none;transition:border-color 0.2s;"
                       onfocus="this.style.borderColor='var(--fg)'" onblur="this.style.borderColor='var(--border)'"/>
            </div>
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--muted);margin-bottom:6px;">Email</label>
                <input type="email" name="reviewer_email" value="{{ old('reviewer_email') }}"
                       style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:6px;font-size:0.88rem;font-family:var(--font-body);outline:none;transition:border-color 0.2s;"
                       onfocus="this.style.borderColor='var(--fg)'" onblur="this.style.borderColor='var(--border)'"/>
            </div>
            <div style="margin-bottom:14px;" x-data="{ rating: 0 }">
                <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--muted);margin-bottom:6px;">Rating *</label>
                <div style="display:flex;gap:4px;">
                    <template x-for="i in 5" :key="i">
                        <button type="button" @click="rating = i; $refs.ratingInput.value = i"
                                style="background:none;border:none;font-size:1.3rem;cursor:pointer;padding:2px;"
                                :style="i <= rating ? 'color:#F59E0B' : 'color:var(--border)'"><i class="fas fa-star"></i></button>
                    </template>
                </div>
                <input type="hidden" name="rating" value="{{ old('rating') }}" x-ref="ratingInput" required/>
            </div>
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--muted);margin-bottom:6px;">Komentar *</label>
                <textarea name="comment" rows="3" required
                          style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:6px;font-size:0.88rem;font-family:var(--font-body);outline:none;resize:vertical;transition:border-color 0.2s;"
                          onfocus="this.style.borderColor='var(--fg)'" onblur="this.style.borderColor='var(--border)'">{{ old('comment') }}</textarea>
            </div>
            <button type="submit"
                    style="width:100%;padding:12px;background:var(--fg);color:white;border:none;border-radius:6px;font-family:var(--font-heading);font-size:0.85rem;font-weight:600;cursor:pointer;transition:opacity 0.2s;"
                    onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                <i class="fas fa-paper-plane" style="margin-right:6px;"></i> Kirim Review
            </button>
            <p style="font-size:0.72rem;color:var(--muted);margin-top:10px;text-align:center;">Review akan ditampilkan setelah disetujui.</p>
        </form>
    </div>
</section>
@endif

<!-- FOOTER -->
<footer class="min-footer">
    <div class="min-footer-inner">
        <div class="min-footer-grid">
            <div>
                <a href="{{ $homeUrl }}" class="min-logo" style="margin-bottom:4px;">
                    <div class="min-logo-mark">
                        @if($website->logo_url)
                            <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                        @else
                            <i class="fas fa-compass"></i>
                        @endif
                    </div>
                    <span class="min-logo-name">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
                </a>
                <p class="min-footer-brand-desc">{{ $settings->description ?? 'Powered by adaylink — platform website untuk driver dan agen wisata.' }}</p>
            </div>
            <div class="min-footer-col">
                <div class="min-footer-col-title">Halaman</div>
                <ul>
                    @foreach($pages as $p)
                        <li><a href="{{ $pageUrlBase . '/' . $p->slug }}">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="min-footer-col">
                <div class="min-footer-col-title">Kontak</div>
                <ul>
                    @if($settings->phone ?? null)
                        <li><a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a></li>
                    @endif
                    @if($settings->email ?? null)
                        <li><a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></li>
                    @endif
                    @if($settings->address ?? null)
                        <li><a href="#">{{ $settings->address }}</a></li>
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
        <div class="min-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
            <span>Powered by <a href="#">adaylink</a></span>
        </div>
    </div>
</footer>

{{-- Floating WhatsApp --}}
@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
       target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:24px;right:24px;z-index:99;background:#25D366;color:white;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 12px rgba(37,211,102,0.25);text-decoration:none;font-size:20px;"
       x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(16px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},800)">
        <i class="fab fa-whatsapp" style="font-size:20px;"></i>
    </a>
@endif

{{-- Social Share FAB --}}
@if($features['social_share'] ?? false)
    <div class="share-fab" x-data="socialShare()" x-init="init()">
        <div class="share-fab-options" x-show="isOpen"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            <a :href="'https://wa.me/?text=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#25D366;"><i class="fab fa-whatsapp"></i></a>
            <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#1877F2;"><i class="fab fa-facebook-f"></i></a>
            <a :href="'https://twitter.com/intent/tweet?url=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#000;"><i class="fab fa-x-twitter"></i></a>
            <button @click="copyLink()" style="background:var(--fg);" :style="copied ? 'background:#059669' : ''">
                <i class="fas" :class="copied ? 'fa-check' : 'fa-link'"></i>
            </button>
        </div>
        <button @click="isOpen = !isOpen" class="share-fab-trigger"><i class="fas fa-share-alt"></i></button>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function galleryLightbox(images) {
        return {
            images, isOpen: false, currentIndex: 0,
            init() {
                document.addEventListener('keydown', (e) => {
                    if (!this.isOpen) return;
                    if (e.key === 'Escape') this.close();
                    if (e.key === 'ArrowLeft') this.prev();
                    if (e.key === 'ArrowRight') this.next();
                });
            },
            open(i) { this.currentIndex = i; this.isOpen = true; document.body.style.overflow = 'hidden'; },
            close() { this.isOpen = false; document.body.style.overflow = ''; },
            prev() { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length; },
            next() { this.currentIndex = (this.currentIndex + 1) % this.images.length; },
        };
    }

    function socialShare() {
        return {
            isOpen: false, pageUrl: window.location.href, copied: false,
            init() { document.addEventListener('click', (e) => { if (!this.$el.contains(e.target)) this.isOpen = false; }); },
            copyLink() { navigator.clipboard.writeText(this.pageUrl).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }); },
        };
    }
</script>
</body>
</html>
