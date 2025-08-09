<?php

namespace App\Observers;

// use App\Models\Instructor;
use App\Models\User;
use App\Services\UsernameGenerator;

class UserObserver
{
    public function creating(User $user) 
    {
        if (empty($user->username)) {
            $user->username = UsernameGenerator::generate($user->name?: $user->email);
        }
    }
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
       //
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
