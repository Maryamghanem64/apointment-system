<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderWorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    protected function dayOfWeek(): Attribute
    {
        $map = [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 0,
        ];

        return Attribute::make(
            get: fn (string $value) => $map[$value] ?? (int) $value,
            set: fn (mixed $value) => $map[array_search((int) $value, array_values($map))] ?? $value,
        );
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForProvider($query, $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    public function isWorking(Carbon $datetime): bool
    {
        $dayNum = $datetime->dayOfWeek; // 0=sun,6=sat
        if ($this->day_of_week != $dayNum) {
            return false;
        }

        $dayStart = $datetime->copy()->startOfDay()->setTimeFromTimeString($this->start_time->format('H:i'));
        $dayEnd = $datetime->copy()->startOfDay()->setTimeFromTimeString($this->end_time->format('H:i'));

        if ($dayEnd->lt($dayStart)) {
            $dayEnd->addDay();
        }

        return $datetime->gte($dayStart) && $datetime->lte($dayEnd);
    }

    public function conflictsWith(Carbon $start, Carbon $end): bool
    {
        $dayNum = $start->dayOfWeek;
        if ($this->day_of_week != $dayNum) {
            return false;
        }

        $dayStart = $start->copy()->startOfDay()->setTimeFromTimeString($this->start_time->format('H:i'));
        $dayEnd = $start->copy()->startOfDay()->setTimeFromTimeString($this->end_time->format('H:i'));

        if ($dayEnd->lt($dayStart)) {
            $dayEnd->addDay();
        }

        // No conflict if appointment completely before or after working hours
        return !($end->lte($dayStart) || $start->gte($dayEnd));
    }
}

