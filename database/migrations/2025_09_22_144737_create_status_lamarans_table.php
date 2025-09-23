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
        Schema::create('status_lamarans', function (Blueprint $table) {
            $table->id('id_status_lamaran');
            $table->unsignedBigInteger('id_lamaran');
            $table->enum('status_lamaran', ['Diajukan', 'Diproses', 'Interview', 'Diterima', 'Ditolak']);
            $table->timestamps();
            $table->foreign('id_lamaran')->references('id_lamaran')->on('lamarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_lamarans');
    }
};
