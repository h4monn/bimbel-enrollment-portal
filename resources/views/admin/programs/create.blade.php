@extends('layouts.admin')

@section('title', 'Tambah Program')
@section('page_title', 'Tambah Program Baru')

@section('content')
<div class="max-w-xl rounded-xl border border-slate-200 bg-white p-6">
    <form method="POST" action="{{ route('admin.programs.store') }}" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700">Nama Program</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                   class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm focus:border-sky-500 focus:outline-none">
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="education_level" class="block text-xs font-semibold text-slate-700">Jenjang</label>
                <select id="education_level" name="education_level" required class="mt-1.5 block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm">
                    @foreach (['SD', 'SMP', 'SMA', 'UTBK', 'UMUM'] as $lvl)
                        <option value="{{ $lvl }}" @selected(old('education_level') === $lvl)>{{ $lvl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="duration" class="block text-xs font-semibold text-slate-700">Durasi</label>
                <input id="duration" name="duration" type="text" value="{{ old('duration') }}" placeholder="Contoh: 1 Semester (6 Bulan)" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
            </div>
        </div>

        <div>
            <label for="price" class="block text-xs font-semibold text-slate-700">Harga Lunas (Rp)</label>
            <input id="price" name="price" type="number" min="0" value="{{ old('price') }}" required
                   class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">
        </div>

        <div>
            <label for="description" class="block text-xs font-semibold text-slate-700">Deskripsi Program</label>
            <textarea id="description" name="description" rows="4" required
                      class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm">{{ old('description') }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="h-4 w-4 rounded border-slate-300 text-sky-600">
            <span>Aktifkan program di katalog publik</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="cursor-pointer rounded-lg bg-sky-600 px-5 py-2 text-xs font-semibold text-white hover:bg-sky-700">Simpan Program</button>
            <a href="{{ route('admin.programs.index') }}" class="text-xs font-medium text-slate-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
