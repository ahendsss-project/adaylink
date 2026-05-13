{{-- Template: Adventure — Bold immersive outdoor style --}}
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
    <title>{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if($settings)
        <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700;800&family={{ urlencode($fontBody) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @if(($features['reviews'] ?? false) && isset($reviewSchema) && $reviewSchema)
        <script type="application/ld+json">{{ json_encode($reviewSchema) }}</script>
    @endif
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
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(27,67,50,0.92);
            backdrop-filter: blur(12px);
            padding: 0 24px;
        }

        .adv-nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .adv-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .adv-logo-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 15px;
            overflow: hidden;
        }

        .adv-logo-icon img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }

        .adv-logo-text {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 700;
            color: white;
        }

        .adv-nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }

        .adv-nav-links a {
            padding: 8px 16px;
            border-radius: 8px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .adv-nav-links a:hover { color: white; background: rgba(255,255,255,0.1); }

        .adv-nav-links .nav-cta {
            background: var(--accent);
            color: white !important;
            border-radius: 8px;
        }

        .adv-nav-links .nav-cta:hover { background: var(--accent-dark); }

        .adv-hamburger {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
        }

        .adv-mobile {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 200;
            background: rgba(0,0,0,0.4);
        }

        .adv-mobile-panel {
            position: absolute;
            top: 0; right: 0;
            width: min(300px, 80vw);
            height: 100%;
            background: var(--forest);
            padding: 24px;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
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

        /* ── HERO: FULL-SCREEN IMMERSIVE ── */
        .adv-hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding-top: 64px;
        }

        .adv-hero-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
        }

        .adv-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(27,67,50,0.85) 0%, rgba(27,67,50,0.5) 50%, rgba(231,111,81,0.3) 100%);
        }

        .adv-hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px 24px;
            max-width: 720px;
            animation: advFadeUp 0.8s ease-out both;
        }

        @keyframes advFadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .adv-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border-radius: 100px;
            color: white;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 24px;
            border: 1px solid rgba(255,255,255,0.15);
        }

        .adv-hero h1 {
            font-family: var(--font-heading);
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 800;
            color: white;
            line-height: 1.08;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }

        .adv-hero h1 em { font-style: normal; color: var(--accent); }

        .adv-hero-desc {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        .adv-hero-btns {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .btn-adv-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: var(--font-body);
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-adv-primary:hover { background: var(--accent-dark); transform: translateY(-2px); }

        .btn-adv-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: rgba(255,255,255,0.12);
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            font-family: var(--font-body);
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-adv-ghost:hover { background: rgba(255,255,255,0.2); }

        .adv-hero-stats {
            display: flex;
            gap: 40px;
            justify-content: center;
        }

        .adv-hero-stat-num {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 700;
            color: white;
        }

        .adv-hero-stat-label {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.6);
        }

        /* ── SECTIONS ── */
        .adv-section {
            padding: 72px 24px;
        }

        .adv-section-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .adv-section-head {
            margin-bottom: 40px;
        }

        .adv-section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent);
            margin-bottom: 10px;
        }

        .adv-section-title {
            font-family: var(--font-heading);
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .adv-section-title em { font-style: normal; color: var(--accent); }

        /* ── TOUR CARDS: STACKED OVERLAY ── */
        .adv-tour-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .adv-tour-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            aspect-ratio: 3/4;
            text-decoration: none;
            color: inherit;
            display: block;
            transition: transform 0.3s;
        }

        .adv-tour-card:hover { transform: translateY(-4px); }

        .adv-tour-card img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .adv-tour-card:hover img { transform: scale(1.05); }

        .adv-tour-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(27,67,50,0.9) 0%, rgba(27,67,50,0.2) 50%, transparent 100%);
        }

        .adv-tour-card-badge {
            position: absolute;
            top: 14px; left: 14px;
            padding: 5px 12px;
            background: var(--accent);
            color: white;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 8px;
        }

        .adv-tour-card-body {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 24px;
            color: white;
        }

        .adv-tour-card-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 8px;
        }

        .adv-tour-card-meta span { display: flex; align-items: center; gap: 4px; }

        .adv-tour-card-body h3 {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.25;
        }

        .adv-tour-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .adv-tour-price-from { font-size: 0.7rem; color: rgba(255,255,255,0.6); }

        .adv-tour-price-amount {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
        }

        .adv-tour-card-btn {
            padding: 8px 16px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border-radius: 8px;
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }

        .adv-tour-card-btn:hover { background: var(--accent); }

        /* ── GALLERY: HORIZONTAL STRIP ── */
        .adv-gallery-strip {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            padding-bottom: 8px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .adv-gallery-strip::-webkit-scrollbar { display: none; }

        .adv-gallery-item {
            flex: 0 0 280px;
            scroll-snap-align: start;
            border-radius: 14px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            aspect-ratio: 3/4;
            transition: transform 0.3s;
        }

        .adv-gallery-item:hover { transform: translateY(-4px); }

        .adv-gallery-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .adv-gallery-item:hover img { transform: scale(1.05); }

        .adv-gallery-item-overlay {
            position: absolute;
            inset: 0;
            background: rgba(27,67,50,0.2);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .adv-gallery-item:hover .adv-gallery-item-overlay { opacity: 1; }

        /* ── VEHICLES: HORIZONTAL CARDS ── */
        .adv-vehicle-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .adv-vehicle-card {
            display: flex;
            background: var(--card);
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .adv-vehicle-card:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 20px rgba(231,111,81,0.08);
        }

        .adv-vehicle-card-img {
            width: 200px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .adv-vehicle-card-img img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .adv-vehicle-card:hover .adv-vehicle-card-img img { transform: scale(1.04); }

        .adv-vehicle-card-body {
            flex: 1;
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .adv-vehicle-type {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--accent);
            margin-bottom: 4px;
        }

        .adv-vehicle-card-body h3 {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .adv-vehicle-specs {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .adv-vehicle-spec {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .adv-vehicle-spec i { color: var(--forest-light); }

        .adv-vehicle-price .label { font-size: 0.78rem; color: var(--muted); }

        .adv-vehicle-price .amount {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
        }

        .adv-vehicle-price .unit { font-size: 0.72rem; color: var(--muted); }

        /* ── REVIEWS: BUBBLE CARDS ── */
        .adv-review-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            max-width: 800px;
            margin: 0 auto;
        }

        .adv-review-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            transition: border-color 0.2s;
        }

        .adv-review-card:hover { border-color: var(--accent); }

        .adv-review-stars {
            display: flex;
            gap: 2px;
            margin-bottom: 12px;
            color: var(--warm);
            font-size: 0.82rem;
        }

        .adv-review-text {
            font-size: 0.9rem;
            line-height: 1.7;
            color: #4A5D4E;
            margin-bottom: 16px;
            font-style: italic;
        }

        .adv-review-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .adv-review-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--sand);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--forest);
        }

        .adv-review-name { font-weight: 700; font-size: 0.85rem; }
        .adv-review-date { font-size: 0.72rem; color: var(--muted); }

        .adv-review-form {
            max-width: 500px;
            margin: 36px auto 0;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
        }

        .adv-review-form h3 {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .adv-form-field { margin-bottom: 12px; }

        .adv-form-field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 5px;
        }

        .adv-input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: var(--font-body);
            font-size: 0.88rem;
            background: var(--bg);
            color: var(--fg);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .adv-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(231,111,81,0.1);
        }

        .adv-btn-submit {
            width: 100%;
            background: var(--accent);
            color: white;
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-family: var(--font-body);
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .adv-btn-submit:hover { background: var(--accent-dark); }

        .adv-form-note {
            text-align: center;
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 10px;
        }

        /* ── EMPTY STATE ── */
        .adv-empty {
            text-align: center;
            padding: 72px 24px;
        }

        .adv-empty .icon { font-size: 2.5rem; margin-bottom: 16px; color: var(--border); }
        .adv-empty h3 { font-family: var(--font-heading); font-size: 1.2rem; margin-bottom: 8px; color: var(--muted); }
        .adv-empty p { font-size: 0.88rem; color: var(--muted); }

        /* ── FOOTER ── */
        .adv-footer {
            background: var(--forest);
            color: rgba(255,255,255,0.55);
            padding: 48px 24px 24px;
        }

        .adv-footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .adv-footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 36px;
        }

        .adv-footer-brand .adv-logo-text { color: white; }
        .adv-footer-desc { font-size: 0.82rem; line-height: 1.7; margin-top: 12px; }

        .adv-footer-col h5 {
            color: white;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 14px;
        }

        .adv-footer-col ul { list-style: none; }
        .adv-footer-col ul li { margin-bottom: 8px; }
        .adv-footer-col ul a {
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            font-size: 0.82rem;
            transition: color 0.2s;
        }
        .adv-footer-col ul a:hover { color: var(--accent); }

        .adv-footer-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 18px;
        }

        .adv-footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
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

        /* ── SCROLL REVEAL ── */
        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ── RESPONSIVE ── */
        @media (max-width: 960px) {
            .adv-nav-links { display: none; }
            .adv-hamburger { display: block; }
            .adv-mobile.open { display: block; }
            .adv-hero { min-height: auto; padding-top: 64px; }
            .adv-hero-content { padding: 36px 20px; }
            .adv-hero h1 { font-size: clamp(1.8rem, 5vw, 2.5rem); }
            .adv-hero-desc { font-size: 0.95rem; margin-bottom: 24px; }
            .adv-hero-stats { gap: 28px; }
            .adv-hero-stat-num { font-size: 1.3rem; }
            .adv-section { padding: 52px 16px; }
            .adv-section-head { margin-bottom: 28px; }
            .adv-tour-grid { grid-template-columns: 1fr; gap: 16px; }
            .adv-tour-card { aspect-ratio: 4/3; }
            .adv-gallery-item { flex: 0 0 220px; }
            .adv-vehicle-card { flex-direction: column; }
            .adv-vehicle-card-img { width: 100%; height: 180px; }
            .adv-vehicle-card-body { padding: 16px; }
            .adv-review-grid { grid-template-columns: 1fr; }
            .adv-review-form { padding: 20px; }
            .adv-footer { padding: 36px 16px 20px; }
            .adv-footer-grid { grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
            .adv-footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .adv-hero-content { padding: 28px 16px; }
            .adv-hero h1 { font-size: 1.6rem; }
            .adv-hero-desc { font-size: 0.9rem; }
            .btn-adv-primary, .btn-adv-ghost { padding: 12px 20px; font-size: 0.85rem; }
            .adv-hero-stats { gap: 20px; }
            .adv-hero-stat-num { font-size: 1.1rem; }
            .adv-section { padding: 40px 12px; }
            .adv-section-head { margin-bottom: 22px; }
            .adv-section-title { font-size: 1.3rem; }
            .adv-tour-card-body { padding: 18px; }
            .adv-tour-card-body h3 { font-size: 1rem; }
            .adv-gallery-item { flex: 0 0 180px; }
            .adv-review-card { padding: 16px; }
            .adv-review-form { padding: 16px; }
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
<section class="adv-hero">
    <div class="adv-hero-bg" style="background-image: url('{{ $settings->hero_image_url ?? 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=1800&q=80' }}');"></div>
    <div class="adv-hero-overlay"></div>
    <div class="adv-hero-content">
        <span class="adv-hero-badge"><i class="fas fa-compass"></i> {{ $settings->hero_subtitle ?? 'Temukan Petualangan Baru' }}</span>
        <h1>{!! ($settings->hero_title ?? $website->site_name ?? 'Selamat Datang') !!}</h1>
        @if($settings->description)
            <p class="adv-hero-desc">{{ $settings->description }}</p>
        @endif
        <div class="adv-hero-btns">
            @if($tourPackages->isNotEmpty())
                <a href="#tours" class="btn-adv-primary"><i class="fas fa-compass"></i> Lihat Paket Tour</a>
            @endif
            @if(($features['gallery_lightbox'] ?? false) && $galleryImages->isNotEmpty())
                <a href="#gallery" class="btn-adv-ghost"><i class="fas fa-images"></i> Galeri</a>
            @endif
        </div>
        <div class="adv-hero-stats">
            @if($tourPackages->isNotEmpty())
                <div>
                    <div class="adv-hero-stat-num">{{ $tourPackages->count() }}+</div>
                    <div class="adv-hero-stat-label">{{ __('messages.tours') }}</div>
                </div>
            @endif
            @if($reviews->isNotEmpty())
                <div>
                    <div class="adv-hero-stat-num">{{ number_format($reviews->avg('rating'), 1) }} <i class="fas fa-star" style="font-size:0.5em;color:var(--warm)"></i></div>
                    <div class="adv-hero-stat-label">Rating</div>
                </div>
            @endif
            @if($vehicles->isNotEmpty())
                <div>
                    <div class="adv-hero-stat-num">{{ $vehicles->count() }}</div>
                    <div class="adv-hero-stat-label">Kendaraan</div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- TOUR PACKAGES --}}
