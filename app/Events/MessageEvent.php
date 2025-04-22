<?php

namespace App\Events;

use App\Models\Chat as CourseChat;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;
    public $message;
    public $courseId;
    public $createdAt;

    public function __construct($user_id, $course_id, $message, $createdAt)
    {
        $this->message = $message;
        $this->courseId = $course_id;
        $this->username = User::find($user_id)->name;
        $this->createdAt = $createdAt;
    }


    public function broadcastOn(): array
    {
        return [
            new Channel('our-channel'),
            // new \Illuminate\Broadcasting\PresenceChannel('course-chat.' . $this->courseId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'username' => $this->username,
            'message' => $this->message,
            'course_id' => $this->courseId,
            'createdAt' => $this->createdAt,
        ];
    }
}
