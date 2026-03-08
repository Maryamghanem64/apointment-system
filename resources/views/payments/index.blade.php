@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-2xl font-bold text-white">Payments</h1>
                <p class="text-white/50 mt-2 text-sm">View and manage payment records</p>
            </div>

            <div class="glass-card rounded-2xl p-6">
                @if($payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('ID') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('Appointment') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('Amount') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($payments as $payment)
                                    <tr class="hover:bg-white/10 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">#{{ $payment->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-white">
                                            {{ $payment->appointment->service->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-emerald-400">
                                            ${{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'paid' => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
                                                    'unpaid' => 'bg-rose-500/20 text-rose-400 border border-rose-500/30',
                                                    'refunded' => 'bg-slate-500/20 text-slate-400 border border-slate-500/30',
                                                ];
                                                $statusClass = $statusClasses[$payment->status] ?? 'bg-slate-500/20 text-slate-400 border border-slate-500/30';
                                            @endphp
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">
                                            {{ $payment->created_at->format('Y-m-d') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $payments->links() }}
                    </div>
                @else
                    <p class="text-white/50 text-center py-8">{{ __('No payments found.') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
