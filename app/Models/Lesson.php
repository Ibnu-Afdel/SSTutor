<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function course()
    // {
    //     return $this->belongsTo(Course::class);
    // }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('completed_at')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function course(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
