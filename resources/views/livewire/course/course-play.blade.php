<div>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.css" />
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.min.js"></script>
        <script>
            Livewire.on('player-reload', () => {
                setTimeout(() => {
                    const player = new Plyr('#plyr-video');
                }, 300);
            });
        </script>
    @endpush

    <h1 class="px-4 py-2 mb-4 text-2xl font-bold bg-gray-100">{{ $course->name }}</h1>

    <div class="flex flex-col md:flex-row">
        {{-- Sidebar --}}
        <aside class="w-full p-4 bg-white border-r md:w-1/4 lg:w-1/5 md:min-h-screen">
            <h2 class="mb-3 text-lg font-semibold">Course Content</h2>
            @if ($sections->isEmpty())
                <p class="text-sm text-gray-500">No content available yet.</p>
            @else
                <div class="space-y-4">
                    @foreach ($sections as $section)
                        <div wire:key="section-{{ $section->id }}">
                            <h3 class="mb-1 font-semibold text-gray-800">{{ $section->title }}</h3>
                            <ul class="ml-2 space-y-1">
                                @forelse ($section->lessons as $lesson)
                                    <li wire:key="lesson-{{ $lesson->id }}"
                                        wire:click="selectLesson({{ $lesson->id }})"
                                        wire:loading.class="opacity-50 cursor-wait"
                                        wire:target="selectLesson({{ $lesson->id }})"
                                        class="cursor-pointer px-2 py-1 rounded hover:bg-gray-100
                                        {{ $currentLesson && $currentLesson->id === $lesson->id ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700' }}
                                        {{ in_array($lesson->id, $completedLessons) ? 'line-through text-green-600' : '' }}">
                                        {{ $lesson->title }}
                                        @if (in_array($lesson->id, $completedLessons))
                                            <span class="ml-1 text-green-500">✔</span>
                                        @endif
                                    </li>
                                @empty
                                    <li class="ml-2 text-sm italic text-gray-400">No lessons in this section.</li>
                                @endforelse
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </aside>

        {{-- Main Area --}}
        <main class="w-full p-6 md:w-3/4 lg:w-4/5">
            @if ($currentLesson)
                <h2 class="mb-4 text-2xl font-semibold">{{ $currentLesson->title }}</h2>

                @if (session()->has('error'))
                    <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Video Player --}}
                <div class="mb-6 bg-black rounded shadow">
                    @if ($currentLesson->video_url && filter_var($currentLesson->video_url, FILTER_VALIDATE_URL))
                        @php
                            $videoUrl = str_replace(
                                'youtube.com/embed',
                                'youtube-nocookie.com/embed',
                                $currentLesson->video_url,
                            );
                            $videoUrl .= (str_contains($videoUrl, '?') ? '&' : '?') . 'rel=0';
                            $isEmbed =
                                str_contains($videoUrl, 'youtube-nocookie.com/embed') ||
                                str_contains($videoUrl, 'player.vimeo.com/video');
                        @endphp

                        @if ($isEmbed)
                            <div class="relative aspect-[16/9] w-full overflow-hidden rounded">
                                {{-- Watermark --}}
                                <div
                                    class="absolute z-10 text-xs pointer-events-none select-none text-white/60 sm:text-sm bottom-2 right-4">
                                    {{ Auth::user()->email }}
                                </div>
                                <iframe src="{{ $videoUrl }}" class="w-full h-full" allowfullscreen
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    frameborder="0">
                                </iframe>
                            </div>
                        @else
                            <div class="flex items-center justify-center text-white bg-gray-800 aspect-[16/9] rounded">
                                <p>This video URL is not embeddable. Please use a YouTube embed link.</p>
                            </div>
                        @endif
                    @else
                        <div class="flex items-center justify-center text-gray-500 bg-gray-200 rounded aspect-[16/9]">
                            <span>Video not available for this lesson.</span>
                        </div>
                    @endif
                </div>

                {{-- Mark as Complete --}}
                @if (!in_array($currentLesson->id, $completedLessons))
                    <button wire:click="markAsComplete"
                        class="px-4 py-2 mb-4 text-white bg-green-600 rounded hover:bg-green-700">
                        ✅ Mark as Complete
                    </button>
                @endif

                {{-- Lesson Content --}}
                @if ($currentLesson->content)
                    <div class="mb-6 prose max-w-none">
                        {!! $currentLesson->content !!}
                    </div>
                @endif

                {{-- Navigation --}}
                <div class="flex items-center justify-between pt-4 border-t">
                    <button wire:click="goToPreviousLesson" wire:loading.attr="disabled"
                        wire:target="goToPreviousLesson, selectLesson" @disabled(!$previousLesson)
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading wire:target="goToPreviousLesson">...</span>
                        <span wire:loading.remove wire:target="goToPreviousLesson">&leftarrow; Previous</span>
                    </button>

                    <button wire:click="goToNextLesson" wire:loading.attr="disabled"
                        wire:target="goToNextLesson, selectLesson" @disabled(!$nextLesson)
                        class="px-4 py-2 rounded text-white {{ $nextLesson ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-400' }} disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading wire:target="goToNextLesson">...</span>
                        @if ($nextLesson)
                            <span wire:loading.remove wire:target="goToNextLesson">Next &rightarrow;</span>
                        @else
                            <span wire:loading.remove wire:target="goToNextLesson">End of Course</span>
                        @endif
                    </button>
                </div>
            @else
                <div class="py-10 text-center">
                    <p class="text-gray-600">
                        @if ($sections->isEmpty())
                            This course doesn't have any content yet.
                        @else
                            Please select a lesson from the sidebar to begin.
                        @endif
                    </p>
                </div>
            @endif
        </main>
    </div>
</div>
