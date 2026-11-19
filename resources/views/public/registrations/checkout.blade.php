@extends('layouts.public')

@section('title', 'Pembayaran Pendaftaran')

@push('head')
    @if ($snapToken)
        <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
@endpush

@section('content')
<div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 sm:py-12">
    {{-- Stepper Visual 3 Langkah --}}
    <nav aria-label="Tahapan pendaftaran" class="mb-8">
        <ol class="grid grid-cols-3 gap-2 rounded-xl border border-sky-200 bg-sky-50/60 p-2 text-xs font-bold">
            <li class="flex items-center justify-center gap-2 rounded-lg px-3 py-2.5 text-sky-800">
                <span class="font-mono">01</span>
                <span>Data & Syarat</span>
            </li>
            <li class="flex items-center justify-center gap-2 rounded-lg bg-sky-900 px-3 py-2.5 text-white">
                <span class="font-mono">02</span>
                <span>Pembayaran 24 Jam</span>
            </li>
            <li class="flex items-center justify-center gap-2 rounded-lg px-3 py-2.5 text-slate-500">
                <span class="font-mono">03</span>
                <span>Konfirmasi</span>
            </li>
        </ol>
    </nav>

    <div class="rounded-xl border border-sky-200 bg-white p-6 sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <span class="rounded-md bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">
                Menunggu Pembayaran
            </span>
            <span class="text-xs text-slate-600">
                No. Pendaftaran: <strong class="font-mono text-sky-900">{{ $registration->registration_number }}</strong>
            </span>
        </div>

        <h1 class="mt-4 text-xl font-bold text-slate-900">Selesaikan Pembayaran Lunas</h1>
        <p class="mt-1 text-xs text-slate-600">
            Kuota kelas telah dikunci sementara untuk Anda. Selesaikan pembayaran sebelum batas waktu 24 jam berakhir.
        </p>

        {{-- Countdown Timer 24 Jam --}}
        <div class="mt-5 rounded-xl border border-sky-200 bg-sky-50 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-sky-800">Batas Waktu Pembayaran</p>
                <p class="text-xs font-semibold text-slate-700 mt-0.5">
                    {{ $registration->expired_at->translatedFormat('d M Y, H:i') }} WIB
                </p>
            </div>
            <div class="sm:text-right">
                <span class="text-[11px] text-slate-500 block">Sisa Waktu</span>
                <span id="payment-countdown" data-expires="{{ $registration->expired_at->toIso8601String() }}"
                      class="font-mono text-lg font-extrabold tabular-nums text-sky-900">
                    --:--:--
                </span>
            </div>
        </div>

        <dl class="mt-6 space-y-2.5 rounded-xl border border-slate-200 bg-slate-50/60 p-4 text-xs">
            <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Nama Siswa</dt>
                <dd class="font-semibold text-slate-900 text-right">{{ $registration->student->full_name }}</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Program & Kelas</dt>
                <dd class="font-semibold text-slate-900 text-right">{{ $registration->classRoom->program->name }} - {{ $registration->classRoom->name }}</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Jadwal & Ruang</dt>
                <dd class="font-semibold text-slate-900 text-right">{{ $registration->classRoom->schedule_days }}, {{ $registration->classRoom->schedule_time }} ({{ $registration->classRoom->room }})</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Order ID</dt>
                <dd class="font-mono font-semibold text-slate-700 text-right">{{ $registration->latestPayment?->order_id }}</dd>
            </div>
            <div class="flex justify-between gap-4 border-t border-slate-200 pt-3 text-sm">
                <dt class="font-bold text-slate-900">Total Tagihan Lunas</dt>
                <dd class="font-extrabold tabular-nums text-sky-900 text-right">Rp {{ number_format($registration->total_amount, 0, ',', '.') }}</dd>
            </div>
        </dl>

        <div class="mt-6 space-y-2.5">
            @if ($snapToken)
                <button type="button" id="pay-button"
                        class="min-h-[48px] w-full cursor-pointer rounded-lg bg-sky-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-900 transition-colors">
                    Bayar Sekarang via Midtrans (VA / QRIS / E-Wallet)
                </button>
            @endif

            @if ($redirectUrl)
                <a href="{{ $redirectUrl }}" target="_blank" rel="noopener"
                   class="flex min-h-[44px] w-full items-center justify-center rounded-lg border border-sky-200 bg-sky-50 px-4 py-2.5 text-xs font-bold text-sky-900 hover:bg-sky-100 transition-colors">
                    Buka Halaman Pembayaran Midtrans
                </a>
            @endif

            <a href="{{ route('dashboard') }}"
               class="flex min-h-[44px] w-full items-center justify-center rounded-lg border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Cek Status di Riwayat Pendaftaran
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        (function () {
            const el = document.getElementById('payment-countdown');
            if (!el) return;
            const target = new Date(el.dataset.expires).getTime();

            function tick() {
                const diff = Math.max(0, Math.floor((target - Date.now()) / 1000));
                if (diff <= 0) {
                    el.textContent = 'Kedaluwarsa';
                    return;
                }
                const h = String(Math.floor(diff / 3600)).padStart(2, '0');
                const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
                const s = String(diff % 60).padStart(2, '0');
                el.textContent = `${h}j : ${m}m : ${s}d`;
            }

            tick();
            setInterval(tick, 1000);
        })();
    </script>
    @if ($snapToken)
        <script>
            document.getElementById('pay-button')?.addEventListener('click', function () {
                window.snap.pay(@json($snapToken), {
                    onSuccess: function () {
                        window.location.href = @json(route('registrations.success', $registration));
                    },
                    onPending: function () {
                        window.location.href = @json(route('dashboard'));
                    },
                    onError: function () {
                        alert('Pembayaran gagal diproses. Silakan coba metode pembayaran lain.');
                    }
                });
            });
        </script>
    @endif
@endpush
