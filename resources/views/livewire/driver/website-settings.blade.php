<div>
    {{-- Flash --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
             class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
             class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Template Selection --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800"><i class="fas fa-palette mr-2" style="color: var(--brand)"></i>Pilih Template</h3>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background: var(--brand-light); color: var(--brand-dark)">
                    Paket: {{ $allowedTier === 'All' ? 'Semua Tier' : $allowedTier }}
                </span>
            </div>

            @php
                $canAccessPremium = $allowedTier === 'All' || $allowedTier === 'Premium';
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($templates as $template)
                    @php
                        $isLocked = $template->tier === 'Premium' && !$canAccessPremium;
                    @endphp

                    @if ($isLocked)
                        <div class="relative border-2 rounded-xl p-4 text-center border-gray-200 bg-gray-50 opacity-75 cursor-not-allowed">
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center gap-1 text-xs bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full">
                                    <i class="fas fa-lock text-[10px]"></i> Premium
                                </span>
                            </div>
                            @if ($template->thumbnail_url)
                                <img src="{{ upload_url($template->thumbnail_url) }}" alt="{{ $template->name }}"
                                     class="w-full h-20 object-cover rounded-lg mb-2 grayscale" />
                            @else
                                <div class="w-full h-20 bg-gray-200 rounded-lg mb-2 flex items-center justify-center">
                                    <i class="fas fa-image text-2xl text-gray-300"></i>
                                </div>
                            @endif
                            <p class="text-sm font-medium text-gray-400">{{ $template->name }}</p>
                            <p class="text-xs text-amber-600 mt-1 font-medium">Upgrade paket untuk membuka</p>
                        </div>
                    @else
                        <label class="cursor-pointer group relative">
                            <input type="radio" wire:model="template_id" value="{{ $template->id }}" class="hidden peer" />
                            <div class="border-2 rounded-xl p-4 text-center transition peer-checked:border-[var(--brand)] peer-checked:bg-[var(--brand-light)] peer-checked:shadow-md @error('template_id') border-red-300 @enderror border-gray-200 hover:border-gray-300 group-hover:border-gray-300">
                                @if ($template->tier === 'Premium')
                                    <div class="mb-2">
                                        <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">⭐ Premium</span>
                                    </div>
                                @endif
                                @if ($template->thumbnail_url)
                                    <img src="{{ upload_url($template->thumbnail_url) }}" alt="{{ $template->name }}"
                                         class="w-full h-20 object-cover rounded-lg mb-2" />
                                @else
                                    <div class="w-full h-20 bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                                        <i class="fas fa-image text-2xl text-gray-300"></i>
                                    </div>
                                @endif
                                <p class="text-sm font-medium text-gray-700">{{ $template->name }}</p>
                                @if ($template->folder_name)
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $template->folder_name }}</p>
                                @endif
                            </div>
                            <a href="{{ route('demo.template', $template->folder_name) }}" target="_blank" rel="noopener"
                               onclick="event.stopPropagation()"
                               class="absolute top-2 right-2 text-xs bg-white/90 hover:bg-white text-gray-600 hover:text-[var(--brand)] px-2 py-1 rounded-md shadow-sm border border-gray-200 transition z-10"
                               title="Preview template">
                                <i class="fas fa-external-link-alt mr-0.5"></i> Demo
                            </a>
                        </label>
                    @endif
                @endforeach

                @if ($templates->isEmpty())
                    <p class="text-sm text-gray-400 col-span-full">Belum ada template tersedia.</p>
                @endif
            </div>

            @error('template_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Brand & Contact Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-id-card mr-2" style="color: var(--brand)"></i>Identitas & Kontak</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Judul Website <span class="text-gray-400 text-xs">(Site Title)</span></label>
                    <input type="text" wire:model="site_title"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color: var(--brand)"
                           placeholder="Nama website Anda, contoh: Bali Tour Driver" />
                    @error('site_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Nama yang tampil di navbar, footer, dan judul tab browser</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Logo URL</label>
                    <input type="text" wire:model="logo_url"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color: var(--brand)"
                           placeholder="https://example.com/logo.png" />
                    @error('logo_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @if ($logo_url)
                        <div class="mt-2">
                            <img src="{{ $logo_url }}" alt="Logo Preview" class="h-12 w-12 rounded-lg object-cover border border-gray-200" />
                        </div>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">Masukkan URL gambar logo Anda (PNG/JPG, rasio 1:1 disarankan)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nomor WhatsApp</label>
                    <div class="flex items-center gap-2">
                        <span class="bg-gray-100 border border-r-0 border-gray-300 px-3 py-2 rounded-l-lg text-sm text-gray-500">+62</span>
                        <input type="text" wire:model="contact_whatsapp"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color: var(--brand)"
                               placeholder="8123456789" />
                    </div>
                    @error('contact_whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Nomor WhatsApp yang tampil di website (tanpa +62, contoh: 8123456789)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Bahasa Utama Website</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition {{ $default_locale === 'id' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model="default_locale" value="id" class="sr-only" />
                            <span class="text-2xl">🇮🇩</span>
                            <div>
                                <span class="text-sm font-medium text-gray-800">Indonesia</span>
                                <p class="text-xs text-gray-500">Bahasa Indonesia</p>
                            </div>
                        </label>
                        <label class="relative flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition {{ $default_locale === 'en' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model="default_locale" value="en" class="sr-only" />
                            <span class="text-2xl">🇬🇧</span>
                            <div>
                                <span class="text-sm font-medium text-gray-800">English</span>
                                <p class="text-xs text-gray-500">International</p>
                            </div>
                        </label>
                    </div>
                    @error('default_locale') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Bahasa default untuk konten website Anda. Fitur multi-bahasa harus diaktifkan di paket Anda.</p>
                </div>
            </div>
        </div>

        {{-- Tampilan & Warna (Appearance & Colors) --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-swatchbook mr-2" style="color: var(--brand)"></i>Tampilan & Warna</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Warna Utama (Primary)</label>
                    <div class="flex items-center gap-3">
                        <input type="color" wire:model="primary_color" class="w-12 h-10 rounded border border-gray-300 cursor-pointer" />
                        <input type="text" wire:model="primary_color" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" />
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Warna utama untuk tombol, aksen, dan elemen brand</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Warna Sekunder (Secondary)</label>
                    <div class="flex items-center gap-3">
                        <input type="color" wire:model="secondary_color" class="w-12 h-10 rounded border border-gray-300 cursor-pointer" />
                        <input type="text" wire:model="secondary_color" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" />
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Warna pendukung untuk elemen sekunder</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Font Heading</label>
                    <select wire:model="font_heading" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--brand)">
                        <option value="Inter">Inter</option>
                        <option value="Playfair Display">Playfair Display</option>
                        <option value="DM Sans">DM Sans</option>
                        <option value="Poppins">Poppins</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Lora">Lora</option>
                        <option value="Raleway">Raleway</option>
                        <option value="Open Sans">Open Sans</option>
                        <option value="Nunito">Nunito</option>
                        <option value="Merriweather">Merriweather</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Font untuk judul (h1, h2, h3)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Font Body</label>
                    <select wire:model="font_body" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--brand)">
                        <option value="Inter">Inter</option>
                        <option value="Playfair Display">Playfair Display</option>
                        <option value="DM Sans">DM Sans</option>
                        <option value="Poppins">Poppins</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Lora">Lora</option>
                        <option value="Raleway">Raleway</option>
                        <option value="Open Sans">Open Sans</option>
                        <option value="Nunito">Nunito</option>
                        <option value="Merriweather">Merriweather</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Font untuk teks paragraf dan konten</p>
                </div>
            </div>

            {{-- Live Preview --}}
            <div class="mt-5 p-4 rounded-lg border border-gray-100 bg-gray-50">
                <p class="text-xs font-medium text-gray-500 mb-3"><i class="fas fa-eye mr-1"></i> Pratinjau Live</p>
                <div wire:ignore>
                    <link id="preview-font-link" href="" rel="stylesheet"/>
                </div>
                <div id="font-preview-area">
                    <h4 id="preview-heading" class="text-xl font-bold text-gray-800 mb-1">Contoh Judul Heading</h4>
                    <p id="preview-body" class="text-sm text-gray-600">Contoh teks body untuk paragraf. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <script>
                document.addEventListener('livewire:initialized', () => {
                    const updatePreview = () => {
                        const heading = document.getElementById('preview-heading');
                        const body = document.getElementById('preview-body');
                        const link = document.getElementById('preview-font-link');
                        if (!heading || !body) return;

                        const headingFont = document.querySelector('select[wire\\:model="font_heading"]')?.value || 'Inter';
                        const bodyFont = document.querySelector('select[wire\\:model="font_body"]')?.value || 'Inter';

                        heading.style.fontFamily = `'${headingFont}', serif`;
                        body.style.fontFamily = `'${bodyFont}', sans-serif`;

                        // Update Google Fonts link for preview
                        if (link) {
                            const fonts = [headingFont, bodyFont].filter((v, i, a) => a.indexOf(v) === i);
                            link.href = `https://fonts.googleapis.com/css2?family=${fonts.map(f => encodeURIComponent(f) + ':wght@400;500;600;700').join('&family=')}&display=swap`;
                        }
                    };

                    // Listen for changes on the selects
                    document.querySelectorAll('select[wire\\:model="font_heading"], select[wire\\:model="font_body"]').forEach(el => {
                        el.addEventListener('change', updatePreview);
                    });
                    updatePreview();
                });
            </script>
        </div>

        {{-- Hero Section --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-panorama mr-2" style="color: var(--brand)"></i>Hero / Banner</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Judul Hero</label>
                    <input type="text" wire:model="hero_title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Selamat Datang di Website Kami" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Subjudul Hero</label>
                    <textarea wire:model="hero_subtitle" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Nikmati perjalanan terbaik bersama kami..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">URL Gambar Hero</label>
                    <input type="text" wire:model="hero_image_url" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="https://example.com/banner.jpg" />
                    @if ($hero_image_url)
                        <div class="mt-2">
                            <img src="{{ $hero_image_url }}" alt="Hero Preview" class="h-24 rounded-lg object-cover border border-gray-200" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Gallery Images --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-images mr-2" style="color: var(--brand)"></i>Galeri Website</h3>
            <p class="text-sm text-gray-500 mb-4">Tambahkan gambar untuk galeri website Anda. Gambar dari Paket Tour dan Armada akan otomatis ditampilkan.</p>

            @if (session()->has('gallery_error'))
                <div class="mb-3 bg-red-50 border border-red-200 text-red-600 px-3 py-2 rounded-lg text-sm">
                    {{ session('gallery_error') }}
                </div>
            @endif

            {{-- Add Image --}}
            <div class="flex gap-2 mb-4">
                <input type="text" wire:model="new_gallery_image"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                       style="--tw-ring-color: var(--brand)"
                       placeholder="https://example.com/gambar.jpg" />
                <button type="button" wire:click="addGalleryImage"
                        class="shrink-0 inline-flex items-center gap-1.5 text-white text-sm font-medium px-4 py-2 rounded-lg transition hover:opacity-90"
                        style="background: var(--brand)">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>

            {{-- Gallery Grid --}}
            @if (!empty($gallery_images))
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach ($gallery_images as $index => $imageUrl)
                        <div class="relative group">
                            <img src="{{ $imageUrl }}" alt="Gallery {{ $index + 1 }}"
                                 class="w-full h-28 object-cover rounded-lg border border-gray-200" />
                            <button type="button" wire:click="removeGalleryImage({{ $index }})"
                                    class="absolute top-1 right-1 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <i class="fas fa-images text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada gambar galeri. Tambahkan URL gambar di atas.</p>
                </div>
            @endif
        </div>

        {{-- SEO --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-magnifying-glass mr-2" style="color: var(--brand)"></i>SEO</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Meta Title</label>
                    <input type="text" wire:model="seo_meta_title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Title untuk search engine" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Meta Description</label>
                    <textarea wire:model="seo_meta_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Deskripsi singkat untuk search engine..."></textarea>
                </div>
            </div>
        </div>

        {{-- Custom Domain --}}
        @if ($customDomainEnabled)
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-globe mr-2" style="color: var(--brand)"></i>Custom Domain
                </h3>
                @if ($customDomainVerified)
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                        <i class="fas fa-check-circle"></i> Terverifikasi
                    </span>
                @elseif ($custom_domain && !$customDomainVerified)
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700">
                        <i class="fas fa-exclamation-triangle"></i> Belum Diverifikasi
                    </span>
                @endif
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Domain Custom</label>
                    <div class="flex items-center gap-2">
                        <input type="text" wire:model="custom_domain"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color: var(--brand)"
                               placeholder="tour.bali-anda.com" />
                        @if ($custom_domain && !$customDomainVerified)
                            <button type="button" wire:click="removeCustomDomain"
                                    class="shrink-0 inline-flex items-center gap-1 text-red-600 hover:text-red-700 text-sm font-medium px-3 py-2 rounded-lg border border-red-200 hover:bg-red-50 transition"
                                    title="Hapus domain">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endif
                    </div>
                    @error('custom_domain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">
                        Masukkan domain Anda tanpa http:// atau https:// (contoh: <code class="bg-gray-100 px-1 rounded">tour.bali-anda.com</code>)
                    </p>
                </div>

                @if ($custom_domain && $customDomainDnsToken && !$customDomainVerified)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-blue-800 mb-3">
                            <i class="fas fa-info-circle mr-1"></i> Instruksi Verifikasi DNS
                        </h4>
                        <p class="text-xs text-blue-700 mb-3">
                            Untuk menghubungkan domain Anda, tambahkan record DNS berikut di panel DNS domain Anda:
                        </p>

                        <div class="space-y-3">
                            {{-- CNAME Record --}}
                            <div class="bg-white rounded-lg p-3 border border-blue-100">
                                <p class="text-xs font-semibold text-gray-700 mb-2">1. CNAME Record</p>
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div>
                                        <span class="text-gray-500">Type:</span>
                                        <code class="block bg-gray-50 px-2 py-1 rounded mt-0.5 font-mono text-gray-800">CNAME</code>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Name:</span>
                                        @php $namePart = str_contains($custom_domain, '.') ? explode('.', $custom_domain)[0] : '@'; @endphp
                                        <code class="block bg-gray-50 px-2 py-1 rounded mt-0.5 font-mono text-gray-800">{{ $namePart }}</code>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Value:</span>
                                        <code class="block bg-gray-50 px-2 py-1 rounded mt-0.5 font-mono text-gray-800">{{ parse_url(config('app.url'), PHP_URL_HOST) ?: 'adaylink.com' }}</code>
                                    </div>
                                </div>
                            </div>

                            {{-- TXT Record --}}
                            <div class="bg-white rounded-lg p-3 border border-blue-100">
                                <p class="text-xs font-semibold text-gray-700 mb-2">2. TXT Record (untuk verifikasi)</p>
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div>
                                        <span class="text-gray-500">Type:</span>
                                        <code class="block bg-gray-50 px-2 py-1 rounded mt-0.5 font-mono text-gray-800">TXT</code>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Name:</span>
                                        <code class="block bg-gray-50 px-2 py-1 rounded mt-0.5 font-mono text-gray-800">{{ $namePart }}</code>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Value:</span>
                                        <code class="block bg-gray-50 px-2 py-1 rounded mt-0.5 font-mono text-gray-800 break-all">{{ $customDomainDnsToken }}</code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <button type="button" wire:click="verifyDomain"
                                    class="inline-flex items-center gap-1.5 text-white text-sm font-medium px-4 py-2 rounded-lg transition hover:opacity-90"
                                    style="background: var(--brand)">
                                <i class="fas fa-check-double"></i> Verifikasi Domain
                            </button>
                            <span class="text-xs text-blue-600">
                                <i class="fas fa-clock mr-1"></i> DNS membutuhkan waktu beberapa menit hingga beberapa jam untuk propagate
                            </span>
                        </div>
                    </div>
                @elseif ($customDomainVerified && $custom_domain)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-medium text-green-800">Domain Aktif</p>
                                <p class="text-xs text-green-600 mt-1">
                                    Domain <strong>{{ $custom_domain }}</strong> sudah terverifikasi dan aktif.
                                    Pengunjung dapat mengakses website Anda melalui domain tersebut.
                                </p>
                                <a href="https://{{ $custom_domain }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1 text-xs text-green-700 hover:text-green-800 font-medium mt-2">
                                    <i class="fas fa-external-link-alt"></i> Kunjungi {{ $custom_domain }}
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1 text-gray-400"></i>
                            Masukkan domain custom Anda di atas, lalu simpan pengaturan. Setelah itu, Anda akan mendapatkan instruksi untuk mengatur DNS dan verifikasi domain.
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @else
        {{-- Custom Domain locked (feature not available in plan) --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 opacity-75">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-globe mr-2" style="color: var(--brand)"></i>Custom Domain
                </h3>
                <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-gray-200 text-gray-500">
                    <i class="fas fa-lock text-[10px]"></i> Tidak Tersedia
                </span>
            </div>
            <p class="text-sm text-gray-400">Fitur Custom Domain tersedia di paket yang lebih tinggi. Upgrade paket Anda untuk menggunakan domain sendiri.</p>
        </div>
        @endif

        {{-- Submit --}}
        <button type="submit" wire:loading.attr="disabled"
                class="w-full md:w-auto text-white font-medium py-2.5 px-6 rounded-lg transition hover:opacity-90 disabled:opacity-50"
                style="background: var(--brand)">
            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
        </button>
    </form>
</div>
