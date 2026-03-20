@extends('layouts.dark-app')
@section('content')

<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-lg">
        <div class="relative bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-10 text-center overflow-hidden">

            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full opacity-20 pointer-events-none"
                 style="background: radial-gradient(circle, #f43f5e, transparent);">
            </div>

            <div class="relative mx-auto mb-6 w-24 h-24">
                <div class="relative w-24 h-24 bg-rose-500/20 border-2 border-rose-500/50 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>

            <h1 style="font-family:'Syne',sans-serif;"
                class="text-3xl font-bold text-white mb-2">
                Payment Cancelled
            </h1>
            <p class="text-white/50 text-sm mb-8">
                Your payment was cancelled. Your appointment is still pending.
            </p>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('payments.show', $appointment) }}"
                   class="flex-1 bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-6 py-3 hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300 text-sm text-center">
                    Try Again
                </a>
                <a href="{{ route('client.dashboard') }}"
                   class="flex-1 bg-white/5 border border-white/10 text-white/70 rounded-xl px-6 py-3 hover:bg-white/10 hover:text-white transition-all duration-300 text-sm text-center">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
