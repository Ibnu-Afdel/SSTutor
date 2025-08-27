@if ($course->requirements)
    <div class="p-6 mb-8 space-y-6 bg-white border border-gray-200 shadow-sm rounded-xl">
        <div>
            <h2 class="flex items-center mb-3 text-xl font-semibold text-gray-800">
                <i class="mr-2 text-blue-500 fas fa-clipboard-list fa-fw"></i>
                Requirements
            </h2>
            <div class="prose-sm prose text-gray-700 max-w-none">
                {!! nl2br(e($course->requirements)) !!}
            </div>
        </div>
    </div>
@endif