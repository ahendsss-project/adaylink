@props(['locale', 'altLocale', 'subdomain', 'features' => []])

@php
    $currentUrl = url()->current();
    $altFlag = $altLocale === 'en' ? '🇬🇧' : '🇮🇩';
    $altLabel = $altLocale === 'en' ? 'EN' : 'ID';
    $altUrl = $currentUrl . (str_contains($currentUrl, '?') ? '&' : '?') . 'lang=' . $altLocale;
@endphp

@if($features['multilanguage'] ?? false)
<div class="lang-switch" x-data="{ open: false }" @click.away="open = false" style="position: relative; display: inline-block;">
    <button @click="open = !open"
            style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); background: rgba(255,255,255,0.9); cursor: pointer; font-size: 13px; font-weight: 500; backdrop-filter: blur(8px);">
        <span>{{ $locale === 'en' ? '🇬🇧' : '🇮🇩' }}</span>
        <span>{{ strtoupper($locale) }}</span>
        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="open" x-transition
         style="position: absolute; right: 0; top: 100%; margin-top: 4px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); overflow: hidden; z-index: 50; min-width: 120px;">
        <a href="{{ $altUrl }}"
           style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; font-size: 13px; text-decoration: none; color: #333;"
           onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
            <span>{{ $altFlag }}</span>
            <span>{{ $altLabel }}</span>
        </a>
    </div>
</div>
@endif
