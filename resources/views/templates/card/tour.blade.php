{{-- Template: Card + Conversion — Tour Detail with Sticky Bottom Bar --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
    $primaryColor = $settings->primary_color ?? '#4F46E5';
    $secondaryColor = $settings->secondary_color ?? '#1E1B4B';
    $fontHeading = $settings->font_heading ?? 'Sora';
    $fontBody = $settings->font_body ?? 'Figtree';
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
            --bg: #F9FAFB;
            --fg: #1E1B4B;
            --muted: #6B7280;
            --accent: {{ $primaryColor }};
            --accent-dark: #3730A3;
            --accent-soft: #EEF2FF;
            --green: #059669;
            --green-soft: #ECFDF5;
            --card: #FFFFFF;
            --border: #E5E7EB;
            --surface: #F3F4F6;
            --font-heading: '{{ $fontHeading }}', sans-serif;
            --font-body: '{{ $fontBody }}', sans-serif;
            --radius: 16px;
            --shadow: 0 2px 16px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--fg);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            padding-bottom: 80px;
        }

        /* ── NAVBAR ── */
        .crd-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(229,231,235,0.6);
            transition: all 0.3s;
        }

        .crd-nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .crd-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .crd-logo-icon { width: 36px; height: 36px; background: var(--accent); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; overflow: hidden; flex-shrink: 0; }
        .crd-logo-icon img { width: 100%; height: 100%; object-fit: cover; }
        .crd-logo-text { font-family: var(--font-heading); font-size: 1.05rem; font-weight: 700; color: var(--fg); }
        .crd-nav-links { display: flex; align-items: center; gap: 4px; list-style: none; }
        .crd-nav-links a { padding: 8px 14px; border-radius: 10px; color: var(--muted); text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: all 0.2s; }
        .crd-nav-links a:hover { color: var(--fg); background: var(--surface); }
        .crd-btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 22px; background: var(--accent); color: white; text-decoration: none; border-radius: 12px; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; transition: all 0.2s; border: none; cursor: pointer; }
        .crd-btn:hover { background: var(--accent-dark); }
        .crd-hamburger { display: none; background: none; border: none; color: var(--fg); font-size: 1.2rem; cursor: pointer; padding: 8px; }

        /* ── MOBILE DRAWER ── */
        .crd-drawer { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 200; background: rgba(0,0,0,0.3); }
        .crd-drawer-panel { position: absolute; top: 0; right: 0; width: min(320px, 85vw); height: 100%; background: var(--card); padding: 24px; transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .crd-drawer.open .crd-drawer-panel { transform: translateX(0); }
        .crd-drawer-close { background: none; border: none; color: var(--muted); font-size: 1.3rem; cursor: pointer; padding: 8px; float: right; }
        .crd-drawer-links { clear: both; padding-top: 24px; }
        .crd-drawer-links a { display: block; padding: 14px 0; color: var(--fg); text-decoration: none; font-size: 1rem; font-weight: 500; border-bottom: 1px solid var(--border); }
        .crd-drawer-links a:last-child { border-bottom: none; }

        /* ── FULL-BLEED HERO ── */
        .crd-hero {
            position: relative;
            height: 480px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--accent) 0%, #1E1B4B 100%);
        }

        .crd-hero-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-size: cover;
            background-position: center;
        }

        .crd-hero-gradient {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg,
                rgba(30,27,75,0.2) 0%,
                rgba(30,27,75,0.1) 40%,
                rgba(30,27,75,0.6) 75%,
                rgba(30,27,75,0.9) 100%
            );
        }

        .crd-hero-content {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            z-index: 2;
            padding: 0 24px 40px;
        }

        .crd-hero-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .crd-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.5);
        }

        .crd-breadcrumb a { color: rgba(255,255,255,0.6); text-decoration: none; }
        .crd-breadcrumb a:hover { color: white; }
        .crd-breadcrumb .sep { font-size: 0.6rem; }

        .crd-hero-title {
            font-family: var(--font-heading);
            font-size: 2.6rem;
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
            line-height: 1.12;
            margin-bottom: 16px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .crd-hero-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .crd-hero-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.85);
            font-weight: 500;
        }

        .crd-hero-meta-item i { color: rgba(255,255,255,0.6); font-size: 0.8rem; }

        .crd-hero-placeholder {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.2);
            font-size: 4rem;
        }

        /* ── INFO BAR ── */
        .crd-info-bar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .crd-info-bar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .crd-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .crd-info-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .crd-info-icon.blue { background: var(--accent-soft); color: var(--accent); }
        .crd-info-icon.green { background: var(--green-soft); color: var(--green); }
        .crd-info-icon.amber { background: #FEF3C7; color: #D97706; }

        .crd-info-text { font-size: 0.82rem; color: var(--muted); }
        .crd-info-text strong { display: block; font-size: 0.88rem; color: var(--fg); font-weight: 600; }

        /* ── MAIN CONTENT ── */
        .crd-main {
            max-width: 860px;
            margin: 0 auto;
            padding: 48px 24px;
        }

        .crd-content-block {
            margin-bottom: 48px;
        }

        .crd-content-block:last-child { margin-bottom: 0; }

        .crd-block-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .crd-block-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .crd-description {
            font-size: 0.95rem;
            line-height: 1.85;
            color: var(--fg);
        }

        .crd-description p { margin-bottom: 16px; }

        /* ── ITINERARY — Horizontal Timeline ── */
        .crd-itinerary-scroll {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 8px;
        }

        .crd-itinerary-scroll::-webkit-scrollbar { height: 4px; }
        .crd-itinerary-scroll::-webkit-scrollbar-track { background: transparent; }
        .crd-itinerary-scroll::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .crd-itinerary-card {
            flex: 0 0 260px;
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            scroll-snap-align: start;
            position: relative;
            border-top: 3px solid var(--accent);
        }

        .crd-itinerary-day-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: var(--accent);
            color: white;
            border-radius: 6px;
            font-family: var(--font-heading);
            font-size: 0.72rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .crd-itinerary-title {
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 6px;
        }

        .crd-itinerary-desc {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── INCLUDES / EXCLUDES ── */
        .crd-check-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .crd-check-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
        }

        .crd-check-title {
            font-family: var(--font-heading);
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .crd-check-title.include { color: var(--green); }
        .crd-check-title.exclude { color: #DC2626; }

        .crd-check-list { list-style: none; }
        .crd-check-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 6px 0;
            font-size: 0.88rem;
            color: var(--fg);
            line-height: 1.5;
        }

        .crd-check-list li i { margin-top: 3px; font-size: 0.7rem; flex-shrink: 0; }
        .crd-check-include li i { color: var(--green); }
        .crd-check-exclude li i { color: #DC2626; }

        /* ── GALLERY — Horizontal Scroll ── */
        .crd-gallery-scroll {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 8px;
        }

        .crd-gallery-scroll::-webkit-scrollbar { height: 4px; }
        .crd-gallery-scroll::-webkit-scrollbar-track { background: transparent; }
        .crd-gallery-scroll::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .crd-gallery-item {
            flex: 0 0 220px;
            aspect-ratio: 4/3;
            overflow: hidden;
            border-radius: 12px;
            cursor: pointer;
            scroll-snap-align: start;
        }

        .crd-gallery-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .crd-gallery-item:hover img { transform: scale(1.08); }

        /* ── NOTES ── */
        .crd-notes {
            font-size: 0.88rem;
            color: var(--muted);
            line-height: 1.7;
            padding: 20px;
            background: var(--accent-soft);
            border-radius: var(--radius);
            border-left: 4px solid var(--accent);
        }

        /* ── STICKY BOTTOM BAR ── */
        .crd-bottom-bar {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 90;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid var(--border);
            box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .crd-bottom-bar.visible { transform: translateY(0); }

        .crd-bottom-bar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .crd-bottom-price {
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .crd-bottom-price-label {
            font-size: 0.72rem;
            color: var(--muted);
            font-weight: 500;
        }

        .crd-bottom-price-value {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--accent);
            letter-spacing: -0.5px;
        }

        .crd-bottom-price-value span {
            font-size: 0.78rem;
            font-weight: 400;
            color: var(--muted);
        }

        .crd-bottom-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .crd-bottom-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 12px 28px;
            background: var(--green);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-family: var(--font-heading);
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .crd-bottom-btn:hover { background: #047857; }

        .crd-bottom-share {
            width: 42px; height: 42px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .crd-bottom-share:hover { border-color: var(--accent); color: var(--accent); }

        /* ── RELATED ── */
        .crd-related {
            background: var(--card);
            border-top: 1px solid var(--border);
        }

        .crd-related-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 56px 24px 64px;
        }

        .crd-related-scroll {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 8px;
        }

        .crd-related-scroll::-webkit-scrollbar { height: 4px; }
        .crd-related-scroll::-webkit-scrollbar-track { background: transparent; }
        .crd-related-scroll::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .crd-rel-card {
            flex: 0 0 280px;
            border-radius: var(--radius);
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            scroll-snap-align: start;
        }

        .crd-rel-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

        .crd-rel-card-img { width: 100%; aspect-ratio: 16/10; object-fit: cover; display: block; }
        .crd-rel-card-img-placeholder { width: 100%; aspect-ratio: 16/10; background: var(--accent-soft); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.2rem; }

        .crd-rel-card-body { padding: 16px; }
        .crd-rel-card-title { font-family: var(--font-heading); font-size: 0.92rem; font-weight: 700; color: var(--fg); margin-bottom: 8px; }
        .crd-rel-card-footer { display: flex; align-items: center; justify-content: space-between; }
        .crd-rel-price { font-size: 0.85rem; font-weight: 700; color: var(--accent); }
        .crd-rel-price span { font-weight: 400; font-size: 0.72rem; color: var(--muted); }
        .crd-rel-cta { padding: 6px 14px; background: var(--accent); color: white; border-radius: 8px; font-size: 0.72rem; font-weight: 600; }

        /* ── FOOTER ── */
        .crd-footer { background: var(--fg); color: rgba(255,255,255,0.5); }
        .crd-footer-inner { max-width: 1200px; margin: 0 auto; }
        .crd-footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 48px; padding: 48px 24px 28px; }
        .crd-footer-brand .crd-logo-text { color: white; }
        .crd-footer-desc { font-size: 0.82rem; line-height: 1.7; margin-top: 12px; max-width: 300px; }
        .crd-footer-col h5 { color: white; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 14px; }
        .crd-footer-col ul { list-style: none; }
        .crd-footer-col ul li { margin-bottom: 10px; }
        .crd-footer-col ul a { color: rgba(255,255,255,0.4); text-decoration: none; font-size: 0.82rem; transition: color 0.2s; }
        .crd-footer-col ul a:hover { color: white; }
        .crd-footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin-bottom: 18px; }
        .crd-footer-bottom { display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; padding: 0 24px 24px; }
        .crd-footer-bottom a { color: white; text-decoration: none; }

        /* ── SHARE FAB ── */
        .share-fab { position: fixed; bottom: 100px; left: 24px; z-index: 90; }
        .share-fab-options { display: flex; flex-direction: column; gap: 8px; margin-bottom: 8px; }
        .share-fab-options a, .share-fab-options button { width: 40px; height: 40px; border-radius: 12px; border: none; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 14px; transition: transform 0.2s; }
        .share-fab-options a:hover, .share-fab-options button:hover { transform: scale(1.1); }
        .share-fab-trigger { width: 46px; height: 46px; border-radius: 14px; border: none; background: var(--accent); color: white; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 16px rgba(79,70,229,0.3); transition: all 0.2s; }
        .share-fab-trigger:hover { background: var(--accent-dark); }

        /* ══════════ RESPONSIVE ══════════ */

        @media (max-width: 960px) {
            .crd-nav-links { display: none; }
            .crd-hamburger { display: block; }
            .crd-drawer { display: block; }

            .crd-hero { height: 400px; }
            .crd-hero-title { font-size: 2rem; }
            .crd-hero-content { padding: 0 24px 32px; }

            .crd-info-bar-inner { gap: 16px; }

            .crd-main { padding: 36px 24px; }
            .crd-content-block { margin-bottom: 40px; }
            .crd-check-grid { grid-template-columns: 1fr; gap: 16px; }

            .crd-related-inner { padding: 44px 24px 52px; }
            .crd-footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
            .crd-footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            body { padding-bottom: 72px; }
            .crd-nav-inner { height: 56px; }
            .crd-logo-icon { width: 32px; height: 32px; font-size: 12px; }
            .crd-logo-text { font-size: 0.92rem; }

            .crd-hero { height: 360px; }
            .crd-hero-content { padding: 0 16px 24px; }
            .crd-hero-title { font-size: 1.6rem; letter-spacing: -0.5px; }
            .crd-hero-meta { gap: 12px; }
            .crd-hero-meta-item { font-size: 0.78rem; }

            .crd-info-bar-inner { padding: 16px; gap: 12px; flex-wrap: wrap; }
            .crd-info-item { gap: 8px; }
            .crd-info-icon { width: 36px; height: 36px; font-size: 0.78rem; }
            .crd-info-text { font-size: 0.75rem; }
            .crd-info-text strong { font-size: 0.82rem; }

            .crd-main { padding: 28px 16px; }
            .crd-content-block { margin-bottom: 32px; }
            .crd-description { font-size: 0.9rem; }

            .crd-itinerary-card { flex: 0 0 220px; padding: 16px; }
            .crd-gallery-item { flex: 0 0 180px; }

            .crd-check-card { padding: 18px; }

            .crd-bottom-bar-inner { padding: 12px 16px; gap: 12px; }
            .crd-bottom-price-value { font-size: 1.15rem; }
            .crd-bottom-btn { padding: 10px 20px; font-size: 0.82rem; }
            .crd-bottom-share { width: 38px; height: 38px; font-size: 0.82rem; }

            .crd-related-inner { padding: 36px 16px 44px; }
            .crd-rel-card { flex: 0 0 240px; }

            .crd-footer-grid { grid-template-columns: 1fr; gap: 24px; padding: 36px 16px 20px; }
            .share-fab { bottom: 88px; left: 16px; }
            .share-fab-trigger { width: 40px; height: 40px; font-size: 14px; }
            .share-fab-options a, .share-fab-options button { width: 36px; height: 36px; font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="crd-nav">
    <div class="crd-nav-inner">
        <a href="{{ $homeUrl }}" class="crd-logo">
            <div class="crd-logo-icon">
                @if($website->logo_url) <img src="{{ $website->logo_url }}" alt="Logo"/> @else <i class="fas fa-suitcase-rolling"></i> @endif
            </div>
            <span class="crd-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="crd-nav-links">
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="crd-btn" style="padding:8px 18px;font-size:0.82rem;"><i class="fab fa-whatsapp"></i> Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="crd-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true"><i class="fas fa-bars"></i></button>
    </div>
</nav>

<!-- MOBILE DRAWER -->
<div class="crd-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="crd-drawer-panel">
        <button class="crd-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="crd-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false">{{ __('messages.contact') }}</a>
            @endif
        </div>
    </div>
</div>

<!-- FULL-BLEED HERO -->
<section class="crd-hero">
    @if($tour->thumbnail_url)
        <div class="crd-hero-bg" style="background-image:url('{{ $tour->thumbnail_url }}');"></div>
    @elseif($tour->images->count() > 0)
        <div class="crd-hero-bg" style="background-image:url('{{ $tour->images->first()->url }}');"></div>
    @else
        <div class="crd-hero-placeholder"><i class="fas fa-mountain"></i></div>
    @endif
    <div class="crd-hero-gradient"></div>
    <div class="crd-hero-content">
        <div class="crd-hero-inner">
            <div class="crd-breadcrumb">
                <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
                <span class="sep"><i class="fas fa-chevron-right"></i></span>
                <span>Tour</span>
                <span class="sep"><i class="fas fa-chevron-right"></i></span>
                <span style="color:rgba(255,255,255,0.8);">{{ $tour->title }}</span>
            </div>
            <h1 class="crd-hero-title">{{ $tour->title }}</h1>
            <div class="crd-hero-meta">
                @if($tour->duration_text ?? $tour->duration)
                    <div class="crd-hero-meta-item"><i class="far fa-clock"></i> {{ $tour->duration_text ?? $tour->duration }}</div>
                @endif
                @if($tour->location)
                    <div class="crd-hero-meta-item"><i class="fas fa-map-marker-alt"></i> {{ $tour->location }}</div>
                @endif
                @if($tour->price_start_from)
                    <div class="crd-hero-meta-item"><i class="fas fa-tag"></i> Mulai Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- INFO BAR -->
<div class="crd-info-bar">
    <div class="crd-info-bar-inner">
        @if($tour->duration_text ?? $tour->duration)
            <div class="crd-info-item">
                <div class="crd-info-icon blue"><i class="far fa-clock"></i></div>
                <div class="crd-info-text">{{ __('messages.duration') }}<strong>{{ $tour->duration_text ?? $tour->duration }}</strong></div>
            </div>
        @endif
        @if($tour->location)
            <div class="crd-info-item">
                <div class="crd-info-icon green"><i class="fas fa-map-marker-alt"></i></div>
                <div class="crd-info-text">Lokasi<strong>{{ $tour->location }}</strong></div>
            </div>
        @endif
        @if($tour->price_start_from)
            <div class="crd-info-item">
                <div class="crd-info-icon amber"><i class="fas fa-tag"></i></div>
                <div class="crd-info-text">{{ __('messages.price') }}<strong>Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}/orang</strong></div>
            </div>
        @endif
        @if($tour->difficulty ?? null)
            <div class="crd-info-item">
                <div class="crd-info-icon blue"><i class="fas fa-signal"></i></div>
                <div class="crd-info-text">Tingkat<strong>{{ $tour->difficulty }}</strong></div>
            </div>
        @endif
        @if($tour->min_pax ?? null)
            <div class="crd-info-item">
                <div class="crd-info-icon green"><i class="fas fa-users"></i></div>
                <div class="crd-info-text">Min. Peserta<strong>{{ $tour->min_pax }} orang</strong></div>
            </div>
        @endif
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="crd-main">
    @if($tour->description)
    <div class="crd-content-block">
        <div class="crd-block-label">Deskripsi</div>
        <div class="crd-description">{!! $tour->description !!}</div>
    </div>
    @endif

    @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
    <div class="crd-content-block">
        <div class="crd-block-label">Itinerary</div>
        <div class="crd-itinerary-scroll">
            @foreach($tour->itinerary as $i => $item)
                <div class="crd-itinerary-card">
                    <div class="crd-itinerary-day-badge">Day {{ $i + 1 }}</div>
                    <div class="crd-itinerary-title">{{ is_array($item) ? ($item['title'] ?? 'Hari ' . ($i + 1)) : $item }}</div>
                    @if(is_array($item) && isset($item['description']))
                        <div class="crd-itinerary-desc">{{ $item['description'] }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    @if((is_array($tour->includes) && count($tour->includes) > 0) || (is_array($tour->excludes) && count($tour->excludes) > 0))
    <div class="crd-content-block">
        <div class="crd-block-label">{{ __('messages.includes') }} & {{ __('messages.excludes') }}</div>
        <div class="crd-check-grid">
            @if(is_array($tour->includes) && count($tour->includes) > 0)
                <div class="crd-check-card">
                    <div class="crd-check-title include"><i class="fas fa-check-circle"></i> Termasuk</div>
                    <ul class="crd-check-list crd-check-include">
                        @foreach($tour->includes as $item) <li><i class="fas fa-check-circle"></i> {{ $item }}</li> @endforeach
                    </ul>
                </div>
            @endif
            @if(is_array($tour->excludes) && count($tour->excludes) > 0)
                <div class="crd-check-card">
                    <div class="crd-check-title exclude"><i class="fas fa-times-circle"></i> Tidak Termasuk</div>
                    <ul class="crd-check-list crd-check-exclude">
                        @foreach($tour->excludes as $item) <li><i class="fas fa-times-circle"></i> {{ $item }}</li> @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    @endif

    @if($tour->images->count() > 1)
    <div class="crd-content-block">
        <div class="crd-block-label">{{ __('messages.gallery') }}</div>
        <div class="crd-gallery-scroll" x-data="tourGallery(@js($tour->images->values()->all()))" x-init="init()">
            @foreach($tour->images as $i => $img)
                <div class="crd-gallery-item" @click="open({{ $i }})">
                    <img src="{{ $img->url }}" alt="{{ $img->alt_text ?? $tour->title }}" loading="lazy"/>
                </div>
            @endforeach
            @if($features['gallery_lightbox'] ?? false)
            <template x-if="isOpen">
                <div style="position:fixed;top:0;left:0;right:0;bottom:0;z-index:300;background:rgba(0,0,0,0.92);display:flex;align-items:center;justify-content:center;" @click.self="close()" x-transition>
                    <button @click="close()" style="position:absolute;top:20px;right:20px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;">&times;</button>
                    <button @click="prev()" style="position:absolute;left:16px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;">&#8249;</button>
                    <img :src="images[currentIndex]?.url || ''" style="max-width:90vw;max-height:85vh;object-fit:contain;border-radius:8px;"/>
                    <button @click="next()" style="position:absolute;right:16px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;">&#8250;</button>
                    <div style="position:absolute;bottom:20px;color:rgba(255,255,255,0.6);font-size:0.8rem;" x-text="(currentIndex + 1) + ' / ' + images.length"></div>
                </div>
            </template>
            @endif
        </div>
    </div>
    @endif

    @if($tour->notes)
    <div class="crd-content-block">
        <div class="crd-block-label">{{ __('messages.notes') }}</div>
        <div class="crd-notes">{!! $tour->notes !!}</div>
    </div>
    @endif
</div>

<!-- STICKY BOTTOM BAR -->
<div class="crd-bottom-bar" x-data x-init="window.addEventListener('scroll', () => { $el.classList.toggle('visible', window.scrollY > 400); })">
    <div class="crd-bottom-bar-inner">
        <div class="crd-bottom-price">
            @if($tour->price_start_from)
                <div>
                    <div class="crd-bottom-price-label">{{ __('messages.starting_from') }}</div>
                    <div class="crd-bottom-price-value">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }} <span>/{{ __(messages.people) }}</span></div>
                </div>
            @endif
        </div>
        <div class="crd-bottom-actions">
            @if($features['social_share'] ?? false)
                <div style="position:relative;" x-data="socialShare()" x-init="init()">
                    <button class="crd-bottom-share" @click="isOpen = !isOpen"><i class="fas fa-share-alt"></i></button>
                    <div x-show="isOpen" @click.away="isOpen = false"
                         style="position:absolute;bottom:52px;right:0;background:var(--card);border-radius:12px;box-shadow:var(--shadow-lg);padding:8px;display:flex;gap:8px;z-index:10;"
                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-2">
                        <a :href="'https://wa.me/?text=' + encodeURIComponent(pageUrl)" target="_blank" style="width:36px;height:36px;border-radius:8px;background:#25D366;color:white;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;"><i class="fab fa-whatsapp"></i></a>
                        <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(pageUrl)" target="_blank" style="width:36px;height:36px;border-radius:8px;background:#1877F2;color:white;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;"><i class="fab fa-facebook-f"></i></a>
                        <button @click="copyLink()" style="width:36px;height:36px;border-radius:8px;border:none;background:var(--accent);color:white;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px;" :style="copied ? 'background:#059669' : ''"><i class="fas" :class="copied ? 'fa-check' : 'fa-link'"></i></button>
                    </div>
                </div>
            @endif
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya tertarik dengan tour ' . $tour->title) }}" target="_blank" class="crd-bottom-btn"><i class="fab fa-whatsapp"></i> Pesan Sekarang</a>
            @endif
        </div>
    </div>
</div>

<!-- RELATED -->
@if(isset($relatedTours) && $relatedTours->count() > 0)
<section class="crd-related">
    <div class="crd-related-inner">
        <div class="crd-block-label" style="margin-bottom:24px;">Tour Lainnya</div>
        <div class="crd-related-scroll">
            @foreach($relatedTours as $related)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $related->slug : '/tour/' . $related->slug }}" class="crd-rel-card">
                    @if($related->thumbnail_url)
                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}" class="crd-rel-card-img"/>
                    @elseif($related->images->count() > 0)
                        <img src="{{ $related->images->first()->url }}" alt="{{ $related->title }}" class="crd-rel-card-img"/>
                    @else
                        <div class="crd-rel-card-img-placeholder"><i class="fas fa-mountain"></i></div>
                    @endif
                    <div class="crd-rel-card-body">
                        <div class="crd-rel-card-title">{{ $related->title }}</div>
                        <div class="crd-rel-card-footer">
                            @if($related->price_start_from)
                                <div class="crd-rel-price"><span>Mulai </span>Rp {{ number_format($related->price_start_from, 0, ',', '.') }}</div>
                            @else
                                <div></div>
                            @endif
                            <span class="crd-rel-cta">Detail</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FOOTER -->
