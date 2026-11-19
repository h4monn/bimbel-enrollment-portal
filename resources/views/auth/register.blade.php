@extends('layouts.public')

@section('title', 'Daftar Akun Pendaftar')

@section('content')
<div class="mx-auto max-w-md px-4 py-12 sm:py-16">
    <div class="rounded-xl border border-sky-200 bg-white p-6 sm:p-8">
        {{-- Tab Switcher Masuk / Daftar --}}
        <div class="grid grid-cols-2 rounded-lg bg-sky-50 p-1 text-center text-xs font-bold">
            <a href="{{ route('login') }}" class="rounded-md py-2.5 text-slate-600 hover:text-sky-900 transition-colors">Masuk Akun</a>
            <span class="rounded-md bg-white py-2.5 text-sky-900 shadow-2xs">Daftar Baru</span>
        </div>

        <h1 class="mt-6 text-xl font-bold text-slate-900">Buat Akun Pendaftar</h1>
        <p class="mt-1 text-xs text-slate-600">Akun langsung aktif tanpa verifikasi email sebelum mendaftar kelas.</p>

        <a href="{{ route('auth.google') }}"
           class="mt-5 flex min-h-[44px] w-full items-center justify-center gap-2.5 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-800 hover:bg-sky-50 transition-colors">
            <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17Z"/>
                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.11-6.72-4.96H1.29v3.14C3.26 21.3 7.31 24 12 24Z"/>
                <path fill="#FBBC05" d="M5.28 14.24c-.24-.72-.38-1.49-.38-2.24s.14-1.52.38-2.24V6.62H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.38l3.99-3.14Z"/>
                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.99 3.14c.95-2.85 3.6-4.96 6.72-4.96Z"/>
            </svg>
            Lanjutkan dengan Google
        </a>

        <div class="my-5 flex items-center gap-3 text-xs text-slate-400">
            <div class="h-px flex-1 bg-slate-200"></div>
            <span>atau daftar dengan email</span>
            <div class="h-px flex-1 bg-slate-200"></div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700">Nama Lengkap (Wali / Siswa)</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                       class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                @error('name') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                       class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                @error('email') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="whatsapp" class="block text-xs font-bold text-slate-700">Nomor WhatsApp Aktif</label>
                <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" placeholder="081234567890" required
                       class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                @error('whatsapp') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                @error('password') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
            </div>

            <button type="submit"
                    class="min-h-[44px] w-full cursor-pointer rounded-lg bg-sky-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-900 transition-colors">
                Buat Akun & Lanjutkan
            </button>
        </form>
    </div>
</div>
@endsection
