{{-- Assuming $is_pro (boolean for current user's pro status) is passed to this view --}}
@php
    // Define $is_pro for testing if not passed (REMOVE THIS IN PRODUCTION)
    // $is_pro = auth()->check() && auth()->user()->is_pro; // Example: Get from authenticated user
    // If using Livewire, $is_pro should already be a public property or passed data.

    // Default $is_pro to false if not set, for safety in testing/dev environments
    if (!isset($is_pro)) {
        $is_pro = false;
    }
    // Note: $is_locked is defined inside the loop now as it depends on the specific $course
@endphp

<div class="min-h-screen px-4 py-10 bg-gray-50 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <h1 class="mb-10 text-2xl font-bold text-center text-gray-800 sm:text-3xl">
            <i class="mr-2 text-indigo-600 fas fa-graduation-cap"></i>
            Available Courses
        </h1>

        @if ($courses->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $course)
                    {{-- Determine if the button should be in a 'locked' state for *this specific course* --}}
                    @php $is_locked = $course->is_pro && !$is_pro; @endphp

                    {{-- Course Card - Apply conditional background classes --}}
                    <div @class([
                        'relative', // Base for positioning absolute badges
                        'flex flex-col overflow-hidden transition rounded-lg shadow-sm', // Base structure & transition
                        // --- Conditional Background/Border Styling ---
                        'bg-yellow-50 border border-yellow-200 hover:shadow-lg hover:border-yellow-300' =>
                            $course->is_pro && $is_pro,
                        'bg-gray-100 border border-gray-300 hover:shadow-lg hover:border-gray-400' => $is_locked,
                        'bg-white border border-gray-200 hover:shadow-md' => !$course->is_pro,
                    ])>

                        {{-- Discount Badge (Top Left) --}}
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

                        {{-- Premium Badge (Top Right) - Only if course is premium --}}
                        @if ($course->is_pro)
                            <span @class([
                                'absolute z-10 px-2 py-1 text-xs font-semibold rounded shadow top-3 right-3', // Position & Base Style
                                'bg-yellow-500 text-white' => $is_pro && $course->is_pro, // Added $course->is_pro for clarity
                                'bg-gray-500 text-white' => $is_locked,
                            ])>
                                <i class="fas fa-star fa-fw"></i> Premium
                            </span>
                        @endif

                        {{-- Course Image --}}
                        <div class="w-full aspect-[4/3] overflow-hidden bg-gray-200">
                            <a href="{{ route('course.detail', $course->id) }}"
                                @if ($is_locked) onclick="return false;" title="Upgrade to Pro to access" @endif
                                class="block w-full h-full">
                                @if ($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}"
                                        class="object-cover w-full h-full transition-transform duration-300 hover:scale-105 @if ($is_locked) opacity-75 @endif">
                                @else
                                    <div
                                        class="flex items-center justify-center w-full h-full text-gray-400 @if ($is_locked) opacity-75 @endif">
                                        <i class="text-4xl fas fa-image"></i>
                                    </div>
                                @endif
                            </a>
                        </div>

                        {{-- Course Content --}}
                        <div class="flex flex-col justify-between flex-grow p-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 truncate">
                                    <a href="{{ route('course.detail', $course->id) }}"
                                        @if ($is_locked) onclick="return false;" title="Upgrade to Pro to access" @endif
                                        class="hover:text-indigo-700 @if ($is_locked) cursor-default hover:text-gray-800 @endif">{{ $course->name }}</a>
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

                                {{-- Price Calculation --}}
                                @php
                                    // **FIXED COMMENT STYLE HERE**
                                    // Calculate original and final prices
                                    $originalPrice = $course->original_price ?? $course->price; // Fallback if original_price missing
                                    $finalPrice = $originalPrice;
                                    if ($course->discount && $course->discount_value > 0) {
                                        if ($course->discount_type === 'percent') {
                                            $finalPrice = $originalPrice * ((100 - $course->discount_value) / 100);
                                        } elseif ($course->discount_type === 'amount') {
                                            $finalPrice = max(0, $originalPrice - $course->discount_value); // Ensure price doesn't go below 0
                                        }
                                    }
                                @endphp

                                {{-- Price Display --}}
                                <div>
                                    @if ($course->discount && $course->discount_value > 0 && $finalPrice < $originalPrice)
                                        <div class="text-base font-bold text-green-600">
                                            {{ number_format($finalPrice, 2) }} ETB
                                        </div>
                                        <div class="text-xs text-gray-400 line-through">
                                            {{ number_format($originalPrice, 2) }} ETB
                                        </div>
                                    @else
                                        <div class="text-base font-bold text-gray-800">
                                            {{ number_format($originalPrice, 2) }} ETB
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- View Course Button --}}
                        <div class="px-4 pb-4 mt-auto">
                            <a href="{{ route('course.detail', $course->id) }}" @class([
                                'block w-full px-4 py-2 text-sm font-medium text-center text-white transition rounded-md',
                                'bg-gray-400 hover:bg-gray-500 cursor-not-allowed' => $is_locked,
                                'bg-indigo-600 hover:bg-indigo-700' => !$is_locked,
                            ])
                                @if ($is_locked) onclick="return false;"
                                title="Upgrade to Pro to access this course" @endif>
                                @if ($is_locked)
                                    <i class="mr-1 fas fa-lock fa-fw"></i>
                                @endif
                                View Course
                            </a>
                        </div>

                    </div> {{-- End Course Card --}}
                @endforeach
            </div>
            {{-- Pagination Links --}}
            {{-- <div class="mt-10">
                {{ $courses->links() }}
            </div> --}}
        @else
            {{-- No Courses Found State --}}
            <div class="p-10 mt-6 text-center bg-white border border-gray-200 shadow rounded-xl">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-indigo-100 rounded-full">
                    <i class="text-3xl text-indigo-500 fas fa-info-circle"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700">No Courses Available Yet</h3>
                <p class="mt-1 text-sm text-gray-500">We are working on adding new courses. Please check back soon!</p>
            </div>
        @endif
    </div>
</div>
