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
        Schema::table('interview_schedules', function (Blueprint $table) {
            // Tambahkan kolom baru tanpa mengubah yang ada
            if (!Schema::hasColumn('interview_schedules', 'id_lamaran')) {
                $table->unsignedBigInteger('id_lamaran')->nullable()->after('id_lowongan');
                $table->foreign('id_lamaran')->references('id_lamaran')->on('lamarans')->onDelete('cascade');
            }
            if (!Schema::hasColumn('interview_schedules', 'tanggal_interview')) {
                $table->timestamp('tanggal_interview')->nullable()->after('waktu_jadwal');
            }
            if (!Schema::hasColumn('interview_schedules', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('tempat');
            }
            if (!Schema::hasColumn('interview_schedules', 'tipe')) {
                $table->enum('tipe', ['Online', 'Offline'])->default('Online')->after('type');
            }
            if (!Schema::hasColumn('interview_schedules', 'status')) {
                $table->enum('status', ['Scheduled', 'Completed', 'Cancelled'])->default('Scheduled')->after('catatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('interview_schedules', 'id_lamaran')) {
                $table->dropForeign(['id_lamaran']);
                $table->dropColumn('id_lamaran');
            }
            if (Schema::hasColumn('interview_schedules', 'tanggal_interview')) {
                $table->dropColumn('tanggal_interview');
            }
            if (Schema::hasColumn('interview_schedules', 'lokasi')) {
                $table->dropColumn('lokasi');
            }
            if (Schema::hasColumn('interview_schedules', 'tipe')) {
                $table->dropColumn('tipe');
            }
            if (Schema::hasColumn('interview_schedules', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
