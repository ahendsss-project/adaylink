{{-- Template: Adventure — Bold immersive Tour Detail View --}}
@php
    $homeUrl = isset($demoTemplate) ? '/app/demo/' . $demoTemplate : (isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/');
    $pageUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/page' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page' : '/page');
    $tourUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/tour' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour' : '/tour');
    $primaryColor = $settings->primary_color ?? '#E76F51';
    $secondaryColor = $settings->secondary_color ?? '#1B4332';
    $fontHeading = $settings->font_heading ?? 'Outfit';
    $fontBody = $settings->font_body ?? 'Nunito';
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
            --bg: #FEFCF8;
            --fg: {{ $secondaryColor }};
            --muted: #5A6B5E;
            --accent: {{ $primaryColor }};
            --accent-dark: #C4533A;
            --forest: #1B4332;
            --forest-light: #2D6A4F;
            --sand: #F5EFE0;
            --card: #FFFFFF;
            --border: #D8D2C4;
            --warm: #D4A373;
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
        }

        /* ── NAVBAR ── */
        .adv-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(27,67,50,0.92); backdrop-filter: blur(12px); padding: 0 24px;
        }

        .adv-nav-inner {
            max-width: 1200px; margin: 0 auto; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
        }

        .adv-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }

        .adv-logo-icon {
            width: 36px; height: 36px; background: var(--accent); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 15px; overflow: hidden;
        }

        .adv-logo-icon img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }

        .adv-logo-text { font-family: var(--font-heading); font-size: 1.15rem; font-weight: 700; color: white; }

        .adv-nav-links { display: flex; align-items: center; gap: 6px; list-style: none; }

        .adv-nav-links a {
            padding: 8px 16px; border-radius: 8px; color: rgba(255,255,255,0.75);
            text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s;
        }

        .adv-nav-links a:hover { color: white; background: rgba(255,255,255,0.1); }

        .adv-nav-links .nav-cta { background: var(--accent); color: white !important; border-radius: 8px; }
        .adv-nav-links .nav-cta:hover { background: var(--accent-dark); }

        .adv-hamburger {
            display: none; background: none; border: none; color: white;
            font-size: 1.2rem; cursor: pointer; padding: 8px;
        }

        .adv-mobile {
            display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            z-index: 200; background: rgba(0,0,0,0.4);
        }

        .adv-mobile-panel {
            position: absolute; top: 0; right: 0; width: min(300px, 80vw); height: 100%;
            background: var(--forest); padding: 24px;
            transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .adv-mobile.open .adv-mobile-panel { transform: translateX(0); }

        .adv-mobile-close {
            background: none; border: none; color: rgba(255,255,255,0.6);
            font-size: 1.3rem; cursor: pointer; padding: 8px; float: right;
        }

        .adv-mobile-links { clear: both; padding-top: 24px; }

        .adv-mobile-links a {
            display: block; padding: 14px 0; color: rgba(255,255,255,0.85);
            text-decoration: none; font-size: 1rem; font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        /* ── HERO ── */
        .adv-hero {
            position: relative; height: 65vh; min-height: 460px;
            display: flex; align-items: flex-end; overflow: hidden;
        }

        .adv-hero-bg {
            position: absolute; inset: 0;
            background-size: cover; background-position: center;
        }

        .adv-hero-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(27,67,50,0.95) 0%, rgba(27,67,50,0.3) 60%, transparent 100%);
        }

        .adv-hero-content {
            position: relative; z-index: 2;
            padding: 0 24px 48px; max-width: 1200px; margin: 0 auto; width: 100%;
            animation: advFadeUp 0.8s ease-out both;
        }

        @keyframes advFadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .adv-breadcrumb {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.78rem; font-weight: 600; color: rgba(255,255,255,0.5);
            margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px;
        }

        .adv-breadcrumb a { color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s; }
        .adv-breadcrumb a:hover { color: white; }
        .adv-breadcrumb .sep { color: rgba(255,255,255,0.25); }

        .adv-hero h1 {
            font-family: var(--font-heading); font-size: clamp(2rem, 4.5vw, 3.4rem);
            font-weight: 800; color: white; line-height: 1.1; letter-spacing: -1px; margin-bottom: 16px;
        }

        .adv-hero-chips { display: flex; gap: 8px; flex-wrap: wrap; }

        .adv-chip {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 100px;
            background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.9);
            font-size: 0.8rem; font-weight: 600;
            border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(8px);
        }

        /* ── INFO STRIP ── */
        .adv-info-strip {
            max-width: 900px; margin: -32px auto 0; position: relative; z-index: 5;
            background: var(--card); border-radius: 16px; padding: 24px 28px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
        }

        .adv-info-items { display: flex; gap: 24px; flex-wrap: wrap; }

        .adv-info-item { display: flex; align-items: center; gap: 10px; }

        .adv-info-icon {
            width: 40px; height: 40px; background: var(--sand); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: var(--forest-light); font-size: 1rem; flex-shrink: 0;
        }

        .adv-info-item .label { font-size: 0.7rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
        .adv-info-item .value { font-weight: 700; font-size: 0.92rem; }

        .adv-info-price { text-align: right; }
        .adv-info-price .label { font-size: 0.7rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .adv-info-price .amount { font-family: var(--font-heading); font-size: 1.5rem; font-weight: 700; color: var(--accent); }

        /* ── CONTENT ── */
        .adv-content {
            max-width: 860px; margin: 0 auto; padding: 52px 24px 72px;
        }

        .adv-sec { margin-bottom: 44px; }

        .adv-sec-label {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 2px; color: var(--accent); margin-bottom: 10px;
        }

        .adv-sec-title {
            font-family: var(--font-heading); font-size: 1.4rem; font-weight: 700;
            letter-spacing: -0.3px; margin-bottom: 20px;
        }

        .adv-desc { font-size: 0.95rem; line-height: 1.85; color: #4A5D4E; white-space: pre-line; }

        /* ── ITINERARY: HORIZONTAL CARDS ── */
        .adv-itin { display: flex; flex-direction: column; gap: 12px; }

        .adv-itin-item {
            display: flex; gap: 16px; background: var(--card);
            border: 1px solid var(--border); border-radius: 12px;
            padding: 20px; transition: border-color 0.2s;
        }

        .adv-itin-item:hover { border-color: var(--accent); }

        .adv-itin-day {
            flex-shrink: 0; width: 52px; height: 52px;
            background: var(--forest); color: var(--accent);
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-family: var(--font-heading); font-weight: 700; font-size: 0.85rem;
        }

        .adv-itin-body h4 { font-family: var(--font-heading); font-size: 1rem; font-weight: 700; margin-bottom: 4px; }
        .adv-itin-body p { font-size: 0.88rem; color: var(--muted); line-height: 1.65; }

        /* ── INCLUDES / EXCLUDES ── */
        .adv-check-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }

        .adv-check-row {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 10px; font-size: 0.88rem; transition: background 0.2s;
        }

        .adv-check-row:hover { background: var(--sand); }

        .adv-check-row .ic {
            width: 22px; height: 22px; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; flex-shrink: 0;
        }

        .adv-check-row.inc .ic { background: #ECFDF5; color: #059669; }
        .adv-check-row.exc .ic { background: #FEF2F2; color: #DC2626; }

        /* ── NOTES ── */
        .adv-notes {
            background: #FFFBEB; border-left: 4px solid #F59E0B;
            border-radius: 0 10px 10px 0; padding: 20px 24px;
            font-size: 0.88rem; line-height: 1.75; color: #92400E; white-space: pre-line;
        }

        /* ── GALLERY ── */
        .adv-gallery-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;
        }

        .adv-gallery-grid .ag-item {
            border-radius: 10px; overflow: hidden; cursor: pointer;
            position: relative; aspect-ratio: 4/3;
        }

        .adv-gallery-grid .ag-item:first-child { grid-column: span 2; grid-row: span 2; aspect-ratio: auto; }

        .adv-gallery-grid .ag-item img {
            width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s;
        }

        .adv-gallery-grid .ag-item:hover img { transform: scale(1.05); }

        .ag-item-overlay {
            position: absolute; inset: 0; background: rgba(27,67,50,0.2);
            opacity: 0; transition: opacity 0.3s;
            display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem;
        }

        .adv-gallery-grid .ag-item:hover .ag-item-overlay { opacity: 1; }

        /* ── INLINE CTA ── */
        .adv-cta-inline {
            background: var(--forest); border-radius: 16px; padding: 32px 28px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 20px; flex-wrap: wrap; margin: 36px 0;
        }

        .adv-cta-text { color: white; }
        .adv-cta-text h3 { font-family: var(--font-heading); font-size: 1.3rem; font-weight: 700; margin-bottom: 4px; }
        .adv-cta-text p { font-size: 0.9rem; opacity: 0.8; }

        .btn-adv-wa {
            display: inline-flex; align-items: center; gap: 10px;
            background: #25D366; color: white; padding: 14px 28px;
            border-radius: 12px; font-size: 0.92rem; font-weight: 700;
            text-decoration: none; transition: all 0.2s; border: none; cursor: pointer;
        }

        .btn-adv-wa:hover { background: #1DA851; transform: translateY(-2px); }

        /* ── RELATED ── */
        .adv-related {
            background: var(--sand); padding: 64px 24px;
        }

        .adv-related-inner { max-width: 1200px; margin: 0 auto; }

        .adv-related-head { margin-bottom: 36px; }

        .adv-related-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;
        }

        .adv-rel-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 14px; overflow: hidden; text-decoration: none;
            color: inherit; transition: all 0.3s;
        }

        .adv-rel-card:hover { border-color: var(--accent); box-shadow: 0 4px 20px rgba(231,111,81,0.08); }

        .adv-rel-card-img { overflow: hidden; aspect-ratio: 4/3; }

        .adv-rel-card-img img {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s;
        }

        .adv-rel-card:hover .adv-rel-card-img img { transform: scale(1.04); }

        .adv-rel-card-body { padding: 18px; }

        .adv-rel-card-meta {
            font-size: 0.72rem; color: var(--muted); margin-bottom: 4px;
            display: flex; align-items: center; gap: 4px;
        }

        .adv-rel-card-body h3 {
            font-family: var(--font-heading); font-size: 1rem; font-weight: 700;
            margin-bottom: 10px; line-height: 1.3;
        }

        .adv-rel-card-footer {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 12px; border-top: 1px solid var(--border);
        }

        .adv-rel-price { font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; color: var(--accent); }
        .adv-rel-price span { font-family: var(--font-body); font-size: 0.7rem; font-weight: 400; color: var(--muted); }

        .adv-rel-btn {
            padding: 7px 14px; background: var(--forest); color: white;
            border-radius: 8px; font-size: 0.78rem; font-weight: 600;
            text-decoration: none; transition: background 0.2s;
        }

        .adv-rel-btn:hover { background: var(--accent); }

        /* ── FOOTER ── */
        .adv-footer {
            background: var(--forest); color: rgba(255,255,255,0.55);
            padding: 48px 24px 24px;
        }

        .adv-footer-inner { max-width: 1200px; margin: 0 auto; }

        .adv-footer-grid {
            display: grid; grid-template-columns: 1.5fr 1fr 1fr;
            gap: 40px; margin-bottom: 36px;
        }

        .adv-footer-brand .adv-logo-text { color: white; }
        .adv-footer-desc { font-size: 0.82rem; line-height: 1.7; margin-top: 12px; }

        .adv-footer-col h5 {
            color: white; font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;
        }

        .adv-footer-col ul { list-style: none; }
        .adv-footer-col ul li { margin-bottom: 8px; }
        .adv-footer-col ul a {
            color: rgba(255,255,255,0.45); text-decoration: none;
            font-size: 0.82rem; transition: color 0.2s;
        }
        .adv-footer-col ul a:hover { color: var(--accent); }

        .adv-footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.08); margin-bottom: 18px; }

        .adv-footer-bottom {
            display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem;
        }

        .adv-footer-bottom a { color: var(--accent); text-decoration: none; }

        /* ── LIGHTBOX ── */
        .lightbox-overlay {
            position: fixed; inset: 0; z-index: 300;
            background: rgba(0,0,0,0.95);
            display: flex; align-items: center; justify-content: center; padding: 20px;
        }

        .lightbox-overlay img { max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: 6px; }

        .lightbox-close {
            position: absolute; top: 20px; right: 24px;
            background: rgba(255,255,255,0.1); border: none; color: white;
            font-size: 1.2rem; cursor: pointer; z-index: 10;
            width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: background 0.2s;
        }

        .lightbox-close:hover { background: rgba(255,255,255,0.2); }

        .lightbox-prev, .lightbox-next {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(255,255,255,0.1); border: none; color: white;
            font-size: 1rem; width: 48px; height: 48px; border-radius: 50%;
            cursor: pointer; z-index: 10; transition: background 0.2s;
            display: flex; align-items: center; justify-content: center;
        }

        .lightbox-prev { left: 20px; }
        .lightbox-next { right: 20px; }
        .lightbox-prev:hover, .lightbox-next:hover { background: rgba(255,255,255,0.25); }

        .lightbox-counter {
            position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%);
            color: rgba(255,255,255,0.4); font-size: 0.82rem; font-weight: 500;
        }

        /* ── SHARE FAB ── */
        .share-fab { position: fixed; bottom: 24px; left: 24px; z-index: 99; }

        .share-fab-trigger {
            width: 48px; height: 48px; border-radius: 50%;
            background: var(--forest); color: white; border: none;
            font-size: 1rem; cursor: pointer; box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            display: flex; align-items: center; justify-content: center; transition: all 0.2s;
        }

        .share-fab-trigger:hover { background: var(--accent); transform: scale(1.08); }

        .share-fab-options {
            position: absolute; bottom: 58px; left: 0;
            display: flex; flex-direction: column; gap: 8px;
        }

        .share-fab-options a, .share-fab-options button {
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; text-decoration: none; border: none; cursor: pointer;
            font-size: 0.9rem; box-shadow: 0 2px 8px rgba(0,0,0,0.12); transition: transform 0.2s;
        }

        .share-fab-options a:hover, .share-fab-options button:hover { transform: scale(1.12); }

        /* ── RESPONSIVE ── */
        @media (max-width: 960px) {
            .adv-nav-links { display: none; }
            .adv-hamburger { display: block; }
            .adv-mobile.open { display: block; }
            .adv-hero { height: 50vh; min-height: 380px; }
            .adv-hero-content { padding: 0 20px 40px; }
            .adv-hero h1 { font-size: clamp(1.6rem, 5vw, 2.2rem); }
            .adv-info-strip {
                margin: -24px 16px 0; padding: 20px;
                flex-direction: column; align-items: flex-start;
            }
            .adv-info-items { gap: 16px; }
            .adv-info-price { text-align: left; }
            .adv-content { padding: 36px 16px 60px; }
            .adv-sec { margin-bottom: 36px; }
            .adv-sec-title { font-size: 1.25rem; }
            .adv-check-grid { grid-template-columns: 1fr; }
            .adv-gallery-grid { grid-template-columns: repeat(2, 1fr); }
            .adv-gallery-grid .ag-item:first-child { grid-column: span 2; grid-row: span 1; }
            .adv-cta-inline { flex-direction: column; text-align: center; padding: 28px 20px; }
            .adv-related { padding: 48px 16px; }
            .adv-related-grid { grid-template-columns: 1fr; }
            .adv-footer { padding: 36px 16px 20px; }
            .adv-footer-grid { grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
            .adv-footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .adv-hero { height: 45vh; min-height: 320px; }
            .adv-hero-content { padding: 0 16px 32px; }
            .adv-hero h1 { font-size: 1.5rem; }
            .adv-chip { padding: 5px 10px; font-size: 0.72rem; }
            .adv-info-strip { margin: -20px 12px 0; padding: 16px; }
            .adv-info-icon { width: 36px; height: 36px; font-size: 0.9rem; }
            .adv-content { padding: 28px 12px 48px; }
            .adv-sec { margin-bottom: 28px; }
            .adv-sec-label { font-size: 0.65rem; }
            .adv-sec-title { font-size: 1.15rem; margin-bottom: 14px; }
            .adv-desc { font-size: 0.9rem; }
            .adv-itin-item { padding: 16px; gap: 12px; }
            .adv-itin-day { width: 44px; height: 44px; font-size: 0.78rem; }
            .adv-itin-body h4 { font-size: 0.92rem; }
            .adv-gallery-grid { grid-template-columns: 1fr 1fr; gap: 4px; }
            .adv-gallery-grid .ag-item:first-child { grid-column: span 1; }
            .adv-cta-inline { padding: 24px 16px; }
            .adv-cta-text h3 { font-size: 1.1rem; }
            .btn-adv-wa { padding: 12px 22px; font-size: 0.85rem; }
            .adv-related { padding: 36px 12px; }
            .adv-rel-card-body { padding: 14px; }
            .adv-footer { padding: 28px 12px 16px; }
            .adv-footer-grid { grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px; }
            .adv-footer-col h5 { margin-bottom: 10px; }
        }
    </style>
</head>
<body x-data="{ drawerOpen: false }">

<!-- NAVBAR -->
<div class="adv-nav">
    <div class="adv-nav-inner">
        <a href="{{ $homeUrl }}" class="adv-logo">
            <div class="adv-logo-icon">
                @if($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                @else
                    <i class="fas fa-mountain"></i>
                @endif
            </div>
            <span class="adv-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="adv-nav-links">
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ $pageUrlBase . '/' . $p->slug }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="nav-cta"><i class="fab fa-whatsapp"></i> Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="adv-hamburger" @click="drawerOpen = true" aria-label="Menu"><i class="fas fa-bars"></i></button>
    </div>
</div>

<!-- Mobile Drawer -->
<div class="adv-mobile" :class="{ 'open': drawerOpen }" x-show="drawerOpen"
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click.self="drawerOpen = false">
    <div class="adv-mobile-panel" @click.stop>
        <button class="adv-mobile-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="adv-mobile-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p)
                <a href="{{ $pageUrlBase . '/' . $p->slug }}" @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false"><i class="fab fa-whatsapp"></i> WhatsApp</a>
            @endif
        </div>
    </div>
