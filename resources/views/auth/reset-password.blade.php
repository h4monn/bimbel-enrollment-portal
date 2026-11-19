@extends('layouts.public')

@section('title', 'Buat Password Baru')

@section('content')
<div class="mx-auto max-w-sm px-4 py-12 sm:py-16">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
        <h1 class="text-xl font-bold text-slate-900">Buat Password Baru</h1>

        <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700">Password Baru</label>
                <input id="password" name="password" type="password" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">Konfirmasi Password Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-1.5 block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20">
            </div>

            <button type="submit"
                    class="min-h-[44px] w-full cursor-pointer rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 transition-colors">
                Simpan Password
            </button>
        </form>
    </div>
</div>
@endsection
