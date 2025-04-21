<?php

namespace App\Livewire\Course;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Enrollment as EnrollmentModel;

class CourseEnrollment extends Component
{
    public $courseId;
    public $enrollment_status = false;
    public $successMessage = '';

    public function mount($courseId)
    {
        $this->courseId = $courseId;

        $this->enrollment_status = Auth::check()
            ? EnrollmentModel::where('user_id', Auth::id())
            ->where('course_id', $this->courseId)
            ->exists()
            : false;
    }

    public function enroll()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!$this->enrollment_status) {
            EnrollmentModel::create([
                'user_id' => Auth::id(),
                'course_id' => $this->courseId,
                'enrolled_at' => now(),
                'completion_status' => 'in-progress',
                'progress' => 0,
            ]);

            $this->enrollment_status = true;
            $this->successMessage = 'You have successfully enrolled in this course!';
            $this->dispatch('user-enrolled');
        }
    }

    public function render()
    {
        return view('livewire.course.course-enrollment');
    }
}
