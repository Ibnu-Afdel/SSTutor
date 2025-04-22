<div class="min-h-screen px-4 py-10 bg-gray-100 sm:px-6 lg:px-8">

    <h1 class="flex items-center justify-center mb-4 text-3xl font-bold text-center text-gray-800">
        <i class="mr-3 text-indigo-600 fas fa-tachometer-alt"></i> 
        Instructor Dashboard
    </h1>

    <div class="mb-10 text-center">
        <a href="{{ route('instructor.course_management') }}"
           class="inline-flex items-center px-5 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="mr-2 fas fa-list-alt fa-fw"></i> 
            Course Management
        </a>
    </div>

    <div class="grid max-w-6xl grid-cols-1 gap-6 mx-auto sm:grid-cols-2 lg:grid-cols-5">

        <div class="p-6 text-center transition duration-150 ease-in-out bg-white border border-transparent shadow-md rounded-xl hover:shadow-lg hover:border-blue-300">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-blue-100 rounded-full">
                <i class="text-xl text-blue-600 fas fa-book fa-fw"></i> 
            </div>
            <h2 class="text-base font-semibold text-gray-600">Total Courses</h2> 
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalCourses }}</p>
        </div>

        <div class="p-6 text-center transition duration-150 ease-in-out bg-white border border-transparent shadow-md rounded-xl hover:shadow-lg hover:border-yellow-300">
             <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-yellow-100 rounded-full">
                <i class="text-xl text-yellow-600 fas fa-tasks fa-fw"></i> 
            </div>
            <h2 class="text-base font-semibold text-gray-600">In Progress</h2>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $inProgressCourses }}</p>
        </div>

        <div class="p-6 text-center transition duration-150 ease-in-out bg-white border border-transparent shadow-md rounded-xl hover:shadow-lg hover:border-green-300">
             <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 rounded-full">
                <i class="text-xl text-green-600 fas fa-check-circle fa-fw"></i> 
            </div>
            <h2 class="text-base font-semibold text-gray-600">Published</h2>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $publishedCourses }}</p>
        </div>
        
        <div class="p-6 text-center transition duration-150 ease-in-out bg-white border border-transparent shadow-md rounded-xl hover:shadow-lg hover:border-green-600">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 rounded-full">
                <i class=" fas fa-list-alt fa-fw"></i> 

           </div>
           <h2 class="text-base font-semibold text-gray-600">Archived</h2>
           <p class="mt-1 text-3xl font-bold text-gray-900">{{ $archivedCourses }}</p>
       </div>

        <div class="p-6 text-center transition duration-150 ease-in-out bg-white border border-transparent shadow-md rounded-xl hover:shadow-lg hover:border-purple-300">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-purple-100 rounded-full">
                <i class="text-xl text-purple-600 fas fa-users fa-fw"></i> 
            </div>
            <h2 class="text-base font-semibold text-gray-600">Total Students</h2> 
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $enrolledStudents }}</p>
        </div>

    </div>
</div>