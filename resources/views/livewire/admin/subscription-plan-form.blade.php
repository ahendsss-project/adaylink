<div>
    <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Paket
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">{{ $isEditing ? 'Edit Paket' : 'Tambah Paket Baru' }}</h3>

        <form wire:submit="save" class="space-y-5">
            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Paket *</label>
                <input type="text" wire:model="name" placeholder="Contoh: Pro Agent"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Price --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Harga per Bulan (Rp) *</label>
                <input type="number" wire:model="price" min="0" step="1000" placeholder="50000"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Max Tours, Vehicles & Pages --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Maks. Paket Tour *</label>
                    <input type="number" wire:model="max_tours" min="0" placeholder="5"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                    @error('max_tours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Maks. Armada *</label>
                    <input type="number" wire:model="max_vehicles" min="0" placeholder="3"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                    @error('max_vehicles') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Maks. Halaman *</label>
                    <input type="number" wire:model="max_pages" min="0" placeholder="5"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                    @error('max_pages') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Template Access (legacy label, access now controlled per-template) --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Label Tier Template</label>
                <select wire:model="allowed_template_tier"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="Basic">Basic</option>
                    <option value="Premium">Premium</option>
                    <option value="All">All</option>
                </select>
                @error('allowed_template_tier') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-gray-400 text-xs mt-1">
                    <svg class="w-3.5 h-3.5 inline text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Akses template sekarang dikonfigurasi langsung di masing-masing template (Manage Templates → Edit Template → Dapat Diakses oleh Paket).
                </p>
            </div>

            {{-- Active --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" wire:model="is_active" id="is_active"
                       class="w-4 h-4 rounded border-gray-300 focus:ring-indigo-500" style="accent-color: var(--brand)" />
                <label for="is_active" class="text-sm font-medium text-gray-600">Tampilkan di halaman pendaftaran</label>
            </div>

            {{-- Feature Toggles --}}
            <div class="border-t border-gray-200 pt-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fa-solid fa-puzzle-piece mr-1" style="color: var(--brand)"></i>
                    Fitur Website
                </h4>
                <p class="text-xs text-gray-400 mb-4">Aktifkan fitur-fitur yang tersedia untuk paket ini pada website subdomain driver.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        <input type="checkbox" wire:model="feature_floating_whatsapp"
                               class="w-4 h-4 rounded border-gray-300 focus:ring-indigo-500" style="accent-color: var(--brand)" />
                        <div>
                            <span class="text-sm font-medium text-gray-700"><i class="fa-brands fa-whatsapp text-green-500 mr-1"></i> Floating WhatsApp</span>
                            <p class="text-xs text-gray-400">Tombol WhatsApp melayang di pojok kanan bawah</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        <input type="checkbox" wire:model="feature_social_share"
                               class="w-4 h-4 rounded border-gray-300 focus:ring-indigo-500" style="accent-color: var(--brand)" />
                        <div>
                            <span class="text-sm font-medium text-gray-700"><i class="fa-solid fa-share-nodes text-blue-500 mr-1"></i> Social Share</span>
                            <p class="text-xs text-gray-400">Tombol bagikan ke media sosial</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        <input type="checkbox" wire:model="feature_gallery_lightbox"
                               class="w-4 h-4 rounded border-gray-300 focus:ring-indigo-500" style="accent-color: var(--brand)" />
                        <div>
                            <span class="text-sm font-medium text-gray-700"><i class="fa-solid fa-images text-purple-500 mr-1"></i> Gallery (Lightbox)</span>
                            <p class="text-xs text-gray-400">Galeri foto dengan efek lightbox</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        <input type="checkbox" wire:model="feature_reviews"
                               class="w-4 h-4 rounded border-gray-300 focus:ring-indigo-500" style="accent-color: var(--brand)" />
                        <div>
                            <span class="text-sm font-medium text-gray-700"><i class="fa-solid fa-star text-amber-500 mr-1"></i> Review & Rating</span>
                            <p class="text-xs text-gray-400">Review pengunjung dengan Schema.org markup</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        <input type="checkbox" wire:model="feature_multilanguage"
                               class="w-4 h-4 rounded border-gray-300 focus:ring-indigo-500" style="accent-color: var(--brand)" />
                        <div>
                            <span class="text-sm font-medium text-gray-700"><i class="fa-solid fa-language text-cyan-500 mr-1"></i> Multi-Bahasa (Translate)</span>
                            <p class="text-xs text-gray-400">Fitur translate konten website ke bahasa lain</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        <input type="checkbox" wire:model="feature_custom_domain"
                               class="w-4 h-4 rounded border-gray-300 focus:ring-indigo-500" style="accent-color: var(--brand)" />
                        <div>
                            <span class="text-sm font-medium text-gray-700"><i class="fa-solid fa-globe text-purple-500 mr-1"></i> Custom Domain</span>
                            <p class="text-xs text-gray-400">Gunakan domain sendiri untuk website</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" wire:loading.attr="disabled"
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium py-2.5 px-6 rounded-lg transition text-sm">
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Paket' }}
                </button>
                <a href="{{ route('admin.plans.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
