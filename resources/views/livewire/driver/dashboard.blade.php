<div>
    {{-- Welcome Card --}}
    <div class="rounded-2xl p-6 md:p-8 text-white mb-6" style="background: linear-gradient(135deg, #40ac98, #2d8a78)">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl md:text-2xl font-bold mb-1">Selamat Datang, {{ $user->full_name }}! 👋</h2>
                @if ($website)
                    <p class="text-white/80 text-sm">Website: <strong>{{ $website->subdomain }}.adaylink.com</strong></p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <span class="bg-white/20 text-white text-xs font-medium px-3 py-1 rounded-full">{{ $user->plan?->name ?? $user->subscription_plan }}</span>
                @if ($user->subscription_expires_at)
                    <span class="text-white/70 text-xs">s/d {{ $user->subscription_expires_at->format('d M Y') }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: var(--brand-light)">
                    <i class="fa-solid fa-calendar-check" style="color: var(--brand)"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $daysLeft > 0 ? $daysLeft : 0 }}</p>
            <p class="text-xs text-gray-500">Hari Tersisa</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-50">
                    <i class="fa-solid fa-car-side text-blue-600"></i>
                </div>
                @if ($maxVehicles > 0)
                    <span class="text-xs text-gray-400">{{ $vehicleCount }}/{{ $maxVehicles }}</span>
                @endif
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $vehicleCount }}</p>
            <p class="text-xs text-gray-500">Armada</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-amber-50">
                    <i class="fa-solid fa-route text-amber-600"></i>
                </div>
                @if ($maxTours > 0)
                    <span class="text-xs text-gray-400">{{ $tourCount }}/{{ $maxTours }}</span>
                @endif
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $tourCount }}</p>
            <p class="text-xs text-gray-500">Paket Tour</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-purple-50">
                    <i class="fa-solid fa-globe text-purple-600"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $website ? '✅' : '❌' }}</p>
            <p class="text-xs text-gray-500">Website</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Content Activity Chart --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Aktivitas Konten (6 Bulan)</h3>
            <div class="relative" style="height: 220px;">
                <canvas id="contentActivityChart"></canvas>
            </div>
        </div>

        {{-- Content Distribution Doughnut --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Distribusi Konten</h3>
            <div class="relative flex items-center justify-center" style="height: 220px;">
                @if ($vehicleCount > 0 || $tourCount > 0)
                    <canvas id="contentDistChart"></canvas>
                @else
                    <div class="text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        <p class="text-sm">Belum ada konten</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Menu Cepat</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('driver.settings') }}" class="flex flex-col items-center gap-2 p-4 bg-gray-50 rounded-xl hover:shadow-md transition">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: var(--brand-light)">
                    <i class="fa-solid fa-gear" style="color: var(--brand)"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">Pengaturan</span>
            </a>
            <a href="{{ route('driver.vehicles.create') }}" class="flex flex-col items-center gap-2 p-4 bg-gray-50 rounded-xl hover:shadow-md transition">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-50">
                    <i class="fa-solid fa-plus text-blue-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">+ Armada</span>
            </a>
            <a href="{{ route('driver.tours.create') }}" class="flex flex-col items-center gap-2 p-4 bg-gray-50 rounded-xl hover:shadow-md transition">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-amber-50">
                    <i class="fa-solid fa-plus text-amber-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">+ Paket Tour</span>
            </a>
            @if ($website)
                <a href="{{ url('/s/' . $website->subdomain) }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-gray-50 rounded-xl hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-purple-50">
                        <i class="fa-solid fa-arrow-up-right-from-square text-purple-600"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700">Lihat Website</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Chart.js Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const brandColor = '#40ac98';
            const brandLight = 'rgba(64, 172, 152, 0.15)';

            // Content Activity Chart
            const actCtx = document.getElementById('contentActivityChart');
            if (actCtx) {
                new Chart(actCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($monthLabels),
                        datasets: [{
                            label: 'Konten Baru',
                            data: @json($monthlyActivity),
                            backgroundColor: brandColor,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // Content Distribution Doughnut
            const distCtx = document.getElementById('contentDistChart');
            if (distCtx && (@json($vehicleCount) + @json($tourCount)) > 0) {
                new Chart(distCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($contentLabels),
                        datasets: [{
                            data: @json($contentCounts),
                            backgroundColor: [brandColor, '#f59e0b'],
                            borderWidth: 0,
                            spacing: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { padding: 16, usePointStyle: true, pointStyleWidth: 8 }
                            }
                        }
                    }
                });
            }
        });
    </script>
</div>
