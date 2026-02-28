<?php

namespace App\Http\Controllers;

use App\Models\ProviderHoliday;
use Illuminate\Http\Request;

class ProviderHolidaysController extends Controller
{public function index()
{
    $holidays = ProviderHoliday::with('provider.user')
        ->paginate(10);

    return view('holidays.index', compact('holidays'));
}

public function create()
{
    $providers = Provider::with('user')->get();
    return view('holidays.create', compact('providers'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'provider_id' => 'required|exists:providers,id',
        'holiday_date' => 'required|date',
        'reason' => 'nullable|string|max:255',
    ]);

    ProviderHoliday::create($validated);

    return redirect()->route('holidays.index')
        ->with('success','Holiday added successfully');
}

public function edit(ProviderHoliday $holiday)
{
    $providers = Provider::with('user')->get();
    return view('holidays.edit', compact('holiday','providers'));
}

public function update(Request $request, ProviderHoliday $holiday)
{
    $validated = $request->validate([
        'provider_id' => 'required|exists:providers,id',
        'holiday_date' => 'required|date',
        'reason' => 'nullable|string|max:255',
    ]);

    $holiday->update($validated);

    return redirect()->route('holidays.index')
        ->with('success','Holiday updated successfully');
}

public function destroy(ProviderHoliday $holiday)
{
    $holiday->delete();

    return redirect()->route('holidays.index')
        ->with('success','Holiday deleted successfully');
}
}