<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
        $table->unsignedBigInteger('package_id')->nullable(); // TANPA after()
        $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
        $table->dropForeign(['package_id']);
        $table->dropColumn('package_id');
        });
    }
};
