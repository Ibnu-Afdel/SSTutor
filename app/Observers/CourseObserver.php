<?php

namespace App\Observers;

use App\Models\Course;

class CourseObserver
{
    public function creating(Course $course)
    {
        if (empty($course->slug)) {
            $course->slug = $course->generateUniqueSlug($course->name, 'slug');
        }
    }

    public function updating(Course $course)
    {
        if ($course->isDirty('name')) {
            $course->slug = $course->generateUniqueSlug($course->name, 'slug');
        }
    }
    /**
     * Handle the Course "created" event.
     */
    public function created(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "updated" event.
     */
    public function updated(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "deleted" event.
     */
    public function deleted(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(Course $course): void
    {
        //
    }
}