</div>

<!-- HERO -->
<div class="adv-hero">
    <div class="adv-hero-bg" style="background-image: url('{{ $tour->thumbnail_url ?? 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=1800&q=80' }}');"></div>
    <div class="adv-hero-overlay"></div>
    <div class="adv-hero-content">
        <div class="adv-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep">/</span>
            <a href="{{ $homeUrl }}#tours">Tour</a>
            <span class="sep">/</span>
            <span style="color:rgba(255,255,255,0.8)">{{ $tour->title }}</span>
        </div>
        <h1>{{ $tour->title }}</h1>
        <div class="adv-hero-chips">
            @if($tour->duration_text)
                <span class="adv-chip"><i class="far fa-clock"></i> {{ $tour->duration_text }}</span>
            @endif
            @if($tour->is_featured)
                <span class="adv-chip"><i class="fas fa-fire"></i> Best Seller</span>
            @endif
            @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
                <span class="adv-chip"><i class="fas fa-route"></i> {{ count($tour->itinerary) }} Hari</span>
            @endif
        </div>
    </div>
</div>

<!-- INFO STRIP -->
<div style="padding:0 24px;">
    <div class="adv-info-strip">
        <div class="adv-info-items">
            @if($tour->duration_text)
                <div class="adv-info-item">
                    <div class="adv-info-icon"><i class="far fa-clock"></i></div>
                    <div><div class="label">{{ __('messages.duration') }}</div><div class="value">{{ $tour->duration_text }}</div></div>
                </div>
            @endif
            @if(is_array($tour->includes) && count($tour->includes) > 0)
                <div class="adv-info-item">
                    <div class="adv-info-icon"><i class="fas fa-check-circle"></i></div>
                    <div><div class="label">{{ __('messages.includes') }}</div><div class="value">{{ count($tour->includes) }} item</div></div>
                </div>
            @endif
            @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
                <div class="adv-info-item">
                    <div class="adv-info-icon"><i class="fas fa-map-marked-alt"></i></div>
                    <div><div class="label">Perjalanan</div><div class="value">{{ count($tour->itinerary) }} hari</div></div>
                </div>
            @endif
        </div>
        @if($tour->price_start_from)
            <div class="adv-info-price">
                <div class="label">{{ __('messages.starting_from') }}</div>
                <div class="amount">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
            </div>
        @endif
    </div>
