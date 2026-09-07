@extends('layouts.app')

@section('title', 'Tentang — hoaxlin.id')
@section('description', 'hoaxlin.id adalah sistem deteksi hoax berbasis BERT yang dikembangkan sebagai Tugas Akhir D3 Teknik Komputer.')

@section('content')
<div class="ds-section ds-section-first">
    <div class="ds-prose">
        <p class="ds-eyebrow">Tugas Akhir D3 Teknik Komputer</p>
        <h1>Tentang hoaxlin.id</h1>
        <p class="ds-lead">Sistem pendeteksi berita hoax berbasis kecerdasan buatan untuk membantu masyarakat Indonesia melawan disinformasi digital.</p>

        <h2>Misi kami</h2>
        <p>Penyebaran berita hoax dan disinformasi di Indonesia meningkat pesat seiring masifnya penggunaan media sosial. hoaxlin.id hadir sebagai solusi berbasis AI yang dapat diakses siapa saja.</p>
        <p>Dengan memanfaatkan kekuatan model BERT (Bidirectional Encoder Representations from Transformers), sistem ini menganalisis pola kebahasaan yang membedakan berita valid dan hoax dalam konteks Bahasa Indonesia. Sistem menerima 4 jenis input — teks, gambar, video, dan tautan — dan dikembangkan pada tahun 2026.</p>

        <h2>Pengembang</h2>
        <p><strong>Abdan Dzul Ghaffar Razaq</strong>, mahasiswa D3 Teknik Komputer. Proyek ini merupakan Tugas Akhir Program Diploma III Teknik Komputer berjudul <em>"Rancang Bangun Sistem Pendeteksi Berita Hoax dengan Metode BERT untuk Analisis Teks Mendalam"</em>.</p>
        <p>Dibangun dengan Laravel, Livewire, Filament, Python, BERT/IndoBERT, Hugging Face, dan MySQL.</p>

        <h2>Keterbatasan sistem</h2>
        <div class="ds-alert ds-alert-warning" role="note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <span>Sistem ini memberikan <strong>indikasi probabilistik</strong> berdasarkan pola kebahasaan, bukan keputusan hukum atau jaminan mutlak. Model dioptimalkan untuk teks <strong>Bahasa Indonesia</strong>, dan akurasi OCR serta transkripsi turut mempengaruhi kualitas hasil. Selalu verifikasi berita ke sumber terpercaya.</span>
        </div>
    </div>
</div>
@endsection
