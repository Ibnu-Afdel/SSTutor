<?php

namespace App\Livewire\Course;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithoutUrlPagination;

   public function getIsProProperty()
   {
    return Auth::check() && Auth::user()->is_pro;
   }

    public function render()
    {
        $courses = Course::paginate(10);
       $is_pro = $this->isPro;

        return view('livewire.course.index', compact('courses', 'is_pro'));
    }
}
