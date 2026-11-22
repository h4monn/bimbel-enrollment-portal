@extends('layouts.public')

@section('title', 'Program dan Jadwal — Bimbel Cendekia')
@section('meta_description', 'Daftar program belajar tatap muka SD, SMP, SMA, UTBK, dan mata pelajaran khusus beserta durasi, biaya penuh, dan sisa kursi.')

@section('content')
<div class="wrap">
  <div class="page-header">
    <div>
      <h1>Program belajar tatap muka</h1>
      <p>Pilih jenjang untuk memeriksa durasi, biaya penuh, dan sisa kursi kelas yang masih dibuka.</p>
    </div>
  </div>

  @php
      $levelLabels = [
          'SD' => 'SD',
          'SMP' => 'SMP',
          'SMA' => 'SMA',
          'UTBK' => 'UTBK',
          'UMUM' => 'Mapel khusus',
      ];
  @endphp

  <div class="filter-bar" role="navigation" aria-label="Saring berdasarkan jenjang">
    <a href="{{ route('programs.index') }}" class="filter-tab {{ ! $activeLevel ? 'is-active' : '' }}">Semua jenjang</a>
    @foreach ($levelLabels as $code => $label)
      <a href="{{ route('programs.index', ['level' => $code]) }}" class="filter-tab {{ $activeLevel === $code ? 'is-active' : '' }}">{{ $label }}</a>
    @endforeach
  </div>

  @if ($programs->isEmpty())
    <div class="plain-box stack-4">
      <p>Belum ada program aktif untuk jenjang ini.</p>
      <div><a class="link" href="{{ route('programs.index') }}">Tampilkan semua jenjang</a></div>
    </div>
  @else
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Program</th>
            <th>Jenjang</th>
            <th>Durasi</th>
            <th>Ketersediaan kelas</th>
            <th class="num">Biaya lunas</th>
            <th class="num">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($programs as $program)
            @php
                $totalRemaining = $program->classes->sum('remaining_quota');
                $classCount = $program->classes->count();
            @endphp
            <tr>
              <td>
                <strong>{{ $program->name }}</strong>
                <span class="subline" style="max-width: 46ch;">{{ $program->description }}</span>
              </td>
              <td>{{ $levelLabels[$program->education_level] ?? $program->education_level }}</td>
              <td>{{ $program->duration }}</td>
              <td>
                @if ($totalRemaining > 0)
                  <span class="tag tag--info">{{ $totalRemaining }} kursi tersedia</span>
                @else
                  <span class="tag tag--err">Kuota penuh</span>
                @endif
                <span class="subline">{{ $classCount }} kelas aktif</span>
              </td>
              <td class="num"><strong>Rp {{ number_format($program->price, 0, ',', '.') }}</strong></td>
              <td class="num">
                <a class="btn btn--secondary btn--sm" href="{{ route('programs.show', $program) }}">Pilih kelas</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
