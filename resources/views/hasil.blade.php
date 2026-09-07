@extends('layouts.app')

@section('title', 'Hasil Deteksi — hoaxlin.id')
@section('description', 'Lihat hasil analisis deteksi hoax dari sistem AI BERT.')

@section('content')
<div class="ds-section ds-section-first">
    <div class="ds-content">
        <div class="result-flow">

            <div class="result-back">
                <a href="{{ request()->routeIs('riwayat.show') ? route('riwayat') : url('/') }}" class="ds-btn ds-btn-ghost ds-btn-sm" aria-label="Kembali">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    {{ request()->routeIs('riwayat.show') ? 'Kembali ke Riwayat' : 'Kembali ke Beranda' }}
                </a>
            </div>

            @if(! $result)
            {{-- ── Processing state (Livewire polling + JS/no-JS fallbacks) ── --}}
            <div class="ds-card" role="main" aria-label="Status pemrosesan">
                <div id="submission-progress-wrapper" data-submission-id="{{ $submission->id }}" data-status-url="{{ route('hasil.status', $submission->id) }}">
                    <livewire:submission-progress :submission="$submission" />
                    <noscript>
                        <div class="ds-note">
                            JavaScript dinonaktifkan — <a href="{{ route('hasil', $submission->id) }}">muat ulang</a> untuk melihat progress terbaru.
                        </div>
                        <meta http-equiv="refresh" content="5;url={{ route('hasil', $submission->id) }}">
                    </noscript>
                </div>

                <hr class="ds-divider mt-6">

                <h2 class="result-sub mt-6">Input yang dikirim</h2>
                <dl class="tech-list">
                    <div>
                        <dt>Jenis input</dt>
                        <dd>{{ ucfirst($submission->input_type) }}</dd>
                    </div>
                    <div>
                        <dt>Dikirim</dt>
                        <dd>{{ $submission->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>

                @if($submission->raw_input)
                    <p class="result-sub mt-6">Teks dikirim</p>
                    <div class="evidence ds-prewrap">{{ $submission->raw_input }}</div>
                @elseif($submission->source_url)
                    <p class="result-sub mt-6">URL dikirim</p>
                    <div class="evidence ds-break">
                        <a class="ds-link" href="{{ $submission->source_url }}" target="_blank" rel="noopener noreferrer">{{ $submission->source_url }}</a>
                    </div>
                @elseif($submission->media_path)
                    <p class="result-sub mt-6">Media tersimpan</p>
                    <div class="evidence">
                        <p class="mb-3">File {{ $submission->input_type }} berhasil disimpan secara privat.</p>
                        @if($submission->input_type === 'image')
                            <p class="mb-3"><img src="{{ route('hasil.media', $submission->id) }}" alt="Pratinjau media yang diunggah" class="evidence-media"></p>
                        @endif
                        <a class="ds-link" href="{{ route('hasil.media', $submission->id) }}" target="_blank" rel="noopener">Lihat / unduh media (tautan privat 5 menit)</a>
                    </div>
                @endif

                @if($submission->failure_reason)
                    <div class="ds-alert ds-alert-error mt-6" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $submission->failure_reason }}</span>
                    </div>
                @endif
            </div>
            @else
            {{-- ── Completed result (DS §29 hierarchy) ── --}}
            @php
                $label = $result->label ?? 'meragukan';
                $labelLower = strtolower($label);
                $labelMap = ['valid' => 'Valid', 'hoax' => 'Hoax', 'meragukan' => 'Meragukan'];
                $labelDisplay = $labelMap[$labelLower] ?? $label;
                $interpretations = [
                    'valid' => 'Model menemukan pola yang lebih konsisten dengan berita valid.',
                    'hoax' => 'Model menemukan pola yang lebih konsisten dengan konten hoax.',
                    'meragukan' => 'Model tidak cukup yakin untuk menentukan valid atau hoax.',
                ];
                $interpretation = $interpretations[$labelLower] ?? '';
                $confidence = (float) ($result->confidence_score ?? 0);
                $confidencePct = round($confidence * 100);
                $inputTypeLabels = [
                    'text' => 'Teks',
                    'image' => 'Gambar (OCR)',
                    'video' => 'Video (transkripsi)',
                    'video_url' => 'Video URL (transkripsi)',
                    'url' => 'Tautan artikel',
                ];
                $inputTypeLabel = $inputTypeLabels[$submission->input_type] ?? $submission->input_type;
            @endphp
            <div class="ds-card" role="main" aria-label="Hasil deteksi berita">
                <p class="ds-eyebrow">Hasil pemeriksaan</p>
                <x-ui.badge type="{{ $labelLower }}" class="ds-badge-lg">{{ $labelDisplay }}</x-ui.badge>
                @if($interpretation)
                <p class="result-verdict-sub">{{ $interpretation }}</p>
                @endif

                <div class="result-confidence">
                    <div class="ds-progress-row">
                        <span>Tingkat keyakinan model</span>
                        <strong aria-label="{{ $confidencePct }} persen">{{ $confidencePct }}%</strong>
                    </div>
                    <div class="ds-confidence-track" role="progressbar" aria-valuenow="{{ $confidencePct }}" aria-valuemin="0" aria-valuemax="100" aria-label="Tingkat keyakinan model {{ $confidencePct }} persen">
                        <div class="ds-confidence-fill is-{{ $labelLower }}" style="width: {{ $confidencePct }}%;"></div>
                    </div>
                </div>

                <hr class="ds-divider mt-6">

                <h2 class="result-sub mt-6">Penjelasan hasil</h2>
                <div class="ds-prose">
                    <p>{{ $result->explanation ?? 'Berdasarkan analisis model terhadap teks yang diberikan, ditemukan karakteristik yang konsisten dengan kategori yang terdeteksi. Indikator kebahasaan seperti gaya penulisan, pilihan kata, dan struktur kalimat dipertimbangkan dalam klasifikasi ini.' }}</p>
                </div>

                <h2 class="result-sub mt-6">Konten yang dianalisis</h2>
                @if(($submission->input_type ?? 'text') === 'text' && filled($submission->raw_input))
                    <div class="evidence ds-prewrap">{{ $submission->raw_input }}</div>
                @else
                    @if(filled($submission->extracted_text))
                        <div class="evidence ds-prewrap">{{ $submission->extracted_text }}</div>
                        <p class="ds-hint mt-2">Teks di atas diekstraksi otomatis dari input {{ strtolower($inputTypeLabel) }} sebelum dianalisis.</p>
                    @endif
                    @if(filled($submission->source_url))
                        <div class="evidence ds-break mt-4">
                            <a class="ds-link" href="{{ $submission->source_url }}" target="_blank" rel="noopener noreferrer">{{ $submission->source_url }}</a>
                        </div>
                    @endif
                    @if(filled($submission->media_path))
                        <div class="evidence mt-4">
                            @if($submission->input_type === 'image')
                                <p class="mb-3"><img src="{{ route('hasil.media', $submission->id) }}" alt="Pratinjau media yang diunggah" class="evidence-media"></p>
                            @endif
                            <a class="ds-link" href="{{ route('hasil.media', $submission->id) }}" target="_blank" rel="noopener">Lihat / unduh media (tautan privat 5 menit)</a>
                        </div>
                    @endif
                @endif

                @if(filled($submission->translated_text))
                    <div class="ds-alert ds-alert-info mt-6" role="note">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span>
                            <strong>Diterjemahkan ke Bahasa Indonesia sebelum klasifikasi</strong> ({{ $submission->translation_model }}{{ $submission->translation_cached ? ' · dari cache' : '' }}).
                            <span class="ds-break">{{ $submission->translated_text }}</span>
                        </span>
                    </div>
                @endif

                <h2 class="result-sub mt-6">Verifikasi hasil ini</h2>
                <div class="ds-alert ds-alert-warning" role="note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <span>Hasil ini adalah <strong>estimasi model</strong>, bukan vonis kebenaran. Bandingkan dengan sumber independen —
                        <a class="ds-link" href="https://www.kominfo.go.id" target="_blank" rel="noopener noreferrer">Kominfo</a>,
                        <a class="ds-link" href="https://www.mafindo.or.id" target="_blank" rel="noopener noreferrer">Mafindo</a>,
                        <a class="ds-link" href="https://cekfakta.com" target="_blank" rel="noopener noreferrer">CekFakta</a> —
                        sebelum menyimpulkan atau membagikan.</span>
                </div>

                <details class="ds-tech mt-6">
                    <summary>Detail teknis</summary>
                    <dl class="tech-list">
                        <div>
                            <dt>ID submission</dt>
                            <dd>{{ $submission->id }}</dd>
                        </div>
                        <div>
                            <dt>Jenis input</dt>
                            <dd>{{ $inputTypeLabel }}</dd>
                        </div>
                        <div>
                            <dt>Status</dt>
                            <dd>{{ $submission->status }}</dd>
                        </div>
                        <div>
                            <dt>Model</dt>
                            <dd>{{ $result->model_version ?? 'tidak tercatat' }}</dd>
                        </div>
                        <div>
                            <dt>Prompt</dt>
                            <dd>v{{ config('services.openai.prompt_version', '1.0') }}</dd>
                        </div>
                        @if(filled($submission->translation_model))
                        <div>
                            <dt>Penerjemah</dt>
                            <dd>{{ $submission->translation_model }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt>Dikirim</dt>
                            <dd>{{ $submission->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </details>
            </div>
            @endif

            @if($result)
            {{-- ── Feedback ── --}}
            <div class="ds-card" role="region" aria-label="Umpan balik hasil">
                <h2 class="result-sub">Apakah hasil ini akurat?</h2>
                <p class="ds-hint mb-4">Umpan balikmu membantu meningkatkan akurasi model.</p>

                @auth
                    @if($feedback ?? null)
                    <div class="ds-alert ds-alert-success" role="status">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span>
                            <strong>{{ $feedback->is_correct ? 'Prediksi ditandai benar' : 'Prediksi ditandai tidak benar' }}</strong>
                            @if($feedback->comment)
                            <br>{{ $feedback->comment }}
                            @endif
                            <br><span class="ds-hint">Dikirim {{ $feedback->created_at->format('d M Y, H:i') }}</span>
                        </span>
                    </div>
                    @elseif(auth()->id() === $submission->user_id)
                    <form action="{{ route('feedback') }}" method="POST" id="feedback-form">
                        @csrf
                        <input type="hidden" name="submission_id" value="{{ $submission->id ?? '' }}">
                        <input type="hidden" name="is_correct" id="feedback-is-correct" value="">

                        <div class="feedback-choices" role="group" aria-label="Pilih umpan balik">
                            <button type="button" id="fb-correct" class="ds-btn ds-btn-secondary" aria-pressed="false">Ya, sudah benar</button>
                            <button type="button" id="fb-wrong" class="ds-btn ds-btn-secondary" aria-pressed="false">Tidak akurat</button>
                        </div>

                        @error('is_correct')
                        <p class="ds-error mb-4" role="alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror

                        <div id="feedback-comment-area" class="ds-hidden">
                            <div class="ds-field">
                                <label class="ds-label" for="feedback-comment">Komentar (opsional)</label>
                                <textarea id="feedback-comment" name="comment" class="ds-textarea" maxlength="1000" placeholder="Tambahkan komentar tentang hasil prediksi...">{{ old('comment') }}</textarea>
                                @error('comment')
                                <p class="ds-error" role="alert">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    <span>{{ $message }}</span>
                                </p>
                                @enderror
                            </div>
                            <button type="submit" class="ds-btn ds-btn-primary">Kirim umpan balik</button>
                        </div>
                    </form>
                    @else
                    <div class="ds-alert ds-alert-info" role="note">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span>Umpan balik hanya dapat diberikan oleh pemilik submission.</span>
                    </div>
                    @endif
                @else
                <div class="ds-alert ds-alert-info" role="note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span><a class="ds-link" href="{{ route('login') }}">Masuk ke akun</a> untuk memberikan umpan balik dan membantu meningkatkan akurasi model.</span>
                </div>
                @endauth
            </div>
            @endif

            <div class="ds-actions">
                <a href="{{ url('/') }}#cek-berita" class="ds-btn ds-btn-secondary">Cek berita lain</a>
                @if($result)
                <a href="{{ route('hasil.pdf', $submission->id) }}" class="ds-btn ds-btn-secondary">Unduh PDF</a>
                @else
                <button type="button" id="print-btn" class="ds-btn ds-btn-secondary">Cetak / simpan PDF</button>
                @endif
            </div>
            @auth
            <p class="ds-note"><a href="{{ route('riwayat') }}">Lihat riwayat</a> · <a href="{{ route('riwayat.csv') }}">Unduh riwayat (CSV)</a></p>
            @endauth

        </div>
    </div>
</div>
@endsection
