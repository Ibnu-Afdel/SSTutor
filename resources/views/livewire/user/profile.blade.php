<div class="max-w-xl p-4 mx-auto bg-white border border-gray-200 shadow-md sm:p-6 rounded-2xl">
    <div class="flex items-center mb-6 space-x-4">
        <div class="flex-shrink-0">
            <div class="flex items-center justify-center text-xl text-gray-500 bg-gray-200 rounded-full w-14 h-14">
                <i class="fas fa-user" aria-hidden="true"></i>
                <noscript><span>👤</span></noscript>
            </div>
        </div>
        <div>
            <h1 class="text-xl font-semibold text-gray-800 sm:text-2xl">Profile: {{ $user->name }}</h1>
            <p class="text-sm text-gray-500">Role: {{ ucfirst($user->role) }}</p>
            <p class="text-sm text-gray-500">{{ucfirst('@' . $user->username) }}</p>

        </div>
    </div>

    <div class="space-y-2 text-sm text-gray-700 sm:text-base">
        <p>
            <i class="mr-2 text-indigo-500 fas fa-envelope" aria-hidden="true"></i>
            <noscript><span>📧</span></noscript>
            {{ $user->email }}
        </p>

        @if($user->role === 'student')
            <div class="mt-4">
                <h2 class="mb-2 font-medium text-gray-800">Enrolled Courses</h2>
                <ul class="space-y-1 text-gray-600 list-disc list-inside">
                    @forelse($enrolledCourses as $course)
                        <li>{{ $course->name }}</li>
                    @empty
                        <li class="italic text-gray-400">No courses enrolled yet.</li>
                    @endforelse
                </ul>
            </div>
        @elseif($user->role === 'instructor')
            <div class="mt-4">
                <h2 class="mb-2 font-medium text-gray-800">Your Courses</h2>
                <ul class="space-y-1 text-gray-600 list-disc list-inside">
                    @forelse($instructorCourses as $course)
                        <li>{{ $course->name }}</li>
                    @empty
                        <li class="italic text-gray-400">You haven’t created any courses yet.</li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>
</div>
