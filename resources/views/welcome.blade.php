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

        <!-- Reviews Section -->
        <section id="reviews" class="py-24 relative">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="scroll-animate font-heading text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
                        What Our Users Are Saying
                    </h2>
                    <p class="scroll-animate delay-100 text-white/50 text-lg max-w-2xl mx-auto mb-4">
                        Real experiences from real people who trust Schedora
                    </p>
                    @if($totalReviews > 0)
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass">
                            <span class="text-2xl font-bold text-cyan-400">{{ number_format($averageRating, 1) }}</span>
                            <span class="text-cyan-400 text-lg">★</span>
                            <span class="text-white/50">|</span>
                            <span class="text-white/70">{{ $totalReviews }} reviews</span>
                        </div>
                    @endif
                </div>

                @if($totalReviews > 0)
                    <!-- Review Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featuredReviews as $review)
                            <div class="scroll-animate glass-card rounded-2xl p-6 hover:-translate-y-1 transition-all duration-300 border border-white/10">
                                <!-- Star Rating -->
                                <div class="flex items-center gap-1 mb-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $review->rating ? 'text-cyan-400' : 'text-white/20' }} text-lg">★</span>
                                    @endfor
                                </div>
                                
                                <!-- Review Comment -->
                                <p class="text-white/70 leading-relaxed mb-6">
                                    {{ $review->comment ?? 'Great service! Highly recommended.' }}
                                </p>
                                
                                <!-- User Info -->
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ substr($review->user->name ?? 'U', 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-white font-medium">{{ $review->user->name ?? 'User' }}</div>
                                        @if($review->review_type === 'provider' && $review->provider)
                                            <div class="text-cyan-400 text-sm">{{ $review->provider->user->name ?? 'Provider' }}</div>
                                        @else
                                            <div class="text-blue-400 text-sm">Platform Review</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Date -->
                                <div class="text-white/40 text-xs mt-3">
                                    {{ $review->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/5 mb-4">
                            <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <p class="text-white/50 text-lg">No reviews yet. Be the first to share your experience!</p>
                    </div>
                @endif
            </div>
        </section>

        {{-- Become a Provider Section --}}
        <section class="py-20 px-6 relative">

            <div class="absolute inset-0 pointer-events-none"
                 style="background: radial-gradient(ellipse at center, rgba(59,130,246,0.08) 0%, transparent 70%);">
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="relative bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-12 overflow-hidden glass-card">

                    {{-- Background glow --}}
                    <div class="absolute -top-10 -right-10 w-60 h-60 rounded-full opacity-20 pointer-events-none animate-glow-pulse"
                         style="background: radial-gradient(circle, #22d3ee, transparent);">
                    </div>
                    <div class="absolute -bottom-10 -left-10 w-48 h-48 rounded-full opacity-15 pointer-events-none animate-float delay-500"
                         style="background: radial-gradient(circle, #3b82f6, transparent);">
                    </div>

                    <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                        {{-- Left: Text --}}
                        <div>
                            <div class="inline-flex items-center gap-2 bg-cyan-500/10 border border-cyan-500/20 rounded-full px-4 py-1.5 mb-6">
                                <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>
                                <span class="text-cyan-400 text-xs font-medium">Join Our Network</span>
                            </div>

                            <h2 style="font-family:'Syne',sans-serif;
                                       background: linear-gradient(135deg, #ffffff, #22d3ee);
                                       -webkit-background-clip: text;
                                       -webkit-text-fill-color: transparent;
                                       background-clip: text;"
                                class="text-4xl font-bold mb-4 scroll-animate">
                                Become a Provider
                            </h2>

                            <p class="text-white/60 text-lg leading-relaxed mb-8 scroll-animate delay-200">
                                Join Schedora as a service provider and grow your business.
                                Manage your schedule, accept bookings, and get paid — all in one place.
                            </p>

                            {{-- Benefits --}}
                            <div class="space-y-4 scroll-animate delay-400">
                                @foreach([
                                    ['icon' => 'M9 12l2 2 4-4', 'text' => 'Manage your schedule easily'],
                                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7', 'text' => 'Accept online bookings 24/7'],
                                    ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2', 'text' => 'Get paid securely'],
                                    ['icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674', 'text' => 'Build reputation with reviews'],
                                ] as $benefit)
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 text-cyan-400 flex-shrink-0 mt-0.5">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="{{ $benefit['icon'] }}"/>
                                        </svg>
                                    </div>
                                    <span class="text-white/80 text-sm leading-relaxed">{{ $benefit['text'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Right: CTA Card --}}
                        <div class="scroll-animate delay-500">
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 text-center glass-card hover:-translate-y-2">
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-white/20">
                                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>

                                <h3 style="font-family:'Syne',sans-serif;"
                                    class="text-2xl font-bold text-white mb-4">
                                    Ready to get started?
                                </h3>
                                <p class="text-white/60 text-sm mb-8 leading-relaxed">
                                    Fill out a quick application and our team will review it within 24 hours.
                                </p>

                                <a href="{{ route('provider.application.create') }}"
                                   class="block w-full bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-semibold rounded-xl px-6 py-4 hover:shadow-2xl hover:shadow-cyan-500/40 hover:-translate-y-1 transition-all duration-300 mb-4 text-lg">
                                    Apply Now — It's Free
                                </a>

                                <p class="text-white/40 text-xs">
                                    🔒 Your information is safe and secure
                                </p>
                            </div>
                        </div>
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
