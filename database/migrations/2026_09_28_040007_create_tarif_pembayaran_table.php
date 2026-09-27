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
        Schema::create('tarif_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayaran')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete()->comment('Nullable jika tarif berlaku per jurusan/sekolah');
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->nullOnDelete()->comment('Nullable jika tarif berlaku umum/per kelas');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->comment('Nominal tarif dalam Rupiah');
            $table->date('effective_date')->comment('Tanggal berlaku tarif, acuan untuk snapshot tarif');
            $table->timestamps();

            $table->index(['jenis_pembayaran_id', 'tahun_ajaran_id', 'effective_date'], 'idx_tarif_lookup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_pembayaran');
    }
};
