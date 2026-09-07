{{-- UI-v2 empty state (DESIGN_SYSTEM §51: explain condition + next action, no big emoji).
  Usage: <x-ui.empty-state title="..." description="..."><x-ui.button>...</x-ui.button></x-ui.empty-state> --}}
@props(['title', 'description' => null])

<div class="ds-empty" {{ $attributes }}>
    <p class="ds-empty-title">{{ $title }}</p>
    @if($description)<p class="ds-empty-desc">{{ $description }}</p>@endif
    @if(trim($slot) !== '')<div>{{ $slot }}</div>@endif
</div>
