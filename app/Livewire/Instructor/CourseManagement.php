<?php

namespace App\Livewire\Instructor;

use App\Models\Category;
use App\Models\Course;
use App\Traits\CloudinaryUpload;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CourseManagement extends Component
{
    use WithFileUploads, CloudinaryUpload;

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
    public $is_pro = false;

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

    public function editCourse($courseId)
    {
        $this->courseId = $courseId;
        $course = Course::findOrFail($courseId);
        $this->name = $course->name;
        $this->description = $course->description;
        $this->price = $course->original_price ?? $course->price;
        $this->level = $course->level;
        $this->duration = $course->duration;
        
        $this->start_date = optional($course->start_date)->format('Y-m-d');
        $this->end_date = optional($course->end_date)->format('Y-m-d');
        $this->status = $course->status;
        $this->enrollment_limit = $course->enrollment_limit;
        $this->requirements = $course->requirements;
        $this->syllabus = $course->syllabus;
        $this->discount = (bool) $course->discount;
        $this->discount_type = $course->discount_type;
        $this->discount_value = $course->discount_value;
        $this->is_pro = $course->is_pro;
        
        // Set existing image URL
        $this->existingImageUrl = $course->imageUrl;
        
        $this->isCreatingOrEditing = true;
    }

    public function saveCourse()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
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
        ]);

        $finalPrice = $this->price;

        if ($this->discount && $this->discount_type === 'percent') {
            $finalPrice = $this->price * ((100 - $this->discount_value) / 100);
        } elseif ($this->discount && $this->discount_type === 'amount') {
            $finalPrice = max(0, $this->price - $this->discount_value);
        }

        if ($this->courseId) {
            // Update existing course
            $course = Course::findOrFail($this->courseId);
            
            // Handle image upload
            $imageData = $course->images;
            if ($this->image) {
                // Delete the old image from Cloudinary if it exists
                if (is_array($course->images) && isset($course->images['public_id'])) {
                    $this->deleteFromCloudinary($course->images['public_id']);
                }
                
                // Upload the new image to Cloudinary
                $imageData = $this->uploadToCloudinary($this->image, 'course-images');
            }
            
            $course->update([
                'name' => $this->name,
                'description' => $this->description,
                'images' => $imageData,
                'price' => $finalPrice,
                'original_price' => $this->price,
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
                'is_pro' => $this->is_pro,
            ]);

            session()->flash('message', 'Course updated successfully.');
        } else {
            // Create new course
            // Upload to Cloudinary if image exists
            $imageData = null;
            if ($this->image) {
                $imageData = $this->uploadToCloudinary($this->image, 'course-images');
            }

            Course::create([
                'name' => $this->name,
                'description' => $this->description,
                'images' => $imageData,
                'price' => $finalPrice,
                'original_price' => $this->price,
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
                'instructor_id' => Auth::id(),
                'is_pro' => $this->is_pro,
                'rating' => null,
            ]);

            session()->flash('message', 'Course created successfully.');
        }

        $this->reset(['courseId', 'name', 'description', 'image', 'price', 'level', 'start_date', 'end_date', 'status', 'enrollment_limit', 'requirements', 'syllabus', 'discount', 'discount_type', 'discount_value', 'existingImageUrl', 'is_pro']);
        $this->isCreatingOrEditing = false;
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
