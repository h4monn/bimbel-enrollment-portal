@extends('layouts.admin')

@section('title', 'Ubah Program')
@section('page_title', 'Ubah program: ' . $program->name)

@section('content')
<div class="plain-box" style="max-width:680px;">
  <form method="POST" action="{{ route('admin.programs.update', $program) }}" class="form">
    @csrf
    @method('PUT')
    <div class="field field--full">
      <label for="name">Nama program</label>
      <input id="name" name="name" type="text" value="{{ old('name', $program->name) }}" required>
    </div>

    <div class="field field--full">
      <label for="slug">Slug URL</label>
      <input id="slug" name="slug" type="text" value="{{ old('slug', $program->slug) }}" required>
    </div>

    <div class="field">
      <label for="education_level">Jenjang</label>
      <select id="education_level" name="education_level" required>
        @foreach (['SD', 'SMP', 'SMA', 'UTBK', 'UMUM'] as $lvl)
          <option value="{{ $lvl }}" @selected(old('education_level', $program->education_level) === $lvl)>{{ $lvl }}</option>
        @endforeach
      </select>
    </div>

    <div class="field">
      <label for="duration">Durasi</label>
      <input id="duration" name="duration" type="text" value="{{ old('duration', $program->duration) }}" required>
    </div>

    <div class="field field--full">
      <label for="price">Biaya lunas (Rp)</label>
      <input id="price" name="price" type="number" min="0" value="{{ old('price', $program->price) }}" required>
    </div>

    <div class="field field--full">
      <label for="description">Deskripsi program</label>
      <textarea id="description" name="description" rows="4" required>{{ old('description', $program->description) }}</textarea>
    </div>

    <div class="field field--full">
      <label class="check-row">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $program->is_active))>
        <span>Aktifkan program di katalog publik</span>
      </label>
    </div>

    <div class="btn-row">
      <button type="submit" class="btn btn--primary">Simpan perubahan</button>
      <a href="{{ route('admin.programs.index') }}" class="btn btn--secondary">Batal</a>
    </div>
  </form>
</div>
@endsection
