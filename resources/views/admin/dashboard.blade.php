@extends('layouts.admin')

@section('title', 'Ringkasan')
@section('page_title', 'Ringkasan operasional')

@section('content')
@php
    $nearlyFullCount = $classes->filter(fn ($c) => $c->is_active && $c->remaining_quota <= 3)->count();
@endphp

<div class="kpi-strip">
  <div class="kpi-item">
    <p class="kpi-item__label">Total pendaftar</p>
    <p class="kpi-item__value">{{ number_format($totalRegistrations) }}</p>
    <span class="subline">{{ number_format($pendingCount) }} menunggu bayar</span>
  </div>
  <div class="kpi-item">
    <p class="kpi-item__label">Lunas dan dikonfirmasi</p>
    <p class="kpi-item__value">{{ number_format($confirmedCount) }}</p>
    <span class="subline">Kursi aktif</span>
  </div>
  <div class="kpi-item">
    <p class="kpi-item__label">Pendapatan lunas</p>
    <p class="kpi-item__value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    <span class="subline">Total terverifikasi</span>
  </div>
  <div class="kpi-item">
    <p class="kpi-item__label">Kelas hampir penuh</p>
    <p class="kpi-item__value">{{ number_format($nearlyFullCount) }}</p>
    <span class="subline">Sisa 3 kursi atau kurang</span>
  </div>
</div>

<section class="stack-4">
  <div style="display:flex;align-items:baseline;justify-content:space-between;gap:var(--s-4);">
    <h2 style="font-size: var(--t-h3);">Sisa kuota per kelas</h2>
    <a href="{{ route('admin.classes.index') }}" class="link">Kelola kelas</a>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>Program dan kelas</th>
          <th>Jadwal dan ruang</th>
          <th>Pengajar</th>
          <th>Kapasitas</th>
          <th class="num">Sisa</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($classes as $classRoom)
          @php
              $remaining = $classRoom->remaining_quota;
              $isFull = $remaining <= 0;
              $percent = $classRoom->quota > 0 ? min(100, round(($classRoom->enrolled_count / $classRoom->quota) * 100)) : 100;
          @endphp
          <tr>
            <td>
              <strong>{{ $classRoom->name }}</strong>
              <span class="subline">{{ $classRoom->program?->name }}</span>
            </td>
            <td>
              {{ $classRoom->schedule_days }}, {{ $classRoom->schedule_time }}
              <span class="subline">{{ $classRoom->room }}</span>
            </td>
            <td>{{ $classRoom->teacher_name }}</td>
            <td>
              <span class="t-small mono">{{ $classRoom->enrolled_count }}/{{ $classRoom->quota }}</span>
              <div class="meter" aria-hidden="true">
                <div class="meter__fill {{ $isFull ? 'meter__fill--full' : '' }}" style="width: {{ $percent }}%"></div>
              </div>
            </td>
            <td class="num">
              <span class="tag {{ $isFull ? 'tag--err' : 'tag--info' }}">{{ $isFull ? 'Penuh' : $remaining . ' kursi' }}</span>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="muted">Belum ada kelas terdaftar.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<section class="stack-4">
  <div style="display:flex;align-items:baseline;justify-content:space-between;gap:var(--s-4);">
    <h2 style="font-size: var(--t-h3);">Pendaftar terbaru</h2>
    <a href="{{ route('admin.registrations.index') }}" class="link">Semua pendaftar</a>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>Nomor pendaftaran</th>
          <th>Siswa dan wali</th>
          <th>Kelas</th>
          <th class="num">Tagihan</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($recentRegistrations as $reg)
          <tr>
            <td class="mono">{{ $reg->registration_number }}</td>
            <td>
              <strong>{{ $reg->student?->full_name }}</strong>
              <span class="subline">{{ $reg->user?->name }} &middot; {{ $reg->user?->whatsapp }}</span>
            </td>
            <td>{{ $reg->classRoom?->program?->name }} &mdash; {{ $reg->classRoom?->name }}</td>
            <td class="num">Rp {{ number_format($reg->total_amount, 0, ',', '.') }}</td>
            <td><span class="tag">{{ $reg->status }}</span></td>
          </tr>
        @empty
          <tr><td colspan="5" class="muted">Belum ada data pendaftar.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
