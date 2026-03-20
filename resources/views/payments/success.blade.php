@extends('layouts.dark-app')
@section('content')

<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-lg">

        {{-- Success Card --}}
        <div class="relative bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-10 text-center overflow-hidden">

            {{-- Background glow --}}
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full opacity-20 pointer-events-none"
                 style="background: radial-gradient(circle, #22d3ee, transparent);">
            </div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full opacity-20 pointer-events-none"
                 style="background: radial-gradient(circle, #10b981, transparent);">
            </div>

            {{-- Animated success icon --}}
            <div class="relative mx-auto mb-6 w-24 h-24">
                <div class="absolute inset-0 bg-emerald-500/20 rounded-full animate-ping opacity-30"></div>
                <div class="relative w-24 h-24 bg-emerald-500/20 border-2 border-emerald-500/50 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            {{-- Title --}}
            <h1 style="font-family:'Syne',sans-serif;
                       background: linear-gradient(135deg, #ffffff, #22d3ee);
                       -webkit-background-clip: text;
                       -webkit-text-fill-color: transparent;"
                class="text-3xl font-bold mb-2">
                Payment Successful!
            </h1>
            <p class="text-white/50 text-sm mb-8">
                Your appointment has been confirmed and payment received.
            </p>

            {{-- Appointment Details Card --}}
            <div class="bg-white/5 border border-white/10 rounded-xl p-5 mb-8 text-left space-y-3">

                <div class="flex items-center justify-between">
                    <span class="text-white/40 text-xs uppercase tracking-wider">Service</span>
                    <span class="text-white text-sm font-medium">
                        {{ $appointment->service->name ?? 'Service' }}
                    </span>
                </div>

                <div class="border-t border-white/5"></div>

                <div class="flex items-center justify-between">
                    <span class="text-white/40 text-xs uppercase tracking-wider">Provider</span>
                    <span class="text-white text-sm font-medium">
                        {{ $appointment->provider->user->name ?? 'Provider' }}
                    </span>
                </div>

                <div class="border-t border-white/5"></div>

                <div class="flex items-center justify-between">
                    <span class="text-white/40 text-xs uppercase tracking-wider">Date & Time</span>
                    <span class="text-white text-sm font-medium">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y — h:i A') }}
                    </span>
                </div>

                <div class="border-t border-white/5"></div>

                <div class="flex items-center justify-between">
                    <span class="text-white/40 text-xs uppercase tracking-wider">Status</span>
                    <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full px-3 py-0.5 text-xs font-medium">
                        ✓ Confirmed & Paid
                    </span>
                </div>
            </div>

            {{-- Auto redirect notice --}}
            <p class="text-white/30 text-xs mb-6">
                Redirecting to your appointments in
                <span id="countdown" class="text-cyan-400 font-bold">5</span> seconds...
            </p>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('client.appointments') }}"
                   class="flex-1 bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-6 py-3 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300 text-sm text-center">
                    View My Appointments
                </a>
                <a href="{{ route('client.dashboard') }}"
                   class="flex-1 bg-white/5 border border-white/10 text-white/70 font-medium rounded-xl px-6 py-3 hover:bg-white/10 hover:text-white transition-all duration-300 text-sm text-center">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Auto redirect JS --}}
<script>
let seconds = 5;
const countdown = document.getElementById('countdown');

const timer = setInterval(function() {
    seconds--;
    if (countdown) countdown.textContent = seconds;
    if (seconds <= 0) {
        clearInterval(timer);
        window.location.href = "{{ route('client.appointments') }}";
    }
}, 1000);
</script>

@endsection
