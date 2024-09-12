<div>
    @if ($enrollment_status)
    <p>You are already enrolled in this course.</p>
@else
    <button wire:click="enroll" class="px-6 py-3 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
        Enroll Now
    </button>
@endif
</div>