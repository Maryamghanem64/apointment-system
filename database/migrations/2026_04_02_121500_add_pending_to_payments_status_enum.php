<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get current ENUM values
        $query = "SHOW COLUMNS FROM payments LIKE 'status'";
        $result = DB::select($query);
        $enum = $result[0]->Type;
        
        // Extract values: enum('unpaid','paid','refunded')
        preg_match("/^enum\\((.*)\\)$/", $enum, $matches);
        $values = explode(',', $matches[1]);
        
        // Add 'pending' if not exists
        if (!in_array("'pending'", $values)) {
            $values[] = "'pending'";
            $newEnum = 'enum(' . implode(',', $values) . ')';
            
            // Modify column
            DB::statement("ALTER TABLE payments MODIFY COLUMN status {$newEnum} DEFAULT 'unpaid'");
        }
    }

    public function down(): void
    {
        // Revert - remove 'pending' (approximate, manual adjust if needed)
        DB::statement("ALTER TABLE payments MODIFY COLUMN status enum('unpaid','paid','refunded') DEFAULT 'unpaid'");
    }
};
?>

