<div class="min-h-screen px-4 py-10 bg-gray-50 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <h1 class="mb-10 text-2xl font-bold text-center text-gray-800 sm:text-3xl">
            <i class="mr-2 text-indigo-600 fas fa-graduation-cap"></i>
            Available Courses
        </h1>

        @if($courses->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($courses as $course)
                    <div class="relative flex flex-col overflow-hidden transition bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">

                        @if ($course->discount && $course->discount_value > 0)
                            <span class="absolute z-10 px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded shadow top-3 left-3">
                                @if($course->discount_type === 'percent')
                                    {{ $course->discount_value }}% OFF
                                @else
                                    {{ number_format($course->discount_value, 0) }} ETB OFF
                                @endif
                            </span>
                        @endif

                        <div class="w-full aspect-[4/3] overflow-hidden bg-gray-100">
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}"
                                     alt="{{ $course->name }}"
                                     class="object-cover w-full h-full transition-transform duration-300 hover:scale-105">
                            @else
                                <div class="flex items-center justify-center w-full h-full text-gray-400">
                                    <i class="text-4xl fas fa-image"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col justify-between flex-grow p-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 truncate">
                                    {{ $course->name }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                    {{ $course->description }}
                                </p>
                            </div>

                            <div class="mt-4 space-y-1">

                                <div class="flex items-center text-sm text-yellow-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($course->rating))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="text-gray-300 far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-xs text-gray-500">({{ number_format($course->rating, 1) }})</span>
                                </div>

                                @php
                                    $originalPrice = $course->original_price;
                                    $finalPrice = $originalPrice;
                                    if ($course->discount && $course->discount_value > 0) {
                                        if ($course->discount_type === 'percent') {
                                            $finalPrice = $originalPrice * ((100 - $course->discount_value) / 100);
                                        } elseif ($course->discount_type === 'amount') {
                                            $finalPrice = max(0, $originalPrice - $course->discount_value);
                                        }
                                    }
                                @endphp

                                <div>
                                    @if($course->discount && $course->discount_value > 0)
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

                        <div class="px-4 pb-4">
                            <a href="{{ route('course.detail', $course->id) }}"
                               class="block w-full px-4 py-2 text-sm text-center text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                                View Course
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
            {{-- Pagination Links (If you implement pagination) --}}
            {{-- <div class="mt-10">
                {{ $courses->links() }}
            </div> --}}
        @else
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


             

