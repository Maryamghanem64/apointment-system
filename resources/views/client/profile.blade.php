@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">My Profile</h1>
                <p class="text-white/60 mt-2">View and manage your profile information</p>
            </div>

            <div class="glass-card rounded-xl p-6">
                <h3 class="font-heading text-lg font-semibold text-white mb-6">{{ __('Profile Information') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-white/50">{{ __('Name') }}</label>
                        <p class="mt-1 text-lg font-medium text-white">{{ $user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/50">{{ __('Email') }}</label>
                        <p class="mt-1 text-lg font-medium text-white">{{ $user->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/50">{{ __('Member Since') }}</label>
                        <p class="mt-1 text-lg font-medium text-white">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/50">{{ __('Role') }}</label>
                        <p class="mt-1">
                            @foreach($user->roles as $role)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </p>
                    </div>
                </div>
                
                <div class="mt-8">
                    <a href="{{ route('profile.edit') }}" class="btn-primary text-white font-semibold py-3 px-6 rounded-xl inline-block">
                        {{ __('Edit Profile') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
