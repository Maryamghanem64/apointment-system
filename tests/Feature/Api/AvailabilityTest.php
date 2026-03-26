<?php

namespace Tests\Feature\Api;

use App\Models\Provider;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    private Provider $provider;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->provider = Provider::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'is_verified' => true,
        ]);
        $this->service = Service::factory()->create(['duration' => 60]);
    }

    /** @test */
    public function it_checks_availability_via_api()
    {
        $response = $this->getJson("/api/availability/check", [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'proposed_start' => '2024-10-07T10:00:00Z',
            'proposed_end' => '2024-10-07T11:00:00Z',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'isAvailable',
            'failures',
            'dailyRemainingSlots'
        ]);
    }

    /** @test */
    public function it_returns_available_slots_via_api()
    {
        $response = $this->getJson("/api/availability/slots", [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_date' => '2024-10-07',
            'end_date' => '2024-10-07',
            'max_results' => 5,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['*', 'start', 'end']);
    }

    // Additional 6 tests for failures, auth, validation, etc.
}

