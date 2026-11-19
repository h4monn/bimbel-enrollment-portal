@extends('layouts.admin')

@section('title', 'Tambah Kelas')
@section('page_title', 'Tambah Kelas Baru')

@section('content')
<div class="max-w-xl rounded-xl border border-slate-200 bg-white p-6">
    <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-4">
        @csrf
        <div>
            <label for="program_id" class="block text-xs font-semibold text-slate-700">Program</label>
            <select id="program_id" name="program_id" required class="mt-1.5 block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm">
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" @selected(old('program_id') == $program->id)>{{ $program->name }} ({{ $program->education_level }})</option>
                @endforeach
            </select>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700">Nama Kelas</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Contoh: Kelas Pagi A" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
            </div>
            <div>
                <label for="teacher_name" class="block text-xs font-semibold text-slate-700">Nama Pengajar</label>
                <input id="teacher_name" name="teacher_name" type="text" value="{{ old('teacher_name') }}" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="schedule_days" class="block text-xs font-semibold text-slate-700">Hari Belajar</label>
                <input id="schedule_days" name="schedule_days" type="text" value="{{ old('schedule_days') }}" placeholder="Contoh: Senin & Kamis" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
            </div>
            <div>
                <label for="schedule_time" class="block text-xs font-semibold text-slate-700">Jam Belajar</label>
                <input id="schedule_time" name="schedule_time" type="text" value="{{ old('schedule_time') }}" placeholder="Contoh: 15:30 - 17:00 WIB" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="room" class="block text-xs font-semibold text-slate-700">Ruang Kelas</label>
                <input id="room" name="room" type="text" value="{{ old('room') }}" placeholder="Contoh: Ruang 102" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
            </div>
            <div>
                <label for="quota" class="block text-xs font-semibold text-slate-700">Kuota Maksimal</label>
                <input id="quota" name="quota" type="number" min="1" value="{{ old('quota', 15) }}" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
            </div>
        </div>

        <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="h-4 w-4 rounded border-slate-300 text-sky-600">
            <span>Kelas aktif & dapat dipilih pendaftar</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="cursor-pointer rounded-lg bg-sky-600 px-5 py-2 text-xs font-semibold text-white hover:bg-sky-700">Simpan Kelas</button>
            <a href="{{ route('admin.classes.index') }}" class="text-xs font-medium text-slate-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
