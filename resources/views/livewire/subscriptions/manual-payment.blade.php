<div class="max-w-xl p-4 mx-auto">
    @if (session()->has('success'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Choose Plan</label>
            <select wire:model.defer="plan" class="w-full border-gray-300 rounded">
                <option value="">Select a plan</option>
                @foreach ($plans as $key => $planOption)
                    <option value="{{ $key }}">{{ $planOption['label'] }}</option>
                @endforeach
            </select>
            @error('plan')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Upload Screenshot</label>
            <input type="file" wire:model="screenshot" class="w-full" />
            @error('screenshot')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Note (optional)</label>
            <textarea wire:model.defer="notes" class="w-full border-gray-300 rounded"></textarea>
        </div>

        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
            Submit Payment
        </button>
    </form>
</div>
