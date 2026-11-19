@extends('layouts.public')

@section('title', 'Bimbingan Belajar Tatap Muka')

@section('content')
{{-- Hero Section (Gradasi Biru Muda Lembut) --}}
<section class="border-b border-sky-100 bg-gradient-to-b from-sky-50 via-sky-50/40 to-white">
    <div class="mx-auto max-w-5xl px-4 py-16 sm:px-6 sm:py-24 text-center">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-5xl max-w-3xl mx-auto leading-[1.15]">
            Daftar Bimbingan Belajar Tatap Muka <span class="text-sky-700">Lebih Cepat</span> dan Transparan
        </h1>

        <p class="mt-5 text-base text-slate-600 max-w-xl mx-auto leading-relaxed">
            Pilih program SD, SMP, SMA, atau UTBK, pantau sisa kuota kelas secara real-time, dan selesaikan pembayaran langsung dari HP.
        </p>

        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="{{ route('programs.index') }}"
               class="inline-flex min-h-[46px] w-full sm:w-auto items-center justify-center gap-2 rounded-lg bg-sky-900 px-7 py-3 text-sm font-semibold text-white hover:bg-slate-900 transition-colors">
                Lihat Program
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
            @guest('web')
                <a href="{{ route('register') }}"
                   class="inline-flex min-h-[46px] w-full sm:w-auto items-center justify-center rounded-lg border border-sky-200 bg-white px-7 py-3 text-sm font-semibold text-sky-900 hover:bg-sky-50 transition-colors">
                    Buat Akun Pendaftar
                </a>
            @endguest
        </div>
    </div>
</section>

{{-- Alur Pendaftaran 4 Langkah --}}
<section id="cara-daftar" class="mx-auto max-w-6xl px-4 py-14 sm:px-6 border-b border-slate-100">
    <div class="text-center max-w-xl mx-auto">
        <p class="text-xs font-bold uppercase tracking-wider text-sky-700">Alur Pendaftaran</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Daftar Kelas dalam 4 Langkah</h2>
    </div>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['num' => '01', 'title' => 'Pilih Program & Kelas', 'desc' => 'Lihat jadwal tatap muka, nama pengajar, ruang kelas, dan sisa kuota real-time.'],
            ['num' => '02', 'title' => 'Masuk & Isi Data Siswa', 'desc' => 'Login dengan Google atau email (langsung aktif), lalu isi data siswa dan wali.'],
            ['num' => '03', 'title' => 'Bayar Lunas (24 Jam)', 'desc' => 'Kuota dikunci sementara selama 24 jam. Bayar praktis lewat VA, QRIS, atau E-Wallet.'],
            ['num' => '04', 'title' => 'Konfirmasi Otomatis', 'desc' => 'Bukti lunas dan nomor pendaftaran langsung dikirim ke Email serta WhatsApp.'],
        ] as $step)
            <div class="rounded-xl border border-sky-200/80 bg-sky-50/40 p-5">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-sky-900 font-mono text-xs font-bold text-white">
                    {{ $step['num'] }}
                </span>
                <h3 class="mt-3.5 text-sm font-bold text-slate-900">{{ $step['title'] }}</h3>
                <p class="mt-1.5 text-xs text-slate-600 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Keunggulan Bimbel --}}
<section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-sky-700">Keunggulan Kami</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Dirancang untuk Kenyamanan Orang Tua</h2>
        </div>
        <p class="text-sm text-slate-600 max-w-sm">Informasi biaya, jadwal, dan sisa kursi terbuka sejak awal tanpa harus menunggu balasan chat.</p>
    </div>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $features = [
                ['title' => 'Pengajar Spesialis', 'desc' => 'Dibimbing langsung oleh tutor berpengalaman sesuai jenjang pendidikan.'],
                ['title' => 'Kuota Kelas Terbatas', 'desc' => 'Kapasitas ruang dibatasi agar setiap siswa mendapat perhatian optimal.'],
                ['title' => 'Biaya & Jadwal Transparan', 'desc' => 'Harga penuh, hari, jam belajar, dan ruangan tercantum jelas sebelum daftar.'],
                ['title' => 'Notifikasi Real-Time', 'desc' => 'Status pembayaran terverifikasi otomatis dengan konfirmasi Email & WhatsApp.'],
            ];
        @endphp
        @foreach ($features as $feature)
            <div class="rounded-xl border border-sky-200 bg-white p-6 transition-colors hover:bg-sky-50/30">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-800">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <h3 class="mt-4 text-sm font-bold text-slate-900">{{ $feature['title'] }}</h3>
                <p class="mt-1.5 text-xs text-slate-600 leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Katalog Program Unggulan --}}
