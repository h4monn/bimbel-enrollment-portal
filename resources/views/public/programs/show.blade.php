@extends('layouts.public')

@section('title', $program->name . ' — Pilih Kelas')
@section('meta_description', 'Jadwal tatap muka, nama pengajar, ruang belajar, dan sisa kursi untuk program ' . $program->name . '.')

@section('content')
<div class="wrap">
  <div class="breadcrumb">
    <a href="{{ route('programs.index') }}">Program</a>
    <span aria-hidden="true">/</span>
    <span>{{ $program->name }}</span>
  </div>

  <div class="page-header">
    <div>
      <h1>{{ $program->name }}</h1>
      <p>{{ $program->description }}</p>
    </div>
    <div class="surface-box" style="min-width: 240px;">
      <p class="t-label muted">Biaya lunas ({{ $program->duration }})</p>
      <p class="t-stat" style="margin-top: var(--s-2);">Rp {{ number_format($program->price, 0, ',', '.') }}</p>
      <p class="subline" style="margin-top: var(--s-2);">Jenjang {{ $program->education_level }} &middot; Tatap muka</p>
    </div>
  </div>

  <div class="stack-4">
    <h2>Jadwal dan kuota kelas</h2>

    @if ($program->classes->isEmpty())
      <div class="plain-box">
        <p>Belum ada jadwal kelas aktif untuk program ini.</p>
      </div>
    @else
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Kelas</th>
              <th>Hari dan jam</th>
              <th>Pengajar</th>
              <th>Ruang</th>
              <th>Kuota</th>
              <th class="num">Pendaftaran</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($program->classes as $classRoom)
              @php
                  $remaining = $classRoom->remaining_quota;
                  $isFull = $remaining <= 0;
                  $percent = $classRoom->quota > 0 ? min(100, round(($classRoom->enrolled_count / $classRoom->quota) * 100)) : 100;
              @endphp
              <tr>
                <td><strong>{{ $classRoom->name }}</strong></td>
                <td>
                  {{ $classRoom->schedule_days }}
                  <span class="subline">{{ $classRoom->schedule_time }}</span>
                </td>
                <td>{{ $classRoom->teacher_name }}</td>
                <td>{{ $classRoom->room }}</td>
                <td>
                  @if ($isFull)
                    <span class="tag tag--err">Kuota penuh</span>
                  @else
                    <span class="tag tag--info">Sisa {{ $remaining }} dari {{ $classRoom->quota }}</span>
                  @endif
                  <div class="meter" aria-hidden="true">
                    <div class="meter__fill {{ $isFull ? 'meter__fill--full' : '' }}" style="width: {{ $percent }}%"></div>
                  </div>
                </td>
                <td class="num">
                  @if ($isFull)
                    <button type="button" class="btn btn--secondary btn--sm" disabled style="opacity: 0.45; cursor: not-allowed; text-decoration: none;">Kuota penuh</button>
                  @else
                    <a class="btn btn--primary btn--sm" href="{{ route('registrations.create', $classRoom) }}">Daftar kelas ini</a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endsection
