@extends('layouts.dark-app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-12 w-full max-w-md text-center">

        {{-- Cancel Icon --}}
        <div class="w-24 h-24 bg-rose-500/20 border-2 border-rose-500/30 rounded-3xl flex items-center justify-center mx-auto mb-8">
            <svg class="w-12 h-12 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <h2 style="font-family:'Syne',sans-serif;" class="text-3xl font-bold text-white mb-4">
            Payment Cancelled
        </h2>
        <p class="text-white/60 text-lg mb-8">
            No charges were made. Your appointment is still <span class="font-semibold text-yellow-400">pending</span>.
        </p>

        <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-6 mb-8">
            <p class="text-white/80 text-sm mb-2">You can try again anytime:</p>
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

        <div class="space-y-3">
            <a href="{{ route('payments.show', $appointment) }}" class="w-full block bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold rounded-xl px-8 py-4 shadow-lg hover:shadow-xl hover:shadow-cyan-500/25 hover:-translate-y-1 transition-all duration-300">
                Try Payment Again
            </a>
            <a href="{{ route('client.appointments') }}" class="w-full block bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold rounded-xl px-8 py-4 transition-all duration-300">
                Back to Appointments
            </a>
        </div>

    </div>
</div>
@endsection
