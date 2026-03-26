@extends('layouts.dark-app')

@section('title', 'My Services - Provider Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-white">My Services</h1>
<a href="{{ route('provider.services.create') }}"class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white px-6 py-2 rounded-xl hover:shadow-lg transition-all">
              >  + Add New Service
            </a>
        </div>

        @if (session('success'))
            <div class="bg-cyan-500/20 border border-cyan-500/40 text-cyan-400 p-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-500/20 border border-rose-500/40 text-rose-400 p-4 rounded-xl mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="glass-card rounded-xl p-8">
            @if($services->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="text-left py-4 text-white/60 font-medium">Service Name</th>
                                <th class="text-left py-4 text-white/60 font-medium">Price</th>
                                <th class="text-left py-4 text-white/60 font-medium">Duration</th>
                                <th class="text-left py-4 text-white/60 font-medium w-48">Description</th>
                                <th class="text-left py-4 text-white/60 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($services as $service)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-4 font-medium text-white">{{ $service->name }}</td>
                                    <td class="py-4 text-white/80">${{ number_format($service->price, 2) }}</td>
                                    <td class="py-4 text-white/80">{{ $service->duration }} min</td>
                                    <td class="py-4 text-white/60 max-w-xs truncate">{{ Str::limit($service->description ?? '', 60) }}</td>
                                    <td class="py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('provider.services.edit', $service) }}" class="text-cyan-400 hover:text-cyan-300 p-2 -m-2 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.5h3m1 1v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h6.5z"/>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('provider.services.destroy', $service) }}" class="inline" onsubmit="return confirm('Delete this service?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-400 hover:text-rose-300 p-2 -m-2 rounded-lg transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $services->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-white/30 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white mb-2">No Services Yet</h3>
                    <p class="text-white/50 mb-4">Get started by adding your first service.</p>
                    <a href="{{ route('provider.services.create') }}" class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white px-5 py-2 rounded-xl hover:shadow-lg transition-all">
                        + Add First Service
                    </a>
                </div>

            @endif
        </div>
    </div>
</div>
@endsection
