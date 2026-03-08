@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Admin Dashboard</h1>
                <p class="text-white/50 mt-2">Overview of your appointment system</p>
            </div>

            <!-- Main Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
                <!-- Total Users -->
                <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-blue-500/20 p-3">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">{{ __('Total Users') }}</dt>
                                <dd class="text-2xl font-bold text-white">{{ number_format($totalUsers) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Total Providers -->
                <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-blue-500/20 p-3">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">{{ __('Providers') }}</dt>
                                <dd class="text-2xl font-bold text-white">{{ number_format($totalProviders) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Total Services -->
                <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-blue-500/20 p-3">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">{{ __('Services') }}</dt>
                                <dd class="text-2xl font-bold text-white">{{ number_format($totalServices) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Total Appointments -->
                <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-blue-500/20 p-3">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">{{ __('Appointments') }}</dt>
                                <dd class="text-2xl font-bold text-white">{{ number_format($totalAppointments) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Total Payments -->
                <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-blue-500/20 p-3">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">{{ __('Payments') }}</dt>
                                <dd class="text-2xl font-bold text-white">{{ number_format($totalPayments) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Total Reviews -->
                <div class="glass-card rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-cyan-500/20 p-3">
                                <svg class="h-6 w-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">{{ __('Reviews') }}</dt>
                                <dd class="text-2xl font-bold text-white">{{ number_format($totalReviews) }}</dd>
                            </dl>
                        </div>
                        @if($pendingReviews > 0)
                        <div class="ml-2">
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-amber-400 bg-amber-500/20 rounded-full">
                                {{ $pendingReviews }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics by Status -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Appointment Status Statistics -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">{{ __('Appointment Statistics') }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Pending -->
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-amber-400">{{ __('Pending') }}</p>
                                    <p class="text-2xl font-bold text-white">{{ number_format($appointmentStats['pending'] ?? 0) }}</p>
                                </div>
                                <div class="bg-amber-500/20 p-3 rounded-xl">
                                    <svg class="h-6 w-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Confirmed -->
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-cyan-400">{{ __('Confirmed') }}</p>
                                    <p class="text-2xl font-bold text-white">{{ number_format($appointmentStats['confirmed'] ?? 0) }}</p>
                                </div>
                                <div class="bg-cyan-500/20 p-3 rounded-xl">
                                    <svg class="h-6 w-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Completed -->
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-emerald-400">{{ __('Completed') }}</p>
                                    <p class="text-2xl font-bold text-white">{{ number_format($appointmentStats['completed'] ?? 0) }}</p>
                                </div>
                                <div class="bg-emerald-500/20 p-3 rounded-xl">
                                    <svg class="h-6 w-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Cancelled -->
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-rose-400">{{ __('Cancelled') }}</p>
                                    <p class="text-2xl font-bold text-white">{{ number_format($appointmentStats['cancelled'] ?? 0) }}</p>
                                </div>
                                <div class="bg-rose-500/20 p-3 rounded-xl">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status Statistics -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">{{ __('Payment Statistics') }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Paid -->
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-emerald-400">{{ __('Paid') }}</p>
                                    <p class="text-2xl font-bold text-white">{{ number_format($paymentStats['paid'] ?? 0) }}</p>
                                </div>
                                <div class="bg-emerald-500/20 p-3 rounded-xl">
                                    <svg class="h-6 w-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Unpaid -->
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-rose-400">{{ __('Unpaid') }}</p>
                                    <p class="text-2xl font-bold text-white">{{ number_format($paymentStats['unpaid'] ?? 0) }}</p>
                                </div>
                                <div class="bg-rose-500/20 p-3 rounded-xl">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Refunded -->
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10 col-span-2">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-400">{{ __('Refunded') }}</p>
                                    <p class="text-2xl font-bold text-white">{{ number_format($paymentStats['refunded'] ?? 0) }}</p>
                                </div>
                                <div class="bg-slate-500/20 p-3 rounded-xl">
                                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('users.index') }}" class="flex items-center justify-center bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl hover:shadow-cyan-500/25 transition-all duration-300 hover:-translate-y-1">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    {{ __('Manage Users') }}
                </a>
                <a href="{{ route('providers.index') }}" class="flex items-center justify-center bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl hover:shadow-cyan-500/25 transition-all duration-300 hover:-translate-y-1">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Manage Providers') }}
                </a>
                <a href="{{ route('appointments.index') }}" class="flex items-center justify-center bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl hover:shadow-cyan-500/25 transition-all duration-300 hover:-translate-y-1">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('View Appointments') }}
                </a>
            </div>

            <!-- Recent Appointments -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">{{ __('Recent Appointments') }}</h3>
                    <a href="{{ route('appointments.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>
                
                @if($recentAppointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('Client') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('Provider') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('Service') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('Date & Time') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($recentAppointments as $appointment)
                                    <tr class="hover:bg-white/10 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-400">
                                                        {{ substr($appointment->client->name ?? 'N/A', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-white">{{ $appointment->client->name ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white/80">{{ $appointment->provider->user->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white/80">{{ $appointment->service->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white/60">
                                                {{ $appointment->start_time ? $appointment->start_time->format('M d, Y H:i') : 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
                                                    'confirmed' => 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30',
                                                    'pending' => 'bg-amber-500/20 text-amber-400 border border-amber-500/30',
                                                    'cancelled' => 'bg-rose-500/20 text-rose-400 border border-rose-500/30',
                                                ];
                                                $statusClass = $statusClasses[$appointment->status] ?? $statusClasses['pending'];
                                            @endphp
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($appointment->status ?? 'pending') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-white/50">{{ __('No appointments yet.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
