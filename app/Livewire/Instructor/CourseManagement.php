<?php

namespace App\Livewire\Instructor;

use App\Models\Category; 
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads; 

class CourseManagement extends Component
{
    use WithFileUploads; 

    public $courses = [];
    public $categories; 

    // --- Form State ---
    public $isCreatingOrEditing = false; 
    public $courseId;

    // --- Form Fields ---
    public $name, $description, $price, $level = 'beginner';
    public $start_date, $end_date, $status = 'draft', $enrollment_limit, $requirements, $syllabus;
    public $discount = true;
    public $discount_type;
    public $discount_value;
    public $duration;
    public $image;
    public $existingImageUrl; 
    public $instructor_id; 
    public $confirmingCourseDeletion = false;    

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            // required on create, optional on update
            'image' => ($this->courseId ? 'nullable' : 'required') . '|image|mimes:jpg,png,jpeg|max:2048',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|numeric|min:1', 
            'level' => 'required|in:beginner,intermediate,advanced',
            'start_date' => 'nullable|date', 
            'end_date' => 'nullable|date|after_or_equal:start_date', 
            'status' => 'required|in:draft,published,archived',
            'enrollment_limit' => 'nullable|integer|min:1',
            'requirements' => 'nullable|string',
            'syllabus' => 'nullable|string',
            
            'discount_type' => 'required_if:discount,true|nullable|in:percent,amount',
            'discount_value' => 'required_if:discount,true|nullable|numeric|min:0',
        ];
    }


    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->role === 'instructor') { 
             $this->courses = Course::where('instructor_id', $user->id)->get();
            // $this->categories = Category::all(); // Load if needed
        } else {
             abort(403, 'Unauthorized action.'); // Or redirect, flash message
        }
    }

    public function createCourse()
    {
        $this->resetInputFields();
        $this->isCreatingOrEditing = true; 
    }

    public function editCourse($id)
    {
        $user = Auth::user();
        $course = Course::find($id);

        if ($course && $user && $course->instructor_id == $user->id) {
            $this->courseId = $course->id;
            $this->name = $course->name;
            $this->description = $course->description;
            $this->price = $course->original_price ?? $course->price; 
            $this->discount = $course->discount;
            $this->discount_type = $course->discount_type;
            $this->discount_value = $course->discount_value;
            $this->level = $course->level;
            $this->start_date = $course->start_date ? $course->start_date->format('Y-m-d') : null; 
            $this->end_date = $course->end_date ? $course->end_date->format('Y-m-d') : null;     
            $this->duration = $course->duration;
            $this->enrollment_limit = $course->enrollment_limit;
            $this->requirements = $course->requirements;
            $this->syllabus = $course->syllabus;
            $this->existingImageUrl = $course->image ? asset('storage/' . $course->image) : null; 
            $this->status = $course->status; 

            $this->isCreatingOrEditing = true; 
        } else {
             session()->flash('error', 'Course not found or you are not authorized to edit it.');
             $this->isCreatingOrEditing = false;
        }
    }

     public function saveCourse()
    {
        $validatedData = $this->validate(); 

        $user = Auth::user();
        // Double-check authorization
        if (!$user || $user->role !== 'instructor') {
             abort(403, 'You are not authorized to save courses.');
        }

        // Handle image upload (only if a new image is provided)
        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('course-images', 'public');
        }

        // Prepare data array common to create and update
        $courseData = [
            'name' => $this->name,
            'description' => $this->description,
            'original_price' => $this->price, // Store the base price
            'duration' => $this->duration,
            'level' => $this->level,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status, 
            'enrollment_limit' => $this->enrollment_limit,
            'requirements' => $this->requirements,
            'syllabus' => $this->syllabus,
            'discount' => $this->discount,
            'discount_type' => $this->discount ? $this->discount_type : null, 
            'discount_value' => $this->discount ? $this->discount_value : null, 
            
        ];

        // Add image path only if a new one was uploaded
        if ($imagePath) {
            $courseData['image'] = $imagePath;
            // Optionally: delete the old image if updating
        }

        // Calculate final price based on discount
        $finalPrice = $this->price;
        if ($this->discount && $this->discount_type && $this->discount_value) {
            if ($this->discount_type === 'percent') {
                $finalPrice = $this->price * ((100 - $this->discount_value) / 100);
            } elseif ($this->discount_type === 'amount') {
                $finalPrice = max(0, $this->price - $this->discount_value); // Ensure price doesn't go below 0
            }
        }
        $courseData['price'] = $finalPrice; 


        if ($this->courseId) {
            // Update existing course
            $course = Course::find($this->courseId);
            if ($course && $course->instructor_id == $user->id) {
                 // If a new image was uploaded and an old one exists, delete the old one
                 if ($imagePath && $course->image) {
                     \Storage::disk('public')->delete($course->image);
                 }
                 $course->update($courseData);
                 session()->flash('message', 'Course updated successfully.');
            } else {
                 session()->flash('error', 'Failed to update course.');
                 return; 
            }
        } else {
            // Create new course
            $courseData['instructor_id'] = $user->id;
            Course::create($courseData);
            session()->flash('message', 'Course created successfully.');
        }

        $this->isCreatingOrEditing = false; 
        $this->resetInputFields(); 
        $this->courses = Course::where('instructor_id', $user->id)->get(); 
    }

    public function cancel()
    {
        $this->isCreatingOrEditing = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->courseId = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->discount = false;
        $this->discount_type = null;
        $this->discount_value = null;
        $this->level = 'beginner'; 
        $this->start_date = null;
        $this->end_date = null;
        $this->duration = '';
        $this->enrollment_limit = null;
        $this->requirements = '';
        $this->syllabus = '';
        $this->image = null; 
        $this->existingImageUrl = null;
        $this->status = 'draft'; 
        $this->resetValidation(); 
    }

    public function confirmCourseDeletion(Course $course)
    {
        $this->confirmingCourseDeletion = $course->id;
    }

    public function confirmDelete(Course $course)
    {
        // ensure only the instructor can delete their own course
        $user = Auth::user();
        if (!$course || $course->instructor_id !== $user->id) {
            session()->flash('error', 'Course not found or unauthorized.');
            $this->confirmingCourseDeletion = false;
            return;
        }
        
        $course->delete();
        session()->flash('message', 'Course deleted successfully.');
        $this->confirmingCourseDeletion = false;

        $this->courses = Course::where('instructor_id', $user->id)->get();
    }


    

    /**
     * Perform deletion after confirmation.
     */
    // public function deleteCourse($courseId)
    // {
    //     $user = Auth::user();
    //     $course = Course::find($courseId);
    //     if (!$course || $course->instructor_id !== $user->id) {
    //         session()->flash('error', 'Course not found or unauthorized.');
    //         $this->confirmingCourseDeletion = false;
    //         return;
    //     }
    //     $course->delete();
    //     session()->flash('message', 'Course deleted successfully.');
    //     $this->confirmingCourseDeletion = false;

    //     // refresh list
    //     $this->courses = Course::where('instructor_id', $user->id)->get();
    // }

    public function render()
    {
        return view('livewire.instructor.course-management', [
            'currentImageUrl' => $this->existingImageUrl,
        ]);
    }
}