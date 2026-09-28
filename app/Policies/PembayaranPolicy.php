<?php

namespace App\Policies;

use App\Models\Pembayaran;
use App\Models\User;

/**
 * Policy untuk pembayaran / transaksi keuangan (PRD Bab 11.2).
 *
 * Aturan void pembayaran:
 * - Admin bisa REQUEST void (mengajukan pembatalan).
 * - Hanya Super Admin yang bisa APPROVE void (persetujuan pembatalan).
 * - Ini memastikan ada audit trail dua pihak untuk setiap pembatalan
 *   transaksi keuangan (separation of duties).
 */
class PembayaranPolicy
{
    /**
     * Super Admin dan Admin bisa melihat daftar pembayaran.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * Super Admin dan Admin bisa melihat detail pembayaran.
     */
    public function view(User $user, Pembayaran $pembayaran): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * Super Admin dan Admin bisa memproses pembayaran baru.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('process_payments');
    }

    /**
     * Admin bisa MENGAJUKAN void pembayaran (request void).
     * Super Admin juga bisa request void (implicit).
     */
    public function requestVoid(User $user, Pembayaran $pembayaran): bool
    {
        if ($pembayaran->status === 'void') {
            return false;
        }

        return $user->hasPermissionTo('request_void_payments');
    }

    /**
     * Hanya Super Admin yang bisa MENYETUJUI void pembayaran (approve void).
     * Pembayaran yang sudah void tidak bisa di-void lagi.
     */
    public function approveVoid(User $user, Pembayaran $pembayaran): bool
    {
        if ($pembayaran->status === 'void') {
            return false;
        }

        return $user->hasPermissionTo('approve_void_payments');
    }

    /**
     * Hanya Super Admin yang bisa melihat laporan pembayaran lengkap.
     * Admin hanya bisa melihat laporan standar (view_reports).
     */
    public function viewFullReport(User $user): bool
    {
        return $user->hasRole('super_admin');
    }
}
