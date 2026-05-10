<div>
    {{-- Back Button --}}
    <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Manage Templates
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6 max-w-2xl">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            {{ $isEditing ? 'Edit Template' : 'Tambah Template Baru' }}
        </h3>

        <form wire:submit="save" class="space-y-5">
            {{-- Template Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-600 mb-1">Nama Template *</label>
                <input type="text" wire:model="name" id="name"
                       placeholder="Contoh: Bali Minimalist"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Folder Name --}}
            <div>
                <label for="folder_name" class="block text-sm font-medium text-gray-600 mb-1">Folder Name *</label>
                <div class="flex items-center">
                    <span class="bg-gray-100 border border-r-0 border-gray-300 px-3 py-2.5 rounded-l-lg text-sm text-gray-400 whitespace-nowrap font-mono">
                        views/templates/
                    </span>
                    <input type="text" wire:model="folder_name" id="folder_name"
                           placeholder="bali-minimalist"
                           class="flex-1 px-3 py-2.5 border border-gray-300 rounded-r-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none font-mono" />
                </div>
                @error('folder_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-gray-400 text-xs mt-1">Nama folder fisik di <code>resources/views/templates/</code></p>
            </div>

            {{-- Tier --}}
            <div>
                <label for="tier" class="block text-sm font-medium text-gray-600 mb-1">Tier *</label>
                <select wire:model="tier" id="tier"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none bg-white">
                    <option value="Basic">Basic — Tersedia untuk semua paket</option>
                    <option value="Premium">Premium — Hanya untuk paket Pro Agent+</option>
                </select>
                @error('tier') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Thumbnail --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Thumbnail Preview</label>

                {{-- Current thumbnail preview --}}
                @if ($thumbnail_url)
                    <div class="mb-3">
                        <img src="{{ upload_url($thumbnail_url) }}" alt="Preview" class="w-48 h-32 rounded-lg object-cover border border-gray-200" />
                        <p class="text-xs text-gray-400 mt-1">Thumbnail saat ini</p>
                    </div>
                @endif

                {{-- Upload new thumbnail --}}
                <div class="flex items-center gap-3">
                    <label class="flex-1 cursor-pointer">
                        <div class="border-2 border-dashed border-gray-300 hover:border-indigo-400 rounded-lg px-4 py-3 text-center transition">
                            <svg class="w-6 h-6 text-gray-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs text-gray-500">Klik untuk upload gambar baru</p>
                            <input type="file" wire:model="thumbnail_image" accept="image/*" class="hidden" />
                        </div>
                    </label>
                </div>
                @error('thumbnail_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                {{-- Upload preview --}}
                @if ($thumbnail_image)
                    <div class="mt-3">
                        <img src="{{ $thumbnail_image->temporaryUrl() }}" alt="Preview" class="w-48 h-32 rounded-lg object-cover border border-gray-200" />
                    </div>
                @endif
            </div>

            {{-- Active Toggle --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" wire:model="is_active" id="is_active"
                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                <label for="is_active" class="text-sm font-medium text-gray-600">Aktifkan template (tersedia untuk dipilih tenant)</label>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" wire:loading.attr="disabled"
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium py-2.5 px-6 rounded-lg transition text-sm">
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Template' }}
                </button>
                <a href="{{ route('admin.templates.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
