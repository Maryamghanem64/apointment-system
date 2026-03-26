<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Provider;
use App\Models\Service;

class ProductionHealthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_01_migrations_all_ran()
    {
        $status = Artisan::call('migrate:status');
        $output = Artisan::output();
        
        // Critical tables must exist
        $this->assertStringContainsString('Ran', $output);
        $this->assertDatabaseHasTables([
            'users', 'providers', 'services', 'appointments',
            'provider_holidays', 'provider_service', 'payments'
        ]);
    }

    /** @test */
    public function test_02_api_holidays_route_exists()
    {
        Artisan::call('route:clear');
        Artisan::call('route:cache');
        
        $routes = Artisan::output();
        $this->assertMatchesRegularExpression('/providers.*holidays/', $routes);
        $this->assertMatchesRegularExpression('/api\/providers\/\{provider\}\/holidays/', $routes);
    }

    /** @test */
    public function test_03_admin_dashboard_accessible()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_04_api_provider_availability_200()
    {
        $provider = Provider::factory()->create();
        
        $response = $this->json('GET', "/api/providers/{$provider->id}/availability");
        $response->assertStatus(200);
    }

    /** @test */
    public function test_05_services_seeded()
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        
        $this->assertTrue(Service::count() > 0);
        $this->assertDatabaseHas('services', ['is_active' => 1]);
    }

    /** @test */
    public function test_06_queue_configuration()
    {
        config(['queue.default' => 'database']);
        $this->assertEquals('database', config('queue.default'));
        
        $this->assertDatabaseTableExists('jobs');
        $this->assertDatabaseTableExists('failed_jobs');
    }

    /** @test */
    public function test_07_email_configuration()
    {
        config(['mail.mailers.smtp.transport' => 'smtp']);
        $this->assertNotEmpty(config('mail.mailers.smtp.host'));
    }

    /** @test */
    public function test_08_stripe_keys_loaded()
    {
        config(['services.stripe.key' => env('STRIPE_KEY')]);
        $this->assertNotEmpty(config('services.stripe.key'));
    }

    /** @test */
    public function test_09_roles_seeded()
    {
        Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
        
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'provider']);
        $this->assertDatabaseHas('roles', ['name' => 'client']);
    }

    /** @test */
    public function test_10_production_cache_ready()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        
        $this->assertFileExists(storage_path('framework/cache/config.php'));
        $this->assertFileExists(storage_path('framework/routes/routes.php'));
    }

    /** @test */
    public function test_bonus_all_tests_pass()
    {
        $result = Artisan::call('test', ['--coverage']);
        $this->assertEquals(0, $result, 'All tests must pass for production');
    }

    private function assertDatabaseHasTables(array $tables): void
    {
        foreach ($tables as $table) {
            $this->assertTrue(DB::hasTable($table), "Table $table missing");
        }
    }
}

