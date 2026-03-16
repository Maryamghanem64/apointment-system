<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show payment page for an appointment
    public function show(Appointment $appointment)
    {
        abort_if($appointment->client_id !== auth()->id(), 403);
        $payment = $appointment->payment;
        return view('payments.show', compact('appointment', 'payment'));
    }

    // Create Stripe Checkout Session
    public function checkout(Appointment $appointment)
    {
        abort_if($appointment->client_id !== auth()->id(), 403);

        Stripe::setApiKey(config('services.stripe.secret'));

        $servicePrice = $appointment->service->price ?? 50;
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Appointment - ' . ($appointment->service->name ?? 'Service'),
                        'description' => 'Provider: ' . ($appointment->provider->user->name ?? 'Provider'),
                    ],
                    'unit_amount' => $servicePrice * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payments.success') . '?session_id={CHECKOUT_SESSION_ID}&appointment=' . $appointment->id,
            'cancel_url' => route('payments.cancel') . '?appointment=' . $appointment->id,
            'metadata' => [
                'appointment_id' => $appointment->id,
                'user_id' => auth()->id(),
            ],
        ]);

        // Create pending payment record
        Payment::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'user_id' => auth()->id(),
                'amount' => $servicePrice,
                'currency' => 'usd',
                'status' => 'pending',
                'stripe_session_id' => $session->id,
            ]
        );

        return redirect($session->url);
    }

    // Success callback
    public function success(Request $request)
    {
        $session_id = $request->get('session_id');
        $appointment_id = $request->get('appointment');

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($session_id);
        $appointment = Appointment::findOrFail($appointment_id);

        if ($session->payment_status === 'paid') {
            Payment::where('stripe_session_id', $session_id)->update([
                'status' => 'paid',
                'stripe_payment_intent' => $session->payment_intent,
                'paid_at' => now(),
            ]);

            $appointment->update(['status' => 'confirmed']);
        }

        return view('payments.success', compact('appointment'));
    }

    // Cancel callback
    public function cancel(Request $request)
    {
        $appointment_id = $request->get('appointment');
        $appointment = Appointment::findOrFail($appointment_id);
        return view('payments.cancel', compact('appointment'));
    }

    // Admin — view all payments
    public function adminIndex()
    {
        $payments = Payment::with(['appointment.service', 'appointment.provider.user', 'user'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total'    => Payment::where('status', 'paid')->sum('amount'),
            'paid'     => Payment::where('status', 'paid')->count(),
            'pending'  => Payment::whereIn('status', ['pending', 'unpaid'])->count(),
            'failed'   => Payment::where('status', 'failed')->count(),
        ];

        return view('payments.index', compact('payments', 'stats'));
    }
}
