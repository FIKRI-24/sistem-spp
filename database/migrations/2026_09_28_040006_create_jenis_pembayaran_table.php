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
        Schema::create('jenis_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode unik, misal: SPP, UJIAN, GEDUNG');
            $table->string('name')->comment('Nama jenis pembayaran');
            $table->enum('category', ['recurring', 'one_time'])->comment('recurring: bulanan/rutin, one_time: sekali bayar');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pembayaran');
    }
};
