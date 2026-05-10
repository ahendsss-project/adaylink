{{-- Template: Quick — Booking-Focused Tour Detail --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
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
    <title>{{ $tour->title }} — {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if($settings)
        <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($tour->description ?? ''), 160) }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700;800&family={{ urlencode($fontBody) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        :root {
            --bg: #F0FDFA; --fg: #134E4A; --muted: #6B7280; --accent: {{ $primaryColor }}; --accent-dark: #0E7490;
            --accent-soft: #ECFEFF; --cta: #EA580C; --cta-dark: #C2410C; --cta-soft: #FFF7ED;
            --card: #FFFFFF; --border: #D1D5DB; --surface: #F9FAFB;
            --font-heading: '{{ $fontHeading }}', sans-serif; --font-body: '{{ $fontBody }}', sans-serif;
            --radius: 12px; --shadow: 0 1px 8px rgba(0,0,0,0.06); --shadow-lg: 0 4px 24px rgba(0,0,0,0.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: var(--font-body); background: var(--bg); color: var(--fg); overflow-x: hidden; -webkit-font-smoothing: antialiased; padding-bottom: 72px; }

        /* NAVBAR */
        .qk-nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; background: var(--card); border-bottom: 2px solid var(--accent); }
        .qk-nav-inner { max-width: 1100px; margin: 0 auto; padding: 0 20px; height: 60px; display: flex; align-items: center; justify-content: space-between; }
        .qk-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .qk-logo-icon { width: 34px; height: 34px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 13px; overflow: hidden; flex-shrink: 0; }
        .qk-logo-icon img { width: 100%; height: 100%; object-fit: cover; }
        .qk-logo-text { font-family: var(--font-heading); font-size: 1rem; font-weight: 700; color: var(--fg); }
        .qk-nav-links { display: flex; align-items: center; gap: 4px; list-style: none; }
        .qk-nav-links a { padding: 7px 14px; border-radius: 8px; color: var(--muted); text-decoration: none; font-size: 0.82rem; font-weight: 500; transition: all 0.2s; }
        .qk-nav-links a:hover { color: var(--fg); background: var(--surface); }
        .qk-btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 20px; background: var(--cta); color: white; text-decoration: none; border-radius: 8px; font-family: var(--font-heading); font-size: 0.82rem; font-weight: 700; transition: all 0.2s; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(234,88,12,0.25); }
        .qk-btn:hover { background: var(--cta-dark); }
        .qk-btn-accent { background: var(--accent); box-shadow: 0 2px 8px rgba(8,145,178,0.25); }
        .qk-btn-accent:hover { background: var(--accent-dark); }
        .qk-hamburger { display: none; background: none; border: none; color: var(--fg); font-size: 1.1rem; cursor: pointer; padding: 8px; }

        /* MOBILE DRAWER */
        .qk-drawer { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 200; background: rgba(0,0,0,0.3); }
        .qk-drawer-panel { position: absolute; top: 0; right: 0; width: min(300px, 85vw); height: 100%; background: var(--card); padding: 20px; transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .qk-drawer.open .qk-drawer-panel { transform: translateX(0); }
        .qk-drawer-close { background: none; border: none; color: var(--muted); font-size: 1.2rem; cursor: pointer; padding: 8px; float: right; }
        .qk-drawer-links { clear: both; padding-top: 20px; }
        .qk-drawer-links a { display: block; padding: 12px 0; color: var(--fg); text-decoration: none; font-size: 0.95rem; font-weight: 500; border-bottom: 1px solid var(--border); }
        .qk-drawer-links a:last-child { border-bottom: none; }

        /* HERO */
        .qk-hero { padding-top: 60px; }
        .qk-hero-inner { max-width: 1100px; margin: 0 auto; padding: 28px 20px 0; animation: qkIn 0.5s ease both; }
        @keyframes qkIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .qk-breadcrumb { display: flex; align-items: center; gap: 6px; margin-bottom: 12px; font-size: 0.75rem; color: var(--muted); }
        .qk-breadcrumb a { color: var(--muted); text-decoration: none; }
        .qk-breadcrumb a:hover { color: var(--accent); }
        .qk-breadcrumb .sep { font-size: 0.55rem; }
        .qk-hero-title { font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: var(--fg); letter-spacing: -0.5px; line-height: 1.15; margin-bottom: 10px; }
        .qk-hero-meta { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
        .qk-hero-meta-item { display: flex; align-items: center; gap: 5px; font-size: 0.82rem; color: var(--muted); font-weight: 500; }
        .qk-hero-meta-item i { color: var(--accent); font-size: 0.75rem; }
        .qk-hero-image { width: 100%; aspect-ratio: 21/8; overflow: hidden; border-radius: var(--radius); background: var(--surface); }
        .qk-hero-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .qk-hero-image-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: 2rem; }

        /* CONTENT + BOOKING SIDEBAR */
        .qk-content-wrap { max-width: 1100px; margin: 0 auto; padding: 28px 20px 56px; display: grid; grid-template-columns: 1fr 340px; gap: 28px; align-items: start; }
        .qk-main { min-width: 0; }
        .qk-content-block { margin-bottom: 36px; }
        .qk-content-block:last-child { margin-bottom: 0; }
        .qk-block-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--accent); margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid var(--accent); display: inline-block; }
        .qk-description { font-size: 0.92rem; line-height: 1.85; color: var(--fg); }
        .qk-description p { margin-bottom: 14px; }

        /* ITINERARY */
        .qk-itinerary-list { list-style: none; }
        .qk-itinerary-item { display: flex; gap: 12px; padding: 14px 0; border-bottom: 1px solid var(--border); }
        .qk-itinerary-item:last-child { border-bottom: none; }
        .qk-itinerary-day { flex-shrink: 0; width: 44px; height: 44px; background: var(--accent); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: var(--font-heading); font-size: 0.72rem; font-weight: 700; }
        .qk-itinerary-body { flex: 1; min-width: 0; }
        .qk-itinerary-title { font-family: var(--font-heading); font-size: 0.9rem; font-weight: 700; color: var(--fg); margin-bottom: 3px; }
        .qk-itinerary-desc { font-size: 0.82rem; color: var(--muted); line-height: 1.6; }

        /* INCLUDES / EXCLUDES */
        .qk-check-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .qk-check-list { list-style: none; }
        .qk-check-list li { display: flex; align-items: flex-start; gap: 6px; padding: 6px 0; font-size: 0.85rem; color: var(--fg); line-height: 1.5; }
        .qk-check-list li i { margin-top: 3px; font-size: 0.65rem; flex-shrink: 0; }
        .qk-check-include li i { color: #059669; }
        .qk-check-exclude li i { color: #DC2626; }

        /* GALLERY */
        .qk-gallery-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
        .qk-gallery-item { aspect-ratio: 1; overflow: hidden; border-radius: 8px; cursor: pointer; }
        .qk-gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .qk-gallery-item:hover img { transform: scale(1.06); }

        /* NOTES */
        .qk-notes { font-size: 0.85rem; color: var(--muted); line-height: 1.7; padding: 14px; background: var(--accent-soft); border-radius: 8px; border-left: 3px solid var(--accent); }

        /* BOOKING SIDEBAR */
        .qk-sidebar { position: sticky; top: 76px; }
        .qk-booking-sidebar {
            background: var(--card); border-radius: var(--radius); padding: 20px;
            box-shadow: var(--shadow-lg); border: 2px solid var(--cta);
        }
        .qk-booking-sidebar h3 { font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; color: var(--fg); margin-bottom: 14px; display: flex; align-items: center; gap: 6px; }
        .qk-booking-sidebar h3 i { color: var(--cta); }
        .qk-price-row { display: flex; align-items: baseline; gap: 6px; margin-bottom: 16px; }
        .qk-price-label { font-size: 0.72rem; color: var(--muted); font-weight: 500; }
        .qk-price-value { font-family: var(--font-heading); font-size: 1.6rem; font-weight: 800; color: var(--cta); letter-spacing: -0.5px; }
        .qk-price-value span { font-size: 0.78rem; font-weight: 400; color: var(--muted); }
        .qk-booking-field { margin-bottom: 10px; }
        .qk-booking-field label { display: block; font-size: 0.68rem; font-weight: 600; color: var(--muted); margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.5px; }
        .qk-booking-input { width: 100%; padding: 9px 10px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 0.82rem; font-family: var(--font-body); outline: none; transition: border-color 0.2s; background: var(--surface); }
        .qk-booking-input:focus { border-color: var(--accent); background: white; }
        .qk-sidebar-info-list { list-style: none; margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border); }
        .qk-sidebar-info-list li { display: flex; align-items: center; gap: 8px; padding: 7px 0; font-size: 0.82rem; color: var(--fg); }
        .qk-sidebar-info-list li i { color: var(--accent); font-size: 0.72rem; width: 14px; text-align: center; }

        /* RELATED */
        .qk-related { background: var(--card); border-top: 1px solid var(--border); }
        .qk-related-inner { max-width: 1100px; margin: 0 auto; padding: 44px 20px 52px; }
        .qk-related-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .qk-rel-card { border-radius: var(--radius); overflow: hidden; text-decoration: none; color: inherit; box-shadow: var(--shadow); border: 1px solid var(--border); transition: all 0.2s; }
        .qk-rel-card:hover { box-shadow: var(--shadow-lg); border-color: var(--accent); }
        .qk-rel-card-img { width: 100%; aspect-ratio: 16/10; object-fit: cover; display: block; }
        .qk-rel-card-img-placeholder { width: 100%; aspect-ratio: 16/10; background: var(--accent-soft); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1rem; }
        .qk-rel-card-body { padding: 14px; }
        .qk-rel-card-title { font-family: var(--font-heading); font-size: 0.88rem; font-weight: 700; color: var(--fg); margin-bottom: 6px; }
        .qk-rel-card-footer { display: flex; align-items: center; justify-content: space-between; }
        .qk-rel-price { font-size: 0.82rem; font-weight: 700; color: var(--cta); }
        .qk-rel-price span { font-weight: 400; font-size: 0.68rem; color: var(--muted); }
        .qk-rel-cta { padding: 5px 12px; background: var(--cta); color: white; border-radius: 6px; font-size: 0.68rem; font-weight: 700; }

        /* FOOTER */
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

        /* SHARE FAB */
        .share-fab { position: fixed; bottom: 20px; left: 20px; z-index: 90; }
        .share-fab-options { display: flex; flex-direction: column; gap: 6px; margin-bottom: 6px; }
        .share-fab-options a, .share-fab-options button { width: 36px; height: 36px; border-radius: 8px; border: none; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; transition: transform 0.2s; }
        .share-fab-options a:hover, .share-fab-options button:hover { transform: scale(1.1); }
        .share-fab-trigger { width: 42px; height: 42px; border-radius: 10px; border: none; background: var(--accent); color: white; font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 12px rgba(8,145,178,0.3); transition: all 0.2s; }
        .share-fab-trigger:hover { background: var(--accent-dark); }

        /* STICKY BOTTOM BAR */
        .qk-bottom-bar {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 90;
            background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            border-top: 2px solid var(--cta); box-shadow: 0 -2px 16px rgba(0,0,0,0.06);
            transform: translateY(100%); transition: transform 0.3s ease;
        }
        .qk-bottom-bar.visible { transform: translateY(0); }
        .qk-bottom-bar-inner { max-width: 1100px; margin: 0 auto; padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .qk-bottom-price-label { font-size: 0.68rem; color: var(--muted); }
        .qk-bottom-price-value { font-family: var(--font-heading); font-size: 1.2rem; font-weight: 800; color: var(--cta); }
        .qk-bottom-price-value span { font-size: 0.72rem; font-weight: 400; color: var(--muted); }

        /* RESPONSIVE */
        @media (max-width: 960px) {
            .qk-nav-links { display: none; } .qk-hamburger { display: block; } .qk-drawer { display: block; }
            .qk-hero-inner { padding: 24px 20px 0; }
            .qk-hero-title { font-size: 1.8rem; }
            .qk-hero-image { aspect-ratio: 16/9; }
            .qk-content-wrap { grid-template-columns: 1fr; gap: 24px; padding: 24px 20px 44px; }
            .qk-sidebar { position: static; }
            .qk-check-grid { grid-template-columns: 1fr; gap: 16px; }
            .qk-related-inner { padding: 36px 20px 44px; }
            .qk-related-grid { grid-template-columns: repeat(2, 1fr); }
            .qk-footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
            .qk-footer-bottom { flex-direction: column; gap: 6px; text-align: center; }
        }

        @media (max-width: 600px) {
            body { padding-bottom: 64px; }
            .qk-nav-inner { height: 52px; }
            .qk-logo-icon { width: 30px; height: 30px; font-size: 11px; }
            .qk-logo-text { font-size: 0.88rem; }
            .qk-hero { padding-top: 52px; }
            .qk-hero-inner { padding: 20px 16px 0; }
            .qk-hero-title { font-size: 1.45rem; letter-spacing: -0.3px; }
            .qk-hero-meta { gap: 10px; }
            .qk-hero-image { aspect-ratio: 16/10; border-radius: 10px; }
            .qk-content-wrap { padding: 20px 16px 36px; gap: 20px; }
            .qk-content-block { margin-bottom: 28px; }
            .qk-description { font-size: 0.88rem; }
            .qk-itinerary-item { padding: 12px 0; gap: 10px; }
            .qk-itinerary-day { width: 38px; height: 38px; font-size: 0.68rem; }
            .qk-gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .qk-booking-sidebar { padding: 16px; }
            .qk-price-value { font-size: 1.3rem; }
            .qk-related-inner { padding: 32px 16px 36px; }
            .qk-related-grid { grid-template-columns: 1fr; gap: 10px; }
            .qk-footer-grid { grid-template-columns: 1fr; gap: 20px; padding: 32px 16px 16px; }
            .qk-bottom-bar-inner { padding: 8px 16px; }
            .qk-bottom-price-value { font-size: 1rem; }
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
            <div class="qk-logo-icon">@if($website->logo_url) <img src="{{ $website->logo_url }}" alt="Logo"/> @else <i class="fas fa-paper-plane"></i> @endif</div>
            <span class="qk-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="qk-nav-links">
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="qk-btn"><i class="fab fa-whatsapp"></i> Book Now</a></li>
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
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false">Book Now</a>
            @endif
        </div>
    </div>
</div>

<!-- HERO -->
<section class="qk-hero">
    <div class="qk-hero-inner">
        <div class="qk-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span>Tour</span>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span style="color:var(--fg);">{{ $tour->title }}</span>
        </div>
        <h1 class="qk-hero-title">{{ $tour->title }}</h1>
        <div class="qk-hero-meta">
            @if($tour->duration_text ?? $tour->duration)
                <div class="qk-hero-meta-item"><i class="far fa-clock"></i> {{ $tour->duration_text ?? $tour->duration }}</div>
            @endif
            @if($tour->location)
                <div class="qk-hero-meta-item"><i class="fas fa-map-marker-alt"></i> {{ $tour->location }}</div>
            @endif
            @if($tour->price_start_from)
                <div class="qk-hero-meta-item"><i class="fas fa-tag"></i> Mulai Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
            @endif
        </div>
        <div class="qk-hero-image">
            @if($tour->thumbnail_url)
                <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}"/>
            @elseif($tour->images->count() > 0)
                <img src="{{ $tour->images->first()->url }}" alt="{{ $tour->title }}"/>
            @else
                <div class="qk-hero-image-placeholder"><i class="fas fa-mountain"></i></div>
            @endif
        </div>
    </div>
</section>

<!-- CONTENT + BOOKING SIDEBAR -->
<div class="qk-content-wrap">
    <div class="qk-main">
        @if($tour->description)
        <div class="qk-content-block">
            <div class="qk-block-label">Deskripsi</div>
            <div class="qk-description">{!! $tour->description !!}</div>
        </div>
        @endif

        @if(is_array($tour->itinerary) && count($tour->itinerary) > 0)
        <div class="qk-content-block">
            <div class="qk-block-label">Itinerary</div>
            <ul class="qk-itinerary-list">
                @foreach($tour->itinerary as $i => $item)
                    <li class="qk-itinerary-item">
                        <div class="qk-itinerary-day">D{{ $i + 1 }}</div>
                        <div class="qk-itinerary-body">
                            <div class="qk-itinerary-title">{{ is_array($item) ? ($item['title'] ?? 'Day ' . ($i + 1)) : $item }}</div>
                            @if(is_array($item) && isset($item['description']))
                                <div class="qk-itinerary-desc">{{ $item['description'] }}</div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if((is_array($tour->includes) && count($tour->includes) > 0) || (is_array($tour->excludes) && count($tour->excludes) > 0))
        <div class="qk-content-block">
            <div class="qk-block-label">{{ __('messages.includes') }} & {{ __('messages.excludes') }}</div>
            <div class="qk-check-grid">
                @if(is_array($tour->includes) && count($tour->includes) > 0)
                    <div>
                        <div style="font-family:var(--font-heading);font-size:0.82rem;font-weight:700;margin-bottom:10px;color:#059669;"><i class="fas fa-check-circle"></i> Termasuk</div>
                        <ul class="qk-check-list qk-check-include">
                            @foreach($tour->includes as $item) <li><i class="fas fa-check-circle"></i> {{ $item }}</li> @endforeach
                        </ul>
                    </div>
                @endif
                @if(is_array($tour->excludes) && count($tour->excludes) > 0)
                    <div>
                        <div style="font-family:var(--font-heading);font-size:0.82rem;font-weight:700;margin-bottom:10px;color:#DC2626;"><i class="fas fa-times-circle"></i> Tidak Termasuk</div>
                        <ul class="qk-check-list qk-check-exclude">
                            @foreach($tour->excludes as $item) <li><i class="fas fa-times-circle"></i> {{ $item }}</li> @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        @endif

        @if($tour->images->count() > 1)
        <div class="qk-content-block">
            <div class="qk-block-label">{{ __('messages.gallery') }}</div>
            <div class="qk-gallery-grid" x-data="tourGallery(@js($tour->images->values()->all()))" x-init="init()">
                @foreach($tour->images as $i => $img)
                    <div class="qk-gallery-item" @click="open({{ $i }})">
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
        <div class="qk-content-block">
            <div class="qk-block-label">{{ __('messages.notes') }}</div>
            <div class="qk-notes">{!! $tour->notes !!}</div>
        </div>
        @endif
    </div>

    <!-- BOOKING SIDEBAR -->
    <div class="qk-sidebar">
        <div class="qk-booking-sidebar" x-data="{ bookName: '', bookDate: '', bookPax: '' }">
            <h3><i class="fas fa-calendar-check"></i> Quick Booking</h3>
            @if($tour->price_start_from)
                <div class="qk-price-row">
                    <div>
                        <div class="qk-price-label">{{ __('messages.starting_from') }}</div>
                        <div class="qk-price-value">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }} <span>/{{ __(messages.people) }}</span></div>
                    </div>
                </div>
            @endif
            <div class="qk-booking-field">
                <label>{{ __('messages.your_name') }}</label>
                <input type="text" class="qk-booking-input" placeholder="Nama Anda" x-model="bookName"/>
            </div>
            <div class="qk-booking-field">
                <label>Tanggal</label>
                <input type="date" class="qk-booking-input" x-model="bookDate"/>
            </div>
            <div class="qk-booking-field">
                <label>Jumlah Orang</label>
                <input type="number" class="qk-booking-input" placeholder="2" min="1" x-model="bookPax"/>
            </div>
            @if($website->contact_whatsapp)
                <a :href="'https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text=' + encodeURIComponent('Halo, saya ingin booking:\n\nTour: {{ $tour->title }}\nNama: ' + (bookName || '-') + '\nTanggal: ' + (bookDate || '-') + '\nJumlah: ' + (bookPax || '-') + ' orang')" target="_blank" class="qk-btn" style="width:100%;justify-content:center;padding:11px;margin-top:4px;text-decoration:none;"><i class="fab fa-whatsapp"></i> Booking Sekarang</a>
            @endif
            <ul class="qk-sidebar-info-list">
                @if($tour->duration_text ?? $tour->duration) <li><i class="far fa-clock"></i> {{ $tour->duration_text ?? $tour->duration }}</li> @endif
                @if($tour->location) <li><i class="fas fa-map-marker-alt"></i> {{ $tour->location }}</li> @endif
                @if($tour->difficulty ?? null) <li><i class="fas fa-signal"></i> {{ $tour->difficulty }}</li> @endif
                @if($tour->min_pax ?? null) <li><i class="fas fa-users"></i> Min. {{ $tour->min_pax }} orang</li> @endif
            </ul>
        </div>
    </div>
</div>

<!-- STICKY BOTTOM BAR -->
<div class="qk-bottom-bar" x-data x-init="window.addEventListener('scroll', () => { $el.classList.toggle('visible', window.scrollY > 400); })">
    <div class="qk-bottom-bar-inner">
        <div>
            @if($tour->price_start_from)
                <div class="qk-bottom-price-label">{{ __('messages.starting_from') }}</div>
                <div class="qk-bottom-price-value">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }} <span>/{{ __(messages.people) }}</span></div>
            @endif
        </div>
        @if($website->contact_whatsapp)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya tertarik dengan tour ' . $tour->title) }}" target="_blank" class="qk-btn"><i class="fab fa-whatsapp"></i> Booking Sekarang</a>
        @endif
    </div>
