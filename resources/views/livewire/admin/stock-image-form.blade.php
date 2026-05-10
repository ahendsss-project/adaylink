<div>
    @php use Illuminate\Support\Facades\Storage; @endphp

    <a href="{{ route('admin.stock-images.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Galeri
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">{{ $isEditing ? 'Edit Gambar' : 'Upload Gambar Baru' }}</h3>

        <form wire:submit="save" class="space-y-5">
            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Kategori *</label>
                <select wire:model="category"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="Tour">Tour</option>
                    <option value="Vehicle">Vehicle</option>
                    <option value="HeroBanner">Hero Banner</option>
                    <option value="General">General</option>
                </select>
                @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Judul Gambar *</label>
                <input type="text" wire:model="title" placeholder="Contoh: Pura Besakih Pagi Hari"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Alt Text (SEO) --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Alt Text (SEO) * <span class="text-gray-400 font-normal">— Wajib untuk SEO</span></label>
                <input type="text" wire:model="alt_text" placeholder="Contoh: Sewa mobil murah ke Besakih Bali"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
                @error('alt_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Image Upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    {{ $isEditing ? 'Ganti Gambar (opsional)' : 'Upload Gambar *' }}
                </label>

                @if ($isEditing && $stockImage?->image_url)
                    <div class="mb-3">
                        <img src="{{ upload_url($stockImage->image_url) }}" alt="{{ $stockImage->alt_text }}"
                             class="w-40 h-40 object-cover rounded-lg border border-gray-200" />
                        <p class="text-xs text-gray-400 mt-1">Gambar saat ini</p>
                    </div>
                @endif

                <div wire:loading wire:target="image" class="text-sm text-indigo-500 mb-2">Uploading...</div>

                <input type="file" wire:model="image" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                @if ($image && !$errors->has('image'))
                    <div class="mt-2">
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-40 h-40 object-cover rounded-lg border border-gray-200" />
                    </div>
                @endif

                <p class="text-xs text-gray-400 mt-1">Maks. 5MB. Format: JPG, PNG, WebP.</p>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" wire:loading.attr="disabled"
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium py-2.5 px-6 rounded-lg transition text-sm">
                    {{ $isEditing ? 'Simpan Perubahan' : 'Upload Gambar' }}
                </button>
                <a href="{{ route('admin.stock-images.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
