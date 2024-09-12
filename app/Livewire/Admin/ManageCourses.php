<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;

class ManageCourses extends Component
{

    public $courses;

    public function mount()
    {
        $this->courses = Course::with('instructor')->get();
    }

    public function deleteCourse($id)
    {
        Course::findOrFail($id)->delete();
        $this->courses = Course::with('instructor')->get();
    }
    public function render()
    {
        return view('livewire.admin.manage-courses');
    }
}
