{{-- Template: Card + Conversion — Sales-focused with full-bleed hero + floating stats --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
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
            --bg: #F9FAFB;
            --fg: #1E1B4B;
            --muted: #6B7280;
            --accent: {{ $primaryColor }};
            --accent-dark: #3730A3;
            --accent-soft: #EEF2FF;
            --green: #059669;
            --green-soft: #ECFDF5;
            --card: #FFFFFF;
            --border: #E5E7EB;
            --surface: #F3F4F6;
            --font-heading: '{{ $fontHeading }}', sans-serif;
            --font-body: '{{ $fontBody }}', sans-serif;
            --radius: 16px;
            --shadow: 0 2px 16px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.1);
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
        .crd-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(229,231,235,0.6);
            transition: background 0.3s;
        }

        .crd-nav.scrolled {
            background: rgba(255,255,255,0.97);
        }

        .crd-nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .crd-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .crd-logo-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .crd-logo-icon img { width: 100%; height: 100%; object-fit: cover; }

        .crd-logo-text {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--fg);
        }

        .crd-nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
        }

        .crd-nav-links a {
            padding: 8px 14px;
            border-radius: 10px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .crd-nav-links a:hover { color: var(--fg); background: var(--surface); }
        .crd-nav-links a.active { color: var(--accent); background: var(--accent-soft); }

        .crd-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 22px;
            background: var(--accent);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-family: var(--font-heading);
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .crd-btn:hover { background: var(--accent-dark); }

        .crd-btn-green { background: var(--green); }
        .crd-btn-green:hover { background: #047857; }

        .crd-btn-outline {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1.5px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(4px);
        }

        .crd-btn-outline:hover { background: rgba(255,255,255,0.25); border-color: rgba(255,255,255,0.5); }

        .crd-hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--fg);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
        }

        /* ── MOBILE DRAWER ── */
        .crd-drawer {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 200;
            background: rgba(0,0,0,0.3);
        }

        .crd-drawer-panel {
            position: absolute;
            top: 0; right: 0;
            width: min(320px, 85vw);
            height: 100%;
            background: var(--card);
            padding: 24px;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .crd-drawer.open .crd-drawer-panel { transform: translateX(0); }

        .crd-drawer-close {
            background: none; border: none; color: var(--muted);
            font-size: 1.3rem; cursor: pointer; padding: 8px; float: right;
        }

        .crd-drawer-links { clear: both; padding-top: 24px; }
        .crd-drawer-links a {
            display: block; padding: 14px 0; color: var(--fg);
            text-decoration: none; font-size: 1rem; font-weight: 500;
            border-bottom: 1px solid var(--border);
        }
        .crd-drawer-links a:last-child { border-bottom: none; }

        /* ── HERO — Full-Bleed Background ── */
        .crd-hero {
            position: relative;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark, #3730A3) 50%, #1E1B4B 100%);
        }

        .crd-hero-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-size: cover;
            background-position: center;
            opacity: 0.3;
        }

        .crd-hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(30,27,75,0.4) 0%, rgba(30,27,75,0.75) 100%);
        }

        .crd-hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 120px 24px 80px;
            max-width: 720px;
            animation: crdHeroIn 0.8s ease both;
        }

        @keyframes crdHeroIn {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .crd-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 100px;
            color: rgba(255,255,255,0.9);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            backdrop-filter: blur(4px);
        }

        .crd-hero-badge i { color: #FCD34D; }

        .crd-hero h1 {
            font-family: var(--font-heading);
            font-size: 3rem;
            font-weight: 800;
            color: white;
            line-height: 1.1;
            letter-spacing: -1.5px;
            margin-bottom: 16px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.2);
        }

        .crd-hero-desc {
            font-size: 1.05rem;
            line-height: 1.7;
            color: rgba(255,255,255,0.8);
            margin-bottom: 32px;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        .crd-hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        /* ── Floating Stat Cards ── */
        .crd-stats-bar {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: -40px;
            padding: 0 24px;
            animation: crdStatsIn 0.8s ease 0.3s both;
        }

        @keyframes crdStatsIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .crd-stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px 28px;
            box-shadow: var(--shadow-lg);
            text-align: center;
            min-width: 160px;
            flex: 1;
            max-width: 220px;
        }

        .crd-stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 1rem;
        }

        .crd-stat-icon.blue { background: var(--accent-soft); color: var(--accent); }
        .crd-stat-icon.green { background: var(--green-soft); color: var(--green); }
        .crd-stat-icon.amber { background: #FEF3C7; color: #D97706; }

        .crd-stat-value {
            font-family: var(--font-heading);
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--fg);
            letter-spacing: -0.5px;
        }

        .crd-stat-label {
            font-size: 0.75rem;
            color: var(--muted);
            font-weight: 500;
            margin-top: 2px;
        }

        /* ── SECTION COMMON ── */
        .crd-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 72px 24px;
        }

        .crd-section-head {
            text-align: center;
            margin-bottom: 44px;
        }

        .crd-section-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 8px;
        }

        .crd-section-title {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--fg);
            letter-spacing: -0.5px;
        }

        .crd-section-subtitle {
            font-size: 0.9rem;
            color: var(--muted);
            margin-top: 8px;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ── TOUR CARDS — Horizontal Scroll ── */
        .crd-tours-scroll {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 12px;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }

        .crd-tours-scroll::-webkit-scrollbar { height: 6px; }
        .crd-tours-scroll::-webkit-scrollbar-track { background: transparent; }
        .crd-tours-scroll::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        .crd-tour-card {
            flex: 0 0 320px;
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            scroll-snap-align: start;
            position: relative;
        }

        .crd-tour-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

        .crd-tour-card-img-wrap { position: relative; overflow: hidden; }

        .crd-tour-card-img {
            width: 100%;
            aspect-ratio: 16/10;
            object-fit: cover;
            display: block;
            transition: transform 0.5s;
        }

        .crd-tour-card:hover .crd-tour-card-img { transform: scale(1.05); }

        .crd-tour-card-img-placeholder {
            width: 100%;
            aspect-ratio: 16/10;
            background: var(--accent-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1.5rem;
        }

        .crd-tour-card-badge {
            position: absolute;
            top: 12px; left: 12px;
            padding: 4px 12px;
            background: var(--green);
            color: white;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .crd-tour-card-body { padding: 20px; }

        .crd-tour-card-title {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .crd-tour-card-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            font-size: 0.78rem;
            color: var(--muted);
        }

        .crd-tour-card-meta i { color: var(--accent); font-size: 0.7rem; }

        .crd-tour-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        .crd-tour-price {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--accent);
        }

        .crd-tour-price span {
            font-weight: 400;
            font-size: 0.72rem;
            color: var(--muted);
        }

        .crd-tour-card-cta {
            padding: 8px 18px;
            background: var(--accent);
            color: white;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 600;
            transition: background 0.2s;
        }

        .crd-tour-card:hover .crd-tour-card-cta { background: var(--accent-dark); }

        /* ── GALLERY ── */
        .crd-gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .crd-gallery-item {
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 12px;
            cursor: pointer;
        }

        .crd-gallery-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .crd-gallery-item:hover img { transform: scale(1.08); }

        /* ── VEHICLES — Horizontal Cards ── */
        .crd-vehicles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .crd-vehicle-card {
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            padding: 20px;
            gap: 16px;
            transition: all 0.3s;
        }

        .crd-vehicle-card:hover { box-shadow: var(--shadow-lg); }

        .crd-vehicle-thumb {
            width: 72px; height: 72px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--accent-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1.2rem;
        }

        .crd-vehicle-thumb img { width: 100%; height: 100%; object-fit: cover; }

        .crd-vehicle-info { flex: 1; min-width: 0; }

        .crd-vehicle-name {
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 6px;
        }

        .crd-vehicle-specs {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.78rem;
            color: var(--muted);
        }

        .crd-vehicle-specs span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .crd-vehicle-specs i { color: var(--accent); font-size: 0.7rem; }

        /* ── REVIEWS ── */
        .crd-reviews-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .crd-review-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            position: relative;
        }

        .crd-review-card::before {
            content: '\201C';
            position: absolute;
            top: 12px; right: 20px;
            font-size: 3rem;
            font-family: Georgia, serif;
            color: var(--accent-soft);
            line-height: 1;
        }

        .crd-review-stars {
            display: flex;
            gap: 2px;
            margin-bottom: 12px;
            color: #F59E0B;
            font-size: 0.8rem;
        }

        .crd-review-text {
            font-size: 0.9rem;
            line-height: 1.7;
            color: var(--fg);
            margin-bottom: 16px;
        }

        .crd-review-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .crd-review-avatar {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .crd-review-name { font-size: 0.85rem; font-weight: 600; color: var(--fg); }
        .crd-review-date { font-size: 0.72rem; color: var(--muted); }

        /* ── REVIEW FORM ── */
        .crd-review-form {
            max-width: 520px;
            margin: 36px auto 0;
            background: var(--card);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: var(--shadow);
        }

        .crd-review-form h3 {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .crd-form-field { margin-bottom: 14px; }
        .crd-form-field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .crd-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.88rem;
            font-family: var(--font-body);
            outline: none;
            transition: border-color 0.2s;
        }

        .crd-input:focus { border-color: var(--accent); }

        /* ── CTA BANNER ── */
        .crd-cta-banner {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark, #3730A3) 100%);
            padding: 64px 24px;
            text-align: center;
        }

        .crd-cta-banner::before {
            content: '';
            position: absolute;
            top: -50%; right: -20%;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .crd-cta-banner::after {
            content: '';
            position: absolute;
            bottom: -30%; left: -10%;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }

        .crd-cta-banner > * { position: relative; z-index: 1; }

        .crd-cta-banner h3 {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 700;
            color: white;
            margin-bottom: 12px;
        }

        .crd-cta-banner p {
            font-size: 0.92rem;
            color: rgba(255,255,255,0.75);
            margin-bottom: 28px;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        .crd-cta-banner .crd-btn {
            background: white;
            color: var(--accent);
            font-size: 0.92rem;
            padding: 14px 32px;
        }

        .crd-cta-banner .crd-btn:hover { background: rgba(255,255,255,0.9); }

        /* ── FOOTER ── */
        .crd-footer {
            background: var(--fg);
            color: rgba(255,255,255,0.5);
        }

        .crd-footer-inner { max-width: 1200px; margin: 0 auto; }

        .crd-footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 48px;
            padding: 48px 24px 28px;
        }

        .crd-footer-brand .crd-logo-text { color: white; }
        .crd-footer-desc { font-size: 0.82rem; line-height: 1.7; margin-top: 12px; max-width: 300px; }

        .crd-footer-col h5 {
            color: white; font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 14px;
        }

        .crd-footer-col ul { list-style: none; }
        .crd-footer-col ul li { margin-bottom: 10px; }
        .crd-footer-col ul a {
            color: rgba(255,255,255,0.4); text-decoration: none;
            font-size: 0.82rem; transition: color 0.2s;
        }
        .crd-footer-col ul a:hover { color: white; }

        .crd-footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin-bottom: 18px; }

        .crd-footer-bottom {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.75rem; padding: 0 24px 24px;
        }

        .crd-footer-bottom a { color: white; text-decoration: none; }

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
            background: var(--accent); color: white; font-size: 16px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(79,70,229,0.3); transition: all 0.2s;
        }
        .share-fab-trigger:hover { background: var(--accent-dark); }

        /* ══════════ RESPONSIVE ══════════ */

        @media (max-width: 960px) {
            .crd-nav-links { display: none; }
            .crd-hamburger { display: block; }
            .crd-drawer { display: block; }

            .crd-hero { min-height: 520px; }
            .crd-hero h1 { font-size: 2.4rem; }
            .crd-hero-content { padding: 100px 24px 72px; }

            .crd-stats-bar { gap: 12px; }
            .crd-stat-card { padding: 16px 20px; min-width: 140px; }

            .crd-section { padding: 56px 24px; }
            .crd-tour-card { flex: 0 0 280px; }
            .crd-gallery-grid { grid-template-columns: repeat(3, 1fr); }
            .crd-vehicles-grid { grid-template-columns: repeat(2, 1fr); }
            .crd-footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
            .crd-footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .crd-nav-inner { height: 56px; }
            .crd-logo-icon { width: 32px; height: 32px; font-size: 12px; }
            .crd-logo-text { font-size: 0.92rem; }

            .crd-hero { min-height: 480px; }
            .crd-hero-content { padding: 88px 16px 64px; }
            .crd-hero h1 { font-size: 1.9rem; letter-spacing: -0.5px; }
            .crd-hero-desc { font-size: 0.92rem; }
            .crd-hero-actions { flex-direction: column; align-items: center; }
            .crd-btn { padding: 10px 20px; font-size: 0.82rem; }

            .crd-stats-bar {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
                margin-top: -28px;
            }
            .crd-stat-card { min-width: 100px; max-width: 160px; padding: 14px 16px; }
            .crd-stat-value { font-size: 1.2rem; }
            .crd-stat-label { font-size: 0.68rem; }

            .crd-section { padding: 44px 16px; }
            .crd-section-title { font-size: 1.4rem; }
            .crd-tour-card { flex: 0 0 260px; }
            .crd-tour-card-body { padding: 16px; }

            .crd-gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            .crd-vehicles-grid { grid-template-columns: 1fr; gap: 12px; }
            .crd-vehicle-card { padding: 16px; }

            .crd-reviews-grid { grid-template-columns: 1fr; gap: 12px; }
            .crd-review-card { padding: 18px; }
            .crd-review-form { padding: 20px; }

            .crd-cta-banner { padding: 44px 16px; }
            .crd-cta-banner h3 { font-size: 1.25rem; }

            .crd-footer-grid { grid-template-columns: 1fr; gap: 24px; padding: 36px 16px 20px; }
            .share-fab { bottom: 16px; left: 16px; }
            .share-fab-trigger { width: 40px; height: 40px; font-size: 14px; }
            .share-fab-options a, .share-fab-options button { width: 36px; height: 36px; font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="crd-nav" x-data x-init="window.addEventListener('scroll', () => { $el.classList.toggle('scrolled', window.scrollY > 40); })">
    <div class="crd-nav-inner">
        <a href="{{ $homeUrl }}" class="crd-logo">
            <div class="crd-logo-icon">
                @if($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                @else
                    <i class="fas fa-suitcase-rolling"></i>
                @endif
            </div>
            <span class="crd-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
        </a>
        <ul class="crd-nav-links">
            <li><a href="{{ $homeUrl }}" class="active">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
            @endforeach
            @if($website->contact_whatsapp)
                <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="crd-btn" style="padding:8px 18px;font-size:0.82rem;"><i class="fab fa-whatsapp"></i> Hubungi</a></li>
            @endif
        </ul>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
        <button class="crd-hamburger" x-data="{ drawerOpen: false }" @click="drawerOpen = true">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- MOBILE DRAWER -->
<div class="crd-drawer" x-data="{ drawerOpen: false }" x-init="$watch('drawerOpen', v => { $el.style.display = v ? 'block' : 'none'; document.body.style.overflow = v ? 'hidden' : ''; })" :class="{ 'open': drawerOpen }" @click.self="drawerOpen = false">
    <div class="crd-drawer-panel">
        <button class="crd-drawer-close" @click="drawerOpen = false"><i class="fas fa-times"></i></button>
        <div class="crd-drawer-links">
            <a href="{{ $homeUrl }}" @click="drawerOpen = false">{{ __('messages.home') }}</a>
            @foreach($pages as $p)
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}"
                   @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false"><i class="fab fa-whatsapp" style="margin-right:6px;"></i>{{ __('messages.contact') }}</a>
            @endif
        </div>
    </div>
</div>

<!-- HERO — Full-Bleed Background -->
<section class="crd-hero">
    @if($settings->hero_image_url)
        <div class="crd-hero-bg" style="background-image:url('{{ $settings->hero_image_url }}');"></div>
    @elseif($galleryImages->count() > 0)
        <div class="crd-hero-bg" style="background-image:url('{{ $galleryImages->first()['url'] }}');"></div>
    @endif
    <div class="crd-hero-overlay"></div>
    <div class="crd-hero-content">
        @if($reviews->count() > 0)
            <div class="crd-hero-badge">
                <i class="fas fa-star"></i> {{ number_format($reviews->avg('rating'), 1) }} Rating · Terpercaya
            </div>
        @endif
        <h1>{{ $settings->hero_title ?? 'Jelajahi Destinasi Impian Anda' }}</h1>
        <p class="crd-hero-desc">{{ $settings->hero_subtitle ?? $settings->description ?? 'Pesan tour sekarang dan nikmati pengalaman perjalanan tak terlupakan dengan pemandu berpengalaman.' }}</p>
        <div class="crd-hero-actions">
            @if($tourPackages->count() > 0)
                <a href="#tours" class="crd-btn crd-btn-green"><i class="fas fa-search"></i> Lihat Paket Tour</a>
            @endif
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="crd-btn crd-btn-outline"><i class="fab fa-whatsapp"></i> Tanya Sekarang</a>
            @endif
        </div>
    </div>
</section>

<!-- FLOATING STATS -->
<div class="crd-stats-bar">
    @if($reviews->count() > 0)
        <div class="crd-stat-card">
            <div class="crd-stat-icon amber"><i class="fas fa-star"></i></div>
            <div class="crd-stat-value">{{ number_format($reviews->avg('rating'), 1) }}</div>
            <div class="crd-stat-label">Rating</div>
        </div>
    @endif
    @if($tourPackages->count() > 0)
        <div class="crd-stat-card">
            <div class="crd-stat-icon blue"><i class="fas fa-route"></i></div>
            <div class="crd-stat-value">{{ $tourPackages->count() }}+</div>
            <div class="crd-stat-label">Tour Tersedia</div>
        </div>
    @endif
    <div class="crd-stat-card">
        <div class="crd-stat-icon green"><i class="fas fa-shield-alt"></i></div>
        <div class="crd-stat-value">100%</div>
        <div class="crd-stat-label">Terpercaya</div>
    </div>
</div>

<!-- TOUR PACKAGES — Horizontal Scroll -->
@if($tourPackages->count() > 0)
<section class="crd-section" id="tours">
    <div class="crd-section-head">
        <div class="crd-section-label">{{ __('messages.tours') }}</div>
        <h2 class="crd-section-title">{{ __('messages.tours') }}</h2>
        <p class="crd-section-subtitle">Temukan paket tour terbaik dengan harga terjangkau dan layanan profesional</p>
    </div>
    <div class="crd-tours-scroll">
        @foreach($tourPackages as $tour)
            <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $tour->slug : '/tour/' . $tour->slug }}" class="crd-tour-card">
                <div class="crd-tour-card-img-wrap">
                    @if($tour->thumbnail_url)
                        <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}" class="crd-tour-card-img"/>
                    @elseif($tour->images->count() > 0)
                        <img src="{{ $tour->images->first()->url }}" alt="{{ $tour->title }}" class="crd-tour-card-img"/>
                    @else
                        <div class="crd-tour-card-img-placeholder"><i class="fas fa-mountain"></i></div>
                    @endif
                    @if($tour->is_featured)
                        <div class="crd-tour-card-badge">Best Seller</div>
                    @endif
                </div>
                <div class="crd-tour-card-body">
                    <h3 class="crd-tour-card-title">{{ $tour->title }}</h3>
                    <div class="crd-tour-card-meta">
                        @if($tour->duration_text ?? $tour->duration)
                            <span><i class="far fa-clock"></i> {{ $tour->duration_text ?? $tour->duration }}</span>
                        @endif
                        @if($tour->location)
                            <span><i class="fas fa-map-marker-alt"></i> {{ $tour->location }}</span>
                        @endif
                    </div>
                    <div class="crd-tour-card-footer">
                        @if($tour->price_start_from)
                            <div class="crd-tour-price"><span>Mulai </span>Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
                        @else
                            <div></div>
                        @endif
                        <span class="crd-tour-card-cta">Pesan</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- GALLERY -->
