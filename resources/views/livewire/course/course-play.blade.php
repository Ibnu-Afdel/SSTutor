<div>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.css" />
        <style>
            :root {
                --plyr-color-main: #4f46e5;
            }

            .plyr--video .plyr__controls {
                background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.min.js"></script>
        <script>
            let playerInstance = null;

            function initializePlayer() {
                const videoElement = document.querySelector('#plyr-video');
                if (videoElement) {
                    if (playerInstance) {
                        playerInstance.destroy(); 
                    }
                    playerInstance = new Plyr(videoElement);
                  
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                initializePlayer();
            });

            Livewire.on('player-reload', () => {
                // Use setTimeout to ensure DOM updates are complete
                setTimeout(() => {
                    initializePlayer();
                }, 150); // Reduced delay slightly
            });
        </script>
    @endpush

    <div class="px-6 py-4 mb-6 bg-white border-b border-gray-200 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-800">{{ $course->name }}</h1>
    </div>
    <a href="{{ route('course.detail', ['courseId' => $course->id]) }}"
        class="inline-flex items-center px-4 pb-2 font-medium text-gray-700 transition text-md">
         <i class="mr-2 text-gray-500 fas fa-arrow-left"></i>
         Back
     </a>
     

    <div class="flex flex-col md:flex-row">

        <aside class="w-full p-6 bg-white border-r border-gray-200 md:w-1/4 lg:w-1/5 md:min-h-screen md:shadow-sm">
            <h2 class="flex items-center mb-5 text-sm font-semibold tracking-wider text-gray-500 uppercase">
                <i class="mr-2 text-gray-400 fas fa-list-ul"></i>
                Course Content
            </h2>
            @if ($sections->isEmpty())
                <p class="text-sm text-gray-500">No content available yet.</p>
            @else
                <div class="space-y-6">
                    @foreach ($sections as $section)
                        <div wire:key="section-{{ $section->id }}">
                            <h3 class="mb-2 font-semibold text-gray-800">{{ $section->title }}</h3>

                            <ul class="ml-1 space-y-1 border-l-2 border-gray-200">
                                @forelse ($section->lessons as $lesson)
                                    <li wire:key="lesson-{{ $lesson->id }}"
                                        wire:click="selectLesson({{ $lesson->id }})"
                                        wire:loading.class="opacity-50 cursor-wait"
                                        wire:target="selectLesson({{ $lesson->id }})"
                                        class="flex items-center justify-between px-3 py-2 text-sm rounded-r cursor-pointer group transition duration-150 ease-in-out
                                        {{ $currentLesson && $currentLesson->id === $lesson->id
                                            ? 'bg-indigo-100 text-indigo-800 font-semibold border-l-2 border-indigo-500 -ml-px' 
                                            : 'text-gray-700 hover:bg-gray-100'  }}
                                        {{ in_array($lesson->id, $completedLessons) && !($currentLesson && $currentLesson->id === $lesson->id)
                                            ? 'text-green-700 hover:bg-green-50' 
                                            : '' }}">

                                        <span class="flex items-center">

                                            @if ($currentLesson && $currentLesson->id === $lesson->id)
                                                <i class="mr-2 text-indigo-600 fas fa-play-circle fa-fw"></i>

                                                @elseif (in_array($lesson->id, $completedLessons))
                                                <i class="mr-2 text-green-500 fas fa-check-circle fa-fw"></i>

                                                @else
                                                <i
                                                    class="mr-2 text-gray-400 group-hover:text-gray-600 far fa-circle fa-fw"></i>

                                                @endif
                                            {{ $lesson->title }}
                                        </span>

                                        <div wire:loading wire:target="selectLesson({{ $lesson->id }})">
                                            <svg class="w-4 h-4 text-indigo-500 animate-spin"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                    </li>
                                @empty
                                    <li class="ml-3 text-xs italic text-gray-400">No lessons in this section.</li>
                                @endforelse
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </aside>

        <main class="w-full p-6 md:p-8 md:w-3/4 lg:w-4/5 bg-gray-50">
            @if ($currentLesson)

            <h2 class="mb-6 text-3xl font-bold text-gray-800">{{ $currentLesson->title }}</h2>

                @if (session()->has('error'))
                    <div class="relative px-4 py-3 mb-6 text-red-800 bg-red-100 border border-red-300 rounded shadow-sm"
                        role="alert">
                        <strong class="font-semibold"><i class="mr-2 fas fa-exclamation-triangle"></i>Error:</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="mb-8 overflow-hidden bg-black rounded-lg shadow-lg">
                    @if ($currentLesson->video_url && filter_var($currentLesson->video_url, FILTER_VALIDATE_URL))
                        @php

                            $videoUrl = str_replace(
                                'youtube.com/embed',
                                'youtube-nocookie.com/embed',
                                $currentLesson->video_url,
                            );
                            $videoUrl .=
                                (str_contains($videoUrl, '?') ? '&' : '?') . 'rel=0&modestbranding=1&showinfo=0'; // Add more params for cleaner look
                            $isEmbed =
                                str_contains($videoUrl, 'youtube-nocookie.com/embed') ||
                                str_contains($videoUrl, 'player.vimeo.com/video');
                        @endphp

                        @if ($isEmbed)
                            <div class="relative aspect-[16/9] w-full">
                                {{-- Watermark --}}
                                <div
                                    class="absolute z-10 text-xs pointer-events-none select-none text-white/50 sm:text-sm bottom-3 right-3">
                                    {{ Auth::user()->email }}
                                </div>
                                {{-- Updated Plyr Integration: Needs id="plyr-video" --}}
                                {{-- Important: The `key` forces DOM replacement, helping Plyr re-initialize --}}
                                <div wire:key="player-{{ $currentLesson->id }}" class="w-full h-full">
                                    <iframe id="plyr-video" src="{{ $videoUrl }}"
                                        class="absolute top-0 left-0 w-full h-full" allowfullscreen
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        frameborder="0">
                                    </iframe>
                                </div>
                            </div>
                        @else
                            {{-- Non-Embeddable Video Message --}}
                            <div
                                class="flex items-center justify-center text-center text-white bg-gray-800 aspect-[16/9]">
                                <div>
                                    <i class="mb-2 text-4xl text-red-400 fas fa-video-slash"></i>
                                    <p class="text-lg">Video cannot be embedded.</p>
                                    <p class="text-sm text-gray-300">Please use a valid YouTube or Vimeo embed URL.</p>
                                </div>
                            </div>
                        @endif
                    @else
                        {{-- No Video Message --}}
                        <div class="flex items-center justify-center text-gray-500 bg-gray-200 rounded aspect-[16/9]">
                            <div class="text-center">
                                <i class="mb-2 text-4xl text-gray-400 fas fa-photo-video"></i>
                                <p>Video not available for this lesson.</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if (!in_array($currentLesson->id, $completedLessons))
                    <div class="mb-8">
                        <button wire:click="markAsComplete" wire:loading.attr="disabled" wire:target="markAsComplete"
                            class="inline-flex items-center px-6 py-2 text-white transition duration-150 ease-in-out rounded shadow-sm bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-75 disabled:cursor-not-allowed">
                            <span wire:loading wire:target="markAsComplete" class="mr-2">
                                <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span wire:loading.remove wire:target="markAsComplete">
                                <i class="mr-2 fas fa-check"></i>
                            </span>
                            Mark as Complete
                        </button>
                    </div>
                @endif

                @if ($currentLesson->content)
                    <div
                        class="p-6 mt-8 mb-8 prose bg-white border border-gray-200 rounded-lg shadow-sm max-w-none prose-indigo">
                        {!! $currentLesson->content !!}
                    </div>
                @endif

                <div class="flex items-center justify-between pt-6 mt-8 border-t border-gray-200">
                    <button wire:click="goToPreviousLesson" wire:loading.attr="disabled"
                        wire:target="goToPreviousLesson, selectLesson" @disabled(!$previousLesson)
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <div wire:loading wire:target="goToPreviousLesson" class="mr-2">
                            <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <i class="mr-1 text-gray-500 fas fa-arrow-left fa-fw" wire:loading.remove
                            wire:target="goToPreviousLesson"></i>
                        Previous
                    </button>

                    <button wire:click="goToNextLesson" wire:loading.attr="disabled"
                        wire:target="goToNextLesson, selectLesson" @disabled(!$nextLesson)
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-indigo-400">
                        <div wire:loading wire:target="goToNextLesson" class="mr-2">
                            <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        @if ($nextLesson)
                            <span wire:loading.remove wire:target="goToNextLesson">Next</span>
                            <i class="ml-1 fas fa-arrow-right fa-fw" wire:loading.remove
                                wire:target="goToNextLesson"></i>
                        @else
                            <span wire:loading.remove wire:target="goToNextLesson">End of Course</span>
                            <i class="ml-1 fas fa-flag-checkered fa-fw" wire:loading.remove
                                wire:target="goToNextLesson"></i>
                        @endif
                    </button>
                </div>
            @else
                {{-- Placeholder when no lesson is selected --}}
                <div
                    class="flex flex-col items-center justify-center h-full min-h-[400px] py-10 text-center rounded-lg bg-gray-100 border border-dashed border-gray-300">
                    <i class="mb-4 text-5xl text-gray-400 fas fa-hand-pointer"></i>
                    <p class="text-xl font-medium text-gray-600">
                        @if ($sections->isEmpty())
                            This course doesn't have any content yet.
                        @else
                            Select a lesson from the sidebar to get started!
                        @endif
                    </p>
                    <p class="mt-1 text-sm text-gray-500">Choose any lesson to begin learning.</p>
                </div>
            @endif

            @if ($currentLesson)
    {{-- ...existing lesson content here... --}}

        @livewire('course.lesson-comments', ['lesson' => $currentLesson], key('comments-' . $currentLesson->id))
        @endif
        </main>
    </div>
</div>
