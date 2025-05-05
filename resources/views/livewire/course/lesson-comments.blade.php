<div class="mt-8">
    <h3 class="mb-4 text-xl font-semibold text-gray-800">Comments</h3>

    <form wire:submit.prevent="save" class="mb-6 space-y-3">
        <textarea wire:model.defer="body" rows="3"
            class="w-full p-3 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-indigo-300"
            placeholder="Leave a comment..."></textarea>
        @error('body')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
        <button type="submit" class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">
            Post Comment
        </button>
    </form>


    <div class="space-y-4">
        @forelse ($comments as $comment)
            <div class="p-4 bg-white border border-gray-200 rounded shadow-sm" wire:key="comment-{{ $comment->id }}">
                <p class="text-sm text-gray-800">{{ $comment->body }}</p>
                <div class="mt-2 text-xs text-gray-500">
                    <a href="{{ route('user.profile', ['username' => $comment->user->username]) }}">Posted by
                        {{ $comment->user->name }}</a> ({{ $comment->created_at->diffForHumans() }})
                </div>

                <button wire:click="replyTo({{ $comment->id }})"
                    class="mt-1 text-xs text-blue-600 hover:underline">Reply</button>

                @if ($parentId === $comment->id)
                    <form wire:submit.prevent="save" class="mt-3 space-y-2">
                        <textarea wire:model.defer="body" rows="2" class="w-full p-2 border border-gray-300 rounded shadow-sm"
                            placeholder="Write a reply..."></textarea>
                        @error('body')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                        <button type="submit"
                            class="px-3 py-1 text-sm text-white bg-indigo-600 rounded hover:bg-indigo-700">Post
                            Reply</button>
                    </form>
                @endif

                @if ($comment->replies->count())
                    <div class="pl-4 mt-4 ml-6 space-y-3 border-l-2 border-gray-200">
                        @foreach ($comment->replies as $reply)
                            <div class="p-3 rounded bg-gray-50" wire:key="reply-{{ $reply->id }}">
                                <p class="text-sm">{{ $reply->body }}</p>
                                <div class="mt-1 text-xs text-gray-500">
                                    <a
                                        href="{{ route('user.profile', ['username' => $reply->user->username]) }}">{{ $reply->user->name }}</a>
                                    ({{ $reply->created_at->diffForHumans() }})
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-500">No comments yet. Be the first to comment!</p>
        @endforelse
    </div>
</div>
