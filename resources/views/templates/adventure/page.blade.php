{{-- Template: Adventure — Bold immersive Page View --}}
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
    <title>{{ $page->title }} — {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if($settings)
        <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
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
            flex-shrink: 0;
        }

        .adv-logo-icon img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .adv-logo-text {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.3px;
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
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .adv-nav-links a:hover { color: white; background: rgba(255,255,255,0.1); }
        .adv-nav-links a.active { color: var(--accent); background: rgba(231,111,81,0.15); }

        .adv-nav-cta {
            background: var(--accent) !important;
            color: white !important;
            border-radius: 8px !important;
            font-weight: 700 !important;
        }

        .adv-nav-cta:hover { background: var(--accent-dark) !important; }

        .adv-hamburger {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .adv-hamburger:hover { background: rgba(255,255,255,0.1); }

        /* ── MOBILE DRAWER ── */
        .adv-drawer {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 200;
            background: rgba(0,0,0,0.5);
        }

        .adv-drawer-panel {
            position: absolute;
            top: 0; right: 0;
            width: min(320px, 85vw);
            height: 100%;
            background: var(--forest);
            padding: 24px;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .adv-drawer.open .adv-drawer-panel { transform: translateX(0); }

        .adv-drawer-close {
            background: none;
            border: none;
            color: rgba(255,255,255,0.6);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 8px;
            float: right;
        }

        .adv-drawer-close:hover { color: white; }

        .adv-drawer-links {
            clear: both;
            padding-top: 24px;
        }

        .adv-drawer-links a {
            display: block;
            padding: 14px 0;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            transition: color 0.2s;
        }

        .adv-drawer-links a:hover { color: var(--accent); }
        .adv-drawer-links a:last-child { border-bottom: none; }

        /* ── PAGE HERO ── */
        .adv-page-hero {
            position: relative;
            padding-top: 64px;
            background: linear-gradient(135deg, var(--forest) 0%, #2D6A4F 50%, #40916C 100%);
            overflow: hidden;
        }

        .adv-page-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(231,111,81,0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(212,163,115,0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .adv-page-hero-inner {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 24px 72px;
            text-align: center;
            animation: advFadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes advFadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .adv-page-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 0.82rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
        }

        .adv-page-breadcrumb a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: color 0.2s;
        }

        .adv-page-breadcrumb a:hover { color: var(--accent); }
        .adv-page-breadcrumb .sep { font-size: 0.7rem; }
        .adv-page-breadcrumb .current { color: var(--accent); }

        .adv-page-hero h1 {
            font-family: var(--font-heading);
            font-size: 3rem;
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
            line-height: 1.1;
            margin-bottom: 16px;
        }

        .adv-page-hero-subtitle {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.55);
            font-weight: 400;
            max-width: 560px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Decorative mountain shapes */
        .adv-page-hero-deco {
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 60px;
            overflow: hidden;
        }

        .adv-page-hero-deco svg {
            display: block;
            width: 100%;
            height: 100%;
        }

        /* ── PAGE CONTENT ── */
        .adv-page-content {
            max-width: 860px;
            margin: 0 auto;
            padding: 64px 24px 80px;
            animation: advFadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both;
        }

        .adv-page-body {
            font-size: 1rem;
            line-height: 1.85;
            color: var(--fg);
        }

        .adv-page-body h1,
        .adv-page-body h2,
        .adv-page-body h3,
        .adv-page-body h4 {
            font-family: var(--font-heading);
            font-weight: 700;
            color: var(--forest);
            margin-top: 40px;
            margin-bottom: 16px;
            line-height: 1.25;
        }

        .adv-page-body h1 { font-size: 2rem; }
        .adv-page-body h2 { font-size: 1.6rem; }
        .adv-page-body h3 { font-size: 1.3rem; }
        .adv-page-body h4 { font-size: 1.1rem; }

        .adv-page-body p {
            margin-bottom: 20px;
        }

        .adv-page-body ul,
        .adv-page-body ol {
            margin-bottom: 20px;
            padding-left: 24px;
        }

        .adv-page-body li {
            margin-bottom: 8px;
            line-height: 1.7;
        }

        .adv-page-body a {
            color: var(--accent);
            text-decoration: underline;
            text-underline-offset: 3px;
            transition: color 0.2s;
        }

        .adv-page-body a:hover { color: var(--accent-dark); }

        .adv-page-body img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 28px 0;
            display: block;
        }

        .adv-page-body blockquote {
            border-left: 4px solid var(--accent);
            padding: 16px 20px;
            margin: 28px 0;
            background: var(--sand);
            border-radius: 0 12px 12px 0;
            font-style: italic;
            color: var(--muted);
        }

        .adv-page-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 28px 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .adv-page-body th,
        .adv-page-body td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .adv-page-body th {
            background: var(--forest);
            color: white;
            font-family: var(--font-heading);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .adv-page-body td {
            font-size: 0.92rem;
        }

        .adv-page-body tr:nth-child(even) td {
            background: rgba(245,239,224,0.4);
        }

        .adv-page-body hr {
            border: none;
            height: 2px;
            background: var(--border);
            margin: 36px 0;
            border-radius: 1px;
        }

        .adv-page-body iframe {
            max-width: 100%;
            border-radius: 12px;
            margin: 28px 0;
        }

        /* ── CTA SECTION ── */
        .adv-page-cta {
            background: var(--sand);
            padding: 56px 24px;
            text-align: center;
        }

        .adv-page-cta-inner {
            max-width: 600px;
            margin: 0 auto;
        }

        .adv-page-cta h3 {
            font-family: var(--font-heading);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--forest);
            margin-bottom: 12px;
        }

        .adv-page-cta p {
            font-size: 0.95rem;
            color: var(--muted);
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .adv-page-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            background: var(--forest);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.25s;
        }

        .adv-page-cta-btn:hover {
            background: var(--forest-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(27,67,50,0.25);
        }

        /* ── FOOTER ── */
        .adv-footer {
            background: var(--forest);
            color: rgba(255,255,255,0.55);
        }

        .adv-footer-inner { max-width: 1200px; margin: 0 auto; }

        .adv-footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 40px;
            padding: 56px 24px 32px;
        }

        .adv-footer-brand .adv-logo-text { color: white; }
        .adv-footer-desc { font-size: 0.82rem; line-height: 1.7; margin-top: 12px; }

        .adv-footer-col h5 {
            color: white; font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 14px;
        }

        .adv-footer-col ul { list-style: none; }
        .adv-footer-col ul li { margin-bottom: 8px; }
        .adv-footer-col ul a {
            color: rgba(255,255,255,0.45); text-decoration: none;
            font-size: 0.85rem; transition: color 0.2s;
        }
        .adv-footer-col ul a:hover { color: var(--accent); }

        .adv-footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.08); margin-bottom: 18px; }

        .adv-footer-bottom {
            display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem;
            padding: 0 24px 24px;
        }

        .adv-footer-bottom a { color: var(--accent); text-decoration: none; }

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
            width: 40px; height: 40px;
            border-radius: 50%;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            transition: transform 0.2s;
        }

        .share-fab-options a:hover,
        .share-fab-options button:hover { transform: scale(1.1); }

        .share-fab-trigger {
            width: 46px; height: 46px;
            border-radius: 50%;
            border: none;
            background: var(--forest);
            color: white;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(27,67,50,0.3);
            transition: all 0.25s;
        }

        .share-fab-trigger:hover {
            background: var(--accent);
            transform: scale(1.05);
        }

        /* ══════════ RESPONSIVE ══════════ */

        /* ── Tablet (≤960px) ── */
        @media (max-width: 960px) {
            .adv-nav-links { display: none; }
            .adv-hamburger { display: block; }
            .adv-drawer { display: block; }

            .adv-page-hero-inner {
                padding: 60px 24px 56px;
            }

            .adv-page-hero h1 {
                font-size: 2.4rem;
            }

            .adv-page-content {
                padding: 48px 24px 64px;
            }

            .adv-footer { padding: 36px 16px 20px; }
            .adv-footer-grid { grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
            .adv-footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        /* ── Mobile (≤600px) ── */
        @media (max-width: 600px) {
            .adv-nav-inner { height: 56px; }
            .adv-logo-icon { width: 32px; height: 32px; font-size: 13px; }
            .adv-logo-text { font-size: 0.95rem; }

            .adv-page-hero { padding-top: 56px; }

            .adv-page-hero-inner {
                padding: 40px 16px 44px;
            }

            .adv-page-hero h1 {
                font-size: 1.85rem;
                letter-spacing: -0.5px;
            }

            .adv-page-hero-subtitle {
                font-size: 0.92rem;
            }

            .adv-page-hero-deco {
                height: 36px;
            }

            .adv-page-content {
                padding: 36px 16px 48px;
            }

            .adv-page-body {
                font-size: 0.92rem;
                line-height: 1.75;
            }

            .adv-page-body h1 { font-size: 1.6rem; margin-top: 28px; }
            .adv-page-body h2 { font-size: 1.35rem; margin-top: 28px; }
            .adv-page-body h3 { font-size: 1.15rem; margin-top: 24px; }
            .adv-page-body h4 { font-size: 1rem; margin-top: 20px; }

            .adv-page-body blockquote {
                padding: 12px 16px;
                margin: 20px 0;
            }

            .adv-page-body th,
            .adv-page-body td {
                padding: 10px 12px;
                font-size: 0.85rem;
            }

            .adv-page-cta {
                padding: 40px 16px;
            }

            .adv-page-cta h3 {
                font-size: 1.25rem;
            }

            .adv-page-cta-btn {
                padding: 12px 24px;
                font-size: 0.9rem;
            }

            .adv-footer { padding: 28px 12px 16px; }
            .adv-footer-grid { grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px; }
            .adv-footer-col h5 { margin-bottom: 10px; }

            .share-fab { bottom: 16px; left: 16px; }
            .share-fab-trigger { width: 40px; height: 40px; font-size: 14px; }
            .share-fab-options a,
            .share-fab-options button { width: 36px; height: 36px; font-size: 13px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="adv-nav">
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
                <li><a href="{{ $pageUrlBase . '/' . $p->slug }}"
                       class="{{ isset($page) && $page->slug === $p->slug ? 'active' : '' }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="adv-nav-cta"><i class="fab fa-whatsapp" style="margin-right:4px;"></i> Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="adv-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- MOBILE DRAWER -->
<div class="adv-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="adv-drawer-panel">
        <button class="adv-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="adv-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p)
                <a href="{{ $pageUrlBase . '/' . $p->slug }}"
                   @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false"><i class="fab fa-whatsapp" style="margin-right:6px;"></i>{{ __('messages.contact') }}</a>
            @endif
        </div>
    </div>
</div>

<!-- PAGE HERO -->
<section class="adv-page-hero">
    <div class="adv-page-hero-inner">
        <div class="adv-page-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span class="current">{{ $page->title }}</span>
        </div>
        <h1>{{ $page->title }}</h1>
        @if($page->excerpt ?? null)
            <p class="adv-page-hero-subtitle">{{ $page->excerpt }}</p>
        @endif
    </div>
    <div class="adv-page-hero-deco">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" fill="none">
            <path d="M0 60L48 52C96 44 192 28 288 22C384 16 480 20 576 28C672 36 768 48 864 48C960 48 1056 36 1152 28C1248 20 1344 16 1392 14L1440 12V60H0Z" fill="var(--bg)"/>
        </svg>
    </div>
</section>

<!-- PAGE CONTENT -->
<section class="adv-page-content">
    <div class="adv-page-body">
        {!! $page->content !!}
    </div>
</section>

<!-- CTA SECTION -->
@if($website->contact_whatsapp)
<section class="adv-page-cta">
    <div class="adv-page-cta-inner">
        <h3>Siap Berpetualang?</h3>
        <p>Hubungi kami sekarang untuk informasi lebih lanjut dan pemesanan.</p>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang ' . $page->title . '.') }}"
           target="_blank" class="adv-page-cta-btn">
            <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
        </a>
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
