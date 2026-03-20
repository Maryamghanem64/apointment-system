@extends('layouts.dark-app')
@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 style="font-family:'Syne',sans-serif;"
                class="text-3xl font-bold text-white mb-1">
                My Appointments
            </h1>
            <p class="text-white/50 text-sm">
                Track and manage all your appointments
            </p>
        </div>
        <a href="{{ route('client.appointments.create') }}"
           class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-5 py-2.5 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300 text-sm">
            + Book New
        </a>
    </div>

    {{-- Success message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
        <span>✓</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Appointments list --}}
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden">

        {{-- Table header --}}
        <div class="px-6 py-4 border-b border-white/10">
            <h2 style="font-family:'Syne',sans-serif;"
                class="text-white font-semibold">
                All Appointments
            </h2>
        </div>

        @forelse($appointments as $appointment)
        <div class="px-6 py-4 border-b border-white/5 last:border-0 hover:bg-white/5 transition-all duration-300">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                {{-- Left: Provider + Service info --}}
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold shrink-0">
                        {{ strtoupper(substr($appointment->provider->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-white font-medium text-sm">
                            {{ $appointment->provider->user->name ?? 'Provider' }}
                        </p>
                        <p class="text-cyan-400 text-xs">
                            {{ $appointment->service->name ?? 'Service' }}
                        </p>
                        <p class="text-white/40 text-xs mt-0.5 flex items-center gap-1">
                            🕐 {{ \Carbon\Carbon::parse($appointment->start_time)->format('M d, Y — h:i A') }}
                        </p>
                    </div>
                </div>

                {{-- Right: Status + Actions --}}
                <div class="flex items-center gap-3 flex-wrap">

                    {{-- Status badge --}}
                    @php
                        $colors = [
                            'pending'   => 'amber',
                            'confirmed' => 'cyan',
                            'completed' => 'emerald',
                            'cancelled' => 'rose',
                        ];
                        $color = $colors[$appointment->status] ?? 'white';
                    @endphp
                    <span class="bg-{{ $color }}-500/20 text-{{ $color }}-400 border border-{{ $color }}-500/30 rounded-full px-3 py-1 text-xs capitalize font-medium">
                        {{ ucfirst($appointment->status) }}
                    </span>

                    {{-- Pay Now --}}
                    @if($appointment->status === 'confirmed' && !$appointment->payment)
                    <a href="{{ route('payments.show', $appointment) }}"
                       class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white text-xs font-medium rounded-lg px-3 py-1.5 hover:shadow-lg hover:shadow-cyan-500/20 transition-all duration-300">
                        💳 Pay Now
                    </a>
                    @elseif($appointment->payment && $appointment->payment->status === 'paid')
                    <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-lg px-3 py-1.5 text-xs">
                        ✓ Paid
                    </span>
                    @endif

                    {{-- Review --}}
                    @if($appointment->status === 'completed' && !$appointment->review)
                    <a href="{{ route('reviews.create', $appointment) }}"
                       class="bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-lg px-3 py-1.5 text-xs hover:bg-amber-500/30 transition-all duration-300">
                        ★ Review
                    </a>
                    @endif

                </div>
            </div>

            {{-- Status Timeline --}}
            @php
                $currentStep = match($appointment->status) {
                    'pending'   => 1,
                    'confirmed' => $appointment->payment?->status === 'paid' ? 3 : 2,
                    'completed' => 4,
                    default     => 1,
                };
                $steps = [1 => 'Pending', 2 => 'Confirmed', 3 => 'Paid', 4 => 'Completed'];
            @endphp
            <div class="flex items-center gap-1 mt-4 ml-15">
                @foreach($steps as $step => $label)
                <div class="flex items-center gap-1">
                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300
                        {{ $currentStep > $step ? 'bg-cyan-400 text-slate-900' :
                          ($currentStep === $step ? 'bg-cyan-400/30 text-cyan-400 border border-cyan-400' :
                          'bg-white/5 text-white/20') }}">
                        {{ $currentStep > $step ? '✓' : $step }}
                    </div>
                    <span class="text-xs hidden md:block
                        {{ $currentStep >= $step ? 'text-white/60' : 'text-white/20' }}">
                        {{ $label }}
                    </span>
                    @if($step < 4)
                    <div class="w-6 h-px {{ $currentStep > $step ? 'bg-cyan-400' : 'bg-white/10' }}"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-white/40 text-sm font-medium mb-2">No appointments yet</p>
            <p class="text-white/20 text-xs mb-6">Book your first appointment to get started</p>
            <a href="{{ route('client.appointments.create') }}"
               class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-6 py-2.5 hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300 text-sm">
                Book Now
            </a>
        </div>
        @endforelse
    </div>

</div>

@endsection

