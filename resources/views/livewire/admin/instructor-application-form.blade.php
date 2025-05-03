<div class="max-w-2xl px-4 py-6 mx-auto bg-white border border-gray-200 shadow rounded-2xl">
    @if ($existingApplication)
        @if ($existingApplication->status === 'pending')
            <div class="p-4 text-yellow-700 bg-yellow-100 border border-yellow-300 rounded">
                <h2 class="text-lg font-semibold">Application Under Review</h2>
                <p>Your application is being reviewed. Please wait for approval from the admin.</p>
            </div>
        @elseif ($existingApplication->status === 'approved')
            <div class="p-4 text-green-700 bg-green-100 border border-green-300 rounded">
                <h2 class="text-lg font-semibold">You're Approved!</h2>
                <p>Congratulations! You're now an instructor. <a href="/" class="underline">Back to Home</a></p>
            </div>
        @elseif ($existingApplication->status === 'rejected')
            <div class="p-4 text-red-700 bg-red-100 border border-red-300 rounded">
                <h2 class="text-lg font-semibold">Application Rejected</h2>
                <p>Unfortunately, your application was rejected. If you believe this is a mistake, please <a
                        href="/contact" class="underline">contact the admins</a>.</p>
            </div>
        @endif
    @else
        <div class="max-w-2xl px-4 py-6 mx-auto bg-white border border-gray-200 shadow rounded-2xl">
            <h1 class="mb-6 text-2xl font-bold text-gray-800">Apply to Become an Instructor</h1>

            @if (session()->has('success'))
                <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-user"></i> Full Name
                    </label>
                    <input type="text" wire:model.defer="full_name" class="w-full input"
                        placeholder="Your full name" />
                    @error('full_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-envelope"></i> Email
                    </label>
                    <input type="email" wire:model.defer="email" class="w-full input" placeholder="you@example.com" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-phone"></i> Phone Number
                    </label>
                    <input type="text" wire:model.defer="phone_number" class="w-full input" placeholder="+251..." />
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-calendar-alt"></i> Date of Birth
                    </label>
                    <input type="date" wire:model.defer="date_of_birth" class="w-full input" />
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-map-marker-alt"></i> Address
                    </label>
                    <input type="text" wire:model.defer="adress" class="w-full input" placeholder="City, Country" />
                    @error('adress')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-globe"></i> Website
                    </label>
                    <input type="url" wire:model.defer="webiste" class="w-full input"
                        placeholder="https://yourwebsite.com" />
                    @error('webiste')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fab fa-linkedin"></i> LinkedIn
                    </label>
                    <input type="url" wire:model.defer="linkedin" class="w-full input"
                        placeholder="https://linkedin.com/in/yourprofile" />
                    @error('linkedin')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-file-alt"></i> Resume
                    </label>
                    <textarea wire:model.defer="resume" class="w-full input" rows="4" placeholder="Paste your resume here..."></textarea>
                    @error('resume')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-graduation-cap"></i> Highest Qualification
                    </label>
                    <input type="text" wire:model.defer="higest_qualification" class="w-full input"
                        placeholder="e.g. MSc Computer Science" />
                    @error('higest_qualification')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-briefcase"></i> Current Occupation
                    </label>
                    <input type="text" wire:model.defer="current_ocupation" class="w-full input"
                        placeholder="e.g. Software Engineer" />
                    @error('current_ocupation')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-indigo-600 fas fa-comment-dots"></i> Why do you want to be an instructor?
                    </label>
                    <textarea wire:model.defer="reason" class="w-full input" rows="4" placeholder="Tell us your motivation..."></textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full px-4 py-2 text-white transition bg-indigo-600 rounded hover:bg-indigo-700">
                    Submit Application
                </button>
            </form>

        </div>
    @endif
</div>
