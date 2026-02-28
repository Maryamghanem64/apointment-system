<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderWorkingHour extends Model
{
    protected $fillable = [
        'provider_id',
        'day_of_week',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}