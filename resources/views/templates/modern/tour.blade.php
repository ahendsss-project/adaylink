{{-- Template: Modern Travel — Tour Detail View --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
    $primaryColor = $settings->primary_color ?? '#FF6B35';
    $secondaryColor = $settings->secondary_color ?? '#0F172A';
    $fontHeading = $settings->font_heading ?? 'Poppins';
    $fontBody = $settings->font_body ?? 'Plus Jakarta Sans';
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
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700;800&family={{ urlencode($fontBody) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        :root {
            --bg: #F8FAFC;
            --fg: #0F172A;
            --muted: #64748B;
            --accent: {{ $primaryColor }};
            --accent-dark: #E55A2B;
            --accent-soft: #FFF4EE;
            --navy: #0F172A;
            --navy-light: #1E293B;
            --sky: #E0F2FE;
            --card: #FFFFFF;
            --border: #E2E8F0;
            --surface: #F1F5F9;
            --success: #059669;
            --font-heading: '{{ $fontHeading }}', sans-serif;
            --font-body: '{{ $fontBody }}', sans-serif;
            --radius: 14px;
            --shadow: 0 4px 24px rgba(15,23,42,0.06);
            --shadow-lg: 0 12px 40px rgba(15,23,42,0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--fg);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── NAVBAR ── */
        .mod-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
        }

        .mod-nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mod-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .mod-logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .mod-logo-icon img { width: 100%; height: 100%; object-fit: cover; }

        .mod-logo-text {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--fg);
        }

        .mod-nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }

        .mod-nav-links a {
            padding: 8px 16px;
            border-radius: 10px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .mod-nav-links a:hover { color: var(--fg); background: var(--surface); }

        .mod-nav-cta {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark)) !important;
            color: white !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 16px rgba(255,107,53,0.25) !important;
        }

        .mod-hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--fg);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 10px;
        }

        /* ── MOBILE DRAWER ── */
        .mod-drawer {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 200;
            background: rgba(15,23,42,0.3);
        }

        .mod-drawer-panel {
            position: absolute;
            top: 0; right: 0;
            width: min(320px, 85vw);
            height: 100%;
            background: var(--card);
            padding: 24px;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mod-drawer.open .mod-drawer-panel { transform: translateX(0); }

        .mod-drawer-close {
            background: none; border: none; color: var(--muted);
            font-size: 1.3rem; cursor: pointer; padding: 8px; float: right;
        }

        .mod-drawer-links { clear: both; padding-top: 24px; }
        .mod-drawer-links a {
            display: block; padding: 14px 0; color: var(--fg);
            text-decoration: none; font-size: 1rem; font-weight: 500;
            border-bottom: 1px solid var(--border);
        }
        .mod-drawer-links a:last-child { border-bottom: none; }

        /* ── HERO ── */
        .mod-hero { padding-top: 68px; }

        .mod-hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px 0;
            animation: modIn 0.6s ease both;
        }

        @keyframes modIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mod-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .mod-breadcrumb a { color: var(--muted); text-decoration: none; }
        .mod-breadcrumb a:hover { color: var(--accent); }
        .mod-breadcrumb .sep { font-size: 0.6rem; }

        .mod-hero-title {
            font-family: var(--font-heading);
            font-size: 2.6rem;
            font-weight: 800;
            color: var(--fg);
            letter-spacing: -1px;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .mod-hero-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .mod-hero-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 500;
        }

        .mod-hero-meta-item i { color: var(--accent); font-size: 0.8rem; }

        .mod-hero-image {
            width: 100%;
            aspect-ratio: 21/9;
            overflow: hidden;
            border-radius: 16px;
            background: var(--surface);
        }

        .mod-hero-image img {
            width: 100%; height: 100%;
            object-fit: cover; display: block;
        }

        .mod-hero-image-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); font-size: 2rem;
        }

        /* ── CONTENT LAYOUT ── */
        .mod-content-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px 72px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 40px;
            align-items: start;
        }

        /* ── MAIN CONTENT ── */
        .mod-main { min-width: 0; }

        .mod-content-block { margin-bottom: 44px; }
        .mod-content-block:last-child { margin-bottom: 0; }

        .mod-block-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 16px;
        }

        .mod-description {
            font-size: 0.95rem;
            line-height: 1.85;
            color: var(--fg);
        }

        .mod-description p { margin-bottom: 16px; }

        /* ── ITINERARY ── */
        .mod-itinerary-list { list-style: none; }

        .mod-itinerary-item {
            display: flex;
            gap: 16px;
            padding: 20px 0;
            border-bottom: 1px solid var(--border);
        }

        .mod-itinerary-item:last-child { border-bottom: none; }

        .mod-itinerary-day {
            flex-shrink: 0;
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-heading);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .mod-itinerary-body { flex: 1; min-width: 0; }

        .mod-itinerary-title {
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 4px;
        }

        .mod-itinerary-desc {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── INCLUDES / EXCLUDES ── */
        .mod-check-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .mod-check-list { list-style: none; }

        .mod-check-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 8px 0;
            font-size: 0.88rem;
            color: var(--fg);
            line-height: 1.5;
        }

        .mod-check-list li i { margin-top: 3px; font-size: 0.7rem; flex-shrink: 0; }
        .mod-check-include li i { color: var(--success); }
        .mod-check-exclude li i { color: #DC2626; }

        /* ── GALLERY ── */
        .mod-gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .mod-gallery-item {
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 12px;
            cursor: pointer;
        }

        .mod-gallery-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .mod-gallery-item:hover img { transform: scale(1.08); }

        /* ── SIDEBAR ── */
        .mod-sidebar { position: sticky; top: 88px; }

        .mod-sidebar-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }

        .mod-price-label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .mod-price-value {
            font-family: var(--font-heading);
            font-size: 2rem;
            font-weight: 800;
            color: var(--accent);
            letter-spacing: -1px;
            margin-bottom: 20px;
        }

        .mod-price-value span {
            font-size: 0.85rem;
            font-weight: 400;
            color: var(--muted);
        }

        .mod-sidebar-btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-family: var(--font-heading);
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(255,107,53,0.3);
            transition: all 0.25s;
            border: none;
            cursor: pointer;
        }

        .mod-sidebar-btn:hover { opacity: 0.9; transform: translateY(-1px); }

        .mod-sidebar-info-list { list-style: none; }

        .mod-sidebar-info-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.85rem;
            color: var(--fg);
        }

        .mod-sidebar-info-list li:last-child { border-bottom: none; }
        .mod-sidebar-info-list li i { color: var(--accent); font-size: 0.8rem; width: 16px; text-align: center; }

        /* ── NOTES ── */
        .mod-notes {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.7;
            padding: 18px;
            background: var(--accent-soft);
            border-radius: 12px;
            border-left: 3px solid var(--accent);
        }

        /* ── RELATED ── */
        .mod-related {
            background: var(--card);
        }

        .mod-related-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 56px 24px 72px;
        }

        .mod-related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .mod-rel-card {
            border-radius: var(--radius);
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            box-shadow: var(--shadow);
            transition: all 0.3s;
        }

        .mod-rel-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

        .mod-rel-card-img {
            width: 100%;
            aspect-ratio: 16/10;
            object-fit: cover;
            display: block;
        }

        .mod-rel-card-img-placeholder {
            width: 100%;
            aspect-ratio: 16/10;
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 1.2rem;
        }

        .mod-rel-card-body { padding: 16px; }

        .mod-rel-card-title {
            font-family: var(--font-heading);
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 8px;
        }

        .mod-rel-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mod-rel-price {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--accent);
        }

        .mod-rel-price span {
            font-weight: 400;
            font-size: 0.72rem;
            color: var(--muted);
        }

        .mod-rel-arrow {
            width: 30px; height: 30px;
            background: var(--accent-soft);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 0.65rem;
        }

        /* ── FOOTER ── */
        .mod-footer { background: var(--navy); color: rgba(255,255,255,0.5); }
        .mod-footer-inner { max-width: 1200px; margin: 0 auto; }

        .mod-footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 48px;
            padding: 56px 24px 32px;
        }

        .mod-footer-brand .mod-logo-text { color: white; }
        .mod-footer-desc { font-size: 0.85rem; line-height: 1.7; margin-top: 14px; max-width: 320px; }

        .mod-footer-col h5 {
            color: white; font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 16px;
        }

        .mod-footer-col ul { list-style: none; }
        .mod-footer-col ul li { margin-bottom: 10px; }
        .mod-footer-col ul a {
            color: rgba(255,255,255,0.4); text-decoration: none;
            font-size: 0.85rem; transition: color 0.2s;
        }
        .mod-footer-col ul a:hover { color: var(--accent); }

        .mod-footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin-bottom: 20px; }

        .mod-footer-bottom {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.75rem; padding: 0 24px 24px;
        }

        .mod-footer-bottom a { color: var(--accent); text-decoration: none; }

        /* ── SOCIAL SHARE FAB ── */
        .share-fab { position: fixed; bottom: 24px; left: 24px; z-index: 90; }
        .share-fab-options { display: flex; flex-direction: column; gap: 8px; margin-bottom: 8px; }
        .share-fab-options a, .share-fab-options button {
            width: 40px; height: 40px; border-radius: 12px; border: none;
            color: white; display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 14px; transition: transform 0.2s;
        }
        .share-fab-options a:hover, .share-fab-options button:hover { transform: scale(1.1); }
        .share-fab-trigger {
            width: 46px; height: 46px; border-radius: 14px; border: none;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white; font-size: 16px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 20px rgba(255,107,53,0.3); transition: all 0.25s;
        }
        .share-fab-trigger:hover { transform: scale(1.05); }

        /* ══════════ RESPONSIVE ══════════ */

        @media (max-width: 960px) {
            .mod-nav-links { display: none; }
            .mod-hamburger { display: block; }
            .mod-drawer { display: block; }

            .mod-hero-inner { padding: 32px 24px 0; }
            .mod-hero-title { font-size: 2rem; }
            .mod-hero-image { aspect-ratio: 16/9; }

            .mod-content-wrap {
                grid-template-columns: 1fr;
                gap: 32px;
                padding: 36px 24px 56px;
            }

            .mod-sidebar { position: static; }
            .mod-check-grid { grid-template-columns: 1fr; gap: 16px; }

            .mod-related-inner { padding: 44px 24px 56px; }
            .mod-related-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .mod-footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
            .mod-footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .mod-nav-inner { height: 56px; }
            .mod-logo-icon { width: 32px; height: 32px; font-size: 13px; }
            .mod-logo-text { font-size: 0.95rem; }
            .mod-hero { padding-top: 56px; }
            .mod-hero-inner { padding: 24px 16px 0; }
            .mod-hero-title { font-size: 1.6rem; letter-spacing: -0.5px; }
            .mod-hero-meta { gap: 12px; }
            .mod-hero-image { aspect-ratio: 16/10; border-radius: 12px; }

            .mod-content-wrap { padding: 28px 16px 48px; gap: 28px; }
            .mod-content-block { margin-bottom: 32px; }
            .mod-description { font-size: 0.9rem; }
            .mod-itinerary-item { padding: 16px 0; gap: 12px; }
            .mod-itinerary-day { width: 44px; height: 44px; font-size: 0.72rem; }
            .mod-gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            .mod-sidebar-card { padding: 20px; }
            .mod-price-value { font-size: 1.6rem; }

            .mod-related-inner { padding: 36px 16px 48px; }
            .mod-related-grid { grid-template-columns: 1fr; gap: 12px; }

            .mod-footer-grid { grid-template-columns: 1fr; gap: 24px; padding: 40px 16px 24px; }

            .share-fab { bottom: 16px; left: 16px; }
            .share-fab-trigger { width: 40px; height: 40px; font-size: 14px; }
            .share-fab-options a, .share-fab-options button { width: 36px; height: 36px; font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="mod-nav">
    <div class="mod-nav-inner">
        <a href="{{ $homeUrl }}" class="mod-logo">
            <div class="mod-logo-icon">
                @if($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                @else
                    <i class="fas fa-paper-plane"></i>
                @endif
            </div>
            <span class="mod-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="mod-nav-links">
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="mod-nav-cta"><i class="fab fa-whatsapp" style="margin-right:4px;"></i> Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="mod-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- MOBILE DRAWER -->
<div class="mod-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="mod-drawer-panel">
        <button class="mod-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="mod-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}"
                   @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false">{{ __('messages.contact') }}</a>
            @endif
        </div>
    </div>
</div>

<!-- HERO -->
<section class="mod-hero">
    <div class="mod-hero-inner">
        <div class="mod-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span>Tour</span>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span style="color:var(--fg);">{{ $tour->title }}</span>
        </div>
        <h1 class="mod-hero-title">{{ $tour->title }}</h1>
        <div class="mod-hero-meta">
            @if($tour->duration_text ?? $tour->duration)
                <div class="mod-hero-meta-item"><i class="far fa-clock"></i> {{ $tour->duration_text ?? $tour->duration }}</div>
            @endif
            @if($tour->location)
                <div class="mod-hero-meta-item"><i class="fas fa-map-marker-alt"></i> {{ $tour->location }}</div>
            @endif
            @if($tour->price_start_from)
                <div class="mod-hero-meta-item"><i class="fas fa-tag"></i> Mulai Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
            @endif
        </div>
        <div class="mod-hero-image">
            @if($tour->thumbnail_url)
                <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}"/>
            @elseif($tour->images->count() > 0)
                <img src="{{ $tour->images->first()->url }}" alt="{{ $tour->title }}"/>
            @else
                <div class="mod-hero-image-placeholder"><i class="fas fa-mountain"></i></div>
            @endif
        </div>
    </div>
</section>

<!-- CONTENT -->
<div class="mod-content-wrap">
    <div class="mod-main">
        @if($tour->description)
        <div class="mod-content-block">
            <div class="mod-block-label">Deskripsi</div>
            <div class="mod-description">{!! $tour->description !!}</div>
        </div>
        @endif

        @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
        <div class="mod-content-block">
            <div class="mod-block-label">Itinerary</div>
            <ul class="mod-itinerary-list">
                @foreach($tour->itinerary as $i => $item)
                    <li class="mod-itinerary-item">
                        <div class="mod-itinerary-day">D{{ $i + 1 }}</div>
                        <div class="mod-itinerary-body">
                            <div class="mod-itinerary-title">{{ is_array($item) ? ($item['title'] ?? 'Day ' . ($i + 1)) : $item }}</div>
                            @if(is_array($item) && isset($item['description']))
                                <div class="mod-itinerary-desc">{{ $item['description'] }}</div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if((is_array($tour->includes) && count($tour->includes) > 0) || (is_array($tour->excludes) && count($tour->excludes) > 0))
        <div class="mod-content-block">
            <div class="mod-block-label">{{ __('messages.includes') }} & {{ __('messages.excludes') }}</div>
            <div class="mod-check-grid">
                @if(is_array($tour->includes) && count($tour->includes) > 0)
                    <div>
                        <div style="font-family:var(--font-heading);font-size:0.85rem;font-weight:700;margin-bottom:12px;color:var(--fg);">{{ __('messages.includes') }}</div>
                        <ul class="mod-check-list mod-check-include">
                            @foreach($tour->includes as $item)
                                <li><i class="fas fa-check-circle"></i> {{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(is_array($tour->excludes) && count($tour->excludes) > 0)
                    <div>
                        <div style="font-family:var(--font-heading);font-size:0.85rem;font-weight:700;margin-bottom:12px;color:var(--fg);">{{ __('messages.excludes') }}</div>
                        <ul class="mod-check-list mod-check-exclude">
                            @foreach($tour->excludes as $item)
                                <li><i class="fas fa-times-circle"></i> {{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        @endif

        @if($tour->images->count() > 1)
        <div class="mod-content-block">
            <div class="mod-block-label">{{ __('messages.gallery') }}</div>
            <div class="mod-gallery-grid"
                 x-data="tourGallery(@js($tour->images->values()->all()))" x-init="init()">
                @foreach($tour->images as $i => $img)
                    <div class="mod-gallery-item" @click="open({{ $i }})">
                        <img src="{{ $img->url }}" alt="{{ $img->alt_text ?? $tour->title }}" loading="lazy"/>
                    </div>
                @endforeach

                @if($features['gallery_lightbox'] ?? false)
                <template x-if="isOpen">
                    <div style="position:fixed;top:0;left:0;right:0;bottom:0;z-index:300;background:rgba(0,0,0,0.92);display:flex;align-items:center;justify-content:center;"
                         @click.self="close()" x-transition>
                        <button @click="close()" style="position:absolute;top:20px;right:20px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;padding:8px;">&times;</button>
                        <button @click="prev()" style="position:absolute;left:16px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;padding:8px;">&#8249;</button>
                        <img :src="images[currentIndex]?.url || ''" style="max-width:90vw;max-height:85vh;object-fit:contain;border-radius:8px;"/>
                        <button @click="next()" style="position:absolute;right:16px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;padding:8px;">&#8250;</button>
                        <div style="position:absolute;bottom:20px;color:rgba(255,255,255,0.6);font-size:0.8rem;" x-text="(currentIndex + 1) + ' / ' + images.length"></div>
                    </div>
                </template>
                @endif
            </div>
        </div>
        @endif

        @if($tour->notes)
        <div class="mod-content-block">
            <div class="mod-block-label">{{ __('messages.notes') }}</div>
            <div class="mod-notes">{!! $tour->notes !!}</div>
        </div>
        @endif
    </div>

    <!-- SIDEBAR -->
    <div class="mod-sidebar">
        <div class="mod-sidebar-card">
            @if($tour->price_start_from)
                <div class="mod-price-label">{{ __('messages.price') }} {{ __('messages.starting_from') }}</div>
                <div class="mod-price-value">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }} <span>/{{ __(messages.people) }}</span></div>
            @endif
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya tertarik dengan tour ' . $tour->title . '. Apakah tersedia?') }}"
                   target="_blank" class="mod-sidebar-btn" style="margin-bottom:10px;">
                    <i class="fab fa-whatsapp" style="margin-right:6px;"></i> Pesan via WhatsApp
                </a>
            @endif
        </div>

        <div class="mod-sidebar-card">
            <div class="mod-block-label" style="margin-bottom:12px;">{{ __('messages.notes') }}</div>
            <ul class="mod-sidebar-info-list">
                @if($tour->duration_text ?? $tour->duration)
                    <li><i class="far fa-clock"></i> {{ $tour->duration_text ?? $tour->duration }}</li>
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
<section class="mod-related">
    <div class="mod-related-inner">
        <div class="mod-block-label" style="margin-bottom:24px;">Tour Lainnya</div>
        <div class="mod-related-grid">
            @foreach($relatedTours as $related)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $related->slug : '/tour/' . $related->slug }}" class="mod-rel-card">
                    @if($related->thumbnail_url)
                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}" class="mod-rel-card-img"/>
                    @elseif($related->images->count() > 0)
                        <img src="{{ $related->images->first()->url }}" alt="{{ $related->title }}" class="mod-rel-card-img"/>
                    @else
                        <div class="mod-rel-card-img-placeholder"><i class="fas fa-mountain"></i></div>
                    @endif
                    <div class="mod-rel-card-body">
                        <div class="mod-rel-card-title">{{ $related->title }}</div>
                        <div class="mod-rel-card-footer">
                            @if($related->price_start_from)
                                <div class="mod-rel-price"><span>Mulai </span>Rp {{ number_format($related->price_start_from, 0, ',', '.') }}</div>
                            @else
                                <div></div>
                            @endif
                            <span class="mod-rel-arrow"><i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FOOTER -->
