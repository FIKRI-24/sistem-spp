<?php

namespace Database\Factories;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class TahunAjaranFactory extends Factory
{
    protected $model = TahunAjaran::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->numerify('20##/20##'),
            'is_active' => true,
            'is_locked' => false,
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
        ];
    }
}
