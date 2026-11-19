@extends('layouts.public')

@section('title', 'Formulir Pendaftaran Siswa')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-12">
    {{-- Stepper Visual 3 Langkah --}}
    <nav aria-label="Tahapan pendaftaran" class="mb-8">
        <ol class="grid grid-cols-3 gap-2 rounded-xl border border-sky-200 bg-sky-50/60 p-2 text-xs font-bold">
            <li class="flex items-center justify-center gap-2 rounded-lg bg-sky-900 px-3 py-2.5 text-white">
                <span class="font-mono">01</span>
                <span>Data & Syarat</span>
            </li>
            <li class="flex items-center justify-center gap-2 rounded-lg px-3 py-2.5 text-slate-500">
                <span class="font-mono">02</span>
                <span>Pembayaran 24 Jam</span>
            </li>
            <li class="flex items-center justify-center gap-2 rounded-lg px-3 py-2.5 text-slate-500">
                <span class="font-mono">03</span>
                <span>Konfirmasi</span>
            </li>
        </ol>
    </nav>

    <div class="grid gap-8 lg:grid-cols-12 lg:items-start">
        {{-- Ringkasan Kelas Terpilih (Kartu atas di HP, Sticky kanan di Desktop) --}}
        <aside class="lg:col-span-5 lg:order-2 lg:sticky lg:top-24">
            <div class="rounded-xl border border-sky-200 bg-sky-50 p-6">
                <span class="inline-block rounded-md bg-sky-100 px-2.5 py-1 text-[11px] font-bold text-sky-800">
                    Ringkasan Kelas Terpilih
                </span>
                <h2 class="mt-3 text-lg font-bold text-slate-900">{{ $classRoom->program->name }}</h2>
                <p class="text-sm font-semibold text-sky-900">{{ $classRoom->name }}</p>

                <dl class="mt-4 space-y-2 border-t border-sky-200/70 pt-4 text-xs text-slate-600">
                    <div class="flex justify-between gap-2">
                        <dt>Hari & Jam</dt>
                        <dd class="font-semibold text-slate-900 text-right">{{ $classRoom->schedule_days }}, {{ $classRoom->schedule_time }}</dd>
                    </div>
                    <div class="flex justify-between gap-2">
                        <dt>Pengajar</dt>
                        <dd class="font-semibold text-slate-900 text-right">{{ $classRoom->teacher_name }}</dd>
                    </div>
                    <div class="flex justify-between gap-2">
                        <dt>Ruang Kelas</dt>
                        <dd class="font-semibold text-slate-900 text-right">{{ $classRoom->room }}</dd>
                    </div>
                    <div class="flex justify-between gap-2">
                        <dt>Sisa Kuota Saat Ini</dt>
                        <dd class="font-bold text-sky-800 tabular-nums text-right">{{ $classRoom->remaining_quota }} / {{ $classRoom->quota }} kursi</dd>
                    </div>
                </dl>

                <div class="mt-4 border-t border-sky-200 pt-4 flex items-baseline justify-between">
                    <span class="text-xs font-bold text-slate-700">Total Tagihan Lunas</span>
                    <span class="text-xl font-extrabold tabular-nums text-slate-900">
                        Rp {{ number_format($classRoom->program->price, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </aside>

        {{-- Formulir Multi-Bagian --}}
        <div class="lg:col-span-7 lg:order-1">
            <form id="registration-form" method="POST" action="{{ route('registrations.store') }}" class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 sm:p-8">
                @csrf
                <input type="hidden" name="class_id" value="{{ $classRoom->id }}">

                <div>
                    <h1 class="text-xl font-bold text-slate-900">Formulir Pendaftaran Siswa</h1>
                    <p class="mt-1 text-xs text-slate-600">Kuota kursi akan dikunci otomatis selama 24 jam setelah formulir ini dikirim.</p>
                </div>

                @error('class_id')
                    <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-xs font-semibold text-rose-800">
                        {{ $message }}
                    </div>
                @enderror

                {{-- Bagian 1: Data Siswa --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-bold text-sky-900 border-b border-sky-100 pb-2">1. Data Siswa</h2>

                    <div>
                        <label for="student_full_name" class="block text-xs font-bold text-slate-700">Nama Lengkap Siswa</label>
                        <input id="student_full_name" name="student_full_name" type="text" value="{{ old('student_full_name') }}" required
                               class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                        @error('student_full_name') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="birth_date" class="block text-xs font-bold text-slate-700">Tanggal Lahir</label>
                            <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date') }}" required
                                   class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                            @error('birth_date') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="school_name" class="block text-xs font-bold text-slate-700">Asal Sekolah</label>
                            <input id="school_name" name="school_name" type="text" value="{{ old('school_name') }}" placeholder="Contoh: SMPN 1 Jakarta" required
                                   class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                            @error('school_name') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="education_level" class="block text-xs font-bold text-slate-700">Jenjang</label>
                            <select id="education_level" name="education_level" required
                                    class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                                @foreach (['SD', 'SMP', 'SMA', 'UTBK', 'UMUM'] as $lvl)
                                    <option value="{{ $lvl }}" @selected(old('education_level', $classRoom->program->education_level) === $lvl)>{{ $lvl }}</option>
                                @endforeach
                            </select>
                            @error('education_level') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="grade" class="block text-xs font-bold text-slate-700">Kelas / Tingkat</label>
                            <input id="grade" name="grade" type="text" value="{{ old('grade') }}" placeholder="Contoh: Kelas 8" required
                                   class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                            @error('grade') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Bagian 2: Data Orang Tua / Wali --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-bold text-sky-900 border-b border-sky-100 pb-2">2. Data Orang Tua / Wali</h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700">Nama Akun / Wali</label>
                            <input type="text" value="{{ $user->name }}" disabled
                                   class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700">Email Penerima Konfirmasi</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="whatsapp" class="block text-xs font-bold text-slate-700">Nomor WhatsApp Aktif</label>
                            <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp', $user->whatsapp) }}" placeholder="081234567890" required
                                   class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                            @error('whatsapp') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Bagian 3: Persetujuan Syarat & Kebijakan Refund --}}
                <div class="rounded-xl border border-sky-200 bg-sky-50/70 p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="agree_terms" value="1" @checked(old('agree_terms')) required
                               class="mt-0.5 h-4 w-4 rounded border-slate-300 text-sky-800 focus:ring-sky-600">
                        <span class="text-xs text-slate-700 leading-relaxed">
                            Saya menyatakan data di atas benar dan menyetujui <strong>Syarat & Ketentuan</strong>, termasuk kebijakan bahwa <strong>pembayaran lunas di muka tidak dapat di-refund</strong> kecuali apabila kelas dibatalkan oleh pihak bimbel atau terjadi pembayaran ganda akibat kendala sistem.
                        </span>
                    </label>
                    @error('agree_terms') <p class="mt-2 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                </div>

                <button type="submit" id="submit-registration-btn"
                        class="min-h-[48px] w-full cursor-pointer rounded-lg bg-sky-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-900 transition-colors">
                    Kunci Kuota & Lanjut ke Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('registration-form')?.addEventListener('submit', function () {
        const btn = document.getElementById('submit-registration-btn');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Memproses & Mengunci Kuota...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        }
    });
</script>
@endpush
