<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Review;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get real users (non-admin users)
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'admin');
        })->get();

        // If no users with roles, get all users except admins
        if ($users->isEmpty()) {
            $users = User::all();
        }

        $providers = Provider::all();

        // If no providers exist, create one from a user
        if ($providers->isEmpty()) {
            $this->command->warn('No providers found. Creating a default provider...');
            
            if ($users->isNotEmpty()) {
                $providerUser = $users->first();
                $provider = Provider::create([
                    'user_id' => $providerUser->id,
                ]);
                $providers = collect([$provider]);
            } else {
                $this->command->error('No users found. Please seed users first.');
                return;
            }
        }

        // Get services or create a default one
        $services = Service::all();
        
        if ($services->isEmpty()) {
            $this->command->warn('No services found. Creating a default service...');
            $service = Service::create([
                'name' => 'General Consultation',
                'description' => 'General consultation service',
                'price' => 50.00,
                'duration' => 60,
            ]);
            $services = collect([$service]);
        }

        // Define realistic review data
        $reviewsData = [
            ['rating' => 5, 'comment' => 'Absolutely seamless experience. Booking was quick and the provider was professional.'],
            ['rating' => 5, 'comment' => 'Best appointment platform I have used. Clean interface and reliable service.'],
            ['rating' => 4, 'comment' => 'Very easy to schedule and manage my appointments. Highly recommend!'],
            ['rating' => 5, 'comment' => 'Schedora saved me so much time. Everything is organized perfectly.'],
            ['rating' => 4, 'comment' => 'Great platform, intuitive design and fast booking process.'],
            ['rating' => 5, 'comment' => 'The provider was on time and the whole process was smooth from start to finish.'],
        ];

        $seededCount = 0;

        foreach ($reviewsData as $index => $reviewData) {
            // Get user, provider, and service (cycle through if fewer than 6)
            $user = $users[$index % max($users->count(), 1)];
            $provider = $providers[$index % max($providers->count(), 1)];
            $service = $services[$index % max($services->count(), 1)];

            // Generate random start time
            $startTime = Carbon::now()->subDays(rand(5, 30))->setHour(rand(9, 17))->setMinute(0)->setSecond(0);
            $endTime = (clone $startTime)->addMinutes($service->duration ?? 60);

            // Create or find a completed appointment
            $appointment = Appointment::firstOrCreate(
                [
                    'client_id' => $user->id,
                    'provider_id' => $provider->id,
                    'service_id' => $service->id,
                    'status' => 'completed',
                ],
                [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'client_note' => 'Review seed appointment',
                ]
            );

            // Create review using firstOrCreate to avoid duplicates
            $review = Review::firstOrCreate(
                [
                    'appointment_id' => $appointment->id,
                ],
                [
                    'user_id' => $user->id,
                    'provider_id' => $provider->id,
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment'],
                    'is_approved' => true,
                ]
            );

            if ($review->wasRecentlyCreated) {
                $seededCount++;
                $this->command->info("Seeded review #{$seededCount} for user: {$user->name}");
            } else {
                $this->command->warn("Review already exists for appointment #{$appointment->id}");
            }
        }

        $this->command->info("Review seeding completed! Total new reviews seeded: {$seededCount}");
    }
}
