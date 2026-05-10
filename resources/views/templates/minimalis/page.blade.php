{{-- Template: Minimalis — Pure minimalist Page View --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
    $primaryColor = $settings->primary_color ?? '#111111';
    $secondaryColor = $settings->secondary_color ?? '#555555';
    $fontHeading = $settings->font_heading ?? 'Manrope';
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
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700;800&family={{ urlencode($fontBody) }}:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        :root {
            --bg: #FAFAFA;
            --fg: #111111;
            --muted: #888888;
            --accent: {{ $primaryColor }};
            --accent2: {{ $secondaryColor }};
            --card: #FFFFFF;
            --border: #E5E5E5;
            --light: #F5F5F5;
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
            -moz-osx-font-smoothing: grayscale;
        }

        /* ── NAVBAR ── */
        .min-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(250,250,250,0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .min-nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 32px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .min-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .min-logo-mark {
            width: 28px; height: 28px;
            background: var(--fg);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .min-logo-mark img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .min-logo-name {
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--fg);
            letter-spacing: -0.3px;
        }

        .min-nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
        }

        .min-nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.2px;
            transition: color 0.2s;
        }

        .min-nav-links a:hover { color: var(--fg); }
        .min-nav-links a.active { color: var(--fg); font-weight: 600; }

        .min-nav-cta {
            padding: 7px 18px !important;
            background: var(--fg) !important;
            color: white !important;
            border-radius: 6px !important;
            font-weight: 600 !important;
            letter-spacing: 0 !important;
            transition: opacity 0.2s !important;
        }

        .min-nav-cta:hover { opacity: 0.8; }

        .min-hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--fg);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 6px;
        }

        /* ── MOBILE DRAWER ── */
        .min-drawer {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 200;
            background: rgba(0,0,0,0.2);
        }

        .min-drawer-panel {
            position: absolute;
            top: 0; right: 0;
            width: min(300px, 85vw);
            height: 100%;
            background: var(--card);
            padding: 28px 24px;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .min-drawer.open .min-drawer-panel { transform: translateX(0); }

        .min-drawer-close {
            background: none;
            border: none;
            color: var(--muted);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 4px;
            float: right;
        }

        .min-drawer-links {
            clear: both;
            padding-top: 32px;
        }

        .min-drawer-links a {
            display: block;
            padding: 14px 0;
            color: var(--fg);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            border-bottom: 1px solid var(--border);
        }

        .min-drawer-links a:last-child { border-bottom: none; }

        /* ── PAGE HEADER ── */
        .min-page-header {
            padding-top: 60px;
            border-bottom: 1px solid var(--border);
        }

        .min-page-header-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 56px 32px 48px;
            animation: minIn 0.6s ease both;
        }

        @keyframes minIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .min-page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 0.78rem;
            color: var(--muted);
        }

        .min-page-breadcrumb a {
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .min-page-breadcrumb a:hover { color: var(--fg); }
        .min-page-breadcrumb .sep { font-size: 0.6rem; }

        .min-page-title {
            font-family: var(--font-heading);
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--fg);
            letter-spacing: -1px;
            line-height: 1.15;
        }

        /* ── PAGE CONTENT ── */
        .min-page-content {
            max-width: 720px;
            margin: 0 auto;
            padding: 48px 32px 80px;
            animation: minIn 0.6s ease 0.1s both;
        }

        .min-page-body {
            font-size: 0.95rem;
            line-height: 1.85;
            color: var(--fg);
        }

        .min-page-body h1,
        .min-page-body h2,
        .min-page-body h3,
        .min-page-body h4 {
            font-family: var(--font-heading);
            font-weight: 700;
            color: var(--fg);
            margin-top: 40px;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .min-page-body h1 { font-size: 1.8rem; }
        .min-page-body h2 { font-size: 1.5rem; }
        .min-page-body h3 { font-size: 1.25rem; }
        .min-page-body h4 { font-size: 1.05rem; }

        .min-page-body p {
            margin-bottom: 20px;
        }

        .min-page-body ul,
        .min-page-body ol {
            margin-bottom: 20px;
            padding-left: 24px;
        }

        .min-page-body li {
            margin-bottom: 8px;
            line-height: 1.7;
        }

        .min-page-body a {
            color: var(--fg);
            text-decoration: underline;
            text-underline-offset: 3px;
            text-decoration-color: var(--border);
            transition: text-decoration-color 0.2s;
        }

        .min-page-body a:hover { text-decoration-color: var(--fg); }

        .min-page-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 28px 0;
            display: block;
        }

        .min-page-body blockquote {
            border-left: 2px solid var(--fg);
            padding: 12px 20px;
            margin: 28px 0;
            font-style: italic;
            color: var(--muted);
        }

        .min-page-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 28px 0;
        }

        .min-page-body th,
        .min-page-body td {
            padding: 10px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 0.88rem;
        }

        .min-page-body th {
            font-family: var(--font-heading);
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--muted);
        }

        .min-page-body hr {
            border: none;
            height: 1px;
            background: var(--border);
            margin: 36px 0;
        }

        .min-page-body iframe {
            max-width: 100%;
            border-radius: 8px;
            margin: 28px 0;
        }

        /* ── FOOTER ── */
        .min-footer {
            border-top: 1px solid var(--border);
        }

        .min-footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 32px 32px;
        }

        .min-footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 40px;
        }

        .min-footer-brand-desc {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.7;
            margin-top: 12px;
            max-width: 320px;
        }

        .min-footer-col-title {
            font-family: var(--font-heading);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--fg);
            margin-bottom: 16px;
        }

        .min-footer-col ul { list-style: none; }
        .min-footer-col ul li { margin-bottom: 10px; }
        .min-footer-col ul a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.82rem;
            transition: color 0.2s;
        }
        .min-footer-col ul a:hover { color: var(--fg); }

        .min-footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            font-size: 0.75rem;
            color: var(--muted);
        }

        .min-footer-bottom a {
            color: var(--fg);
            text-decoration: none;
            font-weight: 500;
        }

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
            width: 38px; height: 38px;
            border-radius: 50%;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            transition: transform 0.2s;
        }

        .share-fab-options a:hover,
        .share-fab-options button:hover { transform: scale(1.1); }

        .share-fab-trigger {
            width: 42px; height: 42px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--fg);
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .share-fab-trigger:hover { border-color: var(--fg); }

        /* ══════════ RESPONSIVE ══════════ */

        /* ── Tablet (≤960px) ── */
        @media (max-width: 960px) {
            .min-nav-links { display: none; }
            .min-hamburger { display: block; }
            .min-drawer { display: block; }

            .min-page-header-inner {
                padding: 44px 24px 36px;
            }

            .min-page-title { font-size: 1.9rem; }

            .min-page-content {
                padding: 40px 24px 64px;
            }

            .min-footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }
        }

        /* ── Mobile (≤600px) ── */
        @media (max-width: 600px) {
            .min-nav-inner { height: 52px; padding: 0 16px; }
            .min-logo-mark { width: 24px; height: 24px; font-size: 10px; }
            .min-logo-name { font-size: 0.85rem; }

            .min-page-header { padding-top: 52px; }

            .min-page-header-inner {
                padding: 32px 16px 28px;
            }

            .min-page-title {
                font-size: 1.5rem;
                letter-spacing: -0.5px;
            }

            .min-page-content {
                padding: 28px 16px 48px;
            }

            .min-page-body {
                font-size: 0.9rem;
                line-height: 1.75;
            }

            .min-page-body h1 { font-size: 1.45rem; margin-top: 28px; }
            .min-page-body h2 { font-size: 1.25rem; margin-top: 28px; }
            .min-page-body h3 { font-size: 1.1rem; margin-top: 24px; }
            .min-page-body h4 { font-size: 0.95rem; margin-top: 20px; }

            .min-page-body blockquote {
                padding: 10px 16px;
                margin: 20px 0;
            }

            .min-page-body th,
            .min-page-body td {
                padding: 8px 12px;
                font-size: 0.82rem;
            }

            .min-footer-inner { padding: 36px 16px 24px; }

            .min-footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
                margin-bottom: 28px;
            }

            .min-footer-bottom {
                flex-direction: column;
                gap: 6px;
                text-align: center;
            }

            .share-fab { bottom: 16px; left: 16px; }
            .share-fab-trigger { width: 38px; height: 38px; font-size: 13px; }
            .share-fab-options a,
            .share-fab-options button { width: 34px; height: 34px; font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="min-nav">
    <div class="min-nav-inner">
        <a href="{{ $homeUrl }}" class="min-logo">
            <div class="min-logo-mark">
                @if($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                @else
                    <i class="fas fa-compass"></i>
                @endif
            </div>
            <span class="min-logo-name">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="min-nav-links">
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}"
                       class="{{ isset($page) && $page->slug === $p->slug ? 'active' : '' }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="min-nav-cta">Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="min-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- MOBILE DRAWER -->
<div class="min-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="min-drawer-panel">
        <button class="min-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="min-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}"
                   @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false">{{ __('messages.contact') }}</a>
            @endif
        </div>
    </div>
