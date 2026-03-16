<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles; 

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    
    public function provider()
    {
        return $this->hasOne(Provider::class);
    }

    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    public function hasRoleName($role)
    {
        return $this->hasRole($role); // Spatie built-in
    }

    /**
     * Get the reviews for the user (platform reviews and service reviews)
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
