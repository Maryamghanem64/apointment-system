@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-2xl font-bold text-white">Create Provider</h1>
                <p class="text-white/50 mt-2 text-sm">Add a new service provider</p>
            </div>

            <div class="glass-card rounded-2xl p-8 max-w-2xl mx-auto">
                <form method="POST" action="{{ route('providers.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- User Selection -->
                        <div>
                            <x-input-label for="user_id" :value="__('Select User')" />
                            <select id="user_id" name="user_id" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300">
                                <option value="">{{ __('Select a user') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                        </div>

                        <!-- Services Selection -->
                        <div>
                            <x-input-label for="service_ids" :value="__('Assign Services')" />
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                @forelse($services as $service)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="service_{{ $service->id }}" 
                                               name="service_ids[]" 
                                               value="{{ $service->id }}"
                                               class="h-4 w-4 text-cyan-400 border-white/20 rounded focus:ring-cyan-400/30 bg-white/5">
                                        <label for="service_{{ $service->id }}" class="ml-2 block text-sm text-white/80">
                                            {{ $service->name }}
                                            <span class="text-white/40">({{ $service->duration }} min - ${{ number_format($service->price, 2) }})</span>
                                        </label>
                                    </div>
                                @empty
                                    <p class="col-span-2 text-sm text-white/40">No services available. Please create services first.</p>
                                @endforelse
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('service_ids')" />
                        </div>

                        {{-- Working Hours Section --}}
                        <div class="mt-8">
                            <h3 style="font-family:'Syne',sans-serif;" class="text-lg font-bold text-white mb-1">
                                Working Hours
                            </h3>
                            <p class="text-white/40 text-sm mb-4">
                                Set the provider's available hours for each day
                            </p>

                            <div class="space-y-3">
                                @php
                                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                                @endphp

                                @foreach($days as $index => $day)
                                <div class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-xl px-4 py-3">

                                    {{-- Day toggle --}}
                                    <div class="flex items-center gap-2 w-36">
                                        <input type="checkbox"
                                            name="working_hours[{{ $index }}][is_active]"
                                            value="1"
                                            id="day_{{ $index }}"
                                            class="w-4 h-4 rounded accent-cyan-400"
                                            onchange="toggleDay({{ $index }})">
                                        <label for="day_{{ $index }}"
                                            class="text-white/70 text-sm font-medium cursor-pointer">
                                            {{ $day }}
                                        </label>
                                        <input type="hidden"
                                            name="working_hours[{{ $index }}][day]"
                                            value="{{ $day }}">
                                    </div>

                                    {{-- Start time --}}
                                    <div class="flex items-center gap-2 flex-1" id="times_{{ $index }}">
                                        <span class="text-white/40 text-xs">From</span>
                                        <input type="time"
                                            name="working_hours[{{ $index }}][start_time]"
                                            value="09:00"
                                            class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white text-sm focus:outline-none focus:border-cyan-400 transition-all duration-300"
                                            style="color-scheme:dark;">

                                        <span class="text-white/40 text-xs">To</span>
                                        <input type="time"
                                            name="working_hours[{{ $index }}][end_time]"
                                            value="17:00"
                                            class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white text-sm focus:outline-none focus:border-cyan-400 transition-all duration-300"
                                            style="color-scheme:dark;">
                                    </div>

                                    {{-- Off badge --}}
                                    <span id="off_{{ $index }}"
                                        class="text-white/30 text-xs">
                                        Day Off
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Holidays Section --}}
                        <div class="mt-8">
                            <h3 style="font-family:'Syne',sans-serif;" class="text-lg font-bold text-white mb-1">
                                Holidays
                            </h3>
                            <p class="text-white/40 text-sm mb-4">
                                Add days when the provider is not available
                            </p>

                            <div id="holidays-container" class="space-y-3 mb-4">
                                {{-- Holiday rows added dynamically --}}
                            </div>

                            {{-- Add Holiday button --}}
                            <button type="button"
                                onclick="addHoliday()"
                                class="flex items-center gap-2 border border-white/20 text-white/60 rounded-xl px-4 py-2 text-sm hover:border-cyan-400 hover:text-cyan-400 transition-all duration-300">
                                <span class="text-lg leading-none">+</span>
                                Add Holiday
                            </button>
                        </div>

                        {{-- Services Section (existing, kept) --}}
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <x-secondary-button onclick="window.location.href='{{ route('providers.index') }}'">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            {{ __('Create Provider') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function toggleDay(index) {
        const checkbox = document.getElementById('day_' + index);
        const times = document.getElementById('times_' + index);
        const off = document.getElementById('off_' + index);
        if (checkbox.checked) {
            times.style.opacity = '1';
            times.style.pointerEvents = 'auto';
            off.style.display = 'none';
        } else {
            times.style.opacity = '0.3';
            times.style.pointerEvents = 'none';
            off.style.display = 'block';
        }
    }
    // init all days as inactive
    document.addEventListener('DOMContentLoaded', function() {
        [0,1,2,3,4,5,6].forEach(i => toggleDay(i));
    });

    let holidayCount = 0;
    function addHoliday() {
        holidayCount++;
        const container = document.getElementById('holidays-container');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-3';
        row.id = 'holiday_row_' + holidayCount;
        row.innerHTML = `
            <input type="date"
                name="holidays[${holidayCount}][date]"
                class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white text-sm focus:outline-none focus:border-cyan-400 transition-all duration-300 flex-1"
                style="color-scheme:dark;">
            <input type="text"
                name="holidays[${holidayCount}][reason]"
                placeholder="Reason (optional)"
                class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white placeholder-white/30 text-sm focus:outline-none focus:border-cyan-400 transition-all duration-300 flex-1">
            <button type="button"
                onclick="removeHoliday(${holidayCount})"
                class="text-rose-400 hover:text-rose-300 text-lg transition-all duration-200">
                ✕
            </button>
        `;
        container.appendChild(row);
    }

    function removeHoliday(id) {
        document.getElementById('holiday_row_' + id).remove();
    }
    </script>
@endsection

