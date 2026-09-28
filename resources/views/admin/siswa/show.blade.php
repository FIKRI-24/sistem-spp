<x-admin-layout title="Detail Siswa: {{ $siswa->name }}">
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Top Action Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali ke Daftar Siswa
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.siswa.edit', $siswa) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                    Edit Siswa
                </a>
            </div>
        </div>

        <!-- Student Profile Hero Card -->
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-6">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 font-bold text-2xl text-indigo-600 dark:text-indigo-400 ring-4 ring-indigo-50/50 dark:ring-indigo-950/30">
                        {{ strtoupper(substr($siswa->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">
                                {{ $siswa->name }}
                            </h2>
                            @if($siswa->status == 'aktif')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 px-3 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                            @endif
                        </div>
                        <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400 font-mono">
                            <span>NIS: <strong class="text-slate-800 dark:text-slate-200">{{ $siswa->nis }}</strong></span>
                            <span>&bull;</span>
                            <span>NISN: <strong class="text-slate-800 dark:text-slate-200">{{ $siswa->nisn ?: '-' }}</strong></span>
                            <span>&bull;</span>
                            <span class="font-sans">Jenis Kelamin: <strong class="text-slate-800 dark:text-slate-200">{{ $siswa->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 p-4 border border-slate-100 dark:border-slate-800 text-right">
                    <span class="block text-xs uppercase tracking-wider text-slate-400">Rombel Kelas Saat Ini</span>
                    <span class="block text-base font-bold text-indigo-600 dark:text-indigo-400 mt-0.5">
                        {{ $siswa->kelas->name ?? 'Belum Ditentukan' }}
                    </span>
                    <span class="block text-xs text-slate-500 dark:text-slate-400">
                        {{ $siswa->kelas->jurusan->name ?? '' }} ({{ $siswa->kelas->tahunAjaran->name ?? '' }})
                    </span>
                </div>
            </div>

            <!-- Detail Grid Info -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6">
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Tanggal Masuk</span>
                    <span class="mt-1 block text-sm font-medium text-slate-900 dark:text-white">
                        {{ $siswa->entry_date ? \Carbon\Carbon::parse($siswa->entry_date)->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Orang Tua / Wali</span>
                    <span class="mt-1 block text-sm font-medium text-slate-900 dark:text-white">
                        {{ $siswa->parent_name ?: '-' }}
                    </span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Kontak WhatsApp / HP</span>
                    <span class="mt-1 block text-sm font-medium text-slate-900 dark:text-white font-mono">
                        {{ $siswa->parent_phone ?: '-' }}
                    </span>
                </div>
                <div class="sm:col-span-3">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Alamat Tempat Tinggal</span>
                    <span class="mt-1 block text-sm text-slate-700 dark:text-slate-300">
                        {{ $siswa->address ?: 'Belum ada data alamat domisili.' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Section: Riwayat Kelas Siswa (Audit Trail PRD Bab 10.3) -->
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i>
                        Audit Trail Riwayat Kelas & Mutasi Rombel
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Rekam jejak perpindahan rombel kelas siswa dari waktu ke waktu (PRD Bab 10.3).
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300">
                    <i class="fa-solid fa-timeline text-indigo-500"></i>
                    {{ $siswa->riwayatKelasSiswa->count() }} Periode Tercatat
                </span>
            </div>

            <div class="relative pl-6 space-y-6 before:absolute before:bottom-0 before:top-2 before:left-2.5 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800">
                @forelse($siswa->riwayatKelasSiswa->sortByDesc('start_date') as $riwayat)
                    <div class="relative flex items-start gap-4">
                        <span class="absolute -left-6 top-1 flex h-5 w-5 items-center justify-center rounded-full bg-white dark:bg-slate-900 ring-2 {{ is_null($riwayat->end_date) ? 'ring-emerald-500 text-emerald-500' : 'ring-slate-300 dark:ring-slate-700 text-slate-400' }}">
                            <span class="h-2 w-2 rounded-full {{ is_null($riwayat->end_date) ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                        </span>

                        <div class="flex-1 rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4 border border-slate-200/60 dark:border-slate-800">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">
                                        {{ $riwayat->kelas->name ?? 'Kelas tidak diketahui' }}
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">
                                        &bull; {{ $riwayat->kelas->jurusan->name ?? '' }}
                                    </span>
                                    @if(is_null($riwayat->end_date))
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            Rombel Berjalan
                                        </span>
                                    @endif
                                </div>
                                <span class="text-xs font-mono text-slate-400">
                                    Tahun Ajaran: {{ $riwayat->tahunAjaran->name ?? '-' }}
                                </span>
                            </div>

                            <div class="mt-2 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-4">
                                <span>Mulai: <strong class="text-slate-700 dark:text-slate-300">{{ $riwayat->start_date ? \Carbon\Carbon::parse($riwayat->start_date)->translatedFormat('d M Y') : '-' }}</strong></span>
                                <span>&bull;</span>
                                <span>Selesai: <strong class="text-slate-700 dark:text-slate-300">{{ $riwayat->end_date ? \Carbon\Carbon::parse($riwayat->end_date)->translatedFormat('d M Y') : 'Sekarang' }}</strong></span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-xs">
                        Belum ada rekaman riwayat kelas.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
