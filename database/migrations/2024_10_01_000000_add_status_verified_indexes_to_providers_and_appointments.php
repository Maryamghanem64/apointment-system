<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add status and verified to providers
        if (Schema::hasTable('providers')) {
            Schema::table('providers', function (Blueprint $table) {
                $table->enum('status', ['inactive', 'active'])->default('active')->after('user_id');
                $table->boolean('is_verified')->default(false)->after('status');
                $table->index(['status', 'is_verified']);
            });
        }

        // Additional indexes for performance
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->index(['provider_id', DB::raw('DATE(start_time)')]);
                $table->index('status');
            });
        }

        if (Schema::hasTable('provider_holidays')) {
            Schema::table('provider_holidays', function (Blueprint $table) {
                $table->index(['provider_id', 'holiday_date']);
            });
        }

        if (Schema::hasTable('provider_working_hours')) {
            Schema::table('provider_working_hours', function (Blueprint $table) {
                $table->index(['provider_id', 'day_of_week']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropIndex(['status', 'is_verified']);
            $table->dropColumn(['status', 'is_verified']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['provider_id', 'start_date']);
            $table->dropIndex('status');
        });

        Schema::table('provider_holidays', function (Blueprint $table) {
            $table->dropIndex(['provider_id', 'holiday_date']);
        });

        Schema::table('provider_working_hours', function (Blueprint $table) {
            $table->dropIndex(['provider_id', 'day_of_week']);
        });
    }
};
