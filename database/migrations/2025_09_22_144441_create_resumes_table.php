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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id('id_resume');
            $table->unsignedBigInteger('id_pelamar');
            $table->string('nama_resume');
            $table->string('skill');
            $table->text('file_resume')->nullable();
            $table->timestamps();
            $table->foreign('id_pelamar')->references('id_pelamar')->on('pelamars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
