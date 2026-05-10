{{-- Template: Quick — Booking-Focused Page View --}}
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
    <title>{{ $page->title }} — {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if($settings)
        <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700;800&family={{ urlencode($fontBody) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        :root {
            --bg: #F0FDFA; --fg: #134E4A; --muted: #6B7280; --accent: {{ $primaryColor }}; --accent-dark: #0E7490;
            --accent-soft: #ECFEFF; --cta: #EA580C; --cta-dark: #C2410C; --cta-soft: #FFF7ED;
            --card: #FFFFFF; --border: #D1D5DB; --surface: #F9FAFB;
            --font-heading: '{{ $fontHeading }}', sans-serif; --font-body: '{{ $fontBody }}', sans-serif;
            --radius: 12px; --shadow: 0 1px 8px rgba(0,0,0,0.06);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: var(--font-body); background: var(--bg); color: var(--fg); overflow-x: hidden; -webkit-font-smoothing: antialiased; }

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
        .qk-nav-links a.active { color: var(--accent); background: var(--accent-soft); }
        .qk-btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 20px; background: var(--cta); color: white; text-decoration: none; border-radius: 8px; font-family: var(--font-heading); font-size: 0.82rem; font-weight: 700; transition: all 0.2s; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(234,88,12,0.25); }
        .qk-btn:hover { background: var(--cta-dark); }
        .qk-hamburger { display: none; background: none; border: none; color: var(--fg); font-size: 1.1rem; cursor: pointer; padding: 8px; }

        /* MOBILE DRAWER */
        .qk-drawer { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 200; background: rgba(0,0,0,0.3); }
        .qk-drawer-panel { position: absolute; top: 0; right: 0; width: min(300px, 85vw); height: 100%; background: var(--card); padding: 20px; transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .qk-drawer.open .qk-drawer-panel { transform: translateX(0); }
        .qk-drawer-close { background: none; border: none; color: var(--muted); font-size: 1.2rem; cursor: pointer; padding: 8px; float: right; }
        .qk-drawer-links { clear: both; padding-top: 20px; }
        .qk-drawer-links a { display: block; padding: 12px 0; color: var(--fg); text-decoration: none; font-size: 0.95rem; font-weight: 500; border-bottom: 1px solid var(--border); }
        .qk-drawer-links a:last-child { border-bottom: none; }

        /* PAGE HEADER */
        .qk-page-header {
            padding-top: 60px;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 220px;
        }

        .qk-page-header-inner {
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            padding: 40px 20px;
            text-align: center;
            animation: qkIn 0.5s ease both;
        }

        @keyframes qkIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        .qk-page-breadcrumb {
            display: flex; align-items: center; justify-content: center;
            gap: 6px; margin-bottom: 12px; font-size: 0.75rem; color: rgba(255,255,255,0.5);
        }
        .qk-page-breadcrumb a { color: rgba(255,255,255,0.6); text-decoration: none; }
        .qk-page-breadcrumb a:hover { color: white; }
        .qk-page-breadcrumb .sep { font-size: 0.55rem; }

        .qk-page-title {
            font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800;
            color: white; letter-spacing: -0.5px; line-height: 1.15;
        }

        /* PAGE CONTENT */
        .qk-page-content { max-width: 760px; margin: 0 auto; padding: 44px 20px 72px; }
        .qk-page-body { font-size: 0.92rem; line-height: 1.85; color: var(--fg); }
        .qk-page-body h1, .qk-page-body h2, .qk-page-body h3, .qk-page-body h4 { font-family: var(--font-heading); font-weight: 700; color: var(--fg); margin-top: 36px; margin-bottom: 14px; line-height: 1.3; }
        .qk-page-body h1 { font-size: 1.7rem; } .qk-page-body h2 { font-size: 1.4rem; } .qk-page-body h3 { font-size: 1.2rem; } .qk-page-body h4 { font-size: 1rem; }
        .qk-page-body p { margin-bottom: 18px; }
        .qk-page-body ul, .qk-page-body ol { margin-bottom: 18px; padding-left: 22px; }
        .qk-page-body li { margin-bottom: 6px; line-height: 1.7; }
        .qk-page-body a { color: var(--accent); text-decoration: underline; text-underline-offset: 3px; }
        .qk-page-body img { max-width: 100%; height: auto; border-radius: 10px; margin: 24px 0; display: block; }
        .qk-page-body blockquote { border-left: 3px solid var(--accent); padding: 12px 18px; margin: 24px 0; background: var(--accent-soft); border-radius: 0 10px 10px 0; font-style: italic; color: var(--muted); }
        .qk-page-body table { width: 100%; border-collapse: collapse; margin: 24px 0; }
        .qk-page-body th, .qk-page-body td { padding: 9px 14px; text-align: left; border-bottom: 1px solid var(--border); font-size: 0.85rem; }
        .qk-page-body th { font-family: var(--font-heading); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); }
        .qk-page-body hr { border: none; height: 1px; background: var(--border); margin: 32px 0; }
        .qk-page-body iframe { max-width: 100%; border-radius: 10px; margin: 24px 0; }

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

        /* RESPONSIVE */
        @media (max-width: 960px) {
            .qk-nav-links { display: none; } .qk-hamburger { display: block; } .qk-drawer { display: block; }
            .qk-page-header { min-height: 200px; }
            .qk-page-header-inner { padding: 36px 20px; }
            .qk-page-title { font-size: 1.8rem; }
            .qk-page-content { padding: 36px 20px 56px; }
            .qk-footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
            .qk-footer-bottom { flex-direction: column; gap: 6px; text-align: center; }
        }

        @media (max-width: 600px) {
            .qk-nav-inner { height: 52px; }
            .qk-logo-icon { width: 30px; height: 30px; font-size: 11px; }
            .qk-logo-text { font-size: 0.88rem; }
            .qk-page-header { padding-top: 52px; min-height: 180px; }
            .qk-page-header-inner { padding: 28px 16px; }
            .qk-page-title { font-size: 1.5rem; letter-spacing: -0.3px; }
            .qk-page-content { padding: 24px 16px 44px; }
            .qk-page-body { font-size: 0.88rem; }
            .qk-page-body h1 { font-size: 1.35rem; } .qk-page-body h2 { font-size: 1.2rem; } .qk-page-body h3 { font-size: 1.05rem; }
            .qk-page-body blockquote { padding: 10px 14px; margin: 18px 0; }
            .qk-footer-grid { grid-template-columns: 1fr; gap: 20px; padding: 32px 16px 16px; }
        }
    </style>
</head>
<body>

<nav class="qk-nav">
    <div class="qk-nav-inner">
        <a href="{{ $homeUrl }}" class="qk-logo">
            <div class="qk-logo-icon">@if($website->logo_url) <img src="{{ $website->logo_url }}" alt="Logo"/> @else <i class="fas fa-paper-plane"></i> @endif</div>
            <span class="qk-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="qk-nav-links">
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" class="{{ isset($page) && $page->slug === $p->slug ? 'active' : '' }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="qk-btn"><i class="fab fa-whatsapp"></i> Book Now</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="qk-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true"><i class="fas fa-bars"></i></button>
    </div>
</nav>

<div class="qk-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="qk-drawer-panel">
        <button class="qk-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="qk-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p) <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" @click="drawerOpen = false">{{ $p->title }}</a> @endforeach
            @if($website->contact_whatsapp) <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false">Book Now</a> @endif
        </div>
    </div>
</div>

<section class="qk-page-header">
    <div class="qk-page-header-inner">
        <div class="qk-page-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span style="color:white;">{{ $page->title }}</span>
        </div>
        <h1 class="qk-page-title">{{ $page->title }}</h1>
    </div>
</section>

<section class="qk-page-content">
    <div class="qk-page-body">{!! $page->content !!}</div>
</section>

<footer class="qk-footer">
    <div class="qk-footer-inner">
        <div class="qk-footer-grid">
            <div class="qk-footer-brand">
                <a href="{{ $homeUrl }}" class="qk-logo"><div class="qk-logo-icon">@if($website->logo_url) <img src="{{ $website->logo_url }}" alt="Logo"/> @else <i class="fas fa-paper-plane"></i> @endif</div><span class="qk-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span></a>
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
       style="position:fixed;bottom:20px;right:20px;z-index:99;background:#25D366;color:white;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 12px rgba(37,211,102,0.3);text-decoration:none;font-size:20px;"
       x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(16px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},800)">
        <i class="fab fa-whatsapp" style="font-size:20px;"></i>
    </a>
@endif

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
