<div class="max-w-2xl p-6 mx-auto bg-white border rounded-xl">
    <h1 class="mb-4 text-2xl font-bold">Apply to Become an Instructor</h1>

    @if (session()->has('success'))
        <div class="p-4 mb-4 text-green-600 bg-green-100 border rounded">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4">
        <input type="text" wire:model.defer="full_name" placeholder="Full Name" class="w-full p-2 border rounded" />
        <input type="email" wire:model.defer="email" placeholder="Email" class="w-full p-2 border rounded" />
        <input type="text" wire:model.defer="phone_number" placeholder="Phone Number"
            class="w-full p-2 border rounded" />
        <input type="date" wire:model.defer="date_of_birth" class="w-full p-2 border rounded" />
        <input type="text" wire:model.defer="adress" placeholder="Address" class="w-full p-2 border rounded" />
        <input type="text" wire:model.defer="webiste" placeholder="Website" class="w-full p-2 border rounded" />
        <input type="text" wire:model.defer="linkedin" placeholder="LinkedIn" class="w-full p-2 border rounded" />
        <textarea wire:model.defer="resume" placeholder="Paste your resume" class="w-full p-2 border rounded"></textarea>
        <input type="text" wire:model.defer="higest_qualification" placeholder="Highest Qualification"
            class="w-full p-2 border rounded" />
        <input type="text" wire:model.defer="current_ocupation" placeholder="Current Occupation"
            class="w-full p-2 border rounded" />
        <textarea wire:model.defer="reason" placeholder="Why do you want to be an instructor?"
            class="w-full p-2 border rounded"></textarea>

        <button type="submit" class="px-4 py-2 text-white bg-indigo-600 rounded">Submit Application</button>
    </form>
</div>
