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
        // Convert skills to global master data (remove id_pelamar)
        Schema::table('skills', function (Blueprint $table) {
            // Check if the column exists before dropping
            if (Schema::hasColumn('skills', 'id_pelamar')) {
                $table->dropForeign(['id_pelamar']);
                $table->dropColumn('id_pelamar');
            }
        });

        // Create pelamar_skill pivot table (many-to-many with additional fields)
        Schema::create('pelamar_skill', function (Blueprint $table) {
            $table->id('id_pelamar_skill');
            $table->unsignedBigInteger('id_pelamar');
            $table->unsignedBigInteger('id_skill');
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced', 'Expert'])->default('Beginner');
            $table->integer('years_experience')->nullable()->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_pelamar')->references('id_pelamar')->on('pelamars')->onDelete('cascade');
            $table->foreign('id_skill')->references('id_skill')->on('skills')->onDelete('cascade');

            // Unique constraint to prevent duplicate skill entries per pelamar
            $table->unique(['id_pelamar', 'id_skill']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelamar_skill');

        Schema::table('skills', function (Blueprint $table) {
            // Add back id_pelamar column
            $table->unsignedBigInteger('id_pelamar')->after('id_skill');
            $table->foreign('id_pelamar')->references('id_pelamar')->on('pelamars')->onDelete('cascade');
        });
    }
};
