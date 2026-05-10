<div>
    {{-- Success Flash --}}
    @if (session()->has('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition
            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2"
        >
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Header Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingUsers->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\User::where('subscription_status', 'Active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Expired</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\User::where('subscription_status', 'Expired')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending List --}}
    @if ($pendingUsers->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-500 mb-1">Tidak Ada Pending</h3>
            <p class="text-sm text-gray-400">Semua akun driver sudah diproses.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Daftar Driver Pending ({{ $pendingUsers->count() }})</h3>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach ($pendingUsers as $user)
                    <div class="px-6 py-5 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            {{-- User Info --}}
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 font-bold text-lg">{{ strtoupper(substr($user->full_name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">{{ $user->full_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    <div class="flex items-center gap-3 mt-1">
                                        @if ($user->phone)
                                            <span class="text-xs text-gray-400">{{ $user->phone }}</span>
                                        @endif
                                        <span class="text-xs text-gray-400">
                                            Daftar: {{ $user->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Website & Transaction Info --}}
                            <div class="flex items-center gap-8">
                                @php
                                    $website = $user->websites->first();
                                    $transaction = $user->transactions->where('status', 'Pending')->first();
                                @endphp

                                @if ($website)
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-700">{{ $website->subdomain }}.adaylink.com</p>
                                        <p class="text-xs text-gray-400">{{ $website->site_name }}</p>
                                    </div>
                                @else
                                    <div class="text-right">
                                        <p class="text-sm text-gray-400 italic">Belum klaim subdomain</p>
                                    </div>
                                @endif

                                @if ($transaction)
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">ID Transaksi</p>
                                        <p class="text-xs font-mono text-gray-600">{{ \Illuminate\Support\Str::limit($transaction->id, 8, '') }}</p>
                                    </div>
                                @endif

                                {{-- Status Badge --}}
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Pending
                                </span>

                                {{-- Approve Button --}}
                                <button
                                    wire:click="approve('{{ $user->id }}')"
                                    wire:confirm="Apakah Anda yakin ingin mengaktifkan akun {{ $user->full_name }}?"
                                    class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition duration-200 shadow-sm"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Approve
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
