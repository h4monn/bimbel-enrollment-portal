@extends('layouts.admin')

@section('title', 'Ringkasan Dashboard')
@section('page_title', 'Ringkasan Operasional Bimbel')

@section('content')
@php
    $nearlyFullCount = $classes->filter(fn ($c) => $c->is_active && $c->remaining_quota <= 3)->count();
@endphp

{{-- 4 Kartu KPI Utama --}}
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-xl border border-sky-200 bg-white p-5">
        <p class="text-xs font-semibold text-slate-500">Total Pendaftar</p>
        <p class="mt-2 text-2xl font-extrabold tabular-nums text-slate-900">{{ number_format($totalRegistrations) }}</p>
        <p class="mt-1 text-[11px] text-slate-500">{{ number_format($pendingCount) }} menunggu pembayaran</p>
    </div>
    <div class="rounded-xl border border-sky-200 bg-white p-5">
        <p class="text-xs font-semibold text-slate-500">Lunas & Dikonfirmasi</p>
        <p class="mt-2 text-2xl font-extrabold tabular-nums text-emerald-800">{{ number_format($confirmedCount) }}</p>
        <p class="mt-1 text-[11px] text-slate-500">Kursi terkonfirmasi aktif</p>
    </div>
    <div class="rounded-xl border border-sky-200 bg-white p-5">
        <p class="text-xs font-semibold text-slate-500">Total Pendapatan Lunas</p>
        <p class="mt-2 text-2xl font-extrabold tabular-nums text-sky-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <p class="mt-1 text-[11px] text-slate-500">Akumulasi pembayaran lunas</p>
    </div>
    <div class="rounded-xl border border-sky-200 bg-white p-5">
        <p class="text-xs font-semibold text-slate-500">Kelas Hampir / Sudah Penuh</p>
        <p class="mt-2 text-2xl font-extrabold tabular-nums {{ $nearlyFullCount > 0 ? 'text-rose-800' : 'text-slate-900' }}">{{ number_format($nearlyFullCount) }}</p>
        <p class="mt-1 text-[11px] text-slate-500">Sisa kuota &le; 3 kursi</p>
    </div>
</div>

{{-- Tabel Ringkasan Sisa Kuota per Kelas dengan Progress Bar Biru Muda --}}
<div class="rounded-xl border border-sky-200 bg-white overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-sky-100">
        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Sisa Kuota per Kelas</h2>
        <a href="{{ route('admin.classes.index') }}" class="text-xs font-bold text-sky-800 hover:underline">Kelola Kelas</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-sky-50/70 text-[11px] font-bold text-slate-600 uppercase">
                <tr>
                    <th class="px-5 py-3">Program & Kelas</th>
                    <th class="px-5 py-3">Jadwal & Ruang</th>
                    <th class="px-5 py-3">Pengajar</th>
                    <th class="px-5 py-3">Kapasitas & Progress</th>
                    <th class="px-5 py-3 text-right">Status Kuota</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($classes as $classRoom)
                    @php
                        $remaining = $classRoom->remaining_quota;
                        $isFull = $remaining <= 0;
                        $percent = $classRoom->quota > 0 ? min(100, round(($classRoom->enrolled_count / $classRoom->quota) * 100)) : 100;
                    @endphp
                    <tr>
                        <td class="px-5 py-3">
                            <p class="font-bold text-slate-900">{{ $classRoom->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $classRoom->program?->name }}</p>
                        </td>
                        <td class="px-5 py-3 text-slate-600">
                            {{ $classRoom->schedule_days }}, {{ $classRoom->schedule_time }}<br>
                            <span class="text-slate-500">Ruang {{ $classRoom->room }}</span>
                        </td>
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $classRoom->teacher_name }}</td>
                        <td class="px-5 py-3 min-w-[170px]">
                            <div class="flex justify-between text-[11px] font-semibold tabular-nums text-slate-700 mb-1">
                                <span>Terisi {{ $classRoom->enrolled_count }} / {{ $classRoom->quota }}</span>
                                <span>{{ $percent }}%</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-sky-100">
                                <div class="h-full rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-sky-400' }}" style="width: {{ $percent }}%"></div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-right tabular-nums">
                            @if ($isFull)
                                <span class="rounded-md bg-rose-100 px-2.5 py-1 text-[11px] font-bold text-rose-800">Penuh</span>
                            @else
                                <span class="rounded-md bg-sky-100 px-2.5 py-1 text-[11px] font-bold text-sky-800">Sisa {{ $remaining }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-6 text-center text-slate-500">Belum ada kelas terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pendaftar Terbaru --}}
<div class="rounded-xl border border-sky-200 bg-white overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-sky-100">
        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Pendaftar Terbaru</h2>
        <a href="{{ route('admin.registrations.index') }}" class="text-xs font-bold text-sky-800 hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-sky-50/70 text-[11px] font-bold text-slate-600 uppercase">
                <tr>
                    <th class="px-5 py-3">No. Pendaftaran</th>
                    <th class="px-5 py-3">Siswa & Wali</th>
                    <th class="px-5 py-3">Program & Kelas</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($recentRegistrations as $reg)
                    @php
                        $badges = [
                            'pending_payment' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-100 text-amber-800'],
                            'paid' => ['label' => 'Lunas', 'class' => 'bg-emerald-100 text-emerald-800'],
                            'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'bg-emerald-100 text-emerald-800'],
                            'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-rose-100 text-rose-800'],
                            'refunded' => ['label' => 'Refund', 'class' => 'bg-rose-100 text-rose-800'],
                        ];
                        $b = $badges[$reg->status] ?? ['label' => $reg->status, 'class' => 'bg-slate-100 text-slate-700'];
                    @endphp
                    <tr>
                        <td class="px-5 py-3 font-mono font-bold text-sky-900">{{ $reg->registration_number }}</td>
                        <td class="px-5 py-3">
                            <p class="font-bold text-slate-900">{{ $reg->student?->full_name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $reg->user?->name }} ({{ $reg->user?->whatsapp }})</p>
                        </td>
                        <td class="px-5 py-3 text-slate-700">{{ $reg->classRoom?->program?->name }} - {{ $reg->classRoom?->name }}</td>
                        <td class="px-5 py-3 text-right tabular-nums font-bold">Rp {{ number_format($reg->total_amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-md px-2 py-0.5 text-[11px] font-bold {{ $b['class'] }}">{{ $b['label'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-6 text-center text-slate-500">Belum ada pendaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
