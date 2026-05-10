<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - adaylink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <h1 class="text-xl font-bold text-gray-800">
                    <span class="text-indigo-600">a</span>daylink
                </h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">{{ auth('web')->user()->full_name }}</span>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">{{ auth('web')->user()->subscription_plan }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, {{ auth('web')->user()->full_name }}! 🎉</h2>
            <p class="text-gray-500">Ini adalah halaman dashboard Driver/Tenant Anda. Halaman ini akan dikembangkan lebih lanjut.</p>
        </div>
    </main>
</body>
</html>
