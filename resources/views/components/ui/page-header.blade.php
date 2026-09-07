{{-- UI-v2 page header (standard title + description + optional actions).
  Usage: <x-ui.page-header title="..." description="..."><x-ui.button>...</x-ui.button></x-ui.page-header> --}}
@props(['title', 'description' => null])

<div class="ds-page-header" {{ $attributes }}>
    <h1>{{ $title }}</h1>
    @if($description)<p>{{ $description }}</p>@endif
    @if(trim($slot) !== '')<div class="ds-page-header-actions">{{ $slot }}</div>@endif
</div>
