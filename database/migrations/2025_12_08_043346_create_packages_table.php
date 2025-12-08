<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('price');
        $table->integer('job_limit')->nullable(); // null = unlimited
        $table->text('description')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};