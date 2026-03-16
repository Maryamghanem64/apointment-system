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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('appointment_id');
            $table->string('currency')->default('usd')->after('amount');
            $table->string('stripe_session_id')->nullable()->after('status');
            $table->string('stripe_payment_intent')->nullable()->after('stripe_session_id');
            $table->timestamp('paid_at')->nullable()->after('stripe_payment_intent');
            
            DB::statement('ALTER TABLE payments MODIFY COLUMN status ENUM(\"pending\", \"paid\", \"failed\", \"refunded\") DEFAULT \"pending\"');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
