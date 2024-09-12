<?php

namespace App\Livewire\Course;

use Livewire\Component;

class ReviewList extends Component
{

    public $courseId;
    public $reviews;

    protected $listeners = ['reviewSubmitted' => 'loadReviews'];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->loadReviews();
    }

    public function loadReviews()
    {
        $this->reviews = Review::where('course_id', $this->courseId)
            ->with('user')
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.course.review-list');
    }
}
