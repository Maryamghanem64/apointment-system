<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'status', 'is_verified'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'provider_service');
    }

    public function workingHours()
    {
        return $this->hasMany(ProviderWorkingHour::class);
    }

    public function holidays()
    {
        return $this->hasMany(ProviderHoliday::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating');
    }

    /**
     * Get working hours summary as a formatted string
     */
    public function getWorkingHoursSummary()
    {
        $workingHours = $this->workingHours;
        
        if ($workingHours->isEmpty()) {
            return 'Not set';
        }

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $summary = [];
        
        foreach ($workingHours->groupBy('day_of_week') as $day => $hours) {
            $dayName = isset($days[$day]) ? $days[$day] : 'Unknown';
            $timeRange = $hours->pluck('start_time')->implode('-') . ' - ' . $hours->pluck('end_time')->implode('-');
            $summary[] = "{$dayName}: {$timeRange}";
        }
        
        return implode(', ', $summary);
    }

    /**
     * Get holiday count
     */
    public function getHolidayCount()
    {
        return $this->holidays()->count();
    }

    /**
     * Get assigned services count
     */
    public function getServicesCount()
    {
        return $this->services()->count();
    }
}
