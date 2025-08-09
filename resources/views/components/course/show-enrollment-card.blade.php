{{-- @props(['course', 'enrollment_status' => false, 'continueLearningLesson']) --}}
@props(['course', 'enrollment_status', 'continueLearningLesson', 'isNewEnrollment'])
<div class="p-6 mb-8 bg-white border border-gray-200 shadow-sm rounded-xl">
  @if (!$enrollment_status)
      <h3 class="mb-3 text-lg font-semibold text-gray-800">Ready to start?</h3>

      <livewire:course.course-enrollment :course-id="$course->id" />

  @else

      <div class="flex flex-col items-start gap-4 md:flex-row md:items-center md:justify-between">
          <div>
              <p class="flex items-center gap-2 font-semibold text-green-700">
                  <i class="fas fa-check-circle"></i> You are enrolled in this course.
              </p>
          </div>
          <div class="flex flex-wrap gap-3 shrink-0">
              @auth
                  @if ($continueLearningLesson)
                      <a href="{{ route('course-play', ['course' => $course->id, 'lesson' => $continueLearningLesson->id]) }}"
                          class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                          @if ($isNewEnrollment)
                              <i class="mr-2 fas fa-play"></i> Start Learning
                          @else
                              <i class="mr-2 fas fa-redo"></i> Continue Learning
                          @endif
                      </a>
                  @endif
                  {{-- <a href="{{ route('course-chat', ['course' => $course->id]) }}"
                      class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                      <i class="mr-2 fas fa-comments"></i> Chat
                  </a> --}}
              @endauth
          </div>
      </div>
  @endif
</div>