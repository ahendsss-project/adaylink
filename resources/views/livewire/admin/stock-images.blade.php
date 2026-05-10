<div>
    {{-- Flash --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
             class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Stock Images</h3>
        <div class="flex items-center gap-3">
            {{-- Category Filter --}}
            <select wire:model.live="filterCategory"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            <a href="{{ route('admin.stock-images.create') }}"
               class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Upload Gambar
            </a>
        </div>
    </div>

    {{-- Images Grid --}}
    @if ($images->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-500 text-sm">Belum ada gambar. Klik "Upload Gambar" untuk menambahkan.</p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($images as $img)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden group">
                    <div class="aspect-square bg-gray-100 relative">
                        <img src="{{ upload_url($img->image_url) }}" alt="{{ $img->alt_text }}"
                             class="w-full h-full object-cover" />
                        {{-- Overlay on hover --}}
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                            <a href="{{ route('admin.stock-images.edit', $img->id) }}"
                               class="p-2 bg-white rounded-lg text-gray-700 hover:bg-indigo-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <button wire:click="delete({{ $img->id }})" wire:confirm="Hapus gambar ini?"
                                    class="p-2 bg-white rounded-lg text-red-500 hover:bg-red-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $img->title }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">{{ $img->category }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1 truncate" title="{{ $img->alt_text }}">{{ $img->alt_text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
