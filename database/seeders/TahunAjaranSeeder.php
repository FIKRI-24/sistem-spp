<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TahunAjaran::firstOrCreate(
            ['name' => '2025/2026'],
            [
                'is_active' => true,
                'is_locked' => false,
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
            ]
        );
    }
}