@if($galleryImages->count() > 0)
<section style="background:var(--card);">
    <div class="crd-section">
        <div class="crd-section-head">
            <div class="crd-section-label">{{ __('messages.gallery') }}</div>
            <h2 class="crd-section-title">{{ __('messages.gallery_description') }}</h2>
        </div>
        <div class="crd-gallery-grid"
             x-data="galleryLightbox(@js($galleryImages->values()->all()))" x-init="init()">
            @foreach($galleryImages as $i => $img)
                <div class="crd-gallery-item" @click="open({{ $i }})">
                    <img src="{{ $img['url'] }}" alt="{{ $img['alt'] ?? 'Gallery' }}" loading="lazy"/>
                </div>
            @endforeach
            @if($features['gallery_lightbox'] ?? false)
            <template x-if="isOpen">
                <div style="position:fixed;top:0;left:0;right:0;bottom:0;z-index:300;background:rgba(0,0,0,0.92);display:flex;align-items:center;justify-content:center;"
                     @click.self="close()" x-transition>
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
<section class="crd-section">
    <div class="crd-section-head">
        <div class="crd-section-label">{{ __('messages.vehicles') }}</div>
        <h2 class="crd-section-title">{{ __('messages.our_fleet') }}</h2>
    </div>
    <div class="crd-vehicles-grid">
        @foreach($vehicles as $vehicle)
            <div class="crd-vehicle-card">
                <div class="crd-vehicle-thumb">
                    @if($vehicle->image_url)
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model_name }}"/>
                    @elseif($vehicle->images->count() > 0)
                        <img src="{{ $vehicle->images->first()->url }}" alt="{{ $vehicle->model_name }}"/>
                    @else
                        <i class="fas fa-car"></i>
                    @endif
                </div>
                <div class="crd-vehicle-info">
                    <div class="crd-vehicle-name">{{ $vehicle->model_name }}</div>
                    <div class="crd-vehicle-specs">
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
</section>
@endif

