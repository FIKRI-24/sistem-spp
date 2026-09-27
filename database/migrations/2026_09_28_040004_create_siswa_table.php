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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique()->comment('Nomor Induk Siswa unik di seluruh sistem');
            $table->string('nisn')->nullable();
            $table->string('name');
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete()->comment('Kelas saat ini (denormalisasi untuk performa query)');
            $table->date('entry_date')->comment('Tanggal masuk siswa, acuan generate SPP jika masuk tengah tahun');
            $table->enum('status', ['active', 'graduated', 'transferred', 'dropped_out'])->default('active');
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->text('address')->nullable();
            $table->softDeletes()->comment('Dilarang hard-delete data siswa');
            $table->timestamps();

            $table->index(['kelas_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
