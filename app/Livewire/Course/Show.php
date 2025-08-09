<?php

namespace App\Livewire\Course;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;


// use App\Models\Enrollment as EnrollmentModel;
// use App\Models\Lesson as LessonModel;




class Show extends Component
{
    public Course $course;
    public $username;
    public $isInstructor = false;

    public $enrollment_status = false;
    public $completedLessonsIds = [];
    public $allCourseLessons,$firstCourseLesson,$nextUncompletedCourseLesson,$continueLearningLesson,$isNewEnrollment;

    public function mount()
    {
        if (Auth::check()){
            $user = Auth::user();
            $this->username = $user->username;
            $this->isInstructor = $user->role === 'instructor';

            $this->enrollment_status = $user->isEnrolledIn($this->course);

            if($this->enrollment_status){

                $this->allCourseLessons = $this->course
                ->sections()
                ->with(['lessons' => fn($q) => $q->orderBy('order_by')])
                ->orderBy('order_by')
                ->get()
                ->flatMap(fn($section) => $section->lessons);
        
                $this->completedLessonsIds = Auth::user()
                ->lessons()
                ->whereHas('section', fn($query) => $query->where('course_id' , $this->course->id))
                ->pluck('lessons.id')
                ->toArray();
        
                $this->firstCourseLesson = $this->allCourseLessons->first();
                $this->nextUncompletedCourseLesson = $this->allCourseLessons->first(
                    fn($lesson) => !in_array($lesson->id, $this->completedLessonsIds)
                );
                $this->continueLearningLesson = $this->nextUncompletedCourseLesson ?? $this->firstCourseLesson;
                $this->isNewEnrollment = empty($this->completedLessonsIds);
        
            } else {
                $this->enrollment_status = false;
                $this->completedLessonsIds = [];
                $this->allCourseLessons = collect();
                $this->firstCourseLesson = null;
                $this->nextUncompletedCourseLesson = null;
                $this->continueLearningLesson = null;
                $this->isNewEnrollment = false;
            }
        }

    }

    #[On('user-enrolled')]
    public function handleUserEnrolled()
    {
        $this->enrollment_status = true;
    }
    public function render()
    {
        // $lessons = LessonModel::where('course_id', $this->course->id)->orderBy('order')->get();

        return view('livewire.course.show');
    }
}
