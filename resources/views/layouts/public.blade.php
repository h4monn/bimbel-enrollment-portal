<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kumon') — Pendaftaran Kelas Tatap Muka</title>
    <meta name="description" content="@yield('meta_description', 'Daftar bimbingan belajar tatap muka untuk SD, SMP, SMA, dan UTBK secara online dengan kuota kelas real-time.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-white font-sans text-slate-900 antialiased flex flex-col selection:bg-sky-100 selection:text-sky-900">
    <header class="sticky top-0 z-40 border-b border-sky-100 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3.5 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center shrink-0 rounded-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600">
                <img src="{{ asset('images/logo-kumon.png') }}" alt="Logo Kumon" class="h-6 sm:h-7 w-auto object-contain" style="height: 26px; width: auto;">
            </a>

            <nav class="hidden md:flex items-center gap-7 text-sm font-medium" aria-label="Navigasi utama">
                <a href="{{ route('programs.index') }}"
                   class="transition-colors {{ request()->routeIs('programs.*') ? 'text-sky-700 font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                    Program
                </a>
                <a href="{{ route('home') }}#cara-daftar" class="text-slate-600 hover:text-slate-900 transition-colors">
                    Cara Daftar
                </a>
                <a href="{{ route('home') }}#testimoni" class="text-slate-600 hover:text-slate-900 transition-colors">
                    Testimoni
                </a>
                @auth('web')
                    <a href="{{ route('dashboard') }}"
                       class="transition-colors {{ request()->routeIs('dashboard') ? 'text-sky-700 font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        Akun Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="min-h-[40px] cursor-pointer rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-700 font-semibold hover:text-sky-800 transition-colors">Masuk</a>
                    <a href="{{ route('programs.index') }}"
                       class="inline-flex min-h-[42px] items-center whitespace-nowrap rounded-lg bg-sky-900 px-5 py-2 text-xs font-semibold text-white hover:bg-slate-900 transition-colors">
                        Daftar Sekarang
                    </a>
                @endauth
            </nav>

            <button
                type="button"
                id="mobile-menu-toggle"
                aria-expanded="false"
                aria-controls="mobile-menu"
                aria-label="Buka menu navigasi"
                class="md:hidden inline-flex h-11 w-11 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-sky-50 cursor-pointer transition-colors"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="hidden md:hidden border-t border-sky-100 bg-white px-4 py-4 space-y-1.5">
            <a href="{{ route('programs.index') }}" class="flex min-h-[44px] items-center rounded-lg px-3.5 text-sm font-medium text-slate-700 hover:bg-sky-50 hover:text-sky-900">Program Belajar</a>
            <a href="{{ route('home') }}#cara-daftar" class="flex min-h-[44px] items-center rounded-lg px-3.5 text-sm font-medium text-slate-700 hover:bg-sky-50 hover:text-sky-900">Cara Daftar</a>
            <a href="{{ route('home') }}#testimoni" class="flex min-h-[44px] items-center rounded-lg px-3.5 text-sm font-medium text-slate-700 hover:bg-sky-50 hover:text-sky-900">Testimoni</a>
            @auth('web')
                <a href="{{ route('dashboard') }}" class="flex min-h-[44px] items-center rounded-lg px-3.5 text-sm font-semibold text-sky-800 bg-sky-50/70">Akun Saya (Riwayat & Status)</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="min-h-[44px] w-full cursor-pointer rounded-lg border border-slate-200 px-3.5 py-2.5 text-left text-sm font-semibold text-rose-700 hover:bg-rose-50">
                        Keluar Akun
                    </button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2.5 pt-2">
                    <a href="{{ route('login') }}" class="flex min-h-[44px] items-center justify-center rounded-lg border border-slate-200 bg-white px-3.5 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Masuk
                    </a>
                    <a href="{{ route('programs.index') }}" class="flex min-h-[44px] items-center justify-center rounded-lg bg-sky-900 px-3.5 text-sm font-semibold text-white hover:bg-slate-900">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </header>

    <main class="flex-1">
        @if (session('status'))
            <div class="mx-auto max-w-6xl px-4 pt-5 sm:px-6">
                <div class="rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-medium text-sky-900" role="status">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-sky-100 bg-sky-50/40 mt-20">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4 text-xs text-slate-600">
                <div class="space-y-2.5">
                    <img src="{{ asset('images/logo-kumon.png') }}" alt="Logo Kumon" class="h-5 w-auto object-contain" style="height: 20px; width: auto;">
                    <p class="leading-relaxed text-slate-600">
                        Lembaga bimbingan belajar tatap muka resmi (1 cabang) untuk jenjang SD, SMP, SMA, dan persiapan UTBK.
                    </p>
                </div>

                <div>
                    <p class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Alamat & Operasional</p>
                    <p class="mt-2.5 leading-relaxed">Jl. Pendidikan Raya No. 10, Cabang Utama</p>
                    <p class="mt-1 text-slate-500">Senin - Sabtu: 08.00 - 18.00 WIB</p>
                </div>

                <div>
                    <p class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Kontak Resmi</p>
                    <p class="mt-2.5">WhatsApp Admin: <span class="font-semibold text-slate-800">0812-3456-7890</span></p>
                    <p class="mt-1">Email: <span class="font-semibold text-slate-800">info@bimbel.test</span></p>
                </div>

                <div>
                    <p class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Kebijakan & Akses</p>
                    <ul class="mt-2.5 space-y-1.5 font-medium">
                        <li><a href="{{ route('programs.index') }}" class="hover:text-sky-800">Katalog Program & Harga</a></li>
                        <li><span class="text-slate-500">Kebijakan Privasi (UU PDP)</span></li>
                        <li><span class="text-slate-500">Syarat & Kebijakan Non-Refund</span></li>
                        <li><a href="{{ route('admin.login') }}" class="text-sky-800 hover:underline">Portal Staf Admin</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 border-t border-sky-100 pt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between text-[11px] text-slate-500 gap-2">
                <p>&copy; {{ date('Y') }} Website Pendaftaran Bimbel. Seluruh kelas berlangsung tatap muka.</p>
                <p>Pembayaran lunas di muka tidak dapat di-refund kecuali kelas dibatalkan bimbel atau terjadi pembayaran ganda.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', String(!expanded));
            menu?.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
