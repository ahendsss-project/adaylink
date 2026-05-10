{{-- Template: Clean — Swiss/Editorial Page View --}}
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
    <title>{{ $page->title }} — {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if($settings)
        <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
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

        .topbar-nav a:hover { color: var(--fg); background: var(--surface); }
        .topbar-nav a.active { color: var(--accent); background: var(--accent-soft); }

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

        /* ── PAGE HEADER ── */
        .page-header {
            padding-top: 64px;
            background: var(--card);
            border-bottom: 1px solid var(--border);
        }

        .page-header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 24px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .page-breadcrumb a {
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .page-breadcrumb a:hover { color: var(--accent); }
        .page-breadcrumb .sep { color: var(--border); }

        .page-header h1 {
            font-family: var(--font-heading);
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        /* ── PAGE CONTENT ── */
        .page-body {
            max-width: 800px;
            margin: 0 auto;
            padding: 60px 24px 80px;
        }

        .page-body .content-text {
            font-size: 0.95rem;
            line-height: 1.85;
            color: #475569;
            white-space: pre-line;
        }

        .page-body .content-text h1,
        .page-body .content-text h2,
        .page-body .content-text h3 {
            font-family: var(--font-heading);
            color: var(--fg);
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            letter-spacing: -0.3px;
        }

        .page-body .content-text p { margin-bottom: 1em; }

        .page-body .content-text a {
            color: var(--accent);
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .page-body .content-text ul,
        .page-body .content-text ol {
            margin-bottom: 1em;
            padding-left: 1.5em;
        }

        .page-body .content-text img {
            max-width: 100%;
            border-radius: 8px;
            margin: 1.5em 0;
        }

        .page-body .content-text blockquote {
            border-left: 3px solid var(--accent);
            padding-left: 20px;
            margin: 1.5em 0;
            color: var(--muted);
            font-style: italic;
        }

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
            .page-header-inner { padding: 36px 20px; }
            .page-header h1 { font-size: clamp(1.6rem, 4vw, 2.2rem); }
            .page-body { padding: 36px 16px 60px; }
            .site-footer { padding: 36px 16px 20px; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
            .footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .page-header-inner { padding: 28px 16px; }
            .page-header h1 { font-size: 1.5rem; }
            .page-breadcrumb { font-size: 0.7rem; margin-bottom: 12px; }
            .page-body { padding: 28px 12px 48px; }
            .page-body .content-text { font-size: 0.9rem; line-height: 1.75; }
            .site-footer { padding: 28px 12px 16px; }
            .footer-grid { grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px; }
            .footer-col h5 { margin-bottom: 10px; }
        }
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
                <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" {{ $p->slug === $page->slug ? 'class="active"' : '' }}>{{ $p->title }}</a></li>
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

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="page-header-inner">
        <div class="page-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep">/</span>
            <span style="color:var(--fg)">{{ $page->title }}</span>
        </div>
        <h1>{{ $page->title }}</h1>
    </div>
</div>

<!-- PAGE CONTENT -->
<div class="page-body">
    <div class="content-text">{{ $page->content }}</div>
</div>

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
