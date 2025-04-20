
<div>
<div class="max-w-4xl p-4 mx-auto">
    <!-- Course Title -->
    <h1 class="mb-6 text-4xl font-bold">{{ $course->name }}</h1>
    
    <!-- Course Image -->
    @if($course->image)
        <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}" class="object-cover w-full mb-6 rounded-lg h-60">
    @else
        <!-- Placeholder for courses without an image -->
        <div class="flex items-center justify-center w-full mb-6 bg-gray-200 rounded-lg h-60">
            <span class="text-gray-500">No Image Available</span>
        </div>
    @endif

    <!-- Course Description -->
    <p class="mb-6 text-lg">{{ $course->description }}</p>

    <!-- Course Details -->
    <div class="p-4 mb-6 bg-gray-100 rounded-lg shadow-md">
        <p class="mb-2 font-semibold">Rating:
            @if($course->rating)
                <span class="text-yellow-500">
                    @for($i = 0; $i < floor($course->rating); $i++)
                        ★
                    @endfor
                    @for($i = 0; $i < (5 - floor($course->rating)); $i++)
                        ☆
                    @endfor
                </span>
            @else
                <span class="text-gray-400">No ratings yet</span>
            @endif
        </p>

        <p class="mb-2 font-semibold">Price: ${{ number_format($course->price, 2) }}</p>

        <p class="mb-2 text-sm">Duration: {{ $course->duration }} hours</p>

        <p class="mb-2 text-sm">Level: {{ ucfirst($course->level) }}</p>

        <p class="mb-2 text-sm">Starts: {{ \Carbon\Carbon::parse($course->start_date)->format('F j, Y') }}</p>
        <p class="mb-2 text-sm">Ends: {{ \Carbon\Carbon::parse($course->end_date)->format('F j, Y') }}</p>

        <p class="mb-2 text-sm">Enrollment Limit: {{ $course->enrollment_limit }}</p>

        <p class="mb-2 text-sm">Requirements: {{ $course->requirements }}</p>
        <p class="mb-4 text-sm">Syllabus: {{ $course->syllabus }}</p>

        <p class="mb-4 font-semibold">Instructor: {{ $course->instructor->name }}</p>
    </div>

    
        <!-- Add the Chat Component -->
        <!-- Course details content -->

    <!-- Chat sidebar (only displayed if the user is enrolled) -->
    @auth
    @livewire('course.chat', ['course' => $course])
    @endauth

<!-- Guests cannot access the chat, they will only see the course details -->
  

    @livewire('course.review', ['courseId' => $course->id])


    <!-- Enrollment Button -->
    @livewire('course.course-enrollment', ['courseId' => $course->id])

    <!-- Lessons Section -->
    @livewire('course.lesson', ['courseId' => $course->id])

    <!-- Lessons Video -->
    <div class="mt-8">
        @foreach($course->lessons as $lesson)
            <div class="mt-4">
                <h2 class="text-2xl font-semibold">{{ $lesson->title }}</h2>
                @if($lesson->video_url)
                    <div class="mt-2">
                        <video width="640" height="360" controls>
                            <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @else
                    <p class="mt-2 text-gray-500">No video available for this lesson.</p>
                @endif
                <p class="mt-2">{{ $lesson->content }}</p>
            </div>
        @endforeach
    </div>

</div>
</div>