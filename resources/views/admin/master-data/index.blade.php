<x-admin-layout title="Master Data">
    <div class="space-y-6">
        <!-- ========================================================= -->
        <!-- SECTION 1: EDITORIAL MASTHEAD & ASYMMETRIC STAT DOSSIER   -->
        <!-- ========================================================= -->
        <section class="space-y-6">
            <!-- Masthead Tape / Archival Header Strip -->
            <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-stone-900 dark:border-stone-100 pb-3 text-xs font-mono uppercase tracking-widest text-stone-600 dark:text-stone-300">
                <div class="flex items-center gap-2">
                    <span class="inline-block h-2.5 w-2.5 bg-stone-900 dark:bg-stone-100"></span>
                    <span>[ DOC ID: SIPS-ARC-{{ date('Y') }} // BUKU BESAR INDUK ]</span>
                </div>
                <div class="flex items-center gap-4">
                    <span>TERAKHIR DISINKRON: {{ date('d.m.Y — H:i') }} WIB</span>
                    <span class="inline-flex items-center gap-1.5 border-2 border-rose-800 bg-rose-50 text-rose-900 px-2.5 py-0.5 font-bold shadow-[2px_2px_0px_#9f1239] -rotate-1">
                        <i class="fa-solid fa-stamp text-[11px]"></i>
                        RESMI & AUDITED
                    </span>
                </div>
            </div>

            <!-- Main Editorial Magazine Headline & Dispatch Dossier -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left: Headline & Editorial Dek -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="inline-block bg-stone-900 text-stone-100 dark:bg-stone-100 dark:text-stone-900 px-2.5 py-1 font-mono text-[11px] font-bold tracking-widest uppercase">
                        // PUSAT REGULASI & ACUAN KEUANGAN
                    </div>
                    <h1 class="font-serif text-4xl sm:text-6xl lg:text-7xl font-black text-stone-950 dark:text-white leading-[0.95] tracking-tight">
                        Master<span class="italic font-light text-stone-500 dark:text-stone-400">Data</span>
                        <span class="block text-2xl sm:text-3xl lg:text-4xl font-mono font-bold tracking-normal text-stone-800 dark:text-stone-300 mt-2">
                            & Buku Induk Penagihan.
                        </span>
                    </h1>
                    <p class="text-base sm:text-lg text-stone-700 dark:text-stone-300 font-sans leading-relaxed max-w-2xl">
                        Katalog acuan pokok satuan pendidikan, pemetaan rombongan belajar kesiswaan, serta regulasi penetapan tarif SPP dengan landasan integritas <span class="font-semibold underline decoration-stone-400 underline-offset-4">snapshot nominal mutlak</span>.
                    </p>
                </div>

                <!-- Right: School Dispatch Sticky Dossier -->
                <div class="lg:col-span-5 relative">
                    <!-- Paper clip / sticker badge -->
                    <div class="absolute -top-3.5 right-6 z-10 bg-amber-300 text-stone-950 font-mono text-[10px] font-bold uppercase tracking-wider px-3 py-0.5 border-2 border-stone-900 shadow-[2px_2px_0px_#000] rotate-2">
                        <i class="fa-solid fa-paperclip mr-1"></i> DISPATCH // KANVAS ACUAN
                    </div>

                    <div class="rounded-none border-2 border-stone-900 dark:border-stone-100 bg-[#FAF6EE] dark:bg-stone-900 p-6 shadow-[6px_6px_0px_#1c1917] dark:shadow-[6px_6px_0px_#f5f5f4] space-y-4">
                        <div class="flex items-center justify-between border-b border-stone-300 dark:border-stone-700 pb-3">
                            <span class="font-mono text-xs font-bold uppercase tracking-wider text-stone-500 dark:text-stone-400">INSTANSI PENDIDIKAN</span>
                            <span class="font-mono text-xs font-bold text-stone-900 dark:text-stone-100">KODE: SMKN1</span>
                        </div>
                        <div>
                            <div class="font-serif text-xl font-bold text-stone-950 dark:text-white leading-snug">
                                {{ $stats['pengaturan_sekolah']['school_name'] }}
                            </div>
                            <div class="mt-2 text-xs font-mono text-stone-600 dark:text-stone-400 space-y-1">
                                <div class="flex justify-between">
                                    <span>Tahun Ajaran Aktif:</span>
                                    <span class="font-bold text-stone-900 dark:text-stone-100">{{ $stats['tahun_ajaran']['active'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Buku Terkunci:</span>
                                    <span class="font-bold text-stone-900 dark:text-stone-100">{{ $stats['tahun_ajaran']['locked'] }} Periode (Rule #11)</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('admin.tahun-ajaran.index') }}" 
                               class="group inline-flex items-center justify-between w-full bg-stone-900 text-stone-50 hover:bg-stone-800 dark:bg-stone-100 dark:text-stone-950 border-2 border-stone-950 dark:border-stone-200 px-4 py-3 font-mono text-xs font-bold uppercase tracking-wider shadow-[3px_3px_0px_#b91c1c] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all">
                                <span>Verifikasi Siklus Akademik</span>
                                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Asymmetrical Broken Grid Stat Dossier Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                <!-- Index Card 1: Periode Akademik -->
                <div class="group relative border-2 border-stone-900 dark:border-stone-200 bg-[#FDFCF7] dark:bg-stone-900 p-6 shadow-[5px_5px_0px_#1c1917] dark:shadow-[5px_5px_0px_#fafaf9] hover:-translate-y-1 transition-transform">
                    <div class="flex items-center justify-between mb-3 text-xs font-mono">
                        <span class="font-bold uppercase tracking-wider text-stone-500 dark:text-stone-400">[INDEX-01 // AKADEMIK]</span>
                        <span class="inline-flex items-center gap-1 font-bold text-emerald-700 dark:text-emerald-400">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            AKTIF
                        </span>
                    </div>
                    <div class="font-serif text-3xl sm:text-4xl font-black text-stone-950 dark:text-white tracking-tight">
                        {{ $stats['tahun_ajaran']['active'] }}
                    </div>
                    <p class="mt-2 text-xs font-mono uppercase tracking-wider text-stone-600 dark:text-stone-400 font-semibold">
                        Siklus Penagihan Berjalan
                    </p>
                    <div class="mt-4 pt-3 border-t-2 border-dashed border-stone-300 dark:border-stone-700 flex items-center justify-between text-xs font-mono text-stone-600 dark:text-stone-400">
                        <span>{{ $stats['tahun_ajaran']['total'] }} Total Arsip TA</span>
                        <a href="{{ route('admin.tahun-ajaran.index') }}" class="font-bold text-stone-950 dark:text-stone-100 underline underline-offset-2 hover:text-indigo-600">
                            Kelola &rarr;
                        </a>
                    </div>
                </div>

                <!-- Index Card 2: Kesiswaan & Rombel (Tactile Stacked Paper) -->
                <div class="group relative border-2 border-stone-900 dark:border-stone-200 bg-[#FAF7F0] dark:bg-stone-900 p-6 shadow-[5px_5px_0px_#1c1917] dark:shadow-[5px_5px_0px_#fafaf9] hover:-translate-y-1 transition-transform">
                    <div class="flex items-center justify-between mb-3 text-xs font-mono">
                        <span class="font-bold uppercase tracking-wider text-stone-500 dark:text-stone-400">[INDEX-02 // KESISWAAN]</span>
                        <span class="bg-stone-900 text-stone-100 dark:bg-stone-100 dark:text-stone-950 px-2 py-0.5 font-bold text-[10px]">
                            {{ $stats['jurusan']['total'] }} JURUSAN
                        </span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="font-serif text-5xl sm:text-6xl font-black text-stone-950 dark:text-white tracking-tight">
                            {{ $stats['siswa']['aktif'] }}
                        </span>
                        <span class="font-mono text-xs font-bold uppercase text-stone-500 dark:text-stone-400">
                            / {{ $stats['siswa']['total'] }} Total
                        </span>
                    </div>
                    <p class="mt-2 text-xs font-mono uppercase tracking-wider text-stone-600 dark:text-stone-400 font-semibold">
                        Siswa Aktif Ter-registrasi
                    </p>
                    <div class="mt-4 pt-3 border-t-2 border-dashed border-stone-300 dark:border-stone-700 flex items-center justify-between text-xs font-mono text-stone-600 dark:text-stone-400">
                        <span>{{ $stats['kelas']['total'] }} Rombel Aktif</span>
                        <a href="{{ route('admin.siswa.index') }}" class="font-bold text-stone-950 dark:text-stone-100 underline underline-offset-2 hover:text-indigo-600">
                            Buku Induk &rarr;
                        </a>
                    </div>
                </div>

                <!-- Index Card 3: Keuangan & Tarif (High Contrast Numbers) -->
                <div class="group relative border-2 border-stone-900 dark:border-stone-200 bg-[#F9F5EB] dark:bg-stone-900 p-6 shadow-[5px_5px_0px_#1c1917] dark:shadow-[5px_5px_0px_#fafaf9] hover:-translate-y-1 transition-transform">
                    <div class="flex items-center justify-between mb-3 text-xs font-mono">
                        <span class="font-bold uppercase tracking-wider text-stone-500 dark:text-stone-400">[INDEX-03 // TARIF & POS]</span>
                        <span class="border border-stone-900 dark:border-stone-300 px-1.5 py-0.5 text-[10px] font-bold">
                            RULE #7 OK
                        </span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="font-serif text-4xl sm:text-5xl font-black text-stone-950 dark:text-white tracking-tight">
                            {{ $stats['jenis_pembayaran']['total'] }}
                        </span>
                        <span class="font-mono text-xs font-bold uppercase text-stone-600 dark:text-stone-400">Pos Biaya</span>
                        <span class="text-stone-400 dark:text-stone-600 text-2xl font-light">/</span>
                        <span class="font-serif text-4xl sm:text-5xl font-black text-stone-950 dark:text-white tracking-tight">
                            {{ $stats['tarif_pembayaran']['total'] }}
                        </span>
                        <span class="font-mono text-xs font-bold uppercase text-stone-600 dark:text-stone-400">Tarif</span>
                    </div>
                    <p class="mt-2 text-xs font-mono uppercase tracking-wider text-stone-600 dark:text-stone-400 font-semibold">
                        {{ $stats['jenis_pembayaran']['rutin'] }} SPP Berkala • {{ $stats['jenis_pembayaran']['sekali_bayar'] }} Sekali Bayar
                    </p>
                    <div class="mt-4 pt-3 border-t-2 border-dashed border-stone-300 dark:border-stone-700 flex items-center justify-between text-xs font-mono text-stone-600 dark:text-stone-400">
                        <span>{{ $stats['potongan_siswa']['aktif'] }} Siswa Beasiswa</span>
                        <a href="{{ route('admin.tarif-pembayaran.index') }}" class="font-bold text-stone-950 dark:text-stone-100 underline underline-offset-2 hover:text-indigo-600">
                            Matriks Tarif &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ledger Footnote / Archival Tape Bar -->
            <div class="border-2 border-stone-900 dark:border-stone-200 bg-[#F2EDE2] dark:bg-stone-950 p-3.5 shadow-[4px_4px_0px_#1c1917] dark:shadow-[4px_4px_0px_#fafaf9] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs font-mono">
                <div class="flex items-center gap-2 text-stone-700 dark:text-stone-300">
                    <span class="inline-block w-2 h-2 bg-rose-700"></span>
                    <span class="font-bold">STATUS AUDIT KEUANGAN:</span>
                    <span>Tahun ajaran terkunci bersifat permanen. Seluruh penerbitan tagihan akan mengunci snapshot nominal saat tanggal transaksi.</span>
                </div>
                <div class="shrink-0 flex items-center gap-3">
                    <span class="text-stone-500 dark:text-stone-400">PRD BAB 10 & BAB 11</span>
                    <span class="bg-stone-900 text-stone-50 dark:bg-stone-100 dark:text-stone-950 font-bold px-2 py-0.5 text-[10px]">
                        PASSED 100%
                    </span>
                </div>
            </div>
        </section>

        <!-- Section Separator -->
        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t-2 border-stone-900 dark:border-stone-300"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="bg-slate-50 dark:bg-slate-900 px-4 font-mono text-xs font-bold uppercase tracking-widest text-stone-900 dark:text-stone-100">
                    // KATALOG 8 MODUL MASTER DATA
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
