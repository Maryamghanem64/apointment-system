<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Provider;

class MigrationIntegrityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function appointments_table_has_required_columns()
    {
        Schema::create('users', fn($table) => $table->id());
        
        $this->artisan('migrate');
        
        $this->assertTrue(Schema::hasTable('appointments'));
        $this->assertTrue(Schema::hasColumn('appointments', 'id'));
        $this->assertTrue(Schema::hasColumn('appointments', 'client_id'));
        $this->assertTrue(Schema::hasColumn('appointments', 'provider_id'));
        $this->assertTrue(Schema::hasColumn('appointments', 'service_id'));
        $this->assertTrue(Schema::hasColumn('appointments', 'stripe_payment_id'));
        $this->assertTrue(Schema::hasColumn('appointments', 'status'));
        $this->assertTrue(Schema::hasColumn('appointments', 'start_time'));
        $this->assertTrue(Schema::hasColumn('appointments', 'end_time'));
        $this->assertTrue(Schema::hasColumn('appointments', 'duration_minutes'));
        $this->assertTrue(Schema::hasColumn('appointments', 'deleted_at'));
    }

    /** @test */
    public function services_table_structure_correct()
    {
        $this->artisan('migrate');
        $this->assertTrue(Schema::hasColumn('services', 'name'));
        $this->assertTrue(Schema::hasColumn('services', 'duration'));
        $this->assertTrue(Schema::hasColumn('services', 'price'));
        $this->assertTrue(Schema::hasColumn('services', 'deleted_at'));
    }

    /** @test */
    public function providers_table_structure_correct()
    {
        $this->artisan('migrate');
        $this->assertTrue(Schema::hasColumn('providers', 'user_id'));
        $this->assertTrue(Schema::hasColumn('providers', 'deleted_at'));
    }

    /** @test */
    public function appointments_indexes_exist()
    {
        $this->artisan('migrate');
        $indexes = DB::select('SHOW INDEX FROM appointments');
        $this->assertNotEmpty(array_filter($indexes, fn($i) => $i->Key_name === 'appointments_start_time_index'));
        $this->assertNotEmpty(array_filter($indexes, fn($i) => $i->Key_name === 'appointments_stripe_payment_id_index'));
    }

    /** @test */
    public function models_work_post_migration()
    {
        $this->artisan('migrate');
        $appointment = Appointment::factory()->create();
        $this->assertNotNull($appointment->fresh());
        $this->assertDatabaseHas('appointments', ['id' => $appointment->id]);
    }
}
