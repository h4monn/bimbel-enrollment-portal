<?php

use App\Http\Controllers\Public\DashboardController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProgramController;
use App\Http\Controllers\Public\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/program', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/program/{program:slug}', [ProgramController::class, 'show'])->name('programs.show');

Route::middleware('auth:web')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/daftar/{classRoom}', [RegistrationController::class, 'create'])->name('registrations.create');
    Route::post('/daftar', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/pendaftaran/{registration}/checkout', [RegistrationController::class, 'checkout'])->name('registrations.checkout');
    Route::get('/pendaftaran/{registration}/sukses', [RegistrationController::class, 'success'])->name('registrations.success');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
