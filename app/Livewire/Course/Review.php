<?php

namespace App\Livewire\Course;
use App\Models\Review as ReviewModel;
use App\Models\Enrollment as EnrollmentModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Review extends Component
{
    public $courseId;
    public $rating = 0; // Initialize rating
    public $reviewText = '';
    public $reviews = [];
    public $isEnrolled = false;

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->reviews = ReviewModel::where('course_id', $this->courseId)->get();
        
        if (Auth::check()) {
            $this->isEnrolled = EnrollmentModel::where('course_id', $this->courseId)
                ->where('user_id', Auth::id())
                ->exists();
        }
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function submitReview()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        // Check if the user is enrolled in the course
        if (!$this->isEnrolled) {
            session()->flash('error', 'You must be enrolled in the course to submit a review.');
            return;
        }

        $validatedData = $this->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'reviewText' => 'nullable|string',
        ]);

        ReviewModel::updateOrCreate(
            ['user_id' => $userId, 'course_id' => $this->courseId],
            ['rating' => $this->rating, 'review' => $this->reviewText]
        );

        $this->reviews = ReviewModel::where('course_id', $this->courseId)->get();

        // Reset form
        $this->reset('rating', 'reviewText');

        session()->flash('success', 'Review submitted successfully.');
    }
    public function render()
    {
        return view('livewire.course.review', [
            'reviews' => $this->reviews,
            'isEnrolled' => $this->isEnrolled,
        ]);
    }
}
