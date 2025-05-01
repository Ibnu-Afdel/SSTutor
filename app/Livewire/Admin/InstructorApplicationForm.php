<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\InstructorApplication;

class InstructorApplicationForm extends Component
{
    public $full_name, $email, $phone_number, $date_of_birth, $adress, $webiste, $linkedin,
        $resume, $higest_qualification, $current_ocupation, $reason;

    public function mount()
    {
        $this->email = Auth::user()->email;
        $this->full_name = Auth::user()->name;
    }

    public function submit()
    {
        $this->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'adress' => 'nullable|string|max:255',
            'webiste' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'resume' => 'required|string',
            'higest_qualification' => 'nullable|string|max:255',
            'current_ocupation' => 'nullable|string|max:255',
            'reason' => 'required|string',
        ]);


        InstructorApplication::create([
            'user_id' => Auth::id(),
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'date_of_birth' => $this->date_of_birth,
            'adress' => $this->adress,
            'webiste' => $this->webiste,
            'linkedin' => $this->linkedin,
            'resume' => $this->resume,
            'higest_qualification' => $this->higest_qualification,
            'current_ocupation' => $this->current_ocupation,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Your application has been submitted!');
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.admin.instructor-application-form');
    }
}
