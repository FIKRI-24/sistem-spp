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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique()->comment('Format unik: TRX-YYYYMMDD-XXXX');
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->comment('Petugas kasir yang memproses pembayaran');
            $table->decimal('total_amount', 12, 2)->comment('Total nominal transaksi');
            $table->enum('payment_method', ['cash', 'transfer', 'other'])->default('cash');
            $table->enum('status', ['completed', 'pending_void', 'void'])->default('completed');
            $table->text('void_reason')->nullable()->comment('Alasan pembatalan transaksi');
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete()->comment('Super Admin yang menyetujui void');
            $table->timestamp('voided_at')->nullable();
            $table->text('notes')->nullable()->comment('Catatan tambahan transaksi');
            $table->timestamp('payment_date')->useCurrent()->comment('Tanggal dan waktu transaksi tercatat');
            $table->timestamps();

            $table->index(['siswa_id', 'status']);
            $table->index(['payment_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
