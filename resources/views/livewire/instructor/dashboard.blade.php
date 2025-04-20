<div class="px-4 py-10">
    <h1 class="mb-8 text-3xl font-bold text-center text-gray-800">🎓 Instructor Dashboard</h1>
    <div class="mb-8 font-bold text-center"> <a class="p-3 text-white bg-green-500 rounded-lg hover:shadow-sm" href="{{ route('instructor.course_management') }}">Course Managment</a> </div>
    

    <div class="grid max-w-6xl grid-cols-1 gap-6 mx-auto sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Courses -->
        <div class="p-6 text-center transition bg-white shadow-md rounded-xl hover:shadow-lg">
            <div class="mb-2 text-4xl text-blue-600">📚</div>
            <h2 class="text-xl font-semibold text-gray-800">Total Courses</h2>
            <p class="mt-1 text-2xl font-bold text-gray-700">{{ $totalCourses }}</p>
        </div>

        <!-- Courses In Progress -->
        <div class="p-6 text-center transition bg-white shadow-md rounded-xl hover:shadow-lg">
            <div class="mb-2 text-4xl text-yellow-500">⏳</div>
            <h2 class="text-xl font-semibold text-gray-800">In Progress</h2>
            <p class="mt-1 text-2xl font-bold text-gray-700">{{ $inProgressCourses }}</p>
        </div>

        <!-- Completed Courses -->
        <div class="p-6 text-center transition bg-white shadow-md rounded-xl hover:shadow-lg">
            <div class="mb-2 text-4xl text-green-600">✅</div>
            <h2 class="text-xl font-semibold text-gray-800">Completed</h2>
            <p class="mt-1 text-2xl font-bold text-gray-700">{{ $completedCourses }}</p>
        </div>

        <!-- Enrolled Students -->
        <div class="p-6 text-center transition bg-white shadow-md rounded-xl hover:shadow-lg">
            <div class="mb-2 text-4xl text-purple-600">👨‍🎓</div>
            <h2 class="text-xl font-semibold text-gray-800">Students</h2>
            <p class="mt-1 text-2xl font-bold text-gray-700">{{ $enrolledStudents }}</p>
        </div>
    </div>
</div>

