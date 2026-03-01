@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Provider Profile</h1>
                <p class="text-white/60 mt-2">View your profile information</p>
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
                    
                    @if($provider)
                    <div>
                        <label class="block text-sm font-medium text-white/50">{{ __('Provider ID') }}</label>
                        <p class="mt-1 text-lg font-medium text-white">{{ $provider->id }}</p>
                    </div>
                    @endif
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
