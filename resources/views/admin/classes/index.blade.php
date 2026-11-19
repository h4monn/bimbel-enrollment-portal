@extends('layouts.admin')

@section('title', 'Kelas, Jadwal & Kuota')
@section('page_title', 'Manajemen Kelas, Jadwal, Pengajar & Kuota')

@section('content')
<div class="flex items-center justify-between gap-4">
    <p class="text-xs text-slate-600">Atur jadwal tatap muka, ruang, pengajar, dan kapasitas kuota setiap kelas.</p>
    <a href="{{ route('admin.classes.create') }}"
       class="inline-flex min-h-[38px] items-center rounded-lg bg-sky-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-sky-700 transition-colors shrink-0">
        Tambah Kelas
    </a>
</div>

<div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase">
                <tr>
                    <th class="px-5 py-3">Program & Kelas</th>
                    <th class="px-5 py-3">Jadwal & Ruang</th>
                    <th class="px-5 py-3">Pengajar</th>
                    <th class="px-5 py-3 text-right">Terisi / Kuota</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($classes as $classRoom)
                    <tr>
                        <td class="px-5 py-3">
                            <p class="font-semibold text-slate-900">{{ $classRoom->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $classRoom->program?->name }}</p>
                        </td>
                        <td class="px-5 py-3 text-slate-600">
                            {{ $classRoom->schedule_days }}, {{ $classRoom->schedule_time }}<br>
                            <span class="text-slate-400">Ruang {{ $classRoom->room }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-700">{{ $classRoom->teacher_name }}</td>
                        <td class="px-5 py-3 text-right tabular-nums font-semibold">
                            {{ $classRoom->enrolled_count }} / {{ $classRoom->quota }}
                            <span class="block text-[11px] font-medium {{ $classRoom->remaining_quota > 0 ? 'text-sky-700' : 'text-red-600' }}">
                                Sisa {{ $classRoom->remaining_quota }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $classRoom->is_active ? 'bg-sky-50 text-sky-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $classRoom->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.classes.edit', $classRoom) }}" class="font-semibold text-sky-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.classes.destroy', $classRoom) }}" class="inline" onsubmit="return confirm('Hapus kelas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cursor-pointer font-semibold text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-500">Belum ada kelas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
