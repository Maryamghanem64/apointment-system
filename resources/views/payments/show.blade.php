@extends('layouts.dark-app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10 bg-gradient-to-br from-slate-900 via-purple-900/10 to-slate-900">
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 w-full max-w-lg">

        <h2 style="font-family:'Syne',sans-serif;" class="text-2xl font-bold text-white mb-1">
            Complete Payment
        </h2>
        <p class="text-white/50 text-sm mb-8">
            Secure payment powered by Stripe
        </p>

        {{-- Appointment Summary --}}
        <div class="bg-white/5 border border-white/10 rounded-xl p-6 mb-8 space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-white/50 text-sm">Service</span>
                <span class="text-white font-medium">
                    {{ $appointment->service->name ?? 'Service' }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-white/50 text-sm">Provider</span>
                <span class="text-white font-medium">
                    {{ $appointment->provider->user->name ?? 'Provider' }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-white/50 text-sm">Date</span>
                <span class="text-white font-medium">
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('M d, Y') }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-white/50 text-sm">Time</span>
                <span class="text-white font-medium">
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                </span>
            </div>
            <div class="border-t border-white/10 pt-4 flex justify-between items-center">
                <span class="text-white/50 text-lg font-medium">Total</span>
                <span class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">
                    ${{ number_format($appointment->service->price ?? 50, 2) }}
                </span>
            </div>
        </div>

        {{-- Pay Button --}}
        <form method="POST" action="{{ route('payments.checkout', $appointment) }}">
            @csrf
            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold text-lg rounded-xl px-6 py-4 shadow-lg hover:shadow-xl hover:shadow-cyan-500/25 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Pay with Stripe Securely
            </button>
        </form>

        {{-- Cancel --}}
        <a href="{{ route('client.appointments') }}" class="block text-center mt-6 text-white/40 hover:text-white text-sm font-medium transition-colors duration-200">
            ← Cancel and return to appointments
        </a>

        {{-- Trust badge --}}
        <div class="flex items-center justify-center gap-2 mt-8 p-4 bg-white/5 border border-white/10 rounded-xl">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="text-white/60 text-xs">
                Secured by Stripe • Your data stays safe
            </p>
        </div>
    </div>
</div>
@endsection
