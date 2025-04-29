<div
    class="flex items-center justify-center min-h-screen px-4 py-12 bg-gradient-to-br from-indigo-100 via-white to-purple-100 sm:px-6 lg:px-8">
    <div class="w-full max-w-md p-8 space-y-8 bg-white shadow-xl rounded-2xl">

        <div>
            <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-sm text-center text-gray-600">
                Or
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    create a new account
                </a>
            </p>
        </div>


        <div class="space-y-4">
            <p class="text-sm font-medium text-center text-gray-500">Sign in quickly with</p>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <a href="/auth/github"
                    class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-gray-800 border border-transparent rounded-lg shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12 0C5.373 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.91 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.565 21.799 24 17.3 24 12c0-6.627-5.373-12-12-12z"
                            clip-rule="evenodd" />
                    </svg>
                    GitHub
                </a>
                <a href="/auth/google"
                    class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2 -ml-1" viewBox="0 0 48 48" aria-hidden="true">
                        <path fill="#EA4335"
                            d="M24 9.5c3.48 0 6.48 1.21 8.83 3.49l6.46-6.46C35.01 2.83 29.88 0 24 0 14.88 0 7.04 5.58 2.79 13.5l7.85 6.09C12.09 13.19 17.53 9.5 24 9.5z">
                        </path>
                        <path fill="#4285F4"
                            d="M46.98 24.55c0-1.63-.15-3.2-.43-4.72H24v9.09h12.93c-.56 2.99-2.2 5.54-4.74 7.32l7.85 6.09C43.07 39.27 46.98 32.68 46.98 24.55z">
                        </path>
                        <path fill="#FBBC05"
                            d="M10.64 29.59c-.52-1.57-.82-3.24-.82-4.99s.3-3.42.82-4.99L2.79 13.5C1.03 16.94 0 20.38 0 24.6s1.03 7.66 2.79 11.1l7.85-6.11z">
                        </path>
                        <path fill="#34A853"
                            d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.85-6.09c-2.16 1.45-4.96 2.3-8.04 2.3-6.48 0-11.93-4.31-13.86-10.08l-7.85 6.09C7.04 42.42 14.88 48 24 48z">
                        </path>
                        <path fill="none" d="M0 0h48v48H0z"></path>
                    </svg>
                    Google
                </a>
                {{-- LinkedIn Button Commented Out, get from register when needed --}}
            </div>
        </div>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="px-2 text-sm text-gray-500 bg-white">
                    OR
                </span>
            </div>
        </div>

        @if (session('status') || session('error'))
            <div
                class="{{ session('status') ? 'bg-green-50 border-green-300 text-green-700' : 'bg-red-50 border-red-300 text-red-700' }} p-3 rounded-md border flex items-center space-x-2 text-sm">

                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">
                    @if (session('error'))
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" />
                    @else
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" />
                    @endif
                </svg>
                <span>{{ session('status') ?? session('error') }}</span>
            </div>
        @endif


        <form wire:submit.prevent="login" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <div class="mt-1">
                    <input type="email" wire:model.lazy="email" id="email" name="email" required
                        autocomplete="email"
                        class="block w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                @error('email')
                    <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1">
                    <input type="password" wire:model.lazy="password" id="password" name="password" required
                        autocomplete="current-password"
                        class="block w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                @error('password')
                    <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <button type="submit"
                    class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span wire:loading.remove wire:target="login">
                        Sign In
                    </span>
                    <span wire:loading wire:target="login">
                        <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Signing In...
                    </span>
                </button>
            </div>
        </form>

    </div>
</div>
