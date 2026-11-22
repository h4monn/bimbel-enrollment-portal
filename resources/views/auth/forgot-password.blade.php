@extends('layouts.public')

@section('title', 'Reset Password — Bimbel Cendekia')
@section('meta_description', 'Minta tautan pengaturan ulang password untuk akun pendaftar Anda.')

@section('content')
<div class="wrap">
  <div class="auth-box">
    <h1 style="font-size: var(--t-h2); margin-bottom: var(--s-2);">Atur ulang password</h1>
    <p class="t-small muted" style="margin-bottom: var(--s-5);">Masukkan email akun yang terdaftar untuk menerima tautan password baru.</p>

    <form method="POST" action="{{ route('password.email') }}" class="form" style="grid-template-columns: 1fr;">
      @csrf
      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" @error('email') aria-invalid="true" aria-describedby="err-email" @enderror>
        @error('email') <span id="err-email" class="field__error">{{ $message }}</span> @enderror
      </div>

      <div class="btn-row">
        <button type="submit" class="btn btn--primary" style="width:100%;">Kirim tautan reset</button>
      </div>
      <p class="t-small"><a href="{{ route('login') }}" class="link">Kembali ke halaman masuk</a></p>
    </form>
  </div>
</div>
@endsection
