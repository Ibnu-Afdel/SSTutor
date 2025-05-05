<div class="max-w-2xl px-6 py-10 mx-auto bg-white border border-gray-100 shadow-2xl rounded-2xl">

    <div class="mb-4">
        <a href="{{ route('user.profile', ['username' => auth()->user()->username]) }}"
            class="inline-flex items-center text-sm font-medium text-gray-500 transition-colors duration-150 hover:text-indigo-600">
            <i class="mr-1 text-gray-400 fas fa-arrow-left fa-fw"></i>
            Back to Profile
        </a>
    </div>

    @if ($existingApplication)

        @if ($existingApplication->status === 'pending')
            <div class="p-4 mb-4 text-yellow-800 bg-yellow-100 border border-yellow-300 rounded">
                <h2 class="text-base font-semibold">Application Under Review</h2>
                <p>Your application is being reviewed. Please wait for approval from the admin.</p>
            </div>
        @elseif ($existingApplication->status === 'approved')
            <div class="p-4 mb-4 text-green-800 bg-green-100 border border-green-300 rounded">
                <h2 class="text-base font-semibold">You're Approved!</h2>
                <p>Congratulations! You're now an instructor.
                    <a href="{{ route('user.profile', ['username' => auth()->user()->username]) }}"
                        class="underline">Back to Profile</a>
                </p>
            </div>
        @elseif ($existingApplication->status === 'rejected')
            <div class="p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded">
                <h2 class="text-base font-semibold">Application Rejected</h2>
                <p>Unfortunately, your application was rejected.
                    <a href="{{ route('user.profile', ['username' => auth()->user()->username]) }}"
                        class="underline">Contact the admins</a> if you believe this is a mistake.
                </p>
            </div>
        @endif
    @else
        <h2 class="mb-1 text-3xl font-bold text-gray-900">Become an Instructor</h2>
        <p class="mb-8 text-gray-500">Submit your application — it only takes a few minutes.</p>

        @if (session()->has('success'))
            <div class="p-4 mb-6 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50">
                <i class="mr-2 text-green-500 fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-6">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="relative">
                    <label class="block mb-1 text-xs text-gray-600">Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model.defer="full_name" placeholder="Your full name" required
                        class="w-full px-4 py-3 text-sm text-gray-700 bg-gray-100 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" />
                    @error('full_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label class="block mb-1 text-xs text-gray-600">Email <span class="text-red-500">*</span></label>
                    <input type="email" wire:model.defer="email" placeholder="you@example.com"
                        class="w-full px-4 py-3 text-sm text-gray-700 bg-gray-100 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block mb-1 text-xs text-gray-600">Phone Number <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model.defer="phone_number" placeholder="+251..."
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" />
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-xs text-gray-600">Date of Birth <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model.defer="date_of_birth"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" />
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block mb-1 text-xs text-gray-600">Address</label>
                    <input type="text" wire:model.defer="adress" placeholder="City, Country"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" />
                    @error('adress')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 text-xs text-gray-600">Website (optional)</label>
                    <input type="url" wire:model.defer="webiste" placeholder="https://yourwebsite.com"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" />
                    @error('webiste')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block mb-1 text-xs text-gray-600">LinkedIn Profile <span
                        class="text-red-500">*</span></label>
                <input type="url" wire:model.defer="linkedin" placeholder="https://linkedin.com/in/your-profile"
                    class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" />
                @error('linkedin')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block mb-1 text-xs text-gray-600">Highest Qualification <span
                            class="text-red-500">*</span></label>
                    <select wire:model.defer="higest_qualification"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select qualification</option>
                        <option>None</option>
                        <option>Diploma</option>
                        <option>Bachelor's</option>
                        <option>Master's</option>
                        <option>PhD</option>
                    </select>
                    @error('higest_qualification')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-xs text-gray-600">Current Occupation <span
                            class="text-red-500">*</span></label>
                    <select wire:model.defer="current_ocupation"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select occupation</option>
                        <option>Student</option>
                        <option>Freelancer</option>
                        <option>Full-time Job</option>
                        <option>Part-time Job</option>
                        <option>Unemployed</option>
                    </select>
                    @error('current_ocupation')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block mb-1 text-xs text-gray-600">Why do you want to become an instructor? </label>
                <textarea wire:model.defer="reason" rows="5"
                    class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Share your background, teaching experience, or motivation..."></textarea>
            </div>

            <div>
                <button type="submit"
                    class="w-full px-6 py-3 font-semibold text-white transition duration-200 shadow-md bg-slate-600 hover:bg-slate-700 rounded-xl">
                    <i class="mr-2 fas fa-paper-plane"></i>Submit Application
                </button>
            </div>

            <p class="mt-4 text-xs text-center text-gray-400">
                <i class="mr-1 fas fa-lock"></i> We respect your privacy and only use this data for review.
            </p>
        </form>
    @endif
</div>