@if($tourPackages->isNotEmpty())
<section class="adv-section" id="tours">
    <div class="adv-section-inner">
        <div class="adv-section-head reveal">
            <div class="adv-section-label"><i class="fas fa-fire"></i> Paket Pilihan</div>
            <h2 class="adv-section-title">Tour <em>Terpopuler</em></h2>
        </div>
        <div class="adv-tour-grid">
            @foreach($tourPackages as $tour)
                <a href="{{ $tourUrlBase . '/' . $tour->slug }}" class="adv-tour-card reveal">
                    @if($tour->thumbnail_url)
                        <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}"/>
                    @else
                        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=600&q=80" alt="{{ $tour->title }}"/>
                    @endif
                    <div class="adv-tour-card-overlay"></div>
                    @if($tour->is_featured)
                        <div class="adv-tour-card-badge">Best Seller</div>
                    @endif
                    <div class="adv-tour-card-body">
                        <div class="adv-tour-card-meta">
                            @if($tour->duration_text)
                                <span><i class="far fa-clock"></i> {{ $tour->duration_text }}</span>
                            @endif
                        </div>
                        <h3>{{ $tour->title }}</h3>
                        <div class="adv-tour-card-footer">
                            @if($tour->price_start_from)
                                <div>
                                    <div class="adv-tour-price-from">{{ __('messages.starting_from') }}</div>
                                    <div class="adv-tour-price-amount">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
                                </div>
                            @endif
                            <span class="adv-tour-card-btn">Detail →</span>
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
<section class="adv-section" id="gallery" style="background:var(--sand);"
         x-data="galleryLightbox(@js($galleryImages->values()->all()))" x-init="init()">
    <div class="adv-section-inner">
        <div class="adv-section-head reveal">
            <div class="adv-section-label"><i class="fas fa-camera"></i> Galeri Foto</div>
            <h2 class="adv-section-title">Momen <em>Petualangan</em></h2>
        </div>
        <div class="adv-gallery-strip reveal">
            @foreach($galleryImages as $index => $image)
                <div class="adv-gallery-item" @click="open({{ $index }})">
                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? 'Gallery' }}"/>
                    <div class="adv-gallery-item-overlay"><i class="fas fa-expand"></i></div>
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
                <img :src="images[currentIndex]?.url || ''" :alt="images[currentIndex]?.alt || ''" @click.stop/>
                <div class="lightbox-counter"><span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span></div>
            </div>
        </template>
    </div>
