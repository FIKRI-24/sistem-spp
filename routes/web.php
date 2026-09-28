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

use App\Http\Controllers\Admin\JenisPembayaranController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\PengaturanSekolahController;
use App\Http\Controllers\Admin\PotonganSiswaController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\TarifPembayaranController;

// Routes hanya untuk Super Admin
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 4+
    })->name('users.index');

    Route::get('/settings', [PengaturanSekolahController::class, 'index'])->name('settings.index');
    Route::put('/settings', [PengaturanSekolahController::class, 'update'])->name('settings.update');

    Route::get('/audit-log', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 9
    })->name('audit-log.index');
});

// Routes untuk Super Admin & Admin (role gabungan)
Route::middleware(['auth', 'role:super_admin|admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');

    // 1. Tahun Ajaran
    Route::post('/tahun-ajaran/{tahun_ajaran}/toggle-active', [TahunAjaranController::class, 'toggleActive'])->name('tahun-ajaran.toggle-active');
    Route::post('/tahun-ajaran/{tahun_ajaran}/toggle-lock', [TahunAjaranController::class, 'toggleLock'])->name('tahun-ajaran.toggle-lock');
    Route::resource('/tahun-ajaran', TahunAjaranController::class)->except(['create', 'show', 'edit']);

    // 2. Jurusan
    Route::resource('/jurusan', JurusanController::class)->except(['create', 'show', 'edit']);

    // 3. Kelas
    Route::resource('/kelas', KelasController::class)->except(['create', 'show', 'edit']);

    // 4. Siswa & Riwayat Kelas
    Route::get('/siswa/{siswa}/riwayat-kelas', [SiswaController::class, 'riwayatKelas'])->name('siswa.riwayat-kelas');
    Route::resource('/siswa', SiswaController::class);

    // 5. Jenis Pembayaran
    Route::post('/jenis-pembayaran/{jenis_pembayaran}/toggle-active', [JenisPembayaranController::class, 'toggleActive'])->name('jenis-pembayaran.toggle-active');
    Route::resource('/jenis-pembayaran', JenisPembayaranController::class)->except(['create', 'show', 'edit']);

    // 6. Tarif Pembayaran
    Route::resource('/tarif-pembayaran', TarifPembayaranController::class)->except(['create', 'show', 'edit']);

    // 7. Potongan / Diskon Siswa
    Route::post('/potongan-siswa/{potongan_siswa}/toggle-active', [PotonganSiswaController::class, 'toggleActive'])->name('potongan-siswa.toggle-active');
    Route::resource('/potongan-siswa', PotonganSiswaController::class)->except(['create', 'show', 'edit']);

    // Placeholder untuk fase berikutnya
    Route::get('/tagihan', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 5
    })->name('tagihan.index');

    Route::get('/pembayaran', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 6
    })->name('pembayaran.index');

    Route::get('/laporan', function () {
        return view('dashboard'); // Placeholder — akan diimplementasikan di Phase 7
    })->name('laporan.index');
});

require __DIR__.'/auth.php';
