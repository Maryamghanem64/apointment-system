@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
<h1 class="font-heading text-3xl font-bold text-white mb-8">Payments Dashboard</h1>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-card p-6 rounded-2xl text-center">
        <div class="text-3xl font-bold text-emerald-400">${{ number_format($stats['total'] ?? 0, 2) }}</div>
        <div class="text-white/60 text-sm mt-1">Total Revenue</div>
    </div>
    <div class="glass-card p-6 rounded-2xl text-center">
        <div class="text-3xl font-bold text-blue-400">{{ $stats['paid'] ?? 0 }}</div>
        <div class="text-white/60 text-sm mt-1">Paid</div>
    </div>
    <div class="glass-card p-6 rounded-2xl text-center">
        <div class="text-3xl font-bold text-amber-400">{{ $stats['pending'] ?? 0 }}</div>
        <div class="text-white/60 text-sm mt-1">Pending</div>
    </div>
    <div class="glass-card p-6 rounded-2xl text-center">
        <div class="text-3xl font-bold text-rose-400">{{ $stats['failed'] ?? 0 }}</div>
        <div class="text-white/60 text-sm mt-1">Failed</div>
    </div>
</div>
                <p class="text-white/50 mt-2 text-sm">View and manage payment records</p>
            </div>

            <div class="glass-card rounded-2xl p-6">
                @if($payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">Provider</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">Stripe ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
@foreach($payments as $payment)
                                    <tr class="hover:bg-white/10 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">#{{ $payment->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-white">
                                            {{ $payment->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-white/90">
                                            {{ $payment->appointment->service->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/80">
                                            {{ $payment->appointment->provider->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-emerald-400">
                                            ${{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'paid' => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
                                                    'pending' => 'bg-amber-500/20 text-amber-400 border border-amber-500/30',
                                                    'failed' => 'bg-rose-500/20 text-rose-400 border border-rose-500/30',
                                                    'refunded' => 'bg-slate-500/20 text-slate-400 border border-slate-500/30',
                                                    'unpaid' => 'bg-gray-500/20 text-gray-400 border border-gray-500/30',
                                                ];
                                                $statusClass = $statusClasses[$payment->status] ?? 'bg-slate-500/20 text-slate-400 border border-slate-500/30';
                                            @endphp
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/50 text-xs font-mono">
                                            {{ Str::limit($payment->stripe_session_id ?? 'N/A', 12, '...') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">
                                            {{ $payment->created_at->format('Y-m-d H:i') }}
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
