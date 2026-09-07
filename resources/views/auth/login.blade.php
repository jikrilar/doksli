@extends('layouts.app')

@section('title', 'Masuk — hoaxlin.id')
@section('description', 'Masuk ke akun hoaxlin.id untuk mengakses riwayat pengecekan berita.')

@section('content')
<x-auth.card title="Selamat datang kembali" description="Masuk untuk mengakses riwayat dan umpan balik.">
    <form method="POST" action="{{ route('login.store') }}" id="login-form" data-double-submit novalidate>
        @csrf

        <div class="ds-field">
            <label class="ds-label" for="email">Alamat email</label>
            <input type="email" id="email" name="email" class="ds-input" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="kamu@email.com" @if($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
            @error('email')
            <p class="ds-error" id="email-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $message }}</span>
            </p>
            @enderror
        </div>

        <div class="ds-field">
            <div class="auth-row">
                <label class="ds-label" for="password">Kata sandi</label>
                @if(Route::has('password.request'))
                <a class="ds-link" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
            </div>
            <div class="pwd-wrap">
                <input type="password" id="password" name="password" class="ds-input" required autocomplete="current-password" placeholder="Kata sandi kamu" @if($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
                <button type="button" class="pwd-toggle" data-password-toggle="password" aria-label="Tampilkan kata sandi" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            @error('password')
            <p class="ds-error" id="password-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $message }}</span>
            </p>
            @enderror
        </div>

        <div class="ds-field">
            <div class="ds-check-row">
                <input type="checkbox" id="remember_me" name="remember" class="ds-checkbox">
                <label for="remember_me">Ingat saya selama 30 hari</label>
            </div>
        </div>

        <button type="submit" class="ds-btn ds-btn-primary ds-btn-block" id="login-btn">Masuk ke akun</button>
    </form>
    <x-slot:footer>
        <p>Belum punya akun? <a class="ds-link" href="{{ route('register') }}">Daftar gratis</a></p>
        <p><a class="ds-link" href="{{ url('/') }}">Lanjutkan sebagai tamu</a></p>
    </x-slot:footer>
</x-auth.card>
@endsection
