<?php

namespace App\Livewire\User;

use App\Models\InstructorApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{

    public $user;
    public $enrolledCourses;
    public $instructorCourses;

    public function mount($username)
    {
        $this->user = $this->user = User::where('username', $username)->firstOrFail();
        // Auth::user()->where('username', $username)->firstOrFail();

        if ($this->user->role == 'student') {
            $this->enrolledCourses = $this->user->enrollments->pluck('course');
        } elseif ($this->user->role == 'instructor') {
            $this->instructorCourses = $this->user->courses;
        }
    }


    public function render()
    {
        // if (!isset($application)) {
        //     $application = class_exists(\App\Models\InstructorApplication::class)
        //         ? \App\Models\InstructorApplication::where('user_id', $user->id)->latest()->first()
        //         : null;
        // }
        $application = InstructorApplication::where('user_id', $this->user->id)->latest()->first();
        return view('livewire.user.profile', [
            'application' => $application
        ]);
    }
}
