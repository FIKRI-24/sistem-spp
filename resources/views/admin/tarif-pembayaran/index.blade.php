<x-admin-layout title="Tarif Pembayaran">
    <div x-data="{ 
        addModalOpen: false, 
        editModalOpen: false, 
        deleteModalOpen: false,
        selectedItem: null,
        editData: { id: '', jenis_pembayaran_id: '', tahun_ajaran_id: '', jurusan_id: '', kelas_id: '', amount: '', effective_date: '' },
        openEdit(item) {
            this.editData = {
                id: item.id,
                jenis_pembayaran_id: item.jenis_pembayaran_id,
                tahun_ajaran_id: item.tahun_ajaran_id,
                jurusan_id: item.jurusan_id || '',
                kelas_id: item.kelas_id || '',
                amount: item.amount,
                effective_date: item.effective_date ? item.effective_date.substring(0, 10) : ''
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
                        <i class="fa-solid fa-money-bill-wave text-lg"></i>
                    </span>
                    Tarif & Besaran Biaya Pembayaran
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Penetapan nominal biaya per pos pembayaran untuk kelas atau jurusan pada tahun ajaran aktif.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('admin.master-data.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Kembali
                </a>
                <button type="button" @click="addModalOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tetapkan Tarif Baru
                </button>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800">
                <form method="GET" action="{{ route('admin.tarif-pembayaran.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Filter Tahun Ajaran -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Filter Tahun Ajaran</label>
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

                    <!-- Filter Jenis Pembayaran -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Filter Pos Biaya</label>
                        <select name="jenis_pembayaran_id" onchange="this.form.submit()" 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 px-3.5 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Semua Pos Biaya</option>
                            @foreach($jenisPembayaranOptions as $jp)
                                <option value="{{ $jp->id }}" {{ $jenisPembayaranId == $jp->id ? 'selected' : '' }}>
                                    {{ $jp->name }} ({{ $jp->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <div class="w-full rounded-xl bg-indigo-50/50 dark:bg-indigo-950/30 p-2.5 border border-indigo-100 dark:border-indigo-900/40 text-xs text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
                            <i class="fa-solid fa-camera text-indigo-500"></i>
                            <span>Nominal Snapshot (Rule #7): Tarif yang berlaku saat tagihan dibuat akan dibekukan permanen.</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-slate-800">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3">Pos Pembayaran</th>
                            <th scope="col" class="px-3 py-3.5">Sasaran Tarif (Rombel / Jurusan)</th>
                            <th scope="col" class="px-3 py-3.5">Tahun Ajaran</th>
                            <th scope="col" class="px-3 py-3.5 text-right font-mono">Besaran Tarif (Rp)</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800">
                        @forelse($tarifList as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-4 pl-6 pr-3 font-semibold text-slate-900 dark:text-white">
                                    <div class="flex items-center gap-2.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 font-mono text-xs text-slate-700 dark:text-slate-300">
                                            {{ $item->jenisPembayaran->code ?? '-' }}
                                        </span>
                                        <span>{{ $item->jenisPembayaran->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-xs">
                                    @if($item->kelas)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 font-semibold text-indigo-700 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60">
                                            <i class="fa-solid fa-chalkboard text-[10px]"></i>
                                            Kelas: {{ $item->kelas->name }}
                                        </span>
                                    @elseif($item->jurusan)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 font-semibold text-slate-700 dark:text-slate-300">
                                            <i class="fa-solid fa-graduation-cap text-[10px]"></i>
                                            Semua Kelas Jurusan: {{ $item->jurusan->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                            <i class="fa-solid fa-school text-[10px]"></i>
                                            Berlaku untuk Seluruh Kelas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-xs font-medium text-slate-600 dark:text-slate-300">
                                    {{ $item->tahunAjaran->name ?? '-' }}
                                </td>
                                <td class="px-3 py-4 text-right font-mono font-bold text-slate-900 dark:text-white">
                                    Rp {{ number_format($item->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" @click="openEdit({{ json_encode($item) }})" 
                                                class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 dark:hover:bg-slate-800 dark:hover:text-indigo-400 transition" 
                                                title="Edit Tarif">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                        <button type="button" @click="openDelete({{ json_encode($item) }})" 
                                                class="rounded-lg p-2 text-slate-500 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition" 
                                                title="Hapus Tarif">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-money-bill-wave text-4xl mb-3 text-slate-300 dark:text-slate-700 block"></i>
                                    <p class="font-medium">Belum ada tarif pembayaran yang ditetapkan untuk filter ini.</p>
                                    <p class="text-xs mt-1">Klik tombol "Tetapkan Tarif Baru" untuk menambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tarifList->hasPages())
                <div class="p-4 border-t border-slate-200/80 dark:border-slate-800">
                    {{ $tarifList->links() }}
                </div>
            @endif
        </div>

        <!-- Add Modal -->
        <div x-show="addModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>

                <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-slate-900 p-6 shadow-2xl ring-1 ring-slate-900/10">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-plus-circle text-indigo-600"></i>
                            Tetapkan Tarif Pembayaran Baru
                        </h3>
                        <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.tarif-pembayaran.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Pos Jenis Pembayaran <span class="text-rose-500">*</span>
                            </label>
                            <select name="jenis_pembayaran_id" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">-- Pilih Pos Pembayaran --</option>
                                @foreach($jenisPembayaranOptions as $jp)
                                    <option value="{{ $jp->id }}" {{ ($jenisPembayaranId ?? '') == $jp->id ? 'selected' : '' }}>
                                        {{ $jp->name }} ({{ $jp->code }}) - {{ ucfirst($jp->category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Tahun Ajaran <span class="text-rose-500">*</span>
                            </label>
                            <select name="tahun_ajaran_id" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @foreach($tahunAjaranOptions as $ta)
                                    <option value="{{ $ta->id }}" {{ ($tahunAjaranId ?? '') == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->name }} {{ $ta->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Spesifik Jurusan (Opsional)
                                </label>
                                <select name="jurusan_id"
                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">Semua Jurusan</option>
                                    @foreach($jurusanOptions as $j)
                                        <option value="{{ $j->id }}">{{ $j->code }} - {{ $j->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Spesifik Kelas (Opsional)
                                </label>
                                <select name="kelas_id"
                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Besaran Nominal Tarif (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-2.5 text-sm font-bold text-slate-400">Rp</span>
                                <input type="number" name="amount" min="0" step="1000" placeholder="250000" required
                                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 pl-11 pr-3.5 py-2.5 text-sm text-slate-900 dark:text-white font-mono font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="addModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Simpan Tarif
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

                <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-slate-900 p-6 shadow-2xl ring-1 ring-slate-900/10">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square text-indigo-600"></i>
                            Edit Tarif Pembayaran
                        </h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/tarif-pembayaran') }}/' + editData.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Pos Jenis Pembayaran <span class="text-rose-500">*</span>
                            </label>
                            <select name="jenis_pembayaran_id" x-model="editData.jenis_pembayaran_id" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @foreach($jenisPembayaranOptions as $jp)
                                    <option value="{{ $jp->id }}">{{ $jp->name }} ({{ $jp->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Tahun Ajaran <span class="text-rose-500">*</span>
                            </label>
                            <select name="tahun_ajaran_id" x-model="editData.tahun_ajaran_id" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @foreach($tahunAjaranOptions as $ta)
                                    <option value="{{ $ta->id }}">{{ $ta->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Spesifik Jurusan (Opsional)
                                </label>
                                <select name="jurusan_id" x-model="editData.jurusan_id"
                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">Semua Jurusan</option>
                                    @foreach($jurusanOptions as $j)
                                        <option value="{{ $j->id }}">{{ $j->code }} - {{ $j->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Spesifik Kelas (Opsional)
                                </label>
                                <select name="kelas_id" x-model="editData.kelas_id"
                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Besaran Nominal Tarif (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-2.5 text-sm font-bold text-slate-400">Rp</span>
                                <input type="number" name="amount" x-model="editData.amount" min="0" step="1000" required
                                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 pl-11 pr-3.5 py-2.5 text-sm text-slate-900 dark:text-white font-mono font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="editModalOpen = false" class="rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Perbarui Tarif
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
                        Hapus Tarif Pembayaran?
                    </h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Apakah Anda yakin ingin menghapus penetapan tarif ini?
                    </p>

                    <form :action="'{{ url('admin/tarif-pembayaran') }}/' + selectedItem?.id" method="POST" class="mt-6 flex justify-center gap-3">
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
