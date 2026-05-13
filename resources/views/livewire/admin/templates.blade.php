<div>
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
             class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
             class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Manage Templates</h3>
            <p class="text-sm text-gray-500 mt-0.5">Daftarkan folder template untuk digunakan tenant</p>
        </div>
        <a href="{{ route('admin.templates.create') }}"
           class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Template
        </a>
    </div>

    {{-- Templates Table --}}
    @if ($templates->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            <p class="text-gray-500 text-sm">Belum ada template terdaftar.</p>
            <p class="text-gray-400 text-xs mt-1">Klik "Tambah Template" untuk mendaftarkan folder template.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium text-gray-600">Template</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-600">Folder</th>
                            <th class="text-center px-4 py-3 font-medium text-gray-600">Tier / Akses Paket</th>
                            <th class="text-center px-4 py-3 font-medium text-gray-600">Status</th>
                            <th class="text-center px-4 py-3 font-medium text-gray-600">Digunakan</th>
                            <th class="text-right px-4 py-3 font-medium text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($templates as $template)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($template->thumbnail_url)
                                            <img src="{{ upload_url($template->thumbnail_url) }}" alt="{{ $template->name }}"
                                                 class="w-12 h-8 rounded object-cover border border-gray-200" />
                                        @else
                                            <div class="w-12 h-8 rounded bg-gray-100 flex items-center justify-center border border-gray-200">
                                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-800">{{ $template->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <code class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded font-mono">{{ $template->folder_name ?? '-' }}</code>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{-- Tier label --}}
                                    @if ($template->tier === 'Premium')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mb-1">
                                            ⭐ Premium
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 mb-1">
                                            Basic
                                        </span>
                                    @endif
                                    {{-- Accessible plans --}}
                                    <div class="mt-1">
                                        @if (empty($template->allowed_plan_ids))
                                            <span class="text-xs text-green-600 font-medium">Semua paket</span>
                                        @else
                                            <div class="flex flex-wrap gap-1 justify-center">
                                                @foreach ($template->allowed_plan_ids as $planId)
                                                    @if (isset($plans[$planId]))
                                                        <span class="text-xs bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded font-medium">
                                                            {{ $plans[$planId] }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleActive({{ $template->id }})"
                                            class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full transition {{ $template->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                        @if ($template->is_active)
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                                        @endif
                                    </button>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs text-gray-500">{{ $template->websiteSettings()->count() }} website</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.templates.edit', $template->id) }}"
                                           class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button wire:click="delete({{ $template->id }})" wire:confirm="Hapus template {{ $template->name }}?"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
