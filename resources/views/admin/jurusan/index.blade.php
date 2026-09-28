<x-admin-layout title="Data Jurusan">
    <div x-data="{ 
        addModalOpen: false, 
        editModalOpen: false, 
        deleteModalOpen: false,
        selectedItem: null,
        editData: { id: '', code: '', name: '', description: '' },
        openEdit(item) {
            this.editData = {
                id: item.id,
                code: item.code,
                name: item.name,
                description: item.description || ''
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
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </span>
                    Program Keahlian / Jurusan
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Kelola data jurusan atau kompetensi keahlian yang tersedia di sekolah.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('admin.master-data.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Kembali
                </a>
                <button type="button" @click="addModalOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Jurusan
                </button>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                <form method="GET" action="{{ route('admin.jurusan.index') }}" class="w-full sm:w-80 flex items-center relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-sm"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode jurusan..." 
                           class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 pl-10 pr-10 py-2 text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @if($search)
                        <a href="{{ route('admin.jurusan.index') }}" class="absolute right-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </a>
                    @endif
                </form>

                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 font-medium text-slate-700 dark:text-slate-300">
                        <i class="fa-solid fa-layer-group text-indigo-500"></i>
                        Total: {{ $totalJurusan }} Jurusan
                    </span>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-slate-800">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3">Kode Jurusan</th>
                            <th scope="col" class="px-3 py-3.5">Nama Jurusan</th>
                            <th scope="col" class="px-3 py-3.5">Deskripsi / Keterangan</th>
                            <th scope="col" class="px-3 py-3.5 text-center">Kelas Terhubung</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800">
                        @forelse($jurusanList as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-4 pl-6 pr-3 font-semibold text-slate-900 dark:text-white">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60 font-mono text-xs">
                                        <i class="fa-solid fa-tag text-[10px]"></i>
                                        {{ $item->code }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 font-medium text-slate-900 dark:text-white">
                                    {{ $item->name }}
                                </td>
                                <td class="px-3 py-4 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $item->description ?: '-' }}
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->kelas_count > 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                                        <i class="fa-solid fa-chalkboard text-[10px]"></i>
                                        {{ $item->kelas_count }} Rombel
                                    </span>
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" @click="openEdit({{ json_encode($item) }})" 
                                                class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 dark:hover:bg-slate-800 dark:hover:text-indigo-400 transition" 
                                                title="Edit Jurusan">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                        @if($item->kelas_count == 0)
                                            <button type="button" @click="openDelete({{ json_encode($item) }})" 
                                                    class="rounded-lg p-2 text-slate-500 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition" 
                                                    title="Hapus Jurusan">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-graduation-cap text-4xl mb-3 text-slate-300 dark:text-slate-700 block"></i>
                                    <p class="font-medium">Tidak ada data jurusan.</p>
                                    <p class="text-xs mt-1">Klik tombol "Tambah Jurusan" untuk membuat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jurusanList->hasPages())
                <div class="p-4 border-t border-slate-200/80 dark:border-slate-800">
                    {{ $jurusanList->links() }}
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
                            Tambah Jurusan Baru
                        </h3>
                        <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.jurusan.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Kode Jurusan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="code" placeholder="Contoh: RPL, TKJ, AKL, IPA" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm uppercase text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Nama Lengkap Jurusan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" placeholder="Contoh: Rekayasa Perangkat Lunak" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Deskripsi / Catatan Tambahan
                            </label>
                            <textarea name="description" rows="3" placeholder="Keterangan singkat mengenai jurusan ini..."
                                      class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="addModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Simpan Jurusan
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
                            Edit Data Jurusan
                        </h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/jurusan') }}/' + editData.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Kode Jurusan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="code" x-model="editData.code" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm uppercase text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Nama Lengkap Jurusan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" x-model="editData.name" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Deskripsi / Catatan Tambahan
                            </label>
                            <textarea name="description" rows="3" x-model="editData.description"
                                      class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="editModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Perbarui Jurusan
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
                        Hapus Jurusan?
                    </h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Apakah Anda yakin ingin menghapus jurusan <strong class="text-slate-800 dark:text-slate-200" x-text="selectedItem?.name"></strong>?
                    </p>

                    <form :action="'{{ url('admin/jurusan') }}/' + selectedItem?.id" method="POST" class="mt-6 flex justify-center gap-3">
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
