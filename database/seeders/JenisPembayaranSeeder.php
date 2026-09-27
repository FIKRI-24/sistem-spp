<?php

namespace Database\Seeders;

use App\Models\JenisPembayaran;
use Illuminate\Database\Seeder;

class JenisPembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'code' => 'SPP',
                'name' => 'SPP Bulanan',
                'category' => 'recurring',
                'description' => 'Iuran sumbangan pembinaan pendidikan bulanan rutin',
                'is_active' => true,
            ],
            [
                'code' => 'UJIAN',
                'name' => 'Biaya Ujian Akhir',
                'category' => 'one_time',
                'description' => 'Biaya pelaksanaan ujian akhir semester',
                'is_active' => true,
            ],
            [
                'code' => 'GEDUNG',
                'name' => 'Uang Pengembangan Gedung',
                'category' => 'one_time',
                'description' => 'Biaya pembangunan dan sarana prasarana sekolah',
                'is_active' => true,
            ],
            [
                'code' => 'SERAGAM',
                'name' => 'Uang Seragam & Atribut',
                'category' => 'one_time',
                'description' => 'Paket seragam sekolah, jas almamater, dan atribut',
                'is_active' => true,
            ],
        ];

        foreach ($types as $t) {
            JenisPembayaran::firstOrCreate(['code' => $t['code']], $t);
        }
    }
}
