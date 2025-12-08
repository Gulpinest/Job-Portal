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
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'nama_package')) {
                $table->string('nama_package')->nullable();
            }
            if (!Schema::hasColumn('packages', 'duration_months')) {
                $table->integer('duration_months')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'nama_package')) {
                $table->dropColumn('nama_package');
            }
            if (Schema::hasColumn('packages', 'duration_months')) {
                $table->dropColumn('duration_months');
            }
        });
    }
};