</div>

<!-- PAGE HEADER -->
<section class="min-page-header">
    <div class="min-page-header-inner">
        <div class="min-page-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span style="color:var(--fg);">{{ $page->title }}</span>
        </div>
        <h1 class="min-page-title">{{ $page->title }}</h1>
    </div>
</section>

<!-- PAGE CONTENT -->
<section class="min-page-content">
    <div class="min-page-body">
        {!! $page->content !!}
    </div>
</section>

<!-- FOOTER -->
<footer class="min-footer">
    <div class="min-footer-inner">
        <div class="min-footer-grid">
            <div>
                <a href="{{ $homeUrl }}" class="min-logo" style="margin-bottom:4px;">
                    <div class="min-logo-mark">
                        @if($website->logo_url)
                            <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                        @else
                            <i class="fas fa-compass"></i>
                        @endif
                    </div>
                    <span class="min-logo-name">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
                </a>
                <p class="min-footer-brand-desc">{{ $settings->description ?? 'Powered by adaylink — platform website untuk driver dan agen wisata.' }}</p>
            </div>
            <div class="min-footer-col">
                <div class="min-footer-col-title">Halaman</div>
                <ul>
                    @foreach($pages as $p)
                        <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="min-footer-col">
                <div class="min-footer-col-title">Kontak</div>
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
                        <li><a href="{{ $settings->social_instagram }}" target="_blank">Instagram</a></li>
                    @endif
                    @if($website->contact_whatsapp)
                        <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank">WhatsApp</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="min-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
            <span>Powered by <a href="#">adaylink</a></span>
        </div>
    </div>
</footer>

{{-- Floating WhatsApp --}}
@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
       target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:24px;right:24px;z-index:99;background:#25D366;color:white;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 12px rgba(37,211,102,0.25);text-decoration:none;font-size:20px;"
       x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(16px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},800)">
        <i class="fab fa-whatsapp" style="font-size:20px;"></i>
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
            <button @click="copyLink()" style="background:var(--fg);" :style="copied ? 'background:#059669' : ''">
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
