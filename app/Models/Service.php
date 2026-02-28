<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'duration',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'provider_service');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}