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

    protected $casts = [
        'screenshot_path' => 'array',
    ];

    /**
     * Get the screenshot URL
     * 
     * @return string|null
     */
    public function getScreenshotUrlAttribute()
    {
        if (is_array($this->screenshot_path) && isset($this->screenshot_path['url'])) {
            return $this->screenshot_path['url'];
        }
        
        // Fallback for legacy data stored as paths
        if (is_string($this->screenshot_path)) {
            if (filter_var($this->screenshot_path, FILTER_VALIDATE_URL)) {
                return $this->screenshot_path;
            }
            return asset('storage/' . $this->screenshot_path);
        }
        
        return null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
