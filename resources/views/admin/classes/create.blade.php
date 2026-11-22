@extends('layouts.admin')

@section('title', 'Tambah Kelas')
@section('page_title', 'Tambah kelas baru')

@section('content')
<div class="plain-box" style="max-width:680px;">
  <form method="POST" action="{{ route('admin.classes.store') }}" class="form">
    @csrf
    <div class="field field--full">
      <label for="program_id">Program</label>
      <select id="program_id" name="program_id" required>
        @foreach ($programs as $program)
          <option value="{{ $program->id }}" @selected(old('program_id') == $program->id)>{{ $program->name }} ({{ $program->education_level }})</option>
        @endforeach
      </select>
    </div>

    <div class="field">
      <label for="name">Nama kelas</label>
      <input id="name" name="name" type="text" value="{{ old('name') }}" required>
    </div>

    <div class="field">
      <label for="teacher_name">Nama pengajar</label>
      <input id="teacher_name" name="teacher_name" type="text" value="{{ old('teacher_name') }}" required>
    </div>

    <div class="field">
      <label for="schedule_days">Hari belajar</label>
      <input id="schedule_days" name="schedule_days" type="text" value="{{ old('schedule_days') }}" required>
    </div>

    <div class="field">
      <label for="schedule_time">Jam belajar</label>
      <input id="schedule_time" name="schedule_time" type="text" value="{{ old('schedule_time') }}" required>
    </div>

    <div class="field">
      <label for="room">Ruang kelas</label>
      <input id="room" name="room" type="text" value="{{ old('room') }}" required>
    </div>

    <div class="field">
      <label for="quota">Kuota maksimal</label>
      <input id="quota" name="quota" type="number" min="1" value="{{ old('quota', 15) }}" required>
    </div>

    <div class="field field--full">
      <label class="check-row">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
        <span>Kelas aktif dan dapat dipilih pendaftar</span>
      </label>
    </div>

    <div class="btn-row">
      <button type="submit" class="btn btn--primary">Simpan kelas</button>
      <a href="{{ route('admin.classes.index') }}" class="btn btn--secondary">Batal</a>
    </div>
  </form>
</div>
@endsection
