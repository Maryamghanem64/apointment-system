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
       Schema::create('appointments', function (Blueprint $table) {
    $table->id();

    $table->foreignId('client_id')
          ->constrained('users')
          ->onDelete('cascade');

    $table->foreignId('provider_id')
          ->constrained()
          ->onDelete('cascade');

    $table->foreignId('service_id')
          ->constrained()
          ->onDelete('cascade');

    $table->dateTime('start_time');
    $table->dateTime('end_time');

    $table->enum('status', [
        'pending',
        'confirmed',
        'cancelled',
        'completed',
        'no_show'
    ])->default('pending');

    $table->text('client_note')->nullable();
    $table->text('provider_note')->nullable();

    $table->timestamps();
    $table->softDeletes();

    $table->unique(['provider_id', 'start_time']);
    $table->index(['provider_id', 'start_time']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
