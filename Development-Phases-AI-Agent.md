# Development Phases — Sistem Informasi Pembayaran Sekolah (SPP)
### Panduan Eksekusi untuk AI Coding Agent

**Referensi:** Dokumen ini adalah breakdown teknis dari `PRD-Sistem-Pembayaran-SPP.md`, dipecah menjadi fase-fase yang dapat dieksekusi satu per satu oleh AI coding agent (mis. Claude Code).

**Cara pakai:** Kerjakan fase secara berurutan. Jangan lompat ke fase berikutnya sebelum "Definition of Done" pada fase sebelumnya terpenuhi. Setiap fase mencantumkan konteks singkat agar agent tidak perlu membaca ulang seluruh PRD setiap kali, tapi PRD lengkap tetap jadi sumber kebenaran (source of truth) jika ada detail bisnis yang perlu diverifikasi.

**Tech stack:** Laravel 13, PHP 8.4, MySQL, Blade, Eloquent, Laragon 6.0 (local dev).

---

## Phase 0 — Persiapan & Konvensi Proyek

**Tujuan:** Menetapkan aturan main sebelum coding dimulai, agar semua fase berikutnya konsisten.

**Tasks:**
1. Tetapkan struktur folder sesuai rekomendasi PRD Bab 24: `app/Services/`, `app/Policies/`, `app/Http/Requests/`.
2. Tetapkan konvensi penamaan:
   - Tabel: snake_case, plural (`student_bills`, `payment_details`).
   - Model: singular, PascalCase (`StudentBill`, `PaymentDetail`).
   - Service class: `<Domain>Service.php` (`BillingService`, `PaymentService`).
3. Tetapkan bahwa **semua operasi finansial multi-tabel wajib dibungkus `DB::transaction()`** — tulis ini sebagai catatan/CONTRIBUTING.md di root project agar agent selalu ingat di fase-fase berikutnya.
4. Setup `.env.example` dengan variabel: DB connection, APP_NAME, APP_TIMEZONE=Asia/Jakarta, LOCALE=id.
5. Install package pendukung yang akan dipakai di fase-fase selanjutnya (boleh dilakukan bertahap sesuai kebutuhan fase, tidak wajib sekaligus):
   - `laravel/breeze` atau `laravel/fortify` (auth)
   - `barryvdh/laravel-dompdf` atau `spatie/laravel-pdf` (bukti pembayaran PDF)
   - `maatwebsite/excel` (export laporan Excel)
   - `spatie/laravel-permission` (role & permission) — **[Rekomendasi]**, alternatif dari role manual di kolom `users.role`

**Definition of Done:**
- Repo Laravel 13 terinisialisasi, bisa dijalankan di Laragon (`php artisan serve` sukses).
- Struktur folder Service/Policy/Request sudah ada (boleh kosong).
- File konvensi (CONTRIBUTING.md atau catatan serupa) tersedia sebagai referensi fase berikutnya.

---

## Phase 1 — Project Setup

**Tujuan:** Fondasi aplikasi siap sebelum masuk ke database & fitur.

**Tasks:**
1. `composer create-project laravel/laravel` versi 13, pastikan `php -v` = 8.4.
2. Konfigurasi `.env` untuk koneksi MySQL via Laragon.
3. Setup Git repository + `.gitignore` standar Laravel.
4. Install Laravel Breeze (Blade stack) untuk basis auth scaffolding — akan disesuaikan di Phase 3.
5. Setup base layout Blade responsive (gunakan Tailwind/Bootstrap — pilih salah satu, konsisten di seluruh aplikasi) dengan struktur: `layouts/admin.blade.php` (untuk Super Admin/Admin) dan `layouts/portal.blade.php` (untuk Siswa/Ortu — bisa dikerjakan nanti di Phase 8, tapi layout dasar disiapkan sekarang agar konsisten).
6. Setup middleware dasar & route file terpisah bila perlu: `routes/web.php` (admin) dan pertimbangkan `routes/portal.php` untuk Phase 8.

**Definition of Done:**
- Aplikasi bisa diakses di browser dengan halaman welcome/login kosong.
- Struktur layout Blade untuk area Admin sudah ada dan responsive di mobile.
- Git commit awal (`Initial project setup`) sudah dibuat.

---

## Phase 2 — Database

**Tujuan:** Seluruh skema database sesuai PRD Bab 16 tersedia sebagai migration, dengan seeder data awal.

