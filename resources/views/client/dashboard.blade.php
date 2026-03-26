{{-- resources/views/client/dashboard.blade.php --}}
@extends('layouts.dark-app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    {{-- Section 1 — Hero Welcome with Glow --}}
    <div class="relative bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 mb-6 overflow-hidden">
        {{-- Background glow --}}
        <div class="absolute -top-10 -right-10 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 style="font-family:'Syne',sans-serif;" class="flex items-center gap-2 text-3xl font-bold text-white mb-2">
                    <svg class="w-8 h-8 flex-shrink-0 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                @if($nextAppointment ?? false)
                    <p class="text-white/60 text-sm">
                        Your next appointment is on
                        <span class="text-cyan-400 font-medium">
                            {{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('M d, Y \a\t h:i A') }}
                        </span>
                    </p>
                @else
                    <p class="text-white/60 text-sm">
                        You have no upcoming appointments.
                        <a href="#browse-providers"
                           class="text-cyan-400 hover:text-cyan-300 transition">
                            Book one now →
                        </a>
                    </p>
                @endif
            </div>

            <a href="#browse-providers"
               class="shrink-0 bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-6 py-3 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300 text-sm">
               <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Book Appointment
            </a>
        </div>
    </div>

    {{-- Section 2 — Stats Row (4 cards) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
            $stats = [
                ['label' => 'Upcoming', 'value' => $upcomingCount ?? 0, 'color' => 'blue', 'icon' => 'calendar'],
                ['label' => 'Pending', 'value' => $pendingCount ?? 0, 'color' => 'amber', 'icon' => 'clock'],
                ['label' => 'Completed', 'value' => $completedCount ?? 0, 'color' => 'emerald', 'icon' => 'check'],
                ['label' => 'Cancelled', 'value' => $cancelledCount ?? 0, 'color' => 'rose', 'icon' => 'x-mark'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-5 hover:-translate-y-1 hover:border-white/20 transition-all duration-300">
            <svg class="w-8 h-8 text-{{ $stat['color'] }}-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if($stat['icon'] === 'calendar')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/>
                @elseif($stat['icon'] === 'clock')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                @elseif($stat['icon'] === 'check')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 0 0 0 6.364L12 20.364l7.682-7.682a4.5 4.5 0 0 0-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 0 0-6.364 0z"/>
                @elseif($stat['icon'] === 'x-mark')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                @endif
            </svg>
            <div class="text-3xl font-bold text-{{ $stat['color'] }}-400 mb-1"
                 style="font-family:'Syne',sans-serif;">
                {{ $stat['value'] }}
            </div>
            <div class="text-white/40 text-xs uppercase tracking-wider">
                {{ $stat['label'] }}
            </div>
        </div>
        @endforeach
    </div>

    {{-- Section 3 — Quick Actions Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('client.appointments.create') ?? '#' }}"
           class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30 rounded-2xl p-5 hover:border-cyan-400/60 hover:-translate-y-1 transition-all duration-300 group text-center">
            <svg class="w-6 h-6 text-blue-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <div class="text-white font-semibold text-sm group-hover:text-cyan-400 transition">
                Book Appointment
            </div>
            <div class="text-white/30 text-xs mt-1">Find a provider</div>
        </a>

        <a href="{{ route('client.appointments') ?? '#' }}"
           class="bg-white/5 border border-white/10 rounded-2xl p-5 hover:border-white/30 hover:-translate-y-1 transition-all duration-300 group text-center">
            <svg class="w-6 h-6 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <div class="text-white font-semibold text-sm group-hover:text-cyan-400 transition">
                My Appointments
            </div>
            <div class="text-white/30 text-xs mt-1">View history</div>
        </a>

        <a href="{{ route('profile.edit') ?? '#' }}"
           class="bg-white/5 border border-white/10 rounded-2xl p-5 hover:border-white/30 hover:-translate-y-1 transition-all duration-300 group text-center">
            <svg class="w-6 h-6 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.5h3m1 4H9a3 3 0 01-3-3V9a3 3 0 013-3h7a3 3 0 013 3v13a3 3 0 01-3 3z"/></svg>
            <div class="text-white font-semibold text-sm group-hover:text-cyan-400 transition">
                My Profile
            </div>
            <div class="text-white/30 text-xs mt-1">Edit your info</div>
        </a>

        <a href="{{ route('client.settings') ?? '#' }}"
           class="bg-white/5 border border-white/10 rounded-2xl p-5 hover:border-white/30 hover:-translate-y-1 transition-all duration-300 group text-center">
            <svg class="w-6 h-6 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <div class="text-white font-semibold text-sm group-hover:text-cyan-400 transition">
                Settings
            </div>
            <div class="text-white/30 text-xs mt-1">Preferences</div>
        </a>
    </div>

    {{-- Section 4 — Next Appointment Card --}}
    @if($nextAppointment ?? false)
    <div class="bg-white/5 backdrop-blur-md border border-cyan-500/20 rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 style="font-family:'Syne',sans-serif;" class="flex items-center gap-2 text-lg font-bold text-white">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Next Appointment
            </h3>
            <span class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 rounded-full px-3 py-1 text-xs">
                Coming Up
            </span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold text-xl">
                    {{ strtoupper(substr($nextAppointment->provider->user->name ?? 'P', 0, 1)) }}
                </div>
                <div>
                    <p class="text-white font-semibold">
                        {{ $nextAppointment->provider->user->name ?? 'Provider' }}
                    </p>
                    <p class="text-cyan-400 text-sm">
                        {{ $nextAppointment->service->name ?? 'Service' }}
                    </p>
                    <p class="text-white/40 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('M d, Y — h:i A') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                @php
                    $statusColors = [
                        'pending'   => 'amber',
                        'confirmed' => 'emerald',  
                        'completed' => 'cyan',
                        'cancelled' => 'rose',
                    ];
                    $color = $statusColors[$nextAppointment->status] ?? 'white';
                @endphp

                <span class="bg-{{ $color }}-500/20 text-{{ $color }}-400 border border-{{ $color }}-500/30 rounded-full px-3 py-1 text-xs capitalize">
                    {{ $nextAppointment->status }}
                </span>

                @if($nextAppointment->status === 'confirmed' && !$nextAppointment->payment)
                    <a href="{{ route('payments.show', $nextAppointment) }}"
                       class="flex items-center gap-1.5 bg-gradient-to-r from-blue-500 to-cyan-400 text-white text-xs font-medium rounded-xl px-4 py-2 hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> Pay Now
                    </a>
                @elseif($nextAppointment->payment?->status === 'paid')
                    <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl px-3 py-1.5 text-xs">
                        ✓ Paid
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Section 5 — Appointment History Timeline --}}
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between mb-5">
            <h3 style="font-family:'Syne',sans-serif;" class="flex items-center gap-2 text-lg font-bold text-white">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg> My Appointment History
            </h3>
            <a href="{{ route('client.appointments') ?? '#' }}"
               class="text-cyan-400 text-sm hover:text-cyan-300 transition">
                View all →
            </a>
        </div>

        @forelse($recentAppointments ?? [] as $appointment)
        <div class="flex items-center gap-4 py-3 border-b border-white/5 last:border-0 hover:bg-white/5 rounded-xl px-3 transition-all duration-300">

            {{-- Status dot --}}
            @php
                $dotColor = match($appointment->status) {
                    'completed' => 'bg-emerald-400',
                    'confirmed' => 'bg-cyan-400',
                    'pending'   => 'bg-amber-400',
                    'cancelled' => 'bg-rose-400',
                    default     => 'bg-white/20',
                };
            @endphp
            <div class="w-2 h-2 rounded-full {{ $dotColor }} shrink-0"></div>

            {{-- Info --}}
            <div class="flex-1">
                <p class="text-white text-sm font-medium">
                    {{ $appointment->service->name ?? 'Service' }}
                </p>
                <p class="text-white/40 text-xs">
                    {{ $appointment->provider->user->name ?? 'Provider' }}
                </p>
            </div>

            {{-- Date --}}
            <div class="text-right">
                <p class="text-white/60 text-xs">
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                </p>
                <span class="text-{{ $dotColor === 'bg-emerald-400' ? 'emerald' : ($dotColor === 'bg-rose-400' ? 'rose' : 'amber') }}-400 text-xs capitalize">
                    {{ $appointment->status }}
                </span>
            </div>

            {{-- Actions --}}
            @if($appointment->status === 'completed' && !$appointment->review)
                <a href="{{ route('reviews.create', $appointment) ?? '#' }}"
                   class="bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-lg px-2 py-1 text-xs hover:bg-amber-500/30 transition shrink-0">
                    ★ Review
                </a>
            @endif
        </div>
        @empty
        <div class="text-center py-8">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <p class="text-white/30 text-sm">No appointments yet.</p>
            <a href="#browse-providers"
               class="text-cyan-400 text-sm hover:text-cyan-300 transition mt-2 inline-block">
                Book your first appointment →
            </a>
        </div>
        @endforelse
    </div>

    {{-- Section 6 — Browse Providers (with Search) --}}
    <div id="browse-providers"
         class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 mb-6">

        <div class="flex items-center justify-between mb-4">
            <h3 style="font-family:'Syne',sans-serif;" class="flex items-center gap-2 text-lg font-bold text-white">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.601 10.601z"/></svg> Find a Provider
            </h3>
        </div>

        {{-- Search bar --}}
        <div class="relative mb-5">
            <input type="text"
                id="providerSearch"
                placeholder="Search by name or service..."
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 pl-10 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300">
            <svg class="absolute left-3 top-3.5 w-4 h-4 text-white/30"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="providers-grid">
            @forelse($providers ?? [] as $provider)
            <div class="provider-card bg-white/5 border border-white/10 rounded-xl p-4 hover:border-cyan-400/30 hover:-translate-y-1 transition-all duration-300"
                 data-name="{{ strtolower($provider->user->name ?? '') }}"
                 data-service="{{ strtolower($provider->services->pluck('name')->implode(' ')) }}">

                {{-- Provider header --}}
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($provider->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-white text-sm font-semibold">
                            {{ $provider->user->name ?? 'Provider' }}
                        </p>
                        <div class="flex items-center gap-1 mt-0.5">
                            @php $avg = round($provider->reviews->avg('rating') ?? 0, 1); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $avg ? 'text-cyan-400' : 'text-white/20' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                            @endfor
                            <span class="text-white/40 text-xs ml-1">({{ $provider->reviews->count() }})</span>
                        </div>
                    </div>
                </div>

                {{-- Services --}}
                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach($provider->services->take(2) as $service)
                    <span class="bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-full px-2 py-0.5 text-xs">
                        {{ $service->name }}
                    </span>
                    @endforeach
                </div>

                {{-- Book button --}}
                <a href="{{ route('client.appointments.create') }}?provider={{ $provider->id }}"
                   class="block w-full text-center bg-gradient-to-r from-blue-500 to-cyan-400 text-white text-xs font-medium rounded-xl px-3 py-2.5 hover:shadow-lg hover:shadow-cyan-500/20 hover:-translate-y-0.5 transition-all duration-300">
                    Book Now
                </a>
            </div>
            @empty
            <div class="col-span-3 text-center py-8">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.601 10.601z"/></svg>
                <p class="text-white/30 text-sm">No providers available yet.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Provider search JS --}}
    <script>
    document.getElementById('providerSearch').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        document.querySelectorAll('.provider-card').forEach(card => {
            const name = card.dataset.name;
            const service = card.dataset.service;
            card.style.display = (name.includes(search) || service.includes(search)) ? '' : 'none';
        });
    });
    </script>

    {{-- Section 7 — My Reviews --}}
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 mb-6">
            <h3 style="font-family:'Syne',sans-serif;" class="flex items-center gap-2 text-lg font-bold text-white mb-4">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg> My Reviews
        </h3>

        @forelse($myReviews ?? [] as $review)
        <div class="flex items-start gap-4 py-4 border-b border-white/5 last:border-0">
                <div class="flex gap-0.5 shrink-0">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-cyan-400' : 'text-white/20' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    @endfor
                </div>
            <div class="flex-1">
                <p class="text-white/70 text-sm leading-relaxed">
                    {{ $review->comment ?? 'No comment' }}
                </p>
                <p class="text-white/30 text-xs mt-1">
                    {{ $review->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
            <p class="text-white/30 text-sm">No reviews yet.</p>
            <p class="text-white/20 text-xs mt-1">Complete an appointment to leave a review!</p>
        </div>
        @endforelse
    </div>

    {{-- Become a Provider Section - Only for clients --}}
    @if(auth()->user()->hasRole('client') && !auth()->user()->provider)
    <div class="mb-6">
        @include('partials.client.become-provider')
    </div>
    @endif

    {{-- Section 8 — Rate Your Experience (Platform Reviews - Preserve Existing) --}}
    {{-- Note: Add any existing platform review section here if present --}}
    
</div>
  
        {{-- ===== PLATFORM REVIEW SECTION ===== --}}
        <div class="mt-10 mb-10 flex flex-col items-center text-center">

        {{-- Section Header --}}
        <div class="mb-6">
            <h2 style="font-family:'Syne',sans-serif;"
                class="text-2xl font-bold text-white mb-1">
                Rate Your Experience
            </h2>
            <p class="text-white/50 text-sm">
                Help us improve by sharing your feedback about Schedora
            </p>
        </div>

@php
            $existingReview = \App\Models\Review::where('user_id', auth()->id())
                ->where('review_type', 'platform')
                ->first();
        @endphp

        {{-- FORM — only if no review yet --}}
        @if(!$existingReview)
<div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 w-full max-w-2xl mx-auto transition-all duration-300"
>
            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
            <div class="mb-6 bg-rose-500/20 border border-rose-500/30 text-rose-400 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                <span>✕</span>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="review_type" value="platform">

                {{-- Star Rating --}}
                <div class="mb-6">
                    <label class="block text-white/60 text-sm font-medium mb-3">
                        Your Rating <span class="text-rose-400">*</span>
                    </label>
<div class="flex gap-3 justify-center" id="platform-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button"
                                    class="star-btn w-12 h-12 text-white/20 transition-all duration-200 cursor-pointer hover:scale-110 hover:text-cyan-400 flex items-center justify-center"
                                    data-value="{{ $i }}">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                </button>
                            @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="">
                    @error('rating')
                        <p class="text-rose-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comment --}}
                <div class="mb-6">
                    <label class="block text-white/60 text-sm font-medium mb-2">
                        Your Comment
                        <span class="text-white/30 font-normal">(optional)</span>
                    </label>
                    <textarea
                        name="comment"
                        rows="4"
                        maxlength="500"
                        placeholder="Share your experience with Schedora..."
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 resize-none"
                    >{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-rose-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-8 py-3 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>

        {{-- EXISTING REVIEW — show if already reviewed --}}
        @else
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 max-w-2xl">
            <p class="text-white/40 text-xs uppercase tracking-wider mb-4">
                Your Review
            </p>

            {{-- Stars --}}
            <div class="flex gap-1 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-8 h-8 {{ $i <= $existingReview->rating ? 'text-cyan-400' : 'text-white/20' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                @endfor
            </div>

            {{-- Comment --}}
            @if($existingReview->comment)
            <p class="text-white/70 text-sm leading-relaxed mb-3">
                "{{ $existingReview->comment }}"
            </p>
            @endif

            {{-- Date --}}
            <p class="text-white/30 text-xs">
                Submitted {{ $existingReview->created_at->diffForHumans() }}
            </p>

            {{-- Approval badge --}}
            @if($existingReview->is_approved)
            <span class="inline-flex items-center gap-1 mt-3 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full px-3 py-1 text-xs">
                ✓ Approved
            </span>
            @else
            <span class="inline-flex items-center gap-1 mt-3 bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-full px-3 py-1 text-xs">
                ⏳ Pending Approval
            </span>
            @endif
        </div>
        @endif
    </div></div> 

    {{-- Star Rating JS --}}
    <script>
    (function() {
        const stars = document.querySelectorAll('#platform-stars .star-btn');
        const input = document.getElementById('rating-input');
        if (!stars.length) return;

        stars.forEach((star, index) => {
            // hover
            star.addEventListener('mouseover', () => {
                stars.forEach((s, i) => {
                    s.style.color = i <= index ? '#22d3ee' : 'rgba(255,255,255,0.2)';
                    s.style.transform = i <= index ? 'scale(1.1)' : 'scale(1)';
                });
            });
            // mouseout
            star.addEventListener('mouseout', () => {
                const val = parseInt(input.value) || 0;
                stars.forEach((s, i) => {
                    s.style.color = i < val ? '#22d3ee' : 'rgba(255,255,255,0.2)';
                    s.style.transform = 'scale(1)';
                });
            });
            // click
            star.addEventListener('click', () => {
                input.value = star.dataset.value;
                stars.forEach((s, i) => {
                    s.style.color = i <= index ? '#22d3ee' : 'rgba(255,255,255,0.2)';
                });
            });
        });
    })();
    </script>
@endsection
