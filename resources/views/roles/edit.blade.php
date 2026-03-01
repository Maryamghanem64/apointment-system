@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Edit Role</h1>
                <p class="text-white/60 mt-2">Update role information</p>
            </div>

            <div class="glass-card rounded-xl p-6 sm:p-8">
                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Role Name -->
                        <div>
                            <x-input-label for="name" :value="__('Role Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full md:w-1/2" :value="old('name', $role->name)" required autofocus />
                            <p class="mt-1 text-sm text-white/40">Enter a unique role name (e.g., manager, editor).</p>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-4">
                        <x-secondary-button onclick="window.location.href='{{ route('roles.index') }}'">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            {{ __('Update Role') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
