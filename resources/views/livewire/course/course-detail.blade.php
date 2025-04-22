<div class="px-4 py-8 mx-auto bg-gray-50 sm:px-6 lg:px-8 max-w-7xl">

    <div class="max-w-5xl mx-auto"> 

        {{-- Course Header --}}
        <h1 class="mb-2 text-3xl font-bold text-gray-900 md:text-4xl">{{ $course->name }}</h1>
        <a href="{{ route('courses.index') }}"
            class="inline-flex items-center px-4 py-2 font-medium text-gray-700 text-md ">
             <i class="mr-2 text-gray-500 fas fa-arrow-left"></i>
             Back
         </a>
         
        @if ($isInstructor)
            <a href="{{ route('instructor.manage_content', $course->id) }}"
               class="inline-flex items-center gap-1 mb-6 text-sm font-medium text-indigo-600 transition duration-150 ease-in-out hover:text-indigo-800">
                <i class="fas fa-edit fa-fw"></i>
                Manage Content
            </a>
        @endif

        <div class="mb-8 overflow-hidden rounded-lg shadow-lg">
            @if ($course->image)
                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}"
                     class="object-cover w-full h-64 md:h-80">
            @else
                <div class="flex flex-col items-center justify-center w-full h-64 text-gray-500 bg-gray-200 md:h-80 rounded-xl">
                    <i class="mb-2 text-gray-400 fas fa-image fa-3x"></i>
                    <span>No Image Available</span>
                </div>
            @endif
        </div>

        <p class="mb-8 text-lg leading-relaxed text-gray-700">{{ $course->description }}</p>

        <div class="p-6 mb-8 bg-white border border-gray-200 shadow-sm rounded-xl">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">Course Details</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div class="space-y-4">
                    <div class="flex items-center gap-3 text-sm">
                        <i class="w-5 text-center text-yellow-500 fas fa-star fa-fw"></i>
                        <span class="font-medium text-gray-600">Rating:</span>
                        <span class="text-gray-800">
                            @if ($course->rating)
                                {{ number_format($course->rating, 1) }}/5
                            @else
                                Not Rated Yet
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i class="w-5 text-center text-green-600 fas fa-dollar-sign fa-fw"></i>
                        <span class="font-medium text-gray-600">Price:</span>
                        <span class="text-gray-800">${{ number_format($course->price, 2) }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i class="w-5 text-center text-blue-600 far fa-clock fa-fw"></i>
                        <span class="font-medium text-gray-600">Duration:</span>
                        <span class="text-gray-800">{{ $course->duration }} hours</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i class="w-5 text-center text-purple-600 far fa-calendar-alt fa-fw"></i>
                        <span class="font-medium text-gray-600">Start Date:</span>
                        <span class="text-gray-800">{{ \Carbon\Carbon::parse($course->start_date)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i class="w-5 text-center text-purple-600 far fa-calendar-check fa-fw"></i> {{-- Different icon for End Date --}}
                        <span class="font-medium text-gray-600">End Date:</span>
                        <span class="text-gray-800">{{ \Carbon\Carbon::parse($course->end_date)->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-3 text-sm">
                        <i class="w-5 text-center text-teal-600 fas fa-graduation-cap fa-fw"></i>
                        <span class="font-medium text-gray-600">Level:</span>
                        <span class="text-gray-800">{{ ucfirst($course->level) }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i class="w-5 text-center text-orange-600 fas fa-users fa-fw"></i>
                        <span class="font-medium text-gray-600">Enrollment Limit:</span>
                        <span class="text-gray-800">{{ $course->enrollment_limit ?? 'N/A' }}</span>
                    </div>
                     <div class="flex items-center gap-3 text-sm">
                        <i class="w-5 text-center text-indigo-600 fas fa-chalkboard-teacher fa-fw"></i>
                        <span class="font-medium text-gray-600">Instructor:</span>
                        <span class="text-gray-800">{{ $course->instructor->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        @php
        $syllabusSections = $course
            ->sections()
            ->with(['lessons' => fn($q) => $q->orderBy('order')])
            ->orderBy('order')
            ->get();
        @endphp
        <div x-data="{ openSection: @json($syllabusSections->isNotEmpty() ? $syllabusSections->first()->id : null) }" {{-- Open first section by default --}}
             class="p-6 mb-8 space-y-6 bg-white border border-gray-200 shadow-sm rounded-xl">

            {{-- Requirements --}}
            @if($course->requirements)
            <div class="pb-6 border-b border-gray-200">
                <h2 class="flex items-center mb-3 text-xl font-semibold text-gray-800">
                    <i class="mr-2 text-blue-500 fas fa-clipboard-list fa-fw"></i>
                    Requirements
                </h2>
                {{-- Use prose for better formatting of potentially multi-line text --}}
                <div class="prose-sm prose text-gray-700 max-w-none">
                    {!! nl2br(e($course->requirements)) !!} {{-- Use nl2br and e() for safety and line breaks --}}
                </div>
            </div>
            @endif

            {{-- Syllabus --}}
            <div>
                <h2 class="flex items-center mb-4 text-xl font-semibold text-gray-800">
                   <i class="mr-2 text-green-500 fas fa-book-open fa-fw"></i>
                   Syllabus
                </h2>

                @if($syllabusSections->isNotEmpty())
                    <div class="space-y-2">
                        @foreach ($syllabusSections as $section)
                            <div class="overflow-hidden border border-gray-200 rounded-md" wire:key="syllabus-section-{{ $section->id }}">
                                {{-- Accordion Button --}}
                                <button
                                    @click="openSection === {{ $section->id }} ? openSection = null : openSection = {{ $section->id }}"
                                    :aria-expanded="openSection === {{ $section->id }}"
                                    class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-left text-gray-700 transition duration-150 ease-in-out bg-gray-50 hover:bg-gray-100 focus:outline-none">
                                    <span>{{ $section->title }}</span>
                                    <i class="text-gray-500 transition-transform duration-200 fas fa-chevron-down fa-fw"
                                       :class="{ 'rotate-180': openSection === {{ $section->id }} }"></i>
                                </button>

                                {{-- Accordion Content --}}
                                <div x-show="openSection === {{ $section->id }}" x-collapse x-cloak
                                     class="px-5 py-4 space-y-2 text-sm text-gray-700 bg-white border-t border-gray-200">
                                    @forelse ($section->lessons as $lesson)
                                        <div class="flex items-center gap-2 py-1" wire:key="syllabus-lesson-{{ $lesson->id }}">
                                            <i class="text-xs text-emerald-500 fas fa-play-circle fa-fw"></i>
                                            <span>{{ $lesson->title }}</span>
                                        </div>
                                    @empty
                                        <p class="italic text-gray-500">No lessons in this section.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                     <p class="text-sm italic text-gray-500">The syllabus will be available soon.</p>
                @endif
            </div>
        </div>

        <div class="p-6 mb-8 bg-white border border-gray-200 shadow-sm rounded-xl">
             @if (!$enrollment_status)

             <h3 class="mb-3 text-lg font-semibold text-gray-800">Ready to start?</h3>
                @livewire('course.course-enrollment', ['courseId' => $course->id])
            @else

            <div class="flex flex-col items-start gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="flex items-center gap-2 font-semibold text-green-700">
                           <i class="fas fa-check-circle"></i> You are enrolled in this course.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3 shrink-0">
                        @auth
                            @php

                                $user = Auth::user();
                                $completed = $user->lessons()
                                    ->whereHas('section', fn ($query) => $query->where('course_id', $course->id))
                                    ->pluck('lessons.id')->toArray();
                                $allLessons = $course->sections()
                                    ->with(['lessons' => fn($q) => $q->orderBy('order')])
                                    ->orderBy('order')->get()
                                    ->flatMap(fn($section) => $section->lessons);
                                $firstLesson = $allLessons->first();
                                $nextUncompletedLesson = $allLessons->first(fn($lesson) => !in_array($lesson->id, $completed));
                                $continueLesson = $nextUncompletedLesson ?? $firstLesson; // Fallback to first if all completed or no lessons
                                $isNew = empty($completed);
                            @endphp

                            @if($continueLesson) {{-- Only show button if a lesson exists --}}
                            <a href="{{ route('course-play', ['course' => $course->id, 'lesson' => $continueLesson->id]) }}"
                               class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                @if($isNew)
                                    <i class="mr-2 fas fa-play"></i> Start Learning
                                @else
                                    <i class="mr-2 fas fa-redo"></i> Continue Learning
                                @endif
                            </a>
                            @endif

                            <a href="{{ route('course-chat', ['course' => $course->id]) }}"
                               class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="mr-2 fas fa-comments"></i> Chat
                            </a>
                        @endauth
                    </div>
                 </div>
            @endif
        </div>

        @if($enrollment_status)
            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                 <h3 class="mb-4 text-xl font-semibold text-gray-800">Leave a Review</h3>
                 @livewire('course.review', ['courseId' => $course->id])
            </div>
        @endif

    </div>
</div>