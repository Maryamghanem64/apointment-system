@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-2xl font-bold text-white">Create Provider</h1>
                <p class="text-white/50 mt-2 text-sm">Add a new service provider</p>
            </div>

            <div class="glass-card rounded-2xl p-8 max-w-2xl mx-auto">
                <form method="POST" action="{{ route('providers.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- User Selection -->
                        <div>
                            <x-input-label for="user_id" :value="__('Select User')" />
                            <select id="user_id" name="user_id" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300">
                                <option value="">{{ __('Select a user') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                        </div>

                        <!-- Services Selection -->
                        <div>
                            <x-input-label for="service_ids" :value="__('Assign Services')" />
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                @forelse($services as $service)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="service_{{ $service->id }}" 
                                               name="service_ids[]" 
                                               value="{{ $service->id }}"
                                               class="h-4 w-4 text-cyan-400 border-white/20 rounded focus:ring-cyan-400/30 bg-white/5">
                                        <label for="service_{{ $service->id }}" class="ml-2 block text-sm text-white/80">
                                            {{ $service->name }}
                                            <span class="text-white/40">({{ $service->duration }} min - ${{ number_format($service->price, 2) }})</span>
                                        </label>
                                    </div>
                                @empty
                                    <p class="col-span-2 text-sm text-white/40">No services available. Please create services first.</p>
                                @endforelse
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('service_ids')" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <x-secondary-button onclick="window.location.href='{{ route('providers.index') }}'">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            {{ __('Create Provider') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
