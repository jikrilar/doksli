{{-- UI-v2 alert primitive (DESIGN_SYSTEM §53: icon + message, semantic style).
  Usage: <x-ui.alert type="success|error|warning|info" title="...">Message</x-ui.alert> --}}
@props(['type' => 'info', 'title' => null])

@php
$styles = [
    'success' => 'ds-alert-success',
    'error' => 'ds-alert-error',
    'warning' => 'ds-alert-warning',
    'info' => 'ds-alert-info',
];
$icons = [
    'success' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
    'error' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
    'warning' => '<path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
    'info' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
];
$style = $styles[$type] ?? $styles['info'];
@endphp

<div {{ $attributes->merge(['class' => "ds-alert {$style}", 'role' => $type === 'error' ? 'alert' : 'status']) }}>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icons[$type] ?? $icons['info'] !!}</svg>
    <div>
        @if($title)<p class="ds-alert-title">{{ $title }}</p>@endif
        {{ $slot }}
    </div>
</div>
