{{-- Template: Clean — Swiss/Editorial Tour Detail View --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
    $tourUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $tour->slug : '/tour/' . $tour->slug;
    $primaryColor = $settings->primary_color ?? '#2563EB';
    $secondaryColor = $settings->secondary_color ?? '#0F172A';
    $fontHeading = $settings->font_heading ?? 'Space Grotesk';
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
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700&family={{ urlencode($fontBody) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        :root {
            --bg: #FFFFFF;
            --fg: {{ $secondaryColor }};
            --muted: #64748B;
            --accent: {{ $primaryColor }};
            --accent-soft: #EFF6FF;
            --card: #F8FAFC;
            --border: #E2E8F0;
            --surface: #F1F5F9;
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

        /* ── TOPBAR ── */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }

        .topbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--fg);
            letter-spacing: -0.5px;
        }

        .topbar-logo-icon {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            overflow: hidden;
        }

        .topbar-logo-icon img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
        }

        .topbar-nav a {
            padding: 8px 16px;
            border-radius: 100px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .topbar-nav a:hover {
            color: var(--fg);
            background: var(--surface);
        }

        .topbar-nav .nav-pill {
            background: var(--fg);
            color: white !important;
        }

        .topbar-nav .nav-pill:hover {
            background: var(--accent);
        }

        .topbar-hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--fg);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .topbar-hamburger:hover { background: var(--surface); }

        .mobile-drawer {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 200;
            background: rgba(0,0,0,0.3);
        }

        .mobile-drawer-panel {
            position: absolute;
            top: 0; right: 0;
            width: min(320px, 85vw);
            height: 100%;
            background: var(--bg);
            padding: 24px;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mobile-drawer.open .mobile-drawer-panel { transform: translateX(0); }

        .mobile-drawer-close {
            background: none;
            border: none;
            color: var(--muted);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 8px;
            float: right;
        }

        .mobile-drawer-links {
            clear: both;
            padding-top: 24px;
        }

        .mobile-drawer-links a {
            display: block;
            padding: 14px 0;
            color: var(--fg);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            border-bottom: 1px solid var(--border);
        }

        .mobile-drawer-links a:last-child { border-bottom: none; }

        /* ── HERO: FULL-BLEED IMAGE ── */
        .hero-full {
            position: relative;
            height: 70vh;
            min-height: 500px;
            overflow: hidden;
        }

        .hero-full-img {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform 8s ease-out;
        }

        .hero-full:hover .hero-full-img { transform: scale(1.03); }

        .hero-full-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.4) 40%, rgba(15,23,42,0.1) 100%);
        }

        .hero-full-content {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 0 24px 48px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 500;
            color: rgba(255,255,255,0.5);
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hero-breadcrumb a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: color 0.2s;
        }

        .hero-breadcrumb a:hover { color: white; }
        .hero-breadcrumb .sep { color: rgba(255,255,255,0.25); }

        .hero-full h1 {
            font-family: var(--font-heading);
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 700;
            color: white;
            line-height: 1.1;
            letter-spacing: -1px;
            margin-bottom: 20px;
            max-width: 700px;
        }

        .hero-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 500;
            background: rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.9);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(8px);
        }

        /* ── STICKY PRICE BAR ── */
        .price-bar {
            position: sticky;
            top: 64px;
            z-index: 50;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
        }

        .price-bar-inner {
            max-width: 1200px;
            margin: 0 auto;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .price-bar-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .price-bar-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: var(--muted);
        }

        .price-bar-item i { color: var(--accent); font-size: 0.9rem; }
        .price-bar-item strong { color: var(--fg); font-weight: 600; }

        .price-bar-right {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .price-bar-amount {
            font-family: var(--font-heading);
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--accent);
        }

        .price-bar-label {
            font-size: 0.7rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-book {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-book:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* ── CONTENT GRID ── */
        .content-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 48px 24px 80px;
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 40px;
        }

        .content-main { min-width: 0; }
        .content-aside {}

        /* ── SECTION ── */
        .sec {
            margin-bottom: 48px;
        }

        .sec-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent);
            margin-bottom: 16px;
        }

        .sec-label::before {
            content: '';
            width: 20px;
            height: 2px;
            background: var(--accent);
        }

        .sec-title {
            font-family: var(--font-heading);
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 20px;
        }

        .desc-text {
            font-size: 0.95rem;
            line-height: 1.8;
            color: #475569;
            white-space: pre-line;
        }

        /* ── ITINERARY: LARGE NUMBER STYLE ── */
        .itin-list {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .itin-item {
            display: grid;
            grid-template-columns: 72px 1fr;
            gap: 20px;
            padding: 24px 0;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
        }

        .itin-item:first-child { padding-top: 0; }
        .itin-item:last-child { border-bottom: none; }

        .itin-num {
            font-family: var(--font-heading);
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent);
            line-height: 1;
            opacity: 0.3;
            transition: opacity 0.3s;
        }

        .itin-item:hover .itin-num { opacity: 1; }

        .itin-day-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--accent);
            margin-bottom: 4px;
        }

        .itin-body h4 {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .itin-body p {
            font-size: 0.88rem;
            color: var(--muted);
            line-height: 1.7;
        }

        /* ── INCLUDES / EXCLUDES: CHECK GRID ── */
        .check-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .check-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.88rem;
            transition: background 0.2s;
        }

        .check-row:hover { background: var(--surface); }

        .check-row.inc .check-ic {
            width: 22px; height: 22px;
            background: #ECFDF5;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #059669;
            font-size: 0.65rem;
            flex-shrink: 0;
        }

        .check-row.exc .check-ic {
            width: 22px; height: 22px;
            background: #FEF2F2;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #DC2626;
            font-size: 0.65rem;
            flex-shrink: 0;
        }

        /* ── NOTES ── */
        .notes-callout {
            background: #FFFBEB;
            border-left: 4px solid #F59E0B;
            border-radius: 0 8px 8px 0;
            padding: 20px 24px;
            font-size: 0.88rem;
            line-height: 1.75;
            color: #92400E;
            white-space: pre-line;
        }

        /* ── GALLERY: MASONRY-LIKE GRID ── */
        .gallery-masonry {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .gallery-masonry .gm-item {
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
        }

        .gallery-masonry .gm-item:first-child {
            grid-row: span 2;
        }

        .gallery-masonry .gm-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s;
        }

        .gallery-masonry .gm-item:hover img { transform: scale(1.06); }

        .gm-item-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15,23,42,0.2);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .gallery-masonry .gm-item:hover .gm-item-overlay { opacity: 1; }

        /* ── ASIDE: SIDEBAR CARDS ── */
        .aside-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .aside-card.sticky-top {
            position: sticky;
            top: 136px;
        }

        .aside-price-label {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .aside-price {
            font-family: var(--font-heading);
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 4px;
        }

        .aside-price-note {
            font-size: 0.78rem;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .aside-meta-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }

        .aside-meta-row {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.88rem;
            color: var(--fg);
        }

        .aside-meta-icon {
            width: 36px; height: 36px;
            background: var(--accent-soft);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .btn-wa-full {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px;
            background: #25D366;
            color: white;
            border: none;
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-wa-full:hover {
            background: #1DA851;
            transform: translateY(-1px);
        }

        .aside-highlights-list {
            list-style: none;
        }

        .aside-highlights-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.85rem;
            color: #475569;
        }

        .aside-highlights-list li:last-child { border-bottom: none; }

        .aside-highlights-list .hl-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--accent);
            flex-shrink: 0;
            margin-top: 7px;
        }

        /* ── RELATED TOURS: HORIZONTAL CARDS ── */
        .related-band {
            background: var(--card);
            border-top: 1px solid var(--border);
            padding: 64px 24px;
        }

        .related-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .related-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 20px;
        }

        .related-header-left .sec-label { margin-bottom: 8px; }

        .related-header-title {
            font-family: var(--font-heading);
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .related-header-title em { font-style: normal; color: var(--accent); }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .rel-card {
            display: grid;
            grid-template-columns: 140px 1fr;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s;
        }

        .rel-card:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 20px rgba(37,99,235,0.08);
        }

        .rel-card-img {
            overflow: hidden;
        }

        .rel-card-img img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .rel-card:hover .rel-card-img img { transform: scale(1.06); }

        .rel-card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .rel-card-meta {
            font-size: 0.72rem;
            color: var(--muted);
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .rel-card-body h3 {
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .rel-card-price {
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--accent);
        }

        .rel-card-price span {
            font-family: var(--font-body);
            font-size: 0.7rem;
            font-weight: 400;
            color: var(--muted);
        }

        /* ── FOOTER: MINIMAL ── */
        .site-footer {
            background: var(--fg);
            color: rgba(255,255,255,0.5);
            padding: 48px 24px 24px;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand .topbar-logo { color: white; margin-bottom: 12px; }
        .footer-brand .topbar-logo .topbar-logo-icon { background: var(--accent); }
        .footer-desc { font-size: 0.82rem; line-height: 1.7; }

        .footer-col h5 {
            color: white;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }

        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 8px; }
        .footer-col ul a {
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            font-size: 0.82rem;
            transition: color 0.2s;
        }
        .footer-col ul a:hover { color: var(--accent); }

        .footer-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 20px;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
        }

        .footer-bottom a { color: var(--accent); text-decoration: none; }

        /* ── LIGHTBOX ── */
        .lightbox-overlay {
            position: fixed;
            inset: 0;
            z-index: 300;
            background: rgba(0,0,0,0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .lightbox-overlay img {
            max-width: 90vw;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 4px;
        }

        .lightbox-close {
            position: absolute;
            top: 20px; right: 24px;
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            z-index: 10;
            width: 44px; height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .lightbox-close:hover { background: rgba(255,255,255,0.2); }

        .lightbox-prev, .lightbox-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            font-size: 1rem;
            width: 48px; height: 48px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-prev { left: 20px; }
        .lightbox-next { right: 20px; }
        .lightbox-prev:hover, .lightbox-next:hover { background: rgba(255,255,255,0.25); }

        .lightbox-counter {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.4);
            font-size: 0.82rem;
            font-weight: 500;
        }

        /* ── SHARE FAB ── */
        .share-fab {
            position: fixed;
            bottom: 24px; left: 24px;
            z-index: 99;
        }

        .share-fab-trigger {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: var(--fg);
            color: white;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .share-fab-trigger:hover { background: var(--accent); transform: scale(1.08); }

        .share-fab-options {
            position: absolute;
            bottom: 58px; left: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .share-fab-options a, .share-fab-options button {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            transition: transform 0.2s;
        }

        .share-fab-options a:hover, .share-fab-options button:hover { transform: scale(1.12); }

        /* ── RESPONSIVE ── */
        @media (max-width: 960px) {
            .topbar-nav { display: none; }
            .topbar-hamburger { display: block; }
            .mobile-drawer.open { display: block; }
            .hero-full { height: 50vh; min-height: 360px; }
            .hero-full-content { padding: 0 20px 40px; }
            .hero-full h1 { font-size: clamp(1.6rem, 5vw, 2.2rem); letter-spacing: -0.5px; }
            .hero-breadcrumb { font-size: 0.7rem; margin-bottom: 14px; }
            .hero-tag { padding: 5px 10px; font-size: 0.72rem; }
            .price-bar { padding: 0 16px; }
            .price-bar-inner { height: auto; padding: 12px 0; flex-wrap: wrap; gap: 12px; }
            .price-bar-left { gap: 12px; flex-wrap: wrap; }
            .price-bar-right { width: 100%; justify-content: space-between; }
            .price-bar-amount { font-size: 1.1rem; }
            .btn-book { padding: 10px 20px; font-size: 0.82rem; }
            .content-wrap {
                grid-template-columns: 1fr;
                padding: 24px 16px 60px;
                gap: 24px;
            }
            .sec { margin-bottom: 36px; }
            .sec-title { font-size: 1.25rem; margin-bottom: 16px; }
            .desc-text { font-size: 0.9rem; }
            .itin-item { grid-template-columns: 48px 1fr; gap: 14px; padding: 18px 0; }
            .itin-num { font-size: 1.8rem; }
            .check-grid { grid-template-columns: 1fr; gap: 6px; }
            .check-row { padding: 8px 12px; font-size: 0.84rem; }
            .gallery-masonry { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .gallery-masonry .gm-item:first-child { grid-row: auto; }
            .aside-card { padding: 20px; }
            .aside-card.sticky-top { position: relative; top: auto; }
            .aside-price { font-size: 1.6rem; }
            .related-band { padding: 48px 16px; }
            .related-header { margin-bottom: 28px; }
            .related-grid { grid-template-columns: 1fr; gap: 12px; }
            .rel-card { grid-template-columns: 110px 1fr; }
            .rel-card-body { padding: 12px; }
            .rel-card-body h3 { font-size: 0.88rem; }
            .site-footer { padding: 36px 16px 20px; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; margin-bottom: 28px; }
            .footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .hero-full { height: 45vh; min-height: 320px; }
            .hero-full-content { padding: 0 16px 32px; }
            .hero-full h1 { font-size: 1.5rem; }
            .hero-tags { gap: 6px; }
            .price-bar-left { display: none; }
            .price-bar-right { width: 100%; }
            .content-wrap { padding: 20px 12px 48px; }
            .sec { margin-bottom: 28px; }
            .sec-label { font-size: 0.65rem; margin-bottom: 10px; }
            .sec-title { font-size: 1.15rem; margin-bottom: 14px; }
            .itin-item { grid-template-columns: 40px 1fr; gap: 10px; padding: 14px 0; }
            .itin-num { font-size: 1.5rem; }
            .itin-body h4 { font-size: 0.95rem; }
            .itin-body p { font-size: 0.82rem; }
            .gallery-masonry { grid-template-columns: 1fr 1fr; gap: 4px; }
            .notes-callout { padding: 16px; font-size: 0.84rem; }
            .aside-card { padding: 16px; margin-bottom: 14px; }
            .aside-price { font-size: 1.4rem; }
            .btn-wa-full { padding: 12px; font-size: 0.85rem; }
            .rel-card { grid-template-columns: 90px 1fr; }
            .site-footer { padding: 28px 12px 16px; }
            .footer-grid { grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px; }
            .footer-col h5 { margin-bottom: 10px; }
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-inner">
        <a href="{{ $homeUrl }}" class="topbar-logo">
            <div class="topbar-logo-icon">
                @if($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                @else
                    <i class="fas fa-paper-plane"></i>
                @endif
            </div>
            {{ $settings->site_title ?? $website->site_name ?? 'Website' }}
        </a>
        <ul class="topbar-nav">
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="nav-pill"><i class="fab fa-whatsapp"></i> Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="topbar-hamburger" @click="drawerOpen = true" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</div>

<!-- Mobile Drawer -->
<div class="mobile-drawer" :class="{ 'open': drawerOpen }" x-show="drawerOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="drawerOpen = false">
    <div class="mobile-drawer-panel" @click.stop>
        <button class="mobile-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="mobile-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false"><i class="fab fa-whatsapp"></i> WhatsApp</a>
            @endif
        </div>
    </div>
</div>

<!-- HERO FULL-BLEED -->
<div class="hero-full">
    <div class="hero-full-img" style="background-image: url('{{ $tour->thumbnail_url ?? 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1800&q=80' }}');"></div>
    <div class="hero-full-overlay"></div>
    <div class="hero-full-content">
        <div class="hero-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep">/</span>
            <a href="{{ $homeUrl }}#tours">Tour</a>
            <span class="sep">/</span>
            <span style="color:rgba(255,255,255,0.8)">{{ $tour->title }}</span>
        </div>
        <h1>{{ $tour->title }}</h1>
        <div class="hero-tags">
            @if($tour->duration_text)
                <span class="hero-tag"><i class="far fa-clock"></i> {{ $tour->duration_text }}</span>
            @endif
            @if($tour->is_featured)
                <span class="hero-tag"><i class="fas fa-star"></i> Best Seller</span>
            @endif
            @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
                <span class="hero-tag"><i class="fas fa-route"></i> {{ count($tour->itinerary) }} Hari</span>
            @endif
        </div>
    </div>
</div>

<!-- STICKY PRICE BAR -->
<div class="price-bar">
    <div class="price-bar-inner">
        <div class="price-bar-left">
            @if($tour->duration_text)
                <div class="price-bar-item"><i class="far fa-clock"></i> <strong>{{ $tour->duration_text }}</strong></div>
            @endif
            @if(is_array($tour->includes) && count($tour->includes) > 0)
                <div class="price-bar-item"><i class="fas fa-check-circle"></i> <strong>{{ count($tour->includes) }}</strong> termasuk</div>
            @endif
            @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
                <div class="price-bar-item"><i class="fas fa-map-marked-alt"></i> <strong>{{ count($tour->itinerary) }}</strong> hari</div>
            @endif
        </div>
        <div class="price-bar-right">
            @if($tour->price_start_from)
                <div>
                    <div class="price-bar-label">{{ __('messages.starting_from') }}</div>
                    <div class="price-bar-amount">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
                </div>
            @endif
            @if($website->contact_whatsapp)
                @php $waMsg = "Halo, saya tertarik dengan paket tour *{$tour->title}*."; @endphp
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode($waMsg) }}" target="_blank" class="btn-book">
                    <i class="fab fa-whatsapp"></i> Pesan Sekarang
                </a>
            @endif
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="content-wrap">
    <div class="content-main">

        {{-- Description --}}
        @if($tour->description)
        <div class="sec">
            <div class="sec-label">Tentang Tour</div>
            <h2 class="sec-title">Deskripsi</h2>
            <div class="desc-text">{{ $tour->description }}</div>
        </div>
        @endif

        {{-- Itinerary --}}
        @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
        <div class="sec">
            <div class="sec-label">Rencana Perjalanan</div>
            <h2 class="sec-title">Itinerary</h2>
            <div class="itin-list">
                @foreach($tour->itinerary as $i => $item)
                    <div class="itin-item">
                        <div class="itin-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="itin-body">
                            <div class="itin-day-label">Hari {{ $i + 1 }}</div>
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
        <div class="sec">
            <div class="sec-label">{{ __('messages.includes') }}</div>
            <h2 class="sec-title">{{ __('messages.includes') }}</h2>
            <div class="check-grid">
                @foreach($tour->includes as $item)
                    <div class="check-row inc">
                        <span class="check-ic"><i class="fas fa-check"></i></span>
                        {{ $item }}
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Excludes --}}
        @if(is_array($tour->excludes) && count($tour->excludes) > 0)
        <div class="sec">
            <div class="sec-label">{{ __('messages.includes') }}</div>
            <h2 class="sec-title">{{ __('messages.excludes') }}</h2>
            <div class="check-grid">
                @foreach($tour->excludes as $item)
                    <div class="check-row exc">
                        <span class="check-ic"><i class="fas fa-times"></i></span>
                        {{ $item }}
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Notes --}}
        @if($tour->notes)
        <div class="sec">
            <div class="sec-label">{{ __('messages.notes') }}</div>
            <h2 class="sec-title">{{ __('messages.notes') }}</h2>
            <div class="notes-callout">{{ $tour->notes }}</div>
        </div>
        @endif

        {{-- Gallery --}}
        @if($tour->images->count() > 0)
        <div class="sec" x-data="tourGallery(@js($tour->images->values()->all()))" x-init="init()">
            <div class="sec-label">{{ __('messages.gallery') }}</div>
            <h2 class="sec-title">{{ __('messages.gallery_title') }}</h2>
            <div class="gallery-masonry">
                @foreach($tour->images as $index => $img)
                    <div class="gm-item" @click="open({{ $index }})">
                        <img src="{{ $img->url }}" alt="{{ $img->alt_text ?? $tour->title }}"/>
                        <div class="gm-item-overlay"><i class="fas fa-expand"></i></div>
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
    <div class="content-aside">
        <div class="aside-card sticky-top">
            @if($tour->price_start_from)
                <div class="aside-price-label">{{ __('messages.starting_from') }}</div>
                <div class="aside-price">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
                <div class="aside-price-note">Per orang (harga dapat berubah)</div>
            @endif

            <div class="aside-meta-list">
                @if($tour->duration_text)
                    <div class="aside-meta-row">
                        <div class="aside-meta-icon"><i class="far fa-clock"></i></div>
                        <span>{{ $tour->duration_text }}</span>
                    </div>
                @endif
                @if(is_array($tour->includes) && count($tour->includes) > 0)
                    <div class="aside-meta-row">
                        <div class="aside-meta-icon"><i class="fas fa-check-circle"></i></div>
                        <span>{{ count($tour->includes) }} item termasuk</span>
                    </div>
                @endif
                @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
                    <div class="aside-meta-row">
                        <div class="aside-meta-icon"><i class="fas fa-calendar-alt"></i></div>
                        <span>{{ count($tour->itinerary) }} hari perjalanan</span>
                    </div>
                @endif
            </div>

            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode($waMsg ?? 'Halo, saya tertarik dengan paket tour Anda.') }}" target="_blank" class="btn-wa-full">
                    <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
                </a>
            @endif
        </div>

        {{-- Highlights --}}
        @if(is_array($tour->includes) && count($tour->includes) > 0)
        <div class="aside-card">
            <h4 style="font-family:var(--font-heading);font-size:0.95rem;font-weight:600;margin-bottom:14px;">Highlight</h4>
            <ul class="aside-highlights-list">
                @foreach(array_slice($tour->includes, 0, 5) as $item)
                    <li><span class="hl-dot"></span> {{ $item }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>

{{-- RELATED TOURS --}}
@if($relatedTours->count() > 0)
<section class="related-band">
    <div class="related-inner">
        <div class="related-header">
            <div class="related-header-left">
                <div class="sec-label">{{ __('messages.related_tours') }}</div>
                <h2 class="related-header-title">Paket Tour <em>Lainnya</em></h2>
            </div>
        </div>
        <div class="related-grid">
            @foreach($relatedTours as $related)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $related->slug : '/tour/' . $related->slug }}" class="rel-card">
                    <div class="rel-card-img">
                        @if($related->thumbnail_url)
                            <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}"/>
                        @else
                            <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=400&q=80" alt="{{ $related->title }}"/>
                        @endif
                    </div>
                    <div class="rel-card-body">
                        @if($related->duration_text)
                            <div class="rel-card-meta"><i class="far fa-clock"></i> {{ $related->duration_text }}</div>
                        @endif
                        <h3>{{ $related->title }}</h3>
                        @if($related->price_start_from)
                            <div class="rel-card-price"><span>Mulai </span>Rp {{ number_format($related->price_start_from, 0, ',', '.') }}</div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FOOTER -->
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ $homeUrl }}" class="topbar-logo">
                    <div class="topbar-logo-icon">
                        @if($website->logo_url)
                            <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                        @else
                            <i class="fas fa-paper-plane"></i>
                        @endif
                    </div>
                    {{ $settings->site_title ?? $website->site_name ?? 'Website' }}
                </a>
                <p class="footer-desc">{{ $settings->description ?? 'Powered by adaylink — platform website untuk driver dan agen wisata.' }}</p>
            </div>
            <div class="footer-col">
                <h5>Halaman</h5>
                <ul>
                    @foreach($pages as $p)
                        <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
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
                </ul>
            </div>
            <div class="footer-col">
                <h5>Sosial</h5>
                <ul>
                    @if($settings->social_instagram ?? null)
                        <li><a href="{{ $settings->social_instagram }}" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></li>
                    @endif
                    @if($website->contact_whatsapp)
                        <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="footer-bottom">
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
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
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

{{-- Alpine.js --}}
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
