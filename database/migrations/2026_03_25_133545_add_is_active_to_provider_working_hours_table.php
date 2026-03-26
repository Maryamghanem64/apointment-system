<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_working_hours', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('end_time');
            $table->index(['provider_id', 'day_of_week', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('provider_working_hours', function (Blueprint $table) {
            $table->dropIndex(['provider_id', 'day_of_week', 'is_active']);
            $table->dropColumn('is_active');
        });
    }
};

