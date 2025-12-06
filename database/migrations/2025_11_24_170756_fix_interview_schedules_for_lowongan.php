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
            // Make id_lowongan NOT NULL (required) since system is now lowongan-based
            if (Schema::hasColumn('interview_schedules', 'id_lowongan')) {
                $table->unsignedBigInteger('id_lowongan')->nullable(false)->change();
            }

            // Add foreign key to lowongans if not exists
            if (!Schema::hasColumn('interview_schedules', 'id_lowongan')) {
                $table->unsignedBigInteger('id_lowongan')->after('id_lamaran');
                $table->foreign('id_lowongan')->references('id_lowongan')->on('lowongans')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_schedules', function (Blueprint $table) {
            // Revert changes
        });
    }
};
