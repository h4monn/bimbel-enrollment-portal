@extends('layouts.public')

@section('title', 'Masuk Akun Wali atau Siswa — Bimbel Cendekia')
@section('meta_description', 'Masuk dengan email atau akun Google untuk mengisi formulir pendaftaran kelas dan memeriksa status pembayaran.')

@section('content')
<div class="wrap">
  <div class="auth-box">
    <div class="auth-tabs">
      <span class="is-active">Masuk</span>
      <a href="{{ route('register') }}">Daftar akun baru</a>
    </div>

    <h1 style="font-size: var(--t-h2); margin-bottom: var(--s-2);">Masuk ke akun pendaftar</h1>
    <p class="t-small muted" style="margin-bottom: var(--s-5);">Gunakan akun yang terdaftar untuk mendaftar kelas atau melihat riwayat tagihan.</p>

    <a href="{{ route('auth.google') }}" class="btn-google">
      <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17Z"/>
        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.11-6.72-4.96H1.29v3.14C3.26 21.3 7.31 24 12 24Z"/>
        <path fill="#FBBC05" d="M5.28 14.24c-.24-.72-.38-1.49-.38-2.24s.14-1.52.38-2.24V6.62H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.38l3.99-3.14Z"/>
        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.99 3.14c.95-2.85 3.6-4.96 6.72-4.96Z"/>
      </svg>
      Lanjutkan dengan Google
    </a>

    <div class="divider-row">atau masuk dengan email</div>

    <form method="POST" action="{{ route('login') }}" class="form" style="grid-template-columns: 1fr;">
      @csrf
      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" @error('email') aria-invalid="true" aria-describedby="err-email" @enderror>
        @error('email') <span id="err-email" class="field__error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <div style="display:flex;justify-content:space-between;align-items:baseline;">
          <label for="password">Password</label>
          <a href="{{ route('password.request') }}" class="t-small" style="color:var(--action);text-decoration:underline;text-underline-offset:3px;">Lupa password</a>
        </div>
        <input id="password" name="password" type="password" required autocomplete="current-password" @error('password') aria-invalid="true" aria-describedby="err-password" @enderror>
        @error('password') <span id="err-password" class="field__error">{{ $message }}</span> @enderror
      </div>

      <label class="check-row">
        <input type="checkbox" name="remember">
        <span>Ingat sesi masuk saya di perangkat ini</span>
      </label>

      <div class="btn-row" style="margin-top: var(--s-2);">
        <button type="submit" class="btn btn--primary" style="width:100%;">Masuk akun</button>
      </div>
    </form>
  </div>
</div>
@endsection
