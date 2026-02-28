<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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
                                <x-text-input id="duration" name="duration" type="number" class="mt-1 block w-full" :value="old('duration', $service->duration)" required min="1" />
                                <x-input-error class="mt-2" :messages="$errors->get('duration')" />
                            </div>

                            <!-- Price -->
                            <div>
                                <x-input-label for="price" :value="__('Price')" />
                                <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price', $service->price)" required min="0" />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
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
    </div>
</x-app-layout>
