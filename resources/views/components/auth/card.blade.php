{{-- Shared authentication pattern (UI-v2 Phase 6, DESIGN_SYSTEM §42).
  Centered narrow card, no glow/glass/gradient. Usage:
  <x-auth.card title="..." description="..."> form <x-slot:footer>links</x-slot:footer> </x-auth.card> --}}
@props(['title', 'description' => null])

<div class="auth-wrap">
    <div class="auth-card" role="main">
        <div class="auth-head">
            <a href="{{ url('/') }}" class="nav-logo" aria-label="hoaxlin.id beranda">hoaxlin.id</a>
            <h1>{{ $title }}</h1>
            @if($description)<p>{{ $description }}</p>@endif
        </div>
        {{ $slot }}
        @if(isset($footer))
        <div class="auth-foot">{{ $footer }}</div>
        @endif
    </div>
</div>
