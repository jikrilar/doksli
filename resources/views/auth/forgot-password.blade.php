@extends('layouts.app')

@section('title', 'Lupa Kata Sandi — hoaxlin.id')
@section('description', 'Reset kata sandi akun hoaxlin.id kamu melalui email.')

@section('content')
<x-auth.card title="Lupa kata sandi?" description="Masukkan email yang terdaftar. Kami akan mengirimkan tautan untuk mereset kata sandimu.">
    <div class="mb-4">
        <a href="{{ route('login') }}" class="ds-btn ds-btn-ghost ds-btn-sm" aria-label="Kembali ke halaman masuk">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali masuk
        </a>
    </div>

    @if(session('status'))
    <div class="ds-alert ds-alert-success mb-4" role="status">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span>{{ session('status') }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="forgot-form" data-double-submit novalidate>
        @csrf

        <div class="ds-field">
            <label class="ds-label" for="email">Alamat email</label>
            <input type="email" id="email" name="email" class="ds-input" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="kamu@email.com" @if($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @else aria-describedby="email-hint" @endif>
            @error('email')
            <p class="ds-error" id="email-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $message }}</span>
            </p>
            @else
            <p class="ds-hint" id="email-hint">Masukkan email yang kamu gunakan saat mendaftar.</p>
            @enderror
        </div>

        <button type="submit" class="ds-btn ds-btn-primary ds-btn-block" id="reset-btn">Kirim tautan reset</button>
    </form>
    <x-slot:footer>
        <p>Ingat kata sandimu? <a class="ds-link" href="{{ route('login') }}">Masuk sekarang</a></p>
        <p>Belum punya akun? <a class="ds-link" href="{{ route('register') }}">Daftar gratis</a></p>
    </x-slot:footer>
</x-auth.card>
@endsection
