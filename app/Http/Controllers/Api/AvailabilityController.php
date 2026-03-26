<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Service;
use App\Services\AvailabilityChecker;
use App\Services\SlotGenerator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    public function __construct(
        private AvailabilityChecker $availabilityChecker,
        private SlotGenerator $slotGenerator
    ) {}

    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'service_id' => 'required|exists:services,id',
            'proposed_start' => 'required|date|after:now',
            'proposed_end' => 'required|date|after:proposed_start',
        ]);

        $provider = Provider::findOrFail($request->provider_id);
        $service = Service::findOrFail($request->service_id);
        $proposedStart = Carbon::parse($request->proposed_start);
        $proposedEnd = Carbon::parse($request->proposed_end);

        $result = $this->availabilityChecker->check(
            $provider,
            $proposedStart,
            $proposedEnd,
            $service
        );

        return response()->json($result->toArray());
    }

    public function slots(Request $request): JsonResponse
    {
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'service_id' => 'required|exists:services,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_results' => 'integer|min:1|max:50|default:10',
        ]);

        $provider = Provider::findOrFail($request->provider_id);
        $service = Service::findOrFail($request->service_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $maxResults = $request->max_results ?? 10;

        $slots = $this->slotGenerator->getAvailableSlots($provider, $startDate, $endDate, $service, $maxResults);

        return response()->json($slots->map(fn($slot) => [
            'start' => $slot['start']->toISOString(),
            'end' => $slot['end']->toISOString(),
        ]));
    }
}
