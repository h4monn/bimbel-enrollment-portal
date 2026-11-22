@extends('layouts.public')

@section('title', 'Buat Password Baru — Bimbel Cendekia')
@section('meta_description', 'Simpan password baru untuk akun pendaftar Anda.')

@section('content')
<div class="wrap">
  <div class="auth-box">
    <h1 style="font-size: var(--t-h2); margin-bottom: var(--s-5);">Buat password baru</h1>

    <form method="POST" action="{{ route('password.update') }}" class="form" style="grid-template-columns: 1fr;">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email" @error('email') aria-invalid="true" aria-describedby="err-email" @enderror>
        @error('email') <span id="err-email" class="field__error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="password">Password baru</label>
        <input id="password" name="password" type="password" required autocomplete="new-password" @error('password') aria-invalid="true" aria-describedby="err-password" @enderror>
        @error('password') <span id="err-password" class="field__error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="password_confirmation">Ulangi password baru</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
      </div>

      <div class="btn-row">
        <button type="submit" class="btn btn--primary" style="width:100%;">Simpan password</button>
      </div>
    </form>
  </div>
</div>
@endsection
