@extends('layouts.app')

@section('title', 'hoaxlin.id — Deteksi Berita Hoax dengan AI BERT')
@section('description', 'Periksa kebenaran berita dengan teknologi AI BERT. Deteksi hoax dari teks, gambar, video, atau tautan berita secara instan dan akurat.')

@php
  // Simple math CAPTCHA (C17) — contract with StoreSubmissionRequest:
  // session key `captcha_answer`, field `captcha_answer`, honeypot `website`.
  $captchaA = random_int(1, 9);
  $captchaB = random_int(1, 9);
  session(['captcha_answer' => $captchaA + $captchaB]);
  $captchaQuestion = "$captchaA + $captchaB = ?";

  // Restore the active tab after a validation round-trip (contract: input_type values).
  $oldType = old('input_type', 'text');
  $activeTab = match ($oldType) {
      'image' => 'gambar',
      'video', 'video_url' => 'video',
      'url' => 'url',
      default => 'teks',
  };
  $isVideoUrl = $oldType === 'video_url';
@endphp

@section('content')

{{-- ── Intro ── --}}
<section class="ds-section ds-section-first" aria-label="Pengantar">
    <div class="ds-container">
        <div class="ds-narrow ds-center">
            <h1 class="ds-hero-title">Periksa informasi sebelum membagikannya.</h1>
            <p class="ds-lead">Tempel teks, unggah gambar atau video, atau tempel tautan berita. Doksli memberikan indikasi <strong>Valid</strong>, <strong>Hoax</strong>, atau <strong>Meragukan</strong> beserta penjelasannya.</p>
            <div class="ds-actions ds-actions-center ds-actions-spaced">
                <a href="#cek-berita" class="ds-btn ds-btn-primary">Cek berita sekarang</a>
            </div>
        </div>
    </div>
</section>

