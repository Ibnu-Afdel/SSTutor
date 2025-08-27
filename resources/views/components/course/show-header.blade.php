<div class="flex flex-wrap items-center gap-3 mb-2">
  <h1 class="text-3xl font-bold text-gray-900 md:text-4xl">{{ $course->name }}</h1>

  @if ($course->is_pro)
      <span
          class="inline-flex items-center px-3 py-1 text-sm font-semibold text-yellow-800 bg-yellow-200 rounded-full">
          <i class="mr-1.5 fas fa-star fa-fw text-yellow-600"></i>
          Premium
      </span>
  @endif
</div>

<div class="flex flex-wrap items-center gap-4 mb-6">
  <a href="{{ route('courses.index') }}"
      class="inline-flex items-center px-4 py-2 font-medium text-gray-700 text-md ">
      <i class="mr-2 text-gray-500 fas fa-arrow-left"></i>
      Back
  </a>

  @if ($isInstructor)
      <a href="{{ route('instructor.manage_content', $course->id) }}"
          class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 transition duration-150 ease-in-out hover:text-indigo-800">
          <i class="fas fa-edit fa-fw"></i>
          Manage Content
      </a>
  @endif
</div>