<?php

namespace Tests\Feature;

use App\Models\Provider;
use App\Models\ProviderWorkingHour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderWorkingHoursTest extends TestCase
{
    use RefreshDatabase;

    public function test_provider_can_view_own_working_hours()
    {
        $providerUser = User::factory()->create(['role' => 'provider']);
        $provider = Provider::factory()->create(['user_id' => $providerUser->id]);
        ProviderWorkingHour::factory()->create(['provider_id' => $provider->id]);

        $response = $this->actingAs($providerUser)->get(route('provider.working-hours.index'));

        $response->assertStatus(200);
    }

    public function test_provider_cannot_view_admin_working_hours()
    {
        $providerUser = User::factory()->create(['role' => 'provider']);
        $adminUser = User::factory()->create(['role' => 'admin']);
        $provider = Provider::factory()->create(['user_id' => $adminUser->id]);

        $response = $this->actingAs($providerUser)->get(route('providers.working-hours.index', $provider));

        $response->assertForbidden();
    }

    public function test_admin_can_view_all_working_hours()
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        ProviderWorkingHour::factory()->count(3)->create();

        $response = $this->actingAs($adminUser)->get(route('provider.working-hours.index'));

        $response->assertStatus(200);
    }

    public function test_provider_can_create_working_hour()
    {
        $providerUser = User::factory()->create(['role' => 'provider']);
        $provider = Provider::factory()->create(['user_id' => $providerUser->id]);

        $response = $this->actingAs($providerUser)->post(route('provider.working-hours.store'), [
            'day_of_week' => 'monday',
            'start_time' => '09:00',
            'end_time' => '18:00',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('provider_working_hours', [
            'provider_id' => $provider->id,
            'day_of_week' => 'monday',
        ]);
    }

    public function test_provider_can_update_working_hour()
    {
        $providerUser = User::factory()->create(['role' => 'provider']);
        $provider = Provider::factory()->create(['user_id' => $providerUser->id]);
        $hour = ProviderWorkingHour::factory()->create(['provider_id' => $provider->id]);

        $response = $this->actingAs($providerUser)->put(route('provider.working-hours.update', $hour), [
            'day_of_week' => 'monday',
            'start_time' => '10:00',
            'end_time' => '19:00',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('provider_working_hours', [
            'id' => $hour->id,
            'start_time' => '10:00',
        ]);
    }

    public function test_provider_can_delete_working_hour()
    {
        $providerUser = User::factory()->create(['role' => 'provider']);
        $provider = Provider::factory()->create(['user_id' => $providerUser->id]);
        $hour = ProviderWorkingHour::factory()->create(['provider_id' => $provider->id]);

        $response = $this->actingAs($providerUser)->delete(route('provider.working-hours.destroy', $hour));

        $response->assertRedirect();
        $this->assertSoftDeleted('provider_working_hours', ['id' => $hour->id]);
    }

    public function test_working_hour_is_working_method()
    {
        $provider = Provider::factory()->create();
        $hour = ProviderWorkingHour::factory()->create([
            'provider_id' => $provider->id,
            'day_of_week' => 'monday',
            'start_time' => '09:00',
            'end_time' => '18:00',
        ]);

        $monday10am = now()->startOfWeek()->addDays(1)->setTime(10, 0);
        $saturday10am = now()->startOfWeek()->addDays(6)->setTime(10, 0);

        $this->assertTrue($hour->isWorking($monday10am));
        $this->assertFalse($hour->isWorking($saturday10am));
        $this->assertFalse($hour->isWorking($monday10am->copy()->setTime(8, 0))); // Before
        $this->assertFalse($hour->isWorking($monday10am->copy()->setTime(19, 0))); // After
    }

    public function test_working_hour_conflicts_with_method()
    {
        $hour = ProviderWorkingHour::factory()->create([
            'day_of_week' => 'monday',
            'start_time' => '09:00',
            'end_time' => '18:00',
        ]);

        $monday = now()->startOfWeek()->addDays(1);

        // No conflict
        $this->assertFalse($hour->conflictsWith($monday->copy()->setTime(8, 0), $monday->copy()->setTime(8, 30)));
        $this->assertFalse($hour->conflictsWith($monday->copy()->setTime(18, 30), $monday->copy()->setTime(19, 0)));

        // Conflict
        $this->assertTrue($hour->conflictsWith($monday->copy()->setTime(10, 0), $monday->copy()->setTime(11, 0)));
        $this->assertTrue($hour->conflictsWith($monday->copy()->setTime(8, 30), $monday->copy()->setTime(9, 30))); // Overlap start
    }

    public function test_livewire_inline_edit()
    {
        $providerUser = User::factory()->create(['role' => 'provider']);
        $provider = Provider::factory()->create(['user_id' => $providerUser->id]);

        $component = \Livewire\Livewire::actingAs($providerUser)
            ->test(\App\Http\Livewire\Provider\WorkingHours\Index::class, ['providerId' => $provider->id]);

        $component->call('toggleActive', 'monday');

        $this->assertDatabaseHas('provider_working_hours', [
            'provider_id' => $provider->id,
            'day_of_week' => 'monday',
            'is_active' => false,
        ]);
    }

    public function test_api_endpoints()
    {
        $providerUser = User::factory()->create(['role' => 'provider']);
        $provider = Provider::factory()->create(['user_id' => $providerUser->id]);
        ProviderWorkingHour::factory()->create(['provider_id' => $provider->id]);

        $response = $this->actingAs($providerUser, 'sanctum')->getJson("/api/providers/{$provider->id}/working-hours");

        $response->assertStatus(200);
    }
}

