<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan_skill', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lowongan');
            $table->string('nama_skill'); // skill yang dibutuhkan
            $table->timestamps();

            // relasi ke tabel lowongans
            $table->foreign('id_lowongan')
                  ->references('id_lowongan')
                  ->on('lowongans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan_skill');
    }
};
