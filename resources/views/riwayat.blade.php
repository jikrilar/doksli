@extends('layouts.app')

@section('title', 'Riwayat Pengecekan — hoaxlin.id')
@section('description', 'Lihat riwayat pengecekan berita yang pernah kamu lakukan.')

@section('content')
<div class="ds-section ds-section-first">
    <div class="ds-container">
        <div class="ds-page-header">
            <h1>Riwayat pemeriksaan</h1>
            <p>Berita yang pernah kamu kirim untuk dianalisis.</p>
        </div>

        <form method="GET" action="{{ route('riwayat') }}" class="history-filters" role="search" aria-label="Cari dan filter riwayat">
            <div class="history-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <label class="ds-visually-hidden" for="history-search">Cari riwayat</label>
                <input type="search" id="history-search" name="search" value="{{ request('search') }}" class="ds-input" placeholder="Cari teks atau URL...">
            </div>

            <label class="ds-visually-hidden" for="filter-type">Filter jenis input</label>
            <select id="filter-type" name="input_type" class="ds-input" aria-label="Filter jenis input">
                <option value="">Semua jenis</option>
                @foreach(['text' => 'Teks', 'image' => 'Gambar', 'video' => 'Video', 'url' => 'URL'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('input_type') === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <label class="ds-visually-hidden" for="filter-status">Filter status</label>
            <select id="filter-status" name="status" class="ds-input" aria-label="Filter status">
                <option value="">Semua status</option>
                @foreach(['pending' => 'Menunggu', 'processing' => 'Diproses', 'completed' => 'Selesai', 'failed' => 'Gagal'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <label class="ds-visually-hidden" for="history-sort">Urutkan riwayat</label>
            <select id="history-sort" name="sort" class="ds-input" aria-label="Urutkan riwayat">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Terbaru</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Terlama</option>
                <option value="confidence" @selected(request('sort') === 'confidence')>Keyakinan tertinggi</option>
            </select>

            <button type="submit" class="ds-btn ds-btn-primary ds-btn-sm">Terapkan</button>
            @if(request()->hasAny(['search', 'label', 'input_type', 'status', 'sort']))
                <a href="{{ route('riwayat') }}" class="ds-btn ds-btn-ghost ds-btn-sm">Reset</a>
            @endif
        </form>

        <div class="history-labels" role="group" aria-label="Filter berdasarkan label hasil">
            @php $queryWithoutLabel = request()->except(['label', 'page']); @endphp
            @foreach(['' => 'Semua hasil', 'valid' => 'Valid', 'hoax' => 'Hoax', 'meragukan' => 'Meragukan'] as $value => $label)
                <a href="{{ route('riwayat', $value === '' ? $queryWithoutLabel : [...$queryWithoutLabel, 'label' => $value]) }}"
                   class="nav-link{{ request('label', '') === $value ? ' active' : '' }}"
                   @if(request('label', '') === $value) aria-current="true" @endif>{{ $label }}</a>
            @endforeach
        </div>

        @if(isset($submissions) && count($submissions) > 0)
        <div class="ds-table-wrap">
            <table class="ds-table history-table">
                <thead>
                    <tr>
                        <th scope="col">Input</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Hasil</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col"><span class="ds-visually-hidden">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                    @php
                        $result = $submission->detectionResult;
                        $label = $result ? strtolower($result->label) : null;
                        $labelMap = ['valid' => 'Valid', 'hoax' => 'Hoax', 'meragukan' => 'Meragukan'];
                        $statusMap = ['pending' => 'Menunggu', 'processing' => 'Diproses', 'completed' => 'Selesai', 'failed' => 'Gagal'];
                        $typeMap = ['text' => 'Teks', 'image' => 'Gambar', 'video' => 'Video', 'url' => 'URL'];
                        $preview = $submission->raw_input ?? $submission->source_url ?? 'Input media';
                    @endphp
                    <tr>
                        <td data-label="Input"><span class="history-input" title="{{ $preview }}">{{ Str::limit($preview, 70) }}</span></td>
                        <td data-label="Jenis">{{ $typeMap[$submission->input_type] ?? $submission->input_type }}</td>
                        <td data-label="Hasil">
                            @if($result)
                                <x-ui.badge type="{{ $label }}">{{ $labelMap[$label] ?? $label }}</x-ui.badge>
                                @if($result?->model_version)
                                    <div class="history-model">v{{ $result->model_version }} · p{{ config('services.openai.prompt_version', '1.0') }}</div>
                                @endif
                            @else
                                {{ $statusMap[$submission->status] ?? ucfirst($submission->status) }}
                            @endif
                        </td>
                        <td data-label="Tanggal">
                            <time datetime="{{ $submission->created_at->toDateString() }}">{{ $submission->created_at->format('d M Y') }}</time>
                            <div class="history-time">{{ $submission->created_at->format('H:i') }}</div>
                        </td>
                        <td data-label="Aksi"><a class="ds-link" href="{{ route('riwayat.show', $submission) }}">Detail</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($submissions->hasPages())
        <nav class="history-pagination" aria-label="Navigasi halaman riwayat">
            {{ $submissions->links() }}
        </nav>
        @endif

        <p class="ds-hint history-summary" role="status">{{ $stats['valid'] + $stats['hoax'] + $stats['meragukan'] }} pemeriksaan · {{ $stats['valid'] }} valid · {{ $stats['hoax'] }} hoax · {{ $stats['meragukan'] }} meragukan</p>
        @else
        @php $isFiltered = request()->hasAny(['search', 'label', 'input_type', 'status']); @endphp
        <x-ui.empty-state
            title="{{ $isFiltered ? 'Riwayat tidak ditemukan' : 'Belum ada riwayat pemeriksaan' }}"
            description="{{ $isFiltered ? 'Coba ubah kata kunci atau filter yang digunakan.' : 'Pemeriksaan yang kamu lakukan akan muncul di sini.' }}">
            @if($isFiltered)
            <x-ui.button variant="secondary" href="{{ route('riwayat') }}">Reset filter</x-ui.button>
            @else
            <x-ui.button variant="primary" href="{{ url('/') }}#cek-berita">Cek berita sekarang</x-ui.button>
            @endif
        </x-ui.empty-state>
        @endif
    </div>
</div>
@endsection
