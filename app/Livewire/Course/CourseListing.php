<?php

namespace App\Livewire\Course;

use App\Models\Course;
use Livewire\Component;

class CourseListing extends Component
{
    public $courses;

    public function mount()
    {
        // Fetch all courses (you can paginate or filter as needed) m
        $this->courses = Course::all();
    }

    

    public function render()
    {
        return view('livewire.course.course-listing');
    }
}
