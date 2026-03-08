@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="font-heading text-2xl font-bold text-white">Appointments</h1>
                    <p class="text-white/50 mt-2 text-sm">Manage all appointments</p>
                </div>
                <a href="{{ route('appointments.create') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/25">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Create Appointment') }}
                </a>
            </div>

            <div class="glass-card rounded-2xl p-6">
                @if($appointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Client') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Provider') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Service') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Date & Time') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($appointments as $appointment)
                                    <tr class="hover:bg-white/10 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-blue-500/20 flex items-center justify-center mr-3">
                                                    <span class="text-sm font-medium text-blue-400">
                                                        {{ strtoupper(substr($appointment->client->name ?? 'N/A', 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div class="text-sm font-medium text-white">{{ $appointment->client->name ?? 'N/A' }}</div>
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
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('M d, Y h:i A') }}
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
                                                $statusClass = $statusClasses[$appointment->status] ?? 'bg-slate-500/20 text-slate-400 border border-slate-500/30';
                                            @endphp
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-400 hover:text-rose-300 font-medium" onclick="return confirm('Are you sure you want to delete this appointment?')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
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
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-white">{{ __('No appointments found') }}</h3>
                        <p class="mt-1 text-sm text-white/50">{{ __('Get started by creating a new appointment.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('appointments.create') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/25">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Create Appointment') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
