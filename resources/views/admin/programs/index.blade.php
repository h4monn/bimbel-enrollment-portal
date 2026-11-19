@extends('layouts.admin')

@section('title', 'Program Belajar')
@section('page_title', 'Manajemen Program Belajar')

@section('content')
<div class="flex items-center justify-between gap-4">
    <p class="text-xs text-slate-600">Kelola daftar program bimbel yang tampil di katalog publik.</p>
    <a href="{{ route('admin.programs.create') }}"
       class="inline-flex min-h-[38px] items-center rounded-lg bg-sky-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-sky-700 transition-colors shrink-0">
        Tambah Program
    </a>
</div>

<div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase">
                <tr>
                    <th class="px-5 py-3">Program</th>
                    <th class="px-5 py-3">Jenjang</th>
                    <th class="px-5 py-3">Durasi</th>
                    <th class="px-5 py-3 text-right">Harga</th>
                    <th class="px-5 py-3 text-center">Kelas</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($programs as $program)
                    <tr>
                        <td class="px-5 py-3">
                            <p class="font-semibold text-slate-900">{{ $program->name }}</p>
                            <p class="text-[11px] text-slate-400">{{ $program->slug }}</p>
                        </td>
                        <td class="px-5 py-3 font-semibold text-sky-700">{{ $program->education_level }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $program->duration }}</td>
                        <td class="px-5 py-3 text-right tabular-nums font-semibold">Rp {{ number_format($program->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-center tabular-nums">{{ $program->classes_count }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $program->is_active ? 'bg-sky-50 text-sky-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $program->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.programs.edit', $program) }}" class="font-semibold text-sky-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" class="inline" onsubmit="return confirm('Hapus program ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cursor-pointer font-semibold text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-500">Belum ada program.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
