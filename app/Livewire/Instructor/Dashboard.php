<?php

namespace App\Livewire\Instructor;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalCourses;
    public $inProgressCourses;
    public $completedCourses;
    public $enrolledStudents;

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->instructor) {
            $instructorId = $user->instructor->id;

            $this->totalCourses = Course::where('instructor_id', $instructorId)->count();
            $this->inProgressCourses = Course::where('instructor_id', $instructorId)
                                             ->where('status', 'in-progress')
                                             ->count();
            $this->completedCourses = Course::where('instructor_id', $instructorId)
                                            ->where('status', 'completed')
                                            ->count();

            $this->enrolledStudents = Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->distinct('student_id')->count();
        } else {
            $this->totalCourses = 0;
            $this->inProgressCourses = 0;
            $this->completedCourses = 0;
            $this->enrolledStudents = 0;
        }
    }

    public function render()
    {
        return view('livewire.instructor.dashboard');
    }
}
