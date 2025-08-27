<div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

    {{-- Header and Buttons --}}
    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="flex items-center text-2xl font-bold text-gray-800 sm:text-3xl">
                <i class="mr-3 text-indigo-600 fas fa-chalkboard-teacher fa-fw"></i>
                Course Management
            </h1>
            <p class="mt-1 text-sm text-gray-500">Manage your courses, view details, and add new ones.</p>
        </div>

        @if (!$isCreatingOrEditing)
            <button wire:click="createCourse"
                class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto">
                <i class="mr-2 fas fa-plus-circle fa-fw"></i>
                New Course
            </button>
        @endif
    </div>
    <a href="{{ route('instructor.dashboard') }}"
        class="inline-flex items-center px-4 py-2 mb-6 font-medium text-gray-700 text-md "> {{-- Added mb-6 for spacing --}}
        <i class="mr-2 text-gray-500 fas fa-arrow-left"></i>
        Back
    </a>

    {{-- Session Messages --}}
    @if (session()->has('message'))
        <div class="flex items-center p-4 mb-6 text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg shadow-sm"
            role="alert">
            <i class="mr-2 fas fa-check-circle fa-fw"></i>
            <span>{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="flex items-center p-4 mb-6 text-sm text-red-700 bg-red-100 border border-red-200 rounded-lg shadow-sm"
            role="alert">
            <i class="mr-2 fas fa-exclamation-triangle fa-fw"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Create/Edit Form Section --}}
    @if ($isCreatingOrEditing)
        <div class="p-6 mb-8 bg-white border border-gray-200 shadow-md rounded-xl">
            <h2 class="pb-2 mb-4 text-xl font-semibold text-gray-700 border-b">
                {{ $courseId ? 'Edit Course' : 'Create New Course' }}
            </h2>
            <form wire:submit.prevent="saveCourse" class="space-y-6">
                @include('livewire.course.create', ['existingImageUrl' => $currentImageUrl])
            </form>
        </div>
    @endif

    {{-- Course Listing Section --}}
    @if (!$isCreatingOrEditing)
        @if ($courses->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($courses as $course)
                    {{-- Card Start - Apply conditional classes here --}}
                    <div @class([
                        'relative', // Added for absolute positioning of badge
                        'flex flex-col overflow-hidden transition duration-150 ease-in-out shadow-md rounded-xl hover:shadow-lg',
                        // --- Conditional Styling Logic ---
                        'bg-yellow-100 border-yellow-300 hover:border-yellow-500' =>
                            $course->is_pro && $is_pro, // Premium Course + Pro User (Yellowish)
                        'bg-gray-200 border-gray-300 hover:border-gray-400' =>
                            $course->is_pro && !$is_pro, // Premium Course + Non-Pro User (Greyish)
                        'bg-white border-gray-200 hover:border-indigo-300' => !$course->is_pro, // Standard Course (White)
                    ])>
                        {{-- Optional: Premium Badge --}}
                        @if ($course->is_pro)
                            <span @class([
                                'absolute top-2 right-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white',
                                'bg-yellow-600' => $is_pro, // Darker yellow badge for pro user on yellow card
                                'bg-gray-600' => !$is_pro, // Darker gray badge for non-pro user on gray card
                            ])>
                                <i class="w-3 h-3 mr-1 fas fa-star"></i> Premium
                            </span>
                        @endif

                        {{-- Course Image (Optional) --}}
                        {{-- <img class="object-cover w-full h-40" src="{{ asset('storage/' . $course->image) ?? 'https://via.placeholder.com/400x200?text=Course+Image' }}" alt="{{ $course->name }}"> --}}

                        <div class="flex flex-col justify-between flex-grow p-5">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $course->name }}</h3>
                                <p class="h-20 mt-2 overflow-hidden text-sm text-gray-600">
                                    {{ Str::limit($course->description, 120) }}
                                </p>
                                <div class="mt-4 space-y-2 text-sm text-gray-500">
                                    <p class="flex items-center">
                                        <i class="w-4 mr-2 text-center text-green-500 fas fa-dollar-sign fa-fw"></i>
                                        Price: ETB {{ number_format($course->price, 2) }}
                                        @if ($course->discount && $course->original_price)
                                            <span class="ml-2 text-xs text-red-500 line-through">
                                                ETB {{ number_format($course->original_price, 2) }}
                                            </span>
                                        @endif
                                    </p>
                                    <p class="flex items-center">
                                        <i class="w-4 mr-2 text-center text-blue-500 fas fa-layer-group fa-fw"></i>
                                        Level: {{ ucfirst($course->level->value) }}
                                    </p>
                                    {{-- Gone dd other relevant info like duration, students etc. if available --}}
                                    {{-- <p class="flex items-center">
                                        <i class="w-4 mr-2 text-center text-purple-500 fas fa-users fa-fw"></i>
                                        Students: {{ $course->students_count ?? 'N/A' }}
                                    </p> --}}
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center justify-end gap-2 pt-4 mt-4 border-t border-gray-100">
                                <button wire:click="editCourse({{ $course->id }})" title="Edit Course"
                                    class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-white transition duration-150 ease-in-out bg-indigo-500 rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-400">
                                    <i class="fas fa-pencil-alt fa-fw"></i>
                                    <span class="ml-1.5 hidden sm:inline">Edit</span>
                                </button>

                                <button wire:click="confirmCourseDeletion({{ $course->id }})" title="Delete Course"
                                    class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-white transition duration-150 ease-in-out bg-red-500 rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-400">
                                    <i class="fas fa-trash-alt fa-fw"></i>
                                    <span class="ml-1.5 hidden sm:inline">Delete</span>
                                </button>
                            </div>
                        </div>
                    </div> {{-- Card End --}}
                @endforeach
            </div>

            {{-- Pagination (Placeholder) --}}
            {{-- <div class="mt-8">
                {{ $courses->links() }}
            </div> --}}

            {{-- No Courses Found State --}}
        @else
            <div class="p-12 text-center bg-white border border-gray-300 border-dashed rounded-xl">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-indigo-100 rounded-full">
                    <i class="text-3xl text-indigo-500 fas fa-folder-open fa-fw"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700">No courses found.</h3>
                <p class="mt-1 text-sm text-gray-500">Ready to share your knowledge? Create your first course!</p>
                <button wire:click="createCourse"
                    class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="mr-2 fas fa-plus-circle fa-fw"></i>
                    Create First Course
                </button>
            </div>
        @endif
    @endif

    {{-- Deletion Confirmation Modal --}}
    @if ($confirmingCourseDeletion)
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                {{-- Trick to center modal content --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                {{-- Modal panel --}}
                <div
                    class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Delete Course</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Are you sure you want to delete this course? This action cannot
                            be undone.</p>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="confirmDelete({{ $confirmingCourseDeletion }})" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button wire:click="$set('confirmingCourseDeletion', false)" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
