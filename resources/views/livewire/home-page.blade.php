<div class="relative bg-white">
    <!-- Hero Section -->
    <section class="relative px-4 py-20 overflow-hidden text-center">
        <!-- Online background image -->
        <img src="https://images.unsplash.com/photo-1601933471669-7a4b331c5a4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80"
            alt="Learning Background"
            class="absolute inset-0 object-cover w-full h-full pointer-events-none opacity-20" />

        <!-- Overlay -->
        <div class="absolute inset-0 bg-white bg-opacity-60 backdrop-blur-sm"></div>

        <!-- Text content -->
        <div class="relative z-10 max-w-2xl mx-auto space-y-6 animate-fade-in-up">
            <h1 class="text-4xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-5xl">
                Welcome to <span class="text-green-700">SSTUTOR</span>
            </h1>
            <p class="text-lg text-gray-700 sm:text-xl">
                Your gateway to quality education, anytime, anywhere.
            </p>
            <div class="flex flex-col items-center justify-center gap-4 pt-2 sm:flex-row">
                <a href="{{ route('courses.index') }}"
                    class="px-6 py-3 font-semibold text-white transition bg-green-700 rounded-lg hover:bg-green-600">
                    🎓 Browse Courses
                </a>
                @auth
                    <a href="{{ route('user.dashboard') }}"
                        class="px-6 py-3 font-semibold text-white transition rounded-lg bg-slate-800 hover:bg-slate-700">
                        📘 Go to Dashboard
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Info Cards -->
    <section class="px-4 py-16 bg-white">
        <div class="grid max-w-5xl grid-cols-1 gap-6 mx-auto text-center sm:grid-cols-2 lg:grid-cols-3">
            <div class="p-6 transition border shadow-sm rounded-xl hover:shadow-md">
                <div class="mb-3 text-4xl text-green-700">📚</div>
                <h3 class="mb-2 text-xl font-bold">Wide Course Selection</h3>
                <p class="text-sm text-gray-600">
                    Access courses in tech, business, design, and more with expert instructors.
                </p>
            </div>
            <div class="p-6 transition border shadow-sm rounded-xl hover:shadow-md">
                <div class="mb-3 text-4xl text-green-700">🧑‍🏫</div>
                <h3 class="mb-2 text-xl font-bold">Top Instructors</h3>
                <p class="text-sm text-gray-600">
                    Learn from experienced professionals who understand your learning needs.
                </p>
            </div>
            <div class="p-6 transition border shadow-sm rounded-xl hover:shadow-md">
                <div class="mb-3 text-4xl text-green-700">💡</div>
                <h3 class="mb-2 text-xl font-bold">Flexible Learning</h3>
                <p class="text-sm text-gray-600">
                    Study anytime, on any device, and track your progress seamlessly.
                </p>
            </div>

        </div>
    </section>
</div>
