<?php

namespace App\Livewire\Course;

use App\Events\MessageEvent;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Chat as CourseChat;
use App\Models\Enrollment as EnrollmentModel;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $course; 
    public $message ;
    public $convo =[];
    public $isEnrolled = false;

    protected $rules = [
        'message' => 'required|max:500',
    ];

    public function mount($course)
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
                    'created_at' => $message->created_at->diffForHumans()
                ];
                
            });

        $this->course = $course;

        // Check if the user is enrolled in the course
        if (Auth::check()) {
            $this->isEnrolled = EnrollmentModel::where('course_id', $this->course->id)
                ->where('user_id', Auth::id())
                ->exists();
        }
    }

    public function sendMessage()
{
    if (!$this->isEnrolled) {
        session()->flash('error', 'You must be enrolled in this course to send messages.');
        return;
    }

    $this->validate();

    MessageEvent::dispatch(Auth::user()->id,  $this->course->id, $this->message);


    // Clear the input
    $this->message = ''; 

 
}
#[On('echo:our-channel,MessageEvent')]
public function listenForMessage($data)
{
    $this->convo[] = [
        'username' => $data['username'],
        'message' => $data['message'],
        'created_at' => $data['createdAt'],
    ];

}

    public function render()
    {

        return view('livewire.course.chat', [
            'convo' => $this->convo,
            'isEnrolled' => $this->isEnrolled,
        ]);
    }
   
}



