@extends('layouts.admin')

@section('title', 'Data Pendaftar & Pembayaran')
@section('page_title', 'Manajemen Pendaftar & Pembayaran')

@section('content')
<div class="rounded-xl border border-sky-200 bg-white p-5 space-y-4">
    <form method="GET" action="{{ route('admin.registrations.index') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
        <div class="lg:col-span-2">
            <label for="search" class="block text-[11px] font-bold text-slate-700">Cari (No. Reg / Nama Siswa / Wali / WA)</label>
            <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}" placeholder="REG-2026... atau nama siswa"
                   class="mt-1 block min-h-[40px] w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:border-sky-600 focus:outline-none">
        </div>

        <div>
            <label for="program_id" class="block text-[11px] font-bold text-slate-700">Program</label>
            <select id="program_id" name="program_id" class="mt-1 block min-h-[40px] w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs">
                <option value="">Semua Program</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" @selected(($filters['program_id'] ?? '') == $program->id)>{{ $program->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="block text-[11px] font-bold text-slate-700">Status Pendaftaran</label>
            <select id="status" name="status" class="mt-1 block min-h-[40px] w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs">
                <option value="">Semua Status</option>
                @foreach ([
                    'pending_payment' => 'Menunggu Pembayaran',
                    'paid' => 'Lunas',
                    'confirmed' => 'Dikonfirmasi',
                    'cancelled' => 'Dibatalkan',
                    'refunded' => 'Refund',
                ] as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="date_from" class="block text-[11px] font-bold text-slate-700">Dari Tanggal</label>
            <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}"
                   class="mt-1 block min-h-[40px] w-full rounded-lg border border-slate-300 px-3 py-2 text-xs">
        </div>

        <div>
            <label for="date_to" class="block text-[11px] font-bold text-slate-700">Sampai Tanggal</label>
            <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}"
                   class="mt-1 block min-h-[40px] w-full rounded-lg border border-slate-300 px-3 py-2 text-xs">
        </div>

        <div class="sm:col-span-2 lg:col-span-6 flex flex-wrap items-center justify-between gap-3 pt-1">
            <div class="flex items-center gap-2">
                <button type="submit" class="min-h-[40px] cursor-pointer rounded-lg bg-sky-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-900">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.registrations.index') }}" class="inline-flex min-h-[40px] items-center rounded-lg border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Reset
                </a>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.registrations.export', array_merge($filters, ['format' => 'csv'])) }}"
                   class="inline-flex min-h-[40px] items-center rounded-lg border border-sky-200 bg-sky-50 px-3.5 py-2 text-xs font-bold text-sky-900 hover:bg-sky-100">
                    Ekspor CSV
                </a>
                <a href="{{ route('admin.registrations.export', array_merge($filters, ['format' => 'xlsx'])) }}"
                   class="inline-flex min-h-[40px] items-center rounded-lg bg-sky-900 px-3.5 py-2 text-xs font-semibold text-white hover:bg-slate-900">
                    Ekspor Excel (.xlsx)
                </a>
            </div>
        </div>
    </form>
</div>

