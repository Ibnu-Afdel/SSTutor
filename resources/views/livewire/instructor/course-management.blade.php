<div class="max-w-6xl px-4 py-8 mx-auto">
    {{-- Header --}}
    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">📚 Course Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your courses easily and elegantly</p>
        </div>
        @if (!$isCreatingOrEditing)
            <button wire:click="createCourse"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg shadow hover:bg-blue-700">
                ➕ New Course
            </button>
        @endif
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Course Form --}}
    @if ($isCreatingOrEditing)
        <div class="p-6 mb-8 bg-white shadow rounded-xl">
            <form wire:submit.prevent="saveCourse" class="space-y-4">
                @include('livewire.course.create', ['existingImageUrl' => $currentImageUrl])
            </form>
        </div>
    @endif

    {{-- Course Cards --}}
    @if (!$isCreatingOrEditing)
        @if ($courses->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $course)
                    <div class="p-5 transition bg-white shadow rounded-xl hover:shadow-lg">
                        <div class="flex flex-col justify-between h-full">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $course->name }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ Str::limit($course->description, 100) }}</p>
                                <div class="mt-3 space-y-1 text-sm text-gray-500">
                                    <p>💰 Price: ${{ number_format($course->price, 2) }}
                                        @if ($course->discount)
                                            <span class="text-xs text-red-500">
                                                (<s>${{ number_format($course->original_price, 2) }}</s>)
                                            </span>
                                        @endif
                                    </p>
                                    <p>🎓 Level: {{ ucfirst($course->level) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-2 mt-4">
                                <button wire:click="editCourse({{ $course->id }})"
                                    class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-white transition bg-indigo-500 rounded-lg hover:bg-indigo-600">
                                    ✏️ Edit
                                </button>
                                {{-- Optional Delete --}}
                                {{-- <button wire:click="deleteCourse({{ $course->id }})"
                                    class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-white transition bg-red-500 rounded-lg hover:bg-red-600">
                                    🗑️ Delete
                                </button> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500 bg-white shadow rounded-xl">
                <p class="text-lg">No courses yet.</p>
                <p class="mt-1 text-sm">Start by creating your first course! ✨</p>
            </div>
        @endif
    @endif
</div>
