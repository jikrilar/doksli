@extends('layouts.app')

@section('title', 'Reset Kata Sandi — hoaxlin.id')

@section('content')
<x-auth.card title="Buat kata sandi baru" description="Gunakan kata sandi baru untuk akun kamu.">
    <form method="POST" action="{{ route('password.store') }}" id="reset-form" data-double-submit novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="ds-field">
            <label class="ds-label" for="email">Alamat email</label>
            <input id="email" class="ds-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="email" placeholder="kamu@email.com" @if($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
            @error('email')
            <p class="ds-error" id="email-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $message }}</span>
            </p>
            @enderror
        </div>

        <div class="ds-field">
            <label class="ds-label" for="password">Kata sandi baru</label>
            <div class="pwd-wrap">
                <input id="password" class="ds-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" @if($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
                <button type="button" class="pwd-toggle" data-password-toggle="password" aria-label="Tampilkan kata sandi baru" aria-pressed="false">
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
            <label class="ds-label" for="password_confirmation">Konfirmasi kata sandi</label>
            <div class="pwd-wrap">
                <input id="password_confirmation" class="ds-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi baru">
                <button type="button" class="pwd-toggle" data-password-toggle="password_confirmation" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="ds-btn ds-btn-primary ds-btn-block">Reset kata sandi</button>
    </form>
    <x-slot:footer>
        <p>Ingat kata sandimu? <a class="ds-link" href="{{ route('login') }}">Masuk sekarang</a></p>
    </x-slot:footer>
</x-auth.card>
@endsection
