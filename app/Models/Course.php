<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category() : BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function instructor() : BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id' );
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function materials()
    {
        return $this->hasMany(CourseMaterial::class);
    }

    public function updates()
    {
        return $this->hasMany(CourseUpdate::class);
    }


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
