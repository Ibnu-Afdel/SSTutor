<div class="max-w-5xl px-4 py-8 mx-auto">

    <h1 class="mb-6 text-3xl font-bold text-gray-900 sm:text-4xl">{{ $course->name }}</h1>
    <h2 class="mb-6 font-bold text-gray-900 "><a href="{{ route('instructor.manage_content', $course->id) }}">Manage
            Content</a></h2>

    @if ($course->image)
        <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}"
            class="object-cover w-full h-64 mb-8 shadow rounded-xl">
    @else
        <div class="flex items-center justify-center w-full h-64 mb-8 text-gray-500 bg-gray-200 rounded-xl">
            📷 No Image Available
        </div>
    @endif

    <p class="mb-6 text-lg text-gray-700">{{ $course->description }}</p>

    <div class="grid gap-6 p-6 mb-10 bg-white shadow sm:grid-cols-2 rounded-xl">
        <div class="space-y-3">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <span class="text-lg text-yellow-500">⭐</span>
                @if ($course->rating)
                    {{ number_format($course->rating, 1) }}/5
                @else
                    No ratings yet
                @endif
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600">
                💰 <span class="font-medium">Price:</span> ${{ number_format($course->price, 2) }}
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600">
                ⏱️ <span class="font-medium">Duration:</span> {{ $course->duration }} hours
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600">
                📅 <span class="font-medium">Start:</span>
                {{ \Carbon\Carbon::parse($course->start_date)->format('F j, Y') }}
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600">
                📅 <span class="font-medium">End:</span>
                {{ \Carbon\Carbon::parse($course->end_date)->format('F j, Y') }}
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                🎓 <span class="font-medium">Level:</span> {{ ucfirst($course->level) }}
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600">
                👥 <span class="font-medium">Enrollment Limit:</span> {{ $course->enrollment_limit }}
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600">
                🧑‍🏫 <span class="font-medium">Instructor:</span> {{ $course->instructor->name }}
            </div>
        </div>
    </div>

    <div class="p-6 mb-10 space-y-6 shadow bg-gray-50 rounded-xl">
        <div>
            <h2 class="mb-2 text-xl font-semibold text-gray-800">📌 Requirements</h2>
            <p class="text-sm text-gray-600 whitespace-pre-line">{{ $course->requirements }}</p>
        </div>
        <div>
            <h2 class="mb-2 text-xl font-semibold text-gray-800">📖 Syllabus</h2>
            <p class="text-sm text-gray-600 whitespace-pre-line">{{ $course->syllabus }}</p>
        </div>
    </div>

    <div class="mb-8">
        @livewire('course.course-enrollment', ['courseId' => $course->id])
    </div>


    @if ($enrollment_status)
        @auth
            <div class="mb-8">
                @livewire('course.chat', ['course' => $course])
            </div>

            <div class="mb-8">
                @livewire('course.review', ['courseId' => $course->id])
            </div>
        @endauth
    @endif

    @livewire('course.lesson', ['courseId' => $course->id])


    <div class="space-y-8">
        <h2 class="text-2xl font-bold text-gray-900">🎥 Lessons</h2>

        @forelse ($course->lessons as $lesson)
            <div class="p-6 bg-white shadow rounded-xl">
                <h3 class="mb-2 text-xl font-semibold text-gray-800">{{ $lesson->title }}</h3>

                @if ($lesson->video_url)
                    <div class="mb-4">
                        <video class="w-full rounded" controls>
                            <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No video available for this lesson.</p>
                @endif

                <p class="mt-2 text-sm text-gray-700 whitespace-pre-line">{{ $lesson->content }}</p>
            </div>
        @empty
            <p class="text-sm text-gray-500">No lessons available yet.</p>
        @endforelse
    </div>
</div>
