<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Course Management System' }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="antialiased bg-gray-100">

    <!-- Header Section -->
    <header class="bg-white shadow" x-data="{ open: false }">
        <div class="container flex items-center justify-between px-4 py-4 mx-auto">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('home') }}">Course Management</a>
            </h1>

            <!-- Mobile menu button -->
            <button @click="open = !open" class="text-gray-700 md:hidden focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Desktop Nav -->
            <nav class="hidden space-x-6 text-sm font-medium text-gray-700 md:flex">
                <a href="{{ route('courses.index') }}" class="hover:text-blue-500">Courses</a>

                {{-- @can('create')
                    <a href="{{ route('instructor.dashboard') }}" class="hover:text-blue-500">Instructors</a>
                    @endcan --}}

                @auth
                    @if (auth()->user()->role === 'student')
                        <a href="{{ route('user.dashboard') }}" class="hover:text-blue-500">Student Dashboard</a>
                    @elseif (auth()->user()->role === 'instructor')
                        <a href="{{ route('instructor.dashboard') }}" class="hover:text-blue-500">Instructor Dashboard</a>
                    @elseif (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-500">Admin Dashboard</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-red-500">Logout</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('register') }}" class="hover:text-blue-500">Register</a>
                    <a href="{{ route('login') }}" class="hover:text-blue-500">Login</a>
                @endguest
            </nav>
        </div>

        <!-- Mobile Nav -->
        <div class="md:hidden" x-show="open" x-transition x-cloak>
            <nav class="px-4 pt-2 pb-4 space-y-2 text-sm font-medium text-gray-700 bg-white border-t border-gray-200">
                <a href="{{ route('courses.index') }}" class="block hover:text-blue-500">Courses</a>

                @can('create')
                    <a href="{{ route('instructor.dashboard') }}" class="block hover:text-blue-500">Instructors</a>
                @endcan

                @auth
                    @if (auth()->user()->role === 'student')
                        <a href="{{ route('user.dashboard') }}" class="block hover:text-blue-500">Student Dashboard</a>
                    @elseif (auth()->user()->role === 'instructor')
                        <a href="{{ route('instructor.dashboard') }}" class="block hover:text-blue-500">Instructor
                            Dashboard</a>
                    @elseif (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block hover:text-blue-500">Admin Dashboard</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block hover:text-red-500">Logout</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('register') }}" class="block hover:text-blue-500">Register</a>
                    <a href="{{ route('login') }}" class="block hover:text-blue-500">Login</a>
                @endguest
            </nav>
        </div>
    </header>



    <!-- Main Content -->
    <main class="container px-4 py-8 mx-auto">
        {{ $slot }}
    </main>

    <!-- Footer Section -->
    <footer class="mt-12 text-white bg-gray-900">
        <div
            class="container flex flex-col items-center justify-between px-4 py-8 mx-auto space-y-4 text-center md:flex-row md:space-y-0 md:text-left">
            <p class="text-sm">
                &copy; {{ date('Y') }} Course Management System. All rights reserved.
            </p>

            {{-- <div class="flex space-x-4 text-sm">
                    <a href="#" class="transition hover:text-blue-400">Privacy Policy</a>
                    <a href="#" class="transition hover:text-blue-400">Terms</a>
                    <a href="#" class="transition hover:text-blue-400">Contact</a>
                </div> --}}
        </div>
    </footer>


    @livewireScripts
    @vite('resources/js/app.js')
</body>

</html>
