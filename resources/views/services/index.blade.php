@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="font-heading text-2xl font-bold text-white">Services</h1>
                    <p class="text-white/50 mt-2 text-sm">Manage your services</p>
                </div>
                <a href="{{ route('services.create') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/25">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Add New Service') }}
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

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('ID') }}</th>
<th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('Duration') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('Price') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('Providers') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($services as $service)
                                <tr class="hover:bg-white/10 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-white/60">{{ $service->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-white">{{ $service->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                                            {{ $service->duration }} min
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-emerald-400">
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
                                        <a href="{{ route('services.edit', $service->id) }}" class="text-cyan-400 hover:text-cyan-300 mr-4 font-medium">{{ __('Edit') }}</a>
                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-300 font-medium" onclick="return confirm('Are you sure?')">{{ __('Delete') }}</button>
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
