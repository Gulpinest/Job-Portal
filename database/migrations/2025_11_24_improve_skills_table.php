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
        Schema::table('skills', function (Blueprint $table) {
            // Add level column (Beginner, Intermediate, Advanced, Expert)
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced', 'Expert'])->default('Beginner')->after('nama_skill');
            
            // Add years of experience
            $table->integer('years_experience')->nullable()->default(0)->after('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn(['level', 'years_experience']);
        });
    }
};
