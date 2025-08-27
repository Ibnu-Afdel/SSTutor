@props(['course', 'is_pro' => false])

<div @class([
  'relative', 
  'flex flex-col overflow-hidden transition rounded-lg shadow-sm', 
  'bg-yellow-50 border border-yellow-200 hover:shadow-lg hover:border-yellow-300' =>
      $course->is_pro && $is_pro ,
  'bg-gray-100 border border-gray-300 hover:shadow-lg hover:border-gray-400' => $course->is_locked,
  'bg-white border border-gray-200 hover:shadow-md' => !$course->is_pro,
])>

  @if ($course->discount && $course->discount_value > 0)
      <span
          class="absolute z-10 px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded shadow top-3 left-3">
          @if ($course->discount_type === 'percent')
              {{ $course->discount_value }}% OFF
          @else
              {{ number_format($course->discount_value, 0) }} ETB OFF
          @endif
      </span>
  @endif

  @if ($course->is_pro)
      <span @class([
          'absolute z-10 px-2 py-1 text-xs font-semibold rounded shadow top-3 right-3',
          'bg-yellow-500 text-white' => $course->is_pro && $is_pro,
          'bg-gray-500 text-white' => $course->is_locked,
      ])>
          <i class="fas fa-star fa-fw"></i> Premium
      </span>
  @endif

  @if ($course->getFirstMediaUrl('image'))
  <div class="w-full aspect-[4/3] overflow-hidden bg-gray-200">
      <a href="{{ route('course.detail', $course->id) }}"
          @if ($course->is_locked) onclick="return false;" title="Upgrade to Pro to access" @endif
          class="block w-full h-full">
          <img src="{{ $course->getFirstMediaUrl('image') }}" alt="{{ $course->name }}"
              class="object-cover w-full h-full transition-transform duration-300 hover:scale-105 @if ($course->is_locked) opacity-75 @endif">
      </a>
  </div>
  @endif

  <div class="flex flex-col justify-between flex-grow p-4">
      <div>
          <h2 class="text-lg font-semibold text-gray-800 truncate">
              <a href="{{ route('course.detail', $course->id) }}"
                  @if ($course->is_locked) onclick="return false;" title="Upgrade to Pro to access" @endif
                  class="hover:text-indigo-700 @if ($course->is_locked) cursor-default hover:text-gray-800 @endif">{{ $course->name }}</a>
          </h2>
          <p class="mt-1 text-sm text-gray-600 line-clamp-2">
              {{ $course->description }}
          </p>
      </div>

      <div class="mt-4 space-y-1">
          {{-- Rating --}}
          <div class="flex items-center text-sm text-yellow-500">
              @for ($i = 1; $i <= 5; $i++)
                  @if ($i <= round($course->rating ?? 0))
                      <i class="fas fa-star"></i>
                  @else
                      <i class="text-gray-300 far fa-star"></i>
                  @endif
              @endfor
              <span
                  class="ml-2 text-xs text-gray-500">({{ number_format($course->rating ?? 0, 1) }})</span>
          </div>

          <div>
            @if ($course->discount && $course->discount_value > 0 && $course->final_price < $course->original_price)
                <div class="text-base font-bold text-green-600">
                    {{ number_format($course->final_price, 2) }} ETB
                </div>
                <div class="text-xs text-gray-400 line-through">
                    {{ number_format($course->original_price, 2) }} ETB
                </div>
            @else
                <div class="text-base font-bold text-gray-800">
                    {{ number_format($course->original_price, 2) }} ETB
                </div>
            @endif
        </div>
        
      </div>
  </div>

  <div class="px-4 pb-4 mt-auto">
      <a href="{{ route('course.detail', $course) }}" @class([
          'block w-full px-4 py-2 text-sm font-medium text-center text-white transition rounded-md',
            'bg-gray-400 hover:bg-gray-500 cursor-not-allowed' => $course->is_locked,
          'bg-indigo-600 hover:bg-indigo-700' => !$course->is_locked,
      ])
          @if ($course->is_locked) onclick="return false;"
          title="Upgrade to Pro to access this course" @endif>
          @if ($course->is_locked)
              <i class="mr-1 fas fa-lock fa-fw"></i>
          @endif
          View Course
      </a>
  </div>
</div>