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
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('companies', 'subscription_date')) {
                $table->timestamp('subscription_date')->nullable()->after('package_id');
            }
            if (!Schema::hasColumn('companies', 'subscription_expired_at')) {
                $table->timestamp('subscription_expired_at')->nullable()->after('subscription_date');
            }
            if (!Schema::hasColumn('companies', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('subscription_expired_at');
            }
            if (!Schema::hasColumn('companies', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('companies', 'rejection_reason')) {
                $table->string('rejection_reason')->nullable()->after('verified_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['package_id', 'subscription_date', 'subscription_expired_at', 'is_verified', 'verified_at', 'rejection_reason']);
        });
    }
};
