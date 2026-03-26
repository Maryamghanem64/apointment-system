<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkingHourRequest;
use App\Http\Requests\UpdateWorkingHourRequest;
use App\Models\ProviderWorkingHour;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderWorkingHoursController extends Controller
{
    public function index(Provider $provider = null)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $workingHours = ProviderWorkingHour::with('provider.user')
                ->active()
                ->paginate(15);
        } else {
            $this->authorize('viewAny', ProviderWorkingHour::class);
            $provider = $user->provider;
            $workingHours = ProviderWorkingHour::forProvider($provider->id)
                ->with('provider')
                ->paginate(15);
        }

return view('provider.working-hours', compact('workingHours', 'provider'));
    }

// create moved to Livewire

public function store(StoreWorkingHourRequest $request)
{
    $this->authorize('create', ProviderWorkingHour::class);

    $user = Auth::user();
    $provider = $user->hasRole('admin') ? Provider::findOrFail($request->provider_id ?? $user->provider->id) : $user->provider;

    $data = $request->validated();
    $data['provider_id'] = $provider->id;
    $data['is_active'] = $data['is_active'] ?? true;

    $workingHour = ProviderWorkingHour::create($data);

    return redirect()->route('provider.working-hours.index', $provider)
        ->with('success','Working hours added successfully');
}

public function edit(ProviderWorkingHour $workingHour)
{
    $this->authorize('update', $workingHour);

    $user = Auth::user();
    $provider = $user->hasRole('admin') ? $workingHour->provider : $user->provider;

    return view('provider.working-hours.edit', compact('workingHour', 'provider'));
}

public function update(UpdateWorkingHourRequest $request, ProviderWorkingHour $workingHour)
{
    $this->authorize('update', $workingHour);

    $data = $request->validated();
    $data['is_active'] = $data['is_active'] ?? true;

    $workingHour->update($data);

    $provider = $workingHour->provider;

    return redirect()->route('provider.working-hours.index', $provider)
        ->with('success','Working hours updated successfully');
}

public function destroy(ProviderWorkingHour $workingHour)
{
    $this->authorize('delete', $workingHour);

    $workingHour->delete();

    $provider = $workingHour->provider;

    return redirect()->route('provider.working-hours.index', $provider)
        ->with('success','Working hours deleted successfully');
}
}
