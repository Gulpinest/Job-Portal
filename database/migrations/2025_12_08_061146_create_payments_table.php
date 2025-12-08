<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company'); // Relasi ke Company
            $table->string('order_id')->unique();     // ID Unik Transaksi (external_id)
            $table->string('external_id')->nullable(); // ID Balikan dari Doovera
            $table->decimal('amount', 12, 2);         // Jumlah Bayar
            $table->string('status')->default('pending'); // pending, paid, expired
            $table->string('va_number')->nullable();  // Nomor VA [cite: 41]
            $table->string('checkout_link')->nullable(); // Link Pembayaran [cite: 45]
            $table->timestamps();

            $table->foreign('id_company')->references('id_company')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};