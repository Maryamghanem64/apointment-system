@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Edit Service</h1>
                <p class="text-white/60 mt-2">Update service information</p>
            </div>

            <div class="glass-card rounded-xl p-6 sm:p-8">
                <form method="POST" action="{{ route('services.update', $service->id) }}">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Service Name -->
                        <div>
                            <x-input-label for="name" :value="__('Service Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $service->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Duration -->
                        <div>
                            <x-input-label for="duration" :value="__('Duration (minutes)')" />
                            <x-text-input id="duration" name="duration" type="number" class="mt-1 block w-full" :value="old('duration', $service->duration)" required min="1" placeholder="e.g., 30" />
                            <p class="mt-1 text-sm text-white/40">Enter duration in minutes (e.g., 30, 60, 90)</p>
                            <x-input-error class="mt-2" :messages="$errors->get('duration')" />
                        </div>

                        <!-- Price -->
                        <div>
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price', $service->price)" required min="0" placeholder="0.00" />
                            <p class="mt-1 text-sm text-white/40">Enter price in dollars (e.g., 25.00)</p>
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-4">
                        <x-secondary-button onclick="window.location.href='{{ route('services.index') }}'">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            {{ __('Update Service') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
