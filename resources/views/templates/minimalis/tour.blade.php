{{-- Template: Minimalis — Pure minimalist Tour Detail View --}}
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
    <title>{{ $tour->title }} — {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if($settings)
        <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($tour->description ?? ''), 160) }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700;800&family={{ urlencode($fontBody) }}:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
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
            padding: 48px 32px 0;
            animation: minIn 0.6s ease both;
        }

        @keyframes minIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .min-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            font-size: 0.78rem;
            color: var(--muted);
        }

        .min-breadcrumb a {
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .min-breadcrumb a:hover { color: var(--fg); }
        .min-breadcrumb .sep { font-size: 0.6rem; }

        .min-hero-title {
            font-family: var(--font-heading);
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--fg);
            letter-spacing: -1px;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .min-hero-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .min-hero-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--muted);
        }

        .min-hero-meta-item i { font-size: 0.75rem; }

        .min-hero-image {
            width: 100%;
            aspect-ratio: 21/9;
            overflow: hidden;
            border-radius: 12px;
            background: var(--light);
        }

        .min-hero-image img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }

        .min-hero-image-placeholder {
            width: 100%; height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 2rem;
        }

        /* ── CONTENT LAYOUT ── */
        .min-content-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 32px 72px;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 48px;
            align-items: start;
        }

        /* ── MAIN CONTENT ── */
        .min-main { min-width: 0; }

        .min-content-block {
            margin-bottom: 48px;
        }

        .min-content-block:last-child { margin-bottom: 0; }

        .min-block-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 16px;
        }

        .min-description {
            font-size: 0.92rem;
            line-height: 1.8;
            color: var(--fg);
        }

        .min-description p { margin-bottom: 16px; }

        /* ── ITINERARY ── */
        .min-itinerary-list {
            list-style: none;
        }

        .min-itinerary-item {
            display: flex;
            gap: 16px;
            padding: 20px 0;
            border-bottom: 1px solid var(--border);
        }

        .min-itinerary-item:last-child { border-bottom: none; }

        .min-itinerary-day {
            flex-shrink: 0;
            width: 48px; height: 48px;
            background: var(--fg);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-heading);
            font-size: 0.75rem;
            font-weight: 700;
        }

        .min-itinerary-body {
            flex: 1;
            min-width: 0;
        }

        .min-itinerary-title {
            font-family: var(--font-heading);
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 4px;
        }

        .min-itinerary-desc {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── INCLUDES / EXCLUDES ── */
        .min-check-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .min-check-list {
            list-style: none;
        }

        .min-check-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 8px 0;
            font-size: 0.85rem;
            color: var(--fg);
            line-height: 1.5;
        }

        .min-check-list li i {
            margin-top: 3px;
            font-size: 0.7rem;
            flex-shrink: 0;
        }

        .min-check-include li i { color: #059669; }
        .min-check-exclude li i { color: #DC2626; }

        /* ── GALLERY ── */
        .min-gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .min-gallery-item {
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
        }

        .min-gallery-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .min-gallery-item:hover img { transform: scale(1.05); }

        /* ── SIDEBAR ── */
        .min-sidebar { position: sticky; top: 84px; }

        .min-sidebar-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .min-sidebar-card:last-child { margin-bottom: 0; }

        .min-price-label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .min-price-value {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--fg);
            letter-spacing: -1px;
            margin-bottom: 20px;
        }

        .min-price-value span {
            font-size: 0.85rem;
            font-weight: 400;
            color: var(--muted);
        }

        .min-sidebar-btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: var(--fg);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-family: var(--font-heading);
            font-size: 0.88rem;
            font-weight: 600;
            transition: opacity 0.2s;
            border: none;
            cursor: pointer;
        }

        .min-sidebar-btn:hover { opacity: 0.85; }

        .min-sidebar-info-list {
            list-style: none;
        }

        .min-sidebar-info-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.82rem;
            color: var(--fg);
        }

        .min-sidebar-info-list li:last-child { border-bottom: none; }
        .min-sidebar-info-list li i { color: var(--muted); font-size: 0.75rem; width: 16px; text-align: center; }

        /* ── NOTES ── */
        .min-notes {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.7;
            padding: 16px;
            background: var(--light);
            border-radius: 8px;
            border-left: 3px solid var(--border);
        }

        /* ── RELATED TOURS ── */
        .min-related {
            border-top: 1px solid var(--border);
        }

        .min-related-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 56px 32px 72px;
        }

        .min-related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .min-rel-card {
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: border-color 0.2s, transform 0.2s;
        }

        .min-rel-card:hover {
            border-color: var(--fg);
            transform: translateY(-2px);
        }

        .min-rel-card-img {
            width: 100%;
            aspect-ratio: 16/10;
            object-fit: cover;
            display: block;
        }

        .min-rel-card-img-placeholder {
            width: 100%;
            aspect-ratio: 16/10;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 1.2rem;
        }

        .min-rel-card-body {
            padding: 16px;
        }

        .min-rel-card-title {
            font-family: var(--font-heading);
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 8px;
        }

        .min-rel-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .min-rel-price {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--fg);
        }

        .min-rel-price span {
            font-weight: 400;
            font-size: 0.72rem;
            color: var(--muted);
        }

        .min-rel-arrow {
            font-size: 0.65rem;
            color: var(--muted);
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

        .share-fab-trigger:hover { border-color: var(--fg); }

        /* ══════════ RESPONSIVE ══════════ */

        /* ── Tablet (≤960px) ── */
        @media (max-width: 960px) {
            .min-nav-links { display: none; }
            .min-hamburger { display: block; }
            .min-drawer { display: block; }

            .min-hero-inner { padding: 40px 24px 0; }

            .min-hero-title { font-size: 1.9rem; }

            .min-hero-image { aspect-ratio: 16/9; }

            .min-content-wrap {
                grid-template-columns: 1fr;
                gap: 32px;
                padding: 36px 24px 56px;
            }

            .min-sidebar { position: static; }

            .min-check-grid { grid-template-columns: 1fr; gap: 16px; }

            .min-related-inner { padding: 44px 24px 56px; }

            .min-related-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .min-footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }
        }

        /* ── Mobile (≤600px) ── */
        @media (max-width: 600px) {
            .min-nav-inner { height: 52px; padding: 0 16px; }
            .min-logo-mark { width: 24px; height: 24px; font-size: 10px; }
            .min-logo-name { font-size: 0.85rem; }

            .min-hero { padding-top: 52px; }

            .min-hero-inner { padding: 28px 16px 0; }

            .min-hero-title {
                font-size: 1.5rem;
                letter-spacing: -0.5px;
            }

            .min-hero-meta {
                gap: 12px;
            }

            .min-hero-image {
                aspect-ratio: 16/10;
                border-radius: 8px;
            }

            .min-content-wrap {
                padding: 28px 16px 48px;
                gap: 28px;
            }

            .min-content-block { margin-bottom: 36px; }

            .min-description { font-size: 0.88rem; }

            .min-itinerary-item {
                padding: 16px 0;
                gap: 12px;
            }

            .min-itinerary-day {
                width: 40px; height: 40px;
                font-size: 0.7rem;
            }

            .min-gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
            }

            .min-sidebar-card { padding: 20px; }

            .min-price-value { font-size: 1.5rem; }

            .min-related-inner { padding: 36px 16px 48px; }

            .min-related-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .min-footer-inner { padding: 36px 16px 24px; }

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
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
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
        <div class="min-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span>Tour</span>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span style="color:var(--fg);">{{ $tour->title }}</span>
        </div>
        <h1 class="min-hero-title">{{ $tour->title }}</h1>
        <div class="min-hero-meta">
            @if($tour->duration)
                <div class="min-hero-meta-item"><i class="far fa-clock"></i> {{ $tour->duration }}</div>
            @endif
            @if($tour->location)
                <div class="min-hero-meta-item"><i class="fas fa-map-marker-alt"></i> {{ $tour->location }}</div>
            @endif
            @if($tour->price_start_from)
                <div class="min-hero-meta-item"><i class="fas fa-tag"></i> Mulai Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
            @endif
        </div>
        <div class="min-hero-image">
            @if($tour->thumbnail_url)
                <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}"/>
            @elseif($tour->images->count() > 0)
                <img src="{{ $tour->images->first()->url }}" alt="{{ $tour->title }}"/>
            @else
                <div class="min-hero-image-placeholder"><i class="fas fa-mountain"></i></div>
            @endif
        </div>
    </div>
