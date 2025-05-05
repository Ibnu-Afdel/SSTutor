<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorApplication extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone_number',
        'date_of_birth',
        'adress',
        'webiste',
        'linkedin',
        'resume',
        'higest_qualification',
        'current_ocupation',
        'reason',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
