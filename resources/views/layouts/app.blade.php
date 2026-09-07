<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO -->
    <title>@yield('title', 'hoaxlin.id') — Sistem Deteksi Berita Hoax Berbasis BERT</title>
    <meta name="description" content="@yield('description', 'Periksa kebenaran berita dengan teknologi AI BERT. Deteksi hoax dari teks, gambar, video, atau tautan berita secara instan.')">
    <meta name="keywords" content="cek hoax, deteksi hoax, berita hoax, BERT, AI, fact check, verifikasi berita">
    <meta name="author" content="Muhamad Jikril Aryanda">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'hoaxlin.id — Deteksi Berita Hoax dengan AI')">
    <meta property="og:description" content="Periksa kebenaran berita dengan teknologi AI BERT berbahasa Indonesia.">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔍</text></svg>">

    <!-- Fonts (Inter only, per DESIGN_SYSTEM §9) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')
</head>
<body>

    <x-layout.navbar />

    @if(session('success') || session('error') || session('status'))
    <div id="flash-banner" class="flash-banner" role="status" aria-live="polite">
        @if(session('success'))
        <div class="ds-alert ds-alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span class="flash-message">{{ session('success') }}</span>
            <button type="button" class="flash-close" data-flash-close aria-label="Tutup notifikasi">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        @elseif(session('error'))
        <div class="ds-alert ds-alert-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span class="flash-message">{{ session('error') }}</span>
            <button type="button" class="flash-close" data-flash-close aria-label="Tutup notifikasi">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        @elseif(session('status'))
        <div class="ds-alert ds-alert-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span class="flash-message">{{ session('status') }}</span>
            <button type="button" class="flash-close" data-flash-close aria-label="Tutup notifikasi">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        @endif
    </div>
    @endif

    <!-- Page Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <x-layout.footer />

    @livewireScripts
    @stack('scripts')
</body>
</html>
