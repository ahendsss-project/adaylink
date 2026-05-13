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

    <form method="POST" action="{{ route('register.post') }}" x-data="{ showPassword: false, password: '', checkPassword() {} }">
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
                    @input="checkPassword()"
                    required
                    placeholder="Minimal 8 karakter"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition pr-10"
                >
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                >
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            {{-- Password strength indicators --}}
            <div class="mt-2 space-y-1" x-show="password && password.length > 0">
                <p class="text-xs" :class="password.length >= 8 ? 'text-green-600' : 'text-gray-400'">
                    <span x-text="password.length >= 8 ? '✅' : '○'"></span> Minimal 8 karakter
                </p>
                <p class="text-xs" :class="/[A-Z]/.test(password) ? 'text-green-600' : 'text-gray-400'">
                    <span x-text="/[A-Z]/.test(password) ? '✅' : '○'"></span> Huruf besar (A-Z)
                </p>
                <p class="text-xs" :class="/[0-9]/.test(password) ? 'text-green-600' : 'text-gray-400'">
                    <span x-text="/[0-9]/.test(password) ? '✅' : '○'"></span> Angka (0-9)
                </p>
                <p class="text-xs" :class="/[^A-Za-z0-9]/.test(password) ? 'text-green-600' : 'text-gray-400'">
                    <span x-text="/[^A-Za-z0-9]/.test(password) ? '✅' : '○'"></span> Karakter khusus (!@#$%...)
                </p>
            </div>
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                placeholder="Ulangi password"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
            >
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