<div class="rounded-xl border border-sky-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-sky-50/70 text-[11px] font-bold text-slate-600 uppercase">
                <tr>
                    <th class="px-4 py-3">No. Pendaftaran</th>
                    <th class="px-4 py-3">Detail Siswa & Wali</th>
                    <th class="px-4 py-3">Program & Kelas</th>
                    <th class="px-4 py-3 text-right">Tagihan</th>
                    <th class="px-4 py-3">Status & Pembayaran</th>
                    <th class="px-4 py-3">Ubah Status / Refund</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($registrations as $reg)
                    @php
                        $regBadges = [
                            'pending_payment' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-100 text-amber-800'],
                            'paid' => ['label' => 'Lunas', 'class' => 'bg-emerald-100 text-emerald-800'],
                            'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'bg-emerald-100 text-emerald-800'],
                            'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-rose-100 text-rose-800'],
                            'refunded' => ['label' => 'Refund', 'class' => 'bg-rose-100 text-rose-800'],
                        ];
                        $rb = $regBadges[$reg->status] ?? ['label' => $reg->status, 'class' => 'bg-slate-100 text-slate-700'];
                    @endphp
                    <tr class="align-top">
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <span class="font-mono font-bold text-sky-900">{{ $reg->registration_number }}</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5 tabular-nums">{{ $reg->created_at?->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="font-bold text-slate-900">{{ $reg->student?->full_name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $reg->student?->school_name }} ({{ $reg->student?->education_level }} - {{ $reg->student?->grade }})</p>
                            <p class="text-[11px] text-slate-700 mt-1">Wali: <strong>{{ $reg->user?->name }}</strong> | WA: {{ $reg->user?->whatsapp }}</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="font-bold text-slate-900">{{ $reg->classRoom?->program?->name }}</p>
                            <p class="text-slate-600">{{ $reg->classRoom?->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $reg->classRoom?->schedule_days }}, {{ $reg->classRoom?->schedule_time }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-right tabular-nums font-bold whitespace-nowrap">
                            Rp {{ number_format($reg->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="inline-block rounded-md px-2 py-0.5 text-[11px] font-bold {{ $rb['class'] }}">
                                {{ $rb['label'] }}
                            </span>
                            <span class="block text-[11px] text-slate-500 mt-1">
                                Gateway: {{ $reg->latestPayment?->status ?? '-' }}
                                @if ($reg->latestPayment?->payment_method)
                                    ({{ strtoupper($reg->latestPayment->payment_method) }})
                                @endif
                            </span>
                            @if ($reg->latestPayment?->refund_notes)
                                <span class="block rounded bg-rose-50 px-2 py-1 text-[11px] font-medium text-rose-800 mt-1">
                                    Catatan: {{ $reg->latestPayment->refund_notes }}
                                </span>
                            @endif

                            @if (in_array($reg->status, ['pending_payment', 'cancelled'], true))
                                <form method="POST" action="{{ route('admin.registrations.verify-payment', $reg) }}" class="mt-2"
                                      onsubmit="return confirm('Verifikasi pembayaran manual & konfirmasi pendaftaran ini?')">
                                    @csrf
                                    <button type="submit" class="cursor-pointer rounded-lg bg-emerald-800 px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-emerald-900">
                                        Verifikasi Manual
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <form method="POST" action="{{ route('admin.registrations.update-status', $reg) }}" class="flex flex-col gap-1.5 min-w-[190px]">
                                @csrf
                                @method('PATCH')
                                <select name="status" aria-label="Ubah status pendaftaran" class="rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-[11px] font-semibold">
                                    @foreach ([
                                        'pending_payment' => 'Menunggu Pembayaran',
                                        'paid' => 'Lunas',
                                        'confirmed' => 'Dikonfirmasi',
                                        'cancelled' => 'Dibatalkan',
                                        'refunded' => 'Refund',
                                    ] as $key => $label)
                                        <option value="{{ $key }}" @selected($reg->status === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="refund_notes" placeholder="Alasan refund (batal kelas/dobel)"
                                       class="rounded-lg border border-slate-300 px-2.5 py-1.5 text-[11px]">
                                <button type="submit" class="cursor-pointer rounded-lg bg-sky-900 px-2.5 py-1.5 text-[11px] font-semibold text-white hover:bg-slate-900">
                                    Simpan Status
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center">
                            <p class="text-xs font-bold text-slate-700">Tidak ada data pendaftar yang cocok dengan filter pencarian.</p>
                            <a href="{{ route('admin.registrations.index') }}" class="mt-2 inline-block text-xs font-semibold text-sky-800 hover:underline">Reset Filter</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($registrations->hasPages())
        <div class="border-t border-slate-100 px-5 py-3">
            {{ $registrations->links() }}
        </div>
    @endif
</div>
@endsection
