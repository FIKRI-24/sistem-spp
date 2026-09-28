<x-admin-layout title="Pengaturan Sekolah">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                        <i class="fa-solid fa-school text-lg"></i>
                    </span>
                    Pengaturan Identitas Sekolah & Kuitansi
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Konfigurasi profil sekolah, kop kuitansi cetak bukti bayar, rekening tujuan, dan preferensi sistem.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 dark:bg-rose-950/50 text-xs font-semibold text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                <i class="fa-solid fa-lock text-[10px]"></i>
                Khusus Super Admin
            </span>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Profil & Identitas Sekolah -->
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3 mb-5">
                    <i class="fa-solid fa-building-columns text-indigo-500"></i>
                    Profil & Kontak Sekolah
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Nama Resmi Lembaga / Sekolah <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="school_name" value="{{ old('school_name', $settings->school_name) }}" required
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-slate-400">Nama ini akan tercetak pada header kuitansi, invoice, dan seluruh laporan resmi.</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Alamat Lengkap Sekolah
                        </label>
                        <textarea name="address" rows="2"
                                  class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">{{ old('address', $settings->address) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Nomor Telepon / WhatsApp Sekolah
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}" placeholder="(021) 12345678"
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Alamat Email Resmi Sekolah
                        </label>
                        <input type="email" name="email" value="{{ old('email', $settings->email) }}" placeholder="info@sekolah.sch.id"
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Logo Sekolah (Format Gambar: PNG, JPG, SVG maks 2MB)
                        </label>
                        <div class="flex items-center gap-4 mt-1">
                            @if($settings->logo_path)
                                <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="h-14 w-14 rounded-xl object-contain border border-slate-200 dark:border-slate-700 p-1 bg-white">
                            @else
                                <div class="h-14 w-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-image text-xl"></i>
                                </div>
                            @endif
                            <input type="file" name="logo" accept="image/*"
                                   class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950/60 dark:file:text-indigo-400">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Format Kuitansi & Rekening -->
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3 mb-5">
                    <i class="fa-solid fa-receipt text-indigo-500"></i>
                    Format Kuitansi & Bukti Pembayaran
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Catatan Header Kuitansi
                        </label>
                        <input type="text" name="receipt_header_note" value="{{ old('receipt_header_note', $settings->receipt_header_note) }}" 
                               placeholder="Contoh: Bukti Pembayaran Sah Diterbitkan Sistem Keuangan Sekolah"
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Catatan Footer Kuitansi (Syarat & Ketentuan)
                        </label>
                        <textarea name="receipt_footer_note" rows="2" placeholder="Contoh: Simpan kuitansi ini sebagai bukti pembayaran yang sah. Uang yang sudah disetorkan tidak dapat ditarik kembali."
                                  class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">{{ old('receipt_footer_note', $settings->receipt_footer_note) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Informasi Rekening Bank Sekolah (Untuk Transfer / Virtual Account)
                        </label>
                        <textarea name="bank_account_info" rows="2" placeholder="Contoh: Bank BSI No. Rekening 1234567890 a.n SMK Negeri 1 Sistem SPP"
                                  class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">{{ old('bank_account_info', $settings->bank_account_info) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3: Pengaturan Transaksi & Sistem -->
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3 mb-5">
                    <i class="fa-solid fa-sliders text-indigo-500"></i>
                    Konfigurasi Transaksi & Sistem
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Format Nomor Transaksi Pembayaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="transaction_number_format" value="{{ old('transaction_number_format', $settings->transaction_number_format ?? 'TRX-{Ymd}-{###}') }}" required
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-slate-400">Contoh format: TRX-{Ymd}-{###} menghasilkan TRX-20260928-0001.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Tahun Ajaran Aktif Saat Ini
                        </label>
                        <select name="active_tahun_ajaran_id"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @foreach($tahunAjaranOptions as $ta)
                                <option value="{{ $ta->id }}" {{ $settings->active_tahun_ajaran_id == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->name }} {{ $ta->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 pt-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="auto_create_portal_account" value="1" {{ $settings->auto_create_portal_account ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Otomatis buat akun portal mandiri siswa saat siswa baru didaftarkan</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    Simpan Pengaturan Sekolah
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
