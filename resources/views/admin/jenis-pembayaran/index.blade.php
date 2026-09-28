<x-admin-layout title="Jenis Pembayaran">
    <div x-data="{ 
        addModalOpen: false, 
        editModalOpen: false, 
        deleteModalOpen: false,
        selectedItem: null,
        editData: { id: '', code: '', name: '', category: 'rutin', description: '', is_active: true },
        openEdit(item) {
            this.editData = {
                id: item.id,
                code: item.code,
                name: item.name,
                category: item.category,
                description: item.description || '',
                is_active: Boolean(item.is_active)
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
                        <i class="fa-solid fa-tags text-lg"></i>
                    </span>
                    Jenis / Pos Biaya Pembayaran
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Definisikan komponen biaya sekolah (SPP bulanan, uang gedung, seragam, ujian, dll).
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('admin.master-data.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Kembali
                </a>
                <button type="button" @click="addModalOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Jenis Biaya
                </button>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400">
                    <i class="fa-solid fa-layer-group text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Total Pos Biaya</span>
                    <span class="block text-xl font-bold text-slate-900 dark:text-white">{{ $totalJenis }} Jenis</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-rotate text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Biaya Rutin (Bulanan / SPP)</span>
                    <span class="block text-xl font-bold text-slate-900 dark:text-white">{{ $totalRutin }} Pos</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                    <i class="fa-solid fa-receipt text-xl"></i>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Biaya Sekali Bayar</span>
                    <span class="block text-xl font-bold text-slate-900 dark:text-white">{{ $totalSekaliBayar }} Pos</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                <form method="GET" action="{{ route('admin.jenis-pembayaran.index') }}" class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative w-full sm:w-72">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode biaya..." 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 pl-10 pr-10 py-2.5 text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        @if($search)
                            <a href="{{ route('admin.jenis-pembayaran.index', ['category' => $category]) }}" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </a>
                        @endif
                    </div>

                    <div class="w-full sm:w-52">
                        <select name="category" onchange="this.form.submit()" 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 px-3.5 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Semua Kategori</option>
                            <option value="recurring" {{ $category == 'recurring' ? 'selected' : '' }}>Rutin (Bulanan / SPP)</option>
                            <option value="one_time" {{ $category == 'one_time' ? 'selected' : '' }}>Sekali Bayar</option>
                        </select>
                    </div>
                </form>

                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-indigo-500"></i>
                    <span>Pos biaya yang telah memiliki riwayat tagihan terproteksi dari penghapusan.</span>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-slate-800">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3">Kode & Nama Biaya</th>
                            <th scope="col" class="px-3 py-3.5">Kategori Tagihan</th>
                            <th scope="col" class="px-3 py-3.5">Keterangan</th>
                            <th scope="col" class="px-3 py-3.5 text-center">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-center">Terkait</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800">
                        @forelse($jenisPembayaranList as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-4 pl-6 pr-3 font-semibold text-slate-900 dark:text-white">
                                    <div class="flex items-center gap-2.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 font-mono text-xs text-slate-700 dark:text-slate-300">
                                            {{ $item->code }}
                                        </span>
                                        <span>{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-xs">
                                    @if($item->category == 'recurring')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                            <i class="fa-solid fa-rotate text-[10px]"></i>
                                            Rutin (Bulanan)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/60 font-semibold text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                            <i class="fa-solid fa-receipt text-[10px]"></i>
                                            Sekali Bayar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $item->description ?: '-' }}
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <form method="POST" action="{{ route('admin.jenis-pembayaran.toggle-active', $item) }}" class="inline">
                                        @csrf
                                        @if($item->is_active)
                                            <button type="submit" title="Aktif: Klik untuk menonaktifkan" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60 hover:opacity-80 transition">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </button>
                                        @else
                                            <button type="submit" title="Nonaktif: Klik untuk mengaktifkan" class="inline-flex items-center gap-1 rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-500 hover:bg-emerald-50 hover:text-emerald-700 dark:hover:bg-emerald-950/40 dark:hover:text-emerald-400 transition">
                                                Nonaktif
                                            </button>
                                        @endif
                                    </form>
                                </td>
                                <td class="px-3 py-4 text-center text-xs">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300" title="{{ $item->tagihan_siswa_count }} Tagihan terkait">
                                        <i class="fa-solid fa-file-invoice-dollar text-slate-400"></i>
                                        {{ $item->tagihan_siswa_count }} Tagihan
                                    </span>
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" @click="openEdit({{ json_encode($item) }})" 
                                                class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 dark:hover:bg-slate-800 dark:hover:text-indigo-400 transition" 
                                                title="Edit Jenis Biaya">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                        @if($item->tagihan_siswa_count == 0 && $item->tarif_pembayaran_count == 0)
                                            <button type="button" @click="openDelete({{ json_encode($item) }})" 
                                                    class="rounded-lg p-2 text-slate-500 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition" 
                                                    title="Hapus Jenis Biaya">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-tags text-4xl mb-3 text-slate-300 dark:text-slate-700 block"></i>
                                    <p class="font-medium">Tidak ada data jenis pembayaran.</p>
                                    <p class="text-xs mt-1">Klik tombol "Tambah Jenis Biaya" untuk mendaftarkan pos baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jenisPembayaranList->hasPages())
                <div class="p-4 border-t border-slate-200/80 dark:border-slate-800">
                    {{ $jenisPembayaranList->links() }}
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
                            Tambah Pos Biaya Pembayaran
                        </h3>
                        <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.jenis-pembayaran.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Kode Pos Biaya <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="code" placeholder="Contoh: SPP, DSP, UJIAN, SERAGAM" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm uppercase text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Nama Jenis Pembayaran <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" placeholder="Contoh: SPP Bulanan Reguler" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Kategori Penagihan <span class="text-rose-500">*</span>
                            </label>
                            <select name="category" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="recurring">Rutin (Bulanan / SPP berkala)</option>
                                <option value="one_time">Sekali Bayar (Pangkal, Gedung, Seragam)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Deskripsi / Catatan Tambahan
                            </label>
                            <textarea name="description" rows="2" placeholder="Catatan peruntukan pos biaya ini..."
                                      class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                        </div>

                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Pos Biaya Aktif Digunakan</span>
                            </label>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="addModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Simpan Pos Biaya
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
                            Edit Pos Biaya Pembayaran
                        </h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/jenis-pembayaran') }}/' + editData.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Kode Pos Biaya <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="code" x-model="editData.code" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm uppercase text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Nama Jenis Pembayaran <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" x-model="editData.name" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Kategori Penagihan <span class="text-rose-500">*</span>
                            </label>
                            <select name="category" x-model="editData.category" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="recurring">Rutin (Bulanan / SPP berkala)</option>
                                <option value="one_time">Sekali Bayar (Pangkal, Gedung, Seragam)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Deskripsi / Catatan Tambahan
                            </label>
                            <textarea name="description" rows="2" x-model="editData.description"
                                      class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                        </div>

                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" x-model="editData.is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Pos Biaya Aktif Digunakan</span>
                            </label>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="editModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Perbarui Pos Biaya
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
                        Hapus Pos Biaya?
                    </h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Apakah Anda yakin ingin menghapus pos pembayaran <strong class="text-slate-800 dark:text-slate-200" x-text="selectedItem?.name"></strong>?
                    </p>

                    <form :action="'{{ url('admin/jenis-pembayaran') }}/' + selectedItem?.id" method="POST" class="mt-6 flex justify-center gap-3">
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
