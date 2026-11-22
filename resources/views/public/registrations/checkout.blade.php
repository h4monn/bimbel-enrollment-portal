@extends('layouts.public')

@section('title', 'Pembayaran Pendaftaran — ' . $registration->registration_number)
@section('meta_description', 'Selesaikan pembayaran lunas melalui Midtrans sebelum batas waktu 24 jam berakhir.')

@push('head')
  @if ($snapToken)
    <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
  @endif
@endpush

@section('content')
<div class="wrap">
  <ol class="steps-bar" aria-label="Tahapan pendaftaran">
    <li class="steps-bar__item is-done"><span class="mono">1.</span> Data siswa dan wali</li>
    <li class="steps-bar__item is-current"><span class="mono">2.</span> Pembayaran lunas 24 jam</li>
    <li class="steps-bar__item"><span class="mono">3.</span> Bukti pendaftaran</li>
  </ol>

  <div class="grid grid--top">
    <div class="span-7 stack-5">
      <div class="page-header" style="margin-bottom: 0; padding-bottom: var(--s-4);">
        <div>
          <span class="tag tag--warn" style="margin-bottom: var(--s-2);">Menunggu pembayaran</span>
          <h1>Tagihan pendaftaran kelas</h1>
          <p>Kuota kelas dikunci sementara untuk Anda hingga {{ $registration->expired_at->translatedFormat('d M Y, H.i') }} WIB.</p>
        </div>
      </div>

      <ul class="rows">
        <li><span class="rows__name">Nomor pendaftaran</span><span class="rows__value mono">{{ $registration->registration_number }}</span></li>
        <li><span class="rows__name">Nama siswa</span><span class="rows__value">{{ $registration->student->full_name }}</span></li>
        <li><span class="rows__name">Program dan kelas</span><span class="rows__value">{{ $registration->classRoom->program->name }} &mdash; {{ $registration->classRoom->name }}</span></li>
        <li><span class="rows__name">Jadwal dan ruang</span><span class="rows__value">{{ $registration->classRoom->schedule_days }}, {{ $registration->classRoom->schedule_time }} ({{ $registration->classRoom->room }})</span></li>
        <li><span class="rows__name">Kode transaksi</span><span class="rows__value mono">{{ $registration->latestPayment?->order_id }}</span></li>
        <li><span class="rows__name">Total dibayar lunas</span><span class="rows__value"><strong>Rp {{ number_format($registration->total_amount, 0, ',', '.') }}</strong></span></li>
      </ul>

      <div class="btn-row">
        @if ($snapToken)
          <button type="button" id="pay-button" class="btn btn--primary">Bayar sekarang</button>
        @endif
        @if ($redirectUrl)
          <a href="{{ $redirectUrl }}" target="_blank" rel="noopener" class="btn btn--secondary">Buka halaman Midtrans</a>
        @endif
        <a href="{{ route('dashboard') }}" class="btn btn--secondary">Lihat riwayat pendaftaran</a>
      </div>
    </div>

    <aside class="span-4 start-9">
      <div class="surface-box stack-4">
        <p class="t-label muted">Batas waktu pembayaran</p>
        <p id="payment-countdown" class="t-stat mono" data-expires="{{ $registration->expired_at->toIso8601String() }}">--:--:--</p>
        <p class="t-small muted">Berakhir pada {{ $registration->expired_at->translatedFormat('d M Y, H.i') }} WIB. Jika melewati 24 jam, tagihan dibatalkan otomatis dan kursi dikembalikan ke kuota kelas.</p>
      </div>
    </aside>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const el = document.getElementById('payment-countdown');
  if (!el) return;
  const target = new Date(el.dataset.expires).getTime();
  function tick() {
    const diff = Math.max(0, Math.floor((target - Date.now()) / 1000));
    if (diff <= 0) {
      el.textContent = 'Kedaluwarsa';
      return;
    }
    const h = String(Math.floor(diff / 3600)).padStart(2, '0');
    const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
    const s = String(diff % 60).padStart(2, '0');
    el.textContent = h + ':' + m + ':' + s;
  }
  tick();
  setInterval(tick, 1000);
})();
</script>
@if ($snapToken)
<script>
document.getElementById('pay-button')?.addEventListener('click', function () {
  window.snap.pay(@json($snapToken), {
    onSuccess: function () { window.location.href = @json(route('registrations.success', $registration)); },
    onPending: function () { window.location.href = @json(route('dashboard')); },
    onError: function () { alert('Pembayaran belum berhasil diproses. Silakan pilih metode pembayaran lain.'); }
  });
});
</script>
@endif
@endpush
