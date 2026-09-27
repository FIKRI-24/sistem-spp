<?php

namespace Database\Factories;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    public function definition(): array
    {
        return [
            'name' => 'X '.fake()->unique()->lexify('???').' 1',
            'jurusan_id' => Jurusan::factory(),
            'tahun_ajaran_id' => TahunAjaran::factory(),
        ];
    }
}