{{-- ── Checker ── --}}
<section id="cek-berita" aria-label="Formulir cek berita">
    <div class="ds-content">
        <div class="ds-card" role="main">
            <div class="ds-page-header">
                <h2>Cek kebenaran berita</h2>
                <p>Pilih jenis input, lalu masukkan konten berita yang ingin diverifikasi.</p>
            </div>

            <div class="ds-tabs" role="tablist" aria-label="Jenis input berita">
                <button type="button" id="tab-teks" class="ds-tab" role="tab" data-tab="teks" aria-selected="{{ $activeTab === 'teks' ? 'true' : 'false' }}" aria-controls="panel-teks">Teks</button>
                <button type="button" id="tab-gambar" class="ds-tab" role="tab" data-tab="gambar" aria-selected="{{ $activeTab === 'gambar' ? 'true' : 'false' }}" aria-controls="panel-gambar">Gambar</button>
                <button type="button" id="tab-video" class="ds-tab" role="tab" data-tab="video" aria-selected="{{ $activeTab === 'video' ? 'true' : 'false' }}" aria-controls="panel-video">Video</button>
                <button type="button" id="tab-url" class="ds-tab" role="tab" data-tab="url" aria-selected="{{ $activeTab === 'url' ? 'true' : 'false' }}" aria-controls="panel-url">Tautan</button>
            </div>

            {{-- Panel: Teks --}}
            <div id="panel-teks" class="tab-content{{ $activeTab === 'teks' ? ' active' : '' }}" role="tabpanel" aria-labelledby="tab-teks">
                <form id="form-teks" action="{{ url('/deteksi') }}" method="POST" data-checker-form="teks">
                    @csrf
                    <input type="hidden" name="input_type" value="text">

                    @error('input_type')
                    <div class="ds-field">
                        <div class="ds-alert ds-alert-error" role="alert"><span>{{ $message }}</span></div>
                    </div>
                    @enderror
                    @error('website')
                    <div class="ds-field">
                        <div class="ds-alert ds-alert-error" role="alert"><span>{{ $message }}</span></div>
                    </div>
                    @enderror

                    <div class="ds-field">
                        <label class="ds-label" for="teks-input">Teks berita</label>
                        <textarea id="teks-input" name="raw_input" class="ds-input ds-textarea ds-textarea-lg" placeholder="Tempel atau ketik teks berita yang ingin diverifikasi di sini." required minlength="50" maxlength="50000" aria-describedby="teks-meta">{{ old('raw_input') }}</textarea>
                        @error('raw_input')
                        <p class="ds-error" role="alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                        <div class="ds-field-meta" id="teks-meta">
                            <span class="ds-hint">Minimal 50 karakter.</span>
                            <span class="ds-hint" id="teks-count" aria-live="polite">0 karakter</span>
                        </div>
                    </div>

                    <input type="text" name="website" class="ds-hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div class="ds-field">
                        <div class="ds-captcha">
                            <label class="ds-label" for="captcha-teks">Verifikasi keamanan: berapa {{ $captchaQuestion }}</label>
                            <input type="number" id="captcha-teks" name="captcha_answer" class="ds-input" placeholder="?" required inputmode="numeric" autocomplete="off">
                        </div>
                        @error('captcha_answer')
                        <p class="ds-error" role="alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <button type="submit" class="ds-btn ds-btn-primary ds-btn-block">Analisis Sekarang</button>
                </form>
            </div>

            {{-- Panel: Gambar --}}
            <div id="panel-gambar" class="tab-content{{ $activeTab === 'gambar' ? ' active' : '' }}" role="tabpanel" aria-labelledby="tab-gambar">
                <form id="form-gambar" action="{{ url('/deteksi') }}" method="POST" enctype="multipart/form-data" data-checker-form="gambar">
                    @csrf
                    <input type="hidden" name="input_type" value="image">

                    @error('input_type')
                    <div class="ds-field">
                        <div class="ds-alert ds-alert-error" role="alert"><span>{{ $message }}</span></div>
                    </div>
                    @enderror
                    @error('website')
                    <div class="ds-field">
                        <div class="ds-alert ds-alert-error" role="alert"><span>{{ $message }}</span></div>
                    </div>
                    @enderror

                    <div class="ds-field">
                        <span class="ds-label" id="gambar-label">Foto atau tangkapan layar berita</span>
                        <label class="ds-upload" for="gambar-input">
                            <input type="file" id="gambar-input" name="media_file" accept="image/*" required aria-labelledby="gambar-label">
                            <svg class="ds-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <span id="gambar-preview-label">
                                <span class="ds-upload-title" data-file-name>Klik atau seret gambar ke sini</span>
                                <span class="ds-upload-sub" data-file-size>PNG, JPG, WEBP, HEIC hingga 10 MB</span>
                            </span>
                            <img id="gambar-preview-img" class="upload-preview-img ds-hidden" src="" alt="Pratinjau gambar yang diunggah">
                        </label>
                        @error('media_file')
                        <p class="ds-error" role="alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                        <p class="ds-hint">Teks diekstraksi otomatis dengan OCR, lalu dianalisis model IndoBERT.</p>
                    </div>

                    <input type="text" name="website" class="ds-hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div class="ds-field">
                        <div class="ds-captcha">
                            <label class="ds-label" for="captcha-gambar">Verifikasi keamanan: berapa {{ $captchaQuestion }}</label>
                            <input type="number" id="captcha-gambar" name="captcha_answer" class="ds-input" placeholder="?" required inputmode="numeric" autocomplete="off">
                        </div>
                        @error('captcha_answer')
                        <p class="ds-error" role="alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <button type="submit" class="ds-btn ds-btn-primary ds-btn-block">Analisis Gambar</button>
                </form>
            </div>

            {{-- Panel: Video --}}
            <div id="panel-video" class="tab-content{{ $activeTab === 'video' ? ' active' : '' }}" role="tabpanel" aria-labelledby="tab-video">
                <form id="form-video" action="{{ url('/deteksi') }}" method="POST" enctype="multipart/form-data" data-checker-form="video">
                    @csrf
                    <input type="hidden" name="input_type" id="video-input-type" value="{{ $isVideoUrl ? 'video_url' : 'video' }}">

                    @error('input_type')
                    <div class="ds-field">
                        <div class="ds-alert ds-alert-error" role="alert"><span>{{ $message }}</span></div>
                    </div>
                    @enderror
                    @error('website')
                    <div class="ds-field">
                        <div class="ds-alert ds-alert-error" role="alert"><span>{{ $message }}</span></div>
                    </div>
                    @enderror

                    <div class="ds-field">
                        <div class="ds-actions" role="group" aria-label="Jenis input video">
                            <button type="button" id="video-tab-upload" class="ds-btn ds-btn-secondary ds-btn-sm{{ $isVideoUrl ? '' : ' is-active' }}" aria-pressed="{{ $isVideoUrl ? 'false' : 'true' }}">Unggah file</button>
                            <button type="button" id="video-tab-url" class="ds-btn ds-btn-secondary ds-btn-sm{{ $isVideoUrl ? ' is-active' : '' }}" aria-pressed="{{ $isVideoUrl ? 'true' : 'false' }}">Tautan video</button>
                        </div>
                    </div>

                    <div id="video-upload-panel" class="video-panel{{ $isVideoUrl ? '' : ' active' }}">
                        <div class="ds-field">
                            <span class="ds-label" id="video-label">File video</span>
                            <label class="ds-upload" for="video-file-input">
                                <input type="file" id="video-file-input" name="media_file" accept="video/*" aria-labelledby="video-label"{{ $isVideoUrl ? '' : ' required' }}>
                                <svg class="ds-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                                <span id="video-preview-label">
                                    <span class="ds-upload-title" data-file-name>Klik atau seret video ke sini</span>
                                    <span class="ds-upload-sub" data-file-size>MP4, MOV, AVI hingga 200 MB</span>
                                </span>
                            </label>
                            @error('media_file')
                            <p class="ds-error" role="alert">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                            @enderror
                        </div>
                    </div>

                    <div id="video-url-panel" class="video-panel{{ $isVideoUrl ? ' active' : '' }}">
                        <div class="ds-field">
                            <label class="ds-label" for="video-url-input">Tautan video</label>
                            <input type="url" id="video-url-input" name="source_url" class="ds-input" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('source_url') }}"{{ $isVideoUrl ? ' required' : '' }}>
                            @error('source_url')
                            <p class="ds-error" role="alert">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                            @enderror
                        </div>
                    </div>

                    <div class="ds-field">
                        <div class="ds-alert ds-alert-info" role="note">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span>Video membutuhkan waktu lebih lama karena audio ditranskripsi terlebih dahulu. Masuk agar hasilnya tersimpan di riwayat.</span>
                        </div>
                    </div>

                    <input type="text" name="website" class="ds-hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div class="ds-field">
                        <div class="ds-captcha">
                            <label class="ds-label" for="captcha-video">Verifikasi keamanan: berapa {{ $captchaQuestion }}</label>
                            <input type="number" id="captcha-video" name="captcha_answer" class="ds-input" placeholder="?" required inputmode="numeric" autocomplete="off">
                        </div>
                        @error('captcha_answer')
                        <p class="ds-error" role="alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <button type="submit" class="ds-btn ds-btn-primary ds-btn-block">Analisis Video</button>
                </form>
            </div>

            {{-- Panel: Tautan --}}
            <div id="panel-url" class="tab-content{{ $activeTab === 'url' ? ' active' : '' }}" role="tabpanel" aria-labelledby="tab-url">
                <form id="form-url" action="{{ url('/deteksi') }}" method="POST" data-checker-form="url">
                    @csrf
                    <input type="hidden" name="input_type" value="url">

                    @error('input_type')
                    <div class="ds-field">
                        <div class="ds-alert ds-alert-error" role="alert"><span>{{ $message }}</span></div>
                    </div>
                    @enderror
                    @error('website')
                    <div class="ds-field">
                        <div class="ds-alert ds-alert-error" role="alert"><span>{{ $message }}</span></div>
                    </div>
                    @enderror

                    <div class="ds-field">
                        <label class="ds-label" for="url-input">Tautan artikel berita</label>
                        <input type="url" id="url-input" name="source_url" class="ds-input" placeholder="https://www.contoh.id/berita/..." required value="{{ old('source_url') }}" aria-describedby="url-hint">
                        <p class="ds-hint" id="url-hint">Tempel tautan artikel lengkap. Konten artikel diekstraksi otomatis.</p>
                        @error('source_url')
                        <p class="ds-error" role="alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <div id="url-preview" class="ds-field ds-hidden" aria-live="polite">
                        <div class="ds-alert ds-alert-info" role="note">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            <span><strong id="url-domain"></strong><br><span id="url-full" class="ds-break"></span></span>
                        </div>
                    </div>

                    <input type="text" name="website" class="ds-hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div class="ds-field">
                        <div class="ds-captcha">
                            <label class="ds-label" for="captcha-url">Verifikasi keamanan: berapa {{ $captchaQuestion }}</label>
                            <input type="number" id="captcha-url" name="captcha_answer" class="ds-input" placeholder="?" required inputmode="numeric" autocomplete="off">
                        </div>
                        @error('captcha_answer')
                        <p class="ds-error" role="alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <button type="submit" class="ds-btn ds-btn-primary ds-btn-block">Analisis Tautan</button>
                </form>
            </div>

            {{-- Loading overlay (shown on submit; IDs preserved for checker.js) --}}
            <div id="loading-overlay" class="ds-hidden submit-loading" role="status" aria-live="assertive">
                <div class="submit-spinner-row">
                    <div class="ds-spinner" aria-label="Memproses..."></div>
                </div>
                <h3>Sedang Menganalisis...</h3>
                <div class="submit-steps" aria-label="Langkah proses">
                    <div class="progress-step active" id="step-1">
                        <div class="step-icon active" aria-hidden="true">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                        </div>
                        <span id="step-1-text" class="submit-step-label">Memproses input...</span>
                    </div>
                    <div class="progress-step pending" id="step-2">
                        <div class="step-icon pending" aria-hidden="true">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        </div>
                        <span class="submit-step-label">Klasifikasi BERT...</span>
                    </div>
                    <div class="progress-step pending" id="step-3">
                        <div class="step-icon pending" aria-hidden="true">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                        </div>
                        <span class="submit-step-label">Menyusun penjelasan...</span>
                    </div>
                </div>
                <p class="submit-note">
                    Mohon tunggu sebentar. Jangan tutup halaman ini.
                </p>
            </div>
        </div>

        <p class="ds-note">Hasil analisis bersifat <strong>indikatif (estimasi model)</strong>, bukan vonis kebenaran. Selalu verifikasi ke sumber tepercaya:
            <a href="https://www.kominfo.go.id" target="_blank" rel="noopener noreferrer">Kominfo</a>,
            <a href="https://www.mafindo.or.id" target="_blank" rel="noopener noreferrer">Mafindo</a>,
            <a href="https://cekfakta.com" target="_blank" rel="noopener noreferrer">CekFakta</a>.
        </p>
    </div>
