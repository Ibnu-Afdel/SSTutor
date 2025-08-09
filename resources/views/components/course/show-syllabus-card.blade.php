<div x-data="{ openSection: @json($course->syllabus_sections->isNotEmpty() ? $course->syllabus_sections->first()->id : null) }" class="p-6 mb-8 space-y-6 bg-white border border-gray-200 shadow-sm rounded-xl">

  <div>
      <h2 class="flex items-center mb-4 text-xl font-semibold text-gray-800">
          <i class="mr-2 text-green-500 fas fa-book-open fa-fw"></i>
          Syllabus
      </h2>

      @if ($course->syllabus_sections->isNotEmpty())
          <div class="space-y-2">
              @foreach ($course->syllabus_sections as $section)
                  <div class="overflow-hidden border border-gray-200 rounded-md"
                      wire:key="syllabus-section-{{ $section->id }}">
                      <button
                          @click="openSection === {{ $section->id }} ? openSection = null : openSection = {{ $section->id }}"
                          :aria-expanded="openSection === {{ $section->id }}"
                          class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-left text-gray-700 transition duration-150 ease-in-out bg-gray-50 hover:bg-gray-100 focus:outline-none">
                          <span>{{ $section->title }}</span>
                          <i class="text-gray-500 transition-transform duration-200 fas fa-chevron-down fa-fw"
                              :class="{ 'rotate-180': openSection === {{ $section->id }} }"></i>
                      </button>

                      <div x-show="openSection === {{ $section->id }}" x-collapse x-cloak
                          class="px-5 py-4 space-y-2 text-sm text-gray-700 bg-white border-t border-gray-200">
                          @forelse ($section->lessons as $lesson)
                              <div class="flex items-center gap-2 py-1"
                                  wire:key="syllabus-lesson-{{ $lesson->id }}">
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