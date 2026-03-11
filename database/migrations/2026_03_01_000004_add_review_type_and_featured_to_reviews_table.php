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
        Schema::table('reviews', function (Blueprint $table) {
            // Add review_type column (platform or provider)
            $table->enum('review_type', ['platform', 'provider'])->default('platform')->after('user_id');
            
            // Make provider_id and appointment_id nullable for platform reviews
            $table->foreignId('provider_id')->nullable()->change();
            $table->foreignId('appointment_id')->nullable()->change();
            
            // Add is_featured column for admin to feature reviews on welcome page
            $table->boolean('is_featured')->default(false)->after('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['review_type', 'is_featured']);
            
            // Restore the foreign keys as required (not nullable)
            $table->foreignId('provider_id')->constrained()->onDelete('cascade')->change();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade')->change();
        });
    }
};

