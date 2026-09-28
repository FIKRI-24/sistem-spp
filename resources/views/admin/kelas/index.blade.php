<x-admin-layout title="Data Kelas">
    <div x-data="{ 
        addModalOpen: false, 
        editModalOpen: false, 
        deleteModalOpen: false,
        selectedItem: null,
        editData: { id: '', name: '', jurusan_id: '', tahun_ajaran_id: '' },
        openEdit(item) {
            this.editData = {
                id: item.id,
                name: item.name,
                jurusan_id: item.jurusan_id,
                tahun_ajaran_id: item.tahun_ajaran_id
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
                        <i class="fa-solid fa-chalkboard-user text-lg"></i>
                    </span>
                    Rombongan Belajar / Kelas
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Kelola data rombel kelas per jurusan dan tahun ajaran.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('admin.master-data.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Kembali
                </a>
                <button type="button" @click="addModalOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Kelas
                </button>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800">
                <form method="GET" action="{{ route('admin.kelas.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <!-- Search Input -->
                    <div class="relative sm:col-span-2">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelas (misal: X RPL 1)..." 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 pl-10 pr-10 py-2.5 text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        @if($search)
                            <a href="{{ route('admin.kelas.index', ['tahun_ajaran_id' => $tahunAjaranId, 'jurusan_id' => $jurusanId]) }}" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Filter Tahun Ajaran -->
                    <div>
                        <select name="tahun_ajaran_id" onchange="this.form.submit()" 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 px-3.5 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach($tahunAjaranOptions as $ta)
                                <option value="{{ $ta->id }}" {{ $tahunAjaranId == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->name }} {{ $ta->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Jurusan -->
                    <div>
                        <select name="jurusan_id" onchange="this.form.submit()" 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 px-3.5 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Semua Jurusan</option>
                            @foreach($jurusanOptions as $j)
                                <option value="{{ $j->id }}" {{ $jurusanId == $j->id ? 'selected' : '' }}>
                                    {{ $j->code }} - {{ $j->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-slate-800">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3">Nama Kelas / Rombel</th>
                            <th scope="col" class="px-3 py-3.5">Jurusan</th>
                            <th scope="col" class="px-3 py-3.5">Tahun Ajaran</th>
                            <th scope="col" class="px-3 py-3.5 text-center">Jumlah Siswa</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800">
                        @forelse($kelasList as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-4 pl-6 pr-3 font-semibold text-slate-900 dark:text-white">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-solid fa-chalkboard text-indigo-500"></i>
                                        <span>{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-xs">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 font-medium text-slate-700 dark:text-slate-300">
                                        <i class="fa-solid fa-graduation-cap text-slate-400 text-[10px]"></i>
                                        {{ $item->jurusan->name }} ({{ $item->jurusan->code }})
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-xs font-medium text-slate-600 dark:text-slate-300">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fa-solid fa-calendar-days text-slate-400 text-xs"></i>
                                        {{ $item->tahunAjaran->name }}
                                        @if($item->tahunAjaran->is_active)
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" title="Tahun Ajaran Aktif"></span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $item->siswa_count > 0 ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60' : 'bg-slate-100 text-slate-400 dark:bg-slate-800' }}">
                                        <i class="fa-solid fa-user-group text-[10px]"></i>
                                        {{ $item->siswa_count }} Siswa
                                    </span>
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" @click="openEdit({{ json_encode($item) }})" 
                                                class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 dark:hover:bg-slate-800 dark:hover:text-indigo-400 transition" 
                                                title="Edit Kelas">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                        @if($item->siswa_count == 0)
                                            <button type="button" @click="openDelete({{ json_encode($item) }})" 
                                                    class="rounded-lg p-2 text-slate-500 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition" 
                                                    title="Hapus Kelas">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-chalkboard text-4xl mb-3 text-slate-300 dark:text-slate-700 block"></i>
                                    <p class="font-medium">Tidak ada data kelas yang sesuai.</p>
                                    <p class="text-xs mt-1">Coba sesuaikan filter atau klik tombol "Tambah Kelas".</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kelasList->hasPages())
                <div class="p-4 border-t border-slate-200/80 dark:border-slate-800">
                    {{ $kelasList->links() }}
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
                            Tambah Kelas Baru
                        </h3>
                        <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.kelas.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Nama Kelas / Rombel <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" placeholder="Contoh: X RPL 1, XI IPA 2" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Jurusan <span class="text-rose-500">*</span>
                            </label>
                            <select name="jurusan_id" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach($jurusanOptions as $j)
                                    <option value="{{ $j->id }}">{{ $j->code }} - {{ $j->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Tahun Ajaran <span class="text-rose-500">*</span>
                            </label>
                            <select name="tahun_ajaran_id" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($tahunAjaranOptions as $ta)
                                    <option value="{{ $ta->id }}" {{ ($tahunAjaranId ?? '') == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->name }} {{ $ta->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="addModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Simpan Kelas
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
                            Edit Data Kelas
                        </h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/kelas') }}/' + editData.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Nama Kelas / Rombel <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" x-model="editData.name" required
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Jurusan <span class="text-rose-500">*</span>
                            </label>
                            <select name="jurusan_id" x-model="editData.jurusan_id" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach($jurusanOptions as $j)
                                    <option value="{{ $j->id }}">{{ $j->code }} - {{ $j->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Tahun Ajaran <span class="text-rose-500">*</span>
                            </label>
                            <select name="tahun_ajaran_id" x-model="editData.tahun_ajaran_id" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($tahunAjaranOptions as $ta)
                                    <option value="{{ $ta->id }}">
                                        {{ $ta->name }} {{ $ta->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="editModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Perbarui Kelas
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
                        Hapus Kelas?
                    </h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Apakah Anda yakin ingin menghapus rombel kelas <strong class="text-slate-800 dark:text-slate-200" x-text="selectedItem?.name"></strong>?
                    </p>

                    <form :action="'{{ url('admin/kelas') }}/' + selectedItem?.id" method="POST" class="mt-6 flex justify-center gap-3">
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
