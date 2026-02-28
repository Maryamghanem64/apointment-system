<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Provider Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Profile Information') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('Name') }}</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('Email') }}</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $user->email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('Member Since') }}</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                        </div>
                        
                        @if($provider)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('Provider ID') }}</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $provider->id }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('profile.edit') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Edit Profile') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
