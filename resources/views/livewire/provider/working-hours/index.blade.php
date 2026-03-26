@if(session()->has('message'))
<div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400">
    {{ session('message') }}
</div>
@endif

<div class="flex items-center justify-between mb-8">
    <h2 class="text-3xl font-bold text-white font-heading">Working Hours Schedule</h2>
    <div class="flex gap-3">
        <button wire:click="copyToWeekdays" class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-6 py-3 rounded-xl hover:shadow-lg hover:shadow-emerald-500/25 hover:-translate-y-1 transition-all font-medium">
            📋 Copy to Weekdays
        </button>
        <button wire:click="selectAll" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl hover:shadow-lg hover:shadow-slate-500/25 hover:-translate-y-1 transition-all font-medium">
            {{ $bulkSelectAll ? 'Deselect All' : 'Select All' }}
        </button>
        @if(count($selectedDays) > 0)
        <button wire:click="bulkToggle" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl hover:shadow-lg hover:shadow-indigo-500/25 hover:-translate-y-1 transition-all font-medium">
            {{ $bulkSelectAll ? 'Deactivate Selected' : 'Toggle Selected' }}
        </button>
        @endif
    </div>
</div>

<div class="glass-card rounded-xl p-8">
    @if(count($hours) === 7 && collect($hours)->every(fn($h) => !$h['id']))
    <div class="text-center py-16">
        <svg class="mx-auto h-16 w-16 text-white/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="text-2xl font-bold text-white mb-2">No Working Hours Set</h3>
        <p class="text-white/60 mb-6 max-w-md mx-auto">Set your availability so clients can book appointments during your working hours.</p>
        <div class="flex flex-wrap gap-3 justify-center">
            <button wire:click="toggleActive('monday')" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl hover:shadow-lg transition-all font-medium">
                Quick Setup Monday-Friday
            </button>
        </div>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-white/10">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/60 uppercase tracking-wider w-12">Select</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/60 uppercase tracking-wider">Day</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/60 uppercase tracking-wider">Start Time</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/60 uppercase tracking-wider">End Time</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/60 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/60 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @foreach($days as $day)
                <tr class="hover:bg-white/5 transition-colors @if($editingDay === $day) bg-emerald-500/10 @endif">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" wire:model.live="selectedDays" value="{{ $day }}" class="w-4 h-4 text-emerald-500 rounded border-white/20 bg-white/5 focus:ring-emerald-500 focus:ring-2">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-white font-medium capitalize text-sm">{{ ucfirst($day) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($editingDay === $day)
                            <input type="time" step="900" wire:model.live="tempStart" class="input-dark w-24 text-white text-sm">
                        @else
                            <span class="text-white/80 text-sm">{{ $hours[$day]['start_time'] ?: '—' }}</span>
                            @if($hours[$day]['start_time'])
                                <button wire:click="editDay('{{ $day }}')" class="ml-2 p-1.5 bg-white/10 hover:bg-white/20 rounded-lg transition-all text-cyan-400 hover:text-cyan-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.5h3m1 1v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h6.5z"/>
                                    </svg>
                                </button>
                            @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($editingDay === $day)
                            <div class="flex gap-2 items-center">
                                <input type="time" step="900" wire:model.live="tempStart" class="input-dark w-24 text-white text-sm" x-ref="tempStart">
                                <div class="flex flex-col">
                                    @error('tempStart')
                                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <input type="time" step="900" wire:model.live="tempEnd" class="input-dark w-24 text-white text-sm" x-ref="tempEnd">
                                <div class="flex flex-col">
                                    @error('tempEnd')
                                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button wire:click="saveDay('{{ $day }}')" class="px-4 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm rounded-xl hover:shadow-lg transition-all font-medium whitespace-nowrap">
                                    Save Changes
                                </button>
                                <button wire:click="$set('editingDay', null)" class="px-3 py-1.5 bg-slate-600/50 text-white/80 text-sm rounded-lg hover:bg-slate-500/70 transition-all">
                                    Cancel
                                </button>
                            </div>
                            @error('general')
                                <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        @else
                            <span class="text-white/80 text-sm">{{ $hours[$day]['end_time'] ?: '—' }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $hours[$day]['is_active'] ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-500/20 text-slate-400 border border-slate-500/30' }}">
                            {{ $hours[$day]['is_active'] ? 'Active' : 'Inactive' }}
                        </span>
                        <label class="relative inline-flex items-center mt-1 cursor-pointer ml-2">
                            <input type="checkbox" wire:click="toggleActive('{{ $day }}')" {{ $hours[$day]['is_active'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if($hours[$day]['id'])
                            <button wire:click="deleteDay('{{ $day }}')" class="text-rose-400 hover:text-rose-300 p-2 rounded-lg hover:bg-rose-500/10 transition-all" onclick="return confirm('Delete {{ ucfirst($day) }} working hours?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<div class="mt-8 p-6 bg-slate-900/50 border border-white/10 rounded-xl">
    <h4 class="text-white font-semibold mb-2">ℹ️ Quick Tips</h4>
    <ul class="text-white/60 text-sm space-y-1 list-disc list-inside">
        <li>Click edit icon to modify times, toggle switch for active/inactive</li>
        <li>Bulk select days and toggle status for multiple days</li>
        <li>Copy Monday schedule to all weekdays with one click</li>
        <li>Clients only see active working hours for booking</li>
        <li>15min slot increments (step=900 seconds)</li>
    </ul>
</div>

<style>
input[type="time"] {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: white;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    backdrop-filter: blur(10px);
}
input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    opacity: 0.8;
}
input[type="time"]:focus {
    border-color: #22d3ee;
    box-shadow: 0 0 0 3px rgba(34,211,238,0.15);
    outline: none;
}
</style>

