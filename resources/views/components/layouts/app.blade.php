{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        @vite('resources/css/app.css')
    </head>
    <body class="flex flex-col min-h-screen">

        <!-- Header -->
        <header class="bg-gray-800 text-white p-4">
            <div class="container mx-auto">
                <h1 class="text-xl font-bold">My Livewire Project</h1>
            </div>
        </header>

        <!-- Navigation Bar -->
        <nav class="bg-gray-700 text-white p-2">
            <div class="container mx-auto">
                <ul class="flex space-x-4">
                    <li><a href="/" class="hover:text-gray-300">Home</a></li>
                    <li><a href="/about" class="hover:text-gray-300">About</a></li>
                    <li><a href="/contact" class="hover:text-gray-300">Contact</a></li>
                    <li><a href="/dashboard" class="hover:text-gray-300">Dashboard</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto p-4">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white p-4">
            <div class="container mx-auto text-center">
                <p>&copy; {{ date('Y') }} My Livewire Project. All rights reserved.</p>
            </div>
        </footer>

    </body>
</html> --}}


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Course Management System' }}</title>
        @vite('resources/css/app.css')
        @livewireStyles
    </head>
    <body class="antialiased bg-gray-100">

        <!-- Header Section -->
        <header class="bg-white shadow">
            <div class="container mx-auto px-4 py-6 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-700">
                    <a href="{{ route('home') }}">Course Management System</a>
                </h1>
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="{{ route('courses.index') }}" class="text-gray-700 hover:text-blue-500">Courses</a></li>
                        <li><a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-blue-500">Categories</a></li>
                        <li><a href="{{ route('instructors.index') }}" class="text-gray-700 hover:text-blue-500">Instructors</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-500">Dashboard</a></li>
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
        <main class="container mx-auto px-4 py-8">
            {{ $slot }}
        </main>

        <!-- Footer Section -->
        <footer class="bg-gray-800 text-white py-6 mt-8">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; {{ date('Y') }} Course Management System. All rights reserved.</p>
                <nav class="mt-4">
                    <ul class="flex justify-center space-x-4">
                        <li><a href="{{ route('about') }}" class="hover:underline">About Us</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:underline">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:underline">Terms & Conditions</a></li>
                    </ul>
                </nav>
            </div>
        </footer>

        @livewireScripts
        @vite('resources/js/app.js')
    </body>
</html>
