<?php

namespace App\Livewire\Course;

use App\Models\Comment;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LessonComments extends Component
{
    public Lesson $lesson;
    public string $body = '';

    protected $rules = [
        'body' => 'required|string|min:2|max:1000'
    ];

    public function save()
    {
        $this->validate();

        Comment::create([
            'lesson_id' => $this->lesson->id,
            'user_id' => Auth::id(),
            'body' => $this->body
        ]);

        $this->reset('body');
    }

    public function render()
    {
        return view('livewire.course.lesson-comments', [
            'comments' => $this->lesson->comments()->with('user')->get(),
        ]);
    }
}
