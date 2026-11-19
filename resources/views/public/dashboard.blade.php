@extends('layouts.public')

@section('title', 'Riwayat & Status Pendaftaran')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 sm:py-14">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-sky-700">Akun Saya</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Riwayat & Status Pendaftaran</h1>
            <p class="mt-1 text-xs text-slate-600">Pantau status pendaftaran, batas waktu pembayaran, dan bukti konfirmasi kelas.</p>
        </div>
        <a href="{{ route('programs.index') }}"
           class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-sky-900 px-5 py-2.5 text-xs font-semibold text-white hover:bg-slate-900 transition-colors">
            Daftar Program Baru
        </a>
    </div>

    @if ($registrations->isEmpty())
        <div class="mt-8 rounded-xl border border-slate-200 bg-white p-10 text-center">
            <p class="text-sm font-bold text-slate-800">Anda belum memiliki riwayat pendaftaran.</p>
            <p class="mt-1 text-xs text-slate-500">Pilih program belajar untuk mulai mendaftarkan siswa.</p>
            <a href="{{ route('programs.index') }}"
               class="mt-5 inline-flex min-h-[44px] items-center justify-center rounded-lg bg-sky-900 px-5 py-2.5 text-xs font-semibold text-white hover:bg-slate-900">
                Lihat Katalog Program
            </a>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($registrations as $registration)
                @php
                    $statusStyles = [
                        'pending_payment' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-100 text-amber-800'],
                        'paid' => ['label' => 'Lunas', 'class' => 'bg-emerald-100 text-emerald-800'],
                        'confirmed' => ['label' => 'Lunas & Dikonfirmasi', 'class' => 'bg-emerald-100 text-emerald-800'],
                        'cancelled' => ['label' => 'Dibatalkan / Kedaluwarsa', 'class' => 'bg-rose-100 text-rose-800'],
                        'refunded' => ['label' => 'Refund', 'class' => 'bg-rose-100 text-rose-800'],
                    ];
                    $badge = $statusStyles[$registration->status] ?? ['label' => $registration->status, 'class' => 'bg-slate-100 text-slate-700'];
                @endphp

                <div class="rounded-xl border border-sky-200 bg-white p-5 sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2.5">
                                <span class="font-mono text-xs font-bold text-sky-900">{{ $registration->registration_number }}</span>
                                <span class="rounded-md px-2.5 py-0.5 text-[11px] font-bold {{ $badge['class'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </div>
                            <h2 class="mt-2 text-base font-bold text-slate-900">
                                {{ $registration->classRoom->program->name }} - {{ $registration->classRoom->name }}
                            </h2>
                            <p class="mt-1 text-xs text-slate-600">
                                Siswa: <strong>{{ $registration->student->full_name }}</strong> |
                                {{ $registration->classRoom->schedule_days }}, {{ $registration->classRoom->schedule_time }} (Ruang {{ $registration->classRoom->room }})
                            </p>
                        </div>

                        <div class="sm:text-right shrink-0">
                            <p class="text-base font-extrabold tabular-nums text-slate-900">Rp {{ number_format($registration->total_amount, 0, ',', '.') }}</p>
                            @if ($registration->latestPayment?->paid_at)
                                <p class="text-[11px] text-emerald-800 font-semibold mt-0.5">
                                    Lunas {{ $registration->latestPayment->paid_at->translatedFormat('d M Y, H:i') }}
                                </p>
                            @elseif ($registration->status === 'pending_payment')
                                <p class="text-[11px] text-amber-800 font-semibold mt-0.5">
                                    Batas bayar: {{ $registration->expired_at->translatedFormat('d M Y, H:i') }} WIB
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3.5 text-xs text-slate-500">
                        <span>Metode: {{ $registration->latestPayment?->payment_method ? strtoupper($registration->latestPayment->payment_method) : 'Belum dipilih' }}</span>

                        @if ($registration->status === 'pending_payment')
                            <a href="{{ route('registrations.checkout', $registration) }}"
                               class="inline-flex min-h-[40px] items-center rounded-lg bg-sky-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-900 transition-colors">
                                Lanjutkan Bayar
                            </a>
                        @elseif (in_array($registration->status, ['paid', 'confirmed'], true))
                            <a href="{{ route('registrations.success', $registration) }}"
                               class="inline-flex min-h-[40px] items-center rounded-lg border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-bold text-sky-900 hover:bg-sky-100 transition-colors">
                                Lihat Bukti Pendaftaran
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
