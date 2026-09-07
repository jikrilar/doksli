{{-- Livewire processing progress (UI-v2 Phase 4).
  Contracts preserved: wire:key, conditional wire:poll.2s.visible, role=progressbar
  with aria values, aria-live percentage, data-stage-label hooks for the vanilla
  JS fallback, auto-reload on completion. Styling only. --}}
<div
    wire:key="submission-progress-{{ $submission->id }}"
    role="region"
    aria-label="Progress pemrosesan"
>
    <div
        @if(! $isCompleted && ! $isFailed)
            wire:poll.2s.visible="refreshProgress"
        @endif
    >
    @if($isFailed)
        <span class="ds-progress-icon is-failed" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </span>
    @elseif($isCompleted)
        <span class="ds-progress-icon is-done" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
        </span>
    @else
        <span class="ds-spinner" role="status" aria-label="Memproses"></span>
    @endif

    <p class="ds-eyebrow mt-4">Status: <span data-stage-label>{{ $isFailed ? 'Gagal' : ($isCompleted ? 'Selesai' : $stageLabel) }}</span></p>

    <h2 class="ds-progress-title">
        {{ $isFailed ? 'Pemrosesan Gagal' : ($isCompleted ? 'Analisis Selesai' : 'Sedang Menganalisis') }}
    </h2>

    <p class="ds-progress-desc">
        @if($isFailed)
            Terjadi kendala saat memproses input. Silakan coba kirim ulang atau hubungi administrator jika masalah berlanjut.
        @elseif($isCompleted)
            Analisis telah selesai. Hasil deteksi akan ditampilkan di bawah.
        @else
            Input kamu sedang diproses. Tahapan saat ini: <strong data-stage-label>{{ $stageLabel }}</strong> — halaman ini akan diperbarui otomatis.
        @endif
    </p>

    <div class="ds-progress-meter">
        <div class="ds-progress-row">
            <span>Progress</span>
            <span aria-live="polite">{{ $progress }}%</span>
        </div>
        <div
            class="ds-confidence-track"
            role="progressbar"
            aria-valuenow="{{ $progress }}"
            aria-valuemin="0"
            aria-valuemax="100"
            aria-label="Progress pemrosesan {{ $progress }} persen, tahap {{ $stageLabel }}"
        >
            <div
                class="ds-progress-fill{{ $isFailed ? ' is-failed' : ($isCompleted ? ' is-done' : '') }}"
                style="width: {{ $progress }}%;"
            ></div>
        </div>
        <div class="ds-progress-stages" aria-hidden="true">
            @php
                $stages = [
                    ['label' => 'Antrean', 'pct' => 0],
                    ['label' => 'Ekstraksi', 'pct' => 25],
                    ['label' => 'Klasifikasi', 'pct' => 60],
                    ['label' => 'Penjelasan', 'pct' => 85],
                    ['label' => 'Selesai', 'pct' => 100],
                ];
            @endphp
            @foreach($stages as $s)
                <span class="{{ $progress >= $s['pct'] ? 'is-on' : '' }}">{{ $s['label'] }}</span>
            @endforeach
        </div>
    </div>

    @if($isCompleted)
        <p class="mt-5">
            <a href="{{ route('hasil', $submission->id) }}" class="ds-btn ds-btn-primary" wire:navigate>
                Lihat Hasil
            </a>
        </p>
        <script>
            // Auto-reload once when completed to show the full result (if still on polling view)
            if (! window.__submissionCompletedReloaded) {
                window.__submissionCompletedReloaded = true;
                setTimeout(() => window.location.reload(), 800);
            }
        </script>
    @endif
    </div>
</div>
