<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('companies', function (Blueprint $table) {
        // Default 0 agar mereka harus beli dulu, atau kasih 1 untuk trial
        $table->integer('job_quota')->default(0); 
    });
}

public function down()
{
    Schema::table('companies', function (Blueprint $table) {
        $table->dropColumn('job_quota');
    });
}
};
