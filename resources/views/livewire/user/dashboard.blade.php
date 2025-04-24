<div class="px-4 sm:px-6 lg:px-8">

    <!-- In Progress Courses -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                <i class="mr-2 text-blue-600 fas fa-spinner"></i>
                In Progress Courses
            </h2>
        </div>

        @if($inProgressCourses->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($inProgressCourses as $course)
                    @livewire('user.course-card', ['course' => $course], key($course->id))
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center p-8 text-gray-500 border rounded-lg bg-gray-50">
                <i class="mr-2 text-blue-500 fas fa-circle-info"></i>
                No courses in progress.
            </div>
        @endif
    </div>

    <!-- Completed Courses -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                <i class="mr-2 text-green-600 fas fa-check-circle"></i>
                Completed Courses
            </h2>
        </div>

        @if($completedCourses->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($completedCourses as $course)
                    @livewire('user.course-card', ['course' => $course], key($course->id))
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center p-8 text-gray-500 border rounded-lg bg-gray-50">
                <i class="mr-2 text-green-500 fas fa-circle-info"></i>
                No completed courses.
            </div>
        @endif
    </div>

</div>
