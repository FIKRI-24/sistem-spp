<?php

namespace Database\Seeders;

use App\Models\JenisPembayaran;
use App\Models\TahunAjaran;
use App\Models\TarifPembayaran;
use Illuminate\Database\Seeder;

class TarifPembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ta = TahunAjaran::where('is_active', true)->first();
        if (! $ta) {
            return;
        }

        $spp = JenisPembayaran::where('code', 'SPP')->first();
        $ujian = JenisPembayaran::where('code', 'UJIAN')->first();
        $gedung = JenisPembayaran::where('code', 'GEDUNG')->first();

        $rates = [
            // SPP Rp 250.000 / bulan berlaku umum
            [
                'jenis_pembayaran_id' => $spp?->id,
                'kelas_id' => null,
                'jurusan_id' => null,
                'tahun_ajaran_id' => $ta->id,
                'amount' => 250000.00,
                'effective_date' => '2025-07-01',
            ],
            // Ujian Rp 150.000
            [
                'jenis_pembayaran_id' => $ujian?->id,
                'kelas_id' => null,
                'jurusan_id' => null,
                'tahun_ajaran_id' => $ta->id,
                'amount' => 150000.00,
                'effective_date' => '2025-07-01',
            ],
            // Gedung Rp 1.500.000
            [
                'jenis_pembayaran_id' => $gedung?->id,
                'kelas_id' => null,
                'jurusan_id' => null,
                'tahun_ajaran_id' => $ta->id,
                'amount' => 1500000.00,
                'effective_date' => '2025-07-01',
            ],
        ];

        foreach ($rates as $r) {
            if ($r['jenis_pembayaran_id']) {
                TarifPembayaran::create($r);
            }
        }
    }
}
