<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    use HasFactory;
    // protected $guarded = [];
    protected $fillable = [
        'name',
        'description',
        'images',
        'discount',
        'discount_type',
        'discount_value',
        'rating',
        'price',
        'original_price',
        'duration',
        'level',
        'start_date',
        'end_date',
        'status',
        'enrollment_limit',
        'requirements',
        'syllabus',
        'instructor_id',
        'is_pro'
    ];


    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'images'     => 'array',
    ];

    /**
     * Get the image URL for this course
     * 
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        if (is_array($this->images) && isset($this->images['url'])) {
            return $this->images['url'];
        }
        
        // Fallback for legacy data stored as paths
        if (is_string($this->images)) {
            if (filter_var($this->images, FILTER_VALIDATE_URL)) {
                return $this->images;
            }
            return asset('storage/' . $this->images);
        }
        
        return null;
    }

    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // public function lessons()
    // {
    //     return $this->hasMany(Lesson::class);
    // }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Section::class);
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

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }
}
