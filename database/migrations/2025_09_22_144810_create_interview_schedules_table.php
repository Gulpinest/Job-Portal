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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_lowongan');
            $table->string('type'); // e.g., 'Online', 'Offline', Etc.
            $table->string('tempat')->nullable(); // Link Zoom atau Alamat Tempat
            $table->dateTime('waktu_jadwal');
            $table->text('catatan')->nullable();
            $table->foreign('id_lowongan')->references('id_lowongan')->on('lowongans')->onDelete('cascade');
            $table->timestamps();
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
