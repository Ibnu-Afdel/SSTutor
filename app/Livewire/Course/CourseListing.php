<?php

namespace App\Livewire\Course;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CourseListing extends Component
{
    public $courses;
    public $is_pro = false;

    public function mount()
    {
        // Fetch all courses (you can paginate or filter as needed) m
        $this->courses = Course::all();
    }



    public function render()
    {
        if (Auth::check()) {
            // Make sure your User model has an 'is_pro' attribute/column or accessor
            $this->is_pro = (bool) Auth::user()->is_pro; // Cast to boolean
        } else {
            // If no user is logged in, they are not pro
            $this->is_pro = false;
        }
        return view('livewire.course.course-listing');
    }
}
