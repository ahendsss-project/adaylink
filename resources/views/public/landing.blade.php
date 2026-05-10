@php
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
    <title>{{ $settings->site_title ?? $website->site_name ?? 'Website' }}</title>
    @if ($settings)
        <meta name="description" content="{{ $settings->seo_meta_description ?? '' }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700&family={{ urlencode($fontBody) }}:wght@300;400;500&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    {{-- Schema.org JSON-LD for reviews --}}
    @if (($features['reviews'] ?? false) && isset($reviewSchema) && $reviewSchema)
        <script type="application/ld+json">{{ json_encode($reviewSchema) }}</script>
    @endif
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --primary-light: {{ $primaryColor }}20;
            --secondary: {{ $secondaryColor }};
            --font-heading: '{{ $fontHeading }}', serif;
            --font-body: '{{ $fontBody }}', sans-serif;
        }
        body { font-family: var(--font-body); }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-heading); }
        /* Lightbox styles */
        .lightbox-overlay {
            background: rgba(0,0,0,0.9);
        }
        /* Star rating interactive */
        .star-rating-input button {
            cursor: pointer;
            transition: transform 0.15s;
        }
        .star-rating-input button:hover {
            transform: scale(1.2);
        }
        /* Social share animation */
        .share-fab {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .share-options {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="min-h-screen bg-white">

    {{-- Navigation --}}
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-40" x-data="{ mobileOpen: false }">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            {{-- Logo / Site Name (Left) --}}
            <div class="flex items-center gap-3">
                @if ($website->logo_url)
                    <img src="{{ $website->logo_url }}" alt="{{ $website->site_name }}" class="h-8 w-8 rounded-lg object-cover" />
                @else
                    <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white font-bold text-sm" style="background-color: var(--primary)">
                        {{ strtoupper(substr($website->site_name, 0, 1)) }}
                    </div>
                @endif
                <span class="font-bold text-gray-800">{{ $website->site_name }}</span>
            </div>

            {{-- Desktop Menu (Center) --}}
            <div class="hidden md:flex items-center gap-5 text-sm text-gray-600">
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/' }}" class="hover:text-gray-900 transition">Beranda</a>
                @if ($tourPackages->isNotEmpty())
                    <a href="#paket-tour" class="hover:text-gray-900 transition">Paket Tour</a>
                @endif
                @if ($vehicles->isNotEmpty())
                    <a href="#armada" class="hover:text-gray-900 transition">Armada</a>
                @endif
                @foreach ($pages as $p)
                    <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" class="hover:text-gray-900 transition">{{ $p->title }}</a>
                @endforeach
            </div>

            {{-- Right Side --}}
            <div class="flex items-center gap-2">
                @if ($website->contact_whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="hidden md:inline-flex text-sm font-medium px-4 py-2 rounded-lg text-white transition hover:opacity-90"
                       style="background-color: var(--primary)">
                        <i class="fab fa-whatsapp mr-1"></i> Hubungi Kami
                    </a>
                @endif
                {{-- Mobile Hamburger --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-gray-600 text-xl">
                    <i class="fas" :class="mobileOpen ? 'fa-times' : 'fa-bars'"></i>
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
             class="md:hidden border-t border-gray-100 px-4 py-4 space-y-3 text-sm text-gray-600">
            <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/' }}" class="block hover:text-gray-900">Beranda</a>
            @if ($tourPackages->isNotEmpty())
                <a href="#paket-tour" class="block hover:text-gray-900">Paket Tour</a>
            @endif
            @if ($vehicles->isNotEmpty())
                <a href="#armada" class="block hover:text-gray-900">Armada</a>
            @endif
            @foreach ($pages as $p)
            <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $p->slug : '/page/' . $p->slug }}" class="block hover:text-gray-900">{{ $p->title }}</a>
        @endforeach
            @if ($website->contact_whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}"
                   target="_blank" rel="noopener noreferrer"
                   class="block text-center py-2 rounded-lg text-white font-medium"
                   style="background-color: var(--primary)">
                    <i class="fab fa-whatsapp mr-1"></i> Hubungi Kami
                </a>
            @endif
        </div>
    </nav>

    {{-- Page Content (when viewing a specific page via showPage()) --}}
    @isset($page)
        <section class="py-16 md:py-24">
            <div class="max-w-4xl mx-auto px-4">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-8">{{ $page->title }}</h1>
                <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed whitespace-pre-line">{{ $page->content }}</div>
            </div>
        </section>

    @else

    {{-- Hero Section --}}
    <section id="beranda">
        @if ($settings && ($settings->hero_title || $settings->hero_image_url))
            <div class="relative overflow-hidden" style="background-color: var(--primary)">
                @if ($settings->hero_image_url)
                    <div class="absolute inset-0">
                        <img src="{{ $settings->hero_image_url }}" alt="Hero" class="w-full h-full object-cover opacity-30" />
                    </div>
                @endif
                <div class="relative max-w-6xl mx-auto px-4 py-16 md:py-24 text-center">
                    @if ($settings->hero_title)
                        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">{{ $settings->hero_title }}</h1>
                    @endif
                    @if ($settings->hero_subtitle)
                        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto mb-6">{{ $settings->hero_subtitle }}</p>
                    @endif
                    @if ($website->contact_whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 bg-white font-medium px-6 py-3 rounded-lg transition hover:shadow-lg"
                           style="color: var(--primary)">
                            <i class="fab fa-whatsapp text-lg"></i>
                            Booking Sekarang
                        </a>
                    @endif
                </div>
            </div>
        @else
            {{-- Fallback hero if no settings --}}
            <div class="py-16 md:py-24 text-center" style="background-color: var(--primary)">
                <div class="max-w-6xl mx-auto px-4">
                    <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">{{ $website->site_name }}</h1>
                    <p class="text-lg text-white/80">Selamat datang di website kami</p>
                </div>
            </div>
        @endif
    </section>

    {{-- Tour Packages Section --}}
    @if ($tourPackages->isNotEmpty())
        <section id="paket-tour" class="py-16 md:py-20 bg-gray-50">
            <div class="max-w-6xl mx-auto px-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-2">Paket Tour</h2>
                <p class="text-gray-500 text-center mb-8">Pilih paket perjalanan terbaik untuk Anda</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @foreach ($tourPackages as $tour)
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition">
                            {{-- Thumbnail --}}
                            @if ($tour->thumbnail_url)
                                <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}" class="w-full h-48 object-cover" />
                            @endif
                            @if ($tour->is_featured)
                                <div class="bg-amber-500 text-white text-xs font-bold px-3 py-1 text-center">⭐ PAKET FAVORIT</div>
                            @endif
                            <div class="p-5">
                                <h3 class="font-bold text-gray-800 text-lg mb-2">{{ $tour->title }}</h3>

                                @if ($tour->duration_text)
                                    <p class="text-sm text-gray-500 mb-2"><i class="far fa-clock mr-1"></i> {{ $tour->duration_text }}</p>
                                @endif

                                {{-- Description --}}
                                @if ($tour->description)
                                    <div class="text-sm text-gray-600 mb-3 line-clamp-4 whitespace-pre-line">
                                        {{ $tour->description }}
                                    </div>
                                @endif

                                @if ($tour->itinerary && is_array($tour->itinerary))
                                    <div class="mb-3">
                                        <p class="text-xs font-semibold text-gray-500 mb-1">Itinerary:</p>
                                        <ul class="text-sm text-gray-600 space-y-1">
                                            @foreach (array_slice($tour->itinerary, 0, 3) as $item)
                                                <li class="flex items-start gap-1.5">
                                                    <span class="text-blue-500 mt-0.5"><i class="fas fa-calendar-day text-xs"></i></span>
                                                    <span>{{ $item }}</span>
                                                </li>
                                            @endforeach
                                            @if (count($tour->itinerary) > 3)
                                                <li class="text-gray-400 text-xs">+{{ count($tour->itinerary) - 3 }} hari lainnya</li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif

                                @if ($tour->includes && is_array($tour->includes))
                                    <div class="mb-3">
                                        <p class="text-xs font-semibold text-gray-500 mb-1">Termasuk:</p>
                                        <ul class="text-sm text-gray-600 space-y-0.5">
                                            @foreach (array_slice($tour->includes, 0, 4) as $item)
                                                <li class="flex items-start gap-1.5">
                                                    <span class="text-green-500 mt-0.5"><i class="fas fa-check text-xs"></i></span>
                                                    <span>{{ $item }}</span>
                                                </li>
                                            @endforeach
                                            @if (count($tour->includes) > 4)
                                                <li class="text-gray-400 text-xs">+{{ count($tour->includes) - 4 }} lainnya</li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif

                                @if ($tour->excludes && is_array($tour->excludes))
                                    <div class="mb-3">
                                        <p class="text-xs font-semibold text-gray-500 mb-1">Tidak Termasuk:</p>
                                        <ul class="text-sm text-gray-600 space-y-0.5">
                                            @foreach (array_slice($tour->excludes, 0, 3) as $item)
                                                <li class="flex items-start gap-1.5">
                                                    <span class="text-red-400 mt-0.5"><i class="fas fa-times text-xs"></i></span>
                                                    <span>{{ $item }}</span>
                                                </li>
                                            @endforeach
                                            @if (count($tour->excludes) > 3)
                                                <li class="text-gray-400 text-xs">+{{ count($tour->excludes) - 3 }} lainnya</li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif

                                @if ($tour->notes)
                                    <div class="mb-3 bg-amber-50 border border-amber-200 rounded-lg p-2.5">
                                        <p class="text-xs font-semibold text-amber-700 mb-0.5"><i class="fas fa-sticky-note mr-1"></i>Catatan:</p>
                                        <p class="text-xs text-amber-700 whitespace-pre-line">{{ $tour->notes }}</p>
                                    </div>
                                @endif

                                @if ($tour->price_start_from)
                                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                                        <div>
                                            <p class="text-xs text-gray-400">Mulai dari</p>
                                            <p class="text-xl font-bold" style="color: var(--primary)">Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</p>
                                        </div>
                                        @if ($website->contact_whatsapp)
                                            @php
                                                $waMessage = "Halo, saya tertarik dengan paket tour *{$tour->title}*. Apakah bisa info lebih lanjut?";
                                            @endphp
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode($waMessage) }}"
                                               target="_blank" rel="noopener noreferrer"
                                               class="text-sm font-medium px-4 py-2 rounded-lg text-white transition hover:opacity-90"
                                               style="background-color: var(--primary)">
                                                Tanya
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Vehicles Section --}}
    @if ($vehicles->isNotEmpty())
        <section id="armada" class="py-16 md:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-2">Armada Kami</h2>
                <p class="text-gray-500 text-center mb-8">Pilih kendaraan yang sesuai kebutuhan Anda</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @foreach ($vehicles as $vehicle)
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition">
                            @if ($vehicle->image_url)
                                <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model_name }}" class="w-full h-48 object-cover" />
                            @else
                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-gray-300"></i>
                                </div>
                            @endif
                            <div class="p-5">
                                <h3 class="font-bold text-gray-800 text-lg">{{ $vehicle->model_name }}</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full"><i class="fas fa-users mr-1"></i> {{ $vehicle->capacity_people }} orang</span>
                                </div>
                                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                                    <div>
                                        <p class="text-xs text-gray-400">Per hari</p>
                                        <p class="text-xl font-bold" style="color: var(--primary)">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</p>
                                    </div>
                                    @if ($website->contact_whatsapp)
                                        @php
                                            $waMessage = "Halo, saya ingin sewa kendaraan *{$vehicle->model_name}*. Apakah tersedia?";
                                        @endphp
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode($waMessage) }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="text-sm font-medium px-4 py-2 rounded-lg text-white transition hover:opacity-90"
                                           style="background-color: var(--primary)">
                                            Sewa
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Gallery Section (Feature: gallery_lightbox) --}}
    @if (($features['gallery_lightbox'] ?? false) && isset($galleryImages) && $galleryImages->isNotEmpty())
        <section class="py-16 md:py-20 bg-gray-50">
            <div class="max-w-6xl mx-auto px-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-2">Galeri</h2>
                <p class="text-gray-500 text-center mb-8">Lihat momen dan destinasi perjalanan kami</p>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4"
                     x-data="galleryLightbox(@js($galleryImages))"
                     x-init="init()">
                    @foreach ($galleryImages as $index => $img)
                        <div class="relative group cursor-pointer overflow-hidden rounded-lg aspect-square"
                             @click="open({{ $index }})">
                            <img src="{{ $img['url'] }}" alt="{{ $img['alt'] }}"
                                 class="w-full h-full object-cover transition duration-300 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-expand text-white text-xl opacity-0 group-hover:opacity-100 transition"></i>
                            </div>
                        </div>
                    @endforeach

                    {{-- Lightbox Modal --}}
                    <template x-if="isOpen">
                        <div class="lightbox-overlay fixed inset-0 z-50 flex items-center justify-center p-4"
                             @click.self="close()"
                             x-show="isOpen"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            {{-- Close button --}}
                            <button @click="close()" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10">
                                <i class="fas fa-times"></i>
                            </button>

                            {{-- Previous button --}}
                            <button @click="prev()" class="absolute left-4 text-white text-3xl hover:text-gray-300 z-10">
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            {{-- Next button --}}
                            <button @click="next()" class="absolute right-4 text-white text-3xl hover:text-gray-300 z-10">
                                <i class="fas fa-chevron-right"></i>
                            </button>

                            {{-- Image --}}
                            <div class="max-w-4xl max-h-[80vh] relative">
                                <img :src="images[currentIndex]?.url"
                                     :alt="images[currentIndex]?.alt"
                                     class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl"
                                     @click.stop />
                                <p class="text-center text-white/70 text-sm mt-3"
                                   x-text="images[currentIndex]?.alt"></p>
                            </div>

                            {{-- Counter --}}
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/60 text-sm">
                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>
    @endif

    {{-- Reviews Section (Feature: reviews) --}}
    @if ($features['reviews'] ?? false)
        <section class="py-16 md:py-20" id="reviews">
            <div class="max-w-6xl mx-auto px-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-2">Review Pelanggan</h2>
                <p class="text-gray-500 text-center mb-8">Apa kata mereka tentang layanan kami</p>

                {{-- Review Success Message --}}
                @if (session('review_success'))
                    <div class="max-w-xl mx-auto mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-center">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('review_success') }}
                    </div>
                @endif

                @if (isset($reviews) && $reviews->isNotEmpty())
                    {{-- Average Rating Summary --}}
                    @php
                        $avgRating = round($reviews->avg('rating'), 1);
                    @endphp
                    <div class="text-center mb-8">
                        <div class="text-4xl font-bold text-gray-800">{{ $avgRating }}</div>
                        <div class="flex items-center justify-center gap-1 mt-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star text-amber-400"></i>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $reviews->count() }} review</p>
                    </div>

                    {{-- Review Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-10">
                        @foreach ($reviews as $review)
                            <div class="bg-white rounded-xl border border-gray-200 p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                             style="background-color: var(--primary)">
                                            {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $review->reviewer_name }}</p>
                                            <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-0.5 mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-amber-400 text-sm"></i>
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 mb-8">
                        <i class="far fa-comment-dots text-4xl mb-3"></i>
                        <p>Belum ada review. Jadilah yang pertama!</p>
                    </div>
                @endif

                {{-- Review Submission Form --}}
                <div class="max-w-xl mx-auto bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h3 class="font-bold text-gray-800 text-lg mb-4"><i class="fas fa-pen-to-square mr-2" style="color: var(--primary)"></i>Tulis Review</h3>
                    <form method="POST" action="{{ route('public.reviews.store', $website->subdomain) }}">
                        @csrf
                        @if ($errors->any())
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                                @foreach ($errors->all() as $error)
                                    <p><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                                <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                       style="--tw-ring-color: var(--primary)"
                                       placeholder="Nama Anda" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="reviewer_email" value="{{ old('reviewer_email') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                       style="--tw-ring-color: var(--primary)"
                                       placeholder="email@contoh.com" />
                            </div>
                        </div>

                        {{-- Star Rating --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating <span class="text-red-500">*</span></label>
                            <div class="star-rating-input flex items-center gap-1" x-data="{ rating: 0 }">
                                <template x-for="i in 5" :key="i">
                                    <button type="button" @click="rating = i; $refs.ratingInput.value = i"
                                            class="text-2xl" :class="i <= rating ? 'text-amber-400' : 'text-gray-300'">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </template>
                                <input type="hidden" name="rating" value="{{ old('rating') }}" x-ref="ratingInput" required />
                            </div>
                        </div>

                        {{-- Comment --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Komentar <span class="text-red-500">*</span></label>
                            <textarea name="comment" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent resize-none"
                                      style="--tw-ring-color: var(--primary)"
                                      placeholder="Bagikan pengalaman Anda...">{{ old('comment') }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 rounded-lg text-white font-medium text-sm transition hover:opacity-90"
                                style="background-color: var(--primary)">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Review
                        </button>
                        <p class="text-xs text-gray-400 text-center mt-2">Review akan ditampilkan setelah disetujui oleh pemilik website.</p>
                    </form>
                </div>
            </div>
        </section>
    @endif

    {{-- Empty State --}}
    @if ($tourPackages->isEmpty() && $vehicles->isEmpty())
        <section class="py-16 text-center">
            <div class="max-w-md mx-auto px-4">
                <i class="fas fa-box-open text-6xl text-gray-200 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-500 mb-2">Website Sedang Disiapkan</h3>
                <p class="text-sm text-gray-400">Konten akan segera tersedia.</p>
            </div>
        </section>
    @endif

    @endisset

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-sm">© {{ date('Y') }} {{ $website->site_name }}. Powered by <span class="text-indigo-400 font-medium">adaylink</span></p>
        </div>
    </footer>

    {{-- Floating WhatsApp Button (Feature: floating_whatsapp) --}}
    @if (($features['floating_whatsapp'] ?? false) && $website->contact_whatsapp)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Halo, saya ingin bertanya tentang layanan Anda.') }}"
           target="_blank" rel="noopener noreferrer"
           class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg shadow-green-500/30 flex items-center justify-center transition hover:scale-110"
           x-data
           x-init="
               $el.classList.add('translate-y-20', 'opacity-0');
               setTimeout(() => {
                   $el.classList.remove('translate-y-20', 'opacity-0');
                   $el.classList.add('translate-y-0', 'opacity-100');
               }, 1000);
           "
        >
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>
    @endif

    {{-- Social Share Buttons (Feature: social_share) --}}
    @if ($features['social_share'] ?? false)
        <div class="fixed bottom-6 left-6 z-50"
             x-data="socialShare()"
             x-init="init()">
            {{-- Share Options --}}
            <div class="share-options flex flex-col gap-2 mb-2"
                 x-show="isOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2">
                <a :href="'https://wa.me/?text=' + encodeURIComponent(pageUrl)"
                   target="_blank" rel="noopener noreferrer"
                   class="w-11 h-11 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg flex items-center justify-center transition hover:scale-110"
                   title="Bagikan via WhatsApp">
                    <i class="fab fa-whatsapp text-lg"></i>
                </a>
                <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(pageUrl)"
                   target="_blank" rel="noopener noreferrer"
                   class="w-11 h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition hover:scale-110"
                   title="Bagikan via Facebook">
                    <i class="fab fa-facebook-f text-lg"></i>
                </a>
                <a :href="'https://twitter.com/intent/tweet?url=' + encodeURIComponent(pageUrl) + '&text=' + encodeURIComponent('{{ $website->site_name }}')"
                   target="_blank" rel="noopener noreferrer"
                   class="w-11 h-11 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg flex items-center justify-center transition hover:scale-110"
                   title="Bagikan via X/Twitter">
                    <i class="fab fa-x-twitter text-lg"></i>
                </a>
                <button @click="copyLink()"
                        class="w-11 h-11 bg-gray-600 hover:bg-gray-700 text-white rounded-full shadow-lg flex items-center justify-center transition hover:scale-110"
                        :class="{ 'bg-emerald-500': copied }"
                        :title="copied ? 'Tersalin!' : 'Salin link'">
                    <i class="fas" :class="copied ? 'fa-check' : 'fa-link'"></i>
                </button>
            </div>

            {{-- FAB Toggle --}}
            <button @click="isOpen = !isOpen"
                    class="share-fab w-12 h-12 rounded-full shadow-lg flex items-center justify-center text-white transition hover:scale-110"
                    :class="isOpen ? 'bg-gray-700 rotate-45' : ''"
                    style="background-color: var(--primary)">
                <i class="fas fa-share-nodes text-lg" :class="isOpen ? 'rotate-45' : ''"></i>
            </button>
        </div>
    @endif

    {{-- Alpine.js Component Scripts --}}
    <script>
        function galleryLightbox(images) {
            return {
                images: images,
                isOpen: false,
                currentIndex: 0,
                init() {
                    document.addEventListener('keydown', (e) => {
                        if (!this.isOpen) return;
                        if (e.key === 'Escape') this.close();
                        if (e.key === 'ArrowLeft') this.prev();
                        if (e.key === 'ArrowRight') this.next();
                    });
                },
                open(index) {
                    this.currentIndex = index;
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                close() {
                    this.isOpen = false;
                    document.body.style.overflow = '';
                },
                prev() {
                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                },
                next() {
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                },
            };
        }

        function socialShare() {
            return {
                isOpen: false,
                pageUrl: window.location.href,
                copied: false,
                init() {
                    document.addEventListener('click', (e) => {
                        if (!this.$el.contains(e.target)) {
                            this.isOpen = false;
                        }
                    });
                },
                copyLink() {
                    navigator.clipboard.writeText(this.pageUrl).then(() => {
                        this.copied = true;
                        setTimeout(() => { this.copied = false; }, 2000);
                    });
                },
            };
        }
    </script>
</body>
</html>
