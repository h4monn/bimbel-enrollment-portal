@extends('layouts.public')

@section('title', 'Riwayat Pendaftaran — Bimbel Cendekia')
@section('meta_description', 'Daftar status pendaftaran kelas, batas waktu tagihan, dan bukti pembayaran akun Anda.')

@section('content')
<div class="wrap">
  <div class="page-header">
    <div>
      <h1>Riwayat pendaftaran</h1>
      <p>Status pendaftaran kelas, nomor registrasi, dan bukti pembayaran akun {{ auth('web')->user()->name }}.</p>
    </div>
    <a href="{{ route('programs.index') }}" class="btn btn--primary">Daftar kelas baru</a>
  </div>

  @if ($registrations->isEmpty())
    <div class="plain-box stack-4">
      <p>Belum ada pendaftaran kelas yang tercatat pada akun ini.</p>
      <div><a class="link" href="{{ route('programs.index') }}">Lihat daftar program dan jadwal</a></div>
    </div>
  @else
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Nomor pendaftaran</th>
            <th>Siswa dan kelas</th>
            <th>Status</th>
            <th>Keterangan pembayaran</th>
            <th class="num">Total</th>
            <th class="num">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($registrations as $registration)
            @php
                $statusMap = [
                    'pending_payment' => ['label' => 'Menunggu pembayaran', 'class' => 'tag--warn'],
                    'paid' => ['label' => 'Lunas', 'class' => 'tag--ok'],
                    'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'tag--ok'],
                    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'tag--err'],
                    'refunded' => ['label' => 'Refund', 'class' => 'tag--err'],
                ];
                $st = $statusMap[$registration->status] ?? ['label' => $registration->status, 'class' => ''];
            @endphp
            <tr>
              <td>
                <span class="mono">{{ $registration->registration_number }}</span>
                <span class="subline">{{ $registration->created_at?->format('d/m/Y H.i') }}</span>
              </td>
              <td>
                <strong>{{ $registration->student->full_name }}</strong>
                <span class="subline">{{ $registration->classRoom->program->name }} &mdash; {{ $registration->classRoom->name }} ({{ $registration->classRoom->schedule_days }})</span>
              </td>
              <td><span class="tag {{ $st['class'] }}">{{ $st['label'] }}</span></td>
              <td>
                @if ($registration->latestPayment?->paid_at)
                  Lunas {{ $registration->latestPayment->paid_at->format('d/m/Y H.i') }}
                @elseif ($registration->status === 'pending_payment')
                  Batas bayar {{ $registration->expired_at->format('d/m/Y H.i') }}
                @else
                  &mdash;
                @endif
                @if ($registration->latestPayment?->payment_method)
                  <span class="subline">{{ strtoupper($registration->latestPayment->payment_method) }}</span>
                @endif
              </td>
              <td class="num"><strong>Rp {{ number_format($registration->total_amount, 0, ',', '.') }}</strong></td>
              <td class="num">
                @if ($registration->status === 'pending_payment')
                  <a class="btn btn--primary btn--sm" href="{{ route('registrations.checkout', $registration) }}">Lanjutkan bayar</a>
                @elseif (in_array($registration->status, ['paid', 'confirmed'], true))
                  <a class="btn btn--secondary btn--sm" href="{{ route('registrations.success', $registration) }}">Lihat bukti</a>
                @else
                  <span class="t-small muted">&mdash;</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