</div>

<!-- CONTENT -->
<div class="adv-content">

    @if($tour->description)
    <div class="adv-sec">
        <div class="adv-sec-label">Tentang Tour</div>
        <h2 class="adv-sec-title">Deskripsi</h2>
        <div class="adv-desc">{{ $tour->description }}</div>
    </div>
    @endif

    @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
    <div class="adv-sec">
        <div class="adv-sec-label">Rencana Perjalanan</div>
        <h2 class="adv-sec-title">Itinerary</h2>
        <div class="adv-itin">
            @foreach($tour->itinerary as $i => $item)
                <div class="adv-itin-item">
                    <div class="adv-itin-day">D{{ $i + 1 }}</div>
                    <div class="adv-itin-body">
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

    @if(is_array($tour->includes) && count($tour->includes) > 0)
    <div class="adv-sec">
        <div class="adv-sec-label">{{ __('messages.includes') }}</div>
        <h2 class="adv-sec-title">{{ __('messages.includes') }}</h2>
        <div class="adv-check-grid">
            @foreach($tour->includes as $item)
                <div class="adv-check-row inc"><span class="ic"><i class="fas fa-check"></i></span> {{ $item }}</div>
            @endforeach
        </div>
    </div>
    @endif

    @if(is_array($tour->excludes) && count($tour->excludes) > 0)
    <div class="adv-sec">
        <div class="adv-sec-label">{{ __('messages.includes') }}</div>
        <h2 class="adv-sec-title">{{ __('messages.excludes') }}</h2>
        <div class="adv-check-grid">
            @foreach($tour->excludes as $item)
                <div class="adv-check-row exc"><span class="ic"><i class="fas fa-times"></i></span> {{ $item }}</div>
            @endforeach
        </div>
    </div>
    @endif

    @if($tour->notes)
    <div class="adv-sec">
        <div class="adv-sec-label">{{ __('messages.notes') }}</div>
        <h2 class="adv-sec-title">{{ __('messages.notes') }}</h2>
        <div class="adv-notes">{{ $tour->notes }}</div>
    </div>
    @endif

    @if($tour->images->count() > 0)
    <div class="adv-sec" x-data="tourGallery(@js($tour->images->values()->all()))" x-init="init()">
        <div class="adv-sec-label">{{ __('messages.gallery') }}</div>
        <h2 class="adv-sec-title">{{ __('messages.gallery_title') }}</h2>
        <div class="adv-gallery-grid">
            @foreach($tour->images as $index => $img)
                <div class="ag-item" @click="open({{ $index }})">
                    <img src="{{ $img->url }}" alt="{{ $img->alt_text ?? $tour->title }}"/>
                    <div class="ag-item-overlay"><i class="fas fa-expand"></i></div>
                </div>
            @endforeach
        </div>
        <template x-if="isOpen">
            <div class="lightbox-overlay" @click.self="close()" @keydown.escape="close()"
                 x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <button class="lightbox-close" @click="close()"><i class="fas fa-times"></i></button>
                <button class="lightbox-prev" @click.stop="prev()"><i class="fas fa-chevron-left"></i></button>
                <button class="lightbox-next" @click.stop="next()"><i class="fas fa-chevron-right"></i></button>
                <img :src="images[currentIndex]?.url || ''" :alt="images[currentIndex]?.alt_text || ''" @click.stop/>
                <div class="lightbox-counter"><span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span></div>
            </div>
        </template>
    </div>
    @endif

    @if($website->contact_whatsapp)
        @php $waMsg = "Halo, saya tertarik dengan paket tour *{$tour->title}*."; @endphp
        <div class="adv-cta-inline">
            <div class="adv-cta-text">
                <h3>Tertarik dengan petualangan ini?</h3>
                <p>Hubungi kami via WhatsApp untuk info lebih lanjut.</p>
            </div>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode($waMsg) }}" target="_blank" class="btn-adv-wa">
                <i class="fab fa-whatsapp" style="font-size:1.2rem;"></i> Chat WhatsApp
            </a>
        </div>
    @endif

