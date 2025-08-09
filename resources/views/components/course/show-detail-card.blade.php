@if ($course->getFirstMediaUrl('image'))
<div class="mb-8 overflow-hidden rounded-lg shadow-lg">
    <img src="{{ $course->getFirstMediaUrl('image') }}" alt="{{ $course->name }}"
        class="object-cover w-full h-64 md:h-80">
</div>
@endif


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
          {{-- <div class="flex items-center gap-3 text-sm">
              <i class="w-5 text-center text-green-600 fas fa-dollar-sign fa-fw"></i>
              <span class="font-medium text-gray-600">Price:</span>

              <span class="text-gray-800">${{ number_format($course->price, 2) }}</span>
          </div> --}}
          <div class="flex items-center gap-3 text-sm">
              <i class="w-5 text-center text-blue-600 far fa-clock fa-fw"></i>
              <span class="font-medium text-gray-600">Duration:</span>
              <span class="text-gray-800">{{ $course->duration }} hours</span>
          </div>
          <div class="flex items-center gap-3 text-sm">
              <i class="w-5 text-center text-purple-600 far fa-calendar-alt fa-fw"></i>
              <span class="font-medium text-gray-600">Start Date:</span>
              <span
                  class="text-gray-800">{{ \Carbon\Carbon::parse($course->start_date)->format('M d, Y') }}</span>
          </div>
          <div class="flex items-center gap-3 text-sm">
              <i class="w-5 text-center text-purple-600 far fa-calendar-check fa-fw"></i>
              <span class="font-medium text-gray-600">End Date:</span>
              <span
                  class="text-gray-800">{{ \Carbon\Carbon::parse($course->end_date)->format('M d, Y') }}</span>
          </div>
      </div>

      <div class="space-y-4">
          <div class="flex items-center gap-3 text-sm">
              <i class="w-5 text-center text-teal-600 fas fa-graduation-cap fa-fw"></i>
              <span class="font-medium text-gray-600">Level:</span>
              <span class="text-gray-800">{{ ucfirst($course->level->value) }}</span>
          </div>
          <div class="flex items-center gap-3 text-sm">
              <i class="w-5 text-center text-orange-600 fas fa-users fa-fw"></i>
              <span class="font-medium text-gray-600">Enrollment Limit:</span>
              <span class="text-gray-800">{{ $course->enrollment_limit ?? 'N/A' }}</span>
          </div>
          <div class="flex items-center gap-3 text-sm">
              <i class="w-5 text-center text-indigo-600 fas fa-chalkboard-teacher fa-fw"></i>
              <span class="font-medium text-gray-600">Instructor:</span>
              <a href="{{ route('user.profile', ['username' => $course->instructor->username]) }}">
                  <span
                      class="p-2 font-bold text-blue-800 rounded-lg bg-blue-50 hover:bg-blue-100">{{ $course->instructor->name }}</span>
              </a>
          </div>
      </div>
  </div>
</div>