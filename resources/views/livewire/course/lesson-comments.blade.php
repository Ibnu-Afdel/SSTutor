<!-- resources/views/livewire/lesson-comments.blade.php -->
<div class="mt-8">
    <h3 class="mb-4 text-xl font-semibold text-gray-800">Comments</h3>

    <form wire:submit.prevent="save" class="mb-6 space-y-3">
        <textarea wire:model.defer="body" rows="4"
            class="w-full p-3 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-indigo-300"
            placeholder="Leave a comment..."></textarea>
        @error('body') <span class="text-sm text-red-600">{{ $message }}</span> @enderror

        <button type="submit"
            class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">Post Comment</button>
    </form>

    <div class="space-y-4">
        @forelse ($comments as $comment)
            <div class="p-4 bg-white border border-gray-200 rounded shadow-sm">
                <p class="text-sm text-gray-800">{{ $comment->body }}</p>
                <div class="mt-2 text-xs text-gray-500">
                    Posted by {{ $comment->user->name }} • {{ $comment->created_at->diffForHumans() }}
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">No comments yet. Be the first to comment!</p>
        @endforelse
    </div>
</div>
