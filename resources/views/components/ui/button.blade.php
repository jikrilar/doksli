{{-- UI-v2 button primitive (DESIGN_SYSTEM §17–§18).
  Usage: <x-ui.button variant="primary|secondary|ghost|danger" href="..." block>Label</x-ui.button>
  Renders <a> when href is set, otherwise <button>. No behavior change. --}}
@props(['variant' => 'primary', 'href' => null, 'type' => 'button', 'block' => false])

@php
$variants = [
    'primary' => 'ds-btn-primary',
    'secondary' => 'ds-btn-secondary',
    'ghost' => 'ds-btn-ghost',
    'danger' => 'ds-btn-danger',
];
$class = 'ds-btn '.($variants[$variant] ?? $variants['primary']).($block ? ' ds-btn-block' : '');
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</button>
@endif
