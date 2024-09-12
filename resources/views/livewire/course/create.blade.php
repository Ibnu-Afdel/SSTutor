<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Create a New Course</h2>

    <form wire:submit.prevent="saveCourse">
        <!-- Course Name -->
        <div class="mb-4">
            <label for="name" class="block font-medium text-gray-700">Course Name</label>
            <input type="text" wire:model="name" id="name" class="mt-1 block w-full" required>
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="block font-medium text-gray-700">Description</label>
            <textarea wire:model="description" id="description" class="mt-1 block w-full" required></textarea>
            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Image Upload -->
        <div class="mb-4">
            <label for="image" class="block font-medium text-gray-700">Course Image</label>
            <input type="file" wire:model="image" id="image" class="mt-1 block w-full">
            @error('image') <span class="text-red-500">{{ $message }}</span> @enderror
            <div wire:loading wire:target="image">Uploading...</div>
        </div>

        <!-- Price -->
        <div class="mb-4">
            <label for="price" class="block font-medium text-gray-700">Course Price</label>
            <input type="number" wire:model="price" id="price" class="mt-1 block w-full" required>
            @error('price') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Discount Toggle -->
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="discount" class="form-checkbox">
                <span class="ml-2">Apply Discount</span>
            </label>
        </div>

        <!-- Discount Type and Value -->
        <div class="mb-4">
            <label for="discount_type" class="block font-medium text-gray-700">Discount Type</label>
            <select wire:model="discount_type" id="discount_type" class="mt-1 block w-full">
                <option value="">Select Type</option>
                <option value="percent">Percentage</option>
                <option value="amount">Fixed Amount</option>
            </select>
            @error('discount_type') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="discount_value" class="block font-medium text-gray-700">Discount Value</label>
            <input type="number" wire:model="discount_value" id="discount_value" class="mt-1 block w-full">
            @error('discount_value') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Level -->
        <div class="mb-4">
            <label for="level" class="block font-medium text-gray-700">Course Level</label>
            <select wire:model="level" id="level" class="mt-1 block w-full">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
            @error('level') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Start Date & End Date -->
        <div class="mb-4">
            <label for="start_date" class="block font-medium text-gray-700">Start Date</label>
            <input type="date" wire:model="start_date" id="start_date" class="mt-1 block w-full">
            @error('start_date') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="end_date" class="block font-medium text-gray-700">End Date</label>
            <input type="date" wire:model="end_date" id="end_date" class="mt-1 block w-full">
            @error('end_date') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Duration -->
        <div class="mb-4">
            <label for="duration" class="block font-medium text-gray-700">Course Duration (hours)</label>
            <input type="number" wire:model="duration" id="duration" class="mt-1 block w-full" required>
            @error('duration') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>


        <!-- Enrollment Limit -->
        <div class="mb-4">
            <label for="enrollment_limit" class="block font-medium text-gray-700">Enrollment Limit</label>
            <input type="number" wire:model="enrollment_limit" id="enrollment_limit" class="mt-1 block w-full">
            @error('enrollment_limit') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Requirements -->
        <div class="mb-4">
            <label for="requirements" class="block font-medium text-gray-700">Requirements</label>
            <textarea wire:model="requirements" id="requirements" class="mt-1 block w-full"></textarea>
            @error('requirements') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Syllabus -->
        <div class="mb-4">
            <label for="syllabus" class="block font-medium text-gray-700">Syllabus</label>
            <textarea wire:model="syllabus" id="syllabus" class="mt-1 block w-full"></textarea>
            @error('syllabus') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <div class="mb-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Course
            </button>
        </div>
    </form>
</div>
