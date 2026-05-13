<div>
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola semua driver / user terdaftar</p>
        </div>
        <div class="text-sm text-gray-500">
            Total: <span class="font-semibold text-gray-700">{{ $users->total() }}</span> user
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari nama atau email..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            {{-- Filter Plan --}}
            <div class="sm:w-56">
                <select wire:model.live="filterPlan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                    <option value="">Semua Paket</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">User</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Telepon</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Paket</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Expired</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            {{-- User Info --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-indigo-600 font-semibold text-sm">{{ strtoupper(substr($user->full_name, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-800 truncate">{{ $user->full_name }}</div>
                                        <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Phone --}}
                            <td class="px-4 py-3 text-gray-600 hidden md:table-cell">
                                {{ $user->phone ?? '-' }}
                            </td>

                            {{-- Plan --}}
                            <td class="px-4 py-3">
                                @if ($user->plan)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $user->plan->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Belum pilih paket</span>
                                @endif
                            </td>

                            {{-- Expired --}}
                            <td class="px-4 py-3 text-gray-600 hidden lg:table-cell">
                                @if ($user->subscription_expires_at)
                                    <span class="text-xs {{ $user->subscription_expires_at->isPast() ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                                        {{ $user->subscription_expires_at->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- Status Badges --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    {{-- Subscription Status --}}
                                    @if ($user->subscription_status === 'Active')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-700">
                                            Active
                                        </span>
                                    @elseif ($user->subscription_status === 'Pending')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-100 text-amber-700">
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500">
                                            {{ $user->subscription_status ?? 'None' }}
                                        </span>
                                    @endif

                                    {{-- Verified --}}
                                    @if ($user->is_verified)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-indigo-100 text-indigo-700">
                                            ✓ Verified
                                        </span>
                                    @endif

                                    {{-- Blocked --}}
                                    @if ($user->is_blocked)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-700">
                                            ✕ Blocked
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Verify --}}
                                    <button wire:click="toggleVerify('{{ $user->id }}')"
                                            wire:confirm="{{ $user->is_verified ? 'Batalkan verifikasi user ini?' : 'Verifikasi user ini?' }}"
                                            title="{{ $user->is_verified ? 'Unverify' : 'Verify' }}"
                                            class="p-1.5 rounded-lg transition {{ $user->is_verified ? 'text-indigo-600 hover:bg-indigo-50' : 'text-gray-400 hover:bg-gray-100 hover:text-indigo-600' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>

                                    {{-- Block/Unblock --}}
                                    <button wire:click="toggleBlock('{{ $user->id }}')"
                                            wire:confirm="{{ $user->is_blocked ? 'Buka blokir user ini?' : 'Blokir user ini?' }}"
                                            title="{{ $user->is_blocked ? 'Unblock' : 'Block' }}"
                                            class="p-1.5 rounded-lg transition {{ $user->is_blocked ? 'text-red-600 hover:bg-red-50' : 'text-gray-400 hover:bg-gray-100 hover:text-red-600' }}">
                                        @if ($user->is_blocked)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        @endif
                                    </button>

                                    {{-- Edit Subscription --}}
                                    <button wire:click="openEditModal('{{ $user->id }}')"
                                            title="Edit Subscription"
                                            class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-blue-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Admin Note --}}
                                    <button wire:click="openNoteModal('{{ $user->id }}')"
                                            title="Catatan Admin"
                                            class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-amber-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                    </button>

                                    {{-- Reset Password --}}
                                    <button wire:click="openResetPasswordModal('{{ $user->id }}')"
                                            title="Reset Password"
                                            class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-orange-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                    </button>

                                    {{-- Login As (Impersonate) --}}
                                    <button wire:click="impersonate('{{ $user->id }}')"
                                            wire:confirm="Login sebagai {{ $user->full_name }}?"
                                            title="Login As"
                                            class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-purple-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="font-medium">Tidak ada user ditemukan</p>
                                <p class="text-xs mt-1">Coba ubah filter atau kata pencarian</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal: Edit Subscription --}}
    @if ($showEditModal)
        <div wire:transition:enter="transition ease-out duration-200"
             wire:transition:enter-start="opacity-0"
             wire:transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
             wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md" x-data="{ open: true }">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Subscription</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Ubah paket dan tanggal expired user</p>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4 space-y-4">
                    {{-- Plan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paket Langganan</label>
                        <select wire:model="editPlanId"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                            <option value="">-- Pilih Paket --</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} — Rp {{ number_format($plan->price, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                        @error('editPlanId')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Expires At --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Expired</label>
                        <input type="date"
                               wire:model="editExpiresAt"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                        @error('editExpiresAt')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button wire:click="saveSubscription"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: Reset Password --}}
    @if ($showResetPasswordModal)
        @php $targetUser = $users->firstWhere('id', $resetPasswordUserId); @endphp
        <div wire:transition:enter="transition ease-out duration-200"
             wire:transition:enter-start="opacity-0"
             wire:transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
             wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] flex flex-col"
                 x-data="{
                     showNew: false,
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
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Reset Password</h3>
                            @if ($targetUser)
                                <p class="text-sm text-gray-500">{{ $targetUser->full_name }} &mdash; {{ $targetUser->email }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4 space-y-4 overflow-y-auto flex-1">
                    {{-- New Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'"
                                   wire:model="newPassword"
                                   @input="password = $event.target.value"
                                   placeholder="Buat password yang kuat"
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                            <button type="button" @click="showNew = !showNew"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <ul class="mt-2 space-y-0.5 text-xs" x-show="password.length > 0">
                            <li :class="hasLength ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5">
                                <span x-text="hasLength ? '✓' : '○'"></span> Minimal 8 karakter
                            </li>
                            <li :class="hasUpper ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5">
                                <span x-text="hasUpper ? '✓' : '○'"></span> Huruf kapital (A-Z)
                            </li>
                            <li :class="hasLower ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5">
                                <span x-text="hasLower ? '✓' : '○'"></span> Huruf kecil (a-z)
                            </li>
                            <li :class="hasNumber ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5">
                                <span x-text="hasNumber ? '✓' : '○'"></span> Angka (0-9)
                            </li>
                            <li :class="hasSymbol ? 'text-green-600' : 'text-gray-400'" class="flex items-center gap-1.5">
                                <span x-text="hasSymbol ? '✓' : '○'"></span> Simbol (!@#$%^&*)
                            </li>
                        </ul>

                        @error('newPassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'"
                                   wire:model="confirmPassword"
                                   placeholder="Ulangi password baru"
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                            <button type="button" @click="showConfirm = !showConfirm"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('confirmPassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <p class="text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5 inline mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        Password lama akan langsung tidak bisa digunakan. Informasikan password baru ke driver secara langsung.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button wire:click="resetPassword" wire:loading.attr="disabled"
                            class="px-4 py-2 text-sm font-medium text-black bg-orange-600 rounded-lg hover:bg-orange-700 disabled:opacity-50 transition">
                        <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                        <span wire:loading wire:target="resetPassword">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: Admin Note --}}
    @if ($showNoteModal)
        <div wire:transition:enter="transition ease-out duration-200"
             wire:transition:enter-start="opacity-0"
             wire:transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
             wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Catatan Admin</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Catatan internal tentang user ini</p>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4">
                    <textarea wire:model="adminNote"
                              rows="4"
                              placeholder="Tulis catatan internal tentang user ini..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none resize-none"></textarea>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button wire:click="saveNote"
                            class="px-4 py-2 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition">
                        Simpan Catatan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
