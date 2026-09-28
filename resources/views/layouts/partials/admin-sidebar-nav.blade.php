<nav class="flex flex-1 flex-col overflow-y-auto px-3 py-4 space-y-6">
    <!-- Menu Utama -->
    <div>
        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Menu Utama
        </div>
        <div class="mt-2 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="group flex items-center gap-x-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-gauge-high text-base w-5 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                Dashboard
            </a>
        </div>
    </div>

    <!-- Modul Master Data -->
    <div>
        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Master Data
        </div>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.master-data.index') }}" 
               class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.master-data.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-database text-sm w-5 text-center {{ request()->routeIs('admin.master-data.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                Pusat Master Data
            </a>
            <a href="{{ route('admin.siswa.index') }}" 
               class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.siswa.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-user-graduate text-sm w-5 text-center {{ request()->routeIs('admin.siswa.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                Data Siswa
            </a>
            <a href="{{ route('admin.kelas.index') }}" 
               class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.kelas.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-chalkboard-user text-sm w-5 text-center {{ request()->routeIs('admin.kelas.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                Rombel Kelas
            </a>
            <a href="{{ route('admin.jurusan.index') }}" 
               class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.jurusan.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-graduation-cap text-sm w-5 text-center {{ request()->routeIs('admin.jurusan.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                Program Jurusan
            </a>
            <a href="{{ route('admin.tahun-ajaran.index') }}" 
               class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.tahun-ajaran.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-calendar-days text-sm w-5 text-center {{ request()->routeIs('admin.tahun-ajaran.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                Tahun Ajaran
            </a>
            <a href="{{ route('admin.jenis-pembayaran.index') }}" 
               class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.jenis-pembayaran.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-tags text-sm w-5 text-center {{ request()->routeIs('admin.jenis-pembayaran.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                Jenis Pembayaran
            </a>
            <a href="{{ route('admin.tarif-pembayaran.index') }}" 
               class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.tarif-pembayaran.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-money-bill-wave text-sm w-5 text-center {{ request()->routeIs('admin.tarif-pembayaran.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                Tarif Pembayaran
            </a>
            <a href="{{ route('admin.potongan-siswa.index') }}" 
               class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.potongan-siswa.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fa-solid fa-percent text-sm w-5 text-center {{ request()->routeIs('admin.potongan-siswa.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                Beasiswa & Potongan
            </a>
        </div>
    </div>

    <!-- Modul Transaksi & Kasir (Phase 6) -->
    <div>
        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Transaksi Kasir
        </div>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.pembayaran.index') }}" class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition">
                <i class="fa-solid fa-cash-register text-sm w-5 text-center text-emerald-500"></i>
                Entri Pembayaran
            </a>
            <a href="{{ route('admin.pembayaran.index') }}" class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition">
                <i class="fa-solid fa-clock-rotate-left text-sm w-5 text-center text-indigo-500"></i>
                Histori Transaksi
            </a>
        </div>
    </div>

    <!-- Modul Tagihan & Tunggakan (Phase 5) -->
    <div>
        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Tagihan & Piutang
        </div>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.tagihan.index') }}" class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition">
                <i class="fa-solid fa-file-invoice-dollar text-sm w-5 text-center text-amber-500"></i>
                Generate Tagihan SPP
            </a>
            <a href="{{ route('admin.tagihan.index') }}" class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition">
                <i class="fa-solid fa-circle-exclamation text-sm w-5 text-center text-rose-500"></i>
                Data Tunggakan
            </a>
        </div>
    </div>

    <!-- Modul Laporan (Phase 7) -->
    <div>
        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Laporan Keuangan
        </div>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.laporan.index') }}" class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition">
                <i class="fa-solid fa-chart-pie text-sm w-5 text-center text-slate-400 group-hover:text-indigo-600"></i>
                Laporan Pembayaran
            </a>
        </div>
    </div>

    <!-- Modul Khusus Super Admin -->
    @role('super_admin')
    <div>
        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Sistem & Otoritas
        </div>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fa-solid fa-users-gear text-sm w-5 text-center text-slate-400 group-hover:text-indigo-600"></i>
                Manajemen Petugas
            </a>
            <a href="{{ route('admin.audit-log.index') }}" class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition {{ request()->routeIs('admin.audit-log.*') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fa-solid fa-shield-halved text-sm w-5 text-center text-slate-400 group-hover:text-indigo-600"></i>
                Audit Log
            </a>
            <a href="{{ route('admin.settings.index') }}" class="group flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fa-solid fa-school text-sm w-5 text-center text-slate-400 group-hover:text-indigo-600"></i>
                Pengaturan Sekolah
            </a>
        </div>
    </div>
    @endrole
</nav>
