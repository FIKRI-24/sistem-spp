<x-admin-layout title="Data Siswa">
    <div x-data="{ 
        deleteModalOpen: false,
        selectedItem: null,
        openDelete(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }">
        <!-- Page Header & Action -->
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                        <i class="fa-solid fa-user-graduate text-lg"></i>
                    </span>
                    Data Siswa
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Kelola data induk siswa, penempatan rombel kelas, dan audit trail riwayat kelas.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('admin.master-data.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Kembali
                </a>
                <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    Tambah Siswa Baru
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Total Siswa Terdaftar</span>
                    <span class="block text-xl font-bold text-slate-900 dark:text-white">{{ number_format($totalSiswa) }} Siswa</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-user-check text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Siswa Aktif</span>
                    <span class="block text-xl font-bold text-slate-900 dark:text-white">{{ number_format($totalAktif) }} Siswa</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Siswa Telah Lulus</span>
                    <span class="block text-xl font-bold text-slate-900 dark:text-white">{{ number_format($totalLulus) }} Alumni</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800">
                <form method="GET" action="{{ route('admin.siswa.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <!-- Search Input -->
                    <div class="relative sm:col-span-2">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIS, atau NISN siswa..." 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 pl-10 pr-10 py-2.5 text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        @if($search)
                            <a href="{{ route('admin.siswa.index', ['kelas_id' => $kelasId, 'status' => $status]) }}" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Filter Kelas -->
                    <div>
                        <select name="kelas_id" onchange="this.form.submit()" 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 px-3.5 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Semua Rombel Kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }} ({{ $k->jurusan->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <select name="status" onchange="this.form.submit()" 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 px-3.5 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="graduated" {{ $status == 'graduated' ? 'selected' : '' }}>Lulus</option>
                            <option value="transferred" {{ $status == 'transferred' ? 'selected' : '' }}>Pindah</option>
                            <option value="dropped_out" {{ $status == 'dropped_out' ? 'selected' : '' }}>Drop Out</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-slate-800">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3">Identitas Siswa</th>
                            <th scope="col" class="px-3 py-3.5">NIS & NISN</th>
                            <th scope="col" class="px-3 py-3.5">Kelas & Jurusan</th>
                            <th scope="col" class="px-3 py-3.5 text-center">Status</th>
                            <th scope="col" class="px-3 py-3.5">Kontak Wali</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800">
                        @forelse($siswaList as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-4 pl-6 pr-3 font-medium text-slate-900 dark:text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 font-bold text-xs text-indigo-600 dark:text-indigo-400">
                                            {{ strtoupper(substr($item->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.siswa.show', $item) }}" class="font-semibold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                {{ $item->name }}
                                            </a>
                                            <span class="block text-xs text-slate-400">
                                                {{ $item->gender == 'L' ? 'Laki-laki' : 'Perempuan' }} &bull; Masuk: {{ $item->entry_date ? \Carbon\Carbon::parse($item->entry_date)->format('d/m/Y') : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-xs">
                                    <span class="block font-mono font-semibold text-slate-800 dark:text-slate-200">NIS: {{ $item->nis }}</span>
                                    <span class="block text-slate-400 font-mono">NISN: {{ $item->nisn ?: '-' }}</span>
                                </td>
                                <td class="px-3 py-4 text-xs">
                                    @if($item->kelas)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 font-semibold text-indigo-700 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60">
                                            <i class="fa-solid fa-chalkboard text-[10px]"></i>
                                            {{ $item->kelas->name }}
                                        </span>
                                        <span class="block text-[11px] text-slate-400 mt-0.5">
                                            {{ $item->kelas->jurusan->name ?? '' }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-center">
                                    @if($item->status == 'active')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @elseif($item->status == 'graduated')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 dark:bg-indigo-950/60 px-2.5 py-1 text-xs font-semibold text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800/60">
                                            Lulus
                                        </span>
                                    @elseif($item->status == 'transferred')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 dark:bg-amber-950/60 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60">
                                            Pindah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 dark:bg-rose-950/60 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60">
                                            Drop Out
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-xs text-slate-500 dark:text-slate-400">
                                    <span class="block text-slate-800 dark:text-slate-200">{{ $item->parent_name ?: '-' }}</span>
                                    <span class="block text-slate-400 font-mono">{{ $item->parent_phone ?: '-' }}</span>
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.siswa.show', $item) }}" 
                                           class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 dark:hover:bg-slate-800 dark:hover:text-indigo-400 transition" 
                                           title="Lihat Detail & Riwayat Kelas">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.siswa.edit', $item) }}" 
                                           class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 dark:hover:bg-slate-800 dark:hover:text-indigo-400 transition" 
                                           title="Edit Siswa">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>
                                        <button type="button" @click="openDelete({{ json_encode($item) }})" 
                                                class="rounded-lg p-2 text-slate-500 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition" 
                                                title="Arsipkan / Hapus Siswa">
                                            <i class="fa-solid fa-box-archive text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-user-graduate text-4xl mb-3 text-slate-300 dark:text-slate-700 block"></i>
                                    <p class="font-medium">Tidak ada data siswa yang cocok dengan kriteria.</p>
                                    <p class="text-xs mt-1">Coba sesuaikan pencarian atau klik tombol "Tambah Siswa Baru".</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($siswaList->hasPages())
                <div class="p-4 border-t border-slate-200/80 dark:border-slate-800">
                    {{ $siswaList->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Modal (Soft Delete) -->
        <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="deleteModalOpen = false"></div>

                <div class="relative w-full max-w-sm rounded-2xl bg-white dark:bg-slate-900 p-6 shadow-2xl ring-1 ring-slate-900/10 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-950/50 text-amber-600 mb-4">
                        <i class="fa-solid fa-box-archive text-xl"></i>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                        Arsipkan Data Siswa?
                    </h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Data siswa <strong class="text-slate-800 dark:text-slate-200" x-text="selectedItem?.name"></strong> akan diarsipkan (soft delete). Seluruh riwayat transaksi pembayaran terdahulu tetap terjaga aman.
                    </p>

                    <form :action="'{{ url('admin/siswa') }}/' + selectedItem?.id" method="POST" class="mt-6 flex justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="deleteModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                            Ya, Arsipkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
