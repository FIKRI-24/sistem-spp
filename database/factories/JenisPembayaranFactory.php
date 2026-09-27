<?php

namespace Database\Factories;

use App\Models\JenisPembayaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisPembayaranFactory extends Factory
{
    protected $model = JenisPembayaran::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('???'),
            'name' => fake()->words(2, true),
            'category' => fake()->randomElement(['recurring', 'one_time']),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
