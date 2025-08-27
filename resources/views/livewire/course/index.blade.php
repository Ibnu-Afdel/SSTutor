
<div class="min-h-screen px-4 py-10 bg-gray-50 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        <h1 class="mb-10 text-2xl font-bold text-center text-gray-800 sm:text-3xl">
            <i class="mr-2 text-indigo-600 fas fa-graduation-cap"></i>
            Available Courses
        </h1>
        @if (!empty($courses) && $courses->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $course)
             

                    <x-course.card :course="$course" :is_pro="$is_pro" />
                        

                @endforeach
            </div>
            <div class="mt-10">
                {{ $courses->links() }}
            </div>
        @else
            <div class="p-10 mt-6 text-center bg-white border border-gray-200 shadow rounded-xl">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-indigo-100 rounded-full">
                    <i class="text-3xl text-indigo-500 fas fa-info-circle"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700">No Courses Available Yet</h3>
                <p class="mt-1 text-sm text-gray-500">We are working on adding new courses. Please check back soon!</p>
            </div>
        @endif
    </div>
</div>
