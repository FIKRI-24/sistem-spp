<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
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

        $rpl = Jurusan::where('code', 'RPL')->first();
        $tkj = Jurusan::where('code', 'TKJ')->first();
        $akl = Jurusan::where('code', 'AKL')->first();

        $classes = [
            ['name' => 'X RPL 1', 'jurusan_id' => $rpl?->id, 'tahun_ajaran_id' => $ta->id],
            ['name' => 'XI RPL 1', 'jurusan_id' => $rpl?->id, 'tahun_ajaran_id' => $ta->id],
            ['name' => 'XII RPL 1', 'jurusan_id' => $rpl?->id, 'tahun_ajaran_id' => $ta->id],
            ['name' => 'X TKJ 1', 'jurusan_id' => $tkj?->id, 'tahun_ajaran_id' => $ta->id],
            ['name' => 'XI TKJ 1', 'jurusan_id' => $tkj?->id, 'tahun_ajaran_id' => $ta->id],
            ['name' => 'XII TKJ 1', 'jurusan_id' => $tkj?->id, 'tahun_ajaran_id' => $ta->id],
            ['name' => 'X AKL 1', 'jurusan_id' => $akl?->id, 'tahun_ajaran_id' => $ta->id],
            ['name' => 'XI AKL 1', 'jurusan_id' => $akl?->id, 'tahun_ajaran_id' => $ta->id],
            ['name' => 'XII AKL 1', 'jurusan_id' => $akl?->id, 'tahun_ajaran_id' => $ta->id],
        ];

        foreach ($classes as $c) {
            Kelas::firstOrCreate(
                ['name' => $c['name'], 'jurusan_id' => $c['jurusan_id'], 'tahun_ajaran_id' => $c['tahun_ajaran_id']],
                $c
            );
        }
    }
}
