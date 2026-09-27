<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = [
            ['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak', 'description' => 'Jurusan pengembangan software dan pemrograman'],
            ['code' => 'TKJ', 'name' => 'Teknik Komputer dan Jaringan', 'description' => 'Jurusan infrastruktur jaringan dan hardware'],
            ['code' => 'AKL', 'name' => 'Akuntansi dan Keuangan Lembaga', 'description' => 'Jurusan pembukuan dan administrasi keuangan'],
        ];

        foreach ($majors as $m) {
            Jurusan::firstOrCreate(['code' => $m['code']], $m);
        }
    }
}
