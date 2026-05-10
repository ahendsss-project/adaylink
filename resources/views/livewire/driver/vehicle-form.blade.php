<div x-data="{ showGallery: false }">
    {{-- Back Button --}}
    <a href="{{ route('driver.vehicles.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Armada
    </a>

    {{-- Quota Exceeded Warning --}}
    @if ($quotaExceeded)
        <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-4">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <div>
                    <h4 class="font-semibold text-red-800">Limit Paket Tercapai</h4>
                    <p class="text-sm text-red-600 mt-1">Anda sudah mencapai batas maksimal <strong>{{ $maxVehicles }} armada</strong> untuk paket Anda. Silakan upgrade paket untuk menambah armada.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-5 md:p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            {{ $isEditing ? 'Edit Armada' : 'Tambah Armada Baru' }}
        </h3>

        {{-- Quota Info --}}
        <div class="flex items-center gap-2 mb-5 text-xs text-gray-500">
            <span>Kuota: {{ $currentCount }} / {{ $maxVehicles }} armada</span>
        </div>

        <form wire:submit="save" class="space-y-5">
            {{-- Model Name --}}
            <div>
                <label for="model_name" class="block text-sm font-medium text-gray-600 mb-1">Nama Model / Tipe Kendaraan *</label>
                <input type="text" wire:model="model_name" id="model_name"
                       placeholder="Contoh: Toyota HiAce Commuter"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                @error('model_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Capacity & Price --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="capacity_people" class="block text-sm font-medium text-gray-600 mb-1">Kapasitas (orang) *</label>
                    <input type="number" wire:model="capacity_people" id="capacity_people" min="1"
                       placeholder="12"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                    @error('capacity_people') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="price_per_day" class="block text-sm font-medium text-gray-600 mb-1">Harga per Hari (Rp) *</label>
                    <input type="number" wire:model="price_per_day" id="price_per_day" min="0" step="10000"
                       placeholder="350000"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                    @error('price_per_day') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Image URL with Gallery Picker --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Gambar Kendaraan</label>
                <div class="flex gap-2">
                    <input type="text" wire:model="image_url" id="image_url"
                           placeholder="https://example.com/foto-mobil.jpg"
                           class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                    <button type="button" @click="showGallery = true"
                            class="shrink-0 inline-flex items-center gap-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-sm font-medium px-3 py-2.5 rounded-lg border border-purple-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Galeri
                    </button>
                </div>
                @error('image_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                {{-- Image Preview --}}
                @if ($image_url)
                    <div class="mt-3">
                        <img src="{{ $image_url }}" alt="Preview" class="w-32 h-24 rounded-lg object-cover border border-gray-200" />
                    </div>
                @endif
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" wire:loading.attr="disabled"
                        @if($quotaExceeded) disabled @endif
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium py-2.5 px-6 rounded-lg transition text-sm">
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Armada' }}
                </button>
                <a href="{{ route('driver.vehicles.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Stock Image Gallery Modal --}}
    <div x-show="showGallery" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
         @click.self="showGallery = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Pilih Gambar dari Galeri</h3>
                <button @click="showGallery = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-5 overflow-y-auto max-h-[60vh]">
                @if ($stockImages->isEmpty())
                    <p class="text-gray-500 text-sm text-center py-8">Belum ada gambar stok untuk kategori Vehicle. Hubungi admin.</p>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($stockImages as $stockImage)
                            <button type="button"
                                    wire:click="selectStockImage('{{ $stockImage->image_url }}')"
                                    @click="showGallery = false"
                                    class="group relative rounded-lg overflow-hidden border-2 border-transparent hover:border-indigo-500 transition focus:border-indigo-500 focus:outline-none">
                                <img src="{{ upload_url($stockImage->image_url) }}" alt="{{ $stockImage->alt_text }}"
                                     class="w-full h-32 object-cover" />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                    <span class="text-white opacity-0 group-hover:opacity-100 transition text-sm font-medium">Pilih</span>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                                    <p class="text-white text-xs truncate">{{ $stockImage->title }}</p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
