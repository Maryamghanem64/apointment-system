@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Create Appointment</h1>
                <p class="text-white/60 mt-2">Schedule a new appointment</p>
            </div>

            <div class="glass-card rounded-xl p-6 sm:p-8">
                <form method="POST" action="{{ route('appointments.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- <div>
                            Client Selection -->
                        <x-input-label for="client_id" :value="__('Client')" />
                            <select id="client_id" name="client_id" class="input-dark mt-1 block w-full rounded-xl">
                                <option value="">{{ __('Select a client') }}</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                        </div>

                        <!-- Provider Selection -->
                        <div>
                            <x-input-label for="provider_id" :value="__('Provider')" />
                            <select id="provider_id" name="provider_id" class="input-dark mt-1 block w-full rounded-xl">
                                <option value="">{{ __('Select a provider') }}</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->user->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('provider_id')" />
                        </div>

                        <!-- Service Selection -->
                        <div>
                            <x-input-label for="service_id" :value="__('Service')" />
                            <select id="service_id" name="service_id" class="input-dark mt-1 block w-full rounded-xl">
                                <option value="">{{ __('Select a service') }}</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }} - ${{ $service->price }} ({{ $service->duration }} min)</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('service_id')" />
                        </div>

                        <!-- Date & Time -->
                        <div>
                            <x-input-label for="start_time" :value="__('Date & Time')" />
                            <x-text-input id="start_time" name="start_time" type="datetime-local" class="mt-1 block w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-4">
                        <x-secondary-button onclick="window.location.href='{{ route('appointments.index') }}'">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            {{ __('Create Appointment') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
