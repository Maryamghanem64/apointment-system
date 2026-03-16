<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        $appointment = Appointment::create($validated);
        
        // Set status to pending
        $appointment->update(['status' => 'pending']);

        // Send email to provider
        $provider = $appointment->provider->user;
        Mail::to($provider->email)->send(
            new \App\Mail\NewAppointmentMail($appointment)
        );

        return redirect()->route('appointments.index')
            ->with('success','Appointment created successfully');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success','Appointment deleted successfully');
    }

    public function accept(Appointment $appointment)
    {
        abort_if($appointment->provider->user_id !== auth()->id(), 403);
        $appointment->update(['status' => 'confirmed']);

        // Notify client by email
        Mail::to($appointment->client->email)->send(
            new \App\Mail\AppointmentConfirmedMail($appointment)
        );

        return back()->with('success', 'Appointment confirmed.');
    }

    public function reject(Appointment $appointment)
    {
        abort_if($appointment->provider->user_id !== auth()->id(), 403);
        $appointment->update(['status' => 'cancelled']);

        // Notify client by email
        Mail::to($appointment->client->email)->send(
            new \App\Mail\AppointmentCancelledMail($appointment)
        );

        return back()->with('success', 'Appointment rejected.');
    }

    public function complete(Appointment $appointment)
    {
        abort_if($appointment->provider->user_id !== auth()->id(), 403);
        abort_if($appointment->payment?->status !== 'paid', 403);

        $appointment->update(['status' => 'completed']);

        // Notify client
        Mail::to($appointment->client->email)->send(
            new \App\Mail\AppointmentCompletedMail($appointment)
        );

        return back()->with('success', 'Appointment marked as completed.');
    }
}

