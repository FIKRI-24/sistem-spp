<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy untuk manajemen pengguna (PRD Bab 7 & 8.3).
 *
 * Hanya Super Admin yang berhak mengelola user.
 * User tidak boleh menonaktifkan akun dirinya sendiri
 * untuk mencegah kehilangan akses admin secara tidak sengaja.
 */
class UserPolicy
{
    /**
     * Hanya Super Admin yang bisa melihat daftar user.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Hanya Super Admin yang bisa melihat detail user lain.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Hanya Super Admin yang bisa membuat user baru.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Hanya Super Admin yang bisa mengubah data user.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Hanya Super Admin yang bisa menghapus user.
     * User tidak boleh menghapus dirinya sendiri.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('super_admin') && $user->id !== $model->id;
    }

    /**
     * Hanya Super Admin yang bisa toggle status aktif/nonaktif user.
     * User tidak boleh menonaktifkan akunnya sendiri (mencegah
     * kehilangan akses Super Admin terakhir secara tidak sengaja).
     */
    public function toggleActive(User $user, User $model): bool
    {
        return $user->hasRole('super_admin') && $user->id !== $model->id;
    }

    /**
     * Hanya Super Admin yang bisa assign/revoke role user.
     */
    public function manageRoles(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }
}
