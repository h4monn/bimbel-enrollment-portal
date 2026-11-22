<!doctype html>
@php
    $isHome = request()->routeIs('home');
@endphp
<html lang="id" data-type="place" @if($isHome) data-page-mode="short" @endif data-radius="sharp" data-buttons="arrow" data-motion>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Bimbel Cendekia: bimbingan belajar tatap muka SD hingga UTBK')</title>
<meta name="description" content="@yield('meta_description', 'Pendaftaran kelas tatap muka SD, SMP, SMA, dan UTBK. Lihat jadwal ruang, sisa kuota langsung, dan selesaikan biaya lunas dalam 24 jam.')">
<meta property="og:type" content="website">
<meta property="og:title" content="@yield('title', 'Bimbel Cendekia: bimbingan belajar tatap muka SD hingga UTBK')">
<meta property="og:description" content="@yield('meta_description', 'Pendaftaran kelas tatap muka SD, SMP, SMA, dan UTBK dengan kuota per ruang dan pembayaran daring 24 jam.')">
<meta property="og:image" content="{{ asset('share.jpg') }}">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<meta name="theme-color" content="#f8fafb">
<!-- <link rel="canonical" href="https://pendaftaran.example/"> -->
<meta name="site-kind" content="other">
<meta name="page-type" content="place">
<meta name="page-kind" content="place">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/system.css') }}">
<link rel="stylesheet" href="{{ asset('assets/motion/motion.css') }}">
<link rel="stylesheet" href="{{ asset('assets/layout/layout.css') }}">
<link rel="stylesheet" href="{{ asset('assets/portal.css') }}">
@if($isHome)
<script>document.documentElement.classList.add('m-enter');setTimeout(()=>document.documentElement.classList.add('m-enter-done'),700)</script>
<script>document.documentElement.classList.add('mo-js');setTimeout(()=>document.documentElement.classList.add('mo-safe'),2500)</script>
<link rel="modulepreload" href="{{ asset('assets/moments/moments.js') }}">
<link rel="modulepreload" href="{{ asset('assets/moments/lib.js') }}">
<link rel="modulepreload" href="{{ asset('assets/moments/picture-develops/moment.js') }}">
@endif
@stack('head')
</head>
<body>
<!-- type: place
     deal: palette brand (#f8fafb/#161d24/#0077c8), type instrument-sans, hero place, nav plain, footer footer-columns, rhythm space, buttons arrow, mark stack
     moments: picture-develops (#program) -->
<header class="nav">
  <div class="wrap nav__bar">
    <a class="skip-link" href="#top">Lewati ke isi utama</a>
    <a class="nav__logo" href="{{ $isHome ? '#top' : route('home') }}">
      <span class="mark" data-mark="stack" aria-hidden="true"></span>Bimbel Cendekia
    </a>
    <nav class="nav__links" aria-label="Utama">
      <div class="nav__item">
        <a class="nav__link" href="{{ $isHome ? '#program' : route('programs.index') }}" @if(!$isHome && request()->routeIs('programs.*')) aria-current="page" @endif>Program</a>
      </div>
      <div class="nav__item">
        <a class="nav__link" href="{{ $isHome ? '#alur' : route('home').'#alur' }}">Alur</a>
      </div>
      @auth('web')
        <div class="nav__item">
          <a class="nav__link" href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif>Riwayat</a>
        </div>
      @endauth
    </nav>
    <div class="nav__actions">
      @auth('web')
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="nav__link" style="background:none;border:0;cursor:pointer;">Keluar</button>
        </form>
      @else
        <a class="nav__link" href="{{ route('login') }}" @if(request()->routeIs('login')) aria-current="page" @endif>Masuk</a>
      @endauth
      <a class="btn btn--primary" href="{{ route('programs.index') }}">Daftar kelas<svg class="m-arrow" viewBox="0 0 12 12" aria-hidden="true"><path class="m-arrow__stem" d="M1 6h8"/><path class="m-arrow__head" d="M4.5 2.5 8 6l-3.5 3.5"/></svg></a>
    </div>
    <button class="nav__toggle" type="button" aria-expanded="false" aria-label="Menu">
      <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true"><path d="M2 5h14M2 13h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
    </button>
  </div>
</header>

<main id="top" @if(!$isHome) class="page-main" @endif>
  @if(!$isHome && session('status'))
    <div class="wrap">
      <div class="alert alert--ok" role="status">{{ session('status') }}</div>
    </div>
  @endif

  @yield('content')
</main>

@hasSection('footer')
  @yield('footer')
@else
  <footer class="inner-footer" id="contact">
    <div class="wrap inner-footer__row">
      <span>&copy; {{ date('Y') }} Bimbel Cendekia. Pendaftaran kelas tatap muka satu cabang.</span>
      <div class="inner-footer__links">
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('programs.index') }}">Program dan jadwal</a>
        @auth('web')
          <a href="{{ route('dashboard') }}">Riwayat pendaftaran</a>
        @else
          <a href="{{ route('login') }}">Masuk akun</a>
        @endauth
        <a href="{{ route('admin.login') }}">Portal admin</a>
      </div>
    </div>
  </footer>
@endif

@if($isHome)
<nav class="actionbar" aria-label="Tindakan utama">
  <a class="btn btn--primary" href="{{ route('programs.index') }}">Daftar kelas<svg class="m-arrow" viewBox="0 0 12 12" aria-hidden="true"><path class="m-arrow__stem" d="M1 6h8"/><path class="m-arrow__head" d="M4.5 2.5 8 6l-3.5 3.5"/></svg></a>
</nav>
@endif

<script type="module" src="{{ asset('assets/system.js') }}"></script>
<script type="module" src="{{ asset('assets/motion/motion.js') }}"></script>
<script type="module" src="{{ asset('assets/layout/layout.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const navLinks = document.querySelector('.nav__links');
  if (!navLinks) return;
  const links = Array.from(navLinks.querySelectorAll('.nav__link'));
  if (!links.length) return;

  let activeLink = links.find(a => a.getAttribute('aria-current') === 'page') || null;

  function setActive(link) {
    activeLink = link;
    links.forEach(a => a.classList.toggle('is-nav-active', a === link));
  }

  links.forEach(link => {
    link.addEventListener('click', () => {
      const href = link.getAttribute('href') || '';
      if (!href.startsWith('#')) return;
      setActive(link);
    });
  });

  const isHome = document.documentElement.dataset.pageMode === 'short';
  if (isHome) {
    const programEl = document.getElementById('program');
    const alurEl = document.getElementById('alur');
    const progLink = links.find(a => a.getAttribute('href') === '#program');
    const alurLink = links.find(a => a.getAttribute('href') === '#alur');

    function syncNavOnScroll() {
      if (!programEl) return;
      const vh = window.innerHeight;
      const progRect = programEl.getBoundingClientRect();
      const alurRect = alurEl ? alurEl.getBoundingClientRect() : null;

      if (progRect.top > vh * 0.45) {
        setActive(null);
        return;
      }

      if (alurRect && alurRect.top <= Math.max(160, vh * 0.35)) {
        setActive(alurLink);
        return;
      }

      setActive(progLink);
    }

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => {
        ticking = false;
        syncNavOnScroll();
      });
    }, { passive: true });

    syncNavOnScroll();
  }
});
</script>
@stack('scripts')
</body>
</html>
