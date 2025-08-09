<?php

namespace App\Models;

use App\Enums\Course\DiscountType;
use App\Enums\Course\Levels;
use App\Enums\Course\Status;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use HasSlug;
    // protected $guarded = [];
    protected $fillable = [
        'name',
        'description',
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
        'is_pro' => 'boolean',
        'status' =>  Status::class,
        'level' => Levels::class,
        'discount_type' => DiscountType::class
    ];

    public function getIsLockedAttribute(): bool
    {
        $user = Auth::user();
        return $this->is_pro && (! $user || !$user->is_pro);
    }

    public function getOriginalPriceAttribute()
    {
        return $this->original_price ?? $this->price;
    }

    public function getFinalPriceAttribute()
    {
        $price = $this->original_price ?? $this->price;
        if ($this->discount && $this->discount_value > 0){
            return match($this->discount_type){
                'percent' => $price *((100 - $this->discount_value) / 100),
                'amount' => max(0, $price - $this->discount_value),
                default => $price
            };
        }
        return $price;

    }

        public function getSyllabusSectionsAttribute()
        {
        return  $this->sections()
                    ->with(['lessons' => fn($q) => $q->orderBy('order')])
                    ->orderBy('order')
                    ->get();
                    
        }

        public function getRouteKeyName()
        {
            return 'slug';
        }

    /**
     * Get the image URL for this course
     * 
     * @return string|null
     */
    

    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }


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
