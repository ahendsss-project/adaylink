<div>
    {{-- Flash --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
             class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
             class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Paket Tour</h3>
            {{-- Quota Badge --}}
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xs {{ $quotaExceeded ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                    {{ $currentCount }} / {{ $maxTours }} paket tour
                </span>
                @if ($quotaExceeded)
                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Limit tercapai</span>
                @endif
            </div>
        </div>
        @if ($quotaExceeded)
            <button disabled
               class="inline-flex items-center gap-1.5 bg-gray-300 text-gray-500 text-sm font-medium px-4 py-2 rounded-lg cursor-not-allowed"
               title="Limit paket tercapai">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah
            </button>
        @else
            <a href="{{ route('driver.tours.create') }}"
               class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah
            </a>
        @endif
    </div>

    {{-- Tour List --}}
    @if ($tours->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945" />
            </svg>
            <p class="text-gray-500 text-sm">Belum ada paket tour. Klik "Tambah" untuk menambahkan.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($tours as $tour)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                @if ($tour->thumbnail_url)
                                    <img src="{{ $tour->thumbnail_url }}" alt="{{ $tour->title }}" class="w-10 h-10 rounded-lg object-cover shrink-0" />
                                @endif
                                <h4 class="font-medium text-gray-800">{{ $tour->title }}</h4>
                                @if ($tour->is_featured)
                                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">⭐ Featured</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5 font-mono truncate">{{ $tour->slug }}</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @if ($tour->price_start_from)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">
                                        Mulai Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}
                                    </span>
                                @endif
                                @if ($tour->duration_text)
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                                        🕐 {{ $tour->duration_text }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 shrink-0 ml-2">
                            <a href="{{ route('driver.tours.edit', $tour->id) }}"
                               class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <button wire:click="delete('{{ $tour->id }}')" wire:confirm="Hapus paket tour {{ $tour->title }}?"
                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
