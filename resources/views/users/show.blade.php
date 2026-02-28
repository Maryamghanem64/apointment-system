<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Edit') }}
                            </a>
                            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <div class="mt-1 text-gray-900">{{ $user->name }}</div>
                        </div>
                        
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <div class="mt-1 text-gray-900">{{ $user->email }}</div>
                        </div>
                        
                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <div class="mt-1 text-gray-900">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div>
                            <x-input-label for="created_at" :value="__('Created At')" />
                            <div class="mt-1 text-gray-900">{{ $user->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t pt-6">
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this user?')">
                                {{ __('Delete User') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
