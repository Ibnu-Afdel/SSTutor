<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\InstructorApplication;

class InstructorApplicationForm extends Component
{
    public $full_name, $email, $phone_number, $date_of_birth, $adress, $webiste, $linkedin,
        $resume, $higest_qualification, $current_ocupation, $reason;
    public $existingApplication;

    public function mount()
    {
        $this->existingApplication = InstructorApplication::where('user_id', auth()->id())->latest()->first();
        $this->email = Auth::user()->email;
        $this->full_name = Auth::user()->name;
    }

    public function submit()
    {
        $this->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'adress' => 'nullable|string|max:255',
            'webiste' => 'nullable|url|max:255',
            'linkedin' => 'required|url|max:255',
            'higest_qualification' => 'required|string|in:None,Diploma,Bachelor\'s,Master\'s,PhD',
            'current_ocupation' => 'required|string|in:Student,Freelancer,Full-time Job,Part-time Job,Unemployed',
            'reason' => 'nullable|string',
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
            'resume' => '',
            'higest_qualification' => $this->higest_qualification,
            'current_ocupation' => $this->current_ocupation,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Your application has been submitted!');
        return redirect()->route('user.profile', ['username' => auth()->user()->username]);
    }

    public function render()
    {
        return view('livewire.admin.instructor-application-form');
    }
}
