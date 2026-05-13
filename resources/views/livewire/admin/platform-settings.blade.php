<div>
    {{-- Flash --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
             class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Brand & Logo --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">
                <i class="fas fa-palette mr-2" style="color: var(--brand)"></i>Brand & Logo
            </h3>
            <div class="space-y-4">
                {{-- App Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Aplikasi</label>
                    <input type="text" wire:model="app_name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color: var(--brand)"
                           placeholder="adaylink" />
                    @error('app_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Nama yang tampil di header, login, dan register</p>
                </div>

                {{-- Tagline --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tagline</label>
                    <input type="text" wire:model="tagline"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color: var(--brand)"
                           placeholder="Platform Website Tour Driver" />
                    @error('tagline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Logo Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Logo Platform</label>
                    <div class="space-y-3">
                        {{-- File Upload --}}
                        <div class="flex items-center gap-3">
                            <input type="file" wire:model="logo_file" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        </div>
                        @error('logo_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        {{-- OR --}}
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400">atau URL:</span>
                            <input type="text" wire:model="main_logo_url"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="--tw-ring-color: var(--brand)"
                                   placeholder="https://example.com/logo.png" />
                        </div>
                        @error('main_logo_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        {{-- Upload Progress --}}
                        <div wire:loading wire:target="logo_file">
                            <div class="flex items-center gap-2 text-sm text-blue-600">
                                <i class="fas fa-spinner fa-spin"></i> Mengupload...
                            </div>
                        </div>

                        {{-- Logo Preview --}}
                        @if ($main_logo_url)
                            <div class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <p class="text-xs text-gray-500 mb-2">Preview Logo:</p>
                                <div class="flex items-center gap-4">
                                    <img src="{{ $main_logo_url }}" alt="Logo" class="h-12 w-auto rounded" />
                                    <img src="{{ $main_logo_url }}" alt="Logo Small" class="h-8 w-auto rounded" />
                                    <img src="{{ $main_logo_url }}" alt="Logo Tiny" class="h-6 w-auto rounded" />
                                </div>
                            </div>
                        @endif

                        <p class="text-xs text-gray-400">Format: PNG/JPG/SVG. Disarankan rasio 1:1. Maks 2MB.</p>
                    </div>
                </div>

                {{-- Favicon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Favicon URL</label>
                    <input type="text" wire:model="favicon_url"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color: var(--brand)"
                           placeholder="https://example.com/favicon.ico" />
                    @error('favicon_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">
                <i class="fas fa-address-book mr-2" style="color: var(--brand)"></i>Kontak Admin
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">WhatsApp Admin</label>
                    <input type="text" wire:model="admin_whatsapp"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color: var(--brand)"
                           placeholder="628123456789" />
                    @error('admin_whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email Admin</label>
                    <input type="email" wire:model="admin_email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color: var(--brand)"
                           placeholder="admin@adaylink.com" />
                    @error('admin_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Analytics & Advanced --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">
                <i class="fas fa-cog mr-2" style="color: var(--brand)"></i>Pengaturan Lanjutan
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Google Analytics ID</label>
                    <input type="text" wire:model="google_analytics_id"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color: var(--brand)"
                           placeholder="G-XXXXXXXXXX" />
                    @error('google_analytics_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Maintenance Mode --}}
                <div class="flex items-center gap-3 p-3 rounded-lg {{ $maintenance_mode ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-200' }}">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="maintenance_mode" class="sr-only peer" />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                    </label>
                    <div>
                        <span class="text-sm font-medium {{ $maintenance_mode ? 'text-red-700' : 'text-gray-700' }}">Maintenance Mode</span>
                        <p class="text-xs {{ $maintenance_mode ? 'text-red-500' : 'text-gray-400' }}">
                            @if ($maintenance_mode)
                                ⚠️ Website akan menampilkan halaman maintenance. Driver tidak bisa mengakses dashboard.
                            @else
                                Aktifkan untuk menonaktifkan sementara semua akses website.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" wire:loading.attr="disabled"
                class="w-full md:w-auto text-white font-medium py-2.5 px-6 rounded-lg transition hover:opacity-90 disabled:opacity-50"
                style="background: var(--brand)">
            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
        </button>
    </form>
</div>
