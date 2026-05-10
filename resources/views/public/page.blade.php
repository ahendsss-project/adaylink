{{-- Shared Page Detail Template --}}
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
    $primaryColor = $settings->primary_color ?? '#40ac98';
    $secondaryColor = $settings->secondary_color ?? '#333333';
    $fontHeading = $settings->font_heading ?? 'Inter';
    $fontBody = $settings->font_body ?? 'Inter';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} — {{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if ($settings)
        <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700&family={{ urlencode($fontBody) }}:wght@300;400;500&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
            --font-heading: '{{ $fontHeading }}', serif;
            --font-body: '{{ $fontBody }}', sans-serif;
        }
        body { font-family: var(--font-body); }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-heading); }
        .share-fab { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .share-options { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="min-h-screen bg-white text-gray-800">

    {{-- Navigation --}}
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-40" x-data="{ mobileOpen: false }">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Logo / Site Name --}}
            <a href="{{ $homeUrl }}" class="flex items-center gap-3 no-underline">
                @if ($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $website->site_name }}" class="h-8 w-8 rounded-lg object-cover" />
                @else
                    <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white font-bold text-sm" style="background-color: {{ $primaryColor }}">
                        {{ strtoupper(substr($website->site_name ?? 'W', 0, 1)) }}
                    </div>
                @endif
                <span class="font-semibold text-gray-800 text-lg">{{ $website->site_name ?? 'Website' }}</span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-6 text-sm text-gray-500">
                <a href="{{ $homeUrl }}" class="hover:text-gray-900 transition flex items-center gap-1.5">
                    <i class="fas fa-home text-xs"></i>
                    Beranda
                </a>
                @foreach ($pages as $p)
                    @php $pageUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug; @endphp
                    <a href="{{ $pageUrl }}" class="hover:text-gray-900 transition {{ $p->slug === $page->slug ? 'text-gray-900 font-medium' : '' }}">{{ $p->title }}</a>
                @endforeach
            </div>

            <div class="flex items-center gap-3">
                {{-- Mobile Hamburger --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-gray-600 p-1" aria-label="Toggle menu">
                    <i x-show="!mobileOpen" class="fas fa-bars text-xl"></i>
                    <i x-show="mobileOpen" class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="md:hidden border-t border-gray-100 px-6 py-4 space-y-3 text-sm text-gray-600">
            <a href="{{ $homeUrl }}" class="block hover:text-gray-900 flex items-center gap-1.5">
                <i class="fas fa-home text-xs"></i>
                Beranda
            </a>
            @foreach ($pages as $p)
                @php $pageUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug; @endphp
                <a href="{{ $pageUrl }}" class="block hover:text-gray-900 {{ $p->slug === $page->slug ? 'text-gray-900 font-medium' : '' }}">{{ $p->title }}</a>
            @endforeach
        </div>
    </nav>

    {{-- Page Title Hero --}}
    <section style="background-color: {{ $primaryColor }}">
        <div class="max-w-5xl mx-auto px-6 py-12 md:py-16">
            <nav class="text-sm text-white/70 mb-4 flex items-center gap-2">
                <a href="{{ $homeUrl }}" class="hover:text-white transition">Beranda</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-white">{{ $page->title }}</span>
            </nav>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white">{{ $page->title }}</h1>
        </div>
    </section>

    {{-- Page Content --}}
    <section class="py-12 md:py-16">
        <div class="max-w-3xl mx-auto px-6">
            <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed whitespace-pre-line">{{ $page->content }}</div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 py-8 mt-8">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <p class="text-sm text-gray-400">&copy; {{ date('Y') }} {{ $website->site_name }}. Powered by <span style="color: {{ $primaryColor }}">adaylink</span></p>
        </div>
    </footer>

    {{-- Floating WhatsApp (Feature: floating_whatsapp) --}}
    @if (($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
           target="_blank" rel="noopener noreferrer"
           class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg flex items-center justify-center transition hover:scale-110"
           x-data x-init="$el.classList.add('translate-y-20','opacity-0'); setTimeout(()=>$el.classList.remove('translate-y-20','opacity-0'),1000)"
           aria-label="Chat WhatsApp">
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>
    @endif

    {{-- Social Share (Feature: social_share) --}}
    @if ($features['social_share'] ?? false)
        <div class="fixed bottom-6 left-6 z-50" x-data="socialShare()" x-init="init()">
            <div class="share-options flex flex-col gap-2 mb-2" x-show="isOpen"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-2">
                {{-- WhatsApp --}}
                <a :href="'https://wa.me/?text=' + encodeURIComponent(pageUrl)" target="_blank" class="w-10 h-10 bg-green-500 text-white rounded-full shadow flex items-center justify-center hover:scale-110 transition">
                    <i class="fab fa-whatsapp"></i>
                </a>
                {{-- Facebook --}}
                <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(pageUrl)" target="_blank" class="w-10 h-10 bg-blue-600 text-white rounded-full shadow flex items-center justify-center hover:scale-110 transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                {{-- X / Twitter --}}
                <a :href="'https://twitter.com/intent/tweet?url=' + encodeURIComponent(pageUrl)" target="_blank" class="w-10 h-10 bg-black text-white rounded-full shadow flex items-center justify-center hover:scale-110 transition">
                    <i class="fab fa-x-twitter"></i>
                </a>
                {{-- Copy Link --}}
                <button @click="copyLink()" class="w-10 h-10 bg-gray-500 text-white rounded-full shadow flex items-center justify-center hover:scale-110 transition" :class="{'bg-emerald-500': copied}">
                    <i class="fas" :class="copied ? 'fa-check' : 'fa-link'"></i>
                </button>
            </div>
            <button @click="isOpen = !isOpen" class="share-fab w-11 h-11 rounded-full shadow flex items-center justify-center text-white hover:scale-110 transition" style="background-color: {{ $primaryColor }}" aria-label="Share">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>
    @endif

    {{-- Alpine.js Scripts --}}
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