<!-- REVIEWS -->
@if($features['reviews'] ?? false)
<section style="background:var(--card);">
    <div class="crd-section" id="reviews">
        <div class="crd-section-head">
            <div class="crd-section-label">Testimoni</div>
            <h2 class="crd-section-title">Apa Kata Mereka</h2>
        </div>

        @if(session('review_success'))
            <div style="background:var(--green-soft);border:1px solid #A7F3D0;border-radius:12px;padding:12px 20px;margin-bottom:24px;text-align:center;font-size:0.85rem;color:var(--green);max-width:600px;margin-left:auto;margin-right:auto;">
                <i class="fas fa-check-circle"></i> {{ session('review_success') }}
            </div>
        @endif

        @if($reviews->count() > 0)
        <div class="crd-reviews-grid">
            @foreach($reviews as $review)
                <div class="crd-review-card">
                    <div class="crd-review-stars">
                        @for($s = 0; $s < 5; $s++)
                            <i class="{{ $s < $review->rating ? 'fas' : 'far' }} fa-star" style="{{ $s >= $review->rating ? 'color:#D1D5DB;' : '' }}"></i>
                        @endfor
                    </div>
                    <p class="crd-review-text">"{{ $review->comment }}"</p>
                    <div class="crd-review-author">
                        <div class="crd-review-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</div>
                        <div>
                            <div class="crd-review-name">{{ $review->reviewer_name }}</div>
                            <div class="crd-review-date">{{ $review->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:32px;color:var(--muted);font-size:0.9rem;">
            <i class="far fa-comment-dots" style="font-size:1.5rem;display:block;margin-bottom:8px;"></i>
            Belum ada review. Jadilah yang pertama!
        </div>
        @endif

        <div class="crd-review-form">
            <h3><i class="fas fa-pen" style="color:var(--accent);margin-right:6px;"></i> Tulis Review</h3>
            <form method="POST" action="{{ route('public.reviews.store', $website->subdomain) }}">
                @csrf
                @if($errors->any())
                    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:0.82rem;color:#DC2626;">
                        @foreach($errors->all() as $error) <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p> @endforeach
                    </div>
                @endif
                <div class="crd-form-field">
                    <label>Nama *</label>
                    <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}" required class="crd-input"/>
                </div>
                <div class="crd-form-field">
                    <label>Email</label>
                    <input type="email" name="reviewer_email" value="{{ old('reviewer_email') }}" class="crd-input"/>
                </div>
                <div class="crd-form-field" x-data="{ rating: 0 }">
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
                <div class="crd-form-field">
                    <label>Komentar *</label>
                    <textarea name="comment" rows="3" required class="crd-input" style="resize:vertical;">{{ old('comment') }}</textarea>
                </div>
                <button type="submit" class="crd-btn" style="width:100%;justify-content:center;padding:13px;"><i class="fas fa-paper-plane"></i> Kirim Review</button>
                <p style="font-size:0.72rem;color:var(--muted);margin-top:10px;text-align:center;">Review akan ditampilkan setelah disetujui.</p>
            </form>
        </div>
    </div>
</section>
@endif

<!-- CTA BANNER -->
@if($website->contact_whatsapp)
<section class="crd-cta-banner">
    <h3>Siap Memulai Petualangan?</h3>
    <p>Hubungi kami sekarang dan dapatkan penawaran terbaik untuk perjalanan Anda.</p>
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang paket tour Anda.') }}"
       target="_blank" class="crd-btn">
        <i class="fab fa-whatsapp"></i> Chat via WhatsApp
    </a>
</section>
@endif

<!-- FOOTER -->
<footer class="crd-footer">
    <div class="crd-footer-inner">
        <div class="crd-footer-grid">
            <div class="crd-footer-brand">
                <a href="{{ $homeUrl }}" class="crd-logo">
                    <div class="crd-logo-icon">
                        @if($website->logo_url)
                            <img src="{{ $website->logo_url }}" alt="{{ $settings->site_title ?? $website->site_name ?? 'Website' }}"/>
                        @else
                            <i class="fas fa-suitcase-rolling"></i>
                        @endif
                    </div>
                    <span class="crd-logo-text">{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
                </a>
                <p class="crd-footer-desc">{{ $settings->description ?? 'Powered by adaylink.' }}</p>
            </div>
            <div class="crd-footer-col">
                <h5>Halaman</h5>
                <ul>
                    @foreach($pages as $p)
                        <li><a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="crd-footer-col">
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
        <hr class="crd-footer-divider">
        <div class="crd-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</span>
            <span>Powered by <a href="#">adaylink</a></span>
        </div>
    </div>
</footer>

{{-- Floating WhatsApp --}}
@if(($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
       target="_blank" rel="noopener noreferrer"
       style="position:fixed;bottom:24px;right:24px;z-index:99;background:#25D366;color:white;width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(37,211,102,0.3);text-decoration:none;font-size:22px;"
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
            <button @click="copyLink()" style="background:var(--accent);" :style="copied ? 'background:#059669' : ''">
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
</script>
</body>
</html>
