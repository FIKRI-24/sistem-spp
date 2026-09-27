<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            TahunAjaranSeeder::class,
            JurusanSeeder::class,
            KelasSeeder::class,
            JenisPembayaranSeeder::class,
            TarifPembayaranSeeder::class,
            PengaturanSekolahSeeder::class,
            SiswaSeeder::class,
        ]);
    }
}
