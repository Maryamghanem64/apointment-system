<?php

namespace Tests\Unit\Services;

use App\DTOs\AvailabilityResult;
use App\Models\Appointment;
use App\Models\Provider;
use App\Models\ProviderHoliday;
use App\Models\ProviderWorkingHour;
use App\Models\Service;
use App\Services\AvailabilityChecker;
use App\Services\SlotGenerator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityCheckerTest extends TestCase
{
    use RefreshDatabase;

    private AvailabilityChecker $checker;
    private Provider $provider;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checker = app(AvailabilityChecker::class);
        $this->provider = Provider::factory()->create(['status' => 'active', 'is_verified' => true]);
        
        // Mon 9-18
        ProviderWorkingHour::factory()->create([
            'provider_id' => $this->provider->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00',
            'end_time' => '18:00',
            'is_active' => true,
        ]);

        $this->service = Service::factory()->create(['duration' => 60]);
    }

    /** @test */
    public function it_returns_available_for_valid_slot()
    {
        $start = Carbon::parse('2024-10-07 10:00'); // Mon 10AM
        $end = $start->copy()->addMinutes($this->service->duration);

        $result = $this->checker->check($this->provider, $start, $end, $this->service);

        $this->assertTrue($result->isAvailable);
        $this->assertEmpty($result->failures);
    }

    /** @test */
    public function it_fails_for_inactive_provider()
    {
        $this->provider->update(['status' => 'inactive']);
        $start = Carbon::parse('2024-10-07 10:00');
        $end = $start->copy()->addMinutes(60);

        $result = $this->checker->check($this->provider, $start, $end, $this->service);

        $this->assertFalse($result->isAvailable);
        $this->assertContains('provider_status', $result->failures);
    }

    /** @test */
    public function it_fails_for_unverified_provider()
    {
        $this->provider->update(['is_verified' => false]);
        $start = Carbon::parse('2024-10-07 10:00');
        $end = $start->copy()->addMinutes(60);

        $result = $this->checker->check($this->provider, $start, $end, $this->service);

        $this->assertFalse($result->isAvailable);
        $this->assertContains('provider_status', $result->failures);
    }

    /** @test */
    public function it_fails_outside_working_hours()
    {
        $start = Carbon::parse('2024-10-07 08:00'); // Before 9AM
        $end = $start->copy()->addMinutes(60);

        $result = $this->checker->check($this->provider, $start, $end, $this->service);

        $this->assertFalse($result->isAvailable);
        $this->assertContains('working_hours', $result->failures);
    }

    /** @test */
    public function it_fails_on_holiday()
    {
        ProviderHoliday::create([
            'provider_id' => $this->provider->id,
            'holiday_date' => '2024-10-07',
        ]);

        $start = Carbon::parse('2024-10-07 10:00');
        $end = $start->copy()->addMinutes(60);

        $result = $this->checker->check($this->provider, $start, $end, $this->service);

        $this->assertFalse($result->isAvailable);
        $this->assertContains('holiday', $result->failures);
    }

    /** @test */
    public function it_fails_on_overlap_with_buffer()
    {
        Appointment::create([
            'client_id' => 1,
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => Carbon::parse('2024-10-07 09:20'), // Overlaps with buffer
            'end_time' => Carbon::parse('2024-10-07 10:20'),
            'status' => 'confirmed',
        ]);

        $start = Carbon::parse('2024-10-07 10:00'); // Within 30min buffer
        $end = $start->copy()->addMinutes(60);

        $result = $this->checker->check($this->provider, $start, $end, $this->service);

        $this->assertFalse($result->isAvailable);
        $this->assertContains('overlap', $result->failures);
    }

    /** @test */
    public function it_fails_service_duration_mismatch()
    {
        $service2 = Service::factory()->create(['duration' => 30]);
        $start = Carbon::parse('2024-10-07 10:00');
        $end = $start->copy()->addMinutes(60); // Wrong duration

        $result = $this->checker->check($this->provider, $start, $end, $service2);

        $this->assertFalse($result->isAvailable);
        $this->assertContains('service_duration', $result->failures);
    }

    /** @test */
    public function it_fails_daily_capacity()
    {
        for ($i = 0; $i < 8; $i++) {
            Appointment::create([
                'client_id' => 1,
                'provider_id' => $this->provider->id,
                'service_id' => $this->service->id,
                'start_time' => Carbon::parse('2024-10-07 09:00')->addMinutes($i * 60),
                'end_time' => Carbon::parse('2024-10-07 10:00')->addMinutes($i * 60),
                'status' => 'confirmed',
            ]);
        }

        $start = Carbon::parse('2024-10-07 17:00');
        $end = $start->copy()->addMinutes(60);

        $result = $this->checker->check($this->provider, $start, $end, $this->service);

        $this->assertFalse($result->isAvailable);
        $this->assertContains('daily_capacity', $result->failures);
    }

    // Additional tests for breaks, recurring holidays, edge cases (25+ total)
    // ...

    /** @test */
    public function it_returns_suggested_slots()
    {
        $start = Carbon::parse('2024-10-07 08:00'); // Invalid time
        $end = $start->copy()->addMinutes(60);

        $result = $this->checker->check($this->provider, $start, $end, $this->service);

        $this->assertFalse($result->isAvailable);
        $this->assertNotEmpty($result->suggestedSlots);
    }

    /** @test */
    public function get_available_slots_returns_collection()
    {
        $result = $this->checker->getAvailableSlots(
            $this->provider,
            Carbon::parse('2024-10-07'),
            Carbon::parse('2024-10-07'),
            $this->service,
            5
        );

        $this->assertInstanceOf(Collection::class, $result);
    }
}

