<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete interview schedules with null waktu_jadwal
        DB::table('interview_schedules')
            ->whereNull('waktu_jadwal')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse - just cleaning up invalid data
    }
};
