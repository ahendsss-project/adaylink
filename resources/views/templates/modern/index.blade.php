{{-- Template: Modern Travel — Vibrant travel style with Poppins + Plus Jakarta Sans --}}
@php
    $homeUrl = isset($demoTemplate) ? '/app/demo/' . $demoTemplate : (isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/');
    $pageUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/page' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page' : '/page');
    $tourUrlBase = isset($demoTemplate) ? '/app/demo/' . $demoTemplate . '/tour' : (isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour' : '/tour');
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
            --bg: #F8FAFC;
            --fg: #0F172A;
            --muted: #64748B;
            --accent: {{ $primaryColor }};
            --accent-dark: #E55A2B;
            --accent-soft: #FFF4EE;
            --navy: #0F172A;
            --navy-light: #1E293B;
            --sky: #E0F2FE;
            --card: #FFFFFF;
            --border: #E2E8F0;
            --surface: #F1F5F9;
            --success: #059669;
            --font-heading: '{{ $fontHeading }}', sans-serif;
            --font-body: '{{ $fontBody }}', sans-serif;
            --radius: 14px;
            --shadow: 0 4px 24px rgba(15,23,42,0.06);
            --shadow-lg: 0 12px 40px rgba(15,23,42,0.1);
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
            -webkit-backdrop-filter: blur(20px);
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
            letter-spacing: -0.3px;
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

        .mod-nav-cta:hover { opacity: 0.9; }

        .mod-hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--fg);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 10px;
            transition: background 0.2s;
        }

        .mod-hamburger:hover { background: var(--surface); }

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
            background: none;
            border: none;
            color: var(--muted);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 8px;
            float: right;
        }

        .mod-drawer-links {
            clear: both;
            padding-top: 24px;
        }

        .mod-drawer-links a {
            display: block;
            padding: 14px 0;
            color: var(--fg);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            border-bottom: 1px solid var(--border);
        }

        .mod-drawer-links a:last-child { border-bottom: none; }

        /* ── HERO ── */
        .mod-hero {
            padding-top: 68px;
            position: relative;
            overflow: hidden;
        }

        .mod-hero-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, var(--navy) 0%, #1E3A5F 50%, #0C4A6E 100%);
            z-index: 0;
        }

        .mod-hero-bg::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at 70% 30%, rgba(255,107,53,0.15) 0%, transparent 60%);
        }

        .mod-hero-inner {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 24px 88px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 56px;
            align-items: center;
            animation: modIn 0.8s ease both;
        }

        @keyframes modIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mod-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(255,107,53,0.15);
            border: 1px solid rgba(255,107,53,0.25);
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 20px;
        }

        .mod-hero h1 {
            font-family: var(--font-heading);
            font-size: 3rem;
            font-weight: 800;
            color: white;
            line-height: 1.1;
            letter-spacing: -1px;
            margin-bottom: 18px;
        }

        .mod-hero h1 em {
            font-style: normal;
            background: linear-gradient(135deg, var(--accent), #FFB088);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .mod-hero-desc {
            font-size: 1rem;
            line-height: 1.7;
            color: rgba(255,255,255,0.6);
            margin-bottom: 32px;
            max-width: 440px;
        }

        .mod-hero-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .mod-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-family: var(--font-heading);
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(255,107,53,0.3);
            transition: all 0.25s;
            border: none;
            cursor: pointer;
        }

        .mod-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(255,107,53,0.35); }

        .mod-btn-ghost {
            background: rgba(255,255,255,0.1);
            box-shadow: none;
            border: 1px solid rgba(255,255,255,0.15);
        }

        .mod-btn-ghost:hover { background: rgba(255,255,255,0.15); box-shadow: none; }

        .mod-hero-image {
            position: relative;
        }

        .mod-hero-img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            border-radius: 16px;
            display: block;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .mod-hero-img-placeholder {
            width: 100%;
            aspect-ratio: 4/3;
            background: rgba(255,255,255,0.08);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.3);
            font-size: 2.5rem;
        }

        /* Floating stats badge */
        .mod-hero-stat {
            position: absolute;
            bottom: -16px; left: 24px;
            background: var(--card);
            border-radius: 12px;
            padding: 14px 20px;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mod-hero-stat-icon {
            width: 40px; height: 40px;
            background: var(--accent-soft);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1rem;
        }

        .mod-hero-stat-text {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .mod-hero-stat-text strong {
            display: block;
            font-size: 1rem;
            color: var(--fg);
        }

        /* ── SECTION COMMON ── */
        .mod-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 72px 24px;
        }

        .mod-section-alt {
            background: var(--card);
        }

        .mod-section-alt-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 72px 24px;
        }

        .mod-section-head {
            margin-bottom: 40px;
        }

        .mod-section-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 8px;
        }

        .mod-section-title {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--fg);
            letter-spacing: -0.5px;
        }

        /* ── TOUR CARDS ── */
        .mod-tours-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .mod-tour-card {
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            box-shadow: var(--shadow);
            transition: all 0.3s;
        }

        .mod-tour-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .mod-tour-card-img-wrap {
            position: relative;
            overflow: hidden;
        }

        .mod-tour-card-img {
            width: 100%;
            aspect-ratio: 16/10;
            object-fit: cover;
            display: block;
            transition: transform 0.5s;
        }

        .mod-tour-card:hover .mod-tour-card-img { transform: scale(1.05); }

        .mod-tour-card-img-placeholder {
            width: 100%;
            aspect-ratio: 16/10;
            background: linear-gradient(135deg, var(--sky), var(--surface));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 1.5rem;
        }

        .mod-tour-card-badge {
            position: absolute;
            top: 12px; left: 12px;
            padding: 4px 12px;
            background: var(--accent);
            color: white;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .mod-tour-card-body {
            padding: 20px;
        }

        .mod-tour-card-title {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .mod-tour-card-desc {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .mod-tour-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mod-tour-price {
            font-family: var(--font-heading);
            font-size: 1rem;
            font-weight: 700;
            color: var(--accent);
        }

        .mod-tour-price span {
            font-weight: 400;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .mod-tour-card-btn {
            width: 36px; height: 36px;
            background: var(--accent-soft);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 0.75rem;
            transition: all 0.2s;
        }

        .mod-tour-card:hover .mod-tour-card-btn {
            background: var(--accent);
            color: white;
        }

        /* ── GALLERY ── */
        .mod-gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .mod-gallery-item {
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 12px;
            cursor: pointer;
            position: relative;
        }

        .mod-gallery-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .mod-gallery-item:hover img { transform: scale(1.08); }

        .mod-gallery-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(15,23,42,0);
            transition: background 0.3s;
            border-radius: 12px;
        }

        .mod-gallery-item:hover::after {
            background: rgba(15,23,42,0.15);
        }

        /* ── VEHICLES ── */
        .mod-vehicles-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .mod-vehicle-card {
            display: flex;
            gap: 16px;
            padding: 18px;
            background: var(--bg);
            border-radius: var(--radius);
            text-decoration: none;
            color: inherit;
            transition: all 0.25s;
        }

        .mod-vehicle-card:hover { box-shadow: var(--shadow); transform: translateY(-2px); }

        .mod-vehicle-thumb {
            width: 88px; height: 88px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 1.3rem;
        }

        .mod-vehicle-thumb img { width: 100%; height: 100%; object-fit: cover; }

        .mod-vehicle-info { flex: 1; min-width: 0; }

        .mod-vehicle-name {
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--fg);
            margin-bottom: 4px;
        }

        .mod-vehicle-desc {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ── REVIEWS ── */
        .mod-reviews-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .mod-review-card {
            padding: 24px;
            background: var(--bg);
            border-radius: var(--radius);
        }

        .mod-review-stars {
            display: flex;
            gap: 2px;
            margin-bottom: 12px;
            color: #F59E0B;
            font-size: 0.8rem;
        }

        .mod-review-text {
            font-size: 0.9rem;
            line-height: 1.7;
            color: var(--fg);
            margin-bottom: 16px;
        }

        .mod-review-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mod-review-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .mod-review-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--fg);
        }

        .mod-review-date {
            font-size: 0.72rem;
            color: var(--muted);
        }

        /* ── REVIEW FORM ── */
        .mod-review-form {
            max-width: 520px;
            margin: 36px auto 0;
            padding: 28px;
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .mod-review-form h3 {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--fg);
        }

        .mod-form-field {
            margin-bottom: 14px;
        }

        .mod-form-field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .mod-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.88rem;
            font-family: var(--font-body);
            outline: none;
            transition: border-color 0.2s;
        }

        .mod-input:focus { border-color: var(--accent); }

        .mod-btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            border: none;
            border-radius: 10px;
            font-family: var(--font-heading);
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s;
        }

        .mod-btn-submit:hover { opacity: 0.9; }

        .mod-form-note {
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 10px;
            text-align: center;
        }

        /* ── FOOTER ── */
        .mod-footer {
            background: var(--navy);
            color: rgba(255,255,255,0.5);
        }

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
            border-radius: 12px;
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
            border-radius: 14px;
            border: none;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(255,107,53,0.3);
            transition: all 0.25s;
        }

        .share-fab-trigger:hover { transform: scale(1.05); }

        /* ══════════ RESPONSIVE ══════════ */

        @media (max-width: 960px) {
            .mod-nav-links { display: none; }
            .mod-hamburger { display: block; }
            .mod-drawer { display: block; }

            .mod-hero-inner {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 60px 24px 64px;
            }

            .mod-hero h1 { font-size: 2.3rem; }

            .mod-hero-stat { position: static; margin-top: 16px; }

            .mod-section, .mod-section-alt-inner { padding: 56px 24px; }

            .mod-tours-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }

            .mod-gallery-grid { grid-template-columns: repeat(3, 1fr); }

            .mod-footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
            .mod-footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .mod-nav-inner { height: 56px; }
            .mod-logo-icon { width: 32px; height: 32px; font-size: 13px; }
            .mod-logo-text { font-size: 0.95rem; }

            .mod-hero { padding-top: 56px; }

            .mod-hero-inner {
                padding: 36px 16px 44px;
                gap: 28px;
            }

            .mod-hero h1 { font-size: 1.8rem; letter-spacing: -0.5px; }
            .mod-hero-desc { font-size: 0.9rem; }

            .mod-hero-actions { flex-direction: column; align-items: flex-start; }
            .mod-btn { padding: 12px 22px; font-size: 0.85rem; }

            .mod-hero-img, .mod-hero-img-placeholder { border-radius: 12px; }

            .mod-section, .mod-section-alt-inner { padding: 40px 16px; }
            .mod-section-title { font-size: 1.4rem; }

            .mod-tours-grid { grid-template-columns: 1fr; gap: 16px; }
            .mod-tour-card-body { padding: 16px; }

            .mod-gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }

            .mod-vehicles-grid { grid-template-columns: 1fr; gap: 12px; }

            .mod-reviews-grid { grid-template-columns: 1fr; gap: 12px; }
            .mod-review-card { padding: 18px; }

            .mod-review-form { padding: 20px; margin-top: 28px; }

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
            <li><a href="{{ $homeUrl }}" class="active">{{ __('messages.home') }}</a></li>
            @foreach($pages as $p)
                <li><a href="{{ $pageUrlBase . '/' . $p->slug }}">{{ $p->title }}</a></li>
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
                <a href="{{ $pageUrlBase . '/' . $p->slug }}"
                   @click="drawerOpen = false">{{ $p->title }}</a>
            @endforeach
            @if($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" @click="drawerOpen = false"><i class="fab fa-whatsapp" style="margin-right:6px;"></i>{{ __('messages.contact') }}</a>
            @endif
        </div>
    </div>
</div>

<!-- HERO -->
<section class="mod-hero">
    <div class="mod-hero-bg"></div>
    <div class="mod-hero-inner">
        <div>
            <div class="mod-hero-badge"><i class="fas fa-sparkles"></i> {{ $settings->site_title ?? $website->site_name ?? 'Travel' }}</div>
            <h1>Jelajahi <em>Dunia</em> Bersama Kami</h1>
            <p class="mod-hero-desc">{{ $settings->hero_subtitle ?? $settings->description ?? 'Temukan pengalaman perjalanan terbaik dengan layanan profesional dan harga terjangkau.' }}</p>
            <div class="mod-hero-actions">
                @if($tourPackages->count() > 0)
                    <a href="#tours" class="mod-btn">Lihat Tour <i class="fas fa-arrow-right" style="font-size:0.75rem;"></i></a>
                @endif
                @if($website->contact_whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank" class="mod-btn mod-btn-ghost"><i class="fab fa-whatsapp"></i> Hubungi Kami</a>
                @endif
            </div>
        </div>
        <div class="mod-hero-image">
            @if($settings->hero_image_url)
                <img src="{{ $settings->hero_image_url }}" alt="Hero" class="mod-hero-img"/>
            @elseif($galleryImages->count() > 0)
                <img src="{{ $galleryImages->first()['url'] }}" alt="Gallery" class="mod-hero-img"/>
            @else
                <div class="mod-hero-img-placeholder"><i class="fas fa-image"></i></div>
            @endif
            @if($tourPackages->count() > 0)
                <div class="mod-hero-stat">
                    <div class="mod-hero-stat-icon"><i class="fas fa-route"></i></div>
                    <div class="mod-hero-stat-text">
                        <strong>{{ $tourPackages->count() }}+ Tour</strong>
                        Tersedia untuk Anda
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- TOUR PACKAGES -->
@if($tourPackages->count() > 0)
<section class="mod-section" id="tours">
    <div class="mod-section-head">
        <div class="mod-section-label"><i class="fas fa-fire"></i> Destinasi Populer</div>
        <h2 class="mod-section-title">{{ __('messages.tours') }}</h2>
    </div>
    <div class="mod-tours-grid">
        @foreach($tourPackages as $tour)
            <a href="{{ $tourUrlBase . '/' . $tour->slug }}" class="mod-tour-card">
                <div class="mod-tour-card-img-wrap">
                    @if($tour->thumbnail_url)
                        <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}" class="mod-tour-card-img"/>
                    @elseif($tour->images->count() > 0)
                        <img src="{{ $tour->images->first()->url }}" alt="{{ $tour->title }}" class="mod-tour-card-img"/>
                    @else
                        <div class="mod-tour-card-img-placeholder"><i class="fas fa-mountain"></i></div>
                    @endif
                    @if($tour->is_featured)
                        <div class="mod-tour-card-badge">Best Seller</div>
                    @endif
                </div>
                <div class="mod-tour-card-body">
                    <h3 class="mod-tour-card-title">{{ $tour->title }}</h3>
                    @if($tour->description)
                        <p class="mod-tour-card-desc">{{ strip_tags($tour->description) }}</p>
                    @endif
                    <div class="mod-tour-card-footer">
                        @if($tour->price_start_from)
                            <div class="mod-tour-price"><span>Mulai </span>Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</div>
                        @else
                            <div></div>
                        @endif
                        <div class="mod-tour-card-btn"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- GALLERY -->
@if($galleryImages->count() > 0)
<section class="mod-section-alt">
    <div class="mod-section-alt-inner">
        <div class="mod-section-head">
            <div class="mod-section-label"><i class="fas fa-camera"></i> Galeri</div>
            <h2 class="mod-section-title">Momen Perjalanan</h2>
        </div>
        <div class="mod-gallery-grid"
             x-data="galleryLightbox(@js($galleryImages->values()->all()))" x-init="init()">
            @foreach($galleryImages as $i => $img)
                <div class="mod-gallery-item" @click="open({{ $i }})">
                    <img src="{{ $img['url'] }}" alt="{{ $img['alt'] ?? 'Gallery' }}" loading="lazy"/>
                </div>
            @endforeach

            @if($features['gallery_lightbox'] ?? false)
            <template x-if="isOpen">
                <div style="position:fixed;top:0;left:0;right:0;bottom:0;z-index:300;background:rgba(0,0,0,0.92);display:flex;align-items:center;justify-content:center;"
                     @click.self="close()" x-transition>
                    <button @click="close()" style="position:absolute;top:20px;right:20px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;padding:8px;">&times;</button>
                    <button @click="prev()" style="position:absolute;left:16px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;padding:8px;">&#8249;</button>
                    <img :src="images[currentIndex]?.url || ''" style="max-width:90vw;max-height:85vh;object-fit:contain;border-radius:8px;"/>
                    <button @click="next()" style="position:absolute;right:16px;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;padding:8px;">&#8250;</button>
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
<section class="mod-section">
    <div class="mod-section-head">
        <div class="mod-section-label"><i class="fas fa-car"></i> Transportasi</div>
        <h2 class="mod-section-title">Armada Kendaraan</h2>
    </div>
    <div class="mod-vehicles-grid">
        @foreach($vehicles as $vehicle)
            <div class="mod-vehicle-card">
                <div class="mod-vehicle-thumb">
                    @if($vehicle->image_url)
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model_name }}"/>
                    @elseif($vehicle->images->count() > 0)
                        <img src="{{ $vehicle->images->first()->url }}" alt="{{ $vehicle->model_name }}"/>
                    @else
                        <i class="fas fa-car"></i>
                    @endif
                </div>
                <div class="mod-vehicle-info">
                    <div class="mod-vehicle-name">{{ $vehicle->model_name }}</div>
                    <div style="display:flex;align-items:center;gap:12px;margin-top:6px;flex-wrap:wrap;">
                        @if($vehicle->capacity_people)
                            <span style="font-size:0.78rem;color:var(--muted);display:flex;align-items:center;gap:4px;">
                                <i class="fas fa-users" style="color:var(--accent);font-size:0.7rem;"></i> {{ $vehicle->capacity_people }} Kursi
                            </span>
                        @endif
                        @if($vehicle->price_per_day)
                            <span style="font-size:0.82rem;font-weight:700;color:var(--accent);">
                                Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}<span style="font-weight:400;font-size:0.72rem;color:var(--muted);"> /hari</span>
                            </span>
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
<section class="mod-section-alt" id="reviews">
    <div class="mod-section-alt-inner">
        <div class="mod-section-head" style="text-align:center;">
            <div class="mod-section-label" style="justify-content:center;"><i class="fas fa-star"></i> Testimoni</div>
            <h2 class="mod-section-title">Apa Kata Mereka</h2>
        </div>

        @if(session('review_success'))
            <div style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:12px 20px;margin-bottom:24px;text-align:center;font-size:0.85rem;color:var(--success);max-width:600px;margin-left:auto;margin-right:auto;">
                <i class="fas fa-check-circle"></i> {{ session('review_success') }}
            </div>
        @endif

        @if($reviews->count() > 0)
        <div class="mod-reviews-grid">
            @foreach($reviews as $review)
                <div class="mod-review-card">
                    <div class="mod-review-stars">
                        @for($s = 0; $s < 5; $s++)
                            @if($s < $review->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star" style="color:#D1D5DB;"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="mod-review-text">"{{ $review->comment }}"</p>
                    <div class="mod-review-author">
                        <div class="mod-review-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</div>
                        <div>
                            <div class="mod-review-name">{{ $review->reviewer_name }}</div>
                            <div class="mod-review-date">{{ $review->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:36px 24px;color:var(--muted);font-size:0.9rem;">
            <i class="far fa-comment-dots" style="font-size:1.5rem;display:block;margin-bottom:8px;"></i>
            Belum ada review. Jadilah yang pertama!
        </div>
        @endif

        <div class="mod-review-form">
            <h3><i class="fas fa-pen" style="color:var(--accent);margin-right:6px;"></i> Tulis Review</h3>
            <form method="POST" action="{{ route('public.reviews.store', $website->subdomain) }}">
                @csrf
                @if($errors->any())
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:0.82rem;color:#dc2626;">
                        @foreach($errors->all() as $error) <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p> @endforeach
                    </div>
                @endif
                <div class="mod-form-field">
                    <label>Nama *</label>
                    <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}" required class="mod-input"/>
                </div>
                <div class="mod-form-field">
                    <label>Email</label>
                    <input type="email" name="reviewer_email" value="{{ old('reviewer_email') }}" class="mod-input"/>
                </div>
                <div class="mod-form-field" x-data="{ rating: 0 }">
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
                <div class="mod-form-field">
                    <label>Komentar *</label>
                    <textarea name="comment" rows="3" required class="mod-input" style="resize:vertical;">{{ old('comment') }}</textarea>
                </div>
                <button type="submit" class="mod-btn-submit"><i class="fas fa-paper-plane" style="margin-right:6px;"></i> Kirim Review</button>
                <p class="mod-form-note">Review akan ditampilkan setelah disetujui.</p>
            </form>
        </div>
    </div>
</section>
@endif

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
                        <li><a href="{{ $pageUrlBase . '/' . $p->slug }}">{{ $p->title }}</a></li>
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
                        <li><a href="{{ $settings->social_instagram }}" target="_blank"><i class="fab fa-instagram" style="margin-right:4px;"></i> Instagram</a></li>
                    @endif
                    @if($website->contact_whatsapp)
                        <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" target="_blank"><i class="fab fa-whatsapp" style="margin-right:4px;"></i> WhatsApp</a></li>
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

{{-- Social Share FAB --}}
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
