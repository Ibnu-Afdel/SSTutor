<div class="flex items-center justify-center min-h-screen px-4 py-12 bg-gray-100 sm:px-6 lg:px-8">
    <div class="w-full max-w-md p-8 space-y-8 bg-white shadow-lg rounded-2xl">
        <div>
            <h2 class="text-3xl font-bold text-center text-gray-900">Create your account</h2>
            <p class="mt-2 text-sm text-center text-gray-600">
                Already have an account?
                <a href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">Login</a>
            </p>
        </div>

        <form wire:submit.prevent="register" class="mt-8 space-y-6">
            <div class="-space-y-px rounded-md shadow-sm">
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('name')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="email" id="email"
                        class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('email')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" wire:model="password" id="password"
                        class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('password')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                        Password</label>
                    <input type="password" wire:model="password_confirmation" id="password_confirmation"
                        class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('password_confirmation')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit"
                    class="flex justify-center w-full px-4 py-2 text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>
