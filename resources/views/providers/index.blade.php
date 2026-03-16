@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="font-heading text-2xl font-bold text-white">Providers</h1>
                    <p class="text-white/50 mt-2 text-sm">Manage service providers</p>
                </div>
                <a href="{{ route('providers.create') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/25">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Add Provider') }}
                </a>
            </div>

@if(session('success'))
                <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-500/20 border border-rose-500/30 text-rose-300 px-4 py-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Search bar --}}
            <div class="mb-6 flex flex-col md:flex-row gap-3">
                <input
                    type="text"
                    id="tableSearch"
                    placeholder="Search..."
                    class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 w-full md:max-w-sm">
            </div>

            <script>
            document.getElementById('tableSearch').addEventListener('input', function() {
                const search = this.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(search) ? '' : 'none';
                });
            });
            </script>

            <div class="glass-card rounded-2xl p-6">

                @if($providers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Provider') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Services') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Working Hours') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Holidays') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($providers as $provider)
                                    <tr class="hover:bg-white/10 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-12 w-12 rounded-full bg-blue-500/20 flex items-center justify-center mr-3">
                                                    <span class="text-sm font-bold text-blue-400">
                                                        {{ strtoupper(substr($provider->user->name ?? 'N/A', 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-white">{{ $provider->user->name ?? 'N/A' }}</div>
                                                    <div class="text-sm text-white/60">{{ $provider->user->email ?? 'N/A' }}</div>
                                                    @if($provider->user->phone ?? false)
                                                        <div class="text-xs text-white/40">{{ $provider->user->phone }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($provider->services->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($provider->services->take(3) as $service)
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                                                            {{ $service->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($provider->services->count() > 3)
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-white/10 text-white/60">
                                                            +{{ $provider->services->count() - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-sm text-white/40">No services</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($provider->workingHours->count() > 0)
                                                <div class="text-sm text-white">
                                                    <div class="font-medium">{{ $provider->workingHours->pluck('day_of_week')->unique()->count() }} day(s)</div>
                                                </div>
                                            @else
                                                <span class="text-sm text-white/40">Not set</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($provider->holidays->count() > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                                    {{ $provider->holidays->count() }}
                                                </span>
                                            @else
                                                <span class="text-sm text-white/40">0</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('providers.edit', $provider->id) }}" class="text-cyan-400 hover:text-cyan-300 mr-4 font-medium">
                                                {{ __('Edit') }}
                                            </a>
                                            <form action="{{ route('providers.destroy', $provider->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-400 hover:text-rose-300 font-medium" onclick="return confirm('Are you sure you want to delete this provider?')">
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
                        {{ $providers->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-white">{{ __('No providers found') }}</h3>
                        <p class="mt-1 text-sm text-white/50">{{ __('Get started by adding a new provider.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('providers.create') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/25">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Add Provider') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
