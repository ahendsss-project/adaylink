{{-- Template: Modern Travel — Page View --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
    $primaryColor = $settings->primary_color ?? '#FF6B35';
    $secondaryColor = $settings->secondary_color ?? '#0F172A';
    $fontHeading = $settings->font_heading ?? 'Poppins';
    $fontBody = $settings->font_body ?? 'Plus Jakarta Sans';
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
            --bg: #F8FAFC;
            --fg: #0F172A;
            --muted: #64748B;
            --accent: {{ $primaryColor }};
            --accent-dark: #E55A2B;
            --accent-soft: #FFF4EE;
            --navy: #0F172A;
            --card: #FFFFFF;
            --border: #E2E8F0;
            --surface: #F1F5F9;
            --font-heading: '{{ $fontHeading }}', sans-serif;
            --font-body: '{{ $fontBody }}', sans-serif;
            --radius: 14px;
            --shadow: 0 4px 24px rgba(15,23,42,0.06);
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
        .mod-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
        }

        .mod-nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mod-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .mod-logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .mod-logo-icon img { width: 100%; height: 100%; object-fit: cover; }

        .mod-logo-text {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--fg);
        }

        .mod-nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }

        .mod-nav-links a {
            padding: 8px 16px;
            border-radius: 10px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .mod-nav-links a:hover { color: var(--fg); background: var(--surface); }
        .mod-nav-links a.active { color: var(--accent); background: var(--accent-soft); }

        .mod-nav-cta {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark)) !important;
            color: white !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 16px rgba(255,107,53,0.25) !important;
        }

        .mod-hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--fg);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 10px;
        }

        /* ── MOBILE DRAWER ── */
        .mod-drawer {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 200;
            background: rgba(15,23,42,0.3);
        }

        .mod-drawer-panel {
            position: absolute;
            top: 0; right: 0;
            width: min(320px, 85vw);
            height: 100%;
            background: var(--card);
            padding: 24px;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mod-drawer.open .mod-drawer-panel { transform: translateX(0); }

        .mod-drawer-close {
            background: none; border: none; color: var(--muted);
            font-size: 1.3rem; cursor: pointer; padding: 8px; float: right;
        }

        .mod-drawer-links { clear: both; padding-top: 24px; }
        .mod-drawer-links a {
            display: block; padding: 14px 0; color: var(--fg);
            text-decoration: none; font-size: 1rem; font-weight: 500;
            border-bottom: 1px solid var(--border);
        }
        .mod-drawer-links a:last-child { border-bottom: none; }

        /* ── PAGE HEADER ── */
        .mod-page-header {
            padding-top: 68px;
            background: linear-gradient(135deg, var(--navy) 0%, #1E3A5F 50%, #0C4A6E 100%);
            position: relative;
            overflow: hidden;
        }

        .mod-page-header::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at 70% 30%, rgba(255,107,53,0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .mod-page-header-inner {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 72px 24px 64px;
            text-align: center;
            animation: modIn 0.7s ease both;
        }

        @keyframes modIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mod-page-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
        }

        .mod-page-breadcrumb a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: color 0.2s;
        }

        .mod-page-breadcrumb a:hover { color: var(--accent); }
        .mod-page-breadcrumb .sep { font-size: 0.6rem; }

        .mod-page-title {
            font-family: var(--font-heading);
            font-size: 2.6rem;
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
            line-height: 1.15;
        }

        /* ── PAGE CONTENT ── */
        .mod-page-content {
            max-width: 760px;
            margin: 0 auto;
            padding: 48px 24px 80px;
            animation: modIn 0.7s ease 0.1s both;
        }

        .mod-page-body {
            font-size: 0.95rem;
            line-height: 1.85;
            color: var(--fg);
        }

        .mod-page-body h1, .mod-page-body h2, .mod-page-body h3, .mod-page-body h4 {
            font-family: var(--font-heading);
            font-weight: 700;
            color: var(--fg);
            margin-top: 40px;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .mod-page-body h1 { font-size: 1.8rem; }
        .mod-page-body h2 { font-size: 1.5rem; }
        .mod-page-body h3 { font-size: 1.25rem; }
        .mod-page-body h4 { font-size: 1.05rem; }

        .mod-page-body p { margin-bottom: 20px; }

        .mod-page-body ul, .mod-page-body ol {
            margin-bottom: 20px;
            padding-left: 24px;
        }

        .mod-page-body li { margin-bottom: 8px; line-height: 1.7; }

        .mod-page-body a {
            color: var(--accent);
            text-decoration: underline;
            text-underline-offset: 3px;
            transition: color 0.2s;
        }

        .mod-page-body a:hover { color: var(--accent-dark); }

        .mod-page-body img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 28px 0;
            display: block;
        }

        .mod-page-body blockquote {
            border-left: 3px solid var(--accent);
            padding: 14px 20px;
            margin: 28px 0;
            background: var(--accent-soft);
            border-radius: 0 12px 12px 0;
            font-style: italic;
            color: var(--muted);
        }

        .mod-page-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 28px 0;
        }

        .mod-page-body th, .mod-page-body td {
            padding: 10px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 0.88rem;
        }

        .mod-page-body th {
            font-family: var(--font-heading);
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--muted);
        }

        .mod-page-body hr {
            border: none;
            height: 1px;
            background: var(--border);
            margin: 36px 0;
        }

        .mod-page-body iframe {
            max-width: 100%;
            border-radius: 12px;
            margin: 28px 0;
        }

        /* ── FOOTER ── */
        .mod-footer { background: var(--navy); color: rgba(255,255,255,0.5); }
        .mod-footer-inner { max-width: 1200px; margin: 0 auto; }

        .mod-footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 48px;
            padding: 56px 24px 32px;
        }

        .mod-footer-brand .mod-logo-text { color: white; }
        .mod-footer-desc { font-size: 0.85rem; line-height: 1.7; margin-top: 14px; max-width: 320px; }

        .mod-footer-col h5 {
            color: white; font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 16px;
        }

        .mod-footer-col ul { list-style: none; }
        .mod-footer-col ul li { margin-bottom: 10px; }
        .mod-footer-col ul a {
            color: rgba(255,255,255,0.4); text-decoration: none;
            font-size: 0.85rem; transition: color 0.2s;
        }
        .mod-footer-col ul a:hover { color: var(--accent); }

        .mod-footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin-bottom: 20px; }

        .mod-footer-bottom {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.75rem; padding: 0 24px 24px;
        }

        .mod-footer-bottom a { color: var(--accent); text-decoration: none; }

        /* ── SOCIAL SHARE FAB ── */
        .share-fab { position: fixed; bottom: 24px; left: 24px; z-index: 90; }
        .share-fab-options { display: flex; flex-direction: column; gap: 8px; margin-bottom: 8px; }
        .share-fab-options a, .share-fab-options button {
            width: 40px; height: 40px; border-radius: 12px; border: none;
            color: white; display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 14px; transition: transform 0.2s;
        }
        .share-fab-options a:hover, .share-fab-options button:hover { transform: scale(1.1); }
        .share-fab-trigger {
            width: 46px; height: 46px; border-radius: 14px; border: none;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white; font-size: 16px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 20px rgba(255,107,53,0.3); transition: all 0.25s;
        }
        .share-fab-trigger:hover { transform: scale(1.05); }

        /* ══════════ RESPONSIVE ══════════ */

        @media (max-width: 960px) {
            .mod-nav-links { display: none; }
            .mod-hamburger { display: block; }
            .mod-drawer { display: block; }

            .mod-page-header-inner { padding: 56px 24px 48px; }
            .mod-page-title { font-size: 2rem; }
            .mod-page-content { padding: 40px 24px 64px; }
            .mod-footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
            .mod-footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .mod-nav-inner { height: 56px; }
            .mod-logo-icon { width: 32px; height: 32px; font-size: 13px; }
            .mod-logo-text { font-size: 0.95rem; }
            .mod-page-header { padding-top: 56px; }
            .mod-page-header-inner { padding: 36px 16px 36px; }
            .mod-page-title { font-size: 1.6rem; letter-spacing: -0.5px; }
            .mod-page-content { padding: 28px 16px 48px; }
            .mod-page-body { font-size: 0.9rem; line-height: 1.75; }
            .mod-page-body h1 { font-size: 1.45rem; margin-top: 28px; }
            .mod-page-body h2 { font-size: 1.25rem; margin-top: 28px; }
            .mod-page-body h3 { font-size: 1.1rem; margin-top: 24px; }
            .mod-page-body blockquote { padding: 10px 16px; margin: 20px 0; }
            .mod-page-body th, .mod-page-body td { padding: 8px 12px; font-size: 0.82rem; }
            .mod-footer-grid { grid-template-columns: 1fr; gap: 24px; padding: 40px 16px 24px; }
            .share-fab { bottom: 16px; left: 16px; }
            .share-fab-trigger { width: 40px; height: 40px; font-size: 14px; }
            .share-fab-options a, .share-fab-options button { width: 36px; height: 36px; font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="mod-nav">
    <div class="mod-nav-inner">
        <a href="{{ $homeUrl }}" class="mod-logo">
            <div class="mod-logo-icon">
                @if($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                @else
                    <i class="fas fa-paper-plane"></i>
                @endif
            </div>
            <span class="mod-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="mod-nav-links">
            <li><a href="{{ $homeUrl }}">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}"
                       class="{{ isset($page) && $page->slug === $p->slug ? 'active' : '' }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="mod-nav-cta"><i class="fab fa-whatsapp" style="margin-right:4px;"></i> Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="mod-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- MOBILE DRAWER -->
<div class="mod-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="mod-drawer-panel">
        <button class="mod-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="mod-drawer-links">
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
<section class="mod-page-header">
    <div class="mod-page-header-inner">
        <div class="mod-page-breadcrumb">
            <a href="{{ $homeUrl }}">{{ __('messages.home') }}</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span style="color:var(--accent);">{{ $page->title }}</span>
        </div>
        <h1 class="mod-page-title">{{ $page->title }}</h1>
    </div>
</section>

<!-- PAGE CONTENT -->
<section class="mod-page-content">
    <div class="mod-page-body">
        {!! $page->content !!}
    </div>
</section>

<!-- FOOTER -->
<footer class="mod-footer">
    <div class="mod-footer-inner">
        <div class="mod-footer-grid">
            <div class="mod-footer-brand">
                <a href="{{ $homeUrl }}" class="mod-logo">
                    <div class="mod-logo-icon">
                        @if($website->logo_url)
                            <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                        @else
                            <i class="fas fa-paper-plane"></i>
                        @endif
                    </div>
                    <span class="mod-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
                </a>
                <p class="mod-footer-desc">{{ $settings->description ?? 'Powered by adaylink — platform website untuk driver dan agen wisata.' }}</p>
            </div>
            <div class="mod-footer-col">
                <h5>Halaman</h5>
                <ul>
                    @foreach($pages as $p)
                        <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="mod-footer-col">
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
                        <li><a href="{{ $settings->social_instagram }}" target="_blank">Instagram</a></li>
                    @endif
                    @if($website->contact_whatsapp)
                        <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank">WhatsApp</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <hr class="mod-footer-divider">
        <div class="mod-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
            <span>Powered by <a href="#">adaylink</a></span>
        </div>
    </div>
</footer>

{{-- Floating WhatsApp --}}
@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
       target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:24px;right:24px;z-index:99;background:#25D366;color:white;width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(37,211,102,0.3);text-decoration:none;font-size:22px;"
       x-data x-init="$el.style.opacity='0';$el.style.transform='translateY(20px)';setTimeout(()=>{$el.style.transition='all 0.5s';$el.style.opacity='1';$el.style.transform='translateY(0)';},1000)">
        <i class="fab fa-whatsapp" style="font-size:22px;"></i>
    </a>
@endif

@if($features['social_share'] ?? false)
    <div class="share-fab" x-data="socialShare()" x-init="init()">
        <div class="share-fab-options" x-show="isOpen"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            <a :href="'https://wa.me/?text=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#25D366;"><i class="fab fa-whatsapp"></i></a>
            <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#1877F2;"><i class="fab fa-facebook-f"></i></a>
            <a :href="'https://twitter.com/intent/tweet?url=' + encodeURIComponent(pageUrl)" target="_blank" style="background:#000;"><i class="fab fa-x-twitter"></i></a>
            <button @click="copyLink()" style="background:var(--navy);" :style="copied ? 'background:#059669' : ''">
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