**Referensi tabel (PRD Bab 16):** users, students, classes, majors, academic_years, student_class_history, payment_types, payment_rates, student_discounts, student_bills, payments, payment_details, school_settings, audit_logs, transaction_counters, portal_accounts.

**Tasks:**
1. Buat migration untuk setiap tabel di atas, dengan field, foreign key, dan index/unique constraint **persis seperti didefinisikan di PRD Bab 16**. Perhatikan khusus:
   - `students.nis` → **unique**.
   - `student_bills` → **unique constraint (student_id, payment_type_id, period)** — ini krusial untuk mencegah tagihan ganda (PRD Bab 17, Business Rule #3).
   - `payments.transaction_number` → **unique**.
   - `payment_rates` → tidak ada update, hanya insert baru (tidak perlu soft delete, cukup `effective_date`).
2. Buat Eloquent Model untuk setiap tabel dengan relationship yang sesuai (lihat diagram relationship di PRD Bab 16.1):
   - Contoh: `Student::class` → `hasMany(StudentBill::class)`, `hasMany(StudentClassHistory::class)`, `hasOne(PortalAccount::class)`.
   - `StudentBill::class` → `belongsTo(Student::class)`, `belongsTo(PaymentType::class)`, `hasMany(PaymentDetail::class)`.
   - `Payment::class` → `belongsTo(Student::class)`, `belongsTo(User::class)`, `hasMany(PaymentDetail::class)`.
3. Tambahkan **accessor/computed attribute** di model `StudentBill` untuk menghitung status otomatis berdasarkan `amount` vs `amount_paid` (lihat PRD Bab 10.7 — logika Unpaid/Partial/Paid), agar tidak ada tempat lain di kode yang menduplikasi logika ini.
4. Buat seeder:
   - `PaymentTypeSeeder` — isi jenis pembayaran default (SPP, Uang Ujian, Uang Kegiatan, dll.) sesuai contoh PRD Bab 1.
   - `AcademicYearSeeder` — 1 tahun ajaran aktif contoh.
   - `UserSeeder` — 1 akun Super Admin default untuk testing.
   - (Opsional) `StudentSeeder` + `ClassSeeder` dengan data dummy untuk testing manual.
5. Buat factory untuk `Student`, `StudentBill`, `Payment` untuk keperluan automated testing di Phase 10.

**Definition of Done:**
- `php artisan migrate:fresh --seed` berjalan tanpa error.
- Semua relationship Eloquent bisa dites lewat Tinker (`Student::first()->studentBills`).
- Unique constraint anti-duplikat tagihan **sudah diverifikasi manual** (coba insert data duplikat via Tinker, harus gagal).

---

## Phase 3 — Authentication & Authorization

**Tujuan:** Login berjalan dengan role-based access sesuai PRD Bab 7.

**Tasks:**
1. Sesuaikan scaffolding Breeze: hapus fitur registrasi publik (user hanya dibuat oleh Super Admin — lihat PRD Bab 8.3), sisakan halaman login, logout, forgot password.
2. Tambahkan kolom/mekanisme role pada `users` (`enum: super_admin, admin`) — atau gunakan `spatie/laravel-permission` jika dipilih di Phase 0.
3. Buat Middleware `CheckRole` atau gunakan Gate/Policy berbasis role untuk membatasi akses route per role (PRD Bab 7 — tabel permission per role).
4. Buat Policy dasar untuk model-model kritikal (akan dilengkapi bertahap di fase berikutnya seiring fitur dibuat):
   - `UserPolicy` — hanya Super Admin yang boleh manage user.
   - `PaymentPolicy` — siapa boleh void (lihat PRD Bab 11.2 — void butuh approval Super Admin).
5. Implementasikan lock akun setelah 5x gagal login (PRD Bab 8.2) menggunakan Laravel rate limiter (`RateLimiter::hit`).
6. Redirect setelah login harus sesuai role (Super Admin/Admin → dashboard admin; nanti Phase 8 siswa/ortu → dashboard portal).

**Definition of Done:**
- Login/logout berfungsi untuk role Super Admin dan Admin.
- Route yang seharusnya hanya bisa diakses Super Admin (mis. manajemen user) mengembalikan 403 jika diakses Admin biasa.
- Percobaan login gagal berulang kali men-trigger lock/throttle.

---

## Phase 4 — Master Data

**Tujuan:** CRUD lengkap untuk seluruh data master sesuai PRD Bab 8.4–8.9.

**Modul yang dikerjakan (urutan disarankan karena ada dependency):**
1. **Tahun Ajaran** (`academic_years`) — dikerjakan pertama karena semua modul lain bergantung pada tahun ajaran aktif. Termasuk fitur "tandai sebagai aktif" (validasi: hanya 1 aktif — PRD Business Rule #10) dan "kunci tahun ajaran" (Business Rule #11).
2. **Jurusan** (`majors`).
3. **Kelas** (`classes`) — bergantung pada Jurusan + Tahun Ajaran, unique constraint (name, major_id, academic_year_id).
4. **Siswa** (`students`) — CRUD lengkap sesuai PRD Bab 8.4, termasuk:
   - Validasi NIS unik.
   - Field `entry_date`, `status` (active/graduated/transferred/dropped_out).
   - Saat siswa dibuat/pindah kelas, catat ke `student_class_history` (PRD Bab 10.3).
5. **Jenis Pembayaran** (`payment_types`) — CRUD dengan field `category` (recurring/one_time) sesuai PRD Bab 8.8.
6. **Tarif Pembayaran** (`payment_rates`) — bergantung pada Jenis Pembayaran + Kelas/Jurusan + Tahun Ajaran. **Penting:** implementasikan sebagai insert-only (tidak ada update tarif lama), sesuai Business Rule #7 (nominal snapshot).
7. **Diskon/Beasiswa Siswa** (`student_discounts`) — CRUD, terhubung ke siswa + jenis pembayaran (nullable = semua jenis) + tahun ajaran.
8. **Pengaturan Sekolah** (`school_settings`) — form single-row config (nama sekolah, alamat, logo, kop bukti pembayaran).

**Untuk setiap modul, terapkan pola konsisten:**
- Form Request terpisah untuk validasi (`StoreStudentRequest`, `UpdateStudentRequest`, dst.).
- Policy per model jika ada pembatasan role (mis. Admin tidak bisa hapus data master yang sudah dipakai — Business Rule #14).
- Soft delete untuk `students` (jangan hard delete, lihat PRD Bab 7.2).
- View index dengan pencarian & pagination (siswa bisa ratusan/ribuan — jangan load semua sekaligus).

**Definition of Done:**
- Seluruh 8 modul master data punya CRUD berfungsi penuh dengan validasi sesuai business rule terkait.
- Test manual: coba buat 2 tahun ajaran aktif sekaligus → sistem harus menolak/otomatis menonaktifkan yang lama.
- Test manual: coba hapus jenis pembayaran yang sudah dipakai di tagihan → harus ditolak/hanya bisa dinonaktifkan.

---

## Phase 5 — Modul Tagihan

**Tujuan:** Generate tagihan SPP & non-SPP sesuai PRD Bab 10 & 11.1, dengan seluruh edge case tertangani.

**Tasks:**
1. Buat `BillingService` (lihat PRD Bab 24) berisi seluruh logika generate tagihan — **jangan taruh logika ini di Controller**.
2. **Generate Tagihan SPP** (`BillingService::generateMonthlySpp()`):
   - Input: tahun ajaran, bulan, kelas (atau semua kelas).
   - Ambil tarif SPP aktif per kelas dari `payment_rates` (yang `effective_date` terbaru ≤ tanggal generate).
   - Ambil siswa aktif di kelas tersebut, filter yang `entry_date` ≤ bulan yang digenerate (PRD Bab 10.2 — siswa masuk tengah tahun).
   - Cek `student_discounts` aktif untuk siswa tsb → hitung nominal final setelah diskon (PRD Bab 10.5).
   - **Skip siswa yang sudah punya tagihan untuk kombinasi (student_id, payment_type_id, period) yang sama** — manfaatkan unique constraint sebagai safety net, tapi tetap lakukan pengecekan eksplisit dulu agar bisa menampilkan laporan "berapa yang di-skip" ke user (PRD Bab 10.6 & User Flow D).
   - Simpan nominal sebagai snapshot final di `student_bills.amount` (bukan referensi live ke `payment_rates`).
   - Bungkus seluruh proses generate massal dalam `DB::transaction()`.
   - Return summary: jumlah berhasil, jumlah di-skip beserta alasan.
3. **Generate Tagihan Non-SPP** (`BillingService::generateOneTimeBill()`):
   - Input: jenis pembayaran, siswa/kelas/massal, tahun ajaran, due_date (opsional).
   - Logika sama seperti di atas tapi tanpa konsep `period` bulanan (PRD Bab 11.1).
4. **Void Tagihan** — Admin bisa membatalkan tagihan yang belum dibayar, dengan alasan wajib diisi, tercatat di `audit_logs`.
5. **Edit Tagihan** — hanya diizinkan jika tagihan belum ada pembayaran sama sekali (`amount_paid = 0`); jika sudah ada pembayaran sebagian, edit ditolak (harus void + buat ulang).
6. UI generate tagihan: tampilkan preview sebelum eksekusi (mis. "akan generate untuk 32 siswa di kelas XII RPL 1"), lalu tombol konfirmasi.

**Definition of Done:**
- Generate SPP massal untuk satu kelas menghasilkan tagihan yang benar untuk siswa aktif, dengan nominal sudah memperhitungkan diskon.
- Generate ulang untuk kelas & bulan yang sama tidak menghasilkan duplikat (0 tagihan baru, semua di-skip).
- Siswa dengan `entry_date` di tengah tahun ajaran tidak mendapat tagihan untuk bulan sebelum ia masuk.
- Test unit untuk `BillingService` mencakup skenario: siswa baru, siswa dengan diskon, siswa pindah kelas (lihat PRD Bab 20 — Edge Cases).

---

## Phase 6 — Modul Transaksi

**Tujuan:** Proses pembayaran end-to-end sesuai PRD Bab 11.2, 12, dan 13 — ini adalah fase paling kritikal secara bisnis.

**Tasks:**
1. Buat `TransactionNumberService` — generate nomor format `TRX-YYYYMMDD-XXXX` dengan row-locking pada tabel `transaction_counters` (`SELECT ... FOR UPDATE` di dalam `DB::transaction()`) untuk mencegah duplikat saat concurrent request (PRD Bab 12).
2. Buat `PaymentService::processPayment()`:
   - Input: student_id, array alokasi [{student_bill_id, amount}], payment_method, user_id (petugas login).
   - Validasi: total alokasi = total yang diinput petugas; setiap alokasi tidak melebihi sisa tagihan terkait (PRD Business Rule #4) kecuali sudah dialokasikan penuh ke tagihan lain.
   - Tolak jika ada kelebihan bayar yang tidak dialokasikan (PRD Bab 11.2 — AC-06).
   - Generate `transaction_number` via `TransactionNumberService`.
   - Simpan `payments` + banyak `payment_details` dalam satu `DB::transaction()`.
   - Update `amount_paid` dan `status` pada setiap `student_bills` terkait (logika status ada di accessor Model dari Phase 2, tapi field `status` fisik perlu diupdate juga agar bisa diquery/index).
   - **Implementasikan idempotency**: gunakan token sekali pakai per form submission untuk mencegah double-submit (PRD Bab 20 — Edge Case "Transaksi ganda").
3. Buat `ReceiptService::generatePdf()`:
   - Generate PDF bukti pembayaran sesuai isi PRD Bab 13 (kop sekolah, rincian, watermark VOID jika perlu).
   - Simpan/generate ulang dari data tersimpan, bukan hard-code di view sekali generate saja — agar cetak ulang kapan pun tetap akurat.
4. **Void Transaksi:**
   - Admin ajukan void dengan alasan wajib (`void_reason`).
   - Status transaksi tidak langsung berubah — buat state `pending_void` jika ingin approval workflow eksplisit **[Rekomendasi tambahan dari PRD Bab 25]**, atau minimal cek role di `PaymentPolicy::approveVoid()` sehingga hanya Super Admin yang bisa eksekusi final void.
   - Saat void disetujui: `status = void`, `voided_by` diisi, dan **kembalikan** `amount_paid`/`status` pada `student_bills` terkait ke kondisi sebelum transaksi (kurangi alokasi yang sudah tercatat).
   - Catat ke `audit_logs`.
5. UI transaksi: pencarian siswa (NIS/nama) dengan autocomplete/live search, tampilkan seluruh tagihan outstanding, checkbox multi-select tagihan, input nominal per tagihan (default = sisa tagihan), tombol proses.

**Definition of Done:**
- Semua skenario di PRD Bab 11.2 (penuh, sebagian, multi-tagihan, kelebihan bayar ditolak, void, koreksi via void+baru) berjalan sesuai spesifikasi.
- Nomor transaksi tidak pernah duplikat meski disimulasikan concurrent request (test dengan beberapa request paralel).
- Bukti pembayaran PDF bisa di-generate ulang kapan saja dari histori dan menampilkan watermark VOID jika transaksi dibatalkan.
- AC-01 s/d AC-06 di PRD Bab 22 lulus semua secara manual/automated test.

---

## Phase 7 — Laporan & Dashboard

**Tujuan:** Seluruh laporan (PRD Bab 14) dan dashboard (PRD Bab 15) tersedia dengan filter yang sesuai.

**Tasks:**
1. Buat `ReportService` berisi query agregasi untuk setiap jenis laporan (jangan taruh query kompleks di Controller):
   - Pembayaran harian/bulanan/per periode/per jenis/per kelas/per siswa.
   - Tunggakan (per kelas/jurusan/jenis pembayaran/tahun ajaran).
   - Pemasukan (breakdown per jenis pembayaran).
2. Setiap laporan punya filter sesuai tabel PRD Bab 14, hasil bisa diexport PDF (`ReceiptService`/dompdf reuse) dan Excel (`maatwebsite/excel`).
3. Dashboard (PRD Bab 15):
   - Widget angka: total siswa aktif, pembayaran hari ini/bulan ini, total pemasukan, total tunggakan, jumlah siswa menunggak.
   - Grafik pemasukan per bulan (12 bulan terakhir) dan per jenis pembayaran — gunakan library chart (mis. Chart.js via CDN, konsisten dengan stack Blade).
   - Widget transaksi terbaru (10 terakhir).
   - **Penting:** semua data harus difilter berdasarkan tahun ajaran aktif secara default, dengan opsi ganti tahun ajaran untuk melihat histori.

**Definition of Done:**
- Semua laporan di PRD Bab 14 bisa diakses, difilter, dan diexport tanpa error untuk data dummy skala besar (≥ 1000 transaksi, gunakan seeder/factory dari Phase 2 untuk stress test ringan).
- Dashboard menampilkan angka yang konsisten dengan data laporan (cross-check manual angka dashboard vs laporan pemasukan bulan yang sama).

---

## Phase 8 — Portal Siswa/Orang Tua

**Tujuan:** Portal read-only sesuai PRD Bab 7.3 dan User Flow terkait.

**Tasks:**
1. Buat guard/auth terpisah untuk `portal_accounts` (bisa multi-guard Laravel, atau reuse `users` table dengan role tambahan — pilih sesuai kompleksitas, disarankan multi-guard agar terpisah jelas dari akun Admin).
2. Generate akun portal otomatis saat siswa baru dibuat (opsional, sesuai toggle di Pengaturan Sekolah) — default username = NIS, password default = tanggal lahir, dengan flag `must_change_password = true`.
3. Halaman portal (read-only, hanya data milik siswa yang login — **wajib scope query by `student_id` dari akun yang login, jangan andalkan input dari frontend**):
   - Tagihan Saya (list + status).
   - Histori Pembayaran.
   - Total Tunggakan.
   - Download bukti pembayaran (PDF, reuse `ReceiptService`).
4. Wajib ganti password di login pertama jika `must_change_password = true`.
5. Layout terpisah dari admin (`layouts/portal.blade.php` dari Phase 1), lebih sederhana dan mobile-friendly karena kemungkinan besar diakses dari HP orang tua.

**Definition of Done:**
- Login portal berhasil dan hanya menampilkan data siswa terkait (test dengan 2 akun berbeda, pastikan tidak bisa lihat data siswa lain — AC-07 di PRD Bab 22).
- Download bukti pembayaran dari portal menghasilkan PDF identik dengan yang dicetak petugas.

---

## Phase 9 — Audit Log & Security Hardening

**Tujuan:** Menutup celah keamanan dan memastikan jejak audit lengkap sesuai PRD Bab 18.

**Tasks:**
1. Implementasikan audit logging otomatis (bisa pakai Observer pada Model atau package seperti `spatie/laravel-activitylog`) untuk aksi: create/update/void pada `students`, `student_bills`, `payments`, `payment_rates`, `users`.
2. Review seluruh Form Request memastikan validasi input lengkap (tidak ada input yang lolos tanpa validasi).
3. Pastikan seluruh view Blade menggunakan `{{ }}` (auto-escape) bukan `{!! !!}` kecuali benar-benar perlu (dan disanitasi jika perlu).
4. Pastikan tidak ada raw query tanpa parameter binding (audit seluruh penggunaan `DB::raw`/`whereRaw`).
5. Setup session security: `secure` cookie (jika HTTPS), session timeout wajar (mis. 2 jam idle), regenerate session ID setelah login.
6. Setup rate limiting pada route login dan route proses pembayaran (mencegah brute force & spam transaksi).
7. Setup backup terjadwal (`spatie/laravel-backup` atau custom scheduled command) minimal harian ke lokasi terpisah dari server utama.
8. Review permission tiap route sekali lagi — pastikan tidak ada route sensitif (laporan, void, manajemen user) yang lolos tanpa middleware role check.

**Definition of Done:**
- Audit log mencatat seluruh aksi kritikal dengan data before/after yang bisa ditelusuri Super Admin.
- Checklist keamanan PRD Bab 18 sudah diverifikasi satu per satu (buat checklist terpisah jika perlu).
- Backup terjadwal sudah aktif dan teruji (coba restore dari backup di environment lokal).

---

## Phase 10 — Testing

**Tujuan:** Validasi seluruh business logic kritikal dan acceptance criteria di PRD Bab 22.

**Tasks:**
1. Unit test untuk `BillingService`:
   - Generate SPP normal.
   - Generate SPP dengan siswa baru tengah tahun (skip bulan sebelum masuk).
   - Generate SPP dengan diskon aktif.
   - Generate SPP duplikat (harus di-skip, bukan error fatal).
2. Unit test untuk `PaymentService`:
   - Pembayaran penuh, sebagian, multi-tagihan.
   - Kelebihan bayar tanpa alokasi → harus ditolak.
   - Void transaksi → tagihan kembali ke status semula.
3. Unit test untuk `TransactionNumberService`:
   - Generate nomor secara paralel/berurutan dalam satu hari → tidak boleh ada duplikat.
4. Feature test (HTTP test) untuk route-route sensitif:
   - Admin tidak bisa akses menu Super Admin (403).
   - Siswa A tidak bisa akses data siswa B via manipulasi URL/parameter di portal.
5. Jalankan seluruh Acceptance Criteria PRD Bab 22 (AC-01 s/d AC-09) sebagai test otomatis (PHPUnit/Pest) atau checklist manual QA jika waktu terbatas — prioritaskan AC-01, AC-02, AC-03, AC-04, AC-06 karena berkaitan langsung dengan integritas keuangan.

**Definition of Done:**
- Seluruh test di atas lulus (green) sebelum deployment.
- Tidak ada regresi pada business rule anti-duplikat tagihan dan anti-kelebihan-bayar.

---

## Phase 11 — Deployment

**Tujuan:** Sistem siap dipakai di lingkungan produksi sekolah.

**Tasks:**
1. Siapkan server produksi (VPS/shared hosting sesuai kapasitas sekolah), install PHP 8.4, MySQL, konfigurasi web server (Nginx/Apache).
2. Migrasi environment: `.env` produksi, `APP_DEBUG=false`, `APP_ENV=production`.
3. Jalankan `php artisan migrate --force` + seeder minimal (jenis pembayaran default, akun Super Admin awal) — **jangan** jalankan seeder data dummy testing di produksi.
4. Setup HTTPS (SSL certificate).
5. Setup backup terjadwal produksi (cron job `php artisan backup:run` atau setara).
6. Buat dokumentasi user sederhana (manual penggunaan) untuk role Admin dan Super Admin, minimal mencakup: cara generate tagihan SPP bulanan, cara memproses pembayaran, cara membuat laporan.
7. Uji coba dengan data riil terbatas (mis. 1 kelas dulu) sebelum full rollout ke seluruh sekolah.
8. Training singkat untuk petugas keuangan sekolah sebelum go-live.

**Definition of Done:**
- Aplikasi berjalan stabil di server produksi dengan HTTPS aktif.
- Backup otomatis terverifikasi berjalan.
- Minimal 1 siklus penuh (generate tagihan → bayar → cetak bukti → lihat laporan) berhasil dilakukan oleh petugas sekolah sungguhan sebagai user acceptance test final.

---

## Catatan untuk AI Agent

- **Selalu rujuk business rule di PRD Bab 17 sebelum menulis logic baru** yang menyentuh tagihan/pembayaran — banyak edge case (PRD Bab 20) yang mudah terlewat jika hanya fokus pada "happy path".
- **Jangan pernah hard delete** data `students`, `student_bills`, atau `payments` — selalu gunakan soft delete atau status (`void`, `dropped_out`, dst.) sesuai desain di PRD.
- **Setiap kali menambah fitur yang mengubah data finansial, cek apakah perlu dibungkus `DB::transaction()`.**
- Jika suatu requirement terasa ambigu saat implementasi, rujuk ke bagian **[Asumsi]** di PRD asli — jika asumsi tersebut ternyata salah untuk konteks sekolah spesifik, tandai sebagai catatan untuk dikonfirmasi ke stakeholder, jangan menebak sendiri di tengah implementasi fitur keuangan.
