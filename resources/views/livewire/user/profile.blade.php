<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="max-w-2xl p-6 mx-auto bg-white border border-gray-200 shadow-lg rounded-2xl">
    <!-- Profile Header -->
    <div class="flex items-center mb-6 space-x-5">
        <div
            class="flex items-center justify-center w-16 h-16 text-2xl text-indigo-600 bg-indigo-100 rounded-full shadow">
            <i class="fas fa-user"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
            <p class="text-sm text-gray-500"><i class="mr-1 text-gray-400 fas fa-user-tag"></i>
                {{ ucfirst($user->role) }}</p>
            <p class="text-sm text-gray-500"><i class="mr-1 text-gray-400 fas fa-at"></i>{{ '@' . $user->username }}</p>
        </div>
    </div>

    <!-- Contact -->
    <div class="mb-6">
        <p class="text-base text-gray-700">
            <i class="mr-2 text-indigo-500 fas fa-envelope"></i>{{ $user->email }}
        </p>
    </div>

    <!-- Role-Based Content -->
    @if ($user->role === 'student')
        <div class="mb-6">
            <h2 class="mb-2 text-lg font-semibold text-gray-800"><i
                    class="mr-2 text-blue-500 fas fa-book-reader"></i>Enrolled Courses</h2>
            <ul class="space-y-1 text-gray-600 list-disc list-inside">
                @forelse($enrolledCourses as $course)
                    <li>{{ $course->name }}</li>
                @empty
                    <li class="italic text-gray-400">No courses enrolled yet.</li>
                @endforelse
            </ul>
        </div>

        @if (auth()->check() && auth()->id() === $user->id)
            @php
                $application = \App\Models\InstructorApplication::where('user_id', $user->id)->latest()->first();
            @endphp

            @if ($application)
                <div class="p-4 mt-4 space-y-2 border rounded-md bg-gray-50">
                    <p class="text-sm font-semibold">
                        <i class="mr-1 text-gray-500 fas fa-clipboard-check"></i>
                        Application Status:
                        <span
                            class="px-2 py-1 text-white rounded text-xs 
                            {{ $application->status === 'pending' ? 'bg-yellow-500' : ($application->status === 'approved' ? 'bg-green-600' : 'bg-red-600') }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </p>
                    <p><i class="mr-2 text-gray-400 fas fa-user-circle"></i>{{ $application->full_name }}</p>
                    <p><i class="mr-2 text-gray-400 fas fa-envelope-open"></i>{{ $application->email }}</p>
                    <p>
                        <i class="mr-2 text-gray-400 fas fa-globe"></i>
                        <a href="{{ $application->webiste }}" target="_blank" rel="noopener noreferrer"
                            class="text-indigo-600 hover:underline">
                            {{ $application->webiste }}
                        </a>
                    </p>
                    <p>
                        <i class="mr-2 text-gray-400 fab fa-linkedin"></i>
                        <a href="{{ $application->linkedin }}" target="_blank" rel="noopener noreferrer"
                            class="text-indigo-600 hover:underline">
                            {{ $application->linkedin }}
                        </a>
                    </p>

                    @if ($application->higest_qualification !== 'none')
                        <p><i
                                class="mr-2 text-gray-400 fas fa-graduation-cap"></i>{{ $application->higest_qualification }}
                        </p>
                    @endif
                </div>
            @else
                <a href="{{ route('instructor.apply') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 mt-4 text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                    <i class="fas fa-chalkboard-teacher"></i>Become an Instructor
                </a>
            @endif
        @endif
    @elseif($user->role === 'instructor')
        <div>
            <h2 class="mb-2 text-lg font-semibold text-gray-800"><i
                    class="mr-2 text-green-500 fas fa-chalkboard-teacher"></i>Your Courses</h2>
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
