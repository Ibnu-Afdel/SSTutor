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
    public ?int $parentId = null;

    protected $rules = [
        'body' => 'required|string|min:2|max:1000'
    ];

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function save()
    {
        $this->validate();

        Comment::create([
            'lesson_id' => $this->lesson->id,
            'user_id' => Auth::id(),
            'body' => $this->body,
            'parent_id' => $this->parentId
        ]);

        $this->reset(['body', 'parentId']);
    }

    public function replyTo($commentId)
    {
        $this->parentId = $commentId;
    }

    public function render()
    {
        $comments = $this->lesson->comments()
            ->whereNull('parent_id')
            ->with('user', 'replies.user')
            ->get();


        return view('livewire.course.lesson-comments', [
            'comments' => $comments,
        ]);
    }
}
