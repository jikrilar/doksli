@extends('layouts.app')

@section('title', 'Daftar Akun — hoaxlin.id')
@section('description', 'Buat akun gratis di hoaxlin.id untuk menyimpan riwayat pengecekan berita.')

@section('content')
<x-auth.card title="Buat akun gratis" description="Simpan riwayat pengecekan dan beri umpan balik hasil.">
    <form method="POST" action="{{ route('register.store') }}" id="register-form" data-double-submit novalidate>
        @csrf

        <div class="ds-field">
            <label class="ds-label" for="name">Nama lengkap</label>
            <input type="text" id="name" name="name" class="ds-input" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama kamu" @if($errors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif>
            @error('name')
            <p class="ds-error" id="name-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $message }}</span>
            </p>
            @enderror
        </div>

        <div class="ds-field">
            <label class="ds-label" for="email">Alamat email</label>
            <input type="email" id="email" name="email" class="ds-input" value="{{ old('email') }}" required autocomplete="email" placeholder="kamu@email.com" @if($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
            @error('email')
            <p class="ds-error" id="email-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $message }}</span>
            </p>
            @enderror
        </div>

        <div class="ds-field">
            <label class="ds-label" for="password">Kata sandi</label>
            <div class="pwd-wrap">
                <input type="password" id="password" name="password" class="ds-input" required autocomplete="new-password" placeholder="Minimal 8 karakter" @if($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
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
            <div id="register-strength-container" class="ds-hidden">
                <div class="pwd-strength" data-score="0" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                <p id="register-strength-label" class="ds-hint" aria-live="polite"></p>
            </div>
        </div>

        <div class="ds-field">
            <label class="ds-label" for="password_confirmation">Konfirmasi kata sandi</label>
            <div class="pwd-wrap">
                <input type="password" id="password_confirmation" name="password_confirmation" class="ds-input" required autocomplete="new-password" placeholder="Ulangi kata sandi" aria-describedby="confirm-match">
                <button type="button" class="pwd-toggle" data-password-toggle="password_confirmation" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            <p id="confirm-match" class="ds-hint ds-hidden" aria-live="polite"></p>
        </div>

        <div class="ds-field">
            <div class="ds-check-row">
                <input type="checkbox" id="terms" name="terms" class="ds-checkbox" required>
                <label for="terms">Saya menyetujui <a class="ds-link" href="{{ url('/kebijakan-privasi') }}">Kebijakan Privasi</a> dan memahami bahwa hasil deteksi bersifat indikatif.</label>
            </div>
            @error('terms')
            <p class="ds-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $message }}</span>
            </p>
            @enderror
        </div>

        <button type="submit" class="ds-btn ds-btn-primary ds-btn-block" id="register-btn">Buat akun sekarang</button>
    </form>
    <x-slot:footer>
        <p>Sudah punya akun? <a class="ds-link" href="{{ route('login') }}">Masuk ke akun</a></p>
    </x-slot:footer>
</x-auth.card>
@endsection
