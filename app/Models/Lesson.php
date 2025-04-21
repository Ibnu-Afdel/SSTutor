<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
