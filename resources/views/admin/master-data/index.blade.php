<x-admin-layout title="Master Data">
    <div class="space-y-6">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                        <i class="fa-solid fa-database text-lg"></i>
                    </span>
                    Pusat Master Data
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Kelola seluruh data acuan dasar sekolah, rombel kesiswaan, dan struktur tarif pembayaran SPP.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 px-3.5 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    TA Aktif: {{ $stats['tahun_ajaran']['active'] }}
                </span>
            </div>
        </div>

        <!-- Master Data Modules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- 1. Tahun Ajaran -->
            <a href="{{ route('admin.tahun-ajaran.index') }}" class="group relative rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                            <i class="fa-solid fa-calendar-days text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {{ $stats['tahun_ajaran']['total'] }} Periode
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                        Tahun Ajaran
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Atur tahun ajaran aktif dan fitur pembekuan/kunci buku tahunan (PRD Rule #10 & #11).
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Kelola Periode</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <!-- 2. Jurusan -->
            <a href="{{ route('admin.jurusan.index') }}" class="group relative rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {{ $stats['jurusan']['total'] }} Jurusan
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                        Jurusan / Program
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Kompetensi keahlian dan peminatan siswa (IPA, IPS, RPL, TKJ, dll).
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Kelola Jurusan</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <!-- 3. Kelas -->
            <a href="{{ route('admin.kelas.index') }}" class="group relative rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                            <i class="fa-solid fa-chalkboard-user text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {{ $stats['kelas']['total'] }} Rombel
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                        Rombel Kelas
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Manajemen kelas unik per tahun ajaran dan jurusan rombongan belajar.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Kelola Kelas</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <!-- 4. Data Siswa -->
            <a href="{{ route('admin.siswa.index') }}" class="group relative rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition">
                            <i class="fa-solid fa-user-graduate text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400">
                            {{ $stats['siswa']['aktif'] }} Aktif
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                        Data Siswa
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Biodata induk siswa, pencatatan mutasi kelas, dan audit trail riwayat kelas (PRD Bab 10.3).
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Kelola Siswa</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <!-- 5. Jenis Pembayaran -->
            <a href="{{ route('admin.jenis-pembayaran.index') }}" class="group relative rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition">
                            <i class="fa-solid fa-tags text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {{ $stats['jenis_pembayaran']['total'] }} Pos
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                        Jenis Pembayaran
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Pos tagihan berkala/rutin (SPP) vs biaya sekali bayar (gedung, formulir, seragam).
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Kelola Pos Biaya</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <!-- 6. Tarif Pembayaran -->
            <a href="{{ route('admin.tarif-pembayaran.index') }}" class="group relative rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 group-hover:scale-110 transition">
                            <i class="fa-solid fa-money-bill-wave text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {{ $stats['tarif_pembayaran']['total'] }} Tarif
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                        Tarif Pembayaran
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Besaran nominal biaya per kelas/jurusan dengan jaminan nominal snapshot (PRD Rule #7).
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Atur Besaran Tarif</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <!-- 7. Potongan / Diskon Siswa -->
            <a href="{{ route('admin.potongan-siswa.index') }}" class="group relative rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition">
                            <i class="fa-solid fa-percent text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400">
                            {{ $stats['potongan_siswa']['aktif'] }} Aktif
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                        Beasiswa & Diskon
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Pemotongan tagihan berkala untuk siswa berprestasi, bantuan yatim, atau keringanan khusus.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Kelola Beasiswa</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <!-- 8. Pengaturan Sekolah (Super Admin Only) -->
            <a href="{{ route('admin.settings.index') }}" class="group relative rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 group-hover:scale-110 transition">
                            <i class="fa-solid fa-school text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 border border-rose-200/60 dark:border-rose-900">
                            Super Admin
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                        Pengaturan Sekolah
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Kop cetak kuitansi, nama sekolah, nomor rekening tujuan, dan logo instansi.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Konfigurasi Sekolah</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>
        </div>
    </div>
</x-admin-layout>
