<?php
namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ProviderWorkingHour;
use App\Models\ProviderHoliday;
use Illuminate\Http\Request;


class WorkingHoursController extends Controller
{
    public function index()
    {
        $provider = auth()->user()->provider;
        abort_if(!$provider, 403);

        $workingHours = ProviderWorkingHour::where('provider_id', $provider->id)
            ->where('is_active', true)
            ->get()
            ->keyBy('day');

        $holidays = ProviderHoliday::where('provider_id', $provider->id)
            ->orderBy('holiday_date')
            ->get();

        return view('provider.working-hours.index', compact(
            'provider', 'workingHours', 'holidays'
        ));
    }

    // Save working hours
    public function updateHours(Request $request)
    {
        $provider = auth()->user()->provider;
        abort_if(!$provider, 403);

        // Delete old working hours
        ProviderWorkingHour::where('provider_id', $provider->id)->delete();

        // Save new ones
        if ($request->has('working_hours')) {
            foreach ($request->working_hours as $index => $hours) {
                if (isset($hours['is_active'])) {
                    ProviderWorkingHour::create([
                        'provider_id' => $provider->id,
                        'day_of_week' => $hours['day_of_week'],
                        'start_time'  => $hours['start_time'],
                        'end_time'    => $hours['end_time'],
                        'is_active'   => true,
                    ]);
                }
            }
        }

        return back()->with('success', 'Working hours updated successfully!');
    }

    // Add holiday
    public function storeHoliday(Request $request)
    {
        $provider = auth()->user()->provider;
        abort_if(!$provider, 403);

        $request->validate([
            'date'   => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        // Check duplicate
        $exists = ProviderHoliday::where('provider_id', $provider->id)
            ->where('holiday_date', $request->date)
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'This date is already marked as a holiday.']);
        }

        ProviderHoliday::create([
            'provider_id' => $provider->id,
            'holiday_date' => $request->date,
            'reason'       => $request->reason,
        ]);

        return back()->with('success', 'Holiday added successfully!');
    }

    // Delete holiday
    public function destroyHoliday(ProviderHoliday $holiday)
    {
        $provider = auth()->user()->provider;
        abort_if($holiday->provider_id !== $provider->id, 403);

        $holiday->delete();
        return back()->with('success', 'Holiday removed.');
    }
}

