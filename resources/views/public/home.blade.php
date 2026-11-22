@extends('layouts.public')

@section('title', 'Bimbel Cendekia: bimbingan belajar tatap muka SD hingga UTBK')
@section('meta_description', 'Pendaftaran kelas tatap muka SD, SMP, SMA, dan UTBK di satu cabang. Lihat jadwal ruang, sisa kuota langsung, dan bayar lunas dalam 24 jam.')

@section('content')
  <section class="hero hero--place" data-enter aria-labelledby="hero-h">
    <img class="hero__bg"
         src="{{ asset('assets/img/photo-c877.jpg') }}"
         srcset="{{ asset('assets/img/photo-c877-500.jpg') }} 500w, {{ asset('assets/img/photo-c877-960.jpg') }} 960w, {{ asset('assets/img/photo-c877-1280.jpg') }} 1280w, {{ asset('assets/img/photo-c877.jpg') }} 1920w"
         sizes="(max-width: 639px) 188vw, 100vw"
         width="1920" height="1280"
         alt="Tumpukan buku pelajaran dan lembar latihan belajar di meja"
         style="--ly-pos: 50% 50%"
         fetchpriority="high" decoding="async"
         data-phone-fill data-source="library-057" data-subject="desk">
    <div class="wrap">
      <div class="hero-text">
        <div class="hero__text">
          <h1 id="hero-h">Bimbingan belajar tatap muka SD hingga UTBK</h1>
          <p class="hero__lead">Cek jadwal ruang dan sisa kursi langsung, lalu selesaikan biaya lunas dalam 24 jam.</p>
          <div class="btn-row">
            <a class="btn btn--primary" href="{{ route('programs.index') }}">Daftar kelas<svg class="m-arrow" viewBox="0 0 12 12" aria-hidden="true"><path class="m-arrow__stem" d="M1 6h8"/><path class="m-arrow__head" d="M4.5 2.5 8 6l-3.5 3.5"/></svg></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="program" aria-labelledby="program-h">
    <div class="wrap">
      <div class="section-head section-head--row">
        <h2 id="program-h">Program kelas dan biaya</h2>
        <a class="link" href="{{ route('programs.index') }}">Lihat semua kelas</a>
      </div>

      <div class="grid grid--top home-offer-grid">
        <div class="span-6 home-tabs-wrap">
          <div class="level-tabs" role="tablist" aria-label="Filter jenjang program">
            <button type="button" class="level-tab" role="tab" id="tab-semua" aria-selected="true" aria-controls="panel-semua">Semua</button>
            @foreach ($featuredPrograms as $program)
              <button type="button" class="level-tab" role="tab" id="tab-{{ strtolower($program->education_level) }}" aria-selected="false" aria-controls="panel-{{ strtolower($program->education_level) }}">{{ $program->education_level }}</button>
            @endforeach
            <span class="level-tabs__indicator" aria-hidden="true"></span>
          </div>

          <div id="panel-semua" class="level-panel" role="tabpanel" aria-labelledby="tab-semua">
            <ul class="rows" data-assumed>
              @forelse ($featuredPrograms as $program)
                <li>
                  <a class="row-link" href="{{ route('programs.show', $program) }}">
                    <span class="rows__name">{{ $program->name }}</span>
                    <span class="rows__value">Rp {{ number_format($program->price, 0, ',', '.') }}</span>
                  </a>
                </li>
              @empty
                <li>
                  <span class="rows__name">Program periode baru</span>
                  <span class="rows__value">Segera dibuka</span>
                </li>
              @endforelse
            </ul>
          </div>

          @foreach ($featuredPrograms as $program)
            @php
              $activeClasses = $program->classes;
              $totalRemaining = $activeClasses->sum(fn ($c) => max(0, $c->quota - $c->enrolled_count));
              $firstClass = $activeClasses->first();
            @endphp
            <div id="panel-{{ strtolower($program->education_level) }}" class="level-panel" role="tabpanel" aria-labelledby="tab-{{ strtolower($program->education_level) }}" hidden>
              <ul class="rows" data-assumed>
                <li>
                  <a class="row-link" href="{{ route('programs.show', $program) }}">
                    <span class="rows__name">{{ $program->name }}</span>
                    <span class="rows__value">Lihat kelas &rarr;</span>
                  </a>
                </li>
                <li>
                  <span class="rows__name">Biaya lunas</span>
                  <span class="rows__value">Rp {{ number_format($program->price, 0, ',', '.') }}</span>
                </li>
                <li>
                  <span class="rows__name">Durasi program</span>
                  <span class="rows__value">{{ $program->duration }}</span>
                </li>
                <li>
                  <span class="rows__name">{{ $firstClass ? $firstClass->schedule_days : 'Jadwal ruang' }}</span>
                  <span class="rows__value">{{ $totalRemaining > 0 ? $totalRemaining . ' kursi tersedia' : 'Kuota penuh' }}</span>
                </li>
              </ul>
            </div>
          @endforeach
        </div>

        <figure class="pl-photo span-6" style="--pl-r: 1.5" data-moment="picture-develops">
          <img src="{{ asset('assets/img/photo-d183.jpg') }}"
               srcset="{{ asset('assets/img/photo-d183-500.jpg') }} 500w, {{ asset('assets/img/photo-d183-960.jpg') }} 960w, {{ asset('assets/img/photo-d183.jpg') }} 1280w"
               sizes="(max-width: 639px) 188vw, (max-width: 1023px) 188vw, 50vw"
               width="1280" height="853"
               alt="Buku tulis terbuka dengan pensil kayu di atas meja belajar"
               loading="lazy" decoding="async"
               data-source="library-057" data-subject="desk">
        </figure>
      </div>
    </div>
  </section>

  <section class="section" id="alur" aria-labelledby="alur-h">
    <div class="wrap">
      <div class="section-head section-head--row">
        <h2 id="alur-h">Alur pendaftaran kelas</h2>
        <a class="link" href="{{ route('programs.index') }}">Mulai pilih jadwal</a>
      </div>

      <ol class="flow-steps">
        <li class="flow-step">
          <span class="flow-step__num">01</span>
          <h3 class="flow-step__title">Pilih jadwal kelas</h3>
          <p class="flow-step__desc">Periksa program, jam ruang belajar, dan sisa kursi langsung pada katalog kelas.</p>
        </li>
        <li class="flow-step">
          <span class="flow-step__num">02</span>
          <h3 class="flow-step__title">Masuk akun wali</h3>
          <p class="flow-step__desc">Daftar atau masuk menggunakan email maupun akun Google untuk menyimpan riwayat.</p>
        </li>
        <li class="flow-step">
          <span class="flow-step__num">03</span>
          <h3 class="flow-step__title">Isi data siswa</h3>
          <p class="flow-step__desc">Lengkapi satu formulir biodata siswa dan nomor kontak aktif, lalu kunci kursi.</p>
        </li>
        <li class="flow-step">
          <span class="flow-step__num">04</span>
          <h3 class="flow-step__title">Bayar lunas 24 jam</h3>
          <p class="flow-step__desc">Selesaikan pembayaran penuh sebelum batas 24 jam agar kursi terkonfirmasi otomatis.</p>
        </li>
      </ol>
    </div>
  </section>
