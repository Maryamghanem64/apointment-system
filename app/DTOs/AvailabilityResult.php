<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailabilityResult
{
    public bool $isAvailable;
    public array $failures = []; // ['working_hours', 'holiday', 'overlap', ...]
    public ?Carbon $nearestAvailable = null;
    public array $suggestedSlots = [];
    public int $dailyRemainingSlots;

    public function __construct(
        bool $isAvailable,
        array $failures = [],
        ?Carbon $nearestAvailable = null,
        array $suggestedSlots = [],
        int $dailyRemainingSlots = 0
    ) {
        $this->isAvailable = $isAvailable;
        $this->failures = $failures;
        $this->nearestAvailable = $nearestAvailable;
        $this->suggestedSlots = $suggestedSlots;
        $this->dailyRemainingSlots = $dailyRemainingSlots;
    }

    public function toArray(): array
    {
        return [
            'isAvailable' => $this->isAvailable,
            'failures' => $this->failures,
            'nearestAvailable' => $this->nearestAvailable?->toISOString(),
            'suggestedSlots' => $this->suggestedSlots,
            'dailyRemainingSlots' => $this->dailyRemainingSlots,
        ];
    }

    public function addFailure(string $failure): self
    {
        $this->failures[] = $failure;
        return $this;
    }
}
