{{-- Template: Card + Conversion — Page View with Diagonal Header --}}
@php
    $homeUrl = isset($demoTemplate) ? '/app/demo/' . $demoTemplate : (isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/');
    $pageUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/page' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page' : '/page');
    $tourUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/tour' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour' : '/tour');
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
    <title>{{ $page->title }} — {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if($settings)
        <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700;800&family={{ urlencode($fontBody) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        :root {
            --bg: #F9FAFB; --fg: #1E1B4B; --muted: #6B7280; --accent: {{ $primaryColor }}; --accent-dark: #3730A3;
            --accent-soft: #EEF2FF; --green: #059669; --card: #FFFFFF; --border: #E5E7EB; --surface: #F3F4F6;
            --font-heading: '{{ $fontHeading }}', sans-serif; --font-body: '{{ $fontBody }}', sans-serif;
            --radius: 16px; --shadow: 0 2px 16px rgba(0,0,0,0.06);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: var(--font-body); background: var(--bg); color: var(--fg); overflow-x: hidden; -webkit-font-smoothing: antialiased; }

        /* NAVBAR */
        .crd-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(255,255,255,0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(229,231,235,0.6); transition: all 0.3s;
        }
        .crd-nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; height: 64px; display: flex; align-items: center; justify-content: space-between; }
        .crd-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .crd-logo-icon { width: 36px; height: 36px; background: var(--accent); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; overflow: hidden; flex-shrink: 0; }
        .crd-logo-icon img { width: 100%; height: 100%; object-fit: cover; }
        .crd-logo-text { font-family: var(--font-heading); font-size: 1.05rem; font-weight: 700; color: var(--fg); }
        .crd-nav-links { display: flex; align-items: center; gap: 4px; list-style: none; }
        .crd-nav-links a { padding: 8px 14px; border-radius: 10px; color: var(--muted); text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: all 0.2s; }
        .crd-nav-links a:hover { color: var(--fg); background: var(--surface); }
        .crd-nav-links a.active { color: var(--accent); background: var(--accent-soft); }
        .crd-btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 22px; background: var(--accent); color: white; text-decoration: none; border-radius: 12px; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; transition: all 0.2s; border: none; cursor: pointer; }
        .crd-btn:hover { background: var(--accent-dark); }
        .crd-hamburger { display: none; background: none; border: none; color: var(--fg); font-size: 1.2rem; cursor: pointer; padding: 8px; }

        /* MOBILE DRAWER */
        .crd-drawer { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 200; background: rgba(0,0,0,0.3); }
        .crd-drawer-panel { position: absolute; top: 0; right: 0; width: min(320px, 85vw); height: 100%; background: var(--card); padding: 24px; transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .crd-drawer.open .crd-drawer-panel { transform: translateX(0); }
        .crd-drawer-close { background: none; border: none; color: var(--muted); font-size: 1.3rem; cursor: pointer; padding: 8px; float: right; }
        .crd-drawer-links { clear: both; padding-top: 24px; }
        .crd-drawer-links a { display: block; padding: 14px 0; color: var(--fg); text-decoration: none; font-size: 1rem; font-weight: 500; border-bottom: 1px solid var(--border); }
        .crd-drawer-links a:last-child { border-bottom: none; }

        /* PAGE HEADER — Centered */
        .crd-page-header {
            position: relative;
            padding-top: 64px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark, #3730A3) 60%, #1E1B4B 100%);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 320px;
        }

        .crd-page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .crd-page-header-inner {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 48px 24px;
            text-align: center;
            animation: crdIn 0.6s ease both;
        }

        @keyframes crdIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        .crd-page-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
        }

        .crd-page-breadcrumb a { color: rgba(255,255,255,0.6); text-decoration: none; }
        .crd-page-breadcrumb a:hover { color: white; }
        .crd-page-breadcrumb .sep { font-size: 0.6rem; }

        .crd-page-title {
            font-family: var(--font-heading);
            font-size: 2.6rem;
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
            line-height: 1.12;
        }

        /* Decorative circles */
        .crd-header-deco {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }

        .crd-header-deco-1 { width: 300px; height: 300px; top: -80px; right: -60px; }
        .crd-header-deco-2 { width: 200px; height: 200px; bottom: -40px; left: 10%; }

        /* PAGE CONTENT */
        .crd-page-content {
            max-width: 760px;
            margin: 0 auto;
            padding: 48px 24px 80px;
        }

        .crd-page-body {
            font-size: 0.95rem;
            line-height: 1.85;
            color: var(--fg);
        }

        .crd-page-body h1, .crd-page-body h2, .crd-page-body h3, .crd-page-body h4 {
            font-family: var(--font-heading);
            font-weight: 700;
            color: var(--fg);
            margin-top: 40px;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .crd-page-body h1 { font-size: 1.8rem; }
        .crd-page-body h2 { font-size: 1.5rem; }
        .crd-page-body h3 { font-size: 1.25rem; }
        .crd-page-body h4 { font-size: 1.05rem; }
        .crd-page-body p { margin-bottom: 20px; }
        .crd-page-body ul, .crd-page-body ol { margin-bottom: 20px; padding-left: 24px; }
        .crd-page-body li { margin-bottom: 8px; line-height: 1.7; }
        .crd-page-body a { color: var(--accent); text-decoration: underline; text-underline-offset: 3px; }
        .crd-page-body img { max-width: 100%; height: auto; border-radius: 12px; margin: 28px 0; display: block; }
        .crd-page-body blockquote {
            border-left: 3px solid var(--accent);
            padding: 14px 20px;
            margin: 28px 0;
            background: var(--accent-soft);
            border-radius: 0 12px 12px 0;
            font-style: italic;
            color: var(--muted);
        }
        .crd-page-body table { width: 100%; border-collapse: collapse; margin: 28px 0; }
        .crd-page-body th, .crd-page-body td { padding: 10px 16px; text-align: left; border-bottom: 1px solid var(--border); font-size: 0.88rem; }
        .crd-page-body th { font-family: var(--font-heading); font-weight: 600; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); }
        .crd-page-body hr { border: none; height: 1px; background: var(--border); margin: 36px 0; }
        .crd-page-body iframe { max-width: 100%; border-radius: 12px; margin: 28px 0; }

        /* FOOTER */
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

        /* RESPONSIVE */
        @media (max-width: 960px) {
            .crd-nav-links { display: none; } .crd-hamburger { display: block; } .crd-drawer { display: block; }
            .crd-page-header { min-height: 280px; }
            .crd-page-header-inner { padding: 40px 24px; }
            .crd-page-title { font-size: 2rem; }
            .crd-page-content { padding: 40px 24px 64px; }
            .crd-footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
            .crd-footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .crd-nav-inner { height: 56px; }
            .crd-logo-icon { width: 32px; height: 32px; font-size: 12px; }
            .crd-logo-text { font-size: 0.92rem; }
            .crd-page-header { padding-top: 56px; min-height: 240px; }
            .crd-page-header-inner { padding: 32px 16px; }
            .crd-page-title { font-size: 1.6rem; letter-spacing: -0.5px; }
            .crd-page-content { padding: 28px 16px 48px; }
            .crd-page-body { font-size: 0.9rem; }
            .crd-page-body h1 { font-size: 1.45rem; }
            .crd-page-body h2 { font-size: 1.25rem; }
            .crd-page-body h3 { font-size: 1.1rem; }
            .crd-page-body blockquote { padding: 10px 16px; margin: 20px 0; }
            .crd-footer-grid { grid-template-columns: 1fr; gap: 24px; padding: 36px 16px 20px; }
        }
    </style>
</head>
<body>

<nav class="crd-nav">
    <div class="crd-nav-inner">
        <a href="{{ $homeUrl }}" class="crd-logo">
            <div class="crd-logo-icon">@if($website->logo_url) <img src="{{ $website->logo_url }}" alt="Logo"/> @else <i class="fas fa-suitcase-rolling"></i> @endif</div>
            <span class="crd-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="crd-nav-links">
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ $pageUrlBase . '/' . $p->slug }}" class="{{ isset($page) && $page->slug === $p->slug ? 'active' : '' }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="crd-btn" style="padding:8px 18px;font-size:0.82rem;"><i class="fab fa-whatsapp"></i> Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="crd-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true"><i class="fas fa-bars"></i></button>
    </div>
</nav>

<div class="crd-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="crd-drawer-panel">
        <button class="crd-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="crd-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p) <a href="{{ $pageUrlBase . '/' . $p->slug }}" @click="drawerOpen = false">{{ $p->title }}</a> @endforeach
            @if($website->contact_whatsapp) <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false">{{ __('messages.contact') }}</a> @endif
        </div>
    </div>
