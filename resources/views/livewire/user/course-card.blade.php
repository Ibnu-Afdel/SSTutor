<div class="relative flex flex-col p-4 bg-white border rounded-lg shadow-lg">
    @if ($course->discount && $course->discount_value > 0)
        <span class="absolute inline-block px-3 py-1 text-xs font-bold text-white bg-red-600 rounded-full top-2 right-2">
            @if($course->discount_type === 'percent')
                {{ $course->discount_value }}% OFF
            @elseif($course->discount_type === 'amount')
                ${{ number_format($course->discount_value, 2) }} OFF
            @endif
        </span>
    @endif

    @if($course->imageUrl)
        <img src="{{ $course->imageUrl }}" alt="{{ $course->name }}" class="object-cover w-full h-40 mb-4 rounded-lg">
    @else
        <div class="flex items-center justify-center w-full h-40 mb-4 bg-gray-200 rounded-lg">
            <span class="font-serif text-2xl text-gray-500">{{ $course->name }}</span>
        </div>
    @endif

    <div class="flex-1">
        <h2 class="text-xl font-semibold">{{ $course->name }}</h2>
        <p class="mt-2 text-gray-600">{{ Str::limit($course->description, 100) }}</p>

        <p class="mt-2 text-sm font-semibold">
            Rating:
            @if($course->rating)
                <span class="text-yellow-500">
                    @for($i = 0; $i < floor($course->rating); $i++) ★ @endfor
                    @for($i = 0; $i < (5 - floor($course->rating)); $i++) ☆ @endfor
                </span>
            @else
                <span class="text-gray-400">No ratings yet</span>
            @endif
        </p>
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

    <div class="mt-4">
        @if($course->discount && $course->discount_value > 0)
            <p class="text-lg font-bold text-green-600">{{ number_format($finalPrice, 2) }} USD</p>
            <p class="text-sm text-gray-500 line-through">{{ number_format($originalPrice, 2) }} USD</p>
        @else
            <p class="text-lg font-bold">{{ number_format($originalPrice, 2) }} USD</p>
        @endif
    </div>

    <a href="{{ route('course.detail', $course->id) }}" class="mt-4 font-semibold text-blue-600 hover:underline">
        View Course
    </a>
</div>
