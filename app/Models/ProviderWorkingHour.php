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
        'start_time' => 'string',
        'end_time'   => 'string',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}