@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="font-heading text-3xl font-bold text-white">Appointments</h1>
                    <p class="text-white/60 mt-2">Manage all appointments</p>
                </div>
                <a href="{{ route('appointments.create') }}" class="btn-primary inline-flex items-center text-white font-semibold py-3 px-6 rounded-xl">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Create Appointment') }}
                </a>
            </div>

            <div class="glass-card rounded-xl p-6">
                @if($appointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">
                                        {{ __('Client') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">
                                        {{ __('Provider') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">
                                        {{ __('Service') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">
                                        {{ __('Date & Time') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($appointments as $appointment)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-indigo-500/20 flex items-center justify-center mr-3">
                                                    <span class="text-sm font-medium text-indigo-400">
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
                                                    'completed' => 'bg-green-500/20 text-green-400 border border-green-500/30',
                                                    'confirmed' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
                                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
                                                    'cancelled' => 'bg-red-500/20 text-red-400 border border-red-500/30',
                                                ];
                                                $statusClass = $statusClasses[$appointment->status] ?? 'bg-gray-500/20 text-gray-400 border border-gray-500/30';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this appointment?')">
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
                            <a href="{{ route('appointments.create') }}" class="btn-primary inline-flex items-center text-white font-semibold py-3 px-6 rounded-xl">
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
