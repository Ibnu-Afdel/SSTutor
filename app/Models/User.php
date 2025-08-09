<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // Import the FilamentUser interface
use Filament\Panel; // Ensure you have this use statement
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser // Implement the FilamentUser interface
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'status',
        'is_pro',
        'username',
        'name',
        'email',
        'password',
        'pro_expires_at',
        'subscription_type',
        'subscription_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Allow users with admin role or emails ending with @buki.com
        return $this->role === 'admin' || str_ends_with($this->email, '@admin.com');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function instructor(): HasOne
    {
        return $this->hasOne(Instructor::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function followedBy()
    {
        return $this->hasMany(Follow::class, 'followed_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)
            ->withPivot('completed_at')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isEnrolledIn(Course $course)
    {
        return $this->enrollments()->where('course_id', $course->id)->exists();
    }

    // return Enrollment::where('user_id', $this->id)
    // ->where('course_id', $course->id)
    // ->exists();

}
