<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderApplication extends Model
{
    protected $fillable = [
        'user_id', 'full_name', 'email', 'phone',
        'specialty', 'experience', 'bio', 'status', 'admin_notes'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

