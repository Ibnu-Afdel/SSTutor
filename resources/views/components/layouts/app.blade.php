<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Course Management System' }}</title>
    
    {{-- Using our custom component to load assets properly in all environments --}}
    <x-vite-assets :inputs="['resources/css/app.css', 'resources/js/app.js']" />
    
    @livewireStyles
    {{-- Consider moving Toastr CSS/JS to app.js/app.css via Vite for better bundling --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="antialiased bg-gray-100">

    <header class="bg-white shadow" x-data="{ open: false }">
        <div class="container flex items-center justify-between px-4 py-4 mx-auto">
            {{-- Brand Link - Usually doesn't have an icon, but you could add one like <i class="mr-2 fa-solid fa-graduation-cap"></i> if desired --}}
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('home') }}">Course Management</a>
            </h1>

            <button @click="open = !open" class="text-gray-700 md:hidden focus:outline-none">
                <i class="fa-solid fa-bars"></i> {{-- Changed to bars icon --}}
                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg> --}}
            </button>

            <nav class="items-center hidden space-x-6 text-sm font-medium text-gray-700 md:flex">
                <a href="{{ route('courses.index') }}" class="flex items-center hover:text-blue-500">
                    <i class="mr-1 fa-solid fa-book"></i> Courses
                </a>

                {{-- Optional Instructor link based on permission --}}
                {{-- @can('create')
                    <a href="{{ route('instructor.dashboard') }}" class="flex items-center hover:text-blue-500">
                        <i class="mr-1 fa-solid fa-chalkboard-user"></i> Instructors
                    </a>
                @endcan --}}

                @auth
                    {{-- Dashboard Link based on Role --}}
                    @if (auth()->user()->role === 'student')
                        <a href="{{ route('user.dashboard') }}" class="flex items-center hover:text-blue-500">
                            <i class="mr-1 fa-solid fa-user-graduate"></i> Dashboard
                        </a>
                    @elseif (auth()->user()->role === 'instructor')
                        <a href="{{ route('instructor.dashboard') }}" class="flex items-center hover:text-blue-500">
                            <i class="mr-1 fa-solid fa-chalkboard-user"></i> Dashboard
                        </a>
                    @elseif (auth()->user()->role === 'admin')
                        <a href="/admin" class="flex items-center hover:text-blue-500">
                            <i class="mr-1 fa-solid fa-user-shield"></i> Dashboard
                        </a>
                    @endif

                    {{-- Profile Link for Authenticated Users --}}
                    <a href="{{ route('user.profile', ['username' => auth()->user()->username]) }}"
                        class="flex items-center hover:text-blue-500">
                        <i class="mr-1 fa-solid fa-user"></i> Profile
                    </a>

                    {{-- Logout Form/Button --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center hover:text-red-500">
                            <i class="mr-1 fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('register') }}" class="flex items-center hover:text-blue-500">
                        <i class="mr-1 fa-solid fa-user-plus"></i> Register
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center hover:text-blue-500">
                        <i class="mr-1 fa-solid fa-right-to-bracket"></i> Login
                    </a>
                @endguest
            </nav>
        </div>

        <div class="md:hidden" x-show="open" x-transition x-cloak>
            <nav class="px-4 pt-2 pb-4 space-y-2 text-sm font-medium text-gray-700 bg-white border-t border-gray-200">
                <a href="{{ route('courses.index') }}" class="flex items-center block hover:text-blue-500">
                    <i class="mr-1 fa-solid fa-book"></i> Courses
                </a>

                {{-- Optional Instructor link based on permission --}}
                {{-- @can('create')
                    <a href="{{ route('instructor.dashboard') }}" class="flex items-center block hover:text-blue-500">
                        <i class="mr-1 fa-solid fa-chalkboard-user"></i> Instructors
                    </a>
                @endcan --}}

                @auth

                    @if (auth()->user()->role === 'student')
                        <a href="{{ route('user.dashboard') }}" class="flex items-center block hover:text-blue-500">
                            <i class="mr-1 fa-solid fa-user-graduate"></i> Dashboard
                        </a>
                    @elseif (auth()->user()->role === 'instructor')
                        <a href="{{ route('instructor.dashboard') }}" class="flex items-center block hover:text-blue-500">
                            <i class="mr-1 fa-solid fa-chalkboard-user"></i> Dashboard
                        </a>
                    @elseif (auth()->user()->role === 'admin')
                        <a href="/admin" class="flex items-center block hover:text-blue-500">
                            <i class="mr-1 fa-solid fa-user-shield"></i> Dashboard
                        </a>
                    @endif

                    {{-- Profile Link for Authenticated Users --}}
                    <a href="{{ route('user.profile', ['username' => auth()->user()->username]) }}"
                        class="flex items-center block hover:text-blue-500">
                        <i class="mr-1 fa-solid fa-user"></i> Profile
                    </a>

                    {{-- Logout Form/Button --}}
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="flex items-center w-full text-left hover:text-red-500">
                            <i class="mr-1 fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('register') }}" class="flex items-center block hover:text-blue-500">
                        <i class="mr-1 fa-solid fa-user-plus"></i> Register
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center block hover:text-blue-500">
                        <i class="mr-1 fa-solid fa-right-to-bracket"></i> Login
                    </a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="container px-4 py-8 mx-auto">
        {{ $slot }}
    </main>

    <footer class="mt-12 text-white bg-gray-900">
        <div
            class="container flex flex-col items-center justify-between px-4 py-8 mx-auto space-y-4 text-center md:flex-row md:space-y-0 md:text-left">
            <p class="text-sm">
                &copy; {{ date('Y') }} Course Management System. All rights reserved.
            </p>

            {{-- Footer links (if you add them later) could also have icons --}}
            {{-- <div class="flex space-x-4 text-sm">
                    <a href="#" class="transition hover:text-blue-400">Privacy Policy</a>
                    <a href="#" class="transition hover:text-blue-400">Terms</a>
                    <a href="#" class="transition hover:text-blue-400">Contact</a>
                </div> --}}
        </div>
    </footer>


    @livewireScripts
</body>

</html>
