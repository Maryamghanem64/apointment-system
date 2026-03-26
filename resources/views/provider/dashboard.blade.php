@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <h3 class="font-heading text-2xl font-bold text-white">{{ __('Welcome back, ') }} {{ Auth::user()->name }}!</h3>
                <p class="text-white/60 mt-2">{{ __('Manage your appointments and schedule.') }}</p>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Today\'s Appointments') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $todayAppointments->count() }}</div>
                </div>
                
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Total Appointments') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $totalAppointments }}</div>
                </div>
                
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Completed') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $completedAppointments }}</div>
                </div>
                
                <!-- Average Rating -->
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Average Rating') }}</div>
                    <div class="flex items-center mt-1">
                        <div class="text-3xl font-bold text-white">{{ number_format($averageRating, 1) }}</div>
                        <div class="flex ml-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($averageRating))
                                    <span class="text-cyan-400 text-xl">★</span>
                                @else
                                    <span class="text-white/20 text-xl">★</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="text-sm text-white/40 mt-1">{{ __('Based on ') . $totalReviews . __(' reviews') }}</div>
                </div>
            </div>
            
            <!-- My Reviews Section -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">{{ __('My Reviews') }}</h3>
                
                @if($reviews->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($reviews as $review)
                            <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-500/20 flex items-center justify-center mr-2">
                                            <span class="text-sm font-medium text-blue-400">
                                                {{ substr($review->user->name ?? 'U', 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="text-white font-medium">{{ $review->user->name ?? 'Anonymous' }}</div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <span class="text-cyan-400 text-lg">★</span>
                                            @else
                                                <span class="text-white/20 text-lg">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-white/60 text-sm mb-2">{{ Str::limit($review->comment, 100) }}</p>
                                @endif
                                <div class="text-xs text-white/40">{{ $review->created_at->format('M d, Y') }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-white/50">{{ __('No reviews yet.') }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Today's Appointments -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">{{ __('Today\'s Appointments') }}</h3>
                
                @if($todayAppointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Client') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Service') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Time') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($todayAppointments as $appointment)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            {{ $appointment->client->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/80">
                                            {{ $appointment->service->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
                                                    'confirmed' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
                                                    'pending' => 'bg-amber-500/20 text-amber-400 border border-amber-500/30',
                                                    'cancelled' => 'bg-rose-500/20 text-rose-400 border border-rose-500/30',
                                                ];
                                                $statusClass = $statusClasses[$appointment->status] ?? 'bg-slate-500/20 text-slate-400 border border-slate-500/30';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($appointment->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($appointment->status === 'pending')
                                                <div class="flex gap-1">
                                                    <form method="POST" action="{{ route('appointments.accept', $appointment) }}" style="display: inline;">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium p-1 rounded transition-colors">✓</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('appointments.reject', $appointment) }}" style="display: inline;">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-medium p-1 rounded transition-colors">✕</button>
                                                    </form>
                                                </div>
                                            @elseif($appointment->status === 'confirmed' && $appointment->payment && $appointment->payment->status === 'paid')
                                                <form method="POST" action="{{ route('appointments.complete', $appointment) }}" style="display: inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-cyan-400 hover:text-cyan-300 text-xs font-medium p-1 rounded transition-colors">Complete</button>
                                                </form>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-white/50">{{ __('No appointments scheduled for today.') }}</p>
                @endif
            </div>
            
            <!-- Upcoming Appointments -->
            <div class="glass-card rounded-xl p-6">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">{{ __('Upcoming Appointments') }}</h3>
                
                @if($upcomingAppointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Client') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Service') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Date & Time') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($upcomingAppointments as $appointment)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            {{ $appointment->client->name ?? 'N/A' }}
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
                                                    'completed' => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
                                                    'confirmed' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
                                                    'pending' => 'bg-amber-500/20 text-amber-400 border border-amber-500/30',
                                                    'cancelled' => 'bg-rose-500/20 text-rose-400 border border-rose-500/30',
                                                ];
                                                $statusClass = $statusClasses[$appointment->status] ?? 'bg-slate-500/20 text-slate-400 border border-slate-500/30';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($appointment->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($appointment->status === 'pending')
                                                <div class="flex gap-1">
                                                    <form method="POST" action="{{ route('appointments.accept', $appointment) }}" style="display: inline;">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium p-1 rounded transition-colors">✓</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('appointments.reject', $appointment) }}" style="display: inline;">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-medium p-1 rounded transition-colors">✕</button>
                                                    </form>
                                                </div>
                                            @elseif($appointment->status === 'confirmed' && $appointment->payment && $appointment->payment->status === 'paid')
                                                <form method="POST" action="{{ route('appointments.complete', $appointment) }}" style="display: inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-cyan-400 hover:text-cyan-300 text-xs font-medium p-1 rounded transition-colors">Complete</button>
                                                </form>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-white/50">{{ __('No upcoming appointments.') }}</p>
                @endif
            </div>
            </div>
        </div>

        <!-- Optimized My Services Preview - Compacted -->
        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-xl p-6">
                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-6">
                    <div>
                        <h3 class="font-heading text-xl font-bold text-white mb-1">My Services</h3>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-cyan-500/20 text-cyan-400 text-xs font-medium rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4l-8-4"/>
                            </svg>
                            {{ $services->count() }} {{ Str::plural('service', $services->count()) }}
                        </span>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">

                    <a href="{{ route('provider.working-hours.index') }}" class="flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-cyan-400 hover:text-cyan-300 px-4 py-2 rounded-lg font-medium transition-all group hover:shadow-md hover:shadow-cyan-500/20">
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Hours
                    </a>
                    <a href="{{ route('provider.services.index') }}" class="flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white px-5 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        View All
                    </a>
                </div>
            </div>


            @if($services->count() > 0)
                <!-- Services Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($services->take(4) as $service)
                        <div class="group relative overflow-hidden rounded-2xl p-6 glass-card hover:-translate-y-2 hover:shadow-2xl hover:shadow-cyan-500/25 transition-all duration-500 bg-gradient-to-br from-white/5 via-transparent to-white/2 border border-white/20 backdrop-blur-xl">
                            <!-- Service Icon -->
                            <div class="absolute -top-6 -right-6 w-16 h-16 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-2xl flex items-center justify-center border border-cyan-500/30 shadow-lg group-hover:scale-110 transition-all duration-300">
                                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>

                            <!-- Status Badge -->
                            <div class="inline-flex items-center gap-1 px-3 py-1 bg-cyan-500/20 text-cyan-400 text-xs font-semibold rounded-full border border-cyan-500/30 mb-4">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Bookable
                            </div>

                            <!-- Content -->
                            <div class="space-y-3">
                                <h4 class="font-semibold text-white text-lg leading-tight line-clamp-2 group-hover:text-cyan-300 transition-colors">{{ $service->name }}</h4>
                                
                                <div class="flex items-center gap-4 text-sm">
                                    <div class="flex items-center gap-1 text-cyan-400 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        {{ $service->duration }} min
                                    </div>
                                    <div class="flex items-center gap-1 text-white/70">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08 .402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        ${{ number_format($service->price, 2) }}
                                    </div>
                                </div>

                                @if($service->description)
                                    <p class="text-white/60 text-sm leading-relaxed line-clamp-2">{{ $service->description }}</p>
                                @endif
                            </div>

                            <!-- Quick Action Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6 pointer-events-none">
                                <a href="{{ route('provider.services.edit', $service) }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white px-4 py-2 rounded-xl font-medium shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 pointer-events-auto opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.5h3m1 1v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h6.5z"/>
                                    </svg>
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($services->count() > 4)
                    <div class="mt-8 text-center">
                        <a href="{{ route('provider.services.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white px-8 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            View All Services
                        </a>
                    </div>
                @endif
            @else
                <!-- Compact Empty State -->
                <div class="flex flex-col items-center text-center py-12 px-6">
                    <div class="relative">
                        <svg class="w-16 h-16 text-white/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4l-8-4"/>
                        </svg>
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 rounded-full blur animate-pulse opacity-60"></div>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">No Services Yet</h4>
                    <p class="text-base text-white/60 mb-6 max-w-sm leading-relaxed">Add your first service to get started</p>
                    <a href="{{ route('provider.services.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        + Add First Service
                    </a>
                    <p class="text-xs text-white/40 mt-4">Takes less than 2 minutes</p>
                </div>

            @endif
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
                            class="star-btn text-5xl text-white/20 transition-all duration-200 cursor-pointer hover:scale-110"
                            data-value="{{ $i }}">
                            ★
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
                    <span class="text-3xl {{ $i <= $existingReview->rating ? 'text-cyan-400' : 'text-white/20' }}">
                        ★
                    </span>
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
