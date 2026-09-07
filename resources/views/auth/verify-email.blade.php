@extends('layouts.app')

@section('title', 'Verifikasi Email — hoaxlin.id')

@section('content')
<x-auth.card title="Verifikasi alamat email" description="Verifikasi email untuk mengakses riwayat dan mengirim umpan balik.">
    <div class="ds-prose mb-4">
        <p>Tautan verifikasi telah dikirim ke <strong>{{ auth()->user()->email }}</strong>.</p>
    </div>

    @if(session('status') === 'verification-link-sent')
    <div class="ds-alert ds-alert-success mb-4" role="status">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span>Tautan verifikasi baru telah dikirim.</span>
    </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" data-double-submit>
        @csrf
        <button type="submit" class="ds-btn ds-btn-primary ds-btn-block">Kirim ulang email verifikasi</button>
    </form>
    <x-slot:footer>
        <div class="ds-actions ds-actions-center">
            <a href="{{ route('profile') }}" class="ds-btn ds-btn-ghost ds-btn-sm">Ubah email</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="ds-btn ds-btn-ghost ds-btn-sm">Keluar</button>
            </form>
        </div>
    </x-slot:footer>
</x-auth.card>
@endsection
