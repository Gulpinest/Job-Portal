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
            // Drop old columns if they exist
            if (Schema::hasColumn('interview_schedules', 'tipe')) {
                $table->dropColumn('tipe');
            }
            if (Schema::hasColumn('interview_schedules', 'tanggal_interview')) {
                $table->dropColumn('tanggal_interview');
            }
            if (Schema::hasColumn('interview_schedules', 'tempat')) {
                $table->dropColumn('tempat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_schedules', function (Blueprint $table) {
            //
        });
    }
};
