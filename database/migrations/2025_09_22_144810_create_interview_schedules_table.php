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
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id('id_schedule');
            $table->string('type');
            $table->string('tempat')->nullable();
            $table->dateTime('waktu_jadwal')->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('id_lowongan');
            $table->unsignedBigInteger('id_company');
            $table->unsignedBigInteger('id_lamaran');
            $table->timestamps();
            $table->foreign('id_lowongan')->references('id_lowongan')->on('lowongans')->onDelete('cascade');
            $table->foreign('id_company')->references('id_company')->on('companies')->onDelete('cascade');
            $table->foreign('id_lamaran')->references('id_lamaran')->on('lamarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_schedules');
    }
};
