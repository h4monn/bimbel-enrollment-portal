@extends('layouts.public')

@section('title', 'Katalog Program Belajar')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 sm:py-14">
    <div class="max-w-xl">
        <p class="text-xs font-bold uppercase tracking-wider text-sky-700">Katalog Program</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Pilih Program Belajar</h1>
        <p class="mt-2 text-sm text-slate-600">Seluruh kelas berlangsung tatap muka (offline) dengan biaya penuh yang transparan.</p>
    </div>

    @php
        $levelLabels = [
            'SD' => 'SD',
            'SMP' => 'SMP',
            'SMA' => 'SMA',
            'UTBK' => 'UTBK',
            'UMUM' => 'Mapel Khusus / Umum',
        ];
    @endphp

    <div class="mt-6 flex flex-wrap gap-2" role="navigation" aria-label="Filter jenjang program">
        <a href="{{ route('programs.index') }}"
           class="inline-flex min-h-[44px] items-center rounded-lg px-4 py-2 text-xs font-semibold transition-colors {{ ! $activeLevel ? 'bg-sky-900 text-white' : 'border border-slate-200 bg-white text-slate-700 hover:bg-sky-50 hover:border-sky-200' }}">
            Semua Jenjang
        </a>
        @foreach ($levelLabels as $code => $label)
            <a href="{{ route('programs.index', ['level' => $code]) }}"
               class="inline-flex min-h-[44px] items-center rounded-lg px-4 py-2 text-xs font-semibold transition-colors {{ $activeLevel === $code ? 'bg-sky-900 text-white' : 'border border-slate-200 bg-white text-slate-700 hover:bg-sky-50 hover:border-sky-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($programs->isEmpty())
        <div class="mt-8 rounded-xl border border-slate-200 bg-white p-12 text-center">
            <p class="text-sm font-bold text-slate-800">Belum ada program aktif untuk jenjang ini.</p>
            <p class="mt-1 text-xs text-slate-500">Silakan pilih filter jenjang lainnya.</p>
            <a href="{{ route('programs.index') }}" class="mt-4 inline-flex min-h-[40px] items-center rounded-lg border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-semibold text-sky-900 hover:bg-sky-100">
                Tampilkan Semua Program
            </a>
        </div>
    @else
        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($programs as $program)
                @php
                    $totalRemaining = $program->classes->sum('remaining_quota');
                @endphp
                <article class="flex flex-col justify-between rounded-xl border border-sky-200 bg-white p-6">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="rounded-md bg-sky-100 px-2.5 py-1 text-[11px] font-bold text-sky-800">
                                {{ $levelLabels[$program->education_level] ?? $program->education_level }}
                            </span>
                            @if ($totalRemaining > 0)
                                <span class="rounded-md bg-sky-100 px-2.5 py-1 text-[11px] font-bold text-sky-800 tabular-nums">
                                    {{ $totalRemaining }} kursi tersedia
                                </span>
                            @else
                                <span class="rounded-md bg-rose-100 px-2.5 py-1 text-[11px] font-bold text-rose-800">
                                    Kuota Penuh
                                </span>
                            @endif
                        </div>
                        <h2 class="mt-3.5 text-lg font-bold tracking-tight text-slate-900">{{ $program->name }}</h2>
                        <p class="mt-1.5 text-xs text-slate-600 leading-relaxed">{{ $program->description }}</p>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-4">
                        <div class="flex items-baseline justify-between">
                            <div>
                                <span class="block text-[11px] text-slate-500">Durasi: {{ $program->duration }}</span>
                                <span class="mt-0.5 block text-xl font-extrabold tracking-tight tabular-nums text-slate-900">
                                    Rp {{ number_format($program->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="text-xs font-semibold text-slate-500 tabular-nums">{{ $program->classes->count() }} kelas</span>
                        </div>

                        <a href="{{ route('programs.show', $program) }}"
                           class="mt-4 flex min-h-[44px] w-full items-center justify-center rounded-lg bg-sky-900 px-4 py-2.5 text-xs font-semibold text-white hover:bg-slate-900 transition-colors">
                            Lihat Jadwal & Pilih Kelas
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
