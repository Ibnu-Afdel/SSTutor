<div>
    @auth
        @if ($isEnrolled) 
            <form wire:submit.prevent="submitReview" class="mb-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Rating</label>
                    <div class="flex mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg wire:click="setRating({{ $i }})"
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 cursor-pointer {{ $rating >= $i ? 'text-yellow-500' : 'text-gray-300' }}"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                stroke="none">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.4 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                    </div>
                    @error('rating') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="reviewText" class="block text-sm font-medium text-gray-700">Review</label>
                    <textarea id="reviewText" wire:model="reviewText" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"></textarea>
                    @error('reviewText') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="px-6 py-3 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Submit Review
                </button>
            </form>
        @else
            <p class="text-red-500">You must be enrolled in the course to submit a review.</p>
        @endif
    @else
        <p class="text-red-500">You must be logged in to submit a review.</p>
    @endauth

    <!-- Display Reviews -->
    <div class="mt-6">
        @forelse($reviews as $review)
            <div class="p-4 mb-4 border border-gray-200 rounded-lg shadow-sm">
                <p class="font-semibold">{{ $review->user->name }}</p>
                <div class="flex mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 {{ $review->rating >= $i ? 'text-yellow-500' : 'text-gray-300' }}"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                            stroke="none">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.4 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    @endfor
                </div>
                <p class="mt-2">{{ $review->review }}</p>
            </div>
        @empty
            <p class="text-gray-500">No reviews yet.</p>
        @endforelse
    </div>
</div>
