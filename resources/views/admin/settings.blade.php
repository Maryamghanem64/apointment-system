<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('System Settings') }}</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <a href="{{ route('services.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <h4 class="font-medium text-gray-900">{{ __('Manage Services') }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ __('Add, edit, or remove services offered.') }}</p>
                            </a>
                        </div>
                        
                        <div>
                            <a href="{{ route('roles.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <h4 class="font-medium text-gray-900">{{ __('Manage Roles') }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ __('Configure user roles and permissions.') }}</p>
                            </a>
                        </div>
                        
                        <div>
                            <a href="{{ route('payments.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <h4 class="font-medium text-gray-900">{{ __('Manage Payments') }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ __('View and manage payment records.') }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
