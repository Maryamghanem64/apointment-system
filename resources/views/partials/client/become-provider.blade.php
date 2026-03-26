<div class="glass-card border border-white/10 rounded-2xl p-6 hover:-translate-y-1 hover:border-cyan-400/50 transition-all duration-300 overflow-hidden">

    {{-- Background glows (scaled down) --}}
    <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full opacity-20 pointer-events-none animate-glow-pulse bg-cyan-500/20 blur-xl"></div>
    <div class="absolute -bottom-4 -left-4 w-20 h-20 rounded-full opacity-15 pointer-events-none animate-float bg-blue-500/20 blur-xl"></div>

    <div class="relative">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 bg-cyan-500/10 border border-cyan-500/20 rounded-full px-3 py-1 mb-4">
            <div class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></div>
            <span class="text-cyan-400 text-xs font-medium">Join Our Network</span>
        </div>

        {{-- Title --}}
        <h3 style="font-family:'Syne',sans-serif; background: linear-gradient(135deg, #ffffff, #22d3ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"
            class="text-2xl font-bold mb-3 scroll-animate">
            Become a Provider
        </h3>

        {{-- Description --}}
        <p class="text-white/60 text-sm leading-relaxed mb-6 scroll-animate delay-100">
            Join Schedora as a service provider. Manage your schedule, accept bookings, and grow your business.
        </p>

        {{-- Benefits (shortened) --}}
        <div class="space-y-2 mb-6 scroll-animate delay-200">
            @foreach([
                ['icon' => 'M9 12l2 2 4-4m-7 4h6', 'text' => 'Smart scheduling'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7', 'text' => '24/7 online bookings'],
                ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2', 'text' => 'Secure payments'],
            ] as $benefit)
            <div class="flex items-start gap-2">
                <div class="w-4 h-4 text-cyan-400 flex-shrink-0 mt-0.5">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $benefit['icon'] }}"/>
                    </svg>
                </div>
                <span class="text-white/80 text-xs">{{ $benefit['text'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <a href="{{ route('provider.application.create') }}"
           class="block w-full bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-semibold rounded-xl px-6 py-3 hover:shadow-2xl hover:shadow-cyan-500/40 hover:-translate-y-1 transition-all duration-300 text-base">
            Apply Now — Free
        </a>

        <p class="text-white/40 text-xs mt-3 text-center">
            🔒 Secure • Reviewed in 24h
        </p>
    </div>
</div>
