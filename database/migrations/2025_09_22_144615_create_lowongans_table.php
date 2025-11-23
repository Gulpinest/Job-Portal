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
        Schema::create('lowongans', function (Blueprint $table) {
            $table->id('id_lowongan');
            $table->unsignedBigInteger('id_company');
            $table->string('judul');
            $table->string('posisi')->nullable();
            
            // --- FIELD BARU DITAMBAHKAN SESUAI FORM ---
            $table->string('lokasi_kantor')->nullable();
            $table->string('gaji')->nullable();
            $table->string('keterampilan')->nullable();
            $table->string('tipe_kerja')->nullable(); // Menggunakan string untuk menyimpan daftar skill yang dipisahkan koma
            // ------------------------------------------

            $table->text('deskripsi')->nullable();
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->timestamps();
            
            // Definisi Foreign Key
            $table->foreign('id_company')->references('id_company')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};
