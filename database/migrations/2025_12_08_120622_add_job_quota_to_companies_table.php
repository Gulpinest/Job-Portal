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
        Schema::table('companies', function (Blueprint $table) {
            // Menambahkan kolom job_quota
            // Kita set default 0, dan setelah user bayar, nilainya akan bertambah
            $table->integer('job_quota')->unsigned()->default(0)->after('id_user'); 
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Saat rollback, hapus kolom job_quota
            $table->dropColumn('job_quota');
        });
    }
};
