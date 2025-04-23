<div class="container px-4 py-8 mx-auto">

    <h1 class="mb-6 text-2xl font-semibold text-gray-800 md:text-3xl">
        Manage Content: <span class="font-light">{{ $course->name }}</span>
    </h1>

    <div class="mb-6 space-y-3">
        @if (session()->has('message'))
            <div class="relative px-4 py-3 text-sm text-green-800 bg-green-100 border border-green-300 rounded shadow-sm"
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="relative px-4 py-3 text-sm text-red-800 bg-red-100 border border-red-300 rounded shadow-sm"
                role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    {{-- Sections Area (Sortable Container) --}}
    <div class="space-y-6" x-data="{}" {{-- Add Alpine scope --}} x-sortable="'updateSectionOrder'"
        {{-- Use Alpine directive, pass method name as string --}} wire:loading.class="opacity-50" wire:target="updateSectionOrder" {{-- Keep for loading indicator --}}>
        @forelse ($sections as $section)
            {{-- Section Block --}}
            {{-- Keep wire:key for Livewire DOM diffing --}}
            {{-- Keep wire:sortable.item for SortableJS identification --}}
            <div wire:key="section-{{ $section->id }}" wire:sortable.item="{{ $section->id }}"
                class="overflow-hidden transition-shadow duration-200 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">

                {{-- Section Header / Edit Form --}}
                <div class="p-4 border-b border-gray-200">
                    @if ($editingSectionId === $section->id)
                        {{-- Section Edit Form --}}
                        <form wire:submit.prevent="updateSection"
                            class="space-y-3 sm:space-y-0 sm:flex sm:items-center sm:space-x-3">
                            <div class="flex-grow">
                                <input type="text" wire:model.defer="editingSectionTitle" placeholder="Section Title"
                                    class="block w-full px-3 py-2 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('editingSectionTitle')
                                    <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex items-center flex-shrink-0 pt-2 space-x-2 sm:pt-0">
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Save
                                </button>
                                <button type="button" wire:click="cancelEditingSection"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                            </div>
                            <div wire:loading wire:target="updateSection" class="text-xs text-gray-500">Saving...</div>
                        </form>
                    @else
                        {{-- Section Display Header --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <h2 class="flex items-center text-lg font-medium text-gray-900">
                                {{-- Keep wire:sortable.handle if you want specific drag handles --}}
                                <span wire:sortable.handle class="mr-3 text-gray-400 cursor-move hover:text-gray-600"
                                    title="Drag to reorder section">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                    </svg>
                                </span>
                                {{ $section->title }}
                            </h2>
                            <div class="flex items-center flex-shrink-0 mt-2 space-x-2 sm:mt-0 sm:ml-4">
                                <button wire:click="startEditingSection({{ $section->id }})" title="Edit Section"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Edit
                                </button>
                                <button wire:click="confirmDeleteSection({{ $section->id }})"
                                    {{-- wire:confirm="Are you sure you want to delete this section and ALL its lessons? This cannot be undone." --}}
                                    title="Delete Section"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Delete
                                </button>
                                <div wire:loading wire:target="startEditingSection, deleteSection" class="w-4 h-4">
                                    <svg class="w-4 h-4 text-gray-400 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if($confirmingDeleteSection)
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Delete "{{ $titleToDeleted }}"</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this course? This action cannot be undone.</p>
                            </div>
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button wire:click="deleteSection({{ $confirmingDeleteSection }})" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Delete
                                </button>
                                <button wire:click="$set('confirmingDeleteSection', false)" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

                {{-- Lessons Area (Sortable Group) --}}
                {{-- Use x-sortable, pass method name. Add group key attribute for JS --}}
                <div class="p-4 bg-gray-50" x-data="{}" {{-- Add Alpine scope --}}
                    x-sortable="'updateLessonOrder'" {{-- Use Alpine directive --}}
                    wire:sortable-group-key="{{ $section->id }}" {{-- Attribute for JS to get Section ID --}} {{-- Optional: wire:sortable-group-name="lessons-{{ $section->id }}" --}}>
                    {{-- Name if dragging between sections --}}

                    <div class="pl-4 space-y-3 border-l-2 border-gray-300">
                        @forelse ($section->lessons as $lesson)
                            {{-- Lesson Item --}}
                            {{-- Keep wire:key and wire:sortable.item --}}
                            <div wire:key="lesson-{{ $lesson->id }}" wire:sortable.item="{{ $lesson->id }}"
                                class="p-3 transition-shadow duration-200 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow">

                                @if ($editingLessonId === $lesson->id)
                                    {{-- Lesson Edit Form --}}
                                    <form wire:submit.prevent="updateLesson" class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Title</label>
                                            <input type="text" wire:model.defer="editingLessonTitle"
                                                class="block w-full px-3 py-1.5 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            @error('editingLessonTitle')
                                                <span class="text-xs text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Video URL
                                                (Optional)
                                            </label>
                                            <input type="url" wire:model.defer="editingLessonVideoUrl"
                                                placeholder="https://www.youtube-nocookie.com/embed/"
                                                class="block w-full px-3 py-1.5 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            @error('editingLessonVideoUrl')
                                                <span class="text-xs text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Content
                                                (Optional)
                                            </label>
                                            <textarea wire:model.defer="editingLessonContent" rows="3"
                                                class="block w-full px-3 py-1.5 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Save Lesson
                                            </button>
                                            <button type="button" wire:click="cancelEditingLesson"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Cancel
                                            </button>
                                            <div wire:loading wire:target="updateLesson" class="text-xs text-gray-500">
                                                Saving...</div>
                                        </div>
                                    </form>
                                @else
                                    {{-- Lesson Display --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <p class="flex items-center text-sm text-gray-800">
                                            {{-- Keep wire:sortable.handle for the drag handle --}}
                                            <span wire:sortable.handle
                                                class="mr-2 text-gray-400 cursor-move hover:text-gray-600"
                                                title="Drag to reorder lesson">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                                </svg>
                                            </span>
                                            {{ $lesson->title }}
                                            @if ($lesson->video_url)
                                                <span
                                                    class="ml-2 text-xs font-medium text-blue-600 bg-blue-100 px-1.5 py-0.5 rounded">[Video]</span>
                                            @endif
                                            @if ($lesson->content)
                                                <span
                                                    class="ml-2 text-xs font-medium text-green-600 bg-green-100 px-1.5 py-0.5 rounded">[Content]</span>
                                            @endif
                                        </p>
                                        <div class="flex items-center flex-shrink-0 mt-2 space-x-2 sm:mt-0 sm:ml-3">
                                            <button wire:click="startEditingLesson({{ $lesson->id }})"
                                                title="Edit Lesson"
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Edit
                                            </button>
                                            <button wire:click="confirmDeleteLesson({{ $lesson->id }})"
                                                {{-- wire:confirm="Are you sure you want to delete this lesson?" --}}
                                                title="Delete Lesson"
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Delete
                                            </button>
                                            <div wire:loading wire:target="startEditingLesson, deleteLesson"
                                                class="w-4 h-4">
                                                <svg class="w-4 h-4 text-gray-400 animate-spin"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs italic text-gray-500">No lessons in this section yet.</p>
                        @endforelse

                        @if($confirmingDeleteLesson)
                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Delete "{{ $titleToDeleted }}" </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Are you sure you want to delete this course? This action cannot be undone.</p>
                                    </div>
                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                        <button wire:click="deleteLesson({{ $confirmingDeleteLesson }})" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Delete
                                        </button>
                                        <button wire:click="$set('confirmingDeleteLesson', false)" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                        <div class="pt-3 mt-3 border-t border-gray-300">
                            @if ($addingLessonToSectionId === $section->id)
                                <form wire:submit.prevent="addLesson"
                                    class="p-3 -m-3 space-y-3 bg-gray-100 rounded-md">
                                    <h4 class="text-sm font-medium text-gray-800">Add New Lesson</h4>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Title <span
                                                class="text-red-600">*</span></label>
                                        <input type="text" wire:model.defer="newLessonTitle"
                                            placeholder="Lesson Title" required
                                            class="block w-full px-3 py-1.5 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @error('newLessonTitle')
                                            <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Video URL
                                            (Optional)</label>
                                        <input type="url" wire:model.defer="newLessonVideoUrl"
                                            placeholder="https://www.youtube-nocookie.com/embed/"
                                            class="block w-full px-3 py-1.5 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @error('newLessonVideoUrl')
                                            <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Content
                                            (Optional)</label>
                                        <textarea wire:model.defer="newLessonContent" rows="3" placeholder="Lesson details..."
                                            class="block w-full px-3 py-1.5 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Add Lesson
                                        </button>
                                        <button type="button" wire:click="cancelAddingLesson"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Cancel
                                        </button>
                                        <div wire:loading wire:target="addLesson" class="text-xs text-gray-500">
                                            Adding...</div>
                                    </div>
                                </form>
                            @else
                                <button wire:click="startAddingLesson({{ $section->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    + Add Lesson
                                </button>
                                <div wire:loading wire:target="startAddingLesson({{ $section->id }})"
                                    class="inline-block w-4 h-4 ml-2">
                                    <svg class="w-4 h-4 text-gray-400 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div> {{-- End Lessons Area --}}
            </div> {{-- End Section Block --}}
        @empty
            <div
                class="px-6 py-4 text-sm text-center text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm">
                No sections created yet. Add one below to get started.
            </div>
        @endforelse {{-- ($sections) --}}
    </div> {{-- End Sections Area --}}


    <div class="pt-8 mt-8 border-t border-gray-200">
        <h3 class="mb-3 text-lg font-medium text-gray-900">Add New Section</h3>
        <form wire:submit.prevent="addSection"
            class="p-4 space-y-3 bg-gray-100 rounded-lg sm:space-y-0 sm:flex sm:items-start sm:space-x-3">
            <div class="flex-grow">
                <input type="text" wire:model.defer="newSectionTitle" placeholder="New Section Title" required
                    class="block w-full px-3 py-2 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('newSectionTitle')
                    <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex items-center flex-shrink-0 pt-2 space-x-2 sm:pt-0">
                <button type="submit"
                    class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm sm:w-auto hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Section
                </button>
                <div wire:loading wire:target="addSection" class="text-xs text-gray-500">Adding...</div>
            </div>
        </form>
    </div>
</div>
