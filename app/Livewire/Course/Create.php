<?php

namespace App\Livewire\Course;

use App\Enums\Course\DiscountType;
use App\Enums\Course\Levels;
use App\Enums\Course\Status;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
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
    public $is_pro = false;

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
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|numeric|min:1',
            'level' => ['required', new Enum(Levels::class)],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => ['required', new Enum(Status::class)],
            'enrollment_limit' => 'nullable|integer|min:1',
            'requirements' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'discount_type' => ['nullable', new Enum(DiscountType::class)],
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        // Upload image to local storage if exists
        // $imageData = null;
        // if ($this->image) {
        //     $path = $this->image->store('course-images', 'public');
        //     $imageData = ['path' => $path];
        // }


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

        $course = Course::create([
            'is_pro' => (bool) $this->is_pro,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $finalPrice,
            'original_price' => $this->price,
            'duration' => $this->duration,
            'level' => $this->level,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'enrollment_limit' => $this->enrollment_limit,
            'requirements' => $this->requirements,
            // 'syllabus' => $this->syllabus,
            'instructor_id' => $user->id,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            // 'rating' => $this->rating ?: null,
        ]);

        if ($this->image) {
            $course->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('images');
        }

        return redirect()->route('courses.index');
    }

    public function render()
    {
        $courses = Course::get();
        return view('livewire.course.create');
    }
}
