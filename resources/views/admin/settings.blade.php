@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-2xl font-bold text-white">Admin Settings</h1>
                <p class="text-white/50 mt-2 text-sm">Configure system settings and manage resources</p>
            </div>

            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-white mb-6">{{ __('System Settings') }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <a href="{{ route('services.index') }}" class="block p-5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-blue-500/20 p-3 rounded-xl">
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-medium text-white">{{ __('Manage Services') }}</h4>
                                    <p class="text-sm text-white/50 mt-1">{{ __('Add, edit, or remove services offered.') }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div>
                        <a href="{{ route('roles.index') }}" class="block p-5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-blue-500/20 p-3 rounded-xl">
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-medium text-white">{{ __('Manage Roles') }}</h4>
                                    <p class="text-sm text-white/50 mt-1">{{ __('Configure user roles and permissions.') }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div>
                        <a href="{{ route('payments.index') }}" class="block p-5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-blue-500/20 p-3 rounded-xl">
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-medium text-white">{{ __('Manage Payments') }}</h4>
                                    <p class="text-sm text-white/50 mt-1">{{ __('View and manage payment records.') }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
