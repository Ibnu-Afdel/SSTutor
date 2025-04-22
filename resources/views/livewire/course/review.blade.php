<div>
    @auth
        @if ($isEnrolled)

            <form wire:submit.prevent="submitReview" class="p-6 mb-8 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Leave Your Review</h3>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Your Rating</label>
                    <div class="flex gap-1 mb-1"> 
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="setRating({{ $i }})" aria-label="Rate {{ $i }} out of 5"
                                    class="p-0 text-xl transition-transform duration-100 ease-in-out transform border-none cursor-pointer focus:outline-none hover:scale-110 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300 hover:text-gray-400' }}">
                                <i class="fas fa-star"></i> 
                            </button>
                        @endfor
                    </div>
                    @error('rating') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="mb-5">
                    <label for="reviewText" class="block mb-1 text-sm font-medium text-gray-700">Your Review</label>
                    <textarea id="reviewText" wire:model.lazy="reviewText" rows="4" placeholder="Share your thoughts about the course..."
                              class="block w-full p-3 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"></textarea>
                    @error('reviewText') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled" wire:target="submitReview"
                        class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <span wire:loading wire:target="submitReview" class="mr-2">
                         <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                    <span wire:loading.remove wire:target="submitReview">
                         <i class="mr-1 fas fa-paper-plane fa-fw"></i>
                    </span>
                    Submit Review
                </button>
            </form>
        @else

            <div class="flex items-center gap-2 p-4 mb-6 text-sm text-blue-800 bg-blue-100 border border-blue-300 rounded-md shadow-sm" role="alert">
                <i class="fas fa-info-circle fa-fw"></i>
                <span>You must be enrolled in the course to submit a review.</span>
            </div>
        @endif
    @else
    
        <div class="flex items-center gap-2 p-4 mb-6 text-sm text-yellow-800 bg-yellow-100 border border-yellow-300 rounded-md shadow-sm" role="alert">
            <i class="fas fa-sign-in-alt fa-fw"></i>
            <span>You must be logged in to submit a review.</span>
        </div>
    @endauth

    <div class="mt-8">
        <h3 class="pb-4 mb-4 text-xl font-semibold text-gray-800 border-b border-gray-200">
            Student Reviews ({{ $reviews->count() }})
        </h3>

        @forelse($reviews as $review)
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm" wire:key="review-{{ $review->id }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                         <i class="text-gray-400 far fa-user-circle fa-lg"></i> 
                        <span class="font-semibold text-gray-800">{{ $review->user->name }}</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span> 
                </div>

                <div class="flex mb-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star fa-fw text-sm {{ $review->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>

                <p class="mt-1 text-sm leading-relaxed text-gray-700">
                    {!! nl2br(e($review->review)) !!} {{-- Sanitize and format line breaks --}}
                </p>
            </div>
        @empty
            <div class="py-6 text-center text-gray-500 border border-gray-300 border-dashed rounded-lg bg-gray-50">
                <i class="mb-2 text-3xl text-gray-400 far fa-comment-dots"></i>
                <p class="text-sm">Be the first to review this course!</p>
            </div>
        @endforelse

        {{-- Optional: Add pagination links if needed --}}
        {{-- {{ $reviews->links() }} --}}
    </div>
</div>