@extends('layouts.admin')

@section('title', 'Ubah Kelas')
@section('page_title', 'Ubah kelas: ' . $classRoom->name)

@section('content')
<div class="plain-box" style="max-width:680px;">
  <form method="POST" action="{{ route('admin.classes.update', $classRoom) }}" class="form">
    @csrf
    @method('PUT')
    <div class="field field--full">
      <label for="program_id">Program</label>
      <select id="program_id" name="program_id" required>
        @foreach ($programs as $program)
          <option value="{{ $program->id }}" @selected(old('program_id', $classRoom->program_id) == $program->id)>{{ $program->name }} ({{ $program->education_level }})</option>
        @endforeach
      </select>
    </div>

    <div class="field">
      <label for="name">Nama kelas</label>
      <input id="name" name="name" type="text" value="{{ old('name', $classRoom->name) }}" required>
    </div>

    <div class="field">
      <label for="teacher_name">Nama pengajar</label>
      <input id="teacher_name" name="teacher_name" type="text" value="{{ old('teacher_name', $classRoom->teacher_name) }}" required>
    </div>

    <div class="field">
      <label for="schedule_days">Hari belajar</label>
      <input id="schedule_days" name="schedule_days" type="text" value="{{ old('schedule_days', $classRoom->schedule_days) }}" required>
    </div>

    <div class="field">
      <label for="schedule_time">Jam belajar</label>
      <input id="schedule_time" name="schedule_time" type="text" value="{{ old('schedule_time', $classRoom->schedule_time) }}" required>
    </div>

    <div class="field">
      <label for="room">Ruang kelas</label>
      <input id="room" name="room" type="text" value="{{ old('room', $classRoom->room) }}" required>
    </div>

    <div class="field">
      <label for="quota">Kuota (terisi saat ini: {{ $classRoom->enrolled_count }})</label>
      <input id="quota" name="quota" type="number" min="{{ $classRoom->enrolled_count }}" value="{{ old('quota', $classRoom->quota) }}" required>
    </div>

    <div class="field field--full">
      <label class="check-row">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $classRoom->is_active))>
        <span>Kelas aktif dan dapat dipilih pendaftar</span>
      </label>
    </div>

    <div class="btn-row">
      <button type="submit" class="btn btn--primary">Simpan perubahan</button>
      <a href="{{ route('admin.classes.index') }}" class="btn btn--secondary">Batal</a>
    </div>
  </form>
</div>
@endsection
