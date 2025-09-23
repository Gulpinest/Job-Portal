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
        Schema::create('lamarans', function (Blueprint $table) {
            $table->id('id_lamaran');
            $table->unsignedBigInteger('id_pelamar');
            $table->unsignedBigInteger('id_lowongan');
            $table->text('cv')->nullable();
            $table->enum('status_ajuan', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
            $table->timestamps();
            $table->foreign('id_pelamar')->references('id_pelamar')->on('pelamars')->onDelete('cascade');
            $table->foreign('id_lowongan')->references('id_lowongan')->on('lowongans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lamarans');
    }
};
