@extends('layouts.dark-app')
@section('content')

<div class="max-w-5xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 style="font-family:'Syne',sans-serif;"
                class="text-3xl font-bold text-white mb-1">
                Working Hours
            </h1>
            <p class="text-white/50 text-sm">
                Manage your availability schedule for client bookings
            </p>
        </div>
        <a href="{{ route('provider.dashboard') }}"
           class="flex items-center gap-2 bg-white/5 border border-white/10 text-white/60 rounded-xl px-4 py-2.5 hover:bg-white/10 hover:text-white transition-all duration-300 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-rose-500/20 border border-rose-500/30 text-rose-400 rounded-xl px-4 py-3 text-sm">
        @foreach($errors->all() as $error)
            <p>• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- ===== WORKING HOURS SECTION ===== --}}
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden mb-6">

        {{-- Section Header --}}
        <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 style="font-family:'Syne',sans-serif;"
                    class="text-white font-semibold">
                    Weekly Schedule
                </h2>
            </div>
            <span class="text-white/30 text-xs">
                Toggle days on/off and set your hours
            </span>
        </div>

        <form method="POST" action="{{ route('provider.working-hours.update') }}" class="p-6">
            @csrf

            <div class="space-y-3">
                @php
                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                    $dayMap = [1,2,3,4,5,6,0]; // Mon=1..Sun=0
                    $dayColors = [
                        'Monday'    => 'blue',
                        'Tuesday'   => 'cyan',
                        'Wednesday' => 'blue',
                        'Thursday'  => 'cyan',
                        'Friday'    => 'blue',
                        'Saturday'  => 'amber',
                        'Sunday'    => 'rose',
                    ];
                @endphp

                @foreach($days as $index => $day)
                @php
                    $existing = $workingHours->get($day);
                    $isActive = $existing && $existing->is_active;
                    $dayNum = $dayMap[$index];
                @endphp

                <div class="flex flex-col md:flex-row md:items-center gap-4 p-4 rounded-xl border transition-all duration-300
                    {{ $isActive ? 'bg-white/5 border-white/10' : 'bg-white/2 border-white/5' }}"
                     id="day-row-{{ $index }}">

                    {{-- Day Toggle --}}
                    <div class="flex items-center gap-3 w-40 shrink-0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                name="working_hours[{{ $index }}][is_active]"
                                value="1"
                                id="toggle_{{ $index }}"
                                {{ $isActive ? 'checked' : '' }}
                                class="sr-only peer"
                                onchange="toggleDay({{ $index }})">
                            <div class="w-11 h-6 bg-white/10 peer-focus:ring-2 peer-focus:ring-cyan-400/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-cyan-400"></div>
                        </label>
                        <input type="hidden"
                            name="working_hours[{{ $index }}][day_of_week]"
                            value="{{ $dayNum }}">
                        <input type="hidden"
                            name="working_hours[{{ $index }}][day]"
                            value="{{ $day }}">
                        <span class="text-white font-medium text-sm {{ $isActive ? '' : 'text-white/40' }}"
                              id="day-label-{{ $index }}">
                            {{ $day }}
                        </span>
                    </div>

                    {{-- Time Inputs --}}
                    <div class="flex items-center gap-3 flex-1"
                         id="time-inputs-{{ $index }}"
                         style="{{ $isActive ? '' : 'opacity:0.3;pointer-events:none;' }}">

                        <div class="flex items-center gap-2 flex-1">
                            <span class="text-white/40 text-xs shrink-0">From</span>
                            <input type="time"
                                name="working_hours[{{ $index }}][start_time]"
                                value="{{ $existing ? $existing->start_time->format('H:i') : '09:00' }}"
                                class="flex-1 bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-cyan-400 transition-all duration-300"
                                style="color-scheme:dark;">
                        </div>

                        <div class="flex items-center gap-2 flex-1">
                            <span class="text-white/40 text-xs shrink-0">To</span>
                            <input type="time"
                                name="working_hours[{{ $index }}][end_time]"
                                value="{{ $existing ? $existing->end_time->format('H:i') : '17:00' }}"
                                class="flex-1 bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-cyan-400 transition-all duration-300"
                                style="color-scheme:dark;">
                        </div>

                        {{-- Duration display --}}
                        <span class="text-white/30 text-xs shrink-0 hidden md:block"
                              id="duration-{{ $index }}">
                            @if($existing && $existing->is_active)
                                {{ $existing->start_time->diffInHours($existing->end_time) }}h
                            @else
                                Day Off
                            @endif
                        </span>
                    </div>

                    {{-- Day Off Badge --}}
                    <span id="dayoff-badge-{{ $index }}"
                        class="text-white/30 text-xs bg-white/5 border border-white/10 rounded-full px-3 py-1 shrink-0
                            {{ $isActive ? 'hidden' : '' }}">
                        Day Off
                    </span>
                </div>
                @endforeach
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end mt-6 pt-4 border-t border-white/10">
                <button type="submit"
                    class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-8 py-3 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Schedule
                </button>
            </div>
        </form>
    </div>

    {{-- ===== HOLIDAYS SECTION ===== --}}
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden">

        {{-- Section Header --}}
        <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 style="font-family:'Syne',sans-serif;"
                    class="text-white font-semibold">
                    Holidays & Days Off
                </h2>
            </div>
            <span class="bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-full px-3 py-1 text-xs">
                {{ $holidays->count() }} holidays
            </span>
        </div>

        <div class="p-6">

            {{-- Add Holiday Form --}}
            <div class="bg-white/5 border border-white/10 rounded-xl p-5 mb-6">
                <h3 class="text-white/60 text-sm font-medium mb-4">Add New Holiday</h3>
                <form method="POST" action="{{ route('provider.holidays.store') }}"
                      class="flex flex-col md:flex-row gap-3">
                    @csrf

                    <div class="flex-1">
                        <input type="date"
                            name="date"
                            min="{{ date('Y-m-d') }}"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-cyan-400 transition-all duration-300 text-sm"
                            style="color-scheme:dark;"
                            required>
                    </div>

                    <div class="flex-1">
                        <input type="text"
                            name="reason"
                            placeholder="Reason (optional) — e.g. National Holiday, Vacation..."
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 transition-all duration-300 text-sm">
                    </div>

                    <button type="submit"
                        class="shrink-0 bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-5 py-2.5 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Holiday
                    </button>
                </form>
            </div>

            {{-- Holidays Table --}}
            @if($holidays->count() > 0)
            <div class="overflow-hidden rounded-xl border border-white/10">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10 bg-white/5">
                            <th class="px-4 py-3 text-left text-white/40 text-xs uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-white/40 text-xs uppercase tracking-wider">Day</th>
                            <th class="px-4 py-3 text-left text-white/40 text-xs uppercase tracking-wider">Reason</th>
                            <th class="px-4 py-3 text-left text-white/40 text-xs uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-white/40 text-xs uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($holidays as $holiday)
                        @php
                            $holidayDate = \Carbon\Carbon::parse($holiday->holiday_date);
                            $isPast = $holidayDate->isPast();
                            $isToday = $holidayDate->isToday();
                            $isSoon = $holidayDate->isFuture() && $holidayDate->diffInDays(now()) <= 7;
                        @endphp
                        <tr class="border-b border-white/5 last:border-0 hover:bg-white/5 transition-all duration-300">
                            <td class="px-4 py-3">
                                <span class="text-white text-sm font-medium">
                                    {{ $holidayDate->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-white/60 text-sm">
                                    {{ $holidayDate->format('l') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-white/60 text-sm">
                                    {{ $holiday->reason ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($isToday)
                                <span class="bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-full px-2 py-0.5 text-xs">
                                    Today
                                </span>
                                @elseif($isPast)
                                <span class="bg-white/5 text-white/30 border border-white/10 rounded-full px-2 py-0.5 text-xs">
                                    Past
                                </span>
                                @elseif($isSoon)
                                <span class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 rounded-full px-2 py-0.5 text-xs">
                                    Soon
                                </span>
                                @else
                                <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full px-2 py-0.5 text-xs">
                                    Upcoming
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST"
                                      action="{{ route('provider.holidays.destroy', $holiday) }}"
                                      onsubmit="return confirm('Remove this holiday?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-lg px-3 py-1 text-xs hover:bg-rose-500/30 transition-all duration-300">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-10">
                <div class="w-14 h-14 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-white/40 text-sm">No holidays added yet</p>
                <p class="text-white/20 text-xs mt-1">Add days when you won't be available</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Toggle JS --}}
<script>
function toggleDay(index) {
    const checkbox = document.getElementById('toggle_' + index);
    const timeInputs = document.getElementById('time-inputs-' + index);
    const dayoffBadge = document.getElementById('dayoff-badge-' + index);
    const dayLabel = document.getElementById('day-label-' + index);
    const row = document.getElementById('day-row-' + index);

    if (checkbox.checked) {
        timeInputs.style.opacity = '1';
        timeInputs.style.pointerEvents = 'auto';
        dayoffBadge.classList.add('hidden');
        dayLabel.classList.remove('text-white/40');
        row.classList.remove('bg-white/2', 'border-white/5');
        row.classList.add('bg-white/5', 'border-white/10');
    } else {
        timeInputs.style.opacity = '0.3';
        timeInputs.style.pointerEvents = 'none';
        dayoffBadge.classList.remove('hidden');
        dayLabel.classList.add('text-white/40');
        row.classList.add('bg-white/2', 'border-white/5');
        row.classList.remove('bg-white/5', 'border-white/10');
    }
}
</script>

@endsection
