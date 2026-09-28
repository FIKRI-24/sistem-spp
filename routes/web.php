<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Route Groups Berdasarkan Role (PRD Bab 7)
|--------------------------------------------------------------------------
| super_admin: Akses penuh termasuk manajemen user, settings, audit log.
| admin:       Operasional harian (siswa, tagihan, pembayaran, laporan).
| Kedua role:  Akses bersama ke modul yang diizinkan masing-masing.
*/

// Routes hanya untuk Super Admin
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/users', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 4+
    })->name('admin.users.index');

    Route::get('/settings', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 4+
    })->name('admin.settings.index');

    Route::get('/audit-log', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 9
    })->name('admin.audit-log.index');
});

// Routes untuk Super Admin & Admin (role gabungan)
Route::middleware(['auth', 'role:super_admin|admin'])->prefix('admin')->group(function () {
    Route::get('/master-data', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 4
    })->name('admin.master-data.index');

    Route::get('/siswa', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 4
    })->name('admin.siswa.index');

    Route::get('/tagihan', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 5
    })->name('admin.tagihan.index');

    Route::get('/pembayaran', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 6
    })->name('admin.pembayaran.index');

    Route::get('/laporan', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 7
    })->name('admin.laporan.index');
});

require __DIR__.'/auth.php';
