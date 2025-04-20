<?php

namespace App\Livewire\Course;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads;

    public $name, $description, $image, $price, $duration, $level = 'beginner';
    public $start_date, $end_date, $status = 'draft', $enrollment_limit, $requirements, $syllabus;
    public $discount = false; 
    public $discount_type; 
    public $discount_value; 
    public $instructor_id; 

    public function mount()
    {
        if (Auth::user()->role === 'instructor') {
            $this->instructor_id = Auth::id();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function saveCourse()
{
    $validatedData = $this->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        'price' => 'required|numeric|min:0',
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

  
    $imagePath = $this->image ? $this->image->store('course-images', 'public') : null;

    
    $finalPrice = $this->price;

    if ($this->discount && $this->discount_type === 'percent') {
        $finalPrice = $this->price * ((100 - $this->discount_value) / 100);
    } elseif ($this->discount && $this->discount_type === 'amount') {
        $finalPrice = max(0, $this->price - $this->discount_value);
    }

   
    $user = Auth::user();

    if ($user->role !== 'instructor') {
        abort(403, 'You are not authorized to create courses.');
    }

    Course::create([
        'name' => $this->name,
        'description' => $this->description,
        'image' => $imagePath,
        'price' => $finalPrice, // Use finalPrice instead of original price
        'original_price' => $this->price, // Save the original price
        'duration' => $this->duration,
        'level' => $this->level,
        'start_date' => $this->start_date,
        'end_date' => $this->end_date,
        'status' => $this->status,
        'enrollment_limit' => $this->enrollment_limit,
        'requirements' => $this->requirements,
        'syllabus' => $this->syllabus,
        'instructor_id' => $user->id, 
        'discount' => $this->discount,
        'discount_type' => $this->discount_type,
        'discount_value' => $this->discount_value,
        'rating' => null, 
    ]);

  
    return redirect()->route('courses.index');
}

    public function render()
    {
        return view('livewire.course.create');
    }
}
