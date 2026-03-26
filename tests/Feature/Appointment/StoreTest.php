<?php

namespace Tests\Feature\Appointment;

use App\Models\Appointment;
use App\Models\Provider;
use App\Models\Service;
use App\Models\User;
use App\Models\ProviderWorkingHour;
use App\Models\ProviderHoliday;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->client()->create();
        $this->providerUser = User::factory()->create();
        $this->providerUser->assignRole('provider');
        $this->provider = Provider::factory()->create(['user_id' => $this->providerUser->id]);
        $this->service = Service::factory()->create(['duration' => 60, 'price' => 50]);
    }

    /** @test */
    public function invalid_dates_rejected_422()
    {
        $response = $this->actingAs($this->user)->postJson(route('client.appointments.store'), [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => 'invalid-date',
            'duration_minutes' => 60,
            'payment_method' => 'stripe',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function double_booking_prevented()
    {
        // Setup working hour
        ProviderWorkingHour::factory()->create([
            'provider_id' => $this->provider->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $start = Carbon::now()->addDay()->setTime(10, 0); // Monday 10AM

        // Create first appointment
        $this->actingAs($this->user)->post(route('client.appointments.store'), [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => $start->toDateTimeString(),
            'duration_minutes' => 60,
            'payment_method' => 'free',
        ]);

        // Second should fail overlap
        $response = $this->actingAs($this->user)->post(route('client.appointments.store'), [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => $start->toDateTimeString(),
            'duration_minutes' => 60,
            'payment_method' => 'free',
        ]);

        $response->assertSessionHasErrors(['start_time']);
        $this->assertDatabaseCount('appointments', 1);
    }

    /** @test */
    public function stripe_session_created_for_paid()
    {
        ProviderWorkingHour::factory()->create([
            'provider_id' => $this->provider->id,
            'day_of_week' => Carbon::now()->addDay()->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($this->user)->post(route('client.appointments.store'), [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => Carbon::now()->addDay()->setTime(10,0)->toDateTimeString(),
            'end_time' => Carbon::now()->addDay()->setTime(11,0)->toDateTimeString(),
            'duration_minutes' => 60,
            'payment_method' => 'stripe',
        ]);

        $response->assertRedirectContains('payments.checkout');
        $this->assertDatabaseHas('appointments', ['status' => 'pending']);
    }

    /** @test */
    public function email_queued_for_free_appointments()
    {
        ProviderWorkingHour::factory()->create([
            'provider_id' => $this->provider->id,
            'day_of_week' => Carbon::now()->addDay()->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $this->fake(Mail::fake());

        $response = $this->actingAs($this->user)->post(route('client.appointments.store'), [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => Carbon::now()->addDay()->setTime(10,0)->toDateTimeString(),
            'end_time' => Carbon::now()->addDay()->setTime(11,0)->toDateTimeString(),
            'duration_minutes' => 60,
            'payment_method' => 'free',
        ]);

        Mail::assertQueued(\App\Mail\NewAppointmentMail::class);
    }

    /** @test */
    public function transaction_rollback_on_failure()
    {
        ProviderHoliday::factory()->create([
            'provider_id' => $this->provider->id,
            'holiday_date' => Carbon::now()->addDay()->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->post(route('client.appointments.store'), [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => Carbon::now()->addDay()->setTime(10,0)->toDateTimeString(),
            'end_time' => Carbon::now()->addDay()->setTime(11,0)->toDateTimeString(),
            'duration_minutes' => 60,
            'payment_method' => 'free',
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('appointments', 0);
    }

    /** @test */
    public function working_hours_violation_rejected()
    {
        ProviderWorkingHour::factory()->create([
            'provider_id' => $this->provider->id,
            'day_of_week' => Carbon::now()->addDay()->dayOfWeek,
            'start_time' => '12:00', // Lunch after
            'end_time' => '13:00',
        ]);

        $response = $this->actingAs($this->user)->post(route('client.appointments.store'), [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => Carbon::now()->addDay()->setTime(12,30)->toDateTimeString(),
            'end_time' => Carbon::now()->addDay()->setTime(13,30)->toDateTimeString(),
            'duration_minutes' => 60,
            'payment_method' => 'free',
        ]);

        $response->assertSessionHasErrors(['start_time']);
    }

    /** @test */
    public function daily_capacity_limit_respected()
    {
        ProviderWorkingHour::factory()->create([
            'provider_id' => $this->provider->id,
            'day_of_week' => Carbon::now()->addDay()->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $date = Carbon::now()->addDay();
        for ($i = 0; $i < 8; $i++) {
            Appointment::factory()->create([
                'provider_id' => $this->provider->id,
                'start_time' => $date->copy()->setTime(9 + $i, 0),
                'end_time' => $date->copy()->setTime(10 + $i, 0),
                'status' => 'confirmed',
            ]);
        }

        $response = $this->actingAs($this->user)->post(route('client.appointments.store'), [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => $date->setTime(17,0)->toDateTimeString(),
            'end_time' => $date->setTime(18,0)->toDateTimeString(),
            'duration_minutes' => 60,
            'payment_method' => 'free',
        ]);

        $response->assertSessionHasErrors(['start_time']);
    }

    /** @test */
    public function service_duration_mismatch_rejected()
    {
        ProviderWorkingHour::factory()->create([
            'provider_id' => $this->provider->id,
            'day_of_week' => Carbon::now()->addDay()->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response = $this->actingAs($this->user)->post(route('client.appointments.store'), [
            'provider_id' => $this->provider->id,
            'service_id' => $this->service->id,
            'start_time' => Carbon::now()->addDay()->setTime(10,0)->toDateTimeString(),
            'end_time' => Carbon::now()->addDay()->setTime(11,0)->toDateTimeString(),
            'duration_minutes' => 45, // Mismatch with service 60
            'payment_method' => 'free',
        ]);

        $response->assertSessionHasErrors(['start_time']);
        $this->assertDatabaseCount('appointments', 0);
    }
}

