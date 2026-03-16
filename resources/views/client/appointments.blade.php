@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">My Appointments</h1>
                <p class="text-white/60 mt-2">View all your appointments</p>
            </div>
            
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="glass-card rounded-xl p-6">
                @if($appointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Provider') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Service') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Time') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($appointments as $appointment)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            {{ $appointment->provider->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/80">
                                            {{ $appointment->service->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($appointment->status === 'completed' && !$appointment->review)
                                                <a href="{{ route('reviews.create', $appointment->id) }}" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white text-xs font-semibold rounded-lg transition-all duration-300">
                                                    Leave Review
                                                </a>
                                            @elseif($appointment->payment?->status === 'paid')
                                                <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-lg px-3 py-1.5 text-xs font-medium">
                                                    ✓ Paid
                                                </span>
                                            @elseif($appointment->status === 'confirmed' && !$appointment->payment)
                                                <a href="{{ route('payments.show', $appointment) }}" class="bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white text-xs font-semibold rounded-lg px-3 py-1.5 hover:shadow-lg hover:shadow-cyan-500/20 transition-all duration-300 inline-flex items-center">
                                                    Pay Now
                                                </a>
                                            @elseif($appointment->review)
                                                <span class="text-white/40 text-xs">Reviewed</span>
                                            @else
                                                <span class="text-white/40 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $appointments->links() }}
                    </div>
                @else
                    <p class="text-white/50 text-center py-8">{{ __('No appointments found.') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