</div>

{{-- RELATED --}}
@if($relatedTours->count() > 0)
<section class="adv-related">
    <div class="adv-related-inner">
        <div class="adv-related-head">
            <div class="adv-sec-label"><i class="fas fa-compass"></i> Rekomendasi</div>
            <h2 class="adv-sec-title">Paket Tour <em>Lainnya</em></h2>
        </div>
        <div class="adv-related-grid">
            @foreach($relatedTours as $related)
                <a href="{{ $tourUrlBase . '/' . $related->slug }}" class="adv-rel-card">
                    <div class="adv-rel-card-img">
                        @if($related->thumbnail_url)
                            <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}"/>
                        @else
                            <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=400&q=80" alt="{{ $related->title }}"/>
                        @endif
                    </div>
                    <div class="adv-rel-card-body">
                        @if($related->duration_text)
                            <div class="adv-rel-card-meta"><i class="far fa-clock"></i> {{ $related->duration_text }}</div>
                        @endif
                        <h3>{{ $related->title }}</h3>
                        <div class="adv-rel-card-footer">
                            @if($related->price_start_from)
                                <div class="adv-rel-price"><span>Mulai </span>Rp {{ number_format($related->price_start_from, 0, ',', '.') }}</div>
                            @endif
                            <span class="adv-rel-btn">Detail</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FOOTER -->
