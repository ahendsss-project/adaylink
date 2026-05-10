<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Paket - adaylink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    {{-- Header --}}
    <div class="text-center pt-12 pb-8 px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800">
            <span class="text-indigo-600">a</span>daylink
        </h1>
        <p class="text-gray-500 mt-2 text-sm">Website Builder untuk Driver & Travel Agent Bali</p>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mt-8">Pilih Paket Langganan Anda</h2>
        <p class="text-gray-500 mt-2 max-w-xl mx-auto">Buat website profesional untuk usaha transportasi & tour Anda. Pilih paket yang sesuai dengan kebutuhan.</p>
    </div>

    {{-- Pricing Cards --}}
    <div class="max-w-5xl mx-auto px-4 pb-16">
        @if ($plans->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Belum ada paket tersedia saat ini.</p>
                <p class="text-gray-400 text-sm mt-2">Silakan hubungi admin untuk informasi lebih lanjut.</p>
            </div>
        @else
            <div class="grid md:grid-cols-{{ $plans->count() > 2 ? '3' : $plans->count() }} gap-6 md:gap-8">
                @foreach ($plans as $index => $plan)
                    @php
                        $isPopular = $index === 1 && $plans->count() > 2;
                        $tierBadge = $plan->allowed_template_tier === 'All' ? 'Semua Template' : 'Template ' . $plan->allowed_template_tier;
                    @endphp

                    <div class="relative bg-white rounded-2xl shadow-lg border {{ $isPopular ? 'border-indigo-300 ring-2 ring-indigo-500 scale-[1.02]' : 'border-gray-200' }} overflow-hidden flex flex-col">
                        {{-- Popular Badge --}}
                        @if ($isPopular)
                            <div class="bg-indigo-600 text-white text-center text-xs font-bold py-1.5 uppercase tracking-wider">
                                Paling Populer
                            </div>
                        @endif

                        {{-- Plan Header --}}
                        <div class="p-6 {{ $isPopular ? 'pt-4' : '' }}">
                            <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                            <div class="mt-4 flex items-baseline gap-1">
                                <span class="text-sm text-gray-500">Rp</span>
                                <span class="text-4xl font-extrabold text-gray-900">{{ number_format($plan->price, 0, ',', '.') }}</span>
                                <span class="text-sm text-gray-500">/bulan</span>
                            </div>
                        </div>

                        {{-- Features --}}
                        <div class="px-6 pb-6 flex-1">
                            <ul class="space-y-3">
                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Maks. {{ $plan->max_tours }} Paket Tour
                                </li>
                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Maks. {{ $plan->max_vehicles }} Kendaraan
                                </li>
                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $tierBadge }}
                                </li>
                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Subdomain .adaylink.com
                                </li>
                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Tombol WhatsApp
                                </li>
                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    SEO Friendly
                                </li>
                            </ul>
                        </div>

                        {{-- CTA Button --}}
                        <div class="p-6 pt-0">
                            <a
                                href="{{ route('register') }}?plan={{ $plan->id }}"
                                class="block w-full text-center py-3 px-4 rounded-lg font-semibold text-sm transition {{ $isPopular
                                    ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/25'
                                    : 'bg-gray-900 hover:bg-gray-800 text-white'
                                }}"
                            >
                                Daftar {{ $plan->name }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Footer --}}
        <div class="text-center mt-8">
            <p class="text-gray-400 text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Masuk di sini</a></p>
            <p class="text-gray-400 text-xs mt-4">&copy; {{ date('Y') }} adaylink. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
