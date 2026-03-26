<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $provider = auth()->user()->provider;
        if (!$provider) {
            abort(403, 'Provider profile not found.');
        }

        $services = $provider->services()
            ->latest()
            ->paginate(10);

        return view('provider.services.index', compact('services'));
    }

    public function create()
    {
        return view('provider.services.create');
    }

    public function store(Request $request)
    {
        $provider = auth()->user()->provider;
        if (!$provider) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Create global service and auto-assign to this provider
        $service = Service::create($data);
        $provider->services()->attach($service->id);

        return redirect()->route('provider.services.index')
            ->with('success', 'Service added successfully.');
    }

    public function edit(Service $service)
    {
        $provider = auth()->user()->provider;
        if (!$provider || !$provider->services()->where('service_id', $service->id)->exists()) {
            abort(403);
        }

        return view('provider.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $provider = auth()->user()->provider;
        if (!$provider || !$provider->services()->where('service_id', $service->id)->exists()) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $service->update($data);

        return redirect()->route('provider.services.index')
            ->with('success', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $provider = auth()->user()->provider;
        if (!$provider || !$provider->services()->where('service_id', $service->id)->exists()) {
            abort(403);
        }

        // Check if service used in appointments
        if ($service->appointments()->count() > 0) {
            return redirect()->route('provider.services.index')
                ->with('error', 'Cannot delete service used in appointments.');
        }

        $provider->services()->detach($service->id);
        $service->delete();

        return redirect()->route('provider.services.index')
            ->with('success', 'Service deleted.');
    }
}

