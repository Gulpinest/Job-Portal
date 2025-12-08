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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Perusahaan yang melakukan pembelian
            $table->foreignId('package_id')->constrained('packages'); // Paket yang dibeli
            
            $table->string('midtrans_order_id')->unique(); // ID yang unik dikirim ke Midtrans
            $table->unsignedBigInteger('gross_amount');
            
            $table->string('snap_token')->nullable(); // Token dari Midtrans
            $table->string('payment_status')->default('pending'); // Status Pembayaran (pending, settlement, expire, dll)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
