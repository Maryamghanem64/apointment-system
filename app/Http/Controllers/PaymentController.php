<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
{
    $payments = Payment::with('appointment')->paginate(10);
    return view('payments.index', compact('payments'));
}

public function update(Request $request, Payment $payment)
{
    $validated = $request->validate([
        'status' => 'required|in:unpaid,paid,refunded',
    ]);

    $payment->update($validated);

    return redirect()->route('payments.index')
        ->with('success','Payment updated successfully');
}}