</section>

{{-- ── Cara kerja singkat ── --}}
<section id="cara-kerja" class="ds-section" aria-label="Cara kerja singkat">
    <div class="ds-container">
        <div class="ds-page-header">
            <p class="ds-eyebrow">Cara kerja</p>
            <h2>Dari berita menjadi hasil dalam empat langkah</h2>
            <p>Alur yang sama untuk teks, gambar, video, dan tautan.</p>
        </div>
        <ol class="steps">
            <li class="step">
                <span class="step-num" aria-hidden="true">01</span>
                <div>
                    <h3>Kirim berita</h3>
                    <p>Tempel teks, unggah gambar atau video, atau tempel tautan artikel.</p>
                </div>
            </li>
            <li class="step">
                <span class="step-num" aria-hidden="true">02</span>
                <div>
                    <h3>Ekstraksi teks</h3>
                    <p>Gambar dibaca dengan OCR, audio video ditranskripsi, konten artikel diekstraksi otomatis.</p>
                </div>
            </li>
            <li class="step">
                <span class="step-num" aria-hidden="true">03</span>
                <div>
                    <h3>Klasifikasi IndoBERT</h3>
                    <p>Teks dianalisis model bahasa Indonesia menjadi Valid, Hoax, atau Meragukan.</p>
                </div>
            </li>
            <li class="step">
                <span class="step-num" aria-hidden="true">04</span>
                <div>
                    <h3>Hasil dan penjelasan</h3>
                    <p>Terima label, tingkat keyakinan, dan penjelasan yang mudah dipahami.</p>
                </div>
            </li>
        </ol>
        <div class="ds-actions ds-actions-spaced">
            <a class="ds-btn ds-btn-secondary" href="{{ url('/cara-kerja') }}">Pelajari selengkapnya</a>
        </div>
    </div>
