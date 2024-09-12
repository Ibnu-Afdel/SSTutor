<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{

    public $user;
    public $enrolledCourses;
    public $instructorCourses;

    public function mount()
    {
        $this->user = Auth::user();
        
        if ($this->user->role == 'student') {
            $this->enrolledCourses = $this->user->enrollments->pluck('course');
        } elseif ($this->user->role == 'instructor') {
            $this->instructorCourses = $this->user->courses;
        }
    }

    public function render()
    {
        return view('livewire.user.profile');
    }
}
