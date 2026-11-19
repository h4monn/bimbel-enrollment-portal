<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') — Kumon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-sky-50/40 font-sans text-slate-900 antialiased">
    @php
        $admin = auth('admin')->user();
    @endphp

    <div class="min-h-screen flex flex-col lg:flex-row">
        {{-- Sidebar Navigasi Admin --}}
        <aside class="w-full lg:w-64 bg-white border-b lg:border-b-0 lg:border-r border-sky-100 shrink-0">
            <div class="flex items-center justify-between px-5 py-4 border-b border-sky-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                    <img src="{{ asset('images/logo-kumon.png') }}" alt="Logo Kumon" class="h-5 w-auto object-contain" style="height: 20px; width: auto;">
                    <span class="rounded-md bg-sky-100 px-2 py-0.5 text-[10px] font-bold text-sky-900">Admin</span>
                </a>
                <button type="button" id="admin-nav-toggle" class="lg:hidden inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-700 hover:bg-sky-50 cursor-pointer" aria-label="Buka navigasi admin">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            <nav id="admin-nav" class="hidden lg:block px-3 py-4 space-y-1 text-xs font-semibold" aria-label="Navigasi panel admin">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex min-h-[42px] items-center gap-2.5 rounded-lg px-3.5 py-2.5 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-sky-50 text-sky-900 border-l-4 border-sky-600 font-bold' : 'text-slate-600 hover:bg-sky-50/50 hover:text-slate-900' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    Dashboard Ringkas
                </a>

                <a href="{{ route('admin.registrations.index') }}"
                   class="flex min-h-[42px] items-center gap-2.5 rounded-lg px-3.5 py-2.5 transition-colors {{ request()->routeIs('admin.registrations.*') ? 'bg-sky-50 text-sky-900 border-l-4 border-sky-600 font-bold' : 'text-slate-600 hover:bg-sky-50/50 hover:text-slate-900' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Data Pendaftar & Pembayaran
                </a>

                <a href="{{ route('admin.programs.index') }}"
                   class="flex min-h-[42px] items-center gap-2.5 rounded-lg px-3.5 py-2.5 transition-colors {{ request()->routeIs('admin.programs.*') ? 'bg-sky-50 text-sky-900 border-l-4 border-sky-600 font-bold' : 'text-slate-600 hover:bg-sky-50/50 hover:text-slate-900' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    Program Belajar
                </a>

                <a href="{{ route('admin.classes.index') }}"
                   class="flex min-h-[42px] items-center gap-2.5 rounded-lg px-3.5 py-2.5 transition-colors {{ request()->routeIs('admin.classes.*') ? 'bg-sky-50 text-sky-900 border-l-4 border-sky-600 font-bold' : 'text-slate-600 hover:bg-sky-50/50 hover:text-slate-900' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    Kelas, Jadwal & Kuota
                </a>
            </nav>
        </aside>

        {{-- Area Konten Utama --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-sky-100 px-4 py-3.5 sm:px-6 flex items-center justify-between gap-4">
                <h1 class="text-sm font-bold text-slate-900 truncate">@yield('page_title', 'Dashboard Admin')</h1>

                <div class="flex items-center gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-slate-900 hidden sm:inline">{{ $admin?->name }}</span>
                        <span class="rounded-md bg-sky-100 px-2.5 py-1 text-[11px] font-bold text-sky-800">
                            {{ $admin?->isSuperAdmin() ? 'Super Admin' : 'Staf Admin' }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="min-h-[38px] cursor-pointer rounded-lg border border-slate-200 px-3 py-1.5 font-semibold text-slate-700 hover:bg-slate-50">
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 space-y-5">
                @if (session('status'))
                    <div class="rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-xs font-semibold text-sky-900">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-800">
                        {{ $errors->first() }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.getElementById('admin-nav-toggle')?.addEventListener('click', function () {
            document.getElementById('admin-nav')?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
