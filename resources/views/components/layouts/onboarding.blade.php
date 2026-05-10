<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Onboarding') - adaylink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50">
    {{-- Top Bar --}}
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 items-center">
                <h1 class="text-lg font-bold text-gray-800">
                    <span class="text-indigo-600">a</span>daylink
                </h1>
                {{-- Step Indicator --}}
                <div class="flex items-center gap-2 text-sm">
                    <span @class([
                        'px-3 py-1 rounded-full font-medium',
                        'bg-indigo-100 text-indigo-700' => request()->routeIs('onboarding.subdomain'),
                        'bg-gray-100 text-gray-500' => !request()->routeIs('onboarding.subdomain'),
                    ])>
                        1. Subdomain
                    </span>
                    <span class="text-gray-300">→</span>
                    <span @class([
                        'px-3 py-1 rounded-full font-medium',
                        'bg-indigo-100 text-indigo-700' => request()->routeIs('onboarding.paywall'),
                        'bg-gray-100 text-gray-500' => !request()->routeIs('onboarding.paywall'),
                    ])>
                        2. Pembayaran
                    </span>
                    <span class="text-gray-300">→</span>
                    <span class="px-3 py-1 rounded-full font-medium bg-gray-100 text-gray-400">
                        3. Aktif
                    </span>
                </div>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="max-w-2xl mx-auto px-4 py-12">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
