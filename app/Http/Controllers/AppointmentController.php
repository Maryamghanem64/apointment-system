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

    public function store(CreateAppointmentRequest $request, AvailabilityChecker $checker)
    {
        return DB::transaction(function () use ($request, $checker) {
            // Availability check
            $availability = $checker->checkAvailability(
                $request->provider_id,
                $request->start_time,
                $request->duration_minutes,
                $request->service_id
            );

            if (!$availability['available']) {
                return back()->withErrors(['start_time' => $availability['messages'][array_key_first($availability['conflicts'])] ?? 'Slot unavailable']);
            }

            // Validate duration matches service
            $service = \App\Models\Service::findOrFail($request->service_id);
            $validated = $request->validated();
            $validated['client_id'] = $request->client_id; // From form
            $validated['end_time'] = \Carbon\Carbon::parse($request->start_time)->addMinutes($request->duration_minutes);
            $validated['status'] = 'pending';

            $appointment = \App\Models\Appointment::create($validated);

            // Stripe if not free
            if ($request->payment_method === 'stripe') {
                return redirect()->route('payments.checkout', $appointment);
            }

            // Free: queue email
            Mail::to($appointment->provider->user->email)->queue(new \App\Mail\NewAppointmentMail($appointment));

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment created successfully');
        });
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

    public function clientStore(CreateAppointmentRequest $request, AvailabilityChecker $checker)
    {
        $validated = $request->validated();
        $validated['client_id'] = auth()->id();
        $validated['start_time'] = $request->appointment_date ?? $validated['start_time']; // Backward compat
        $validated['client_note'] = $request->notes ?? $validated['client_note'];

        return DB::transaction(function () use ($validated, $checker) {
            // Availability check
            $availability = $checker->checkAvailability(
                $validated['provider_id'],
                $validated['start_time'],
                $validated['duration_minutes'],
                $validated['service_id']
            );

            if (!$availability['available']) {
                return back()->withErrors(['start_time' => $availability['messages'][array_key_first($availability['conflicts'])] ?? 'Slot unavailable']);
            }

            $service = \App\Models\Service::findOrFail($validated['service_id']);
            $validated['end_time'] = \Carbon\Carbon::parse($validated['start_time'])->addMinutes($validated['duration_minutes']);
            $validated['status'] = 'pending';

            $appointment = \App\Models\Appointment::create($validated);

            // Stripe if not free
            if ($validated['payment_method'] === 'stripe') {
                return redirect()->route('payments.checkout', $appointment);
            }

            // Free: queue email
            Mail::to($appointment->provider->user->email)->queue(new \App\Mail\NewAppointmentMail($appointment));

            return redirect()->route('client.appointments')
                ->with('success', 'Appointment booked successfully! Waiting for provider confirmation.');
        });
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

