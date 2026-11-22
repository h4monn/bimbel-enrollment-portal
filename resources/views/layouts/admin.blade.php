<!doctype html>
<html lang="id" data-type="place" data-radius="sharp" data-buttons="arrow">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard Admin') — Bimbel Cendekia</title>
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/system.css') }}">
<link rel="stylesheet" href="{{ asset('assets/portal.css') }}">
</head>
<body>
@php
    $admin = auth('admin')->user();
@endphp

<div class="admin-shell">
  <aside class="admin-side">
    <div class="admin-side__head">
      <a href="{{ route('admin.dashboard') }}" class="nav__logo">
        <span class="mark" data-mark="stack" aria-hidden="true"></span>Bimbel Cendekia
      </a>
      <button type="button" id="admin-nav-toggle" class="nav__toggle" aria-expanded="false" aria-label="Menu admin" style="width:40px;height:40px;">
        <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true"><path d="M2 5h14M2 13h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
      </button>
    </div>

    <nav id="admin-nav" class="admin-nav" aria-label="Menu admin">
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Ringkasan</a>
      <a href="{{ route('admin.registrations.index') }}" class="{{ request()->routeIs('admin.registrations.*') ? 'is-active' : '' }}">Pendaftar dan pembayaran</a>
      <a href="{{ route('admin.programs.index') }}" class="{{ request()->routeIs('admin.programs.*') ? 'is-active' : '' }}">Program belajar</a>
      <a href="{{ route('admin.classes.index') }}" class="{{ request()->routeIs('admin.classes.*') ? 'is-active' : '' }}">Kelas, jadwal, dan kuota</a>
    </nav>
  </aside>

  <div class="admin-main">
    <header class="admin-top">
      <h1 style="font-size: var(--t-card); font-weight: 500;">@yield('page_title', 'Dashboard Admin')</h1>
      <div style="display:flex;align-items:center;gap:var(--s-3);">
        <span class="t-small">{{ $admin?->name }}</span>
        <span class="tag tag--info">{{ $admin?->isSuperAdmin() ? 'Super Admin' : 'Staf Admin' }}</span>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="btn btn--secondary btn--sm">Keluar</button>
        </form>
      </div>
    </header>

    <main class="admin-body">
      @if (session('status'))
        <div class="alert alert--ok" role="status">{{ session('status') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert--err" role="alert">{{ $errors->first() }}</div>
      @endif

      @yield('content')
    </main>
  </div>
</div>

<script>
document.getElementById('admin-nav-toggle')?.addEventListener('click', function () {
  const nav = document.getElementById('admin-nav');
  const open = nav?.classList.toggle('is-open');
  this.setAttribute('aria-expanded', String(Boolean(open)));
});
</script>
</body>
</html>
