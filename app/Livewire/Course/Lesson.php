<?php

namespace App\Livewire\Course;


use Livewire\Component;
use App\Models\Lesson as LessonModel;
use Illuminate\Support\Facades\Auth;

class Lesson extends Component
{
    public $courseId;
    public $title, $content, $video_url, $duration, $order;
    public $lessons;

    public function mount($courseId)
    {
        $this->courseId = $courseId;

        if (Auth::user() && Auth::user()->role === 'instructor') {
            $this->lessons = LessonModel::where('course_id', $courseId)->orderBy('order')->get();
        } else {
            $this->lessons = [];
        }
    }

    public function addLesson()
    {
        if (Auth::user() && Auth::user()->role === 'instructor') {
            $validatedData = $this->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'video_url' => 'nullable|url',
                'duration' => 'nullable|integer|min:1',
                'order' => 'nullable|integer|min:1',
            ]);

            LessonModel::create([
                'course_id' => $this->courseId,
                'title' => $this->title,
                'content' => $this->content,
                'video_url' => $this->video_url,
                'duration' => $this->duration,
                'order' => $this->order ?? 0,
            ]);

            $this->resetForm();
            $this->lessons = LessonModel::where('course_id', $this->courseId)->orderBy('order')->get();
        }
    }

    public function deleteLesson($lessonId)
    {
        if (Auth::user() && Auth::user()->role === 'instructor') {
            LessonModel::findOrFail($lessonId)->delete();
            $this->lessons = LessonModel::where('course_id', $this->courseId)->orderBy('order')->get();
        }
    }

    public function resetForm()
    {
        $this->title = '';
        $this->content = '';
        $this->video_url = '';
        $this->duration = '';
        $this->order = '';
    }

    public function render()
    {
        return view('livewire.course.lesson');
    }
}
