<?php

namespace App\Livewire\Course;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $courseId;
    public $name, $description, $image, $price, $duration, $level;
    public $start_date, $end_date, $status, $enrollment_limit, $requirements, $syllabus;
    public $original_price;
    public $discount = false; // Toggle for discount
    public $discount_type; // 'percent' or 'amount'
    public $discount_value; // Value for discount (numeric)
    public $instructor_id; // Instructor's ID

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $course = Course::findOrFail($courseId);

        // Check if the logged-in user is the instructor of the course
        if (Auth::id() !== $course->instructor_id) {
            abort(403, 'Unauthorized action.');
        }

        // Load course data into properties
        $this->name = $course->name;
        $this->description = $course->description;
        $this->price = $course->original_price;
        $this->original_price = $course->original_price;
        $this->duration = $course->duration;
        $this->level = $course->level;
        $this->start_date = $course->start_date;
        $this->end_date = $course->end_date;
        $this->status = $course->status;
        $this->enrollment_limit = $course->enrollment_limit;
        $this->requirements = $course->requirements;
        $this->syllabus = $course->syllabus;
        $this->discount = $course->discount;
        $this->discount_type = $course->discount_type;
        $this->discount_value = $course->discount_value;
        $this->instructor_id = $course->instructor_id;
    }

    

    public function updateCourse()
    {
        // Validation rules
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            // 'price' => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'duration' => 'nullable|numeric|min:1',
            'level' => 'required|in:beginner,intermediate,advanced',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'enrollment_limit' => 'nullable|integer|min:1',
            'requirements' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'discount_type' => 'nullable|in:percent,amount',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        // Find the course to update
        $course = Course::findOrFail($this->courseId);

        // Handle image upload
        $imagePath = $this->image ? $this->image->store('course-images', 'public') : $course->image;

        // Calculate final price with discount if applicable
        $finalPrice = $this->price;

        if ($this->discount && $this->discount_type === 'percent') {
            $finalPrice = $this->price * ((100 - $this->discount_value) / 100);
        } elseif ($this->discount && $this->discount_type === 'amount') {
            $finalPrice = max(0, $this->price - $this->discount_value);
        }

        // Update the course with validated data and final price
        $course->update([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $imagePath,
            'price' => $finalPrice,
            'original_price' => $this->price, // Save the original price
            'duration' => $this->duration,
            'level' => $this->level,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'enrollment_limit' => $this->enrollment_limit,
            'requirements' => $this->requirements,
            'syllabus' => $this->syllabus,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
        ]);

        // Redirect to the courses listing
        session()->flash('message', 'Course updated successfully.');
        return redirect()->route('courses.index');
    }

    public function render()
    {
        return view('livewire.course.edit');
    }
}