<footer class="adv-footer">
    <div class="adv-footer-inner">
        <div class="adv-footer-grid">
            <div class="adv-footer-brand">
                <a href="{{ $homeUrl }}" class="adv-logo">
                    <div class="adv-logo-icon">
                        @if($website->logo_url)
                            <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                        @else
                            <i class="fas fa-mountain"></i>
                        @endif
                    </div>
                    <span class="adv-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
                </a>
                <p class="adv-footer-desc">{{ $settings->description ?? 'Powered by adaylink — platform website untuk driver dan agen wisata.' }}</p>
            </div>
            <div class="adv-footer-col">
                <h5>Halaman</h5>
                <ul>
                    @foreach($pages as $p)
                        <li><a href="{{ $pageUrlBase . '/' . $p->slug }}">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="adv-footer-col">
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
                        <li><a href="{{ $settings->social_instagram }}" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></li>
                    @endif
                    @if($website->contact_whatsapp)
                        <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <hr class="adv-footer-divider">
        <div class="adv-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
            <span>Powered by <a href="#">adaylink</a></span>
        </div>
    </div>
</footer>

{{-- Floating WhatsApp --}}
@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
       target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:24px;right:24px;z-index:99;background:#25D366;color:white;width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(37,211,102,0.3);text-decoration:none;font-size:22px;"
       x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(20px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},1000)">
        <i class="fab fa-whatsapp" style="font-size:22px;"></i>
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
            <button @click="copyLink()" style="background:var(--forest);" :style="copied ? 'background:#059669' : ''">
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
