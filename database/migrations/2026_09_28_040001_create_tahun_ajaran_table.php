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
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Contoh: 2025/2026');
            $table->boolean('is_active')->default(false)->comment('Hanya satu tahun ajaran aktif dalam satu waktu');
            $table->boolean('is_locked')->default(false)->comment('Tahun ajaran yang dikunci tidak dapat menerima tagihan/transaksi baru');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
