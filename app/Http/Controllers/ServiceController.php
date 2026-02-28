<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
{
    $services = Service::paginate(10);
    return view('services.index', compact('services'));
}

public function create()
{
    return view('services.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:150',
        'duration' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
    ]);

    Service::create($validated);

    return redirect()->route('services.index')
        ->with('success','Service created successfully');
}

public function edit(Service $service)
{
    return view('services.edit', compact('service'));
}

public function update(Request $request, Service $service)
{
    $validated = $request->validate([
        'name' => 'required|string|max:150',
        'duration' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
    ]);

    $service->update($validated);

    return redirect()->route('services.index')
        ->with('success','Service updated successfully');
}

public function destroy(Service $service)
{
    $service->delete();

    return redirect()->route('services.index')
        ->with('success','Service deleted successfully');
}
}