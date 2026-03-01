@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <h3 class="font-heading text-2xl font-bold text-white">{{ __('Welcome back, ') }} {{ Auth::user()->name }}!</h3>
                <p class="text-white/60 mt-2">{{ __('Manage your appointments and book new services.') }}</p>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Upcoming Appointments') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $upcomingAppointments->count() }}</div>
                </div>
                
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Past Appointments') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $pastAppointments }}</div>
                </div>
                
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Completed Services') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $totalSpent }}</div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <a href="{{ route('client.appointments') }}" class="btn-primary text-white font-semibold py-4 px-6 rounded-xl text-center">
                    {{ __('View My Appointments') }}
                </a>
                <a href="{{ route('client.profile') }}" class="btn-secondary text-white font-semibold py-4 px-6 rounded-xl text-center border border-white/20">
                    {{ __('My Profile') }}
                </a>
            </div>
            
            <!-- Upcoming Appointments -->
            <div class="glass-card rounded-xl p-6">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">{{ __('Upcoming Appointments') }}</h3>
                
                @if($upcomingAppointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Provider') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Service') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Date & Time') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($upcomingAppointments as $appointment)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            {{ $appointment->provider->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/80">
                                            {{ $appointment->service->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-green-500/20 text-green-400 border border-green-500/30',
                                                    'confirmed' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
                                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
                                                    'cancelled' => 'bg-red-500/20 text-red-400 border border-red-500/30',
                                                ];
                                                $statusClass = $statusClasses[$appointment->status] ?? 'bg-gray-500/20 text-gray-400 border border-gray-500/30';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $appointment->status ?? 'pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-white/50">{{ __('No upcoming appointments.') }}</p>
                    <a href="{{ route('services.index') }}" class="mt-2 inline-block text-cyan-400 hover:text-cyan-300">
                        {{ __('Browse Services') }} →
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
