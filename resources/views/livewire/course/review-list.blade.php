<div>
    @foreach($reviews as $review)
        <div class="p-4 mb-4 bg-gray-100 rounded-lg shadow-md">
            <p class="font-semibold">{{ $review->user->name }} - {{ number_format($review->rating, 1) }}★</p>
            <p>{{ $review->review }}</p>
            <p class="text-sm text-gray-500">{{ $review->created_at->format('F j, Y') }}</p>
        </div>
    @endforeach
</div>