@if ($featuredPrograms->isNotEmpty())
<section class="border-y border-sky-100 bg-sky-50/60 py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-sky-700">Katalog Program</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Program Belajar Tersedia</h2>
            </div>
            <a href="{{ route('programs.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-sky-800 hover:underline">
                Lihat Semua Program
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($featuredPrograms as $program)
                <div class="flex flex-col justify-between rounded-xl border border-sky-200 bg-white p-5">
                    <div>
                        <span class="inline-block rounded-md bg-sky-100 px-2.5 py-0.5 text-[11px] font-bold text-sky-800">
                            {{ $program->education_level }}
                        </span>
                        <h3 class="mt-3 text-base font-bold text-slate-900">{{ $program->name }}</h3>
                        <p class="mt-1.5 text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ $program->description }}</p>
                    </div>
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        <p class="text-[11px] text-slate-500">{{ $program->duration }}</p>
                        <p class="mt-0.5 text-lg font-extrabold tabular-nums text-slate-900">Rp {{ number_format($program->price, 0, ',', '.') }}</p>
                        <a href="{{ route('programs.show', $program) }}"
                           class="mt-3.5 flex min-h-[44px] w-full items-center justify-center rounded-lg bg-sky-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-900 transition-colors">
                            Lihat Kelas & Kuota
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimoni & CTA Penutup --}}
<section id="testimoni" class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
    <div class="text-center max-w-xl mx-auto">
        <p class="text-xs font-bold uppercase tracking-wider text-sky-700">Testimoni</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Dipercaya Orang Tua & Siswa</h2>
    </div>

    <div class="mt-8 grid gap-5 md:grid-cols-3">
        @php
            $testimonials = [
                ['initial' => 'IR', 'name' => 'Ibu Ratna', 'role' => 'Orang Tua Siswa SMP', 'quote' => 'Daftar lewat HP sangat praktis. Sisa kuota dan jadwal langsung terlihat tanpa perlu menunggu balasan chat.'],
                ['initial' => 'DP', 'name' => 'Dimas Pratama', 'role' => 'Siswa Kelas 12 (UTBK)', 'quote' => 'Kelas tatap mukanya fokus karena jumlah peserta dibatasi. Tutor sangat membantu saat diskusi soal.'],
                ['initial' => 'BH', 'name' => 'Bapak Hendra', 'role' => 'Orang Tua Siswa SD', 'quote' => 'Biaya dan jadwal transparan dari awal. Setelah bayar, konfirmasi langsung masuk ke WhatsApp dan Email.'],
            ];
        @endphp
        @foreach ($testimonials as $item)
            <figure class="rounded-xl border border-slate-200 bg-white p-6">
                <blockquote class="text-xs text-slate-600 leading-relaxed">"{{ $item['quote'] }}"</blockquote>
                <figcaption class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-4">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-xs font-bold text-sky-800">
                        {{ $item['initial'] }}
                    </span>
                    <div>
                        <p class="text-xs font-bold text-slate-900">{{ $item['name'] }}</p>
                        <p class="text-[11px] text-slate-500">{{ $item['role'] }}</p>
                    </div>
                </figcaption>
            </figure>
        @endforeach
    </div>

    <div class="mt-14 rounded-xl border border-sky-200 bg-sky-50 px-6 py-10 text-center sm:px-12">
        <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Siap Memulai Periode Belajar Baru?</h2>
        <p class="mt-2 text-sm text-slate-600 max-w-md mx-auto">Pilih kelas yang sesuai jadwal siswa dan amankan kursi sebelum kuota penuh.</p>
        <a href="{{ route('programs.index') }}"
           class="mt-6 inline-flex min-h-[46px] items-center justify-center rounded-lg bg-sky-900 px-7 py-3 text-sm font-semibold text-white hover:bg-slate-900 transition-colors">
            Lihat Program Sekarang
        </a>
    </div>
</section>
@endsection
