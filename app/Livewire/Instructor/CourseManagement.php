<?php

namespace App\Livewire\Instructor;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CourseManagement extends Component
{
    public $courses = []; // Initialize as an empty array or collection
    public $categories;
    public $courseId;
    public $name;
    public $description;
    public $image;
    public $price;
    public $discount = false;
    public $discount_type;
    public $discount_value;
    public $level;
    public $start_date;
    public $end_date;
    public $duration;
    public $enrollment_limit;
    public $requirements;
    public $syllabus;

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->instructor) {
            $instructorId = $user->instructor->id;

            $this->courses = Course::where('instructor_id', $instructorId)->get();
            $this->categories = Category::all(); // Assuming you want to show categories
        }
    }

    public function createCourse()
    {
        $this->resetInputFields();
        $this->courseId = null; // Ensure no course ID is set for new course
    }

    public function editCourse($id)
    {
        $course = Course::find($id);

        if ($course && $course->instructor_id == Auth::user()->instructor->id) {
            $this->courseId = $course->id;
            $this->name = $course->name;
            $this->description = $course->description;
            $this->price = $course->price;
            $this->discount = $course->discount;
            $this->discount_type = $course->discount_type;
            $this->discount_value = $course->discount_value;
            $this->level = $course->level;
            $this->start_date = $course->start_date;
            $this->end_date = $course->end_date;
            $this->duration = $course->duration;
            $this->enrollment_limit = $course->enrollment_limit;
            $this->requirements = $course->requirements;
            $this->syllabus = $course->syllabus;
        }
    }

    public function saveCourse()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image',
            'discount' => 'boolean',
            'discount_type' => 'nullable|in:percent,amount',
            'discount_value' => 'nullable|numeric',
            'level' => 'required|in:beginner,intermediate,advanced',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'duration' => 'required|numeric',
            'enrollment_limit' => 'nullable|numeric',
            'requirements' => 'nullable|string',
            'syllabus' => 'nullable|string',
        ]);

        $courseData = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'level' => $this->level,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'duration' => $this->duration,
            'enrollment_limit' => $this->enrollment_limit,
            'requirements' => $this->requirements,
            'syllabus' => $this->syllabus,
        ];

        if ($this->courseId) {
            $course = Course::find($this->courseId);
            $course->update($courseData);
        } else {
            $courseData['instructor_id'] = Auth::user()->instructor->id;
            Course::create($courseData);
        }

        session()->flash('message', 'Course saved successfully.');
        $this->resetInputFields();
        $this->courses = Course::where('instructor_id', Auth::user()->instructor->id)->get(); // Refresh course list
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->discount = false;
        $this->discount_type = '';
        $this->discount_value = '';
        $this->level = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->duration = '';
        $this->enrollment_limit = '';
        $this->requirements = '';
        $this->syllabus = '';
    }

    public function render()
    {
        return view('livewire.instructor.course-management');
    }    
}
