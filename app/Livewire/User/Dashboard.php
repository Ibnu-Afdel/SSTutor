<?php

namespace App\Livewire\User;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $enrolledCourses = [];
    public $completedCourses = [];
    public $inProgressCourses = [];

    public function mount()
    {
        $user = Auth::user();

        // Fetch enrolled courses
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course') // Eager load courses
            ->get();

        // Categorize courses
        $this->completedCourses = $enrollments->filter(fn($e) => $e->completion_status === 'completed')->map->course;
        $this->inProgressCourses = $enrollments->filter(fn($e) => $e->completion_status === 'in-progress')->map->course;
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }

}
