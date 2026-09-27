<?php

namespace Database\Factories;

use App\Models\JenisPembayaran;
use App\Models\Siswa;
use App\Models\TagihanSiswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagihanSiswaFactory extends Factory
{
    protected $model = TagihanSiswa::class;

    public function definition(): array
    {
        return [
            'siswa_id' => Siswa::factory(),
            'jenis_pembayaran_id' => JenisPembayaran::factory(),
            'tahun_ajaran_id' => TahunAjaran::factory(),
            'period' => fake()->numerify('2025-##'),
            'amount' => 250000.00,
            'amount_paid' => 0.00,
            'status' => 'unpaid',
            'due_date' => '2025-07-10',
            'void_reason' => null,
        ];
    }
}
