@extends('layouts.dark-app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-12 w-full max-w-md text-center">

        {{-- Success Icon --}}
        <div class="w-24 h-24 bg-emerald-500/20 border-2 border-emerald-500/30 rounded-3xl flex items-center justify-center mx-auto mb-8">
            <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h2 style="font-family:'Syne',sans-serif;" class="text-3xl font-bold text-white mb-4">
            Payment Successful!
        </h2>
        <p class="text-white/60 text-lg mb-2">
            Your appointment has been confirmed
        </p>
        <p class="text-emerald-400 font-semibold text-lg mb-8">
            ${{ number_format($appointment->service->price ?? 50, 2) }} paid successfully
        </p>

        <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-6 mb-8">
            <p class="text-white/80 text-sm mb-2">Appointment Details:</p>
            <div class="text-left space-y-1 text-sm">
                <div class="flex justify-between">
                    <span class="text-white/60">Service:</span>
                    <span class="font-medium">{{ $appointment->service->name ?? 'Service' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">Provider:</span>
                    <span class="font-medium">{{ $appointment->provider->user->name ?? 'Provider' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">Date:</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($appointment->start_time)->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <a href="{{ route('client.dashboard') }}" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-xl px-8 py-4 shadow-lg hover:shadow-xl hover:shadow-emerald-500/25 hover:-translate-y-1 transition-all duration-300 inline-block text-lg">
            ← Back to Dashboard
        </a>

        <p class="text-white/30 text-xs mt-6">
            You will receive confirmation email shortly
        </p>
    </div>
</div>
@endsection
