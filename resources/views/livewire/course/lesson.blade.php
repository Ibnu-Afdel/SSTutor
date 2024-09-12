
<div>
    @if(Auth::check()) <!-- Check if user is authenticated -->
        @if(Auth::user()->role === 'instructor')
            <!-- Form for adding lessons -->
            <form wire:submit.prevent="addLesson">
                <div class="mb-4">
                    <label for="title" class="block font-medium text-gray-700">Title</label>
                    <input type="text" wire:model="title" id="title" class="block w-full mt-1">
                    @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="content" class="block font-medium text-gray-700">Content</label>
                    <textarea wire:model="content" id="content" class="block w-full mt-1"></textarea>
                    @error('content') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="video_url" class="block font-medium text-gray-700">Video URL</label>
                    <input type="url" wire:model="video_url" id="video_url" class="block w-full mt-1">
                    @error('video_url') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="duration" class="block font-medium text-gray-700">Duration (minutes)</label>
                    <input type="number" wire:model="duration" id="duration" class="block w-full mt-1">
                    @error('duration') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="order" class="block font-medium text-gray-700">Order</label>
                    <input type="number" wire:model="order" id="order" class="block w-full mt-1">
                    @error('order') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="px-6 py-3 text-white bg-blue-500 rounded-lg hover:bg-blue-600">Add Lesson</button>
            </form>

            <hr class="my-4">

            <!-- List of lessons -->
            <ul>
                @foreach($lessons as $lesson)
                    <li>
                        <h3>{{ $lesson->title }}</h3>
                        <p>{{ $lesson->content }}</p>
                        @if($lesson->video_url)
                            <a href="{{ $lesson->video_url }}" target="_blank">Watch Video</a>
                        @endif
                        <p>Duration: {{ $lesson->duration }} minutes</p>
                        <button wire:click="deleteLesson({{ $lesson->id }})" class="text-red-500">Delete</button>
                    </li>
                @endforeach
            </ul>
        @else
            <p>You do not have permission to manage lessons.</p>
        @endif
    @else
        <p>Please log in to manage lessons.</p>
    @endauth
</div>
