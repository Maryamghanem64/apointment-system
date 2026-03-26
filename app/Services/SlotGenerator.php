<?php

namespace App\Services;

use App\DTOs\AvailabilityResult;
use App\Models\Provider;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SlotGenerator
{
    private AvailabilityChecker $availabilityChecker;
    private const SLOT_INTERVAL_MINUTES = 15;
    private const BREAK_MINUTES = 15;
    private const BUFFER_MINUTES = 30;

    public function __construct(AvailabilityChecker $availabilityChecker)
    {
        $this->availabilityChecker = $availabilityChecker;
    }

    /**
     * Generate available slots for provider in date range.
     */
    public function getAvailableSlots(
        Provider $provider,
        Carbon $startDate,
        Carbon $endDate,
        Service $service,
        int $maxResults = 10
    ): Collection {
        $cacheKey = "provider:slots:{$provider->id}:{$startDate->toDateString()}:{$endDate->toDateString()}:{$service->id}";
        
        return Cache::remember($cacheKey, 15 * 60, function () use ($provider, $startDate, $endDate, $service, $maxResults) {
            $slots = collect();

            $period = CarbonPeriod::create($startDate, $endDate);

            foreach ($period as $date) {
                if (count($slots) >= $maxResults) {
                    break;
                }

                $daySlots = $this->generateDaySlots($provider, $date, $service);
                $slots = $slots->merge($daySlots)->take($maxResults);
            }

            return $slots->sortBy(fn($slot) => $slot['start'])->take($maxResults);
        });
    }

    private function generateDaySlots(Provider $provider, Carbon $date, Service $service): Collection
    {
        $slots = collect();
        $duration = $service->duration;
        $endTime = $date->copy()->addDay();

        // Get working hours for this day
        $workingHours = $provider->workingHours()
            ->where('day_of_week', $date->dayOfWeek)
            ->where('is_active', true)
            ->get();

        foreach ($workingHours as $hour) {
            $dayStart = $date->copy()->setTimeFromTimeString($hour->start_time);
            $dayEnd = $date->copy()->setTimeFromTimeString($hour->end_time);

            if ($dayEnd->lt($dayStart)) {
                $dayEnd->addDay();
            }

            $current = $dayStart->copy();

            while ($current->lt($dayEnd) && $slots->count() < 50) { // Limit per day
                $slotEnd = $current->copy()->addMinutes($duration);
                
                if ($slotEnd->gt($dayEnd)) {
                    break;
                }

                // Check breaks/buffer implicitly via availability checker
                $result = $this->availabilityChecker->check(
                    $provider,
                    $current,
                    $slotEnd,
                    $service
                );

                if ($result->isAvailable) {
                    $slots->push([
                        'start' => $current->clone(),
                        'end' => $slotEnd->clone(),
                    ]);
                }

                // Advance by interval + break
                $current->addMinutes(self::SLOT_INTERVAL_MINUTES + self::BREAK_MINUTES);
            }
        }

        return $slots->slice(0, 20); // Top 20 per day
    }
}
