<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Client Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Account Settings') }}</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <a href="{{ route('profile.edit') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <h4 class="font-medium text-gray-900">{{ __('Edit Profile Information') }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ __('Update your name, email, and other profile information.') }}</p>
                            </a>
                        </div>
                        
                        <div>
                            <a href="{{ route('profile.update-password') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <h4 class="font-medium text-gray-900">{{ __('Change Password') }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ __('Update your password to keep your account secure.') }}</p>
                            </a>
                        </div>
                        
                        <div>
                            <a href="{{ route('client.dashboard') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <h4 class="font-medium text-gray-900">{{ __('Notification Preferences') }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ __('Manage how you receive notifications.') }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
