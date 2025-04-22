<?php

namespace App\Livewire\User;

use Livewire\Component;

class CourseCard extends Component
{
    public $course;

    public function mount($course)
    {
        $this->course = $course;
    }
    public function render()
    {
        return view('livewire.user.course-card');
    }
}
