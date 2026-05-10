<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - adaylink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {{-- Font Awesome 6 CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>
        :root { --brand: #40ac98; --brand-light: #e8f5f1; --brand-dark: #2d8a78; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 pb-20 md:pb-0">
    {{-- Impersonation Banner --}}
    @if (session('impersonating_admin'))
        <div class="bg-red-600 text-white px-4 py-2.5 flex items-center justify-between sticky top-0 z-50">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <span class="text-sm font-medium">
                    ⚠️ Mode Impersonate — Anda login sebagai <strong>{{ auth('web')->user()->full_name }}</strong>
                </span>
            </div>
            <a href="{{ route('exit-impersonate') }}"
               class="inline-flex items-center gap-1.5 bg-white text-red-600 px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Exit & Back to Admin
            </a>
        </div>
    @endif

    {{-- Mobile Top Bar --}}
    <header class="md:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-30">
        <h1 class="text-lg font-bold text-gray-800">
            <span style="color: var(--brand)">a</span>daylink
        </h1>
        <div class="flex items-center gap-2">
            <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background: var(--brand-light); color: var(--brand-dark)">{{ auth('web')->user()->plan?->name ?? auth('web')->user()->subscription_plan }}</span>
        </div>
    </header>

    <div class="flex">
        {{-- Desktop Sidebar --}}
        <aside class="hidden md:block w-64 bg-white border-r border-gray-200 min-h-screen fixed left-0 top-0">
            {{-- Brand --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-xl font-bold text-gray-800">
                    <span style="color: var(--brand)">a</span>daylink
                </h1>
                <p class="text-xs text-gray-400 mt-0.5">Driver Dashboard</p>
            </div>

            {{-- User Info --}}
            <div class="px-4 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--brand-light)">
                        <span style="color: var(--brand-dark)" class="font-bold">{{ strtoupper(substr(auth('web')->user()->full_name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ auth('web')->user()->full_name }}</p>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background: var(--brand-light); color: var(--brand-dark)">{{ auth('web')->user()->plan?->name ?? auth('web')->user()->subscription_plan }}</span>
                    </div>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="px-3 py-4 space-y-1">
                <a href="{{ route('driver.dashboard') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('driver.dashboard'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('driver.dashboard'),
                ]) style="{{ request()->routeIs('driver.dashboard') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-house w-5 text-center"></i>
                    Dashboard
                </a>

                <a href="{{ route('driver.settings') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('driver.settings'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('driver.settings'),
                ]) style="{{ request()->routeIs('driver.settings') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-globe w-5 text-center"></i>
                    Pengaturan Website
                </a>

                <a href="{{ route('driver.vehicles.index') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('driver.vehicles.*'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('driver.vehicles.*'),
                ]) style="{{ request()->routeIs('driver.vehicles.*') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-car-side w-5 text-center"></i>
                    Armada
                </a>

                <a href="{{ route('driver.tours.index') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('driver.tours.*'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('driver.tours.*'),
                ]) style="{{ request()->routeIs('driver.tours.*') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-route w-5 text-center"></i>
                    Paket Tour
                </a>

                <a href="{{ route('driver.reviews.index') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('driver.reviews.*'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('driver.reviews.*'),
                ]) style="{{ request()->routeIs('driver.reviews.*') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-star-half-stroke w-5 text-center"></i>
                    Review
                </a>

                <a href="{{ route('driver.pages.index') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('driver.pages.*'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('driver.pages.*'),
                ]) style="{{ request()->routeIs('driver.pages.*') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-file-lines w-5 text-center"></i>
                    Halaman
                </a>

                <div class="pt-4 mt-4 border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 transition w-full">
                            <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 md:ml-64">
            {{-- Desktop Top Bar --}}
            <header class="hidden md:flex bg-white border-b border-gray-200 px-6 py-3 items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">@yield('header_title', 'Dashboard')</h2>
                <div class="flex items-center gap-3">
                    @php $website = auth('web')->user()->websites->first(); @endphp
                    @if ($website)
                        <span class="text-sm text-gray-500">{{ $website->subdomain }}.adaylink.com</span>
                    @endif
                </div>
            </header>

            <main class="p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Mobile Bottom Navigation --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-30">
        <div class="flex items-center justify-around py-2">
            <a href="{{ route('driver.dashboard') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-xs" style="color: {{ request()->routeIs('driver.dashboard') ? 'var(--brand)' : '#9ca3af' }}">
                <i class="fa-solid fa-house text-lg"></i>
                Home
            </a>
            <a href="{{ route('driver.settings') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-xs" style="color: {{ request()->routeIs('driver.settings') ? 'var(--brand)' : '#9ca3af' }}">
                <i class="fa-solid fa-globe text-lg"></i>
                Setting
            </a>
            <a href="{{ route('driver.vehicles.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-xs" style="color: {{ request()->routeIs('driver.vehicles.*') ? 'var(--brand)' : '#9ca3af' }}">
                <i class="fa-solid fa-car-side text-lg"></i>
                Armada
            </a>
            <a href="{{ route('driver.tours.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-xs" style="color: {{ request()->routeIs('driver.tours.*') ? 'var(--brand)' : '#9ca3af' }}">
                <i class="fa-solid fa-route text-lg"></i>
                Tour
            </a>
            <a href="{{ route('driver.reviews.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-xs" style="color: {{ request()->routeIs('driver.reviews.*') ? 'var(--brand)' : '#9ca3af' }}">
                <i class="fa-solid fa-star-half-stroke text-lg"></i>
                Review
            </a>
            <a href="{{ route('driver.pages.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-xs" style="color: {{ request()->routeIs('driver.pages.*') ? 'var(--brand)' : '#9ca3af' }}">
                <i class="fa-solid fa-file-lines text-lg"></i>
                Halaman
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 text-xs text-gray-400">
                @csrf
                <button type="submit" class="flex flex-col items-center gap-0.5">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    @livewireScripts
</body>
</html>
