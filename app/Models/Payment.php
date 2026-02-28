<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const STATUS_UNPAID   = 'unpaid';
    const STATUS_PAID     = 'paid';
    const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'appointment_id',
        'amount',
        'status',
        'payment_method',
        'transaction_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}