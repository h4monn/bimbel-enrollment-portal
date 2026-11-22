<!doctype html>
<html lang="id" data-type="place" data-radius="sharp" data-buttons="arrow">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Masuk Admin — Bimbel Cendekia</title>
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/system.css') }}">
<link rel="stylesheet" href="{{ asset('assets/portal.css') }}">
</head>
<body style="min-height:100svh;display:grid;place-items:center;padding:var(--s-5);background:var(--surface);">
  <div class="auth-box" style="width:100%;">
    <div style="margin-bottom:var(--s-5);">
      <h1 style="font-size: var(--t-h2);">Portal admin</h1>
      <p class="t-small muted" style="margin-top:var(--s-1);">Akses terbatas untuk akun yang didaftarkan Super Admin.</p>
    </div>

    @if ($errors->any())
      <div class="alert alert--err" role="alert">{{ $errors->first() }}</div>
    @endif

    @if ($authMethod === 'google')
      <a href="{{ route('admin.auth.google') }}" class="btn-google">
        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
          <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17Z"/>
          <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.11-6.72-4.96H1.29v3.14C3.26 21.3 7.31 24 12 24Z"/>
          <path fill="#FBBC05" d="M5.28 14.24c-.24-.72-.38-1.49-.38-2.24s.14-1.52.38-2.24V6.62H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.38l3.99-3.14Z"/>
          <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.99 3.14c.95-2.85 3.6-4.96 6.72-4.96Z"/>
        </svg>
        Masuk admin dengan Google
      </a>
    @else
      <form method="POST" action="{{ route('admin.login.store') }}" class="form" style="grid-template-columns:1fr;">
        @csrf
        <div class="field">
          <label for="email">Email admin</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" required autocomplete="current-password">
        </div>
        <label class="check-row">
          <input type="checkbox" name="remember">
          <span>Ingat sesi ini</span>
        </label>
        <div class="btn-row" style="margin-top:var(--s-2);">
          <button type="submit" class="btn btn--primary" style="width:100%;">Masuk dashboard</button>
        </div>
      </form>
    @endif

    <p class="t-small" style="margin-top:var(--s-5);border-top:1px solid var(--line);padding-top:var(--s-4);">
      <a href="{{ route('home') }}" class="link">Kembali ke beranda publik</a>
    </p>
  </div>
</body>
</html>
