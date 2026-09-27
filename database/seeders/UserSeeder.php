<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin default
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@sppsistem.test'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Petugas Keuangan / Admin default
        $admin = User::firstOrCreate(
            ['email' => 'admin@sppsistem.test'],
            [
                'name' => 'Petugas Keuangan',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');
    }
}
