<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHolidayRequest;
use App\Http\Requests\UpdateHolidayRequest;
use App\Models\Provider;
use App\Models\ProviderHoliday;
use App\Services\AvailabilityChecker;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProviderHolidaysController extends Controller
{
    protected AvailabilityChecker $availabilityChecker;

    public function __construct(AvailabilityChecker $availabilityChecker)
    {
        $this->availabilityChecker = $availabilityChecker;
        $this->middleware('auth');
        $this->middleware('role:admin|provider');
    }

    /**
     * Display provider holidays calendar view (Web)
     */
    public function index(Request $request, Provider $provider = null)
    {
        $provider = $provider ?? Auth::user()->providers()->first();
        
        if (!$provider) {
            abort(404, 'No provider found');
        }

        $holidays = ProviderHoliday::where('provider_id', $provider->id)
            ->with('provider.user')
            ->get()
            ->groupBy(fn($holiday) => $holiday->holiday_date->format('Y-m'));

        return view('provider.holidays.index', compact('provider', 'holidays'));
    }

    /**
     * Store new holiday (Web + API)
     */
    public function store(CreateHolidayRequest $request, Provider $provider = null): JsonResponse
    {
        $provider = $provider ?? Auth::user()->providers()->first();
        
        if (!$provider) {
            return response()->json(['error' => 'Provider not found'], 404);
        }

        // Check conflicts with existing appointments/working hours
        $availability = $this->availabilityChecker->checkAvailability(
            $provider,
            $request->holiday_date,
            $request->duration ?? 60
        );

        if (!$availability->isAvailable) {
            return response()->json(['error' => 'Conflict with existing appointments'], 422);
        }

        $holiday = ProviderHoliday::create([
            'provider_id' => $provider->id,
            'holiday_date' => $request->holiday_date,
            'reason' => $request->reason,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Holiday created successfully',
            'holiday' => $holiday->load('provider.user')
        ]);
    }

    /**
     * Update holiday
     */
    public function update(UpdateHolidayRequest $request, ProviderHoliday $holiday): JsonResponse
    {
        $this->authorize('update', $holiday);

        $holiday->update($request->validated());

        return response()->json([
            'message' => 'Holiday updated successfully',
            'holiday' => $holiday->fresh()->load('provider.user')
        ]);
    }

    /**
     * Remove holiday
     */
    public function destroy(ProviderHoliday $holiday): JsonResponse
    {
        $this->authorize('delete', $holiday);

        $holiday->delete();

        return response()->json([
            'message' => 'Holiday deleted successfully'
        ]);
    }

    /**
     * API: Get upcoming holidays for calendar
     */
    public function upcoming(Provider $provider, Request $request): JsonResponse
    {
        $holidays = ProviderHoliday::where('provider_id', $provider->id)
            ->where('holiday_date', '>=', now())
            ->orderBy('holiday_date')
            ->limit(30)
            ->get();

        return response()->json($holidays);
    }
}

