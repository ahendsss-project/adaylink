{{-- Template: Clean — Swiss/Editorial Homepage --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
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
    <title>{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if($settings)
        <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700&family={{ urlencode($fontBody) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @if(($features['reviews'] ?? false) && isset($reviewSchema) && $reviewSchema)
        <script type="application/ld+json">{{ json_encode($reviewSchema) }}</script>
    @endif
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

        .topbar-nav a:hover { color: var(--fg); background: var(--surface); }

        .topbar-nav .nav-pill {
            background: var(--fg);
            color: white !important;
        }

        .topbar-nav .nav-pill:hover { background: var(--accent); }

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

        /* ── HERO: SPLIT SCREEN ── */
        .hero-split {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            padding-top: 64px;
        }

        .hero-split-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 32px 60px 24px;
            max-width: 580px;
            margin-left: auto;
            animation: slideRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideRight {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 100px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 24px;
            width: fit-content;
        }

        .hero-split h1 {
            font-family: var(--font-heading);
            font-size: clamp(2.5rem, 4.5vw, 4rem);
            font-weight: 700;
            line-height: 1.08;
            letter-spacing: -1.5px;
            margin-bottom: 20px;
        }

        .hero-split h1 em { font-style: normal; color: var(--accent); }

        .hero-split-desc {
            font-size: 1.05rem;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 440px;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .btn-accent {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: var(--accent);
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

        .btn-accent:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(37,99,235,0.25); }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: transparent;
            color: var(--fg);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: 0.92rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

        .hero-stats-row {
            display: flex;
            gap: 40px;
            padding-top: 32px;
            border-top: 1px solid var(--border);
        }

        .hero-stat-num {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--fg);
        }

        .hero-stat-label {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .hero-split-right {
            position: relative;
            overflow: hidden;
            animation: fadeIn 1s ease-out 0.3s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .hero-split-right img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .hero-img-overlay {
            position: absolute;
            bottom: 24px; left: 24px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-radius: 10px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .hero-img-overlay-icon {
            width: 40px; height: 40px;
            background: var(--accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .hero-img-overlay-text .title { font-weight: 600; font-size: 0.88rem; }
        .hero-img-overlay-text .sub { font-size: 0.75rem; color: var(--muted); }

        /* ── SECTIONS ── */
        .section {
            padding: 80px 24px;
        }

        .section-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            margin-bottom: 48px;
        }

        .section-header .sec-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent);
            margin-bottom: 12px;
        }

        .section-header .sec-label::before {
            content: '';
            width: 20px;
            height: 2px;
            background: var(--accent);
        }

        .section-header .sec-title {
            font-family: var(--font-heading);
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .section-header .sec-title em { font-style: normal; color: var(--accent); }

        .section-header .sec-subtitle {
            color: var(--muted);
            font-size: 0.95rem;
            max-width: 480px;
            margin-top: 8px;
        }

        /* ── TOUR CARDS: BENTO GRID ── */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .bento-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s;
        }

        .bento-card:hover {
            border-color: var(--accent);
            box-shadow: 0 8px 32px rgba(37,99,235,0.08);
        }

        .bento-card.featured {
            grid-column: span 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .bento-card-img {
            position: relative;
            overflow: hidden;
            aspect-ratio: 4/3;
        }

        .bento-card.featured .bento-card-img { aspect-ratio: auto; }

        .bento-card-img img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .bento-card:hover .bento-card-img img { transform: scale(1.04); }

        .bento-card-badge {
            position: absolute;
            top: 12px; left: 12px;
            padding: 4px 12px;
            background: var(--accent);
            color: white;
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 100px;
        }

        .bento-card-body {
            padding: 20px;
        }

        .bento-card-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.78rem;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .bento-card-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .bento-card-body h3 {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .bento-card-body p {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .bento-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        .bento-price-from { font-size: 0.7rem; color: var(--muted); }

        .bento-price-amount {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--accent);
        }

        .bento-card-link {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: gap 0.2s;
        }

        .bento-card-link:hover { gap: 8px; }

        /* ── GALLERY: COMPACT GRID ── */
        .gallery-compact {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .gallery-compact .gc-item {
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            aspect-ratio: 1;
        }

        .gallery-compact .gc-item:first-child {
            grid-column: span 2;
            grid-row: span 2;
        }

        .gallery-compact .gc-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .gallery-compact .gc-item:hover img { transform: scale(1.06); }

        .gc-item-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15,23,42,0.2);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .gallery-compact .gc-item:hover .gc-item-overlay { opacity: 1; }

        /* ── VEHICLES: COMPACT LIST ── */
        .vehicle-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .vehicle-card {
            display: flex;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .vehicle-card:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 16px rgba(37,99,235,0.06);
        }

        .vehicle-card-img {
            width: 160px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .vehicle-card-img img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .vehicle-card:hover .vehicle-card-img img { transform: scale(1.04); }

        .vehicle-card-body {
            flex: 1;
            padding: 18px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .vehicle-card-type {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--accent);
            margin-bottom: 4px;
        }

        .vehicle-card-body h3 {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .vehicle-card-specs {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .vehicle-card-spec {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.78rem;
            color: var(--muted);
        }

        .vehicle-card-spec i { color: var(--accent); font-size: 0.8rem; }

        .vehicle-card-price {
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .vehicle-card-price .label { font-size: 0.78rem; color: var(--muted); }

        .vehicle-card-price .amount {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--accent);
        }

        .vehicle-card-price .unit { font-size: 0.72rem; color: var(--muted); }

        /* ── REVIEWS: CARD GRID ── */
        .review-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            max-width: 800px;
            margin: 0 auto;
        }

        .review-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 24px;
            transition: border-color 0.2s;
        }

        .review-card:hover { border-color: var(--accent); }

        .review-card-stars {
            display: flex;
            gap: 2px;
            margin-bottom: 12px;
            color: #F59E0B;
            font-size: 0.82rem;
        }

        .review-card-quote {
            font-size: 0.92rem;
            line-height: 1.7;
            color: #475569;
            margin-bottom: 16px;
            position: relative;
            padding-left: 16px;
            border-left: 2px solid var(--accent);
        }

        .review-card-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .review-card-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--accent-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--accent);
        }

        .review-card-name { font-weight: 600; font-size: 0.85rem; }
        .review-card-date { font-size: 0.72rem; color: var(--muted); }

        .review-form-card {
            max-width: 500px;
            margin: 40px auto 0;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 28px;
        }

        .review-form-card h3 {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-field { margin-bottom: 14px; }

        .form-field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: 0.88rem;
            background: var(--bg);
            color: var(--fg);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        .btn-submit {
            width: 100%;
            background: var(--accent);
            color: white;
            border: none;
            padding: 13px;
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }

        .form-note {
            text-align: center;
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 10px;
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 80px 24px;
        }

        .empty-state .icon { font-size: 2.5rem; margin-bottom: 16px; color: var(--border); }
        .empty-state h3 { font-family: var(--font-heading); font-size: 1.2rem; margin-bottom: 8px; color: var(--muted); }
        .empty-state p { font-size: 0.88rem; color: var(--muted); }

        /* ── FOOTER ── */
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

        /* ── SCROLL REVEAL ── */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 960px) {
            .topbar-nav { display: none; }
            .topbar-hamburger { display: block; }
            .mobile-drawer.open { display: block; }
            .hero-split {
                grid-template-columns: 1fr;
                min-height: auto;
                padding-top: 64px;
            }
            .hero-split-left {
                padding: 36px 20px;
                max-width: 100%;
            }
            .hero-split h1 { font-size: clamp(1.8rem, 5vw, 2.5rem); letter-spacing: -0.5px; }
            .hero-split-desc { font-size: 0.95rem; margin-bottom: 24px; }
            .hero-actions { margin-bottom: 28px; }
            .hero-stats-row { gap: 28px; padding-top: 24px; }
            .hero-stat-num { font-size: 1.3rem; }
            .hero-split-right {
                height: 45vh;
                min-height: 280px;
            }
            .hero-split-right img { width: 100%; height: 100%; object-fit: cover; }
            .hero-img-overlay { bottom: 16px; left: 16px; padding: 10px 14px; }
            .section { padding: 56px 16px; }
            .section-header { margin-bottom: 32px; }
            .bento-grid { grid-template-columns: 1fr; gap: 12px; }
            .bento-card.featured { grid-column: auto; grid-template-columns: 1fr; }
            .bento-card-body { padding: 16px; }
            .bento-card-body h3 { font-size: 1rem; }
            .gallery-compact { grid-template-columns: repeat(2, 1fr); gap: 4px; }
            .gallery-compact .gc-item:first-child { grid-column: span 2; grid-row: span 1; }
            .vehicle-cards { grid-template-columns: 1fr; gap: 12px; }
            .vehicle-card-img { width: 130px; }
            .vehicle-card-body { padding: 14px; }
            .review-cards { grid-template-columns: 1fr; gap: 12px; }
            .review-card { padding: 18px; }
            .review-form-card { padding: 20px; }
            .site-footer { padding: 36px 16px 20px; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
            .footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .hero-split-left { padding: 28px 16px; }
            .hero-split h1 { font-size: 1.6rem; }
            .hero-split-desc { font-size: 0.9rem; }
            .btn-accent, .btn-outline { padding: 12px 20px; font-size: 0.85rem; }
            .hero-stats-row { gap: 20px; }
            .hero-stat-num { font-size: 1.1rem; }
            .hero-split-right { height: 38vh; min-height: 240px; }
            .section { padding: 40px 12px; }
            .section-header { margin-bottom: 24px; }
            .bento-card-body { padding: 14px; }
            .bento-card-body h3 { font-size: 0.92rem; }
            .bento-card-body p { font-size: 0.82rem; margin-bottom: 12px; }
            .gallery-compact { grid-template-columns: 1fr 1fr; gap: 3px; }
            .gallery-compact .gc-item:first-child { grid-column: span 1; }
            .vehicle-card { flex-direction: column; }
            .vehicle-card-img { width: 100%; height: 160px; }
            .vehicle-card-body { padding: 14px; }
            .review-card { padding: 16px; }
            .review-card-quote { font-size: 0.85rem; }
            .review-form-card { padding: 16px; }
            .site-footer { padding: 28px 12px 16px; }
            .footer-grid { grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px; }
            .footer-col h5 { margin-bottom: 10px; }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--muted); }
    </style>
</head>
<body x-data="{ drawerOpen: false }">

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

<!-- HERO SPLIT -->
<section class="hero-split">
    <div class="hero-split-left">
        <span class="hero-badge"><i class="fas fa-compass"></i> {{ $settings->hero_subtitle ?? 'Temukan Pengalaman Baru' }}</span>
        <h1>{!! ($settings->hero_title ?? $website->site_name ?? 'Selamat Datang') !!}</h1>
        @if($settings->description)
            <p class="hero-split-desc">{{ $settings->description }}</p>
        @endif
        <div class="hero-actions">
            @if($tourPackages->isNotEmpty())
                <a href="#tours" class="btn-accent"><i class="fas fa-compass"></i> Lihat Paket Tour</a>
            @endif
            @if(($features['gallery_lightbox'] ?? false) && $galleryImages->isNotEmpty())
                <a href="#gallery" class="btn-outline"><i class="fas fa-images"></i> Galeri</a>
            @endif
        </div>
        <div class="hero-stats-row">
            @if($tourPackages->isNotEmpty())
                <div>
                    <div class="hero-stat-num">{{ $tourPackages->count() }}+</div>
                    <div class="hero-stat-label">{{ __('messages.tours') }}</div>
                </div>
            @endif
            @if($reviews->isNotEmpty())
                <div>
                    <div class="hero-stat-num">{{ number_format($reviews->avg('rating'), 1) }} <i class="fas fa-star" style="font-size:0.5em;color:#F59E0B"></i></div>
                    <div class="hero-stat-label">Rating</div>
                </div>
            @endif
            @if($vehicles->isNotEmpty())
                <div>
                    <div class="hero-stat-num">{{ $vehicles->count() }}</div>
                    <div class="hero-stat-label">Kendaraan</div>
                </div>
            @endif
        </div>
    </div>
    <div class="hero-split-right">
        @if($settings->hero_image_url)
            <img src="{{ $settings->hero_image_url }}" alt="{{ $settings->site_title ?? 'Hero' }}"/>
        @else
            <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800&h=900&fit=crop" alt="Travel"/>
        @endif
        <div class="hero-img-overlay">
            <div class="hero-img-overlay-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="hero-img-overlay-text">
                <div class="title">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</div>
                <div class="sub">Siap melayani Anda</div>
            </div>
        </div>
    </div>
</section>

{{-- TOUR PACKAGES --}}
@if($tourPackages->isNotEmpty())
<section class="section" id="tours">
    <div class="section-inner">
        <div class="section-header reveal">
            <div class="sec-label">Paket Pilihan</div>
            <h2 class="sec-title">Tour <em>Terpopuler</em></h2>
        </div>
        <div class="bento-grid">
            @foreach($tourPackages as $index => $tour)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $tour->slug : '/tour/' . $tour->slug }}" class="bento-card {{ $tour->is_featured ? 'featured' : '' }} reveal">
                    <div class="bento-card-img">
                        @if($tour->thumbnail_url)
                            <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}"/>
                        @else
                            <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=600&q=80" alt="{{ $tour->title }}"/>
                        @endif
                        @if($tour->is_featured)
                            <div class="bento-card-badge">Best Seller</div>
                        @endif
                    </div>
                    <div class="bento-card-body">
                        <div class="bento-card-meta">
                            @if($tour->duration_text)
                                <span><i class="far fa-clock"></i> {{ $tour->duration_text }}</span>
                            @endif
                        </div>
                        <h3>{{ $tour->title }}</h3>
                        @if($tour->description)
                            <p>{{ $tour->description }}</p>
                        @endif
                        <div class="bento-card-footer">
                            @if($tour->price_start_from)
                                <div>
                                    <div class="bento-price-from">{{ __('messages.starting_from') }}</div>
                                    <div class="bento-price-amount">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
                                </div>
                            @endif
                            <span class="bento-card-link">Detail <i class="fas fa-arrow-right" style="font-size:0.7em;"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- GALLERY --}}
@if(($features['gallery_lightbox'] ?? false) && $galleryImages->isNotEmpty())
<section class="section" id="gallery" style="background:var(--card);"
         x-data="galleryLightbox(@js($galleryImages->values()->all()))" x-init="init()">
    <div class="section-inner">
        <div class="section-header reveal">
            <div class="sec-label">{{ __('messages.gallery_title') }}</div>
            <h2 class="sec-title">Momen <em>Tak Terlupakan</em></h2>
        </div>
        <div class="gallery-compact reveal">
            @foreach($galleryImages as $index => $image)
                <div class="gc-item" @click="open({{ $index }})">
                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? 'Gallery' }}"/>
                    <div class="gc-item-overlay"><i class="fas fa-expand"></i></div>
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
    </div>
</section>
@endif

{{-- VEHICLES --}}
@if($vehicles->isNotEmpty())
<section class="section" id="vehicles">
    <div class="section-inner">
        <div class="section-header reveal">
            <div class="sec-label">{{ __('messages.our_fleet') }}</div>
            <h2 class="sec-title">Kendaraan <em>Nyaman & Aman</em></h2>
        </div>
        <div class="vehicle-cards">
            @foreach($vehicles as $vehicle)
                <div class="vehicle-card reveal">
                    <div class="vehicle-card-img">
                        @if($vehicle->image_url)
                            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model_name }}"/>
                        @else
                            <img src="https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=400&q=80" alt="{{ $vehicle->model_name }}"/>
                        @endif
                    </div>
                    <div class="vehicle-card-body">
                        <div class="vehicle-card-type">Kendaraan</div>
                        <h3>{{ $vehicle->model_name }}</h3>
                        <div class="vehicle-card-specs">
                            @if($vehicle->capacity_people)
                                <span class="vehicle-card-spec"><i class="fas fa-users"></i> {{ $vehicle->capacity_people }} Kursi</span>
                            @endif
                            <span class="vehicle-card-spec"><i class="fas fa-snowflake"></i> AC</span>
                        </div>
                        @if($vehicle->price_per_day)
                            <div class="vehicle-card-price">
                                <span class="label">Mulai</span>
                                <span class="amount">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</span>
                                <span class="unit">/hari</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- REVIEWS --}}
