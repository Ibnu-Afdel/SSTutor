<?php

namespace App\Livewire\Instructor;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Section;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ManageContent extends Component
{

    use AuthorizesRequests; // Optional

    public Course $course;
    public $sections; // Collection of sections with lessons


    public $newSectionTitle = '';
    public $editingSectionId = null;
    public $editingSectionTitle = '';


    public $addingLessonToSectionId = null;
    public $newLessonTitle = '';
    public $newLessonContent = '';
    public $newLessonVideoUrl = '';

    public $editingLessonId = null;
    public $editingLessonTitle = '';
    public $editingLessonContent = '';
    public $editingLessonVideoUrl = '';
    protected function rules()
    {
        $rules = [];

        if ($this->editingSectionId !== null) {
            $rules['editingSectionTitle'] = 'required|string|max:255';
        } else {
            $rules['newSectionTitle'] = 'required|string|max:255';
        }

        if ($this->addingLessonToSectionId !== null) {
            $rules['newLessonTitle'] = 'required|string|max:255';
            $rules['newLessonVideoUrl'] = 'nullable|url';
            // the vedio have to be required, and look if more fields to have rule, below for editing too
        }

        if ($this->editingLessonId !== null) {
            $rules['editingLessonTitle'] = 'required|string|max:255';
            $rules['editingLessonVideoUrl'] = 'nullable|url';
        }

        return $rules;
    }

    public function mount(Course $course)
    {
        $this->course = $course;
        // Optional: Authorize that the logged-in user can manage this course
        // $this->authorize('update', $this->course);
        $this->loadSections();
    }

    public function loadSections()
    {
        $this->sections = $this->course->sections()
            ->with(['lessons' => fn($query) => $query->orderBy('order')])
            ->orderBy('order')
            ->get();

        $this->resetEditingStates();
    }

    public function resetEditingStates()
    {
        $this->newSectionTitle = '';
        $this->editingSectionId = null;
        $this->editingSectionTitle = '';
        $this->addingLessonToSectionId = null;
        $this->newLessonTitle = '';
        $this->newLessonContent = '';
        $this->newLessonVideoUrl = '';
        $this->editingLessonId = null;
        $this->editingLessonTitle = '';
        $this->editingLessonContent = '';
        $this->editingLessonVideoUrl = '';
    }


    // public function addSection()
    // {
    //     $this->validate(['newSectionTitle' => 'required|string|max:255']);

    //     $maxOrder = $this->course->sections()->max('order') ?? -1;

    //     $this->course->sections()->create([
    //         'title' => $this->newSectionTitle,
    //         'order' => $maxOrder + 1,
    //     ]);

    //     $this->reset('newSectionTitle');
    //     $this->loadSections(); 
    //     session()->flash('message', 'Section added successfully.'); 
    // }


    public function addSection()
    {
        // Use the validated data
        $validated = $this->validate(['newSectionTitle' => 'required|string|max:255']);

        Log::info('Attempting to add section for course: ' . $this->course->id, ['title' => $validated['newSectionTitle']]); // Log validated title

        try {
            $maxOrder = $this->course->sections()->max('order') ?? -1;

            // Use the relationship to create, passing validated data
            $newSection = $this->course->sections()->create([
                'title' => $validated['newSectionTitle'],
                'order' => $maxOrder + 1,
            ]);

            // Check if the model was actually saved (it should have an ID now)
            if ($newSection->exists) {
                Log::info('Section created successfully', ['id' => $newSection->id, 'title' => $newSection->title]);
                $this->reset('newSectionTitle');
                $this->loadSections(); // Refresh the list
                session()->flash('message', 'Section added successfully.');
            } else {
                // This case is rare but means create() didn't throw an error but didn't save
                Log::error('Section creation failed silently for course: ' . $this->course->id, ['title' => $validated['newSectionTitle']]);
                session()->flash('error', 'Failed to add section (silent failure).');
            }
        } catch (Exception $e) {
            // Log any exception that occurs during creation
            Log::error('Error adding section for course: ' . $this->course->id . ' - ' . $e->getMessage(), [
                'title' => $validated['newSectionTitle'],
                'exception' => $e
            ]);
            session()->flash('error', 'An error occurred while adding the section. Please check the logs.');
        }
    }

    // so i have to apply similar try-catch and logging logic to addLesson, updateSection, updateLesson etc. if needed.

    public function startEditingSection(int $sectionId)
    {
        $section = Section::where('id', $sectionId)->where('course_id', $this->course->id)->first();
        if ($section) {
            $this->resetEditingStates();
            $this->editingSectionId = $section->id;
            $this->editingSectionTitle = $section->title;
        } else {
            session()->flash('error', 'Section not found or could not be edited.');
            $this->resetEditingStates();
        }
    }

    public function updateSection()
    {
        $this->validate(['editingSectionTitle' => 'required|string|max:255']);

        if ($this->editingSectionId) {
            $section = Section::find($this->editingSectionId);
            if ($section && $section->course_id == $this->course->id) { // Ensure it belongs to the course
                $section->update(['title' => $this->editingSectionTitle]);
                $this->loadSections();
                session()->flash('message', 'Section updated successfully.');
            } else {
                session()->flash('error', 'Section not found or invalid.');
            }
        }
        $this->resetEditingStates();
    }

