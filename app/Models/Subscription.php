<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{

    protected $fillable = [
        'user_id',
        'payment_method',
        'status',
        'amount',
        'duration_in_days',
        'screenshot_path',
        'transaction_reference',
        'paid_at',
        'starts_at',
        'expires_at',
        'notes'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
