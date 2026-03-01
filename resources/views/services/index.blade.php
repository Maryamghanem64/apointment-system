@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="font-heading text-3xl font-bold text-white">Services</h1>
                    <p class="text-white/60 mt-2">Manage your services</p>
                </div>
                <a href="{{ route('services.create') }}" class="btn-primary inline-flex items-center text-white font-semibold py-3 px-6 rounded-xl">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Add New Service') }}
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="glass-card rounded-xl p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('ID') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Duration') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Price') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Providers') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($services as $service)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-white/60">{{ $service->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-white">{{ $service->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                            {{ $service->duration }} min
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-400">
                                        ${{ number_format($service->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @forelse($service->providers as $provider)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-white/10 text-white/80 border border-white/10 mr-1 mb-1">
                                                {{ $provider->user->name ?? 'N/A' }}
                                            </span>
                                        @empty
                                            <span class="text-white/40 text-sm">No providers</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('services.edit', $service->id) }}" class="text-cyan-400 hover:text-cyan-300 mr-4">{{ __('Edit') }}</a>
                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure?')">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-white/50">{{ __('No services found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    {{ $services->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
