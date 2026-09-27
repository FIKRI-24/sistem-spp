<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tagihan_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayaran')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->string('period', 7)->nullable()->comment('Format YYYY-MM untuk tagihan periodik SPP (misal: 2026-07), null jika non-SPP');
            $table->decimal('amount', 12, 2)->comment('Nominal snapshot final saat tagihan dibuat (sudah termasuk diskon)');
            $table->decimal('amount_paid', 12, 2)->default(0)->comment('Akumulasi pembayaran yang sudah masuk (denormalisasi performa)');
            $table->enum('status', ['unpaid', 'partial', 'paid', 'void'])->default('unpaid')->comment('unpaid = belum bayar, partial = sebagian, paid = lunas, void = dibatalkan');
            $table->date('due_date')->nullable()->comment('Tanggal jatuh tempo tagihan');
            $table->text('void_reason')->nullable()->comment('Alasan pembatalan tagihan jika status void');
            $table->timestamps();

            // UNIQUE CONSTRAINT KRUSIAL: Mencegah tagihan ganda per siswa + jenis pembayaran + periode
            $table->unique(['siswa_id', 'jenis_pembayaran_id', 'period'], 'uq_tagihan_siswa_periode');

            // Indexes performa query
            $table->index(['siswa_id', 'status']);
            $table->index(['tahun_ajaran_id', 'jenis_pembayaran_id']);
            $table->index(['status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_siswa');
    }
};
