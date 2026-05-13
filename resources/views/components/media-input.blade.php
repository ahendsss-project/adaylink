@props([
    'urlModel',
    'fileModel',
    'placeholder' => 'https://example.com/image.jpg',
    'previewUrl' => null,
    'previewClass' => 'w-32 h-24 object-cover',
    'accept' => '.jpg,.jpeg,.png,.webp',
])

<div x-data="{ tab: 'url' }">
    {{-- Tab switcher --}}
    <div class="flex mb-2 bg-gray-100 rounded-lg p-0.5 w-fit gap-0.5">
        <button type="button" @click="tab = 'url'"
                :class="tab === 'url' ? 'bg-white shadow-sm text-indigo-600 font-medium' : 'text-gray-500 hover:text-gray-700'"
                class="px-3 py-1 text-xs rounded-md transition-all">
            <svg class="w-3.5 h-3.5 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </svg>
            Tempel URL
        </button>
        <button type="button" @click="tab = 'upload'"
                :class="tab === 'upload' ? 'bg-white shadow-sm text-indigo-600 font-medium' : 'text-gray-500 hover:text-gray-700'"
                class="px-3 py-1 text-xs rounded-md transition-all">
            <svg class="w-3.5 h-3.5 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            Upload File
        </button>
    </div>

    {{-- URL tab --}}
    <div x-show="tab === 'url'">
        <div class="flex gap-2">
            <input type="text"
                   wire:model="{{ $urlModel }}"
                   placeholder="{{ $placeholder }}"
                   class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
            @if (isset($urlExtra) && $urlExtra->isNotEmpty())
                {{ $urlExtra }}
            @endif
        </div>
        @error($urlModel)
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Upload tab --}}
    <div x-show="tab === 'upload'" style="display: none;">
        <div class="relative">
            <input type="file"
                   wire:model="{{ $fileModel }}"
                   accept="{{ $accept }}"
                   class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer
                          file:mr-4 file:py-2.5 file:px-4 file:border-0 file:rounded-l-lg
                          file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700
                          hover:file:bg-indigo-100 focus:outline-none transition" />
            <div wire:loading wire:target="{{ $fileModel }}"
                 class="absolute inset-0 bg-white/80 flex items-center justify-center rounded-lg">
                <div class="flex items-center gap-2 text-indigo-600 text-xs font-medium">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 12 12h4z"></path>
                    </svg>
                    Mengupload...
                </div>
            </div>
        </div>
        <p class="text-gray-400 text-xs mt-1">Format: WEBP, PNG, JPG — Maks. 1 MB — Min. 50×50 px</p>
        @error($fileModel)
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Image preview --}}
    @if ($previewUrl)
        <div class="mt-3">
            <img src="{{ $previewUrl }}" alt="Preview"
                 class="{{ $previewClass }} rounded-lg border border-gray-200" />
        </div>
    @endif
</div>
