@extends('layouts.public')

@section('title', 'Masuk Akun Pendaftar')

@section('content')
<div class="mx-auto max-w-md px-4 py-12 sm:py-16">
    <div class="rounded-xl border border-sky-200 bg-white p-6 sm:p-8">
        {{-- Tab Switcher Masuk / Daftar --}}
        <div class="grid grid-cols-2 rounded-lg bg-sky-50 p-1 text-center text-xs font-bold">
            <span class="rounded-md bg-white py-2.5 text-sky-900 shadow-2xs">Masuk Akun</span>
            <a href="{{ route('register') }}" class="rounded-md py-2.5 text-slate-600 hover:text-sky-900 transition-colors">Daftar Baru</a>
        </div>

        <h1 class="mt-6 text-xl font-bold text-slate-900">Masuk ke Akun Anda</h1>
        <p class="mt-1 text-xs text-slate-600">Masuk untuk mengisi formulir pendaftaran dan melihat status pembayaran.</p>

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
            <span>atau gunakan email</span>
            <div class="h-px flex-1 bg-slate-200"></div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                       class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                @error('email') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-sky-800 hover:underline">Lupa password?</a>
                </div>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="mt-1.5 block min-h-[44px] w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                @error('password') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-sky-700 focus:ring-sky-500">
                <span>Ingat sesi masuk saya</span>
            </label>

            <button type="submit"
                    class="min-h-[44px] w-full cursor-pointer rounded-lg bg-sky-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-900 transition-colors">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection
