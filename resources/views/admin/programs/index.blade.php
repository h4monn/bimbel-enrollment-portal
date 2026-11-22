@extends('layouts.admin')

@section('title', 'Program Belajar')
@section('page_title', 'Program belajar')

@section('content')
<div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:var(--s-4);">
  <p class="t-small muted">Daftar program bimbel yang tampil di katalog publik.</p>
  <a href="{{ route('admin.programs.create') }}" class="btn btn--primary btn--sm">Tambah program</a>
</div>

<div class="table-wrap">
  <table class="data-table">
    <thead>
      <tr>
        <th>Program</th>
        <th>Jenjang</th>
        <th>Durasi</th>
        <th class="num">Biaya lunas</th>
        <th class="num">Kelas</th>
        <th>Status</th>
        <th class="num">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($programs as $program)
        <tr>
          <td>
            <strong>{{ $program->name }}</strong>
            <span class="subline mono">{{ $program->slug }}</span>
          </td>
          <td>{{ $program->education_level }}</td>
          <td>{{ $program->duration }}</td>
          <td class="num"><strong>Rp {{ number_format($program->price, 0, ',', '.') }}</strong></td>
          <td class="num">{{ $program->classes_count }}</td>
          <td><span class="tag {{ $program->is_active ? 'tag--info' : '' }}">{{ $program->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
          <td class="num">
            <a href="{{ route('admin.programs.edit', $program) }}" class="link" style="margin-right:var(--s-3);">Ubah</a>
            <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" style="display:inline;" onsubmit="return confirm('Hapus program ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" style="background:none;border:0;color:var(--status-err-ink);font-weight:500;cursor:pointer;">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="muted">Belum ada program.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
