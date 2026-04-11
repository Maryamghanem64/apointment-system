<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CreateAppointmentRequest;
use App\Services\AvailabilityChecker;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('client','provider.user','service')
            ->latest();

        if (auth()->user()->hasRole('provider')) {
            $provider = auth()->user()->provider;
            if (!$provider) {
                abort(403, 'Provider profile not found.');
            }
            $appointments = $appointments->where('provider_id', $provider->id);
        }

        $appointments = $appointments->paginate(10);

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
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'service_id'  => 'required|exists:services,id',
            'start_time'  => 'required|date|after:now',
            'end_time'    => 'required|date|after:start_time',
            'client_note' => 'nullable|string|max:500',
        ]);

        // Check slot not already taken
        $conflict = \App\Models\Appointment::where('provider_id', $request->provider_id)
            ->where('status', '!=', 'cancelled')
            ->where(function($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'start_time' => 'This time slot is already booked. Please choose another time.'
            ])->withInput();
        }

        $appointment = \App\Models\Appointment::create([
            'client_id'   => auth()->id(),
            'provider_id' => $request->provider_id,
            'service_id'  => $request->service_id,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'status'      => 'pending',
            'client_note' => $request->client_note,
        ]);

        // Send email to provider
        try {
            \Mail::to($appointment->provider->user->email)->send(
                new \App\Mail\NewAppointmentMail($appointment)
            );
        } catch (\Exception $e) {
            \Log::error('Email failed: ' . $e->getMessage());
        }

        return redirect()->route('client.appointments')
            ->with('success', 'Appointment booked! Waiting for provider confirmation.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success','Appointment deleted successfully');
    }

    public function accept(Appointment $appointment)
    {
        $provider = auth()->user()->provider;
        abort_if(!$provider, 403, 'Provider profile not found.');
        abort_if($appointment->provider_id !== $provider->id, 403, 'You are not authorized to manage this appointment.');

        $appointment->update(['status' => 'confirmed']);

        // Notify client by email
        Mail::to($appointment->client->email)->send(
            new \App\Mail\AppointmentConfirmedMail($appointment)
        );

        return back()->with('success', 'Appointment confirmed.');
    }

    public function reject(Appointment $appointment)
    {
        $provider = auth()->user()->provider;
        abort_if(!$provider, 403, 'Provider profile not found.');
        abort_if($appointment->provider_id !== $provider->id, 403, 'You are not authorized to manage this appointment.');

        $appointment->update(['status' => 'cancelled']);

        // Notify client by email
        Mail::to($appointment->client->email)->send(
            new \App\Mail\AppointmentCancelledMail($appointment)
        );

        return back()->with('success', 'Appointment rejected.');
    }

    public function complete(Appointment $appointment)
    {
        $provider = auth()->user()->provider;
        abort_if(!$provider, 403, 'Provider profile not found.');
        abort_if($appointment->provider_id !== $provider->id, 403, 'You are not authorized to manage this appointment.');
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

    /**
     * Client booking store method
     */
    public function clientStore(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'service_id'  => 'required|exists:services,id',
            'start_time'  => 'required|date|after:now',
            'end_time'    => 'required|date|after:start_time',
            'client_note' => 'nullable|string|max:500',
        ]);

        // Check slot not already taken
        $conflict = \App\Models\Appointment::where('provider_id', $request->provider_id)
            ->where('status', '!=', 'cancelled')
            ->where(function($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'start_time' => 'This time slot is already booked. Please choose another time.'
            ])->withInput();
        }

        $appointment = \App\Models\Appointment::create([
            'client_id'   => auth()->id(),
            'provider_id' => $request->provider_id,
            'service_id'  => $request->service_id,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'status'      => 'pending',
            'client_note' => $request->client_note,
        ]);

        // Send email to provider
        try {
            \Mail::to($appointment->provider->user->email)->send(
                new \App\Mail\NewAppointmentMail($appointment)
            );
        } catch (\Exception $e) {
            \Log::error('Email failed: ' . $e->getMessage());
        }

        return redirect()->route('client.appointments')
            ->with('success', 'Appointment booked! Waiting for provider confirmation.');
    }

    /**
     * API endpoint for frontend availability check
     */
    public function availabilityCheck(Request $request, AvailabilityChecker $checker)
    {
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
        ]);

        $availability = $checker->checkAvailability(
            $request->provider_id,
            $request->start_time,
            $request->duration_minutes,
            $request->service_id
        );

        return response()->json($availability);
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

