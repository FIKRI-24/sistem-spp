# SIPS — Sistem Informasi Pembayaran Sekolah (SPP)

Sistem Informasi Pembayaran Sekolah (SIPS) adalah aplikasi web berbasis **Laravel 13**, **PHP 8.4**, **MySQL**, dan **Blade + Tailwind CSS** yang dirancang untuk mengelola seluruh siklus tagihan dan pembayaran SPP sekolah dengan integritas finansial tinggi, jejak audit lengkap, serta pencegahan tagihan ganda.

---

## 🛠️ Tech Stack & Ekosistem

- **Framework**: Laravel 13
- **Bahasa Pemrograman**: PHP 8.4
- **Database**: MySQL 8.x / MariaDB
- **Frontend / UI**: Blade Templates + Tailwind CSS (Breeze Scaffolding)
- **Komponen Interaktif**: Alpine.js
- **Assets Bundler**: Vite 8
- **Role & Permission**: `spatie/laravel-permission` (v8.3)
- **Laporan & Dokumen**:
  - `barryvdh/laravel-dompdf` (Cetak Bukti Pembayaran / Kuitansi PDF)
  - `maatwebsite/excel` (Export Rekapitulasi & Laporan Excel)

---

## 📋 Progres Pengembangan (Roadmap Eksekusi)

Pengembangan dilakukan secara bertahap mengikuti panduan `Development-Phases-AI-Agent.md` dan spesifikasi bisnis `PRD-Sistem-Pembayaran-SPP.md`:

| Fase | Modul / Fokus | Status | Deskripsi Ringkas |
| :---: | :--- | :---: | :--- |
| **Phase 0** | **Persiapan & Konvensi Proyek** | ✅ **Selesai** | Penyiapan struktur arsitektur folder (`Services`, `Policies`, `Requests`), penetapan konvensi penamaan, aturan integritas transaksi multi-tabel (`DB::transaction()`), konfigurasi timezone `Asia/Jakarta` & locale `id`, instalasi dependensi inti. |
| **Phase 1** | **Project Setup & Base Layouts** | ✅ **Selesai** | Inisialisasi Git, setup autentikasi Laravel Breeze (Blade stack), pembuatan base layout responsif admin (`layouts/admin.blade.php`), portal siswa (`layouts/portal.blade.php`), pemisahan rute (`routes/portal.php`), kompilasi Vite, dan pengujian unit test. |
| **Phase 2** | Database & Eloquent Models | ⏳ *Next* | 16 skema tabel PRD Bab 16, unique constraint anti-duplikat, relasi Eloquent, accessor status tagihan, dan seeder data awal. |
| **Phase 3** | Authentication & Authorization | ⏳ Pending | Role-based access control (`super_admin`, `admin`), penyesuaian login, rate limiter (lock 5x gagal login), Policies. |
| **Phase 4** | Master Data | ⏳ Pending | CRUD Tahun Ajaran, Jurusan, Kelas, Siswa, Jenis Pembayaran, Tarif Pembayaran (snapshot-based), Diskon, Pengaturan Sekolah. |
| **Phase 5** | Modul Tagihan SPP | ⏳ Pending | `BillingService` (generate massal/per kelas, filter siswa baru tengah tahun, kalkulasi diskon, void & edit tagihan). |
| **Phase 6** | Modul Transaksi & Pembayaran | ⏳ Pending | `PaymentService`, generator nomor transaksi unik (`TRX-YYYYMMDD-XXXX`) dengan row-locking anti race condition, kuitansi PDF (`ReceiptService`), void transaksi dengan approval. |
| **Phase 7** | Laporan & Dashboard | ⏳ Pending | `ReportService`, rekapitulasi tunggakan, filter laporan pembayaran, export PDF/Excel, widget metrik ringkasan & grafik Chart.js. |
| **Phase 8** | Portal Mandiri Siswa / Wali | ⏳ Pending | Multi-guard akses siswa/orang tua untuk cek status tagihan, histori pembayaran, dan download kuitansi mandiri. |
| **Phase 9** | Audit Log & Security Hardening | ⏳ Pending | Logging mutasi data kritikal, proteksi CSRF/XSS/SQLi, session timeout, rate limiting endpoint transaksi. |
| **Phase 10**| Automated Testing & QA | ⏳ Pending | Unit & feature test skenario finansial (AC-01 s/d AC-09 pada PRD Bab 22). |
| **Phase 11**| Deployment & Dokumentasi | ⏳ Pending | Persiapan server produksi, konfigurasi SSL, cron backup otomatis, dan panduan operasional. |

---

## 🔒 Konvensi & Aturan Finansial Utama

1. **Semua Operasi Finansial Multi-Tabel Wajib `DB::transaction()`**  
   Pembayaran, pembatalan/void, dan penerbitan tagihan massal tidak boleh commit sebagian jika terjadi kegagalan.
2. **Snapshot Tarif**  
   Nominal tagihan di tabel `student_bills` bersifat snapshot riil saat diterbitkan, tidak terpengaruh jika tarif pembayaran diperbarui di kemudian hari.
3. **Anti Tagihan Ganda**  
   Kombinasi `(student_id, payment_type_id, period)` diproteksi unique constraint di tingkat database dan divalidasi di level `BillingService`.
4. **Row-Locking Nomor Transaksi**  
   Penomoran format `TRX-YYYYMMDD-XXXX` di-lock dengan `SELECT ... FOR UPDATE` pada tabel `transaction_counters` untuk mencegah duplikasi akibat concurrent request.
5. **Dilarang Hard-Delete**  
   Data siswa, tagihan, dan transaksi finansial menggunakan soft delete atau status (`void`, `dropped_out`, dsb.).

---

## 🚀 Panduan Menjalankan Proyek (Local Dev)

1. **Clone repository:**
   ```bash
   git clone https://github.com/FIKRI-24/sistem-spp.git
   cd sistem-spp
   ```

2. **Install dependensi PHP & Node.js:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Pastikan konfigurasi database di `.env` sudah sesuai dengan MySQL lokal (Laragon/XAMPP):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_spp
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Build Frontend Assets:**
   ```bash
   npm run build
   # atau untuk mode development watch:
   # npm run dev
   ```

5. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di `http://127.0.0.1:8000`.

---

## 📄 Lisensi
Dikembangkan untuk kebutuhan pengelolaan administrasi dan pembayaran sekolah.
