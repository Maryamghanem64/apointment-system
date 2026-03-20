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

    public function clientCreate(Request $request)
    {
        $providers = \App\Models\Provider::with(['user', 'services', 'reviews'])
            ->get();

        $selectedProvider = null;
        if ($request->has('provider')) {
            $selectedProvider = \App\Models\Provider::with([
                'user',
                'services',
                'workingHours',
                'holidays'
            ])->find($request->provider);
        }

        $services = \App\Models\Service::all();

        return view('client.appointments.create', compact(
            'providers',
            'selectedProvider',
            'services'
        ));
    }

    public function clientStore(Request $request)
    {
        $request->validate([
            'provider_id'      => 'required|exists:providers,id',
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
        ]);

        $startTime = $request->appointment_date;

        // Check if slot is already taken
        $exists = \App\Models\Appointment::where('provider_id', $request->provider_id)
            ->where('start_time', $startTime)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'appointment_date' => 'This time slot is already booked. Please choose another time.'
            ])->withInput();
        }

        $service = \App\Models\Service::findOrFail($request->service_id);
        $endTime = now()->parse($startTime)->addMinutes($service->duration);

        $appointment = \App\Models\Appointment::create([
            'client_id'         => auth()->id(),
            'provider_id'       => $request->provider_id,
            'service_id'        => $request->service_id,
            'start_time'        => $startTime,
            'end_time'          => $endTime,
            'status'            => 'pending',
            'client_note'       => $request->notes,
        ]);

        // Send email to provider
        try {
            \Mail::to($appointment->provider->user->email)->send(
                new \App\Mail\NewAppointmentMail($appointment)
            );
        } catch (\Exception $e) {
            // fail silently
        }

        return redirect()->route('client.appointments')
            ->with('success', 'Appointment booked successfully! Waiting for provider confirmation.');
    }

    public function cancel(Appointment $appointment)
    {
        // Make sure appointment belongs to this client
        abort_if($appointment->client_id !== auth()->id(), 403);

        // Only allow cancel if pending or confirmed
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'This appointment cannot be cancelled.']);
        }

        $appointment->update(['status' => 'cancelled']);

        // Notify provider
        try {
            \Mail::to($appointment->provider->user->email)->send(
                new \App\Mail\AppointmentCancelledMail($appointment)
            );
        } catch (\Exception $e) {
            // fail silently
        }

        return back()->with('success', 'Appointment cancelled successfully.');
    }
}

