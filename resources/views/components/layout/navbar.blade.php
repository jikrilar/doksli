{{-- Application shell navbar (UI-v2 Phase 2, DESIGN_SYSTEM §34–§36).
  Solid light surface, sticky behavior preserved (fixed), no gradients.
  Contracts preserved: routes, logout POST + CSRF, aria wiring, element IDs. --}}
<nav class="navbar" id="main-navbar" aria-label="Navigasi utama">
    <div class="site-navbar-inner">
        <a href="{{ url('/') }}" class="nav-logo" aria-label="hoaxlin.id beranda">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
            <span>hoaxlin.id</span>
        </a>

        <div class="site-nav-desktop" aria-label="Navigasi desktop">
            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}" @if(request()->is('/')) aria-current="page" @endif>Cek Berita</a>
            <a href="{{ url('/cara-kerja') }}" class="nav-link {{ request()->is('cara-kerja') ? 'active' : '' }}" @if(request()->is('cara-kerja')) aria-current="page" @endif>Cara Kerja</a>
            <a href="{{ url('/tentang') }}" class="nav-link {{ request()->is('tentang') ? 'active' : '' }}" @if(request()->is('tentang')) aria-current="page" @endif>Tentang</a>
        </div>

        <div class="site-nav-auth">
            @auth
            <div class="account-chip" id="user-menu-wrapper">
                <button type="button" id="user-menu-btn" class="account-btn" aria-haspopup="true" aria-expanded="false" aria-controls="user-dropdown">
                    <span class="account-avatar" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="account-name">{{ auth()->user()->name }}</span>
                    <svg id="user-menu-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div id="user-dropdown" class="account-menu" role="menu">
                    <a href="{{ route('profile') }}" class="dropdown-item" role="menuitem">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Profil Saya
                    </a>
                    <a href="{{ route('riwayat') }}" class="dropdown-item" role="menuitem">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                        Riwayat Pengecekan
                    </a>
                    <div class="account-menu-sep" aria-hidden="true"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-item-danger" role="menuitem">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
            @else
            <x-ui.button variant="ghost" href="{{ route('login') }}" class="ds-btn-sm">Masuk</x-ui.button>
            <x-ui.button variant="primary" href="{{ route('register') }}" class="ds-btn-sm">Daftar</x-ui.button>
            @endauth

            <button type="button" id="menu-toggle" class="ds-btn ds-btn-ghost ds-btn-sm" aria-expanded="false" aria-controls="mobile-menu" aria-label="Buka menu navigasi">
                <svg id="menu-icon-open" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg id="menu-icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" hidden><path d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="mobile-menu" aria-label="Navigasi seluler">
        <div class="mobile-menu-head">
            <span class="mobile-menu-title">Menu</span>
            <button type="button" id="menu-close" class="ds-btn ds-btn-ghost ds-btn-sm" aria-label="Tutup menu navigasi">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <a href="{{ url('/') }}" class="mobile-link {{ request()->is('/') ? 'active' : '' }}">Cek Berita</a>
        <a href="{{ url('/cara-kerja') }}" class="mobile-link {{ request()->is('cara-kerja') ? 'active' : '' }}">Cara Kerja</a>
        <a href="{{ url('/tentang') }}" class="mobile-link {{ request()->is('tentang') ? 'active' : '' }}">Tentang</a>
        <div class="account-menu-sep" aria-hidden="true"></div>
        @auth
        <a href="{{ route('profile') }}" class="mobile-link">Profil Saya</a>
        <a href="{{ route('riwayat') }}" class="mobile-link">Riwayat</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mobile-link mobile-link-danger">Keluar</button>
        </form>
        @else
        <a href="{{ route('login') }}" class="mobile-link">Masuk</a>
        <a href="{{ route('register') }}" class="mobile-link">Daftar</a>
        @endauth
    </div>
</nav>
