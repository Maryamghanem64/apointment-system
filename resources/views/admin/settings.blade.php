@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Admin Settings</h1>
                <p class="text-white/60 mt-2">Configure system settings and manage resources</p>
            </div>

            <div class="glass-card rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-6">{{ __('System Settings') }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <a href="{{ route('services.index') }}" class="block p-5 rounded-xl border border-white/10 hover:bg-white/5 transition-all duration-200">
                            <h4 class="font-medium text-white">{{ __('Manage Services') }}</h4>
                            <p class="text-sm text-white/50 mt-1">{{ __('Add, edit, or remove services offered.') }}</p>
                        </a>
                    </div>
                    
                    <div>
                        <a href="{{ route('roles.index') }}" class="block p-5 rounded-xl border border-white/10 hover:bg-white/5 transition-all duration-200">
                            <h4 class="font-medium text-white">{{ __('Manage Roles') }}</h4>
                            <p class="text-sm text-white/50 mt-1">{{ __('Configure user roles and permissions.') }}</p>
                        </a>
                    </div>
                    
                    <div>
                        <a href="{{ route('payments.index') }}" class="block p-5 rounded-xl border border-white/10 hover:bg-white/5 transition-all duration-200">
                            <h4 class="font-medium text-white">{{ __('Manage Payments') }}</h4>
                            <p class="text-sm text-white/50 mt-1">{{ __('View and manage payment records.') }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
