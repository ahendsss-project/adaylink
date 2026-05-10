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
            <h3 class="text-lg font-semibold text-gray-800">Daftar Halaman</h3>
            {{-- Quota Badge --}}
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xs {{ $quotaExceeded ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                    {{ $currentCount }} / {{ $maxPages }} halaman
                </span>
                @if ($quotaExceeded)
                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Limit tercapai</span>
                @endif
            </div>
        </div>
        @if ($quotaExceeded)
            <button disabled
               class="inline-flex items-center gap-1.5 bg-gray-300 text-gray-500 text-sm font-medium px-4 py-2 rounded-lg cursor-not-allowed"
               title="Limit halaman tercapai">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Halaman
            </button>
        @else
            <a href="{{ route('driver.pages.create') }}"
               class="inline-flex items-center gap-1.5 text-white text-sm font-medium px-4 py-2 rounded-lg transition"
               style="background: var(--brand)">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Halaman
            </a>
        @endif
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari halaman..."
               class="w-full md:w-80 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#40ac98] focus:border-[#40ac98] outline-none" />
    </div>

    {{-- Pages List --}}
    @if ($pages->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <i class="fa-solid fa-file-lines text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm">Belum ada halaman. Klik "Tambah Halaman" untuk menambahkan.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Judul</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Status</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Urutan</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($pages as $page)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $page->title }}</p>
                                        <p class="text-xs text-gray-400 font-mono truncate">{{ $page->slug }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($page->is_published)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                            <i class="fa-solid fa-circle text-[6px] mr-1"></i> Dipublikasi
                                        </span>
                                    @else
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                                            <i class="fa-solid fa-circle text-[6px] mr-1"></i> Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-600">{{ $page->sort_order }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('driver.pages.edit', $page->id) }}"
                                           class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                           title="Edit">
                                            <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                                        </a>
                                        <button wire:click="togglePublish('{{ $page->id }}')"
                                                class="p-2 rounded-lg transition {{ $page->is_published ? 'text-green-500 hover:bg-green-50' : 'text-gray-400 hover:text-green-600 hover:bg-green-50' }}"
                                                title="{{ $page->is_published ? 'Sembunyikan' : 'Publikasikan' }}">
                                            <i class="fa-solid {{ $page->is_published ? 'fa-eye' : 'fa-eye-slash' }} w-4 h-4"></i>
                                        </button>
                                        <button wire:click="delete('{{ $page->id }}')" wire:confirm="Hapus halaman {{ $page->title }}?"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Hapus">
                                            <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach ($pages as $page)
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-800 text-sm">{{ $page->title }}</h4>
                                <p class="text-xs text-gray-400 font-mono truncate">{{ $page->slug }}</p>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if ($page->is_published)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                            <i class="fa-solid fa-circle text-[6px] mr-1"></i> Dipublikasi
                                        </span>
                                    @else
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                                            <i class="fa-solid fa-circle text-[6px] mr-1"></i> Draft
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-1 shrink-0 ml-2">
                                <a href="{{ route('driver.pages.edit', $page->id) }}"
                                   class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                                </a>
                                <button wire:click="togglePublish('{{ $page->id }}')"
                                        class="p-2 rounded-lg transition {{ $page->is_published ? 'text-green-500 hover:bg-green-50' : 'text-gray-400 hover:text-green-600 hover:bg-green-50' }}">
                                    <i class="fa-solid {{ $page->is_published ? 'fa-eye' : 'fa-eye-slash' }} w-4 h-4"></i>
                                </button>
                                <button wire:click="delete('{{ $page->id }}')" wire:confirm="Hapus halaman {{ $page->title }}?"
                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $pages->links() }}
        </div>
    @endif
</div>
