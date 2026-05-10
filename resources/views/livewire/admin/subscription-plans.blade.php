<div>
    {{-- Flash --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
             class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Subscription Plans</h3>
        <a href="{{ route('admin.plans.create') }}"
           class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Paket
        </a>
    </div>

    {{-- Plans Grid --}}
    @if ($plans->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <p class="text-gray-500 text-sm">Belum ada paket langganan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($plans as $plan)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    {{-- Header --}}
                    <div class="p-5 {{ $plan->is_active ? 'bg-indigo-50' : 'bg-gray-50' }}">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-gray-800 text-lg">{{ $plan->name }}</h4>
                            <button wire:click="toggleActive({{ $plan->id }})"
                                    class="text-xs font-medium px-2.5 py-1 rounded-full transition {{ $plan->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-200 text-gray-500 hover:bg-gray-300' }}">
                                {{ $plan->is_active ? '✅ Active' : '⏸ Inactive' }}
                            </button>
                        </div>
                        <p class="text-2xl font-bold mt-2" style="color: {{ $plan->is_active ? '#4F46E5' : '#9CA3AF' }}">
                            Rp {{ number_format($plan->price, 0, ',', '.') }}
                            <span class="text-sm font-normal text-gray-400">/bulan</span>
                        </p>
                    </div>

                    {{-- Details --}}
                    <div class="p-5 space-y-2">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span class="text-green-500">✓</span>
                            <span>Maks. <strong>{{ $plan->max_tours }}</strong> paket tour</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span class="text-green-500">✓</span>
                            <span>Maks. <strong>{{ $plan->max_vehicles }}</strong> armada</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span class="text-green-500">✓</span>
                            <span>Maks. <strong>{{ $plan->max_pages }}</strong> halaman</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span class="text-green-500">✓</span>
                            <span>Template tier: <strong>{{ $plan->allowed_template_tier }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span class="text-green-500">✓</span>
                            <span>{{ $plan->users()->count() }} subscriber</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="px-5 py-3 border-t border-gray-100 flex items-center gap-2">
                        <a href="{{ route('admin.plans.edit', $plan->id) }}"
                           class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Edit</a>
                        <span class="text-gray-300">|</span>
                        <button wire:click="delete({{ $plan->id }})" wire:confirm="Hapus paket {{ $plan->name }}?"
                                class="text-sm text-red-500 hover:text-red-700 font-medium">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
