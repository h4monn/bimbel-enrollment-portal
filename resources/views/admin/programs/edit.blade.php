@extends('layouts.admin')

@section('title', 'Edit Program')
@section('page_title', 'Edit Program: ' . $program->name)

@section('content')
<div class="max-w-xl rounded-xl border border-slate-200 bg-white p-6">
    <form method="POST" action="{{ route('admin.programs.update', $program) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700">Nama Program</label>
            <input id="name" name="name" type="text" value="{{ old('name', $program->name) }}" required
                   class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
        </div>

        <div>
            <label for="slug" class="block text-xs font-semibold text-slate-700">Slug URL</label>
            <input id="slug" name="slug" type="text" value="{{ old('slug', $program->slug) }}" required
                   class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="education_level" class="block text-xs font-semibold text-slate-700">Jenjang</label>
                <select id="education_level" name="education_level" required class="mt-1.5 block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm">
                    @foreach (['SD', 'SMP', 'SMA', 'UTBK', 'UMUM'] as $lvl)
                        <option value="{{ $lvl }}" @selected(old('education_level', $program->education_level) === $lvl)>{{ $lvl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="duration" class="block text-xs font-semibold text-slate-700">Durasi</label>
                <input id="duration" name="duration" type="text" value="{{ old('duration', $program->duration) }}" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
            </div>
        </div>

        <div>
            <label for="price" class="block text-xs font-semibold text-slate-700">Harga Lunas (Rp)</label>
            <input id="price" name="price" type="number" min="0" value="{{ old('price', $program->price) }}" required
                   class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
        </div>

        <div>
            <label for="description" class="block text-xs font-semibold text-slate-700">Deskripsi Program</label>
            <textarea id="description" name="description" rows="4" required
                      class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">{{ old('description', $program->description) }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $program->is_active)) class="h-4 w-4 rounded border-slate-300 text-sky-600">
            <span>Aktifkan program di katalog publik</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="cursor-pointer rounded-lg bg-sky-600 px-5 py-2 text-xs font-semibold text-white hover:bg-sky-700">Simpan Perubahan</button>
            <a href="{{ route('admin.programs.index') }}" class="text-xs font-medium text-slate-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
