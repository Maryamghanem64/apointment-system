<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Role Name -->
                            <div>
                                <x-input-label for="name" :value="__('Role Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full md:w-1/2" :value="old('name')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <x-secondary-button onclick="window.location.href='{{ route('roles.index') }}'">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                {{ __('Create Role') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
