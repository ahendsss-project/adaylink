<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Ditangguhkan - adaylink</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        {{-- Icon --}}
        <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>

        {{-- Message --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Website Ditangguhkan</h1>
        <p class="text-gray-500 mb-2">Maaf, website ini sedang ditangguhkan dan tidak dapat diakses saat ini.</p>

        @isset($reason)
            <p class="text-sm text-gray-400 mb-6">{{ $reason }}</p>
        @else
            <p class="text-sm text-gray-400 mb-6">Silakan hubungi pemilik website atau coba kembali nanti.</p>
        @endisset

        {{-- Brand --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-400">
                Powered by <span class="font-semibold text-indigo-500">adaylink</span>
            </p>
        </div>
    </div>
</body>
</html>
