<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - adaylink</title>
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
<body class="min-h-screen bg-gray-50">
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-gray-200 min-h-screen fixed left-0 top-0 z-40">
            {{-- Brand --}}
            @php $platformConfig = \App\Models\PlatformConfig::first(); @endphp
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-xl font-bold flex items-center gap-2">
                    @if ($platformConfig?->main_logo_url)
                        <img src="{{ $platformConfig->main_logo_url }}" alt="{{ $platformConfig->app_name ?? 'adaylink' }}" class="h-8 w-auto" />
                    @else
                        <span style="color: var(--brand)">a</span>daylink
                    @endif
                    <span class="text-xs font-normal text-gray-400 ml-1">Admin</span>
                </h1>
            </div>

            {{-- Navigation --}}
            <nav class="px-3 py-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('admin.dashboard'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('admin.dashboard'),
                ]) style="{{ request()->routeIs('admin.dashboard') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.pending-approvals') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('admin.pending-approvals'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('admin.pending-approvals'),
                ]) style="{{ request()->routeIs('admin.pending-approvals') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-clock w-5 text-center"></i>
                    Pending Approvals
                    @php $pendingCount = \App\Models\User::where('subscription_status', 'Pending')->count(); @endphp
                    @if ($pendingCount > 0)
                        <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.users.index') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('admin.users.*'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('admin.users.*'),
                ]) style="{{ request()->routeIs('admin.users.*') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    User Management
                </a>

                <a href="{{ route('admin.plans.index') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('admin.plans.*'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('admin.plans.*'),
                ]) style="{{ request()->routeIs('admin.plans.*') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-tags w-5 text-center"></i>
                    Subscription Plans
                </a>

                <a href="{{ route('admin.stock-images.index') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('admin.stock-images.*'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('admin.stock-images.*'),
                ]) style="{{ request()->routeIs('admin.stock-images.*') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-images w-5 text-center"></i>
                    Stock Images
                </a>

                <a href="{{ route('admin.templates.index') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('admin.templates.*'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('admin.templates.*'),
                ]) style="{{ request()->routeIs('admin.templates.*') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-palette w-5 text-center"></i>
                    Manage Templates
                </a>

                {{-- Divider --}}
                <div class="border-t border-gray-100 my-3"></div>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-50 transition">
                    <i class="fa-solid fa-newspaper w-5 text-center"></i>
                    Blog Posts
                </a>

                <a href="{{ route('admin.platform-settings') }}" @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                    'text-white' => request()->routeIs('admin.platform-settings'),
                    'text-gray-600 hover:bg-gray-50' => !request()->routeIs('admin.platform-settings'),
                ]) style="{{ request()->routeIs('admin.platform-settings') ? 'background: var(--brand)' : '' }}">
                    <i class="fa-solid fa-gear w-5 text-center"></i>
                    Platform Settings
                </a>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 ml-64">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between sticky top-0 z-30">
                <h2 class="text-lg font-semibold text-gray-800">@yield('header_title', 'Dashboard')</h2>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">{{ auth('admin')->user()->full_name }}</span>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background: var(--brand-light); color: var(--brand-dark)">{{ auth('admin')->user()->role->role_name ?? 'Admin' }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium transition">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
