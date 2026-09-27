<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition(): array
    {
        return [
            'transaction_number' => 'TRX-'.date('Ymd').'-'.fake()->unique()->numerify('####'),
            'siswa_id' => Siswa::factory(),
            'user_id' => User::factory(),
            'total_amount' => 250000.00,
            'payment_method' => 'cash',
            'status' => 'completed',
            'void_reason' => null,
            'voided_by' => null,
            'voided_at' => null,
            'notes' => fake()->sentence(),
            'payment_date' => now(),
        ];
    }
}
