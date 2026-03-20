@extends('layouts.dark-app')

@section('content')

<div class="min-h-screen flex items-center justify-center px-6 py-12">
    <div class="glass-card backdrop-blur-md border border-white/10 rounded-2xl p-12 max-w-lg w-full text-center">

        <div class="w-20 h-20 bg-gradient-to-br from-cyan-500 to-blue-400 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-white/20">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 style="font-family:'Syne',sans-serif;"
            class="text-2xl md:text-3xl font-bold text-white mb-4">
            Application Submitted!
        </h1>
        <p class="text-white/60 text-lg mb-2 leading-relaxed">
            Thank you for applying to become a provider. 
        </p>
        <p class="text-white/50 text-base mb-8">
            Our team will review your application and get back to you within 24 hours.
        </p>

        <div class="space-y-3 text-white/50 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-emerald-400 rounded-full"></div>
                <span>Check your email for updates</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></div>
                <span>Review usually takes 24 hours</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                <span>Manage appointments once approved</span>
            </div>
        </div>

        <a href="{{ url('/') }}"
           class="w-full block bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-semibold py-4 px-8 rounded-xl hover:shadow-xl hover:shadow-cyan-500/30 hover:-translate-y-1 transition-all duration-300 inline-flex items-center justify-center gap-2 mx-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>

        <p class="text-white/30 text-xs mt-6 pt-6 border-t border-white/10">
            🔒 Your information is secure and private
        </p>
    </div>
</div>

@endsection

