@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-2xl font-bold text-white">Create Role</h1>
                <p class="text-white/50 mt-2 text-sm">Add a new role</p>
            </div>

            <div class="glass-card rounded-2xl p-8 max-w-2xl mx-auto">
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Role Name -->
                        <div>
                            <x-input-label for="name" :value="__('Role Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full md:w-1/2" :value="old('name')" required autofocus />
                            <p class="mt-1 text-sm text-white/40">Enter a unique role name (e.g., manager, editor).</p>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
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
@endsection
