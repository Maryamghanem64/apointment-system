<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\BookingService;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('client','provider.user','service')
            ->latest()
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $clients = User::whereHas('roles', function($query) {
            $query->where('name', 'client');
        })->get();
        
        $providers = Provider::with('user')->get();
        $services = Service::all();

        return view('appointments.create', compact('clients','providers','services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'provider_id' => 'required|exists:providers,id',
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        $validated['end_time'] = now()
            ->parse($validated['start_time'])
            ->addMinutes($service->duration);

        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success','Appointment created successfully');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success','Appointment deleted successfully');
    }
}
