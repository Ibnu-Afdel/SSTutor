<div>
    <h2 class="mb-6 text-2xl font-bold">Course Management</h2>

    <button wire:click="createCourse" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
        Create New Course
    </button>

    @if($courseId)
        <div class="mt-6">
            @include('livewire.course.create') <!-- Include the existing form for creating/editing courses -->
        </div>
    @else
        <div class="mt-6">
            @if(count($courses) > 0)
                @foreach($courses as $course)
                    <div class="p-4 mb-4 border">
                        <h3 class="text-xl font-semibold">{{ $course->name }}</h3>
                        <p>{{ $course->description }}</p>
                        <button wire:click="editCourse({{ $course->id }})" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                            Edit
                        </button>
                    </div>
                @endforeach
            @else
                <p>No courses found.</p>
            @endif
        </div>
    @endif
</div>
