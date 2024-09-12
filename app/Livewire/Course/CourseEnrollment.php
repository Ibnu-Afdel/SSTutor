<?php

namespace App\Livewire\Course;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Enrollment as EnrollmentModel;

class CourseEnrollment extends Component
{
    public $courseId;
    public $enrollment_status = false;

    public function mount($courseId)
    {
        $this->courseId = $courseId;

        // Check if the user is authenticated
        if (Auth::check()) {
            $this->checkEnrollmentStatus();
        } else {
            $this->enrollment_status = false;
        }
    }

    public function checkEnrollmentStatus()
    {
        $userId = Auth::id();

        // Check if the user is already enrolled in the course
        $this->enrollment_status = EnrollmentModel::where('user_id', $userId)
            ->where('course_id', $this->courseId)
            ->exists();
    }

    public function enroll()
    {
        if (!Auth::check()) {
            // Redirect if not authenticated
            return redirect()->route('login');
        }

        $userId = Auth::id();

        // Enroll the user if they are not already enrolled
        if (!$this->enrollment_status) {
            EnrollmentModel::create([
                'user_id' => $userId,
                'course_id' => $this->courseId,
                'enrolled_at' => now(),
                'completion_status' => 'in-progress',
                'progress' => 0,
            ]);

            $this->enrollment_status = true;
        }
    }

    public function render()
    {
        return view('livewire.course.course-enrollment', [
            'enrollment_status' => $this->enrollment_status,
        ]);
    }
}
