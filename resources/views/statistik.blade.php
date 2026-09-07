@extends('layouts.app')

@section('title', 'Statistik Tren — hoaxlin.id')
@section('description', 'Tren pemeriksaan dan distribusi label deteksi hoax pada sistem.')

@section('content')
<div class="ds-section ds-section-first">
    <div class="ds-container">
        <div class="ds-page-header">
            <h1>Statistik tren</h1>
            <p>Tren jumlah pemeriksaan per bulan dan distribusi label hasil.</p>
        </div>

        @php
            $totalLabeled = ($byTopic['valid'] ?? 0) + ($byTopic['hoax'] ?? 0) + ($byTopic['meragukan'] ?? 0);
        @endphp
        <p class="ds-hint" role="status">{{ $totalLabeled }} hasil berlabel · {{ $byTopic['valid'] ?? 0 }} valid · {{ $byTopic['hoax'] ?? 0 }} hoax · {{ $byTopic['meragukan'] ?? 0 }} meragukan</p>

        <div class="ds-card mt-6">
            <h2 class="result-sub">Tren 12 bulan</h2>
            <noscript><p class="ds-hint">Aktifkan JavaScript untuk melihat grafik tren.</p></noscript>
            <div class="chart-wrap"><canvas id="trendChart" aria-label="Grafik garis tren pemeriksaan 12 bulan" role="img"></canvas></div>
        </div>

        <div class="ds-card mt-6">
            <h2 class="result-sub">Distribusi label</h2>
            <ul class="dist-list">
                @foreach(['valid' => 'Valid', 'hoax' => 'Hoax', 'meragukan' => 'Meragukan'] as $label => $display)
                @php $count = $byTopic[$label] ?? 0; @endphp
                <li>
                    <x-ui.badge type="{{ $label }}">{{ $display }}</x-ui.badge>
                    <strong>{{ $count }}</strong>
                    <span class="ds-hint">{{ $totalLabeled > 0 ? round($count / $totalLabeled * 100).'%' : '–' }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const trend = @json($trend);
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: trend.map(t => t.label),
        datasets: [
            { label: 'Total', data: trend.map(t => t.total), borderColor: '#1d4ed8', backgroundColor: 'rgba(29,78,216,0.10)', tension: 0.3 },
            { label: 'Valid', data: trend.map(t => t.valid), borderColor: '#15803d', backgroundColor: 'rgba(21,128,61,0.08)', tension: 0.3 },
            { label: 'Hoax', data: trend.map(t => t.hoax), borderColor: '#b91c1c', backgroundColor: 'rgba(185,28,28,0.08)', tension: 0.3 },
        ]
    },
    options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { labels: { color: '#52525b' } } }, scales: { x: { ticks: { color: '#71717a' } }, y: { ticks: { color: '#71717a' } } } }
});
</script>
@endpush
