<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
                    Dashboard Keuangan SPP
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Selamat datang kembali di Sistem Informasi Pembayaran Sekolah.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-300/40">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Aktif
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Quick Welcome Banner -->
        <div class="rounded-2xl bg-gradient-to-r from-indigo-700 via-indigo-800 to-slate-900 p-6 sm:p-8 text-white shadow-lg shadow-indigo-900/10">
            <div class="max-w-2xl">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight">
                    Sistem Informasi Pembayaran Sekolah (SIPS)
                </h2>
                <p class="mt-2 text-sm sm:text-base text-indigo-100/90 leading-relaxed">
                    Aplikasi siap untuk setup skema database dan modul master data pada fase selanjutnya. Seluruh transaksi finansial multi-tabel diproteksi dengan integritas atomik.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-indigo-200">
                    <span class="bg-white/10 px-3 py-1 rounded-lg backdrop-blur">Laravel 13</span>
                    <span class="bg-white/10 px-3 py-1 rounded-lg backdrop-blur">PHP 8.4</span>
                    <span class="bg-white/10 px-3 py-1 rounded-lg backdrop-blur">MySQL</span>
                    <span class="bg-white/10 px-3 py-1 rounded-lg backdrop-blur">Tailwind CSS</span>
                </div>
            </div>
        </div>

        <!-- Quick Summary Cards Placeholder -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Siswa Aktif</p>
                <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">-</p>
                <p class="mt-1 text-xs text-slate-400">Menunggu Phase 2 (Database & Data)</p>
            </div>
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Penerimaan Hari Ini</p>
                <p class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp 0</p>
                <p class="mt-1 text-xs text-slate-400">Menunggu Phase 6 (Transaksi)</p>
            </div>
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Penerimaan Bulan Ini</p>
                <p class="mt-2 text-2xl font-bold text-indigo-600 dark:text-indigo-400">Rp 0</p>
                <p class="mt-1 text-xs text-slate-400">Menunggu Phase 6 (Transaksi)</p>
            </div>
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Tunggakan SPP</p>
                <p class="mt-2 text-2xl font-bold text-rose-600 dark:text-rose-400">Rp 0</p>
                <p class="mt-1 text-xs text-slate-400">Menunggu Phase 5 (Tagihan)</p>
            </div>
        </div>
    </div>
</x-admin-layout>
