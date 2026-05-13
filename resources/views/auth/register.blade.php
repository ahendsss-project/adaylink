@extends('layouts.auth')

@section('title', 'Register')
@section('page_title', 'Register')
@section('subtitle', 'Buat Akun Driver')

@section('content')
    <h2 class="text-xl font-semibold text-gray-700 text-center mb-6">Buat Akun Baru</h2>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}" x-data="{
        showPassword: false,
        showConfirm: false,
        password: '',
        get hasLength()  { return this.password.length >= 8; },
        get hasUpper()   { return /[A-Z]/.test(this.password); },
        get hasLower()   { return /[a-z]/.test(this.password); },
        get hasNumber()  { return /[0-9]/.test(this.password); },
        get hasSymbol()  { return /[^A-Za-z0-9]/.test(this.password); },
        get score()      { return [this.hasLength, this.hasUpper, this.hasLower, this.hasNumber, this.hasSymbol].filter(Boolean).length; },
        get strengthLabel() { return ['', 'Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'][this.score]; },
        get strengthColor() { return ['', 'bg-red-500', 'bg-orange-500', 'bg-amber-400', 'bg-blue-500', 'bg-green-500'][this.score]; },
        get strengthText()  { return ['', 'text-red-500', 'text-orange-500', 'text-amber-500', 'text-blue-500', 'text-green-600'][this.score]; },
    }">
        @csrf

        {{-- Full Name --}}
        <div class="mb-4">
            <label for="full_name" class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
            <input
                type="text"
                id="full_name"
                name="full_name"
                value="{{ old('full_name') }}"
                required
                autofocus
                placeholder="Nama lengkap Anda"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
            >
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                placeholder="email@contoh.com"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
            >
        </div>

        {{-- Phone --}}
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-600 mb-1">Nomor WhatsApp <span class="text-gray-400">(opsional)</span></label>
            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="08xxxxxxxxxx"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
            >
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-600 mb-1">Password</label>
            <div class="relative">
                <input
                    :type="showPassword ? 'text' : 'password'"
                    id="password"
                    name="password"
                    x-model="password"
                    required
                    placeholder="Buat password yang kuat"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition pr-10"
                >
                <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>

            {{-- Strength Bar --}}
            <div x-show="password.length > 0" class="mt-2">
                <div class="flex gap-1 mb-1">
                    <template x-for="i in 5">
                        <div class="h-1.5 flex-1 rounded-full transition-all duration-300"
                             :class="i <= score ? strengthColor : 'bg-gray-200'"></div>
                    </template>
                </div>
                <p class="text-xs font-medium" :class="strengthText" x-text="strengthLabel"></p>
            </div>

            {{-- Requirements Checklist --}}
            <ul class="mt-2 space-y-0.5" x-show="password.length > 0">
                <li :class="hasLength ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5 text-xs">
                    <span x-text="hasLength ? '✓' : '○'"></span> Minimal 8 karakter
                </li>
                <li :class="hasUpper ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5 text-xs">
                    <span x-text="hasUpper ? '✓' : '○'"></span> Huruf kapital (A-Z)
                </li>
                <li :class="hasLower ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5 text-xs">
                    <span x-text="hasLower ? '✓' : '○'"></span> Huruf kecil (a-z)
                </li>
                <li :class="hasNumber ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5 text-xs">
                    <span x-text="hasNumber ? '✓' : '○'"></span> Angka (0-9)
                </li>
                <li :class="hasSymbol ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5 text-xs">
                    <span x-text="hasSymbol ? '✓' : '○'"></span> Simbol (!@#$%^&*)
                </li>
            </ul>

            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password</label>
            <div class="relative">
                <input
                    :type="showConfirm ? 'text' : 'password'"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    placeholder="Ulangi password"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition pr-10"
                >
                <button type="button" @click="showConfirm = !showConfirm"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition duration-200"
        >
            Daftar & Lanjut Pilih Paket
        </button>
    </form>

    {{-- Login Link --}}
    <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Masuk di sini</a>
    </p>

    {{-- Pricing Link --}}
    <p class="text-center text-xs text-gray-400 mt-3">
        Lihat daftar paket? <a href="{{ route('pricing') }}" class="text-indigo-500 hover:text-indigo-600 font-medium">Halaman Pricing</a>
    </p>
@endsection
