<div class="max-w-4xl p-4 mx-auto">
    <h2 class="mb-6 text-3xl font-extrabold text-gray-800">📚 Course Management</h2>

    {{-- Show Create Button only if form is not visible --}}
    @if(!$isCreatingOrEditing)
        <button
            wire:click="createCourse"
            class="px-5 py-2.5 mb-6 text-sm font-semibold text-white transition-all duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
            ➕ Create New Course
        </button>
        {{-- Remove this link as creation is handled inline --}}
        {{-- <a href="{{ route('courses.create') }}">Create new course</a> --}}
    @endif

    {{-- Form Section (for Create or Edit) --}}
    @if($isCreatingOrEditing)
        {{-- The form tag now wraps the include --}}
        <form wire:submit.prevent="saveCourse" class="mt-6 mb-8">

            {{-- Pass the existing image URL to the included view if available --}}
            @include('livewire.course.create', ['existingImageUrl' => $currentImageUrl])

            {{-- Submit/Cancel buttons can be here or inside the include --}}
            {{-- If inside the include, ensure they are within this form tag --}}
            {{-- Example buttons if placed here: --}}
            {{--
            <div class="pt-5 mt-6 border-t border-gray-200">
                <div class="flex justify-end space-x-3">
                     <button type="button" wire:click="cancel"
                             class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                         Cancel
                     </button>
                     <button type="submit"
                             class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span wire:loading.remove wire:target="saveCourse">
                            {{ $courseId ? 'Update Course' : 'Create Course' }}
                        </span>
                        <span wire:loading wire:target="saveCourse">
                            Saving...
                        </span>
                     </button>
                </div>
            </div>
            --}}
        </form>
    @endif


    {{-- Course List (Show only if form is not visible) --}}
    @if(!$isCreatingOrEditing)
        <div class="space-y-4">
             {{-- Flash Messages --}}
             @if (session()->has('message'))
                 <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                     {{ session('message') }}
                 </div>
             @endif
             @if (session()->has('error'))
                 <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                     {{ session('error') }}
                 </div>
             @endif

            @forelse($courses as $course)
                <div class="p-5 transition bg-white rounded-lg shadow hover:shadow-lg">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $course->name }}</h3>
                            <p class="mt-1 text-gray-600">{{ Str::limit($course->description, 100) }}</p>
                            {{-- Add more details like price, level etc. if desired --}}
                            <div class="mt-2 text-sm text-gray-500">
                                Price: ${{ number_format($course->price, 2) }}
                                @if($course->discount)
                                 (<s>${{ number_format($course->original_price, 2) }}</s> Discount!)
                                @endif
                                | Level: {{ ucfirst($course->level) }}
                            </div>
                        </div>
                        <div class="flex-shrink-0 mt-4 sm:mt-0 sm:ml-4">
                            <button
                                wire:click="editCourse({{ $course->id }})"
                                class="inline-block px-4 py-2 text-sm font-semibold text-white bg-indigo-500 rounded hover:bg-indigo-600">
                                ✏️ Edit
                            </button>
                            {{-- Add delete button etc. here if needed --}}
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-600 bg-white rounded shadow">
                    <p>No courses found. Click "Create New Course" to add one 👆</p>
                </div>
            @endforelse
        </div>
    @endif
</div>