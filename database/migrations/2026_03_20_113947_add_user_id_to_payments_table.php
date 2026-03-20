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
    Schema::table('payments', function (Blueprint $table) {
        if (!Schema::hasColumn('payments', 'user_id')) {
            $table->unsignedBigInteger('user_id')->nullable()->after('appointment_id');
        }
        if (!Schema::hasColumn('payments', 'currency')) {
            $table->string('currency', 10)->default('usd');
        }
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn(['user_id', 'currency']);
    });
}
    /**
     * Reverse the migrations.
     */
    
};
