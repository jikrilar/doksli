@extends('layouts.app')

@section('title', 'Pengaturan Akun — hoaxlin.id')
@section('description', 'Kelola profil, keamanan, dan data akun hoaxlin.id kamu.')

@section('content')
<div class="ds-section ds-section-first">
    <div class="ds-content">
        <div class="ds-page-header">
            <h1>Pengaturan akun</h1>
            <p>Kelola profil, keamanan, dan data akun kamu.</p>
        </div>

        <div class="settings-identity">
            <span class="settings-avatar" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <div class="settings-identity-text">
                <p class="settings-name">{{ auth()->user()->name }}</p>
                <p class="ds-hint">{{ auth()->user()->email }}</p>
                <p class="ds-hint">
                    Bergabung sejak {{ auth()->user()->created_at->format('d M Y') }} ·
                    @if(auth()->user()->hasVerifiedEmail())
                    <x-ui.badge type="valid">Terverifikasi</x-ui.badge>
                    @else
                    <x-ui.badge type="meragukan">Belum verifikasi</x-ui.badge>
                    @endif
                </p>
            </div>
        </div>

        <section aria-labelledby="profile-heading">
            <h2 id="profile-heading" class="settings-title">Profil</h2>
            <form method="POST" action="{{ route('profile.update') }}" id="profile-form" novalidate>
                @csrf
                @method('PATCH')

                <div class="ds-field">
                    <label class="ds-label" for="name">Nama lengkap</label>
                    <input type="text" id="name" name="name" class="ds-input" value="{{ old('name', auth()->user()->name) }}" required autocomplete="name" placeholder="Nama lengkap kamu" @if($errors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif>
                    @error('name')
                    <p class="ds-error" id="name-error" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                    @enderror
                </div>

                <div class="ds-field">
                    <label class="ds-label" for="email">Alamat email</label>
                    <input type="email" id="email" name="email" class="ds-input" value="{{ old('email', auth()->user()->email) }}" required autocomplete="email" placeholder="kamu@email.com" @if($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
                    @error('email')
                    <p class="ds-error" id="email-error" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                    @enderror
                </div>

                <button type="submit" class="ds-btn ds-btn-primary" id="save-profile-btn">Simpan perubahan</button>
            </form>
        </section>

        <hr class="ds-divider settings-sep">

        <section aria-labelledby="security-heading">
            <h2 id="security-heading" class="settings-title">Keamanan</h2>
            <form method="POST" action="{{ route('password.update') }}" id="password-form" novalidate>
                @csrf
                @method('PUT')

                <div class="ds-field">
                    <label class="ds-label" for="current_password">Kata sandi saat ini</label>
                    <div class="pwd-wrap">
                        <input type="password" id="current_password" name="current_password" class="ds-input" required autocomplete="current-password" placeholder="Kata sandi kamu sekarang" @if($errors->has('current_password')) aria-invalid="true" aria-describedby="current-password-error" @endif>
                        <button type="button" class="pwd-toggle" data-password-toggle="current_password" aria-label="Tampilkan kata sandi saat ini" aria-pressed="false">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('current_password')
                    <p class="ds-error" id="current-password-error" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                    @enderror
                </div>

                <div class="ds-field">
                    <label class="ds-label" for="password">Kata sandi baru</label>
                    <div class="pwd-wrap">
                        <input type="password" id="password" name="password" class="ds-input" required autocomplete="new-password" placeholder="Minimal 8 karakter" @if($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
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
                    <div id="strength-container" class="ds-hidden">
                        <div class="pwd-strength" data-score="0" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                        <p id="strength-label" class="ds-hint" aria-live="polite"></p>
                    </div>
                </div>

                <div class="ds-field">
                    <label class="ds-label" for="password_confirmation">Konfirmasi kata sandi baru</label>
                    <div class="pwd-wrap">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="ds-input" required autocomplete="new-password" placeholder="Ulangi kata sandi baru">
                        <button type="button" class="pwd-toggle" data-password-toggle="password_confirmation" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="ds-btn ds-btn-primary" id="save-password-btn">Perbarui kata sandi</button>
            </form>
        </section>

        <hr class="ds-divider settings-sep">

        <section aria-labelledby="danger-heading">
            <h2 id="danger-heading" class="settings-title settings-danger-title">Hapus akun</h2>
            <p class="ds-hint mb-4">Menghapus akun bersifat permanen dan tidak dapat dibatalkan. Seluruh riwayat pengecekan dan data kamu akan dihapus selamanya.</p>
            <button type="button" class="ds-btn ds-btn-danger" id="delete-account-btn" data-open-modal="delete-modal">Hapus akun saya</button>
        </section>
    </div>
</div>

<div id="delete-modal" class="ds-modal-overlay{{ $errors->has('password') ? '' : ' ds-hidden' }}" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
    <div class="ds-modal" role="document">
        <h2 class="ds-modal-title" id="delete-modal-title">Hapus akun secara permanen?</h2>
        <p class="ds-modal-desc">Tindakan ini tidak dapat dibatalkan. Seluruh data kamu, termasuk riwayat pengecekan, akan dihapus secara permanen.</p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            <div class="ds-field">
                <label class="ds-label" for="confirm-password">Konfirmasi dengan kata sandi</label>
                <input type="password" id="confirm-password" name="password" class="ds-input" placeholder="Kata sandi kamu" required autocomplete="current-password" @if($errors->has('password')) aria-invalid="true" aria-describedby="confirm-password-error" @endif>
                @error('password')
                <p class="ds-error" id="confirm-password-error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </p>
                @enderror
            </div>
            <div class="ds-modal-actions">
                <button type="button" class="ds-btn ds-btn-ghost" data-close-modal>Batal</button>
                <button type="submit" class="ds-btn ds-btn-danger">Ya, hapus akun</button>
            </div>
        </form>
    </div>
</div>
@endsection
