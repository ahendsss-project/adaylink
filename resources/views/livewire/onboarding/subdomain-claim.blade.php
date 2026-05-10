<div>
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Klaim Subdomain Anda</h2>
        <p class="text-gray-500 mt-2">Pilih nama bisnis dan subdomain untuk website Anda.</p>
    </div>

    {{-- Error Flash --}}
    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form wire:submit="claim" class="space-y-6">
        {{-- Site Name --}}
        <div>
            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Bisnis / Website</label>
            <input
                wire:model="site_name"
                type="text"
                id="site_name"
                placeholder="Contoh: Adi Trans Bali"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
            />
            @error('site_name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Language Selection (Optional) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Bahasa Utama Website
                <span class="text-xs text-gray-400 font-normal ml-1">(opsional, bisa diubah nanti)</span>
            </label>
            <div class="grid grid-cols-2 gap-3">
                <label class="relative flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition {{ $default_locale === 'id' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" wire:model="default_locale" value="id" class="sr-only" />
                    <span class="text-2xl">🇮🇩</span>
                    <div>
                        <span class="text-sm font-medium text-gray-800">Indonesia</span>
                        <p class="text-xs text-gray-500">Bahasa Indonesia</p>
                    </div>
                </label>
                <label class="relative flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition {{ $default_locale === 'en' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" wire:model="default_locale" value="en" class="sr-only" />
                    <span class="text-2xl">🇬🇧</span>
                    <div>
                        <span class="text-sm font-medium text-gray-800">English</span>
                        <p class="text-xs text-gray-500">International</p>
                    </div>
                </label>
            </div>
            @error('default_locale')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Subdomain --}}
        <div>
            <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-1">Subdomain</label>
            <div class="flex items-center">
                <input
                    wire:model.live="subdomain"
                    type="text"
                    id="subdomain"
                    placeholder="adi-trans"
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                />
                <span class="bg-gray-100 border border-l-0 border-gray-300 px-4 py-3 rounded-r-lg text-gray-500 text-sm whitespace-nowrap">
                    .adaylink.com
                </span>
            </div>
            @error('subdomain')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @if ($subdomain && !$errors->has('subdomain'))
                <p class="text-green-600 text-xs mt-1">✅ {{ $subdomain }}.adaylink.com tersedia!</p>
            @endif
            <p class="text-gray-400 text-xs mt-1">Hanya huruf kecil, angka, dan tanda hubung (-). Min 3 karakter.</p>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium py-3 rounded-lg transition duration-200 flex items-center justify-center gap-2"
        >
            <svg wire:loading class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Klaim Subdomain & Lanjutkan</span>
        </button>
    </form>
</div>
