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
        Schema::create('counter_transaksi', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique()->comment('Tanggal transaksi, nomor urut di-reset tiap hari');
            $table->unsignedInteger('last_number')->default(0)->comment('Nomor urut terakhir yang di-lock dengan SELECT FOR UPDATE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counter_transaksi');
    }
};
