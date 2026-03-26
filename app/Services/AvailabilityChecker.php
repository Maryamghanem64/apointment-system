<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Provider;
use App\Models\ProviderHoliday;
use App\Models\ProviderWorkingHour;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AvailabilityChecker
{
    private const BUFFER_MINUTES = 15;
    private const MAX_DAILY_APPOINTMENTS = 8;

    public function checkAvailability(int $providerId, string $startTime, ?int $durationMinutes = null, ?int $serviceId = null): array
    {
        $start = Carbon::parse($startTime);
        $end = $durationMinutes ? $start->copy()->addMinutes($durationMinutes) : $start->copy()->addMinutes(60);

        $provider = Provider::with(['workingHours', 'holidays'])->findOrFail($providerId);
        $service = $serviceId ? Service::find($serviceId) : null;
        $conflicts = [];
        $suggestedSlots = [];

        // 1. Service duration match
        if ($service && $durationMinutes && $service->duration !== $durationMinutes) {
            $conflicts[] = 'service_duration_mismatch';
        }

        // 2. Holidays
        $isHoliday = $provider->holidays()
            ->where('holiday_date', $start->toDateString())
            ->exists();
        if ($isHoliday) {
            $conflicts[] = 'holiday';
        }

        // 3. Working hours
        $dayName = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'][$start->dayOfWeek];
        $workingHour = $provider->workingHours->firstWhere('day_of_week', $dayName);
        if (!$workingHour || !$workingHour->isWorking($start)) {
            $conflicts[] = 'working_hours';
        }

        // 4. Overlapping appointments (+ buffer)
        $overlap = Appointment::where('provider_id', $providerId)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->whereColumn('end_time', '>', DB::raw("TIMESTAMP('$start')"))
                      ->whereColumn('start_time', '<', DB::raw("TIMESTAMP('$end')"));
                });
            })
            ->exists();
        if ($overlap) {
            $conflicts[] = 'overlap';
        }

        // 5. Daily capacity
        $dailyCount = Appointment::where('provider_id', $providerId)
            ->whereDate('start_time', $start->toDateString())
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->count();
        if ($dailyCount >= self::MAX_DAILY_APPOINTMENTS) {
            $conflicts[] = 'daily_capacity';
        }

        $available = empty($conflicts);

        // Suggested slots (next 3 available in next 24h)
        if (!$available) {
            $suggestedSlots = $this->findSuggestedSlots($providerId, $start, $service?->duration ?? 60, 3);
        }

        return [
            'available' => $available,
            'conflicts' => $conflicts,
            'start_time' => $start->toISOString(),
            'end_time' => $end->toISOString(),
            'suggested_slots' => $suggestedSlots,
            'messages' => $this->getConflictMessages($conflicts),
        ];
    }

    // Use model method
    // private function withinWorkingHours removed - use $hour->isWorking($start) && !$hour->conflictsWith($start, $end)

    private function findSuggestedSlots(int $providerId, Carbon $preferredStart, int $duration, int $count): array
    {
        $slots = [];
        $searchStart = $preferredStart->copy()->addHour();

        for ($i = 0; $i < 24 && count($slots) < $count; $i += 1) { // Hourly increments
            $testStart = $searchStart->copy()->addHour($i);
            $result = $this->checkAvailability($providerId, $testStart->toISOString(), $duration);
            if ($result['available']) {
                $slots[] = $testStart->toISOString();
            }
        }

        return $slots;
    }

    private function getConflictMessages(array $conflicts): array
    {
        return [
            'working_hours' => 'Provider unavailable during this time. Check working hours.',
            'holiday' => 'Provider is on holiday on this date.',
            'overlap' => 'Time slot overlaps with another appointment.',
            'daily_capacity' => 'Provider has reached daily appointment limit (8).',
            'service_duration_mismatch' => 'Requested duration does not match service duration.',
        ];
    }
}

