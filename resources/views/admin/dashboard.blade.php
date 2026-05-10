<x-layouts.admin title="Dashboard - Admin adaylink">
    @php
        $totalDrivers = \App\Models\User::count();
        $activeDrivers = \App\Models\User::where('subscription_status', 'Active')->count();
        $pendingDrivers = \App\Models\User::where('subscription_status', 'Pending')->count();
        $totalWebsites = \App\Models\Website::count();
        $totalRevenue = \App\Models\Transaction::where('status', 'Success')->sum('amount');

        // Top packages data
        $topPlans = \App\Models\SubscriptionPlan::withCount('users')->orderBy('users_count', 'desc')->get();
        $planLabels = $topPlans->pluck('name')->toArray();
        $planCounts = $topPlans->pluck('users_count')->toArray();

        // Monthly revenue (last 6 months)
        $monthlyRevenue = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->format('M Y');
            $monthlyRevenue[] = \App\Models\Transaction::where('status', 'Success')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
        }

        // Monthly new users (last 6 months)
        $monthlyUsers = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyUsers[] = \App\Models\User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // Recent users
        $recentUsers = \App\Models\User::with('plan')->orderBy('created_at', 'desc')->take(5)->get();
    @endphp

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-lg flex items-center justify-center" style="background: var(--brand-light)">
                    <i class="fa-solid fa-users" style="color: var(--brand)"></i>
                </div>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $totalDrivers }}</p>
            <p class="text-sm text-gray-500">Total Driver</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-green-50">
                    <i class="fa-solid fa-circle-check text-green-600"></i>
                </div>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-50 text-green-600">Active</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $activeDrivers }}</p>
            <p class="text-sm text-gray-500">Driver Aktif</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-amber-50">
                    <i class="fa-solid fa-clock text-amber-600"></i>
                </div>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-amber-50 text-amber-600">Pending</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $pendingDrivers }}</p>
            <p class="text-sm text-gray-500">Menunggu Approval</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-blue-50">
                    <i class="fa-solid fa-money-bill-trend-up text-blue-600"></i>
                </div>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">Revenue</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500">Total Pendapatan</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Top Packages Chart --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Paket Terpopuler</h3>
            <div class="relative" style="height: 260px;">
                <canvas id="topPackagesChart"></canvas>
            </div>
        </div>

        {{-- Monthly Revenue Chart --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Pendapatan Bulanan</h3>
            <div class="relative" style="height: 260px;">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Second Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- User Growth Chart --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 lg:col-span-2">
            <h3 class="font-semibold text-gray-800 mb-4">Pertumbuhan Driver (6 Bulan)</h3>
            <div class="relative" style="height: 240px;">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Ringkasan</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Total Website</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $totalWebsites }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Driver Aktif</span>
                    <span class="text-sm font-semibold text-green-600">{{ $activeDrivers }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Pending</span>
                    <span class="text-sm font-semibold text-amber-600">{{ $pendingDrivers }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Blocked</span>
                    <span class="text-sm font-semibold text-red-600">{{ \App\Models\User::where('is_blocked', true)->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Expired</span>
                    <span class="text-sm font-semibold text-gray-600">{{ \App\Models\User::where('subscription_status', 'Expired')->count() }}</span>
                </div>
                <div class="pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium hover:underline" style="color: var(--brand)">Lihat semua →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Driver Terbaru</h3>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium hover:underline" style="color: var(--brand)">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase">Nama</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase">Paket</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase">Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentUsers as $user)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="py-2.5 px-3 font-medium text-gray-800">{{ $user->full_name }}</td>
                            <td class="py-2.5 px-3 text-gray-500">{{ $user->email }}</td>
                            <td class="py-2.5 px-3">
                                @if ($user->plan)
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background: var(--brand-light); color: var(--brand-dark)">{{ $user->plan->name }}</span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3">
                                @if ($user->subscription_status === 'Active')
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-50 text-green-600">Active</span>
                                @elseif ($user->subscription_status === 'Pending')
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-amber-50 text-amber-600">Pending</span>
                                @else
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">{{ $user->subscription_status }}</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const brandColor = '#40ac98';
            const brandLight = 'rgba(64, 172, 152, 0.15)';

            // Top Packages Chart (Bar)
            new Chart(document.getElementById('topPackagesChart'), {
                type: 'bar',
                data: {
                    labels: @json($planLabels),
                    datasets: [{
                        label: 'Jumlah Subscriber',
                        data: @json($planCounts),
                        backgroundColor: [brandColor, '#f59e0b', '#6366f1', '#ef4444', '#8b5cf6'],
                        borderRadius: 8,
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

            // Monthly Revenue Chart (Line)
            new Chart(document.getElementById('monthlyRevenueChart'), {
                type: 'line',
                data: {
                    labels: @json($monthLabels),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($monthlyRevenue),
                        borderColor: brandColor,
                        backgroundColor: brandLight,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: brandColor,
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { callback: (v) => 'Rp ' + (v/1000000).toFixed(1) + 'jt' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // User Growth Chart (Line)
            new Chart(document.getElementById('userGrowthChart'), {
                type: 'line',
                data: {
                    labels: @json($monthLabels),
                    datasets: [{
                        label: 'Driver Baru',
                        data: @json($monthlyUsers),
                        borderColor: brandColor,
                        backgroundColor: brandLight,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: brandColor,
                        pointRadius: 5,
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
        });
    </script>
</x-layouts.admin>
