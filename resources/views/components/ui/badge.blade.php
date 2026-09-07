{{-- UI-v2 status badge (DESIGN_SYSTEM §28: icon + text, never color alone, no emoji).
  Usage: <x-ui.badge type="valid|hoax|meragukan|info|neutral">Valid</x-ui.badge> --}}
@props(['type' => 'neutral'])

@php
$styles = [
    'valid' => 'ds-badge-valid',
    'hoax' => 'ds-badge-hoax',
    'meragukan' => 'ds-badge-meragukan',
    'info' => 'ds-badge-info',
    'neutral' => 'ds-badge-neutral',
];
$icons = [
    'valid' => '<polyline points="20 6 9 17 4 12"/>',
    'hoax' => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
    'meragukan' => '<line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>',
    'info' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
    'neutral' => '<line x1="5" y1="12" x2="19" y2="12"/>',
];
$style = $styles[$type] ?? $styles['neutral'];
@endphp

<span {{ $attributes->merge(['class' => "ds-badge {$style}"]) }}>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icons[$type] ?? $icons['neutral'] !!}</svg>
    {{ $slot }}
</span>
