<div>
    <div @class([
        'max-w-2xl p-6 mx-auto shadow-lg rounded-2xl border',
        'bg-gradient-to-br from-amber-50 to-white border-amber-200' =>
            $user->is_pro,
        'bg-white border-gray-200' => !$user->is_pro,
    ])>

        <div class="flex items-center mb-6 space-x-5">
            <div @class([
                'flex items-center justify-center w-16 h-16 text-2xl rounded-full shadow shrink-0',
                'bg-gradient-to-tr from-yellow-400 to-amber-500 text-white' =>
                    $user->is_pro,
                'bg-indigo-100 text-indigo-600' => !$user->is_pro,
            ])>

                <i class="fas {{ $user->is_pro ? 'fa-crown' : 'fa-user' }}"></i>
            </div>
            <div class="flex-grow">

                <div class="flex flex-wrap items-center gap-x-2">
                    <h1 class="text-2xl font-bold text-gray-800 shrink-0">{{ $user->name }}</h1>
                    @if ($user->is_pro)
                        <span
                            class="text-xs font-semibold text-yellow-800 bg-yellow-200 px-2.5 py-0.5 rounded-full inline-flex items-center border border-yellow-300 shrink-0">
                            <i class="mr-1 text-yellow-600 fa-solid fa-star"></i>Premium
                        </span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center mt-1 gap-x-4 gap-y-1">
                    <p class="text-sm text-gray-500 whitespace-nowrap"><i class="mr-1 text-gray-400 fas fa-user-tag"></i>
                        {{ ucfirst($user->role) }}</p>

                    <p class="text-sm text-gray-500 whitespace-nowrap"><i
                            class="mr-1 text-gray-400 fas fa-at"></i>{{ $user->username }}</p>
                </div>
            </div>
        </div>

        <div class="mb-6 border-t pt-4 {{ $user->is_pro ? 'border-amber-200' : 'border-gray-200' }}">

            <h3 class="mb-2 text-sm font-semibold text-gray-500 uppercase">Contact Information</h3>
            <p class="text-base text-gray-700">
                <i class="w-5 mr-2 text-center text-indigo-500 fas fa-envelope fa-fw"></i>{{ $user->email }}
            </p>

        </div>

        <div class="mb-6 border-t pt-4 {{ $user->is_pro ? 'border-amber-200' : 'border-gray-200' }}">

            @if ($user->role === 'student')
                <h3 class="mb-2 text-sm font-semibold text-gray-500 uppercase">Student Details</h3>
                <div class="mb-6">
                    <h4 class="mb-2 font-semibold text-gray-800"><i
                            class="w-5 mr-2 text-center text-blue-500 fas fa-book-reader fa-fw"></i>Enrolled Courses
                    </h4>

                    @isset($enrolledCourses)
                        @if ($enrolledCourses->count() > 0)
                            <ul class="space-y-1 text-gray-600 list-disc list-inside">
                                @foreach ($enrolledCourses as $course)
                                    <li>{{ $course->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="italic text-gray-400">No courses enrolled yet.</p>
                        @endif
                    @else
                        <p class="italic text-gray-400">Enrolled course data not available.</p>
                    @endisset
                </div>

                @if (auth()->check() && auth()->id() === $user->id)
                    <h4 class="mb-2 font-semibold text-gray-800"><i
                            class="w-5 mr-2 text-center text-purple-500 fas fa-chalkboard-teacher fa-fw"></i>Instructor
                        Status</h4>
                    @php
                        if (!isset($application)) {
                            $application = class_exists(\App\Models\InstructorApplication::class)
                                ? \App\Models\InstructorApplication::where('user_id', $user->id)->latest()->first()
                                : null;
                        }
                    @endphp

                    @if ($application)
                        <div class="p-4 mt-2 space-y-2 border border-gray-200 rounded-md bg-gray-50/50">

                            <p class="text-sm font-semibold">
                                <i class="w-5 mr-1 text-center text-gray-500 fas fa-clipboard-check fa-fw"></i>
                                Application Status:
                                <span @class([
                                    'px-2 py-0.5 text-xs text-white rounded ml-1',
                                    'bg-yellow-500' => $application->status === 'pending',
                                    'bg-green-600' => $application->status === 'approved',
                                    'bg-red-600' => $application->status === 'rejected',
                                ])>
                                    {{ ucfirst($application->status) }}
                                </span>
                            </p>

                            @if (!empty($application->full_name))
                                <p class="text-sm"><i
                                        class="w-5 mr-2 text-center text-gray-400 fas fa-user-circle fa-fw"></i>{{ $application->full_name }}
                                </p>
                            @endif
                            @if (!empty($application->email))
                                <p class="text-sm"><i
                                        class="w-5 mr-2 text-center text-gray-400 fas fa-envelope-open fa-fw"></i>{{ $application->email }}
                                </p>
                            @endif
                            @if (!empty($application->website))
                                <p class="text-sm"><i
                                        class="w-5 mr-2 text-center text-gray-400 fas fa-globe fa-fw"></i><a
                                        href="{{ $application->website }}" target="_blank" rel="noopener noreferrer"
                                        class="text-indigo-600 hover:underline">{{ $application->website }}</a></p>
                            @endif
                            @if (!empty($application->linkedin))
                                <p class="text-sm"><i
                                        class="w-5 mr-2 text-center text-gray-400 fab fa-linkedin fa-fw"></i><a
                                        href="{{ $application->linkedin }}" target="_blank" rel="noopener noreferrer"
                                        class="text-indigo-600 hover:underline">{{ $application->linkedin }}</a></p>
                            @endif
                            @if (isset($application->highest_qualification) && $application->highest_qualification !== 'none')
                                <p class="text-sm"><i
                                        class="w-5 mr-2 text-center text-gray-400 fas fa-graduation-cap fa-fw"></i>{{ $application->highest_qualification }}
                                </p>
                            @endif
                        </div>
                    @else
                        @if (Route::has('instructor.apply'))
                            <a href="{{ route('instructor.apply') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 mt-2 text-sm text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                                <i class="fas fa-plus-circle"></i>Become an Instructor
                            </a>
                        @else
                            <p class="mt-2 text-sm italic text-gray-500">Instructor applications are currently closed.
                            </p>
                        @endif
                    @endif
                @endif
            @elseif ($user->role === 'instructor')
                <h3 class="mb-2 text-sm font-semibold text-gray-500 uppercase">Instructor Details</h3>
                <div class="mb-6">
                    <h4 class="mb-2 font-semibold text-gray-800"><i
                            class="w-5 mr-2 text-center text-green-500 fas fa-chalkboard fa-fw"></i>Created Courses</h4>

                    @isset($instructorCourses)
                        @if ($instructorCourses->count() > 0)
                            <ul class="space-y-1 text-gray-600 list-disc list-inside">
                                @foreach ($instructorCourses as $course)
                                    <li>{{ $course->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="italic text-gray-400">You haven’t created any courses yet.</p>
                        @endif
                    @else
                        <p class="italic text-gray-400">Instructor course data not available.</p>
                    @endisset
                </div>
            @elseif ($user->role === 'admin')
                <h3 class="mb-2 text-sm font-semibold text-gray-500 uppercase">Admin Details</h3>
                <p class="text-gray-600"><i class="w-5 mr-2 text-center text-red-500 fas fa-user-shield fa-fw"></i>This
                    user has administrative privileges.</p>
            @endif
        </div>


        @if (auth()->check() && auth()->id() === $user->id && !$user->is_pro)
            <div
                class="p-6 mt-6 text-center bg-gradient-to-r from-purple-600 to-indigo-700 rounded-xl shadow-lg border-t {{ $user->is_pro ? 'border-amber-200' : 'border-gray-200' }}">

                <i class="mb-3 text-4xl text-yellow-300 fa-solid fa-rocket"></i>
                <h3 class="mb-2 text-xl font-semibold text-white">Unlock Premium Features!</h3>
                <p class="mb-5 text-sm text-indigo-100">Upgrade to Pro today for exclusive benefits, advanced courses,
                    and priority support.</p>

                <a href="{{ url('/subscribe') }}"
                    class="inline-flex items-center px-6 py-2.5 font-bold text-indigo-700 transition duration-150 ease-in-out bg-white rounded-full shadow-md hover:bg-gray-100 hover:shadow-lg focus:bg-gray-100 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-200 active:shadow-lg">
                    Become Pro Now <i class="ml-2 fa-solid fa-arrow-right"></i>
                </a>
            </div>
        @endif

    </div>
</div>
