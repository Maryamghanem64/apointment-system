<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration is intentionally left minimal - the actual services table 
// is defined in migration 2026_02_27_090113_create_services_table.php

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Table already created by 2026_02_27_090113_create_services_table.php
        // This empty migration prevents Laravel errors
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed - table managed by earlier migration
    }
};
