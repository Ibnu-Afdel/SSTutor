<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        return view('livewire.home-page', [
            'user' => User::get()
        ]);
    }
}
