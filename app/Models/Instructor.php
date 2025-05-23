<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{
    use HasFactory;
    // protected $guarded = [];

    protected $fillable = [
        'user_id',
        'name',
        'bio',
        'profile_picture',
        'social_links',
        'expertise',
    ];
    

    public function course() : HasMany
    {
        return $this->hasMany(Course::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
