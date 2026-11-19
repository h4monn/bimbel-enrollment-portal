@extends('layouts.public')

@section('title', 'Konfirmasi Pendaftaran')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 sm:py-12">
    {{-- Stepper Visual 3 Langkah --}}
    <nav aria-label="Tahapan pendaftaran" class="mb-8">
        <ol class="grid grid-cols-3 gap-2 rounded-xl border border-sky-200 bg-sky-50/60 p-2 text-xs font-bold">
            <li class="flex items-center justify-center gap-2 rounded-lg px-3 py-2.5 text-sky-800">
                <span class="font-mono">01</span>
                <span>Data & Syarat</span>
            </li>
            <li class="flex items-center justify-center gap-2 rounded-lg px-3 py-2.5 text-sky-800">
                <span class="font-mono">02</span>
                <span>Pembayaran</span>
            </li>
            <li class="flex items-center justify-center gap-2 rounded-lg bg-sky-900 px-3 py-2.5 text-white">
                <span class="font-mono">03</span>
                <span>Konfirmasi</span>
            </li>
        </ol>
    </nav>

    <div class="rounded-xl border border-sky-200 bg-white p-6 text-center sm:p-8">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-800">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </div>

        <span class="mt-3 inline-block rounded-md bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
            {{ $registration->status === 'confirmed' ? 'Lunas & Dikonfirmasi' : 'Menunggu Konfirmasi Pembayaran' }}
        </span>

        <h1 class="mt-3 text-xl font-bold text-slate-900 sm:text-2xl">
            Bukti Pendaftaran Kelas Tatap Muka
        </h1>
        <p class="mt-1.5 text-xs text-slate-600 leading-relaxed">
            Ringkasan jadwal dan bukti pembayaran juga dikirimkan ke email <strong>{{ $registration->user->email }}</strong> serta WhatsApp <strong>{{ $registration->user->whatsapp }}</strong>.
        </p>

        {{-- Kotak Nomor Pendaftaran Mudah Disalin --}}
        <div class="mt-6 flex items-center justify-between gap-3 rounded-xl border border-sky-200 bg-sky-50 px-5 py-4">
            <div class="text-left">
                <span class="block text-[11px] font-bold uppercase tracking-wider text-sky-800">Nomor Pendaftaran</span>
                <strong id="reg-number-text" class="mt-0.5 block font-mono text-lg font-extrabold text-slate-900">{{ $registration->registration_number }}</strong>
            </div>
            <button type="button" id="copy-reg-btn"
                    class="min-h-[38px] cursor-pointer rounded-lg border border-sky-200 bg-white px-3.5 py-1.5 text-xs font-bold text-sky-900 hover:bg-sky-100 transition-colors">
                Salin Nomor
            </button>
        </div>

        <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-left text-xs space-y-2.5">
            <div class="flex justify-between gap-2">
                <span class="text-slate-500">Nama Siswa</span>
                <span class="font-semibold text-slate-900">{{ $registration->student->full_name }}</span>
            </div>
            <div class="flex justify-between gap-2">
                <span class="text-slate-500">Program & Kelas</span>
                <span class="font-semibold text-slate-900">{{ $registration->classRoom->program->name }} - {{ $registration->classRoom->name }}</span>
            </div>
            <div class="flex justify-between gap-2">
                <span class="text-slate-500">Jadwal Tatap Muka</span>
                <span class="font-semibold text-slate-900">{{ $registration->classRoom->schedule_days }}, {{ $registration->classRoom->schedule_time }}</span>
            </div>
            <div class="flex justify-between gap-2">
                <span class="text-slate-500">Ruang & Pengajar</span>
                <span class="font-semibold text-slate-900">Ruang {{ $registration->classRoom->room }} ({{ $registration->classRoom->teacher_name }})</span>
            </div>
            <div class="flex justify-between gap-2 border-t border-slate-200 pt-2.5 text-sm">
                <span class="font-bold text-slate-900">Total Pembayaran</span>
                <strong class="tabular-nums text-sky-900">Rp {{ number_format($registration->total_amount, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="mt-6 flex flex-col gap-2.5 sm:flex-row sm:justify-center">
            <a href="{{ route('dashboard') }}"
               class="inline-flex min-h-[44px] items-center justify-center rounded-lg bg-sky-900 px-5 py-2.5 text-xs font-semibold text-white hover:bg-slate-900 transition-colors">
                Lihat Riwayat Pendaftaran
            </a>
            <a href="{{ route('programs.index') }}"
               class="inline-flex min-h-[44px] items-center justify-center rounded-lg border border-slate-200 px-5 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Daftar Siswa Lain
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('copy-reg-btn')?.addEventListener('click', function () {
        const text = document.getElementById('reg-number-text')?.textContent?.trim();
        if (!text) return;
        navigator.clipboard?.writeText(text);
        this.textContent = 'Tersalin';
        setTimeout(() => { this.textContent = 'Salin Nomor'; }, 2000);
    });
</script>
@endpush
