<?php

namespace App\Livewire\Course;

use App\Models\Course;
use App\Models\Enrollment  as EnrollmentModel;
use App\Models\Lesson as LessonModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CourseDetail extends Component
{
    public $course;
    public $username;
    public $isInstructor = false;



    public $enrollment_status = false;

    public function mount($courseId)
    {
        $this->username = Auth::user()->username;
        $this->course = Course::findOrFail($courseId);
        $this->isInstructor = Auth::check() && Auth::user()->role === 'instructor';

        if (Auth::check()) {
            $this->enrollment_status = EnrollmentModel::where('user_id', Auth::id())
                ->where('course_id', $this->course->id)
                ->exists();
        }
    }

    #[On('user-enrolled')]
    public function handleUserEnrolled()
    {
        $this->enrollment_status = true;
    }
    public function render()
    {
        $lessons = LessonModel::where('course_id', $this->course->id)->orderBy('order')->get();

        return view('livewire.course.course-detail', [
            'lessons' => $lessons,
        ]);
    }
}
