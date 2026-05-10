<div>
    {{-- Back Button --}}
    <a href="{{ route('driver.pages.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-4">
        <i class="fa-solid fa-arrow-left text-xs"></i>
        Kembali ke Daftar Halaman
    </a>

    {{-- Quota Exceeded Warning --}}
    @if ($quotaExceeded && ! $isEditing)
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            ⚠️ Limit halaman tercapai ({{ $currentCount }} / {{ $maxPages }}). Silakan upgrade paket Anda untuk menambah halaman baru.
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-5 md:p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            {{ $isEditing ? 'Edit Halaman' : 'Tambah Halaman Baru' }}
        </h3>

        <form wire:submit="save" class="space-y-5">
            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-600 mb-1">Judul Halaman *</label>
                <input type="text" wire:model.live="title" id="title"
                       placeholder="Contoh: Tentang Kami"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#40ac98] focus:border-[#40ac98] outline-none" />
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Slug --}}
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-600 mb-1">Slug (URL) *</label>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-400 shrink-0">/page/</span>
                    <input type="text" wire:model="slug" id="slug"
                           placeholder="tentang-kami"
                           class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#40ac98] focus:border-[#40ac98] outline-none {{ $isEditing ? 'bg-gray-50' : '' }}"
                           {{ $isEditing ? '' : '' }} />
                </div>
                @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @if (! $isEditing)
                    <p class="text-xs text-gray-400 mt-1">Slug akan otomatis di-generate dari judul.</p>
                @endif
            </div>

            {{-- Sort Order --}}
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-600 mb-1">Urutan</label>
                <input type="number" wire:model="sort_order" id="sort_order" min="0"
                       placeholder="0"
                       class="w-full md:w-48 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#40ac98] focus:border-[#40ac98] outline-none" />
                @error('sort_order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Angka lebih kecil = tampil lebih dulu.</p>
            </div>

            {{-- Content --}}
            <div>
                <label for="content" class="block text-sm font-medium text-gray-600 mb-1">Konten Halaman</label>
                <textarea wire:model="content" id="content" rows="10"
                          placeholder="Tulis konten halaman di sini..."
                          class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#40ac98] focus:border-[#40ac98] outline-none resize-y"></textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Published Toggle --}}
            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="is_published" class="sr-only peer" />
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#40ac98] rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#40ac98]"></div>
                </label>
                <div>
                    <span class="text-sm font-medium text-gray-700">Publikasikan</span>
                    <p class="text-xs text-gray-400">Halaman akan terlihat di website Anda.</p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" wire:loading.attr="disabled"
                        class="text-white font-medium py-2.5 px-6 rounded-lg transition text-sm disabled:opacity-50"
                        style="background: var(--brand)">
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Halaman' }}
                </button>
                <a href="{{ route('driver.pages.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
