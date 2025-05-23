<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{

    public $name;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:6',
        'username' => [
            'required',
            'string',
            'min:3',
            'max:20',
            'unique:users,username',
            'regex:/^[a-zA-Z0-9_-]+$/', // ✅ no spaces, only letters, numbers, dash, underscore
        ],

    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'student',
            'status' => 'approved',
            'is_pro' => false,
        ]);

        Auth::login($user);
        return redirect()->route('user.profile', ['username' => auth()->user()->username]);
    }
    public function render()
    {
        return view('livewire.auth.register');
    }
}
