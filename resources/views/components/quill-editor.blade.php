@props([
    'model',
    'placeholder' => '',
    'minHeight'   => '150px',
    'toolbar'     => 'full',
])

<div>
    <div
        wire:ignore
        x-data="quillEditor()"
        data-model="{{ $model }}"
        data-placeholder="{{ $placeholder }}"
        data-min-height="{{ $minHeight }}"
        data-toolbar="{{ $toolbar }}"
    >
        <div x-ref="editor"></div>
    </div>
    @error($model)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
