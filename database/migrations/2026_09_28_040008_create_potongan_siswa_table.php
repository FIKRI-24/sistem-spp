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
        Schema::create('potongan_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('jenis_pembayaran_id')->nullable()->constrained('jenis_pembayaran')->nullOnDelete()->comment('Null = berlaku untuk seluruh jenis pembayaran');
            $table->enum('discount_type', ['percentage', 'fixed'])->comment('percentage = diskon %, fixed = potongan nominal Rp');
            $table->decimal('value', 12, 2)->comment('Nilai diskon (% atau nominal)');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->text('reason')->nullable()->comment('Alasan pemberian diskon/beasiswa');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['siswa_id', 'tahun_ajaran_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan_siswa');
    }
};
