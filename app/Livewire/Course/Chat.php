<?php

namespace App\Livewire\Course;

use App\Events\MessageEvent;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Chat as CourseChat;
use App\Models\Course;
use App\Models\Enrollment as EnrollmentModel;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $course;
    public $message;
    public $convo = [];
    public $isEnrolled = false;

    protected $rules = [
        'message' => 'required|max:500',
    ];

    public function mount(Course $course)
    {
        $this->course = $course;

        if (Auth::check()) {
            $this->isEnrolled = EnrollmentModel::where('course_id', $this->course->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        $this->loadMessages();
    }

    public function loadMessages()
    {
        $messages = CourseChat::where('course_id', $this->course->id)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse();

        $this->convo = $messages->map(function ($message) {
            return [
                'username' => $message->user->name,
                'message' => $message->message,
                'created_at' => $message->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    public function sendMessage()
    {
        if (!$this->isEnrolled) {
            session()->flash('error', 'You must be enrolled in this course to send messages.');
            return;
        }

        $this->validate();

        $savedMessage = CourseChat::create([
            'course_id' => $this->course->id,
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        MessageEvent::dispatch(
            Auth::id(),
            $this->course->id,
            $this->message,
            $savedMessage->created_at->toIso8601String()
        );

        $this->message = '';
    }

    #[On('echo:our-channel,MessageEvent')]
    // #[On('echo-presence:course-chat.{course.id},MessageEvent')]

    public function listenForMessage($data)
    {
        if ($data['course_id'] == $this->course->id) {
            $this->convo[] = [
                'username' => $data['username'],
                'message' => $data['message'],
                'created_at' => $data['createdAt'],
            ];

            $this->dispatch('message-sent');
        }
    }

    public function render()
    {
        return view('livewire.course.chat');
    }
}
