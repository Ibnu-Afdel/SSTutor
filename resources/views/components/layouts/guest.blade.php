<!-- resources/views/layouts/guest.blade.php -->
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

        <!-- Header Section for Guest Pages -->
        <header class="bg-white shadow">
            <div class="container px-4 py-6 mx-auto">
                <h1 class="text-xl font-bold text-gray-700">
                    <a href="{{ route('home') }}">Course Management System</a>
                </h1>
            </div>
        </header>

        <!-- Main Content for Guest Pages -->
        <main class="container px-4 py-8 mx-auto">
            {{ $slot }}
        </main>

        <!-- Footer Section for Guest Pages -->
        <footer class="py-6 mt-8 text-white bg-gray-800">
            <div class="container px-4 mx-auto text-center">
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
