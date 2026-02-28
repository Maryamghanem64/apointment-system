<?php

namespace App\Http\Controllers;

use App\Models\ProviderWorkingHour;
use Illuminate\Http\Request;

class ProviderWorkingHoursController extends Controller
{
    public function index()
{
    $workingHours = ProviderWorkingHour::with('provider.user')
        ->paginate(10);

    return view('working_hours.index', compact('workingHours'));
}

public function create()
{
    $providers = Provider::with('user')->get();
    return view('working_hours.create', compact('providers'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'provider_id' => 'required|exists:providers,id',
        'day_of_week' => 'required|integer|min:0|max:6',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ]);

    ProviderWorkingHour::create($validated);

    return redirect()->route('working-hours.index')
        ->with('success','Working hours added successfully');
}

public function edit(ProviderWorkingHour $workingHour)
{
    $providers = Provider::with('user')->get();
    return view('working_hours.edit', compact('workingHour','providers'));
}

public function update(Request $request, ProviderWorkingHour $workingHour)
{
    $validated = $request->validate([
        'provider_id' => 'required|exists:providers,id',
        'day_of_week' => 'required|integer|min:0|max:6',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ]);

    $workingHour->update($validated);

    return redirect()->route('working-hours.index')
        ->with('success','Working hours updated successfully');
}

public function destroy(ProviderWorkingHour $workingHour)
{
    $workingHour->delete();

    return redirect()->route('working-hours.index')
        ->with('success','Working hours deleted successfully');
}
}