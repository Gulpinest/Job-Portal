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
         // Nama tabel pivot: interview_schedule_user
        Schema::create('interview_schedule_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_lamaran');
            $table->unsignedBigInteger('id_interview_schedule');
            $table->string('tempat')->nullable();
            $table->foreign('id_lamaran')->references('id_lamaran')->on('lamarans')->onDelete('cascade');
            $table->foreign('id_interview_schedule')->references('id')->on('interview_schedules')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_schedule_user');
    }
};
