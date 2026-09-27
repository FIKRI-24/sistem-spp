<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => fake()->unique()->numerify('2025#####'),
            'nisn' => fake()->numerify('006#######'),
            'name' => fake()->name(),
            'gender' => fake()->randomElement(['L', 'P']),
            'kelas_id' => Kelas::factory(),
            'entry_date' => '2025-07-15',
            'status' => 'active',
            'parent_name' => fake()->name(),
            'parent_phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ];
    }
}
