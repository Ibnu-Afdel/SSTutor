<?php

namespace App\Observers;

use App\Models\Instructor;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if ($user->role === 'instructor') {
            // Check if instructor already exists
            if (!Instructor::where('user_id', $user->id)->exists()) {
                Instructor::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    // Add other fields as needed
                ]);
            }
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