<footer class="crd-footer">
    <div class="crd-footer-inner">
        <div class="crd-footer-grid">
            <div class="crd-footer-brand">
                <a href="{{ $homeUrl }}" class="crd-logo">
                    <div class="crd-logo-icon">@if($website->logo_url) <img src="{{ $website->logo_url }}" alt="Logo"/> @else <i class="fas fa-suitcase-rolling"></i> @endif</div>
                    <span class="crd-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
                </a>
                <p class="crd-footer-desc">{{ $settings->description ?? 'Powered by adaylink.' }}</p>
            </div>
            <div class="crd-footer-col"><h5>Halaman</h5><ul>@foreach($pages as $p)<li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>@endforeach</ul></div>
            <div class="crd-footer-col"><h5>Kontak</h5><ul>
                @if($settings->phone ?? null)<li><a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a></li>@endif
                @if($settings->email ?? null)<li><a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></li>@endif
                @if($settings->address ?? null)<li><a href="#">{{ $settings->address }}</a></li>@endif
                @if($settings->social_instagram ?? null)<li><a href="{{ $settings->social_instagram }}" target="_blank">Instagram</a></li>@endif
                @if($website->contact_whatsapp)<li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank">WhatsApp</a></li>@endif
            </ul></div>
        </div>
        <hr class="crd-footer-divider">
        <div class="crd-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
            <span>Powered by <a href="#">adaylink</a></span>
        </div>
    </div>
</footer>

@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}" target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:100px;right:24px;z-index:89;background:#25D366;color:white;width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(37,211,102,0.3);text-decoration:none;font-size:22px;"
       x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(20px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},1000)">
        <i class="fab fa-whatsapp" style="font-size:22px;"></i>
    </a>
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
            next() { this.currentIndex = (this.currentIndex + 1) % this.images.length; }
        };
    }

    function socialShare() {
        return {
            isOpen: false, pageUrl: window.location.href, copied: false,
            init() { document.addEventListener('click', (e) => { if (!this.$el.contains(e.target)) this.isOpen = false; }); },
            copyLink() { navigator.clipboard.writeText(this.pageUrl).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }); }
        };
    }
</script>
</body>
</html>
