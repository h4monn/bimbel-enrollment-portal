@extends('layouts.admin')

@section('title', 'Kelas, Jadwal, dan Kuota')
@section('page_title', 'Kelas, jadwal, dan kuota')

@section('content')
<div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:var(--s-4);">
  <p class="t-small muted">Atur jadwal tatap muka, ruang belajar, pengajar, dan kapasitas kuota tiap kelas.</p>
  <a href="{{ route('admin.classes.create') }}" class="btn btn--primary btn--sm">Tambah kelas</a>
</div>

<div class="table-wrap">
  <table class="data-table">
    <thead>
      <tr>
        <th>Program dan kelas</th>
        <th>Jadwal dan ruang</th>
        <th>Pengajar</th>
        <th>Kuota</th>
        <th>Status</th>
        <th class="num">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($classes as $classRoom)
        @php
            $remaining = $classRoom->remaining_quota;
            $isFull = $remaining <= 0;
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
            <span class="mono">{{ $classRoom->enrolled_count }}/{{ $classRoom->quota }}</span>
            <span class="subline">{{ $isFull ? 'Penuh' : 'Sisa ' . $remaining . ' kursi' }}</span>
          </td>
          <td><span class="tag {{ $classRoom->is_active ? 'tag--info' : '' }}">{{ $classRoom->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
          <td class="num">
            <a href="{{ route('admin.classes.edit', $classRoom) }}" class="link" style="margin-right:var(--s-3);">Ubah</a>
            <form method="POST" action="{{ route('admin.classes.destroy', $classRoom) }}" style="display:inline;" onsubmit="return confirm('Hapus kelas ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" style="background:none;border:0;color:var(--status-err-ink);font-weight:500;cursor:pointer;">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="muted">Belum ada kelas terdaftar.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