</div>

<!-- RELATED -->
@if(isset($relatedTours) && $relatedTours->count() > 0)
<section class="qk-related">
    <div class="qk-related-inner">
        <div class="qk-block-label" style="margin-bottom:20px;">Tour Lainnya</div>
        <div class="qk-related-grid">
            @foreach($relatedTours as $related)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $related->slug : '/tour/' . $related->slug }}" class="qk-rel-card">
                    @if($related->thumbnail_url)
                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}" class="qk-rel-card-img"/>
                    @elseif($related->images->count() > 0)
                        <img src="{{ $related->images->first()->url }}" alt="{{ $related->title }}" class="qk-rel-card-img"/>
                    @else
                        <div class="qk-rel-card-img-placeholder"><i class="fas fa-mountain"></i></div>
                    @endif
                    <div class="qk-rel-card-body">
                        <div class="qk-rel-card-title">{{ $related->title }}</div>
                        <div class="qk-rel-card-footer">
                            @if($related->price_start_from)
                                <div class="qk-rel-price"><span>Mulai </span>Rp {{ number_format($related->price_start_from, 0, ',', '.') }}</div>
                            @else
                                <div></div>
                            @endif
                            <span class="qk-rel-cta">Book</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FOOTER -->
<footer class="qk-footer">
    <div class="qk-footer-inner">
        <div class="qk-footer-grid">
            <div class="qk-footer-brand">
                <a href="{{ $homeUrl }}" class="qk-logo">
                    <div class="qk-logo-icon">@if($website->logo_url) <img src="{{ $website->logo_url }}" alt="Logo"/> @else <i class="fas fa-paper-plane"></i> @endif</div>
                    <span class="qk-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
                </a>
                <p class="qk-footer-desc">{{ $settings->description ?? 'Powered by adaylink.' }}</p>
            </div>
            <div class="qk-footer-col"><h5>Halaman</h5><ul>@foreach($pages as $p)<li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>@endforeach</ul></div>
            <div class="qk-footer-col"><h5>Kontak</h5><ul>
                @if($settings->phone ?? null)<li><a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a></li>@endif
                @if($settings->email ?? null)<li><a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></li>@endif
                @if($settings->address ?? null)<li><a href="#">{{ $settings->address }}</a></li>@endif
                @if($settings->social_instagram ?? null)<li><a href="{{ $settings->social_instagram }}" target="_blank">Instagram</a></li>@endif
                @if($website->contact_whatsapp)<li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank">WhatsApp</a></li>@endif
            </ul></div>
        </div>
        <hr class="qk-footer-divider">
        <div class="qk-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
            <span>Powered by <a href="#">adaylink</a></span>
        </div>
    </div>
</footer>

@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}" target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:88px;right:20px;z-index:89;background:#25D366;color:white;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 12px rgba(37,211,102,0.3);text-decoration:none;font-size:20px;"
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
    function tourGallery(images) {
        return {
            images, isOpen: false, currentIndex: 0,
            init() { document.addEventListener('keydown', (e) => { if (!this.isOpen) return; if (e.key === 'Escape') this.close(); if (e.key === 'ArrowLeft') this.prev(); if (e.key === 'ArrowRight') this.next(); }); },
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
            copyLink() { navigator.clipboard.writeText(this.pageUrl).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }); },
        };
    }
</script>
</body>
</html>
