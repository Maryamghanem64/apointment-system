@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Client Settings</h1>
                <p class="text-white/60 mt-2">Manage your account settings</p>
            </div>

            <div class="glass-card rounded-xl p-6">
                <h3 class="font-heading text-lg font-semibold text-white mb-6">{{ __('Account Settings') }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <a href="{{ route('profile.edit') }}" class="block p-5 rounded-xl border border-white/10 hover:bg-white/5 transition-all duration-200">
                            <h4 class="font-medium text-white">{{ __('Edit Profile Information') }}</h4>
                            <p class="text-sm text-white/50 mt-1">{{ __('Update your name, email, and other profile information.') }}</p>
                        </a>
                    </div>
                    
                    <div>
                        <a href="{{ route('profile.edit') }}" class="block p-5 rounded-xl border border-white/10 hover:bg-white/5 transition-all duration-200">
                            <h4 class="font-medium text-white">{{ __('Change Password') }}</h4>
                            <p class="text-sm text-white/50 mt-1">{{ __('Update your password to keep your account secure.') }}</p>
                        </a>
                    </div>
                    
                    <div>
                        <a href="{{ route('client.dashboard') }}" class="block p-5 rounded-xl border border-white/10 hover:bg-white/5 transition-all duration-200">
                            <h4 class="font-medium text-white">{{ __('Notification Preferences') }}</h4>
                            <p class="text-sm text-white/50 mt-1">{{ __('Manage how you receive notifications.') }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
