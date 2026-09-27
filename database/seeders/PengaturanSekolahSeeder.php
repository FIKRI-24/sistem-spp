<?php

namespace Database\Seeders;

use App\Models\PengaturanSekolah;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class PengaturanSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ta = TahunAjaran::where('is_active', true)->first();

        PengaturanSekolah::firstOrCreate(
            ['id' => 1],
            [
                'school_name' => 'SMK Teknologi Nusantara',
                'address' => 'Jl. Pendidikan No. 45, Jakarta Selatan',
                'phone' => '(021) 7891234',
                'email' => 'info@smkteknologinusantara.sch.id',
                'receipt_header_note' => 'Bukti Pembayaran Sah Diterbitkan oleh Sistem Kasir SIPS',
                'receipt_footer_note' => 'Simpan bukti pembayaran ini sebagai tanda bukti pembayaran yang sah.',
                'bank_account_info' => 'Bank Mandiri: 123-00-9876543-2 a.n. SMK Teknologi Nusantara',
                'transaction_number_format' => 'TRX-YYYYMMDD-XXXX',
                'active_tahun_ajaran_id' => $ta?->id,
                'auto_create_portal_account' => true,
            ]
        );
    }
}
