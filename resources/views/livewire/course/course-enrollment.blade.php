<div>

    @if ($enrollment_status)
        @if ($successMessage)
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="p-4 mb-4 text-sm text-green-700 transition-opacity duration-500 bg-green-100 border border-green-300 rounded-lg">
                {{ $successMessage }}
            </div>
        @endif
    @else
        <button wire:click="enroll"
            class="px-6 py-3 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Enroll Now
        </button>
    @endif
</div>