</section>

{{-- ── Metodologi singkat ── --}}
<section class="ds-section" aria-label="Metodologi singkat">
    <div class="ds-content">
        <div class="ds-page-header">
            <p class="ds-eyebrow">Metodologi</p>
            <h2>Mengapa IndoBERT?</h2>
        </div>
        <div class="ds-prose">
            <p>IndoBERT adalah model bahasa yang memahami konteks kata dalam Bahasa Indonesia. Doksli menggunakannya untuk mengenali pola kebahasaan — gaya penulisan, pilihan kata, dan struktur kalimat — yang membedakan berita valid dan hoax. Setiap hasil dilengkapi tingkat keyakinan dan penjelasan, tetapi tetap bersifat estimasi, bukan kebenaran mutlak.</p>
            <p><a href="{{ url('/cara-kerja') }}">Pelajari metodologi lengkap di halaman Cara Kerja</a>.</p>
        </div>
    </div>
</section>

{{-- ── Ajakan ── --}}
<section class="ds-section" aria-label="Ajakan">
    <div class="ds-narrow ds-center">
        <h2>Jangan sebarkan sebelum dicek.</h2>
        <p class="ds-lead">Satu menit memeriksa lebih baik daripada ikut menyebarkan hoax.</p>
        <div class="ds-actions ds-actions-center ds-actions-spaced">
            <a href="#cek-berita" class="ds-btn ds-btn-primary">Cek berita sekarang</a>
            @guest
            <a href="{{ route('register') }}" class="ds-btn ds-btn-secondary">Daftar akun gratis</a>
            @endguest
        </div>
    </div>
</section>

@endsection
