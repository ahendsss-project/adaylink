<div>
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Menunggu Pembayaran</h2>
        <p class="text-gray-500 mt-2">Akun Anda sudah terdaftar. Silakan lakukan pembayaran untuk mengaktifkan website Anda.</p>
    </div>

    {{-- Status Card --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="space-y-4">
            {{-- Status --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Status Akun</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-700">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Pending
                </span>
            </div>

            {{-- Email --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Email</span>
                <span class="text-sm font-medium text-gray-700">{{ auth('web')->user()->email }}</span>
            </div>

            {{-- Subdomain --}}
            @php $website = auth('web')->user()->websites()->first(); @endphp
            @if ($website)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Subdomain</span>
                    <span class="text-sm font-medium text-gray-700">{{ $website->subdomain }}.adaylink.com</span>
                </div>
            @endif

            {{-- Plan Info --}}
            @if ($plan)
                <div class="border-t border-gray-100 pt-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Paket</span>
                        <span class="text-sm font-semibold text-indigo-600">{{ $plan->name }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Tagihan Bulanan</span>
                    <span class="text-lg font-bold text-gray-900">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                </div>
            @endif

            {{-- Transaction ID --}}
            @if ($transaction)
                <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                    <span class="text-sm text-gray-500">ID Transaksi</span>
                    <span class="text-sm font-mono text-gray-700">{{ $transaction->id }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Info Box --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 3 3 0 0118 0z" />
            </svg>
            <div>
                <p class="text-sm text-blue-700 font-medium">Cara Aktivasi Akun:</p>
                <ol class="text-sm text-blue-600 mt-1 list-decimal list-inside space-y-1">
                    <li>Klik tombol <strong>"Konfirmasi via WhatsApp"</strong> di bawah</li>
                    <li>Hubungi admin melalui WhatsApp</li>
                    @if ($plan)
                        <li>Lakukan pembayaran <strong>Rp {{ number_format($plan->price, 0, ',', '.') }}</strong> sesuai instruksi</li>
                    @else
                        <li>Lakukan pembayaran sesuai instruksi</li>
                    @endif
                    <li>Admin akan mengaktifkan akun Anda secara manual</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- WhatsApp CTA Button --}}
    <a
        href="{{ $whatsappLink }}"
        target="_blank"
        rel="noopener noreferrer"
        class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3.5 rounded-lg transition duration-200 flex items-center justify-center gap-3 shadow-lg shadow-green-500/25"
    >
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        @if ($plan)
            <span class="text-lg">Konfirmasi via WhatsApp (Rp {{ number_format($plan->price, 0, ',', '.') }})</span>
        @else
            <span class="text-lg">Konfirmasi via WhatsApp</span>
        @endif
    </a>

    {{-- Footer Note --}}
    <p class="text-center text-xs text-gray-400 mt-6">
        Setelah admin memverifikasi pembayaran, akun Anda akan otomatis aktif.
        <br>Halaman ini akan diperbarui saat akun sudah aktif.
    </p>
</div>
