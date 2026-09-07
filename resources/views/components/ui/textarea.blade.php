{{-- UI-v2 textarea (DESIGN_SYSTEM §19–§22).
  Usage: <x-ui.textarea label="..." name="..." :error="..." hint="...">value</x-ui.textarea> --}}
@props(['label' => null, 'name' => null, 'id' => null, 'error' => null, 'hint' => null, 'required' => false])

@php $fieldId = $id ?? $name; @endphp

<div class="ds-field">
    @if($label)
    <label class="ds-label" for="{{ $fieldId }}">{{ $label }}@if($required) <span class="ds-required" aria-hidden="true">*</span>@endif</label>
    @endif
    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'ds-textarea']) }}
        @if($error) aria-invalid="true" aria-describedby="{{ $fieldId }}-error"
        @elseif($hint) aria-describedby="{{ $fieldId }}-hint" @endif
        @if($required) required @endif
    >{{ $slot }}</textarea>
    @if($hint && !$error)<p class="ds-hint" id="{{ $fieldId }}-hint">{{ $hint }}</p>@endif
    @if($error)
    <p class="ds-error" id="{{ $fieldId }}-error" role="alert">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span>{{ $error }}</span>
    </p>
    @endif
</div>
