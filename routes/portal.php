<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Portal Siswa & Orang Tua Routes
|--------------------------------------------------------------------------
|
| Rute untuk portal mandiri siswa dan orang tua (read-only) sesuai PRD Bab 7.3.
| Fitur lengkap diimplementasikan pada Phase 8.
|
*/

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('index');
});
