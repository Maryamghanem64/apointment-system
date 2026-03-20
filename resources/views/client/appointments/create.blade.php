@extends('layouts.dark-app')
@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 style="font-family:'Syne',sans-serif;"
            class="text-3xl font-bold text-white mb-1">
            Book an Appointment
        </h1>
        <p class="text-white/50 text-sm">
            Choose your provider, service, and preferred time
        </p>
    </div>

    {{-- Progress Steps --}}
    <div class="flex items-center gap-2 mb-8">
        @foreach(['Choose Provider', 'Select Service & Time', 'Confirm'] as $i => $step)
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                {{ $i === 0 ? 'bg-gradient-to-br from-blue-500 to-cyan-400 text-white' : 'bg-white/10 text-white/40' }}">
                {{ $i + 1 }}
            </div>
            <span class="text-sm {{ $i === 0 ? 'text-white' : 'text-white/30' }} hidden md:block">
                {{ $step }}
            </span>
        </div>
        @if($i < 2)
            <div class="flex-1 h-px bg-white/10 max-w-16"></div>
        @endif
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- LEFT: Provider Selection --}}
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6">
            <h2 style="font-family:'Syne',sans-serif;"
                class="text-lg font-bold text-white mb-4">
                Choose a Provider
            </h2>

            {{-- Search --}}
            <div class="relative mb-4">
                <input type="text"
                    id="providerSearch"
                    placeholder="Search providers..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 pl-9 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 transition-all duration-300 text-sm">
                <svg class="absolute left-3 top-3 w-4 h-4 text-white/30"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Providers List --}}
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1" id="providers-list">
                @forelse($providers as $provider)
                <div class="provider-item cursor-pointer bg-white/5 border rounded-xl p-4 transition-all duration-300 hover:border-cyan-400/50
                    {{ ($selectedProvider && $selectedProvider->id === $provider->id) ? 'border-cyan-400 bg-cyan-400/5' : 'border-white/10' }}"
                     data-provider="{{ $provider->id }}"
                     data-name="{{ strtolower($provider->user->name ?? '') }}"
                     onclick="selectProvider({{ $provider->id }}, '{{ $provider->user->name ?? 'Provider' }}')">

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold shrink-0">
                            {{ strtoupper(substr($provider->user->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-medium">
                                {{ $provider->user->name ?? 'Provider' }}
                            </p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($provider->services->take(2) as $service)
                                <span class="bg-blue-500/20 text-blue-400 text-xs px-2 py-0.5 rounded-full">
                                    {{ $service->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        {{-- Selected indicator --}}
                        <div class="w-5 h-5 rounded-full border-2 border-white/20 flex items-center justify-center shrink-0 provider-check-{{ $provider->id }}
                            {{ ($selectedProvider && $selectedProvider->id === $provider->id) ? 'bg-cyan-400 border-cyan-400' : '' }}">
                            @if($selectedProvider && $selectedProvider->id === $provider->id)
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-white/30 text-sm">No providers available.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: Booking Form --}}
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6">
            <h2 style="font-family:'Syne',sans-serif;"
                class="text-lg font-bold text-white mb-4">
                Appointment Details
            </h2>

            {{-- Success message --}}
            @if(session('success'))
            <div class="mb-4 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl px-4 py-3 text-sm">
                ✓ {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('client.appointments.store') }}" id="booking-form">
                @csrf

                {{-- Hidden provider_id --}}
                <input type="hidden" name="provider_id" id="provider_id"
                    value="{{ $selectedProvider?->id ?? '' }}">

                {{-- Selected Provider Display --}}
                <div id="selected-provider-display" class="mb-4
                    {{ $selectedProvider ? '' : 'hidden' }}">
                    <label class="block text-white/60 text-sm font-medium mb-2">
                        Selected Provider
                    </label>
                    <div class="bg-cyan-500/10 border border-cyan-500/30 rounded-xl px-4 py-3 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white font-bold text-sm">
                            <span id="provider-avatar">{{ strtoupper(substr($selectedProvider?->user?->name ?? 'P', 0, 1)) }}</span>
                        </div>
                        <span class="text-cyan-400 text-sm font-medium" id="provider-name-display">
                            {{ $selectedProvider?->user?->name ?? '' }}
                        </span>
                    </div>
                </div>

                {{-- No provider selected message --}}
                <div id="no-provider-message" class="mb-4 {{ $selectedProvider ? 'hidden' : '' }}">
                    <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-center">
                        <p class="text-white/30 text-sm">← Select a provider from the left</p>
                    </div>
                </div>

                {{-- Service --}}
                <div class="mb-4">
                    <label class="block text-white/60 text-sm font-medium mb-2">
                        Service <span class="text-rose-400">*</span>
                    </label>
                    <select name="service_id"
                        class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-400 transition-all duration-300"
                        style="color-scheme:dark;">
                        <option value="">Select a service...</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                            @if($service->price) — ${{ $service->price }} @endif
                        </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date & Time --}}
                <div class="mb-4">
                    <label class="block text-white/60 text-sm font-medium mb-2">
                        Date & Time <span class="text-rose-400">*</span>
                    </label>
                    <input type="datetime-local"
                        name="appointment_date"
                        value="{{ old('appointment_date') }}"
                        min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-400 transition-all duration-300"
                        style="color-scheme:dark;">
                    @error('appointment_date')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="mb-6">
                    <label class="block text-white/60 text-sm font-medium mb-2">
                        Notes <span class="text-white/30 font-normal">(optional)</span>
                    </label>
                    <textarea name="notes"
                        rows="3"
                        placeholder="Any special requests or information..."
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-cyan-400 transition-all duration-300 resize-none text-sm">{{ old('notes') }}</textarea>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    id="submit-btn"
                    class="w-full bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-semibold rounded-xl px-6 py-3.5 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                    Confirm Booking
                </button>

                {{-- Cancel --}}
                <a href="{{ route('client.dashboard') }}"
                   class="block text-center mt-3 text-white/30 text-sm hover:text-white/60 transition">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>

<script>
function selectProvider(id, name) {
    // Update hidden input
    document.getElementById('provider_id').value = id;

    // Update display
    document.getElementById('provider-name-display').textContent = name;
    document.getElementById('provider-avatar').textContent = name.charAt(0).toUpperCase();
    document.getElementById('selected-provider-display').classList.remove('hidden');
    document.getElementById('no-provider-message').classList.add('hidden');

    // Highlight selected provider card
    document.querySelectorAll('.provider-item').forEach(item => {
        if (parseInt(item.dataset.provider) === id) {
            item.classList.add('border-cyan-400', 'bg-cyan-400/5');
            item.classList.remove('border-white/10');
        } else {
            item.classList.remove('border-cyan-400', 'bg-cyan-400/5');
            item.classList.add('border-white/10');
        }
    });
}

// Provider search
document.getElementById('providerSearch').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('.provider-item').forEach(item => {
        item.style.display = item.dataset.name.includes(search) ? '' : 'none';
    });
});

// Form validation
document.getElementById('booking-form').addEventListener('submit', function(e) {
    const providerId = document.getElementById('provider_id').value;
    if (!providerId) {
        e.preventDefault();
        alert('Please select a provider first.');
    }
});
</script>

@endsection

