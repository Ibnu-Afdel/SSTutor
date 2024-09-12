<?php

namespace App\Livewire\Course;

use App\Models\Course;
use App\Models\Lesson as LessonModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CourseDetail extends Component
{
    public $course;
    public $isInstructor = false;

    public function mount($courseId)
    {
        $this->course = Course::findOrFail($courseId);
        $this->isInstructor = Auth::check() && Auth::user()->role === 'instructor';
    }

    public function render()
    {
        $lessons = LessonModel::where('course_id', $this->course->id)->orderBy('order')->get();

        return view('livewire.course.course-detail', [
            'lessons' => $lessons,
        ]);
    }
}
