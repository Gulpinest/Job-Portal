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
        $table->unsignedBigInteger('id_company');
        $table->unsignedBigInteger('package_id'); // Paket yang dibeli
        $table->string('order_id')->unique();
        $table->string('external_id')->nullable();
        $table->decimal('amount', 12, 2);
        $table->string('status')->default('pending');
        $table->string('checkout_link')->nullable();
        $table->timestamps();

        $table->foreign('id_company')->references('id_company')->on('companies')->onDelete('cascade');
        // Pastikan referensi ke packages ada
        $table->foreign('package_id')->references('id')->on('packages'); 
    });
}

public function down(): void
{
    Schema::dropIfExists('payments');
}
};