@if($features['reviews'] ?? false)
<section class="section" style="background:var(--card);" id="reviews">
    <div class="section-inner">
        <div class="section-header reveal" style="text-align:center;">
            <div class="sec-label" style="justify-content:center;">Testimoni</div>
            <h2 class="sec-title">Kata Mereka <em>Tentang Kami</em></h2>
        </div>

        @if(session('review_success'))
            <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:12px 20px;margin-bottom:24px;text-align:center;font-size:0.88rem;color:var(--accent);max-width:800px;margin-left:auto;margin-right:auto;">
                <i class="fas fa-check-circle"></i> {{ session('review_success') }}
            </div>
        @endif

        @if($reviews->isNotEmpty())
        <div class="review-cards">
            @foreach($reviews as $review)
                <div class="review-card reveal">
                    <div class="review-card-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <div class="review-card-quote">{{ $review->comment }}</div>
                    <div class="review-card-author">
                        <div class="review-card-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</div>
                        <div>
                            <div class="review-card-name">{{ $review->reviewer_name }}</div>
                            <div class="review-card-date">{{ $review->created_at->format('d M Y') }}</div>
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
        <div class="review-form-card reveal">
            <h3><i class="fas fa-pen-to-square"></i> Tulis Review</h3>
            <form method="POST" action="{{ route('public.reviews.store', $website->subdomain) }}">
                @csrf
                @if($errors->any())
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:0.82rem;color:#dc2626;">
                        @foreach($errors->all() as $error) <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p> @endforeach
                    </div>
                @endif
                <div class="form-field">
                    <label>Nama *</label>
                    <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}" required class="form-input"/>
                </div>
                <div class="form-field">
                    <label>Email</label>
                    <input type="email" name="reviewer_email" value="{{ old('reviewer_email') }}" class="form-input"/>
                </div>
                <div class="form-field" x-data="{ rating: 0 }">
                    <label>Rating *</label>
                    <div style="display:flex;gap:4px;">
                        <template x-for="i in 5" :key="i">
                            <button type="button" @click="rating = i; $refs.ratingInput.value = i"
                                    style="background:none;border:none;font-size:1.3rem;cursor:pointer;padding:2px;"
                                    :style="i <= rating ? 'color:#F59E0B' : 'color:var(--border)'"><i class="fas fa-star"></i></button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" value="{{ old('rating') }}" x-ref="ratingInput" required/>
                </div>
                <div class="form-field">
                    <label>Komentar *</label>
                    <textarea name="comment" rows="3" required class="form-input" style="resize:vertical;">{{ old('comment') }}</textarea>
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Kirim Review
                </button>
                <p class="form-note">Review akan ditampilkan setelah disetujui.</p>
            </form>
        </div>
    </div>
</section>
@endif

{{-- EMPTY STATE --}}
@if($tourPackages->isEmpty() && $vehicles->isEmpty())
    <div class="empty-state">
        <div class="icon"><i class="fas fa-paper-plane"></i></div>
        <h3>Website Sedang Disiapkan</h3>
        <p>Konten akan segera tersedia.</p>
    </div>
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

    // Scroll Reveal
    document.addEventListener('DOMContentLoaded', function() {
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });
        reveals.forEach(el => observer.observe(el));
    });
</script>
</body>
</html>
