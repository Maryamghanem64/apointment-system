@extends('layouts.dark-app')

@section('content')

<div class="max-w-2xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 bg-cyan-500/10 border border-cyan-500/20 rounded-full px-4 py-1.5 mb-4">
            <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>
            <span class="text-cyan-400 text-xs">Provider Application</span>
        </div>
        <h1 style="font-family:'Syne',sans-serif;"
            class="text-3xl font-bold text-white mb-2">
            Join as a Provider
        </h1>
        <p class="text-white/50 text-sm">
            Fill out the form below and we'll review your application within 24 hours.
        </p>
    </div>

    {{-- Form Card --}}
    <div class="glass-card rounded-2xl p-8">

        @if($errors->any())
        <div class="mb-6 bg-rose-500/20 border border-rose-500/30 text-rose-400 rounded-xl px-4 py-3 text-sm">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('provider.application.store') }}">
            @csrf

            {{-- Full Name --}}
            <div class="mb-5">
                <label class="block text-white/60 text-sm font-medium mb-2">
                    Full Name <span class="text-rose-400">*</span>
                </label>
                <input type="text" name="full_name" value="{{ old('full_name') }}"
                    placeholder="Your full name"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 @error('full_name') border-rose-400 @enderror">
                @error('full_name')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-5">
                <label class="block text-white/60 text-sm font-medium mb-2">
                    Email Address <span class="text-rose-400">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}"
                    placeholder="your@email.com"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 @error('email') border-rose-400 @enderror">
                @error('email')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="mb-5">
                <label class="block text-white/60 text-sm font-medium mb-2">
                    Phone Number
                    <span class="text-white/30 font-normal">(optional)</span>
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    placeholder="+1 (555) 123-4567"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300">
            </div>

            {{-- Specialty --}}
            <div class="mb-5">
                <label class="block text-white/60 text-sm font-medium mb-2">
                    Specialty / Service Type <span class="text-rose-400">*</span>
                </label>
                <select name="specialty"
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 appearance-none @error('specialty') border-rose-400 @enderror"
                    style="background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2322d3ee'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;">
                    <option value="">Select your specialty...</option>
                    @foreach([
                        'Medical / Healthcare',
                        'Dental',
                        'Beauty & Hair',
                        'Massage & Wellness',
                        'Personal Training',
                        'Legal Consultation',
                        'Financial Advisory',
                        'IT & Tech Support',
                        'Education & Tutoring',
                        'Other',
                    ] as $specialty)
                    <option value="{{ $specialty }}" {{ old('specialty') === $specialty ? 'selected' : '' }}>
                        {{ $specialty }}
                    </option>
                    @endforeach
                </select>
                @error('specialty')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Experience --}}
            <div class="mb-5">
                <label class="block text-white/60 text-sm font-medium mb-2">
                    Years of Experience & Qualifications
                    <span class="text-white/30 font-normal">(optional)</span>
                </label>
                <textarea name="experience" rows="3"
                    placeholder="e.g. 5 years as a licensed physiotherapist..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 resize-none">{{ old('experience') }}</textarea>
            </div>

            {{-- Bio --}}
            <div class="mb-8">
                <label class="block text-white/60 text-sm font-medium mb-2">
                    Brief Bio
                    <span class="text-white/30 font-normal">(optional)</span>
                </label>
                <textarea name="bio" rows="3"
                    placeholder="Tell clients a bit about yourself..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 resize-none">{{ old('bio') }}</textarea>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full btn-primary text-white font-semibold py-3.5 px-6 rounded-xl hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300 text-lg">
                Submit Application
            </button>

            <a href="{{ url('/') }}"
               class="block text-center mt-3 text-white/30 text-sm hover:text-white/60 transition">
                ← Back to Home
            </a>
        </form>
    </div>
</div>

@endsection

