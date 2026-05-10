<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'adaylink') - @yield('page_title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                <span class="text-indigo-600">a</span>daylink
            </h1>
            <p class="text-gray-500 mt-1 text-sm">@yield('subtitle', '')</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-lg p-8">
            @yield('content')
        </div>

        {{-- Footer --}}
        <p class="text-center text-gray-400 text-xs mt-6">
            &copy; {{ date('Y') }} adaylink. All rights reserved.
        </p>
    </div>
</body>
</html>