</section>
@endif

{{-- VEHICLES --}}
@if($vehicles->isNotEmpty())
<section class="adv-section" id="vehicles">
    <div class="adv-section-inner">
        <div class="adv-section-head reveal">
            <div class="adv-section-label"><i class="fas fa-car"></i> Armada</div>
            <h2 class="adv-section-title">Kendaraan <em>Nyaman</em></h2>
        </div>
        <div class="adv-vehicle-list">
            @foreach($vehicles as $vehicle)
                <div class="adv-vehicle-card reveal">
                    <div class="adv-vehicle-card-img">
                        @if($vehicle->image_url)
                            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model_name }}"/>
                        @else
                            <img src="https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=400&q=80" alt="{{ $vehicle->model_name }}"/>
                        @endif
                    </div>
                    <div class="adv-vehicle-card-body">
                        <div class="adv-vehicle-type">Kendaraan</div>
                        <h3>{{ $vehicle->model_name }}</h3>
                        <div class="adv-vehicle-specs">
                            @if($vehicle->capacity_people)
                                <span class="adv-vehicle-spec"><i class="fas fa-users"></i> {{ $vehicle->capacity_people }} Kursi</span>
                            @endif
                            <span class="adv-vehicle-spec"><i class="fas fa-snowflake"></i> AC</span>
                        </div>
                        @if($vehicle->price_per_day)
                            <div class="adv-vehicle-price">
                                <span class="label">Mulai </span>
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
<section class="adv-section" style="background:var(--sand);" id="reviews">
    <div class="adv-section-inner">
        <div class="adv-section-head reveal" style="text-align:center;">
            <div class="adv-section-label" style="justify-content:center;"><i class="fas fa-quote-right"></i> Testimoni</div>
            <h2 class="adv-section-title">Kata Mereka <em>Tentang Kami</em></h2>
        </div>

        @if(session('review_success'))
            <div style="background:var(--card);border:1px solid var(--border);border-radius:10px;padding:12px 20px;margin-bottom:24px;text-align:center;font-size:0.88rem;color:var(--accent);max-width:800px;margin-left:auto;margin-right:auto;">
                <i class="fas fa-check-circle"></i> {{ session('review_success') }}
            </div>
        @endif

        @if($reviews->isNotEmpty())
        <div class="adv-review-grid">
            @foreach($reviews as $review)
                <div class="adv-review-card reveal">
                    <div class="adv-review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <div class="adv-review-text">{{ $review->comment }}</div>
                    <div class="adv-review-author">
                        <div class="adv-review-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</div>
                        <div>
                            <div class="adv-review-name">{{ $review->reviewer_name }}</div>
                            <div class="adv-review-date">{{ $review->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="adv-empty" style="padding:40px 24px;">
            <div class="icon"><i class="far fa-comment-dots"></i></div>
            <h3>Belum Ada Review</h3>
            <p>Jadilah yang pertama memberikan review!</p>
        </div>
        @endif

        <div class="adv-review-form reveal">
            <h3><i class="fas fa-pen-to-square"></i> Tulis Review</h3>
            <form method="POST" action="{{ route('public.reviews.store', $website->subdomain) }}">
                @csrf
                @if($errors->any())
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:0.82rem;color:#dc2626;">
                        @foreach($errors->all() as $error) <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p> @endforeach
                    </div>
                @endif
                <div class="adv-form-field">
                    <label>Nama *</label>
                    <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}" required class="adv-input"/>
                </div>
                <div class="adv-form-field">
                    <label>Email</label>
                    <input type="email" name="reviewer_email" value="{{ old('reviewer_email') }}" class="adv-input"/>
                </div>
                <div class="adv-form-field" x-data="{ rating: 0 }">
                    <label>Rating *</label>
                    <div style="display:flex;gap:4px;">
                        <template x-for="i in 5" :key="i">
                            <button type="button" @click="rating = i; $refs.ratingInput.value = i"
                                    style="background:none;border:none;font-size:1.3rem;cursor:pointer;padding:2px;"
                                    :style="i <= rating ? 'color:var(--warm)' : 'color:var(--border)'"><i class="fas fa-star"></i></button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" value="{{ old('rating') }}" x-ref="ratingInput" required/>
                </div>
                <div class="adv-form-field">
                    <label>Komentar *</label>
                    <textarea name="comment" rows="3" required class="adv-input" style="resize:vertical;">{{ old('comment') }}</textarea>
                </div>
                <button type="submit" class="adv-btn-submit"><i class="fas fa-paper-plane"></i> Kirim Review</button>
                <p class="adv-form-note">Review akan ditampilkan setelah disetujui.</p>
            </form>
        </div>
    </div>
</section>
@endif

{{-- EMPTY --}}
@if($tourPackages->isEmpty() && $vehicles->isEmpty())
    <div class="adv-empty">
        <div class="icon"><i class="fas fa-mountain"></i></div>
        <h3>Website Sedang Disiapkan</h3>
        <p>Konten akan segera tersedia.</p>
    </div>
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

    document.addEventListener('DOMContentLoaded', function() {
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        reveals.forEach(el => observer.observe(el));
    });
</script>
</body>
</html>