<footer class="mod-footer">
    <div class="mod-footer-inner">
        <div class="mod-footer-grid">
            <div class="mod-footer-brand">
                <a href="{{ $homeUrl }}" class="mod-logo">
                    <div class="mod-logo-icon">
                        @if($website->logo_url)
                            <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                        @else
                            <i class="fas fa-paper-plane"></i>
                        @endif
                    </div>
                    <span class="mod-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
                </a>
                <p class="mod-footer-desc">{{ $settings->description ?? 'Powered by adaylink — platform website untuk driver dan agen wisata.' }}</p>
            </div>
            <div class="mod-footer-col">
                <h5>Halaman</h5>
                <ul>
                    @foreach($pages as $p)
                        <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="mod-footer-col">
                <h5>Kontak</h5>
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
        <hr class="mod-footer-divider">
        <div class="mod-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
            <span>Powered by <a href="#">adaylink</a></span>
        </div>
    </div>
</footer>

{{-- Floating WhatsApp --}}
@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
       target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:24px;right:24px;z-index:99;background:#25D366;color:white;width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(37,211,102,0.3);text-decoration:none;font-size:22px;"
       x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(20px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},1000)">
        <i class="fab fa-whatsapp" style="font-size:22px;"></i>
    </a>
@endif

@if($features['social_share'] ?? false)
    <div class="share-fab" x-data="socialShare()" x-init="init()">
        <div class="share-fab-options" x-show="isOpen"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            <a :href="'https://wa.me/?text=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#25D366;"><i class="fab fa-whatsapp"></i></a>
            <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#1877F2;"><i class="fab fa-facebook-f"></i></a>
            <a :href="'https://twitter.com/intent/tweet?url=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#000;"><i class="fab fa-x-twitter"></i></a>
            <button @click="copyLink()" style="background:var(--navy);" :style="copied ? 'background:#059669' : ''">
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
