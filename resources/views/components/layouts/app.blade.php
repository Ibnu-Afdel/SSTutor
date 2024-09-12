
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

    </head>
    <body class="antialiased bg-gray-100">

        <!-- Header Section -->
        <header class="bg-white shadow">
            <div class="container flex items-center justify-between px-4 py-6 mx-auto">
                <h1 class="text-xl font-bold text-gray-700">
                    <a href="{{ route('home') }}">Course Management System</a>
                </h1>
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="{{ route('courses.index') }}" class="text-gray-700 hover:text-blue-500">Courses</a></li>
                        {{-- <li><a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-blue-500">Categories</a></li> --}}
                        
                        @can('create')
                        <li><a href="{{ route('instructor.dashboard') }}" class="text-gray-700 hover:text-blue-500">Instructors</a></li>
                        @endcan
                        
                        @auth
                           {{-- @if (auth()->user()->role === 'student')
                           <li><a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-blue-500">Dashboard</a></li>
                           @elseif (auth()->user()->role === 'instructor')
                           <li><a href="{{ route('instructor.dashboard') }}" class="text-gray-700 hover:text-blue-500">Dashboard</a></li>
                           @elseif (auth()->user()->role === 'admin')
                           <li><a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-500">Dashboard</a></li> --}}
                           {{-- @endif --}}
                           <li><a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-blue-500">Dashboard</a></li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-gray-700 hover:text-blue-500">Logout</button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container px-4 py-8 mx-auto">
            {{ $slot }}
        </main>

        <!-- Footer Section -->
        <footer class="py-6 mt-8 text-white bg-gray-800">
            <div class="container px-4 mx-auto text-center">
                <p>&copy; {{ date('Y') }} Course Management System. All rights reserved.</p>
                <nav class="mt-4">
                    <ul class="flex justify-center space-x-4">
                        {{-- <li><a href="{{ route('about') }}" class="hover:underline">About Us</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:underline">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:underline">Terms & Conditions</a></li> --}}
                        navs
                    </ul>
                </nav>
            </div>
        </footer>

        @livewireScripts
        @vite('resources/js/app.js')
    </body>
</html>
