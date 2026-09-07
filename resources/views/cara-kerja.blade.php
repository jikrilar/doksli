@extends('layouts.app')

@section('title', 'Cara Kerja — hoaxlin.id')
@section('description', 'Pelajari bagaimana sistem AI BERT kami mendeteksi berita hoax dari berbagai jenis input.')

@section('content')
<div class="ds-section ds-section-first">
    <div class="ds-content">
        <p class="ds-eyebrow">Teknologi dan metodologi</p>
        <h1>Cara kerja hoaxlin.id</h1>
        <p class="ds-lead mt-4">Dari input berita hingga hasil deteksi — empat langkah pipeline sistem pendeteksi hoax berbasis BERT.</p>
    </div>
</div>

<section aria-label="Alur pipeline sistem">
    <div class="ds-content">
        <ol class="steps">
            <li class="step">
                <span class="step-num" aria-hidden="true">01</span>
                <div>
                    <h3>Penerimaan input</h3>
                    <p>Sistem menerima berita dalam empat format. Masing-masing ditangani berbeda sebelum dianalisis.</p>
                    <ul class="doc-list">
                        <li><strong>Teks langsung</strong> — input tercepat, langsung ke tokenisasi.</li>
                        <li><strong>Gambar</strong> — teks diekstraksi dengan OCR (OpenAI Vision).</li>
                        <li><strong>Video</strong> — audio diekstrak lalu ditranskripsi (Whisper).</li>
                        <li><strong>Tautan URL</strong> — artikel diambil dan kontennya diekstraksi.</li>
                    </ul>
                </div>
            </li>
            <li class="step">
                <span class="step-num" aria-hidden="true">02</span>
                <div>
                    <h3>Pra-pemrosesan teks</h3>
                    <p>Teks dibersihkan dan dinormalisasi agar analisis lebih akurat.</p>
                    <ul class="doc-list">
                        <li>Penghapusan tag HTML, URL, dan karakter non-standar.</li>
                        <li>Normalisasi singkatan umum, typo, dan ejaan.</li>
                        <li>Tokenisasi dengan WordPiece tokenizer BERT.</li>
                        <li>Pemotongan teks bila melebihi panjang maksimum model.</li>
                    </ul>
                </div>
            </li>
            <li class="step">
                <span class="step-num" aria-hidden="true">03</span>
                <div>
                    <h3>Klasifikasi IndoBERT</h3>
                    <p>Inti sistem: model IndoBERT yang di-fine-tune pada dataset hoax Indonesia menganalisis teks secara mendalam.</p>
                    <ul class="doc-list">
                        <li><strong>Bidireksional</strong> — membaca konteks dari dua arah sekaligus.</li>
                        <li><strong>Pretrained</strong> — sudah memahami Bahasa Indonesia sebelum fine-tuning.</li>
                        <li><strong>Fine-tuned</strong> — dilatih ulang pada berita hoax Indonesia berlabel.</li>
                        <li><strong>Transformer</strong> — mekanisme attention menangkap relasi antar kata.</li>
                    </ul>
                    <div class="mt-4" role="group" aria-label="Label output model">
                        <x-ui.badge type="valid">Valid</x-ui.badge>
                        <x-ui.badge type="hoax">Hoax</x-ui.badge>
                        <x-ui.badge type="meragukan">Meragukan</x-ui.badge>
                    </div>
                    <details class="ds-tech mt-4">
                        <summary>Detail teknis model</summary>
                        <div class="ds-prose mt-2">
                            <p>Arsitektur encoder 12 layer dengan WordPiece tokenizer dan batas 512 token. Skor keyakinan di bawah ambang model dilabeli Meragukan. Setiap prediksi mencatat versi model yang digunakan.</p>
                        </div>
                    </details>
                </div>
            </li>
            <li class="step">
                <span class="step-num" aria-hidden="true">04</span>
                <div>
                    <h3>Hasil dan penjelasan</h3>
                    <p>Output model disusun menjadi laporan yang mudah dipahami.</p>
                    <ul class="doc-list">
                        <li>Label klasifikasi: Valid, Hoax, atau Meragukan.</li>
                        <li>Skor keyakinan model terhadap prediksinya.</li>
                        <li>Narasi penjelasan berbahasa Indonesia.</li>
                        <li>Riwayat tersimpan untuk pengguna yang masuk.</li>
                    </ul>
                </div>
            </li>
        </ol>
    </div>
</section>

<section class="ds-section" aria-label="Ajakan mencoba">
    <div class="ds-narrow ds-center">
        <h2>Siap mencoba?</h2>
        <p class="ds-lead">Coba analisis berita sekarang — gratis, tanpa perlu daftar.</p>
        <div class="ds-actions ds-actions-center ds-actions-spaced">
            <a href="{{ url('/') }}#cek-berita" class="ds-btn ds-btn-primary">Mulai sekarang</a>
        </div>
    </div>
</section>

@endsection
