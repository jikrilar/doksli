{{-- Application shell footer (UI-v2 Phase 2, DESIGN_SYSTEM §37).
  Simple text-and-spacing hierarchy. Only links to routes that exist;
  no fake social buttons. Contracts preserved: route names, auth states. --}}
<footer class="footer" role="contentinfo">
    <div class="site-footer-inner">
        <div class="site-footer-grid">
            <div>
                <a href="{{ url('/') }}" class="footer-logo">hoaxlin.id</a>
                <p class="footer-desc">Alat bantu pemeriksaan informasi berbasis model klasifikasi bahasa Indonesia. Hasil bersifat estimasi dan bukan vonis kebenaran.</p>
            </div>
            <nav aria-label="Tautan jelajah">
                <h2 class="footer-heading">Jelajahi</h2>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}#cek-berita">Cek Berita</a></li>
                    <li><a href="{{ url('/cara-kerja') }}">Cara Kerja</a></li>
                    <li><a href="{{ route('statistik') }}">Statistik</a></li>
                    <li><a href="{{ url('/tentang') }}">Tentang</a></li>
                    <li><a href="{{ url('/kebijakan-privasi') }}">Kebijakan Privasi</a></li>
                </ul>
            </nav>
            <nav aria-label="Tautan akun">
                <h2 class="footer-heading">Akun</h2>
                <ul class="footer-links">
                    @auth
                    <li><a href="{{ route('riwayat') }}">Riwayat Saya</a></li>
                    <li><a href="{{ route('profile') }}">Profil</a></li>
                    @else
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                    <li><a href="{{ route('register') }}">Daftar</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
        <div class="site-footer-bottom">
            <p class="site-footer-copy">© {{ date('Y') }} hoaxlin.id — Tugas Akhir D3 Teknik Komputer. Dikembangkan oleh Muhamad Jikril Aryanda.</p>
        </div>
    </div>
</footer>
