<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id'
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
}