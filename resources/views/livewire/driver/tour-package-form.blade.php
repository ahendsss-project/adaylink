<div x-data="{ showGallery: false }">
    {{-- Back Button --}}
    <a href="{{ route('driver.tours.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Paket Tour
    </a>

    {{-- Quota Exceeded Warning --}}
    @if ($quotaExceeded)
        <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-4">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <div>
                    <h4 class="font-semibold text-red-800">Limit Paket Tercapai</h4>
                    <p class="text-sm text-red-600 mt-1">Anda sudah mencapai batas maksimal <strong>{{ $maxTours }} paket tour</strong> untuk paket Anda. Silakan upgrade paket untuk menambah tour.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-5 md:p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            {{ $isEditing ? 'Edit Paket Tour' : 'Tambah Paket Tour Baru' }}
        </h3>

        {{-- Quota Info --}}
        <div class="flex items-center gap-2 mb-5 text-xs text-gray-500">
            <span>Kuota: {{ $currentCount }} / {{ $maxTours }} paket tour</span>
        </div>

        <form wire:submit="save" class="space-y-5">
            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-600 mb-1">Judul Paket Tour *</label>
                <input type="text" wire:model.live="title" id="title"
                       placeholder="Contoh: Paket Tour Nusa Penida 2 Hari"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Slug --}}
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-600 mb-1">Slug URL (SEO)</label>
                <div class="flex items-center">
                    <span class="bg-gray-100 border border-r-0 border-gray-300 px-3 py-2.5 rounded-l-lg text-sm text-gray-400 whitespace-nowrap">
                        /paket/
                    </span>
                    <input type="text" wire:model="slug" id="slug"
                           placeholder="paket-tour-nusa-penida"
                           class="flex-1 px-3 py-2.5 border border-gray-300 rounded-r-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none font-mono" />
                </div>
                @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @if ($slug && !$errors->has('slug'))
                    <p class="text-green-600 text-xs mt-1">✅ Slug akan menjadi: /paket/{{ $slug }}</p>
                @endif
            </div>

            {{-- Price & Duration --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="price_start_from" class="block text-sm font-medium text-gray-600 mb-1">Harga Mulai Dari (Rp)</label>
                    <input type="number" wire:model="price_start_from" id="price_start_from" min="0" step="10000"
                           placeholder="500000"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                    @error('price_start_from') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="duration_text" class="block text-sm font-medium text-gray-600 mb-1">Durasi</label>
                    <input type="text" wire:model="duration_text" id="duration_text"
                           placeholder="2 Hari 1 Malam"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                    @error('duration_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Paket Tour</label>
                <textarea wire:model="description" rows="6"
                          placeholder="Tulis deskripsi paket tour Anda di sini...&#10;&#10;Contoh:&#10;Paket tour Nusa Penida 2 hari 1 malam termasuk:&#10;- Transportasi PP AC&#10;- Penginapan 1 malam&#10;- Makan sesuai program&#10;- Guide lokal berpengalaman"
                          class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none resize-y"></textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-gray-400 text-xs mt-1">Tulis deskripsi lengkap tentang paket tour Anda.</p>
            </div>

            {{-- Thumbnail URL with Gallery Picker --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Thumbnail / Gambar Utama</label>
                <div class="flex gap-2">
                    <input type="text" wire:model="thumbnail_url" id="thumbnail_url"
                           placeholder="https://example.com/foto-tour.jpg"
                           class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                    <button type="button" @click="showGallery = true"
                            class="shrink-0 inline-flex items-center gap-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-sm font-medium px-3 py-2.5 rounded-lg border border-purple-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Galeri
                    </button>
                </div>
                @error('thumbnail_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                {{-- Thumbnail Preview --}}
                @if ($thumbnail_url)
                    <div class="mt-3">
                        <img src="{{ $thumbnail_url }}" alt="Preview" class="w-48 h-32 rounded-lg object-cover border border-gray-200" />
                    </div>
                @endif
            </div>

            {{-- Itinerary (Dynamic Day-by-Day) --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-medium text-gray-600">Itinerary / Rencana Perjalanan</label>
                    <button type="button" wire:click="addItineraryDay"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                        <i class="fas fa-plus"></i> Tambah Hari
                    </button>
                </div>

                <div class="space-y-3">
                    @foreach ($itinerary_items as $index => $item)
                        <div class="flex items-start gap-2">
                            <div class="shrink-0 w-16 pt-2">
                                <span class="inline-flex items-center justify-center w-full text-xs font-bold text-white py-1.5 rounded-lg"
                                      style="background: var(--brand)">
                                    Hari {{ $index + 1 }}
                                </span>
                            </div>
                            <input type="text" wire:model="itinerary_items.{{ $index }}"
                                   placeholder="Contoh: Visit Kelingking Beach, Snorkeling di Crystal Bay"
                                   class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                            @if (count($itinerary_items) > 1)
                                <button type="button" wire:click="removeItineraryDay({{ $index }})"
                                        class="shrink-0 w-9 h-9 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition mt-0.5"
                                        title="Hapus hari ini">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if (count($itinerary_items) === 0)
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada itinerary. Klik "Tambah Hari" untuk memulai.</p>
                @endif

                <p class="text-gray-400 text-xs mt-2">Tambahkan rencana perjalanan per hari. Kosongkan jika tidak diperlukan.</p>
            </div>

            {{-- Includes --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-medium text-gray-600">
                        <span class="inline-flex items-center gap-1"><i class="fas fa-check-circle text-green-500"></i> Termasuk (Include)</span>
                    </label>
                    <button type="button" wire:click="addIncludeItem"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                        <i class="fas fa-plus"></i> Tambah Item
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach ($include_items as $index => $item)
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500 text-xs mt-0.5"></i>
                            <input type="text" wire:model="include_items.{{ $index }}"
                                   placeholder="Contoh: Transportasi AC, Makan sesuai program"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                            @if (count($include_items) > 1)
                                <button type="button" wire:click="removeIncludeItem({{ $index }})"
                                        class="shrink-0 w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
                <p class="text-gray-400 text-xs mt-2">Hal-hal yang sudah termasuk dalam harga paket.</p>
            </div>

            {{-- Excludes --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-medium text-gray-600">
                        <span class="inline-flex items-center gap-1"><i class="fas fa-times-circle text-red-400"></i> Tidak Termasuk (Exclude)</span>
                    </label>
                    <button type="button" wire:click="addExcludeItem"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                        <i class="fas fa-plus"></i> Tambah Item
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach ($exclude_items as $index => $item)
                        <div class="flex items-center gap-2">
                            <i class="fas fa-times text-red-400 text-xs mt-0.5"></i>
                            <input type="text" wire:model="exclude_items.{{ $index }}"
                                   placeholder="Contoh: Tiket pesawat, Pengeluaran pribadi"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                            @if (count($exclude_items) > 1)
                                <button type="button" wire:click="removeExcludeItem({{ $index }})"
                                        class="shrink-0 w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
                <p class="text-gray-400 text-xs mt-2">Hal-hal yang tidak termasuk dalam harga paket.</p>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    <span class="inline-flex items-center gap-1"><i class="fas fa-sticky-note text-amber-500"></i> Catatan / Notes</span>
                </label>
                <textarea wire:model="notes" rows="3"
                          placeholder="Catatan tambahan untuk paket tour ini...&#10;Contoh: Minimal 2 orang per booking, berlaku untuk periode low season"
                          class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none resize-y"></textarea>
                <p class="text-gray-400 text-xs mt-1">Informasi tambahan atau syarat & ketentuan paket tour.</p>
            </div>

            {{-- Featured --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" wire:model="is_featured" id="is_featured"
                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                <label for="is_featured" class="text-sm font-medium text-gray-600">Tandai sebagai Featured (⭐)</label>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" wire:loading.attr="disabled"
                        @if($quotaExceeded) disabled @endif
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium py-2.5 px-6 rounded-lg transition text-sm">
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Paket Tour' }}
                </button>
                <a href="{{ route('driver.tours.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Stock Image Gallery Modal --}}
    <div x-show="showGallery" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
         @click.self="showGallery = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Pilih Gambar dari Galeri</h3>
                <button @click="showGallery = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-5 overflow-y-auto max-h-[60vh]">
                @if ($stockImages->isEmpty())
                    <p class="text-gray-500 text-sm text-center py-8">Belum ada gambar stok untuk kategori Tour. Hubungi admin.</p>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($stockImages as $stockImage)
                            <button type="button"
                                    wire:click="selectStockImage('{{ $stockImage->image_url }}')"
                                    @click="showGallery = false"
                                    class="group relative rounded-lg overflow-hidden border-2 border-transparent hover:border-indigo-500 transition focus:border-indigo-500 focus:outline-none">
                                <img src="{{ upload_url($stockImage->image_url) }}" alt="{{ $stockImage->alt_text }}"
                                     class="w-full h-32 object-cover" />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                    <span class="text-white opacity-0 group-hover:opacity-100 transition text-sm font-medium">Pilih</span>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                                    <p class="text-white text-xs truncate">{{ $stockImage->title }}</p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