</div>

<section class="crd-page-header">
    <div class="crd-header-deco crd-header-deco-1"></div>
    <div class="crd-header-deco crd-header-deco-2"></div>
    <div class="crd-page-header-inner">
        <div class="crd-page-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span style="color:white;">{{ $page->title }}</span>
        </div>
        <h1 class="crd-page-title">{{ $page->title }}</h1>
    </div>
</section>

<section class="crd-page-content">
    <div class="crd-page-body">{!! $page->content !!}</div>
</section>

<footer class="crd-footer">
    <div class="crd-footer-inner">
        <div class="crd-footer-grid">
            <div class="crd-footer-brand">
                <a href="{{ $homeUrl }}" class="crd-logo"><div class="crd-logo-icon">@if($website->logo_url) <img src="{{ $website->logo_url }}" alt="Logo"/> @else <i class="fas fa-suitcase-rolling"></i> @endif</div><span class="crd-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span></a>
                <p class="crd-footer-desc">{{ $settings->description ?? 'Powered by adaylink.' }}</p>
            </div>
            <div class="crd-footer-col"><h5>Halaman</h5><ul>@foreach($pages as $p)<li><a href="{{ $pageUrlBase . '/' . $p->slug }}">{{ $p->title }}</a></li>@endforeach</ul></div>
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
       style="position:fixed;bottom:24px;right:24px;z-index:99;background:#25D366;color:white;width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(37,211,102,0.3);text-decoration:none;font-size:22px;"
       x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(20px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},1000)">
        <i class="fab fa-whatsapp" style="font-size:22px;"></i>
    </a>
@endif

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
