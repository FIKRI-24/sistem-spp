<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar permissions sesuai PRD Bab 7
        $permissions = [
            'manage_users',
            'manage_settings',
            'manage_master_data',
            'manage_students',
            'manage_bills',
            'process_payments',
            'approve_void_payments',
            'request_void_payments',
            'view_reports',
            'view_audit_logs',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Role Super Admin - seluruh hak akses
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        // Role Admin / Petugas Keuangan - hak operasional harian (tanpa approve void, manajemen user, config sekolah)
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'manage_students',
            'manage_bills',
            'process_payments',
            'request_void_payments',
            'view_reports',
        ]);
    }
}
