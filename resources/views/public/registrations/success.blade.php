@extends('layouts.public')

@section('title', 'Bukti Pendaftaran — ' . $registration->registration_number)
@section('meta_description', 'Ringkasan pendaftaran kelas, jadwal tatap muka, dan status pembayaran.')

@section('content')
<div class="wrap">
  <ol class="steps-bar" aria-label="Tahapan pendaftaran">
    <li class="steps-bar__item is-done"><span class="mono">1.</span> Data siswa dan wali</li>
    <li class="steps-bar__item is-done"><span class="mono">2.</span> Pembayaran lunas 24 jam</li>
    <li class="steps-bar__item is-current"><span class="mono">3.</span> Bukti pendaftaran</li>
  </ol>

  <div class="grid grid--top">
    <div class="span-7 stack-5">
      <div class="page-header" style="margin-bottom: 0; padding-bottom: var(--s-4);">
        <div>
          @if ($registration->status === 'confirmed')
            <span class="tag tag--ok" style="margin-bottom: var(--s-2);">Lunas dan dikonfirmasi</span>
          @else
            <span class="tag tag--warn" style="margin-bottom: var(--s-2);">Menunggu verifikasi pembayaran</span>
          @endif
          <h1>Bukti pendaftaran kelas</h1>
          <p>Salinan ringkasan pendaftaran dikirimkan ke email {{ $registration->user->email }} dan nomor WhatsApp {{ $registration->user->whatsapp }}.</p>
        </div>
      </div>

      <div class="surface-box" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:var(--s-4);">
        <div>
          <p class="t-label muted">Nomor pendaftaran</p>
          <p id="reg-number-text" class="t-stat mono" style="margin-top:var(--s-1);">{{ $registration->registration_number }}</p>
        </div>
        <button type="button" id="copy-reg-btn" class="btn btn--secondary btn--sm">Salin nomor</button>
      </div>

      <ul class="rows">
        <li><span class="rows__name">Nama siswa</span><span class="rows__value">{{ $registration->student->full_name }}</span></li>
        <li><span class="rows__name">Program dan kelas</span><span class="rows__value">{{ $registration->classRoom->program->name }} &mdash; {{ $registration->classRoom->name }}</span></li>
        <li><span class="rows__name">Jadwal tatap muka</span><span class="rows__value">{{ $registration->classRoom->schedule_days }}, {{ $registration->classRoom->schedule_time }}</span></li>
        <li><span class="rows__name">Ruang dan pengajar</span><span class="rows__value">{{ $registration->classRoom->room }} ({{ $registration->classRoom->teacher_name }})</span></li>
        <li><span class="rows__name">Total dibayar</span><span class="rows__value"><strong>Rp {{ number_format($registration->total_amount, 0, ',', '.') }}</strong></span></li>
      </ul>

      <div class="btn-row">
        <a href="{{ route('dashboard') }}" class="btn btn--primary">Lihat riwayat pendaftaran</a>
        <a href="{{ route('programs.index') }}" class="btn btn--secondary">Daftar kelas lain</a>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('copy-reg-btn')?.addEventListener('click', function () {
  const text = document.getElementById('reg-number-text')?.textContent?.trim();
  if (!text) return;
  navigator.clipboard?.writeText(text);
  this.textContent = 'Tersalin';
  setTimeout(() => { this.textContent = 'Salin nomor'; }, 2000);
});
</script>
@endpush
