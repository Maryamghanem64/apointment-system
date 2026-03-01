@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="font-heading text-3xl font-bold text-white">User Details</h1>
                    <p class="text-white/60 mt-2">View user information</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn-primary px-4 py-2 rounded-lg">
                        {{ __('Edit') }}
                    </a>
                    <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg border border-white/20 text-white/80 hover:bg-white/5">
                        {{ __('Back') }}
                    </a>
                </div>
            </div>

            <div class="glass-card rounded-xl p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <div class="mt-1 text-white">{{ $user->name }}</div>
                    </div>
                    
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <div class="mt-1 text-white">{{ $user->email }}</div>
                    </div>
                    
                    <div>
                        <x-input-label for="role" :value="__('Role')" />
                        <div class="mt-1">
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <x-input-label for="created_at" :value="__('Created At')" />
                        <div class="mt-1 text-white/80">{{ $user->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                
                <div class="mt-8 border-t border-white/10 pt-6">
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30" onclick="return confirm('Are you sure you want to delete this user?')">
                            {{ __('Delete User') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
