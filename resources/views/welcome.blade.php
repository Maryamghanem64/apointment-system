@extends('layouts.dark-app')

@section('content')
        <!-- Hero Section -->
        <section class="min-h-[90vh] flex items-center justify-center">
            <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
                <!-- Radial glow behind hero -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-gradient-to-r from-blue-600/20 via-cyan-500/15 to-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative">
                    <!-- Badge -->
                    <div class="animate-fade-up inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-10">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                        <span class="text-sm text-white/70">Built for modern businesses</span>
                    </div>

                    <!-- Headline -->
                    <h1 class="animate-fade-up delay-100 font-heading text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight mb-8 leading-[1.1]">
                        Smart Appointment
                        <br />
                        <span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-blue-500 via-cyan-400 to-cyan-500">Management</span>
                    </h1>

                    <!-- Subheadline -->
                    <p class="animate-fade-up delay-200 text-lg sm:text-xl text-white/50 max-w-2xl mx-auto mb-12 leading-relaxed">
                        Built for modern businesses that value time. Streamline your scheduling, 
                        manage clients effortlessly, and grow your business with intelligence.
                    </p>

                    <!-- Buttons -->
                    <div class="animate-fade-up delay-300 flex flex-col sm:flex-row justify-center items-center gap-4 mb-16">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary text-white font-semibold py-4 px-10 rounded-xl text-lg w-full sm:w-auto">
                                Start Free Trial
                            </a>
                        @endif
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn-secondary text-white font-semibold py-4 px-10 rounded-xl text-lg w-full sm:w-auto">
                                Sign In
                            </a>
                        @endif
                    </div>

                    <!-- Trust indicators -->
                    <div class="animate-fade-up delay-400 pt-8 border-t border-white/10">
                        <p class="text-white/30 text-sm mb-6">No credit card required · 14-day free trial</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 relative">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="scroll-animate font-heading text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
                        Everything you need
                    </h2>
                    <p class="scroll-animate delay-100 text-white/50 text-lg max-w-2xl mx-auto">
                        Powerful features designed for modern service businesses. 
                        Simple, elegant, and built to scale with you.
                    </p>
                </div>

                <!-- Feature Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Card 1 -->
                    <div class="scroll-animate delay-200 glass-card rounded-2xl p-8">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center mb-6 border border-white/10">
                            <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-heading font-semibold text-white mb-3">Smart Scheduling</h3>
                        <p class="text-white/50 leading-relaxed">
                            Intelligent calendar management with automatic time zone detection, buffer times, and smart conflict resolution.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="scroll-animate delay-300 glass-card rounded-2xl p-8">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center mb-6 border border-white/10">
                            <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-heading font-semibold text-white mb-3">Client Management</h3>
                        <p class="text-white/50 leading-relaxed">
                            Complete client profiles, booking history, preferences, and automated reminders keep everyone happy.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="scroll-animate delay-400 glass-card rounded-2xl p-8">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center mb-6 border border-white/10">
                            <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-heading font-semibold text-white mb-3">Analytics & Insights</h3>
                        <p class="text-white/50 leading-relaxed">
                            Powerful dashboards with real-time analytics, revenue tracking, and growth metrics to scale your business.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="cta" class="py-24 relative">
            <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">
                <div class="relative">
                    <!-- Glow background -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/15 to-cyan-500/15 blur-3xl rounded-3xl"></div>
                    
                    <div class="relative glass rounded-3xl p-12 lg:p-16">
                        <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
                            Ready to transform
                            <br />
                            <span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-blue-500 via-cyan-400 to-cyan-500">your business?</span>
                        </h2>
                        <p class="text-white/50 text-lg mb-10 max-w-md mx-auto">
                            Join thousands of businesses already using Schedora to streamline their appointment management.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary text-white font-semibold py-4 px-10 rounded-xl text-lg w-full sm:w-auto">
                                    Get Started Free
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
