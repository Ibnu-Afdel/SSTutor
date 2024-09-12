<?php

namespace App\Events;

use App\Models\Chat as CourseChat;
use App\Models\Course;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;
    public $message;
    public $courseId;
    public $createdAt;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id,$course_id, $message)
    {
        $newMessage = New CourseChat();
        $newMessage->user_id = $user_id;
        $newMessage->course_id = $course_id;
        $newMessage->message = $message;
        $newMessage->save();

        $this->message = $message;
        $this->courseId = Course::find($course_id);
        $this->username = User::find($user_id)->name;
        $this->createdAt = $newMessage->created_at->toIso8601String();
        

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('our-channel'),
        ];
    }

}