@endsection

@section('footer')
<footer class="footer home-footer ground-surface" id="lokasi" aria-label="Informasi cabang dan navigasi bawah">
  <div class="wrap">
    <div class="home-footer__grid">
      <div class="home-footer__col home-footer__brand">
        <a class="nav__logo" href="#top">
          <span class="mark" data-mark="stack" aria-hidden="true"></span>Bimbel Cendekia
        </a>
        <p class="home-footer__text">Satu cabang tatap muka SD, SMP, SMA, dan UTBK. Seluruh pertemuan berlangsung di ruang kelas.</p>
      </div>

      <div class="home-footer__col">
        <h3 class="home-footer__title">Jam operasional</h3>
        <dl class="hours home-footer__hours" data-assumed>
          <dt>Senin–Jumat</dt><dd>08.00–18.00</dd>
          <dt>Sabtu</dt><dd>08.00–15.00</dd>
          <dt>Minggu</dt><dd>Tutup</dd>
        </dl>
      </div>

      <div class="home-footer__col">
        <h3 class="home-footer__title">Ketentuan pembayaran</h3>
        <p class="home-footer__text">Biaya dibayar lunas tanpa cicilan. Pengembalian dana berlaku jika kelas dibatalkan bimbel atau terjadi pembayaran ganda.</p>
      </div>

      <div class="home-footer__col">
        <h3 class="home-footer__title">Tautan</h3>
        <ul class="home-footer__nav">
          <li><a href="{{ route('programs.index') }}">Program dan jadwal</a></li>
          <li><a href="{{ route('register') }}">Buat akun wali</a></li>
          <li><a href="{{ route('login') }}">Masuk akun</a></li>
          <li><a href="{{ route('dashboard') }}">Riwayat pendaftaran</a></li>
          <li><a href="{{ route('admin.login') }}">Portal admin</a></li>
        </ul>
      </div>
    </div>

    <div class="footer__legal home-footer__legal">
      <span>&copy; {{ date('Y') }} Bimbel Cendekia</span>
      <details class="credits">
        <summary>Photographs via Wikimedia Commons</summary>
        <p>Stacked books by Nenad Stojkovic (CC BY 2.0), open notebook by Jan Kahánek (CC0), via Wikimedia Commons.</p>
      </details>
    </div>
  </div>
</footer>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const list = document.querySelector('#program .level-tabs');
  if (!list) return;
  const tabs = Array.from(list.querySelectorAll('[role="tab"]'));
  const indicator = list.querySelector('.level-tabs__indicator');

  function moveIndicator(tab, instant) {
    if (!indicator || !tab) return;
    if (instant) indicator.style.transition = 'none';
    indicator.style.width = tab.offsetWidth + 'px';
    indicator.style.transform = 'translateX(' + tab.offsetLeft + 'px)';
    if (instant) {
      indicator.getBoundingClientRect();
      indicator.style.transition = '';
    }
  }

  function animatePanel(tab) {
    const id = tab.getAttribute('aria-controls');
    const panel = id ? document.getElementById(id) : null;
    if (!panel) return;
    panel.classList.remove('is-entering');
    void panel.offsetWidth;
    panel.classList.add('is-entering');
  }

  function syncActive(instant) {
    const active = tabs.find(t => t.getAttribute('aria-selected') === 'true') || tabs[0];
    if (!active) return;
    moveIndicator(active, instant);
    if (!instant) animatePanel(active);
  }

  const mo = new MutationObserver(() => syncActive(false));
  tabs.forEach(t => mo.observe(t, { attributes: true, attributeFilter: ['aria-selected'] }));

  window.addEventListener('resize', () => syncActive(true));
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(() => syncActive(true));
  }
  syncActive(true);
});
</script>
@endpush
