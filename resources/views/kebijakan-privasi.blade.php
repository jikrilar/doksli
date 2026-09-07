@extends('layouts.app')

@section('title', 'Kebijakan Privasi — hoaxlin.id')
@section('description', 'Baca kebijakan privasi hoaxlin.id mengenai pengumpulan, penggunaan, dan perlindungan data pengguna.')

@section('content')
<div class="ds-section ds-section-first">
    <div class="ds-prose">
        <div class="result-back">
            <a href="{{ url('/') }}" class="ds-btn ds-btn-ghost ds-btn-sm" aria-label="Kembali ke beranda">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>

        <h1>Kebijakan Privasi</h1>
        <p class="ds-hint">Terakhir diperbarui: 25 Juli 2026</p>

        <section aria-labelledby="intro-heading">
            <h2 id="intro-heading">1. Pendahuluan</h2>
            <p>hoaxlin.id ("kami", "layanan") berkomitmen untuk melindungi privasi pengguna. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda saat menggunakan sistem deteksi hoax berbasis BERT ini.</p>
        </section>

        <section aria-labelledby="data-heading">
            <h2 id="data-heading">2. Data yang Dikumpulkan</h2>
            <ul>
                <li>Teks berita yang Anda kirimkan untuk dianalisis</li>
                <li>Gambar/video yang diunggah (disimpan sementara untuk pemrosesan)</li>
                <li>Tautan URL yang Anda masukkan</li>
                <li>Informasi akun: nama dan email (jika Anda mendaftar)</li>
                <li>Data log teknis: waktu akses, jenis perangkat (tanpa identitas personal)</li>
            </ul>
        </section>

        <section aria-labelledby="use-heading">
            <h2 id="use-heading">3. Penggunaan Data</h2>
            <p>Data yang dikumpulkan digunakan untuk:</p>
            <ul>
                <li>Menjalankan proses deteksi hoax menggunakan model BERT</li>
                <li>Menyimpan riwayat pengecekan untuk pengguna terdaftar</li>
                <li>Meningkatkan akurasi model melalui umpan balik pengguna (opsional)</li>
                <li>Memantau performa dan keandalan sistem</li>
            </ul>
        </section>

        <section aria-labelledby="third-party-heading">
            <h2 id="third-party-heading">4. Layanan Pihak Ketiga</h2>
            <p>Sistem kami menggunakan layanan OpenAI API untuk menerjemahkan berita berbahasa Inggris ke Bahasa Indonesia, OCR gambar, transkripsi video, dan penyusunan narasi penjelasan. Konten yang memerlukan fungsi tersebut mungkin diproses oleh layanan ini sesuai dengan <a href="https://openai.com/policies/privacy-policy" target="_blank" rel="noopener noreferrer">Kebijakan Privasi OpenAI</a>.</p>
        </section>

        <section aria-labelledby="retention-heading">
            <h2 id="retention-heading">5. Retensi Data</h2>
            <p>File media (gambar/video) yang diunggah akan dihapus secara otomatis setelah proses analisis selesai atau paling lambat 24 jam setelah unggahan. Data teks dan hasil analisis disimpan selama akun aktif atau hingga pengguna meminta penghapusan.</p>
        </section>

        <section aria-labelledby="rights-heading">
            <h2 id="rights-heading">6. Hak Pengguna</h2>
            <p>Pengguna terdaftar berhak untuk meminta penghapusan seluruh data riwayat melalui pengaturan akun. Untuk pertanyaan lebih lanjut mengenai privasi, hubungi kami melalui form di halaman Tentang.</p>
        </section>

        <div class="ds-alert ds-alert-info" role="note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span>Kebijakan ini dapat berubah sewaktu-waktu. Perubahan material akan diberitahukan melalui email untuk pengguna terdaftar atau melalui pengumuman di website.</span>
        </div>
    </div>
</div>
@endsection
