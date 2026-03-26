<?php

namespace Database\Seeders;

use App\Models\Provider;
use App\Models\ProviderWorkingHour;
use Illuminate\Database\Seeder;

class ProviderWorkingHoursSeeder extends Seeder
{
    public function run(): void
    {
        $providers = Provider::all();

        foreach ($providers as $provider) {
            // Default 9-18 Mon-Fri active
            $weekdays = [1, 2, 3, 4, 5]; // mon-fri ints
            foreach ($weekdays as $dayInt) {
                ProviderWorkingHour::firstOrCreate(
                    [
                        'provider_id' => $provider->id,
                        'day_of_week' => $dayInt,
                    ],
                    [
                        'start_time' => '09:00:00',
                        'end_time' => '18:00:00',
                        'is_active' => true,
                    ]
                );
            }

            // Weekend off
            $weekends = [6, 0]; // sat=6 sun=0
            foreach ($weekends as $dayInt) {
                ProviderWorkingHour::firstOrCreate(
                    [
                        'provider_id' => $provider->id,
                        'day_of_week' => $dayInt,
                    ],
                    [
                        'start_time' => '00:00:00',
                        'end_time' => '00:00:00',
                        'is_active' => false,
                    ]
                );
            }
        }
    }
}

