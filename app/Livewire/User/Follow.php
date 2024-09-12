<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Follow extends Component
{

    public $userToFollow;
    public $isFollowing = false;

    public function mount($user)
    {
        $this->userToFollow = User::findOrFail($user);
        $this->checkFollowingStatus();
    }

    public function checkFollowingStatus()
    {
        $this->isFollowing = Auth::user()->follows()
            ->where('followed_id', $this->userToFollow->id)
            ->exists(); 
    }

    public function follow()
    {
        if (!$this->isFollowing) {
            Auth::user()->follows()->create([
                'followed_id' => $this->userToFollow->id,
            ]);
            $this->isFollowing = true;
        }
    }

    public function unfollow()
    {
        if ($this->isFollowing) {
            Auth::user()->follows()
                ->where('followed_id', $this->userToFollow->id)
                ->delete();
            $this->isFollowing = false;
        }
    }

    public function render()
    {
        return view('livewire.user.follow');
    }
}
