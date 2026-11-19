@extends('layouts.public')

@section('title', $program->name)

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 sm:py-14">
    <a href="{{ route('programs.index') }}" class="inline-flex min-h-[36px] items-center gap-1.5 text-xs font-bold text-sky-800 hover:underline">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Katalog Program
    </a>

    <div class="mt-4 rounded-xl border border-sky-200 bg-white p-6 sm:p-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <span class="inline-block rounded-md bg-sky-100 px-3 py-1 text-[11px] font-bold text-sky-800">
                    Jenjang {{ $program->education_level }}
                </span>
                <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $program->name }}</h1>
                <p class="mt-2 text-sm text-slate-600 max-w-2xl leading-relaxed">{{ $program->description }}</p>
            </div>
            <div class="rounded-xl border border-sky-200 bg-sky-50 px-6 py-4 sm:text-right shrink-0">
                <span class="block text-xs font-medium text-slate-600">Biaya Lunas ({{ $program->duration }})</span>
                <span class="mt-1 block text-2xl font-extrabold tracking-tight tabular-nums text-slate-900">
                    Rp {{ number_format($program->price, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <div class="mt-10">
        <h2 class="text-lg font-bold tracking-tight text-slate-900">Pilih Kelas & Jadwal Tatap Muka</h2>
        <p class="mt-1 text-xs text-slate-600">Sisa kuota diperbarui secara real-time. Kelas yang penuh otomatis terkunci.</p>

        @if ($program->classes->isEmpty())
            <div class="mt-4 rounded-xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-600">
                Belum ada kelas aktif yang dibuka untuk program ini.
            </div>
        @else
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                @foreach ($program->classes as $classRoom)
                    @php
                        $remaining = $classRoom->remaining_quota;
                        $isFull = $remaining <= 0;
                        $percent = $classRoom->quota > 0 ? min(100, round(($classRoom->enrolled_count / $classRoom->quota) * 100)) : 100;
                    @endphp
                    <div class="flex flex-col justify-between rounded-xl border {{ $isFull ? 'border-slate-200 bg-slate-50 opacity-80' : 'border-sky-200 bg-white' }} p-6">
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-base font-bold text-slate-900">{{ $classRoom->name }}</h3>
                                @if ($isFull)
                                    <span class="rounded-md bg-rose-100 px-2.5 py-1 text-[11px] font-bold text-rose-800 shrink-0">
                                        Kuota Penuh
                                    </span>
                                @else
                                    <span class="rounded-md bg-sky-100 px-2.5 py-1 text-[11px] font-bold text-sky-800 tabular-nums shrink-0">
                                        Sisa {{ $remaining }} / {{ $classRoom->quota }} Kursi
                                    </span>
                                @endif
                            </div>

                            {{-- Progress Bar Kuota Biru Muda --}}
                            <div class="mt-3.5">
                                <div class="h-2 w-full overflow-hidden rounded-full bg-sky-100">
                                    <div class="h-full rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-sky-400' }}" style="width: {{ $percent }}%"></div>
                                </div>
                                <p class="mt-1.5 text-[11px] font-medium text-slate-500 tabular-nums">
                                    Terisi {{ $classRoom->enrolled_count }} dari {{ $classRoom->quota }} kapasitas
                                </p>
                            </div>

                            <dl class="mt-4 space-y-2 text-xs text-slate-600 border-t border-slate-100 pt-4">
                                <div class="flex justify-between gap-2">
                                    <dt class="text-slate-500">Hari & Jam</dt>
                                    <dd class="font-semibold text-slate-900 text-right">{{ $classRoom->schedule_days }}, {{ $classRoom->schedule_time }}</dd>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <dt class="text-slate-500">Pengajar</dt>
                                    <dd class="font-semibold text-slate-900 text-right">{{ $classRoom->teacher_name }}</dd>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <dt class="text-slate-500">Ruang Kelas</dt>
                                    <dd class="font-semibold text-slate-900 text-right">{{ $classRoom->room }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-100">
                            @if ($isFull)
                                <button type="button" disabled
                                        class="min-h-[44px] w-full cursor-not-allowed rounded-lg bg-slate-200 px-4 py-2.5 text-xs font-bold text-slate-500">
                                    Kuota Penuh
                                </button>
                            @else
                                <a href="{{ route('registrations.create', $classRoom) }}"
                                   class="flex min-h-[44px] w-full items-center justify-center rounded-lg bg-sky-900 px-4 py-2.5 text-xs font-semibold text-white hover:bg-slate-900 transition-colors">
                                    Daftar Kelas Ini
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
