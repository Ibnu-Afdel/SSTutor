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
    public $publishedCourses;
    public $archivedCourses;
    public $enrolledStudents;

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->role === 'instructor') {
            $instructorId = $user->id;

            $this->totalCourses = Course::where('instructor_id', $instructorId)->count();
            $this->inProgressCourses = Course::where('instructor_id', $instructorId)
                                             ->where('status', 'draft')
                                             ->count();
            $this->publishedCourses = Course::where('instructor_id', $instructorId)
                                            ->where('status', 'published')
                                            ->count();
            $this->archivedCourses = Course::where('instructor_id', $instructorId)
                                            ->where('status', 'archived')
                                            ->count();

            $this->enrolledStudents = Enrollment::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->distinct('student_id')->count();
        } else {
            $this->totalCourses = 0;
            $this->inProgressCourses = 0;
            $this->publishedCourses = 0;
            $this->archivedCourses = 0;
            $this->enrolledStudents = 0;
        }
    }

    public function render()
    {
        return view('livewire.instructor.dashboard');
    }
}