</section>

<!-- CONTENT -->
<div class="min-content-wrap">
    <div class="min-main">
        <!-- Description -->
        @if($tour->description)
        <div class="min-content-block">
            <div class="min-block-label">Deskripsi</div>
            <div class="min-description">{!! $tour->description !!}</div>
        </div>
        @endif

        <!-- Itinerary -->
        @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
        <div class="min-content-block">
            <div class="min-block-label">Itinerary</div>
            <ul class="min-itinerary-list">
                @foreach($tour->itinerary as $i => $item)
                    <li class="min-itinerary-item">
                        <div class="min-itinerary-day">D{{ $i + 1 }}</div>
                        <div class="min-itinerary-body">
                            <div class="min-itinerary-title">{{ is_array($item) ? ($item['title'] ?? 'Day ' . ($i + 1)) : $item }}</div>
                            @if(is_array($item) && isset($item['description']))
                                <div class="min-itinerary-desc">{{ $item['description'] }}</div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Includes / Excludes -->
        @if((is_array($tour->includes) && count($tour->includes) > 0) || (is_array($tour->excludes) && count($tour->excludes) > 0))
        <div class="min-content-block">
            <div class="min-block-label">{{ __('messages.includes') }} & {{ __('messages.excludes') }}</div>
            <div class="min-check-grid">
                @if(is_array($tour->includes) && count($tour->includes) > 0)
                    <div>
                        <div style="font-family:var(--font-heading);font-size:0.82rem;font-weight:700;margin-bottom:12px;color:var(--fg);">{{ __('messages.includes') }}</div>
                        <ul class="min-check-list min-check-include">
                            @foreach($tour->includes as $item)
                                <li><i class="fas fa-check"></i> {{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(is_array($tour->excludes) && count($tour->excludes) > 0)
                    <div>
                        <div style="font-family:var(--font-heading);font-size:0.82rem;font-weight:700;margin-bottom:12px;color:var(--fg);">{{ __('messages.excludes') }}</div>
                        <ul class="min-check-list min-check-exclude">
                            @foreach($tour->excludes as $item)
                                <li><i class="fas fa-times"></i> {{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Gallery -->
        @if($tour->images->count() > 1)
        <div class="min-content-block">
            <div class="min-block-label">{{ __('messages.gallery') }}</div>
            <div class="min-gallery-grid"
                 x-data="tourGallery({{ $tour->images->toJson() }})"
                 @if($features['gallery_lightbox'] ?? false) x-init="init()" @endif>
                @foreach($tour->images as $i => $img)
                    <div class="min-gallery-item"
                         @if($features['gallery_lightbox'] ?? false) @click="open({{ $i }})" @endif>
                        <img src="{{ $img->url }}" alt="{{ $img->alt_text ?? $tour->title }}" loading="lazy"/>
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
        </div>
        @endif

        <!-- Notes -->
        @if($tour->notes)
        <div class="min-content-block">
            <div class="min-block-label">{{ __('messages.notes') }}</div>
            <div class="min-notes">{!! $tour->notes !!}</div>
        </div>
        @endif
    </div>

    <!-- SIDEBAR -->
    <div class="min-sidebar">
        <div class="min-sidebar-card">
            @if($tour->price_start_from)
                <div class="min-price-label">{{ __('messages.price') }} {{ __('messages.starting_from') }}</div>
                <div class="min-price-value">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }} <span>/{{ __(messages.people) }}</span></div>
            @endif
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya tertarik dengan tour ' . $tour->title . '. Apakah tersedia?') }}"
                   target="_blank" class="min-sidebar-btn" style="margin-bottom:10px;">
                    <i class="fab fa-whatsapp" style="margin-right:6px;"></i> Pesan via WhatsApp
                </a>
            @endif
        </div>

        <div class="min-sidebar-card">
            <div class="min-block-label" style="margin-bottom:12px;">{{ __('messages.notes') }}</div>
            <ul class="min-sidebar-info-list">
                @if($tour->duration)
                    <li><i class="far fa-clock"></i> {{ $tour->duration }}</li>
                @endif
                @if($tour->location)
                    <li><i class="fas fa-map-marker-alt"></i> {{ $tour->location }}</li>
                @endif
                @if($tour->difficulty ?? null)
                    <li><i class="fas fa-signal"></i> {{ $tour->difficulty }}</li>
                @endif
                @if($tour->min_pax ?? null)
                    <li><i class="fas fa-users"></i> Min. {{ $tour->min_pax }} orang</li>
                @endif
                @if($tour->max_pax ?? null)
                    <li><i class="fas fa-user-friends"></i> Max. {{ $tour->max_pax }} orang</li>
                @endif
            </ul>
        </div>
    </div>
</div>

<!-- RELATED TOURS -->
@if(isset($relatedTours) && $relatedTours->count() > 0)
<section class="min-related">
    <div class="min-related-inner">
        <div class="min-block-label" style="margin-bottom:24px;">Tour Lainnya</div>
        <div class="min-related-grid">
            @foreach($relatedTours as $related)
                <a href="{{ $tourUrlBase . '/' . $related->slug }}" class="min-rel-card">
                    @if($related->thumbnail_url)
                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}" class="min-rel-card-img"/>
                    @elseif($related->images->count() > 0)
                        <img src="{{ $related->images->first()->url }}" alt="{{ $related->title }}" class="min-rel-card-img"/>
                    @else
                        <div class="min-rel-card-img-placeholder"><i class="fas fa-mountain"></i></div>
                    @endif
                    <div class="min-rel-card-body">
                        <div class="min-rel-card-title">{{ $related->title }}</div>
                        <div class="min-rel-card-footer">
                            @if($related->price_start_from)
                                <div class="min-rel-price"><span>Mulai </span>Rp {{ number_format($related->price_start_from, 0, ',', '.') }}</div>
                            @else
                                <div></div>
                            @endif
                            <span class="min-rel-arrow"><i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
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
    function tourGallery(images) {
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
