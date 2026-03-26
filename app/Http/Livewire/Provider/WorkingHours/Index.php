<?php

namespace App\Http\Livewire\Provider\WorkingHours;

use App\Models\Provider;
use App\Models\ProviderWorkingHour;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public Provider $provider;
    public array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    public array $hours = [];
    public bool $bulkSelectAll = false;
    public array $selectedDays = [];
    public $tempStart = '';
    public $tempEnd = '';
    public $editingDay = null;

    public function mount($providerId)
    {
        $this->provider = Provider::findOrFail($providerId);
        $this->authorize('viewAny', ProviderWorkingHour::class);

        $this->reloadHours();
    }

    private function reloadHours()
    {
        $this->hours = collect($this->days)->mapWithKeys(function ($day) {
            $hour = $this->provider->fresh()->workingHours->firstWhere('day_of_week', $day);
            return [$day => [
                'id' => $hour?->id,
                'day_of_week' => $day,
                'start_time' => $hour?->start_time?->format('H:i') ?? '',
                'end_time' => $hour?->end_time?->format('H:i') ?? '',
                'is_active' => $hour?->is_active ?? false,
            ]];
        })->toArray();
    }

    public function updated($propertyName)
    {
        // Debounce: Livewire dispatches events or use wire:model.blur
    }

    public function editDay($day)
    {
        $this->editingDay = $day;
        $hour = $this->hours[$day];
        $this->tempStart = $hour['start_time'];
        $this->tempEnd = $hour['end_time'];
    }

    public function saveDay($day)
    {
        $this->validate([
            'tempStart' => 'required|date_format:H:i',
            'tempEnd' => 'required|date_format:H:i|after:tempStart',
        ]);

        try {
            $data = [
                'day_of_week' => $day,
                'start_time' => $this->tempStart,
                'end_time' => $this->tempEnd,
                'is_active' => $this->hours[$day]['is_active'] ?? true,
                'provider_id' => $this->provider->id,
            ];

            if ($this->hours[$day]['id']) {
                $workingHour = ProviderWorkingHour::find($this->hours[$day]['id']);
                $this->authorize('update', $workingHour);
                $workingHour->update($data);
            } else {
                $this->authorize('create', ProviderWorkingHour::class);
                $workingHour = ProviderWorkingHour::create($data);
            }

            $this->editingDay = null;
            $this->tempStart = '';
            $this->tempEnd = '';
            session()->flash('message', 'Working hours updated successfully.');
        } catch (\Exception $e) {
            $this->addError('tempStart', 'Failed to save working hours. Please try again.');
            $this->addError('tempEnd', 'Failed to save working hours. Please try again.');
        }
    }

    public function toggleActive($day)
    {
        try {
            $data = $this->hours[$day];
            if ($data['id']) {
                $workingHour = ProviderWorkingHour::find($data['id']);
                $this->authorize('update', $workingHour);
                $workingHour->update(['is_active' => !$data['is_active']]);
            } else {
                // Create inactive
                ProviderWorkingHour::create([
                    'provider_id' => $this->provider->id,
                    'day_of_week' => $day,
                    'is_active' => false,
                ]);
            }
            $this->reloadHours();
        } catch (\Exception $e) {
            $this->addError('general', 'Failed to toggle working hours.');
        }
    }

    public function deleteDay($day)
    {
        try {
            if ($this->hours[$day]['id']) {
                $workingHour = ProviderWorkingHour::find($this->hours[$day]['id']);
                $this->authorize('delete', $workingHour);
                $workingHour->delete();
            }
            $this->reloadHours();
        } catch (\Exception $e) {
            $this->addError('general', 'Failed to delete working hours.');
        }
    }

    public function copyToWeekdays()
    {
        try {
            $sourceDay = $this->days[0]; // Monday default
            $source = $this->hours[$sourceDay];
            if (!$source['start_time']) return;

            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            foreach ($weekdays as $day) {
                $data = [
                    'provider_id' => $this->provider->id,
                    'day_of_week' => $day,
                    'start_time' => $source['start_time'],
                    'end_time' => $source['end_time'],
                    'is_active' => $source['is_active'],
                ];
                
                if ($this->hours[$day]['id']) {
                    $workingHour = ProviderWorkingHour::find($this->hours[$day]['id']);
                    $this->authorize('update', $workingHour);
                    $workingHour->update([
                        'start_time' => $source['start_time'],
                        'end_time' => $source['end_time'],
                        'is_active' => $source['is_active'],
                    ]);
                } else {
                    $this->authorize('create', ProviderWorkingHour::class);
                    ProviderWorkingHour::create($data);
                }
            }
            $this->reloadHours();
            session()->flash('message', 'Copied to weekdays.');
        } catch (\Exception $e) {
            $this->addError('general', 'Failed to copy working hours.');
        }
    }

    public function bulkToggle()
    {
        foreach ($this->selectedDays as $day) {
            $this->toggleActive($day);
        }
        $this->reloadHours();
        $this->selectedDays = [];
    }

    public function selectAll()
    {
        $this->bulkSelectAll = !$this->bulkSelectAll;
        $this->selectedDays = $this->bulkSelectAll ? $this->days : [];
    }

    public function render()
    {
        return view('livewire.provider.working-hours.index');
    }
}

