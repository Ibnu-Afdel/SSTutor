<?php

namespace App\Livewire\Course;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CoursePlay extends Component
{
    public Course $course;
    public Collection $sections;
    public ?Lesson $currentLesson = null;
    public ?Lesson $nextLesson = null;
    public ?Lesson $previousLesson = null;
    public array $completedLessons = [];

    public function mount(Course $course, Lesson $lesson)
    {
        $this->course = $course;
        $this->loadStructure();
        $this->currentLesson = $lesson ?? $this->findFirstLesson();
        $this->calculateNavigation();
        $this->loadCompletedLessons();
    }

    protected function loadStructure()
    {
        $this->sections = $this->course->sections()
            ->with(['lessons' => fn($q) => $q->orderBy('order')])
            ->orderBy('order')->get();
    }

    protected function loadCompletedLessons()
    {
        $user = Auth::user();
        $this->completedLessons = $user->lessons()->whereNotNull('completed_at')->pluck('lessons.id')->toArray();
    }

    protected function findFirstLesson(): ?Lesson
    {
        foreach ($this->sections as $section) {
            $lesson = $section->lessons->first();
            if ($lesson) return $lesson;
        }
        return null;
    }

    protected function findLessonById(int $lessonId): ?Lesson
    {
        foreach ($this->sections as $section) {
            foreach ($section->lessons as $lesson) {
                if ($lesson->id === $lessonId && $lesson->section->course_id === $this->course->id) {
                    return $lesson;
                }
            }
        }
        return null;
    }

    protected function calculateNavigation()
    {
        $lessons = $this->sections->flatMap(fn($s) => $s->lessons);
        $index = $lessons->search(fn($l) => $l->id === $this->currentLesson?->id);

        $this->previousLesson = $index > 0 ? $lessons[$index - 1] : null;
        $this->nextLesson = $index !== false && $index < $lessons->count() - 1 ? $lessons[$index + 1] : null;
    }

    public function selectLesson(int $lessonId)
    {
        $lesson = $this->findLessonById($lessonId);
        if ($lesson) {
            $this->currentLesson = $lesson;
            $this->calculateNavigation();
            $this->dispatch('player-reload');

            return redirect()->route('course-play', [$this->course->id, $lesson->id]);
        }
    }

    public function markAsComplete()
    {
        Auth::user()->lessons()->syncWithoutDetaching([
            $this->currentLesson->id => ['completed_at' => now()],
        ]);
        $this->loadCompletedLessons();
    }

    public function goToNextLesson()
    {
        if ($this->nextLesson) {
            $this->selectLesson($this->nextLesson->id);
        }
    }

    public function goToPreviousLesson()
    {
        if ($this->previousLesson) {
            $this->selectLesson($this->previousLesson->id);
        }
    }

    public function render()
    {
        return view('livewire.course.course-play');
    }
}
