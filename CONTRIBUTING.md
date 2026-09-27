# Konvensi Proyek & Pedoman Kontribusi — Sistem Informasi Pembayaran Sekolah (SPP)

Dokumen ini merupakan pedoman standar arsitektur, konvensi penamaan, dan aturan finansial yang menjadi acuan pengembang dan AI coding agent dalam setiap fase pengembangan sistem SPP berbasis Laravel 13, PHP 8.4, MySQL, dan Blade.

---

## 1. Aturan Finansial & Integritas Data (KRITIKAL)

1. **DB::transaction() Wajib untuk Operasi Multi-Tabel**
   - **Semua** operasi finansial yang melibatkan lebih dari satu tabel (pembayaran, alokasi detail, pembatalan/void, generate tagihan massal) **WAJIB** dibungkus dalam `DB::transaction()`.
   - Tidak boleh ada operasi finansial multi-tabel yang commit sebagian jika terjadi kegagalan.

2. **Nominal Tagihan adalah Snapshot Final**
   - Saat tagihan diterbitkan (`student_bills`), nominal dihitung berdasarkan tarif aktif (`payment_rates`) dan potongan siswa (`student_discounts`) pada saat itu, lalu disimpan sebagai nilai riil di kolom `amount`.
   - Perubahan tarif di masa mendatang **tidak boleh** mengubah nilai nominal tagihan yang sudah terbit sebelumnya.

3. **Pencegahan Tagihan Ganda (Anti-Duplicate)**
   - Kombinasi `(student_id, payment_type_id, period)` pada tabel `student_bills` wajib memiliki unique constraint di tingkat database.
   - Di level aplikasi (`BillingService`), lakukan pengecekan eksplisit sebelum insert untuk mencatat siswa yang di-skip secara graceful.

4. **Nomor Transaksi Unik & Anti Race-Condition**
   - Format: `TRX-YYYYMMDD-XXXX` (contoh: `TRX-20260928-0001`).
   - Nomor urut di-reset setiap hari melalui tabel helper `transaction_counters`.
   - Wajib menggunakan row-locking (`SELECT ... FOR UPDATE`) di dalam `DB::transaction()` saat membaca dan meng-increment counter harian untuk mencegah nomor ganda ketika diakses kasir bersamaan.

5. **Dilarang Hard Delete Data Finansial & Siswa**
   - Data pada tabel `students`, `student_bills`, dan `payments` tidak boleh dihapus secara permanen (`hard delete`).
   - Gunakan status atau soft-delete:
     - Siswa: soft delete atau status (`active`, `graduated`, `transferred`, `dropped_out`).
     - Tagihan: status (`unpaid`, `partial`, `paid`, `void`) disertai alasan dan audit log.
     - Pembayaran: status (`completed`, `void`) dengan `void_reason` dan approval Super Admin.

6. **Pencegahan Kelebihan Bayar & Idempotency**
   - Pembayaran tidak boleh melebihi sisa tagihan pada satu `student_bill`, kecuali kelebihan tersebut dialokasikan ke tagihan lain dalam transaksi yang sama.
   - Sistem wajib menolak transaksi jika ada kelebihan bayar yang tidak dialokasikan (tidak ada automatic credit balance pada MVP).
   - Form submission transaksi pembayaran wajib dilengkapi token idempotency sekali pakai untuk mencegah duplikasi akibat double-click atau network retry.

---

## 2. Struktur Arsitektur (PRD Bab 24)

Pola arsitektur menggunakan **Service Layer** di atas MVC standar Laravel:

```text
app/
├── Models/              # Eloquent models dengan relationship, casting, dan computed accessors
├── Http/
│   ├── Controllers/     # Controller tipis: orkestrasi request -> service -> response
│   ├── Requests/        # Form Request untuk validasi input
│   └── Middleware/      # CheckRole, rate limiting, session security
├── Policies/            # Autorisasi per model (UserPolicy, PaymentPolicy, StudentPolicy)
└── Services/            # Business logic utama
    ├── BillingService.php            # Generate tagihan bulanan/sekali bayar & void tagihan
    ├── PaymentService.php            # Validasi alokasi, catat pembayaran & void transaksi
    ├── TransactionNumberService.php  # Generate nomor TRX dengan row-locking
    ├── ReceiptService.php            # Generate PDF bukti transaksi & cetak ulang
    └── ReportService.php             # Query agregasi laporan keuangan & tunggakan
```

### Prinsip Utama:
- **Controller tipis (Lean Controllers)**: Jangan menulis query kompleks atau perhitungan saldo/tagihan di controller. Delegasikan ke Service.
- **Form Request**: Semua validasi input wajib menggunakan Form Request class terpisah.
- **Policy**: Semua otorisasi hak akses (misal: void pembayaran oleh Super Admin, penghapusan data master) menggunakan Laravel Policy.

---

## 3. Konvensi Penamaan

- **Database Tables**: `snake_case`, jamak/plural (`students`, `student_bills`, `payments`, `payment_details`, `payment_rates`, `academic_years`, `school_settings`).
- **Foreign Keys**: `<singular_table>_id` (`student_id`, `payment_type_id`, `academic_year_id`, `user_id`).
- **Eloquent Models**: PascalCase, tunggal/singular (`Student`, `StudentBill`, `Payment`, `PaymentDetail`, `PaymentRate`, `AcademicYear`).
- **Role & Permission Management**: Sepenuhnya menggunakan package `spatie/laravel-permission` (menggunakan role `super_admin` dan `admin`, tanpa kolom `role` enum manual pada tabel `users`).
- **Service Classes**: `<Domain>Service.php` (`BillingService`, `PaymentService`, `ReceiptService`).
- **Form Requests**: `<Action><Model>Request.php` (`StoreStudentRequest`, `ProcessPaymentRequest`).
- **Policies**: `<Model>Policy.php` (`PaymentPolicy`, `StudentPolicy`).
- **Controllers**: `<Domain>Controller.php` (`PaymentController`, `BillingController`).

---

## 4. Lokalisasi & Lingkungan

- **Timezone**: `Asia/Jakarta` (WIB).
- **Mata Uang**: Rupiah (IDR / `Rp`), pemisah ribuan titik (`.`), pemisah desimal koma (`,`).
- **Bahasa / Locale**: Bahasa Indonesia (`id`), fallback `en`.
- **Database**: MySQL 8.x / MariaDB (utf8mb4_unicode_ci).
