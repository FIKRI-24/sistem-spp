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
        Schema::create('pengaturan_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('SMK / SMA Negeri Contoh');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('receipt_header_note')->nullable()->comment('Teks catatan kop kuitansi');
            $table->text('receipt_footer_note')->nullable()->comment('Catatan kaki/syarat ketentuan kuitansi');
            $table->text('bank_account_info')->nullable()->comment('Info rekening tujuan transfer');
            $table->string('transaction_number_format')->default('TRX-YYYYMMDD-XXXX');
            $table->foreignId('active_tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->nullOnDelete();
            $table->boolean('auto_create_portal_account')->default(false)->comment('Otomatis buat akun portal saat siswa baru diinput');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sekolah');
    }
};
