<x-admin-layout title="Tahun Ajaran">
    <div x-data="{ 
        addModalOpen: false, 
        editModalOpen: false, 
        deleteModalOpen: false,
        selectedItem: null,
        editData: { id: '', name: '', start_date: '', end_date: '', is_active: false, is_locked: false },
        openEdit(item) {
            this.editData = {
                id: item.id,
                name: item.name,
                start_date: item.start_date ? item.start_date.substring(0, 10) : '',
                end_date: item.end_date ? item.end_date.substring(0, 10) : '',
                is_active: Boolean(item.is_active),
                is_locked: Boolean(item.is_locked)
            };
            this.editModalOpen = true;
        },
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
                        <i class="fa-solid fa-calendar-days text-lg"></i>
                    </span>
                    Tahun Ajaran
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Kelola periode kalender akademik sekolah. Hanya 1 tahun ajaran yang dapat berstatus aktif.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('admin.master-data.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Kembali
                </a>
                <button type="button" @click="addModalOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Tahun Ajaran
                </button>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Tahun Ajaran Aktif</span>
                    <span class="block text-lg font-bold text-slate-900 dark:text-white">
                        {{ $activeYear ? $activeYear->name : 'Belum Ditentukan' }}
                    </span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400">
                    <i class="fa-solid fa-layer-group text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Total Periode</span>
                    <span class="block text-xl font-bold text-slate-900 dark:text-white">{{ $totalYears }} Periode</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400">
                    <i class="fa-solid fa-lock text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Periode Terkunci</span>
                    <span class="block text-xl font-bold text-slate-900 dark:text-white">{{ $lockedYears }} Terkunci</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                <form method="GET" action="{{ route('admin.tahun-ajaran.index') }}" class="w-full sm:w-80 flex items-center relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-sm"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama tahun ajaran..." 
                           class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 pl-10 pr-10 py-2 text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @if($search)
                        <a href="{{ route('admin.tahun-ajaran.index') }}" class="absolute right-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </a>
                    @endif
                </form>

                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-indigo-500"></i>
                    <span>Tahun ajaran terkunci tidak dapat diedit atau dihapus demi integritas data keuangan.</span>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-slate-800">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3">Tahun Ajaran</th>
                            <th scope="col" class="px-3 py-3.5">Periode Kalender</th>
                            <th scope="col" class="px-3 py-3.5 text-center">Status Aktif</th>
                            <th scope="col" class="px-3 py-3.5 text-center">Status Kunci</th>
                            <th scope="col" class="px-3 py-3.5 text-center">Terkait</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800">
                        @forelse($tahunAjaranList as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-4 pl-6 pr-3 font-semibold text-slate-900 dark:text-white">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-solid fa-calendar text-slate-400"></i>
                                        <span>{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-xs text-slate-500 dark:text-slate-400">
                                    @if($item->start_date && $item->end_date)
                                        {{ \Carbon\Carbon::parse($item->start_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->translatedFormat('d M Y') }}
                                    @else
                                        <span class="text-slate-400 italic">Belum diatur</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-center">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('admin.tahun-ajaran.toggle-active', $item) }}" class="inline">
                                            @csrf
                                            <button type="submit" title="Klik untuk jadikan tahun ajaran aktif" 
                                                    class="inline-flex items-center gap-1 rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-950/50 dark:hover:text-indigo-400 transition">
                                                <i class="fa-solid fa-power-off text-[10px]"></i>
                                                Nonaktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <form method="POST" action="{{ route('admin.tahun-ajaran.toggle-lock', $item) }}" class="inline">
                                        @csrf
                                        @if($item->is_locked)
                                            <button type="submit" title="Terkunci: Klik untuk membuka kunci" class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 dark:bg-rose-950/60 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 hover:opacity-80 transition">
                                                <i class="fa-solid fa-lock text-[11px]"></i>
                                                Terkunci
                                            </button>
                                        @else
                                            <button type="submit" title="Terbuka: Klik untuk mengunci tahun ajaran" class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-amber-50 hover:text-amber-700 dark:hover:bg-amber-950/40 dark:hover:text-amber-400 transition">
                                                <i class="fa-solid fa-lock-open text-[11px]"></i>
                                                Terbuka
                                            </button>
                                        @endif
                                    </form>
                                </td>
                                <td class="px-3 py-4 text-center text-xs">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300" title="{{ $item->kelas_count }} Kelas terdaftar">
                                        <i class="fa-solid fa-chalkboard text-slate-400"></i>
                                        {{ $item->kelas_count }} Kelas
                                    </span>
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" @click="openEdit({{ json_encode($item) }})" 
                                                class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 dark:hover:bg-slate-800 dark:hover:text-indigo-400 transition" 
                                                title="Edit Tahun Ajaran">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                        @if(!$item->is_locked && !$item->is_active && $item->kelas_count == 0)
                                            <button type="button" @click="openDelete({{ json_encode($item) }})" 
                                                    class="rounded-lg p-2 text-slate-500 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition" 
                                                    title="Hapus Tahun Ajaran">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-calendar-xmark text-4xl mb-3 text-slate-300 dark:text-slate-700 block"></i>
                                    <p class="font-medium">Tidak ada data tahun ajaran.</p>
                                    <p class="text-xs mt-1">Klik tombol "Tambah Tahun Ajaran" untuk membuat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tahunAjaranList->hasPages())
                <div class="p-4 border-t border-slate-200/80 dark:border-slate-800">
                    {{ $tahunAjaranList->links() }}
                </div>
            @endif
        </div>

        <!-- Add Modal -->
        <div x-show="addModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>

                <div class="relative w-full max-w-md rounded-2xl bg-white dark:bg-slate-900 p-6 shadow-2xl ring-1 ring-slate-900/10">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-plus-circle text-indigo-600"></i>
                            Tambah Tahun Ajaran
                        </h3>
                        <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.tahun-ajaran.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Format Nama Tahun Ajaran <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" placeholder="Contoh: 2025/2026" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-slate-400">Gunakan format 4 digit garis miring 4 digit (YYYY/YYYY).</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Tanggal Mulai
                                </label>
                                <input type="date" name="start_date" 
                                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Tanggal Selesai
                                </label>
                                <input type="date" name="end_date" 
                                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="pt-2 space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Jadikan Tahun Ajaran Aktif Saat Ini</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_locked" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Kunci Tahun Ajaran (Read-Only)</span>
                            </label>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="addModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Simpan Tahun Ajaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>

                <div class="relative w-full max-w-md rounded-2xl bg-white dark:bg-slate-900 p-6 shadow-2xl ring-1 ring-slate-900/10">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square text-indigo-600"></i>
                            Edit Tahun Ajaran
                        </h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/tahun-ajaran') }}/' + editData.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Format Nama Tahun Ajaran <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" x-model="editData.name" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Tanggal Mulai
                                </label>
                                <input type="date" name="start_date" x-model="editData.start_date"
                                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Tanggal Selesai
                                </label>
                                <input type="date" name="end_date" x-model="editData.end_date"
                                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="pt-2 space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" x-model="editData.is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Tahun Ajaran Aktif</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_locked" value="1" x-model="editData.is_locked" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Kunci Tahun Ajaran</span>
                            </label>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="editModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Perbarui Tahun Ajaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="deleteModalOpen = false"></div>

                <div class="relative w-full max-w-sm rounded-2xl bg-white dark:bg-slate-900 p-6 shadow-2xl ring-1 ring-slate-900/10 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 dark:bg-rose-950/50 text-rose-600 mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                        Hapus Tahun Ajaran?
                    </h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Apakah Anda yakin ingin menghapus tahun ajaran <strong class="text-slate-800 dark:text-slate-200" x-text="selectedItem?.name"></strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <form :action="'{{ url('admin/tahun-ajaran') }}/' + selectedItem?.id" method="POST" class="mt-6 flex justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="deleteModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-500">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
