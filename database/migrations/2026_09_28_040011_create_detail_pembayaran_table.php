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
        Schema::create('detail_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayaran')->cascadeOnDelete();
            $table->foreignId('tagihan_siswa_id')->constrained('tagihan_siswa')->cascadeOnDelete();
            $table->decimal('amount_allocated', 12, 2)->comment('Nominal alokasi pembayaran untuk tagihan ini');
            $table->timestamps();

            $table->index(['pembayaran_id', 'tagihan_siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembayaran');
    }
};
