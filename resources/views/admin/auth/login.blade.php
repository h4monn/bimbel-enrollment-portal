<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Bimbel Prestasi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-sky-50/40 font-sans text-slate-900 antialiased flex items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
        <div class="space-y-2">
            <img src="{{ asset('images/logo-kumon.png') }}" alt="Logo Kumon" class="h-6 w-auto object-contain" style="height: 24px; width: auto;">
            <div>
                <h1 class="text-lg font-bold text-slate-900">Portal Admin</h1>
                <p class="text-xs text-slate-500">Khusus akun terdaftar oleh Super Admin</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-3 text-xs font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        @if ($authMethod === 'google')
            <div class="mt-6">
                <a href="{{ route('admin.auth.google') }}"
                   class="flex min-h-[44px] w-full items-center justify-center gap-2.5 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-sky-50/60 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17Z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.11-6.72-4.96H1.29v3.14C3.26 21.3 7.31 24 12 24Z"/>
                        <path fill="#FBBC05" d="M5.28 14.24c-.24-.72-.38-1.49-.38-2.24s.14-1.52.38-2.24V6.62H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.38l3.99-3.14Z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.99 3.14c.95-2.85 3.6-4.96 6.72-4.96Z"/>
                    </svg>
                    Masuk Admin dengan Google
                </a>
            </div>
        @else
            <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700">Email Admin</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                </div>
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                </div>
                <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span>Ingat sesi ini</span>
                </label>
                <button type="submit"
                        class="min-h-[44px] w-full cursor-pointer rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 transition-colors">
                    Masuk ke Dashboard
                </button>
            </form>
        @endif

        <div class="mt-6 border-t border-slate-100 pt-4 text-center">
            <a href="{{ route('home') }}" class="text-xs font-medium text-slate-500 hover:text-sky-700">
                Kembali ke Halaman Publik
            </a>
        </div>
    </div>
</body>
</html>
