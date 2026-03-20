@extends('layouts.dark-app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        
        {{-- Page Header --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white mb-2">Provider Applications</h1>
                <p class="text-white/50">Review and manage provider applications</p>
            </div>
            <div class="glass-card px-6 py-3">
                <div class="flex items-center gap-6 text-sm">
                    <div>
                        <div class="text-2xl font-bold text-cyan-400">{{ $stats['pending'] }}</div>
                        <div class="text-white/50">Pending</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-400">{{ $stats['approved'] }}</div>
                        <div class="text-white/50">Approved</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-rose-400">{{ $stats['rejected'] }}</div>
                        <div class="text-white/50">Rejected</div>
                    </div>
                    <div class="text-sm font-medium text-white/60">Total: {{ $stats['pending'] + $stats['approved'] + $stats['rejected'] }}</div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
        <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Applications Table --}}
        <div class="glass-card rounded-2xl overflow-hidden">
            @if($applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Applicant</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Specialty</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white/60 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-white/60 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach($applications as $application)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/30 to-cyan-400/30 flex items-center justify-center font-medium text-white">
                                        {{ strtoupper(substr($application->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $application->full_name }}</div>
                                        @if($application->user)
                                        <div class="text-white/50 text-sm">{{ $application->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-white/10 rounded-full text-xs font-medium text-cyan-200">
                                    {{ $application->specialty }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-white/70">{{ $application->email }}</td>
                            <td class="px-6 py-4">
                                <span class="text-white/50 text-sm">{{ $application->phone ?: '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-white/60">
                                    {{ $application->created_at->format('M d, Y') }}<br>
                                    <span class="text-white/40">{{ $application->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'pending' => ['label' => 'Pending Review', 'color' => 'amber', 'bg' => 'amber-500/20', 'border' => 'amber-500/30'],
                                        'approved' => ['label' => 'Approved', 'color' => 'emerald', 'bg' => 'emerald-500/20', 'border' => 'emerald-500/30'],
                                        'rejected' => ['label' => 'Rejected', 'color' => 'rose', 'bg' => 'rose-500/20', 'border' => 'rose-500/30'],
                                    ];
                                    $status = $statusConfig[$application->status] ?? $statusConfig['pending'];
                                @endphp
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full text-{{ $status['color'] }}-400 bg-{{ $status['bg'] }} border border-{{ $status['border'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($application->status === 'pending')
                                <form method="POST" action="{{ route('admin.applications.approve', $application) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-1.5 bg-emerald-500/90 hover:bg-emerald-600 text-white text-xs font-semibold rounded-lg transition-all duration-200 whitespace-nowrap">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.applications.reject', $application) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-1.5 bg-rose-500/90 hover:bg-rose-600 text-white text-xs font-semibold rounded-lg transition-all duration-200 whitespace-nowrap ml-1">
                                        Reject
                                    </button>
                                </form>
                                @else
                                <span class="text-white/40 text-xs font-medium uppercase tracking-wide">Complete</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-white/10 bg-white/5">
                {{ $applications->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-white/20 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">No Applications Yet</h3>
                <p class="text-white/50 mb-6">Be the first provider to join our platform.</p>
                <a href="{{ route('provider.application.create') }}" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Preview Application Form
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

