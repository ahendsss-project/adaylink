{{-- Template: Quick — Booking-Focused with DM Sans + Inter --}}
@php
    $homeUrl = isset($demoTemplate) ? '/app/demo/' . $demoTemplate : (isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/');
    $pageUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/page' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page' : '/page');
    $tourUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/tour' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour' : '/tour');
    $primaryColor = $settings->primary_color ?? '#0891B2';
    $secondaryColor = $settings->secondary_color ?? '#164E63';
    $fontHeading = $settings->font_heading ?? 'DM Sans';
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
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700;800&family={{ urlencode($fontBody) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @if(($features['reviews'] ?? false) && isset($reviewSchema) && $reviewSchema)
        <script type="application/ld+json">{{ json_encode($reviewSchema) }}</script>
    @endif
    <style>
        :root {
            --bg: #F0FDFA;
            --fg: #134E4A;
            --muted: #6B7280;
            --accent: {{ $primaryColor }};
            --accent-dark: #0E7490;
            --accent-soft: #ECFEFF;
            --cta: #EA580C;
            --cta-dark: #C2410C;
            --cta-soft: #FFF7ED;
            --card: #FFFFFF;
            --border: #D1D5DB;
            --surface: #F9FAFB;
            --font-heading: '{{ $fontHeading }}', sans-serif;
            --font-body: '{{ $fontBody }}', sans-serif;
            --radius: 12px;
            --shadow: 0 1px 8px rgba(0,0,0,0.06);
            --shadow-lg: 0 4px 24px rgba(0,0,0,0.1);
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
        .qk-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: var(--card);
            border-bottom: 2px solid var(--accent);
        }

        .qk-nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .qk-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .qk-logo-icon {
            width: 34px; height: 34px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 13px; overflow: hidden; flex-shrink: 0;
        }
        .qk-logo-icon img { width: 100%; height: 100%; object-fit: cover; }
        .qk-logo-text { font-family: var(--font-heading); font-size: 1rem; font-weight: 700; color: var(--fg); }

        .qk-nav-links { display: flex; align-items: center; gap: 4px; list-style: none; }
        .qk-nav-links a {
            padding: 7px 14px; border-radius: 8px; color: var(--muted);
            text-decoration: none; font-size: 0.82rem; font-weight: 500; transition: all 0.2s;
        }
        .qk-nav-links a:hover { color: var(--fg); background: var(--surface); }
        .qk-nav-links a.active { color: var(--accent); background: var(--accent-soft); }

        .qk-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 20px; background: var(--cta); color: white;
            text-decoration: none; border-radius: 8px;
            font-family: var(--font-heading); font-size: 0.82rem; font-weight: 700;
            transition: all 0.2s; border: none; cursor: pointer;
            box-shadow: 0 2px 8px rgba(234,88,12,0.25);
        }
        .qk-btn:hover { background: var(--cta-dark); }
        .qk-btn-accent { background: var(--accent); box-shadow: 0 2px 8px rgba(8,145,178,0.25); }
        .qk-btn-accent:hover { background: var(--accent-dark); }
        .qk-btn-outline { background: transparent; color: var(--accent); border: 1.5px solid var(--border); box-shadow: none; }
        .qk-btn-outline:hover { border-color: var(--accent); background: var(--accent-soft); }

        .qk-hamburger { display: none; background: none; border: none; color: var(--fg); font-size: 1.1rem; cursor: pointer; padding: 8px; }

        /* ── MOBILE DRAWER ── */
        .qk-drawer { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 200; background: rgba(0,0,0,0.3); }
        .qk-drawer-panel {
            position: absolute; top: 0; right: 0; width: min(300px, 85vw); height: 100%;
            background: var(--card); padding: 20px;
            transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .qk-drawer.open .qk-drawer-panel { transform: translateX(0); }
        .qk-drawer-close { background: none; border: none; color: var(--muted); font-size: 1.2rem; cursor: pointer; padding: 8px; float: right; }
        .qk-drawer-links { clear: both; padding-top: 20px; }
        .qk-drawer-links a { display: block; padding: 12px 0; color: var(--fg); text-decoration: none; font-size: 0.95rem; font-weight: 500; border-bottom: 1px solid var(--border); }
        .qk-drawer-links a:last-child { border-bottom: none; }

        /* ── HERO — Split with Booking Card ── */
        .qk-hero {
            padding-top: 60px;
            background: linear-gradient(135deg, var(--accent-soft) 0%, #F0FDFA 50%, var(--cta-soft) 100%);
        }

        .qk-hero-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 20px 56px;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 40px;
            align-items: center;
            animation: qkIn 0.6s ease both;
        }

        @keyframes qkIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

        .qk-hero h1 {
            font-family: var(--font-heading);
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--fg);
            line-height: 1.15;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }

        .qk-hero h1 span { color: var(--accent); }

        .qk-hero-desc {
            font-size: 0.92rem;
            line-height: 1.7;
            color: var(--muted);
            margin-bottom: 24px;
            max-width: 480px;
        }

        .qk-hero-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

        .qk-hero-badges { display: flex; gap: 16px; margin-top: 20px; flex-wrap: wrap; }
        .qk-hero-badge { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: var(--muted); font-weight: 500; }
        .qk-hero-badge i { color: var(--accent); font-size: 0.72rem; }

        /* Booking Card */
        .qk-booking-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
        }

        .qk-booking-card h3 {
            font-family: var(--font-heading);
            font-size: 1rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qk-booking-card h3 i { color: var(--cta); }

        .qk-booking-field { margin-bottom: 12px; }
        .qk-booking-field label { display: block; font-size: 0.72rem; font-weight: 600; color: var(--muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
        .qk-booking-input {
            width: 100%; padding: 10px 12px; border: 1.5px solid var(--border);
            border-radius: 8px; font-size: 0.85rem; font-family: var(--font-body);
            outline: none; transition: border-color 0.2s; background: var(--surface);
        }
        .qk-booking-input:focus { border-color: var(--accent); background: white; }

        .qk-booking-info {
            display: flex; align-items: center; gap: 8px;
            padding: 10px; background: var(--accent-soft); border-radius: 8px;
            margin-bottom: 14px; font-size: 0.78rem; color: var(--accent);
        }
        .qk-booking-info i { font-size: 0.72rem; }

        /* ── SECTION COMMON ── */
        .qk-section { max-width: 1100px; margin: 0 auto; padding: 56px 20px; }
        .qk-section-head { margin-bottom: 32px; }
        .qk-section-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--accent); margin-bottom: 6px; }
        .qk-section-title { font-family: var(--font-heading); font-size: 1.6rem; font-weight: 700; color: var(--fg); letter-spacing: -0.3px; }

        /* ── TOUR CARDS ── */
        .qk-tours-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }

        .qk-tour-card {
            background: var(--card); border-radius: var(--radius); overflow: hidden;
            text-decoration: none; color: inherit; box-shadow: var(--shadow);
            transition: all 0.2s; border: 1px solid var(--border); position: relative;
        }
        .qk-tour-card:hover { box-shadow: var(--shadow-lg); border-color: var(--accent); }

        .qk-tour-card-img-wrap { position: relative; overflow: hidden; }
        .qk-tour-card-img { width: 100%; aspect-ratio: 16/10; object-fit: cover; display: block; transition: transform 0.4s; }
        .qk-tour-card:hover .qk-tour-card-img { transform: scale(1.04); }
        .qk-tour-card-img-placeholder { width: 100%; aspect-ratio: 16/10; background: var(--accent-soft); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.3rem; }

        .qk-tour-card-badge {
            position: absolute; top: 10px; left: 10px;
            padding: 3px 10px; background: var(--cta); color: white;
            border-radius: 6px; font-size: 0.68rem; font-weight: 700;
        }

        .qk-tour-card-price-tag {
            position: absolute; bottom: 10px; right: 10px;
            padding: 4px 12px; background: rgba(0,0,0,0.7); color: white;
            border-radius: 6px; font-family: var(--font-heading);
            font-size: 0.82rem; font-weight: 700; backdrop-filter: blur(4px);
        }

        .qk-tour-card-body { padding: 16px; }
        .qk-tour-card-title { font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; color: var(--fg); margin-bottom: 6px; }
        .qk-tour-card-meta { display: flex; align-items: center; gap: 10px; font-size: 0.75rem; color: var(--muted); margin-bottom: 12px; }
        .qk-tour-card-meta i { color: var(--accent); font-size: 0.68rem; }

        .qk-tour-card-book {
            display: block; width: 100%; padding: 9px; background: var(--cta);
            color: white; text-align: center; border-radius: 8px;
            font-family: var(--font-heading); font-size: 0.82rem; font-weight: 700;
            transition: background 0.2s; border: none; cursor: pointer;
        }
        .qk-tour-card-book:hover { background: var(--cta-dark); }

        /* ── GALLERY ── */
        .qk-gallery-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
        .qk-gallery-item { aspect-ratio: 1; overflow: hidden; border-radius: 8px; cursor: pointer; }
        .qk-gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .qk-gallery-item:hover img { transform: scale(1.06); }

        /* ── VEHICLES ── */
        .qk-vehicles-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .qk-vehicle-card {
            background: var(--card); border-radius: var(--radius); padding: 16px;
            box-shadow: var(--shadow); border: 1px solid var(--border);
            display: flex; align-items: center; gap: 14px;
        }
        .qk-vehicle-thumb {
            width: 56px; height: 56px; border-radius: 10px; overflow: hidden; flex-shrink: 0;
            background: var(--accent-soft); display: flex; align-items: center;
            justify-content: center; color: var(--accent); font-size: 1rem;
        }
        .qk-vehicle-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .qk-vehicle-name { font-family: var(--font-heading); font-size: 0.88rem; font-weight: 700; color: var(--fg); margin-bottom: 4px; }
        .qk-vehicle-specs { display: flex; gap: 10px; font-size: 0.72rem; color: var(--muted); }
        .qk-vehicle-specs span { display: flex; align-items: center; gap: 3px; }
        .qk-vehicle-specs i { color: var(--accent); font-size: 0.65rem; }

        /* ── REVIEWS ── */
        .qk-reviews-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .qk-review-card { background: var(--card); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); border: 1px solid var(--border); }
        .qk-review-stars { display: flex; gap: 2px; margin-bottom: 10px; color: #F59E0B; font-size: 0.75rem; }
        .qk-review-text { font-size: 0.85rem; line-height: 1.7; color: var(--fg); margin-bottom: 14px; }
        .qk-review-author { display: flex; align-items: center; gap: 8px; }
        .qk-review-avatar {
            width: 32px; height: 32px; background: var(--accent); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 0.72rem; font-weight: 700; flex-shrink: 0;
        }
        .qk-review-name { font-size: 0.82rem; font-weight: 600; color: var(--fg); }
        .qk-review-date { font-size: 0.68rem; color: var(--muted); }

        /* ── REVIEW FORM ── */
        .qk-review-form { max-width: 480px; margin: 28px auto 0; background: var(--card); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); border: 1px solid var(--border); }
        .qk-review-form h3 { font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; margin-bottom: 16px; }
        .qk-form-field { margin-bottom: 12px; }
        .qk-form-field label { display: block; font-size: 0.72rem; font-weight: 600; color: var(--muted); margin-bottom: 4px; }
        .qk-input {
            width: 100%; padding: 9px 12px; border: 1.5px solid var(--border);
            border-radius: 8px; font-size: 0.85rem; font-family: var(--font-body);
            outline: none; transition: border-color 0.2s;
        }
        .qk-input:focus { border-color: var(--accent); }

        /* ── CTA STRIP ── */
        .qk-cta-strip {
            background: var(--cta);
            padding: 32px 20px;
            text-align: center;
        }
        .qk-cta-strip h3 { font-family: var(--font-heading); font-size: 1.3rem; font-weight: 700; color: white; margin-bottom: 8px; }
        .qk-cta-strip p { font-size: 0.85rem; color: rgba(255,255,255,0.8); margin-bottom: 20px; max-width: 440px; margin-left: auto; margin-right: auto; }
        .qk-cta-strip .qk-btn { background: white; color: var(--cta); box-shadow: none; }
        .qk-cta-strip .qk-btn:hover { background: rgba(255,255,255,0.9); }

        /* ── FOOTER ── */
        .qk-footer { background: var(--fg); color: rgba(255,255,255,0.5); }
        .qk-footer-inner { max-width: 1100px; margin: 0 auto; }
        .qk-footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 40px; padding: 40px 20px 24px; }
        .qk-footer-brand .qk-logo-text { color: white; }
        .qk-footer-desc { font-size: 0.78rem; line-height: 1.7; margin-top: 10px; max-width: 280px; }
        .qk-footer-col h5 { color: white; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; }
        .qk-footer-col ul { list-style: none; }
        .qk-footer-col ul li { margin-bottom: 8px; }
        .qk-footer-col ul a { color: rgba(255,255,255,0.4); text-decoration: none; font-size: 0.78rem; transition: color 0.2s; }
        .qk-footer-col ul a:hover { color: white; }
        .qk-footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin-bottom: 16px; }
        .qk-footer-bottom { display: flex; justify-content: space-between; align-items: center; font-size: 0.72rem; padding: 0 20px 20px; }
        .qk-footer-bottom a { color: white; text-decoration: none; }

        /* ── SHARE FAB ── */
        .share-fab { position: fixed; bottom: 20px; left: 20px; z-index: 90; }
        .share-fab-options { display: flex; flex-direction: column; gap: 6px; margin-bottom: 6px; }
        .share-fab-options a, .share-fab-options button { width: 36px; height: 36px; border-radius: 8px; border: none; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; transition: transform 0.2s; }
        .share-fab-options a:hover, .share-fab-options button:hover { transform: scale(1.1); }
        .share-fab-trigger { width: 42px; height: 42px; border-radius: 10px; border: none; background: var(--accent); color: white; font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 12px rgba(8,145,178,0.3); transition: all 0.2s; }
        .share-fab-trigger:hover { background: var(--accent-dark); }

        /* ══════════ RESPONSIVE ══════════ */
        @media (max-width: 960px) {
            .qk-nav-links { display: none; }
            .qk-hamburger { display: block; }
            .qk-drawer { display: block; }
            .qk-hero-inner { grid-template-columns: 1fr; gap: 28px; padding: 40px 20px 44px; }
            .qk-hero h1 { font-size: 2rem; }
            .qk-booking-card { max-width: 400px; }
            .qk-section { padding: 44px 20px; }
            .qk-tours-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .qk-gallery-grid { grid-template-columns: repeat(3, 1fr); }
            .qk-vehicles-grid { grid-template-columns: repeat(2, 1fr); }
            .qk-footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
            .qk-footer-bottom { flex-direction: column; gap: 6px; text-align: center; }
        }

        @media (max-width: 600px) {
            .qk-nav-inner { height: 52px; }
            .qk-logo-icon { width: 30px; height: 30px; font-size: 11px; }
            .qk-logo-text { font-size: 0.88rem; }
            .qk-hero { padding-top: 52px; }
            .qk-hero-inner { padding: 28px 16px 36px; gap: 20px; }
            .qk-hero h1 { font-size: 1.6rem; }
            .qk-hero-desc { font-size: 0.85rem; }
            .qk-hero-actions { flex-direction: column; align-items: flex-start; }
            .qk-btn { padding: 8px 16px; font-size: 0.78rem; }
            .qk-booking-card { padding: 18px; }
            .qk-section { padding: 36px 16px; }
            .qk-section-title { font-size: 1.3rem; }
            .qk-tours-grid { grid-template-columns: 1fr; gap: 12px; }
            .qk-tour-card-body { padding: 14px; }
            .qk-gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .qk-vehicles-grid { grid-template-columns: 1fr; gap: 10px; }
            .qk-reviews-grid { grid-template-columns: 1fr; gap: 10px; }
            .qk-review-card { padding: 16px; }
            .qk-review-form { padding: 18px; }
            .qk-cta-strip { padding: 28px 16px; }
            .qk-cta-strip h3 { font-size: 1.1rem; }
            .qk-footer-grid { grid-template-columns: 1fr; gap: 20px; padding: 32px 16px 16px; }
            .share-fab { bottom: 14px; left: 14px; }
            .share-fab-trigger { width: 38px; height: 38px; font-size: 13px; }
            .share-fab-options a, .share-fab-options button { width: 34px; height: 34px; font-size: 11px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="qk-nav">
    <div class="qk-nav-inner">
        <a href="{{ $homeUrl }}" class="qk-logo">
            <div class="qk-logo-icon">
                @if($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                @else
                    <i class="fas fa-paper-plane"></i>
                @endif
            </div>
            <span class="qk-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="qk-nav-links">
            <li><a href="{{ $homeUrl }}" class="active">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ $pageUrlBase . '/' . $p->slug }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin booking tour.') }}" target="_blank" class="qk-btn"><i class="fab fa-whatsapp"></i> Book Now</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="qk-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true"><i class="fas fa-bars"></i></button>
    </div>
</nav>

<!-- MOBILE DRAWER -->
<div class="qk-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="qk-drawer-panel">
        <button class="qk-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="qk-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p)
                <a href="{{ $pageUrlBase . '/' . $p->slug }}" @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin booking tour.') }}" target="_blank" @click="drawerOpen = false"><i class="fab fa-whatsapp" style="margin-right:6px;"></i>Book Now</a>
            @endif
        </div>
    </div>
</div>

<!-- HERO with Booking Card -->
<section class="qk-hero">
    <div class="qk-hero-inner">
        <div>
            <h1>{{ $settings->hero_title ?? 'Jelajahi <span>Destinasi</span> Impian' }}</h1>
            <p class="qk-hero-desc">{{ $settings->hero_subtitle ?? $settings->description ?? 'Booking tour dengan cepat dan mudah. Dapatkan pengalaman perjalanan terbaik bersama kami.' }}</p>
            <div class="qk-hero-actions">
                @if($tourPackages->count() > 0)
                    <a href="#tours" class="qk-btn"><i class="fas fa-search"></i> Lihat Paket Tour</a>
                @endif
                @if($website->contact_whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="qk-btn qk-btn-outline"><i class="fab fa-whatsapp"></i> Tanya via WA</a>
                @endif
            </div>
            <div class="qk-hero-badges">
                @if($reviews->count() > 0)
                    <div class="qk-hero-badge"><i class="fas fa-star"></i> {{ number_format($reviews->avg('rating'), 1) }} Rating</div>
                @endif
                @if($tourPackages->count() > 0)
                    <div class="qk-hero-badge"><i class="fas fa-route"></i> {{ $tourPackages->count() }}+ Tour</div>
                @endif
                <div class="qk-hero-badge"><i class="fas fa-bolt"></i> Quick Response</div>
            </div>
        </div>
        @if($website->contact_whatsapp)
        <div class="qk-booking-card" x-data="{ bookName: '', bookTour: '', bookDate: '', bookPax: '' }">
            <h3><i class="fas fa-calendar-check"></i> Quick Booking</h3>
            <div class="qk-booking-info"><i class="fas fa-info-circle"></i> Isi form di bawah untuk booking langsung via WhatsApp</div>
            <div class="qk-booking-field">
                <label>{{ __('messages.your_name') }}</label>
                <input type="text" class="qk-booking-input" placeholder="Nama Anda" x-model="bookName"/>
            </div>
            <div class="qk-booking-field">
                <label>Tour yang Diminati</label>
                <select class="qk-booking-input" x-model="bookTour">
                    <option value="">Pilih Tour...</option>
                    @foreach($tourPackages as $tour)
                        <option value="{{ $tour->title }}">{{ $tour->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="qk-booking-field">
                <label>Tanggal</label>
                <input type="date" class="qk-booking-input" x-model="bookDate"/>
            </div>
            <div class="qk-booking-field">
                <label>Jumlah Orang</label>
                <input type="number" class="qk-booking-input" placeholder="2" min="1" x-model="bookPax"/>
            </div>
            <a :href="'https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text=' + encodeURIComponent('Halo, saya ingin booking:\n\nNama: ' + (bookName || '-') + '\nTour: ' + (bookTour || '-') + '\nTanggal: ' + (bookDate || '-') + '\nJumlah: ' + (bookPax || '-') + ' orang')" target="_blank" class="qk-btn" style="width:100%;justify-content:center;padding:11px;text-decoration:none;"><i class="fab fa-whatsapp"></i> Booking via WhatsApp</a>
        </div>
        @endif
    </div>
</section>

<!-- TOUR PACKAGES -->
@if($tourPackages->count() > 0)
<section style="background:var(--card);"><div class="qk-section" id="tours">
    <div class="qk-section-head">
        <div class="qk-section-label">{{ __('messages.tours') }}</div>
        <h2 class="qk-section-title">{{ __('messages.tours') }}</h2>
    </div>
    <div class="qk-tours-grid">
        @foreach($tourPackages as $tour)
            <a href="{{ $tourUrlBase . '/' . $tour->slug }}" class="qk-tour-card">
                <div class="qk-tour-card-img-wrap">
                    @if($tour->thumbnail_url)
                        <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}" class="qk-tour-card-img"/>
                    @elseif($tour->images->count() > 0)
                        <img src="{{ $tour->images->first()->url }}" alt="{{ $tour->title }}" class="qk-tour-card-img"/>
                    @else
                        <div class="qk-tour-card-img-placeholder"><i class="fas fa-mountain"></i></div>
                    @endif
                    @if($tour->is_featured)
                        <div class="qk-tour-card-badge">Popular</div>
                    @endif
                    @if($tour->price_start_from)
                        <div class="qk-tour-card-price-tag">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
                    @endif
                </div>
                <div class="qk-tour-card-body">
                    <h3 class="qk-tour-card-title">{{ $tour->title }}</h3>
                    <div class="qk-tour-card-meta">
                        @if($tour->duration_text ?? $tour->duration)
                            <span><i class="far fa-clock"></i> {{ $tour->duration_text ?? $tour->duration }}</span>
                        @endif
                        @if($tour->location)
                            <span><i class="fas fa-map-marker-alt"></i> {{ $tour->location }}</span>
                        @endif
                    </div>
                    <div class="qk-tour-card-book">{{ __('messages.view_details') }}</div>
                </div>
            </a>
        @endforeach
    </div>
</div></section>
@endif

<!-- GALLERY -->
@if($galleryImages->count() > 0)
<section style="background:var(--accent-soft);">
    <div class="qk-section">
        <div class="qk-section-head">
            <div class="qk-section-label">{{ __('messages.gallery') }}</div>
            <h2 class="qk-section-title">{{ __('messages.gallery_description') }}</h2>
        </div>
        <div class="qk-gallery-grid" x-data="galleryLightbox(@js($galleryImages->values()->all()))" x-init="init()">
            @foreach($galleryImages as $i => $img)
                <div class="qk-gallery-item" @click="open({{ $i }})">
                    <img src="{{ $img['url'] }}" alt="{{ $img['alt'] ?? 'Gallery' }}" loading="lazy"/>
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
</section>
@endif

<!-- VEHICLES -->
@if($vehicles->count() > 0)
<section style="background:var(--cta-soft);"><div class="qk-section">
    <div class="qk-section-head">
        <div class="qk-section-label">{{ __('messages.vehicles') }}</div>
        <h2 class="qk-section-title">{{ __('messages.our_fleet') }}</h2>
    </div>
    <div class="qk-vehicles-grid">
        @foreach($vehicles as $vehicle)
            <div class="qk-vehicle-card">
                <div class="qk-vehicle-thumb">
                    @if($vehicle->image_url)
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model_name }}"/>
                    @elseif($vehicle->images->count() > 0)
                        <img src="{{ $vehicle->images->first()->url }}" alt="{{ $vehicle->model_name }}"/>
                    @else
                        <i class="fas fa-car"></i>
                    @endif
                </div>
                <div>
                    <div class="qk-vehicle-name">{{ $vehicle->model_name }}</div>
                    <div class="qk-vehicle-specs">
                        @if($vehicle->capacity_people)
                            <span><i class="fas fa-users"></i> {{ $vehicle->capacity_people }} Kursi</span>
                        @endif
                        @if($vehicle->price_per_day)
                            <span><i class="fas fa-tag"></i> Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div></section>
@endif

<!-- REVIEWS -->
@if($features['reviews'] ?? false)
<section style="background:var(--card);">
    <div class="qk-section" id="reviews">
        <div class="qk-section-head">
            <div class="qk-section-label">Testimoni</div>
            <h2 class="qk-section-title">Apa Kata Mereka</h2>
        </div>

        @if(session('review_success'))
            <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:8px;padding:10px 16px;margin-bottom:20px;text-align:center;font-size:0.82rem;color:#059669;max-width:500px;margin-left:auto;margin-right:auto;">
                <i class="fas fa-check-circle"></i> {{ session('review_success') }}
            </div>
        @endif

        @if($reviews->count() > 0)
        <div class="qk-reviews-grid">
            @foreach($reviews as $review)
                <div class="qk-review-card">
                    <div class="qk-review-stars">
                        @for($s = 0; $s < 5; $s++)
                            <i class="{{ $s < $review->rating ? 'fas' : 'far' }} fa-star" style="{{ $s >= $review->rating ? 'color:#D1D5DB;' : '' }}"></i>
                        @endfor
                    </div>
                    <p class="qk-review-text">"{{ $review->comment }}"</p>
                    <div class="qk-review-author">
                        <div class="qk-review-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</div>
                        <div>
                            <div class="qk-review-name">{{ $review->reviewer_name }}</div>
                            <div class="qk-review-date">{{ $review->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:28px;color:var(--muted);font-size:0.85rem;">
            <i class="far fa-comment-dots" style="font-size:1.3rem;display:block;margin-bottom:6px;"></i>
            Belum ada review. Jadilah yang pertama!
        </div>
        @endif

        <div class="qk-review-form">
            <h3><i class="fas fa-pen" style="color:var(--accent);margin-right:6px;"></i> Tulis Review</h3>
            <form method="POST" action="{{ route('public.reviews.store', $website->subdomain) }}">
                @csrf
                @if($errors->any())
                    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:0.78rem;color:#DC2626;">
                        @foreach($errors->all() as $error) <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p> @endforeach
                    </div>
                @endif
                <div class="qk-form-field">
                    <label>Nama *</label>
                    <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}" required class="qk-input"/>
                </div>
                <div class="qk-form-field">
                    <label>Email</label>
                    <input type="email" name="reviewer_email" value="{{ old('reviewer_email') }}" class="qk-input"/>
                </div>
                <div class="qk-form-field" x-data="{ rating: 0 }">
                    <label>Rating *</label>
                    <div style="display:flex;gap:3px;">
                        <template x-for="i in 5" :key="i">
                            <button type="button" @click="rating = i; $refs.ratingInput.value = i"
                                    style="background:none;border:none;font-size:1.2rem;cursor:pointer;padding:2px;"
                                    :style="i <= rating ? 'color:#F59E0B' : 'color:var(--border)'"><i class="fas fa-star"></i></button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" value="{{ old('rating') }}" x-ref="ratingInput" required/>
                </div>
                <div class="qk-form-field">
                    <label>Komentar *</label>
                    <textarea name="comment" rows="3" required class="qk-input" style="resize:vertical;">{{ old('comment') }}</textarea>
                </div>
                <button type="submit" class="qk-btn qk-btn-accent" style="width:100%;justify-content:center;padding:11px;"><i class="fas fa-paper-plane"></i> Kirim Review</button>
                <p style="font-size:0.68rem;color:var(--muted);margin-top:8px;text-align:center;">Review akan ditampilkan setelah disetujui.</p>
            </form>
        </div>
    </div>
</section>
@endif

<!-- CTA STRIP -->
@if($website->contact_whatsapp)
<section class="qk-cta-strip">
    <h3>Siap Booking?</h3>
    <p>Hubungi kami sekarang dan dapatkan penawaran terbaik untuk perjalanan Anda.</p>
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin booking tour.') }}"
       target="_blank" class="qk-btn"><i class="fab fa-whatsapp"></i> Booking via WhatsApp</a>
</section>
@endif

<!-- FOOTER -->
<footer class="qk-footer">
    <div class="qk-footer-inner">
        <div class="qk-footer-grid">
            <div class="qk-footer-brand">
                <a href="{{ $homeUrl }}" class="qk-logo">
                    <div class="qk-logo-icon">
                        @if($website->logo_url)
                            <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                        @else
                            <i class="fas fa-paper-plane"></i>
                        @endif
                    </div>
                    <span class="qk-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
                </a>
                <p class="qk-footer-desc">{{ $settings->description ?? 'Powered by adaylink.' }}</p>
            </div>
            <div class="qk-footer-col">
                <h5>Halaman</h5>
                <ul>
                    @foreach($pages as $p)
                        <li><a href="{{ $pageUrlBase . '/' . $p->slug }}">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="qk-footer-col">
                <h5>Kontak</h5>
                <ul>
                    @if($settings->phone ?? null)<li><a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a></li>@endif
                    @if($settings->email ?? null)<li><a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></li>@endif
                    @if($settings->address ?? null)<li><a href="#">{{ $settings->address }}</a></li>@endif
                    @if($settings->social_instagram ?? null)<li><a href="{{ $settings->social_instagram }}" target="_blank">Instagram</a></li>@endif
                    @if($website->contact_whatsapp)<li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank">WhatsApp</a></li>@endif
                </ul>
            </div>
        </div>
        <hr class="qk-footer-divider">
        <div class="qk-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
            <span>Powered by <a href="#">adaylink</a></span>
        </div>
    </div>
</footer>

{{-- Floating WhatsApp --}}
@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
       target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:20px;right:20px;z-index:99;background:#25D366;color:white;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 12px rgba(37,211,102,0.3);text-decoration:none;font-size:20px;"
       x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(16px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},800)">
        <i class="fab fa-whatsapp" style="font-size:20px;"></i>
    </a>
@endif

@if($features['social_share'] ?? false)
    <div class="share-fab" x-data="socialShare()" x-init="init()">
        <div class="share-fab-options" x-show="isOpen"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            <a :href="'https://wa.me/?text=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#25D366;"><i class="fab fa-whatsapp"></i></a>
            <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#1877F2;"><i class="fab fa-facebook-f"></i></a>
            <button @click="copyLink()" style="background:var(--accent);" :style="copied ? 'background:#059669' : ''"><i class="fas" :class="copied ? 'fa-check' : 'fa-link'"></i></button>
        </div>
        <button @click="isOpen = !isOpen" class="share-fab-trigger"><i class="fas fa-share-alt"></i></button>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function galleryLightbox(images) {
        return {
            images, isOpen: false, currentIndex: 0,
            init() { document.addEventListener('keydown', (e) => { if (!this.isOpen) return; if (e.key === 'Escape') this.close(); if (e.key === 'ArrowLeft') this.prev(); if (e.key === 'ArrowRight') this.next(); }); },
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
