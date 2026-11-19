<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AdminGoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        abort_unless(config('auth.admin_auth_method', 'password') === 'google', 403, 'Login Google admin tidak diaktifkan.');

        return Socialite::driver('google_admin')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        abort_unless(config('auth.admin_auth_method', 'password') === 'google', 403, 'Login Google admin tidak diaktifkan.');

        try {
            $googleUser = Socialite::driver('google_admin')->user();
        } catch (Throwable $e) {
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Gagal autentikasi Google Admin. Silakan coba lagi.']);
        }

        // Hanya akun admin yang sudah didaftarkan oleh Super Admin yang boleh masuk
        $admin = Admin::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $admin) {
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Akun Google Anda tidak terdaftar sebagai Admin. Hubungi Super Admin.']);
        }

        if (! $admin->is_active) {
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Akun admin Anda telah dinonaktifkan.']);
        }

        if (! $admin->google_id) {
            $admin->update(['google_id' => $googleUser->getId()]);
        }

        Auth::guard('admin')->login($admin, true);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }
}
