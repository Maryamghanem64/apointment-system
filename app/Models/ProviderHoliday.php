<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderHoliday extends Model
{
    protected $fillable = [
        'provider_id',
        'holiday_date',
        'reason'
    ];

    protected $casts = [
        'holiday_date' => 'date'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}