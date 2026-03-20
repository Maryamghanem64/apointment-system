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
        if (!Schema::hasColumn('payments', 'stripe_session_id')) {
            $table->string('stripe_session_id')->nullable();
        }
        if (!Schema::hasColumn('payments', 'stripe_payment_intent')) {
            $table->string('stripe_payment_intent')->nullable();
        }
        if (!Schema::hasColumn('payments', 'paid_at')) {
            $table->timestamp('paid_at')->nullable();
        }
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn(['stripe_session_id', 'stripe_payment_intent', 'paid_at']);
    });
}
};
