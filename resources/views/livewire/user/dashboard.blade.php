<div class="container px-4 py-8 mx-auto">
    <h1 class="mb-8 text-3xl font-bold text-center">User Dashboard</h1>

    <!-- In Progress Courses -->
    <div class="mb-8">
        <h2 class="mb-4 text-2xl font-semibold">In Progress Courses</h2>
        @if($inProgressCourses->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                @foreach($inProgressCourses as $course)
                    <div class="relative flex flex-col p-4 bg-white border rounded-lg shadow-lg">
                        <!-- Discount Badge -->
                        @if ($course->discount && $course->discount_value > 0)
                            <span class="absolute inline-block px-3 py-1 text-xs font-bold text-white bg-red-600 rounded-full top-2 right-2">
                                @if($course->discount_type === 'percent')
                                    {{ $course->discount_value }}% OFF
                                @elseif($course->discount_type === 'amount')
                                    ${{ number_format($course->discount_value, 2) }} OFF
                                @endif
                            </span>
                        @endif

                        <!-- Course Image (conditionally rendered) -->
                        @if($course->image)
                            <img src="{{ $course->image }}" alt="{{ $course->name }}" class="object-cover w-full h-40 mb-4 rounded-lg">
                        @else
                            <!-- Placeholder for courses without an image -->
                            <div class="flex items-center justify-center w-full h-40 mb-4 bg-gray-200 rounded-lg">
                                <span class="font-serif text-2xl text-gray-500">{{ $course->name }}</span>
                            </div>
                        @endif

                        <!-- Course Details -->
                        <div class="flex-1">
                            <!-- Course Name -->
                            <h2 class="text-xl font-semibold">{{ $course->name }}</h2>

                            <!-- Course Description -->
                            <p class="mt-2 text-gray-600">{{ Str::limit($course->description, 100) }}</p>

                            <!-- Course Rating -->
                            <p class="mt-2 text-sm font-semibold">
                                Rating:
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
                        </div>

                        <!-- Course Pricing (with Discount Handling) -->
                        <div class="mt-4">
                            @php
                            // Use original price for discount calculations
                            $originalPrice = $course->original_price;
                            $finalPrice = $originalPrice;

                            if ($course->discount && $course->discount_value > 0) {
                                if ($course->discount_type === 'percent') {
                                    // Calculate percentage-based discount
                                    $finalPrice = $originalPrice * ((100 - $course->discount_value) / 100);
                                } elseif ($course->discount_type === 'amount') {
                                    // Calculate fixed-amount discount
                                    $finalPrice = max(0, $originalPrice - $course->discount_value);
                                }
                            }
                            @endphp

                            @if($course->discount && $course->discount_value > 0)
                                <!-- Show the discounted price -->
                                <p class="text-lg font-bold text-green-600">
                                    {{ number_format($finalPrice, 2) }} USD
                                </p>
                                <!-- Show the original price with strikethrough -->
                                <p class="text-sm text-gray-500 line-through">
                                    {{ number_format($originalPrice, 2) }} USD
                                </p>
                            @else
                                <!-- No discount, show only the original price -->
                                <p class="text-lg font-bold">
                                    {{ number_format($originalPrice, 2) }} USD
                                </p>
                            @endif
                        </div>

                        <!-- View Course Link -->
                        <a href="{{ route('course.detail', $course->id) }}" class="mt-4 font-semibold text-blue-600 hover:underline">
                            View Course
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-600">No courses in progress.</p>
        @endif
    </div>

    <!-- Completed Courses -->
    <div class="mb-8">
        <h2 class="mb-4 text-2xl font-semibold">Completed Courses</h2>
        @if($completedCourses->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                @foreach($completedCourses as $course)
                    <div class="relative flex flex-col p-4 bg-white border rounded-lg shadow-lg">
                        <!-- Discount Badge -->
                        @if ($course->discount && $course->discount_value > 0)
                            <span class="absolute inline-block px-3 py-1 text-xs font-bold text-white bg-red-600 rounded-full top-2 right-2">
                                @if($course->discount_type === 'percent')
                                    {{ $course->discount_value }}% OFF
                                @elseif($course->discount_type === 'amount')
                                    ${{ number_format($course->discount_value, 2) }} OFF
                                @endif
                            </span>
                        @endif

                        <!-- Course Image (conditionally rendered) -->
                        @if($course->image)
                            <img src="{{ $course->image }}" alt="{{ $course->name }}" class="object-cover w-full h-40 mb-4 rounded-lg">
                        @else
                            <!-- Placeholder for courses without an image -->
                            <div class="flex items-center justify-center w-full h-40 mb-4 bg-gray-200 rounded-lg">
                                <span class="font-serif text-2xl text-gray-500">{{ $course->name }}</span>
                            </div>
                        @endif

                        <!-- Course Details -->
                        <div class="flex-1">
                            <!-- Course Name -->
                            <h2 class="text-xl font-semibold">{{ $course->name }}</h2>

                            <!-- Course Description -->
                            <p class="mt-2 text-gray-600">{{ Str::limit($course->description, 100) }}</p>

                            <!-- Course Rating -->
                            <p class="mt-2 text-sm font-semibold">
                                Rating:
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
                        </div>

                        <!-- Course Pricing (with Discount Handling) -->
                        <div class="mt-4">
                            @php
                            // Use original price for discount calculations
                            $originalPrice = $course->original_price;
                            $finalPrice = $originalPrice;

                            if ($course->discount && $course->discount_value > 0) {
                                if ($course->discount_type === 'percent') {
                                    // Calculate percentage-based discount
                                    $finalPrice = $originalPrice * ((100 - $course->discount_value) / 100);
                                } elseif ($course->discount_type === 'amount') {
                                    // Calculate fixed-amount discount
                                    $finalPrice = max(0, $originalPrice - $course->discount_value);
                                }
                            }
                            @endphp

                            @if($course->discount && $course->discount_value > 0)
                                <!-- Show the discounted price -->
                                <p class="text-lg font-bold text-green-600">
                                    {{ number_format($finalPrice, 2) }} USD
                                </p>
                                <!-- Show the original price with strikethrough -->
                                <p class="text-sm text-gray-500 line-through">
                                    {{ number_format($originalPrice, 2) }} USD
                                </p>
                            @else
                                <!-- No discount, show only the original price -->
                                <p class="text-lg font-bold">
                                    {{ number_format($originalPrice, 2) }} USD
                                </p>
                            @endif
                        </div>

                        <!-- View Course Link -->
                        <a href="{{ route('course.detail', $course->id) }}" class="mt-4 font-semibold text-blue-600 hover:underline">
                            View Course
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-600">No completed courses.</p>
        @endif
    </div>
</div>
