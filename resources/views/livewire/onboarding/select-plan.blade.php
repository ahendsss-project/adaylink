<div>
    {{-- Step Indicator --}}
    <div class="flex items-center justify-center mb-8">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold">✓</div>
            <span class="text-sm text-green-600 font-medium hidden sm:inline">Daftar Akun</span>
            <div class="w-8 h-0.5 bg-indigo-300"></div>
            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">2</div>
            <span class="text-sm text-indigo-600 font-medium hidden sm:inline">Pilih Paket</span>
            <div class="w-8 h-0.5 bg-gray-300"></div>
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-sm font-bold">3</div>
            <span class="text-sm text-gray-400 hidden sm:inline">Klaim Subdomain</span>
            <div class="w-8 h-0.5 bg-gray-300"></div>
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-sm font-bold">4</div>
            <span class="text-sm text-gray-400 hidden sm:inline">Pembayaran</span>
        </div>
    </div>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pilih Paket Langganan</h2>
        <p class="text-gray-500 mt-2">Pilih paket yang sesuai dengan kebutuhan Anda</p>
    </div>

    {{-- Plan Cards --}}
    @if ($plans->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500">Belum ada paket tersedia. Silakan hubungi admin.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($plans as $index => $plan)
                @php
                    $isPopular = $index === 1 && $plans->count() > 2;
                    $tierBadge = $plan->allowed_template_tier === 'All' ? 'Semua Template' : 'Template ' . $plan->allowed_template_tier;
                @endphp

                <div class="relative bg-white rounded-xl border-2 {{ $isPopular ? 'border-indigo-400 ring-1 ring-indigo-200' : 'border-gray-200' }} overflow-hidden">
                    @if ($isPopular)
                        <div class="bg-indigo-600 text-white text-center text-xs font-bold py-1 uppercase tracking-wider">
                            Paling Populer
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                                <div class="flex items-baseline gap-1 mt-1">
                                    <span class="text-sm text-gray-500">Rp</span>
                                    <span class="text-2xl font-extrabold text-gray-900">{{ number_format($plan->price, 0, ',', '.') }}</span>
                                    <span class="text-sm text-gray-500">/bulan</span>
                                </div>
                            </div>
                            <button
                                wire:click="selectPlan({{ $plan->id }})"
                                class="shrink-0 py-2.5 px-5 rounded-lg font-semibold text-sm transition {{ $isPopular
                                    ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/25'
                                    : 'bg-gray-900 hover:bg-gray-800 text-white'
                                }}"
                            >
                                Pilih {{ $plan->name }}
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-3 mt-3">
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                {{ $plan->max_tours }} Tour
                            </span>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                {{ $plan->max_vehicles }} Armada
                            </span>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                {{ $tierBadge }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
