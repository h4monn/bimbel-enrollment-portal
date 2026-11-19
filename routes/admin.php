<?php

use App\Http\Controllers\Admin\Auth\AdminGoogleController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\ClassRoomController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('admin.guest')->group(function (): void {
        Route::get('login', [AdminLoginController::class, 'create'])->name('login');
        Route::post('login', [AdminLoginController::class, 'store'])->name('login.store');

        Route::get('auth/google/redirect', [AdminGoogleController::class, 'redirect'])->name('auth.google');
        Route::get('auth/google/callback', [AdminGoogleController::class, 'callback'])->name('auth.google.callback');
    });

    Route::middleware('admin.auth')->group(function (): void {
        Route::post('logout', [AdminLoginController::class, 'destroy'])->name('logout');

        // Area operasional yang dapat diakses Super Admin & Staf Admin
        Route::middleware('admin.role:super_admin,staff_admin')->group(function (): void {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

            Route::resource('programs', ProgramController::class)->except(['show']);
            Route::resource('classes', ClassRoomController::class)->except(['show']);

            Route::get('registrations', [RegistrationController::class, 'index'])->name('registrations.index');
            Route::get('registrations/export', [RegistrationController::class, 'export'])->name('registrations.export');
            Route::patch('registrations/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('registrations.update-status');
            Route::post('registrations/{registration}/verify-payment', [RegistrationController::class, 'verifyPayment'])->name('registrations.verify-payment');
        });

        // Area khusus Super Admin (manajemen akun admin & konfigurasi sensitif)
        Route::middleware('admin.role:super_admin')->prefix('users')->name('users.')->group(function (): void {
            Route::get('/', fn () => 'Manajemen Akun Admin (Super Admin Only)')->name('index');
        });
    });
});