    public function deleteSection(int $sectionId)
    {
        $section = Section::where('id', $sectionId)
            ->where('course_id', $this->course->id)
            ->first();
        if ($section) {
            $section->delete();
            $this->loadSections(); // Refresh
            session()->flash('message', 'Section deleted successfully.');
        } else {
            session()->flash('error', 'Section not found or invalid for deletion.');
        }
    }

    public function cancelEditingSection()
    {
        $this->resetEditingStates();
    }

    // Lesson Management 

    public function startAddingLesson(int $sectionId)
    {
        $this->resetEditingStates();
        $this->addingLessonToSectionId = $sectionId;
    }

    public function cancelAddingLesson()
    {
        $this->resetEditingStates();
    }

    public function addLesson()
    {
        $this->validate([
            'newLessonTitle' => 'required|string|max:255',
            'newLessonVideoUrl' => 'nullable|url',
            // same here for vedio url, plus i dont need vedio for section? have to decide
        ]);

        $section = Section::find($this->addingLessonToSectionId);

        if ($section && $section->course_id == $this->course->id) {
            $maxOrder = $section->lessons()->max('order') ?? -1;

            $section->lessons()->create([
                'title' => $this->newLessonTitle,
                'content' => $this->newLessonContent,
                'video_url' => $this->newLessonVideoUrl,
                'order' => $maxOrder + 1,
            ]);

            $this->loadSections();
            session()->flash('message', 'Lesson added successfully.');
        } else {
            session()->flash('error', 'Section not found or invalid for adding lesson.');
        }
        $this->resetEditingStates();
    }


    public function startEditingLesson(Lesson $lesson)
    {
        $this->resetEditingStates();
        $this->editingLessonId = $lesson->id;
        $this->editingLessonTitle = $lesson->title;
        $this->editingLessonContent = $lesson->content;
        $this->editingLessonVideoUrl = $lesson->video_url;
    }

    public function updateLesson()
    {
        $this->validate([
            'editingLessonTitle' => 'required|string|max:255',
            'editingLessonVideoUrl' => 'nullable|url',
            // vedio required? .. 
        ]);

        $lesson = Lesson::with('section')->find($this->editingLessonId);

        // Ensure lesson exists and belongs to the current course via its section
        if ($lesson && $lesson->section->course_id == $this->course->id) {
            $lesson->update([
                'title' => $this->editingLessonTitle,
                'content' => $this->editingLessonContent,
                'video_url' => $this->editingLessonVideoUrl,
            ]);
            $this->loadSections(); // Refresh
            session()->flash('message', 'Lesson updated successfully.');
        } else {
            session()->flash('error', 'Lesson not found or invalid.');
        }
        $this->resetEditingStates();
    }

    // public function deleteLesson(Lesson $lesson)
    // {
    //     // Add confirmation step in the view
    //     // Ensure lesson belongs to the current course via its section
    //     if ($lesson->section->course_id == $this->course->id) {
    //         $lesson->delete();
    //         $this->loadSections(); // Refresh
    //         session()->flash('message', 'Lesson deleted successfully.');
    //     } else {
    //         session()->flash('error', 'Lesson not found or invalid.');
    //     }
    //     $this->resetEditingStates();
    // }

    public function deleteLesson(int $lessonId)
    {
        $lesson = Lesson::with('section')->find($lessonId);

        if ($lesson && $lesson->section->course_id == $this->course->id) {
            $lesson->delete();
            $this->loadSections();
            session()->flash('message', 'Lesson deleted successfully.');
        } else {
            session()->flash('error', 'Lesson not found or invalid.');
        }
        $this->resetEditingStates();
    }


    public function cancelEditingLesson()
    {
        $this->resetEditingStates();
    }


    // Reordering (Requires JS library like SortableJS, 
    // but not working for me so i have tried to use apline.. not working though. 
    // and for the livewire one, i have it in instructor/deleted.txt) 

    public function updateSectionOrder($orderedIds)
    {
        // $orderedIds will typically be an array of section IDs in the new order
        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $index => $id) {
                Section::where('id', $id)
                    ->where('course_id', $this->course->id)
                    ->update(['order' => $index]);
            }
        });
        $this->loadSections();
    }

    public function updateLessonOrder($orderedIds, $sectionId)
    {
        DB::transaction(function () use ($orderedIds, $sectionId) {
            // Ensure section belongs to the course first (security)
            $section = Section::where('id', $sectionId)->where('course_id', $this->course->id)->firstOrFail();

            foreach ($orderedIds as $index => $id) {
                Lesson::where('id', $id)
                    ->where('section_id', $sectionId) // Security check
                    ->update(['order' => $index]);
            }
        });
        $this->loadSections();
    }


    public function render()
    {
        return view('livewire.instructor.manage-content');
    }
}
