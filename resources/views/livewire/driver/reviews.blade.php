<div>
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-star-half-stroke mr-2" style="color: var(--brand)"></i>Review Pelanggan
        </h1>
        <p class="text-gray-500 text-sm mt-1">Kelola review dari pengunjung website Anda</p>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: var(--brand-light)">
                    <i class="fas fa-comments" style="color: var(--brand)"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalReviews }}</p>
                    <p class="text-xs text-gray-500">Total Review</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-amber-50">
                    <i class="fas fa-clock text-amber-500"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingCount }}</p>
                    <p class="text-xs text-gray-500">Menunggu</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-green-50">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $approvedCount }}</p>
                    <p class="text-xs text-gray-500">Disetujui</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-amber-50">
                    <i class="fas fa-star text-amber-400"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $avgRating ? round($avgRating, 1) : '-' }}</p>
                    <p class="text-xs text-gray-500">Rata-rata</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Cari nama atau komentar..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                       style="--tw-ring-color: var(--brand)" />
            </div>
            <div class="flex gap-2">
                <select wire:model.live="filter"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                        style="--tw-ring-color: var(--brand)">
                    <option value="all">Semua</option>
                    <option value="pending">Menunggu</option>
                    <option value="approved">Disetujui</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Reviews Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @if ($reviews->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Reviewer</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Rating</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Komentar</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($reviews as $review)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                             style="background: var(--brand)">
                                            {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $review->reviewer_name }}</p>
                                            @if ($review->reviewer_email)
                                                <p class="text-xs text-gray-400">{{ $review->reviewer_email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-amber-400 text-xs"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td class="px-4 py-3 hidden md:table-cell">
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($review->comment, 100) }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($review->is_approved)
                                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full bg-green-50 text-green-700">
                                            <i class="fas fa-check-circle"></i> Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full bg-amber-50 text-amber-700">
                                            <i class="fas fa-clock"></i> Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @if (! $review->is_approved)
                                            <button wire:click="approve('{{ $review->id }}')"
                                                    wire:confirm="Setujui review ini?"
                                                    class="text-xs px-2.5 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition"
                                                    title="Setujui">
                                                <i class="fas fa-check mr-1"></i> Setujui
                                            </button>
                                        @else
                                            <button wire:click="unapprove('{{ $review->id }}')"
                                                    wire:confirm="Batalkan persetujuan review ini?"
                                                    class="text-xs px-2.5 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition"
                                                    title="Batal Setujui">
                                                <i class="fas fa-rotate-left mr-1"></i> Batal
                                            </button>
                                        @endif
                                        <button wire:click="delete('{{ $review->id }}')"
                                                wire:confirm="Hapus review ini secara permanen?"
                                                class="text-xs px-2.5 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $reviews->links() }}
            </div>
        @else
            <div class="py-12 text-center">
                <i class="far fa-comment-dots text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">
                    @if ($filter === 'pending')
                        Tidak ada review yang menunggu persetujuan.
                    @elseif ($filter === 'approved')
                        Belum ada review yang disetujui.
                    @else
                        Belum ada review dari pengunjung.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
